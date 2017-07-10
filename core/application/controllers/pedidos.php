<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->database();
	}

	public function pedidosdetalle(){

		$resp = array();
		$pedido = $this->input->get('pedido');

		if ($pedido){

		$items = $this->db->query('SELECT * FROM pedidos_detalle 
	   	    WHERE id_pedido like "'.$pedido.'"');

		//$items = $this->db->get_where('pedidos_detalle', array('id_pedido' => $pedido));

	   	$data = array();

	   	foreach($items->result() as $item){
			$this->db->where('id', $item->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			$item->nombre = $producto->nombre;
			$item->dcto = $item->descuento;	
			$data[] = $item;
		}	     	
	    $resp['success'] = true;
        $resp['data'] = $data; 
        echo json_encode($resp);
        }
	}

	public function getObserva(){

		$resp = array();
		$idobserva = $this->input->post('idobserva');

		$query = $this->db->query('SELECT * FROM observacion_pedidos 
	   	WHERE num_pedidos like "'.$idobserva.'"');
	   	$data = array();
	   	if($query->num_rows()>0){			   
		foreach ($query->result() as $row){			
			$data[] = $row;		     
		};
		   $resp['observar'] = $row;
		   $resp['existe'] = true;
		   $resp['success'] = true;	
		}else{
			$resp['existe'] = false;
	   		$resp['success'] = false;
		};

	   		
	   echo json_encode($resp);
	}

	public function validaRut(){

		
		$resp = array();
		$rut = $this->input->get('valida');
        $iddocu = 1;
		
		if(strpos($rut,"-")==false){
	        $RUT[0] = substr($rut, 0, -1);
	        $RUT[1] = substr($rut, -1);
	    }else{
	        $RUT = explode("-", trim($rut));
	    }
	    $elRut = str_replace(".", "", trim($RUT[0]));
	    $factor = 2;
	    $suma=0;
	    for($i = strlen($elRut)-1; $i >= 0; $i--):
	        $factor = $factor > 7 ? 2 : $factor;
	        $suma += $elRut{$i}*$factor++;
	    endfor;
	    $resto = $suma % 11;
	    $dv = 11 - $resto;
	    if($dv == 11){
	        $dv=0;
	    }else if($dv == 10){
	        $dv="k";
	    }else{
	        $dv=$dv;
	    }

	   if($dv == trim(strtolower($RUT[1]))){

	   	    $query = $this->db->query('SELECT * FROM observacion_pedidos 
	   	    WHERE rut like "'.$rut.'"');

	   	    if($query->num_rows()>0){

			   $data = array();
			   foreach ($query->result() as $row)
				{
				$rutautoriza = $row->rut;
			   	if (strlen($rutautoriza) == 8){
			      $ruta1 = substr($rutautoriza, -1);
			      $ruta2 = substr($rutautoriza, -4, 3);
			      $ruta3 = substr($rutautoriza, -7, 3);
			      $ruta4 = substr($rutautoriza, -8, 1);
			      $row->rut = ($ruta4.".".$ruta3.".".$ruta2."-".$ruta1);
			    };
			    if (strlen($rutautoriza) == 9){
			      $ruta1 = substr($rutautoriza, -1);
			      $ruta2 = substr($rutautoriza, -4, 3);
			      $ruta3 = substr($rutautoriza, -7, 3);
			      $ruta4 = substr($rutautoriza, -9, 2);
			      $row->rut = ($ruta4.".".$ruta3.".".$ruta2."-".$ruta1);		   
			    };
			     if (strlen($rutautoriza) == 2){
			      $ruta1 = substr($rutautoriza, -1);
			      $ruta2 = substr($rutautoriza, -4, 1);
			      $row->rut = ($ruta2."-".$ruta1);		     
			    };	   
				$data[] = $row;
				}
	   			$resp['observa'] = $row;
	   			$resp['existe'] = true;
	   			$resp['success'] = true;
	   			echo json_encode($resp);
	        	return false;

	   		}else{
	   			$rutautoriza = $rut;
		   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $rut = ($ruta4.".".$ruta3.".".$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $rut = ($ruta4.".".$ruta3.".".$ruta2."-".$ruta1);		   
		    };
		     if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $rut = ($ruta2."-".$ruta1);		     
		    };		  
			
	        $resp['rut'] = $rut;
	        $resp['existe'] = false;
	        $resp['success'] = true;
	        echo json_encode($resp);
	        return false;

	   		};

	   		
	   }else{
	   	    $resp['success'] = false;
	   	    echo json_encode($resp);
	        return false;
	   }
	
	}

	public function saveobserva(){

		$resp = array();
		$numero = $this->input->post('numero');
		$observa = $this->input->post('observa');
		$id = $this->input->post('numero');

		$query = $this->db->query('SELECT * FROM observacion_pedidos 
	   	WHERE num_pedidos like "'.$id.'"');
	   	$data = array();
	   	if($query->num_rows()>0){
	   	   $row = $query->first_row();
	   	  
	   	    $observa = array(
			'observaciones' => $observa	          
			);

			$this->db->where('num_pedidos', $id);		  
		    $this->db->update('observacion_pedidos', $observa);
		}else{
			$observa = array(
			'num_pedidos' => $numero,			
	        'observaciones' => $observa	          
			);

			$this->db->insert('observacion_pedidos', $observa);
			$idobserva = $this->db->insert_id();
				
		};

		$resp['success'] = true;
		$resp['idobserva'] = $numero;
		echo json_encode($resp);
	}

	public function elimina(){

		$resp = array();
		$idproducto = $this->input->post('producto');
		$idticket = $this->input->post('idticket');

		$query = $this->db->query('DELETE FROM pedidos_detalle WHERE id_pedido = "'.$idticket.'" & id_producto = "'.$idproducto.'"');

		$resp['success'] = true;
       

        echo json_encode($resp);				

	}

	public function edita(){

		$resp = array();
		$idpedidos = $this->input->get('idpedidos');
		
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, v.id as id_vendedor, c.direccion as direccion, c.id_lista as id_lista,c.id_pago as id_pago, suc.direccion as direccion_sucursal, ciu.nombre as ciudad, com.nombre as comuna, cor.nombre as nom_documento, cod.nombre as nom_giro FROM pedidos acc
		left join correlativos cor on (acc.tip_documento = cor.id)
		left join clientes c on (acc.id_cliente = c.id)
		left join vendedores v on (acc.id_vendedor = v.id)
		left join clientes_sucursales suc on (acc.id_sucursal = suc.id)
		left join comuna com on (suc.id_comuna = com.id)
		left join ciudad ciu on (suc.id_ciudad = ciu.id)
		left join cod_activ_econ cod on (c.id_giro = cod.id)
		WHERE acc.id = "'.$idpedidos.'"
		');

		$row1 = $query->result();
		$row = $row1[0];	

	   	
	    	
	    $items = $this->db->get_where('pedidos_detalle', array('id_pedido' => $idpedidos));

	   	$secuencia = 0;

	   	foreach($items->result() as $item){
			
			$secuencia = $secuencia + 1;
			
		};

		$row = $query->first_row();
	   	$resp['cliente'] = $row;
	    $resp['success'] = true;
	    $resp['secuencia'] = $secuencia;       

        echo json_encode($resp);
	}

	public function editapedidos(){

		$resp = array();
		$idcotiza = $this->input->get('idcotiza');
		$factura = 6;
		$correla = $this->db->query('SELECT * FROM correlativos WHERE id like "'.$factura.'"');

		if($correla->num_rows()>0){
	   		$row = $correla->first_row();
	   		$corr = (($row->correlativo)+1); 
	   		$id = ($row->id);

	   		$data3 = array(
	         'correlativo' => $corr
		    );

		    $this->db->where('id', $id);
		  
		    $this->db->update('correlativos', $data3);

		    $this->Bitacora->logger("M", 'correlativos', $id);

		 }
		
		$query = $this->db->query('SELECT ctz.*, cli.direccion as direccion_sucursal, cli.id as id_cliente, cli.nombres as nombres, cli.id_giro as giro, g.nombre as nombre_giro, cli.rut as rut, cli.id_pago as id_pago, cli.direccion as direccion FROM cotiza_cotizaciones ctz
		left join clientes cli ON (ctz.id_cliente = cli.id)
		left join cod_activ_econ g on (cli.id_giro = g.id)
		WHERE ctz.id = '.$idcotiza.'
		');

		$row1 = $query->result();
		$row = $row1[0];
		$rutautoriza = $row->rut;
	   	   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $row->rut = ($ruta4.".".$ruta3.".".$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $row->rut = ($ruta4.".".$ruta3.".".$ruta2."-".$ruta1);
		   
		    };

		     if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $row->rut = ($ruta2."-".$ruta1);
		     
		    };
		 

	   	$row = $query->first_row();
	   	$resp['cliente'] = $row;
	   	$resp['correlanue'] = $corr;
	    $resp['success'] = true;
       

        echo json_encode($resp);
	}

	public function edita3(){

		$resp = array();
		$idpedidos = $this->input->get('idpedidos');
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, v.id as id_vendedor, c.direccion as direccion,
		c.id_pago as id_pago, suc.direccion as direccion_sucursal, ciu.nombre as ciudad, com.nombre as comuna, cor.nombre as nom_documento, cod.nombre as nom_giro FROM pedidos acc
		left join correlativos cor on (acc.id_tip_docu = cor.id)
		left join clientes c on (acc.id_cliente = c.id)
		left join vendedores v on (acc.id_vendedor = v.id)
		left join clientes_sucursales suc on (acc.id_sucursal = suc.id)
		left join comuna com on (suc.id_comuna = com.id)
		left join ciudad ciu on (suc.id_ciudad = ciu.id)
		left join cod_activ_econ cod on (c.id_giro = cod.id)		
		WHERE acc.num_ticket = "'.$idpedidos.'"
		');


		$row1 = $query->result();
		$row = $row1[0];
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

	   	$row = $query->first_row();
	   	
	   	$resp['cliente'] = $row;

	    $resp['success'] = true;
	

        echo json_encode($resp);
	}

	public function edita2(){

		$resp = array();
		$idpedidos = $this->input->get('idpedidos');
		
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, v.id as id_vendedor, c.direccion as direccion,
		c.id_pago as id_pago, suc.direccion as direccion_sucursal, ciu.nombre as ciudad, com.nombre as comuna, cor.nombre as nom_documento, cod.nombre as nom_giro FROM pedidos acc
		left join correlativos cor on (acc.tip_documento = cor.id)
		left join clientes c on (acc.id_cliente = c.id)
		left join vendedores v on (acc.id_vendedor = v.id)
		left join clientes_sucursales suc on (acc.id_sucursal = suc.id)
		left join comuna com on (suc.id_comuna = com.id)
		left join ciudad ciu on (suc.id_ciudad = ciu.id)
		left join cod_activ_econ cod on (c.id_giro = cod.id)
		WHERE acc.id = "'.$idpedidos.'"
		');

		$row1 = $query->result();
		$row = $row1[0];	
		
	   	$items = $this->db->get_where('pedidos_detalle', array('id_pedido' => $idpedidos));

	   	$data = array();

	   	foreach($items->result() as $item){
			$this->db->where('id', $item->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			$item->nom_producto = $producto->nombre;
			$item->precio = $item->precio;
			$item->total = $item->total;
			$item->iva = $item->iva;
			$item->neto = $item->neto;
			$item->descuento = $item->descuento;	
			$data[] = $item;
		}

	   	$resp['success'] = true;
        $resp['data'] = $data;
        echo json_encode($resp);
	}

	public function exportPDFCaja(){
		$idpedidos = $this->input->get('idpedidos');
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, v.id as id_vendedor, c.direccion as direccion,
		c.id_pago as id_pago, suc.direccion as direccion_sucursal, ciu.nombre as ciudad, com.nombre as comuna, cor.nombre as nom_documento, cod.nombre as nom_giro, op.observaciones as observa  FROM pedidos acc
		left join correlativos cor on (acc.tip_documento = cor.id)
		left join clientes c on (acc.id_cliente = c.id)
		left join vendedores v on (acc.id_vendedor = v.id)
		left join observacion_pedidos op on (acc.num_pedido = op.num_pedidos)
		left join clientes_sucursales suc on (acc.id_sucursal = suc.id)
		left join comuna com on (suc.id_comuna = com.id)
		left join ciudad ciu on (suc.id_ciudad = ciu.id)
		left join cod_activ_econ cod on (c.id_giro = cod.id)
		WHERE acc.id = "'.$idpedidos.'"
		');
		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		//items
		$items = $this->db->get_where('pedidos_detalle', array('id_pedido' => $idpedidos));
		//variables generales
		$codigo = $row->num_pedido;
		$nombre_contacto = $row->nom_cliente;
		$hora = $row->horaelabora;
		$vendedor = $row->nom_vendedor;
		$observacion = $row->observa;
		$fecha = $row->fecha_doc;
		$abono= $row->abono;
		$datetime = DateTime::createFromFormat('Y-m-d', $fecha);
		$fecha = $datetime->format('d/m/Y');
		$fechaelabora = $row->fecha_elabora;
		//$datetime = DateTime::createFromFormat('Y-m-d', $fechadespacho);
		$fechaelabora = $datetime->format('d/m/Y');
		$totaliva = 0;
		$neto = ($row->total / 1.19);
		$iva = ($row->total - $neto);
		$subtotal = ($row->total + $row->descuento);

		if($row->direccion_sucursal == " "){

			$direccion = $row->direccion;
			
		}else{

			$direccion = $row->direccion_sucursal;
			
		};
		$saldo=$subtotal - $abono;

		$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Pedidos pdf</title>
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
		   <td width="197px"><img src="http://localhost/Gotru_web/Infosys_web/resources/images/logo_empresa.png" width="150" height="136" /></td>
		    <td width="493px" style="font-size: 14px;text-align:center;vertical-align:text-top"	>
		    <p>GOTRU ALIMENTOS SPA.</p>
		    <p>RUT:78.549.450-4</p>
		    <p>8 ORIENTE, TALCA</p>
		    <p>Fonos: </p>
		    <p>http://www.gotru.cl</p>
		    </td>
		    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
		          <p>pedidos N°: '.$codigo.'</p>
		          <!--p>&nbsp;</p-->
		          <p>FECHA EMISION : '.$fecha.'</p>
		          <!--p>&nbsp;</p-->		         
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>PEDIDOS</h1></td>
		  </tr>
		  <tr>
		    <td colspan="3" width="987px" >
		    	<table width="987px" border="0">
		    		<tr>
		    			<td width="197px">Sr.(es):</td>
		    			<td width="395px">'. $row->nombre_cliente.'</td>
		    			<td width="197px">Rut:</td>
		    			<td width="197px">'. number_format(substr($row->rut_cliente, 0, strlen($row->rut_cliente) - 1),0,".",".")."-".substr($row->rut_cliente,-1).'</td>
		    		</tr>
		    		<tr>
		    		<td width="197px">TELEFONO:</td>
		    		<td width="197px">'.$row->telefono.'</td>
		    		<td width="197px">VENDEDOR:</td>
		    		<td width="197px">'.$row->nom_vendedor.'</td>
		    		</tr>
		    		
		    		<tr>
		    			<td width="197px">Fecha Elaboracion:</td>
		    			<td width="395px">'. $fechaelabora.'</td>
		    			<td width="197px">Hora Elaboracion:</td>
		    			<td width="197px">'.$hora.'</td>
		    		</tr>
		    		
		    	</table>
			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		      <tr>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="395px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Descripci&oacute;n</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Precio/Unidad</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Dcto</td>
		       
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Neto</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Total</td>
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
			<td style="text-align:right">'.$v->cantidad.'&nbsp;&nbsp;</td>			
			<td style="text-align:left">'.$producto->nombre.'</td>			
			<td align="right">$ '.number_format($v->precio, 3, '.', ',').'</td>
			<td align="right">$ '.number_format($v->descuento, 0, '.', ',').'</td>
			<td align="right">$ '.number_format($v->neto, 0, '.', ',').'</td>			
			<td align="right">$ '.number_format($v->total, 0, '.', ',').'</td>
			</tr>';
			
			//}
			$i++;
		}

		// RELLENA ESPACIO
		/*while($i < 30){
			$html .= '<tr><td colspan="5">&nbsp;</td></tr>';
			$i++;
		}
*/

		$html .= '<tr><td colspan="5">&nbsp;</td></tr></table></td>
		  </tr>
		  
		  
		  <tr>
		  	<td colspan="2" rowspan="6" style="font-size: 12px;border-bottom:1pt solid black;border-top:1pt solid black;border-left:1pt solid black;border-right:1pt solid black;text-align:left;">Observaciones : '.$observacion.'</td>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Total</td>
						<td width="146px" style="text-align:right;">$ '. number_format($subtotal, 0, '.', ',') .'</td>
					</tr>
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Abono</td>
						<td width="146px" style="text-align:right;">$ '. number_format($abono, 0, '.', ',') .'</td>
					</tr>
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Saldo</td>
						<td width="146px" style="text-align:right;">$ '. number_format($saldo, 0, '.', ',') .'</td>
					</tr>
				</table>
		  	</td>
		  </tr>
		  <tr>
		  	<td>
				
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				
		  	</td>		  
		  </tr>	
		  <tr>
		  		  
		  </tr>
		  <tr>
		  			  
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
		$idpedidos = $this->input->get('idpedidos');
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, v.id as id_vendedor, c.direccion as direccion, c.id_pago as id_pago, suc.direccion as direccion_sucursal, ciu.nombre as ciudad, com.nombre as comuna, cor.nombre as nom_documento, cod.nombre as nom_giro, op.observaciones as observa, cu.nombre as ciudad_suc, cm.nombre as comuna_suc FROM pedidos acc
		left join correlativos cor on (acc.tip_documento = cor.id)
		left join clientes c on (acc.id_cliente = c.id)
		left join vendedores v on (acc.id_vendedor = v.id)
		left join observacion_pedidos op on (acc.num_pedido = op.num_pedidos)
		left join clientes_sucursales suc on (acc.id_sucursal = suc.id)
		left join comuna com on (c.id_comuna = com.id)
		left join ciudad ciu on (c.id_ciudad = ciu.id)
		left join comuna cm on (suc.id_comuna = cm.id)
		left join ciudad cu on (suc.id_ciudad = cu.id)
		left join cod_activ_econ cod on (c.id_giro = cod.id)
		WHERE acc.id = "'.$idpedidos.'"
		');
		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		//items
		$items = $this->db->get_where('pedidos_detalle', array('id_pedido' => $idpedidos));
		//variables generales
		$codigo = $row->num_pedido;
		$nombre_contacto = $row->nombre_cliente;
		$vendedor = $row->nom_vendedor;
		$observacion = $row->observa;
		$fecha = $row->fecha_doc;
		$totaliva = 0;
		$neto = ($row->total / 1.19);
		$iva = ($row->total - $neto);
		$subtotal = ($row->total + $row->descuento);

		if(!$row->id_sucursal){
			$direccion = $row->direccion;
			$comuna = $row->comuna;
			$ciudad = $row->ciudad;			
		}else{

			$direccion = $row->direccion_sucursal;
			$comuna = $row->comuna_suc;
			$ciudad = $row->ciudad_suc;
			
		};

		$this->load->model('facturaelectronica');
      $empresa = $this->facturaelectronica->get_empresa();

      $logo =  PATH_FILES."facturacion_electronica/images/".$empresa->logo; 



		$html = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Pedidos</title>
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
        <p>Fonos: ' . $empresa->fono . '</p>
		    <p>http://www.lircay.cl</p>
		    </td>
	    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
	          <p>pedidos N°: '.$codigo.'</p>
	          <!--p>&nbsp;</p-->
	          <p>FECHA EMISION : '.$fecha.'</p>
	          <!--p>&nbsp;</p-->		         
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>PEDIDOS</h1></td>
		  </tr>
		  <tr>
		    <td colspan="3" width="987px" >
		    	<table width="987px" border="0">
		    		<tr>
		    			<td width="197px">Sr.(es):</td>
		    			<td width="395px">'. $row->nombre_cliente.'</td>
		    			<td width="197px">Rut:</td>
		    			<td width="197px">'. number_format(substr($row->rut_cliente, 0, strlen($row->rut_cliente) - 1),0,".",".")."-".substr($row->rut_cliente,-1).'</td>
		    		</tr>
		    		<tr>
		    		<td width="197px">TELEFONO:</td>
		    		<td width="197px">'.$row->telefono.'</td>
		    		<td width="197px">VENDEDOR:</td>
		    		<td width="197px">'.$row->nom_vendedor.'</td>
		    		</tr>
		    		
		    		<tr>
		    			<td width="197px">Direccion:</td>
		    			<td width="495px">'.$direccion.'</td>		    			
		    		</tr>
		    		<tr>
		    			<td width="197px">Ciudad</td>
		    			<td width="395px">'.$ciudad.'</td>
		    			<td width="197px">Comuna</td>
		    			<td width="197px">'.$comuna.'</td>
		    		</tr>
		    		
		    	</table>
			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		      <tr>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="395px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Descripci&oacute;n</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Precio/Unidad</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Dcto</td>
		       
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Neto</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Total</td>
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
			<td style="text-align:right">'.$v->cantidad.'&nbsp;&nbsp;</td>			
			<td style="text-align:left">'.$producto->nombre.'</td>			
			<td align="right">$ '.number_format($v->precio, 3, '.', ',').'</td>
			<td align="right">$ '.number_format($v->descuento, 0, '.', ',').'</td>
			<td align="right">$ '.number_format($v->neto, 0, '.', ',').'</td>			
			<td align="right">$ '.number_format($v->total, 0, '.', ',').'</td>
			</tr>';
			
			//}
			$i++;
		}

		// RELLENA ESPACIO
		while($i < 30){
			$html .= '<tr><td colspan="5">&nbsp;</td></tr>';
			$i++;
		}


		$html .= '<tr><td colspan="5">&nbsp;</td></tr></table></td>
		  </tr>
		  <tr>
		  	<td colspan="3" style="border-top:1pt solid black;text-align:center;"><p><b>VALORES EN DETALLE NETOS+IVA</b></p></td>
		  </tr>
		  
		  <tr>
		  	<td colspan="2" rowspan="6" style="font-size: 12px;border-bottom:1pt solid black;border-top:1pt solid black;border-left:1pt solid black;border-right:1pt solid black;text-align:left;">'.$observacion.'</td>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Pretotal</td>
						<td width="146px" style="text-align:right;">$ '. number_format($subtotal, 0, '.', ',') .'</td>
					</tr>
				</table>
		  	</td>
		  </tr>
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Descuento</td>
						<td width="146px" style="text-align:right;">$ '. number_format($row->desc, 0, ',', '.') .'</td>
					</tr>
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Neto</td>
						<td width="146px" style="text-align:right;">$ '.number_format($neto, 0, '.', ',').'</td>
					</tr>
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">IVA</td>
						<td width="146px" style="text-align:right;">$ '.number_format($iva, 0, '.', ',').'</td>
					</tr>
				</table>
		  	</td>		  
		  </tr>
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Total</td>
						<td width="146px" style="text-align:right;">$ '. number_format($row->total, 0, '.', ',') .'</td>
					</tr>
				</table>
		  	</td>		  
		  </tr>
		  <tr>
		  	<td>&nbsp;</td>		  
		  </tr>
		  <tr>
		  	<td>&nbsp;</td>		  
		  </tr>		  		  		  	  
		  <tr>
		    <td colspan="2" style="text-align:right;font-style: italic;"><b>EL SERVICIO MARCA LA DIFERENCIA!!!</b></td>
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

	public function save(){

		$resp = array();
		$idcliente = $this->input->post('idcliente');
		$nomcliente = $this->input->post('nomcliente');
		$telefono = $this->input->post('telefono');
		$numeropedido = $this->input->post('numeropedido');
		$idpago = $this->input->post('idpago');
		$idbodega = $this->input->post('idbodega');

		$fechapedidos = $this->input->post('fechapedido');
		//list($dia, $mes, $anio) = explode("/",$fechapedidos);
		//$fechapedidos = $anio ."-". $mes ."-". $dia;

		$fechadoc = $this->input->post('fechadocum');
		//list($dia, $mes, $anio) = explode("/",$fechadoc);
		//$fechadoc = $anio ."-". $mes ."-". $dia;

	    $vendedor = $this->input->post('vendedor');
		$sucursal = $this->input->post('sucursal');
		$items = json_decode($this->input->post('items'));
		$neto = $this->input->post('neto');
		$desc = $this->input->post('descuento');
		$fiva = $this->input->post('iva');
		$fafecto = $this->input->post('afecto');
		$ftotal = $this->input->post('total');
		$idobserva = $this->input->post('idobserva');
						
		if ($desc){			
			$desc = $this->input->post('descuento');
		}else{				
			$desc = 0;
		};
		
		$pedidos = array(
	        'num_pedido' => $numeropedido,
	        'fecha_doc' => $fechadoc ,
	        'id_cliente' => $idcliente,
	        'nombre_cliente' => strtoupper($nomcliente),
	        'telefono' => $telefono,
	        'id_sucursal' => $sucursal,
	        'id_pago' => $idpago,
	        'id_bodega' => $idbodega,
	        'id_vendedor' => $vendedor,
	        'fecha_pedido' => $fechapedidos,
	        'neto' => $neto,
	        'iva' => $fiva,
	        'id_pago' => $idpago,
	        'descuento' => $desc,
	        'total' => $ftotal,
	        'id_observa' => $idobserva,
	        'estado' => 1
		);

		$this->db->insert('pedidos', $pedidos); 
		$idpedidos = $this->db->insert_id();

		$secuencia = 0;

		foreach($items as $v){

			if (!$v->descuento){

				$v->id_descuento=0;
			   		
			
			};
			$secuencia = $secuencia + 1;
			$pedidos_detalle = array(
		        'id_producto' => $v->id_producto,
		        'id_pedido' => $idpedidos,
		        'id_bodega' => $v->id_bodega,
		        'id_descuento' => $v->id_descuento,
		        'num_pedido' => $numeropedido,
		        'precio' => $v->precio,
		        'neto' => $v->neto,
		        'cantidad' => $v->cantidad,
		        'neto' => $v->neto,
		        'descuento' => $v->dcto,
		        'iva' => $v->iva,
		        'total' => $v->total,
		        'secuencia' => $secuencia,
		        'fecha' => $fechapedidos
			);

		$producto = $v->id;

		$this->db->insert('pedidos_detalle', $pedidos_detalle);

		$general = $this->db->query('SELECT * FROM pedidos_general WHERE id_producto="'.$producto.'"
    	AND fecha_produccion = "'.$fechapedidos.'"');	

		if($general->num_rows()>0){
		 		$row = $general->first_row();
		 	    $id = ($row->id);
		 	    $cantidad = ($row->cantidad + ($v->cantidad) );

		 	    $this->db->where('id', $v->id_producto);
				$producto = $this->db->get("productos");	
				$producto = $producto->result();
				$producto = $producto[0];
				
			    $pedidos_update = array(
		        'cantidad' => $cantidad,
		        );


				$this->db->where('id', $id);
				$this->db->update('pedidos_general', $pedidos_update);
	    }else{

				$cantidad = ($v->cantidad);
				$this->db->where('id', $v->id_producto);
				$producto = $this->db->get("productos");	
				$producto = $producto->result();
				$producto = $producto[0];
				
				$pedidos_general = array(
				'id_producto' => $v->id_producto,
				'cantidad' => $v->cantidad,
				'fecha_produccion' => $fechapedidos,
				'fecha' => $fechapedidos,
				);

				$this->db->insert('pedidos_general', $pedidos_general);

	    };

    	
		
    	
		};

		
        $resp['success'] = true;
		$resp['idpedidos'] = $idpedidos;

		$this->Bitacora->logger("I", 'pedidos', $idpedidos);
		$this->Bitacora->logger("I", 'pedidos_detalle', $idpedidos);
        

        echo json_encode($resp);
	}

	public function save2(){

		$resp = array();
		$idcliente = $this->input->post('idcliente');
		$nomcliente = $this->input->post('nomcliente');
		$telefono = $this->input->post('telefono');
        $idpedidos = $this->input->post('idpedido');
        $abono = $this->input->post('abono');
		$numeropedido = $this->input->post('numeropedido');
		$idtipo = $this->input->post('idtipo');
		$idpago = $this->input->post('idpago');
		$horaelab = $this->input->post('horaelab');
		$idhoraelab = $this->input->post('idhoraelab');
		$idbodega = $this->input->post('idbodega');
		$idtipopedido = $this->input->post('idtipopedido');
		$fechapedidos = $this->input->post('fechapedidos');
		$fechaelaboracion = $this->input->post('fechaelaboracion');
	    $fechadoc = $this->input->post('fechadocum');
	    $horapedido = $this->input->post('horapedido');
	    $fechadespacho = $this->input->post('fechadespacho');
	    $horadespacho = $this->input->post('horadespacho');	    
		$vendedor = $this->input->post('vendedor');
		$sucursal = $this->input->post('sucursal');
		$items = json_decode($this->input->post('items'));
		$neto = $this->input->post('neto');
		$desc = $this->input->post('descuento');
		$fiva = $this->input->post('iva');
		$fafecto = $this->input->post('afecto');
		$ftotal = $this->input->post('total');
		$idobserva = $this->input->post('idobserva');					
						
		if ($desc){			
			$desc = $this->input->post('descuento');
		}else{				
			$desc = 0;
		};

		$query = $this->db->query('DELETE FROM pedidos_detalle WHERE id_pedido = "'.$idpedidos.'"');

		$secuencia = 0;

		foreach($items as $v){
			if (!$v->descuento){
				$v->id_descuento=0;
			};
			$secuencia = $secuencia + 1;
			$pedidos_detalle = array(
		        'id_producto' => $v->id_producto,
		        'id_pedido' => $idpedidos,
		        'id_descuento' => $v->id_descuento,
		        'id_bodega' => $v->id_bodega,
		        'num_pedido' => $numeropedido,
		        'precio' => $v->precio,
		        'neto' => $v->neto,
		        'cantidad' => $v->cantidad,
		        'neto' => $v->neto,
		        'descuento' => $v->descuento,
		        'iva' => $v->iva,
		        'total' => $v->total,
		        'secuencia' => $secuencia,
		        'fecha' => $fechapedidos

			);

		$producto = $v->id_producto;

	    $this->db->insert('pedidos_detalle', $pedidos_detalle);
	    
    	$general = $this->db->query('SELECT * FROM pedidos_general WHERE id_producto="'.$producto.'"
    	AND fecha_produccion = "'.$fechaelaboracion.'" AND idhora = "'.$idhoraelab.'"');	

	    if($general->num_rows()>0){

		 		$row = $general->first_row();
		 	    $id = ($row->id);
		 	    $cantidad = ($row->cantidad + ($v->cantidad) );

		 	    $this->db->where('id', $v->id_producto);
				$producto = $this->db->get("productos");	
				$producto = $producto->result();
				$producto = $producto[0];
				$conversion = $producto->equiv_pro;
		 	    $unidadfisica = ($cantidad * $conversion);
                $idsubfamilia = $producto->id_subfamilia;

			    $pedidos_update = array(
		        'cantidad' => $cantidad,
		        'conversion' => $conversion,
		        'unidadfisica' => $unidadfisica
				);

				$this->db->where('id', $id);
				$this->db->update('pedidos_general', $pedidos_update);
	    }else{

				$cantidad = ($v->cantidad);
				$this->db->where('id', $v->id_producto);
				$producto = $this->db->get("productos");	
				$producto = $producto->result();
				$producto = $producto[0];
				$conversion = $producto->equiv_pro;
				$unidadfisica = ($cantidad * $conversion);
				$idsubfamilia = $producto->id_subfamilia;

				$pedidos_general = array(
				'id_producto' => $v->id_producto,
				'cantidad' => $v->cantidad,
				'fecha_produccion' => $fechaelaboracion,
				'fecha' => $fechapedidos,
				'conversion' => $conversion,
				'unidadfisica' => $unidadfisica,
				'idsubfamilia' => $idsubfamilia,
				'idhora' => $idhoraelab

				);

				$this->db->insert('pedidos_general', $pedidos_general);

	    };
		
    	$resp['detalle'] = false;
    	
		}

		if ($desc){			
			$desc = $this->input->post('descuento');
		}else{				
			$desc = 0;
		};

		if(!$horapedido or !$horadespacho){

			$pedidos = array(
	        'num_pedido' => $numeropedido,
	        'tip_documento' => $idtipo,
	        'fecha_doc' => $fechadoc ,
	        'abono' => $abono,
	        'id_cliente' => $idcliente,
	        'nombre_cliente' => strtoupper($nomcliente),
	        'telefono' => $telefono,
	        'id_pago' => $idpago,
	        'id_bodega' => $idbodega,
	        'id_sucursal' => $sucursal,
	        'id_vendedor' => $vendedor,
	        'fecha_pedido' => $fechapedidos,
	        'fecha_despacho' => $fechadespacho,
	        'fecha_elabora' => $fechaelaboracion,
	        'id_tipopedido' => $idtipopedido,
	        'neto' => $neto,
	        'id_pago' => $idpago,
	        'descuento' => $desc,
	        'total' => $ftotal,
	        'id_observa' => $idobserva
			);			

		}else{

			$pedidos = array(
	        'num_pedido' => $numeropedido,
	        'tip_documento' => $idtipo,
	        'fecha_doc' => $fechadoc ,
	        'id_cliente' => $idcliente,
	        'nombre_cliente' => strtoupper($nomcliente),
	        'abono' => $abono,
	        'telefono' => $telefono,
	        'id_pago' => $idpago,
	        'id_sucursal' => $sucursal,
	        'id_vendedor' => $vendedor,
	        'id_bodega' => $idbodega,
	        'fecha_pedido' => $fechapedidos,
	        'fecha_elabora' => $fechaelaboracion,
	        'hora_pedido' => $horapedido,
	        'fecha_despacho' => $fechadespacho,
	        'hora_despacho' => $horadespacho,
	        'id_tipopedido' => $idtipopedido,
	        'neto' => $neto,
	        'id_pago' => $idpago,
	        'descuento' => $desc,
	        'total' => $ftotal,
	        'id_observa' => $idobserva
			);		

		};
		
		$this->db->where('id', $idpedidos);
		$this->db->update('pedidos', $pedidos);
		
        $resp['success'] = true;

		$resp['idpedidos'] = $idpedidos;

		$this->Bitacora->logger("I", 'pedidos', $idpedidos);
		$this->Bitacora->logger("I", 'pedidos_detalle', $idpedidos);  

        echo json_encode($resp);
	}

	public function update(){
		$resp = array();

		$data = json_decode($this->input->post('data'));
		$id = $data->id;
		$data = array(
	        'num_ticket' => $data->num_ticket,
	        'fecha_venta' => $data->fecha_venta,
	        'id_cliente' => $data->id_cliente,
	        'nombre_cliente' => $data->nombre_cliente,
	        'telefono' => $data->telefono,
	        'id_sucursal' => $sucursal,
	        'id_vendedor' => $data->id_vendedor,
	        'neto' => $data->neto,
	        'desc' => $data->desc,
	        'total' => $data->total
	    );
		$this->db->where('id', $id);
		
		$this->db->update('pedidos', $data); 

        $resp['success'] = true;
        $this->Bitacora->logger("M", 'pedidos', $id);


        echo json_encode($resp);

	}

	public function getAllCaja(){
		
		$resp = array();

        $start = $this->input->post('start');
        $limit = $this->input->post('limit');
        $opcion = $this->input->post('opcion');
        $nombres = $this->input->post('nombre');
        $tipo = $this->input->post('tipo');
        $estado = "";
        $tpedido = 3;

        //$countAll = $this->db->count_all_results("pedidos");

		if($opcion == "Rut"){

			$data = array();		
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE c.rut = "'.$nombres.'" and acc.estado = "'.$estado.'"
			order by acc.id desc');

		    $total = 0;

			  foreach ($query->result() as $row)
				{
					$total = $total +1;
				
				}

				$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE c.rut = "'.$nombres.'" and acc.estado = "'.$estado.'"
			order by acc.id desc			
			limit '.$start.', '.$limit.'');

	    }else if($opcion == "Nombre"){

	    	
			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "and acc.nombre_cliente like '%".$nombre."%' ";
	        }

	        $data = array();	        	    	
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.estado = "'.$estado.'" ' . $sql_nombre . ' 
			order by acc.id desc'
						
			);

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.estado = "'.$estado.'" ' . $sql_nombre . ' order by acc.id desc
			limit '.$start.', '.$limit.'');
	 
		}else if($opcion == "Todos"){

			
			$data = array();
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.estado = "'.$estado.'"
			order by acc.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.estado = "'.$estado.'"
			order by acc.id desc
			limit '.$start.', '.$limit.''	
			
			);	

		}else if($opcion == "Numero"){

			
			$data = array();

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.num_pedido =  "'.$nombres.'" and acc.estado = "'.$estado.'"
			order by acc.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.num_pedido =  "'.$nombres.'" and acc.estado = "'.$estado.'"
			order by acc.id desc');

		}else{
			
			if ($tipo){
				$data = array();
				$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
				WHERE acc.estado = "'.$estado.'"');

				$total = 0;

			  foreach ($query->result() as $row)
				{
					$total = $total +1;
				
				}

				$countAll = $total;

				$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
				WHERE acc.estado = "'.$estado.'"
				limit '.$start.', '.$limit.'');
		}else{

			$data = array();
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.estado = "'.$estado.'" ');

			$total = 0;

			foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.estado = "'.$estado.'"			
			limit '.$start.', '.$limit.'');


		}
		}

		foreach ($query->result() as $row)
		{
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

	
	public function getAll(){
		
		$resp = array();

        $start = $this->input->post('start');
        $limit = $this->input->post('limit');
        $opcion = $this->input->post('opcion');
        $nombres = $this->input->post('nombre');
        $tipo = $this->input->post('tipo');
        $estado = $this->input->post('estado');
        if(!$estado){
        	$opcion = "Todos";
        };

		
        //$countAll = $this->db->count_all_results("pedidos");

		if($opcion == "Rut"){

			if ($estado == 1){

			$data = array();		
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE c.rut = "'.$nombres.'" 
			order by acc.id desc');

		    $total = 0;

			  foreach ($query->result() as $row)
				{
					$total = $total +1;
				
				}

				$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE c.rut = "'.$nombres.'" order by acc.id desc			
			limit '.$start.', '.$limit.'');

		}else{

			$data = array();		
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE c.rut = "'.$nombres.'" and acc.estado = "'.$estado.'"
			order by acc.id desc');

		    $total = 0;

			  foreach ($query->result() as $row)
				{
					$total = $total +1;
				
				}

				$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE c.rut = "'.$nombres.'" and acc.estado = "'.$estado.'" order by acc.id desc			
			limit '.$start.', '.$limit.'');

		};

	    }else if($opcion == "Nombre"){

	    	if ($estado == 1){

	    	$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "acc.nombre_cliente like '%".$nombre."%' ";
	        }

	        $data = array();	        	    	
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE ' . $sql_nombre . ' ');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE ' . $sql_nombre . ' order by acc.id desc
			limit '.$start.', '.$limit.'');
	    		


	    	}else{

	    	
			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "and acc.nombre_cliente like '%".$nombre."%' ";
	        }

	        $data = array();	        	    	
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.estado = "'.$estado.'" ' . $sql_nombre . ' 
			order by acc.id desc'
						
			);

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.estado = "'.$estado.'" ' . $sql_nombre . ' order by acc.id desc
			limit '.$start.', '.$limit.'');

		    };
	 
		}else if($opcion == "Todos"){

			if ($estado == 1){

			
			$data = array();
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			order by acc.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			order by acc.id desc
			limit '.$start.', '.$limit.''	
			
			);

			};
		}else if($opcion == "Numero"){

			if ($estado == 1){

			
			$data = array();

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.num_pedido =  "'.$nombres.'"
			order by acc.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.num_pedido =  "'.$nombres.'" 
			order by acc.id desc');

			}else{

				$data = array();

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.num_pedido =  "'.$nombres.'" and acc.estado = "'.$estado.'"
			order by acc.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.num_pedido =  "'.$nombres.'" and acc.estado = "'.$estado.'"
			order by acc.id desc');
				
			}

		};
			foreach ($query->result() as $row)
		{
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

	public function exportarPdfinformeproduccion() {
            
		$fecha = $this->input->get('fecha');
		list($dia, $mes, $anio) = explode("/",$fecha);
		$fecha2 = $anio ."-". $mes ."-". $dia;
		$fecha3= $dia ."/". $mes ."/". $anio;
		$doc1="";
		$b=0;
		$pag=1;
		$fecha4 = date('d/m/Y');
		
		$this->load->database();
		$pag=1;

		$pedidos = array(	        
	        'estado' => 2
			);		

			
		$this->db->where('fecha_doc', $fecha2);
		$this->db->update('pedidos', $pedidos);
		
		$query = $this->db->query('SELECT * FROM pedidos_general WHERE fecha = "'.$fecha2.'" ');


		$this->load->model('facturaelectronica');
      $empresa = $this->facturaelectronica->get_empresa();

      $logo =  PATH_FILES."facturacion_electronica/images/".$empresa->logo; 
      

        $header = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>INFORME DE PRODUCCION</title>
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
        <p>Fonos: ' . $empresa->fono . '</p>
		    <p>http://www.lircay.cl</p>
		    </td>
		    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top">
		    <p>FECHA : '.$fecha4.'</p>
		    </td>
		  </tr>';              
              
		  $header .= '<tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h2>Listado de Produccion</h2></td>
		  </tr>
		  <tr>
			<td>HORARIO : '.$hora.'</td>
			<td>FECHA PRODUCCION : '.$fecha3.'</td>
		  </tr>
		  <tr>
			
		  </tr>
			<tr><td colspan="3">&nbsp;</td></tr>		  
			</table>';     
		      $i = 0;
              //$body_detail = '';
              $users = $query->result_array();
             
			  $header .= '
	    	<table width="987px" cellspacing="0" cellpadding="0" border="0">
	      <tr>
	        <td width="160"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;font-size:12px" ><h2>CODIGO</h2></td>
	        <td width="240px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;font-size:12px" ><h2>DESCRIPCION</h2></td>
	        <td width="487px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;font-size:12px" ><h2>CANTIDAD</h2></td>        	        	             
	       </tr>';	
	       
		        $a="ok";
				$array_detail = array();
			  
			foreach($users as $v){
							
				$this->db->where('id', $v['id_producto']);
				$producto = $this->db->get("productos");	
				$producto = $producto->result();
				$producto = $producto[0];
				$codigo = $producto->codigo;
		 	    $descripcion = $producto->nombre;

		 	    $body_detail = "";	
	          		
			if ($a=="ok"){
				$a="no";

				$body_detail .= '
				<tr>				
				<td width="60px" style="text-align:center;font-size:12px"><h2>'.$codigo.'</h2></td>	
				<td width="440px" style="text-align:rigth;font-size:12px"><h2>'.$descripcion.'</h2></td>
				<td width="287px" style="text-align:center;font-size:12px"><h2>'.$v['cantidad'].'</h2></td>				
				</tr>
				';				

			}else{

		    $body_detail .= '
				<tr>				
				<td width="60px" style="text-align:center;font-size:12px"><h2>'.$codigo.'</h2></td>	
				<td width="440px" style="text-align:rigth;font-size:12px"><h2>'.$descripcion.'</h2></td>
				<td width="287px" style="text-align:center;font-size:12px"><h2>'.$v['cantidad'].'</h2></td>				
				</tr>
				';


			};				
		
				$array_detail[] = $body_detail;
				$i++;
     		};

     		


     $body_totales = '<table width="987px" cellspacing="0" cellpadding="0" border="0"><tr><td colspan="2">&nbsp;</td></tr><tr>
		<td  colspan="13" style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b></b></td>
		</tr>';
		$footer = "";

		$fin_tabla = "</table>
		</body>
		</html>";
	    
	   	              
        $this->load->library("mpdf");
			
			$this->mpdf->mPDF(
				'',    // mode - default ''
				'',    // format - A4, for example, default ''
				6,     // font size - default 0
				'',    // default font family
				5,    // margin_left
				5,    // margin right
				16,    // margin top
				16,    // margin bottom
				9,     // margin header
				9,     // margin footer
				'L'    // L - landscape, P - portrait
				);  

			$cantidad_hoja = 50;
			$fila = 1;
			$this->mpdf->SetHeader('Gotru - Informe Produccion');
			$this->mpdf->setFooter('{PAGENO}');					
			foreach ($array_detail as $detail) {
				if($fila == 1){
					$this->mpdf->WriteHTML($header);		
					//echo $header.$header2.$body_header;
				};

				$this->mpdf->WriteHTML($detail);
				//echo $detail;
				//exit;

				if(($fila % $cantidad_hoja) == 0 ){  #LLEVA 30 LINEAS EN LA HOJA
						$this->mpdf->WriteHTML($fin_tabla);					
					//echo $fin_tabla;
						$fila = 0;						
						$this->mpdf->AddPage();
				};		
				//echo $fila."<br>";
				$fila++;
				$pag++;
			};
			$this->mpdf->WriteHTML($fin_tabla);
			//echo $body_totales.$footer.$fin_tabla; exit;
			$this->mpdf->WriteHTML($body_totales.$footer.$fin_tabla);
			//echo $html; exit;
			//exit;
			//$this->mpdf->AddPage();
			//$this->mpdf->WriteHTML($html2);
			$this->mpdf->Output("Informe_produccion.pdf", "I");

			exit; 
		
        }
}
