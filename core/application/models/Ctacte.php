<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Model
*
* Version: 2.5.2
*
* Author:  Ben Edmunds
* 		   ben.edmunds@gmail.com
*	  	   @benedmunds
*
* Added Awesomeness: Phil Sturgeon
*
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*
* Created:  10.01.2009
*
* Last Change: 3.22.13
*
* Changelog:
* * 3-22-13 - Additional entropy added - 52aa456eef8b60ad6754b31fbdcc77bb
*
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.
* Original Author name has been kept but that does not mean that the method has not been modified.
*
* Requirements: PHP5 or above
*
*/

class Ctacte extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('cookie');
		$this->load->helper('date');
	}


	 public function busca_parametro_cc($parametro){
		$this->db->select("ifnull(valor,'') as valor",	false)
		  ->from('param_cc')
		  ->where('nombre',$parametro);
		$query = $this->db->get();
		$parametro = $query->row();	

		return $parametro->valor;
	 }	

	 public function set_parametro_cc($parametro,$valor){
		  $this->db->where('nombre',$parametro);
		  $this->db->update('param_cc',array('valor' => $valor));
		return 1;
	 }		 



	 public function get_clave_vigente(){

	 	$usuario = $this->session->userdata('username');
	 	$fec_compara = date('Y-m-d H:i:s');
		$this->db->select("clave, TIME_TO_SEC(TIMEDIFF(fec_caducidad,'" . $fec_compara . "')) AS tiemporestante",	false)
		  ->from('clave_acceso_dinamica')
		  ->where('usuario',$usuario)
		  ->where("fec_caducidad >= '" . $fec_compara . "'"); 	
		$query = $this->db->get();
		$clave = $query->result();	

		return $clave;

	 }



	public function crea_clave_vigente($clave){

			$usuario = $this->session->userdata('username');
			$fec_actual = date('Y-m-d H:i:s');
			$fec_caducidad= strtotime("+ 90 seconds", strtotime ($fec_actual));
			$fec_caducidad = date('Y-m-d H:i:s',$fec_caducidad);


			$array_insert = array(
						'clave' => $clave,
						'fec_genera' => $fec_actual,
						'fec_caducidad' => $fec_caducidad,
						'usuario' => $usuario
						);

		$this->db->insert('clave_acceso_dinamica',$array_insert); 
		return $clave;
	}	 





	 public function del_clave_caducada(){

	 	$usuario = $this->session->userdata('username');
	 	$fec_actual = date('Y-m-d H:i:s');
		  $this->db->where('usuario',$usuario);
		  $this->db->where("fec_caducidad < '" . $fec_actual . "'");
		  $this->db->delete('clave_acceso_dinamica');

		return $clave;

	 }


}
