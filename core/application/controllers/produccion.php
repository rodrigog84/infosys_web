<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produccion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();	
			
		$this->load->database();
	}

	public function estado(){

		$resp = array();
		$idproduccion = $this->input->get('idproduccion');
		$estado=2;
		
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido, p.num_pedido as num_pedido, pr.diasvencimiento as dias FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		left join productos pr on (acc.id_producto = pr.id)
		WHERE acc.id = "'.$idproduccion.'" and acc.estado='.$estado.'');

		if($query->num_rows()>0){
		$row1 = $query->result();
		$row = $row1[0];
		
		$numero = $row->num_produccion;
		$cantidreal = $row->cant_real;
		$idproducto = $row->id_producto;
		$tipo=23;

		/*print_r($row);
		exit;*/

		$query1 = $this->db->query('SELECT acc.*, c.nombre as nom_producto, cor.nombre as nom_tipo_movimiento, c.codigo as codigo, bod.nombre as nom_bodega FROM existencia_detalle acc
		left join productos c on (acc.id_producto = c.id)
		left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
		left join bodegas bod on (acc.id_bodega = bod.id) 
		WHERE acc.num_movimiento="'.$numero.'" and id_tipo_movimiento="'.$tipo.'"');

		if($query1->num_rows()>0){
		foreach($query1->result() as $item){			
			$saldo = $item->saldo;
			$cantidad = $item->cantidad_entrada;
			if ($saldo!=$cantidad){
				 $resp['success'] = false;
				
			}else{
				 $resp['success'] = true;
				 $resp['produccion'] = $row;
			}
		}
		}
		
		$resp['saldo'] = $saldo;   
		$resp['cantidad'] = $cantidad;   
	   
       
        }else{
        	 $resp['success'] = false;
        	
        }

        echo json_encode($resp);

	}

	public function enviarMail(){
		
		$resp = array();
		$nombre = $this->input->post('nombre');
		$idproducto = $this->input->post('producto');
		$mensaje="Producto ".$nombre."."."Con Stock Critico Favor informar";

		$items = $this->db->get_where('productos', array('id' => $idproducto));

		foreach($items->result() as $item){			
			$familia= $item->id_familia;
		}

		$query = $this->db->query('SELECT * FROM mail_autorizados');

		foreach($query->result() as $item){	
				
			$veterinaria= $item->veterinaria;
			$productos= $item->productos;
			$ganado= $item->ganado;

			if ($veterinaria=="SI"&$familia=="1"){

				$html = 'Mensaje de Prueba';
				$email=$item->email;
				
		
			$this->load->model('facturaelectronica');
			$email_data = $this->facturaelectronica->get_email();

			if(count($email_data) > 0){
				$this->load->library('email');
				$config['protocol']    = $email_data->tserver_intercambio;
				$config['smtp_host']    = $email_data->host_intercambio;
				$config['smtp_port']    = $email_data->port_intercambio;
				$config['smtp_timeout'] = '7';
				$config['smtp_user']    = $email_data->email_intercambio;
				$config['smtp_pass']    = $email_data->pass_intercambio;
				$config['charset']    = 'utf-8';
				$config['newline']    = "\r\n";
				$config['mailtype'] = 'html'; // or html
				$config['validation'] = TRUE; // bool whether to validate email or not      			
				$this->email->initialize($config);		  		

			    $this->email->from($email_data->email_intercambio, NOMBRE_EMPRESA);
			    $this->email->to($email);
			    $this->email->subject('Aviso');
			    $this->email->message($mensaje);
				try {
			      $this->email->send();
			      //exit;
			    } catch (Exception $e) {
			      echo $e->getMessage() . '<br />';
			      echo $e->getCode() . '<br />';
			      echo $e->getFile() . '<br />';
			      echo $e->getTraceAsString() . '<br />';
			      echo "no";

			    }
		    }
				
			}

			if ($ganado=="SI"&$familia=="3"){

				$html = 'Mensaje de Prueba';
				$email=$item->email;
				
		
			$this->load->model('facturaelectronica');
			$email_data = $this->facturaelectronica->get_email();

			if(count($email_data) > 0){
				$this->load->library('email');
				$config['protocol']    = $email_data->tserver_intercambio;
				$config['smtp_host']    = $email_data->host_intercambio;
				$config['smtp_port']    = $email_data->port_intercambio;
				$config['smtp_timeout'] = '7';
				$config['smtp_user']    = $email_data->email_intercambio;
				$config['smtp_pass']    = $email_data->pass_intercambio;
				$config['charset']    = 'utf-8';
				$config['newline']    = "\r\n";
				$config['mailtype'] = 'html'; // or html
				$config['validation'] = TRUE; // bool whether to validate email or not      			
				$this->email->initialize($config);		  		

			    $this->email->from($email_data->email_intercambio, NOMBRE_EMPRESA);
			    $this->email->to($email);
			    $this->email->subject('Aviso');
			    $this->email->message($mensaje);
				try {
			      $this->email->send();
			      //exit;
			    } catch (Exception $e) {
			      echo $e->getMessage() . '<br />';
			      echo $e->getCode() . '<br />';
			      echo $e->getFile() . '<br />';
			      echo $e->getTraceAsString() . '<br />';
			      echo "no";

			    }
		    }
				
			}

			if ($productos=="SI"&$familia=="8"){

				$html = 'Alerta Produccion';
				$email=$item->email;
				
		
			$this->load->model('facturaelectronica');
			$email_data = $this->facturaelectronica->get_email();

			if(count($email_data) > 0){
				$this->load->library('email');
				$config['protocol']    = $email_data->tserver_intercambio;
				$config['smtp_host']    = $email_data->host_intercambio;
				$config['smtp_port']    = $email_data->port_intercambio;
				$config['smtp_timeout'] = '7';
				$config['smtp_user']    = $email_data->email_intercambio;
				$config['smtp_pass']    = $email_data->pass_intercambio;
				$config['charset']    = 'utf-8';
				$config['newline']    = "\r\n";
				$config['mailtype'] = 'html'; // or html
				$config['validation'] = TRUE; // bool whether to validate email or not      			
				$this->email->initialize($config);		  		

			    $this->email->from($email_data->email_intercambio, NOMBRE_EMPRESA);
			    $this->email->to($email);
			    $this->email->subject('Aviso');
			    $this->email->message($mensaje);
				try {
			      $this->email->send();
			      //exit;
			    } catch (Exception $e) {
			      echo $e->getMessage() . '<br />';
			      echo $e->getCode() . '<br />';
			      echo $e->getFile() . '<br />';
			      echo $e->getTraceAsString() . '<br />';
			      echo "no";

			    }
		    }
				
			}		


		}		
		    
		//exit;

		$resp['success'] = true;
        echo json_encode($resp);

	   
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
			$item->codigo = $producto->codigo;
			$item->cantidad_pro = $item->cantidad;
			$item->cantidad_real = 0;
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
		$idBodega=1;

		$tipo = $this->input->get('tipo');
		$nombres = $this->input->get('nombre');

        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

       
        if(!$tipo){
        	$tipo="Todos";
        };
                		
        $data = array();
        
        if ($tipo=="Nombre"){

    	$sql_nombre = "";
        $arrayNombre =  explode(" ",$nombres);

        foreach ($arrayNombre as $nombre) {
        	$sql_nombre .= "c.nombres like '%".$nombre."%' and ";
        }        

		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		WHERE ' . $sql_nombre . ' 1 = 1');

			$total = 0;

		  foreach ($query->result() as $row)


		    
			{
				$total = $total +1;
			
			}

			$countAll = $total;
			


		        	
        };

        if ($tipo=="Rut"){        
        		
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		WHERE c.rut = "'.$nombres.'" ');

			$total = 0;

		  foreach ($query->result() as $row)


		    
			{
				$total = $total +1;
			
			}

			$countAll = $total;
			

	    };

	    if ($tipo=="Numero"){        
        		
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		WHERE acc.num_produccion = "'.$nombres.'" ');

			$total = 0;

		  foreach ($query->result() as $row)


		    
			{
				$total = $total +1;
			
			}

			$countAll = $total;
			

	    };

	    if ($tipo=="Producto"){ 
	    
	    $sql_nombre = "";
        $arrayNombre =  explode(" ",$nombres);

        foreach ($arrayNombre as $nombre) {
        	$sql_nombre .= "acc.nom_producto like '%".$nombre."%' and ";
        }           
        		
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		WHERE ' . $sql_nombre . ' 1 = 1');

			$total = 0;

		  foreach ($query->result() as $row)


		    
			{
				$total = $total +1;
			
			}

			$countAll = $total;
			

	    };

	    if ($tipo=="Todos"){ 

	    
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		order by acc.num_produccion desc');

		
			$total = 0;

		  foreach ($query->result() as $row)


		    
			{
				$total = $total +1;
			
			}

			$countAll = $total;
			

		

		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		order by acc.num_produccion desc 
		limit '.$start.', '.$limit.'
            '
			);

		
	    };

	   

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
		//$idformula = $this->input->post('idformula');
		//$nombreformula = $this->input->post('nombreformula');
		$cantidadproduccion = $this->input->post('cantidadproduccion');
		$lote = $this->input->post('lote');
		$nombreproducto = $this->input->post('nombreproducto');
		$idproducto = $this->input->post('idproducto');
		//$items = json_decode($this->input->post('items'));
		$horainicio = $this->input->post('horainicio');
		$encargado = $this->input->post('encargado');
					
		$produccion = array(
	        'id_pedido' => $idpedido,
	        //'id_formula_pedido' => $idformula,
	        'id_cliente' => $idcliente,
	        'num_produccion' => $numproduccion,
	        'fecha_produccion' => $fechaproduccion,
	        //'nom_formula' => $nombreformula,
	        'nom_producto' => $nombreproducto,
	        'id_producto' => $idproducto,
	        //'nom_formula' => $nombreformula,
	        'cantidad' => $cantidadproduccion,
	        'lote' => $lote,
	        'hora_inicio' => $horainicio,
	        'encargado' => $encargado,
	        'estado' => 1
		);

		$this->db->insert('produccion', $produccion); 
		$idproduccion = $this->db->insert_id();

		/*foreach($items as $v){
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
	   	
		};*/

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

	public function save4(){
		
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
	        'estado' => 4
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
		        //'cantidad_real' => 0,
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

	public function save3(){
		
		$resp = array();
		$fechaproduccion = $this->input->post('fechaproduccion');
		$fechavenc = $this->input->post('fechavenc');
		$numproduccion = $this->input->post('numproduccion');
		$cantidadproduccion = $this->input->post('cantidadproduccion');
		$cantidadproduccioncal = $this->input->post('cantidadproduccioncal');
		$idproducto = $this->input->post('idproducto');
		$idcliente = $this->input->post('idcliente');
		$idproduccion = $this->input->post('idproduccion');
		//$idpedido = $this->input->post('idpedido');
		$idbodega = $this->input->post('idbodega');
		$lote = $this->input->post('lote');
		$items = json_decode($this->input->post('items'));
		$horatermino = $this->input->post('horatermino');
		//$horainicio = $this->input->post('horainicio');
		$cero=0;
		$tipo=23;

		$producto=$idproducto;
		$producto2=$idproducto;

		$query = $this->db->query('DELETE FROM produccion_detalle WHERE id_produccion = "'.$idproduccion.'" ');		

		$query2 = $this->db->query('SELECT * FROM existencia_detalle WHERE id_producto = "'.$producto.'" and num_movimiento = "'.$numproduccion.'" and id_tipo_movimiento= "'.$tipo.'" ');
			    	 
		if ($query2->num_rows()>0){
			$row3 = $query2->first_row();		
			$cantidad_entrada2 = $row3->cantidad_entrada;
			$qdelete = $this->db->query('DELETE FROM existencia_detalle WHERE id_producto = "'.$producto.'" and num_movimiento = "'.$numproduccion.'" and id_tipo_movimiento= "'.$tipo.'" ');
		};
				
		$valorprod = 0;
		$cant_prod = 0;
		$valorprodMP = 0;	
		foreach($items as $v){
			$valorprodMP = ($v->precio*$v->cantidad);
			$valorprod = ($valorprod+$valorprodMP);
			$cant_prod = ($cant_prod + $v->cantidad);
			if(!$v->lote){
				$lote1=0;
			}else{
				$lote1=$v->lote;
			}
			$produccion_detalle = array(
			'id_produccion' => $idproduccion,
			'id_producto' => $v->id_producto,
			'id_existencia' => $v->id_existencia,
			'nom_producto' => $v->nom_producto,
			'valor_compra' => $v->valor_compra,
			'lote' => $lote1,
			'cantidad' => $v->cantidad,
			'id_bodega' => 1,
			'cantidad_pro' => $v->cantidad,
			'valor_produccion' => ($v->valor_compra* $v->cantidad),			
			);		
		$this->db->insert('produccion_detalle', $produccion_detalle);
		
		$datos2 = array(
			'num_movimiento' => $numproduccion,
		    'id_producto' => $v->id_producto,
		    'id_tipo_movimiento' => 23,
		    'valor_producto' =>  $v->valor_compra,
		    'cantidad_salida' => $v->cantidad,
		    'fecha_movimiento' => $fechaproduccion,
		    'id_bodega' => 1,
		    'lote' => $lote1,
		    'id_cliente' => $idcliente
		);
		$this->db->insert('existencia_detalle', $datos2);	   	
		};

		$puc = $valorprod;

		$query = $this->db->query('SELECT * FROM productos WHERE id="'.$idproducto.'"');
		if($query->num_rows()>0){
		$row = $query->first_row();

		//print_r($row);
		//exit;

		$query2 = $this->db->query('SELECT * FROM existencia_detalle WHERE id_producto='.$idproducto.' and cantidad_entrada > '.$cero.'');	    	 
		$ppm=0;
		$cal = 1;
		$saldo=0;
		$pmc=0;
		if ($query2->num_rows()>0){	
		$row2 = $query->first_row();		
		foreach ($query2->result() as $r){			 	
			$ppm = $ppm + ($r->valor_producto);
			$cal = $cal +1;
		};
		$ppm = $ppm + $puc;
		$ppm = ($ppm / $cal);
		$saldo = ($row->stock-$cantidad_entrada2)+($cantidadproduccion);		
		$pmc = ($row->p_may_compra);
			
		if ($pmc < $puc){			 		
			$pmc = $puc;
		};			 
		};                
		};

		

		$prod = array(
		 'stock' => $saldo,
		 'p_ult_compra' => $puc,
		 'p_may_compra' => $pmc,
		 'p_promedio' => $ppm,
		 'fecha_ult_compra' => $fechaproduccion,
		 'stock' => $saldo,
		 'fecha_vencimiento' => $fechavenc,
		 'u_lote' => $lote		 
		);
		$this->db->where('id', $idproducto);
		$this->db->update('productos', $prod);		

		$producto=$idproducto;

		$query = $this->db->query('SELECT * FROM existencia WHERE id_producto='.$idproducto.' and id_bodega='.$idbodega.'');
    	 $row = $query->result();
    	 if ($query->num_rows()>0){
		 $row = $row[0];	 
		 if ($producto==($row->id_producto)){
		 	$saldo = ($row->stock-$cantidad_entrada2)+($cantidadproduccion);
		    $datos3 = array(
			'stock' => $saldo,
		    'fecha_ultimo_movimiento' => $fechaproduccion
			);
			$this->db->where('id_producto', $idproducto);
		    $this->db->update('existencia', $datos3);
		 }
		}else{
			$saldo = $cant_prod;
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
		        'valor_producto' => ($valorprod/$cantidadproduccion),
		        'saldo' => $cantidadproduccion,
		        'fecha_vencimiento' => $fechavenc
		);

		$this->db->insert('existencia_detalle', $datos2);	   	
									
		$produccion = array(
	        'fecha_termino' => $fechaproduccion,
	        'cantidad_prod' => $cant_prod,
	        'cant_real' => $cantidadproduccion,
	        'hora_termino' => $horatermino,
	        //'hora_inicio' => $horainicio,
	        'lote' => $lote,
	        'valor_prod' => ($valorprod/$cantidadproduccion),
	        'fecha_vencimiento' => $fechavenc,
	        'estado' => 2
		);
		$this->db->where('id', $idproduccion);		  
		$this->db->update('produccion', $produccion);
		
		$query = $this->db->query('SELECT * FROM productos WHERE id="'.$idproducto.'"');
		if($query->num_rows()>0){
		$row = $query->first_row();
		$query2 = $this->db->query('SELECT * FROM existencia_detalle WHERE id_producto='.$idproducto.' and cantidad_entrada > '.$cero.'');	    	 
		$ppm=0;
		$cal = 1;
		if ($query2->num_rows()>0){
		foreach ($query2->result() as $r){			 	
			$ppm = $ppm + ($r->valor_producto);
			$cal = $cal +1;
		};
		$ppm = $ppm + $puc;
		$ppm = ($ppm / $cal);
		$saldo = ($row->stock-$cantidad_entrada2)+($cantidadproduccion);
		$pmc = ($row->p_may_compra);
		if ($pmc < $puc){			 		
			$pmc = $puc;
		};			 
		};                
		};

		$prod = array(
		 'stock' => $saldo,
		 'p_ult_compra' => $puc,
		 'p_may_compra' => $pmc,
		 'p_promedio' => $ppm,
		 'fecha_ult_compra' => $fechaproduccion,
		 'stock' => $saldo,
		 'fecha_vencimiento' => $fechavenc,
		 'u_lote' => $lote		 
		);
		$this->db->where('id', $idproducto);
		$this->db->update('productos', $prod);	
			
        $resp['idproduccion'] = $idproduccion;		
        $resp['success'] = true;		
       
		$this->Bitacora->logger("M", 'produccion', $idproduccion);
		$this->Bitacora->logger("M", 'produccion_detalle', $idproduccion);    

        echo json_encode($resp);
	}


	public function save2(){
		
		$resp = array();
		$fechaproduccion = $this->input->post('fechaproduccion');
		$fechavenc = $this->input->post('fechavenc');
		$numproduccion = $this->input->post('numproduccion');
		$cantidadproduccion = $this->input->post('cantidadproduccion');
		$cantidadproduccioncal = $this->input->post('cantidadproduccioncal');
		$idproducto = $this->input->post('idproducto');
		$idcliente = $this->input->post('idcliente');
		$idproduccion = $this->input->post('idproduccion');
		$idpedido = $this->input->post('idpedido');
		$idbodega = $this->input->post('idbodega');
		$lote = $this->input->post('lote');
		$items = json_decode($this->input->post('items'));
		$horatermino = $this->input->post('horatermino');
		$horainicio = $this->input->post('horainicio');
		$cero=0;

		$producto=$idproducto;
		$producto2=$idproducto;

		$query = $this->db->query('DELETE FROM produccion_detalle WHERE id_produccion = "'.$idproduccion.'" ');
		
		$valorprod = 0;
		$cant_prod = 0;
		$valorprodMP = 0;	
		foreach($items as $v){
			$valorprodMP = ($v->valor_compra*$v->cantidad_real);
			$valorprod = ($valorprod+$valorprodMP);
			$cant_prod = ($cant_prod + $v->cantidad_real);
			if(!$v->lote){
				$lote1=0;
			}else{
				$lote1=$v->lote;
			}
			$produccion_detalle = array(
			'id_produccion' => $idproduccion,
			'id_producto' => $v->id_producto,
			'id_existencia' => $v->id_existencia,
			'nom_producto' => $v->nom_producto,
			'valor_compra' => $v->valor_compra,
			'lote' => $lote1,
			'cantidad' => $v->cantidad,
			'id_bodega' => 1,
			'cantidad_pro' => $v->cantidad_real,
			'valor_produccion' => ($v->valor_compra* $v->cantidad_real),			
			);		
		$this->db->insert('produccion_detalle', $produccion_detalle);
		
		$datos2 = array(
			'num_movimiento' => $numproduccion,
		    'id_producto' => $v->id_producto,
		    'id_tipo_movimiento' => 23,
		    'valor_producto' =>  $v->valor_compra,
		    'cantidad_salida' => $v->cantidad_real,
		    'fecha_movimiento' => $fechaproduccion,
		    'id_bodega' => 1,
		    'lote' => $lote1,
		    'id_cliente' => $idcliente
		);
		$this->db->insert('existencia_detalle', $datos2);	   	
		};

		$puc = ($valorprod/$cantidadproduccion);

		$query = $this->db->query('SELECT * FROM productos WHERE id="'.$idproducto.'"');
		if($query->num_rows()>0){
		$row = $query->first_row();
		$query2 = $this->db->query('SELECT * FROM existencia_detalle WHERE id_producto='.$idproducto.' and cantidad_entrada > '.$cero.'');	    	 
		$ppm=0;
		$cal = 1;
		if ($query2->num_rows()>0){
		foreach ($query2->result() as $r){			 	
			$ppm = $ppm + ($r->valor_producto);
			$cal = $cal +1;
		};
		$ppm = $ppm + $puc;
		$ppm = ($ppm / $cal);
		$saldo = ($row->stock)+($cantidadproduccion);
		$pmc = ($row->p_may_compra);
		if ($pmc < $puc){			 		
			$pmc = $puc;
		};			 
		}else{
			$pmc = $puc;
			$saldo = $cantidadproduccion;
			$ppm = $puc; 
		};                
		};

		$prod = array(
		 'p_ult_compra' => $puc,
		 'p_may_compra' => $pmc,
		 'p_promedio' => $ppm,
		 'fecha_ult_compra' => $fechaproduccion,
		 'stock' => $saldo,
		 'fecha_vencimiento' => $fechavenc,
		 'u_lote' => $lote		 
		);
		$this->db->where('id', $idproducto);
		$this->db->update('productos', $prod);

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
			$saldo = $cant_prod;
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
		        'valor_producto' => ($valorprod/$cantidadproduccion),
		        'saldo' => $cantidadproduccion,
		        'fecha_vencimiento' => $fechavenc
		);

		$this->db->insert('existencia_detalle', $datos2);	   	
									
		$produccion = array(
	        'fecha_termino' => $fechaproduccion,
	        'cantidad_prod' => $cant_prod,
	        'cant_real' => $cantidadproduccion,
	        'hora_termino' => $horatermino,
	        'hora_inicio' => $horainicio,
	        'lote' => $lote,
	        'valor_prod' => ($valorprod/$cantidadproduccion),
	        'fecha_vencimiento' => $fechavenc,
	        'estado' => 2
		);
		$this->db->where('id', $idproduccion);		  
		$this->db->update('produccion', $produccion);
		
		$query = $this->db->query('SELECT * FROM productos WHERE id="'.$idproducto.'"');
		if($query->num_rows()>0){
		$row = $query->first_row();
		$query2 = $this->db->query('SELECT * FROM existencia_detalle WHERE id_producto='.$idproducto.' and cantidad_entrada > '.$cero.'');	    	 
		$ppm=0;
		$cal = 1;
		if ($query2->num_rows()>0){
		foreach ($query2->result() as $r){			 	
			$ppm = $ppm + ($r->valor_producto);
			$cal = $cal +1;
		};
		$ppm = $ppm + $puc;
		$ppm = ($ppm / $cal);
		$saldo = ($row->stock)+($cantidadproduccion);
		$pmc = ($row->p_may_compra);
		if ($pmc < $puc){			 		
			$pmc = $puc;
		};			 
		};                
		};

		$prod = array(
		 'p_promedio' => $ppm,
		 );
		$this->db->where('id', $idproducto);
		$this->db->update('productos', $prod);	
			
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
		       <td width="140px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Descripcion</td>
		        <td width="395px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Descripcion</td>
		        <td width="140px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="140px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="140px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Valor Compra</td>
		        <td width="140px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="140px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Porcentaje</td>
		        <td width="140px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad Pro</td>
		        <td width="1408px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Porcentaje Pro</td>
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
			<td style="text-align:left"><h2>'.$producto->codigo.'<h2></td>
			<td style="text-align:left"><h2>'.$producto->nombre.'<h2></td>
			<td style="text-align:left"></td>
			<td style="text-align:left"></td>
			<td style="text-align:right"><h1>'.number_format($v->valor_compra, 2, '.', ',').'<h1></td>	
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

	public function exportPDF3(){
		$idproduccion = $this->input->get('idproduccion');
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido, p.num_pedido as num_pedido, v.nombre as nom_vendedor, pr.codigo as codigo FROM produccion acc
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
		
		//$items = $this->db->get_where('produccion_detalle', array('id_produccion' => $idproduccion));
		//variables generales
		$codigo1 = $row->num_produccion;
		$nombre_contacto = $row->nom_cliente;
		$vendedor = $row->nom_vendedor;
		//$observacion = $row->observa;
		$fecha = $row->fecha_produccion;
		$hora = $row->hora_inicio;
		$cantidad = $row->cantidad;
		$codigo = $row->codigo;
		$nom_producto = $row->nom_producto;
		
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
		<title>FORMULARIO TERMINO DE PRODUCCION</title>
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
	          <p><h3>Produccion N°: '.$codigo1.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>FECHA INICIO : '.$fecha.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>HORA INICIO : '.$hora.'</h3></p>
	          <!--p>&nbsp;</p-->		         
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>FORMULARIO TERMINO DE PRODUCCION</h1></td>
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
					</h2></table>
					<table width="687px" border="0">
		    		<tr>		    		
		    		<td width="147px">VENDEDOR:</td>
		    		<td width="540px">'.$row->nom_vendedor.'</td>
		    		</tr>
		    		</table>
		    		<table width="687px" border="0">
		    		<tr>
		    		<td width="80px">CODIGO</td>
		    		<td width="100px"><h3>'.$codigo.'</h3></td>
		    		<td width="100px">PRODUCTO</td>
		    		<td width="503"><h3>'.$nom_producto.'</h3></td>
		    		<td width="60px">LOTE</td>
		    		<td width="80px">_____________________</td>	    		
		    		</tr>		    		
		    	</table>
		    	<table width="987px" border="0">
		    		<tr>
		    		<td width="100px">CANTIDAD:</td>
		    		<td width="100px"><h3>'.number_format($cantidad, 2, ',', '.').'</h3></td>
		    		<td width="137px">CANTIDAD PRODUCCION:</td>
		    		<td width="127px">____________________</td>
		    		<td width="117px">HORA INICIO:</td>
		    		<td width="127px">____________________</td>
		    		<td width="100px">HORA FIN:</td>
		    		<td width="127px">____________________</td>
		    		</tr>
		    		
		    	</table>

			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		      <tr>
		        <td width="395px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >CODIGO</td>
		        <td width="248px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >NOMBRE PRODUCTO</td>
		        <td width="48px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >CANTIDAD</td>
		        <tr><td colspan="5">&nbsp;</td></tr>
		        
		       </tr>';
		$descripciones = '';
		$i = 0;
		/*foreach($items->result() as $v){
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
		}*/

		// RELLENA ESPACIO*/
		while($i < 17){

			$html .= '<tr><td colspan="5">&nbsp;</td></tr>
			<tr><td colspan="5">____________________________ >_____________________________________________________________________________>____________________________</td>
			</tr>
			<tr><td colspan="5">&nbsp;</td></tr>';
			$i++;
		};


		$html .= '<tr><td colspan="5">&nbsp;</td></tr></table></td>
		  </tr>
		   
		  <tr>
		  	<td colspan="2" rowspan="6" style="font-size: 12px;border-bottom:1pt solid black;border-top:1pt solid black;border-left:1pt solid black;border-right:1pt solid black;text-align:left;">'.$observacion.'</td>
		  	<td>
				
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
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido, p.num_pedido as num_pedido, v.nombre as nom_vendedor, pr.nombre as nom_productos, pr.codigo as codigopro FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		left join vendedores v on (p.id_vendedor = v.id)
		left join productos pr on (acc.id_producto = pr.id)
		WHERE acc.id = "'.$idproduccion.'"');

		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		$idpro = $row->id;
		//items
		//$items = $this->db->get_where('formula_detalle', array('id_formula' => $idformula));
		
		$items = $this->db->get_where('produccion_detalle', array('id_produccion' => $idpro));
		//variables generales
		$numpro = $row->num_produccion;
		$codigo = $row->codigopro;
		$nombre_contacto = $row->nom_cliente;
		$vendedor = $row->nom_vendedor;
		$nombreproducto = $row->nom_productos;
		$cantreal = $row->cant_real;
		$cantidad = $row->cantidad_prod;
		$lote = $row->lote;
		$cprod = $row->valor_prod;
		$encargado = $row->encargado;

		//print_r($row);
		//exit;

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
        <p>Fonos: </p>
		    <p>http://www.lircay.cl</p>
		    </td>
	    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
	          <p><h3>Produccion N°: '.$numpro.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Fecha Inicio: '.$fecha.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Fecha Termino: '.$fechatermino.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Hora Inicio: '.$hora.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Hora Termino: '.$horatermino.'</h3></p>
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
					</h2></table>
					<table width="6887px" border="0">
		    		<tr>		    		
		    		<td width="127px">VENDEDOR:</td>
		    		<td width="340px">'.$row->nom_vendedor.'</td>
		    		<td width="127px">ENCARGADO:</td>
		    		<td width="240px">'.$row->encargado.'</td>
		    		</tr>
		    		</table>
		    		<table width="687px" border="0">
		    		<tr>
		    		<td width="80px">CODIGO</td>
		    		<td width="100px"><h3>'.$codigo.'</h3></td>
		    		<td width="100px">PRODUCTO</td>
		    		<td width="380"><h3>'.$nombreproducto.'</h3></td>
		    		<td width="60px">LOTE</td>
		    		<td width="80px"><h3>'.$lote.'<h3></td>	   
		    		<td width="60px">VALOR PRODUC.</td>
		    		<td width="80px"><h3>'.$cprod.'<h3></td>	   
		    		
		    		 		
		    		</tr>		    		
		    	</table>
		    	<table width="987px" border="0">
		    		<tr>
		    		<td width="100px">CANTIDAD:</td>
		    		<td width="100px"><h3>'.number_format($cantidad, 2, ',', '.').'</h3></td>
		    		<td width="137px">CANTIDAD PRODUCCION:</td>
		    		<td width="127px"><h3>'.number_format($cantreal, 2, ',', '.').'<h3></td>
		    		<td width="117px">HORA INICIO:</td>
		    		<td width="127px"><h3>'.$hora.'<h3></td>
		    		<td width="100px">HORA FIN:</td>
		    		<td width="127px"><h3>'.$horatermino.'<h3></td>
		    		</tr>
		    		
		    	</table>

			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		      <tr>
		        <td width="150px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Codigo</td>
		        <td width="349px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Descripcion</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Lote</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Valor Compra</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad Pro</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Porcentaje Pro</td>
		       </tr>';
		$descripciones = '';
		$i = 0;
		$porcentaje_pro =0;
		
		foreach($items->result() as $v){
			//$i = 0;
			//while($i < 30){
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];

			$porcentaje_pro = (($v->cantidad_pro /$cantreal)*100);
			
			$html .= '<tr>
			<td style="text-align:left">'.$producto->codigo.'</td>
			<td style="text-align:left">'.$producto->nombre.'</td>
			<td style="text-align:center">'.$v->lote.'</td>
			<td style="text-align:right">'.number_format($v->valor_compra, 2, '.', ',').'</td>	
			<td align="right"> '.number_format($v->cantidad, 2, '.', ',').'</td>
			<td align="right"></td>
			<td align="right"> '.number_format($v->cantidad_pro, 2, '.', ',').'</td>
			<td align="right">% '.number_format($porcentaje_pro, 2, '.', ',').'</td>
			</tr>';
			//print_r($items);
		    //exit;

			
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

	public function exportPDF5(){
		$idproduccion = $this->input->get('idproduccion');
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido, p.num_pedido as num_pedido, v.nombre as nom_vendedor, pr.nombre as nom_productos, pr.codigo as codigopro FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		left join vendedores v on (p.id_vendedor = v.id)
		left join productos pr on (acc.id_producto = pr.id)
		WHERE acc.num_produccion = "'.$idproduccion.'"');

		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		$idpro = $row->id;
		//items
		//$items = $this->db->get_where('formula_detalle', array('id_formula' => $idformula));
		
		$items = $this->db->get_where('produccion_detalle', array('id_produccion' => $idpro));
		//variables generales
		$numpro = $row->num_produccion;
		$codigo = $row->codigopro;
		$nombre_contacto = $row->nom_cliente;
		$vendedor = $row->nom_vendedor;
		$nombreproducto = $row->nom_productos;
		$cantreal = $row->cant_real;
		$cantidad = $row->cantidad_prod;
		$lote = $row->lote;
		$cprod = $row->valor_prod;
		$encargado = $row->encargado;

		//print_r($row);
		//exit;

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
        <p>Fonos: </p>
		    <p>http://www.lircay.cl</p>
		    </td>
	    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
	          <p><h3>Produccion N°: '.$numpro.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Fecha Inicio: '.$fecha.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Fecha Termino: '.$fechatermino.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Hora Inicio: '.$hora.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Hora Termino: '.$horatermino.'</h3></p>
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
					</h2></table>
					<table width="6887px" border="0">
		    		<tr>		    		
		    		<td width="127px">VENDEDOR:</td>
		    		<td width="340px">'.$row->nom_vendedor.'</td>
		    		<td width="127px">ENCARGADO:</td>
		    		<td width="240px">'.$row->encargado.'</td>
		    		</tr>
		    		</table>
		    		<table width="687px" border="0">
		    		<tr>
		    		<td width="80px">CODIGO</td>
		    		<td width="100px"><h3>'.$codigo.'</h3></td>
		    		<td width="100px">PRODUCTO</td>
		    		<td width="383"><h3>'.$nombreproducto.'</h3></td>
		    		<td width="60px">LOTE</td>
		    		<td width="80px"><h3>'.$lote.'<h3></td>	  
		    		<td width="60px">VALOR PRODUC.</td>
		    		<td width="80px"><h3>'.$cprod.'<h3></td>	    		
		    		</tr>		    		
		    	</table>
		    	<table width="987px" border="0">
		    		<tr>
		    		<td width="100px">CANTIDAD:</td>
		    		<td width="100px"><h3>'.number_format($cantidad, 2, ',', '.').'</h3></td>
		    		<td width="137px">CANTIDAD PRODUCCION:</td>
		    		<td width="127px"><h3>'.number_format($cantreal, 2, ',', '.').'<h3></td>
		    		<td width="117px">HORA INICIO:</td>
		    		<td width="127px"><h3>'.$hora.'<h3></td>
		    		<td width="100px">HORA FIN:</td>
		    		<td width="127px"><h3>'.$horatermino.'<h3></td>
		    		</tr>
		    		
		    	</table>

			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		      <tr>
		        <td width="150px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Codigo</td>
		        <td width="349px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Descripcion</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Valor Compra</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad Pro</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Porcentaje Pro</td>
		       </tr>';
		$descripciones = '';
		$i = 0;
		$porcentaje_pro =0;
		
		foreach($items->result() as $v){
			//$i = 0;
			//while($i < 30){
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];

			$porcentaje_pro = (($v->cantidad_pro /$cantreal)*100);
			
			$html .= '<tr>
			<td style="text-align:left">'.$producto->codigo.'</td>
			<td style="text-align:left">'.$producto->nombre.'</td>
			<td style="text-align:left"></td>
			<td style="text-align:right">'.number_format($v->valor_compra, 2, '.', ',').'</td>	
			<td align="right"> '.number_format($v->cantidad, 2, '.', ',').'</td>
			<td align="right"></td>
			<td align="right"> '.number_format($v->cantidad_pro, 2, '.', ',').'</td>
			<td align="right">% '.number_format($porcentaje_pro, 2, '.', ',').'</td>
			</tr>';
			//print_r($items);
		    //exit;

			
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

	public function exportPDF7(){
		$idproduccion = $this->input->get('idproduccion');
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido, p.num_pedido as num_pedido, v.nombre as nom_vendedor, pr.nombre as nom_productos, pr.codigo as codigopro FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		left join vendedores v on (p.id_vendedor = v.id)
		left join productos pr on (acc.id_producto = pr.id)
		WHERE acc.id = "'.$idproduccion.'"');

		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		$idpro = $row->id;
		//items
		//$items = $this->db->get_where('formula_detalle', array('id_formula' => $idformula));
		
		$items = $this->db->get_where('produccion_detalle', array('id_produccion' => $idpro));
		//variables generales
		$numpro = $row->num_produccion;
		$codigo = $row->codigopro;
		$nombre_contacto = $row->nom_cliente;
		$vendedor = $row->nom_vendedor;
		$nombreproducto = $row->nom_productos;
		$cantreal = $row->cant_real;
		$cantidad = $row->cantidad;
		$lote = $row->lote;
		$encargado = $row->encargado;
		$canproduc=0;

		foreach($items->result() as $v){
			$canproduc = $canproduc + $v->cantidad;
		};

		//print_r($row);
		//exit;

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
        <p>Fonos: </p>
		    <p>http://www.lircay.cl</p>
		    </td>
	    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
	          <p><h3>Produccion N°: '.$numpro.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Fecha Inicio: '.$fecha.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Fecha Termino: '.$fechatermino.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Hora Inicio: '.$hora.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Hora Termino: '.$horatermino.'</h3></p>
	          <!--p>&nbsp;</p-->		         
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>PROCESO PRODUCCION</h1></td>
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
					</h2></table>
					<table width="6887px" border="0">
		    		<tr>		    		
		    		<td width="127px">VENDEDOR:</td>
		    		<td width="340px">'.$row->nom_vendedor.'</td>
		    		<td width="127px">ENCARGADO:</td>
		    		<td width="240px">'.$row->encargado.'</td>
		    		</tr>
		    		</table>
		    		<table width="787px" border="0">
		    		<tr>
		    		<td width="80px">CODIGO</td>
		    		<td width="100px"><h3>'.$codigo.'</h3></td>
		    		<td width="100px">PRODUCTO</td>
		    		<td width="403"><h3>'.$nombreproducto.'</h3></td>
		    		<td width="60px"><h3>LOTE</h3></td>
		    		<td width="120px"><h3>______________</h3></td>	  
		    		<td width="60px"><h3>CICLOS</td>
		    		<td width="80px"><h3>______________</h3></td>	    		
		    		</tr>		    		
		    	</table>
		    	<table width="987px" border="0">
		    	<tr>
		    		<td></td>
		    	</tr>
		    	</table>
		    	<table width="987px" border="0">
		    		<tr>
		    		<td width="100px">CANTIDAD:</td>
		    		<td width="100px"><h3>'.number_format($cantidad, 2, ',', '.').'</h3></td>
		    		<td width="287px">CANTIDAD PRODUCCION:</td>
		    		<td width="107px"><h3>'.number_format($canproduc, 2, ',', '.').'</h3></td>
		    		<td width="187px"><h3>CANTIDAD REAL:</h3></td>
		    		<td width="107px"><h3>______________</h3></td>		    		
		    	</table>
		    	<table width="987px" border="0">
		    	<tr>
		    		<td></td>
		    	</tr>
		    	</table>
		    	<table width="987px" border="0">
		    		<tr>			    		
		    		<td width="117px">HORA INICIO:</td>
		    		<td width="127px"><h3>______________<h3></td>
		    		<td width="100px">HORA FIN:</td>
		    		<td width="127px"><h3>______________</h3></td>
		    		<td width="100px">FECHA FIN:</td>
		    		<td width="127px"><h3>______________</h3></td>
		    		</tr>		    		
		    	</table>

			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
			<table width="1936px" cellspacing="0" cellpadding="0" >
		      <tr>
		        <td width="100px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" ><h3>Codigo</h3></td>
		        <td width="800px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ><h3>Descripcion</h3></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Valor Compra</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >% Formula</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad Pro</td>
				</tr>';
		$descripciones = '';
		$i = 0;
		$porcentaje_pro =0;
		
		foreach($items->result() as $v){
			//$i = 0;
			//while($i < 30){
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];

			$porcentaje_pro = (($v->cantidad /$canproduc)*100);
			
			$html .= '
			<tr>
			<td style="text-align:left">&nbsp;</td>
			</tr>
			<tr>
			<td style="text-align:left"><h3>'.$producto->codigo.'</h3></td>
			<td width="449px" style="text-align:left"><h3>'.$producto->nombre.'<h3></td>
			<td style="text-align:left"></td>
			<td style="text-align:right"><h3>'.number_format($v->valor_compra, 2, '.', ',').'</h3></td>	
			<td align="right"><h3> '.number_format($v->cantidad, 2, '.', ',').'</h3></td>
			<td align="right"><h3>% '.number_format($porcentaje_pro, 2, '.', ',').'</h3></td>
			<td align="right"> ______________</td>			
			</tr>';
			//print_r($items);
		    //exit;

			
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

	public function exportPDF4(){
		$idproduccion = $this->input->get('idproduccion');
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido, p.num_pedido as num_pedido, v.nombre as nom_vendedor, pr.nombre as nom_productos, pr.codigo as codigopro FROM produccion acc
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
		$numpro = $row->num_produccion;
		$codigo = $row->codigopro;
		$nombre_contacto = $row->nom_cliente;
		$vendedor = $row->nom_vendedor;
		$nombreproducto = $row->nom_productos;
		$cantreal = $row->cant_real;
		$cantidad = $row->cantidad;
		$lote = $row->lote;
		$encargado = $row->encargado;
		$canproduc=0;

		foreach($items->result() as $v){
			$canproduc = $canproduc + $v->cantidad;
		};

		//print_r($row);
		//exit;

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
        <p>Fonos: </p>
		    <p>http://www.lircay.cl</p>
		    </td>
	    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
	          <p><h3>Produccion N°: '.$numpro.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Fecha Inicio: '.$fecha.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Fecha Termino: '.$fechatermino.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Hora Inicio: '.$hora.'</h3></p>
	          <!--p>&nbsp;</p-->
	          <p><h3>Hora Termino: '.$horatermino.'</h3></p>
	          <!--p>&nbsp;</p-->		         
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>PROCESO PRODUCCION</h1></td>
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
					</h2></table>
					<table width="6887px" border="0">
		    		<tr>		    		
		    		<td width="127px">VENDEDOR:</td>
		    		<td width="340px">'.$row->nom_vendedor.'</td>
		    		<td width="127px">ENCARGADO:</td>
		    		<td width="240px">'.$row->encargado.'</td>
		    		</tr>
		    		</table>
		    		<table width="787px" border="0">
		    		<tr>
		    		<td width="80px">CODIGO</td>
		    		<td width="100px"><h3>'.$codigo.'</h3></td>
		    		<td width="100px">PRODUCTO</td>
		    		<td width="403"><h3>'.$nombreproducto.'</h3></td>
		    		<td width="60px"><h3>LOTE</h3></td>
		    		<td width="120px"><h3>______________</h3></td>	  
		    		<td width="60px"><h3>CICLOS</td>
		    		<td width="80px"><h3>______________</h3></td>	    		
		    		</tr>		    		
		    	</table>
		    	<table width="987px" border="0">
		    	<tr>
		    		<td></td>
		    	</tr>
		    	</table>
		    	<table width="987px" border="0">
		    		<tr>
		    		<td width="100px">CANTIDAD:</td>
		    		<td width="100px"><h3>'.number_format($cantidad, 2, ',', '.').'</h3></td>
		    		<td width="287px">CANTIDAD PRODUCCION:</td>
		    		<td width="107px"><h3>'.number_format($canproduc, 2, ',', '.').'</h3></td>
		    		<td width="187px"><h3>CANTIDAD REAL:</h3></td>
		    		<td width="107px"><h3>______________</h3></td>		    		
		    	</table>
		    	<table width="987px" border="0">
		    	<tr>
		    		<td></td>
		    	</tr>
		    	</table>
		    	<table width="987px" border="0">
		    		<tr>			    		
		    		<td width="117px">HORA INICIO:</td>
		    		<td width="127px"><h3>______________<h3></td>
		    		<td width="100px">HORA FIN:</td>
		    		<td width="127px"><h3>______________</h3></td>
		    		<td width="100px">FECHA FIN:</td>
		    		<td width="127px"><h3>______________</h3></td>
		    		</tr>		    		
		    	</table>

			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
			<table width="1936px" cellspacing="0" cellpadding="0" >
		      <tr>
		        <td width="100px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" ><h3>Codigo</h3></td>
		        <td width="800px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ><h3>Descripcion</h3></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Valor Compra</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >% Formula</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad Pro</td>
				</tr>';
		$descripciones = '';
		$i = 0;
		$porcentaje_pro =0;
		
		foreach($items->result() as $v){
			//$i = 0;
			//while($i < 30){
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];

			$porcentaje_pro = (($v->cantidad / $canproduc )*100);
			
			$html .= '
			<tr>
			<td style="text-align:left">&nbsp;</td>
			</tr>
			<tr>
			<td style="text-align:left"><h3>'.$producto->codigo.'</h3></td>
			<td width="449px" style="text-align:left"><h3>'.$producto->nombre.'<h3></td>
			<td style="text-align:left"></td>
			<td style="text-align:right"><h3>'.number_format($v->valor_compra, 2, '.', ',').'</h3></td>	
			<td align="right"><h3> '.number_format($v->cantidad, 2, '.', ',').'</h3></td>
			<td align="right"><h3>% '.number_format($porcentaje_pro, 2, '.', ',').'</h3></td>
			<td align="right"> ______________</td>			
			</tr>';
			//print_r($items);
		    //exit;

			
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
