<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->database();
	}



	public function autoriza_pedido($idpedido){

				header("Access-Control-Allow-Origin: *");
				$this->db->select('id, id_producto, cantidad')
						 ->from('pedidos_detalle')
						 ->where('id_pedido',$idpedido);


			   $query = $this->db->get();		
			   $detalle_pedido = $query->result();

			   $cantidad_producto = 0;
			   $idproducto = 0;
			   $pedidocompleto = true;
			   foreach ($detalle_pedido as $detalle) {

					$cantidad_producto = $detalle->cantidad;
			   		$idproducto = $detalle->id_producto;
			   
				   	$this->db->where('id', $idproducto);
					$producto = $this->db->get("productos");	
					$producto = $producto->result();
					$producto = $producto[0];


					$existe_stock = true;
					//var_dump($cantidad_producto);
					//var_dump($producto->stock);
					if($cantidad_producto > $producto->stock){
						$existe_stock = false;
					}

					$existe_stock = false; //forzamos a que actúe como si no tuviera stock

					if($existe_stock){

							$estado_nuevo_detalle = 5;

					}else{
							$pedidocompleto = false;
							$estado_nuevo_detalle = 2;
					}



					$pedidos_detalle_log = array(
												'idproductodetalle' => $detalle->id,
												'idestado' => $estado_nuevo_detalle,
												'fecha' => date('Y-m-d H:i:s')
											);
					$this->db->insert('pedidos_detalle_log_estados', $pedidos_detalle_log); 						


				
					$this->db->where('id', $detalle->id);
					$this->db->update('pedidos_detalle', array('idestadoproducto' => $estado_nuevo_detalle));			




			   }







				



				/*****************************************************/


				$estado_nuevo = 7;

				$pedidos = array(
			        'idestadopedido' => $estado_nuevo
					);			

					
				$this->db->where('id', $idpedido);
				$this->db->update('pedidos', $pedidos);			

				$pedidos_log = array(
											'idpedido' => $idpedido,
											'idestado' => $estado_nuevo,
											'fecha' => date('Y-m-d H:i:s')
										);
				$this->db->insert('pedidos_log_estados', $pedidos_log); 


				//var_dump($pedidocompleto); 

				if($pedidocompleto){

						$pedidos = array(
					        'idestadopedido' => 8
							);			

							
						$this->db->where('id', $idpedido);
						$this->db->update('pedidos', $pedidos);			

						$pedidos_log = array(
													'idpedido' => $idpedido,
													'idestado' => 8,
													'fecha' => date('Y-m-d H:i:s')
												);
						$this->db->insert('pedidos_log_estados', $pedidos_log); 

				}





		$result['success'] = true;
		$result['message'] = "Pedido Autorizado";
		echo json_encode($result);
			
	}

	public function eliminapedido(){

		$resp = array();
		$idpedido = $this->input->get('idpedidos');

		$pedidosver = $this->db->query('SELECT id, estado FROM pedidos WHERE id="'.$idpedido.'" ');	

		

		if($pedidosver->num_rows()>0){

			$row = $pedidosver->first_row();
	   		$estado = $row->estado;	
	   		
			if($estado=="1" or $estado=="4"){

				$query = $this->db->query('DELETE FROM pedidos WHERE id = "'.$idpedido.'"');

				$query = $this->db->query('DELETE FROM pedidos_detalle WHERE id_pedido = "'.$idpedido.'"');

				$resp['success'] = true;
	    	
	        }else{
	        	$resp['success'] = false;	        	
	        }
				
		}else{
			$resp['success'] = false;
			
		};

        echo json_encode($resp);
	}

	public function estado(){

		$resp = array();
		$idpedidos = $this->input->get('idpedidos');
		
		$query = $this->db->query('SELECT acc.*, pr.id as id_produccion, pr.num_produccion as num_produccion, pr.fecha_produccion as fecha_inicio,  pr.fecha_termino as fecha_termino, fo.nom_formula as nom_formula, pro.nombre as nom_producto, pe.cantidad as cantidad, pr.cantidad_prod as cantidad_prod, pr.cant_real as cant_real, pr.hora_inicio as hora_inicio, pr.hora_termino as hora_termino FROM pedidos acc
		left join produccion pr on (acc.id = pr.id_pedido)
		left join formula_pedido fo on (acc.id = fo.id)
		left join pedidos_detalle pe on (acc.id = pe.id)
		left join productos pro on (pr.id_producto = pro.id)
		WHERE acc.id = "'.$idpedidos.'"
		');

		$row1 = $query->result();
		$row = $row1[0];
		$row = $query->first_row();

	   	$resp['cliente'] = $row;
	    $resp['success'] = true;
	   
        echo json_encode($resp);
	}

	public function validaformula(){

		$resp = array();
		$valida = array();
		$pedido = $this->input->get('pedido');
		$i=0;

		$items = $this->db->query('SELECT * FROM formula_pedido WHERE id_pedido like "'.$pedido.'"');

		foreach($items->result() as $item){
		
		$existencia = $this->db->query('SELECT id, nombre, stock FROM productos WHERE id="'.$item->id_producto.'" and stock < "'.$item->cantidad.'"');	

		if($existencia->num_rows()>0){
	 		$array_existencia = $existencia->row();
	 		$valida[$i]['id'] = $array_existencia->id;
	 		$valida[$i]['nombre'] = $array_existencia->nombre;
	 		$valida[$i]['stock'] = $array_existencia->stock;
	 		$valida[$i]['pedido'] = $item->cantidad;
	 		$i++;
	 		

	    }

	    if ($i>0){
	    	$resp['success'] = true;	    	
	    }else{
	    	$resp['success'] = false;	    	
	    }
	    

	   }

	    $resp['valida'] = $valida;
	    $resp['data'] = $valida;
		echo json_encode($resp);

	}

	public function formulas(){

		$resp = array();
		$pedido = $this->input->post('pedido');

		if ($pedido){

		$items = $this->db->query('SELECT * FROM formula_pedido WHERE id_pedido like "'.$pedido.'"');

		//$items = $this->db->get_where('pedidos_detalle', array('id_pedido' => $pedido));

	   	$data = array();

	   	foreach($items->result() as $item){
			$this->db->where('id', $item->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			$item->nombre_producto = $producto->nombre;
			$item->codigo = $producto->codigo;
			$data[] = $item;

		}	     	
	    $resp['success'] = true;
        $resp['data'] = $data; 
        echo json_encode($resp);
        }

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
		
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, v.id as id_vendedor, c.direccion as direccion, c.id_pago as id_pago, suc.direccion as direccion_sucursal, ciu.nombre as ciudad, com.nombre as comuna, cor.nombre as nom_documento, cod.nombre as nom_giro,
		f.nombre_formula as nombre_formula, f.id as id_formula, f.cantidad as cantidad_formula FROM pedidos acc
		left join correlativos cor on (acc.tip_documento = cor.id)
		left join clientes c on (acc.id_cliente = c.id)
		left join vendedores v on (acc.id_vendedor = v.id)
		left join clientes_sucursales suc on (acc.id_sucursal = suc.id)
		left join comuna com on (suc.id_comuna = com.id)
		left join formula f on (acc.id_formula = f.id)
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
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, v.id as id_vendedor, c.direccion as direccion, c.id_pago as id_pago, suc.direccion as direccion_sucursal, ciu.nombre as ciudad, com.nombre as comuna, cor.nombre as nom_documento, cod.nombre as nom_giro FROM pedidos acc
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
		
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, v.id as id_vendedor, c.direccion as direccion, c.id_pago as id_pago, suc.direccion as direccion_sucursal, ciu.nombre as ciudad, com.nombre as comuna, cor.nombre as nom_documento, cod.nombre as nom_giro FROM pedidos acc
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
			$data[] = $item;
		}

	   	$resp['success'] = true;
        $resp['data'] = $data;
        echo json_encode($resp);
	}

	
	public function exportPDFOC(){
		$idpedidos = $this->input->get('idpedidos');
		$oc = $this->input->get('oc');




		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, v.id as id_vendedor, cor.nombre as nom_documento, op.observaciones as observa, f.nombre_formula as nombre_formula, v.siglavendedor FROM pedidos acc
		left join correlativos cor on (acc.tip_documento = cor.id)
		left join clientes c on (acc.id_cliente = c.id)
		left join vendedores v on (acc.id_vendedor = v.id)
		left join formula f on (acc.id_formula = f.id)
		left join observacion_pedidos op on (acc.num_pedido = op.num_pedidos)
		WHERE acc.id = "'.$idpedidos.'"
		');
		//cotizacion header
		$row = $query->result();
		$row = $row[0];

		$ordencompraint = $row->ordencompraint;
		$ordencompraarchivo = $row->nomarchivoocint;

		if($ordencompraint == ''){
			$this->db->where('id', $idpedidos);
			$this->db->update('pedidos', array('ordencompraint' => $oc));		

		}

	    // Ruta del directorio donde se guardarán los archivos
	    $directorio = './produccion/ordencompraint/'.$idpedidos.'/';

		if($ordencompraarchivo == ''){
				//items
				$items = $this->db->get_where('pedidos_detalle', array('id_pedido' => $idpedidos));
				
					
				$codigo = $oc;
				$nombre_contacto = $row->nombre_cliente;
				$vendedor = $row->nom_vendedor;
				$observacion = $row->observa;
				$fecha = $row->fecha_doc;
				$fecha_despacho = $row->fecha_despacho;
				$totaliva = 0;
				$neto = ($row->total / 1.19);
				$iva = ($row->total - $neto);
				$subtotal = ($row->total);		

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
				<table width="892px" height="602" border="0">
				  <tr>
				   <td width="197px"><img src="' . $logo . '" width="150" height="136" /></td>
				    <td width="493px" style="font-size: 14px;text-align:center;vertical-align:text-top"	>
				     <p>' . $empresa->razon_social .'</p>
		        <p>RUT:' . number_format($empresa->rut,0,".",".").'-' . $empresa->dv . '</p>
		        <p>' . $empresa->dir_origen . ' - ' . $empresa->comuna_origen . ' - Chile</p>
		        <p>Fonos: ' . $empresa->comuna_origen . '</p>
				    <p>http://www.lircay.cl</p>
				    </td>
			    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
			          <p>ORDEN COMPRA N°: '.$row->siglavendedor.'-'.$codigo.'</p>
			          <!--p>&nbsp;</p-->
			          <p>FECHA EMISION : '.$fecha.'</p>
			          <!--p>&nbsp;</p-->
			          <p>FECHA DESPACHO : '.$fecha_despacho.'</p>
			          <!--p>&nbsp;</p-->		         
					</td>
				  </tr>
				  <tr>
					<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3" width="892px"><h1>ORDEN DE COMPRA</h1></td>
				  </tr>
				  <tr>
				    <td colspan="3" width="892px" >
				    	<table width="987px" border="0">
				    		<tr>
				    			<td width="197px">Sr.(es):</td>
				    			<td width="395px">'. $row->nombre_cliente.'</td>
				    			<td width="197px">Rut:</td>
				    			<td width="197px">'. number_format(substr($row->rut_cliente, 0, strlen($row->rut_cliente) - 1),0,".",".")."-".substr($row->rut_cliente,-1).'</td>
				    		</tr>
				    		<tr>
				    			<td width="197px">VENDEDOR:</td>
				    			<td width="395px">'. $row->nom_vendedor.'</td>
				    		</tr>
				    		<tr>
				    		
				    		</tr>    	
				    		
				    	</table>
					</td>
				  </tr>
				  <tr>
				    <td colspan="3" >
				    	<table width="950px" cellspacing="0" cellpadding="0" >
				      <tr>
				        <td width="100px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Codigo</td>
				        <td width="448px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Descripcion</td>
				        <td width="168px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
				        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Precio</td>
				        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Neto</td>
				        
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
					<td style="text-align:left">'.$producto->codigo.'</td>
					<td style="text-align:left">'.$producto->nombre.'</td>			
					<td align="right">'.number_format($v->cantidad, 2, '.', ',').'</td>
					<td align="right">'.number_format($v->precio, 2, '.', ',').'</td>
					<td align="right">'.number_format($v->neto, 0, '.', ',').'</td>
					
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
				  	
				  </tr>
				  <tr>
				  	<td>
						<table width="296px" border="0">
							<tr>
								<td width="150px" style="font-size: 20px;text-align:left;"></td>
								<td width="146px" style="text-align:right;"></td>
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






				if(!file_exists($directorio) ){
					mkdir($directorio,0777,true);
				}

				$ordencompraarchivo = "OC_{$codigo}.pdf";


				$mpdf->WriteHTML($html);
				$mpdf->Output($directorio.$ordencompraarchivo, "F");

				$this->db->where('id', $idpedidos);
				$this->db->update('pedidos', array('nomarchivoocint' => $ordencompraarchivo));		

		}
		
		$directorio2 = '/../../produccion/ordencompraint/'.$idpedidos.'/';
		//var_dump($directorio2.$ordencompraarchivo); exit;

			$base_path = __DIR__;
			$base_path = str_replace("\\", "/", $base_path);

			$filename = $ordencompraarchivo; /* Note: Always use .pdf at the end. */
			$file = $base_path .$directorio2 .$filename;	
			header('Content-type: application/pdf');
			header('Content-Disposition: inline; filename="' . $filename . '"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize($file));
			header('Accept-Ranges: bytes');

			@readfile($file);


		
		exit;
	}






	public function exportPDFRT(){
		$idregistro = $this->input->get('idregistro');

		//var_dump($idregistro); exit;
	

		$this->db->select("rt.id
							,rt.num_registro
							,rt.fecha_genera
							,count(distinct pg.idguia) AS cantidad",false)
						  ->from('registro_transporte rt')
						  ->join('pedidos_guias pg','rt.id = pg.idregistrotransporte')
						   ->where('rt.id',$idregistro)
						  ->group_by('rt.id')
						  ->group_by('rt.num_registro')
						  ->group_by('rt.fecha_genera')
						  ->order_by('rt.num_registro','desc'); 	                  
		$query = $this->db->get();	


		$registros = $query->row();	

		$this->db->select("rt.id as idregistro
							,rt.num_registro
							,pg.idguia
							,f.num_factura as numguia			
							,c.rut
							,c.nombres
							,c.direccion
							,cf.nombre as nombreclientefinal
							,cf.direccion as direccionclientefinal",false)
						  ->from('registro_transporte rt')
						  ->join('pedidos_guias pg','rt.id = pg.idregistrotransporte')
						  ->join('pedidos p','pg.idpedido = p.id','LEFT')
						  ->join('cliente_final cf','p.idclientefinal = cf.id','LEFT')
						  ->join('factura_clientes f','pg.idguia = f.id')
						  ->join('clientes c','f.id_cliente = c.id')
						  ->where('rt.id',$idregistro); 	                  
		$query = $this->db->get();		


		$registro = $query->result();
		//cotizacion header


		//echo $this->db->last_query(); exit;
		$guias = $query->result();
		//echo '<pre>';
		//var_dump($guias); exit;

			
		//$items2 = $this->db->get_where('formula_pedido', array('id_pedido' => $idpedidos));

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
        <p>Fonos: ' . $empresa->texto_fono . '</p>
		    <p>http://www.lircay.cl</p>
		    </td>
	    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
	          <p>REGISTRO DE TRANSPORTE N°: '.$registros->num_registro.'</p>
	          <!--p>&nbsp;</p-->
	          <p>FECHA REGISTRO : '.$registros->fecha_genera.'</p>
	          <!--p>&nbsp;</p-->
	          <p>CANTIDAD DOCUMENTOS : '.$registros->cantidad.'</p>
	          <!--p>&nbsp;</p-->			         
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>REGISTRO DE TRANSPORTE</h1></td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="750px" cellspacing="0" cellpadding="0" border="1" >
		      <tr>
		        <td width="50px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" rowspan="2"><b>GD</b></td>
		         <td width="180px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" rowspan="2"><b>ORDEN CARGA</b></td>
		         <td width="340px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" colspan="2"><b>DESTINO</b></td>
		         <td width="120px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" colspan="2"><b>TARIFA FLETE</b></td>
		         <td width="60px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" rowspan="2"><b>KILOS</b></td>
		        </tr>
		       <tr>
		        <td width="230px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" ><b>FUNDO</b></td>	
		        <td width="110px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" ><b>TRANSFER.</b></td>	
		        <td width="60px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" ><b>C/DESCARGA</b></td>	
		       	<td width="60px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" ><b>S/DESCARGA</b></td>	
		       			        
		       </tr>';

		$i = 0;
		$max_filas = 20;
		$total_kilos = 0;
		$rut_transportista = '';
		$nombre_transportista = '';
		$empresa_transporte = '';
		$patente_camion = '';
		$patente_carro = '';

		//var_dump($guias); exit;
		foreach($guias as $guia){	

			$idguia = $guia->idguia;
			$this->db->select("of.id
								,of.id_documento
								,of.rut
								,of.nombre
								,of.id_transportista
								,of.pat_camion
								,of.pat_carro
								,of.fono
								,of.destino
								,of.observacion	
								,of.empresatransporte
								,of.transferencia
								,of.tipodescarga",false)
							  ->from('observacion_facturas of')
							  ->where('of.id_documento',$idguia); 	                  
			$query = $this->db->get();		
			$observacion_guia = $query->row();


			$destino = isset($observacion_guia->destino) ? $observacion_guia->destino .'. ' : '';
			$destino .= $guia->nombreclientefinal.' - ' . $guia->direccionclientefinal;
			$transferencia = isset($observacion_guia->transferencia) ? $observacion_guia->transferencia : '';

			if(isset($observacion_guia->tipodescarga)){
					$condescarga = $observacion_guia->tipodescarga == 'CON DESCARGA' ? 'XX' : '';
					$sindescarga = $observacion_guia->tipodescarga == 'SIN DESCARGA' ? 'XX' : '';
			}else{
				if(isset($observacion_guia->transferencia)){
					$condescarga = $observacion_guia->transferencia == 'ENTREGA CLIENTE' ? 'XX' : '';
					$sindescarga = $observacion_guia->transferencia == 'BASE RALICURA' ? 'XX' : '';

				}else{
					$condescarga = '';
					$sindescarga = '';
				}				
			}


			if(isset($observacion_guia->rut)){
				if($rut_transportista == '' && $observacion_guia->rut != ''){

					$rut_completo =  str_replace('.','',$observacion_guia->rut);


					$rut_transportista = number_format(substr($rut_completo,0,strlen($rut_completo)-1),0,'.','.').'-'.substr($rut_completo,-1);
					$nombre_transportista = $observacion_guia->nombre;
					$empresa_transporte = $observacion_guia->empresatransporte;
					$patente_camion = $observacion_guia->pat_camion;
					$patente_carro = $observacion_guia->pat_carro;

				}

			}

			


			$idguia = $guia->idguia;
			$this->db->select("sum(cantidad) as cantidad",false)
							  ->from('detalle_factura_cliente df')
							  ->where('df.id_factura',$idguia); 	                  
			$query = $this->db->get();		
			$cantidad_guia = $query->row();
			$total_kilos += $cantidad_guia->cantidad;

			$html .= '<tr>
			<td style="text-align:left">'.$guia->numguia.'</td>	
			<td style="text-align:left">'.$guia->nombres.'</td>	
			<td style="text-align:left">'.$destino.'</td>
			<td style="text-align:left">'.$transferencia.'</td>		
			<td style="text-align:left">'.$condescarga.'</td>		
			<td style="text-align:left">'.$sindescarga.'</td>	
			<td style="text-align:left">'.number_format($cantidad_guia->cantidad,2,',','.').'</td>					
			</tr>';
			
			//}
			$i++;
		}


		while($i < $max_filas){
			$html .= '<tr>
			<td style="text-align:left">&nbsp;</td>	
			<td style="text-align:left">&nbsp;</td>	
			<td style="text-align:left">&nbsp;</td>
			<td style="text-align:left">&nbsp;</td>		
			<td style="text-align:left">&nbsp;</td>		
			<td style="text-align:left">&nbsp;</td>	
			<td style="text-align:left">&nbsp;</td>					
			</tr>';

			$i++;
		}

			$html .= '<tr>
			<td colspan="6" style="text-align:left"><b>TOTAL KILOS</b></td>	
			<td style="text-align:left">'.number_format($total_kilos,2,',','.').'</td>		
			</tr>';
		       $html .= '
		       </table>
		      </td></tr>';



		   $html .= '<tr>
		    <td colspan="3" >
		    	<table width="1000px" cellspacing="0" cellpadding="0" border="1" >
		    		<tr>
		    		<td width="200px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;"><b>RUT TRANSPORTISTA</b></td>
		    		<td width="200px" style="text-align:left">' . $rut_transportista . '</td>	
		    		<td width="400px" style="text-align:left">&nbsp;</td>
		    		<td width="200px" colspan="2" style="text-align:left">&nbsp;</td>		
		    		</tr>
		    		<tr>
		    		<td width="200px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" ><b>Empresa Transporte <br></b>' . $empresa_transporte . '</td>
		    		<td width="600px" colspan="2" style="text-align:left">' .  $nombre_transportista . '</td>	
		    		<td width="100px" style="text-align:left"><b>PATENTE</b><br>' .  $patente_camion . '</td>	
		    		<td width="100px" style="text-align:left"><b>CARRO</b><br>' . $patente_carro  . '</td>	
		    		</tr>	
		    		<tr>
		    		<td width="1000px"  colspan="5" style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" >&nbsp;</td>	
		    		</tr>		    		
		    		<tr>
		    		<td width="200px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;"><b>HORA SALIDA</b></td>
		    		<td width="200px" style="text-align:left">&nbsp;</td>	
		    		<td width="600px" colspan="3" rowspan="2" style="text-align:left">PD: En caso de retraso o problemas en la ruta favor llamar al numero telefono del vendedor </td>		
		    		</tr>
		    		<tr>
		    		<td width="200px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;"><b>HORA LLEGADA</b></td>
		    		<td width="200px" style="text-align:left">&nbsp;</td>	
		    		</tr>	
		    		<tr>
		    		<td width="200px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;"><b>TELEFONO ADMINISTRATIVA</b></td>
		    		<td width="200px" style="text-align:left">VERONICA JARA</td>	
		    		<td width="600px" colspan="3" style="text-align:left">961164421</td>		
		    		</tr>	
		    		<tr>
		    		<td width="200px"  style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;"><b>TELEFONO ZONAL</b></td>
		    		<td width="200px" style="text-align:left">MARCELO SCHEIHING</td>	
		    		<td width="600px" colspan="3" style="text-align:left">998884472</td>		
		    		</tr>	
		    		<tr>
		    		<td width="1000px"  colspan="5" style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" >El conductor es responsable de la descarga correcta en cada predio de destino, errores y costos que ellos impliquen seran descontados de los pagos</td>	
		    		</tr>	
		    		<tr>
		    		<td width="1000px"  colspan="5" style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" >El cobro del flete estara sujeto a la presentacion de Guia de Despacho o Factura con Nombre, Rut y firma del receptor</td>	
		    		</tr>		    			    			    			    			    				
		    		 <tr>
		    		<td width="1000px"  colspan="5" style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" >Tambien se recuerda que todas las guias o facturas deben tener la firma conforme de quien recepciono el pedido</td>	
		    		</tr>
		    		 <tr>
		    		<td width="1000px"  colspan="5" style="border-bottom:0.5pt solid black;border-top:1pt solid black;text-align:left;" >Ante cualquier duda de ruta, descarga o destino por favor comunicarse con el contacto</td>	
		    		</tr>		    		
		    	</table>
		   	</td>
		   	</tr>';



		// RELLENA ESPACIO
		/*while($i < 30){
			$html .= '<tr><td colspan="5">&nbsp;</td></tr>';
			$i++;
		}*/


		$html .= '	
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

		//echo $html; exit;
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
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, v.id as id_vendedor, cor.nombre as nom_documento, op.observaciones as observa, f.nombre_formula as nombre_formula FROM pedidos acc
		left join correlativos cor on (acc.tip_documento = cor.id)
		left join clientes c on (acc.id_cliente = c.id)
		left join vendedores v on (acc.id_vendedor = v.id)
		left join formula f on (acc.id_formula = f.id)
		left join observacion_pedidos op on (acc.num_pedido = op.num_pedidos)
		WHERE acc.id = "'.$idpedidos.'"
		');
		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		//items
		$items = $this->db->get_where('pedidos_detalle', array('id_pedido' => $idpedidos));

		$this->db->select('pd.*,
							f.nombre_formula
						',false)
						  ->from('pedidos_detalle pd')
						  ->join('formula f','pd.id_formula = f.id')
						  ->where('pd.id_pedido',$idpedidos); 	                  
		$items = $this->db->get();		
		foreach($items->result() as $c){
			$this->db->where('id', $c->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			$nomproducto = $producto->nombre;
			$cantProducto = $c->cantidad;
		}
			
		//$items2 = $this->db->get_where('formula_pedido', array('id_pedido' => $idpedidos));

		$this->db->select('fp.id
							,fp.nom_formula
							,fp.id_pedido
							,fp.id_detalle_pedido
							,fp.id_bodega
							,fp.id_producto
							,fp.cantidad
							,fp.porcentaje
							,fp.valor_compra
							,fp.valor_produccion
							,p.codigo',false)
						  ->from('formula_pedido fp')
						  ->join('pedidos_detalle pd','fp.id_detalle_pedido = pd.id')
						  ->join('productos p','pd.id_producto = p.id')
						  ->where('fp.id_pedido',$idpedidos); 	                  
		$items2 = $this->db->get();		


		//variables generales
		$codigo = $row->num_pedido;
		$nombreformula = $row->nombre_formula;
		$nombre_contacto = $row->nombre_cliente;
		$vendedor = $row->nom_vendedor;
		$observacion = $row->observa;
		$fecha = $row->fecha_doc;
		$fecha_despacho = $row->fecha_despacho;
		$totaliva = 0;
		$neto = ($row->total / 1.19);
		$iva = ($row->total - $neto);
		$subtotal = ($row->total);		

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
	          <p>PEDIDO N°: '.$codigo.'</p>
	          <!--p>&nbsp;</p-->
	          <p>FECHA EMISION : '.$fecha.'</p>
	          <!--p>&nbsp;</p-->
	          <p>FECHA DESPACHO : '.$fecha_despacho.'</p>
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
		    		<td width="197px">VENDEDOR:</td>
		    		<td width="197px">'.$row->nom_vendedor.'</td>
		    		</tr>
		    	</table>
			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="950px" cellspacing="0" cellpadding="0" >
		      <tr>
		        <td width="100px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Codigo</td>
		        <td width="318px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Descripcion</td>
		         <td width="225px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Fórmula</td>
		        <td width="123px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="123px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Precio</td>
		        <td width="123px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Neto</td>
		        
		       </tr>';

		$i = 0;

		foreach($items->result() as $v){			
			//$i = 0;
			//while($i < 30){
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			
			$html .= '<tr>
			<td style="text-align:left">'.$producto->codigo.'</td>
			<td style="text-align:left">'.$producto->nombre.'</td>			
			<td style="text-align:left">'.$v->nombre_formula.'</td>	
			<td align="right">'.number_format($v->cantidad, 2, '.', ',').'</td>
			<td align="right">'.number_format($v->precio, 2, '.', ',').'</td>
			<td align="right">'.number_format($neto, 0, '.', ',').'</td>
			
			</tr>';
			
			//}
			$i++;
		}


		       $html .= '<tr><td colspan="5">&nbsp;</td></tr>
		       </table>
		      </td></tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		      <tr>
		      	<td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Producto</td>
		        <td width="247px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Descripcion</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Porcentaje</td>
		        
		       </tr>';
		$descripciones = '';
		$i = 0;
		foreach($items2->result() as $v){
			//$i = 0;
			//while($i < 30){
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			
			$html .= '<tr>
			<td style="text-align:left">'.$v->codigo.'</td>
			<td style="text-align:left">'.$producto->nombre.'</td>
			<td style="text-align:left"></td>
			<td style="text-align:left"></td>
			<td align="right"> '.number_format($v->cantidad, 2, '.', ',').'</td>
			<td align="right">% '.number_format($v->porcentaje, 2, '.', ',').'</td>
			
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
						<td width="150px" style="font-size: 20px;text-align:left;"></td>
						<td width="146px" style="text-align:right;"></td>
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
		$idpedidos = $this->input->get('idpedidos');
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, v.id as id_vendedor, cor.nombre as nom_documento, op.observaciones as observa, f.nombre_formula as nombre_formula FROM pedidos acc
		left join correlativos cor on (acc.tip_documento = cor.id)
		left join clientes c on (acc.id_cliente = c.id)
		left join vendedores v on (acc.id_vendedor = v.id)
		left join formula f on (acc.id_formula = f.id)
		left join observacion_pedidos op on (acc.num_pedido = op.num_pedidos)
		WHERE acc.id = "'.$idpedidos.'"
		');
		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		//items
		$items = $this->db->get_where('pedidos_detalle', array('id_pedido' => $idpedidos));
		
			
		$codigo = $row->num_pedido;
		$nombre_contacto = $row->nombre_cliente;
		$vendedor = $row->nom_vendedor;
		$observacion = $row->observa;
		$fecha = $row->fecha_doc;
		$totaliva = 0;
		$neto = ($row->total / 1.19);
		$iva = ($row->total - $neto);
		$subtotal = ($row->total);		

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
		<table width="892px" height="602" border="0">
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
	          <p>PEDIDO N°: '.$codigo.'</p>
	          <!--p>&nbsp;</p-->
	          <p>FECHA EMISION : '.$fecha.'</p>
	          <!--p>&nbsp;</p-->		         
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3" width="892px"><h1>PEDIDOS</h1></td>
		  </tr>
		  <tr>
		    <td colspan="3" width="892px" >
		    	<table width="987px" border="0">
		    		<tr>
		    			<td width="197px">Sr.(es):</td>
		    			<td width="395px">'. $row->nombre_cliente.'</td>
		    			<td width="197px">Rut:</td>
		    			<td width="197px">'. number_format(substr($row->rut_cliente, 0, strlen($row->rut_cliente) - 1),0,".",".")."-".substr($row->rut_cliente,-1).'</td>
		    		</tr>
		    		<tr>
		    		
		    		</tr>
		    		<tr>
		    		
		    		</tr>    	
		    		
		    	</table>
			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="950px" cellspacing="0" cellpadding="0" >
		      <tr>
		        <td width="100px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Codigo</td>
		        <td width="448px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Descripcion</td>
		        <td width="168px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Precio</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Neto</td>
		        
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
			<td style="text-align:left">'.$producto->codigo.'</td>
			<td style="text-align:left">'.$producto->nombre.'</td>			
			<td align="right">'.number_format($v->cantidad, 2, '.', ',').'</td>
			<td align="right">'.number_format($v->precio, 2, '.', ',').'</td>
			<td align="right">'.number_format($neto, 0, '.', ',').'</td>
			
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
		  	
		  </tr>
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;"></td>
						<td width="146px" style="text-align:right;"></td>
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
		$numeropedido = $this->input->post('numeropedido');
		$idbodega = $this->input->post('idbodega');
		//$idformula = $this->input->post('idformula');
		$fechapedidos = $this->input->post('fechapedido');
		$fechadoc = $this->input->post('fechadocum');
		$vendedor = $this->input->post('vendedor');
		$sucursal = $this->input->post('sucursal');
		$fechadespachooc = $this->input->post('fechadespacho');
		//$nomformula = $this->input->post('nomformula');
		$cantidadform = $this->input->post('cantidadfor');
		$items = json_decode($this->input->post('items'));
		$neto = $this->input->post('neto');
		$fiva = $this->input->post('iva');
		$fafecto = $this->input->post('afecto');
		$ftotal = $this->input->post('total');
		$idobserva = $this->input->post('idobserva');
						
			
		$pedidos = array(
	        'num_pedido' => $numeropedido,
	        'fecha_doc' => $fechadoc ,
	        'id_cliente' => $idcliente,
	        'nombre_cliente' => strtoupper($nomcliente),
	        'id_bodega' => $idbodega,
	        'id_vendedor' => $vendedor,
	        'fecha_despacho' => $fechadespachooc,
	        //'id_formula' => $idformula,
	        //'nom_formula' => $nomformula,
	        'fecha_pedido' => $fechapedidos,
	        'neto' => $neto,
	        'iva' => $fiva,
	        'total' => $ftotal,
	        'id_observa' => $idobserva,
	        'estado' => 4,
	        'tipopedido' => 'E',
	        'idestadopedido' => 1
		);

		$this->db->insert('pedidos', $pedidos); 
		$idpedidos = $this->db->insert_id();


		$pedidos_log = array(
								'idpedido' => $idpedidos,
								'idestado' => 1,
								'fecha' => date('Y-m-d H:i:s')
							);
		$this->db->insert('pedidos_log_estados', $pedidos_log); 




		$this->db->select('estado',false)
						  ->from('clientes c')
						  ->where('c.id',$idcliente); 	                  
		$query = $this->db->get();		
		$cliente = $query->row();


		$requiere_autorizacion = false;
		if($cliente->estado == 3 || $cliente->estado == 4){

			$pedidos = array(
		        'idestadopedido' => 6
				);			

				
			$this->db->where('id', $idpedidos);
			$this->db->update('pedidos', $pedidos);			

			$pedidos_log = array(
										'idpedido' => $idpedidos,
										'idestado' => 6,
										'fecha' => date('Y-m-d H:i:s')
									);
			$this->db->insert('pedidos_log_estados', $pedidos_log); 
			$requiere_autorizacion = true;
		}


		$secuencia = 0;
		$cantidadform = 0;

		foreach($items as $v){

			$cantidadform = $cantidadform + $v->cantidad;

			$secuencia = $secuencia + 1;
			$pedidos_detalle = array(
		        'id_producto' => $v->id_producto,
		        'id_pedido' => $idpedidos,
		        'id_bodega' => $v->id_bodega,
		        'num_pedido' => $numeropedido,
		        'precio' => $v->precio,
		        'neto' => $v->neto,
		        'cantidad' => $v->cantidad,
		        'neto' => $v->neto,
		        'iva' => $v->iva,
		        'total' => $v->total,
		        'secuencia' => $secuencia,
		        'fecha' => $fechapedidos
			);

		$producto = $v->id;

		$this->db->insert('pedidos_detalle', $pedidos_detalle);

		$general = $this->db->query('SELECT * FROM pedidos_general WHERE id_producto="'.$producto.'"
    	AND fecha_produccion = "'.$fechapedidos.'"');	

    	$existe_stock = true;

		if($general->num_rows()>0){
		 		$row = $general->first_row();
		 	    $id = ($row->id);
		 	    $cantidad = ($row->cantidad + ($v->cantidad) );

		 	    $this->db->where('id', $v->id_producto);
				$producto = $this->db->get("productos");	
				$producto = $producto->result();
				$producto = $producto[0];


				if($cantidadform > $producto->stock){
					$existe_stock = false;
				}


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
					

				if($cantidadform > $producto->stock){
					$existe_stock = false;
				}


				$pedidos_general = array(
				'id_producto' => $v->id_producto,
				'cantidad' => $v->cantidad,
				'fecha_produccion' => $fechapedidos,
				'fecha' => $fechapedidos,
				);

				$this->db->insert('pedidos_general', $pedidos_general);

	    };
    	
		};


		// SI NO NECESITA AUTORIZACION, EVALUA EL STOCK
		if(!$requiere_autorizacion){
				if($existe_stock){

						$estado_nuevo = 5;

				}else{

						$estado_nuevo = 2;
				}

				$pedidos = array(
			        'idestadopedido' => $estado_nuevo
					);			

					
				$this->db->where('id', $idpedidos);
				$this->db->update('pedidos', $pedidos);			

				$pedidos_log = array(
											'idpedido' => $idpedidos,
											'idestado' => $estado_nuevo,
											'fecha' => date('Y-m-d H:i:s')
										);
				$this->db->insert('pedidos_log_estados', $pedidos_log); 

		}

		
		$data2 = array(
	        'cantidad' => $cantidadform,

	    );
		$this->db->where('id', $idpedidos);
		
		$this->db->update('pedidos', $data2); 
	        

		/*$itemsf = $this->db->query('SELECT * FROM formula_detalle 
	   	    WHERE id_formula like "'.$idformula.'"');

		foreach($itemsf->result() as $item){
			
			$formula_detalle2 = array(
		        'id_producto' => $item->id_producto,
		        'id_pedido' => $idpedidos,
		        'nom_formula' => $nomformula,
		        'id_bodega' => $idbodega,
		        'porcentaje' => $item->porcentaje,
		        'cantidad' => $item->cantidad,
		        'valor_compra' => $item->valor_compra,
		        'valor_produccion' => $item->valor_produccion,
		        );

		$this->db->insert('formula_pedido', $formula_detalle2);	
		}*/	 

		
        $resp['success'] = true;
		$resp['idpedidos'] = $idpedidos;

		$this->Bitacora->logger("I", 'pedidos', $idpedidos);
		$this->Bitacora->logger("I", 'pedidos_detalle', $idpedidos);
        

        echo json_encode($resp);
	}

	public function save2(){

		$resp = array();
		$idpedidos = $this->input->post('idpedido');
        $idcliente = $this->input->post('idcliente');
		$nomcliente = $this->input->post('nomcliente');
		$numeropedido = $this->input->post('numeropedido');
		$idbodega = $this->input->post('idbodega');
		//$idformula = $this->input->post('idformula');
		$fechapedidos = $this->input->post('fechapedido');
		//$cantidadform = $this->input->post('cantidadfor');
		$fechadoc = $this->input->post('fechadocum');
		$vendedor = $this->input->post('vendedor');
		$items = json_decode($this->input->post('items'));
		$neto = $this->input->post('neto');
		$fiva = $this->input->post('iva');
		$fafecto = $this->input->post('afecto');
		$ftotal = $this->input->post('total');
		$idobserva = $this->input->post('idobserva');				
						
		
		$query = $this->db->query('DELETE FROM pedidos_detalle WHERE id_pedido = "'.$idpedidos.'"');

		$query = $this->db->query('DELETE FROM formula_pedido WHERE id_pedido = "'.$idpedidos.'"');


		$secuencia = 0;

		foreach($items as $v){
			$secuencia = $secuencia + 1;
			$pedidos_detalle = array(
				'id_producto' => $v->id_producto,
		        'id_pedido' => $idpedidos,
		        'id_bodega' => $v->id_bodega,
		        'num_pedido' => $numeropedido,
		        'precio' => $v->precio,
		        'neto' => $v->neto,
		        'cantidad' => $v->cantidad,
		        'neto' => $v->neto,
		        'iva' => $v->iva,
		        'total' => $v->total,
		        'secuencia' => $secuencia,
		        'fecha' => $fechapedidos   

			);

		$producto = $v->id_producto;

	    $this->db->insert('pedidos_detalle', $pedidos_detalle);	    	
		}

		/*$itemsf = $this->db->query('SELECT * FROM formula_detalle 
	   	    WHERE id_formula like "'.$idformula.'"');

		foreach($itemsf->result() as $item){
			
			$formula_detalle2 = array(
		        'id_producto' => $item->id_producto,
		        'id_pedido' => $idpedidos,
		        'id_bodega' => 1,
		        'porcentaje' => $item->porcentaje,
		        'cantidad' => $item->cantidad,
		        'valor_compra' => $item->valor_compra,
		        'valor_produccion' => $item->valor_produccion,
		        );

		$this->db->insert('formula_pedido', $formula_detalle2);	
		}	*/

		
		$pedidos = array(
	        'num_pedido' => $numeropedido,
	        'fecha_doc' => $fechadoc ,
	        'id_cliente' => $idcliente,
	        'nombre_cliente' => strtoupper($nomcliente),
	        'id_bodega' => $idbodega,
	        //'id_formula' => $idformula,
	        'cantidad' => $cantidadform,
	        'id_vendedor' => $vendedor,
	        'fecha_pedido' => $fechapedidos,
	        'neto' => $neto,
	        'iva' => $fiva,
	        'total' => $ftotal,
	        'id_observa' => $idobserva,
	        'estado' => 4
			);			

			
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

	public function produccion(){
		
		$resp = array();

        $start = $this->input->post('start');
        $limit = $this->input->post('limit');
        $opcion = $this->input->post('opcion');
        $nombres = $this->input->post('nombre');
        $bodega = $this->input->post('idbodega');
        $tipo = $this->input->post('tipo');
        $estado = $this->input->post('estado');
        $tipopedido = $this->input->post('tipopedido');

        $start = $start == '' ? 0: $start;
        $limit = $limit == '' ? 1000: $limit;



        if(!$estado){
        	$opcion = "Todos";
        	$estado = 4;
        };
        if(!$opcion){
        	$opcion = "Todos";
        };






        $sql_tipo_pedido = '';
        if($tipopedido != ''){

        	$sql_tipo_pedido = " AND acc.tipopedido = '" . $tipopedido . "' ";
        }
		
        //$countAll = $this->db->count_all_results("pedidos");

		if($opcion == "Rut"){

			if ($estado == 1){

			$data = array();		
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE c.rut = "'.$nombres.'"  ' . $sql_tipo_pedido . ' 
			order by acc.id desc');

		    $total = 0;

			  foreach ($query->result() as $row)
				{
					$total = $total +1;
				
				}

				$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto,pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE c.rut = "'.$nombres.'"  ' . $sql_tipo_pedido . ' order by acc.id desc			
			limit '.$start.', '.$limit.'');

		}else{

			$data = array();		
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE c.rut = "'.$nombres.'" and acc.estado = "'.$estado.'" ' . $sql_tipo_pedido . ' 
			order by acc.id desc');

		    $total = 0;

			  foreach ($query->result() as $row)
				{
					$total = $total +1;
				
				}

				$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE c.rut = "'.$nombres.'" and acc.estado = "'.$estado.'"  ' . $sql_tipo_pedido . ' order by acc.id desc			
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
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE ' . $sql_nombre . '  ' . $sql_tipo_pedido . ' ');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE ' . $sql_nombre . '  ' . $sql_tipo_pedido . ' order by acc.id desc
			limit '.$start.', '.$limit.'');
	    		


	    	}else{

	    	
			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "and acc.nombre_cliente like '%".$nombre."%' ";
	        }

	        $data = array();	        	    	
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE acc.estado = "'.$estado.'" ' . $sql_nombre . '  ' . $sql_tipo_pedido . ' 
			order by acc.id desc'
						
			);

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE acc.estado = "'.$estado.'" ' . $sql_nombre . ' ' . $sql_tipo_pedido . '  order by acc.id desc
			limit '.$start.', '.$limit.'');

		    };
	 
		}else if($opcion == "Todos"){

			if ($estado == 1){			
			$data = array();
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE 1=1  ' . $sql_tipo_pedido . ' 
			order by acc.id desc
			');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE 1=1  ' . $sql_tipo_pedido . ' 
			order by acc.id desc
			limit '.$start.', '.$limit.''	
			
			);

			}else{
			$data = array();
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE acc.estado = "'.$estado.'"  ' . $sql_tipo_pedido . ' 
			order by acc.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE acc.estado = "'.$estado.'"  ' . $sql_tipo_pedido . ' 
			order by acc.id desc
			limit '.$start.', '.$limit.''	
			
			);
		};
		}else if($opcion == "Numero"){

			if ($estado == 1){

			
			$data = array();

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE acc.num_pedido =  "'.$nombres.'" ' . $sql_tipo_pedido . ' 
			order by acc.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE acc.num_pedido =  "'.$nombres.'"  ' . $sql_tipo_pedido . ' 
			order by acc.id desc');

			}else{

				$data = array();

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE acc.num_pedido =  "'.$nombres.'" and acc.estado = "'.$estado.'" ' . $sql_tipo_pedido . ' 
			order by acc.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega, pr.nombre as nom_producto, pr.id as id_producto, pr.codigo as codigo FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			left join pedidos_detalle pe on (acc.id = pe.id_pedido)
			left join productos pr on (pe.id_producto = pr.id)
			WHERE acc.num_pedido =  "'.$nombres.'" and acc.estado = "'.$estado.'" ' . $sql_tipo_pedido . ' 
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

	
	public function getAll(){
		
		$resp = array();

        $start = $this->input->post('start');
        $limit = $this->input->post('limit');
        $opcion = $this->input->post('opcion');
        $nombres = $this->input->post('nombre');
        $bodega = $this->input->post('idbodega');
        $vendedor = $this->input->post('idvendedor');
        $tipo = $this->input->post('tipo');
        $estado = $this->input->post('estado');
        $tipopedido = $this->input->post('tipopedido');
        $idestadopedido = $this->input->post('idestadopedido');

        $estadonuevo = $estado;

        if (!$bodega){
	       $bodega = 0;
	    }
        if(!$estado){
        	$opcion = "Todos";
        	$estado = 1;
        };
        if(!$opcion){
        	$opcion = "Todos";
        };

        $sql_tipo_pedido = '';
        if($tipopedido != ''){

        	$sql_tipo_pedido = " AND acc.tipopedido = '" . $tipopedido . "' ";
        }

        $sql_estado_pedido = '';
        if($idestadopedido != ''){

        	$sql_estado_pedido = " AND acc.idestadopedido = '" . $idestadopedido . "' ";
        }




        //echo '<pre>';
        //var_dump($_POST);
       	//var_dump($sql_estado_pedido); exit;
        if($vendedor){

        	if($opcion == "Rut"){

			if ($estado == 1){

			$data = array();		
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			, CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc			   


			FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' AND c.rut = "'.$nombres.'" ' . $sql_tipo_pedido . ' ' . $sql_estado_pedido . '
			order by acc.id desc');

		    $total = 0;

			  foreach ($query->result() as $row)
				{
					$total = $total +1;
				
				}

				$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			   FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' AND c.rut = "'.$nombres.'"  ' . $sql_tipo_pedido . ' ' . $sql_estado_pedido . ' order by acc.id desc			
			limit '.$start.', '.$limit.'');

		}else{

			$data = array();		


			if($estadonuevo == 2){// pendiente autorizacion
				$sql_estado_pedido = " AND acc.idestadopedido = '6' ";
				$sql_estado = '';
			}else if($estadonuevo == 3){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_pendiente > 0';
			}else if($estadonuevo == 4){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_produccion > 0';
			}else if($estadonuevo == 5){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos > 0 and cantidad_listos < cantidad_productos';
			}else if($estadonuevo == 6){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos = cantidad_productos';
			}



			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' AND c.rut = "'.$nombres.'"  ' . $sql_tipo_pedido . ' ' . $sql_estado_pedido . ')a 
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc');

		    $total = 0;

			  foreach ($query->result() as $row)
				{
					$total = $total +1;
				
				}

				$countAll = $total;

			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' AND c.rut = "'.$nombres.'"  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ')a WHERE 1 = 1 ' . $sql_estado . ' order by a.id desc			
			limit '.$start.', '.$limit.'');

			//echo $this->db->last_query();

		};

	    }else if($opcion == "Nombre"){

	    	if ($estado == 1){

	    	$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "acc.nombre_cliente like '%".$nombre."%' ";
	        }

	        $data = array();	        	    	
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' AND ' . $sql_nombre . '  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' ');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' AND ' . $sql_nombre . ' ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' order by acc.id desc
			limit '.$start.', '.$limit.'');
	    		


	    	}else{


			if($estadonuevo == 2){// pendiente autorizacion
				$sql_estado_pedido = " AND acc.idestadopedido = '6' ";
				$sql_estado = '';
			}else if($estadonuevo == 3){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_pendiente > 0';
			}else if($estadonuevo == 4){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_produccion > 0';
			}else if($estadonuevo == 5){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos > 0 and cantidad_listos < cantidad_productos';
			}else if($estadonuevo == 6){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos = cantidad_productos';
			}

	    	
			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "and acc.nombre_cliente like '%".$nombre."%' ";
	        }

	        $data = array();	        	    	
			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.'  ' . $sql_nombre . '  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' )a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc'
						
			);

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc

			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.'  ' . $sql_nombre . '  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ')a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc
			limit '.$start.', '.$limit.'');

		    };
	 
		}else if($opcion == "Todos"){
			if ($estado == 1){				
			$data = array();
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' 
			order by acc.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' 
			order by acc.id desc
			limit '.$start.', '.$limit.''	
			
			);

			}else{
			$data = array();

			if($estadonuevo == 2){// pendiente autorizacion
				$sql_estado_pedido = " AND acc.idestadopedido = '6' ";
				$sql_estado = '';
			}else if($estadonuevo == 3){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_pendiente > 0';
			}else if($estadonuevo == 4){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_produccion > 0';
			}else if($estadonuevo == 5){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos > 0 and cantidad_listos < cantidad_productos';
			}else if($estadonuevo == 6){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos = cantidad_productos';
			}


			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.'  
			 ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ')a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ')a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc
			limit '.$start.', '.$limit.''	
			
			);
		};
		}else if($opcion == "Numero"){

			if ($estado == 1){

			
			$data = array();

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' AND acc.num_pedido =  "'.$nombres.'" ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' 
			order by acc.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' AND acc.num_pedido =  "'.$nombres.'"  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' 
			order by acc.id desc');

			}else{

				$data = array();


			if($estadonuevo == 2){// pendiente autorizacion
				$sql_estado_pedido = " AND acc.idestadopedido = '6' ";
				$sql_estado = '';
			}else if($estadonuevo == 3){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_pendiente > 0';
			}else if($estadonuevo == 4){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_produccion > 0';
			}else if($estadonuevo == 5){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos > 0 and cantidad_listos < cantidad_productos';
			}else if($estadonuevo == 6){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos = cantidad_productos';
			}


			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' AND acc.num_pedido =  "'.$nombres.'"  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ')a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_vendedor = '.$vendedor.' and acc.id_bodega='.$bodega.' AND acc.num_pedido =  "'.$nombres.'" ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ')a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc');
				
			}

		};
        	
        }else{

		
        if($opcion == "Rut"){

			if ($estado == 1){

			$data = array();		
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND c.rut = "'.$nombres.'"  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' 
			order by acc.id desc');

		    $total = 0;

			  foreach ($query->result() as $row)
				{
					$total = $total +1;
				
				}

				$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND c.rut = "'.$nombres.'"  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' order by acc.id desc			
			limit '.$start.', '.$limit.'');

		}else{

			$data = array();		


			if($estadonuevo == 2){// pendiente autorizacion
				$sql_estado_pedido = " AND acc.idestadopedido = '6' ";
				$sql_estado = '';
			}else if($estadonuevo == 3){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_pendiente > 0';
			}else if($estadonuevo == 4){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_produccion > 0';
			}else if($estadonuevo == 5){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos > 0 and cantidad_listos < cantidad_productos';
			}else if($estadonuevo == 6){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos = cantidad_productos';
			}


			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND c.rut = "'.$nombres.'"  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ')a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc');

		    $total = 0;

			  foreach ($query->result() as $row)
				{
					$total = $total +1;
				
				}

				$countAll = $total;

			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND c.rut = "'.$nombres.'"  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ')a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc			
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
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND ' . $sql_nombre . '  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' ');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND ' . $sql_nombre . ' ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' order by acc.id desc
			limit '.$start.', '.$limit.'');
	    		


	    	}else{

	    	
			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "and acc.nombre_cliente like '%".$nombre."%' ";
	        }

	        $data = array();	        	    	

			if($estadonuevo == 2){// pendiente autorizacion
				$sql_estado_pedido = " AND acc.idestadopedido = '6' ";
				$sql_estado = '';
			}else if($estadonuevo == 3){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_pendiente > 0';
			}else if($estadonuevo == 4){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_produccion > 0';
			}else if($estadonuevo == 5){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos > 0 and cantidad_listos < cantidad_productos';
			}else if($estadonuevo == 6){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos = cantidad_productos';
			}


			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' ' . $sql_nombre . '  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ')a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc'
						
			);

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' ' . $sql_nombre . '  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ')a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc
			limit '.$start.', '.$limit.'');

		    };
	 
		}else if($opcion == "Todos"){

			if ($estado == 1){			
			$data = array();
			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' 
			order by acc.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' 
			order by acc.id desc
			limit '.$start.', '.$limit.''	
			
			);

			}else{
			$data = array();

			if($estadonuevo == 2){// pendiente autorizacion
				$sql_estado_pedido = " AND acc.idestadopedido = '6' ";
				$sql_estado = '';
			}else if($estadonuevo == 3){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_pendiente > 0';
			}else if($estadonuevo == 4){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_produccion > 0';
			}else if($estadonuevo == 5){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos > 0 and cantidad_listos < cantidad_productos';
			}else if($estadonuevo == 6){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos = cantidad_productos';
			}



			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.'  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' )a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . '  )a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc
			limit '.$start.', '.$limit.''	
			
			);


			//echo $this->db->last_query();
		};
		}else if($opcion == "Numero"){

			if ($estado == 1){

			
			$data = array();

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc			
			FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.num_pedido =  "'.$nombres.'" ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' 
			order by acc.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			 FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.num_pedido =  "'.$nombres.'"  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ' 
			order by acc.id desc');

			}else{

				$data = array();

			if($estadonuevo == 2){// pendiente autorizacion
				$sql_estado_pedido = " AND acc.idestadopedido = '6' ";
				$sql_estado = '';
			}else if($estadonuevo == 3){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_pendiente > 0';
			}else if($estadonuevo == 4){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_produccion > 0';
			}else if($estadonuevo == 5){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos > 0 and cantidad_listos < cantidad_productos';
			}else if($estadonuevo == 6){
				$sql_estado_pedido = '';
				$sql_estado = 'AND idestadopedido != 6 AND cantidad_listos = cantidad_productos';
			}


			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.num_pedido =  "'.$nombres.'"  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ')a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc');

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT * FROM (SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, co.nombre as nom_documento, v.nombre as nom_vendedor, co.id as id_tip_docu, b.nombre as nom_bodega,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id) as cantidad_productos
			,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (4,5)) as cantidad_listos,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (3)) as cantidad_produccion,(select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and idestadoproducto in (2)) as cantidad_pendiente, (select count(id) as cantidad from pedidos_detalle where id_pedido = acc.id and requierereceta = 1) as cantidad_requiere_receta,
			CASE WHEN acc.autorizarecoc = 1 then 1
			       WHEN (select sum(requierereceta - subereceta) as cantidad from pedidos_detalle where id_pedido = acc.id) <= 0 then 1
			  ELSE 0
			END AS cumplereceta
			,CASE WHEN acc.autorizarecoc = 1 then 1
					WHEN acc.ordencompraint != "" OR acc.subeoc = 1 then 1
				ELSE 0
			END AS cumpleoc
			  FROM pedidos acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join bodegas b on (acc.id_bodega = b.id)
			left join correlativos co on (acc.tip_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.num_pedido =  "'.$nombres.'"  ' . $sql_tipo_pedido . '  ' . $sql_estado_pedido . ')a
			WHERE 1 = 1 ' . $sql_estado . '
			order by a.id desc');
				
			}

		};

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

		    
		    if($row->idestadopedido == 6){
		    		$row->situacionpedido = '<a style="background-color: red; color: white;"><b>Pendiente Autorización Cliente</b></a>';
		    }else{
		    		$color_background = $row->cantidad_listos == $row->cantidad_productos ? 'green' : 'yellow';
		    		$color_text = $row->cantidad_listos == $row->cantidad_productos ? 'white' : 'blue';

		    		$row->situacionpedido = '<a style="background-color: ' . $color_background . '; color: ' . $color_text . ';"><b>Listos ( ' . $row->cantidad_listos . ' / ' . $row->cantidad_productos . ' )</b></a>';

		    }
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
				<td width="287px" style="text-align:center;font-size:12px"><h2> '.number_format($v['cantidad'], 2, ',', '.').'</h2></td>				
				</tr>
				';				

			}else{

		    $body_detail .= '
				<tr>				
				<td width="60px" style="text-align:center;font-size:12px"><h2>'.$codigo.'</h2></td>	
				<td width="440px" style="text-align:rigth;font-size:12px"><h2>'.$descripcion.'</h2></td>
				<td width="287px" style="text-align:center;font-size:12px"><h2> '.number_format($v['cantidad'], 2, ',', '.').'</h2></td>				
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
			$this->mpdf->SetHeader('Agricola y comercial Lircay - Informe Produccion');
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
