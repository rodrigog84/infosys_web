<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->database();
	}

	public function enviarMail(){
		
		$resp = array();
		$nombre = $this->input->post('nombre');
		$idproducto = $this->input->post('producto');
		$fechavenc = $this->input->post('fecha');
		$fecha_actual = date("d-m-Y");

		$items = $this->db->get_where('productos', array('id' => $idproducto));

		foreach($items->result() as $item){			
			$dias= $item->diasvencimiento;
		};

		if(!$dias){
			$factura=10;
		};
		
		$factura = $dias;
		$factura2 = 0;
		$fechafactura = $this->input->post('fecha');

		$fechafactura = substr($fechafactura,0,10);
		$fecha= strtotime("- $factura days", strtotime ($fechafactura));

		$fecha2 = date("d-m-Y", strtotime($fechavenc));		
		$fechaa = date('d-m-Y',$fecha);	

		
		if ($fecha_actual>$fechaa){

			$inicio = strtotime($fecha_actual);
		    $fin = strtotime($fecha2);
		    $dif = $fin - $inicio;
		    $diasFalt = intval(( ( $dif / 60 ) / 60 ) / 24);
		    
			/*print_r($fecha2);
			print_r($fecha_actual);
			print_r($diasFalt);
			exit;*/

			if ($diasFalt < 20){

			if ($diasFalt < 0){

				$mensaje="Producto ".$nombre.".    "."Esta Vencido ".$fecha2."   hace ".$diasFalt."  "."dias";
				
			}else{

				$mensaje="Producto ".$nombre.".    "."Esta Pronto a Vencer   ".$fecha2."   Faltan    ".$diasFalt."  "."dias";
				
			};
			

			$resp['success'] = true;
			$resp['dias'] = $diasFalt;		

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


		}		
				
			}

			
		}

		
		$resp['fechaa'] = $fechaa;
		$resp['fechaven'] = $fecha_actual;
        echo json_encode($resp);

	   
	}

	public function actualiza(){
		$resp = array();

		$query = $this->db->query('SELECT * FROM existencia ');
    	 $row = $query->result();
		if ($query->num_rows()>0){

			foreach ($query->result() as $row)
			{
				$saldo = $row->stock;
				$producto = $row->id_producto;
				$datos = array(
		         'stock' => $saldo,
		    	);

		    	$this->db->where('id', $producto);

		    	$this->db->update('productos', $datos);
			}

			$resp['success'] = true;

			};

		echo json_encode($resp);
	}

	public function elimina(){

	    $resp = array();
	    $idproducto = $this->input->post('idproducto');

	    $query = $this->db->query('SELECT * FROM detalle_factura_cliente WHERE id_producto ="'.$idproducto.'"');

	    if($query->num_rows()>0){

	    	 $resp['success'] = false;
	    	 echo json_encode($resp);

	    }else{


	    $query = $this->db->query('DELETE FROM productos WHERE id = "'.$idproducto.'"');

	    
	    $resp['success'] = true;
	    echo json_encode($resp);

	    	

	    };

	  }

	
	public function save(){

		$resp = array();
		
        $id = $_REQUEST['codigo'];

        $data = array(
		        'nombre' => strtoupper($_REQUEST['nombre']),
		        'codigo' => $_REQUEST['codigo'],			
		        'p_ult_compra' => $_REQUEST['p_ult_compra'],
		        'p_may_compra' => $_REQUEST['p_may_compra'],
		        'p_promedio' => $_REQUEST['p_promedio'],
		        'p_venta' => $_REQUEST['p_venta'],
		        'p_costo' => $_REQUEST['p_costo'],
		        'stock' => $_REQUEST['stock'],
		        'stock_critico' => $_REQUEST['stock_critico'],
		        'diasvencimiento' => $_REQUEST['diasvencimiento'],
		        'id_ubi_prod' => $_REQUEST['id_ubi_prod'],
		        'id_marca' => $_REQUEST['id_marca'],
		        'id_uni_medida' => $_REQUEST['id_uni_medida'],
		        'id_bodega' => $_REQUEST['id_bodega'],
		        'id_familia' => $_REQUEST['id_familia'],
		        'id_agrupacion' => $_REQUEST['id_agrupacion'],
		        'id_subfamilia' => $_REQUEST['id_subfamilia'],
		        'clasificacion' => $_REQUEST['clasificacion']
		);

	    
          $this->db->insert('productos', $data); 
          $producto = $this->db->insert_id();
          $datos3 = array(
			'id_producto' => $producto,
	        'stock' =>  0,
	        'fecha_ultimo_movimiento' => date("Y-m-d H:i:s"),
	        'id_bodega' => $_REQUEST['id_bodega'],			
		  );
		  $this->db->insert('existencia', $datos3);

          $resp['success'] = true;

          $this->Bitacora->logger("I", 'productos', $producto);
          $this->Bitacora->logger("I", 'existencias', $producto);
	      
        echo json_encode($resp);

	}
	
	
	public function update(){

		$resp = array();
		$id = $_REQUEST['id'];
		$idBodega=1;
		$stock=0;

		$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, b.nombre as nom_bodega, c.codigo as codigo FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas b on (acc.id_bodega = b.id)
			WHERE acc.id_producto="'.$id.'" and acc.id_bodega = "'.$idBodega.'"
		' );

		foreach ($query->result() as $row)
		{
			$stock = $row->stock;
			//print_r($stock);
		};

		/*print_r($stock);
		print_r($id);
		exit;*/


		$data = array(

			'nombre' => strtoupper($_REQUEST['nombre']),
	        'codigo' => $_REQUEST['codigo'],			
	        'p_ult_compra' => $_REQUEST['p_ult_compra'],
	        'p_may_compra' => $_REQUEST['p_may_compra'],
	        'p_promedio' => $_REQUEST['p_promedio'],
	        'p_venta' => $_REQUEST['p_venta'],
	        'p_costo' => $_REQUEST['p_costo'],
	        'stock' => $stock,
	        'stock_critico' => $_REQUEST['stock_critico'],
	        'diasvencimiento' => $_REQUEST['diasvencimiento'],	        
	        'id_ubi_prod' => $_REQUEST['id_ubi_prod'],
	        'id_uni_medida' => $_REQUEST['id_uni_medida'],
	        'id_bodega' => $_REQUEST['id_bodega'],
	        'id_familia' => $_REQUEST['id_familia'],
	        'id_agrupacion' => $_REQUEST['id_agrupacion'],
	        'id_subfamilia' => $_REQUEST['id_subfamilia'],
	        'clasificacion' => $_REQUEST['clasificacion'],
	    );

		$this->db->where('id', $id);		
		$this->db->update('productos', $data); 
		$resp['success'] = true;

		$this->Bitacora->logger("M", 'productos', $id);

        echo json_encode($resp);
		}

	public function buscarp(){

		$nombres = $this->input->get('nombre');

		$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id = "'.$nombres.'"');

		$row = $query->first_row();
	   	$resp['cliente'] = $row;
        $resp['success'] = true;
       
        echo json_encode($resp);

	}

	public function buscacodigo(){

		$nombres = $this->input->get('codigo');

		$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.codigo = "'.$nombres.'"');

		if($query->num_rows()>0){
	   			$row = $query->first_row();
	   			$row->p_neto = ($row->p_venta/1.19);
			   	$resp['cliente'] = $row;
		        $resp['success'] = true;
	   	}else{

	   		 $resp['success'] = false;

	   	}

	   	
        echo json_encode($resp);

	}

	
	public function getAll(){
		
		$resp = array();
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $nombres = $this->input->get('nombre');
        $familia = $this->input->get('familia');
        $subfamilia = $this->input->get('subfamilia');
        $agrupacion = $this->input->get('agrupacion');
        $opcion =  $this->input->get('opcion');
        $valor = 0;

        if (!$opcion){        	
        	$opcion = "Todos";
        };

        $countAll = $this->db->count_all_results("clientes");
        
		
		if($opcion == "Nombre"){

			if($nombres) {	        
		
			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "acc.nombre like '%".$nombre."%' and ";
	        }

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE ' . $sql_nombre . ' 1 = 1');

			$total= 0;
				
			foreach ($query->result() as $row)
			{
				$total = $total+1;
				$countAll = $total;
			}

		}else if($familia) {
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id_familia like "%'.$familia.'%"');

			$total= 0;
				
			foreach ($query->result() as $row)
			{
				$total = $total+1;
				$countAll = $total;
			}

					
		}else if($subfamilia) {
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id_subfamilia like "%'.$subfamilia.'%"');

			$total= 0;
				
			foreach ($query->result() as $row)
			{
				$total = $total+1;
				$countAll = $total;
			}
	

		}else if($agrupacion) {
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id_agrupacion like "%'.$agrupacion.'%"');

			$total= 0;
				
			foreach ($query->result() as $row)
			{
				$total = $total+1;
				$countAll = $total;
			}
		}

		};

		if($opcion == "Codigo"){

			if($nombres) {	        
		
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.codigo like "%'.$nombres.'%"');

			$total= 0;
				
			foreach ($query->result() as $row)
			{
				$total = $total+1;
				$countAll = $total;
			}

		}else if($familia) {
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id_familia like "%'.$familia.'%"');

			$total= 0;
				
			foreach ($query->result() as $row)
			{
				$total = $total+1;
				$countAll = $total;
			}

					
		}else if($subfamilia) {
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id_subfamilia like "%'.$subfamilia.'%"');

			$total= 0;
				
			foreach ($query->result() as $row)
			{
				$total = $total+1;
				$countAll = $total;
			}

		}else if($agrupacion) {
			
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id_agrupacion like "%'.$agrupacion.'%"');

			$total= 0;
				
			foreach ($query->result() as $row)
			{
				$total = $total+1;
				$countAll = $total;
			}

			  

		}

		};

		if($opcion == "Todos"){

			if($familia) {
			$count = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id_familia like "%'.$familia.'%"');

			$total= 0;
				
			foreach ($count->result() as $row)
			{
				$total = $total+1;
				$countAll = $total;
			}

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id_familia like "%'.$familia.'%"
			limit '.$start.', '.$limit.'');



		    }else if($subfamilia) {
			$count = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id_subfamilia like "%'.$subfamilia.'%"
			');

			$total= 0;
				
			foreach ($count->result() as $row)
			{
				$total = $total+1;
				$countAll = $total;
			}

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id_subfamilia like "%'.$subfamilia.'%"
			limit '.$start.', '.$limit.'
			');

			
			}else if($agrupacion) {

			$count = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id_agrupacion like "%'.$agrupacion.'%"
			');

			$total= 0;
				
			foreach ($count->result() as $row)
			{
				$total = $total+1;
				
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE acc.id_agrupacion like "%'.$agrupacion.'%"
			limit '.$start.', '.$limit.'
			');
			

			}else{ 
			
			$count = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			');   

			$total= 0;
				
			foreach ($count->result() as $row)
			{
				$total = $total+1;
				
			}

			$countAll = $total;
		
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			limit '.$start.', '.$limit.' '
			);
			
		}

	};
		
		$data = array();
						
		foreach ($query->result() as $row)
		{
			$row->p_neto = ($row->p_venta/1.19);
			$data[] = $row;
		}
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}
}
