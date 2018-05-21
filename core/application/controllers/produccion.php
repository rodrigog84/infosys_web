<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produccion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();	
			
		$this->load->database();
	}

	public function consulta(){

		$resp = array();
	    $idproduccion = $this->input->post('idproduccion');

		$items = $this->db->get_where('produccion', array('id' => $idproduccion));

		foreach($items->result() as $item){			
			$fecha= $item->fecha_produccion;
		}

	   	$resp['success'] = true;
        $resp['fechadoc'] = $fecha;
        echo json_encode($resp);

	}

	public function producciontermino(){

		$resp = array();
	    $idproduccion = $this->input->post('idproduccion');

		$items = $this->db->get_where('produccion_detalle', array('id_produccion' => $idproduccion));

	   	$data = array();

	   	foreach($items->result() as $item){
			$this->db->where('id', $item->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			$item->nom_producto = $producto->nombre;
			$item->cantidad_pro = $item->cantidad;
			$item->porcentaje_pro = $item->porcentaje;
			$data[] = $item;
		}

	   	$resp['success'] = true;
        $resp['data'] = $data;
        echo json_encode($resp);

		

	}

	public function termino(){

	   $resp = array();
	   $idproduccion = $this->input->get('idproduccion');
	  
	   $query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido, p.num_pedido as num_pedido, pr.diasvencimiento as dias FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		left join productos pr on (acc.id_producto = pr.id)
		WHERE acc.id = "'.$idproduccion.'"');

		foreach ($query->result() as $row){
			$rutautoriza = $row->rut_cliente;
		   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $row->rut_cliente = ($ruta4.".".$ruta3.".".$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $row->rut_cliente = ($ruta4.".".$ruta3.".".$ruta2."-".$ruta1);
		   
		    };

		     if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $row->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };

		   if (strlen($rutautoriza) == 7){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $row->rut_cliente = ($ruta3.".".$ruta2."-".$ruta1);
		     
		    };
		    if (strlen($rutautoriza) == 4){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $row->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };
		    if (strlen($rutautoriza) == 6){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -6, 2);
		      $row->rut_cliente = ($ruta3.".".$ruta2."-".$ruta1);
		     
		    };		    
			
		}

        $resp['success'] = true;
        $resp['cliente'] = $row;

        echo json_encode($resp);
		

	}

	public function getAll(){
		
		$resp = array();

        $start = $this->input->post('start');
        $limit = $this->input->post('limit');
                		
        $data = array();		
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		');

	    $total = 0;

		foreach ($query->result() as $row)
		{
		$total = $total +1;

		}

		$countAll = $total;

		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)');

		foreach ($query->result() as $row){
			$rutautoriza = $row->rut_cliente;
		   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $row->rut_cliente = ($ruta4.".".$ruta3.".".$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $row->rut_cliente = ($ruta4.".".$ruta3.".".$ruta2."-".$ruta1);
		   
		    };

		     if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $row->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };

		   if (strlen($rutautoriza) == 7){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $row->rut_cliente = ($ruta3.".".$ruta2."-".$ruta1);
		     
		    };
		    
		    
		    if (strlen($rutautoriza) == 4){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $row->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };	


		     if (strlen($rutautoriza) == 6){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -6, 2);
		      $row->rut_cliente = ($ruta3.".".$ruta2."-".$ruta1);
		     
		    };		    
			$data[] = $row;
		}

        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}
	
	public function save(){
		
		$resp = array();
		$numproduccion = $this->input->post('numproduccion');
		$fechaproduccion = $this->input->post('fechaproduccion');
		$idpedido = $this->input->post('idpedido');
		$numpedido = $this->input->post('numpedido');
		$idcliente = $this->input->post('idcliente');
		$idformula = $this->input->post('idformula');
		$nombreformula = $this->input->post('nombreformula');
		$cantidadproduccion = $this->input->post('cantidadproduccion');
		$lote = $this->input->post('lote');
		$nombreproducto = $this->input->post('nombreproducto');
		$idproducto = $this->input->post('idproducto');
		$items = json_decode($this->input->post('items'));
		$horainicio = $this->input->post('horainicio');
		$encargado = $this->input->post('encargado');
					
		$produccion = array(
	        'id_pedido' => $idpedido,
	        'id_formula_pedido' => $idformula,
	        'id_cliente' => $idcliente,
	        'num_produccion' => $numproduccion,
	        'fecha_produccion' => $fechaproduccion,
	        'nom_formula' => $nombreformula,
	        'nom_producto' => $nombreproducto,
	        'id_producto' => $idproducto,
	        'nom_formula' => $nombreformula,
	        'cantidad' => $cantidadproduccion,
	        'lote' => $lote,
	        'hora_inicio' => $horainicio,
	        'encargado' => $encargado,
	        'estado' => 1
		);

		$this->db->insert('produccion', $produccion); 
		$idproduccion = $this->db->insert_id();

		foreach($items as $v){
			$produccion_detalle = array(
		        'id_produccion' => $idproduccion,
		        'id_producto' => $v->id_producto,
		        'nom_producto' => $v->nombre_producto,
		        'valor_compra' => $v->valor_compra,
		        'cantidad' => $v->cantidad,
		        'valor_produccion' => $v->valor_produccion,
		        'porcentaje' => $v->porcentaje
			);

		
		$this->db->insert('produccion_detalle', $produccion_detalle);
	   	
		};

		$pedidos = array(
	        'estado' => 2
			);			

			
		$this->db->where('id', $idpedido);
		$this->db->update('pedidos', $pedidos);

        $resp['idproduccion'] = $idproduccion;		
        $resp['success'] = true;
       
		$this->Bitacora->logger("I", 'produccion', $idproduccion);
		$this->Bitacora->logger("I", 'produccion_detalle', $idproduccion);        

        echo json_encode($resp);
	}

	public function save2(){
		
		$resp = array();
		$fechaproduccion = $this->input->post('fechaproduccion');
		$fechavenc = $this->input->post('fechavenc');
		$numproduccion = $this->input->post('numproduccion');
		$cantidadproduccion = $this->input->post('cantidadproduccion');
		$idproducto = $this->input->post('idproducto');
		$idcliente = $this->input->post('idcliente');
		$idproduccion = $this->input->post('idproduccion');
		$idpedido = $this->input->post('idpedido');
		$idbodega = $this->input->post('idbodega');
		$lote = $this->input->post('lote');
		$items = json_decode($this->input->post('items'));
		$horatermino = $this->input->post('horatermino');

		$query = $this->db->query('DELETE FROM produccion_detalle WHERE id_produccion = "'.$idproduccion.'" ');

		$query = $this->db->query('SELECT * FROM productos WHERE id="'.$idproducto.'"');
		if($query->num_rows()>0){
		 	$row = $query->first_row();
		 	$saldo = ($row->stock)+($cantidadproduccion);
		 	$datos = array(
			'stock' => $saldo,
	        'fecha_ult_compra' => $fechaproduccion
			);
		 	$this->db->where('id', $idproducto);
    		$this->db->update('productos', $datos);
		};

		$producto=$idproducto;

		$query = $this->db->query('SELECT * FROM existencia WHERE id_producto='.$idproducto.' and id_bodega='.$idbodega.'');
    	 $row = $query->result();
    	 if ($query->num_rows()>0){
		 $row = $row[0];	 
         if ($producto==($row->id_producto)){
         	$saldo = ($row->stock)+($cantidadproduccion);

		    $datos3 = array(
			'stock' => $saldo,
	        'fecha_ultimo_movimiento' => $fechaproduccion
			);

			$this->db->where('id_producto', $idproducto);
    	    $this->db->update('existencia', $datos3);
	     }
		}else{
			$saldo = $cantidadproduccion;
			$datos3 = array(
			'id_producto' => $idproducto,
	        'stock' =>  $saldo,
	        'fecha_ultimo_movimiento' => $fechaproduccion,
	        'id_bodega' => $idbodega			
			);
			$this->db->insert('existencia', $datos3);
		};

		$datos2 = array(
				'num_movimiento' => $numproduccion,
		        'id_producto' => $idproducto,
		        'id_tipo_movimiento' => 23,
		        'cantidad_entrada' => $cantidadproduccion,
		        'fecha_movimiento' => $fechaproduccion,
		        'id_bodega' => $idbodega,
		        'id_cliente' => $idcliente,
		        'lote' => $lote,
		        'fecha_vencimiento' => $fechavenc

		);
		$this->db->insert('existencia_detalle', $datos2);	   	
									
		$produccion = array(
	        'fecha_termino' => $fechaproduccion,
	        'cantidad_prod' => $cantidadproduccion,
	        'hora_termino' => $horatermino,
	        'fecha_vencimiento' => $fechavenc,
	        'estado' => 2
		);
		$this->db->where('id', $idproduccion);		  
		$this->db->update('produccion', $produccion);		
		foreach($items as $v){
			$produccion_detalle = array(
		        'id_produccion' => $idproduccion,
		        'id_producto' => $v->id_producto,
		        'nom_producto' => $v->nom_producto,
		        'valor_compra' => $v->valor_compra,
		        'cantidad' => $v->cantidad,
		        'id_bodega' => 1,
		        'cantidad_pro' => $v->cantidad_pro,
		        'valor_produccion' => $v->valor_produccion,
		        'porcentaje' => $v->porcentaje,
		        'porcentaje_pro' => $v->porcentaje_pro
			);		
		$this->db->insert('produccion_detalle', $produccion_detalle);
		$producto = $v->id_producto;
		$query = $this->db->query('SELECT * FROM productos WHERE id="'.$producto.'"');
		if($query->num_rows()>0){
		 	$row = $query->first_row();
		 	$saldo = ($row->stock)-($v->cantidad_pro);

		 	$datos = array(
			'stock' => $saldo,
	        'fecha_ult_compra' => $fechaproduccion
			);


		 	$this->db->where('id', $producto);

    		$this->db->update('productos', $datos);

		 };
		 $query = $this->db->query('SELECT * FROM existencia WHERE id_producto='.$producto.' and id_bodega=1');
    	 $row = $query->result();
    	 if ($query->num_rows()>0){
		 $row = $row[0];	 
         if ($producto==($row->id_producto)){
         	$saldo = ($row->stock)-($v->cantidad_pro);

		    $datos3 = array(
			'stock' => $saldo,
	        'fecha_ultimo_movimiento' => $fechaproduccion
			);

			$this->db->where('id_producto', $producto);
    	    $this->db->update('existencia', $datos3);
	     }
		};
		$datos2 = array(
				'num_movimiento' => $numproduccion,
		        'id_producto' => $v->id_producto,
		        'id_tipo_movimiento' => 23,
		        'valor_producto' =>  $v->valor_compra,
		        'cantidad_salida' => $v->cantidad_pro,
		        'fecha_movimiento' => $fechaproduccion,
		        'id_bodega' => 1,
		        'id_cliente' => $idcliente,
		        'p_promedio' => $v->valor_compra,
		       
		);
		$this->db->insert('existencia_detalle', $datos2);	   	
		};		
        $resp['idproduccion'] = $idproduccion;		
        $resp['success'] = true;

        $pedidos = array(
	        'estado' => 3
			);			

			
		$this->db->where('id', $idpedido);
		$this->db->update('pedidos', $pedidos);
       
		$this->Bitacora->logger("M", 'produccion', $idproduccion);
		$this->Bitacora->logger("M", 'produccion_detalle', $idproduccion);        

        echo json_encode($resp);
	}

	public function exportPDF(){
		$idproduccion = $this->input->get('idproduccion');
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido, p.num_pedido as num_pedido,
		v.nombre as nom_vendedor FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		left join vendedores v on (p.id_vendedor = v.id)
		WHERE acc.id = "'.$idproduccion.'"');

		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		//items
		//$items = $this->db->get_where('formula_detalle', array('id_formula' => $idformula));
		
		$items = $this->db->get_where('produccion_detalle', array('id_produccion' => $idproduccion));
		//variables generales
		$codigo = $row->num_produccion;
		$nombre_contacto = $row->nom_cliente;
		$vendedor = $row->nom_vendedor;
		//$observacion = $row->observa;
		$fecha = $row->fecha_produccion;
		$hora = $row->hora_inicio;
		
		/*if(!$row->id_sucursal){
			$direccion = $row->direccion;
			$comuna = $row->comuna;
			$ciudad = $row->ciudad;			
		}else{

			$direccion = $row->direccion_sucursal;
			$comuna = $row->comuna_suc;
			$ciudad = $row->ciudad_suc;
			
		};*/

		$this->load->model('facturaelectronica');
      $empresa = $this->facturaelectronica->get_empresa();

      $logo =  PATH_FILES."facturacion_electronica/images/".$empresa->logo; 



		$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>PRODUCCION</title>
		<style type="text/css">
		td {
			font-size: 16px;
		}
		p {
		}
		</style>
		</head>

		<body>
		<table width="987px" height="400" border="0">
		  <tr>
		   <td width="197px"><img src="' . $logo . '" width="150" height="136" /></td>
		    <td width="493px" style="font-size: 14px;text-align:center;vertical-align:text-top"	>
		     <p>' . $empresa->razon_social .'</p>
        <p>RUT:' . number_format($empresa->rut,0,".",".").'-' . $empresa->dv . '</p>
        <p>' . $empresa->dir_origen . ' - ' . $empresa->comuna_origen . ' - Chile</p>
        <p>Fonos: ' . $empresa->fono . '</p>
		    <p>http://www.lircay.cl</p>
		    </td>
	    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
	          <p>Produccion N°: '.$codigo.'</p>
	          <!--p>&nbsp;</p-->
	          <p>FECHA INICIO : '.$fecha.'</p>
	          <!--p>&nbsp;</p-->
	          <p>HORA INICIO : '.$hora.'</p>
	          <!--p>&nbsp;</p-->		         
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>PRODUCCION</h1></td>
		  </tr>
		  <tr>
		    <td colspan="3" width="687px" >
		    	<table width="687px" border="0">
		    		<tr>
					<td width="197px">Sr.(es):</td>
					<td width="395px">'. $row->nom_cliente.'</td>
					<td width="147px">Rut:</td>
					<td width="147px">'. number_format(substr($row->rut_cliente, 0, strlen($row->rut_cliente) - 1),0,".",".")."-".substr($row->rut_cliente,-1).'</td>
					</tr>
		    		<tr>
		    		<td width="197px">FORMULA</td>
		    		<td width="197px">'.$row->nom_formula.'</td>
		    		<td width="147px">VENDEDOR:</td>
		    		<td width="147px">'.$row->nom_vendedor.'</td>
		    		</tr>
		    		
		    	</table>
			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		      <tr>
		        <td width="395px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Descripcion</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Valor Compra</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Porcentaje</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad Pro</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Porcentaje Pro</td>
		       </tr>';
		$descripciones = '';
		$i = 0;
		foreach($items->result() as $v){
			//$i = 0;
			//while($i < 30){
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			
			$html .= '<tr>
			<td style="text-align:left">'.$producto->nombre.'</td>
			<td style="text-align:left"></td>
			<td style="text-align:left"></td>
			<td style="text-align:right">'.number_format($v->valor_compra, 2, '.', ',').'</td>	
			<td align="right"> '.number_format($v->cantidad, 2, '.', ',').'</td>
			<td align="right">% '.number_format($v->porcentaje, 2, '.', ',').'</td>
			<td align="right"> _____________</td>
			<td align="right">% ____________</td>
			</tr>';
			
			//}
			$i++;
		}

		// RELLENA ESPACIO
		/*while($i < 30){
			$html .= '<tr><td colspan="5">&nbsp;</td></tr>';
			$i++;
		}*/


		$html .= '<tr><td colspan="5">&nbsp;</td></tr></table></td>
		  </tr>
		   
		  <tr>
		  	<td colspan="2" rowspan="6" style="font-size: 12px;border-bottom:1pt solid black;border-top:1pt solid black;border-left:1pt solid black;border-right:1pt solid black;text-align:left;">'.$observacion.'</td>
		  	<td>
				<table width="296px" border="0">
					
				</table>
		  	</td>
		  </tr>
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						
					</tr>
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					
				</table>
		  	</td>		  
		  </tr>
		  <tr>
		  	<td>
				<table width="296px" border="0">
					
				</table>
		  	</td>		  
		  </tr>
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

	public function exportPDF2(){
		$idproduccion = $this->input->get('idproduccion');
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido, p.num_pedido as num_pedido,
		v.nombre as nom_vendedor, pr.nombre as nom_productos FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		left join vendedores v on (p.id_vendedor = v.id)
		left join productos pr on (acc.id_producto = pr.id)
		WHERE acc.id = "'.$idproduccion.'"');

		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		//items
		//$items = $this->db->get_where('formula_detalle', array('id_formula' => $idformula));
		
		$items = $this->db->get_where('produccion_detalle', array('id_produccion' => $idproduccion));
		//variables generales
		$codigo = $row->num_produccion;
		$nombre_contacto = $row->nom_cliente;
		$vendedor = $row->nom_vendedor;
		$nombreproducto = $row->nom_productos;
		//$observacion = $row->observa;
		$fecha = $row->fecha_produccion;
		$fechatermino = $row->fecha_termino;		
		$hora = $row->hora_inicio;
		$horatermino= $row->hora_termino;
		
		$this->load->model('facturaelectronica');
      $empresa = $this->facturaelectronica->get_empresa();

      $logo =  PATH_FILES."facturacion_electronica/images/".$empresa->logo; 



		$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>PRODUCCION</title>
		<style type="text/css">
		td {
			font-size: 16px;
		}
		p {
		}
		</style>
		</head>

		<body>
		<table width="987px" height="400" border="0">
		  <tr>
		   <td width="197px"><img src="' . $logo . '" width="150" height="136" /></td>
		    <td width="493px" style="font-size: 14px;text-align:center;vertical-align:text-top"	>
		     <p>' . $empresa->razon_social .'</p>
        <p>RUT:' . number_format($empresa->rut,0,".",".").'-' . $empresa->dv . '</p>
        <p>' . $empresa->dir_origen . ' - ' . $empresa->comuna_origen . ' - Chile</p>
        <p>Fonos: ' . $empresa->fono . '</p>
		    <p>http://www.lircay.cl</p>
		    </td>
	    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
	          <p>Produccion N°: '.$codigo.'</p>
	          <!--p>&nbsp;</p-->
	          <p>FECHA INICIO : '.$fecha.'</p>
	          <!--p>&nbsp;</p-->
	          <p>FECHA TERMINO : '.$fechatermino.'</p>
	          <!--p>&nbsp;</p-->
	          <p>HORA INICIO : '.$hora.'</p>
	          <!--p>&nbsp;</p-->
	          <p>HORA TERMINO : '.$horatermino.'</p>
	          <!--p>&nbsp;</p-->		         
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>PRODUCCION TERMINADA</h1></td>
		  </tr>
		  <tr>
		    <td colspan="3" width="687px" >
		    	<table width="687px" border="0">
		    		<tr>
					<td width="197px">Sr.(es):</td>
					<td width="395px">'. $row->nom_cliente.'</td>
					<td width="147px">Rut:</td>
					<td width="147px">'. number_format(substr($row->rut_cliente, 0, strlen($row->rut_cliente) - 1),0,".",".")."-".substr($row->rut_cliente,-1).'</td>
					</tr>
		    		<tr>
		    		<td width="197px">FORMULA</td>
		    		<td width="197px">'.$row->nom_formula.'</td>
		    		<td width="147px">VENDEDOR:</td>
		    		<td width="147px">'.$row->nom_vendedor.'</td>
		    		</tr>
		    		<tr>
		    		<td width="197px">PRODUCTO</td>
		    		<td width="197px">'.$nombreproducto.'</td>
		    		<td width="147px">CANTIDAD PRODUCIDA:</td>
		    		<td width="147px">'.number_format($row->cantidad_prod, 2, '.', ',').'</td>
		    		</tr>
		    		
		    	</table>
			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		      <tr>
		        <td width="395px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Descripcion</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Valor Compra</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Porcentaje</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad Pro</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Porcentaje Pro</td>
		       </tr>';
		$descripciones = '';
		$i = 0;
		foreach($items->result() as $v){
			//$i = 0;
			//while($i < 30){
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			
			$html .= '<tr>
			<td style="text-align:left">'.$producto->nombre.'</td>
			<td style="text-align:left"></td>
			<td style="text-align:left"></td>
			<td style="text-align:right">'.number_format($v->valor_compra, 2, '.', ',').'</td>	
			<td align="right"> '.number_format($v->cantidad, 2, '.', ',').'</td>
			<td align="right">% '.number_format($v->porcentaje, 2, '.', ',').'</td>
			<td align="right"> '.number_format($v->cantidad_pro, 2, '.', ',').'</td>
			<td align="right">% '.number_format($v->porcentaje_pro, 2, '.', ',').'</td>
			</tr>';
			
			//}
			$i++;
		}

		$html .= '<tr><td colspan="5">&nbsp;</td></tr></table></td>
		  </tr>
		   
		  <tr>
		  	<td colspan="2" rowspan="6" style="font-size: 12px;border-bottom:1pt solid black;border-top:1pt solid black;border-left:1pt solid black;border-right:1pt solid black;text-align:left;">'.$observacion.'</td>
		  	<td>
				<table width="296px" border="0">
					
				</table>
		  	</td>
		  </tr>
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						
					</tr>
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					
				</table>
		  	</td>		  
		  </tr>
		  <tr>
		  	<td>
				<table width="296px" border="0">
					
				</table>
		  	</td>		  
		  </tr>
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
