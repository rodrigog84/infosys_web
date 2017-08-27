<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Existenciasclientes extends CI_Controller {

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
        $rut = $this->input->get('rut');
           
		if (!$nombres){
			$query1 = $this->db->query('SELECT acc.*, ciu.nombre as nombre_ciudad, com.nombre as nombre_comuna, g.nombre as nom_giro, 
			ven.nombre as nombre_vendedor, g.nombre as giro FROM clientes acc
			left join ciudad c on (acc.id_ciudad = c.id)
			left join cod_activ_econ g on (acc.id_giro = g.id)
			left join comuna com on (acc.id_comuna = com.id)
			left join comuna ciu on (acc.id_ciudad = ciu.id)
			left join vendedores ven on (acc.id_vendedor = ven.id)
			WHERE acc.rut="'.$rut.'"');
						
	   		if($query1->num_rows()>0){
	   			
	   			$v = $query1->first_row();
	   			$id = $v->id;	   						
				$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, cor.nombre as nom_tipo_movimiento, c.codigo as codigo, bod.nombre as nom_bodega FROM existencia_detalle acc
				left join productos c on (acc.id_producto = c.id)
				left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
				left join bodegas bod on (acc.id_bodega = bod.id)
				WHERE acc.id_cliente="'.$id.'"');

	   			$data = array();

	   				$total = 0;

			    foreach ($query->result() as $row)
				{
					$total = $total +1;
				
				}

				$countAll = $total;

				$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, cor.nombre as nom_tipo_movimiento, c.codigo as codigo, bod.nombre as nom_bodega FROM existencia_detalle acc
				left join productos c on (acc.id_producto = c.id)
				left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
				left join bodegas bod on (acc.id_bodega = bod.id)
				WHERE acc.id_cliente="'.$id.'"
				limit '.$start.', '.$limit.'');

		
				foreach ($query->result() as $row)
				{
					$data[] = $row;
				}
		        $resp['success'] = true;
		        $resp['total'] = $countAll;
		        $resp['data'] = $data;
       

	   		}

		}else{

			$query1 = $this->db->query('SELECT acc.*, ciu.nombre as nombre_ciudad, com.nombre as nombre_comuna, g.nombre as nom_giro, 
			ven.nombre as nombre_vendedor, g.nombre as giro FROM clientes acc
			left join ciudad c on (acc.id_ciudad = c.id)
			left join cod_activ_econ g on (acc.id_giro = g.id)
			left join comuna com on (acc.id_comuna = com.id)
			left join comuna ciu on (acc.id_ciudad = ciu.id)
			left join vendedores ven on (acc.id_vendedor = ven.id)
			WHERE acc.rut="'.$rut.'"');

			
	   		if($query1->num_rows()>0){
	   			
	   			$v = $query1->first_row();
	   			$id = $v->id;
	   			
				$query3 = $this->db->query('SELECT * FROM productos WHERE codigo="'.$nombres.'"');
				if($query3->num_rows()>0){
					$row = $query3->first_row();
					$idproducto = $row->id;
				};	   		
								
				$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, cor.nombre as nom_tipo_movimiento, c.codigo as codigo, bod.nombre as nom_bodega FROM existencia_detalle acc
				left join productos c on (acc.id_producto = c.id)
				left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
				left join bodegas bod on (acc.id_bodega = bod.id)
				WHERE acc.id_producto="'.$idproducto.'" and acc.id_cliente="'.$id.'"');

				$total = 0;

			    foreach ($query->result() as $row)
				{
					$total = $total +1;
				
				}

				$countAll = $total;

				$query = $this->db->query('SELECT acc.*, c.nombre as nom_producto, cor.nombre as nom_tipo_movimiento, c.codigo as codigo,bod.nombre as nom_bodega FROM existencia_detalle acc
				left join productos c on (acc.id_producto = c.id)
				left join correlativos cor on (acc.id_tipo_movimiento = cor.id)
				left join bodegas bod on (acc.id_bodega = bod.id)
				WHERE acc.id_producto="'.$idproducto.'" and acc.id_cliente="'.$id.'"
				limit '.$start.', '.$limit.'');


	   			$data = array();
		
				foreach ($query->result() as $row)
				{
					$data[] = $row;
				}
		        $resp['success'] = true;
		        $resp['total'] = $countAll;
		        $resp['idp'] =  $idproducto;
		        $resp['idcl'] =  $id;		       
		        $resp['data'] = $data;

		        

	   		}

	   			
	   }

	   echo json_encode($resp);	

   }
}
