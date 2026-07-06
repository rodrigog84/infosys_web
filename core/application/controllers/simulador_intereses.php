<?php header('Access-Control-Allow-Origin: *'); ?>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Simulador_intereses extends CI_Controller {

	/**
	 * Formatea un RUT sin guión (123456789) a 12.345.678-9
	 */
	private function format_rut($rut) {
		$rut = preg_replace('/[^0-9kK]/', '', $rut);
		if (strlen($rut) < 2) { return $rut; }
		$dv  = strtoupper(substr($rut, -1));
		$num = number_format((int)substr($rut, 0, -1), 0, ',', '.');
		return $num . '-' . $dv;
	}

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
				fc.id                                       AS id_factura,
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

			$row->dias_mora       = $dias_mora;
			$row->interes         = round($interes_neto, 0);
			$row->interes_con_iva = round($interes_neto * FACTOR_SUMA_IVA, 0);

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
				t.id            AS tipodocumento,
				t.descripcion   AS tipo_desc,
				dc.numdocumento,
				CONCAT(t.descripcion, ' ', dc.numdocumento) AS nombre_documento,
				DATE_FORMAT(fc.fecha_factura, '%d/%m/%Y')   AS fecha_emision_fmt,
				DATE_FORMAT(fc.fecha_venc,    '%d/%m/%Y')   AS fecha_venc_fmt,
				fc.fecha_venc,
				fc.id                                       AS id_factura,
				dc.saldo
			 FROM detalle_cuenta_corriente dc
			 INNER JOIN tipo_documento   t  ON dc.tipodocumento = t.id
			 INNER JOIN cuenta_corriente cc ON dc.idctacte = cc.id
			 LEFT  JOIN factura_clientes fc ON dc.numdocumento = fc.num_factura
			                              AND dc.tipodocumento = fc.tipo_documento
			 WHERE dc.id IN (" . $ids_sql . ")"
		);

		$total_saldo          = 0;
		$total_interes_neto   = 0;
		$total_interes_con_iva = 0;
		$filas_docs           = '';
		$fila_num             = 0;

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
			$interes_sin_iva = round($interes_neto, 0);
			$interes_con_iva = round($interes_neto * FACTOR_SUMA_IVA, 0);

			$total_saldo           += $doc->saldo;
			$total_interes_neto    += $interes_sin_iva;
			$total_interes_con_iva += $interes_con_iva;

			$color_mora    = $dias_mora > 0 ? 'color:#c0392b;font-weight:bold;' : '';
			$color_interes = $interes_sin_iva > 0 ? 'color:#c0392b;' : '';
			$fila_num++;
			$bg_fila = ($fila_num % 2 === 0) ? 'background-color:#f2f2f2;' : '';

			$filas_docs .= '
			<tr style="' . $bg_fila . '">
				<td style="padding:4px 6px;">'  . htmlspecialchars($doc->nombre_documento) . '</td>
				<td style="text-align:center;padding:4px 6px;">' . ($doc->fecha_emision_fmt ?: '-') . '</td>
				<td style="text-align:center;padding:4px 6px;">' . ($doc->fecha_venc_fmt    ?: '-') . '</td>
				<td style="text-align:right;padding:4px 6px;">$ '  . number_format($doc->saldo, 0, ',', '.') . '</td>
				<td style="text-align:center;padding:4px 6px;' . $color_mora    . '">' . $dias_mora . '</td>
				<td style="text-align:right;padding:4px 6px;'  . $color_interes . '">$ ' . number_format($interes_sin_iva, 0, ',', '.') . '</td>
			</tr>';
		}

		$total_pagar = $total_saldo + $total_interes_con_iva;

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
  .tbl-body td { border-bottom: 1px solid #e8e8e8; }
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
  .tbl-totales            { width: 100%; border-collapse: collapse; margin-top: 4px; }
  .tbl-totales td         { padding: 0; white-space: nowrap; }
  .tbl-totales .lbl-cell  { padding: 7px 12px; font-size: 11px; font-weight: bold; }
  .tbl-totales .mnt-cell  { padding: 7px 14px; font-size: 11px; font-weight: bold;
                             text-align: right; white-space: nowrap; width: 110px; }
  .row-saldo    td        { border-bottom: 1px solid #aaa; }
  .row-interes  td        { background: #c0392b; color: #fff; font-size: 13px; }
  .row-total    td        { background: #1a5276; color: #fff; font-size: 15px; }
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
      <b>Tasa mensual:</b> ' . number_format($tasa_interes, 2, ',', '.') . ' %
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
        <span class="val">' . $this->format_rut($cliente->rut) . '</span>
      </td>
      <td width="50%">
        <span class="lbl">Nombre:</span><br/>
        <span class="val">' . htmlspecialchars($cliente->nombres) . '</span>
      </td>
    </tr>
    <tr><td colspan="2" style="padding-top:6px;">
      <div class="box-credito">
        <span class="lbl">Línea de Crédito Utilizada:</span>&nbsp;&nbsp;
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
      <th class="r">Interés s/IVA</th>
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
      <td width="55%">&nbsp;</td>
      <td width="45%">

        <table class="tbl-totales">
          <tr class="row-saldo">
            <td class="lbl-cell">Total deuda (saldo documentos):</td>
            <td class="mnt-cell">$ ' . number_format($total_saldo, 0, ',', '.') . '</td>
          </tr>
          <tr class="row-saldo">
            <td class="lbl-cell">Total intereses s/IVA:</td>
            <td class="mnt-cell">$ ' . number_format($total_interes_neto, 0, ',', '.') . '</td>
          </tr>
          <tr class="row-interes">
            <td class="lbl-cell">INTERESES A PAGAR (c/IVA)</td>
            <td class="mnt-cell">$ ' . number_format($total_interes_con_iva, 0, ',', '.') . '</td>
          </tr>
          <tr style="height:4px;"><td colspan="2"></td></tr>
          <tr class="row-total">
            <td class="lbl-cell" style="font-size:15px;">TOTAL A PAGAR</td>
            <td class="mnt-cell" style="font-size:15px;">$ ' . number_format($total_pagar, 0, ',', '.') . '</td>
          </tr>
        </table>

      </td>
    </tr>
  </table>
</div>

</body>
</html>';

		// ── Generar PDF ────────────────────────────────────────────────────────
		// Limpiar cualquier output previo (notices, warnings) que corrompa el PDF
		if (ob_get_level()) { ob_end_clean(); }

		$this->load->library('mpdf');
		$this->mpdf->mPDF('', 'A4', 0, '', 12, 12, 16, 16, 9, 9, 'P');
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output('SimuladorIntereses_' . date('Ymd_His') . '.pdf', 'I');
		exit;
	}
}
