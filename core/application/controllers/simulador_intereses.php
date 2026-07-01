<?php header('Access-Control-Allow-Origin: *'); ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Simulador_intereses extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('format');
		$this->load->database();
		$this->load->model('ctacte');
	}

	/**
	 * Busca los datos principales del cliente por RUT.
	 * Retorna: id, rut, nombres, id_ctacte (primera cuenta corriente activa)
	 */
	public function getClienteByRut(){
		$rut = $this->input->post('rut');
		$rut = trim($rut);
		$resp = array();

		$query = $this->db->query(
			"SELECT c.id, c.rut, c.nombres, c.cred_util, cc.id as id_ctacte
			 FROM clientes c
			 INNER JOIN cuenta_corriente cc ON cc.idcliente = c.id
			 WHERE c.rut = '" . $this->db->escape_str($rut) . "'
			 LIMIT 1"
		);

		if ($query->num_rows() > 0) {
			$row = $query->row();
			$resp['success'] = true;
			$resp['data']    = $row;
		} else {
			$resp['success'] = false;
			$resp['message'] = 'No se encontró cliente con el RUT indicado o no tiene cuenta corriente.';
			$resp['data']    = null;
		}

		echo json_encode($resp);
	}

	/**
	 * Retorna todos los documentos impagos del cliente con interés calculado.
	 * POST params:
	 *   rut             - RUT del cliente
	 *   fecha_simulacion - Fecha a usar como base para el cálculo (YYYY-MM-DD)
	 *   tasa_interes    - Tasa mensual (%) a aplicar
	 *   dias_cobro      - Días de gracia antes de empezar a cobrar interés (default 0)
	 */
	public function getDocumentosImpagos(){
		$rut             = $this->input->post('rut');
		$fecha_simulacion = $this->input->post('fecha_simulacion');
		$tasa_interes    = $this->input->post('tasa_interes');
		$dias_cobro      = (int)$this->input->post('dias_cobro');

		$rut             = trim($rut);
		$fecha_simulacion = substr(trim($fecha_simulacion), 0, 10);
		$tasa_interes    = (float)str_replace(',', '.', $tasa_interes);

		$resp = array();

		if (empty($rut) || empty($fecha_simulacion) || $tasa_interes === '') {
			$resp['success'] = false;
			$resp['message'] = 'Debe ingresar RUT, fecha de simulación y tasa de interés.';
			echo json_encode($resp);
			return;
		}

		$query = $this->db->query(
			"SELECT
				dc.id,
				t.id            AS tipodocumento,
				t.descripcion   AS tipo_desc,
				dc.numdocumento,
				CONCAT(t.descripcion, ' ', dc.numdocumento) AS nombre_documento,
				DATE_FORMAT(fc.fecha_factura, '%d/%m/%Y')   AS fecha_emision_fmt,
				DATE_FORMAT(fc.fecha_venc,    '%d/%m/%Y')   AS fecha_venc_fmt,
				fc.fecha_venc,
				dc.saldo
			 FROM detalle_cuenta_corriente dc
			 INNER JOIN tipo_documento     t  ON dc.tipodocumento = t.id
			 INNER JOIN cuenta_corriente   cc ON dc.idctacte = cc.id
			 INNER JOIN clientes           c  ON cc.idcliente = c.id
			 LEFT  JOIN factura_clientes   fc ON dc.numdocumento = fc.num_factura
			                                 AND dc.tipodocumento = fc.tipo_documento
			 WHERE dc.saldo > 0
			   AND c.rut = '" . $this->db->escape_str($rut) . "'"
		);

		$data = array();

		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {

				// Calcular días de mora respecto a la fecha de simulación
				$fecha_venc_val = !empty($row->fecha_venc) ? $row->fecha_venc : $fecha_simulacion;

				$date_venc = new DateTime($fecha_venc_val);
				$date_sim  = new DateTime($fecha_simulacion);

				if ($date_venc > $date_sim) {
					$dias_mora = 0;
				} else {
					$diff      = $date_venc->diff($date_sim);
					$dias_mora = max(0, $diff->days - $dias_cobro);
				}

				// Calcular interés (modelo ctacte)
				$interes_neto = $this->ctacte->calcula_interes_factura(
					$fecha_venc_val,
					$fecha_simulacion,
					$row->saldo,
					$tasa_interes,
					$dias_cobro
				);

				$interes_con_iva = round($interes_neto * FACTOR_SUMA_IVA, 0);
				$total_documento  = $row->saldo + $interes_con_iva;

				$row->dias_mora       = $dias_mora;
				$row->interes         = $interes_con_iva;
				$row->total_documento = $total_documento;

				$data[] = $row;
			}
		}

		$resp['success'] = true;
		$resp['total']   = count($data);
		$resp['data']    = $data;

		echo json_encode($resp);
	}

	/**
	 * Genera PDF de simulación con los documentos seleccionados.
	 * GET params:
	 *   rut              - RUT del cliente
	 *   fecha_simulacion - Fecha base (YYYY-MM-DD)
	 *   tasa_interes     - Tasa mensual (%)
	 *   dias_cobro       - Días de gracia
	 *   ids              - IDs de detalle_cuenta_corriente separados por coma
	 */
	public function exportarPDF(){

		$rut              = $this->input->get('rut');
		$fecha_simulacion = substr(trim($this->input->get('fecha_simulacion')), 0, 10);
		$tasa_interes     = (float)str_replace(',', '.', $this->input->get('tasa_interes'));
		$dias_cobro       = (int)$this->input->get('dias_cobro');
		$ids_raw          = $this->input->get('ids');

		// Validar y sanitizar IDs
		$ids_arr = array();
		foreach (explode(',', $ids_raw) as $id) {
			$id = (int)trim($id);
			if ($id > 0) { $ids_arr[] = $id; }
		}

		if (empty($ids_arr)) {
			echo 'No hay documentos seleccionados.'; return;
		}

		$ids_sql = implode(',', $ids_arr);

		// ── Datos del cliente ──────────────────────────────────────────────────
		$qCliente = $this->db->query(
			"SELECT c.rut, c.nombres, c.cred_util
			 FROM clientes c
			 WHERE c.rut = '" . $this->db->escape_str($rut) . "'
			 LIMIT 1"
		);
		$cliente = $qCliente->num_rows() > 0 ? $qCliente->row() : null;
		if (!$cliente) { echo 'Cliente no encontrado.'; return; }

		// ── Documentos seleccionados ───────────────────────────────────────────
		$qDocs = $this->db->query(
			"SELECT
				dc.id,
				t.descripcion   AS tipo_desc,
				dc.numdocumento,
				CONCAT(t.descripcion, ' ', dc.numdocumento) AS nombre_documento,
				DATE_FORMAT(fc.fecha_factura, '%d/%m/%Y')   AS fecha_emision_fmt,
				DATE_FORMAT(fc.fecha_venc,    '%d/%m/%Y')   AS fecha_venc_fmt,
				fc.fecha_venc,
				dc.saldo
			 FROM detalle_cuenta_corriente dc
			 INNER JOIN tipo_documento   t  ON dc.tipodocumento = t.id
			 INNER JOIN cuenta_corriente cc ON dc.idctacte = cc.id
			 LEFT  JOIN factura_clientes fc ON dc.numdocumento = fc.num_factura
			                              AND dc.tipodocumento = fc.tipo_documento
			 WHERE dc.id IN (" . $ids_sql . ")"
		);

		$total_saldo   = 0;
		$total_interes = 0;
		$filas_docs    = '';

		foreach ($qDocs->result() as $doc) {
			$fecha_venc_val = !empty($doc->fecha_venc) ? $doc->fecha_venc : $fecha_simulacion;

			$date_venc = new DateTime($fecha_venc_val);
			$date_sim  = new DateTime($fecha_simulacion);

			if ($date_venc > $date_sim) {
				$dias_mora = 0;
			} else {
				$diff      = $date_venc->diff($date_sim);
				$dias_mora = max(0, $diff->days - $dias_cobro);
			}

			$interes_neto    = $this->ctacte->calcula_interes_factura(
				$fecha_venc_val, $fecha_simulacion, $doc->saldo, $tasa_interes, $dias_cobro
			);
			$interes_con_iva = round($interes_neto * FACTOR_SUMA_IVA, 0);
			$total_doc       = $doc->saldo + $interes_con_iva;

			$total_saldo   += $doc->saldo;
			$total_interes += $interes_con_iva;

			$color_mora    = $dias_mora > 0 ? 'color:#c0392b;font-weight:bold;' : '';
			$color_interes = $interes_con_iva > 0 ? 'color:#c0392b;' : '';

			$filas_docs .= '
			<tr>
				<td style="padding:4px 6px;">'  . htmlspecialchars($doc->nombre_documento) . '</td>
				<td style="text-align:center;padding:4px 6px;">' . ($doc->fecha_emision_fmt ?: '-') . '</td>
				<td style="text-align:center;padding:4px 6px;">' . ($doc->fecha_venc_fmt    ?: '-') . '</td>
				<td style="text-align:right;padding:4px 6px;">$ '  . number_format($doc->saldo, 0, ',', '.') . '</td>
				<td style="text-align:center;padding:4px 6px;' . $color_mora    . '">' . $dias_mora . '</td>
				<td style="text-align:right;padding:4px 6px;'  . $color_interes . '">$ ' . number_format($interes_con_iva, 0, ',', '.') . '</td>
				<td style="text-align:right;padding:4px 6px;font-weight:bold;">$ ' . number_format($total_doc, 0, ',', '.') . '</td>
			</tr>';
		}

		$total_pagar = $total_saldo + $total_interes;

		// ── Datos empresa (logo + razón social) ───────────────────────────────
		$this->load->model('facturaelectronica');
		$empresa = $this->facturaelectronica->get_empresa();
		$logo    = PATH_FILES . 'facturacion_electronica/images/' . $empresa->logo;

		// ── HTML del PDF ───────────────────────────────────────────────────────
		$html = '
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<style>
  body        { font-family: Arial, sans-serif; font-size: 11px; color: #222; }
  h1          { font-size: 15px; margin: 0; }
  h2          { font-size: 13px; margin: 0; }
  table       { border-collapse: collapse; width: 100%; }
  .tbl-header th {
    background-color: #2c3e50;
    color: #fff;
    padding: 6px 8px;
    text-align: left;
    font-size: 10px;
  }
  .tbl-header th.r { text-align: right; }
  .tbl-header th.c { text-align: center; }
  .tbl-body tr:nth-child(even) td { background-color: #f2f2f2; }
  .box-cliente {
    border: 1px solid #2c3e50;
    padding: 8px 12px;
    margin-bottom: 10px;
    background: #eaf0fb;
  }
  .box-credito {
    border: 2px solid #e67e22;
    padding: 5px 10px;
    background: #fef9e7;
    display: inline-block;
    margin-top: 4px;
  }
  .box-totales {
    margin-top: 14px;
    border-top: 2px solid #2c3e50;
    padding-top: 8px;
  }
  .lbl        { color: #555; font-size: 10px; }
  .val        { font-weight: bold; font-size: 11px; }
  .box-interes {
    background: #c0392b;
    color: #fff;
    padding: 10px 16px;
    text-align: center;
    margin-top: 8px;
    font-size: 14px;
    font-weight: bold;
    border-radius: 3px;
  }
  .box-total-pagar {
    background: #1a5276;
    color: #fff;
    padding: 10px 16px;
    text-align: center;
    margin-top: 6px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 3px;
  }
  .sep { height: 10px; }
</style>
</head>
<body>

<!-- ===== ENCABEZADO EMPRESA ===== -->
<table style="margin-bottom:12px;">
  <tr>
    <td width="130"><img src="' . $logo . '" width="120"/></td>
    <td style="text-align:center;vertical-align:middle;">
      <h1>' . htmlspecialchars($empresa->razon_social) . '</h1>
      <p style="margin:2px 0;">RUT: ' . number_format($empresa->rut, 0, '.', '.') . '-' . $empresa->dv . '</p>
      <p style="margin:2px 0;">' . htmlspecialchars($empresa->dir_origen) . ' &mdash; ' . htmlspecialchars($empresa->comuna_origen) . '</p>
    </td>
    <td width="160" style="text-align:right;vertical-align:top;font-size:10px;">
      <b>Fecha emisión:</b> ' . date('d/m/Y') . '<br/>
      <b>Fecha simulación:</b> ' . date('d/m/Y', strtotime($fecha_simulacion)) . '<br/>
      <b>Tasa mensual:</b> ' . number_format($tasa_interes, 2, ',', '.') . ' %<br/>
      <b>Días de gracia:</b> ' . $dias_cobro . '
    </td>
  </tr>
</table>

<!-- ===== TÍTULO ===== -->
<table style="margin-bottom:10px;">
  <tr>
    <td style="border-top:2px solid #2c3e50;border-bottom:2px solid #2c3e50;
               text-align:center;padding:6px;">
      <h2>SIMULACIÓN DE INTERESES</h2>
    </td>
  </tr>
</table>

<!-- ===== DATOS CLIENTE ===== -->
<div class="box-cliente">
  <table>
    <tr>
      <td width="50%">
        <span class="lbl">RUT Cliente:</span><br/>
        <span class="val">' . htmlspecialchars($cliente->rut) . '</span>
      </td>
      <td width="50%">
        <span class="lbl">Nombre:</span><br/>
        <span class="val">' . htmlspecialchars($cliente->nombres) . '</span>
      </td>
    </tr>
    <tr><td colspan="2" style="padding-top:6px;">
      <div class="box-credito">
        <span class="lbl">Crédito Utilizado:</span>&nbsp;&nbsp;
        <span style="font-size:13px;font-weight:bold;color:#e67e22;">
          $ ' . number_format($cliente->cred_util, 0, ',', '.') . '
        </span>
      </div>
    </td></tr>
  </table>
</div>

<!-- ===== DETALLE DOCUMENTOS ===== -->
<table>
  <thead class="tbl-header">
    <tr>
      <th>Documento</th>
      <th class="c">F. Emisión</th>
      <th class="c">F. Vencimiento</th>
      <th class="r">Saldo</th>
      <th class="c">Días Mora</th>
      <th class="r">Interés c/IVA</th>
      <th class="r">Total Documento</th>
    </tr>
  </thead>
  <tbody class="tbl-body">
    ' . $filas_docs . '
  </tbody>
</table>

<!-- ===== TOTALES ===== -->
<div class="box-totales">
  <table>
    <tr>
      <td width="60%">&nbsp;</td>
      <td width="40%">

        <table style="width:100%;font-size:11px;">
          <tr>
            <td style="padding:3px 8px;"><b>Total deuda (saldo documentos):</b></td>
            <td style="text-align:right;padding:3px 8px;"><b>$ ' . number_format($total_saldo, 0, ',', '.') . '</b></td>
          </tr>
        </table>

        <div class="box-interes">
          INTERESES A PAGAR (c/IVA)&nbsp;&nbsp;&nbsp;
          $ ' . number_format($total_interes, 0, ',', '.') . '
        </div>

        <div class="box-total-pagar">
          TOTAL A PAGAR&nbsp;&nbsp;&nbsp;
          $ ' . number_format($total_pagar, 0, ',', '.') . '
        </div>

      </td>
    </tr>
  </table>
</div>

</body>
</html>';

		// ── Generar PDF ────────────────────────────────────────────────────────
		$this->load->library('mpdf');
		$this->mpdf->mPDF('', 'A4', 0, '', 12, 12, 16, 16, 9, 9, 'P');
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('SimuladorIntereses_' . date('Ymd_His') . '.pdf', 'I');
		exit;
	}
}
