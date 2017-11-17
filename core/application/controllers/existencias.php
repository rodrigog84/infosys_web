<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Existencias extends CI_Controller {

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
        $idBodega = $this->input->get('bodega');

        if (!$idBodega){        	
        	$idBodega = 1;
        };
           
		$countAll = $this->db->count_all_results("existencia");
        
		if($nombres){
			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);
	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "c.nombre like '%".$nombre."%' and ";
	        }

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, b.nombre as nom_bodega FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas b on (acc.id_bodega = b.id)
			WHERE ' . $sql_nombre . ' 1 = 1 and acc.id_bodega = "'.$idBodega.'"');
		}else{
			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, b.nombre as nom_bodega FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas b on (acc.id_bodega = b.id)
			WHERE acc.id_bodega = "'.$idBodega.'"
		    limit '.$start.', '.$limit.' ' );
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

	public function getAllSlectivo(){
		
		$resp = array();
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $nombres = $this->input->get('nombre');
        $opcion = $this->input->get('opcion');
        $total= 0;
           
		//$countAll = $this->db->count_all_results("existencia");

		if($opcion){ 
		
		   if($nombres){
			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);
	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "c.nombre like '%".$nombre."%' and ";
	        }

	        $query2 = $this->db->query('SELECT acc.*, c.nombre as nom_producto, b.nombre as nom_bodega, c.p_costo as p_costo, c.p_venta as p_venta FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas b on (acc.id_bodega = b.id)
			WHERE ' . $sql_nombre . ' 1 = 1 and acc.id_bodega = "'.$opcion.'"');

			foreach ($query2->result() as $row)		    
			{
				$total = $total +1;		
			}
			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, b.nombre as nom_bodega, c.p_costo as p_costo, c.p_venta as p_venta FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas b on (acc.id_bodega = b.id)
			WHERE ' . $sql_nombre . ' 1 = 1 and acc.id_bodega = "'.$opcion.'"
			limit '.$start.', '.$limit.'');
			
		    }else{
		    
			$query2 = $this->db->query('SELECT acc.*, c.nombre as nom_producto, b.nombre as nom_bodega, c.p_costo as p_costo, c.p_venta as p_venta FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas b on (acc.id_bodega = b.id)
			WHERE acc.id_bodega = "'.$opcion.'"');

			foreach ($query2->result() as $row)		    
			{
				$total = $total +1;		
			}
			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, b.nombre as nom_bodega, c.p_costo as p_costo, c.p_venta as p_venta FROM existencia acc
			left join productos c on (acc.id_producto = c.id)
			left join bodegas b on (acc.id_bodega = b.id)
			WHERE acc.id_bodega = "'.$opcion.'"
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

		};      
	
	    
		

        echo json_encode($resp);
	}
}
