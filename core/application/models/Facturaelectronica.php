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
		$path = $base_path . "/../../facturacion_electronica/certificado/certificado.p12";		
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



	 public function contribuyentes_autorizados($start = null,$limit = null){

	 	$tabla_contribuyentes = $this->busca_parametro_fe('tabla_contribuyentes');

	 	$countAll = $this->db->count_all_results($tabla_contribuyentes);

		$data = $this->db->select('rut, dv, concat(rut,"-",dv) as rut_contribuyente, razon_social, nro_resolucion, date_format(fec_resolucion,"%d/%m/%Y") as fec_resolucion, mail, url',false)
		  ->from($tabla_contribuyentes)
		  ->order_by('razon_social');

		$data = is_null($start) || is_null($limit) ? $data : $data->limit($limit,$start);
		$query = $this->db->get();
		return array('total' => $countAll, 'data' => $query->result());

	 }

	public function get_empresa(){
		$this->db->select('rut, dv, razon_social, giro, cod_actividad, dir_origen, comuna_origen, fec_resolucion, nro_resolucion, logo ')
		  ->from('empresa')
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	 }


	public function datos_dte_periodo($mes,$anno){
		$this->db->select('f.folio, f.path_dte, f.archivo_dte, f.dte, f.pdf, f.pdf_cedible, f.trackid, c.tipo_caf, tc.nombre as tipo_doc ')
		  ->from('folios_caf f')
		  ->join('caf c','f.idcaf = c.id')
		  ->join('tipo_caf tc','c.tipo_caf = tc.id')
		  ->join('factura_clientes fc','f.idfactura = fc.id','left')
		  //->where('left(fc.fecha_factura,7)',$anno."-".$mes);
		  ->where('left(f.updated_at,7)',$anno."-".$mes) //AUN TENEMOS FACTURAS QUE NO SE EMITEN POR EL SISTEMA
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


	public function put_log_libros($mes,$anno,$tipo,$archivo){

			$array_insert = array(
						'mes' => $mes,
						'anno' => $anno,
						'tipo_libro' => $tipo,
						'archivo' => $archivo
						);

		$this->db->insert('log_libros',$array_insert); 
		return true;
	}



	public function get_empresa_factura($id_factura){
		$this->db->select('c.nombres as nombre_cliente, c.rut as rut_cliente, c.direccion, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, c.fono, e.nombre as giro, c.e_mail')
		  ->from('factura_clientes acc')
		  ->join('clientes c','acc.id_cliente = c.id','left')
		  ->join('cod_activ_econ e','c.id_giro = e.id','left')
		  ->join('comuna m','c.id_comuna = m.id','left')		  
		  ->join('ciudad s','c.id_ciudad = s.id','left')	
		  ->where('acc.id',$id_factura)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	 }	 


	public function get_detalle_factura($id_factura){
		$this->db->select('p.nombre, f.precio, f.cantidad, f.descuento , f.totalproducto')
		  ->from('detalle_factura_cliente f')
		  ->join('productos p','f.id_producto = p.id')
		  ->where('f.id_factura',$id_factura);
		$query = $this->db->get();
		return $query->result();
	 }

	public function get_detalle_factura_glosa($id_factura){
		$this->db->select('f.glosa, f.neto, f.iva, f.total ')
		  ->from('detalle_factura_glosa f')
		  ->where('f.id_factura',$id_factura);
		$query = $this->db->get();
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

	public function datos_dte($idfactura){
		$this->db->select('f.id, f.folio, f.path_dte, f.archivo_dte, f.dte, f.pdf, f.pdf_cedible, f.trackid, c.tipo_caf, tc.nombre as tipo_doc ')
		  ->from('folios_caf f')
		  ->join('caf c','f.idcaf = c.id')
		  ->join('tipo_caf tc','c.tipo_caf = tc.id')
		  ->where('f.idfactura',$idfactura)
		  ->limit(1);
		$query = $this->db->get();
		return $query->row();
	}	


	public function datos_dte_by_trackid($trackid){
		$this->db->select('f.id, f.folio, f.path_dte, f.archivo_dte, f.dte, f.pdf, f.pdf_cedible, f.trackid, c.tipo_caf, tc.nombre as tipo_doc ')
		  ->from('folios_caf f')
		  ->join('caf c','f.idcaf = c.id')
		  ->join('tipo_caf tc','c.tipo_caf = tc.id')
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


	 public function exportFePDF($idfactura,$tipo_consulta,$cedible = null){

	 	$this->load->model('facturaelectronica');
	 	include $this->facturaelectronica->ruta_libredte();
	 	if($tipo_consulta == 'id'){
	 		$factura = $this->facturaelectronica->datos_dte($idfactura);
	 	}else if($tipo_consulta == 'trackid'){
	 		$factura = $this->facturaelectronica->datos_dte_by_trackid($idfactura);
	 	}
	 	$nombre_pdf = is_null($cedible) ? $factura->pdf : $factura->pdf_cedible;

	 	if($nombre_pdf == ''){
			// sin límite de tiempo para generar documentos
			set_time_limit(0);
		 	// archivo XML de EnvioDTE que se generará
		 	$archivo = "./facturacion_electronica/dte/".$factura->path_dte.$factura->archivo_dte;

		 	// Cargar EnvioDTE y extraer arreglo con datos de carátula y DTEs
		 	$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
		 	$EnvioDte->loadXML(file_get_contents($archivo));
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
			    $pdf->setResolucion(['FchResol'=>$Caratula['FchResol'], 'NroResol'=>$Caratula['NroResol']]);
			    if(!is_null($cedible)){
			    	$pdf->setCedible(true);
			    }
			    $pdf->agregar($DTE->getDatos(), $DTE->getTED());
			    //$pdf->Output('facturacion_electronica/pdf/'.$factura->path_dte.'dte_'.$Caratula['RutEmisor'].'_'.$DTE->getID().'.pdf', 'FI');
			    $archivo = 'dte_'.$Caratula['RutEmisor'].'_'.$DTE->getID();
			    $nombre_archivo = is_null($cedible) ? $archivo.".pdf" : $archivo."_CED.pdf";
			    //$tipo_generacion = is_null($cedible) ? 'FI' : 'F';
			    $tipo_generacion = 'FI';
			    $pdf->Output($path_pdf.$nombre_archivo, $tipo_generacion);
			    $nombre_campo = is_null($cedible) ? 'pdf' : 'pdf_cedible';

			    $this->db->where('idfactura', $idfactura);
				$this->db->update('folios_caf',array($nombre_campo => $nombre_archivo)); 		    

			}

		}else{

			$base_path = __DIR__;
			$base_path = str_replace("\\", "/", $base_path);
			
			$file = $base_path . "/../../facturacion_electronica/pdf/".$factura->path_dte.$nombre_pdf;				
			//echo $file; exit;
			//$file = '.facturacion_electronica/pdf/'.$factura->path_dte.$factura->pdf;
			$filename = $nombre_pdf; /* Note: Always use .pdf at the end. */

			header('Content-type: application/pdf');
			header('Content-Disposition: inline; filename="' . $filename . '"');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . filesize($file));
			header('Accept-Ranges: bytes');

			@readfile($file);


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


		$this->facturaelectronica->set_parametro_fe('tabla_contribuyentes',$tabla_inserta);

		$this->db->query('truncate '. $tabla_contribuyentes);

		$this->db->trans_complete(); 		

	 }	 

}
