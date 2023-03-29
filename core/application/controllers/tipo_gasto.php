<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tipo_gasto extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->database();
	}

	public function save(){
		
		$resp = array();
		$data = json_decode($this->input->post('data'));
		$id = $data->nombre;
		$data = array(
	        'nombre' => strtoupper($data->nombre),
	            
		);

		$this->db->insert('tipo_gasto', $data); 

        $resp['success'] = true;

        $this->Bitacora->logger("I", 'tipo_gasto', $id);

        echo json_encode($resp);

	}

	public function update(){
		$resp = array();

		$data = json_decode($this->input->post('data'));
		$id = $data->id;
		$data = array(
	        'nombre' => strtoupper($data->nombre),
	    );
		$this->db->where('id', $id);
		
		$this->db->update('tipo_gasto', $data); 

        $resp['success'] = true;

         $this->Bitacora->logger("M", 'tipo_gasto', $id);


        echo json_encode($resp);

	}

	public function getAll(){
		$resp = array();

        $start = $this->input->post('start');
        $limit = $this->input->post('limit');


        //filtro por nombre
        $nombre = $this->input->get('nombre');

		$countAll = $this->db->count_all_results("tipo_gasto");

		if($nombre){
			$query = $this->db->query('SELECT * FROM tipo_gasto WHERE nombre like "%'.$nombre.'%"
			');
		}else{
			
			$query = $this->db->query('SELECT * FROM tipo_gasto ');
			
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
}
