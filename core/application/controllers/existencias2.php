<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Existencias2 extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->database();
	}

	
	public function save(){

		
	}
	
	
	public function update(){

		
	}
	
	public function getAll(){
		
		$resp = array();
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $nombres = $this->input->get('nombre');
        $bodega = $this->input->get('bodega');
        $total = 0;
        $stock = 0;
           
		//$countAll = $this->db->count_all_results("existencia");
        
		if($nombres){			
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega, c.codigo as codigo FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			WHERE acc.id_producto="'.$nombres.'"');

			foreach ($query->result() as $row)		    
			{
				$total = $total +1;		
			}
			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega, c.codigo as codigo  FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			WHERE acc.id_producto="'.$nombres.'"
			 order by acc.id desc
		    limit '.$start.', '.$limit.'');
		}else{
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega, c.codigo as codigo  FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			WHERE acc.id_producto="'.$nombres.'"
			order by acc.id desc
		    limit '.$start.', '.$limit.' ' );
		}

		$data = array();
		
		foreach ($query->result() as $row)
		{
			$data[] = $row;
			//$stock = $stock + $row->stock;
		}
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['stock'] = $stock;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function getAll2(){
		
		$resp = array();
		$nombres = $this->input->post('nombre');
		$id_bodega = $this->input->post('id_bodega');
        $total = 0;
        $stock = 0;
           
		//$countAll = $this->db->count_all_results("existencia");
        
		if($nombres){			
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega  FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			WHERE acc.id_producto="'.$nombres.'" and acc.id_bodega = "'.$id_bodega.'" ');
		}

		$data = array();
		
		foreach ($query->result() as $row)
		{
			$data[] = $row;
			//$row = $query->first_row();
			$stock = ($stock + $row->stock);
		}
        $resp['success'] = true;
        $resp['stock'] = $stock;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function getAll3(){
		
		$resp = array();
		$start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $nombres = $this->input->get('nombre');
        $bodega = $this->input->get('bodega');
        $total = 0;
        $stock = 0;
           
		//$countAll = $this->db->count_all_results("existencia");

		if($nombres){
		    if ($bodega){			
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega, cor.nombre as nom_tipo_movimiento FROM existencia_detalle acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
			WHERE acc.id_producto="'.$nombres.'" and acc.id_bodega="'.$bodega.'"');

			foreach ($query->result() as $row)		    
			{
				$total = $total +1;		
			}
			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega, cor.nombre as nom_tipo_movimiento FROM existencia_detalle acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
			WHERE acc.id_producto="'.$nombres.'" and acc.id_bodega="'.$bodega.'"
			 order by acc.id desc
		    limit '.$start.', '.$limit.'');
			}else{

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega, cor.nombre as nom_tipo_movimiento FROM existencia_detalle acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
			WHERE acc.id_producto="'.$nombres.'"');

			foreach ($query->result() as $row)		    
			{
				$total = $total +1;		
			}
			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega, cor.nombre as nom_tipo_movimiento FROM existencia_detalle acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
			WHERE acc.id_producto="'.$nombres.'" 
			order by acc.id desc
		    limit '.$start.', '.$limit.'');
				
			}
		}else{

			if ($bodega){
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega, cor.nombre as nom_tipo_movimiento FROM existencia_detalle acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
			WHERE acc.id_producto="'.$nombres.'" and acc.id_bodega="'.$bodega.'"
			order by acc.id desc
		    limit '.$start.', '.$limit.' ' );
		    }else{
		      $query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega, cor.nombre as nom_tipo_movimiento FROM existencia_detalle acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
			WHERE acc.id_producto="'.$nombres.'"
			order by acc.id desc
		    limit '.$start.', '.$limit.' ' );
		    	
		    }
		}        
		
		$data = array();
		
		foreach ($query->result() as $row)
		{
			$data[] = $row;
			//$row = $query->first_row();
			//$stock = ($stock + $row->stock);
		}
        $resp['success'] = true;
        //$resp['stock'] = $stock;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function getAll4(){
		
		$resp = array();
		$start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $nombres = $this->input->get('id');
        $bodega = $this->input->get('bodega');
        $total = 0;
        $stock = 0;



      $query = $this->db->query('update existencia_detalle set cantidad_entrada_tarjeta = cantidad_entrada where cantidad_entrada_tarjeta is null');

      $query = $this->db->query('update existencia_detalle set cantidad_salida_tarjeta = cantidad_salida where cantidad_salida_tarjeta is null');        
           
		if($nombres){
		    if ($bodega){			
			/*$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega, cor.nombre as nom_tipo_movimiento, c.codigo as codigo, c.p_venta as valor_producto, c.stock_critico as stock_critico, c.p_promedio as p_promedio FROM existencia_detalle acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
			WHERE acc.id_producto="'.$nombres.'" and acc.saldo > 0 and acc.id_bodega="'.$bodega.'" ');*/

			/*$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega, cor.nombre as nom_tipo_movimiento, c.codigo as codigo, c.p_venta as valor_producto, c.stock_critico as stock_critico, c.p_promedio as p_promedio FROM existencia_detalle acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
			WHERE acc.id_producto="'.$nombres.'" and acc.considera_tarjeta = 1 and acc.saldo != 0 and acc.id_bodega="'.$bodega.'" ');*/



			$query = $this->db->query("SELECT 		acc.id
													,acc.id_producto
													,acc.id_bodega
													,bod.nombre as nom_bodega
													,c.codigo as codigo
													,c.nombre as nom_producto
													,acc.id_tipo_movimiento AS tipo_movimiento
													,cor.nombre as nom_tipo_movimiento
													,acc.stock
													,acc.num_movimiento
													,acc.cantidad_entrada
													,acc.cantidad_salida
													,acc.fecha_movimiento
													,acc.fecha_ultimo_movimiento
													,c.p_venta as valor_producto
													,acc.valor_producto_neto
													,acc.saldo
													,acc.fecha_vencimiento
													,acc.lote
													,c.stock_critico as stock_critico
													,c.p_promedio as p_promedio																																										
													
									FROM 			(
													SELECT 		MAX(acc.id) AS id
																	,MAX(acc.id_producto) AS id_producto
																	,MAX(acc.id_bodega) AS id_bodega
																	,'' AS nom_bodega
																	,'' AS codigo
																	,'' AS nom_producto
																	,MAX(acc.id_tipo_movimiento) AS id_tipo_movimiento
																	,'' AS nom_tipo_movimiento
																	,0 AS stock
																	,MAX(acc.num_movimiento) AS num_movimiento
																	,SUM(acc.cantidad_entrada_tarjeta) AS cantidad_entrada
																	,SUM(acc.cantidad_salida_tarjeta) AS cantidad_salida
																	,MAX(acc.fecha_movimiento) AS fecha_movimiento
																	,MAX(acc.fecha_movimiento) AS fecha_ultimo_movimiento
																	,0 AS valor_producto
																	,0 AS valor_producto_neto
																	,(SUM(acc.cantidad_entrada_tarjeta) - SUM(acc.cantidad_salida_tarjeta)) AS saldo
																	,MAX(acc.fecha_movimiento) AS fecha_vencimiento
																	,acc.lote AS lote
																	,0 AS stock_critico
																	,0 AS p_promedio
													FROM			existencia_detalle acc
													WHERE 	 	acc.id_producto='".$nombres."' and acc.considera_tarjeta = 1 and acc.id_bodega='".$bodega."'	
													GROUP BY 	acc.lote
													)acc
									left join productos c on (acc.id_producto = c.id)
									left join bodegas bod on (acc.id_bodega = bod.id)
									left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
									WHERE acc.saldo != 0");			


			foreach ($query->result() as $row)		    
			{
				$total = $total +1;		
			}
			$countAll = $total;

			/*$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega, cor.nombre as nom_tipo_movimiento, c.codigo as codigo, c.p_venta as valor_producto, c.stock_critico as stock_critico, c.p_promedio as p_promedio FROM existencia_detalle acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
			WHERE acc.id_producto="'.$nombres.'" and acc.considera_tarjeta = 1 and acc.saldo != 0 and acc.id_bodega="'.$bodega.'" 
			order by acc.id desc
		    limit '.$start.', '.$limit.'');*/




						$query = $this->db->query("SELECT 		acc.id
																,acc.id_producto
																,acc.id_bodega
																,bod.nombre as nom_bodega
																,c.codigo as codigo
																,c.nombre as nom_producto
																,acc.id_tipo_movimiento AS tipo_movimiento
																,cor.nombre as nom_tipo_movimiento
																,acc.stock
																,acc.num_movimiento
																,acc.cantidad_entrada
																,acc.cantidad_salida
																,acc.fecha_movimiento
																,acc.fecha_ultimo_movimiento
																,c.p_venta as valor_producto
																,acc.valor_producto_neto
																,acc.saldo
																,acc.fecha_vencimiento
																,acc.lote
																,c.stock_critico as stock_critico
																,c.p_promedio as p_promedio																																										
																
												FROM 			(
																SELECT 		MAX(acc.id) AS id
																				,MAX(acc.id_producto) AS id_producto
																				,MAX(acc.id_bodega) AS id_bodega
																				,'' AS nom_bodega
																				,'' AS codigo
																				,'' AS nom_producto
																				,MAX(acc.id_tipo_movimiento) AS id_tipo_movimiento
																				,'' AS nom_tipo_movimiento
																				,0 AS stock
																				,MAX(acc.num_movimiento) AS num_movimiento
																				,SUM(acc.cantidad_entrada_tarjeta) AS cantidad_entrada
																				,SUM(acc.cantidad_salida_tarjeta) AS cantidad_salida
																				,MAX(acc.fecha_movimiento) AS fecha_movimiento
																				,MAX(acc.fecha_movimiento) AS fecha_ultimo_movimiento
																				,0 AS valor_producto
																				,0 AS valor_producto_neto
																				,(SUM(acc.cantidad_entrada_tarjeta) - SUM(acc.cantidad_salida_tarjeta)) AS saldo
																				,MAX(acc.fecha_movimiento) AS fecha_vencimiento
																				,acc.lote AS lote
																				,0 AS stock_critico
																				,0 AS p_promedio
																FROM			existencia_detalle acc
																WHERE 	 	acc.id_producto='".$nombres."' and acc.considera_tarjeta = 1 and acc.id_bodega='".$bodega."'	
																GROUP BY 	acc.lote
																)acc
												left join productos c on (acc.id_producto = c.id)
												left join bodegas bod on (acc.id_bodega = bod.id)
												left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
												WHERE acc.saldo != 0
												limit ".$start.", ".$limit);		




			}
			$data = array();
		
		foreach ($query->result() as $row)
		{

			/*$query_saldo = $this->db->query('SELECT SUM(cantidad_entrada_tarjeta) - SUM(cantidad_salida_tarjeta) AS saldo 
										FROM 			existencia_detalle
										WHERE 		 id_producto = "'.$nombres.'"
										AND 			 id_bodega = "'.$bodega.'" 
										and 			 considera_tarjeta = 1
										AND 			 lote = "'.  $row->lote . '"');		
			//$row_saldo = $query_saldo->result();
			foreach ($query_saldo->result() as $row_saldo){
				if(isset($row_saldo->saldo)){
					if(!is_null($row_saldo->saldo)){

						$row->saldo = $row_saldo->saldo;
					}
				}

			}*/
				

			$row->valor_producto_neto = ($row->valor_producto/1.19);
			$data[] = $row;
		}
		}        
		
		
        $resp['success'] = true;
        //$resp['stock'] = $stock;
        $resp['data'] = $data;

        echo json_encode($resp);
	}
}
