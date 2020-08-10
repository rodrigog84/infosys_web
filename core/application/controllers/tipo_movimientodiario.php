<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipo_movimientodiario extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->database();
	}

	public function saveconsumo(){
		$resp = array();
		$fecha = $this->input->post('fecha');
		$id_tipom = $this->input->post('id_tipom');
		$id_tipomd = 4;
		$detalle = $this->input->post('detalle');
		$items = json_decode($this->input->post('items'));
        $idbodega = 1;
        $numero = 15;
        $lote2 = 0;
        $id_correlativo = 15;
		$query = $this->db->query('SELECT * FROM correlativos WHERE id like "'.$numero.'"');
		if($query->num_rows()>0){
			$ord = $query->result();
		    $ord = $ord[0];
		    $numero = $ord->correlativo+1;
	    };
	    $ad = $id_correlativo;
		$data = array(
	        'correlativo' => $numero
	    );
		$this->db->where('id', $ad);		
		$this->db->update('correlativos', $data);
		$movimiento = array(
			    'numero' => $numero,
	            'fecha' => $fecha,
				'id_tipom' => $id_tipom,
				'id_tipomd' => $id_tipomd,
		        'id_bodegasal' => $idbodega,
		        'detalle' => $detalle,
		);
		$this->db->insert('movimientodiario', $movimiento); 
		$idmovimiento = $this->db->insert_id();
		foreach($items as $v){
		  if ($v->id_producto>0){
		  	$producto = $v->id_producto;
		    $puc = ($v->precio);
		    if(!$v->lote){
		    	$lote2 = 0;
		    }else{
		    	$lote2 = $v->lote;
		    }
		    $cero=0;
		    $fecha_vencimiento = date("Y-m-d", strtotime($v->fecha_vencimiento));
			$movimiento_diario = array(
		        'id_movimiento' => $idmovimiento,
		        'id_tipom' => $id_tipom,
		        'id_tipomd' => $id_tipomd,
		        'id_producto' => $v->id_producto,
		        'cantidad' => $v->cantidad,
		        'valor' => $v->precio,
		        'stock' => $v->cantidad,
		        'fecha' => $fecha,
		        'lote' => $lote2,
			);
			if ($v->id_producto>0){
				$this->db->insert('movimientodiario_detalle', $movimiento_diario);
			};			
			$producto = $v->id_producto;
			if ($id_tipom == 2){
			$existesi = array(
				'num_movimiento' => $numero,
			    'id_producto' => $v->id_producto,
			    'id_tipo_movimiento' => $id_correlativo,
			    'valor_producto' =>  $v->precio,
			    'cantidad_salida' => $v->cantidad,
			    'id_bodega' => $idbodega,
			    'fecha_movimiento' => $fecha,
			    'fecha_vencimiento' => $fecha_vencimiento,
			    'lote' => $lote2		    
			);
			};
			$this->db->insert('existencia_detalle', $existesi);			
	    };
		};		
        $resp['success'] = true;
        $this->Bitacora->logger("I", 'movimientodiario', $numero);
        echo json_encode($resp);
	}

	public function save(){

		$resp = array();

		$fecha = $this->input->post('fecha');
		$id_tipom = $this->input->post('id_tipom');
		$id_tipomd = $this->input->post('id_tipomd');
		$id_correlativo = $this->input->post('id_correlativo');
		$rut = $this->input->post('rut');
		$id_bodegaent = $this->input->post('id_bodegaent');
		$id_bodegasal = $this->input->post('id_bodegasal');
		$detalle = $this->input->post('detalle');
		$items = json_decode($this->input->post('items'));

		if(!$id_bodegaent){
			$id_bodegaent = 1;
		}

		if(!$id_bodegasal){
			$id_bodegasal = 0;
		}

		$numero = $id_correlativo;
		   		
		$query = $this->db->query('SELECT * FROM correlativos WHERE id like "'.$numero.'"');

		if($query->num_rows()>0){

			$ord = $query->result();
		    $ord = $ord[0];
		    $numero = $ord->correlativo+1;
	    }

	    $ad = $id_correlativo;
		$data = array(
	        'correlativo' => $numero
	    );

		$this->db->where('id', $ad);
		
		$this->db->update('correlativos', $data); 

		$movimiento = array(
			    'numero' => $numero,
	            'fecha' => $fecha,
				'id_tipom' => $id_tipom,
				'id_tipomd' => $id_tipomd,
		        'rut' => $rut,
		        'id_bodegaent' => $id_bodegaent,
		        'id_bodegasal' => $id_bodegasal,
		        'detalle' => $detalle,
		);

		$this->db->insert('movimientodiario', $movimiento); 
		$idmovimiento = $this->db->insert_id();

		foreach($items as $v){

		  if ($v->producto>0){

		  	$producto = $v->producto;
		    $puc = ($v->precio);
		    $cero=0;

			$movimiento_diario = array(
		        'id_movimiento' => $idmovimiento,
		        'id_tipom' => $id_tipom,
		        'id_tipomd' => $id_tipomd,
		        'id_producto' => $v->producto,
		        'cantidad' => $v->cantidad,
		        'valor' => $v->precio,
		        'stock' => $v->stock,
		        'fecha' => $fecha,
		        'lote' => $v->lote,
			);

			if ($v->producto>0){
				$this->db->insert('movimientodiario_detalle', $movimiento_diario);
			};
			
			
			$producto = $v->producto;

			$query = $this->db->query('SELECT * FROM productos WHERE id like "'.$producto.'"');
			if($query->num_rows()>0){

				$row = $query->first_row();
				$query2 = $this->db->query('SELECT * FROM existencia_detalle WHERE id_producto='.$producto.' and cantidad_entrada > '.$cero.'');	    	 
				$ppm=0;

				$cal = 1;
				if ($query2->num_rows()>0){
					foreach ($query2->result() as $r){			 	
				 	$ppm = $ppm + ($r->valor_producto);
				 	$cal = $cal +1;
				};
				$ppm = $ppm + $puc;
				$ppm = ($ppm / $cal);
					$saldo = ($row->stock)+($v->cantidad);
					$pmc = ($row->p_may_compra);
					if ($pmc < $puc){			 		
						$pmc = $puc;
					};			 
				}else{
					$saldo = ($v->cantidad);
					$pmc=$puc;
				}
				
				$fecha_vencimiento = date("Y-m-d", strtotime($v->fecha_vencimiento));
				
				$prod = array(
				'stock' => $saldo,
				'p_ult_compra' => $puc,
				'p_venta' => $puc,
				'p_may_compra' => $pmc,
				'p_promedio' => $ppm,
				'fecha_ult_compra' => $fecha,
				'stock' => $saldo,
				'fecha_vencimiento' => $fecha_vencimiento,
				'u_lote' => $v->lote,

				);

	    	$this->db->where('id', $producto);

	    	$this->db->update('productos', $prod);
			 };

			

		     $query = $this->db->query('SELECT * FROM existencia WHERE id_producto='.$producto.' and id_bodega='.$id_bodegaent.' ');
	    	 
			 if ($query->num_rows()>0){

			 	$row = $query->result();
			 	$row = $row[0];
	 
			
	            if ($producto==($row->id_producto)){
				    $datos3 = array(
						'stock' => $saldo,
				        'fecha_ultimo_movimiento' => $fecha
					);

					$this->db->where('id_producto', $producto);

		    	    $this->db->update('existencia', $datos3);

		    	    if ($id_tipom == 1){
		    	    $existesi = array(

						'num_movimiento' => $numero,
				        'id_producto' => $v->producto,
				        'id_tipo_movimiento' => $id_correlativo,
				        'valor_producto' =>  $v->precio,
				        'cantidad_entrada' => $v->cantidad,
				        'id_bodega' => $id_bodegaent,
				        'fecha_movimiento' => $fecha,
				        'lote' => $v->lote,
				        'fecha_vencimiento' => $fecha_vencimiento,
				        'saldo' => $v->cantidad,
					);

					};

					if ($id_tipom == 2){

					$existesi = array(

						'num_movimiento' => $numero,
				        'id_producto' => $v->producto,
				        'id_tipo_movimiento' => $id_correlativo,
				        'valor_producto' =>  $v->precio,
				        'cantidad_salida' => $v->cantidad,
				        'id_bodega' => $id_bodegasal,
				        'fecha_movimiento' => $fecha,
				        'fecha_vencimiento' => $fecha_vencimiento,
				        'lote' => $v->lote,
				        'saldo' => $v->cantidad,
					);

					};

					$this->db->insert('existencia_detalle', $existesi);

	    	     }
	         }else{

	         	$nexiste = array(
					'id_producto' => $producto,
			        'stock' =>  $saldo,
			        'id_bodega' => $id_bodegaent,
			        'fecha_ultimo_movimiento' => $fecha,
				);
				$this->db->insert('existencia', $nexiste);

				$existesi = array(

						'num_movimiento' => $numero,
				        'id_producto' => $v->producto,
				        'id_tipo_movimiento' => $id_correlativo,
				        'valor_producto' =>  $v->precio,
				        'cantidad_entrada' => $v->cantidad,
				        'lote' => $v->lote,
				        'id_bodega' => $id_bodegaent,
				        'fecha_movimiento' => $fecha,
				        'fecha_vencimiento' => $fecha_vencimiento,
				        'saldo' => $v->cantidad,
				);

				$this->db->insert('existencia_detalle', $existesi);

	         };

			
	    }


		}


		
        $resp['success'] = true;

        $this->Bitacora->logger("I", 'movimientodiario', $numero);


        echo json_encode($resp);

	}

	public function update(){
	}

	public function getAll(){
		$resp = array();

        $start = $this->input->post('start');
        $limit = $this->input->post('limit');
        
        $nombre = $this->input->post('nombre');

		$countAll = $this->db->count_all_results("movimientodiario");

		if($nombre){
			$query = $this->db->query('SELECT acc.*,  e.nombre as nom_bodegaent, s.nombre as nom_bodegasal, t.nombre as nom_tipomd FROM movimientodiario acc
			left join bodegas e on (acc.id_bodegaent = e.id)
			left join bodegas s on (acc.id_bodegasal = s.id)
			left join tipo_movimiento t on (acc.id_tipomd = t.id)
			WHERE acc.id_tipom like "'.$nombre.'" 
			limit '.$start.', '.$limit.'');
		}else{
			$query = $this->db->query('SELECT acc.*,  e.nombre as nom_bodegaent, s.nombre as nom_bodegasal, t.nombre as nom_tipomd FROM movimientodiario acc
			left join bodegas e on (acc.id_bodegaent = e.id)
			left join bodegas s on (acc.id_bodegasal = s.id)
			left join tipo_movimiento t on (acc.id_tipomd = t.id)
			order by acc.id desc
			limit '.$start.', '.$limit.'');
		}

		$data = array();
		foreach ($query->result() as $row)
		{
			$data[] = $row;
		}
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function getAll3(){
		$resp = array();

        $start = $this->input->post('start');
        $limit = $this->input->post('limit');
        
        $nombre = 2;

		$countAll = $this->db->count_all_results("movimientodiario");

		if($nombre){
			$query = $this->db->query('SELECT acc.*,  e.nombre as nom_bodegaent, s.nombre as nom_bodegasal, t.nombre as nom_tipomd FROM movimientodiario acc
			left join bodegas e on (acc.id_bodegaent = e.id)
			left join bodegas s on (acc.id_bodegasal = s.id)
			left join tipo_movimiento t on (acc.id_tipomd = t.id)
			WHERE acc.id_tipom like "'.$nombre.'"
			order by acc.id desc
			limit '.$start.', '.$limit.'');
		}else{
			$query = $this->db->query('SELECT acc.*,  e.nombre as nom_bodegaent, s.nombre as nom_bodegasal, t.nombre as nom_tipomd FROM movimientodiario acc
			left join bodegas e on (acc.id_bodegaent = e.id)
			left join bodegas s on (acc.id_bodegasal = s.id)
			left join tipo_movimiento t on (acc.id_tipomd = t.id)
			WHERE acc.id_tipom like "'.$nombre.'" 
			order by acc.id desc
			limit '.$start.', '.$limit.'');
		}

		$data = array();
		foreach ($query->result() as $row)
		{
			$data[] = $row;
		}
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function getAll2(){
		$resp = array();

        $start = $this->input->post('start');
        $limit = $this->input->post('limit');       
        $nombre = $this->input->post('nombre');

		$countAll = $this->db->count_all_results("movimientodiario");

		if($nombre){
			$query = $this->db->query('SELECT acc.*,  e.nombre as nom_bodegaent, s.nombre as nom_bodegasal, t.nombre as nom_tipomd FROM movimientodiario acc
			left join bodegas e on (acc.id_bodegaent = e.id)
			left join bodegas s on (acc.id_bodegasal = s.id)
			left join tipo_movimiento t on (acc.id_tipomd = t.id)
			WHERE acc.id_tipom like "'.$nombre.'"
			limit '.$start.', '.$limit.'');
		}else{
			$query = $this->db->query('SELECT acc.*,  e.nombre as nom_bodegaent, s.nombre as nom_bodegasal, t.nombre as nom_tipomd FROM movimientodiario acc
			left join bodegas e on (acc.id_bodegaent = e.id)
			left join bodegas s on (acc.id_bodegasal = s.id)
			left join tipo_movimiento t on (acc.id_tipomd = t.id)
			order by acc.id desc
			limit '.$start.', '.$limit.'');
		}

		$data = array();
		foreach ($query->result() as $row)
		{
			$data[] = $row;
		}
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function exportPDF2(){
		
		$nombre = $this->input->get('idmov');
				
		$query = $this->db->query('SELECT acc.*,  e.nombre as nom_bodegaent, s.nombre as nom_bodegasal, t.nombre as nom_tipomd FROM movimientodiario acc
			left join bodegas e on (acc.id_bodegaent = e.id)
			left join bodegas s on (acc.id_bodegasal = s.id)
			left join tipo_movimiento t on (acc.id_tipomd = t.id)
			WHERE acc.id like "'.$nombre.'"');
		
		$row = $query->result();		
		$row = $row[0];
		
		$items = $this->db->query('SELECT acc.*,  p.codigo as codigo, t.nombre as nom_tipom, s.nombre as nom_tipom, p.nombre as nom_producto FROM movimientodiario_detalle acc
			left join tipo_movimiento t on (acc.id_tipom = t.id)
			left join tipo_movimiento s on (acc.id_tipomd = s.id)
			left join productos p on (acc.id_producto = p.id)			
			WHERE acc.id_movimiento like "'.$nombre.'"');
		//variables generales
		$codigo = $row->numero;

		//$nombre = $row->empresa;
		$fecha = $row->fecha;
        list($anio, $mes, $dia) = explode("-",$fecha);
        

		$this->load->model('facturaelectronica');
		$empresa = $this->facturaelectronica->get_empresa();
    
		$logo =  PATH_FILES."facturacion_electronica/images/".$empresa->logo; 
		
		$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Untitled Document</title>
		<style type="text/css">
		td {
			font-size: 16px;
		}
		p {
		}
		</style>
		</head>

		<body>
		<table width="987px" height="602" border="0">
		  <tr>
		    <td width="197px"><img src="' . $logo . '" width="150" height="136" /></td>
		    <td width="493px" style="font-size: 14px;text-align:center;vertical-align:text-top"	>
		    <p>' . $empresa->razon_social .'</p>
        <p>RUT:' . number_format($empresa->rut,0,".",".").'-' . $empresa->dv . '</p>
        <p>' . $empresa->dir_origen . ' - ' . $empresa->comuna_origen . ' - Chile</p>
        <p>Fonos:</p>
		    <p>http://www.lircay.cl</p>
		    </td>
		    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
		          <p>MOVIMIENTO N°: '.$codigo.'</p>
		          <!--p>&nbsp;</p-->
		          <p>FECHA EMISION : '.$fecha.'</p>
		          <!--p>&nbsp;</p-->
		    </td>0
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>MOVIMIENTO DIARIO CONSUMO</h1></td>
		  </tr>
		    	</table>
			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		       <tr>
		        <td width="40px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Codigo</td>
		        <td width="10px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >&nbsp;</td>
		        <td width="695px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Nombre Producto</td>
		        <td width="128px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="128px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Lote</td>
		        <td width="128px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Fecha</td>
		      </tr>';
		$descripciones = '';
		$i = 0;
		$linea= 0;
		foreach($items->result() as $v){
			//$i = 0;
			//while($i < 30){
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			$linea = $linea + 1;
			
			$html .= '<tr>
			<td style="text-align:right">'.$producto->codigo.'</td>	
			<td style="text-align:right"></td>					
			<td style="text-align:left">'.$producto->nombre.'</td>
			<td style="text-align:right">'.number_format($v->cantidad,2,'.',',').'&nbsp;&nbsp;</td>			
			<td align="right"> '.($v->lote).'</td>
			<td align="right"> '.($v->fecha).'</td>
			</tr>';
			
			//}
			$i++;
			//print_r($v->lote);
			//exit;
		}

		// RELLENA ESPACIO
		while($i < 30){
			$html .= '<tr><td colspan="5">&nbsp;</td></tr>';
			$i++;
		}


		$html .= '<tr><td colspan="5">&nbsp;</td></tr></table></td>
		 
		  			  
		 
		  
		  <tr>
		  	<td>&nbsp;</td>		  
		  </tr>
		  <tr>
		  	<td>&nbsp;</td>		  
		  </tr>		  		  		  	  
		 		  
		</table>
		</body>
		</html>
		';
		//==============================================================
		//==============================================================
		//==============================================================

		include(dirname(__FILE__)."/../libraries/mpdf60/mpdf.php");

		$mpdf= new mPDF(
			'',    // mode - default ''
			'',    // format - A4, for example, default ''
			0,     // font size - default 0
			'',    // default font family
			15,    // margin_left
			15,    // margin right
			16,    // margin top
			16,    // margin bottom
			9,     // margin header
			9,     // margin footer
			'L'    // L - landscape, P - portrait
			);  

		$mpdf->WriteHTML($html);
		$mpdf->Output("CF_{$codigo}.pdf", "I");
		
		exit;
	}

	public function exportPDF(){
		
		$nombre = $this->input->get('idmov');
				
		$query = $this->db->query('SELECT acc.*,  e.nombre as nom_bodegaent, s.nombre as nom_bodegasal, t.nombre as nom_tipomd FROM movimientodiario acc
			left join bodegas e on (acc.id_bodegaent = e.id)
			left join bodegas s on (acc.id_bodegasal = s.id)
			left join tipo_movimiento t on (acc.id_tipomd = t.id)
			WHERE acc.id like "'.$nombre.'"');
		
		$row = $query->result();		
		$row = $row[0];
		
		$items = $this->db->query('SELECT acc.*,  p.codigo as codigo, t.nombre as nom_tipom, s.nombre as nom_tipom, p.nombre as nom_producto FROM movimientodiario_detalle acc
			left join tipo_movimiento t on (acc.id_tipom = t.id)
			left join tipo_movimiento s on (acc.id_tipomd = s.id)
			left join productos p on (acc.id_producto = p.id)			
			WHERE acc.id_movimiento like "'.$nombre.'"');
		//variables generales
		$codigo = $row->numero;

		//$nombre = $row->empresa;
		$fecha = $row->fecha;
        list($anio, $mes, $dia) = explode("-",$fecha);
        

		$this->load->model('facturaelectronica');
		$empresa = $this->facturaelectronica->get_empresa();
    
		$logo =  PATH_FILES."facturacion_electronica/images/".$empresa->logo; 
		
		$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Untitled Document</title>
		<style type="text/css">
		td {
			font-size: 16px;
		}
		p {
		}
		</style>
		</head>

		<body>
		<table width="987px" height="602" border="0">
		  <tr>
		    <td width="197px"><img src="' . $logo . '" width="150" height="136" /></td>
		    <td width="493px" style="font-size: 14px;text-align:center;vertical-align:text-top"	>
		    <p>' . $empresa->razon_social .'</p>
        <p>RUT:' . number_format($empresa->rut,0,".",".").'-' . $empresa->dv . '</p>
        <p>' . $empresa->dir_origen . ' - ' . $empresa->comuna_origen . ' - Chile</p>
        <p>Fonos:</p>
		    <p>http://www.lircay.cl</p>
		    </td>
		    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
		          <p>MOVIMIENTO N°: '.$codigo.'</p>
		          <!--p>&nbsp;</p-->
		          <p>FECHA EMISION : '.$fecha.'</p>
		          <!--p>&nbsp;</p-->
		    </td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>MOVIMIENTO DIARIO</h1></td>
		  </tr>
		    	</table>
			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		       <tr>
		        <td width="40px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Codigo</td>
		        <td width="10px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >&nbsp;</td>
		        <td width="695px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Nombre Producto</td>
		        <td width="128px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="128px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Lote</td>
		        <td width="128px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Fecha</td>
		      </tr>';
		$descripciones = '';
		$i = 0;
		$linea= 0;
		foreach($items->result() as $v){
			//$i = 0;
			//while($i < 30){
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			$linea = $linea + 1;
			
			$html .= '<tr>
			<td style="text-align:right">'.$producto->codigo.'</td>	
			<td style="text-align:right"></td>					
			<td style="text-align:left">'.$producto->nombre.'</td>
			<td style="text-align:right">'.number_format($v->cantidad,2,'.',',').'&nbsp;&nbsp;</td>			
			<td align="right"> '.($v->lote).'</td>
			<td align="right"> '.($v->fecha).'</td>
			</tr>';
			
			//}
			$i++;
			//print_r($v->lote);
			//exit;
		}

		// RELLENA ESPACIO
		while($i < 30){
			$html .= '<tr><td colspan="5">&nbsp;</td></tr>';
			$i++;
		}


		$html .= '<tr><td colspan="5">&nbsp;</td></tr></table></td>
		 
		  			  
		 
		  
		  <tr>
		  	<td>&nbsp;</td>		  
		  </tr>
		  <tr>
		  	<td>&nbsp;</td>		  
		  </tr>		  		  		  	  
		 		  
		</table>
		</body>
		</html>
		';
		//==============================================================
		//==============================================================
		//==============================================================

		include(dirname(__FILE__)."/../libraries/mpdf60/mpdf.php");

		$mpdf= new mPDF(
			'',    // mode - default ''
			'',    // format - A4, for example, default ''
			0,     // font size - default 0
			'',    // default font family
			15,    // margin_left
			15,    // margin right
			16,    // margin top
			16,    // margin bottom
			9,     // margin header
			9,     // margin footer
			'L'    // L - landscape, P - portrait
			);  

		$mpdf->WriteHTML($html);
		$mpdf->Output("CF_{$codigo}.pdf", "I");
		
		exit;
	}

	
}
