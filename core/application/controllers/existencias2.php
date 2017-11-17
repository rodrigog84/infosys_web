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
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega  FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			WHERE acc.id_producto="'.$nombres.'"');

			foreach ($query->result() as $row)		    
			{
				$total = $total +1;		
			}
			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega  FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas bod on (acc.id_bodega = bod.id)
			WHERE acc.id_producto="'.$nombres.'"
			 order by acc.id desc
		    limit '.$start.', '.$limit.'');
		}else{
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, bod.nombre as nom_bodega  FROM existencia acc
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
			WHERE acc.id_producto="'.$nombres.'" and acc.id_bodega="'.$bodega.'"
			order by acc.id desc
		    limit '.$start.', '.$limit.' ' );
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
}
