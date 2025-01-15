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


	public function getFormulasporproducir(){
		
		$query = $this->db->query("SELECT 		DISTINCT 
													f.id
													,f.nombre_formula
													,f.cantidad
									FROM 			pedidos_detalle d
									INNER JOIN	formula f ON d.id_formula =  f.id
									INNER JOIN 	pedidos p ON d.id_pedido = p.id
									WHERE 		d.idestadoproducto = 2
									AND 			p.estado not in  (2,3)");


		$data = array();
		if(count($query->result()) > 0){
			foreach ($query->result() as $row)
			{
				$row->texto = $row->nombre_formula;
				$data[] = $row;
			}
			
		}else{
			$data[] = array('id' => '','nombre_formula' => '','cantidad' => 0,'texto' => '');
		}

        $resp['success'] = true;
        //$resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}	




	public function getProductosFormulabyiddetalle(){
		


		$iddetallelinea = $this->input->post('iddetallelinea');
		$porct_solicitud = $this->input->post('porct_solicitud');

		$porct_solicitud = $porct_solicitud != '' ? $porct_solicitud : 1;


		$sql_formula = $iddetallelinea != '' ? " and d.id_detalle_pedido = '" . $iddetallelinea . "'": "and 3=4";
	
		$query = $this->db->query("SELECT 			d.id_producto
													,pro.codigo
													,pro.nombre as nombre_producto
													,d.id_bodega
													,d.valor_compra
													,ROUND(d.cantidad*" . $porct_solicitud . ",1) AS cantidad
													,d.valor_produccion
													,d.porcentaje
									FROM 			formula_pedido d
									INNER JOIN 	pedidos p ON d.id_pedido = p.id
									INNER JOIN 	productos pro ON d.id_producto = pro.id
									WHERE 		1 = 1
									" . $sql_formula );


		$data = array();
		if(count($query->result()) > 0){
			foreach ($query->result() as $row)
			{
				$data[] = $row;
			}
			
		}else{
			$data[] = array('id_producto' => '','codigo' => '','nombre_producto' => '','id_bodega' =>  0,'valor_compra' =>  '','cantidad' => '','valor_produccion' => '','porcentaje' => 0);
		}

        $resp['success'] = true;
        //$resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}	



	public function getPedidosFormula(){
		


		$idformula = $this->input->post('idformula');
		$iddetalle = $this->input->post('iddetalle');
		$sql_formula = $idformula != '' ? " and d.id_formula = '" . $idformula . "'": "and 3=4";
		$sql_detalle = $iddetalle != '' ? " and d.id = '" . $iddetalle . "'": "";

		$query = $this->db->query("SELECT 			d.id
													,pro.codigo
													,pro.nombre as producto
													,d.num_pedido
													,p.nombre_cliente
													,(d.cantidad - d.cantidad_solicitada) as cantidad_disponible
													,d.cantidad as cantidad_total
													,pro.stock
													,d.id_formula
									FROM 			pedidos_detalle d
									INNER JOIN 	pedidos p ON d.id_pedido = p.id
									INNER JOIN 	productos pro ON d.id_producto = pro.id
									WHERE 		d.idestadoproducto = 2
									AND  		p.estado not in  (2,3)
									" . $sql_formula . " " . $sql_detalle);


		$data = array();
		if(count($query->result()) > 0){
			foreach ($query->result() as $row)
			{
				$row->texto = $row->codigo . ' | ' . $row->producto . ' | # PEDIDO: ' . $row->num_pedido . ' | ' . $row->nombre_cliente. ' | PENDIENTE ' . $row->cantidad_disponible;
				$data[] = $row;
			}
			
		}else{
			$data[] = array('id' => '','codigo' => '','producto' => '','num_pedido' =>  0,'cliente' =>  '','stock' =>  0,'cantidad_disponible' =>  0,'cantidad_total' =>  0,'formula' =>  0,'texto' => '');
		}

        $resp['success'] = true;
        //$resp['total'] = $countAll;
        $resp['data'] = $data;

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


		$array_email = array();

		$this->load->model('facturaelectronica');
		$email_data = $this->facturaelectronica->get_email();

		//echo '<pre>';
		//$familia = '8';
		foreach($query->result() as $item){	

				//var_dump($item); exit;

				$veterinaria= $item->veterinaria;
				$productos= $item->productos;
				$ganado= $item->ganado;				
				$email = $item->email;
				//var_dump('productos:' . $veterinaria);
				//var_dump('ganado:' . $ganado);
				//var_dump('productos:' . $productos);
				//var_dump('email:' . $email);
				if(($veterinaria == 'SI' && $familia == '1') || ($ganado == 'SI' && $familia == '3') || ($productos == 'SI' && $familia == '8')){
						//echo 'agrega<br>';
						array_push($array_email, $email);

				}

		}		
		    
			//	var_dump($array_email);
	    //$array_email = array('rodrigo.gonzalez@arnou.cl');
	    	//	var_dump($array_email); exit;
	  // $this->facturaelectronica->envia_mail($email_data->email_intercambio,$array_email,'Alerta Stock Crítico',$mensaje,'html','Arnou Alertas');	

	   $this->facturaelectronica->envia_mail_sb($email_data->email_intercambio,$array_email,'Alerta Stock Crítico',$mensaje,'html','Arnou Alertas');	


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


	public function termino2(){

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




	public function getAllProd(){
		
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

	public function noProducir(){

		$iddtallepedidos = $this->input->post('iddetalle');
		$idformula = $this->input->post('idformula');


		$this->db->select("d.id_pedido",false)
						  ->from('pedidos_detalle d')
						  ->where('d.id',$iddtallepedidos); 	                  
		$query = $this->db->get();		

		$pedido_detalle = $query->row();
		$idpedido = $pedido_detalle->id_pedido;
		

		$estado_nuevo_detalle = 5;
		$pedidos_detalle_log = array(
									'idproductodetalle' => $iddtallepedidos,
									'idestado' => $estado_nuevo_detalle,
									'fecha' => date('Y-m-d H:i:s')
								);
		$this->db->insert('pedidos_detalle_log_estados', $pedidos_detalle_log); 	

			
		$this->db->where('id', $iddtallepedidos);
		$this->db->update('pedidos_detalle', array('idestadoproducto' => $estado_nuevo_detalle));		
		//var_dump($iddetalle);


		$this->db->select("d.idestadoproducto",false)
						  ->from('pedidos_detalle d')
						  ->where('d.id_pedido',$idpedido); 	                  
		$query = $this->db->get();		
		$pedido_detalle = $query->result();

		$pedidocompleto = true;
		foreach ($pedido_detalle as $pdetalle) {

			if($pdetalle->idestadoproducto != 5){
				$pedidocompleto = false;
			}

		}



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

		

	}

	public function savesolicita(){
		
		//echo '<pre>';
		//var_dump($_POST); //exit;
		
		$resp = array();
		$numproduccion = $this->input->post('numproduccion');
		$fechaproduccion = $this->input->post('fechaproduccion');
		$idformula = $this->input->post('idformula');
		$lote = $this->input->post('lote');
		$ciclos = $this->input->post('ciclos');
		$items = json_decode($this->input->post('items'));
		$productos = json_decode($this->input->post('productos'));	
		$horainicio = $this->input->post('horainicio');
		$encargado = $this->input->post('encargado');
					


		foreach($productos as $p){
			$idformula = $p->formula;
		}

		//var_dump($idformula);
		//exit;
        $this->db->select('id, nombre_formula')
          ->from('formula')
          ->where('id',$idformula);
        $query = $this->db->get();
        $data_formula = $query->row();


        $nombreformula = $data_formula->nombre_formula;



		$produccion = array(
	        'id_pedido' => 0,
	        'id_formula_pedido' => $idformula,
	        'id_cliente' => 0,
	        'num_produccion' => $numproduccion,
	        'fecha_produccion' => $fechaproduccion,
	        'nom_formula' => $nombreformula,
	        'nom_producto' => '',
	        'id_producto' => 0,
	        'cantidad' => 0,
	        'lote' => $lote,
	        'ciclos' => $ciclos,
	        'hora_inicio' => $horainicio,
	        'encargado' => $encargado,
	        'estado' => 4
		);






		$this->db->insert('produccion', $produccion); 
		$idproduccion = $this->db->insert_id();


		$cantidad_total = 0;
		$fecha_venc = '';
		foreach($productos as $p){



	        $this->db->select('d.id, d.id_pedido, d.id_producto, p.id_cliente, prod.nombre as nomproducto, d.cantidad, p.fecha_pedido, DATE_ADD(p.fecha_pedido, INTERVAL 360 DAY) AS fecha_vencimiento',false)
	          ->from('pedidos_detalle d')
	          ->join('pedidos p','d.id_pedido = p.id')
	          ->join('productos prod','d.id_producto = prod.id')
	          ->where('d.id',$p->id);
	        $query = $this->db->get();
	        $data_detalle_pedido = $query->row();			


			$produccion_detalle_pedidos = array(
				'id_produccion' => $idproduccion,
				'id_cliente' => $data_detalle_pedido->id_cliente,
		        'id_pedido' => $data_detalle_pedido->id_pedido,
		        'id_formula' => $p->formula,
		        'id_detalle_pedido' => $data_detalle_pedido->id,
		        'nom_producto' => $data_detalle_pedido->nomproducto,
		        'id_producto' => $data_detalle_pedido->id_producto,
		        'cantidad' =>  $p->cantidad_disponible,
		        'cantidad_prod' => 0,
		        'cant_real' => 0,
		        'valor_prod' => 0
			);

			$fecha_venc = $data_detalle_pedido->fecha_vencimiento;

			$cantidad_total += $p->cantidad_disponible;
			$this->db->insert('produccion_detalle_pedidos', $produccion_detalle_pedidos); 
			$iddetallepedidos = $this->db->insert_id();



			$pedidos_detalle_log = array(
										'idproductodetalle' => $data_detalle_pedido->id,
										'idestado' => 3,
										'fecha' => date('Y-m-d H:i:s')
									);
			$this->db->insert('pedidos_detalle_log_estados', $pedidos_detalle_log); 	

				
				

			$this->db->query("UPDATE pedidos_detalle SET cantidad_solicitada = cantidad_solicitada + " . $p->cantidad_disponible . " where id = " . $data_detalle_pedido->id);


			// cambia de estado solo cuando ya se completo todo
			$this->db->where('id', $data_detalle_pedido->id);
			$this->db->where('cantidad_solicitada = cantidad');
			$this->db->update('pedidos_detalle', array('idestadoproducto' => 3));	


		}


		$this->db->where('id', $idproduccion);
		$this->db->update('produccion', array('cantidad' => $cantidad_total,'fecha_vencimiento' => $fecha_venc));	


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
	        'estado' => 2,
	        'idestadopedido' => 3
			);			

			
		$this->db->where('id', $idpedido);
		$this->db->update('pedidos', $pedidos);


		$pedidos_log = array(
								'idpedido' => $idpedido,
								'idestado' => 3,
								'fecha' => date('Y-m-d H:i:s')
							);
		$this->db->insert('pedidos_log_estados', $pedidos_log); 



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




	public function save5(){

		/*

		array(12) {
			  ["fechaproduccion"]=>
			  string(10) "2025-01-15"
			  ["cantidadproduccion"]=>
			  string(3) "500"
			  ["cantidadproduccioncal"]=>
			  string(3) "464"
			  ["idbodega"]=>
			  string(1) "1"
			  ["numproduccion"]=>
			  string(5) "10111"
			  ["idproduccion"]=>
			  string(4) "8853"
			  ["lote"]=>
			  string(1) "1"
			  ["ciclos"]=>
			  string(1) "1"
			  ["fechavenc"]=>
			  string(10) "2026-01-10"
			  ["horatermino"]=>
			  string(5) "00:15"
			  ["horainicio"]=>
			  string(5) "00:15"
			  ["items"]=>
			  string(1823) "[{"id":1,"id_producto":"482","id_existencia":"0","id_tipom":"","id_tipomd":"","id_bodegaent":"","cantidad":216,"cantidad_real":216,"id_bodega":1,"precio":"","valor_compra":130,"valor":"","codigo":"30102008","nom_producto":"MAIZ M.PRIMA","lote":"","fecha":null,"fecha_vencimiento":"2025-01-15T00:00:00"},{"id":2,"id_producto":"483","id_existencia":"0","id_tipom":"","id_tipomd":"","id_bodegaent":"","cantidad":69,"cantidad_real":69,"id_bodega":1,"precio":"","valor_compra":130,"valor":"","codigo":"30102023","nom_producto":"AVENA M. PRIMA","lote":"","fecha":null,"fecha_vencimiento":"2025-01-15T00:00:00"},{"id":3,"id_producto":"480","id_existencia":"0","id_tipom":"","id_tipomd":"","id_bodegaent":"","cantidad":129,"cantidad_real":129,"id_bodega":1,"precio":"","valor_compra":85,"valor":"","codigo":"30102002","nom_producto":"AFRECHILLO M. PRIMA","lote":"","fecha":null,"fecha_vencimiento":"2025-01-15T00:00:00"},{"id":4,"id_producto":"489","id_existencia":"0","id_tipom":"","id_tipomd":"","id_bodegaent":"","cantidad":18,"cantidad_real":18,"id_bodega":1,"precio":"","valor_compra":34,"valor":"","codigo":"30102119","nom_producto":"CARBONATO DE CALCIO M.P.","lote":"","fecha":null,"fecha_vencimiento":"2025-01-15T00:00:00"},{"id":5,"id_producto":"658","id_existencia":"0","id_tipom":"","id_tipomd":"","id_bodegaent":"","cantidad":17,"cantidad_real":17,"id_bodega":1,"precio":"","valor_compra":120,"valor":"","codigo":"30102003","nom_producto":"MELAZA","lote":"","fecha":null,"fecha_vencimiento":"2025-01-15T00:00:00"},{"id":6,"id_producto":"777","id_existencia":"0","id_tipom":"","id_tipomd":"","id_bodegaent":"","cantidad":15,"cantidad_real":15,"id_bodega":1,"precio":"","valor_compra":335.35,"valor":"","codigo":"30201596","nom_producto":"LIRCAY VACA 12","lote":"","fecha":null,"fecha_vencimiento":"2025-01-15T00:00:00"}]"
			}

		*/
		//var_dump($_POST); exit;
		
		$resp = array();
		$fechaproduccion = $this->input->post('fechaproduccion');
		$fechavenc = $this->input->post('fechavenc');
		$numproduccion = $this->input->post('numproduccion');
		$cantidadproduccion = $this->input->post('cantidadproduccion');
		$cantidadproduccioncal = $this->input->post('cantidadproduccioncal');
		$idproduccion = $this->input->post('idproduccion');
		$idbodega = $this->input->post('idbodega');
		$lote = $this->input->post('lote');
		$ciclos = $this->input->post('ciclos');
		$items = json_decode($this->input->post('items'));
		$horatermino = $this->input->post('horatermino');
		$horainicio = $this->input->post('horainicio');
		$cero=0;


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


			//{"id":1,"id_producto":"480","id_existencia":"0","id_tipom":"","id_tipomd":"","id_bodegaent":"","cantidad":3705,"cantidad_real":3705,"id_bodega":1,"precio":"","valor_compra":227.74,"valor":"","codigo":"30102002","nom_producto":"AFRECHILLO M. PRIMA","lote":"","fecha":null,"fecha_vencimiento":"2024-03-26T00:00:00"}
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
		    'id_cliente' => 0
		);
		$this->db->insert('existencia_detalle', $datos2);	   	
		};

		$puc = ($valorprod/$cantidadproduccion);



		$this->db->select('d.id_producto, d.id_detalle_pedido, d.id_pedido, pd.cantidad_solicitada , pd.cantidad',false)
						  ->from('produccion_detalle_pedidos d')
						  ->join('pedidos_detalle pd','d.id_detalle_pedido = pd.id')
						  ->where('d.id_produccion',$idproduccion); 	                  
		$query = $this->db->get();		
		$prod_pedido = $query->result();



		foreach ($prod_pedido as $pedido_detalle) {
				$idproducto = $pedido_detalle->id_producto;

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

				$query_detalle = $this->db->query('SELECT * FROM existencia_detalle WHERE id_producto='.$idproducto.' and id_tipo_movimiento = 23 and num_movimiento = "' . $numproduccion . '" AND id_bodega='.$idbodega.' AND cantidad_entrada > 0');
				
				$row_detalle = $query_detalle->result();
				if($query_detalle->num_rows()==0){


					$datos2 = array(
							'num_movimiento' => $numproduccion,
					        'id_producto' => $idproducto,
					        'id_tipo_movimiento' => 23,
					        'cantidad_entrada' => $cantidadproduccion,
					        'fecha_movimiento' => $fechaproduccion,
					        'id_bodega' => $idbodega,
					        'id_cliente' => 0,
					        'lote' => $lote,
					        'valor_producto' => ($valorprod/$cantidadproduccion),
					        'saldo' => $cantidadproduccion,
					        'fecha_vencimiento' => $fechavenc
					);

					$this->db->insert('existencia_detalle', $datos2);	
				}

   


		}	

	
									
		$produccion = array(
	        'fecha_termino' => $fechaproduccion,
	        'cantidad_prod' => $cant_prod,
	        'cant_real' => $cantidadproduccion,
	        'hora_termino' => $horatermino,
	        'hora_inicio' => $horainicio,
	        'lote' => $lote,
	        'ciclos' => $ciclos,
	        'valor_prod' => ($valorprod/$cantidadproduccion),
	        'fecha_vencimiento' => $fechavenc,
	        'estado' => 2
		);
		$this->db->where('id', $idproduccion);		  
		$this->db->update('produccion', $produccion);




		foreach ($prod_pedido as $pedido_detalle) {
	
			$query = $this->db->query('SELECT * FROM productos WHERE id="'.$pedido_detalle->id_producto.'"');
			if($query->num_rows()>0){
			$row = $query->first_row();
			$query2 = $this->db->query('SELECT * FROM existencia_detalle WHERE id_producto='.$pedido_detalle->id_producto.' and cantidad_entrada > '.$cero.'');	    	 
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
			$this->db->where('id', $pedido_detalle->id_producto);
			$this->db->update('productos', $prod);	

		}

		





		// marca el estado de los productos  de un pedido
		foreach ($prod_pedido as $pedido_detalle) {

			if($pedido_detalle->cantidad_solicitada >= $pedido_detalle->cantidad){

				$pedidos_detalle_log = array(
											'idproductodetalle' => $pedido_detalle->id_detalle_pedido,
											'idestado' => 4,
											'fecha' => date('Y-m-d H:i:s')
										);
				$this->db->insert('pedidos_detalle_log_estados', $pedidos_detalle_log); 						

				$pedidos_detalle_log = array(
											'idproductodetalle' => $pedido_detalle->id_detalle_pedido,
											'idestado' => 5,
											'fecha' => date('Y-m-d H:i:s')
										);
				$this->db->insert('pedidos_detalle_log_estados', $pedidos_detalle_log); 	
			
				$this->db->where('id', $pedido_detalle->id_detalle_pedido);
				$this->db->update('pedidos_detalle', array('idestadoproducto' => 5));	

			}


		}


		
		// define si el pedido está listo según tenga todos los productos listos
		foreach ($prod_pedido as $pedido_detalle) {

				$this->db->select('id, id_producto, cantidad')
						 ->from('pedidos_detalle')
						 ->where('id_pedido',$pedido_detalle->id_pedido);

				$query = $this->db->get();
				$detalles_pedido = $query->result();
				$pedidocompleto = true;
				foreach ($detalles_pedido as $dpedido) {


					//var_dump($pedidocompleto);
					$this->db->select('id, id_producto, cantidad')
							 ->from('pedidos_detalle')
							 ->where('id',$dpedido->id)
							 ->where('idestadoproducto not in (4,5)');

					
				   $query = $this->db->get();
				  // echo $this->db->last_query();
				   $cantidad_pendiente = $query->num_rows(); //si existe alguno que no este listo, no hacer nada.  Si no hay ninguno pendiente, finalizar el pedido		
				   //$detalle_pedido = $query->result();
				   //var_dump($cantidad_pendiente);

				   if($cantidad_pendiente > 0){
				   		$pedidocompleto = false;	
				   }

				   // var_dump($pedidocompleto);
				   //$pedidocompleto = $cantidad_pendiente > 0 ? false : true;


				}


				//echo 'termina proceso';
				//var_dump($pedidocompleto);

			   if($pedidocompleto){

					$pedidos = array(
					'idestadopedido' => 8
					);			
					$this->db->where('id', $pedido_detalle->id_pedido);
					$this->db->update('pedidos', $pedidos);



					$pedidos_log = array(
											'idpedido' => $pedido_detalle->id_pedido,
											'idestado' => 8,
											'fecha' => date('Y-m-d H:i:s')
										);
					$this->db->insert('pedidos_log_estados', $pedidos_log); 




			   }





		}


		$this->Bitacora->logger("M", 'produccion', $idproduccion);
		$this->Bitacora->logger("M", 'produccion_detalle', $idproduccion);    

			
        $resp['idproduccion'] = $idproduccion;		
        $resp['success'] = true;


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
		'estado' => 3,
		'idestadopedido' => 4
		);			
		$this->db->where('id', $idpedido);
		$this->db->update('pedidos', $pedidos);



		$pedidos_log = array(
								'idpedido' => $idpedido,
								'idestado' => 4,
								'fecha' => date('Y-m-d H:i:s')
							);
		$this->db->insert('pedidos_log_estados', $pedidos_log); 

       
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
		$ciclos = $row->ciclos;
		$cprod = $row->valor_prod;
		$encargado = $row->encargado;
		$nomcliente = $row->nom_cliente;
		$rut_cliente = $row->rut_cliente;



		if($row->id_pedido == 0){

			if($nomcliente == ''){


					$this->db->select("c.rut as rut_cliente
										,c.nombres as nom_cliente
										,v.nombre as nom_vendedor
										,pr.nombre as nom_productos
										, pr.codigo as codigopro
										",false)
									  ->from('produccion_detalle_pedidos dp')
									  ->join('clientes c ','dp.id_cliente = c.id')
									  ->join('pedidos p ','dp.id_pedido = p.id')
									  ->join('vendedores v ','p.id_vendedor = v.id')
									  ->join('productos pr ','dp.id_producto = pr.id')
									  ->where('dp.id_produccion',$row->id); 	                  
					$query = $this->db->get();		
					$datos_cli = $query->row();
					$nomcliente = $datos_cli->nom_cliente;
					$rut_cliente = $datos_cli->rut_cliente;
					$vendedor = $datos_cli->nom_vendedor;
					$codigo = $datos_cli->codigopro;
					$nombreproducto = $datos_cli->nom_productos;

			}


		}

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
					<td width="395px">'. $nomcliente.'</td>
					<td width="147px">Rut:</td>
					<td width="147px">'. number_format(substr($rut_cliente, 0, strlen($rut_cliente) - 1),0,".",".")."-".substr($rut_cliente,-1).'</td>
					</tr>
					</h2></table>
					<table width="6887px" border="0">
		    		<tr>		    		
		    		<td width="127px">VENDEDOR:</td>
		    		<td width="340px">'.$vendedor.'</td>
		    		<td width="127px">ENCARGADO:</td>
		    		<td width="240px">'.$row->encargado.'</td>
		    		</tr>
		    		</table>
		    		<table width="687px" border="0">
		    		<tr>
		    		<td width="80px">CODIGO</td>
		    		<td width="60px"><h3>'.$codigo.'</h3></td>
		    		<td width="80px">PRODUCTO</td>
		    		<td width="300"><h3>'.$nombreproducto.'</h3></td>
		    		<td width="60px">LOTE</td>
		    		<td width="80px"><h3>'.$lote.'<h3></td>	   
		    		<td width="60px">CICLOS</td>
		    		<td width="80px"><h3>'.$ciclos.'<h3></td>	 		    		
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


	public function exportPDFSolicitud(){
		$idproduccion = $this->input->get('idproduccion');
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido, p.num_pedido as num_pedido, v.nombre as nom_vendedor, pr.nombre as nom_productos, pr.codigo as codigopro, f.nombre_formula FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join formula f on (acc.id_formula_pedido = f.id)
		left join pedidos p on (acc.id_pedido = p.id)
		left join vendedores v on (p.id_vendedor = v.id)
		left join productos pr on (acc.id_producto = pr.id)
		WHERE acc.id = "'.$idproduccion.'"');

		//cotizacion header
		$row = $query->result();
		$row = $row[0];

		//echo '<pre>';
		//var_dump($row); exit;

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
		$ciclos = $row->ciclos;
		$encargado = $row->encargado;
		$canproduc=0;

		foreach($items->result() as $v){
			$canproduc = $canproduc + $v->cantidad;
		};


		$this->db->select('ped.num_pedido, ped.nombre_cliente, d.nom_producto, d.cantidad',false)
						  ->from('produccion_detalle_pedidos d')
						  ->join('pedidos_detalle p','d.id_detalle_pedido = p.id')
						  ->join('pedidos ped','d.id_pedido = ped.id')
						  ->where('d.id_produccion',$idproduccion); 	                  
		$query = $this->db->get();		
		$productos = $query->result();


		//print_r($row);
		//exit;

		//$observacion = $row->observa;
		$fecha = $row->fecha_produccion;
		$fechatermino = $row->fecha_termino;		
		$hora = $row->hora_inicio == '00:00:00' ? '______________' : $row->hora_inicio;
		$horatermino= $row->hora_termino;


		//echo '<pre>';
		//var_dump($row->hora_inicio); 
		//var_dump($hora); 
		//exit;		
		
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
					<table width="6887px" border="0">
		    		<tr>		    		
		    		<td width="127px">ENCARGADO:</td>
		    		<td width="240px">'.$row->encargado.'</td>
		    		</tr>
		    		</table>
		    		<table width="787px" border="0">
		    		<tr>
		    		<td width="100px">FORMULA</td>
		    		<td width="403"><h3>'.$row->nombre_formula.'</h3></td>
		    		<td width="60px"><h3>LOTE:</h3></td>
		    		<td width="120px"><h3>' . $row->lote . '</h3></td>	  	
		    		<td width="60px"><h3>CICLOS:</h3></td>
		    		<td width="120px"><h3>' . $row->ciclos . '</h3></td>	  			    		
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
		    		<td width="100px"><h3>'.number_format($row->cantidad, 2, ',', '.').'</h3></td>
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
		    		<td width="127px"><h3>' . $hora . '<h3></td>
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
		        <td width="200px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" ><h3>Num. Pedido</h3></td>
		        <td width="300px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" ><h3>Cliente</h3></td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" ></td>
		        <td width="300px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Producto</td>
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
				</tr>';

			foreach($productos as $prod){

				$html .= '
				<tr>
				<td style="text-align:left">&nbsp;</td>
				</tr>
				<tr>
				<td width="200px" style="text-align:left"><h3>'.$prod->num_pedido.'</h3></td>
				<td width="300px" style="text-align:left"><h3>'.$prod->nombre_cliente.'<h3></td>
				<td width="148px" style="text-align:left"></td>
				<td width="300px" style="text-align:left"><h3>'.$prod->nom_producto.'</h3></td>	
				<td width="148px" align="right"><h3> '.number_format($prod->cantidad, 2, '.', ',').'</h3></td>		
				</tr>';
			}



			$html .= '<tr><td colspan="5">&nbsp;</td></tr></table></td>
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
