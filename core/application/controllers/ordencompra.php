<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ordencompra extends CI_Controller {



	public function __construct()
	{
		parent::__construct();
		
		$this->load->database();
	}

	public function actualizaocompra(){

		$resp = array();

		$query2 = $this->db->query('SELECT * FROM existencia_detalle WHERE `num_o_compra`!=0');

		foreach ($query2->result() as $row)
		{

			$ocompra = $row->num_o_compra;
			$id = $row->id;

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as nombre_giro, pro.id_giro as id_giro
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			INNER JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			INNER JOIN comuna com ON (pro.id_comuna = com.id)
			INNER JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			where ctz.num_orden = '.$ocompra.' ');

			
			$row1 = $query->first_row();
			if ($row1->id_proveedor){
			$idcliente = $row1->id_proveedor;

			print_r($idcliente);

			$data2 = array(
	        'id_cliente' => $idcliente,
	    	);

			$this->db->where('id', $id);
			$this->db->update('existencia_detalle', $data2);
		    };

		}

		$resp['success'] = true;
        echo json_encode($resp);

		
	}

	public function edita(){

		$resp = array();
		$idorden = $this->input->get('idorden');

		if ($idorden){
		$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as nombre_giro, pro.id_giro as id_giro
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			INNER JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			INNER JOIN comuna com ON (pro.id_comuna = com.id)
			INNER JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			WHERE ctz.id = '.$idorden.'');	
		

		$row1 = $query->result();
		$row = $row1[0];		

	   	$row = $query->first_row();
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
			
	   	$resp['cliente'] = $row;
	    $resp['success'] = true;
        echo json_encode($resp);
        }
	}

	public function edita2(){

		$resp = array();
		$idorden = $this->input->post('idorden');

		if ($idorden){

		$items = $this->db->get_where('orden_compra_item', array('id_ordencompra' => $idorden));

	   	$data = array();

	   	foreach($items->result() as $item){
			$this->db->where('id', $item->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			$item->nombre = $producto->nombre;
			$item->precio = $item->subtotal;
			$item->precio_base = $item->subtotal;
			$item->cantidad = $item->cantidad;
			$item->id_producto = $item->id_producto;
			$item->dcto = $item->descuento;	
			$data[] = $item;
		}
	     	
	    $resp['success'] = true;
        $resp['data'] = $data; 
        echo json_encode($resp);
        }
	}

	public function updateforzada(){

		$resp = array();
		$data = json_decode($this->input->post('items'));
		$numero = $this->input->post('numero');
		$id = $this->input->post('id');
		$idbodega = $this->input->post('idbodega');
		$fecha = $this->input->post('fecha');
		$idprovee = $this->input->post('idprovee');
		$tipodocumento="4";
		
				

		$data2 = array(
	        'semicumplida' => "SI",
	        'cumplida' => "SI",
	        'forzada' => "SI"

	    );

    	$this->db->where('id', $id);

		$this->db->update('orden_compra', $data2);


        $resp['success'] = true;

        $this->Bitacora->logger("M", 'orden_compra', $id);
        
        echo json_encode($resp);

	}

	public function updatefinal(){

		$resp = array();
		$data = json_decode($this->input->post('items'));
		$numero = $this->input->post('numero');
		$id = $this->input->post('id');
		$idbodega = $this->input->post('idbodega');
		$fecha = $this->input->post('fecha');
		$numdoc = $this->input->post('numerodoc');
		$tipdoc = $this->input->post('recepcion');
		$chofer = $this->input->post('chofer');
		$id_chofer = $this->input->post('id_chofer');
		$idprovee = $this->input->post('idprovee');
		$tipodocumento="4";

		if(!$chofer){
			$chofer="";
		}

		if(!$id_chofer){
			$id_chofer=0;
		}
				

		$data2 = array(
	        'semicumplida' => "SI",
	        'id_bodega' => $idbodega
	    	);

    	$this->db->where('id', $id);

		$this->db->update('orden_compra', $data2);


		foreach($data as $v){			

		   if ($v->existe==true){ 			 

		   $producto = $v->id_producto;
		   $puc = ($v->valor);
		   $cero=0;
		   if (!$v->lote){
		   	 $v->lote=0;
		   };
		
		   $query = $this->db->query('SELECT * FROM productos WHERE id="'.$producto.'"');
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
			 	$saldo = ($row->stock)+($v->val_real);
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
	         'fecha_ult_compra' => $fecha,
	         'stock' => $saldo,
	         'fecha_vencimiento' => $v->fecha_vencimiento,
	         'u_lote' => $v->lote,
	         
	    	);

	    	$this->db->where('id', $producto);

	    	$this->db->update('productos', $prod);

	    	///GRABAR EXISTENCIA DETALL

	    	$datos2 = array(
				'num_movimiento' => $numdoc,
		        'id_producto' => $v->id_producto,
		        'id_tipo_movimiento' => $tipdoc,
		        'valor_producto' => $v->valor,
		        'cantidad_entrada' => $v->val_real,
		        'saldo' => $v->val_real,
		        'fecha_movimiento' => $fecha,
		        'fecha_vencimiento' => $v->fecha_vencimiento,
		        'lote' => $v->lote,
		        'id_cliente' => $idprovee,
		        'num_o_compra' => $numero,		        
		        'p_promedio' => $ppm,
		        'id_bodega' => $idbodega,
		        'transportista' => $chofer,
		        'id_transportista' => $id_chofer
			);

			$this->db->insert('existencia_detalle', $datos2);


			 $query = $this->db->query('SELECT * FROM existencia WHERE id_producto='.$producto.' and id_bodega = '.$idbodega.'');
	    	 $row = $query->result();
			 if ($query->num_rows()>0){

				$row = $row[0];
	 
		        if ($producto==($row->id_producto)){
		        	$saldo = ($row->stock)+ $v->val_real;
				    $datos3 = array(
					'stock' => $saldo,
			        'fecha_ultimo_movimiento' => date('Y-m-d H:i:s')
					);
					$this->db->where('id_producto', $producto);

		    	    $this->db->update('existencia', $datos3);
	    	    }else{
	    	    	$saldo = $v->cantidad;
	    	    	$datos3 = array(
					'id_producto' => $producto,
			        'stock' =>  $saldo,
			        'fecha_ultimo_movimiento' =>date('Y-m-d H:i:s'),
			        'id_bodega' => 1,
				
					);
					$this->db->insert('existencia', $datos3);
		    	 	}
				}else{					

		    	    	$datos3 = array(
						'id_producto' => $producto,
				        'stock' =>  $saldo,
				        'fecha_ultimo_movimiento' =>date('Y-m-d H:i:s'),
					    'id_bodega' => 1
						);
						$this->db->insert('existencia', $datos3);
			    	
				};

	    	if ($v->stock==$v->cantidad){

				$data2 = array(
				'cumplida' => "SI",
				'id_bodega' => $idbodega
				);

				$this->db->where('id', $id);

				$this->db->update('orden_compra', $data2);

	    		$data4 = array(
		        'cant_final' => $v->stock,
		        'valor_prom' => $v->valor,
		        'id_bodega' => $idbodega,
		        'fecha_vencimiento' => $v->fecha_vencimiento,
		        'fecha_recepcion' => date('Y-m-d H:i:s'),
		        'lote' => $v->lote,
		        'val_real' => $v->val_real,
		        'transportista' => $chofer,
		        'id_transportista' => $id_chofer,
		        'numero_documento'=> $numdoc,
		        'tipo_documento' => $tipdoc
		    	);

	    		$this->db->where('id', $v->id);

			    $this->db->update('orden_compra_item', $data4);	
	    		

	    	}else{

	    		$saldo1 = ($v->cantidad-$v->stock);

	    		$total1 = ($v->subtotal * $v->val_real);
	    		$neto1 = ($total1 / 1.19);
	    		$iva1 = ($total1 - $neto1);

	    		$data4 = array(
		        'cant_final' => $v->stock,
		        'cantidad' => $v->cantidad,
		        'total' => $total1,
		        'descuento' => $v->dcto,
		        'id_bodega' => $idbodega,
		        'afecto' => $neto1,
		        'fecha_recepcion' => date('Y-m-d H:i:s'),
		        'total' => $total1,
		        'neto' => $neto1,
		        'iva' => $iva1,
		        'valor_prom' => $v->subtotal,
		        'fecha_vencimiento' => $v->fecha_vencimiento,
		        'lote' => $v->lote,
		        'val_real' => $v->val_real,
		        'transportista' => $chofer,
		        'id_transportista' => $id_chofer,
		        'numero_documento'=> $numdoc,
		        'tipo_documento' => $tipdoc
		    	);

		    	$this->db->where('id', $v->id);

			    $this->db->update('orden_compra_item', $data4);	
	    		
               	$cantidad = ($v->cantidad - $v->stock);

	    		$total = ($v->subtotal * $v->cantidad);
	    		$total2 = ($total * 1.19);
	    		$neto = ($total2 - $total);
	    		$iva = ($total2 - $neto);

	    	$orden_compra_item = array(
		        'id_producto' => $v->id_producto,
		        'id_ordencompra' => $id,
		        'subtotal' => $v->precio,
		        'cantidad' => $saldo1,
		        'total' => $v->total,
		        'descuento' => $v->dcto,
		        'afecto' => $v->neto,
		        'total' => $v->total,
		        'neto' => $v->neto,
		        'iva' => $v->iva,
		        'valor_prom' => $v->precio,
		        'lote' => $v->lote,
		        'val_real' => $v->val_real,
		        );

			$this->db->insert('orden_compra_item', $orden_compra_item);	

			$this->Bitacora->logger("I", 'orden_compra_item', $id);

	    	}

	    		
		}

	    }

		$rec = 0;
       
		$data = array();
		$query = $this->db->query('SELECT ctz.*, pro.nombre as nombre FROM orden_compra_item ctz
		INNER JOIN productos pro ON (ctz.id_producto = pro.id)
		WHERE ctz.id_ordencompra="'.$id.'" AND ctz.cant_final="'.$rec.'"');

	   	if($query->num_rows()==0){

	   		$data3 = array(
	        'cumplida' => "SI",
	      	);

	    	$this->db->where('id', $id);

			$this->db->update('orden_compra', $data3);
	   	}

        $resp['success'] = true;

        $this->Bitacora->logger("M", 'orden_compra', $id);
        $this->Bitacora->logger("M", 'orden_compra_item', $id); 
        
                  



        echo json_encode($resp);

	}

	public function update(){

		$resp = array();
		$data = json_decode($this->input->post('items'));
		$numero = $this->input->post('numero');
		$id = $this->input->post('id');
		$idbodega = $this->input->post('idbodega');
		$fecha = $this->input->post('fecha');
		$numdoc = $this->input->post('numerodoc');
		$tipdoc = $this->input->post('recepcion');
		$chofer = $this->input->post('chofer');
		$id_chofer = $this->input->post('id_chofer');
		$idprovee = $this->input->post('idprovee');	

		if(!$chofer){
			$chofer="";
		}

		if(!$id_chofer){
			$id_chofer=0;
		}
		
		$tipodocumento="4";
				

		$data2 = array(
	        'semicumplida' => "SI",
	        'id_bodega' => $idbodega
	    	);

    	$this->db->where('id', $id);

		$this->db->update('orden_compra', $data2);

		foreach($data as $v){

		if ($v->existe==true){ 

		   $producto = $v->id_producto;

		   $puc = ($v->valor);
		   $cero=0;
		   if (!$v->lote){
		   	 $v->lote=0;
		   };


		   $query = $this->db->query('SELECT * FROM productos WHERE id="'.$producto.'"');
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
			 	$saldo = ($row->stock)+($v->val_real);
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
	         'fecha_ult_compra' => $fecha,
	         'stock' => $saldo,
	         'fecha_vencimiento' => $v->fecha_vencimiento,
	         'u_lote' => $v->lote,
	         
	    	);

	    	$this->db->where('id', $producto);

	    	$this->db->update('productos', $prod);

	    	///GRABAR EXISTENCIA DETALL

	    	$datos2 = array(
				'num_movimiento' => $numdoc,
		        'id_producto' => $v->id_producto,
		        'id_tipo_movimiento' => $tipdoc,
		        'valor_producto' =>  $v->valor,
		        'cantidad_entrada' => $v->val_real,
		        'saldo' => $v->val_real,
		        'fecha_movimiento' => $fecha,
		        'fecha_vencimiento' => $v->fecha_vencimiento,
		        'num_o_compra' => $numero,
		        'lote' => $v->lote,
		        'p_promedio' => $ppm,
		        'id_cliente' => $idprovee,
		        'id_bodega' => $idbodega,
		        'transportista' => $chofer,
		        'id_transportista' => $id_chofer
		     );

			$this->db->insert('existencia_detalle', $datos2);


			 $query = $this->db->query('SELECT * FROM existencia WHERE id_producto='.$producto.' and id_bodega='.$idbodega.' ');
	    	 $row = $query->result();
			 if ($query->num_rows()>0){

				$row = $row[0];
	 
		        if ($producto==($row->id_producto)){
		        	$saldo = ($row->stock + $v->stock);
				    $datos3 = array(
					'stock' => $saldo,
			        'fecha_ultimo_movimiento' => date('Y-m-d H:i:s')
					);
					$this->db->where('id_producto', $producto);

		    	    $this->db->update('existencia', $datos3);
	    	    }else{
	    	    	$saldo = $v->stock;
	    	    	$datos3 = array(
					'id_producto' => $producto,
			        'stock' =>  $saldo,
			        'id_bodega' => $idbodega,
			        'fecha_ultimo_movimiento' =>date('Y-m-d H:i:s'),
			       			
					);
					$this->db->insert('existencia', $datos3);
		    	 	}
				}else{					
                        $saldo = $v->stock;
		    	    	$datos3 = array(
						'id_producto' => $producto,
				        'stock' =>  $saldo,
				        'fecha_ultimo_movimiento' =>date('Y-m-d H:i:s'),
				        'id_bodega' => $idbodega
					
						);
						$this->db->insert('existencia', $datos3);
			    	
				};

	    	if ($v->stock==$v->cantidad){


	    		$data4 = array(
		        'cant_final' => $v->stock,
		        'valor_prom' => $v->valor,
		        'id_bodega' => $idbodega,
		        'fecha_vencimiento' => $v->fecha_vencimiento,
		        'lote' => $v->lote,
		        'val_real' => $v->val_real,
		        'transportista' => $chofer,
		        'id_transportista' => $id_chofer,
		        'numero_documento'=> $numdoc,
		        'tipo_documento' => $tipdoc
		    	);

	    		$this->db->where('id', $v->id);

			    $this->db->update('orden_compra_item', $data4);	
	    		

	    	}else{

	    		$total1 = ($v->subtotal * $v->stock);
	    		$neto1 = ($total1 / 1.19);
	    		$iva1 = ($total1 - $neto1);

	    		$data4 = array(
		        'cant_final' => $v->stock,
		        'cantidad' => $v->stock,
		        'total' => $total1,
		        'descuento' => $v->dcto,
		        'id_bodega' => $idbodega,
		        'afecto' => $neto1,
		        'fecha_recepcion' => date('Y-m-d H:i:s'),
		        'total' => $total1,
		        'neto' => $neto1,
		        'iva' => $iva1,
		        'valor_prom' => $v->subtotal,
		        'fecha_vencimiento' => $v->fecha_vencimiento,
		        'lote' => $v->lote,
		        'val_real' => $v->val_real,
		        'transportista' => $chofer,
		        'id_transportista' => $id_chofer,
		        'numero_documento'=> $numdoc,
		        'tipo_documento' => $tipdoc
		    	);

		    	$this->db->where('id', $v->id);

			    $this->db->update('orden_compra_item', $data4);
			    
			    $cantidad = ($v->cantidad - $v->stock);
	    		$total = ($v->subtotal * $cantidad);
	    		$neto = ($total / 1.19);
	    		$iva = ($total - $neto);   		


	    	$orden_compra_item = array(
		        'id_producto' => $v->id_producto,
		        'id_ordencompra' => $id,
		        'subtotal' => $v->subtotal,
		        'cantidad' => $cantidad,
		        'total' => $total,
		        'descuento' => $v->dcto,
		        'id_bodega' => $idbodega,
		        'afecto' => $neto,
		        'fecha_recepcion' => date('Y-m-d H:i:s'),
		        'total' => $total,
		        'neto' => $neto,
		        'iva' => $iva,
		        'valor_prom' => $v->subtotal,
		        'fecha_vencimiento' => $v->fecha_vencimiento,
		        
			);

			$this->db->insert('orden_compra_item', $orden_compra_item);	

			$this->Bitacora->logger("I", 'orden_compra_item', $id);

	    	}	    		
		}

	    }

		$rec = 0;
       
		$data = array();
		$query = $this->db->query('SELECT ctz.*, pro.nombre as nombre FROM orden_compra_item ctz
		INNER JOIN productos pro ON (ctz.id_producto = pro.id)
		WHERE ctz.id_ordencompra="'.$id.'" AND ctz.cant_final="'.$rec.'"');

	   	if($query->num_rows()==0){

	   		$data3 = array(
	        'cumplida' => "SI",
	      	);

	    	$this->db->where('id', $id);

			$this->db->update('orden_compra', $data3);
	   	}

        $resp['success'] = true;
        $resp['ppm'] = $ppm;

        $this->Bitacora->logger("M", 'orden_compra', $id);
        $this->Bitacora->logger("M", 'orden_compra_item', $id);

        echo json_encode($resp);

	}	

	public function getAll(){

		$resp = array();
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $opcion = $this->input->get('opcion');
        $nombres = $this->input->get('nombre');
        $semi ="NO";        

		$countAll = $this->db->count_all_results("orden_compra");
		$data = array();
		$total = 0;

		if($opcion == "Rut"){

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			WHERE ctz.semicumplida="NO" and pro.rut = '.$nombres.'
			order by ctz.id desc		
			limit '.$start.', '.$limit.''
			);

		}else if($opcion == "Numero"){

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			WHERE ctz.num_orden = '.$nombres.'
			order by ctz.id desc		
			limit '.$start.', '.$limit.''		 

			);

		}else if($opcion == "Nombre"){

			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "and pro.nombres like '%".$nombre."%' ";
	        }

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			WHERE ctz.semicumplida="NO" ' . $sql_nombre . '
			order by ctz.id desc		
			limit '.$start.', '.$limit.'');

		}else if($opcion == "Todos"){

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			WHERE ctz.semicumplida="NO"
			order by ctz.id desc		
			limit '.$start.', '.$limit.'');			

		}else{

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			WHERE ctz.semicumplida="NO"
			order by ctz.id desc		
			limit '.$start.', '.$limit.'');

			
		};

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
			//$total = $total +1;
			//$countAll = $total;
		}
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function getAll2(){
		$resp = array();

        $start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $opcion = $this->input->get('opcion');
        $nombres = $this->input->get('nombre');
        $semi ="";

		$countAll = $this->db->count_all_results("orden_compra");
		$data = array();
		$total = 0;

		if($opcion == "Rut"){

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			WHERE rut = "'.$nombres.'"
			order by ctz.id desc		
			limit '.$start.', '.$limit.''		 

			);

		}elseif($opcion == "Numero"){

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			WHERE ctz.num_orden = '.$nombres.'
			order by ctz.id desc		
			limit '.$start.', '.$limit.''		 

			);

		}else if($opcion == "Nombre"){

			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "pro.nombres like '%".$nombre."%' and ";
	        }

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			WHERE ' . $sql_nombre . ' 1 = 1
			order by ctz.id desc		
			limit '.$start.', '.$limit.'');

		}else if($opcion == "Todos"){

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			order by ctz.id desc		
			limit '.$start.', '.$limit.'');

		}else{

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			order by ctz.id desc		
			limit '.$start.', '.$limit.'');
		};


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
			$total = $total +1;
			$countAll = $total;
		}
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function detalle(){
		$resp = array();

        $nombre = $this->input->get('nombre');
       
		$data = array();
		$query = $this->db->query('SELECT ctz.*, pro.nombre as nombre FROM orden_compra_item ctz
		INNER JOIN productos pro ON (ctz.id_producto = pro.id)
		WHERE ctz.id_ordencompra="'.$nombre.'"');

		foreach ($query->result() as $row)
		{
			$data[] = $row;
		}
        $resp['success'] = true;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function detallerec(){
		$resp = array();

        $nombre = $this->input->get('nombre');
        $rec = 0;
       
		$data = array();
		$query = $this->db->query('SELECT ctz.*, pro.nombre as nombre FROM orden_compra_item ctz
		INNER JOIN productos pro ON (ctz.id_producto = pro.id)
		WHERE ctz.id_ordencompra like "'.$nombre.'" AND ctz.cant_final="'.$rec.'"');

		foreach ($query->result() as $row)
		{
			$data[] = $row;
		}
        $resp['success'] = true;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function recepcion(){


		$resp = array();
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $opcion = $this->input->get('opcion');
        $nombres = $this->input->get('nombre');
        //$semi ="NO";        

		$countAll = $this->db->count_all_results("orden_compra");
		$data = array();
		$total = 0;

		if($opcion == "Rut"){

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			WHERE pro.rut = '.$nombres.'
			order by ctz.id desc		
			limit '.$start.', '.$limit.''		 

			);

		}else if($opcion == "Numero"){

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			WHERE ctz.num_orden = '.$nombres.'
			order by ctz.id desc		
			limit '.$start.', '.$limit.''		 

			);

		}else if($opcion == "Nombre"){

			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "pro.nombres like '%".$nombre."%' ";
	        }

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			WHERE ' . $sql_nombre . '
			order by ctz.id desc		
			limit '.$start.', '.$limit.'');

		}else if($opcion == "Todos"){

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			order by ctz.id desc		
			limit '.$start.', '.$limit.'');			

		}else{

			$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
			FROM orden_compra ctz
			INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
			LEFT JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
			LEFT JOIN comuna com ON (pro.id_comuna = com.id)
			LEFT JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
			order by ctz.id desc		
			limit '.$start.', '.$limit.'');

			
		};

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
			//$total = $total +1;
			//$countAll = $total;
		}
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);

	}

	public function forzada(){
		$resp = array();

        $start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $nombre = "SI";


		$countAll = $this->db->count_all_results("orden_compra");
		$data = array();
		$query = $this->db->query('SELECT ctz.*, pro.nombres as empresa, pro.rut as rut, pro.direccion as direccion, ciu.nombre as ciudad, com.nombre as comuna, gir.nombre as giro 
		FROM orden_compra ctz
		INNER JOIN clientes pro ON (ctz.id_proveedor = pro.id)
		INNER JOIN ciudad ciu ON (pro.id_ciudad = ciu.id)
		INNER JOIN comuna com ON (pro.id_comuna = com.id)
		INNER JOIN cod_activ_econ gir ON (pro.id_giro = gir.id)
		WHERE ctz.forzada="'.$nombre.'"');

		foreach ($query->result() as $row)
		{
			$data[] = $row;
		}
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function save2(){
		
		$resp = array();
		$idproveedor = $this->input->post('idproveedor');
		$idorden = $this->input->post('id_orden');
		$dataproveedor = json_decode($this->input->post('dataproveedor'));
		$items = json_decode($this->input->post('items'));
		$neto = $this->input->post('neto');
		$observacion = $this->input->post('observacion');
		$afecto = $this->input->post('afecto');
		$iva = $this->input->post('iva');
		$total = $this->input->post('total');
		$descuento = $this->input->post('descuento');
		$semi = "NO";
        $emitida = "SI";
				
		$query = $this->db->query('DELETE FROM orden_compra_item WHERE id_ordencompra = "'.$idorden.'"');       		
		
		$orden_compra = array(
			    'nombre_contacto' => ucfirst(strtolower($dataproveedor->nombre_contacto)),
				'telefono_contacto' => $dataproveedor->telefono_contacto,
				'mail_contacto' => $dataproveedor->mail_contacto,
		        'id_proveedor' => $idproveedor,
		        'descuento' => $descuento,
		        'afecto' => $afecto,
		        'neto' => $neto,
		        'iva' => $iva,
		        'total' => $total,
				'fecha' => date('Y-m-d H:i:s'),
				'emitida' => $emitida,
				'semicumplida' => $semi,
				'observacion' =>$observacion
		);

		$this->db->where('id', $idorden);
		$this->db->update('orden_compra', $orden_compra);

		
		foreach($items as $v){
			
			$orden_compra_item = array(
				'id_producto' => $v->id_producto,
		        'id_ordencompra' => $idorden,
		        'subtotal' => $v->precio,
		        'cantidad' => $v->cantidad,
		        'total' => $v->total,
		        'descuento' => $v->dcto,
		        'afecto' => $v->neto,
		        'total' => $v->total,
		        'neto' => $v->neto,
		        'iva' => $v->iva,
		        'valor_prom' => $v->precio
			);

			$this->db->insert('orden_compra_item', $orden_compra_item); 

		}

		
        $resp['success'] = true;

         $this->Bitacora->logger("I", 'orden_compra', $idorden);


        echo json_encode($resp);
	}

	public function save(){

		$resp = array();

		$idproveedor = $this->input->post('idproveedor');
		$dataproveedor = json_decode($this->input->post('dataproveedor'));
		$items = json_decode($this->input->post('items'));
		$neto = $this->input->post('neto');
		$iva = $this->input->post('iva');
		$total = $this->input->post('total');
		$descuento = $this->input->post('descuento');
		$observacion = $this->input->post('observacion');
		$afecto = $this->input->post('afecto');		
		$emitida = "SI";
		$semi = "NO";
		$orden = 4;
       		
		$query = $this->db->query('SELECT * FROM correlativos WHERE id like "'.$orden.'"');

		if($query->num_rows()>0){

			$ord = $query->result();
		    $ord = $ord[0];
		    $num_orden = $ord->correlativo+1;
	    }

	    $ad = 4;
		$data = array(
	        'correlativo' => $num_orden
	    );

		$this->db->where('id', $ad);
		
		$this->db->update('correlativos', $data); 

		$orden_compra = array(
		    'num_orden' => $num_orden,
            'nombre_contacto' => ucfirst(strtolower($dataproveedor->nombre_contacto)),
			'telefono_contacto' => $dataproveedor->telefono_contacto,
			'mail_contacto' => $dataproveedor->mail_contacto,
	        'id_proveedor' => $idproveedor,
	        'descuento' => $descuento,
	        'afecto' => $afecto,
	        'neto' => $neto,
	        'iva' => $iva,
	        'total' => $total,
			'fecha' => date('Y-m-d H:i:s'),
			'emitida' => $emitida,
			'semicumplida' => $semi,
			'observacion' =>$observacion
		);

		$this->db->insert('orden_compra', $orden_compra); 
		$idordencompra = $this->db->insert_id();

		foreach($items as $v){
			
			$orden_compra_item = array(

		        'id_producto' => $v->id_producto,
		        'id_ordencompra' => $idordencompra,
		        'subtotal' => $v->precio,
		        'cantidad' => $v->cantidad,
		        'total' => $v->total,
		        'descuento' => $v->dcto,
		        'afecto' => $v->neto,
		        'total' => $v->total,
		        'neto' => $v->neto,
		        'lote' => $v->u_lote,
		        'iva' => $v->iva,
		        'valor_prom' => $v->precio

			);

			$this->db->insert('orden_compra_item', $orden_compra_item); 
			$this->db->insert('ordenconpra_item', $orden_compra_item); 

		}
		
        $resp['success'] = true;

         $this->Bitacora->logger("I", 'orden_compra', $num_orden);


        echo json_encode($resp);
	}

	public function exportPDForiginal(){
		
		$idordencompra = $this->input->get('idordencompra');
		
		$query = $this->db->query('SELECT * FROM orden_compra WHERE id like "'.$idordencompra.'"');

		if($query->num_rows()>0){
			$ord = $query->result();
		    $ord = $ord[0];
		    $emitido = "SI";
		    $ad = $ord->id;
	    }
		
		$query = $this->db->query('SELECT 
			ctz.id, ctz.fecha, ctz.afecto, ctz.neto, ctz.iva, ctz.neto, ctz.descuento, ctz.total, ctz.num_orden, ctz.telefono_contacto, ctz.mail_contacto, ctz.nombre_contacto, cli.nombres as empresa , cli.rut as rut_empresa, cli.direccion as direccion_empresa, cli.fono as fono_empresa, cae.nombre as giro_empresa, c.nombre as ciudad_empresa, pa.nombre as conpago FROM orden_compra ctz
			INNER JOIN clientes cli on (ctz.id_proveedor = cli.id)
			LEFT JOIN cod_activ_econ cae on cli.id_giro = cae.id
			LEFT JOIN cond_pago pa on cli.id_pago = pa.id
			LEFT JOIN ciudad c on cli.id_ciudad = c.id
			WHERE ctz.id = '.$idordencompra.'
		');

		
		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		//items
		$items = $this->db->query('SELECT ctz.neto,ctz.cantidad,ctz.id_producto FROM ordenconpra_item ctz 
		WHERE ctz.id_ordencompra = '.$row->id.'
		');		
		$codigo = $row->num_orden;
		$nombre = $row->empresa;
		$fecha = $row->fecha;
        list($anio, $mes, $dia) = explode("-",$fecha); 
		$direccion = $row->direccion_empresa;
		$nombre_contacto = $row->nombre_contacto;
        $fono_contacto = $row->telefono_contacto;


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
        <p>Fonos: ' . $empresa->fono . '</p>
		    <p>http://www.lircay.cl</p>
		    </td>
		    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
		          <p>ORDEN DE COMPRA N°: '.$codigo.'</p>
		          <!--p>&nbsp;</p-->
		          <p>FECHA EMISION : '.$fecha.'</p>
		          <!--p>&nbsp;</p-->
		          <p>VALIDEZ ORDEN DE COMPRA : 15 DIAS</p>
		          <!--p>&nbsp;</p-->
		          <p>ESTADO : Pendiente</p>
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>ORDEN DE COMPRA</h1></td>
		  </tr>
		  <tr>
		    <td colspan="3" width="987px" >
		    	<table width="987px" border="0">
		    		<tr>
		    			<td width="197px">Sr.(es):</td>
		    			<td width="395px">'. $row->empresa .'</td>
		    			<td width="197px">Rut:</td>
		    			<td width="197px">'. number_format(substr($row->rut_empresa, 0, strlen($row->rut_empresa) - 1),0,".",".")."-".substr($row->rut_empresa,-1).'</td>
		    		</tr>
		    		<tr>
		    			<td width="197px">Direcci&oacute;n:</td>
		    			<td width="395px">'. $direccion .'</td>
		    			<td width="197px">Tel&eacute;fono:</td>
		    			<td width="197px">'. $row->fono_empresa .'</td>
		    		</tr>		    		
		    		<tr>
		    			<td width="197px">Giro:</td>
		    			<td width="395px">'. $row->giro_empresa .'</td>
		    			<td width="197px">Fax:</td>
		    			<td width="197px">&nbsp;</td>
		    		</tr>		    				    		
		    		<tr>
		    			<td width="197px">Ciudad:</td>
		    			<td width="395px">' . $row->ciudad_empresa .'</td>
		    			<td width="197px">O.C N°:</td>
		    			<td width="197px">&nbsp;</td>
		    		</tr>		    				    				    		
		    		<tr>
		    			<td width="197px">Contacto:</td>
		    			<td width="395px">' .$nombre_contacto.'</td>
		    			<td width="197px">Forma Pago:</td>
		    			<td width="197px">&nbsp;</td>
		    		</tr>		    				    				    				    		
		    	</table>
			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		       <tr>
		        <td width="40px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Nro. Linea</td>
		        <td width="10px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >&nbsp;</td>
		        <td width="695px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Descripci&oacute;n</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Precio</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Total</td>
		      </tr>';
		$descripciones = '';
		$i = 0;
		$linea= 0;
		foreach($items->result() as $v){
			
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			$linea = $linea + 1;
						
			$valorunit=($v->neto / $v->cantidad);
			
					
			$html .= '<tr>
			<td style="text-align:right">'.$linea.'</td>	
			<td style="text-align:right">&nbsp;&nbsp;</td>			
			<td style="text-align:left">'.$producto->nombre.'</td>
			<td style="text-align:right">'.number_format($v->cantidad,0,'.',',').'&nbsp;&nbsp;</td>	
			<td align="right">$ '.number_format($valorunit, 2, '.', ',').'</td>

			<td align="right">$ '.number_format($v->neto, 0, '.', ',').'</td>
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
		  	<td colspan="2" rowspan="6" style="font-size: 12px;border-bottom:1pt solid black;border-top:1pt solid black;border-left:1pt solid black;border-right:1pt solid black;text-align:left;">Observacion '.$row->observacion.'</td>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Afecto</td>
						<td width="146px" style="text-align:right;">$ '. number_format($row->neto, 0, '.', ',') .'</td>
					</tr>
				</table>
		  	</td>
		  </tr>
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Descuento</td>
						<td width="146px" style="text-align:right;">$ '. number_format($row->descuento, 0, ',', '.') .'</td>
					</tr>
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Neto</td>
						<td width="146px" style="text-align:right;">$ '.number_format($row->afecto, 0, '.', ',').'</td>
					</tr>
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">IVA</td>
						<td width="146px" style="text-align:right;">$ '.number_format($row->iva, 0, '.', ',').'</td>
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

	public function exportPDF5(){
		
		$idordencompra = $this->input->get('idproduccion');
		
		$query = $this->db->query('SELECT * FROM orden_compra WHERE num_orden like "'.$idordencompra.'"');

		if($query->num_rows()>0){

			$ord = $query->result();
		    $ord = $ord[0];
		    $emitido = "SI";
		    $ad = $ord->id;
	    }

	 	$data = array(
	        'emitida' => $emitido
	    );

		$this->db->where('id', $ad);
		
		$this->db->update('orden_compra', $data);

		
		$query = $this->db->query('SELECT 
			ctz.id, ctz.fecha, ctz.afecto, ctz.neto, ctz.iva, ctz.neto, ctz.descuento, ctz.total, ctz.num_orden, ctz.telefono_contacto, ctz.mail_contacto, ctz.nombre_contacto, cli.nombres as empresa , cli.rut as rut_empresa, cli.direccion as direccion_empresa, cli.fono as fono_empresa, cae.nombre as giro_empresa, c.nombre as ciudad_empresa, pa.nombre as conpago FROM orden_compra ctz
			INNER JOIN clientes cli on (ctz.id_proveedor = cli.id)
			LEFT JOIN cod_activ_econ cae on cli.id_giro = cae.id
			LEFT JOIN cond_pago pa on cli.id_pago = pa.id
			LEFT JOIN ciudad c on cli.id_ciudad = c.id
			WHERE ctz.id = '.$ad.'
		');

		
		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		//items
		$items = $this->db->query('SELECT ctz.val_real,ctz.neto,ctz.cantidad,ctz.id_producto,ctz.numero_documento,cor.nombre as tipodoc FROM orden_compra_item ctz 
		left join correlativos cor on (ctz.tipo_documento = cor.id) 
		WHERE ctz.id_ordencompra = '.$row->id.'
		');

		//$row1 = $items->result();
		//$row1 = $row1[0];

		/*print_r($row1);
		exit;*/
				
		//$items = $this->db->get_where('orden_compra_item', array('id_ordencompra' => $row->id));
		//variables generales
		$codigo = $row->num_orden;
		$nombre = $row->empresa;
		$fecha = $row->fecha;
        list($anio, $mes, $dia) = explode("-",$fecha); 
		$direccion = $row->direccion_empresa;
		$nombre_contacto = $row->nombre_contacto;
        $fono_contacto = $row->telefono_contacto;


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
		    <td width="177px"><img src="' . $logo . '" width="150" height="136" /></td>
		    <td width="493px">
		    <p>' . $empresa->razon_social .'</p>
        <p>RUT:' . number_format($empresa->rut,0,".",".").'-' . $empresa->dv . '</p>
        <p>' . $empresa->dir_origen . '</p>
        <p><center>CHILE</center></p>
        <p>Fonos: 71 2247623 Celular +56 9 92405813</p>
		    <p><center>http://www.lircay.cl<//center></p>
		    </td>
		    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
		          <p>ORDEN DE COMPRA N°: '.$codigo.'</p>
		          <!--p>&nbsp;</p-->
		          <p>FECHA EMISION : '.$fecha.'</p>
		          <!--p>&nbsp;</p-->
		          <p>VALIDEZ ORDEN DE COMPRA : 15 DIAS</p>
		          <!--p>&nbsp;</p-->
		          <p>ESTADO : Pendiente</p>
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>ORDEN DE COMPRA</h1></td>
		  </tr>
		  <tr>
		    <td colspan="3" width="987px" >
		    	<table width="987px" border="0">
		    		<tr>
		    			<td width="197px">Sr.(es):</td>
		    			<td width="395px">'. $row->empresa .'</td>
		    			<td width="197px">Rut:</td>
		    			<td width="197px">'. number_format(substr($row->rut_empresa, 0, strlen($row->rut_empresa) - 1),0,".",".")."-".substr($row->rut_empresa,-1).'</td>
		    		</tr>
		    		<tr>
		    			<td width="197px">Direcci&oacute;n:</td>
		    			<td width="395px">'. $direccion .'</td>
		    			<td width="197px">Tel&eacute;fono:</td>
		    			<td width="197px">'. $row->fono_empresa .'</td>
		    		</tr>		    		
		    		<tr>
		    			<td width="197px">Giro:</td>
		    			<td width="395px">'. $row->giro_empresa .'</td>
		    			<td width="197px">Fax:</td>
		    			<td width="197px">&nbsp;</td>
		    		</tr>		    				    		
		    		<tr>
		    			<td width="197px">Ciudad:</td>
		    			<td width="395px">' . $row->ciudad_empresa .'</td>
		    			<td width="197px">O.C N°:</td>
		    			<td width="197px">&nbsp;</td>
		    		</tr>		    				    				    		
		    		<tr>
		    			<td width="197px">Contacto:</td>
		    			<td width="395px">' .$nombre_contacto.'</td>
		    			<td width="197px">Forma Pago:</td>
		    			<td width="197px">&nbsp;</td>


		    		</tr>		    				    				    				    		
		    	</table>
			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		       <tr>
		        <td width="40px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Nro. Linea</td>
		        <td width="10px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >&nbsp;</td>
		        <td width="695px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Descripci&oacute;n</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Tipo_doc</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Num_doc</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Recepcionada</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Precio</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Total</td>
		      </tr>';
		$descripciones = '';
		$i = 0;
		$linea= 0;
		foreach($items->result() as $v){
			//$i = 0;
			//while($i < 30){
			//print_r($v);
		    //exit;
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			$linea = $linea + 1;
			if(!$v->tipodoc){
				$v->tipodoc=" ";
			};
			if(!$v->numero_documento){
				$v->numero_documento=" ";
			};
			if($v->val_real==0){
				$valorunit=($v->neto / $v->cantidad);
			}else{
				$valorunit=($v->neto / $v->val_real);
			};
					
			$html .= '<tr>
			<td style="text-align:right">'.$linea.'</td>	
			<td style="text-align:right">&nbsp;&nbsp;</td>			
			<td style="text-align:left">'.$producto->nombre.'</td>
			<td style="text-align:left">'.$v->tipodoc.'</td>
			<td style="text-align:left">'.$v->numero_documento.'</td>
			<td style="text-align:right">'.number_format($v->cantidad,0,'.',',').'&nbsp;&nbsp;</td>	
			<td align="right">'.number_format($v->val_real, 0, '.', ',').'</td>		
			<td align="right">$ '.number_format($valorunit, 2, '.', ',').'</td>

			<td align="right">$ '.number_format($v->neto, 0, '.', ',').'</td>
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
		  	<td colspan="2" rowspan="6" style="font-size: 12px;border-bottom:1pt solid black;border-top:1pt solid black;border-left:1pt solid black;border-right:1pt solid black;text-align:left;">&nbsp;</td>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Afecto</td>
						<td width="146px" style="text-align:right;">$ '. number_format($row->neto, 0, '.', ',') .'</td>
					</tr>
				</table>
		  	</td>
		  </tr>
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Descuento</td>
						<td width="146px" style="text-align:right;">$ '. number_format($row->descuento, 0, ',', '.') .'</td>
					</tr>
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Neto</td>
						<td width="146px" style="text-align:right;">$ '.number_format($row->afecto, 0, '.', ',').'</td>
					</tr>
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">IVA</td>
						<td width="146px" style="text-align:right;">$ '.number_format($row->iva, 0, '.', ',').'</td>
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

	public function exportPDF(){
		
		$idordencompra = $this->input->get('idordencompra');
		
		$query = $this->db->query('SELECT * FROM orden_compra WHERE id like "'.$idordencompra.'"');

		if($query->num_rows()>0){

			$ord = $query->result();
		    $ord = $ord[0];
		    $emitido = "SI";
		    $ad = $ord->id;
	    }

	 	$data = array(
	        'emitida' => $emitido
	    );

		$this->db->where('id', $ad);
		
		$this->db->update('orden_compra', $data);

		
		$query = $this->db->query('SELECT 
			ctz.observacion, ctz.id, ctz.fecha, ctz.afecto, ctz.neto, ctz.iva, ctz.neto, ctz.descuento, ctz.total, ctz.num_orden, ctz.telefono_contacto, ctz.mail_contacto, ctz.nombre_contacto, cli.nombres as empresa , cli.rut as rut_empresa, cli.direccion as direccion_empresa, cli.fono as fono_empresa, cae.nombre as giro_empresa, c.nombre as ciudad_empresa, pa.nombre as conpago FROM orden_compra ctz
			INNER JOIN clientes cli on (ctz.id_proveedor = cli.id)
			LEFT JOIN cod_activ_econ cae on cli.id_giro = cae.id
			LEFT JOIN cond_pago pa on cli.id_pago = pa.id
			LEFT JOIN ciudad c on cli.id_ciudad = c.id
			WHERE ctz.id = '.$idordencompra.'
		');

		
		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		//items
		$items = $this->db->query('SELECT ctz.val_real,ctz.neto,ctz.cantidad,ctz.id_producto,ctz.numero_documento,cor.nombre as tipodoc FROM orden_compra_item ctz 
		left join correlativos cor on (ctz.tipo_documento = cor.id) 
		WHERE ctz.id_ordencompra = '.$row->id.'
		');

		//$row1 = $items->result();
		//$row1 = $row1[0];

		/*print_r($row1);
		exit;*/
				
		//$items = $this->db->get_where('orden_compra_item', array('id_ordencompra' => $row->id));
		//variables generales
		$codigo = $row->num_orden;
		$nombre = $row->empresa;
		$fecha = $row->fecha;
        list($anio, $mes, $dia) = explode("-",$fecha); 
		$direccion = $row->direccion_empresa;
		$nombre_contacto = $row->nombre_contacto;
        $fono_contacto = $row->telefono_contacto;


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
		    <td width="177px"><img src="' . $logo . '" width="150" height="136" /></td>
		    <td width="493px">
		    <p>' . $empresa->razon_social .'</p>
        <p>RUT:' . number_format($empresa->rut,0,".",".").'-' . $empresa->dv . '</p>
        <p>' . $empresa->dir_origen . '</p>
        <p><center>CHILE</center></p>
        <p>Fonos: 71 2247623 Celular +56 9 92405813</p>
		    <p><center>http://www.lircay.cl<//center></p>
		    </td>
		    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
		          <p>ORDEN DE COMPRA N°: '.$codigo.'</p>
		          <!--p>&nbsp;</p-->
		          <p>FECHA EMISION : '.$fecha.'</p>
		          <!--p>&nbsp;</p-->
		          <p>VALIDEZ ORDEN DE COMPRA : 15 DIAS</p>
		          <!--p>&nbsp;</p-->
		          <p>ESTADO : Pendiente</p>
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>ORDEN DE COMPRA</h1></td>
		  </tr>
		  <tr>
		    <td colspan="3" width="987px" >
		    	<table width="987px" border="0">
		    		<tr>
		    			<td width="197px">Sr.(es):</td>
		    			<td width="395px">'. $row->empresa .'</td>
		    			<td width="197px">Rut:</td>
		    			<td width="197px">'. number_format(substr($row->rut_empresa, 0, strlen($row->rut_empresa) - 1),0,".",".")."-".substr($row->rut_empresa,-1).'</td>
		    		</tr>
		    		<tr>
		    			<td width="197px">Direcci&oacute;n:</td>
		    			<td width="395px">'. $direccion .'</td>
		    			<td width="197px">Tel&eacute;fono:</td>
		    			<td width="197px">'. $row->fono_empresa .'</td>
		    		</tr>		    		
		    		<tr>
		    			<td width="197px">Giro:</td>
		    			<td width="395px">'. $row->giro_empresa .'</td>
		    			<td width="197px">Fax:</td>
		    			<td width="197px">&nbsp;</td>
		    		</tr>		    				    		
		    		<tr>
		    			<td width="197px">Ciudad:</td>
		    			<td width="395px">' . $row->ciudad_empresa .'</td>
		    			<td width="197px">O.C N°:</td>
		    			<td width="197px">&nbsp;</td>
		    		</tr>		    				    				    		
		    		<tr>
		    			<td width="197px">Contacto:</td>
		    			<td width="395px">' .$nombre_contacto.'</td>
		    			<td width="197px">Forma Pago:</td>
		    			<td width="197px">&nbsp;</td>


		    		</tr>		    				    				    				    		
		    	</table>
			</td>
		  </tr>
		  <tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		       <tr>
		        <td width="40px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Nro. Linea</td>
		        <td width="10px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >&nbsp;</td>
		        <td width="695px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Descripci&oacute;n</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Tipo_doc</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Num_doc</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Cantidad</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Recepcionada</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Precio</td>
		        <td width="108px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Total</td>
		      </tr>';
		$descripciones = '';
		$i = 0;
		$linea= 0;
		foreach($items->result() as $v){
			//$i = 0;
			//while($i < 30){
			//print_r($v);
		    //exit;
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];
			$linea = $linea + 1;
			if(!$v->tipodoc){
				$v->tipodoc=" ";
			};
			if(!$v->numero_documento){
				$v->numero_documento=" ";
			};
			if($v->val_real==0){
				$valorunit=($v->neto / $v->cantidad);
			}else{
				$valorunit=($v->neto / $v->val_real);
			};
					
			$html .= '<tr>
			<td style="text-align:right">'.$linea.'</td>	
			<td style="text-align:right">&nbsp;&nbsp;</td>			
			<td style="text-align:left">'.$producto->nombre.'</td>
			<td style="text-align:left">'.$v->tipodoc.'</td>
			<td style="text-align:left">'.$v->numero_documento.'</td>
			<td style="text-align:right">'.number_format($v->cantidad,0,'.',',').'&nbsp;&nbsp;</td>	
			<td align="right">'.number_format($v->val_real, 0, '.', ',').'</td>		
			<td align="right">$ '.number_format($valorunit, 3, '.', ',').'</td>

			<td align="right">$ '.number_format($v->neto, 0, '.', ',').'</td>
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
		  	<td colspan="2" rowspan="6" style="font-size: 12px;border-bottom:1pt solid black;border-top:1pt solid black;border-left:1pt solid black;border-right:1pt solid black;text-align:left;">Observacion: '. $row->observacion.'</td>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Afecto</td>
						<td width="146px" style="text-align:right;">$ '. number_format($row->neto, 0, '.', ',') .'</td>
					</tr>
				</table>
		  	</td>
		  </tr>
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Descuento</td>
						<td width="146px" style="text-align:right;">$ '. number_format($row->descuento, 0, ',', '.') .'</td>
					</tr>
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Neto</td>
						<td width="146px" style="text-align:right;">$ '.number_format($row->afecto, 0, '.', ',').'</td>
					</tr>
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">IVA</td>
						<td width="146px" style="text-align:right;">$ '.number_format($row->iva, 0, '.', ',').'</td>
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

	public function envioEmailPDF(){
		
		$idordencompra = $this->input->post('idcotiza');
		$mensaje = $this->input->post('mensaje') != '' ? $this->input->post('mensaje') : "Envio Orden de Compra Pdf";
		$email = $this->input->post('email');
		
		$query = $this->db->query('SELECT * FROM orden_compra WHERE id like "'.$idordencompra.'"');

		if($query->num_rows()>0){

			$ord = $query->result();
		    $ord = $ord[0];
		    $emitido = "SI";
		    $ad = $ord->id;
	    }

	 	$data = array(
	        'emitida' => $emitido
	    );

		$this->db->where('id', $ad);
		
		$this->db->update('orden_compra', $data);

		$query = $this->db->query('SELECT 
			ctz.id, ctz.fecha, ctz.afecto, ctz.neto, ctz.iva, ctz.neto, ctz.descuento, ctz.total, ctz.num_orden, ctz.telefono_contacto, ctz.mail_contacto, ctz.nombre_contacto, cli.nombres as empresa , cli.rut as rut_empresa, cli.direccion as direccion_empresa, cli.fono as fono_empresa, cae.nombre as giro_empresa, c.nombre as ciudad_empresa, pa.nombre as conpago FROM orden_compra ctz
			INNER JOIN clientes cli on (ctz.id_proveedor = cli.id)
			LEFT JOIN cod_activ_econ cae on cli.id_giro = cae.id
			LEFT JOIN cond_pago pa on cli.id_pago = pa.id
			LEFT JOIN ciudad c on cli.id_ciudad = c.id
			WHERE ctz.id = '.$idordencompra.'
		');

		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		//items
		$items = $this->db->get_where('orden_compra_item', array('id_ordencompra' => $row->id));
		//variables generales
		$codigo = $row->num_orden;
		$nombre = $row->empresa;
		$fecha = $row->fecha;
        list($anio, $mes, $dia) = explode("-",$fecha); 
		$direccion = $row->direccion_empresa;
		$nombre_contacto = $row->nombre_contacto;
        $fono_contacto = $row->telefono_contacto;
		

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
        <p>Fonos: ' . $empresa->fono . '</p>
		    <p>http://www.lircay.cl</p>
		    </td>
		    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
		          <p>ORDEN DE COMPRA N°: '.$codigo.'</p>
		          <!--p>&nbsp;</p-->
		          <p>FECHA EMISION : '.$fecha.'</p>
		          <!--p>&nbsp;</p-->
		          <p>VALIDEZ ORDEN DE COMPRA : 15 DIAS</p>
		          <!--p>&nbsp;</p-->
		          <p>ESTADO : Pendiente</p>
			</td>
		  </tr>
		  <tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h1>ORDEN DE COMPRA</h1></td>
		  </tr>
		  <tr>
		    <td colspan="3" width="987px" >
		    	<table width="987px" border="0">
		    		<tr>
		    			<td width="197px">Sr.(es):</td>
		    			<td width="395px">'. $row->empresa .'</td>
		    			<td width="197px">Rut:</td>
		    			<td width="197px">'. number_format(substr($row->rut_empresa, 0, strlen($row->rut_empresa) - 1),0,".",".")."-".substr($row->rut_empresa,-1).'</td>
		    		</tr>
		    		<tr>
		    			<td width="197px">Direcci&oacute;n:</td>
		    			<td width="395px">'. $direccion .'</td>
		    			<td width="197px">Tel&eacute;fono:</td>
		    			<td width="197px">'. $row->fono_empresa .'</td>
		    		</tr>		    		
		    		<tr>
		    			<td width="197px">Giro:</td>
		    			<td width="395px">'. $row->giro_empresa .'</td>
		    			<td width="197px">Fax:</td>
		    			<td width="197px">&nbsp;</td>
		    		</tr>		    				    		
		    		<tr>
		    			<td width="197px">Ciudad:</td>
		    			<td width="395px">' . $row->ciudad_empresa .'</td>
		    			<td width="197px">O.C N°:</td>
		    			<td width="197px">&nbsp;</td>
		    		</tr>		    				    				    		
		    		<tr>
		    			<td width="197px">Contacto:</td>
		    			<td width="395px">' .$nombre_contacto.'</td>
		    			<td width="197px">Forma Pago:</td>
		    			<td width="197px">&nbsp;</td>


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
		        <td width="148px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Precio/Oferta</td>
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
			<td style="text-align:right">'.number_format($v->cantidad,0,'.',',').'&nbsp;&nbsp;</td>			
			<td style="text-align:left">'.$producto->nombre.'</td>			
			<td align="right">$ '.number_format($v->neto, 0, '.', ',').'</td>
			<td align="right">$ '.number_format($v->neto - ($v->descuento/$v->cantidad), 0, '.', ',').'</td>

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
		  	<td colspan="2" rowspan="6" style="font-size: 12px;border-bottom:1pt solid black;border-top:1pt solid black;border-left:1pt solid black;border-right:1pt solid black;text-align:left;">&nbsp;</td>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Afecto</td>
						<td width="146px" style="text-align:right;">$ '. number_format($row->neto, 0, '.', ',') .'</td>
					</tr>
				</table>
		  	</td>
		  </tr>
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Descuento</td>
						<td width="146px" style="text-align:right;">$ '. number_format($row->descuento, 0, ',', '.') .'</td>
					</tr>
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">Neto</td>
						<td width="146px" style="text-align:right;">$ '.number_format($row->afecto, 0, '.', ',').'</td>
					</tr>
				</table>
		  	</td>		  
		  </tr>	
		  <tr>
		  	<td>
				<table width="296px" border="0">
					<tr>
						<td width="150px" style="font-size: 20px;text-align:left;">IVA</td>
						<td width="146px" style="text-align:right;">$ '.number_format($row->iva, 0, '.', ',').'</td>
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

		$this->load->library("mpdf");

			//include(defined('BASEPATH')."/libraries/MPDF54/mpdf.php");
			//include(dirname(__FILE__)."/../libraries/MPDF54/mpdf.php");

			$this->mpdf->mPDF(
				'',    // mode - default ''
				'',    // format - A4, for example, default ''
				8,     // font size - default 0
				'',    // default font family
				10,    // margin_left
				5,    // margin right
				16,    // margin top
				16,    // margin bottom
				9,     // margin header
				9,     // margin footer
				'L'    // L - landscape, P - portrait
				);  

			//echo $html; exit;

			$this->mpdf->WriteHTML($html);
			$content = $this->mpdf->Output('', 'S');

			$content = chunk_split(base64_encode($content));
			$mailto = $email;
			$from_name = 'Ferrital';
			$from_mail = 'contacto@ferrital.cl';
			$replyto = 'contacto@ferrital.cl';
			$uid = md5(uniqid(time())); 
			$subject = 'Envio Orden de Compra';
			$message = $mensaje;
			$filename = 'OrdencompraFerrital.pdf';

			$header = "From: ".$from_name." <".$from_mail.">\r\n";
			$header .= "Reply-To: ".$replyto."\r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
			$header .= "This is a multi-part message in MIME format.\r\n";
			$header .= "--".$uid."\r\n";
			$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
			$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
			$header .= $message."\r\n\r\n";
			$header .= "--".$uid."\r\n";
			$header .= "Content-Type: application/pdf; name=\"".$filename."\"\r\n";
			$header .= "Content-Transfer-Encoding: base64\r\n";
			$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
			$header .= $content."\r\n\r\n";
			$header .= "--".$uid."--";

			$is_sent = @mail($mailto, $subject, "", $header);
	
		exit;
	}

	
}









