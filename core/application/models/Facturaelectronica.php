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

class Facturaelectronica extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->helper('cookie');
		$this->load->helper('date');
	}



	public function ruta_libredte(){
		$base_path = __DIR__;
		$base_path = str_replace("\\", "/", $base_path);
		$path = $base_path . "/../libraries/inc.php";		
		return $path;
	}

	public function genera_config(){
		$config = [
		    'firma' => [
		        'file' => $this->ruta_certificado(),
		        'pass' => $this->busca_parametro_fe('cert_password'),
		    ],
		];

		return $config;
	}


	public function ruta_certificado(){
		$base_path = __DIR__;
		$base_path = str_replace("\\", "/", $base_path);
		$path = $base_path . "/../../facturacion_electronica/certificado/certificado.pfx";		
		return $path;
	}

	 public function busca_parametro_fe($parametro){
		$this->db->select('valor ')
		  ->from('param_fe')
		  ->where('nombre',$parametro);
		$query = $this->db->get();
		$parametro = $query->row();	
		return $parametro->valor;
	 }	


	 public function set_parametro_fe($parametro,$valor){
		  $this->db->where('nombre',$parametro);
		  $this->db->update('param_fe',array('valor' => $valor));
		return 1;
	 }		 


	 public function put_trackid($idfactura,$trackid){
		  $this->db->where('idfactura',$idfactura);
		  $this->db->update('folios_caf',array('trackid' => $trackid));
		return 1;
	 }		 

	  public function put_trackid_libro($idlibro,$trackid){
		  $this->db->where('id',$idlibro);
		  $this->db->update('log_libros',array('trackid' => $trackid));
		return 1;
	 }	 


	 public function contribuyentes_autorizados($start = null,$limit = null){

	 	$tabla_contribuyentes = $this->busca_parametro_fe('tabla_contribuyentes');

	 	$countAll = $this->db->count_all_results($tabla_contribuyentes);

		$data = $this->db->select('rut, dv, concat(rut,"-",dv) as rut_contribuyente, razon_social, nro_resolucion, date_format(fec_resolucion,"%d/%m/%Y") as fec_resolucion, mail, url',false)
		  ->from($tabla_contribuyentes)
		  ->order_by('razon_social');


		$data = is_null($start) || is_null($limit) ? $data : $data->limit($limit,$start);
		$query = $this->db->get();
//			print_r($query->result()); 
		return array('total' => $countAll, 'data' => $query->result());

	 }



	  public function consumo_folios($start = null,$limit = null){

	  	$countAll = $this->db->count_all_results('consumo_folios');
	 	$data = $this->db->select('id, fecha, cant_folios, folio_desde, folio_hasta, path_consumo_folios, archivo_consumo_folios, xml, trackid',false)
		  ->from('consumo_folios')
		  ->order_by('fecha','desc');


		$data = is_null($start) || is_null($limit) ? $data : $data->limit($limit,$start);
		$query = $this->db->get();

//			print_r($query->result());
		$result =  $query->result();
		//$countAll = count($result);
		return array('total' => $countAll, 'data' => $result);

	 }


	 public function log_libros($start = null,$limit = null,$estado = null){

	 	$countAll = $this->db->count_all_results('log_libros');
		$data = $this->db->select('id, mes, anno, tipo_libro, archivo, date_format(fecha_solicita,"%d/%m/%Y %H:%i:%s") as fecha_solicita, date_format(fecha_procesa,"%d/%m/%Y %H:%i:%s") as fecha_creacion, estado',false)
		  ->from('log_libros')
		  ->order_by('anno','desc')
		  ->order_by('mes','desc');

		$data = is_null($start) || is_null($limit) ? $data : $data->limit($limit,$start);
		$data = is_null($estado) ? $data : $data->where('estado',$estado);		
		$query = $this->db->get();
		return array('total' => $countAll, 'data' => $query->result());

	 }

	public function get_empresa(){
		$this->db->select('rut, dv, razon_social, giro, cod_actividad, dir_origen, comuna_origen, fec_resolucion, nro_resolucion, logo, texto_fono, texto_sucursales, fec_inicio_boleta ')
		  ->from('empresa')
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	 }


	public function datos_dte_periodo($mes,$anno){
		$this->db->select("f.folio, f.path_dte, f.archivo_dte, f.dte, f.pdf, f.pdf_cedible, f.trackid, c.tipo_caf, tc.nombre as tipo_doc, fc.fecha_factura, concat(left(cl.rut,length(cl.rut)-1),'-',upper(right(cl.rut,1))) as rut, cl.nombres, fc.neto, fc.iva, fc.totalfactura ",false)
		  ->from('folios_caf f')
		  ->join('caf c','f.idcaf = c.id')
		  ->join('tipo_caf tc','c.tipo_caf = tc.id')
		  ->join('factura_clientes fc','f.idfactura = fc.id')
		  ->join('clientes cl','fc.id_cliente = cl.id')
		  ->where('left(fc.fecha_factura,7)',$anno."-".$mes)
		  ->where('c.tipo_caf <> 52')
		  //->where('left(f.updated_at,7)',$anno."-".$mes) //AUN TENEMOS FACTURAS QUE NO SE EMITEN POR EL SISTEMA
		  ->where('f.estado','O');
		$query = $this->db->get();
		return $query->result();
	}



	public function datos_dte_proveedores_periodo($mes,$anno){
		$this->db->select('d.id, d.idproveedor, d.dte, d.envios_recibos, d.recepcion_dte, d.resultado_dte ')
		  ->from('dte_proveedores d')
		  ->where('left(d.fecha_documento,7)',$anno."-".$mes);
		$query = $this->db->get();
		return $query->result();
	}


	public function valida_existe_libro($mes,$anno,$tipo){
		$this->db->select('id, mes, anno, tipo_libro ')
		  ->from('log_libros')
		  ->where('mes',$mes)
		  ->where('anno',$anno)
		  ->where('tipo_libro',$tipo);
		$query = $this->db->get();
		return count($query->result()) > 0 ? true : false;
	}

	public function genera_libro($id_libro,$tipo,$archivo,$xml_libro){
		$array_update = array(
					'estado' => 'G',
					'fecha_procesa' => date("Y-m-d H:i:s"),
					'archivo' => $archivo,
					'xml_libro' => $xml_libro
					);

	    $this->db->where('id', $id_libro);
		$this->db->update('log_libros',$array_update); 




		//$this->db->insert('log_libros',$array_insert); 
		return true;
	}
	


	public function put_log_libros($mes,$anno,$tipo,$archivo){

			$array_insert = array(
						'mes' => $mes,
						'anno' => $anno,
						'tipo_libro' => $tipo,
						'trackid' => 0,
						'fecha_solicita' => date("Y-m-d H:i:s"),
						'archivo' => $archivo
						);

		$this->db->insert('log_libros',$array_insert); 
		return $this->db->insert_id();
	}




	public function get_consumo_folios($fecha){


		$this->db->select('id, fecha, cant_folios, folio_desde, folio_hasta, xml, trackid',false)
		  ->from('consumo_folios')
		  ->where('fecha',$fecha)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	 }	 


public function get_consumo_folios_by_id($id){


		$this->db->select('id, fecha, cant_folios, folio_desde, folio_hasta, path_consumo_folios, archivo_consumo_folios, xml, trackid',false)
		  ->from('consumo_folios')
		  ->where('id',$id)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	 }	

	public function get_empresa_factura($id_factura){

		$tabla_contribuyentes = $this->busca_parametro_fe('tabla_contribuyentes');

		$this->db->select('c.nombres as nombre_cliente, c.rut as rut_cliente, c.direccion, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, c.fono, e.nombre as giro, ifnull(ca.mail,c.e_mail) as e_mail, cs.direccion as dir_sucursal, d.nombre as com_sucursal, c.id as idcliente',false)
		  ->from('factura_clientes acc')
		  ->join('clientes c','acc.id_cliente = c.id','left')
		  ->join('cod_activ_econ e','c.id_giro = e.id','left')
		  ->join('clientes_sucursales cs','acc.id_sucursal = cs.id','left')
		  ->join('comuna m','c.id_comuna = m.id','left')
		  ->join('comuna d','cs.id_comuna = d.id','left')		  
		  ->join('ciudad s','c.id_ciudad = s.id','left')	
		  ->join($tabla_contribuyentes . ' ca','c.rut = concat(ca.rut,ca.dv)','left')
		  ->where('acc.id',$id_factura)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	 }	 


	public function get_detalle_factura($id_factura){
		$this->db->select('p.nombre, p.codigo, f.precio, f.cantidad, f.descuento , f.iva, f.totalproducto, f.lote, f.fecha_vencimiento, f.neto')
		  ->from('detalle_factura_cliente f')
		  ->join('productos p','f.id_producto = p.id')
		  ->where('f.id_factura',$id_factura);
		$query = $this->db->get();
		return $query->result();
	 }

	public function get_detalle_factura_glosa($id_factura){
		$this->db->select('p.nombre, f.glosa, f.cantidad, f.neto, f.iva, f.total, f.kilos ')
		  ->from('detalle_factura_glosa f')
		  ->join('productos p','f.id_producto = p.id','left')
		  ->where('f.id_factura',$id_factura);
		$query = $this->db->get();
		return $query->result();
	 }	 


public function consumo_folios_no_enviada(){
		$this->db->select('c.id, c.fecha, c.cant_folios, c.folio_desde, c.folio_hasta, c.path_consumo_folios, c.archivo_consumo_folios')
		  ->from('consumo_folios c ')
		  ->where('c.trackid','0');
		$query = $this->db->get();
		return $query->result();
	 }	


	public function get_factura_no_enviada(){
		$this->db->select('c.idfactura')
		  ->from('folios_caf c ')
		  ->join('factura_clientes fc','c.idfactura = fc.id')
		  ->join('caf f', 'c.idcaf = f.id')
		  ->where('c.trackid','0')
		  ->where('c.idfactura <> 0')
		  ->where('c.estado','O')
		   ->where("left(c.updated_at,10) >= '2020-11-23'")
		   ->where('f.tipo_caf <> 39');
		$query = $this->db->get();
		return $query->result();
	 }	



	public function get_boleta_no_enviada()
    {
        // envia todo excepto boletas que se envían aparte
        $facturas = $this->db->select('c.idfactura')
            ->from('folios_caf c ')
            ->join('factura_clientes fc', 'c.idfactura = fc.id')
            ->join('caf f', 'c.idcaf = f.id')
            ->where('c.trackid', '0')
            ->where('c.idfactura <> 0')
            ->where('c.estado', 'O')
            ->where("left(c.updated_at,10) >= '2020-11-23'")
            ->where('f.tipo_caf = 39');

        $query = $facturas->get();
        return $query->result();
    } 

	public function get_boletas_dia($fecha){
		$this->db->select('c.idfactura')
		  ->from('folios_caf c ')
		  ->join('factura_clientes fc','c.idfactura = fc.id')
		  ->join('caf f','c.idcaf = f.id')
		  ->where('c.idfactura <> 0')
		  ->where('f.tipo_caf',39)
		  ->where('c.estado','O')
		  ->where('fc.fecha_factura',$fecha);
		$query = $this->db->get();
		//echo $this->db->last_query(); exit;
		return $query->result();
	 }		 


	public function get_content_caf_folio($folio,$tipo_documento){
		$this->db->select('c.archivo, c.caf_content ')
		  ->from('caf c')
		  ->join('folios_caf f','f.idcaf = c.id')
		  ->where('f.folio',$folio)
		  ->where('c.tipo_caf',$tipo_documento)
		  ->limit(1);
		  $query = $this->db->get();
		  $caf = $query->row();					  
		  return $caf;
	 }	 

	/*public function datos_dte($idfactura){

		$this->db->select('f.id, f.folio, f.path_dte, f.archivo_dte, f.dte, f.pdf, f.pdf_cedible, f.trackid, c.tipo_caf, tc.nombre as tipo_doc ')
		  ->from('folios_caf f')
		  ->join('caf c','f.idcaf = c.id')
		  ->join('tipo_caf tc','c.tipo_caf = tc.id')
		  ->where('f.idfactura',$idfactura)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}*/	


	public function datos_dte($idfactura){


		$this->db->select('f.id, f.folio, f.path_dte, f.archivo_dte, f.archivo_dte_cliente, f.dte, f.dte_cliente, f.pdf, f.pdf_cedible, f.trackid, c.tipo_caf, tc.nombre as tipo_doc, cae.nombre as giro, cp.nombre as cond_pago, v.nombre as vendedor, fc.neto, fc.iva, fc.totalfactura, fc.forma')
		  ->from('folios_caf f')
		  ->join('caf c','f.idcaf = c.id')
		  ->join('tipo_caf tc','c.tipo_caf = tc.id')
		  ->join('factura_clientes fc','f.idfactura = fc.id','left')
		  ->join('clientes cl','fc.id_cliente = cl.id','left')
		  ->join('cod_activ_econ cae','cl.id_giro = cae.id','left')
		  ->join('cond_pago cp','fc.id_cond_venta = cp.id','left')
		  ->join('vendedores v','fc.id_vendedor = v.id','left')

		  ->where('f.idfactura',$idfactura)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}	


	public function get_libro_by_id($idlibro){
		$this->db->select('id, mes, anno, tipo_libro, archivo, date_format(fecha_solicita,"%d/%m/%Y %H:%i:%s") as fecha_solicita, date_format(fecha_procesa,"%d/%m/%Y %H:%i:%s") as fecha_creacion, estado, trackid, xml_libro, created_at',false)
		  ->from('log_libros')
		  ->where('id',$idlibro);
		$query = $this->db->get();
		return $query->row();
	}

	public function datos_dte_by_trackid($trackid){
		$this->db->select('f.id, f.folio, f.path_dte, f.archivo_dte, f.dte, f.pdf, f.pdf_cedible, f.trackid, c.tipo_caf, tc.nombre as tipo_doc, cae.nombre as giro, cp.nombre as cond_pago, v.nombre as vendedor    ')
		  ->from('folios_caf f')
		  ->join('caf c','f.idcaf = c.id')
		  ->join('tipo_caf tc','c.tipo_caf = tc.id')
		  ->join('factura_clientes fc','f.idfactura = fc.id','left')
		  ->join('clientes cl','fc.id_cliente = cl.id','left')
		  ->join('cod_activ_econ cae','cl.id_giro = cae.id','left')	
		  ->join('cond_pago cp','fc.id_cond_venta = cp.id','left')	
		  ->join('vendedores v','fc.id_vendedor = v.id','left')  
		  ->where('f.trackid',$trackid)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}		



	public function datos_dte_provee($iddte){
		$this->db->select('d.id, p.nombres as proveedor, p.e_mail, d.path_dte, d.arch_rec_dte, d.arch_res_dte, d.arch_env_rec, date_format(d.fecha_documento,"%d/%m/%Y") as fecha_documento , date_format(d.created_at,"%d/%m/%Y") as fecha_creacion ',false)
		  ->from('dte_proveedores d')
		  ->join('proveedores p','d.idproveedor = p.id')
		  ->where('d.id',$iddte)
		  ->order_by('d.id','desc');
		$query = $this->db->get();
		return $query->row();
	}


	public function datos_dte_transporte($idfactura){
		$this->db->select('d.rut, d.nombre, d.pat_camion, d.pat_carro, d.observacion, d.destino ',false)
		  ->from('observacion_facturas d')
		  ->where('d.id_documento',$idfactura);
		$query = $this->db->get();
		return $query->row();
	}


	 public function exportFePDF($idfactura,$tipo_consulta,$cedible = null){

	 	/*print_r($idfactura);

	 	print_r($tipo_consulta);

	 	print_r($cedible);

	 	exit;*/

	 	include $this->ruta_libredte();	 	

	 	if($tipo_consulta == 'id'){
	 		$factura = $this->datos_dte($idfactura);
	 		//print_r($factura);
	 		//exit;

	 		
	 	}else if($tipo_consulta == 'trackid'){
	 		$factura = $this->datos_dte_by_trackid($idfactura);
	 	}
	 	$nombre_pdf = is_null($cedible) ? $factura->pdf : $factura->pdf_cedible;

	 	//file_exists 

	 	$crea_archivo = true;
	 	if($nombre_pdf != ''){
			$base_path = __DIR__;
			$base_path = str_replace("\\", "/", $base_path);
			$file = $base_path . "/../../facturacion_electronica/pdf/".$factura->path_dte.$nombre_pdf;		 		
	 		if(file_exists($file)){
	 			$crea_archivo = false;
	 		}
	 	}
	 	$crea_archivo = true;
	 	$empresa = $this->get_empresa();
	 		//print_r($empresa); exit;
	 	if($crea_archivo){
			// sin límite de tiempo para generar documentos
			set_time_limit(0);
		 	// archivo XML de EnvioDTE que se generará
		 	$archivo = "./facturacion_electronica/dte/".$factura->path_dte.$factura->archivo_dte;
		 	if(file_exists($archivo)){
		 		$content_xml = file_get_contents($archivo);
		 	}else{
		 		$content_xml = $factura->dte;
		 	}

		 	// Cargar EnvioDTE y extraer arreglo con datos de carátula y DTEs
		 	$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
		 	$EnvioDte->loadXML($content_xml);
			$Caratula = $EnvioDte->getCaratula();
			$Documentos = $EnvioDte->getDocumentos();	 	

			if(!file_exists('./facturacion_electronica/pdf/'.$factura->path_dte)){
				mkdir('./facturacion_electronica/pdf/'.$factura->path_dte,0777,true);
			}		

			$base_path = __DIR__;
			$base_path = str_replace("\\", "/", $base_path);
			$path_pdf = $base_path . "/../../facturacion_electronica/pdf/".$factura->path_dte;				

			foreach ($Documentos as $DTE) {
			    if (!$DTE->getDatos())
			        die('No se pudieron obtener los datos del DTE');
			    $pdf = new \sasco\LibreDTE\Sii\PDF\Dte(false); // =false hoja carta, =true papel contínuo (false por defecto si no se pasa)
			    $pdf->setFooterText();
			    $pdf->setLogo('./facturacion_electronica/images/logo_empresa.png'); // debe ser PNG!

			    if($factura->giro != ""){
			    	$pdf->setGiroCliente($factura->giro);
			    }			    

			    $pdf->setCondPago($factura->cond_pago); 
			    $pdf->setVendedor($factura->vendedor);
			    //	echo $empresa->giro; exit;
			    $pdf->setGiroEmisor($empresa->giro);
			    $pdf->setDireccion($empresa->dir_origen);
			    $pdf->setComuna($empresa->comuna_origen);
			    $pdf->setFono($empresa->texto_fono);
			    $pdf->setSucursales($empresa->texto_sucursales);


			    $pdf->setForma($factura->forma);

			    if($factura->neto > 0){


					$pdf->setNeto($factura->neto);
					$pdf->setIva($factura->iva);		
					$pdf->setTotal($factura->totalfactura);

			    	
			    }
			    

				// esto es para mostrar en la glosa el texto completo.  Hoy se muestra cortado debido a que el xml tiene un texto maximo
				if($factura->forma == 1){

					$detalle_factura_normal = $this->facturaelectronica->get_detalle_factura($idfactura); 
					if(count($detalle_factura_normal) == 0){

							$detalle_factura = $this->facturaelectronica->get_detalle_factura_glosa($idfactura);




							$total_detalle_glosa = 0;
							$detalle_glosa = array();
							$linea = 0;
							foreach ($detalle_factura as $linea_detalle) {
								array_push($detalle_glosa,array(
																'NroLinDet' => ($linea+1),
																'NmbItem' => $linea_detalle->glosa,
																'QtyItem' => 1,
																'PrcItem' => $linea_detalle->neto,
																'MontoItem' => $linea_detalle->neto
																));

								$total_detalle_glosa += $linea_detalle->total;
							}

							
							if($total_detalle_glosa == $factura->totalfactura){

								 $pdf->setDetalleGlosa($detalle_glosa);
							}



					}


				}


				$datos_transporte = $this->datos_dte_transporte($idfactura);
				
				if(count($datos_transporte) > 0){
					$transporte['RUTTrans'] = $datos_transporte->rut;
					$transporte['Chofer']['NombreChofer'] = $datos_transporte->nombre;
					$transporte['Patente'] = $datos_transporte->pat_camion;
					$transporte['Patente_Carro'] = $datos_transporte->pat_carro;
					$transporte['Destino'] = $datos_transporte->destino;

				}else{
					$transporte['RUTTrans'] = null;
					$transporte['Chofer']['NombreChofer'] = null;
					$transporte['Patente'] = null;
					$transporte['Patente_Carro'] = null;
					$transporte['Destino'] = null;

				}

				
				$pdf->setTransporte($transporte);




				//stdClass Object ( [rut] => 02675738K [nombre] => MAJUL SAN MARTIN MICHEL [pat_camion] => FY9540 [observacion] => Prueba )

						/*** agregar detalle para facturas de guias **/
				//echo $factura->tipo_caf ; exit;
				$texto_guias = "";
				if($factura->tipo_caf == 33){
						$this->db->select('num_guia ',false)
								  ->from('detalle_factura_glosa d')
								  ->where('d.id_factura',$idfactura)
								  ->where('d.id_guia != 0');
						$query = $this->db->get();
						$datos = $query->result();		
						if(count($datos) > 0){
							$texto_guias .= "SEGUN GUIAS NROS: ";
							foreach ($datos as $guia) {
								$texto_guias .= $guia->num_guia." ";
							}

						}
				}

				$pdf->setTextoGuia($texto_guias);	

			    //$pdf->setTransportista("Prueba");
			    
			    

			    $pdf->setResolucion(['FchResol'=>$Caratula['FchResol'], 'NroResol'=>$Caratula['NroResol']]);
			    /*if(!is_null($cedible)){
			    	$pdf->setCedible(true);
			    }*/
			    $pdf->agregar($DTE->getDatos(), $DTE->getTED());

 				/*$archivo_envio = 'dte_'.$Caratula['RutEmisor'].'_'.$DTE->getID().'_Envio';
			    $nombre_archivo_envio = $archivo_envio.".pdf";
			    //$tipo_generacion = is_null($cedible) ? 'FI' : 'F';
			    $tipo_generacion_envio = 'F';
			    $pdf->Output($path_pdf.$nombre_archivo_envio, $tipo_generacion_envio);
				*/

			    if($factura->tipo_caf == 52){
			    	$pdf->agregar($DTE->getDatos(), $DTE->getTED());
			    }
			    if($factura->tipo_caf == 33 || $factura->tipo_caf == 34 || $factura->tipo_caf == 52){
				    $pdf->setCedible(true);
				    $pdf->agregar($DTE->getDatos(), $DTE->getTED());			    	
			    }


			    //$pdf->Output('facturacion_electronica/pdf/'.$factura->path_dte.'dte_'.$Caratula['RutEmisor'].'_'.$DTE->getID().'.pdf', 'FI');
			    $archivo = 'dte_'.$Caratula['RutEmisor'].'_'.$DTE->getID();
			    $nombre_archivo = $archivo.".pdf";
			    //$tipo_generacion = is_null($cedible) ? 'FI' : 'F';
			    $tipo_generacion = 'FI';
			    $pdf->Output($path_pdf.$nombre_archivo, $tipo_generacion);
			    $nombre_campo = is_null($cedible) ? 'pdf' : 'pdf_cedible';

			    $this->db->where('idfactura', $idfactura);
				$this->db->update('folios_caf',array($nombre_campo => $nombre_archivo)); 		    

			}		

		}else{

			$filename = $nombre_pdf; /* Note: Always use .pdf at the end. */

			header('Content-type: application/pdf');
			header('Content-Disposition: inline; filename="' . $filename . '"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize($file));
			header('Accept-Ranges: bytes');

			@readfile($file);


		}
	}
	 


	public function exportFePDF_Sincedible($idfactura,$tipo_consulta){

	 	/*print_r($idfactura);

	 	print_r($tipo_consulta);

	 	print_r($cedible);

	 	exit;*/

	 	include $this->ruta_libredte();	 	

	 	if($tipo_consulta == 'id'){
	 		$factura = $this->datos_dte($idfactura);
	 		//print_r($factura);
	 		//exit;

	 		
	 	}else if($tipo_consulta == 'trackid'){
	 		$factura = $this->datos_dte_by_trackid($idfactura);
	 	}
	 	$nombre_pdf = is_null($cedible) ? $factura->pdf : $factura->pdf_cedible;
	 	$nombre_pdf = str_replace('.pdf','_Envio.pdf',$nombre_pdf);

	 	//file_exists 

	 	$crea_archivo = true;
	 	if($nombre_pdf != ''){
			$base_path = __DIR__;
			$base_path = str_replace("\\", "/", $base_path);
			$file = $base_path . "/../../facturacion_electronica/pdf/".$factura->path_dte.$nombre_pdf;		 		
	 		if(file_exists($file)){
	 			$crea_archivo = false;
	 		}
	 	}
	 	$crea_archivo = true;
	 	$empresa = $this->get_empresa();
	 		//print_r($empresa); exit;

	 	if($crea_archivo){
			// sin límite de tiempo para generar documentos
			set_time_limit(0);
		 	// archivo XML de EnvioDTE que se generará
		 	$archivo = "./facturacion_electronica/dte/".$factura->path_dte.$factura->archivo_dte;
		 	if(file_exists($archivo)){
		 		$content_xml = file_get_contents($archivo);
		 	}else{
		 		$content_xml = $factura->dte;
		 	}

		 	// Cargar EnvioDTE y extraer arreglo con datos de carátula y DTEs
		 	$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
		 	$EnvioDte->loadXML($content_xml);
			$Caratula = $EnvioDte->getCaratula();
			$Documentos = $EnvioDte->getDocumentos();	 	

			if(!file_exists('./facturacion_electronica/pdf/'.$factura->path_dte)){
				mkdir('./facturacion_electronica/pdf/'.$factura->path_dte,0777,true);
			}		

			$base_path = __DIR__;
			$base_path = str_replace("\\", "/", $base_path);
			$path_pdf = $base_path . "/../../facturacion_electronica/pdf/".$factura->path_dte;				

			foreach ($Documentos as $DTE) {
			    if (!$DTE->getDatos())
			        die('No se pudieron obtener los datos del DTE');
			    $pdf = new \sasco\LibreDTE\Sii\PDF\Dte(false); // =false hoja carta, =true papel contínuo (false por defecto si no se pasa)
			    $pdf->setFooterText();
			    $pdf->setLogo('./facturacion_electronica/images/logo_empresa.png'); // debe ser PNG!

			    if($factura->giro != ""){
			    	$pdf->setGiroCliente($factura->giro);
			    }			    

			    $pdf->setCondPago($factura->cond_pago); 
			    $pdf->setVendedor($factura->vendedor);
			    //	echo $empresa->giro; exit;
			    $pdf->setGiroEmisor($empresa->giro);
			    $pdf->setDireccion($empresa->dir_origen);
			    $pdf->setComuna($empresa->comuna_origen);
			    $pdf->setFono($empresa->texto_fono);
			    $pdf->setSucursales($empresa->texto_sucursales);

				$pdf->setNeto($factura->neto);
				$pdf->setIva($factura->iva);		
				$pdf->setTotal($factura->totalfactura);



				$datos_transporte = $this->datos_dte_transporte($idfactura);
				
				if(count($datos_transporte) > 0){
					$transporte['RUTTrans'] = $datos_transporte->rut;
					$transporte['Chofer']['NombreChofer'] = $datos_transporte->nombre;
					$transporte['Patente'] = $datos_transporte->pat_camion;
					$transporte['Patente_Carro'] = $datos_transporte->pat_carro;
					$transporte['Destino'] = $datos_transporte->destino;

				}else{
					$transporte['RUTTrans'] = null;
					$transporte['Chofer']['NombreChofer'] = null;
					$transporte['Patente'] = null;
					$transporte['Patente_Carro'] = null;
					$transporte['Destino'] = null;

				}

				
				$pdf->setTransporte($transporte);




				//stdClass Object ( [rut] => 02675738K [nombre] => MAJUL SAN MARTIN MICHEL [pat_camion] => FY9540 [observacion] => Prueba )

						/*** agregar detalle para facturas de guias **/
				//echo $factura->tipo_caf ; exit;
				$texto_guias = "";
				if($factura->tipo_caf == 33){
						$this->db->select('num_guia ',false)
								  ->from('detalle_factura_glosa d')
								  ->where('d.id_factura',$idfactura)
								  ->where('d.id_guia != 0');
						$query = $this->db->get();
						$datos = $query->result();		
						if(count($datos) > 0){
							$texto_guias .= "SEGUN GUIAS NROS: ";
							foreach ($datos as $guia) {
								$texto_guias .= $guia->num_guia." ";
							}

						}
				}

				$pdf->setTextoGuia($texto_guias);	

			    //$pdf->setTransportista("Prueba");
			    
			    

			    $pdf->setResolucion(['FchResol'=>$Caratula['FchResol'], 'NroResol'=>$Caratula['NroResol']]);
			    /*if(!is_null($cedible)){
			    	$pdf->setCedible(true);
			    }*/
			    $pdf->agregar($DTE->getDatos(), $DTE->getTED());
			   

			    //$pdf->Output('facturacion_electronica/pdf/'.$factura->path_dte.'dte_'.$Caratula['RutEmisor'].'_'.$DTE->getID().'.pdf', 'FI');
			    $archivo = 'dte_'.$Caratula['RutEmisor'].'_'.$DTE->getID()."_Envio";
			    $nombre_archivo = $archivo.".pdf";
			    //$tipo_generacion = is_null($cedible) ? 'FI' : 'F';
			    $tipo_generacion = 'F';
			    $pdf->Output($path_pdf.$nombre_archivo, $tipo_generacion);
			}		

		}
	}



	public function carga_contribuyentes($path_base,$archivo){

		$this->db->trans_start();
		$this->db->query('truncate contribuyentes_autorizados'); 

		$base_path = __DIR__;
		$base_path = str_replace("\\", "/", $base_path);
		
		$file = $base_path . "/../../facturacion_electronica/base_contribuyentes/".$path_base.$archivo;				



		$this->db->query('LOAD DATA LOW_PRIORITY LOCAL INFILE "' . $file . '" REPLACE INTO TABLE contribuyentes_autorizados FIELDS TERMINATED BY ";" LINES TERMINATED BY "\n" IGNORE 1 LINES (rut,razon_social,nro_resolucion,fec_resolucion,mail,url);'); 

		$tabla_contribuyentes = $this->busca_parametro_fe('tabla_contribuyentes');
		$tabla_inserta = $tabla_contribuyentes == 'contribuyentes_autorizados_1' ? 'contribuyentes_autorizados_2' : 'contribuyentes_autorizados_1';

		$this->db->query("insert into " . $tabla_inserta . " (rut,dv,razon_social,nro_resolucion,fec_resolucion,mail,url)
						select SUBSTRING_INDEX(rut, '-', 1) as rut, SUBSTRING_INDEX(rut, '-', -1) as dv, razon_social, nro_resolucion, concat(SUBSTRING(fec_resolucion,7,4),'-',SUBSTRING(fec_resolucion,4,2),'-',SUBSTRING(fec_resolucion,1,2)) as fec_resolucion, mail, url  from contribuyentes_autorizados");

		$array_insert = array(
						'nombre_archivo' => $archivo,
						'ruta' => $path_base,
						);

		$this->db->insert('log_cargas_bases_contribuyentes',$array_insert); 


		$this->set_parametro_fe('tabla_contribuyentes',$tabla_inserta);

		$this->db->query('truncate '. $tabla_contribuyentes);

		$this->db->trans_complete(); 		

	 }	 


	 public function registro_email($data){

		$this->db->select('id')
		  ->from('email_fe');
		$query = $this->db->get();
		$email = $query->row();	 		

        	if(count($email) > 0){ //actualizar
        		$this->db->where('id',1);
        		$this->db->update('email_fe',$data);
        	}else{ //insertar
        		$data['created_at'] = date("Y-m-d H:i:s");
				$this->db->insert('email_fe',$data);
        	}	 	
        return true;
	 }

	public function get_email(){
		$this->db->select('email_contacto, pass_contacto, tserver_contacto, port_contacto, host_contacto, email_intercambio, pass_intercambio, tserver_intercambio, port_intercambio, host_intercambio ')
		  ->from('email_fe')
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	 }



	public function envio_mail_dte($idfactura){


			$factura = $this->datos_dte($idfactura);
			$track_id = $factura->trackid;
			$path = $factura->path_dte;

			$nombre_dte = $factura->archivo_dte_cliente != '' ? $factura->archivo_dte_cliente : $factura->archivo_dte;
			$nombre_pdf = $factura->pdf;
			$nombre_pdf = str_replace('.pdf','_Envio.pdf',$nombre_pdf);
			//$nombre_dte = $factura->archivo_dte;

			$empresa = $this->get_empresa();
			$datos_empresa_factura = $this->get_empresa_factura($idfactura);

			$messageBody  = 'Envío de DTE<br><br>';
	        $messageBody .= '<b>Datos Emisor:</b><br>';
	        $messageBody .= $empresa->razon_social.'<br>';
	        $messageBody .= 'RUT:'.$empresa->rut.'-'.$empresa->dv .'<br><br>';

	        $messageBody .= '<b>Datos Receptor:</b><br>';
	        $messageBody .= $datos_empresa_factura->nombre_cliente.'<br>';
	        $messageBody .= 'RUT:'.substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1)."-".substr($datos_empresa_factura->rut_cliente,-1) .'<br><br>';			        

	        //$messageBody .= '<a href="'. base_url() .'facturas/exportFePDF_mail/'.$track_id.'" >Ver Factura</a><br><br>';

	        $messageBody .= 'Este correo adjunta Documentos Tributarios Electrónicos (DTE) para el receptor electrónico indicado.<br><br>';
	        $messageBody .= 'Facturación Electrónica Arnou SPA, Soluciones Digitales a tu alcance. Visítanos: <a href="https://www.arnou.cl">www.arnou.cl</a>';


	        $email_data = $this->facturaelectronica->get_email();
		    //if(count($email_data) > 0 && !is_null($datos_empresa_factura->e_mail)){ //MAIL SE ENVÍA SÓLO EN CASO QUE TENGAMOS REGISTRADOS EMAIL DE ORIGEN Y DESTINOi
			//$datos_empresa_factura->e_mail = 'rodrigog.84@gmail.com';
	        if(!is_null($datos_empresa_factura->e_mail)){ //MAIL SE ENVÍA SÓLO EN CASO QUE TENGAMOS REGISTRADOS EMAIL DE ORIGEN Y DESTINO
				$array_email = array($datos_empresa_factura->e_mail);
				if($datos_empresa_factura->idcliente == 2938){

					array_push($array_email,'jorge.vera@colun.cl');
					//array_push($array_email,'rodrigo.gonzalez@arnou.cl');
				}
				$subject = 'Envio de DTE ' .$track_id . '_'.$empresa->rut.'-'.$empresa->dv."_".substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1)."-".substr($datos_empresa_factura->rut_cliente,-1);

				$ruta =  $factura->archivo_dte_cliente != '' ? 'dte_cliente' : 'dte';

				$attachments = array('./facturacion_electronica/' . $ruta .'/'.$path.$nombre_dte,'./facturacion_electronica/pdf/'.$path.$nombre_pdf);
				//$this->facturaelectronica->envia_mail('enviodte@arnou.cl',$array_email,$subject,$messageBody,'html','Arnou Envio DTE',$attachments);
				//$array_email = array('rodrigog.84@gmail.com');
				$this->facturaelectronica->envia_mail_sb('enviodte@arnou.cl',$array_email,$subject,$messageBody,'html','Arnou Envio DTE',$attachments);



			    //$this->email->attach('./facturacion_electronica/dte/'.$path.$nombre_dte);
				
 			   // $this->email->attach('./facturacion_electronica/' . $ruta .'/'.$path.$nombre_dte);	
			    return true;

			}else{

				return false;
			}

	}

	public function get_factura($id_factura){

		$this->db->select('fc.tipo_documento, fc.num_factura, fc.fecha_factura, fc.sub_total, fc.descuento, fc.neto, fc.iva, fc.totalfactura, fc.forma')
		  ->from('factura_clientes fc')
		  ->where('fc.id',$id_factura)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	 }	 
	 

	public function crea_archivo_dte($xml,$idfactura,$tipo_caf,$tipo_dte){

				$datos_factura = $this->get_factura($idfactura);
				$datos_empresa_factura = $this->get_empresa_factura($idfactura);
				$rutCliente = substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1)."-".substr($datos_empresa_factura->rut_cliente,-1);

			    $xml_dte = $tipo_dte == 'sii' ? $xml : str_replace("60803000-K",$rutCliente,$xml);

				$file_name = $tipo_dte == 'sii' ? "SII_" : "CLI_";
				$nombre_dte = $datos_factura->num_factura."_". $tipo_caf ."_".$idfactura."_".$file_name.date("His").".xml"; // nombre archivo
				$ruta = $tipo_dte == 'sii' ? 'dte' : 'dte_cliente';
				$path = date('Ym').'/'; // ruta guardado
				if(!file_exists('./facturacion_electronica/' . $ruta . '/'.$path)){
					mkdir('./facturacion_electronica/' . $ruta . '/'.$path,0777,true);
				}				
				$f_archivo = fopen('./facturacion_electronica/' . $ruta .'/'.$path.$nombre_dte,'w');
				fwrite($f_archivo,$xml_dte);
				fclose($f_archivo);

				return array('xml_dte' => $xml_dte,
							 'nombre_dte' => $nombre_dte,
							 'path' => $path);

	 }	 	

	public function crea_dte($idfactura){

		$data_factura = $this->get_factura($idfactura);
		$tipodocumento = $data_factura->tipo_documento;
		$numfactura = $data_factura->num_factura;
		$fecemision = $data_factura->fecha_factura;
		$prueba = "";

		if($tipodocumento == 101){
			$tipo_caf = 33;
		}else if($tipodocumento == 103){
			$tipo_caf = 34;
		}else if($tipodocumento == 105){
			$tipo_caf = 52;
		}else if($tipodocumento == 120){
			$tipo_caf = 39;
		}			


		header('Content-type: text/plain; charset=ISO-8859-1');
		$this->load->model('facturaelectronica');
		$config = $this->genera_config();
		include $this->ruta_libredte();


		$empresa = $this->get_empresa();
		$datos_empresa_factura = $this->get_empresa_factura($idfactura);

		$detalle_factura = $this->get_detalle_factura($idfactura);

		//print_r($detalle_factura);
		//exit;
		$lista_detalle = array();
		$i = 0;
		foreach ($detalle_factura as $detalle) {
			//$lista_detalle[$i]['NmbItem'] = $detalle->nombre;
			$lista_detalle[$i]['NmbItem'] = $tipo_caf == 39 ? $detalle->nombre : $detalle->nombre." VENC ".substr($detalle->fecha_vencimiento,8,2)."/".substr($detalle->fecha_vencimiento,5,2)."/".substr($detalle->fecha_vencimiento,0,4);
			$lista_detalle[$i]['QtyItem'] = $detalle->cantidad;
			$lista_detalle[$i]['CdgItem'] = $detalle->codigo;
            $lista_detalle[$i]['UnmdItem'] = substr($detalle->lote,0,4);
			$lista_detalle[$i]['PrcItem'] = $tipo_caf == 39 ? number_format(($detalle->totalproducto/$detalle->cantidad),3,".","") : $detalle->precio;
			//$lista_detalle[$i]['PrcItem'] = round((($detalle->precio*$detalle->cantidad)/1.19)/$detalle->cantidad,0);
			//$total = $detalle->precio*$detalle->cantidad;
			//$neto = round($total/1.19,2);

			//$lista_detalle[$i]['PrcItem'] = round($neto/$detalle->cantidad,2);
			//$lista_detalle[$i]['PrcItem'] = $tipo_caf == 33 ? floor($detalle->precio/1.19) : floor($detalle->precio);

			/*$lista_detalle[$i]['PrcItem'] = $tipo_caf == 33 ? floor(($detalle->totalproducto - $detalle->iva)/$detalle->cantidad) : floor($detalle->precio);
			if($tipo_caf == 33){
				$lista_detalle[$i]['MontoItem'] = ($detalle->totalproducto - $detalle->iva);
			}				

			if($detalle->descuento != 0){
				$porc_descto = round(($detalle->descuento/($detalle->cantidad*$lista_detalle[$i]['PrcItem'])*100),0);
				$lista_detalle[$i]['DescuentoPct'] = $porc_descto;		
				//$lista_detalle[$i]['PrcItem'] =- $lista_detalle[$i]['PrcItem']*$porc_descto;

			}*/

			$i++;
		}

		//var_dump($lista_detalle); exit;

		if(count($lista_detalle) == 0){

				$detalle_factura = $this->facturaelectronica->get_detalle_factura_glosa($idfactura);
                  $i = 0;


                  if($data_factura->forma == 3){
	                  foreach ($detalle_factura as $detalle) {
	                        if($detalle->total > 0){
	                              $lista_detalle[$i]['NmbItem'] = $detalle->nombre == '' ? $detalle->glosa : $detalle->cantidad . " " . $detalle->nombre;
	                              $lista_detalle[$i]['QtyItem'] = $detalle->nombre == '' ? 1 : number_format($detalle->kilos, 3, '.', '');
	                             // $lista_detalle[$i]['CdgItem'] = $detalle->codigo;                        
	                              $lista_detalle[$i]['PrcItem'] = $tipo_caf == 33 || $tipo_caf == 46 ? number_format($detalle->neto/$detalle->kilos, 3, '.', '') : number_format($detalle->total/$detalle->kilos, 3, '.', '');
	                              $lista_detalle[$i]['MontoItem']  = $tipo_caf == 33 || $tipo_caf == 46 ? $detalle->neto : $detalle->total;                 
	                              $i++;
	                        }
	                        
	                  }



                  }else{

						foreach ($detalle_factura as $detalle) {
							$lista_detalle[$i]['NmbItem'] = $detalle->glosa;
							$lista_detalle[$i]['QtyItem'] = 1;
							
							$lista_detalle[$i]['PrcItem'] = $tipo_caf == 33 || $tipo_caf == 46 || $tipo_caf == 52 ? $detalle->neto : $detalle->total;				
							$i++;
						}

                  }


		}

		//print_r($lista_detalle);
		//exit;

		if($tipo_caf == 39){



 				$factura = [
					    'Encabezado' => [
					        'IdDoc' => [
					            'TipoDTE' => $tipo_caf,
					            'Folio' => $numfactura,
					            'FchEmis' => $fecemision
					        ],
					        'Emisor' => [
					            'RUTEmisor' => $empresa->rut.'-'.$empresa->dv,
					            'RznSoc' => substr($empresa->razon_social,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
					            'GiroEmis' => substr($empresa->giro,0,80), //LARGO DE GIRO DEL EMISOR NO PUEDE SER SUPERIOR A 80 CARACTERES
					            'Acteco' => $empresa->cod_actividad,
					            'DirOrigen' => substr($empresa->dir_origen,0,70), //LARGO DE DIRECCION DE ORIGEN NO PUEDE SER SUPERIOR A 70 CARACTERES
					            'CmnaOrigen' => substr($empresa->comuna_origen,0,20), //LARGO DE COMUNA DE ORIGEN NO PUEDE SER SUPERIOR A 20 CARACTERES
					        ],
					        'Receptor' => [
					            'RUTRecep' => substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1)."-".substr($datos_empresa_factura->rut_cliente,-1),
					            'RznSocRecep' => substr($datos_empresa_factura->nombre_cliente,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
					            'GiroRecep' => "pruebaW",//substr($datos_empresa_factura->giro,0,40),  //LARGO DEL GIRO NO PUEDE SER SUPERIOR A 40 CARACTERES
					            'DirRecep' => substr($datos_empresa_factura->direccion,0,70), //LARGO DE DIRECCION NO PUEDE SER SUPERIOR A 70 CARACTERES
					            'CmnaRecep' => substr($datos_empresa_factura->nombre_comuna,0,20), //LARGO DE COMUNA NO PUEDE SER SUPERIOR A 20 CARACTERES
					        ],
					        'Totales' => [
                                                // estos valores serán calculados automáticamente
                                                'MntNeto' => $data_factura->neto,
                                                'IVA' => $data_factura->iva
                                                //'MntTotal' => 12000
                                            ],  

					    	
					    ],		   
						'Detalle' => $lista_detalle			
					];


		}else{

			    $factura = [
					    'Encabezado' => [
					        'IdDoc' => [
					            'TipoDTE' => $tipo_caf,
					            'Folio' => $numfactura,
					            'FchEmis' => $fecemision
					        ],
					        'Emisor' => [
					            'RUTEmisor' => $empresa->rut.'-'.$empresa->dv,
					            'RznSoc' => substr($empresa->razon_social,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
					            'GiroEmis' => substr($empresa->giro,0,80), //LARGO DE GIRO DEL EMISOR NO PUEDE SER SUPERIOR A 80 CARACTERES
					            'Acteco' => $empresa->cod_actividad,
					            'DirOrigen' => substr($empresa->dir_origen,0,70), //LARGO DE DIRECCION DE ORIGEN NO PUEDE SER SUPERIOR A 70 CARACTERES
					            'CmnaOrigen' => substr($empresa->comuna_origen,0,20), //LARGO DE COMUNA DE ORIGEN NO PUEDE SER SUPERIOR A 20 CARACTERES
					        ],
					        'Receptor' => [
					            'RUTRecep' => substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1)."-".substr($datos_empresa_factura->rut_cliente,-1),
					            'RznSocRecep' => substr($datos_empresa_factura->nombre_cliente,0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
					            'GiroRecep' => "pruebaW",//substr($datos_empresa_factura->giro,0,40),  //LARGO DEL GIRO NO PUEDE SER SUPERIOR A 40 CARACTERES
					            'DirRecep' => substr($datos_empresa_factura->direccion,0,70), //LARGO DE DIRECCION NO PUEDE SER SUPERIOR A 70 CARACTERES
					            'CmnaRecep' => substr($datos_empresa_factura->nombre_comuna,0,20), //LARGO DE COMUNA NO PUEDE SER SUPERIOR A 20 CARACTERES
					        ],

					    	
					    ],		   
						'Detalle' => $lista_detalle			
					];


		}


	

		//print_r($factura);
		//exit;

		// datos		

		//FchResol y NroResol deben cambiar con los datos reales de producción
		$caratula = [
		    //'RutEnvia' => '11222333-4', // se obtiene de la firma
		    'RutReceptor' => '60803000-K',
		    'FchResol' => $empresa->fec_resolucion,
		    'NroResol' => $empresa->nro_resolucion
		];

		
		$Firma = new sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital		
		
       
		$caf = $this->facturaelectronica->get_content_caf_folio($numfactura,$tipo_caf);
		$Folios = new sasco\LibreDTE\Sii\Folios($caf->caf_content);


		$DTE = new \sasco\LibreDTE\Sii\Dte($factura);



		$DTE->timbrar($Folios);

		
		$DTE->firmar($Firma);

	
				

		// generar sobre con el envío del DTE y enviar al SII
		$EnvioDTE = new \sasco\LibreDTE\Sii\EnvioDte();

		$EnvioDTE->agregar($DTE);
		$EnvioDTE->setFirma($Firma);
		$EnvioDTE->setCaratula($caratula);
		$EnvioDTE->generar();		

		//var_dump($EnvioDTE->generar()); exit;

		if ($EnvioDTE->schemaValidate()) { // REVISAR PORQUÉ SE CAE CON ESTA VALIDACION
			
			$track_id = 0;
		    $xml_dte = $EnvioDTE->generar();

		    $tipo_envio = $this->busca_parametro_fe('envio_sii'); //ver si está configurado para envío manual o automático

			$nombre_dte = $numfactura."_". $tipo_caf ."_".$idfactura."_".date("His").".xml"; // nombre archivo
			$path = date('Ym').'/'; // ruta guardado
			if(!file_exists('./facturacion_electronica/dte/'.$path)){
				mkdir('./facturacion_electronica/dte/'.$path,0777,true);
			}				
			$f_archivo = fopen('./facturacion_electronica/dte/'.$path.$nombre_dte,'w');
			fwrite($f_archivo,$xml_dte);
			fclose($f_archivo);

		    if($tipo_envio == 'automatico'){
			    $track_id = $EnvioDTE->enviar();
		    }


		    $this->db->where('f.folio', $numfactura);
		    $this->db->where('c.tipo_caf', $tipo_caf);
			$this->db->update('folios_caf f inner join caf c on f.idcaf = c.id',array('dte' => $xml_dte,
																					  'estado' => 'O',
																					  'idfactura' => $idfactura,
																					  'path_dte' => $path,
																					  'archivo_dte' => $nombre_dte,
																					  'trackid' => $track_id
																					  )); 

			if($track_id != 0 && $datos_empresa_factura->e_mail != ''){ //existe track id, se envía correo
				$this->envio_mail_dte($idfactura);
			}			

		}

		return $this->datos_dte($idfactura);

	}




	public function get_contribuyentes(){

		
		$this->db->trans_start();
		header('Content-type: text/plain; charset=ISO-8859-1');

		$config = $this->genera_config();
		include $this->ruta_libredte();

		// solicitar datos
		$datos = \sasco\LibreDTE\Sii::getContribuyentes(
		    new \sasco\LibreDTE\FirmaElectronica($config['firma']),
		    \sasco\LibreDTE\Sii::PRODUCCION
		);


		$tabla_contribuyentes = $this->busca_parametro_fe('tabla_contribuyentes');
		$tabla_inserta = $tabla_contribuyentes == 'contribuyentes_autorizados_1' ? 'contribuyentes_autorizados_2' : 'contribuyentes_autorizados_1';


		foreach ($datos as $dato) {

			$array_rut = explode("-",$dato[0]);
			$array_insert = array(
								'rut' => $array_rut[0],
								'dv' => $array_rut[1],
								'razon_social' => $dato[1],
								'nro_resolucion' => $dato[2],
								'fec_resolucion' => formato_fecha($dato[3],'d-m-Y','Y-m-d'),
								'mail' => $dato[4],
								'url' => $dato[5]
							);

			$this->db->insert($tabla_inserta,$array_insert); 


		}


		$array_insert = array(
						'nombre_archivo' => null,
						'ruta' => null,
						);

		$this->db->insert('log_cargas_bases_contribuyentes',$array_insert); 


		$this->set_parametro_fe('tabla_contribuyentes',$tabla_inserta);

		$this->db->query('truncate '. $tabla_contribuyentes);

		$this->db->trans_complete(); 		

	}	


	public function ruta_turbosmtp(){
		$base_path = __DIR__;
		$base_path = str_replace("\\", "/", $base_path);
		$path = $base_path . "/../libraries/TurboApiClient.php";		
		return $path;
	}



    public function ruta_sendinblue()
    {
        $base_path = __DIR__;
        $base_path = str_replace("\\", "/", $base_path);
        $path = $base_path . "/../libraries/sendinblue.php";
        return $path;
    }    


public function envia_mail_sb($from, $toList, $subject, $content, $type, $alias = "Arnou Envio DTE",$attachments = null)
    {

        if (ENVIO_MAIL) {


                include_once $this->ruta_sendinblue();

                // Configure API key authorization: api-key
                SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key','xkeysib-'.API_KEY_MAIL);

                $api_instance = new SendinBlue\Client\Api\AccountApi();

               
                $smtp_instance = new SendinBlue\Client\Api\SMTPApi();


                if (is_array($toList)) {
                    //array_push($toList,'rodrigog.84@gmail.com');
                    $toList = array_unique($toList);
                    foreach ($toList as $destiny) {

 
				        //var_dump($array_attachments); exit;
				        //var_dump(json_encode($array_attachments)); exit;

				        $array_attachments = array();
                        $sendSmtpEmail = new SendinBlue\Client\Model\SendSmtpEmail([
                             'subject' => $subject,
                             'sender' => ['name' => $alias, 'email' => $from],
                             'replyTo' => ['name' => $alias, 'email' => $from],
                             'to' => [['email' => $destiny]],
                             'htmlContent' => $content,

                        ]);

                    	$array_attachments = array();
				        if(!is_null($attachments)){
				        	foreach ($attachments as $attachment) {
				        		$array_archivo = explode('/',$attachment);
				        		$array_fila = array('content' => chunk_split(base64_encode(file_get_contents($attachment))),'name' => $array_archivo[count($array_archivo)-1]);
				        		array_push($array_attachments,$array_fila);
				        	}
				        		
				        }     

				        if(count($array_attachments) > 0){
				        	$sendSmtpEmail['attachment'] = $array_attachments;	
				        }
                        

                        try {
                            $result = $smtp_instance->sendTransacEmail($sendSmtpEmail);


                            $data_envio = array(
                                'email' => $destiny,
                                'messageid' => $result['messageId']
                            );

                            $this->db->insert('log_envio_mail', $data_envio);

                        } catch (Exception $e) {
                            echo $e->getMessage(),PHP_EOL;
                        }

                    }
                } else {

                        $sendSmtpEmail = new SendinBlue\Client\Model\SendSmtpEmail([
                             'subject' => $subject,
                             'sender' => ['name' => $alias, 'email' => $from],
                             'replyTo' => ['name' => $alias, 'email' => $from],
                             'to' => [['email' => $toList]],
                             'htmlContent' => $content
                        ]);




                    	$array_attachments = array();
				        if(!is_null($attachments)){
				        	foreach ($attachments as $attachment) {
				        		$array_archivo = explode('/',$attachment);
				        		$array_fila = array('content' => chunk_split(base64_encode(file_get_contents($attachment))),'name' => $array_archivo[count($array_archivo)-1]);
				        		array_push($array_attachments,$array_fila);
				        	}
				        		
				        }     

				        if(count($array_attachments) > 0){
				        	$sendSmtpEmail['attachment'] = $array_attachments;	
				        }


                    try {
                        $result = $smtp_instance->sendTransacEmail($sendSmtpEmail);

                        $data_envio = array(
                            'email' => $destiny,
                            'messageid' => $result['messageId']
                        );

                        $this->db->insert('log_envio_mail', $data_envio);




                    } catch (Exception $e) {
                        echo $e->getMessage(),PHP_EOL;
                    }


                }



        }


    }


	public function envia_mail($from,$toList,$subject,$content,$type,$alias = "Arnou Envio DTE",$attachments = null){
    	if(ENVIO_MAIL){
    		include_once $this->ruta_turbosmtp();
    		//$toList = array('rodrigog.84@gmail.com','renegonzalezinfo@gmail.com');
    		if(is_array($toList)){
    			//array_push($toList,'rodrigog.84@gmail.com');
    			$toList = array_unique($toList);
		        foreach ($toList as $destiny) {
			        $email = new Email();
			        $email->setFrom($alias. " <" . $from . ">");
			        $email->setToList($destiny);
			        //$email->setCcList("dd@domain.com,ee@domain.com");
			        //$email->setBccList("ffi@domain.com,rr@domain.com");   
			        $email->setSubject($subject);
			        //$email->setContent("content");

			        if($type == 'html'){
			        	$email->setHtmlContent($content);	
			        }else{
			        	$email->setContent($content);	
			        }
			        
			        $email->addCustomHeader('X-FirstHeader', "value");
			        $email->addCustomHeader('X-SecondHeader', "value");
			        $email->addCustomHeader('X-Header-da-rimuovere', 'value');
			        $email->removeCustomHeader('X-Header-da-rimuovere');

			        if(!is_null($attachments)){
			        	foreach ($attachments as $attachment) {
			        		$email->addAttachment($attachment);
			        	}
			        		
			        }
			        

			        $turboApiClient = new TurboApiClient(TURBOSMTP_USER,TURBOSMTP_PASS);
			       //var_dump($turboApiClient);
			       // $response = $turboApiClient->sendEmail($email);
			      // var_dump($response);
					try {
					    $response = $turboApiClient->sendEmail($email);
					} catch (Exception $e) {
					    echo "";
					}		        
				}    			
    		}else{


		        $email = new Email();
		        $email->setFrom("Tu Gasto Común <" . $from . ">");
		        $email->setToList($toList);
		        //$email->setCcList("dd@domain.com,ee@domain.com");
		        //$email->setBccList("ffi@domain.com,rr@domain.com");   
		        $email->setSubject($subject);
		        //$email->setContent("content");

		        if($type == 'html'){
		        	$email->setHtmlContent($content);	
		        }else{
		        	$email->setContent($content);	
		        }
		        
		        $email->addCustomHeader('X-FirstHeader', "value");
		        $email->addCustomHeader('X-SecondHeader', "value");
		        $email->addCustomHeader('X-Header-da-rimuovere', 'value');
		        $email->removeCustomHeader('X-Header-da-rimuovere');

		        $turboApiClient = new TurboApiClient(TURBOSMTP_USER,TURBOSMTP_PASS);
		        //var_dump($turboApiClient);
		        $response = $turboApiClient->sendEmail($email);
		        //var_dump($response);
				try {
				    $response = $turboApiClient->sendEmail($email);
				} catch (Exception $e) {
				    echo "";
				}

    		}

	        

	        
	    }
    }	

}
