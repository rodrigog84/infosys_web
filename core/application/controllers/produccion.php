<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Produccion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();	
			
		$this->load->database();
	}

	public function getAll(){
		
		$resp = array();

        $start = $this->input->post('start');
        $limit = $this->input->post('limit');
                		
        $data = array();		
		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)
		');

	    $total = 0;

		foreach ($query->result() as $row)
		{
		$total = $total +1;

		}

		$countAll = $total;

		$query = $this->db->query('SELECT acc.*, c.nombres as nom_cliente, c.rut as rut_cliente, p.fecha_pedido as fecha_pedido FROM produccion acc
		left join clientes c on (acc.id_cliente = c.id)
		left join pedidos p on (acc.id_pedido = p.id)');

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
	        'num_produccion' => $idcliente,
	        'fecha_produccion' => $fechaproduccion,
	        'nom_formula' => $nombreformula,
	        'nom_producto' => $nombreproducto,
	        'id_producto' => $idproducto,
	        'nom_formula' => $nombreformula,
	        'cantidad' => $cantidadproduccion,
	        'lote' => $lote,
	        'hora_inicio' => $horainicio,
	        'encargado' => $encargado,
	        'estado' => 1
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
		        'valor_produccion' => $v->valor_produccion,
		        'porcentaje' => $v->porcentaje
			);

		
		$this->db->insert('produccion_detalle', $produccion_detalle);
	   	
		};
		
        $resp['success'] = true;
       
		$this->Bitacora->logger("I", 'produccion', $idproduccion);
		$this->Bitacora->logger("I", 'produccion_detalle', $idproduccion);        

        echo json_encode($resp);
	}
	
}
