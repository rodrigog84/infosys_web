<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bitacora_aviso extends CI_Controller {



	public function __construct()
	{
		parent::__construct();
		
		$this->load->database();
	}
	
	public function getAll(){
		$resp = array();

        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $filter = $this->input->get('nombre');

		$countAll = $this->db->count_all_results("bitacora_avisos");

		if($filter){
			$query = $this->db->query('SELECT acc.*, c.username as nom_usuario, c.apellido as apellido_usuario, a.nombre as nom_email, a.email as email FROM bitacora_avisos acc
			left join usuario c on (acc.id_usuario = c.id)
			left join email_autorizados a on (acc.id_email = a.id) 
			WHERE acc.id_email = '.$filter.'
			order by acc.fecha_aviso desc
			');
		}else{
			$query = $this->db->query('SELECT acc.*, c.username as nom_usuario, c.apellido as apellido_usuario, a.nombre as nom_email, a.email as email FROM bitacora_avisos acc
			left join usuario c on (acc.id_usuario = c.id)
			left join email_autorizados a on (acc.id_email = a.id) 
			order by acc.id desc
			limit '.$start.', '.$limit.' '
            );
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

