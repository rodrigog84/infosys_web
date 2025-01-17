<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientefinal extends CI_Controller {

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
			'rut' => $data->rut,
	        'nombre' => $data->nombre,
	        'direccion' => $data->direccion
		);

		$this->db->insert('cliente_final', $data); 

        $resp['success'] = true;

        $this->Bitacora->logger("I", 'cliente_final', $id);

        echo json_encode($resp);

	}

	public function update(){
		$resp = array();

		$data = json_decode($this->input->post('data'));
		$id = $data->id;
		$data = array(
			'rut' => $data->rut,
	        'nombre' => $data->nombre,
	        'direccion' => $data->direccion        
	    );
		$this->db->where('id', $id);
		
		$this->db->update('cliente_final', $data); 

        $resp['success'] = true;

        $this->Bitacora->logger("I", 'cliente_final', $id);

        echo json_encode($resp);

	}

	public function getAll(){
		$resp = array();

        $start = $this->input->post('start');
        $limit = $this->input->post('limit');
        //filtro por nombre
        $nombre = $this->input->post('nombre');

		$countAll = $this->db->count_all_results("cliente_final");

		if($nombre){
			$query = $this->db->query('SELECT * FROM cliente_final WHERE nombre like "%'.$nombre.'%" 
			limit '.$start.', '.$limit.'');
		}else{
			$query = $this->db->query('SELECT * FROM cliente_final limit '.$start.', '.$limit.'');
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
