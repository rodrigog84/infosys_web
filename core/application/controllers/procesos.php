<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Procesos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('format');
		$this->load->database();
	}


	public function lectura_csv_fe(){

		 	$archivo = "./facturacion_electronica/csv/FACTURAS.CSV";
			$this->load->model('facturaelectronica');
			$codproceso = $this->facturaelectronica->guarda_csv($archivo);
			$this->facturaelectronica->crea_dte_csv($codproceso);


	}


	public function lectura_csv_fe_manual(){

			$archivo = "./facturacion_electronica/csv/procesados/FACT_PROC_2016084114.CSV";
			$this->load->model('facturaelectronica');
			$codproceso = $this->facturaelectronica->guarda_csv($archivo);
			$this->facturaelectronica->crea_dte_csv($codproceso);


	}


	public function genera_libros_pendientes(){

		$this->db->trans_start();
		// respuesta en texto plano
		set_time_limit(0);
		//header('Content-type: text/plain; charset=ISO-8859-1');

		$this->load->model('facturaelectronica');
		$datos_libros = $this->facturaelectronica->log_libros(NULL,NULL,'P');
		if($datos_libros['total'] > 0){

			$libro_genera = isset($datos_libros['data'][0]) ? $datos_libros['data'][0] : false;
			$id_libro = $libro_genera->id;
			$tipo_libro = $libro_genera->tipo_libro;
			$mes = str_pad($libro_genera->mes,2,"0",STR_PAD_LEFT);
			$anno = $libro_genera->anno;			

			if($tipo_libro == 'VENTA'){
				$lista_facturas = $this->facturaelectronica->datos_dte_periodo($mes,$anno);
			}else{ // COMPRAS
				$lista_facturas = $this->facturaelectronica->datos_dte_proveedores_periodo($mes,$anno);
			}


			//NO TIENE MOVIMIENTOS
			if(count($lista_facturas) == 0){

				$result['success'] = true;
				$result['valido'] = false;
				$result['message'] = "No existen movimientos";
				echo json_encode($result);
				exit;
			}



			$config = $this->facturaelectronica->genera_config();
			include $this->facturaelectronica->ruta_libredte();		

			// Objetos de Firma y LibroCompraVenta
			$Firma = new \sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital
			$LibroCompraVenta = new \sasco\LibreDTE\Sii\LibroCompraVenta();

			
			$empresa = $this->facturaelectronica->get_empresa();

			$rut = $Firma->getId(); 
			$rut_consultante = explode("-",$rut);

			// caratula del libro
			$caratula = [
			    'RutEmisorLibro' => $empresa->rut."-".$empresa->dv,
			    'RutEnvia' => $rut_consultante[0]."-".$rut_consultante[1],
			    'PeriodoTributario' => $anno."-".$mes,
			    'FchResol' => $empresa->fec_resolucion,
			    'NroResol' => $empresa->nro_resolucion,
			    'TipoOperacion' => $tipo_libro,
			    'TipoLibro' => 'MENSUAL',
			    'TipoEnvio' => 'TOTAL',
			    //'FolioNotificacion' => 102006,
			];

			// datos del emisor
			$Emisor = [
			    'RUTEmisor' => $empresa->rut.'-'.$empresa->dv,
			    'RznSoc' => $empresa->razon_social,
			    'GiroEmis' => $empresa->giro,
			    'Acteco' => $empresa->cod_actividad,
			    'DirOrigen' => $empresa->dir_origen,
			    'CmnaOrigen' => $empresa->comuna_origen,
			];


			// generar cada DTE y agregar su resumen al detalle del libro

			foreach ($lista_facturas as $factura) {
				if($factura->dte != ''){ # SOLO CONSIDERA CAMPO DTE.  
					$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
					$EnvioDte->loadXML($factura->dte);
					$Documentos = $EnvioDte->getDocumentos();
					$Documento = $Documentos[0];
				    $LibroCompraVenta->agregar($Documento->getResumen(), false); // agregar detalle sin normalizar
			    }
			}
			
			// enviar libro de ventas y mostrar resultado del envío: track id o bien =false si hubo error
			$LibroCompraVenta->setCaratula($caratula);
			$LibroCompraVenta->setFirma($Firma);
			$xml_libro = $LibroCompraVenta->generar(); 

			if(!file_exists('./facturacion_electronica/tmp/')){
				mkdir('./facturacion_electronica/tmp/',0777,true);
			}		
			//genera archivo		
			//$nombre_archivo = "LIBRO_".$tipo_libro."_".date("YmdHis").".xml";
			$nombre_archivo = "LIBRO_".$tipo_libro."_".$anno.$mes.".xml";
			$f_nombre_archivo = fopen('./facturacion_electronica/libros/'.$nombre_archivo,'w');
			fwrite($f_nombre_archivo,$xml_libro);
			fclose($f_nombre_archivo);

			$existe = $this->facturaelectronica->genera_libro($id_libro,$tipo_libro,$nombre_archivo,$xml_libro);

			$result['success'] = true;
			$result['valido'] = true;
			$result['message'] = $tipo_libro == "COMPRA" ? "Libro de Compras Generado Correctamente" : "Libro de Ventas Generado Correctamente";
			$result['nombre_archivo'] = $nombre_archivo;

			echo json_encode($result);


		}
		$this->db->trans_complete();



	}		


	public function libera_folios(){

			$this->db->where('estado', 'T');
			$this->db->update('folios_caf',array(
											'estado' => 'P')); 



	}	


	public function envio_programado_boletas_sii()
    {
        set_time_limit(0);
        $this->load->model('facturaelectronica');
        $facturas = $this->facturaelectronica->get_boleta_no_enviada();
       // print_r($facturas); exit;
        include $this->facturaelectronica->ruta_libredte();
        foreach ($facturas as $factura) {
            $idfactura = $factura->idfactura;
            $factura = $this->facturaelectronica->datos_dte($idfactura);
            //echo "<pre>";
            //print_r($factura);
            //print_r($factura->idempresa);
            //exit;

            $config = $this->facturaelectronica->genera_config();
            
            ///print_r($config);
           // exit;
            $token = \sasco\LibreDTE\EnvioBoleta::getToken($config['firma']);
            //$token = \sasco\LibreDTE\Sii\Autenticacion::getToken($config['firma']);
             if (!$token) {
                foreach (\sasco\LibreDTE\Log::readAll() as $error) {
                    $result['error'] = true;
                }
                $result['message'] = "Error de conexión con SII";
                echo json_encode($result);
                exit;
            }

            //var_dump($token);

            $Firma = new \sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital
            $rut = $Firma->getId();
            $rut_consultante = explode("-", $rut);
            $RutEnvia = $rut_consultante[0] . "-" . $rut_consultante[1];

            //$archivo = "./facturacion_electronica/dte/202104/774_39_6769_SII_162309.xml";
            $archivo = "./facturacion_electronica/dte/" . $factura->path_dte . $factura->archivo_dte;
           // echo "<br>".$archivo."<br>";
            if (file_exists($archivo)) {
                $xml = file_get_contents($archivo);
            } else {
                $xml = $factura->dte;
            }               


            $EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
            $EnvioDte->loadXML($xml);
            $Documentos = $EnvioDte->getDocumentos();

            $DTE = $Documentos[0];
            $RutEmisor = $DTE->getEmisor();

            $result_envio = \sasco\LibreDTE\EnvioBoleta::enviar($RutEnvia, $RutEmisor, $xml, $token);
            //echo htmlentities($xml)."<br>-----------------";

/*
array(6) { ["rut_emisor"]=> string(10) "96516320-4" ["rut_envia"]=> string(10) "10022349-K" ["trackid"]=> int(587975533) ["fecha_recepcion"]=> string(19) "2021-04-14 23:51:30" ["estado"]=> string(3) "REC" ["file"]=> string(48) "dte_ac1b7a00d6b388a04622edd2c53a77d5.xmlSRV_CODE" }        */ 



            // AJUSTAR ESTA PARTE PARA ENVIO DE BOLETAS
            
            // si hubo algún error al enviar al servidor mostrar

			//var_dump($result_envio);
            if ($result_envio === false) {
                foreach (\sasco\LibreDTE\Log::readAll() as $error) {
                    $result['error'] = true;
                }
                $result['message'] = "Error de envío de DTE";
                echo json_encode($result);
                exit;
            }

            // Mostrar resultado del envío
            if ($result_envio['estado'] != 'REC') {
                foreach (\sasco\LibreDTE\Log::readAll() as $error) {
                    $result['error'] = true;
                }
                $result['message'] = "Error de envío de DTE";
                echo json_encode($result);
                exit;
            }


            $track_id = 0;
            $track_id = (int)$result_envio['trackid'];
            $this->db->where('id', $factura->id);
            $this->db->update('folios_caf', array('trackid' => $track_id));


           // echo "<br>".$this->db->last_query()."<br>";

            /*$datos_empresa_factura = $this->facturaelectronica->get_empresa_factura($idfactura);

            if ($track_id != 0 && $datos_empresa_factura->e_mail != '') { //existe track id, se envía correo
                $this->facturaelectronica->envio_mail_dte($idfactura);
            }
                */
            echo "idfactura: " . $factura->id . " -- folio : " . $factura->folio . " -- trackid : " . $track_id . "<br>";
            ob_flush();

            $result['success'] = true;
            $result['message'] = $track_id != 0 ? "DTE enviado correctamente" : "Error en env&iacute;o de DTE";
            $result['trackid'] = $track_id;
            echo json_encode($result);
            //exit;
        }
    }	


	public function envio_programado_sii(){
		set_time_limit(0);
		$this->load->model('facturaelectronica');
		$facturas = $this->facturaelectronica->get_factura_no_enviada();

		

		foreach ($facturas as $factura) {
			$idfactura = $factura->idfactura;
			$factura = $this->facturaelectronica->datos_dte($idfactura);
			$config = $this->facturaelectronica->genera_config();
			include $this->facturaelectronica->ruta_libredte();


			$token = \sasco\LibreDTE\Sii\Autenticacion::getToken($config['firma']);
			if (!$token) {
			    foreach (\sasco\LibreDTE\Log::readAll() as $error){
			    	$result['error'] = true;

			    }
			    $result['message'] = "Error de conexión con SII";		   
			   	echo json_encode($result);
			    exit;
			}

			$Firma = new \sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital
			$rut = $Firma->getId(); 
			$rut_consultante = explode("-",$rut);
			$RutEnvia = $rut_consultante[0]."-".$rut_consultante[1];

			//$xml = $factura->dte;
			$archivo = "./facturacion_electronica/dte/".$factura->path_dte.$factura->archivo_dte;
		 	if(file_exists($archivo)){
		 		$xml = file_get_contents($archivo);
		 	}else{
		 		$xml = $factura->dte;
		 	}


			$EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
			$EnvioDte->loadXML($xml);
			$Documentos = $EnvioDte->getDocumentos();	

			$DTE = $Documentos[0];
			$RutEmisor = $DTE->getEmisor(); 

			// enviar DTE
			$result_envio = \sasco\LibreDTE\Sii::enviar($RutEnvia, $RutEmisor, $xml, $token);

			// si hubo algún error al enviar al servidor mostrar
			if ($result_envio===false) {
			    foreach (\sasco\LibreDTE\Log::readAll() as $error){
			        $result['error'] = true;
			    }
			    $result['message'] = "Error de envío de DTE";		   
			   	echo json_encode($result);
			    exit;
			}

			// Mostrar resultado del envío
			if ($result_envio->STATUS!='0') {
			    foreach (\sasco\LibreDTE\Log::readAll() as $error){
					$result['error'] = true;
			    }
			    $result['message'] = "Error de envío de DTE";		   
			   	echo json_encode($result);
			    exit;
			}


			$track_id = 0;
			$track_id = (int)$result_envio->TRACKID;
		    $this->db->where('id', $factura->id);
			$this->db->update('folios_caf',array('trackid' => $track_id)); 

			$datos_empresa_factura = $this->facturaelectronica->get_empresa_factura($idfactura);
			
			if($track_id != 0 && $datos_empresa_factura->e_mail != ''){ //existe track id, se envía correo
				$this->facturaelectronica->envio_mail_dte($idfactura);
			}

			echo "idfactura: " .$factura->id." -- folio : ".$factura->folio." -- trackid : ". $track_id . "<br>";
			ob_flush(); 

			$result['success'] = true;
			$result['message'] = $track_id != 0 ? "DTE enviado correctamente" : "Error en env&iacute;o de DTE";
			$result['trackid'] = $track_id;
			echo json_encode($result);
			
		}


	}	


	public function envia_email_factura(){
		$this->load->model('facturaelectronica');
		$this->facturaelectronica->envio_mail_dte(4810);

	}

	public function envio_programado_consumo_folios(){
		set_time_limit(0);
		$this->load->model('facturaelectronica');
		$consumo_folios = $this->facturaelectronica->consumo_folios_no_enviada();
		$empresa = $this->facturaelectronica->get_empresa();
		$RutEmisor = $empresa->rut.'-'.$empresa->dv;
		$config = $this->facturaelectronica->genera_config();
		include $this->facturaelectronica->ruta_libredte();

		foreach ($consumo_folios as $consumo_folio) {
			//$idfactura = $factura->idfactura;
			//$factura = $this->facturaelectronica->datos_dte($idfactura);
			

			$token = \sasco\LibreDTE\Sii\Autenticacion::getToken($config['firma']);
			if (!$token) {
				var_dump(\sasco\LibreDTE\Log::readAll());
			    foreach (\sasco\LibreDTE\Log::readAll() as $error){
			    	$result['error'] = true;

			    }
			    $result['message'] = "Error de conexión con SII";		   
			   	echo json_encode($result);
			    exit;
			}

			$Firma = new \sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital
			$rut = $Firma->getId(); 
			$rut_consultante = explode("-",$rut);
			$RutEnvia = $rut_consultante[0]."-".$rut_consultante[1];

			//$xml = $factura->dte;
			$archivo = "./facturacion_electronica/Consumo_Folios/".$consumo_folio->path_consumo_folios.$consumo_folio->archivo_consumo_folios;
		 	if(file_exists($archivo)){
		 		$xml = file_get_contents($archivo);
		 	}else{
		 		$xml = $consumo_folio->xml;
		 	}

			// enviar DTE
			$result_envio = \sasco\LibreDTE\Sii::enviar($RutEnvia, $RutEmisor, $xml, $token);

			// si hubo algún error al enviar al servidor mostrar
			if ($result_envio===false) {
					var_dump(\sasco\LibreDTE\Log::readAll());
			    foreach (\sasco\LibreDTE\Log::readAll() as $error){
			        $result['error'] = true;
			    }
			    $result['message'] = "Error de envío de DTE";		   
			   	echo json_encode($result);
			    exit;
			}

			// Mostrar resultado del envío
			if ($result_envio->STATUS!='0') {
			    foreach (\sasco\LibreDTE\Log::readAll() as $error){
					$result['error'] = true;
			    }
			    $result['message'] = "Error de envío de DTE";		   
			   	echo json_encode($result);
			    exit;
			}


			$track_id = 0;
			$track_id = $result_envio->TRACKID;
		    $this->db->where('id', $consumo_folio->id);
			$this->db->update('consumo_folios',array('trackid' => $track_id)); 

			/*$datos_empresa_factura = $this->facturaelectronica->get_empresa_factura($idfactura);
			
			if($track_id != 0 && $datos_empresa_factura->e_mail != ''){ //existe track id, se envía correo
				$this->facturaelectronica->envio_mail_dte($idfactura);
			}*/

			echo "idconsumofolios: " .$consumo_folio->id." -- consumo folios : ".$consumo_folio->archivo_consumo_folios." -- trackid : ". $track_id . "<br>";
			ob_flush(); 

			$result['success'] = true;
			$result['message'] = $track_id != 0 ? "DTE enviado correctamente" : "Error en env&iacute;o de DTE";
			$result['trackid'] = $track_id;
			echo json_encode($result);
			
		}

	}	

	public function proceso_consumo_folios(){
		set_time_limit(0);
		//https://palena.sii.cl/cgi_dte/UPL/DTEauth?1   -subir
		//https://palena.sii.cl/cgi_dte/UPL/DTEauth?3 --consultar
			//header('Content-type: text/plain; charset=ISO-8859-1');

/*

ilefort@itelecom.cl
dte.cl_sii@einvoicing.signature-cloud.com
dte.cl@einvoicing.signature-cloud.com
*/



			$this->load->model('facturaelectronica');
			include $this->facturaelectronica->ruta_libredte();
			$empresa = $this->facturaelectronica->get_empresa();
			$fec_inicio = $empresa->fec_inicio_boleta;
			$fecha_hoy = date('Y-m-d');
			$dias_evalua = 10;

			while($dias_evalua >= 0){
				$fecha_consumo= strtotime("- $dias_evalua days", strtotime ($fecha_hoy));
				$fecha = date('Y-m-d',$fecha_consumo);
				//echo $fecha."<br>";
				
				if(strtotime($fecha) >= strtotime($fec_inicio)){
					$consumo_folios = $this->facturaelectronica->get_consumo_folios($fecha);
					if(count($consumo_folios) == 0){
						$this->genera_consumo_folios($fecha);	
					}
					
				}

				$dias_evalua--;
			}

	}		


	public function genera_consumo_folios($fecha){
		

		//echo $fecha; exit;
		header('Content-type: text/plain; charset=ISO-8859-1');
		$this->load->model('facturaelectronica');
      	$config = $this->facturaelectronica->genera_config();
      	


		$empresa = $this->facturaelectronica->get_empresa();
		$facturas = $this->facturaelectronica->get_boletas_dia($fecha);
		$Firma = new sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital            
		$ConsumoFolio = new sasco\LibreDTE\Sii\ConsumoFolio();
		$ConsumoFolio->setFirma($Firma);
		//print_r($facturas);  exit;
		$lista_folios = array();
		if(count($facturas) > 0){
			foreach ($facturas as $factura) {
				$idfactura = $factura->idfactura;
				$factura = $this->facturaelectronica->datos_dte($idfactura);
				$archivo = "./facturacion_electronica/dte/".$factura->path_dte.$factura->archivo_dte;
				//echo $archivo; exit;
			 	if(file_exists($archivo)){
			 		$xml = file_get_contents($archivo);
			 	}else{
			 		$xml = $factura->dte;
			 	}
				//echo $xml;


				$rut = $Firma->getId(); 
				$rut_consultante = explode("-",$rut);
				$RutEnvia = $rut_consultante[0]."-".$rut_consultante[1];

				//$xml = $factura->dte;
				


				$EnvioBOLETA = new \sasco\LibreDTE\Sii\EnvioDte();
				$EnvioBOLETA->loadXML($xml);
				// agregar detalle de boletas
				foreach ($EnvioBOLETA->getDocumentos() as $Dte) {
				    $ConsumoFolio->agregar($Dte->getResumen());
				}


				// crear carátula para el envío (se hace después de agregar los detalles ya que
				// así se obtiene automáticamente la fecha inicial y final de los documentos)
				$CaratulaEnvioBOLETA = $EnvioBOLETA->getCaratula();
				$lista_folios[] = $factura->folio;
				
			}


			/**** definir folio min, folio max, cant folios, lista folios ****/
			$folio_min = min($lista_folios);
			$folio_max = max($lista_folios);
			$cant_folios = count($lista_folios);

		}else{

			$ConsumoFolio->setDocumentos([39,61,41]);
			$folio_min = 0;
			$folio_max = 0;
			$cant_folios = 0;

		}

		


		// crear carátula para el envío (se hace después de agregar los detalles ya que
		// así se obtiene automáticamente la fecha inicial y final de los documentos)
		$ConsumoFolio->setCaratula([
		    'RutEmisor' => $empresa->rut.'-'.$empresa->dv,
		    'FchResol' => $empresa->fec_resolucion,
		    'NroResol' =>  $empresa->nro_resolucion,
			'FchInicio' => $fecha,
			'FchFinal' => $fecha,
			'SecEnvio' => 1

		]);
		//echo $ConsumoFolio->generar()."<br>";

		$ConsumoFolio->generar();
		if ($ConsumoFolio->schemaValidate()) {
		    $xml_consumo_folios = $ConsumoFolio->generar();
		    $nombre_archivo =  "Consumo_Folios_" . str_replace("-","",$fecha) . ".xml";
		    $path = date('Ym').'/';
			if(!file_exists('./facturacion_electronica/Consumo_Folios/'.$path)){
				mkdir('./facturacion_electronica/Consumo_Folios/'.$path,0777,true);
			}			    
			$f_archivo = fopen('./facturacion_electronica/Consumo_Folios/'.$path.$nombre_archivo,'w');
			fwrite($f_archivo,$xml_consumo_folios);
			fclose($f_archivo);


			$array_consumo_folios = array (
											'fecha' => $fecha,
											'cant_folios' => $cant_folios,
											'folio_desde' => $folio_min,
											'folio_hasta' => $folio_max,
											'path_consumo_folios' => $path,
											'archivo_consumo_folios' => $nombre_archivo,
											'xml' => $xml_consumo_folios,
											'trackid' => '0',
											'created_at' => date('Y-m-d H:i:s')

										);
			$this->db->insert('consumo_folios',$array_consumo_folios);
			$id_consumo_folios = $this->db->insert_id();



			if(count($lista_folios) > 0){
				$this->db->where_in('f.folio', $lista_folios);
	            $this->db->where('c.tipo_caf', 39);
	            $this->db->update('folios_caf f inner join caf c on f.idcaf = c.id',array('id_consumo_folios' => $id_consumo_folios)); 
			}

  			




		  //  $track_id = $ConsumoFolio->enviar();
		  //  var_dump($track_id);
		}

// si hubo errores mostrar
			///foreach (\sasco\LibreDTE\Log::readAll() as $error)
    			//echo $error,"\n";



	}

	public function get_contribuyentes(){

		set_time_limit(0);
		$this->load->model('facturaelectronica');
		$this->facturaelectronica->get_contribuyentes();
	}		
	

}









