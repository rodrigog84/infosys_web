<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facturas extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('format');
		$this->load->database();
	}

     public function anulaguias(){

        $resp = array();
        $idfactura= $this->input->post('idfactura');
        $idbodega= $this->input->post('idbodega');
        $tipomov=105;

        $fact = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, co.nombre as nombre_docu, v.nombre as nom_vendedor, acc.tipo_documento as id_tip_docu, td.descripcion as tipo_doc   FROM factura_clientes acc
            left join clientes c on (acc.id_cliente = c.id)
            left join vendedores v on (acc.id_vendedor = v.id)
            left join tipo_documento td on (acc.tipo_documento = td.id)
            left join correlativos co on (acc.tipo_documento = co.id)
            WHERE acc.id='.$idfactura.' '            
            );
            $total = 0;

          foreach ($fact->result() as $c)
            {
                $numfactura = $c->num_factura;
            
            }       

        $query = $this->db->query('SELECT acc.*, p.nombre as nombre, p.codigo 
        as codigo, acc.precio as p_venta, acc.cantidad as stock 
        FROM detalle_factura_cliente acc    
        left join productos p on (acc.id_producto = p.id)
        WHERE acc.id_factura = "'.$idfactura.'"');
        
        $anula = array(
            'estado' => 1            
        );

        $this->db->where('id', $idfactura);   
        $this->db->update('factura_clientes', $anula);
        $resp['success'] = true;
        
        if ($query->num_rows()>0){         

        foreach ($query->result() as $v){

         $id = ($v->id_existencia);
         $cantidad = ($v->cantidad);
         $producto = ($v->id_producto);

         $edit = $this->db->query('SELECT acc.*, p.nombre as nom_producto, p.codigo as codigo, acc.fecha_vencimiento as fecha_vencimiento, p.stock as stockp, p.stock_critico as stock_critico FROM existencia_detalle acc
         left join productos p on (acc.id_producto = p.id)
         WHERE acc.id_tipo_movimiento='.$tipomov.' and acc.num_movimiento='.$numfactura.' and acc.id_bodega='.$idbodega.'');
         if ($edit->num_rows()>0){
            foreach ($edit->result() as $e){                 
                $ida=($e->id);
                $borr = $this->db->query('DELETE FROM existencia_detalle WHERE id = "'.$ida.'"');
            }            
         };

         $queryt = $this->db->query('SELECT acc.*, p.nombre as nom_producto, p.codigo as codigo, acc.fecha_vencimiento as fecha_vencimiento, p.stock as stockp, p.stock_critico as stock_critico FROM existencia_detalle acc
         left join productos p on (acc.id_producto = p.id)
         WHERE acc.id='.$id.' and acc.id_bodega='.$idbodega.'');
             $row = $queryt->result();
             $data[] = $row;    
             if ($queryt->num_rows()>0){
                $row = $row[0];
                $saldoext=($row->saldo + $cantidad);   
                $saldoextp=($row->stockp + $cantidad);                 
                $datos5 = array(
                    'saldo' => $saldoext
                );   
                $this->db->where('id', $id);
                $this->db->update('existencia_detalle', $datos5);                 
            };           

            $query = $this->db->query('SELECT * FROM existencia WHERE id_producto='.$producto.' and id_bodega='.$idbodega.'');
            $row = $query->result();
            if ($query->num_rows()>0){
                $row = $row[0];  
                if ($producto==($row->id_producto)){
                      $saldo = ($row->stock)+($cantidad);
                $datos3 = array(
                  'stock' => $saldo,
                );

                $this->db->where('id_producto', $producto);
                $this->db->update('existencia', $datos3);

                $this->db->where('id', $producto);
                $this->db->update('productos', $datos3);
                }
                 $resp['success'] = true;

            };

            }
            
            }         

            echo json_encode($resp);
    }

     public function stock5(){

        $resp = array();
        $items = json_decode($this->input->post('items'));
        $idbodega= $this->input->post('idbodega');

        
        if($items != null){           

        foreach($items as $v){

         $id = ($v->id_existencia);
         $cantidad = ($v->cantidad_real);
         $producto = ($v->id_producto);

         $queryt = $this->db->query('SELECT acc.*, p.nombre as nom_producto, p.codigo as codigo, acc.fecha_vencimiento as fecha_vencimiento, p.stock as stockp, p.stock_critico as stock_critico FROM existencia_detalle acc
         left join productos p on (acc.id_producto = p.id)
         WHERE acc.id='.$id.' and acc.id_bodega='.$idbodega.'');
             $row = $queryt->result();
             $data[] = $row;    
             if ($queryt->num_rows()>0){
                $row = $row[0];
                $saldoext=($row->saldo + $cantidad);   
                $saldoextp=($row->stockp + $cantidad_real);                 
                $datos5 = array(
                    'saldo' => $saldoext
                );   
                $this->db->where('id', $id);
                $this->db->update('existencia_detalle', $datos5);               
             
            };           

            $query = $this->db->query('SELECT * FROM existencia WHERE id_producto='.$producto.' and id_bodega='.$idbodega.'');
            $row = $query->result();
            if ($query->num_rows()>0){
                $row = $row[0];  
                if ($producto==($row->id_producto)){
                      $saldo = ($row->stock)+($cantidad);
                  $datos3 = array(
                  'stock' => $saldo,
                );

                $this->db->where('id_producto', $producto);
                $this->db->update('existencia', $datos3);

                $this->db->where('id', $producto);
                $this->db->update('productos', $datos3);
                };

                 $resp['success'] = true;
            };
            };
           }else{
                 $resp['success'] = false;                
            };

            echo json_encode($resp);
    }

     public function stock3(){

        $resp = array();
        $items = json_decode($this->input->post('items'));
        $idbodega= $this->input->post('idbodega');

        
        if($items != null){           

        foreach($items as $v){

         $id = ($v->id_existencia);
         $cantidad = ($v->cantidad);
         $producto = ($v->id_producto);

         $queryt = $this->db->query('SELECT acc.*, p.nombre as nom_producto, p.codigo as codigo, acc.fecha_vencimiento as fecha_vencimiento, p.stock as stockp, p.stock_critico as stock_critico FROM existencia_detalle acc
         left join productos p on (acc.id_producto = p.id)
         WHERE acc.id='.$id.' and acc.id_bodega='.$idbodega.'');
             $row = $queryt->result();
             $data[] = $row;    
             if ($queryt->num_rows()>0){
                $row = $row[0];
                $saldoext=($row->saldo + $cantidad);   
                $saldoextp=($row->stockp + $cantidad);                 
                $datos5 = array(
                    'saldo' => $saldoext
                );   
                $this->db->where('id', $id);
                $this->db->update('existencia_detalle', $datos5); 
            };           

            $query = $this->db->query('SELECT * FROM existencia WHERE id_producto='.$producto.' and id_bodega='.$idbodega.'');
            $row = $query->result();
            if ($query->num_rows()>0){
                $row = $row[0];  
                if ($producto==($row->id_producto)){
                      $saldo = ($row->stock)+($cantidad);
                $datos3 = array(
                  'stock' => $saldo,
                );

                $this->db->where('id_producto', $producto);
                $this->db->update('existencia', $datos3);
                
                $this->db->where('id', $producto);
                $this->db->update('productos', $datos3);

                }
                 $resp['success'] = true;

            }

            }

           }else{

                 $resp['success'] = false;
                
            }

            echo json_encode($resp);
    }

    public function stock2(){

        $resp = array();
        $id= $this->input->post('id');
        $idbodega= 1;
        $producto= $this->input->post('producto');
        $cantidad= $this->input->post('cantidad');
        $fechafactura= $this->input->post('fechafactura');       

         $queryt = $this->db->query('SELECT acc.*, p.nombre as nom_producto, p.codigo as codigo, acc.fecha_vencimiento as fecha_vencimiento, p.stock as stockp, p.stock_critico as stock_critico FROM existencia_detalle acc
         left join productos p on (acc.id_producto = p.id)
         WHERE acc.id='.$id.' and acc.id_bodega='.$idbodega.'');
             $row2 = $queryt->result();
             $data[] = $row2;    
             if ($queryt->num_rows()>0){
                $row = $row2[0];
                $saldoext=($row->saldo + $cantidad);   
                $saldoextp=($row->stockp + $cantidad);                 
                $datos5 = array(
                    'saldo' => $saldoext
                );   
                $this->db->where('id', $id);
                $this->db->update('existencia_detalle', $datos5);               
                 
            };           

            $query = $this->db->query('SELECT * FROM existencia WHERE id_producto='.$producto.' and id_bodega='.$idbodega.'');
            $row = $query->result();
            if ($query->num_rows()>0){
                $row = $row[0];  
                if ($producto==($row->id_producto)){
                      $saldo = ($row->stock)+($cantidad);
                $datos3 = array(
                  'stock' => $saldo,
                  'fecha_ultimo_movimiento' => $fechafactura
                );
                $datos = array(
                  'stock' => $saldo
                );

                $this->db->where('id_producto', $producto);
                $this->db->update('existencia', $datos3);

                $this->db->where('id', $producto);
                $this->db->update('productos', $datos);
                }
            }

            $resp['cliente'] = $row2;
            $resp['saldo'] = $saldo;
            $resp['success'] = true;
            echo json_encode($resp);
    }

     public function stock(){

        $resp = array();
        $saldoext = 0;
        $saldoextp =0;
        $id= $this->input->post('id');
        $idbodega= $this->input->post('idbodega');
        $producto= $this->input->post('producto');
        $cantidad= $this->input->post('cantidad');
        $fechafactura= $this->input->post('fechafactura');
        
          $queryt = $this->db->query('SELECT acc.*, p.nombre as nom_producto, p.codigo as codigo, acc.fecha_vencimiento as fecha_vencimiento, p.stock as stockp, p.stock_critico as stock_critico FROM existencia_detalle acc
         left join productos p on (acc.id_producto = p.id)
         WHERE acc.id='.$id.' and acc.id_bodega='.$idbodega.'');
             $row = $queryt->result();
             $data[] = $row;    
             if ($queryt->num_rows()>0){
                $row = $row[0];
                $saldoext=($row->saldo - $cantidad);   
                $saldoextp=($row->stockp);                 
                $datos5 = array(
                    'saldo' => $saldoext
                );   
                $this->db->where('id', $id);
                $this->db->update('existencia_detalle', $datos5);               
                 
            };     

            $query = $this->db->query('SELECT * FROM existencia WHERE id_producto='.$producto.' and id_bodega='.$idbodega.'');
            $row = $query->result();
            if ($query->num_rows()>0){
                $row = $row[0];  
                if ($producto==($row->id_producto)){
                      $saldo = ($row->stock)-($cantidad);
                  $datos3 = array(
                  'stock' => $saldo,
                  'fecha_ultimo_movimiento' => $fechafactura
                );

                $datos2 = array(
                  'stock' => $saldo,
                );

                $this->db->where('id_producto', $producto);
                $this->db->update('existencia', $datos3);

                $this->db->where('id', $producto);
                $this->db->update('productos', $datos2);
                }
            };                      
            $resp['success'] = true;
            $resp['saldoext'] = $saldo;
            echo json_encode($resp);
    }



    public function cargacaf(){
        $tipo_caf = $this->input->post('tipoCaf');
        $config['upload_path'] = "./facturacion_electronica/caf/"   ;
        $config['file_name'] = $tipo_caf."_".date("Ymdhis");
        $config['allowed_types'] = "*";
        $config['max_size'] = "10240";
        $config['overwrite'] = TRUE;

        //$config['max_width'] = "2000";
        //$config['max_height'] = "2000";
        $this->load->library('upload', $config);
       // $this->upload->do_upload("certificado");

        $error = false;
        $carga = false;
        if (!$this->upload->do_upload("caf")) {
            print_r($this->upload->data()); 
            print_r($this->upload->display_errors());
            $error = true;
            $message = "Error en subir archivo.  Intente nuevamente";
        }else{
            $data_file_upload = $this->upload->data();
            $carga = true;
            try {
                $xml_content = file_get_contents($config['upload_path'].$config['file_name'].$data_file_upload['file_ext']);
                $xml = new SimpleXMLElement($xml_content);
            } catch (Exception $e) {
                $error = true;
                $message = "Error al cargar XML.  Verifique formato y cargue nuevamente";
            }


            if(!$error){ //Ya cargó.  Leemos si el archivo es del tipo que elegimos anteriormente
                
                $tipo_caf_subido = $xml->CAF->DA->TD; 
                if($tipo_caf_subido != $tipo_caf){
                    $error = true;
                    $message = "CAF cargado no corresponde al seleccionado previamente.  Verifique archivo y cargue nuevamente";
                }
            }



            // VALIDAR EL RUT DE EMPRESA DEL CAF
            if(!$error){

                $this->db->select('valor ')
                  ->from('param_fe')
                  ->where('nombre','rut_empresa');
                $query = $this->db->get();
                $parametro = $query->row(); 

                $rut_parametro = $parametro->valor;

                $rut_caf = $xml->CAF->DA->RE; 
                if($rut_parametro != $rut_caf){
                    $error = true;
                    $message = "CAF cargado no corresponde a empresa registrada.  Verifique archivo y cargue nuevamente";
                }                       
            }


            if(!$error){ //Ya cargó y el archivo es correcto
                $folio_desde = $xml->CAF->DA->RNG->D; 
                $folio_hasta = $xml->CAF->DA->RNG->H; 
                $folio_fecha = (string)$xml->CAF->DA->FA;
                //print_r($folio_fecha);

                $meses = 6;
                
                $fecha= strtotime("+ $meses month", strtotime ($folio_fecha));
                
                $fecha = date('Y-m-d',$fecha);
                //$folio_fecha = date('Y-m-d',$folio_fecha);

                



                //VALIDAMOS SI LOS FOLIOS YA ESTÁN CARGADOS.  SI YA ESTÁN CARGADOS, DAREMOS ERROR INDICANDO QUE CAF YA EXISTE
                $this->db->select('f.id ')
                                  ->from('folios_caf f')
                                  ->join('caf c','f.idcaf = c.id')
                                  ->where('c.tipo_caf',$tipo_caf)
                                  ->where('f.folio between ' . $folio_desde . ' and ' . $folio_hasta);

                $query = $this->db->get();
                $folios_existentes = $query->result();              

                if(count($folios_existentes) > 0){
                    $error = true;
                    $message = "CAF cargado contiene folios ya existentes.  Verifique archivo y cargue nuevamente";
                }else{

                    // SE CREA LOG DE CARGA DE FOLIOS
                    $data_array = array(
                        'tipo_caf' => $tipo_caf,
                        'fd' => $folio_desde,
                        'fh' => $folio_hasta,  
                        'fecha_folios' => $folio_fecha,
                        'fecha_vencimiento' => $fecha,                   
                        'archivo' => $config['file_name'].".xml",
                        'caf_content' => $xml_content,
                        );
                    $this->db->insert('caf',$data_array); 
                    $idcaf = $this->db->insert_id();
                   
                    //print_r($data_array);

                    // SE CREA DETALLE DE FOLIOS

                    for($folio_carga = (int)$folio_desde; $folio_carga <= (int)$folio_hasta; $folio_carga++){
                        $data_folio = array(
                            'folio' => $folio_carga,
                            'idcaf' => $idcaf,
                            'created_at' => date("Y-m-d H:i:s")
                            );
                        $this->db->insert('folios_caf',$data_folio);
                    }
                }





            }


        }



        if($error && $carga){
            unlink($config['upload_path'].$config['file_name'].$data_file_upload['file_ext']);
        }


        $resp['success'] = true;
        $resp['message'] = $error ? $message : "Carga realizada correctamente";
        echo json_encode($resp);
     }

    public function cargacertificado(){
        $password = $this->input->post('password');

        $password_encrypt = md5($password.SALT);
        $config['upload_path'] = "./facturacion_electronica/certificado/"   ;

        $config['file_name'] = "certificado";
        $config['allowed_types'] = "*";
        $config['max_size'] = "10240";
        $config['overwrite'] = TRUE;
        //$config['max_width'] = "2000";
        //$config['max_height'] = "2000";

        $this->load->library('upload', $config);
       // $this->upload->do_upload("certificado");


        if (!$this->upload->do_upload("certificado")) {
            //*** ocurrio un error
            print_r($this->upload->data()); 
            print_r($this->upload->display_errors());
            //redirect('accounts/add_cuenta/2');
            //return;
        }else{
            $this->db->where('nombre', 'cert_password');
            $this->db->update('param_fe',array('valor' => $password)); 

            $this->db->where('nombre', 'cert_password_encrypt'); //veremos si se puede usar la password encriptada
            $this->db->update('param_fe',array('valor' => $password_encrypt)); 

        }
        $dataupload = $this->upload->data();

        $resp['success'] = true;
        echo json_encode($resp);
     }

    public function get_email(){
        $this->load->model('facturaelectronica');
        $email = $this->facturaelectronica->get_email();
        $resp['data'] = count($email) > 0 ? json_encode($email) : false;
        echo json_encode($resp);
     }

     public function dteproveegetAll(){
    $this->db->select('d.id, p.nombres as proveedor, p.e_mail, d.path_dte, d.arch_rec_dte, d.arch_res_dte, d.arch_env_rec, date_format(d.fecha_documento,"%d/%m/%Y") as fecha_documento , date_format(d.created_at,"%d/%m/%Y") as fecha_creacion ',false)
      ->from('dte_proveedores d')
      ->join('proveedores p','d.idproveedor = p.id')
      ->order_by('d.id','desc');
        $query = $this->db->get();
        $dte_provee = $query->result();
        echo json_encode($dte_provee);
    }

    public function contribautorizadosgetAll(){
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $this->load->model('facturaelectronica');
        $datos_contribuyentes = $this->facturaelectronica->contribuyentes_autorizados($start,$limit);

        $resp['success'] = true;
        $resp['total'] = $datos_contribuyentes['total'];
        $resp['data'] = $datos_contribuyentes['data'];

        echo json_encode($resp);
    }


 public function consumofoliosgetAll(){
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $this->load->model('facturaelectronica');
        $datos_consumo_folios = $this->facturaelectronica->consumo_folios($start,$limit);

        $resp['success'] = true;
        $resp['total'] = $datos_consumo_folios['total'];
        $resp['data'] = $datos_consumo_folios['data'];

        echo json_encode($resp);
    }


    public function librosgetAll(){
        $start = $this->input->get('start');
        $limit = $this->input->get('limit');

        $this->load->model('facturaelectronica');
        $datos_contribuyentes = $this->facturaelectronica->log_libros($start,$limit);


        $data = array();
        $nro = 1;
        foreach($datos_contribuyentes['data'] as $data_contribuyentes){
            $data_contribuyentes->mes = month2string($data_contribuyentes->mes);
            $data[] = $data_contribuyentes;
            $data_contribuyentes->estado = $data_contribuyentes->estado == 'P' ? 'Pendiente' : 'Generado';
            $data_contribuyentes->nro = $nro;
            $nro++;
        }

        $resp['success'] = true;
        $resp['total'] = $datos_contribuyentes['total'];
        $resp['data'] = $data;

        echo json_encode($resp);
    }

    public function estado_dte($idfactura){
        $this->load->model('facturaelectronica');
        $datos_dte = $this->facturaelectronica->datos_dte($idfactura);
        $config = $this->facturaelectronica->genera_config();
        include $this->facturaelectronica->ruta_libredte();

        $Firma = new \sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital
        $rut = $Firma->getId(); 
        $rut_consultante = explode("-",$rut);

        $empresa = $this->facturaelectronica->get_empresa();
        $datos_empresa_factura = $this->facturaelectronica->get_empresa_factura($idfactura);

        $result = array();
        $result['error'] = false;
        $result['glosa_estado'] = "";
        $result['glosa_err'] = "";

        $token = \sasco\LibreDTE\Sii\Autenticacion::getToken($config['firma']);
        if (!$token) {
            foreach (\sasco\LibreDTE\Log::readAll() as $error){
                $result['error'] = true;

            }
            $result['message'] = "Error de conexión con SII";          
            echo json_encode($result);
            exit;
        }

        $EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
        $EnvioDte->loadXML($datos_dte->dte);
        $Documentos = $EnvioDte->getDocumentos();
        //print_r($Documentos); exit;
        foreach ($Documentos as $DTE) {
        
            if ($DTE->getDatos()){
                $fecemision = $DTE->getFechaEmision();
                $monto_dte = $DTE->getMontoTotal();
            }
            break; // siempre será sólo 1 documento
        }       

        // consultar estado dte
        $xml = \sasco\LibreDTE\Sii::request('QueryEstDte', 'getEstDte', [
            'RutConsultante'    => $rut_consultante[0],
            'DvConsultante'     => $rut_consultante[1],
            'RutCompania'       => $empresa->rut,
            'DvCompania'        => $empresa->dv,
            'RutReceptor'       => substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1),
            'DvReceptor'        => substr($datos_empresa_factura->rut_cliente,-1),
            'TipoDte'           => $datos_dte->tipo_caf,
            'FolioDte'          => $datos_dte->folio,
            'FechaEmisionDte'   => substr($fecemision,8,2).substr($fecemision,5,2).substr($fecemision,0,4),
            'MontoDte'          => $monto_dte,
            'token'             => $token,
        ]);

        // si el estado se pudo recuperar se muestra
        if ($xml!==false) {
            $array_result = (array)$xml->xpath('/SII:RESPUESTA/SII:RESP_HDR')[0];
            $result['error'] = false;
            $result['glosa_estado'] = $array_result['GLOSA_ESTADO'];
            $result['glosa_err'] = $array_result['GLOSA_ERR'];
            echo json_encode($result);
            exit;           
        }

        // mostrar error si hubo
        foreach (\sasco\LibreDTE\Log::readAll() as $error){
            $result['error'] = true;
            $result['message'] = "Error de conexión con SII";
        }
        echo json_encode($result);
        exit;
    }



  public function estado_dte_consumo_folios($idconsumo){
        $this->load->model('facturaelectronica');
        $datos_dte = $this->facturaelectronica->get_consumo_folios_by_id($idconsumo);
        $config = $this->facturaelectronica->genera_config();
        include $this->facturaelectronica->ruta_libredte();

        $Firma = new \sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital
        $rut = $Firma->getId(); 
        $rut_consultante = explode("-",$rut);

        $empresa = $this->facturaelectronica->get_empresa();
        $datos_empresa_factura = $this->facturaelectronica->get_empresa_factura($idfactura);

        $result = array();
        $result['error'] = false;
        $result['glosa_estado'] = "";
        $result['glosa_err'] = "";

        $token = \sasco\LibreDTE\Sii\Autenticacion::getToken($config['firma']);
        if (!$token) {
            foreach (\sasco\LibreDTE\Log::readAll() as $error){
                $result['error'] = true;

            }
            $result['message'] = "Error de conexión con SII";          
            echo json_encode($result);
            exit;
        }

      /*  $EnvioDte = new \sasco\LibreDTE\Sii\EnvioDte();
        $EnvioDte->loadXML($datos_dte->dte);
        $Documentos = $EnvioDte->getDocumentos();
        //print_r($Documentos); exit;
        foreach ($Documentos as $DTE) {
        
            if ($DTE->getDatos()){
                $fecemision = $DTE->getFechaEmision();
                $monto_dte = $DTE->getMontoTotal();
            }
            break; // siempre será sólo 1 documento
        } */      

        // consultar estado dte
        $xml = \sasco\LibreDTE\Sii::request('QueryEstDte', 'getEstDte', [
            'RutConsultante'    => $rut_consultante[0],
            'DvConsultante'     => $rut_consultante[1],
            'RutCompania'       => $empresa->rut,
            'DvCompania'        => $empresa->dv,
           // 'RutReceptor'       => substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1),
           // 'DvReceptor'        => substr($datos_empresa_factura->rut_cliente,-1),
            //'TipoDte'           => $datos_dte->tipo_caf,
            //'FolioDte'          => $datos_dte->folio,
            //'FechaEmisionDte'   => substr($fecemision,8,2).substr($fecemision,5,2).substr($fecemision,0,4),
            //'MontoDte'          => $monto_dte,
            'token'             => $token,
        ]);

        // si el estado se pudo recuperar se muestra
        if ($xml!==false) {
            $array_result = (array)$xml->xpath('/SII:RESPUESTA/SII:RESP_HDR')[0];
            $result['error'] = false;
            $result['glosa_estado'] = $array_result['GLOSA_ESTADO'];
            $result['glosa_err'] = $array_result['GLOSA_ERR'];
            echo json_encode($result);
            exit;           
        }

        // mostrar error si hubo
        foreach (\sasco\LibreDTE\Log::readAll() as $error){
            $result['error'] = true;
            $result['message'] = "Error de conexión con SII";
        }
        echo json_encode($result);
        exit;
    }
   
    public function estado_tipo_documento($tipo_documento){
        $this->db->select('f.id ')
                          ->from('folios_caf f')
                          ->join('caf c','f.idcaf = c.id')
                          ->where('c.tipo_caf',$tipo_documento)
                          ->where("f.estado = 'P'");
        $query = $this->db->get();
        $folios_existentes = $query->result();              

        $resp['cantidad'] = count($folios_existentes);
        echo json_encode($resp);
     }

    public function busca_parametro_fe($parametro){

        $this->load->model('facturaelectronica');
        $datos = $this->facturaelectronica->busca_parametro_fe($parametro);
        echo json_encode($datos);       
    }

    public function folio_documento_electronico2(){

            $resp = array();
            $tipo_doc = $this->input->post('tipo_doc');
            $numero = $this->input->post('numero');
            $folio = $this->input->post('id_folio');   

            if($folio != 0){
                  $this->db->where('id', $folio);
                  $this->db->update('folios_caf',array(
                  'estado' => 'P',
                  )); 
                  $resp['success'] = true;
            }            
            echo json_encode($resp);
    }

      public function folio_documento_electronico($tipo_doc){

            $tipo_caf = 0;
            $valida="NO";
            $red="NO";
            $id_folio=0;
            $nuevo_folio=0;

            $fecha_hoy=date('Y-m-d');
            
            if($tipo_doc == 101){
                  $tipo_caf = 33;
            }else if($tipo_doc == 102){
                  $tipo_caf = 61;
            }else if($tipo_doc == 103){
                  $tipo_caf = 34;
            }else if($tipo_doc == 104){
                  $tipo_caf = 56;
            }else if($tipo_doc == 105){
                  $tipo_caf = 52;
            }else if($tipo_doc == 107){
                  $tipo_caf = 46;
            }else if($tipo_doc == 120){
                  $tipo_caf = 39;
            }

            $nuevo_folio = 0;
            //buscar primero si existe algún folio ocupado hace más de 4 horas.
            $this->db->select('fc.id, fc.folio, c.fecha_vencimiento ')
              ->from('folios_caf fc')
              ->join('caf c','fc.idcaf = c.id')
              ->where('c.tipo_caf',$tipo_caf)
              ->where('fc.estado','T')
              ->where('fc.updated_at <= (now() - interval 4 hour)')
              ->order_by('fc.folio')
              ->limit(1);
            $query = $this->db->get();
            $folios_caf = $query->row();  
            if(count($folios_caf) < 0){
                  $nuevo_folio = $folios_caf->folio;
                  $red="no";
                  $id_folio = $folios_caf->id;
                  $fecha_venc = $folios_caf->fecha_vencimiento;
                  if(!$fecha_venc){
                            $fecha_venc=$fecha_hoy;
                        };
                   if($fecha_venc < $fecha_hoy){
                    $valida="SI";
                  };
            }else{ // buscar folios pendientes
                  $this->db->select('fc.id, fc.folio, c.fecha_vencimiento ')
                    ->from('folios_caf fc')
                    ->join('caf c','fc.idcaf = c.id')
                    ->where('c.tipo_caf',$tipo_caf)
                    ->where('fc.estado','P')
                    ->order_by('fc.folio')
                    ->limit(1);
                  $query = $this->db->get();
                  $folios_caf = $query->row();  
                  if(count($folios_caf) > 0){
                        $nuevo_folio = $folios_caf->folio;
                        $id_folio = $folios_caf->id;
                        $fecha_venc = $folios_caf->fecha_vencimiento;
                        $red="si";
                        if(!$fecha_venc){
                            $fecha_venc=$fecha_hoy;
                        };
                        if($fecha_venc < $fecha_hoy){
                            $valida="SI";
                        };
                  }else{
                     $fecha_venc=$fecha_hoy;
                      
                  }
            }

            if($nuevo_folio != 0){
                  $this->db->where('id', $id_folio);
                  $this->db->update('folios_caf',array(
                  'estado' => 'T',
                  'updated_at' => date('Y-m-d H:i:s'))); 
            }

            $resp['folio'] = $nuevo_folio;
            $resp['idfolio'] = $id_folio;
            $resp['paso'] = $red;
            $resp['fecha_venc'] = $fecha_venc;
            $resp['valida'] = $valida;
            echo json_encode($resp);
       }


	public function get_annos(){
		$anno = date("Y");
		$array_annos = array();
		$anno_inic = $anno - 15;
		while($anno_inic <= $anno){
			array_push($array_annos,array('anno' => $anno));
			//$array_annos[$anno_inic] = $anno_inic;
			$anno--;
		}
		echo json_encode($array_annos);
	}	

      public function existe_certificado(){
            $this->load->model('facturaelectronica');
            $resp['existe'] = file_exists($this->facturaelectronica->ruta_certificado()) ? true: false;
            echo json_encode($resp);
      }
	

	public function existe_empresa(){
		$this->load->model('facturaelectronica');
		$empresa = $this->facturaelectronica->get_empresa();
		$resp['existe'] = count($empresa) > 0 ? true : false;
   		echo json_encode($resp);
	 }


	public function get_empresa_json(){
		$this->load->model('facturaelectronica');
		$empresa = $this->facturaelectronica->get_empresa();
   		echo json_encode($empresa);		
	}


	public function put_empresa(){
		//print_r($this->input->post(NULL,true)); exit;
		$this->load->model('facturaelectronica');
		$empresa = $this->facturaelectronica->get_empresa();
		$tipo_caf = $this->input->post('tipoCaf');
        $config['upload_path'] = "./facturacion_electronica/images/"	;
        $config['file_name'] = 'logo_empresa';
        $config['allowed_types'] = "*";
        $config['max_size'] = "10240";
        $config['overwrite'] = TRUE;


        $this->load->library('upload', $config);

        $error = false;
        $carga = false;
        if (!$this->upload->do_upload("logo") && is_null($empresa->logo)) { // si no hay descarga y no tiene archivo cargado
            print_r($this->upload->data()); 
            print_r($this->upload->display_errors());
            $error = true;
            $message = "Error en subir archivo.  Intente nuevamente";
        }else{
        	
        	//$empresa = $this->facturaelectronica->get_empresa();
    		$rut = $this->input->post('rut');
    		$array_rut = explode("-",$rut);
    		$fecha_resolucion = $this->input->post('fec_resolucion');
    		$fec_resolucion = substr($fecha_resolucion,6,4)."-".substr($fecha_resolucion,3,2)."-".substr($fecha_resolucion,0,2);
    		$data_empresa = array(
    					'rut' => $array_rut[0],
    					'dv' => $array_rut[1],
    					'razon_social' => $this->input->post('razon_social'),
    					'giro' => $this->input->post('giro'),
    					'cod_actividad' => $this->input->post('cod_actividad'),
    					'dir_origen' => $this->input->post('direccion'),
    					'comuna_origen' => $this->input->post('comuna'),
    					'fec_resolucion' => $fec_resolucion,
    					'nro_resolucion' => $this->input->post('nro_resolucion'),
    					'logo' => 'logo_empresa.png'
    			);
        	if(count($empresa) > 0){ //actualizar
        		$this->db->where('id',1);
        		$this->db->update('empresa',$data_empresa);

        	}else{ //insertar


	        	$carga = true;
				$this->db->insert('empresa',$data_empresa);

        	}






        }



		if($error && $carga){
			unlink($config['upload_path'].$config['file_name'].$data_file_upload['file_ext']);
		}


   		$resp['success'] = true;
   		$resp['message'] = $error ? $message : "Carga realizada correctamente";
   		echo json_encode($resp);
	 }

	public function exportTXTGanado(){

		/**************************exporta txt*******/
		$idfactura = $this->input->get('idfactura');
		            
        $data = array();
        $query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, c.fono, cp.nombre as cond_pago, cp.codigo as codigo_con_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, cor.nombre as nomdocumento, ma.nombre as com_sucursal, v.cod_interno as cod_interno FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join correlativos cor on (acc.tipo_documento = cor.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.id = '.$idfactura.'');

			$row = $query->result();

        
        if ($query->num_rows()>0){	
			
			$v = $row[0];
                    
            $nomdocumento = $v->nomdocumento;
            $fechafactura = $v->fecha_factura;
            $fechavenc = $v->fecha_venc;
            $idbodega = $v->id_bodega;
            
            $fecha = $v->fecha_factura;
			list($anio, $mes, $dia) = explode("-",$fecha);
			$fecha2 = $v->fecha_venc;
			list($anio2, $mes2, $dia2) = explode("-",$fecha2);

			$fechafactura = $dia."/".$mes."/".$anio;
            $fechavenc = $dia2."/".$mes2."/".$anio2;
			
			          
            $numdocumento = $v->num_factura;            
            $nomcliente = substr($v->nombre_cliente, 0, 40);            
            $condventa = $v->cond_pago;
            $codcondventa = substr($v->codigo_con_pago, -2);
            $vendedor = $v->nom_vendedor;
            $codvendedor = $v->id_vendedor;
            if ($v->ciu_sucursal){
            	$ciudad= $v->ciu_sucursal;            	
            }else{
            	$ciudad= $v->nombre_ciudad;
            };
            if ($v->com_sucursal){
            	$comuna= $v->com_sucursal;            	
            }else{
            	$comuna= $v->nombre_comuna;
            };
            $giro = substr($v->giro, 0, 42);
            $valornetocom = 18;
            $emisora ="";
            $sucsii = "";
            $codinterno=$v->cod_interno;
            $codinterno2="";
            $codImpuestoadic1="";
            $tasaImpadic1="";
            $valImpadic1="";
            $codImpuestoadic2="";
            $tasaImpadic2="";
            $valImpadic2="";
            $codImpuestoadic3="";
            $tasaImpadic3="";
            $valImpadic3="";
            $credespecial="";
            $montoperiodo="";
            $montototal="";
            $valordesc1 = "";
            $valordesc2 = "";
            $valordesc3 = "";
            $espaciosfin= "";
            $contactoreceptor="";
            if($v->direc_sucursal){
            	$direccionreceptor=$v->direc_sucursal;            	
            }else{
                $direccionreceptor=$v->direccion;
            };
            if($v->direc_sucursal){
            	$direcciondespacho=$v->direc_sucursal;            	
            }else{
                $direcciondespacho=$v->direccion;
            };
            $personalizadosl3="";
            $personalizadosl4="";
            $patentetrans="";
            $comunadespacho="";
            $ciudaddespacho="";
            $ruttransporte="";
            $neto = intval($v->neto);
            $exento="";
            $pordescuento="";
            $iva = intval($v->iva);            
            $total = intval($v->totalfactura);
            $id = $v->id;
            $espaciost = 25;
            $pregistro="";
		    $espacios20= 19;
		    $espacios5= 4;
		    $espacios2= 1;
		    $espacios8= 7;
		    $espacios9= 8;
		    $espacios35= 34;
		    $espacios40= 39;
		    $espacios42= 41;
		    $espacios60= 59;
		    $espacios80= 79;
		    $espacios300= 299;
		    $espacio300="";
		    $espacios30= 29;
		    $espacios110= 109;
		    $espacios139= 138;
		    $espaciolargo= 299;
		    $totalletras = (valorEnLetras($total));
		    $nomclienteinicio="                     ";
		    $espacios21= 20;
		    $espacios25= 24;

		    $rutautoriza = $v->rut_cliente;
		   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		   
		    };
		    if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $v->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };

		    $rutcliente = $v->rut_cliente;
		    if (strlen($rutcliente) == 9){
		    	$rutcliente=("0".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 8){
		    	$rutcliente=("00".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 7){
		    	$rutcliente=("000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 6){
		    	$rutcliente=("0000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 5){
		    	$rutcliente=("00000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 4){
		    	$rutcliente=("000000".$rutcliente);
		    	
		    };
		    $file_content = "";    
		    $file_content .= $idbodega.$this->crearespacios($espacios21 - strlen( $idbodega));
            $file_content .= ";";
            $file_content .= $condventa.$this->crearespacios($espacios25 - strlen( $condventa));  //Nombre condicion Pago
            $file_content .= ";";
            $file_content .= $vendedor.$this->crearespacios($espacios30 - strlen( $vendedor));  //Nombre Vendedor
            $file_content .= ";";
            $file_content .= $comuna;  //Codigo vendedor
            $file_content .= ";";
            $file_content .= $codcondventa; //Codigo condicion Pago
            $file_content .= chr(13).chr(10);  
                      		    
            //$file_content .= chr(13).chr(10);
            //$file_content .= " ";
            //$file_content .= ";";
            $file_content .= chr(13).chr(10);
            
            //$file_content .= chr(13).chr(10);
            $file_content .= " "; //espacio
            $file_content .= ";";
            $file_content .= str_pad($numdocumento,10," ",STR_PAD_LEFT); // Folio
            $file_content .= ";"; //
            $file_content .= $fechafactura;  //fecha Emision
            $file_content .= ";";
            $file_content .= " "; //indicador de no rebeja
            $file_content .= ";";
            $file_content .= " ";// tipo despacho
            $file_content .= ";";
            $file_content .= "0";// tipo de traslado
            $file_content .= ";";
            $file_content .= " ";// indicador servicio periodico
            $file_content .= ";";
            $file_content .= " ";// indicador montos brutos
            $file_content .= ";";
            $file_content .= "1";// DEBE SELECIONAR 1 O 2 SECUN TIPO DE PAGO
            $file_content .= ";";
            $file_content .= $fechavenc;
            $file_content .= ";";
            $file_content .= $emisora.$this->crearespacios($espacios20 - strlen($emisora));  //sucursal emision
            $file_content .= ";";
            $file_content .= $sucsii.$this->crearespacios($espacios9 - strlen( $sucsii));  //sucursal sii
            $file_content .= ";";
            $file_content .= $codinterno; // codigo vendedor
            $file_content .= ";";   
            $file_content .= $rutcliente; // rut receptor
            $file_content .= ";";
            $file_content .= "          "; // rut solicitante factura
            $file_content .= ";";
            $file_content .= "                    ";//codigo interno receptor
            $file_content .= ";";
            $file_content .= $nomcliente.$this->crearespacios($espacios40 - strlen( $nomcliente));  //razon social
            $file_content .= ";";
            $file_content .= $giro.$this->crearespacios($espacios42 - strlen( $giro)); //Giro receptor
            $file_content .= ";"; 
            $file_content .= $contactoreceptor.$this->crearespacios($espacios30 - strlen( $contactoreceptor)); // Contacto receptor
            $file_content .= ";";
            $file_content .= $direccionreceptor.$this->crearespacios($espacios60 - strlen( $direccionreceptor)); // Direccion receptor
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios110 - strlen( $personalizadosl3));
            $file_content .= ";";// Perzonalizados Linea 3
            $file_content .= chr(13).chr(10);
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad
            $file_content .= ";";
            $file_content .= $direcciondespacho.$this->crearespacios($espacios60 - strlen( $direcciondespacho)); // Direccion Despacho
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna Despacho
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad Despacho
            $file_content .= ";";
            $file_content .= $patentetrans.$this->crearespacios($espacios8 - strlen( $patentetrans)); //Patente Transporte
            $file_content .= ";";
            $file_content .= str_pad($ruttransporte,10," ",STR_PAD_LEFT); // rut Transportista
            $file_content .= ";";
            $file_content .= str_pad($neto,18," ",STR_PAD_LEFT); // Monto Neto
            $file_content .= ";";
            $file_content .= str_pad($exento,18," ",STR_PAD_LEFT); // Monto Exento
            $file_content .= ";";
            $file_content .= "     ";// Tasa Iva
            $file_content .= ";";
            $file_content .= str_pad($iva,18," ",STR_PAD_LEFT); // Monto Iva
            $file_content .= ";";
            $file_content .= "  "; // 2 espacios
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= str_pad($total,18," ",STR_PAD_LEFT); //Valor descuento 1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= chr(13).chr(10); // linea 4 fin
                        
            $query2 = $this->db->get_where('detalle_factura_glosa', array('id_factura' => $idfactura));
            $c=0;

            foreach ($query2->result() as $z){

        	$this->db->where('id', $z->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];

			$c= $c+1;
				
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $producto->codigo.$this->crearespacios($espacios35 - strlen( $producto->codigo));  //razon social
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= $producto->nombre.$this->crearespacios($espacios80 - strlen( $producto->nombre));  //razon social
            $file_content .= ";";
            $file_content .= $espacio300.$this->crearespacios($espacios300 - strlen( $espacio300));
            $file_content .= ";";
            $file_content .= str_pad($z->cantidad,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "    "; // unidad de medidaN
            $file_content .= ";";
            $file_content .= str_pad($z->kilos,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "   "; // Moneda 3 espaios
            $file_content .= ";";
            $file_content .= "          "; // Factor Conversion 10 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->precios)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // recargo pesos 18 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->neto)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= " "; // Indicador de Agente 1 espacios
            $file_content .= ";";
            $file_content .= "              "; // 14 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios
            $file_content .= ";";
            $file_content .= "   %";
            $file_content .= chr(13).chr(10); //fin linea 5
                
            }

            if ($c < 30){
            	
            	$b = (30 - $c);

            	$producto="";
            	$productonombre="";
            	$cantidad="";
            	$precio="";
            	$descuento="";
            	$neto=0;

            	for ($i = 1; $i <= $b; $i++) {

            		//$file_content .= chr(13).chr(10); 
		            $file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));//razon social
		            $file_content .= chr(13).chr(10);
            
		            
		        }

            };
        };

           for ($a = 1; $a <= 10; $a++) {

            	//$file_content .= chr(13).chr(10);
            	$file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));
            	$file_content .= ";";
            	$file_content .= chr(13).chr(10);


            };

            //$file_content .= chr(13).chr(10);
            $file_content .= " "; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= $totalletras.$this->crearespacios($espacios80 - strlen( $totalletras));
		     //$file_content .= chr(13).chr(10);
		     //linea fin          
        
        $nombre_archivo = "33_NPG_".str_pad($numdocumento,7,"0",STR_PAD_LEFT).".spf";
        $path_archivo = './facturas/';
		$f_archivo = fopen($path_archivo.$nombre_archivo,'w');
		fwrite($f_archivo,$file_content);
		fclose($f_archivo);

		$data_archivo = basename($path_archivo.$nombre_archivo);
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename=' . $data_archivo);
		header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
		readfile($path_archivo.$nombre_archivo);			
		/*******************************fin exporta***********/

		//$origen='C:\Users\Sergio\Downloads\facturacion.txt';
		//$destino='C:\facturacion.txt';
		//mkdir(dirname($dstfile), 0777, true);
		//copy($origen, $destino);
		//unlink("C:\Users\Sergio\Downloads\facturacion.txt");
	}

      public function exportTXTGDGanado(){

            /**************************exporta txt*******/
            $idfactura = $this->input->get('idfactura');
                        
        $data = array();
        $query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, c.fono, cp.nombre as cond_pago, cp.codigo as codigo_con_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, cor.nombre as nomdocumento, ma.nombre as com_sucursal, v.cod_interno as cod_interno FROM factura_clientes acc
                  left join clientes c on (acc.id_cliente = c.id)
                  left join cod_activ_econ e on (c.id_giro = e.id)
                  left join correlativos cor on (acc.tipo_documento = cor.id)
                  left join comuna m on (c.id_comuna = m.id)
                  left join ciudad s on (c.id_ciudad = s.id)
                  left join vendedores v on (acc.id_vendedor = v.id)
                  left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
                  left join comuna ma on (cs.id_comuna = ma.id)
                  left join ciudad sa on (cs.id_ciudad = sa.id)
                  left join observacion_facturas ob on (acc.id_observa = ob.id)
                  left join cond_pago cp on (acc.id_cond_venta = cp.id)
                  WHERE acc.id = '.$idfactura.'');

                  $row = $query->result();

        
        if ($query->num_rows()>0){  
                  
                  $v = $row[0];
                    
            $nomdocumento = $v->nomdocumento;
            $fechafactura = $v->fecha_factura;
            $fechavenc = $v->fecha_venc;
            $idbodega = $v->id_bodega;
            
            $fecha = $v->fecha_factura;
                  list($anio, $mes, $dia) = explode("-",$fecha);
                  $fecha2 = $v->fecha_venc;
                  list($anio2, $mes2, $dia2) = explode("-",$fecha2);

                  $fechafactura = $dia."/".$mes."/".$anio;
            $fechavenc = $dia2."/".$mes2."/".$anio2;
                  
                            
            $numdocumento = $v->num_factura;            
            $nomcliente = substr($v->nombre_cliente, 0, 40);            
            $condventa = $v->cond_pago;
            $codcondventa = substr($v->codigo_con_pago, -2);
            $vendedor = $v->nom_vendedor;
            $codvendedor = $v->id_vendedor;
            if ($v->ciu_sucursal){
                  $ciudad= $v->ciu_sucursal;                
            }else{
                  $ciudad= $v->nombre_ciudad;
            };
            if ($v->com_sucursal){
                  $comuna= $v->com_sucursal;                
            }else{
                  $comuna= $v->nombre_comuna;
            };
            $giro = substr($v->giro, 0, 42);
            $valornetocom = 18;
            $emisora ="";
            $sucsii = "";
            $codinterno=$v->cod_interno;
            $codinterno2="";
            $codImpuestoadic1="";
            $tasaImpadic1="";
            $valImpadic1="";
            $codImpuestoadic2="";
            $tasaImpadic2="";
            $valImpadic2="";
            $codImpuestoadic3="";
            $tasaImpadic3="";
            $valImpadic3="";
            $credespecial="";
            $montoperiodo="";
            $montototal="";
            $valordesc1 = "";
            $valordesc2 = "";
            $valordesc3 = "";
            $espaciosfin= "";
            $contactoreceptor="";
            if($v->direc_sucursal){
                  $direccionreceptor=$v->direc_sucursal;                
            }else{
                $direccionreceptor=$v->direccion;
            };
            if($v->direc_sucursal){
                  $direcciondespacho=$v->direc_sucursal;                
            }else{
                $direcciondespacho=$v->direccion;
            };
            $personalizadosl3="";
            $personalizadosl4="";
            $patentetrans="";
            $comunadespacho="";
            $ciudaddespacho="";
            $ruttransporte="";
            $neto = intval($v->neto);
            $exento="";
            $pordescuento="";
            $iva = intval($v->iva);            
            $total = intval($v->totalfactura);
            $id = $v->id;
            $espaciost = 25;
            $pregistro="";
                $espacios20= 19;
                $espacios5= 4;
                $espacios2= 1;
                $espacios8= 7;
                $espacios9= 8;
                $espacios35= 34;
                $espacios40= 39;
                $espacios42= 41;
                $espacios60= 59;
                $espacios80= 79;
                $espacios300= 299;
                $espacio300="";
                $espacios30= 29;
                $espacios110= 109;
                $espacios139= 138;
                $espaciolargo= 299;
                $totalletras = (valorEnLetras($total));
                $nomclienteinicio="                     ";
                $espacios21= 20;
                $espacios25= 24;

                $rutautoriza = $v->rut_cliente;
                  if (strlen($rutautoriza) == 8){
                  $ruta1 = substr($rutautoriza, -1);
                  $ruta2 = substr($rutautoriza, -4, 3);
                  $ruta3 = substr($rutautoriza, -7, 3);
                  $ruta4 = substr($rutautoriza, -8, 1);
                  $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
                };
                if (strlen($rutautoriza) == 9){
                  $ruta1 = substr($rutautoriza, -1);
                  $ruta2 = substr($rutautoriza, -4, 3);
                  $ruta3 = substr($rutautoriza, -7, 3);
                  $ruta4 = substr($rutautoriza, -9, 2);
                  $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
               
                };
                if (strlen($rutautoriza) == 2){
                  $ruta1 = substr($rutautoriza, -1);
                  $ruta2 = substr($rutautoriza, -4, 1);
                  $v->rut_cliente = ($ruta2."-".$ruta1);
                 
                };

                $rutcliente = $v->rut_cliente;
                if (strlen($rutcliente) == 9){
                  $rutcliente=("0".$rutcliente);
                  
                };
                if (strlen($rutcliente) == 8){
                  $rutcliente=("00".$rutcliente);
                  
                };
                if (strlen($rutcliente) == 7){
                  $rutcliente=("000".$rutcliente);
                  
                };
                if (strlen($rutcliente) == 6){
                  $rutcliente=("0000".$rutcliente);
                  
                };
                if (strlen($rutcliente) == 5){
                  $rutcliente=("00000".$rutcliente);
                  
                };
                if (strlen($rutcliente) == 4){
                  $rutcliente=("000000".$rutcliente);
                  
                };
            $file_content = "";    
            //$file_content .= $idbodega.$this->crearespacios($espacios21 - strlen( $idbodega));
            $file_content .= "                     ";
            $file_content .= ";";
            $file_content .= $condventa.$this->crearespacios($espacios25 - strlen( $condventa));  //Nombre condicion Pago
            $file_content .= ";";
            $file_content .= $vendedor.$this->crearespacios($espacios30 - strlen( $vendedor));  //Nombre Vendedor
            $file_content .= ";";
            $file_content .= $comuna;  //Codigo vendedor
            $file_content .= ";";
            $file_content .= $codcondventa; //Codigo condicion Pago
            $file_content .= chr(13).chr(10);  
                                  
            //$file_content .= chr(13).chr(10);
            //$file_content .= " ";
            //$file_content .= ";";
            $file_content .= chr(13).chr(10);
            
            //$file_content .= chr(13).chr(10);
            $file_content .= " "; //espacio
            $file_content .= ";";
            $file_content .= str_pad($numdocumento,10," ",STR_PAD_LEFT); // Folio
            $file_content .= ";"; //
            $file_content .= $fechafactura;  //fecha Emision
            $file_content .= ";";
            $file_content .= " "; //indicador de no rebeja
            $file_content .= ";";
            $file_content .= " ";// tipo despacho
            $file_content .= ";";
            $file_content .= "0";// tipo de traslado
            $file_content .= ";";
            $file_content .= " ";// indicador servicio periodico
            $file_content .= ";";
            $file_content .= " ";// indicador montos brutos
            $file_content .= ";";
            $file_content .= "1";// DEBE SELECIONAR 1 O 2 SECUN TIPO DE PAGO
            $file_content .= ";";
            $file_content .= $fechavenc;
            $file_content .= ";";
            $file_content .= $emisora.$this->crearespacios($espacios20 - strlen($emisora));  //sucursal emision
            $file_content .= ";";
            $file_content .= $sucsii.$this->crearespacios($espacios9 - strlen( $sucsii));  //sucursal sii
            $file_content .= ";";
            $file_content .= $codinterno; // codigo vendedor
            $file_content .= ";";   
            $file_content .= $rutcliente; // rut receptor
            $file_content .= ";";
            $file_content .= "          "; // rut solicitante factura
            $file_content .= ";";
            $file_content .= "                    ";//codigo interno receptor
            $file_content .= ";";
            $file_content .= $nomcliente.$this->crearespacios($espacios40 - strlen( $nomcliente));  //razon social
            $file_content .= ";";
            $file_content .= $giro.$this->crearespacios($espacios42 - strlen( $giro)); //Giro receptor
            $file_content .= ";"; 
            $file_content .= $contactoreceptor.$this->crearespacios($espacios30 - strlen( $contactoreceptor)); // Contacto receptor
            $file_content .= ";";
            $file_content .= $direccionreceptor.$this->crearespacios($espacios60 - strlen( $direccionreceptor)); // Direccion receptor
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios110 - strlen( $personalizadosl3));
            $file_content .= ";";// Perzonalizados Linea 3
            $file_content .= chr(13).chr(10);
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad
            $file_content .= ";";
            $file_content .= $direcciondespacho.$this->crearespacios($espacios60 - strlen( $direcciondespacho)); // Direccion Despacho
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna Despacho
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad Despacho
            $file_content .= ";";
            $file_content .= $patentetrans.$this->crearespacios($espacios8 - strlen( $patentetrans)); //Patente Transporte
            $file_content .= ";";
            $file_content .= str_pad($ruttransporte,10," ",STR_PAD_LEFT); // rut Transportista
            $file_content .= ";";
            $file_content .= str_pad($neto,18," ",STR_PAD_LEFT); // Monto Neto
            $file_content .= ";";
            $file_content .= str_pad($exento,18," ",STR_PAD_LEFT); // Monto Exento
            $file_content .= ";";
            $file_content .= "     ";// Tasa Iva
            $file_content .= ";";
            $file_content .= str_pad($iva,18," ",STR_PAD_LEFT); // Monto Iva
            $file_content .= ";";
            $file_content .= "  "; // 2 espacios
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= str_pad($total,18," ",STR_PAD_LEFT); //Valor descuento 1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= chr(13).chr(10); // linea 4 fin
                        
            $query2 = $this->db->get_where('detalle_factura_glosa', array('id_factura' => $idfactura));
            $c=0;

            foreach ($query2->result() as $z){

            $this->db->where('id', $z->id_producto);
                  $producto = $this->db->get("productos");  
                  $producto = $producto->result();
                  $producto = $producto[0];

                  $c= $c+1;
                        
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $producto->codigo.$this->crearespacios($espacios35 - strlen( $producto->codigo));  //razon social
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= $producto->nombre.$this->crearespacios($espacios80 - strlen( $producto->nombre));  //razon social
            $file_content .= ";";
            $file_content .= $espacio300.$this->crearespacios($espacios300 - strlen( $espacio300));
            $file_content .= ";";
            $file_content .= str_pad($z->cantidad,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "    "; // unidad de medidaN
            $file_content .= ";";
            $file_content .= str_pad($z->kilos,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "   "; // Moneda 3 espaios
            $file_content .= ";";
            $file_content .= "          "; // Factor Conversion 10 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->precios)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // recargo pesos 18 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->neto)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= " "; // Indicador de Agente 1 espacios
            $file_content .= ";";
            $file_content .= "              "; // 14 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios
            $file_content .= ";";
            $file_content .= "    ";
            $file_content .= chr(13).chr(10); //fin linea 5
                
            }

            if ($c < 30){
                  
                  $b = (30 - $c);

                  $producto="";
                  $productonombre="";
                  $cantidad="";
                  $precio="";
                  $descuento="";
                  $neto=0;

                  for ($i = 1; $i <= $b; $i++) {

                        //$file_content .= chr(13).chr(10); 
                        $file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));//razon social
                        $file_content .= chr(13).chr(10);
            
                        
                    }

            };
        };

           for ($a = 1; $a <= 10; $a++) {

                  //$file_content .= chr(13).chr(10);
                  $file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));
                  $file_content .= ";";
                  $file_content .= chr(13).chr(10);


            };

            //$file_content .= chr(13).chr(10);
            $file_content .= " "; // Indicador de Agente 1 espacios
                $file_content .= ";";
                $file_content .= $totalletras.$this->crearespacios($espacios80 - strlen( $totalletras));
                 //$file_content .= chr(13).chr(10);
                 //linea fin          
        
        $nombre_archivo = "33_NPG_".str_pad($numdocumento,7,"0",STR_PAD_LEFT).".spf";
        $path_archivo = './facturas/';
            $f_archivo = fopen($path_archivo.$nombre_archivo,'w');
            fwrite($f_archivo,$file_content);
            fclose($f_archivo);

            $data_archivo = basename($path_archivo.$nombre_archivo);
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename=' . $data_archivo);
            header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
            readfile($path_archivo.$nombre_archivo);              
            /*******************************fin exporta***********/

            //$origen='C:\Users\Sergio\Downloads\facturacion.txt';
            //$destino='C:\facturacion.txt';
            //mkdir(dirname($dstfile), 0777, true);
            //copy($origen, $destino);
            //unlink("C:\Users\Sergio\Downloads\facturacion.txt");
      }

	 
	public function update(){

		$resp = array();

		$idcliente = $this->input->post('idcliente');
		$numfactura = $this->input->post('numfactura');
		$idfactura = $this->input->post('idfactura');
		$fechafactura = $this->input->post('fechafactura');
		$fechavenc = $this->input->post('fechavenc');
		$vendedor = $this->input->post('vendedor');
		$sucursal = $this->input->post('sucursal');
		$datacliente = json_decode($this->input->post('datacliente'));
		$items = json_decode($this->input->post('items'));
		$neto = $this->input->post('netofactura');
		$iva = $this->input->post('ivafactura');
		$afecto = $this->input->post('afectofactura');
		$total = $this->input->post('totalfacturas');
		$totalant = $this->input->post('totalant');
		$tipodocumento = $this->input->post('tipodocumento');
						
		$factura_cliente = array(

			'sub_total' => $neto,
	        'neto' => $neto,
	        'iva' => $iva,
	        'totalfactura' => $total,
	        
		);

		$this->db->where('id', $idfactura);	  
	    $this->db->update('factura_clientes', $factura_cliente);

	    $query = $this->db->query("SELECT cc.id as idcuentacontable FROM cuenta_contable cc WHERE cc.nombre = 'FACTURAS POR COBRAR'");
		 $row = $query->result();
		 $row = $row[0];
		 $idcuentacontable = $row->idcuentacontable;	


			// VERIFICAR SI CLIENTE YA TIENE CUENTA CORRIENTE
		 $query = $this->db->query("SELECT co.idcliente, co.id as idcuentacorriente  FROM cuenta_corriente co
		 							WHERE co.idcuentacontable = '$idcuentacontable' and co.idcliente = '" . $idcliente . "'");
    	 $row = $query->result();
	
		if ($query->num_rows()>0){	
			
			$row = $row[0];
			$query = $this->db->query("UPDATE cuenta_corriente SET saldo = saldo - " . $totalant . " where id = " .  $row->idcuentacorriente );
			$idcuentacorriente =  $row->idcuentacorriente;

			$query = $this->db->query("UPDATE cuenta_corriente SET saldo = saldo + " . $total . " where id = " .  $row->idcuentacorriente );
			$idcuentacorriente =  $row->idcuentacorriente;
		}

		$query = $this->db->query("UPDATE detalle_cuenta_corriente SET saldo = ".$total." where numdocumento = '".$numfactura."'");
        
        //if ($query->num_rows()>0){

        //	$resp['idctacte'] = $idcuentacorriente;

        //}


		$query = $this->db->query("UPDATE cartola_cuenta_corriente SET valor = ".$total." where numdocumento = '".$numfactura."'");

		//if ($query->num_rows()>0){

        //	$resp['numfactura'] = $numfactura;

        //}
		
			
		/*****************************************/
      

		$this->Bitacora->logger("I", 'factura_clientes', $idfactura);


	    $resp['success'] = true;
		$resp['idfactura'] = $idfactura;
		
		
        echo json_encode($resp);

	}

	public function numerofactura(){

		$resp = array();
		$factura = $this->input->get('factura');
       		
		$query = $this->db->query('SELECT * FROM correlativos WHERE id like "'.$factura.'"');

		if($query->num_rows()>0){
	   			$row = $query->first_row();
	   			$resp['cliente'] = $row;
	   

	        $resp['success'] = true;
	        echo json_encode($resp);

	   }else{
	   	    $resp['success'] = false;
	   	    echo json_encode($resp);
	        return false;
	   }

	
	}

	public function calculofechas(){

		$resp = array();
		$factura = $this->input->post('dias');
		$fechafactura = $this->input->post('fechafactura');

		$fechafactura = substr($fechafactura,0,10);
		$fecha= strtotime("+ $factura days", strtotime ($fechafactura));
		
		$fecha = date('Y-m-d',$fecha);
		
	    $resp['success'] = true;
        $resp['fecha_final'] = $fecha;
        
        echo json_encode($resp);   
	
	}
	
	public function getAll(){
		
		$resp = array();
		$start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $opcion = $this->input->get('opcion');
        $nombres = $this->input->get('nombre');
        $tipo = $this->input->get('documento');
        $bodega = $this->input->get('idbodega');
        if (!$bodega){
	       $bodega = 0;
	    }
        if (!$tipo){
	       $sql_tipo_documento = "";
	    }else{
	       $sql_tipo_documento = "acc.tipo_documento = " . $tipo . " and ";
	    }

        //$countAll = $this->db->count_all_results("factura_clientes");

        $data = array();
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, co.nombre as nombre_docu, v.nombre as nom_vendedor, acc.tipo_documento as id_tip_docu, td.descripcion as tipo_doc	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			left join correlativos co on (acc.tipo_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.estado="" AND ' . $sql_tipo_documento . ' 1 = 1'			
			);
			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;
	
	       if($opcion == "Rut"){
		
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, td.descripcion as tipo_doc, acc.tipo_documento as id_tip_docu	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.estado="" AND ' . $sql_tipo_documento . ' c.rut = "'.$nombres.'"');

			$total = 0;

		  	foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, td.descripcion as tipo_doc,  acc.tipo_documento as id_tip_docu	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.estado="" AND ' . $sql_tipo_documento . ' c.rut = "'.$nombres.'"
			order by acc.id desc
			limit '.$start.', '.$limit.''		 
		);


	    }else if($opcion == "Nombre"){

	    	
			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "c.nombres like '%".$nombre."%' and ";
	        }
	        	    	
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, td.descripcion as tipo_doc,  acc.tipo_documento as id_tip_docu	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.estado="" AND ' . $sql_tipo_documento . '  ' . $sql_nombre . ' 1 = 1'
			);

			$total = 0;

		  	foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, td.descripcion as tipo_doc,  acc.tipo_documento as id_tip_docu FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.estado="" AND ' . $sql_tipo_documento . '  ' . $sql_nombre . ' 1 = 1
			order by acc.id desc
			limit '.$start.', '.$limit.''		 
						
			);
	 
		}else if($opcion == "Todos"){

			
			$data = array();
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, co.nombre as nombre_docu, v.nombre as nom_vendedor, acc.tipo_documento as id_tip_docu, td.descripcion as tipo_doc,  acc.tipo_documento as id_tip_docu FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			left join correlativos co on (acc.tipo_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.estado="" AND ' . $sql_tipo_documento . ' 1 = 1
			order by acc.id desc
			limit '.$start.', '.$limit.''	
			
			);	

		}else if($opcion == "Numero"){
		
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, td.descripcion as tipo_doc,  acc.tipo_documento as id_tip_docu FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.estado="" AND ' . $sql_tipo_documento . ' acc.num_factura = "'.$nombres.'" ');

			$total = 0;

		  	foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, td.descripcion as tipo_doc,  acc.tipo_documento as id_tip_docu FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.estado="" AND ' . $sql_tipo_documento . ' acc.num_factura = "'.$nombres.'" order by acc.id desc
			limit '.$start.', '.$limit.''		 
		);

	    }else{

			
		$data = array();
		$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, td.descripcion as tipo_doc,  acc.tipo_documento as id_tip_docu FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			left join correlativos co on (acc.tipo_documento = co.id)
			WHERE acc.id_bodega='.$bodega.' AND  acc.estado="" AND ' . $sql_tipo_documento . '  1 = 1 
			order by acc.id desc		
			limit '.$start.', '.$limit.''
			);
		}
		
		
		foreach ($query->result() as $row)
		{
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
		    $total = $total +1;
			
		 
			$data[] = $row;
		}

		//$countAll = $total;
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function getAll2(){
		
		$resp = array();
		$start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $opcion = $this->input->get('opcion');
        $nombres = $this->input->get('nombre');
        $bodega = $this->input->get('idbodega');
        $tipo = 101;
        $tipo2 = 2;
        $tipo3 = 19;
        $estado = "";
        if (!$bodega){
	       $bodega = 1;
	    }

        $countAll = $this->db->count_all_results("factura_clientes");
		$data = array();
		$total = 0;
	

        if($opcion == "Rut"){
		
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, c.id_pago as id_pago	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.id_bodega='.$bodega.' AND acc.tipo_documento in ( '.$tipo.','.$tipo2.','.$tipo3.') and c.rut = '.$nombres.'
			order by acc.id desc		
			limit '.$start.', '.$limit.''		 

		);

		$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

	    }else if($opcion == "Nombre"){

	    	
			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "and c.nombres like '%".$nombre."%' ";
	        }
	        	    	
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, c.id_pago as id_pago	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.tipo_documento in ( '.$tipo.','.$tipo2.','.$tipo3.') ' . $sql_nombre . '
			order by acc.id desc		
			limit '.$start.', '.$limit.''
						
			);

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;
	 
		}else if($opcion == "Todos"){

			
			$data = array();
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, co.nombre as nombre_docu, v.nombre as nom_vendedor, acc.tipo_documento as id_tip_docu, c.id_pago as id_pago	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join correlativos co on (acc.tipo_documento = co.id)
			WHERE acc.tipo_documento in ( '.$tipo.','.$tipo2.','.$tipo3.') and acc.estado = "'.$estado.'" order by acc.id desc'	
			
			);


			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;
	

		}else{

			
		$data = array();
		$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, co.nombre as nombre_docu, v.nombre as nom_vendedor, acc.tipo_documento as id_tip_docu, c.id_pago as id_pago	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join correlativos co on (acc.tipo_documento = co.id)
			WHERE acc.tipo_documento in ( '.$tipo.','.$tipo2.','.$tipo3.') and acc.estado = "'.$estado.'" order by acc.id desc		
			limit '.$start.', '.$limit.''	

			);


		}		
		
		foreach ($query->result() as $row)
		{
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
		    $total = $total +1;
			
		 
			$data[] = $row;
		}

		//$countAll = $total;
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function getAllnotap(){
		
		$resp = array();
		$start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $nombre = $this->input->get('nombre');
        $codigo = $this->input->get('codigo');        
        $tipo = "101";



		//$countAll = $this->db->count_all_results("detalle_factura_cliente");
		$data = array();

		if($nombre){

			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.id = '.$nombre.' AND acc.tipo_documento = '.$tipo.' ');

			if($query->num_rows()>0){

		 		$row = $query->first_row();

		 	    $forma = ($row->forma);

		 	    if ($forma==0 or $forma==2){

                $query = $this->db->query('SELECT acc.*, p.nombre as nombre, p.codigo 
                as codigo, acc.precio as p_venta, acc.cantidad as stock 
                FROM detalle_factura_cliente acc    
                left join productos p on (acc.id_producto = p.id)
                WHERE acc.id_factura = "'.$nombre.'" and acc.id_notacredito = 0');
		 	    }else{


                $query = $this->db->query('SELECT acc.*, p.nombre as nombre, p.codigo 
                as codigo, acc.precios as p_venta, acc.cantidad as stock 
                FROM detalle_factura_glosa acc    
                left join productos p on (acc.id_producto = p.id)
                WHERE acc.id_factura = "'.$nombre.'" '
                );


                  // validacion de si es factura de guia
                $array_guia = array();
                foreach ($query->result() as $fila) {
                  if($fila->id_guia != 0){
                    array_push($array_guia,$fila->id_guia);
                  }
                  
                }
                

                // si es factura de guia, va a buscar los productos de las guias
                if(count($array_guia) > 0){
                  $lista_guias = implode(',',$array_guia);
                 // var_dump($lista_guias);
                  /*foreach ($array_guia as $guia) {
                    # code...
                  }*/


                  $query = $this->db->query("SELECT acc.*, p.nombre as nombre, p.codigo 
                                            as codigo, acc.precio as p_venta, acc.cantidad as stock 
                                            FROM detalle_factura_cliente acc    
                                            left join productos p on (acc.id_producto = p.id)
                                            WHERE acc.id_factura in (" . $lista_guias .") and acc.id_notacredito = 0");
                }

		 	        
		 	    }
		 	

		    };
		    
		    $total = count($query->result());


		  /*foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}*/

			$countAll = $total;


		$data = array();
		
		foreach ($query->result() as $row)
		{
			
			$data[] = $row;
		}

/**  hasta aqui ***/


	    }else{

	    	$sql_nombre = "";
	        $arrayNombre =  explode(" ",$codigo);

	        foreach ($arrayNombre as $codigo) {
	        	$sql_nombre .= "acc.nombre like '%".$codigo."%' and ";
	        }

			$query = $this->db->query('SELECT acc.*, c.nombre as nom_ubi_prod, ca.nombre as nom_uni_medida, m.nombre as nom_marca, fa.nombre as nom_familia, bo.nombre as nom_bodega, ag.nombre as nom_agrupacion, sb.nombre as nom_subfamilia,
			acc.id as id_producto FROM productos acc
			left join mae_ubica c on (acc.id_ubi_prod = c.id)
			left join marcas m on (acc.id_marca = m.id)
			left join mae_medida ca on (acc.id_uni_medida = ca.id)
			left join familias fa on (acc.id_familia = fa.id)
			left join agrupacion ag on (acc.id_agrupacion = ag.id)
			left join subfamilias sb on (acc.id_subfamilia = sb.id)
			left join bodegas bo on (acc.id_bodega = bo.id)
			WHERE ' . $sql_nombre . ' 1 = 1');

		
		  $total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;


		$data = array();
		
		foreach ($query->result() as $row)
		{
			
			$data[] = $row;
		}

	    	

	    }
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function getAllfacturasdirectas(){
		
		$resp = array();
		$start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $nombre = $this->input->get('nombre');        
        $tipo = "1";


		$countAll = $this->db->count_all_results("factura_clientes");
		$data = array();

		if($nombre){
		$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.id_cliente = '.$nombre.' AND acc.tipo_documento = '.$tipo.'');

		
		  $total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;


		$data = array();
		
		foreach ($query->result() as $row)
		{
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


		 
			$data[] = $row;
		}

	    }
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

    
	public function getAlldespachafactura(){
		
		$resp = array();
		$start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $nombre = $this->input->get('nombre');        
        $tipo = "1";


		$countAll = $this->db->count_all_results("factura_clientes");
		$data = array();

		if($nombre){
		$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.id_cliente = '.$nombre.' AND acc.tipo_documento = '.$tipo.'
			limit '.$start.', '.$limit.' ' 

		);

		
		  $total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;


		$data = array();
		
		foreach ($query->result() as $row)
		{
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


		 
			$data[] = $row;
		}

	    }
        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function getAllfact(){
		
		$resp = array();
		$start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $nombre = $this->input->get('nombre');
        $numero = $this->input->get('numero');        
        $tipo = "101";


		$countAll = $this->db->count_all_results("factura_clientes");
		$data = array();

		if($nombre){
		$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.id_cliente = '.$nombre.' AND acc.tipo_documento = '.$tipo.'' );

		
		  $total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			foreach ($query->result() as $row)
		{
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


		 
			$data[] = $row;
		}

		}if($numero){
		$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.num_factura = '.$numero.' AND acc.tipo_documento = '.$tipo.'' );

		
		  $total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

			foreach ($query->result() as $row)
		{
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
		 
			$data[] = $row;
		}

		}


	    $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function savecompra(){
		
		$resp = array();
		$idcliente = $this->input->post('idcliente');
		$numfactura = $this->input->post('numfactura');
		$idfactura = $this->input->post('idfactura');
		$idbodega = $this->input->post('idbodega');
		$documentoref = $this->input->post('documento');
		$fechafactura = $this->input->post('fechafactura');
		$fechavenc = $this->input->post('fechavenc');
		$vendedor = $this->input->post('vendedor');
		$ordencompra = $this->input->post('ordencompra');
		$sucursal = $this->input->post('sucursal');
		$datacliente = json_decode($this->input->post('datacliente'));
		$items = json_decode($this->input->post('items'));
		$neto = $this->input->post('netofactura');
		$formadepago = $this->input->post('formadepago');
		$fiva11 = $this->input->post('ivafactura11');
		$fiva8 = $this->input->post('ivafactura8');
		$fiva = $fiva11 + $fiva8;
		$fafecto = $this->input->post('afectofactura');
		$ftotal = $this->input->post('totalfacturas');
		$tipodocumento = $this->input->post('tipodocumento');
		
		$data3 = array(
	         'correlativo' => $numfactura
	    );
	    $this->db->where('id', $tipodocumento);
	  
	    $this->db->update('correlativos', $data3);
			
		$factura_cliente = array(
			'tipo_documento' => $tipodocumento,
			'id_bodega' => $idbodega,
	        'id_cliente' => $idcliente,
	        'num_factura' => $numfactura,
	        'id_vendedor' => $vendedor,
	        'id_sucursal' => $sucursal,
	        'id_cond_venta' => $formadepago,
	        'sub_total' => $neto,
	        'descuento' => ($neto - $fafecto),
	        'neto' => $neto,
	        'iva' => $fiva,
	        'iva8' => $fiva8,
	        'iva11' => $fiva11,
	        'totalfactura' => $ftotal,
	        'fecha_factura' => $fechafactura,
	        'fecha_venc' => $fechavenc,
	        'orden_compra' => $ordencompra,
	        'forma' => 4,
	        'documref' => $documentoref
	          
		);

		$this->db->insert('factura_clientes', $factura_cliente); 
		$idfactura = $this->db->insert_id();

		foreach($items as $v){
			
			$factura_clientes_item = array(
		        'id_producto' => $v->id_producto,
		        'id_factura' => $idfactura,
		        'num_factura' => $numfactura,
		        'precio' => $v->precio,
		        'p_promedio' => $v->precio,
		        'cantidad' => $v->cantidad,
		        'neto' => ($v->total - $v->iva),
		        'iva' => $v->iva,
		        'totalproducto' => $v->total,
		        'fecha' => $fechafactura
			);

		$producto = $v->id_producto;

		$this->db->insert('detalle_factura_cliente', $factura_clientes_item);
		
		$query = $this->db->query('SELECT * FROM productos WHERE id="'.$producto.'"');
		 if($query->num_rows()>0){
		 	$row = $query->first_row();
		 	$saldo = ($row->stock)-($v->cantidad);
		 };

		 $query = $this->db->query('SELECT * FROM existencia WHERE id_producto='.$producto.' and id_bodega='.$idbodega.'');
    	 $row = $query->result();
			if ($query->num_rows()>0){
				$row = $row[0];	 
		        if ($producto==($row->id_producto) and $idbodega==($row->id_bodega)){
				    $datos3 = array(
					'stock' => $saldo,
			        'fecha_ultimo_movimiento' => $fechafactura
					);

					$this->db->where('id_producto', $producto);
		    	    $this->db->update('existencia', $datos3);
	    	    }else{
    	    	$datos3 = array(
				'id_producto' => $producto,
		        'stock' =>  $saldo,
		        'fecha_ultimo_movimiento' =>$fechafactura,
		        'id_bodega' => $idbodega				
				);
				$this->db->insert('existencia', $datos3);
	    	 	}
			}else{
    	    	$datos3 = array(
				'id_producto' => $producto,
		        'stock' =>  $saldo,
		        'fecha_ultimo_movimiento' =>$fechafactura,
		        'id_bodega' => $idbodega			
				);
				$this->db->insert('existencia', $datos3);
		    }

		    $datos2 = array(
				'num_movimiento' => $numfactura,
		        'id_producto' => $v->id_producto,
		        'id_tipo_movimiento' => $tipodocumento,
		        'valor_producto' =>  $v->precio,
		        'cantidad_salida' => $v->cantidad,
		        'fecha_movimiento' => $fechafactura,
		        'id_bodega' => $idbodega,
		        'id_cliente' => $idcliente,
		        'p_promedio' => $v->precio
		);

		$this->db->insert('existencia_detalle', $datos2);

		$datos = array(
         'stock' => $saldo,
    	);

    	$this->db->where('id', $producto);

    	$this->db->update('productos', $datos);

		}

			
		if ($tipodocumento != 105){


		/******* CUENTAS CORRIENTES ****/

		 $query = $this->db->query("SELECT cc.id as idcuentacontable FROM cuenta_contable cc WHERE cc.nombre = 'FACTURAS POR COBRAR'");
		 $row = $query->result();
		 $row = $row[0];
		 $idcuentacontable = $row->idcuentacontable;	


			// VERIFICAR SI CLIENTE YA TIENE CUENTA CORRIENTE
		 $query = $this->db->query("SELECT co.idcliente, co.id as idcuentacorriente,  co.saldo as saldo FROM cuenta_corriente co
		 							WHERE co.idcuentacontable = '$idcuentacontable' and co.idcliente = '" . $idcliente . "'");
    	 $row = $query->result();
	
		if ($query->num_rows()==0){	
			$cuenta_corriente = array(
		        'idcliente' => $idcliente,
		        'idcuentacontable' => $idcuentacontable,
		        'saldo' => $ftotal,
		        'fechaactualiza' => $fechafactura
			);
			$this->db->insert('cuenta_corriente', $cuenta_corriente); 
			$idcuentacorriente = $this->db->insert_id();

            $sadoctacte = array(
             'cred_util' => $ftotal
            );
            $this->db->where('id', $idcliente);

            $this->db->update('clientes', $sadoctacte);

		}else{
			$row = $row[0];
            $saldoctacte=$row->saldo;
			$query = $this->db->query("UPDATE cuenta_corriente SET saldo = saldo + " . $ftotal . " where id = " .  $row->idcuentacorriente );
			$idcuentacorriente =  $row->idcuentacorriente;

            $saldoctacte=$saldoctacte + $ftotal;

            $sadoctacte = array(
             'cred_util' => $ftotal
            );
            $this->db->where('id', $idcliente);

            $this->db->update('clientes', $sadoctacte);
		}

		$detalle_cuenta_corriente = array(
	        'idctacte' => $idcuentacorriente,
	        'tipodocumento' => $tipodocumento,
	        'numdocumento' => $numfactura,
	        'saldoinicial' => $ftotal,
	        'saldo' => $ftotal,
	        'fechavencimiento' => $fechavenc,
	        'fecha' => $fechafactura
		);

		$this->db->insert('detalle_cuenta_corriente', $detalle_cuenta_corriente);

		$cartola_cuenta_corriente = array(
	        'idctacte' => $idcuentacorriente,
	        'idcuenta' => $idcuentacontable,
	        'tipodocumento' => $tipodocumento,
	        'numdocumento' => $numfactura,
	        'glosa' => 'Registro de Factura en Cuenta Corriente',
	        'fecvencimiento' => $fechavenc,
	        'valor' => $ftotal,
	        'origen' => 'VENTA',
	        'fecha' => $fechafactura
		);

		$this->db->insert('cartola_cuenta_corriente', $cartola_cuenta_corriente); 			

		/*****************************************/
      

		$this->Bitacora->logger("I", 'factura_clientes', $idfactura);

		};	

		
		$resp['success'] = true;
		$resp['idfactura'] = $idfactura;
        echo json_encode($resp);
	}


	public function save(){
		
		$resp = array();
		$idcliente = $this->input->post('idcliente');
		$numfactura = $this->input->post('numfactura');
		$idfactura = $this->input->post('idfactura');
		$idbodega = $this->input->post('idbodega');
		$fechafactura = $this->input->post('fechafactura');
		$fechavenc = $this->input->post('fechavenc');
		$vendedor = $this->input->post('vendedor');
		$ordencompra = $this->input->post('ordencompra');
    $pedido = $this->input->post('pedido');
    $idtransportista = $this->input->post('idtransportista');
		$sucursal = $this->input->post('sucursal');
		$datacliente = json_decode($this->input->post('datacliente'));
		$items = json_decode($this->input->post('items'));
		$neto = $this->input->post('netofactura');
		$formadepago = $this->input->post('formadepago');
		$fiva = $this->input->post('ivafactura');
		$fafecto = $this->input->post('afectofactura');
		$ftotal = $this->input->post('totalfacturas');
		$tipodocumento = $this->input->post('tipodocumento');
    $observacion = $this->input->post('observacion');
    $idobserva = $this->input->post('idobserva');

    if(!$observacion){
    $observacion="";
    };

    if($idtransportista){
    $query = $this->db->query('SELECT * FROM transportistas WHERE id like "'.$idtransportista.'"');
    if($query->num_rows()>0){
    $row = $query->first_row();
    $transportista = ($row->nombre);        
    };
    }else{
    $idtransportista="";
    $transportista="";
    };
    if(!$idobserva){
      $idobserva="";
    };		
    $data3 = array(
     'correlativo' => $numfactura
    );
    $this->db->where('id', $tipodocumento);
    $this->db->update('correlativos', $data3);

		$factura_cliente = array(
			'tipo_documento' => $tipodocumento,
			'id_bodega' => $idbodega,
      'id_cliente' => $idcliente,
      'num_factura' => $numfactura,
      'id_vendedor' => $vendedor,
      'id_sucursal' => $sucursal,
      'id_cond_venta' => $formadepago,
      'sub_total' => $neto,
      'descuento' => ($neto - $fafecto),
      'neto' => $neto,
      'iva' => $fiva,
      'totalfactura' => $ftotal,
      'fecha_factura' => $fechafactura,
      'fecha_venc' => $fechavenc,
      'orden_compra' => $ordencompra,
      'num_pedido' => $pedido,
      'id_observa' => $idobserva,
      'observacion' => $observacion	          
		);
		$this->db->insert('factura_clientes', $factura_cliente); 
		$idfactura = $this->db->insert_id();

    $data8 = array(
     'id_documento' => $idfactura
    );
    $this->db->where('id', $idobserva);

    $this->db->update('observacion_facturas', $data8);

		foreach($items as $v){

    if (!$v->fecha_vencimiento){
      $v->fecha_vencimiento="0000-00-00";
    };
    $factura_clientes_item = array(
      'id_producto' => $v->id_producto,
      'nombre' => strtoupper($v->nombre),
      'id_existencia' => $v->id_existencia,
      'id_factura' => $idfactura,
      'num_factura' => $numfactura,
      'precio' => $v->precio,
      'cantidad' => $v->cantidad,
      'neto' => ($v->total - $v->iva),
      'descuento' => $v->dcto,
      'iva' => $v->iva,
      'totalproducto' => $v->total,
      'fecha' => $fechafactura,
      'fecha_vencimiento' => $v->fecha_vencimiento,
      'lote' => $v->lote,
    );
		$producto = $v->id_producto;
		$this->db->insert('detalle_factura_cliente', $factura_clientes_item);		
		$query = $this->db->query('SELECT * FROM productos WHERE id="'.$producto.'"');
    if($query->num_rows()>0){
    	$row = $query->first_row();
    	$saldo = ($row->stock)-($v->cantidad);
    }; 
		$datos2 = array(
      'num_movimiento' => $numfactura,
      'id_producto' => $v->id_producto,
      'id_tipo_movimiento' => $tipodocumento,
      'valor_producto' =>  $v->precio,
      'cantidad_salida' => $v->cantidad,
      'fecha_movimiento' => $fechafactura,
      'fecha_vencimiento' => $v->fecha_vencimiento,
      'lote' => $v->lote,
      'id_bodega' => $idbodega,
      'id_cliente' => $idcliente,
      'p_promedio' => $v->p_promedio,
      'id_transportista' => $idtransportista,
      'transportista' => $transportista
		);
		$this->db->insert('existencia_detalle', $datos2);
		};	
    		
		if ($tipodocumento != 105){		/******* CUENTAS CORRIENTES ****/
		 $query = $this->db->query("SELECT cc.id as idcuentacontable FROM cuenta_contable cc WHERE cc.nombre = 'FACTURAS POR COBRAR'");
		 $row = $query->result();
		 $row = $row[0];
		 $idcuentacontable = $row->idcuentacontable;
			// VERIFICAR SI CLIENTE YA TIENE CUENTA CORRIENTE
      $query = $this->db->query("SELECT co.idcliente, co.id as idcuentacorriente, co.saldo as saldo FROM cuenta_corriente co
      WHERE co.idcuentacontable = '$idcuentacontable' and co.idcliente = '" . $idcliente . "'");

      $row = $query->result();	
      if ($query->num_rows()==0){	
      	$cuenta_corriente = array(
              'idcliente' => $idcliente,
              'idcuentacontable' => $idcuentacontable,
              'saldo' => $ftotal,
              'fechaactualiza' => $fechafactura
      	);
      	$this->db->insert('cuenta_corriente', $cuenta_corriente); 
      	$idcuentacorriente = $this->db->insert_id();
              $sadoctacte = array(
               'cred_util' => $ftotal
              );
              $this->db->where('id', $idcliente);
              $this->db->update('clientes', $sadoctacte);
      }else{
			$row = $row[0];
      $saldoctacte=$row->saldo;
			$query = $this->db->query("UPDATE cuenta_corriente SET saldo = saldo + " . $ftotal . " where id = " .  $row->idcuentacorriente );
			$idcuentacorriente =  $row->idcuentacorriente;
      $saldoctacte=$saldoctacte + $ftotal;
      $sadoctacte = array(
       'cred_util' => $saldoctacte
      );
      $this->db->where('id', $idcliente);
      $this->db->update('clientes', $sadoctacte);

  		};

      $detalle_cuenta_corriente = array(
        'idctacte' => $idcuentacorriente,
        'tipodocumento' => $tipodocumento,
        'numdocumento' => $numfactura,
        'saldoinicial' => $ftotal,
        'saldo' => $ftotal,
        'fechavencimiento' => $fechavenc,
        'fecha' => $fechafactura
      );
      $this->db->insert('detalle_cuenta_corriente', $detalle_cuenta_corriente);
      $cartola_cuenta_corriente = array(
        'idctacte' => $idcuentacorriente,
        'idcuenta' => $idcuentacontable,
        'tipodocumento' => $tipodocumento,
        'numdocumento' => $numfactura,
        'glosa' => 'Registro de Factura en Cuenta Corriente',
        'fecvencimiento' => $fechavenc,
        'valor' => $ftotal,
        'origen' => 'VENTA',
        'fecha' => $fechafactura
      );

      $this->db->insert('cartola_cuenta_corriente', $cartola_cuenta_corriente); 	 

      }

      /*****************************************/
        

        if($tipodocumento == 101 || $tipodocumento == 103 || $tipodocumento == 105 || $tipodocumento == 120){  // SI ES FACTURA ELECTRONICA O FACTURA EXENTA ELECTRONICA O BOLETA ELECTRONICA



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
                  $config = $this->facturaelectronica->genera_config();
                  include $this->facturaelectronica->ruta_libredte();


                  $empresa = $this->facturaelectronica->get_empresa();
                  $datos_empresa_factura = $this->facturaelectronica->get_empresa_factura($idfactura);

                  $detalle_factura = $this->facturaelectronica->get_detalle_factura($idfactura);
                  $datos_factura = $this->facturaelectronica->get_factura($idfactura);

                  $referencia = array();
                  $NroLinRef = 1;
                  if($ordencompra != ""){
                        $referencia[($NroLinRef-1)]['NroLinRef'] = $NroLinRef;
                        //$referencia['TpoDocRef'] = $datos_empresa_factura->tipodocref;
                        $referencia[($NroLinRef-1)]['TpoDocRef'] = 801;
                        $referencia[($NroLinRef-1)]['FolioRef'] = $ordencompra;
                        $referencia[($NroLinRef-1)]['FchRef'] = substr($fechafactura,0,10);
                        $NroLinRef++;
                  }

                  if($pedido != ""){
                        $referencia[($NroLinRef-1)]['NroLinRef'] = $NroLinRef;
                        //$referencia['TpoDocRef'] = $datos_empresa_factura->tipodocref;
                        $referencia[($NroLinRef-1)]['TpoDocRef'] = 802;
                        $referencia[($NroLinRef-1)]['FolioRef'] = $pedido;
                        $referencia[($NroLinRef-1)]['FchRef'] = substr($fechafactura,0,10);
                        $NroLinRef++;
                  }
                  

                  $lista_detalle = array();
                  $i = 0;
                  foreach ($detalle_factura as $detalle) {
                        $lista_detalle[$i]['NmbItem'] = $tipo_caf == 39 ? $detalle->nombre : $detalle->nombre." VENC ".substr($detalle->fecha_vencimiento,8,2)."/".substr($detalle->fecha_vencimiento,5,2)."/".substr($detalle->fecha_vencimiento,0,4);
                        $lista_detalle[$i]['QtyItem'] = $detalle->cantidad;
                        $lista_detalle[$i]['CdgItem'] = $detalle->codigo;
                        $lista_detalle[$i]['UnmdItem'] = $detalle->lote;
                        //$lista_detalle[$i]['PrcItem'] = $detalle->precio;
                        //$lista_detalle[$i]['PrcItem'] = round((($detalle->precio*$detalle->cantidad)/1.19)/$detalle->cantidad,0);
                        //$total = $detalle->precio*$detalle->cantidad;
                        //$neto = round($total/1.19,2);
                       $lista_detalle[$i]['PrcItem'] = $tipo_caf == 39 ? floor($detalle->totalproducto/$detalle->cantidad) : floor($detalle->precio); 
                       // $lista_detalle[$i]['PrcItem'] = $detalle->precio;
                       // $lista_detalle[$i]['PrcItem'] = $tipo_caf == 33 || $tipo_caf == 52 ? floor($detalle->neto) : floor($detalle->totalproducto);

                        $i++;
                  }

                $rutCliente = substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1)."-".substr($datos_empresa_factura->rut_cliente,-1);

                $dir_cliente = is_null($datos_empresa_factura->dir_sucursal) ? permite_alfanumerico($datos_empresa_factura->direccion) : permite_alfanumerico($datos_empresa_factura->dir_sucursal);

                $nombre_comuna = is_null($datos_empresa_factura->com_sucursal) ? permite_alfanumerico($datos_empresa_factura->nombre_comuna) : permite_alfanumerico($datos_empresa_factura->com_sucursal);

                if($tipo_caf == 39){
                          $factura = [
                            // CASO 1
                                'Encabezado' => [
                                    'IdDoc' => [
                                       'TipoDTE' => $tipo_caf,
                                        'Folio' => $numfactura,
                                        'FchEmis' => substr($fechafactura,0,10),
                                    ],
                                     'Emisor' => [
                                          'RUTEmisor' => $empresa->rut.'-'.$empresa->dv,
                                          'RznSoc' => substr(permite_alfanumerico($empresa->razon_social),0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES,
                                          'GiroEmis' => substr(permite_alfanumerico($empresa->giro),0,80), //LARGO DE GIRO DEL EMISOR NO PUEDE SER SUPERIOR A 80 CARACTERES
                                          'Acteco' => $empresa->cod_actividad,
                                          'DirOrigen' =>  substr(permite_alfanumerico($empresa->dir_origen),0,70), //LARGO DE DIRECCION DE ORIGEN NO PUEDE SER SUPERIOR A 70 CARACTERES
                                          'CmnaOrigen' => substr(permite_alfanumerico($empresa->comuna_origen),0,20), //LARGO DE COMUNA DE ORIGEN NO PUEDE SER SUPERIOR A 20 CARACTERES
                                      ],
                                   'Receptor' => [
                                            'RUTRecep' => $rutCliente,
                                            'RznSocRecep' =>  substr(permite_alfanumerico($datos_empresa_factura->nombre_cliente),0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
                                            'GiroRecep' => substr(permite_alfanumerico($datos_empresa_factura->giro),0,40),  //LARGO DEL GIRO NO PUEDE SER SUPERIOR A 40 CARACTERES
                                            'DirRecep' => substr($dir_cliente,0,70), //LARGO DE DIRECCION NO PUEDE SER SUPERIOR A 70 CARACTERES
                                            'CmnaRecep' => substr($nombre_comuna,0,20), //LARGO DE COMUNA NO PUEDE SER SUPERIOR A 20 CARACTERES
                                        ],
                                ],
                                'Detalle' => $lista_detalle,
                        ];



                }else{

                        $factura = [
                                'Encabezado' => [
                                    'IdDoc' => [
                                        'TipoDTE' => $tipo_caf,
                                        'Folio' => $numfactura,
                                        'FchEmis' => substr($fechafactura,0,10)
                                        // 'TpoTranVenta' => 4
                                    ],
                                    'Emisor' => [
                                        'RUTEmisor' => $empresa->rut.'-'.$empresa->dv,
                                        'RznSoc' => substr(permite_alfanumerico($empresa->razon_social),0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES,
                                        'GiroEmis' => substr(permite_alfanumerico($empresa->giro),0,80), //LARGO DE GIRO DEL EMISOR NO PUEDE SER SUPERIOR A 80 CARACTERES
                                        'Acteco' => $empresa->cod_actividad,
                                        'DirOrigen' =>  substr(permite_alfanumerico($empresa->dir_origen),0,70), //LARGO DE DIRECCION DE ORIGEN NO PUEDE SER SUPERIOR A 70 CARACTERES
                                        'CmnaOrigen' => substr(permite_alfanumerico($empresa->comuna_origen),0,20), //LARGO DE COMUNA DE ORIGEN NO PUEDE SER SUPERIOR A 20 CARACTERES
                                    ],
                                    'Receptor' => [
                                        'RUTRecep' => $rutCliente,
                                        'RznSocRecep' =>  substr(permite_alfanumerico($datos_empresa_factura->nombre_cliente),0,100), //LARGO DE RAZON SOCIAL NO PUEDE SER SUPERIOR A 100 CARACTERES
                                        'GiroRecep' => substr(permite_alfanumerico($datos_empresa_factura->giro),0,40),  //LARGO DEL GIRO NO PUEDE SER SUPERIOR A 40 CARACTERES
                                        'DirRecep' => substr($dir_cliente,0,70), //LARGO DE DIRECCION NO PUEDE SER SUPERIOR A 70 CARACTERES
                                        'CmnaRecep' => substr($nombre_comuna,0,20), //LARGO DE COMUNA NO PUEDE SER SUPERIOR A 20 CARACTERES
                                    ],
                                  'Totales' => [
                                      // estos valores serán calculados automáticamente
                                      'MntNeto' => isset($datos_factura->neto) ? $datos_factura->neto : 0,
                                      //'TasaIVA' => \sasco\LibreDTE\Sii::getIVA(),
                                      'IVA' => isset($datos_factura->iva) ? $datos_factura->iva : 0,
                                      'MntTotal' => isset($datos_factura->totalfactura) ? $datos_factura->totalfactura : 0,
                                  ],                        
                                ],
                                  'Detalle' => $lista_detalle,
                                  'Referencia' => $referencia
                            ];


                }

                

                  // datos
                  


                  //FchResol y NroResol deben cambiar con los datos reales de producción
                  $caratula = [
                      //'RutEnvia' => '11222333-4', // se obtiene de la firma
                      'RutReceptor' => '60803000-K',
                      'FchResol' => $empresa->fec_resolucion,
                      'NroResol' => $empresa->nro_resolucion
                  ];

                  $caratula_cliente = [
                      //'RutEnvia' => '11222333-4', // se obtiene de la firma
                      'RutReceptor' => $rutCliente,
                      'FchResol' => $empresa->fec_resolucion,
                      'NroResol' => $empresa->nro_resolucion
                  ];  


                                                
                  //exit;
                  // Objetos de Firma y Folios
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
                  $xml_dte = $EnvioDTE->generar();
               /*   echo $xml_dte;
                 var_dump($EnvioDTE->schemaValidate()); 

  foreach (sasco\LibreDTE\Log::readAll() as $error)
          echo $error,"\n";                  
                  

                  exit;*/


                 if ($EnvioDTE->schemaValidate()) { // REVISAR PORQUÉ SE CAE CON ESTA VALIDACION
                        
                        $track_id = 0;
                      $xml_dte = $EnvioDTE->generar();

                    #GENERACIÓN DTE CLIENTE
                    $EnvioDTE_CLI = new \sasco\LibreDTE\Sii\EnvioDte();
                    $EnvioDTE_CLI->agregar($DTE);
                    $EnvioDTE_CLI->setFirma($Firma);
                    $EnvioDTE_CLI->setCaratula($caratula_cliente);
                    $xml_dte_cliente = $EnvioDTE_CLI->generar();                      
                      //$track_id = $EnvioDTE->enviar();
                      $tipo_envio = $this->facturaelectronica->busca_parametro_fe('envio_sii'); //ver si está configurado para envío manual o automático

                    $dte = $this->facturaelectronica->crea_archivo_dte($xml_dte,$idfactura,$tipo_caf,'sii');
                    $dte_cliente = $this->facturaelectronica->crea_archivo_dte($xml_dte_cliente,$idfactura,$tipo_caf,'cliente');


                      /*  $nombre_dte = $numfactura."_". $tipo_caf ."_".$idfactura."_".date("His").".xml"; // nombre archivo
                        $path = date('Ym').'/'; // ruta guardado
                        if(!file_exists('./facturacion_electronica/dte/'.$path)){
                              mkdir('./facturacion_electronica/dte/'.$path,0777,true);
                        }                       
                        $f_archivo = fopen('./facturacion_electronica/dte/'.$path.$nombre_dte,'w');
                        fwrite($f_archivo,$xml_dte);
                        fclose($f_archivo);
*/
                    /*  if($tipo_envio == 'automatico'){
                            $track_id = $EnvioDTE->enviar();
                      }*/



                              $this->db->where('f.folio', $numfactura);
                              $this->db->where('c.tipo_caf', $tipo_caf);
                              $this->db->update('folios_caf f inner join caf c on f.idcaf = c.id',array('dte' => $dte['xml_dte'],
                                                                  'dte_cliente' => $dte_cliente['xml_dte'],
                                                                  'estado' => 'O',
                                                                  'idfactura' => $idfactura,
                                                                  'path_dte' => $dte['path'],
                                                                  'archivo_dte' => $dte['nombre_dte'],
                                                                  'archivo_dte_cliente' => $dte_cliente['nombre_dte'],
                                                                  'trackid' => $track_id
                                                                  )); 


                        if($track_id != 0 && $datos_empresa_factura->e_mail != ''){ //existe track id, se envía correo
                            //  $this->facturaelectronica->envio_mail_dte($idfactura);
                        }




                  }

                  /***********************************************/


            } 
           
      //$this->ver_dte->($idfactura,"sii"); 

    
        
		$resp['success'] = true;
		$resp['idfactura'] = $idfactura;
        echo json_encode($resp);
	}

	public function crearespacios($cantidad){
		$sps = "";
		for($i=0;$i<=$cantidad;$i++){
			$sps.=" ";
		}
		return $sps;
	}

	public function exportTXTNCGLO(){

		/**************************exporta txt*******/
		$idfactura = $this->input->get('idfactura');
		/*header("Content-Type: application/force-download");
        header("Content-Transfer-Encoding: binary");
        header("Content-disposition: attachment; filename=facturacion.txt");*/

      $file_content = "";         
      $data = array();
			$query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, ob.pat_camion as patente, ob.pat_carro as carro, c.fono, cp.nombre as cond_pago, cp.codigo as codigo_con_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, cor.nombre as nomdocumento, ma.nombre as com_sucursal, v.cod_interno as cod_interno FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join correlativos cor on (acc.tipo_documento = cor.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.id = '.$idfactura.'');

			$row = $query->result();

        
        if ($query->num_rows()>0){	

        	$v = $row[0];
			        
            $nomdocumento = $v->nomdocumento;
            $fechafactura = $v->fecha_factura;
            $fechavenc = $v->fecha_venc;
            $idbodega = $v->id_bodega;

            $numordencompra= $v->orden_compra;

            $nomobserv = $v->nom_observ;
            $patente = $v->patente." ".$v->carro;
            
            $fecha = $v->fecha_factura;
			list($anio, $mes, $dia) = explode("-",$fecha);
			$fecha2 = $v->fecha_venc;
			list($anio2, $mes2, $dia2) = explode("-",$fecha2);

			$fechafactura = $dia."/".$mes."/".$anio;
            $fechavenc = $dia2."/".$mes2."/".$anio2;
			
			          
            $numdocumento = $v->num_factura;            
            $nomcliente = substr($v->nombre_cliente, 0, 40);
            $condventa = $v->cond_pago;
            $codcondventa = substr($v->codigo_con_pago, -2);
            $vendedor = $v->nom_vendedor;
            $codvendedor = $v->id_vendedor;
            $docrefer = $v->id_factura;         
            if ($v->ciu_sucursal){
            	$ciudad= $v->ciu_sucursal;            	
            }else{
            	$ciudad= $v->nombre_ciudad;
            };
            if ($v->com_sucursal){
            	$comuna= $v->com_sucursal;            	
            }else{
            	$comuna= $v->nombre_comuna;
            };
            $giro = substr($v->giro, 0, 42);
            $valornetocom = 18;
            $emisora ="";
            $sucsii = "";
            $codinterno=$v->cod_interno;
            $codinterno2="";
            $codImpuestoadic1="";
            $tasaImpadic1="";
            $valImpadic1="";
            $codImpuestoadic2="";
            $tasaImpadic2="";
            $valImpadic2="";
            $codImpuestoadic3="";
            $tasaImpadic3="";
            $valImpadic3="";
            $credespecial="";
            $montoperiodo="";
            $montototal="";
            $valordesc1 = "";
            $valordesc2 = "";
            $valordesc3 = "";
            $espaciosfin= "";
            $contactoreceptor="";
            if($v->direc_sucursal){
            	$direccionreceptor=$v->direc_sucursal;            	
            }else{
                $direccionreceptor=$v->direccion;
            };
            if($v->direc_sucursal){
            	$direcciondespacho=$v->direc_sucursal;            	
            }else{
                $direcciondespacho=$v->direccion;
            };
            $personalizadosl3="";
            $personalizadosl4="";
            $patentetrans="";
            $comunadespacho="";
            $ciudaddespacho="";
            $ruttransporte="";
            $neto = intval($v->neto);
            $exento="";
            $pordescuento="";
            $iva = intval($v->iva);            
            $total = intval($v->totalfactura);
            $id = $v->id;
            $espaciost = 25;
            $pregistro="";
		    $espacios20= 19;
		    $espacios5= 4;
		    $espacios2= 1;
		    $espacios8= 7;
		    $espacios9= 8;
		    $espacios35= 34;
		    $espacios40= 39;
		    $espacios42= 41;
		    $espacios60= 59;
		    $espacios80= 79;
		    $espacios300= 299;
		    $espacio300="";
		    $espacios30= 29;
		    $espacios110= 109;
		    $espacios139= 138;
		    $espaciolargo= 299;
		    $totalletras = (valorEnLetras($total));
		    $nomclienteinicio="                     ";
		    $espacios21= 20;
		    $espacios25= 24;

		    $rutautoriza = $v->rut_cliente;
		   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		   
		    };
		    if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $v->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };

		    $rutcliente = $v->rut_cliente;
		    if (strlen($rutcliente) == 9){
		    	$rutcliente=("0".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 8){
		    	$rutcliente=("00".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 7){
		    	$rutcliente=("000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 6){
		    	$rutcliente=("0000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 5){
		    	$rutcliente=("00000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 4){
		    	$rutcliente=("000000".$rutcliente);
		    	
		    };
		   
		    //$file_content .= $idbodega.$this->crearespacios($espacios21 - strlen( $idbodega));
            $file_content .= "                     ";
            $file_content .= $condventa.$this->crearespacios($espacios25 - strlen( $condventa));  //Nombre condicion Pago
            $file_content .= $vendedor.$this->crearespacios($espacios30 - strlen( $vendedor));  //LINEA 1
            $file_content .= chr(13).chr(10);
            $file_content .= chr(13).chr(10);
            $file_content .= " "; //espacio
            $file_content .= ";";
            $file_content .= str_pad($numdocumento,10," ",STR_PAD_LEFT); // Folio
            $file_content .= ";"; //
            $file_content .= $fechafactura;  //fecha Emision
            $file_content .= ";";
            $file_content .= " "; //indicador de no rebeja
            $file_content .= ";";
            $file_content .= " ";// tipo despacho
            $file_content .= ";";
            $file_content .= "0";// tipo de traslado
            $file_content .= ";";
            $file_content .= " ";// indicador servicio periodico
            $file_content .= ";";
            $file_content .= " ";// indicador montos brutos
            $file_content .= ";";
            $file_content .= "2";// DEBE SELECIONAR 1 O 2 SECUN TIPO DE PAGO
            $file_content .= ";";
            $file_content .= $fechavenc;
            $file_content .= ";";
            $file_content .= $emisora.$this->crearespacios($espacios20 - strlen($emisora));  //sucursal emision
            $file_content .= ";";
            $file_content .= $sucsii.$this->crearespacios($espacios9 - strlen( $sucsii));  //sucursal sii
            $file_content .= ";";
            $file_content .= $codinterno; // codigo vendedor
            $file_content .= ";";   
            $file_content .= $rutcliente; // rut receptor
            $file_content .= ";";
            $file_content .= "          "; // 10 ESPACIOS 
            $file_content .= ";";
            $file_content .= "                    "; // 20 ESPACIOS
            $file_content .= ";";
            $file_content .= $nomcliente.$this->crearespacios($espacios40 - strlen( $nomcliente));  //razon social
            $file_content .= ";";
            $file_content .= $giro.$this->crearespacios($espacios42 - strlen( $giro)); //Giro receptor
            $file_content .= ";"; 
            $file_content .= $contactoreceptor.$this->crearespacios($espacios30 - strlen( $contactoreceptor)); // Contacto receptor
            $file_content .= ";";
            $file_content .= $direccionreceptor.$this->crearespacios($espacios60 - strlen( $direccionreceptor)); // Direccion receptor
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios30 - strlen( $personalizadosl3));
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios40 - strlen( $personalizadosl3));
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios40 - strlen( $personalizadosl3));// Filea 3
            $file_content .= chr(13).chr(10);
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad
            $file_content .= ";";
            $file_content .= $direcciondespacho.$this->crearespacios($espacios60 - strlen( $direcciondespacho)); // Direccion Despacho
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna Despacho
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad Despacho
            $file_content .= ";";
            $file_content .= $patentetrans.$this->crearespacios($espacios8 - strlen( $patentetrans)); //Patente Transporte
            $file_content .= ";";
            $file_content .= str_pad($ruttransporte,10," ",STR_PAD_LEFT); // rut Transportista
            $file_content .= ";";
            $file_content .= str_pad($neto,18," ",STR_PAD_LEFT); // Monto Neto
            $file_content .= ";";
            $file_content .= str_pad($exento,18," ",STR_PAD_LEFT); // Monto Exento
            $file_content .= ";";
            $file_content .= "     ";// 5 ESPACIOS Tasa Iva
            $file_content .= ";";
            $file_content .= str_pad($iva,18," ",STR_PAD_LEFT); // Monto Iva
            $file_content .= ";";
            $file_content .= "      "; // 6 espacio
            $file_content .= ";";
            $file_content .= "  "; // 2 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= str_pad($total,18," ",STR_PAD_LEFT); //Valor descuento 1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= chr(13).chr(10); // linea 4 fin
                        
            $query2 = $this->db->get_where('detalle_factura_glosa', array('id_factura' => $id));

            $c=0;

            foreach ($query2->result() as $z){

			$c= $c+1;
				
            //$file_content .= chr(13).chr(10);
            $producto=" ";
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $producto.$this->crearespacios($espacios35 - strlen( $producto));  //razon social
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= $z->glosa.$this->crearespacios($espacios80 - strlen( $z->glosa));  //razon social
            $file_content .= ";";
            $file_content .= $espacio300.$this->crearespacios($espacios300 - strlen( $espacio300));
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= "    "; // 4 unidad de medidaN
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "   "; // Moneda 3 espaios
            $file_content .= ";";
            $file_content .= "          "; // Factor Conversion 10 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->precios)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // recargo pesos 18 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->neto)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= "  ";
            $file_content .= ";";
            $file_content .= " "; // Indicador de Agente 1 espacios
            $file_content .= ";";
            $file_content .= "                        "; // 24 espacios
            $file_content .= ";";
            $file_content .= "                                                  "; // 50 espacios
            $file_content .= chr(13).chr(10); //fin linea 5
                
            }

            if ($c < 30){
            	
            	$b = (30 - $c);

            	$producto="";
            	$productonombre="";
            	$cantidad="";
            	$precio="";
            	$descuento="";
            	$neto=0;

            	for ($i = 1; $i <= $b; $i++) {

            		//$file_content .= chr(13).chr(10); 
		            $file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));//razon social
		            $file_content .= chr(13).chr(10);
            
		            
		        }

            };
        };

          
		    $file_content .= "033"; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= " ";
		    $file_content .= ";";
		    $file_content .= str_pad($docrefer,18," ",STR_PAD_LEFT);
		    $file_content .= ";";
		    $file_content .= $fechafactura; 
		    $file_content .= ";";
		    $file_content .= "3";		     // Folio           
		    $file_content .= chr(13).chr(10);
		    for ($a = 1; $a <= 9; $a++) {

            	//$file_content .= chr(13).chr(10);
            	$file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));
            	$file_content .= " ";
            	$file_content .= chr(13).chr(10);
            };

            //$file_content .= chr(13).chr(10);
            $file_content .= " "; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= $totalletras.$this->crearespacios($espacios80 - strlen( $totalletras));

            //$file_content .= chr(13).chr(10);
           /* $file_content .= " "; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= $totalletras.$this->crearespacios($espacios80 - strlen( $totalletras));
		     //$file_content .= chr(13).chr(10);
		     //linea fin          
        */
        $nombre_archivo = "61_NPG_".str_pad($numdocumento,7,"0",STR_PAD_LEFT).".spf";
        $path_archivo = './facturas/';
		$f_archivo = fopen($path_archivo.$nombre_archivo,'w');
		fwrite($f_archivo,$file_content);
		fclose($f_archivo);

		$data_archivo = basename($path_archivo.$nombre_archivo);
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename=' . $data_archivo);
		header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
		readfile($path_archivo.$nombre_archivo);			
		/*******************************fin exporta***********/

		//$origen='C:\Users\Sergio\Downloads\facturacion.txt';
		//$destino='C:\facturacion.txt';
		//mkdir(dirname($dstfile), 0777, true);
		//copy($origen, $destino);
		//unlink("C:\Users\Sergio\Downloads\facturacion.txt");
	}

	public function exportTXTGLO(){

		/**************************exporta txt*******/
		$idfactura = $this->input->get('idfactura');
		/*header("Content-Type: application/force-download");
        header("Content-Transfer-Encoding: binary");
        header("Content-disposition: attachment; filename=facturacion.txt");*/

        $file_content = "";         
        $data = array();
        $query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, ob.pat_camion as patente, ob.pat_carro as carro, c.fono, cp.nombre as cond_pago, cp.codigo as codigo_con_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, cor.nombre as nomdocumento, ma.nombre as com_sucursal, v.cod_interno as cod_interno FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join correlativos cor on (acc.tipo_documento = cor.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.id = '.$idfactura.'');

			$row = $query->result();

        
        if ($query->num_rows()>0){	

        	$v = $row[0];
			        
            $nomdocumento = $v->nomdocumento;
            $fechafactura = $v->fecha_factura;
            $fechavenc = $v->fecha_venc;
            $idbodega = $v->id_bodega;

            $numordencompra= $v->orden_compra;

            $nomobserv = $v->nom_observ;
            $patente = $v->patente." ".$v->carro;
            
            $fecha = $v->fecha_factura;
			list($anio, $mes, $dia) = explode("-",$fecha);
			$fecha2 = $v->fecha_venc;
			list($anio2, $mes2, $dia2) = explode("-",$fecha2);

			$fechafactura = $dia."/".$mes."/".$anio;
            $fechavenc = $dia2."/".$mes2."/".$anio2;
			
			          
            $numdocumento = $v->num_factura;            
            $nomcliente = substr($v->nombre_cliente, 0, 40);
            $condventa = $v->cond_pago;
            $codcondventa = substr($v->codigo_con_pago, -2);
            $vendedor = $v->nom_vendedor;
            $codvendedor = $v->id_vendedor;
            $docrefer = $v->id_factura;         
            if ($v->ciu_sucursal){
            	$ciudad= $v->ciu_sucursal;            	
            }else{
            	$ciudad= $v->nombre_ciudad;
            };
            if ($v->com_sucursal){
            	$comuna= $v->com_sucursal;            	
            }else{
            	$comuna= $v->nombre_comuna;
            };
            $giro = substr($v->giro, 0, 42);
            $valornetocom = 18;
            $emisora ="";
            $sucsii = "";
            $codinterno=$v->cod_interno;
            $codinterno2="";
            $codImpuestoadic1="";
            $tasaImpadic1="";
            $valImpadic1="";
            $codImpuestoadic2="";
            $tasaImpadic2="";
            $valImpadic2="";
            $codImpuestoadic3="";
            $tasaImpadic3="";
            $valImpadic3="";
            $credespecial="";
            $montoperiodo="";
            $montototal="";
            $valordesc1 = "";
            $valordesc2 = "";
            $valordesc3 = "";
            $espaciosfin= "";
            $contactoreceptor="";
            if($v->direc_sucursal){
            	$direccionreceptor=$v->direc_sucursal;            	
            }else{
                $direccionreceptor=$v->direccion;
            };
            if($v->direc_sucursal){
            	$direcciondespacho=$v->direc_sucursal;            	
            }else{
                $direcciondespacho=$v->direccion;
            };
            $personalizadosl3="";
            $personalizadosl4="";
            $patentetrans="";
            $comunadespacho="";
            $ciudaddespacho="";
            $ruttransporte="";
            $neto = intval($v->neto);
            $exento="";
            $pordescuento="";
            $iva = intval($v->iva);            
            $total = intval($v->totalfactura);
            $id = $v->id;
            $espaciost = 25;
            $pregistro="";
		    $espacios20= 19;
		    $espacios5= 4;
		    $espacios2= 1;
		    $espacios8= 7;
		    $espacios9= 8;
		    $espacios35= 34;
		    $espacios40= 39;
		    $espacios42= 41;
		    $espacios60= 59;
		    $espacios80= 79;
		    $espacios300= 299;
		    $espacio300="";
		    $espacios30= 29;
		    $espacios110= 109;
		    $espacios139= 138;
		    $espaciolargo= 299;
		    $totalletras = (valorEnLetras($total));
		    $nomclienteinicio="                     ";
		    $espacios21= 20;
		    $espacios25= 24;

		    $rutautoriza = $v->rut_cliente;
		   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		   
		    };
		    if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $v->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };

		    $rutcliente = $v->rut_cliente;
		    if (strlen($rutcliente) == 9){
		    	$rutcliente=("0".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 8){
		    	$rutcliente=("00".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 7){
		    	$rutcliente=("000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 6){
		    	$rutcliente=("0000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 5){
		    	$rutcliente=("00000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 4){
		    	$rutcliente=("000000".$rutcliente);
		    	
		    };
		   
		    $file_content .= $idbodega.$this->crearespacios($espacios21 - strlen( $idbodega));
            $file_content .= $condventa.$this->crearespacios($espacios25 - strlen( $condventa));  //Nombre condicion Pago
            $file_content .= $vendedor.$this->crearespacios($espacios30 - strlen( $vendedor));  //LINEA 1
            $file_content .= chr(13).chr(10);
            $file_content .= chr(13).chr(10);
            $file_content .= " "; //espacio
            $file_content .= ";";
            $file_content .= str_pad($numdocumento,10," ",STR_PAD_LEFT); // Folio
            $file_content .= ";"; //
            $file_content .= $fechafactura;  //fecha Emision
            $file_content .= ";";
            $file_content .= " "; //indicador de no rebeja
            $file_content .= ";";
            $file_content .= " ";// tipo despacho
            $file_content .= ";";
            $file_content .= "0";// tipo de traslado
            $file_content .= ";";
            $file_content .= " ";// indicador servicio periodico
            $file_content .= ";";
            $file_content .= " ";// indicador montos brutos
            $file_content .= ";";
            $file_content .= "2";// DEBE SELECIONAR 1 O 2 SECUN TIPO DE PAGO
            $file_content .= ";";
            $file_content .= $fechavenc;
            $file_content .= ";";
            $file_content .= $emisora.$this->crearespacios($espacios20 - strlen($emisora));  //sucursal emision
            $file_content .= ";";
            $file_content .= $sucsii.$this->crearespacios($espacios9 - strlen( $sucsii));  //sucursal sii
            $file_content .= ";";
            $file_content .= $codinterno; // codigo vendedor
            $file_content .= ";";   
            $file_content .= $rutcliente; // rut receptor
            $file_content .= ";";
            $file_content .= "          "; // 10 ESPACIOS 
            $file_content .= ";";
            $file_content .= "                    "; // 20 ESPACIOS
            $file_content .= ";";
            $file_content .= $nomcliente.$this->crearespacios($espacios40 - strlen( $nomcliente));  //razon social
            $file_content .= ";";
            $file_content .= $giro.$this->crearespacios($espacios42 - strlen( $giro)); //Giro receptor
            $file_content .= ";"; 
            $file_content .= $contactoreceptor.$this->crearespacios($espacios30 - strlen( $contactoreceptor)); // Contacto receptor
            $file_content .= ";";
            $file_content .= $direccionreceptor.$this->crearespacios($espacios60 - strlen( $direccionreceptor)); // Direccion receptor
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios30 - strlen( $personalizadosl3));
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios40 - strlen( $personalizadosl3));
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios40 - strlen( $personalizadosl3));// Filea 3
            $file_content .= chr(13).chr(10);
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad
            $file_content .= ";";
            $file_content .= $direcciondespacho.$this->crearespacios($espacios60 - strlen( $direcciondespacho)); // Direccion Despacho
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna Despacho
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad Despacho
            $file_content .= ";";
            $file_content .= $patentetrans.$this->crearespacios($espacios8 - strlen( $patentetrans)); //Patente Transporte
            $file_content .= ";";
            $file_content .= str_pad($ruttransporte,10," ",STR_PAD_LEFT); // rut Transportista
            $file_content .= ";";
            $file_content .= str_pad($neto,18," ",STR_PAD_LEFT); // Monto Neto
            $file_content .= ";";
            $file_content .= str_pad($exento,18," ",STR_PAD_LEFT); // Monto Exento
            $file_content .= ";";
            $file_content .= "     ";// 5 ESPACIOS Tasa Iva
            $file_content .= ";";
            $file_content .= str_pad($iva,18," ",STR_PAD_LEFT); // Monto Iva
            $file_content .= ";";
            $file_content .= "  "; // 2 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= str_pad($total,18," ",STR_PAD_LEFT); //Valor descuento 1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= chr(13).chr(10); // linea 4 fin
                        
            $query2 = $this->db->get_where('detalle_factura_glosa', array('id_factura' => $id));

            $c=0;

            foreach ($query2->result() as $z){

			$c= $c+1;
				
            //$file_content .= chr(13).chr(10);
            $producto=" ";
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $producto.$this->crearespacios($espacios35 - strlen( $producto));  //razon social
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= $z->glosa.$this->crearespacios($espacios80 - strlen( $z->glosa));  //razon social
            $file_content .= ";";
            $file_content .= $espacio300.$this->crearespacios($espacios300 - strlen( $espacio300));
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= "    "; // 4 unidad de medidaN
            $file_content .= ";";
            $file_content .= str_pad($z->neto,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "   "; // Moneda 3 espaios
            $file_content .= ";";
            $file_content .= "          "; // Factor Conversion 10 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->precios)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // recargo pesos 18 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->neto)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= " "; // Indicador de Agente 1 espacios
            $file_content .= ";";
            $file_content .= "                    "; // 20 espacios
            $file_content .= ";";
            $file_content .= "                                                  "; // 50 espacios
            $file_content .= chr(13).chr(10); //fin linea 5
                
            }

            if ($c < 30){
            	
            	$b = (30 - $c);

            	$producto="";
            	$productonombre="";
            	$cantidad="";
            	$precio="";
            	$descuento="";
            	$neto=0;

            	for ($i = 1; $i <= $b; $i++) {

            		//$file_content .= chr(13).chr(10); 
		            $file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));//razon social
		            $file_content .= chr(13).chr(10);
            
		            
		        }

            };
        };

          
		    for ($a = 1; $a <= 10; $a++) {

            	//$file_content .= chr(13).chr(10);
            	$file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));
            	$file_content .= " ";
            	$file_content .= chr(13).chr(10);
            };

            //$file_content .= chr(13).chr(10);
           /* $file_content .= " "; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= $totalletras.$this->crearespacios($espacios80 - strlen( $totalletras));
		     //$file_content .= chr(13).chr(10);
		     //linea fin          
        */
        $nombre_archivo = "33_NPG_".str_pad($numdocumento,7,"0",STR_PAD_LEFT).".spf";
        $path_archivo = './facturas/';
		$f_archivo = fopen($path_archivo.$nombre_archivo,'w');
		fwrite($f_archivo,$file_content);
		fclose($f_archivo);

		$data_archivo = basename($path_archivo.$nombre_archivo);
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename=' . $data_archivo);
		header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
		readfile($path_archivo.$nombre_archivo);			
		/*******************************fin exporta***********/

		//$origen='C:\Users\Sergio\Downloads\facturacion.txt';
		//$destino='C:\facturacion.txt';
		//mkdir(dirname($dstfile), 0777, true);
		//copy($origen, $destino);
		//unlink("C:\Users\Sergio\Downloads\facturacion.txt");
	}

	public function exportTXTGDGLO(){

		/**************************exporta txt*******/
		$idfactura = $this->input->get('idfactura');
		/*header("Content-Type: application/force-download");
        header("Content-Transfer-Encoding: binary");
        header("Content-disposition: attachment; filename=facturacion.txt");*/

        $file_content = "";         
        $data = array();
        $query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, ob.pat_camion as patente, ob.pat_carro as carro, c.fono, cp.nombre as cond_pago, cp.codigo as codigo_con_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, cor.nombre as nomdocumento, ma.nombre as com_sucursal, v.cod_interno as cod_interno FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join correlativos cor on (acc.tipo_documento = cor.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.id = '.$idfactura.'');

			$row = $query->result();

        
        if ($query->num_rows()>0){	

        	$v = $row[0];
			        
            $nomdocumento = $v->nomdocumento;
            $fechafactura = $v->fecha_factura;
            $fechavenc = $v->fecha_venc;
            $idbodega = $v->id_bodega;

            $numordencompra= $v->orden_compra;

            $nomobserv = $v->nom_observ;
            $patente = $v->patente." ".$v->carro;
            
            $fecha = $v->fecha_factura;
			list($anio, $mes, $dia) = explode("-",$fecha);
			$fecha2 = $v->fecha_venc;
			list($anio2, $mes2, $dia2) = explode("-",$fecha2);

			$fechafactura = $dia."/".$mes."/".$anio;
            $fechavenc = $dia2."/".$mes2."/".$anio2;
			
			          
            $numdocumento = $v->num_factura;            
            $nomcliente = substr($v->nombre_cliente, 0, 40);
            $condventa = $v->cond_pago;
            $codcondventa = substr($v->codigo_con_pago, -2);
            $vendedor = $v->nom_vendedor;
            $codvendedor = $v->id_vendedor;
            $docrefer = $v->id_factura;         
            if ($v->ciu_sucursal){
            	$ciudad= $v->ciu_sucursal;            	
            }else{
            	$ciudad= $v->nombre_ciudad;
            };
            if ($v->com_sucursal){
            	$comuna= $v->com_sucursal;            	
            }else{
            	$comuna= $v->nombre_comuna;
            };
            $giro = substr($v->giro, 0, 42);
            $valornetocom = 18;
            $emisora ="";
            $sucsii = "";
            $codinterno=$v->cod_interno;
            $codinterno2="";
            $codImpuestoadic1="";
            $tasaImpadic1="";
            $valImpadic1="";
            $codImpuestoadic2="";
            $tasaImpadic2="";
            $valImpadic2="";
            $codImpuestoadic3="";
            $tasaImpadic3="";
            $valImpadic3="";
            $credespecial="";
            $montoperiodo="";
            $montototal="";
            $valordesc1 = "";
            $valordesc2 = "";
            $valordesc3 = "";
            $espaciosfin= "";
            $contactoreceptor="";
            if($v->direc_sucursal){
            	$direccionreceptor=$v->direc_sucursal;            	
            }else{
                $direccionreceptor=$v->direccion;
            };
            if($v->direc_sucursal){
            	$direcciondespacho=$v->direc_sucursal;            	
            }else{
                $direcciondespacho=$v->direccion;
            };
            $personalizadosl3="";
            $personalizadosl4="";
            $patentetrans="";
            $comunadespacho="";
            $ciudaddespacho="";
            $ruttransporte="";
            $neto = intval($v->neto);
            $exento="";
            $pordescuento="";
            $iva = intval($v->iva);            
            $total = intval($v->totalfactura);
            $id = $v->id;
            $espaciost = 25;
            $pregistro="";
		    $espacios20= 19;
		    $espacios5= 4;
		    $espacios2= 1;
		    $espacios8= 7;
		    $espacios9= 8;
		    $espacios35= 34;
		    $espacios40= 39;
		    $espacios42= 41;
		    $espacios60= 59;
		    $espacios80= 79;
		    $espacios300= 299;
		    $espacio300="";
		    $espacios30= 29;
		    $espacios110= 109;
		    $espacios139= 138;
		    $espaciolargo= 299;
		    $totalletras = (valorEnLetras($total));
		    $nomclienteinicio="                     ";
		    $espacios21= 20;
		    $espacios25= 24;

		    $rutautoriza = $v->rut_cliente;
		   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		   
		    };
		    if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $v->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };

		    $rutcliente = $v->rut_cliente;
		    if (strlen($rutcliente) == 9){
		    	$rutcliente=("0".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 8){
		    	$rutcliente=("00".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 7){
		    	$rutcliente=("000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 6){
		    	$rutcliente=("0000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 5){
		    	$rutcliente=("00000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 4){
		    	$rutcliente=("000000".$rutcliente);
		    	
		    };
		   
		    $file_content .= $idbodega.$this->crearespacios($espacios21 - strlen( $idbodega));
		    $file_content .= $condventa.$this->crearespacios($espacios25 - strlen( $condventa));  //Nombre condicion Pago
            $file_content .= $vendedor.$this->crearespacios($espacios30 - strlen( $vendedor));  //Nombre Vendedor
            $file_content .= ";";
            $file_content .= "2"; //LINEA 1
            $file_content .= chr(13).chr(10);
            $file_content .= chr(13).chr(10);
            $file_content .= " "; //espacio
            $file_content .= ";";
            $file_content .= str_pad($numdocumento,10," ",STR_PAD_LEFT); // Folio
            $file_content .= ";"; //
            $file_content .= $fechafactura;  //fecha Emision
            $file_content .= ";";
            $file_content .= " "; //indicador de no rebeja
            $file_content .= ";";
            $file_content .= " ";// tipo despacho
            $file_content .= ";";
            $file_content .= "6";// tipo de traslado
            $file_content .= ";";
            $file_content .= " ";// indicador servicio periodico
            $file_content .= ";";
            $file_content .= " ";// indicador montos brutos
            $file_content .= ";";
            $file_content .= "1";// DEBE SELECIONAR 1 O 2 SECUN TIPO DE PAGO
            $file_content .= ";";
            $file_content .= $fechavenc;
            $file_content .= ";";
            $file_content .= $emisora.$this->crearespacios($espacios20 - strlen($emisora));  //sucursal emision
            $file_content .= ";";
            $file_content .= $sucsii.$this->crearespacios($espacios9 - strlen( $sucsii));  //sucursal sii
            $file_content .= ";";
            $file_content .= $codinterno; // codigo vendedor
            $file_content .= ";";   
            $file_content .= $rutcliente; // rut receptor
            $file_content .= ";";
            $file_content .= "          "; // 10 ESPACIOS 
            $file_content .= ";";
            $file_content .= "                    "; // 20 ESPACIOS
            $file_content .= ";";
            $file_content .= $nomcliente.$this->crearespacios($espacios40 - strlen( $nomcliente));  //razon social
            $file_content .= ";";
            $file_content .= $giro.$this->crearespacios($espacios42 - strlen( $giro)); //Giro receptor
            $file_content .= ";"; 
            $file_content .= $contactoreceptor.$this->crearespacios($espacios30 - strlen( $contactoreceptor)); // Contacto receptor
            $file_content .= ";";
            $file_content .= $direccionreceptor.$this->crearespacios($espacios60 - strlen( $direccionreceptor)); // Direccion receptor
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios30 - strlen( $personalizadosl3));
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios40 - strlen( $personalizadosl3));
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios40 - strlen( $personalizadosl3));// Filea 3
            $file_content .= chr(13).chr(10);
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad
            $file_content .= ";";
            $file_content .= $direcciondespacho.$this->crearespacios($espacios60 - strlen( $direcciondespacho)); // Direccion Despacho
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna Despacho
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad Despacho
            $file_content .= ";";
            $file_content .= $patentetrans.$this->crearespacios($espacios8 - strlen( $patentetrans)); //Patente Transporte
            $file_content .= ";";
            $file_content .= str_pad($ruttransporte,10," ",STR_PAD_LEFT); // rut Transportista
            $file_content .= ";";
            $file_content .= str_pad($neto,18," ",STR_PAD_LEFT); // Monto Neto
            $file_content .= ";";
            $file_content .= str_pad($exento,18," ",STR_PAD_LEFT); // Monto Exento
            $file_content .= ";";
            $file_content .= "     ";// 5 ESPACIOS Tasa Iva
            $file_content .= ";";
            $file_content .= str_pad($iva,18," ",STR_PAD_LEFT); // Monto Iva
            $file_content .= ";";
            $file_content .= "  "; // 2 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= str_pad($total,18," ",STR_PAD_LEFT); //Valor descuento 1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= chr(13).chr(10); // linea 4 fin
                        
            $query2 = $this->db->get_where('detalle_factura_glosa', array('id_factura' => $id));

            $c=0;

            foreach ($query2->result() as $z){

			$c= $c+1;
				
            //$file_content .= chr(13).chr(10);
            $producto=" ";
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $producto.$this->crearespacios($espacios35 - strlen( $producto));  //razon social
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= $z->glosa.$this->crearespacios($espacios80 - strlen( $z->glosa));  //razon social
            $file_content .= ";";
            $file_content .= $espacio300.$this->crearespacios($espacios300 - strlen( $espacio300));
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= "    "; // 4 unidad de medidaN
            $file_content .= ";";
            $file_content .= str_pad($z->neto,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "   "; // Moneda 3 espaios
            $file_content .= ";";
            $file_content .= "          "; // Factor Conversion 10 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->precios)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // recargo pesos 18 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->neto)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= " "; // Indicador de Agente 1 espacios
            $file_content .= ";";
            $file_content .= "                              "; // 20 espacios
            $file_content .= ";";
            $file_content .= "                                                                                   "; // 50 espacios
            $file_content .= chr(13).chr(10); //fin linea 5
                
            }

            if ($c < 30){
            	
            	$b = (30 - $c);

            	$producto="";
            	$productonombre="";
            	$cantidad="";
            	$precio="";
            	$descuento="";
            	$neto=0;

            	for ($i = 1; $i <= $b; $i++) {

            		//$file_content .= chr(13).chr(10); 
		            $file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));//razon social
		            $file_content .= chr(13).chr(10);
            
		            
		        }

            };
        };

          
		    for ($a = 1; $a <= 10; $a++) {

            	//$file_content .= chr(13).chr(10);
            	$file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));
            	$file_content .= " ";
            	$file_content .= chr(13).chr(10);
            };

            //$file_content .= chr(13).chr(10);
            $file_content .= " "; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= $totalletras.$this->crearespacios($espacios80 - strlen( $totalletras));
		     //$file_content .= chr(13).chr(10);
		     //linea fin          
        
        $nombre_archivo = "52_NPG_".str_pad($numdocumento,7,"0",STR_PAD_LEFT).".spf";
        $path_archivo = './facturas/';
		$f_archivo = fopen($path_archivo.$nombre_archivo,'w');
		fwrite($f_archivo,$file_content);
		fclose($f_archivo);

		$data_archivo = basename($path_archivo.$nombre_archivo);
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename=' . $data_archivo);
		header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
		readfile($path_archivo.$nombre_archivo);			
		/*******************************fin exporta***********/

		//$origen='C:\Users\Sergio\Downloads\facturacion.txt';
		//$destino='C:\facturacion.txt';
		//mkdir(dirname($dstfile), 0777, true);
		//copy($origen, $destino);
		//unlink("C:\Users\Sergio\Downloads\facturacion.txt");
	}

	public function exportTXTGD(){

		/**************************exporta txt*******/
		$idfactura = $this->input->get('idfactura');
		/*header("Content-Type: application/force-download");
        header("Content-Transfer-Encoding: binary");
        header("Content-disposition: attachment; filename=facturacion.txt");*/

        $file_content = "";         
        $data = array();
        $query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, ob.pat_camion as patente, ob.pat_carro as carro, c.fono, cp.nombre as cond_pago, cp.codigo as codigo_con_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, cor.nombre as nomdocumento, ma.nombre as com_sucursal, v.cod_interno as cod_interno FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join correlativos cor on (acc.tipo_documento = cor.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.id = '.$idfactura.'');

			$row = $query->result();

        
        if ($query->num_rows()>0){	

        	$v = $row[0];
			        
            $nomdocumento = $v->nomdocumento;
            $fechafactura = $v->fecha_factura;
            $fechavenc = $v->fecha_venc;
            $idbodega = $v->id_bodega;

            $numordencompra= $v->orden_compra;

            $nomobserv = $v->nom_observ;
            $patente = $v->patente." ".$v->carro;
            
            $fecha = $v->fecha_factura;
			list($anio, $mes, $dia) = explode("-",$fecha);
			$fecha2 = $v->fecha_venc;
			list($anio2, $mes2, $dia2) = explode("-",$fecha2);

			$fechafactura = $dia."/".$mes."/".$anio;
            $fechavenc = $dia2."/".$mes2."/".$anio2;
			
			          
            $numdocumento = $v->num_factura;            
            $nomcliente = substr($v->nombre_cliente, 0, 40);
            $condventa = $v->cond_pago;
            $codcondventa = substr($v->codigo_con_pago, -2);
            $vendedor = $v->nom_vendedor;
            $codvendedor = $v->id_vendedor;
            $docrefer = $v->id_factura;         
            if ($v->ciu_sucursal){
            	$ciudad= $v->ciu_sucursal;            	
            }else{
            	$ciudad= $v->nombre_ciudad;
            };
            if ($v->com_sucursal){
            	$comuna= $v->com_sucursal;            	
            }else{
            	$comuna= $v->nombre_comuna;
            };
            $giro = substr($v->giro, 0, 42);
            $valornetocom = 18;
            $emisora ="";
            $sucsii = "";
            $codinterno=$v->cod_interno;
            $codinterno2="";
            $codImpuestoadic1="";
            $tasaImpadic1="";
            $valImpadic1="";
            $codImpuestoadic2="";
            $tasaImpadic2="";
            $valImpadic2="";
            $codImpuestoadic3="";
            $tasaImpadic3="";
            $valImpadic3="";
            $credespecial="";
            $montoperiodo="";
            $montototal="";
            $valordesc1 = "";
            $valordesc2 = "";
            $valordesc3 = "";
            $espaciosfin= "";
            $contactoreceptor="";
            if($v->direc_sucursal){
            	$direccionreceptor=$v->direc_sucursal;            	
            }else{
                $direccionreceptor=$v->direccion;
            };
            if($v->direc_sucursal){
            	$direcciondespacho=$v->direc_sucursal;            	
            }else{
                $direcciondespacho=$v->direccion;
            };
            $personalizadosl3="";
            $personalizadosl4="";
            $patentetrans="";
            $comunadespacho="";
            $ciudaddespacho="";
            $ruttransporte="";
            $neto = intval($v->neto);
            $exento="";
            $pordescuento="";
            $iva = intval($v->iva);            
            $total = intval($v->totalfactura);
            $id = $v->id;
            $espaciost = 25;
            $pregistro="";
		    $espacios20= 19;
		    $espacios5= 4;
		    $espacios2= 1;
		    $espacios8= 7;
		    $espacios9= 8;
		    $espacios35= 34;
		    $espacios40= 39;
		    $espacios42= 41;
		    $espacios60= 59;
		    $espacios80= 79;
		    $espacios300= 299;
		    $espacio300="";
		    $espacios30= 29;
		    $espacios110= 109;
		    $espacios139= 138;
		    $espaciolargo= 299;
		    $totalletras = (valorEnLetras($total));
		    $nomclienteinicio="                     ";
		    $espacios21= 20;
		    $espacios25= 24;

		    $rutautoriza = $v->rut_cliente;
		   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		   
		    };
		    if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $v->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };

		    $rutcliente = $v->rut_cliente;
		    if (strlen($rutcliente) == 9){
		    	$rutcliente=("0".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 8){
		    	$rutcliente=("00".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 7){
		    	$rutcliente=("000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 6){
		    	$rutcliente=("0000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 5){
		    	$rutcliente=("00000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 4){
		    	$rutcliente=("000000".$rutcliente);
		    	
		    };
		   
		    $file_content .= $idbodega.$this->crearespacios($espacios21 - strlen( $idbodega));
		    $file_content .= $condventa.$this->crearespacios($espacios25 - strlen( $condventa));  //Nombre condicion Pago
            $file_content .= $vendedor.$this->crearespacios($espacios30 - strlen( $vendedor));  //Nombre Vendedor
            $file_content .= ";";
            $file_content .= "1";
            $file_content .= ";";
            $file_content .= "02"; // linea 1
            $file_content .= chr(13).chr(10);
            $file_content .= $nomobserv.$this->crearespacios($espacios30 - strlen( $nomobserv));
            $file_content .= ";";
            $file_content .= $patente.$this->crearespacios($espacios30 - strlen( $patente));
            $file_content .= ";";
            $file_content .= $direcciondespacho.$this->crearespacios($espacios40 - strlen( $direcciondespacho)); // lineas 2
            $file_content .= chr(13).chr(10);
            $file_content .= " "; //espacio
            $file_content .= ";";
            $file_content .= str_pad($numdocumento,10," ",STR_PAD_LEFT); // Folio
            $file_content .= ";"; //
            $file_content .= $fechafactura;  //fecha Emision
            $file_content .= ";";
            $file_content .= " "; //indicador de no rebeja
            $file_content .= ";";
            $file_content .= " ";// tipo despacho
            $file_content .= ";";
            $file_content .= "1";// tipo de traslado
            $file_content .= ";";
            $file_content .= " ";// indicador servicio periodico
            $file_content .= ";";
            $file_content .= " ";// indicador montos brutos
            $file_content .= ";";
            $file_content .= "2";// DEBE SELECIONAR 1 O 2 SECUN TIPO DE PAGO
            $file_content .= ";";
            $file_content .= $fechavenc;
            $file_content .= ";";
            $file_content .= $emisora.$this->crearespacios($espacios20 - strlen($emisora));  //sucursal emision
            $file_content .= ";";
            $file_content .= $sucsii.$this->crearespacios($espacios9 - strlen( $sucsii));  //sucursal sii
            $file_content .= ";";
            $file_content .= $codinterno; // codigo vendedor
            $file_content .= ";";   
            $file_content .= $rutcliente; // rut receptor
            $file_content .= ";";
            $file_content .= "          "; // 10 ESPACIOS 
            $file_content .= ";";
            $file_content .= "                    "; // 20 ESPACIOS
            $file_content .= ";";
            $file_content .= $nomcliente.$this->crearespacios($espacios40 - strlen( $nomcliente));  //razon social
            $file_content .= ";";
            $file_content .= $giro.$this->crearespacios($espacios42 - strlen( $giro)); //Giro receptor
            $file_content .= ";"; 
            $file_content .= $contactoreceptor.$this->crearespacios($espacios30 - strlen( $contactoreceptor)); // Contacto receptor
            $file_content .= ";";
            $file_content .= $direccionreceptor.$this->crearespacios($espacios60 - strlen( $direccionreceptor)); // Direccion receptor
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios30 - strlen( $personalizadosl3));
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios40 - strlen( $personalizadosl3));// Filea 3
            $file_content .= chr(13).chr(10);
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad
            $file_content .= ";";
            $file_content .= $direcciondespacho.$this->crearespacios($espacios60 - strlen( $direcciondespacho)); // Direccion Despacho
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna Despacho
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad Despacho
            $file_content .= ";";
            $file_content .= $patentetrans.$this->crearespacios($espacios8 - strlen( $patentetrans)); //Patente Transporte
            $file_content .= ";";
            $file_content .= str_pad($ruttransporte,10," ",STR_PAD_LEFT); // rut Transportista
            $file_content .= ";";
            $file_content .= str_pad($neto,18," ",STR_PAD_LEFT); // Monto Neto
            $file_content .= ";";
            $file_content .= str_pad($exento,18," ",STR_PAD_LEFT); // Monto Exento
            $file_content .= ";";
            $file_content .= "     ";// 5 ESPACIOS Tasa Iva
            $file_content .= ";";
            $file_content .= str_pad($iva,18," ",STR_PAD_LEFT); // Monto Iva
            $file_content .= ";";
            $file_content .= "  "; // 2 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= str_pad($total,18," ",STR_PAD_LEFT); //Valor descuento 1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= chr(13).chr(10); // linea 4 fin
                        
            $query2 = $this->db->get_where('detalle_factura_cliente', array('id_factura' => $id));

            $c=0;

            foreach ($query2->result() as $z){

        	$this->db->where('id', $z->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];

			$c= $c+1;
				
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $producto->codigo.$this->crearespacios($espacios35 - strlen( $producto->codigo));  //razon social
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= $producto->nombre.$this->crearespacios($espacios80 - strlen( $producto->nombre));  //razon social
            $file_content .= ";";
            $file_content .= $espacio300.$this->crearespacios($espacios300 - strlen( $espacio300));
            $file_content .= ";";
            $file_content .= str_pad($z->cantidad,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "    "; // 4 unidad de medidaN
            $file_content .= ";";
            $file_content .= str_pad($z->precio,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "   "; // Moneda 3 espaios
            $file_content .= ";";
            $file_content .= "          "; // Factor Conversion 10 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->descuento)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // recargo pesos 18 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->neto)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= " "; // Indicador de Agente 1 espacios
            $file_content .= ";";
            $file_content .= "                              "; // 20 espacios
            $file_content .= ";";
            $file_content .= "                                                                                   "; // 50 espacios
            $file_content .= chr(13).chr(10); //fin linea 5
                
            }

            if ($c < 30){
            	
            	$b = (30 - $c);

            	$producto="";
            	$productonombre="";
            	$cantidad="";
            	$precio="";
            	$descuento="";
            	$neto=0;

            	for ($i = 1; $i <= $b; $i++) {

            		//$file_content .= chr(13).chr(10); 
		            $file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));//razon social
		            $file_content .= chr(13).chr(10);
            
		            
		        }

            };
        };

            $file_content .= "801"; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= " ";
		    $file_content .= ";";
		    $file_content .= str_pad($numordencompra,15," ",STR_PAD_LEFT);
		    $file_content .= ";";
		    $file_content .= $fechafactura; 
		    $file_content .= ";";		     // Folio           
		    $file_content .= chr(13).chr(10);
		    for ($a = 1; $a <= 9; $a++) {

            	//$file_content .= chr(13).chr(10);
            	$file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));
            	$file_content .= " ";
            	$file_content .= chr(13).chr(10);
            };

            //$file_content .= chr(13).chr(10);
            $file_content .= " "; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= $totalletras.$this->crearespacios($espacios80 - strlen( $totalletras));
		     //$file_content .= chr(13).chr(10);
		     //linea fin          
        
        $nombre_archivo = "52_NPG_".str_pad($numdocumento,7,"0",STR_PAD_LEFT).".spf";
        $path_archivo = './facturas/';
		$f_archivo = fopen($path_archivo.$nombre_archivo,'w');
		fwrite($f_archivo,$file_content);
		fclose($f_archivo);

		$data_archivo = basename($path_archivo.$nombre_archivo);
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename=' . $data_archivo);
		header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
		readfile($path_archivo.$nombre_archivo);			
		/*******************************fin exporta***********/

		//$origen='C:\Users\Sergio\Downloads\facturacion.txt';
		//$destino='C:\facturacion.txt';
		//mkdir(dirname($dstfile), 0777, true);
		//copy($origen, $destino);
		//unlink("C:\Users\Sergio\Downloads\facturacion.txt");
	}

	public function exportTXTNC(){

		/**************************exporta txt*******/
		$idfactura = $this->input->get('idfactura');
		/*header("Content-Type: application/force-download");
        header("Content-Transfer-Encoding: binary");
        header("Content-disposition: attachment; filename=facturacion.txt");*/

        $file_content = "";         
        $data = array();
        $query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, c.fono, cp.nombre as cond_pago, cp.codigo as codigo_con_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, cor.nombre as nomdocumento, ma.nombre as com_sucursal, v.cod_interno as cod_interno FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join correlativos cor on (acc.tipo_documento = cor.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.id = '.$idfactura.'');

			$row = $query->result();

        
        if ($query->num_rows()>0){	

        	$v = $row[0];

			$query2 = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, c.fono, cp.nombre as cond_pago, cp.codigo as codigo_con_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, cor.nombre as nomdocumento, ma.nombre as com_sucursal, v.cod_interno as cod_interno FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join correlativos cor on (acc.tipo_documento = cor.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.num_factura = '.$v->id_factura.' and acc.tipo_documento= 1');

			$r = $query2->result();
        
			if ($query2->num_rows()>0){
				$s = $r[0];
				$fechar = $s->fecha_factura;
				list($anior, $mesr, $diar) = explode("-",$fechar);
				$fecharef = $diar."/".$mesr."/".$anior;
			}	
                    
            $nomdocumento = $v->nomdocumento;
            $fechafactura = $v->fecha_factura;
            $fechavenc = $v->fecha_venc;
            $idbodega = $v->id_bodega;
            
            $fecha = $v->fecha_factura;
			list($anio, $mes, $dia) = explode("-",$fecha);
			$fecha2 = $v->fecha_venc;
			list($anio2, $mes2, $dia2) = explode("-",$fecha2);

			$fechafactura = $dia."/".$mes."/".$anio;
            $fechavenc = $dia2."/".$mes2."/".$anio2;
			
			          
            $numdocumento = $v->num_factura;            
            $nomcliente = substr($v->nombre_cliente, 0, 40);
            $condventa = $v->cond_pago;
            $codcondventa = substr($v->codigo_con_pago, -2);
            $vendedor = $v->nom_vendedor;
            $codvendedor = $v->id_vendedor;
            $docrefer = $v->id_factura;         
            if ($v->ciu_sucursal){
            	$ciudad= $v->ciu_sucursal;            	
            }else{
            	$ciudad= $v->nombre_ciudad;
            };
            if ($v->com_sucursal){
            	$comuna= $v->com_sucursal;            	
            }else{
            	$comuna= $v->nombre_comuna;
            };
            $giro = substr($v->giro, 0, 42);
            $valornetocom = 18;
            $emisora ="";
            $sucsii = "";
            $codinterno=$v->cod_interno;
            $codinterno2="";
            $codImpuestoadic1="";
            $tasaImpadic1="";
            $valImpadic1="";
            $codImpuestoadic2="";
            $tasaImpadic2="";
            $valImpadic2="";
            $codImpuestoadic3="";
            $tasaImpadic3="";
            $valImpadic3="";
            $credespecial="";
            $montoperiodo="";
            $montototal="";
            $valordesc1 = "";
            $valordesc2 = "";
            $valordesc3 = "";
            $espaciosfin= "";
            $contactoreceptor="";
            if($v->direc_sucursal){
            	$direccionreceptor=$v->direc_sucursal;            	
            }else{
                $direccionreceptor=$v->direccion;
            };
            if($v->direc_sucursal){
            	$direcciondespacho=$v->direc_sucursal;            	
            }else{
                $direcciondespacho=$v->direccion;
            };
            $personalizadosl3="";
            $personalizadosl4="";
            $patentetrans="";
            $comunadespacho="";
            $ciudaddespacho="";
            $ruttransporte="";
            $neto = intval($v->neto);
            $exento="";
            $pordescuento="";
            $iva = intval($v->iva);            
            $total = intval($v->totalfactura);
            $id = $v->id;
            $espaciost = 25;
            $pregistro="";
		    $espacios20= 19;
		    $espacios5= 4;
		    $espacios2= 1;
		    $espacios8= 7;
		    $espacios9= 8;
		    $espacios35= 34;
		    $espacios40= 39;
		    $espacios42= 41;
		    $espacios60= 59;
		    $espacios80= 79;
		    $espacios300= 299;
		    $espacio300="";
		    $espacios30= 29;
		    $espacios110= 109;
		    $espacios139= 138;
		    $espaciolargo= 299;
		    $totalletras = (valorEnLetras($total));
		    $nomclienteinicio="                     ";
		    $espacios21= 20;
		    $espacios25= 24;

		    $rutautoriza = $v->rut_cliente;
		   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		   
		    };
		    if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $v->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };

		    $rutcliente = $v->rut_cliente;
		    if (strlen($rutcliente) == 9){
		    	$rutcliente=("0".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 8){
		    	$rutcliente=("00".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 7){
		    	$rutcliente=("000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 6){
		    	$rutcliente=("0000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 5){
		    	$rutcliente=("00000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 4){
		    	$rutcliente=("000000".$rutcliente);
		    	
		    };
		    $file_content .= $idbodega.$this->crearespacios($espacios21 - strlen( $idbodega));
		    $file_content .= $condventa.$this->crearespacios($espacios25 - strlen( $condventa));  //Nombre condicion Pago
            $file_content .= $vendedor.$this->crearespacios($espacios30 - strlen( $vendedor));  //Nombre Vendedor
            $file_content .= chr(13).chr(10);
            $file_content .= chr(13).chr(10);
            $file_content .= " "; //espacio
            $file_content .= ";";
            $file_content .= str_pad($numdocumento,10," ",STR_PAD_LEFT); // Folio
            $file_content .= ";"; //
            $file_content .= $fechafactura;  //fecha Emision
            $file_content .= ";";
            $file_content .= " "; //indicador de no rebeja
            $file_content .= ";";
            $file_content .= " ";// tipo despacho
            $file_content .= ";";
            $file_content .= "0";// tipo de traslado
            $file_content .= ";";
            $file_content .= " ";// indicador servicio periodico
            $file_content .= ";";
            $file_content .= " ";// indicador montos brutos
            $file_content .= ";";
            $file_content .= "2";// DEBE SELECIONAR 1 O 2 SECUN TIPO DE PAGO
            $file_content .= ";";
            $file_content .= $fechavenc;
            $file_content .= ";";
            $file_content .= $emisora.$this->crearespacios($espacios20 - strlen($emisora));  //sucursal emision
            $file_content .= ";";
            $file_content .= $sucsii.$this->crearespacios($espacios9 - strlen( $sucsii));  //sucursal sii
            $file_content .= ";";
            $file_content .= $codinterno; // codigo vendedor
            $file_content .= ";";   
            $file_content .= $rutcliente; // rut receptor
            $file_content .= ";";
            $file_content .= "          "; // 10 ESPACIOS 
            $file_content .= ";";
            $file_content .= "                    "; // 20 ESPACIOS
            $file_content .= ";";
            $file_content .= $nomcliente.$this->crearespacios($espacios40 - strlen( $nomcliente));  //razon social
            $file_content .= ";";
            $file_content .= $giro.$this->crearespacios($espacios42 - strlen( $giro)); //Giro receptor
            $file_content .= ";"; 
            $file_content .= $contactoreceptor.$this->crearespacios($espacios30 - strlen( $contactoreceptor)); // Contacto receptor
            $file_content .= ";";
            $file_content .= $direccionreceptor.$this->crearespacios($espacios60 - strlen( $direccionreceptor)); // Direccion receptor
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios30 - strlen( $personalizadosl3));
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios40 - strlen( $personalizadosl3));// Filea 3
            $file_content .= chr(13).chr(10);
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad
            $file_content .= ";";
            $file_content .= $direcciondespacho.$this->crearespacios($espacios60 - strlen( $direcciondespacho)); // Direccion Despacho
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna Despacho
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad Despacho
            $file_content .= ";";
            $file_content .= $patentetrans.$this->crearespacios($espacios8 - strlen( $patentetrans)); //Patente Transporte
            $file_content .= ";";
            $file_content .= str_pad($ruttransporte,10," ",STR_PAD_LEFT); // rut Transportista
            $file_content .= ";";
            $file_content .= str_pad($neto,18," ",STR_PAD_LEFT); // Monto Neto
            $file_content .= ";";
            $file_content .= str_pad($exento,18," ",STR_PAD_LEFT); // Monto Exento
            $file_content .= ";";
            $file_content .= "     ";// 5 ESPACIOS Tasa Iva
            $file_content .= ";";
            $file_content .= str_pad($iva,18," ",STR_PAD_LEFT); // Monto Iva
            $file_content .= ";";
            $file_content .= "      "; // 6 espacios
            $file_content .= ";";
            $file_content .= "  "; // 2 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= str_pad($total,18," ",STR_PAD_LEFT); //Valor descuento 1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= chr(13).chr(10); // linea 4 fin
                        
            $query2 = $this->db->get_where('detalle_factura_cliente', array('id_factura' => $id));

            $c=0;

            foreach ($query2->result() as $z){

        	$this->db->where('id', $z->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];

			$c= $c+1;
				
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $producto->codigo.$this->crearespacios($espacios35 - strlen( $producto->codigo));  //razon social
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= $producto->nombre.$this->crearespacios($espacios80 - strlen( $producto->nombre));  //razon social
            $file_content .= ";";
            $file_content .= $espacio300.$this->crearespacios($espacios300 - strlen( $espacio300));
            $file_content .= ";";
            $file_content .= str_pad($z->cantidad,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "    "; // 4 unidad de medidaN
            $file_content .= ";";
            $file_content .= str_pad($z->precio,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "   "; // Moneda 3 espaios
            $file_content .= ";";
            $file_content .= "          "; // Factor Conversion 10 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->descuento)),20," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // recargo pesos 18 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->neto)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= " "; // Indicador de Agente 1 espacios
            $file_content .= ";";
            $file_content .= "                        "; // 14 espacios
            $file_content .= ";";
            $file_content .= "                                                  "; // 18 espacios
            $file_content .= chr(13).chr(10); //fin linea 5
                
            }

            if ($c < 30){
            	
            	$b = (30 - $c);

            	$producto="";
            	$productonombre="";
            	$cantidad="";
            	$precio="";
            	$descuento="";
            	$neto=0;

            	for ($i = 1; $i <= $b; $i++) {

            		//$file_content .= chr(13).chr(10); 
		            $file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));//razon social
		            $file_content .= chr(13).chr(10);
            
		            
		        }

            };
        };

            $file_content .= "033"; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= " ";
		    $file_content .= ";";
		    $file_content .= str_pad($docrefer,18," ",STR_PAD_LEFT);
		    $file_content .= ";";
		    $file_content .= $fechafactura; 
		    $file_content .= ";";
		    $file_content .= "3";		     // Folio           
		    $file_content .= chr(13).chr(10);
		    for ($a = 1; $a <= 9; $a++) {

            	//$file_content .= chr(13).chr(10);
            	$file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));
            	$file_content .= " ";
            	$file_content .= chr(13).chr(10);
            };

            //$file_content .= chr(13).chr(10);
            $file_content .= " "; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= $totalletras.$this->crearespacios($espacios80 - strlen( $totalletras));
		     //$file_content .= chr(13).chr(10);
		     //linea fin          
        
        $nombre_archivo = "61_NPG_".str_pad($numdocumento,7,"0",STR_PAD_LEFT).".spf";
        $path_archivo = './facturas/';
		$f_archivo = fopen($path_archivo.$nombre_archivo,'w');
		fwrite($f_archivo,$file_content);
		fclose($f_archivo);

		$data_archivo = basename($path_archivo.$nombre_archivo);
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename=' . $data_archivo);
		header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
		readfile($path_archivo.$nombre_archivo);			
		/*******************************fin exporta***********/

		//$origen='C:\Users\Sergio\Downloads\facturacion.txt';
		//$destino='C:\facturacion.txt';
		//mkdir(dirname($dstfile), 0777, true);
		//copy($origen, $destino);
		//unlink("C:\Users\Sergio\Downloads\facturacion.txt");
	}


	public function exportTXT(){

		/**************************exporta txt*******/
		$idfactura = $this->input->get('idfactura');
		/*header("Content-Type: application/force-download");
        header("Content-Transfer-Encoding: binary");
        header("Content-disposition: attachment; filename=facturacion.txt");*/

            
        $data = array();
        $query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, c.fono, cp.nombre as cond_pago, cp.codigo as codigo_con_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, cor.nombre as nomdocumento, ma.nombre as com_sucursal, v.cod_interno as cod_interno FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join correlativos cor on (acc.tipo_documento = cor.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.id = '.$idfactura.'');

			$row = $query->result();

        
        if ($query->num_rows()>0){	
			
			$v = $row[0];
                    
            $nomdocumento = $v->nomdocumento;
            $fechafactura = $v->fecha_factura;
            $fechavenc = $v->fecha_venc;
            $idbodega = $v->id_bodega;
            
            $fecha = $v->fecha_factura;
			list($anio, $mes, $dia) = explode("-",$fecha);
			$fecha2 = $v->fecha_venc;
			list($anio2, $mes2, $dia2) = explode("-",$fecha2);

			$fechafactura = $dia."/".$mes."/".$anio;
            $fechavenc = $dia2."/".$mes2."/".$anio2;
			
			          
            $numdocumento = $v->num_factura;            
            $nomcliente = substr($v->nombre_cliente, 0, 40);            
            $condventa = $v->cond_pago;
            $codcondventa = substr($v->codigo_con_pago, -2);
            $vendedor = $v->nom_vendedor;
            $codvendedor = $v->id_vendedor;
            if ($v->ciu_sucursal){
            	$ciudad= $v->ciu_sucursal;            	
            }else{
            	$ciudad= $v->nombre_ciudad;
            };
            if ($v->com_sucursal){
            	$comuna= $v->com_sucursal;            	
            }else{
            	$comuna= $v->nombre_comuna;
            };
            $giro = substr($v->giro, 0, 42);
            $valornetocom = 18;
            $emisora ="";
            $sucsii = "";
            $codinterno=$v->cod_interno;
            $codinterno2="";
            $codImpuestoadic1="";
            $tasaImpadic1="";
            $valImpadic1="";
            $codImpuestoadic2="";
            $tasaImpadic2="";
            $valImpadic2="";
            $codImpuestoadic3="";
            $tasaImpadic3="";
            $valImpadic3="";
            $credespecial="";
            $montoperiodo="";
            $montototal="";
            $valordesc1 = "";
            $valordesc2 = "";
            $valordesc3 = "";
            $espaciosfin= "";
            $contactoreceptor="";
            if($v->direc_sucursal){
            	$direccionreceptor=$v->direc_sucursal;            	
            }else{
                $direccionreceptor=$v->direccion;
            };
            if($v->direc_sucursal){
            	$direcciondespacho=$v->direc_sucursal;            	
            }else{
                $direcciondespacho=$v->direccion;
            };
            $personalizadosl3="";
            $personalizadosl4="";
            $patentetrans="";
            $comunadespacho="";
            $ciudaddespacho="";
            $ruttransporte="";
            $neto = intval($v->neto);
            $exento="";
            $pordescuento="";
            $iva = intval($v->iva);            
            $total = intval($v->totalfactura);
            $id = $v->id;
            $espaciost = 25;
            $pregistro="";
		    $espacios20= 19;
		    $espacios5= 4;
		    $espacios2= 1;
		    $espacios8= 7;
		    $espacios9= 8;
		    $espacios35= 34;
		    $espacios40= 39;
		    $espacios42= 41;
		    $espacios60= 59;
		    $espacios80= 79;
		    $espacios300= 299;
		    $espacio300="";
		    $espacios30= 29;
		    $espacios110= 109;
		    $espacios139= 138;
		    $espaciolargo= 299;
		    $totalletras = (valorEnLetras($total));
		    $nomclienteinicio="                     ";
		    $espacios21= 20;
		    $espacios25= 24;

		    $rutautoriza = $v->rut_cliente;
		   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		   
		    };
		    if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $v->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };

		    $rutcliente = $v->rut_cliente;
		    if (strlen($rutcliente) == 9){
		    	$rutcliente=("0".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 8){
		    	$rutcliente=("00".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 7){
		    	$rutcliente=("000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 6){
		    	$rutcliente=("0000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 5){
		    	$rutcliente=("00000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 4){
		    	$rutcliente=("000000".$rutcliente);
		    	
		    };
		    $file_content = "";    
		    $file_content .= $idbodega.$this->crearespacios($espacios21 - strlen( $idbodega));
            $file_content .= ";";
            $file_content .= $condventa.$this->crearespacios($espacios25 - strlen( $condventa));  //Nombre condicion Pago
            $file_content .= ";";
            $file_content .= $vendedor.$this->crearespacios($espacios30 - strlen( $vendedor));  //Nombre Vendedor
            $file_content .= ";";
            $file_content .= $comuna;  //Codigo vendedor
            $file_content .= ";";
            $file_content .= $codcondventa; //Codigo condicion Pago
            $file_content .= chr(13).chr(10);  
                      		    
            //$file_content .= chr(13).chr(10);
            //$file_content .= " ";
            //$file_content .= ";";
            $file_content .= chr(13).chr(10);
            
            //$file_content .= chr(13).chr(10);
            $file_content .= " "; //espacio
            $file_content .= ";";
            $file_content .= str_pad($numdocumento,10," ",STR_PAD_LEFT); // Folio
            $file_content .= ";"; //
            $file_content .= $fechafactura;  //fecha Emision
            $file_content .= ";";
            $file_content .= " "; //indicador de no rebeja
            $file_content .= ";";
            $file_content .= " ";// tipo despacho
            $file_content .= ";";
            $file_content .= "0";// tipo de traslado
            $file_content .= ";";
            $file_content .= " ";// indicador servicio periodico
            $file_content .= ";";
            $file_content .= " ";// indicador montos brutos
            $file_content .= ";";
            $file_content .= "1";// DEBE SELECIONAR 1 O 2 SECUN TIPO DE PAGO
            $file_content .= ";";
            $file_content .= $fechavenc;
            $file_content .= ";";
            $file_content .= $emisora.$this->crearespacios($espacios20 - strlen($emisora));  //sucursal emision
            $file_content .= ";";
            $file_content .= $sucsii.$this->crearespacios($espacios9 - strlen( $sucsii));  //sucursal sii
            $file_content .= ";";
            $file_content .= $codinterno; // codigo vendedor
            $file_content .= ";";   
            $file_content .= $rutcliente; // rut receptor
            $file_content .= ";";
            $file_content .= "          "; // rut solicitante factura
            $file_content .= ";";
            $file_content .= "                    ";//codigo interno receptor
            $file_content .= ";";
            $file_content .= $nomcliente.$this->crearespacios($espacios40 - strlen( $nomcliente));  //razon social
            $file_content .= ";";
            $file_content .= $giro.$this->crearespacios($espacios42 - strlen( $giro)); //Giro receptor
            $file_content .= ";"; 
            $file_content .= $contactoreceptor.$this->crearespacios($espacios30 - strlen( $contactoreceptor)); // Contacto receptor
            $file_content .= ";";
            $file_content .= $direccionreceptor.$this->crearespacios($espacios60 - strlen( $direccionreceptor)); // Direccion receptor
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios110 - strlen( $personalizadosl3));
            $file_content .= ";";// Perzonalizados Linea 3
            $file_content .= chr(13).chr(10);
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad
            $file_content .= ";";
            $file_content .= $direcciondespacho.$this->crearespacios($espacios60 - strlen( $direcciondespacho)); // Direccion Despacho
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna Despacho
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad Despacho
            $file_content .= ";";
            $file_content .= $patentetrans.$this->crearespacios($espacios8 - strlen( $patentetrans)); //Patente Transporte
            $file_content .= ";";
            $file_content .= str_pad($ruttransporte,10," ",STR_PAD_LEFT); // rut Transportista
            $file_content .= ";";
            $file_content .= str_pad($neto,18," ",STR_PAD_LEFT); // Monto Neto
            $file_content .= ";";
            $file_content .= str_pad($exento,18," ",STR_PAD_LEFT); // Monto Exento
            $file_content .= ";";
            $file_content .= "     ";// Tasa Iva
            $file_content .= ";";
            $file_content .= str_pad($iva,18," ",STR_PAD_LEFT); // Monto Iva
            $file_content .= ";";
            $file_content .= "  "; // 2 espacios
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= str_pad($total,18," ",STR_PAD_LEFT); //Valor descuento 1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= chr(13).chr(10); // linea 4 fin
                        
            $query2 = $this->db->get_where('detalle_factura_cliente', array('id_factura' => $id));

            $c=0;

            foreach ($query2->result() as $z){

        	$this->db->where('id', $z->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];

			$c= $c+1;
				
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $producto->codigo.$this->crearespacios($espacios35 - strlen( $producto->codigo));  //razon social
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= $producto->nombre.$this->crearespacios($espacios80 - strlen( $producto->nombre));  //razon social
            $file_content .= ";";
            $file_content .= $espacio300.$this->crearespacios($espacios300 - strlen( $espacio300));
            $file_content .= ";";
            $file_content .= str_pad($z->cantidad,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "    "; // unidad de medidaN
            $file_content .= ";";
            $file_content .= str_pad($z->precio,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "   "; // Moneda 3 espaios
            $file_content .= ";";
            $file_content .= "          "; // Factor Conversion 10 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->descuento)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // recargo pesos 18 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->neto)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= " "; // Indicador de Agente 1 espacios
            $file_content .= ";";
            $file_content .= "              "; // 14 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios
            $file_content .= ";";
            $file_content .= "   %";
            $file_content .= chr(13).chr(10); //fin linea 5
                
            }

            if ($c < 30){
            	
            	$b = (30 - $c);

            	$producto="";
            	$productonombre="";
            	$cantidad="";
            	$precio="";
            	$descuento="";
            	$neto=0;

            	for ($i = 1; $i <= $b; $i++) {

            		//$file_content .= chr(13).chr(10); 
		            $file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));//razon social
		            $file_content .= chr(13).chr(10);
            
		            
		        }

            };
        };

           for ($a = 1; $a <= 10; $a++) {

            	//$file_content .= chr(13).chr(10);
            	$file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));
            	$file_content .= ";";
            	$file_content .= chr(13).chr(10);


            };

            //$file_content .= chr(13).chr(10);
            $file_content .= " "; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= $totalletras.$this->crearespacios($espacios80 - strlen( $totalletras));
		     //$file_content .= chr(13).chr(10);
		     //linea fin          
        
        $nombre_archivo = "33_NPG_".str_pad($numdocumento,7,"0",STR_PAD_LEFT).".spf";
        $path_archivo = './facturas/';
		$f_archivo = fopen($path_archivo.$nombre_archivo,'w');
		fwrite($f_archivo,$file_content);
		fclose($f_archivo);

		$data_archivo = basename($path_archivo.$nombre_archivo);
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename=' . $data_archivo);
		header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
		readfile($path_archivo.$nombre_archivo);			
		/*******************************fin exporta***********/

		//$origen='C:\Users\Sergio\Downloads\facturacion.txt';
		//$destino='C:\facturacion.txt';
		//mkdir(dirname($dstfile), 0777, true);
		//copy($origen, $destino);
		//unlink("C:\Users\Sergio\Downloads\facturacion.txt");
	}

	public function exportTXTFC(){

		/**************************exporta txt*******/
		$idfactura = $this->input->get('idfactura');
		/*header("Content-Type: application/force-download");
        header("Content-Transfer-Encoding: binary");
        header("Content-disposition: attachment; filename=facturacion.txt");*/

            
        $data = array();
        $query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, c.fono, cp.nombre as cond_pago, cp.codigo as codigo_con_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, cor.nombre as nomdocumento, ma.nombre as com_sucursal, v.cod_interno as cod_interno FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join correlativos cor on (acc.tipo_documento = cor.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.id = '.$idfactura.'');

			$row = $query->result();

        
        if ($query->num_rows()>0){	
			
			$v = $row[0];
                    
            $nomdocumento = $v->nomdocumento;
            $fechafactura = $v->fecha_factura;
            $fechavenc = $v->fecha_venc;
            $idbodega = $v->id_bodega;
            
            $fecha = $v->fecha_factura;
			list($anio, $mes, $dia) = explode("-",$fecha);
			$fecha2 = $v->fecha_venc;
			list($anio2, $mes2, $dia2) = explode("-",$fecha2);

			$fechafactura = $dia."/".$mes."/".$anio;
            $fechavenc = $dia2."/".$mes2."/".$anio2;
			
			          
            $numdocumento = $v->num_factura;            
            $nomcliente = substr($v->nombre_cliente, 0, 40);            
            $condventa = $v->cond_pago;
            $codcondventa = substr($v->codigo_con_pago, -2);
            $vendedor = $v->nom_vendedor;
            $codvendedor = $v->id_vendedor;
            if ($v->ciu_sucursal){
            	$ciudad= $v->ciu_sucursal;            	
            }else{
            	$ciudad= $v->nombre_ciudad;
            };
            if ($v->com_sucursal){
            	$comuna= $v->com_sucursal;            	
            }else{
            	$comuna= $v->nombre_comuna;
            };
            $giro = substr($v->giro, 0, 42);
            $valornetocom = 18;
            $emisora ="";
            $sucsii = "";
            $codinterno=$v->cod_interno;
            $codinterno2="";
            $codImpuestoadic1="";
            $tasaImpadic1="";
            $valImpadic1="";
            $codImpuestoadic2="";
            $tasaImpadic2="";
            $valImpadic2="";
            $codImpuestoadic3="";
            $tasaImpadic3="";
            $valImpadic3="";
            $credespecial="";
            $montoperiodo="";
            $montototal="";
            $valordesc1 = "";
            $valordesc2 = "";
            $valordesc3 = "";
            $espaciosfin= "";
            $contactoreceptor="";
            if($v->direc_sucursal){
            	$direccionreceptor=$v->direc_sucursal;            	
            }else{
                $direccionreceptor=$v->direccion;
            };
            if($v->direc_sucursal){
            	$direcciondespacho=$v->direc_sucursal;            	
            }else{
                $direcciondespacho=$v->direccion;
            };
            $personalizadosl3="";
            $personalizadosl4="";
            $patentetrans="";
            $comunadespacho="";
            $ciudaddespacho="";
            $ruttransporte="";
            $neto = intval($v->neto);
            $exento="";
            $pordescuento="";
            $iva = intval($v->iva);            
            $total = intval($v->totalfactura);
            $id = $v->id;
            $espaciost = 25;
            $pregistro="";
		    $espacios20= 19;
		    $espacios5= 4;
		    $espacios2= 1;
		    $espacios8= 7;
		    $espacios9= 8;
		    $espacios35= 34;
		    $espacios40= 39;
		    $espacios42= 41;
		    $espacios60= 59;
		    $espacios80= 79;
		    $espacios300= 299;
		    $espacio300="";
		    $espacios30= 29;
		    $espacios110= 109;
		    $espacios139= 138;
		    $espaciolargo= 299;
		    $totalletras = (valorEnLetras($total));
		    $nomclienteinicio="                     ";
		    $espacios21= 20;
		    $espacios25= 24;

		    $rutautoriza = $v->rut_cliente;
		   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		   
		    };
		    if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $v->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };

		    $rutcliente = $v->rut_cliente;
		    if (strlen($rutcliente) == 9){
		    	$rutcliente=("0".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 8){
		    	$rutcliente=("00".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 7){
		    	$rutcliente=("000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 6){
		    	$rutcliente=("0000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 5){
		    	$rutcliente=("00000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 4){
		    	$rutcliente=("000000".$rutcliente);
		    	
		    };
		    $file_content = "";    
		    $file_content .= $idbodega.$this->crearespacios($espacios21 - strlen( $idbodega));
            $file_content .= ";";
            $file_content .= $condventa.$this->crearespacios($espacios25 - strlen( $condventa));  //Nombre condicion Pago
            $file_content .= ";";
            $file_content .= $vendedor.$this->crearespacios($espacios30 - strlen( $vendedor));  //Nombre Vendedor
            $file_content .= ";";
            $file_content .= $comuna;  //Codigo vendedor
            $file_content .= ";";
            $file_content .= $codcondventa; //Codigo condicion Pago
            $file_content .= chr(13).chr(10);  
                      		    
            //$file_content .= chr(13).chr(10);
            //$file_content .= " ";
            //$file_content .= ";";
            $file_content .= chr(13).chr(10);
            
            //$file_content .= chr(13).chr(10);
            $file_content .= " "; //espacio
            $file_content .= ";";
            $file_content .= str_pad($numdocumento,10," ",STR_PAD_LEFT); // Folio
            $file_content .= ";"; //
            $file_content .= $fechafactura;  //fecha Emision
            $file_content .= ";";
            $file_content .= " "; //indicador de no rebeja
            $file_content .= ";";
            $file_content .= " ";// tipo despacho
            $file_content .= ";";
            $file_content .= "0";// tipo de traslado
            $file_content .= ";";
            $file_content .= " ";// indicador servicio periodico
            $file_content .= ";";
            $file_content .= " ";// indicador montos brutos
            $file_content .= ";";
            $file_content .= "1";// DEBE SELECIONAR 1 O 2 SECUN TIPO DE PAGO
            $file_content .= ";";
            $file_content .= $fechavenc;
            $file_content .= ";";
            $file_content .= $emisora.$this->crearespacios($espacios20 - strlen($emisora));  //sucursal emision
            $file_content .= ";";
            $file_content .= $sucsii.$this->crearespacios($espacios9 - strlen( $sucsii));  //sucursal sii
            $file_content .= ";";
            $file_content .= $codinterno; // codigo vendedor
            $file_content .= ";";   
            $file_content .= $rutcliente; // rut receptor
            $file_content .= ";";
            $file_content .= "          "; // rut solicitante factura
            $file_content .= ";";
            $file_content .= "                    ";//codigo interno receptor
            $file_content .= ";";
            $file_content .= $nomcliente.$this->crearespacios($espacios40 - strlen( $nomcliente));  //razon social
            $file_content .= ";";
            $file_content .= $giro.$this->crearespacios($espacios42 - strlen( $giro)); //Giro receptor
            $file_content .= ";"; 
            $file_content .= $contactoreceptor.$this->crearespacios($espacios30 - strlen( $contactoreceptor)); // Contacto receptor
            $file_content .= ";";
            $file_content .= $direccionreceptor.$this->crearespacios($espacios60 - strlen( $direccionreceptor)); // Direccion receptor
            $file_content .= ";";
            $file_content .= $personalizadosl3.$this->crearespacios($espacios110 - strlen( $personalizadosl3));
            $file_content .= ";";// Perzonalizados Linea 3
            $file_content .= chr(13).chr(10);
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad
            $file_content .= ";";
            $file_content .= $direcciondespacho.$this->crearespacios($espacios60 - strlen( $direcciondespacho)); // Direccion Despacho
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna Despacho
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad Despacho
            $file_content .= ";";
            $file_content .= $patentetrans.$this->crearespacios($espacios8 - strlen( $patentetrans)); //Patente Transporte
            $file_content .= ";";
            $file_content .= str_pad($ruttransporte,10," ",STR_PAD_LEFT); // rut Transportista
            $file_content .= ";";
            $file_content .= str_pad($neto,18," ",STR_PAD_LEFT); // Monto Neto
            $file_content .= ";";
            $file_content .= str_pad($exento,18," ",STR_PAD_LEFT); // Monto Exento
            $file_content .= ";";
            $file_content .= "     ";// Tasa Iva
            $file_content .= ";";
            $file_content .= str_pad($iva,18," ",STR_PAD_LEFT); // Monto Iva
            $file_content .= ";";
            $file_content .= "  "; // 2 espacios
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= str_pad($total,18," ",STR_PAD_LEFT); //Valor descuento 1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= chr(13).chr(10); // linea 4 fin
                        
            $query2 = $this->db->get_where('detalle_factura_cliente', array('id_factura' => $id));

            $c=0;

            foreach ($query2->result() as $z){

        	$this->db->where('id', $z->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];

			$c= $c+1;
				
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $producto->codigo.$this->crearespacios($espacios35 - strlen( $producto->codigo));  //razon social
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= $producto->nombre.$this->crearespacios($espacios80 - strlen( $producto->nombre));  //razon social
            $file_content .= ";";
            $file_content .= $espacio300.$this->crearespacios($espacios300 - strlen( $espacio300));
            $file_content .= ";";
            $file_content .= str_pad($z->cantidad,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "    "; // unidad de medidaN
            $file_content .= ";";
            $file_content .= str_pad($z->precio,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "   "; // Moneda 3 espaios
            $file_content .= ";";
            $file_content .= "          "; // Factor Conversion 10 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->descuento)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // recargo pesos 18 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->neto)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= " "; // Indicador de Agente 1 espacios
            $file_content .= ";";
            $file_content .= "              "; // 14 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios
            $file_content .= ";";
            $file_content .= "   %";
            $file_content .= chr(13).chr(10); //fin linea 5
                
            }

            if ($c < 30){
            	
            	$b = (30 - $c);

            	$producto="";
            	$productonombre="";
            	$cantidad="";
            	$precio="";
            	$descuento="";
            	$neto=0;

            	for ($i = 1; $i <= $b; $i++) {

            		//$file_content .= chr(13).chr(10); 
		            $file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));//razon social
		            $file_content .= chr(13).chr(10);
            
		            
		        }

            };
        };

           for ($a = 1; $a <= 10; $a++) {

            	//$file_content .= chr(13).chr(10);
            	$file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));
            	$file_content .= ";";
            	$file_content .= chr(13).chr(10);


            };

            //$file_content .= chr(13).chr(10);
            $file_content .= " "; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= $totalletras.$this->crearespacios($espacios80 - strlen( $totalletras));
		     //$file_content .= chr(13).chr(10);
		     //linea fin          
        
        $nombre_archivo = "33_NPG_".str_pad($numdocumento,7,"0",STR_PAD_LEFT).".spf";
        $path_archivo = './facturas/';
		$f_archivo = fopen($path_archivo.$nombre_archivo,'w');
		fwrite($f_archivo,$file_content);
		fclose($f_archivo);

		$data_archivo = basename($path_archivo.$nombre_archivo);
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename=' . $data_archivo);
		header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
		readfile($path_archivo.$nombre_archivo);			
		/*******************************fin exporta***********/

		//$origen='C:\Users\Sergio\Downloads\facturacion.txt';
		//$destino='C:\facturacion.txt';
		//mkdir(dirname($dstfile), 0777, true);
		//copy($origen, $destino);
		//unlink("C:\Users\Sergio\Downloads\facturacion.txt");
	}

	public function exportTXTlote(){

		/**************************exporta txt*******/
		$idfactura = $this->input->get('idfactura');
		/*header("Content-Type: application/force-download");
        header("Content-Transfer-Encoding: binary");
        header("Content-disposition: attachment; filename=facturacion.txt");*/

            
        $data = array();
        $query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, c.fono, cp.nombre as cond_pago, cp.codigo as codigo_con_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, cor.nombre as nomdocumento, ma.nombre as com_sucursal, v.cod_interno as cod_interno FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join correlativos cor on (acc.tipo_documento = cor.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.id = '.$idfactura.'');

			$row = $query->result();

        
        if ($query->num_rows()>0){	
			
			$v = $row[0];
                    
            $nomdocumento = $v->nomdocumento;
            $fechafactura = $v->fecha_factura;
            $fechavenc = $v->fecha_venc;
            $idbodega = $v->id_bodega;
            
            $fecha = $v->fecha_factura;
			list($anio, $mes, $dia) = explode("-",$fecha);
			$fecha2 = $v->fecha_venc;
			list($anio2, $mes2, $dia2) = explode("-",$fecha2);

			$fechafactura = $dia."/".$mes."/".$anio;
            $fechavenc = $dia2."/".$mes2."/".$anio2;
			
			          
            $numdocumento = $v->num_factura;            
            $nomcliente = substr($v->nombre_cliente, 0, 40);            
            $condventa = $v->cond_pago;
            $codcondventa = substr($v->codigo_con_pago, -2);
            $vendedor = $v->nom_vendedor;
            $codvendedor = $v->id_vendedor;
            if ($v->ciu_sucursal){
            	$ciudad= $v->ciu_sucursal;            	
            }else{
            	$ciudad= $v->nombre_ciudad;
            };
            if ($v->com_sucursal){
            	$comuna= $v->com_sucursal;            	
            }else{
            	$comuna= $v->nombre_comuna;
            };
            $giro = substr($v->giro, 0, 42);
            $valornetocom = 18;
            $emisora ="";
            $sucsii = "";
            $codinterno=$v->cod_interno;
            $codinterno2="";
            $codImpuestoadic1="";
            $tasaImpadic1="";
            $valImpadic1="";
            $codImpuestoadic2="";
            $tasaImpadic2="";
            $valImpadic2="";
            $codImpuestoadic3="";
            $tasaImpadic3="";
            $valImpadic3="";
            $credespecial="";
            $montoperiodo="";
            $montototal="";
            $valordesc1 = "";
            $valordesc2 = "";
            $valordesc3 = "";
            $espaciosfin= "";
            $contactoreceptor="";
            if($v->direc_sucursal){
            	$direccionreceptor=$v->direc_sucursal;            	
            }else{
                $direccionreceptor=$v->direccion;
            };
            if($v->direc_sucursal){
            	$direcciondespacho=$v->direc_sucursal;            	
            }else{
                $direcciondespacho=$v->direccion;
            };
            $personalizadosl3="";
            $personalizadosl4="";
            $patentetrans="";
            $comunadespacho="";
            $ciudaddespacho="";
            $ruttransporte="";
            $neto = intval($v->neto);
            $exento="";
            $pordescuento="";
            $iva = intval($v->iva);            
            $total = intval($v->totalfactura);
            $id = $v->id;
            $espaciost = 25;
            $pregistro="";
		    $espacios20= 19;
		    $espacios5= 4;
		    $espacios2= 1;
		    $espacios8= 7;
		    $espacios9= 8;
		    $espacios35= 34;
		    $espacios40= 39;
		    $espacios42= 41;
		    $espacios60= 59;
		    $espacios80= 79;
		    $espacios300= 299;
		    $espacios500= 499;
		    $espacio300="";
		    $espacios30= 29;
		    $espacios110= 109;
		    $espacios139= 138;
		    $espaciolargo= 299;
		    $totalletras = (valorEnLetras($total));
		    $nomclienteinicio="                     ";
		    $espacios21= 20;
		    $espacios25= 24;

		    $rutautoriza = $v->rut_cliente;
		   	if (strlen($rutautoriza) == 8){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -8, 1);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		    };
		    if (strlen($rutautoriza) == 9){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 3);
		      $ruta3 = substr($rutautoriza, -7, 3);
		      $ruta4 = substr($rutautoriza, -9, 2);
		      $v->rut_cliente = ($ruta4.$ruta3.$ruta2."-".$ruta1);
		   
		    };
		    if (strlen($rutautoriza) == 2){
		      $ruta1 = substr($rutautoriza, -1);
		      $ruta2 = substr($rutautoriza, -4, 1);
		      $v->rut_cliente = ($ruta2."-".$ruta1);
		     
		    };

		    $rutcliente = $v->rut_cliente;
		    if (strlen($rutcliente) == 9){
		    	$rutcliente=("0".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 8){
		    	$rutcliente=("00".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 7){
		    	$rutcliente=("000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 6){
		    	$rutcliente=("0000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 5){
		    	$rutcliente=("00000".$rutcliente);
		    	
		    };
		    if (strlen($rutcliente) == 4){
		    	$rutcliente=("000000".$rutcliente);
		    	
		    };
		    $file_content = "";    
		    //$file_content .= $idbodega.$this->crearespacios($espacios21 - strlen( $idbodega));
            $file_content .= "                     "; //21 espacios
            $file_content .= ";";
            $file_content .= $condventa.$this->crearespacios($espacios25 - strlen( $condventa));  //Nombre condicion Pago
            $file_content .= ";";
            $file_content .= $vendedor.$this->crearespacios($espacios30 - strlen( $vendedor));  //Nombre Vendedor
            $file_content .= ";";
            $file_content .= "  "; 
            $file_content .= ";";           
            $file_content .= "02";  //Codigo vendedor
            $file_content .= chr(13).chr(10);
            $file_content .= chr(13).chr(10);
            $file_content .= " "; //espacio
            $file_content .= ";";
            $file_content .= str_pad($numdocumento,10," ",STR_PAD_LEFT); // Folio
            $file_content .= ";"; //
            $file_content .= $fechafactura;  //fecha Emision
            $file_content .= ";";
            $file_content .= " "; //indicador de no rebeja
            $file_content .= ";";
            $file_content .= " ";// tipo despacho
            $file_content .= ";";
            $file_content .= "0";// tipo de traslado
            $file_content .= ";";
            $file_content .= " ";// indicador servicio periodico
            $file_content .= ";";
            $file_content .= " ";// indicador montos brutos
            $file_content .= ";";
            $file_content .= "2";// DEBE SELECIONAR 1 O 2 SECUN TIPO DE PAGO
            $file_content .= ";";
            $file_content .= $fechavenc;
            $file_content .= ";";
            $file_content .= $emisora.$this->crearespacios($espacios20 - strlen($emisora));  //sucursal emision
            $file_content .= ";";
            $file_content .= $sucsii.$this->crearespacios($espacios9 - strlen( $sucsii));  //sucursal sii
            $file_content .= ";";
            $file_content .= $codinterno; // codigo vendedor
            $file_content .= ";";   
            $file_content .= $rutcliente; // rut receptor
            $file_content .= ";";
            $file_content .= "          "; // rut solicitante factura
            $file_content .= ";";
            $file_content .= "                    ";//codigo interno receptor
            $file_content .= ";";
            $file_content .= $nomcliente.$this->crearespacios($espacios40 - strlen( $nomcliente));  //razon social
            $file_content .= ";";
            $file_content .= $giro.$this->crearespacios($espacios42 - strlen( $giro)); //Giro receptor
            $file_content .= ";"; 
            $file_content .= $contactoreceptor.$this->crearespacios($espacios30 - strlen( $contactoreceptor)); // Contacto receptor
            $file_content .= ";";
            $file_content .= $direccionreceptor.$this->crearespacios($espacios60 - strlen( $direccionreceptor)); // Direccion receptor
            $file_content .= ";";
            $file_content .= "                                "; // 31 edpacios
            $file_content .= ";";
            $file_content .= "                                         "; // 40 edpacios
            $file_content .= ";";
            $file_content .= "                                         "; // 40 edpacios
            $file_content .= chr(13).chr(10);
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad
            $file_content .= ";";
            $file_content .= $direcciondespacho.$this->crearespacios($espacios60 - strlen( $direcciondespacho)); // Direccion Despacho
            $file_content .= ";";
            $file_content .= $comuna.$this->crearespacios($espacios20 - strlen( $comuna)); //Comuna Despacho
            $file_content .= ";";
            $file_content .= $ciudad.$this->crearespacios($espacios20 - strlen( $ciudad)); //Ciudad Despacho
            $file_content .= ";";
            $file_content .= $patentetrans.$this->crearespacios($espacios8 - strlen( $patentetrans)); //Patente Transporte
            $file_content .= ";";
            $file_content .= str_pad($ruttransporte,10," ",STR_PAD_LEFT); // rut Transportista
            $file_content .= ";";
            $file_content .= str_pad($neto,18," ",STR_PAD_LEFT); // Monto Neto
            $file_content .= ";";
            $file_content .= str_pad($exento,18," ",STR_PAD_LEFT); // Monto Exento
            $file_content .= ";";
            $file_content .= "     ";// 5 Tasa Iva
            $file_content .= ";";
            $file_content .= str_pad($iva,18," ",STR_PAD_LEFT); // Monto Iva
            $file_content .= ";";
            $file_content .= "  "; // 2 espacios
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "      "; //6 espacios
            $file_content .= ";";
            $file_content .= "     "; // 5 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= str_pad($total,18," ",STR_PAD_LEFT); //Valor descuento 1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= "                  "; // 18 espacios al imp1
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= " ";// 1 espacio
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= ";";
            $file_content .= "                              ";// Perzonalizados 30
            $file_content .= chr(13).chr(10);
            $guiasdetalle="";
            $glosa = "SEGUN GUIAS:";            
            $c=0;
            
            $query2 = $this->db->get_where('detalle_factura_glosa', array('id_factura' => $id));

            
            foreach ($query2->result() as $y){

            $query3 = $this->db->get_where('detalle_factura_cliente', array('id_factura' => $y->id_guia));

            if (strlen($y->num_guia) == 3){
              	$y->num_guia="0000".$y->num_guia;
		    	
		    };
		    if (strlen($y->num_guia) == 4){
              	$y->num_guia="000".$y->num_guia;
		    	
		    };
		    if (strlen($y->num_guia) == 5){
              	$y->num_guia="00".$y->num_guia;
		    	
		    };
		    if (strlen($y->num_guia) == 6){
              	$y->num_guia="0".$y->num_guia;
		    	
		    };            

            $guiasdetalle = ($guiasdetalle.$y->num_guia)." ";

           
            foreach ($query3->result() as $z){

            $c= $c+1;        


        	$this->db->where('id', $z->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];

			
				
            //$file_content .= chr(13).chr(10);
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $producto->codigo.$this->crearespacios($espacios35 - strlen( $producto->codigo));  //razon social
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= $producto->nombre.$this->crearespacios($espacios80 - strlen( $producto->nombre));  //razon social
            $file_content .= ";";
            $file_content .= $espacio300.$this->crearespacios($espacios300 - strlen( $espacio300));
            $file_content .= ";";
            $file_content .= str_pad($z->cantidad,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "    "; // 4 unidad de medidaN
            $file_content .= ";";
            $file_content .= str_pad($z->precio,18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= "                  "; // 18 precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "   "; // Moneda 3 espaios
            $file_content .= ";";
            $file_content .= "          "; // Factor Conversion 10 espacios
            $file_content .= ";";
            $file_content .= "                  "; // 18 precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "                  "; // recargo pesos 18 espacios
            $file_content .= ";";
            $file_content .= str_pad((intval($z->neto)),18," ",STR_PAD_LEFT); // Valor descuento 3
            $file_content .= ";";
            $file_content .= " "; // Indicador de Agente 1 espacios
            $file_content .= ";";
            $file_content .= "                    "; // 20 espacios
            $file_content .= ";";
            $file_content .= "000000000000000000"; // 18 espacios
            $file_content .= ";";
            $file_content .= "000000000000000000              "; // 18 "0" y 14 ""
            $file_content .= ";";
            $file_content .= chr(13).chr(10); //fin linea 5
                
            }

            
            } 

			$c= $c+1;
				
            //$file_content .= chr(13).chr(10);
            $producto=" ";
            $file_content .= " ";
            $file_content .= ";";
            $file_content .= $producto.$this->crearespacios($espacios35 - strlen( $producto));  //razon social
            $file_content .= ";";
            $file_content .= " "; // 1 espacio
            $file_content .= ";";
            $file_content .= $glosa.$this->crearespacios($espacios80 - strlen( $glosa));  //razon social
            $file_content .= ";";
            $file_content .= $espacio300.$this->crearespacios($espacios300 - strlen( $espacio300));
            $file_content .= ";";
            $file_content .= "                  ";// Perzonalizados 18
            $file_content .= ";";
            $file_content .= "    "; // 4 unidad de medidaN
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "   "; // Moneda 3 espaios
            $file_content .= ";";
            $file_content .= "          "; // Factor Conversion 10 espacios
            $file_content .= ";";
            $file_content .= "                  "; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= "                  "; // recargo pesos 18 espacios
            $file_content .= ";";
            $file_content .= "                 0"; // precio otra moneda 18 espacios
            $file_content .= ";";
            $file_content .= " "; // Indicador de Agente 1 espacios
            $file_content .= ";";
            $file_content .= "                    "; // 20 espacios
            $file_content .= ";";
            $file_content .= "                                                  "; // 50 
            $file_content .= ";";
            $file_content .= $guiasdetalle.$this->crearespacios($espacios500 - strlen( $guiasdetalle));
            $file_content .= chr(13).chr(10);
                           
            if ($c < 30){
            	
            	$b = (30 - $c);

            	$producto="";
            	$productonombre="";
            	$cantidad="";
            	$precio="";
            	$descuento="";
            	$neto=0;

            	for ($i = 1; $i <= $b; $i++) {

            		//$file_content .= chr(13).chr(10); 
		            $file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));//razon social
		            $file_content .= chr(13).chr(10);
            
		            
		        }

            };
        };

           for ($a = 1; $a <= 10; $a++) {

            	//$file_content .= chr(13).chr(10);
            	$file_content .= $espaciosfin.$this->crearespacios($espacios139 - strlen( $espaciosfin));
            	$file_content .= ";";
            	$file_content .= chr(13).chr(10);


            };

            //$file_content .= chr(13).chr(10);
            $file_content .= " "; // Indicador de Agente 1 espacios
		    $file_content .= ";";
		    $file_content .= $totalletras.$this->crearespacios($espacios80 - strlen( $totalletras));
		     //$file_content .= chr(13).chr(10);
		     //linea fin          
        
        $nombre_archivo = "33_NPG_".str_pad($numdocumento,7,"0",STR_PAD_LEFT).".spf";
        $path_archivo = './facturas/';
		$f_archivo = fopen($path_archivo.$nombre_archivo,'w');
		fwrite($f_archivo,$file_content);
		fclose($f_archivo);

		$data_archivo = basename($path_archivo.$nombre_archivo);
		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename=' . $data_archivo);
		header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
		readfile($path_archivo.$nombre_archivo);			
		/*******************************fin exporta***********/

		//$origen='C:\Users\Sergio\Downloads\facturacion.txt';
		//$destino='C:\facturacion.txt';
		//mkdir(dirname($dstfile), 0777, true);
		//copy($origen, $destino);
		//unlink("C:\Users\Sergio\Downloads\facturacion.txt");
	}

    public function exportFePDF($idfactura,$cedible = null){

        $this->load->model('facturaelectronica');
        $this->facturaelectronica->exportFePDF($idfactura,'id',$cedible);       

    }


	public function exportPDF(){

        $idfactura = $this->input->get('idfactura');
        $numero = $this->input->get('numfactura');
       
        $this->load->model('facturaelectronica');
        $datos_factura = $this->facturaelectronica->get_factura($idfactura);

        //$cabecera = $this->db->get_where('factura_clientes', array('id' => $idfactura));  
        $tipodocumento = isset($datos_factura->tipo_documento) ? $datos_factura->tipo_documento : 1;
        /*foreach($cabecera->result() as $v){  
                $tipodocumento = $v->tipo_documento; 
        }*/

        if($tipodocumento == 1){
                $this->exportFacturaPDF($idfactura,$numero);

        }else if($tipodocumento ==  101 || $tipodocumento == 103 || $tipodocumento == 105 || $tipodocumento == 107 || $tipodocumento == 120){ // FACTURA ELECTRONICA O FACTURA EXENTA ELECTRONCA O GUIA DE DESPACHO
                //$es_cedible = is_null($cedible) ? false : true;
                $this->load->model('facturaelectronica');
                $this->facturaelectronica->exportFePDF($idfactura,'id');

        }else{

                $this->exportBoletaPDF($idfactura,$numero);

        }

    }

    public function ver_libro($idlibro){
        $this->load->model('facturaelectronica');
        $libro = $this->facturaelectronica->get_libro_by_id($idlibro);
        $path_archivo = "./facturacion_electronica/libros/";
        $data_archivo = basename($path_archivo.$libro->archivo);
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=' . $data_archivo);
        header('Content-Length: ' . filesize($path_archivo.$libro->archivo));
        readfile($path_archivo.$libro->archivo);            
     }

    public function ver_dte($idfactura,$tipo = 'sii'){

        $ruta = $tipo == 'cliente' ? 'dte_cliente' : 'dte';
        $this->load->model('facturaelectronica');
        $dte = $this->facturaelectronica->datos_dte($idfactura);

       
        if(empty($dte)){
            $dte = $this->facturaelectronica->crea_dte($idfactura);
        }else{

            if($dte->{$ruta} == ''){
                $dte = $this->facturaelectronica->crea_dte($idfactura,$tipo);
            }
        }       

        $nombre_archivo = $tipo == 'cliente' ? $dte->archivo_dte_cliente : $dte->archivo_dte;
        $path_archivo = "./facturacion_electronica/" . $ruta . "/".$dte->path_dte;
        $data_archivo = basename($path_archivo.$nombre_archivo);

        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=' . $data_archivo);
        header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
        readfile($path_archivo.$nombre_archivo);                
     }


     public function ver_consumo_folios($idconsumofolios){

        $this->load->model('facturaelectronica');
        $dte = $this->facturaelectronica->get_consumo_folios_by_id($idconsumofolios);
       // print_r($dte); exit;
       
       /* if(empty($dte)){
            $dte = $this->facturaelectronica->crea_dte($idfactura);
        }else{

            if($dte->{$ruta} == ''){
                $dte = $this->facturaelectronica->crea_dte($idfactura,$tipo);
            }
        }*/       

        $nombre_archivo = $dte->archivo_consumo_folios;
        $path_archivo = "./facturacion_electronica/Consumo_Folios/".$dte->path_consumo_folios;
        $data_archivo = basename($path_archivo.$nombre_archivo);

        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename=' . $data_archivo);
        header('Content-Length: ' . filesize($path_archivo.$nombre_archivo));
        readfile($path_archivo.$nombre_archivo);                
     }

     public function datos_dte_json($idfactura){
        $this->load->model('facturaelectronica');
        $datos = $this->facturaelectronica->datos_dte($idfactura);
        $empresa_factura = $this->facturaelectronica->get_empresa_factura($idfactura);
        $datos->e_mail = $empresa_factura->e_mail;
        echo json_encode($datos);
    }


  public function datos_dte_json_consumo($idconsumo){
        $this->load->model('facturaelectronica');
        $datos = $this->facturaelectronica->get_consumo_folios_by_id($idconsumo);
        //$empresa_factura = $this->facturaelectronica->get_empresa_factura($idfactura);
        //$datos->e_mail = $empresa_factura->e_mail;
        echo json_encode($datos);
    }


  public function estado_envio_dte($idfactura){
    $this->load->model('facturaelectronica');
    $datos_dte = $this->facturaelectronica->datos_dte($idfactura);
    $config = $this->facturaelectronica->genera_config();
    include $this->facturaelectronica->ruta_libredte();
    $empresa = $this->facturaelectronica->get_empresa();

    $result = array();
    $result['error'] = false;
    $result['codigo'] = "";
    $result['glosa'] = "";

    $token = \sasco\LibreDTE\Sii\Autenticacion::getToken($config['firma']);
    if (!$token) {
        foreach (\sasco\LibreDTE\Log::readAll() as $error){
          $result['error'] = true;

        }
        $result['message'] = "Error de conexión con SII";      
        echo json_encode($result);
        exit;
    }

    // consultar estado enviado
    $rut = $empresa->rut;
    $dv = $empresa->dv;
    $trackID = $datos_dte->trackid; // se obtiene al enviar un dte  $track_id = $EnvioDTE->enviar();
    $estado = \sasco\LibreDTE\Sii::request('QueryEstUp', 'getEstUp', [$rut, $dv, $trackID, $token]);
    // si el estado se pudo recuperar se muestra estado y glosa
    if ($estado!==false) {
        $result['error'] = false;
        $result['codigo'] = (string)$estado->xpath('/SII:RESPUESTA/SII:RESP_HDR/ESTADO')[0];      
        $result['glosa'] = (string)$estado->xpath('/SII:RESPUESTA/SII:RESP_HDR/ESTADO')[0] != -11 ? (string)$estado->xpath('/SII:RESPUESTA/SII:RESP_HDR/GLOSA')[0] : "Trackid Err&oacute;neo";      
        echo json_encode($result);
        exit;
    }

    // mostrar error si hubo
    foreach (\sasco\LibreDTE\Log::readAll() as $error){
        $result['error'] = true;
        $result['message'] = "Error de conexión con SII";
    }
    echo json_encode($result);
    exit;
  } 



public function estado_envio_consumo_folios($idconsumo){
    $this->load->model('facturaelectronica');
    $datos_dte = $this->facturaelectronica->get_consumo_folios_by_id($idconsumo);
    $config = $this->facturaelectronica->genera_config();
    include $this->facturaelectronica->ruta_libredte();
    $empresa = $this->facturaelectronica->get_empresa();

    $result = array();
    $result['error'] = false;
    $result['codigo'] = "";
    $result['glosa'] = "";

    $token = \sasco\LibreDTE\Sii\Autenticacion::getToken($config['firma']);
    if (!$token) {
        foreach (\sasco\LibreDTE\Log::readAll() as $error){
          $result['error'] = true;

        }
        $result['message'] = "Error de conexión con SII";      
        echo json_encode($result);
        exit;
    }

    // consultar estado enviado
    $rut = $empresa->rut;
    $dv = $empresa->dv;
    $trackID = $datos_dte->trackid; // se obtiene al enviar un dte  $track_id = $EnvioDTE->enviar();
    $estado = \sasco\LibreDTE\Sii::request('QueryEstUp', 'getEstUp', [$rut, $dv, $trackID, $token]);
    // si el estado se pudo recuperar se muestra estado y glosa
    if ($estado!==false) {
        $result['error'] = false;
        $result['codigo'] = (string)$estado->xpath('/SII:RESPUESTA/SII:RESP_HDR/ESTADO')[0];      
        $result['glosa'] = (string)$estado->xpath('/SII:RESPUESTA/SII:RESP_HDR/ESTADO')[0] != -11 ? (string)$estado->xpath('/SII:RESPUESTA/SII:RESP_HDR/GLOSA')[0] : "Trackid Err&oacute;neo";      
        echo json_encode($result);
        exit;
    }

    // mostrar error si hubo
    foreach (\sasco\LibreDTE\Log::readAll() as $error){
        $result['error'] = true;
        $result['message'] = "Error de conexión con SII";
    }
    echo json_encode($result);
    exit;
  } 

	//$idfactura,$numero

	public function exportFacturaPDF($idfactura,$numero){

		//$idfactura = $this->input->get('idfactura');
		//$numero = $this->input->get('numfactura');

        if ($idfactura){
		$query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, c.fono, p.num_ticket, cp.nombre as cond_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, ma.nombre as com_sucursal FROM factura_clientes acc
			left join preventa p on acc.id = p.id_documento
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.id = '.$idfactura.'');
		}else{
			$query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, c.fono, p.num_ticket, cp.nombre as cond_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, ma.nombre as com_sucursal FROM factura_clientes acc
			left join preventa p on acc.id = p.id_documento
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join comuna m on (c.id_comuna = ma.id)
			left join ciudad s on (c.id_ciudad = sa.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = m.id)
			left join ciudad sa on (cs.id_ciudad = s.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.num_factura = '.$numero.'

		');


		}

		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		$fecha = $row->fecha_venc;
		list($anio, $mes, $dia) = explode("-",$fecha);
		$fecha2 = $row->fecha_factura;
		list($anio2, $mes2, $dia2) = explode("-",$fecha2);

		if ($row->forma == 0){		 
		//items
		$items = $this->db->get_where('detalle_factura_cliente', array('id_factura' => $row->id));
		//print_r($items->result());exit;
		//variables generales
		$codigo = $row->num_factura;
		$nombre_contacto = $row->nombre_cliente;
		$observacion = $row->observacion;
		$rut_cliente = $row->rut_cliente;
		$rut_obs = $row->rut_obs;
		$nom_obs = $row->nom_observ;
		if ($row->direc_sucursal){
			$direccion = $row->direc_sucursal;
			$comuna = $row->com_sucursal;
			$ciudad = $row->ciu_sucursal;	
		}else{
			$direccion = $row->direccion;
			$comuna = $row->nombre_comuna;
			$ciudad = $row->nombre_ciudad;
	    };
		$fecha = $row->fecha_venc;
		$giro = $row->giro;
		$fono = $row->fono;
		$ticket_text = $row->num_ticket != '' ? "Nro. Vale <br> ". $row->num_ticket :  "&nbsp;";
		$cabecera = $this->db->get_where('factura_clientes', array('id' => $row->id));		
		$forma_pago = $row->cond_pago;
		$montoNeto = 0;
	    $ivaTotal = 0;
		$totalFactura = 0;
		foreach($cabecera->result() as $reg){
			$montoAfecto = $reg->sub_total;
			$montoDescuento = $reg->descuento;
			$montoNeto = $reg->neto;
			$ivaTotal = $reg->iva;
			$totalFactura = $reg->totalfactura;
		}
				
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		 

		$html = '
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.cajaInput {
	border: 1px dotted #ED1B24;
}
.style5 {color: #FF0000; font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
.style6 {	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
.colorTextoFijo {	color:#008F9F;
	font-weight: bold;
	font:Arial, Helvetica, sans-serif;
}
.lineaDivisoria {
	border-bottom-style:dotted;
	border-bottom-color:#ED1B24;
	border-bottom-width:1px;
	height: 2px;
}
.cajaInputIzq {
	border-top-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-right-width: 1px;
	border-top-style: dotted;
	border-bottom-style: dotted;
	border-left-style: dotted;
	border-right-style: dotted;
	border-top-color: #ED1B24;
	border-bottom-color: #ED1B24;
	border-left-color: #ED1B24;
	border-right-color: #ED1B24;
}
.style9 {font-size: 8px;
font-family: Arial, Helvetica, sans-serif;
}
.style12 {color: #FFFFFF}
.style13 {font-size: 12px; font-family: Arial, Helvetica, sans-serif;}
.style14 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #FFFFFF; }
.style15 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: bold;
	color: #FFFFFF;
}
-->
</style>
</head>

<body>
   <table width="987px" border="0">
   	 
      <tr>
      	<td width="250px"  height="110px">&nbsp;</td>
        <td width="408px"  height="110px" style="font-size: 20px;vertical-align:bottom;">&nbsp;'. $dia2 . '&nbsp;&nbsp;&nbsp;&nbsp;'.$mes2.'&nbsp;&nbsp;&nbsp;&nbsp;'.$anio2.'</td>
        <td width="329px"  height="110px" >
        <table width="329px" border="0">
        	<tr >
        		<td width="329px" height="100px" style="font-size: 20px;vertical-align:bottom;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$codigo.'</b></td>
        	</tr>
        	<tr >
        		<td width="329px" height="10px">&nbsp;</td>
        	</tr>        	
        </table>
        </td>
      </tr> 
      <tr>
      <td colspan="3" height="110px">
      	<table width="987px" border="0">
      	<tr>
      		<td width="100px" height="30px">&nbsp;</td>
      		<td width="400px" height="30px">'. $nombre_contacto .'</td>
      		<td width="278px" height="30px">&nbsp;</td>
      		<td width="209px" height="30px">' . number_format(substr($rut_cliente,0,strlen($rut_cliente) - 1),0,".",".")."-".substr($rut_cliente,-1) . '</td>
      	</tr>
      	<tr>
      		<td width="100px" height="15px">&nbsp;</td>
      		<td width="400px" height="15px">'. $direccion .'</td>
      		<td width="278px" height="15px">&nbsp;</td>
      		<td width="209px" height="15px">' . $comuna .'</td>
      	</tr>
      	<tr>
      		<td width="100px" height="15px">&nbsp;</td>
      		<td width="400px" height="15px">' . $ciudad . '</td>
      		<td width="278px" height="15px">' . $fono . '</td>
      		<td width="209px" height="15px">' .  $giro . '</td>
      	</tr>
      	<tr>
      		<td width="100px" height="20px">&nbsp;</td>
      		<td width="400px" height="20px">&nbsp;</td>
      		<td width="278px" height="20px">&nbsp;</td>
      		<td width="209px" height="20px">&nbsp;</td>
      	</tr>      	
      	</table>
      </td>
      </tr>
      <tr>
		      	<td colspan="3" >
		      	<table width="987px" border="0">';
		 $tamano_maximo = 400;
		 $i = 1;
		foreach($items->result() as $v){      
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];		

		      $html .= '
		      		
				      	<tr>
				      		<td width="100px" height="20px">' . $producto->codigo . '</td>
				      		<td width="420px" height="20px">' . $producto->nombre . '</td>
				      		<td width="100px" height="20px">' . $v->cantidad . '</td>
				      		<td width="78px" height="20px"></td>
				      		<td width="100px" height="20px">' . number_format($v->precio, 0, ',', '.') . '</td>
				      		<td width="70px" height="20px">' . number_format($v->descuento, 0, ',', '.') . '</td>
				      		<td width="119px" height="20px">' . number_format($v->neto, 0, ',', '.') . '</td>
				      	</tr>
				     ';
		      $i++;
		      $tamano_maximo = $tamano_maximo - 20;
  	}

  	while($tamano_maximo > 0){
  		$html .= '<tr><td colspan="7" height="20px">&nbsp;</td></tr>';
  		$tamano_maximo = $tamano_maximo - 20;	
  	}
  	
    $html .= ' </table>
		      	</td>
		      </tr>


		      <tr>
		      <td  colspan="3" >
		      	<table width="987px" border="0">
		      	<tr >
		      		<td rowspan="3" width="100px" height="60px">&nbsp;</td>
		      		<td width="420px" height="20px">' . $forma_pago . '</td>
		      		<td rowspan="3" width="348px" height="60px">&nbsp;</td>
		      		<td rowspan="3" width="119px" height="60px">&nbsp;</td>
		      	</tr>		      	
		      	<tr>
			      	<td width="119px" height="20px">&nbsp;</td>
		      	</tr>
		      	<tr>
			      	<td width="119px" height="20px">Fecha Vencimiento: '. $dia.'/'.$mes.'/'.$anio.'</td>
		      	</tr>		      	
		      	</table>
		      </td>

		      </tr>


		      <tr>
		      <td  colspan="3" >
		      	<table width="987px" border="1">
		      	<tr >
		      		<td rowspan="3" width="100px" height="60px">' . $ticket_text .'</td>
		      		<td rowspan="3" width="420px" height="60px">' . valorEnLetras($totalFactura) . '</td>
		      		<td rowspan="3" width="348px" height="60px">&nbsp;</td>
		      		<td width="119px" height="20px">' . number_format($montoNeto, 0, ',', '.') . '</td>
		      		<td width="119px" height="20px">' . number_format($montoDescuento, 0, ',', '.') . '</td>
		      		<td width="119px" height="20px">' . number_format($montoAfecto, 0, ',', '.') . '</td>
		      	</tr>		      	
		      	<tr>
			      	<td width="119px" height="20px">' . number_format($montoDescuento, 0, ',', '.') . '</td>
		      	</tr>
		      	<tr>
			      	<td width="119px" height="20px">' . number_format($totalFactura, 0, ',', '.') . '</td>
		      	</tr>		      	
		      	</table>
		      </td>

		      </tr>
		      <tr>
		      	<td  colspan="3" >
		      		<table width="987px" border="1">
		      			<tr>
		      				<td width="698px" height="40px">&nbsp;</td>
		      				<td width="289px" height="40px">'. $nom_obs .'</td>
		      			</tr>
		      			<tr>
		      				<td width="698px" height="20px">&nbsp;</td>
		      				<td width="289px" height="20px">'. number_format(substr($rut_obs,0,strlen($rut_obs) - 1),0,".",".")."-".substr($rut_obs,-1) .'</td>
		      			</tr>		      			
		      		</table>
		      	</td>
		      </tr>
		      </table>';
 

    	/*$html .= '<tr>		        
		      	<td >OBSERVACION : '.$observacion.'</td>
		      	</tr>
		      	<tr>
		      	<td >NOMBRE : '.$nom_obs.'</td>
		      	</tr>	
		      	<td >RUT : '.$rut_obs.'</td>
		      	<tr>		       
		        </tr>';
	*/
      
      $html .='
</body>
</html>
		';
		//==============================================================
		//==============================================================
		//==============================================================
		$this->load->library("mpdf");
		//include(defined('BASEPATH')."/libraries/MPDF54/mpdf.php");
		//include(dirname(__FILE__)."/../libraries/MPDF54/mpdf.php");

		$this->mpdf->mPDF(
			'',    // mode - default ''
			'letter',    // format - A4, for example, default ''
			0,     // font size - default 0
			'',    // default font family
			15,    // margin_left
			15,    // margin right
			16,    // margin top
			16,    // margin bottom
			9,     // margin header
			9,     // margin footer
			'L'    // L - landscape, P - portrait
			);  

		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output("CF_{$codigo}.pdf", "I");		
		exit;

		}else{

			$items = $this->db->get_where('detalle_factura_glosa', array('id_factura' => $row->id));
		//print_r($items->result());exit;
		//variables generales
		$codigo = $row->num_factura;
		$id = $row->id_factura;
		$nombre_contacto = $row->nombre_cliente;
		$observacion = $row->observacion;
		$rut_cliente = $row->rut_cliente;
		$rut_obs = $row->rut_obs;
		$nom_obs = $row->nom_observ;
		if ($row->direc_sucursal){
			$direccion = $row->direc_sucursal;
			$comuna = $row->com_sucursal;
			$ciudad = $row->ciu_sucursal;	
		}else{
			$direccion = $row->direccion;
			$comuna = $row->nombre_comuna;
			$ciudad = $row->nombre_ciudad;
	    };
		$fecha = $row->fecha_venc;
		$giro = $row->giro;
		$fono = $row->fono;
		$ticket_text = $row->num_ticket != '' ? "Nro. Vale <br> ". $row->num_ticket :  "&nbsp;";
		$cabecera = $this->db->get_where('factura_clientes', array('id' => $row->id));		
		$forma_pago = $row->cond_pago;
		$montoNeto = 0;
	    $ivaTotal = 0;
		$totalFactura = 0;
		foreach($cabecera->result() as $reg){
			$montoAfecto = $reg->sub_total;
			$montoDescuento = $reg->descuento;
			$montoNeto = $reg->neto;
			$ivaTotal = $reg->iva;
			$totalFactura = $reg->totalfactura;
		}
				
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		 

		$html = '
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.cajaInput {
	border: 1px dotted #ED1B24;
}
.style5 {color: #FF0000; font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
.style6 {	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
.colorTextoFijo {	color:#008F9F;
	font-weight: bold;
	font:Arial, Helvetica, sans-serif;
}
.lineaDivisoria {
	border-bottom-style:dotted;
	border-bottom-color:#ED1B24;
	border-bottom-width:1px;
	height: 2px;
}
.cajaInputIzq {
	border-top-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-right-width: 1px;
	border-top-style: dotted;
	border-bottom-style: dotted;
	border-left-style: dotted;
	border-right-style: dotted;
	border-top-color: #ED1B24;
	border-bottom-color: #ED1B24;
	border-left-color: #ED1B24;
	border-right-color: #ED1B24;
}
.style9 {font-size: 8px;
font-family: Arial, Helvetica, sans-serif;
}
.style12 {color: #FFFFFF}
.style13 {font-size: 12px; font-family: Arial, Helvetica, sans-serif;}
.style14 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #FFFFFF; }
.style15 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: bold;
	color: #FFFFFF;
}
-->
</style>
</head>

<body>
   <table width="987px" border="0">
   	 
      <tr>
      	<td width="250px"  height="110px">&nbsp;</td>
        <td width="408px"  height="110px" style="font-size: 20px;vertical-align:bottom;">&nbsp;'. $dia2 . '&nbsp;&nbsp;&nbsp;&nbsp;'.$mes2.'&nbsp;&nbsp;&nbsp;&nbsp;'.$anio2.'</td>
        <td width="329px"  height="110px" >
        <table width="329px" border="0">
        	<tr >
        		<td width="329px" height="100px" style="font-size: 20px;vertical-align:bottom;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$codigo.'</b></td>
        	</tr>
        	<tr >
        		<td width="329px" height="10px">&nbsp;</td>
        	</tr>        	
        </table>
        </td>
      </tr> 
      <tr>
      <td colspan="3" height="110px">
      	<table width="987px" border="0">
      	<tr>
      		<td width="100px" height="30px">&nbsp;</td>
      		<td width="400px" height="30px">'. $nombre_contacto .'</td>
      		<td width="278px" height="30px">&nbsp;</td>
      		<td width="209px" height="30px">' . number_format(substr($rut_cliente,0,strlen($rut_cliente) - 1),0,".",".")."-".substr($rut_cliente,-1) . '</td>
      	</tr>
      	<tr>
      		<td width="100px" height="15px">&nbsp;</td>
      		<td width="400px" height="15px">'. $direccion .'</td>
      		<td width="278px" height="15px">&nbsp;</td>
      		<td width="209px" height="15px">' . $comuna .'</td>
      	</tr>
      	<tr>
      		<td width="100px" height="15px">&nbsp;</td>
      		<td width="400px" height="15px">' . $ciudad . '</td>
      		<td width="278px" height="15px">' . $fono . '</td>
      		<td width="209px" height="15px">' .  $giro . '</td>
      	</tr>
      	<tr>
      		<td width="100px" height="20px">&nbsp;</td>
      		<td width="400px" height="20px">&nbsp;</td>
      		<td width="278px" height="20px">&nbsp;</td>
      		<td width="209px" height="20px">&nbsp;</td>
      	</tr>      	
      	</table>
      </td>
      </tr>
      <tr>
		      	<td colspan="3" >
		      	<table width="987px" border="0">';
		 $tamano_maximo = 400;
		 $i = 1;
		 $cantidad = 1;
		foreach($items->result() as $v){
		      
		      $guia=""; 
		      if (!$v->glosa){		      	
		      	$v->glosa="SEGUN GUIA";
		      	$guia = $v->num_guia;
		      	$cantidad="";
		      	$items2 = $this->db->get_where('detalle_factura_cliente', array('num_factura' => $v->num_guia));

		      	foreach($items2->result() as $v){

		      		$data3 = array(
	         			'id_despacho' => $id
				    );
				    $this->db->where('id', $v->id);
				  
				    $this->db->update('detalle_factura_cliente', $data3);
		      	    
					$this->db->where('id', $v->id_producto);
					$producto = $this->db->get("productos");	
					$producto = $producto->result();
					$producto = $producto[0];		

					      $html .= '
					      		
							      	<tr>
							      		<td width="100px" height="20px">' . $producto->codigo . '</td>
							      		<td width="420px" height="20px">' . $producto->nombre . '</td>
							      		<td width="100px" height="20px">' . $v->cantidad . '</td>
							      		<td width="78px" height="20px"></td>
							      		<td width="100px" height="20px">' . number_format($v->precio, 0, ',', '.') . '</td>
							      		<td width="70px" height="20px">' . number_format($v->descuento, 0, ',', '.') . '</td>
							      		<td width="119px" height="20px">' . number_format($v->neto, 0, ',', '.') . '</td>
							      	</tr>
							     ';
					      $i++;
					      $tamano_maximo = $tamano_maximo - 20;
			  		}		
		      			
		    
		      
		     }else{

		     	$html .= '
		      		
				      	<tr>
				      	    <td width="160px" height="20px"></td>
				      		<td width="540" height="20px">' . $v->glosa . '</td>
				      		<td width="50px" height="20px">' . $cantidad . '</td>
				      		<td width="58px" height="20px"></td>
				      		<td width="140px" height="20px">' . number_format($v->neto, 0, ',', '.') . '</td>
				      		<td width="70px" height="20px">' . number_format($v->iva, 0, ',', '.') . '</td>
				      		<td width="99px" height="20px">' . number_format($v->total, 0, ',', '.') . '</td>
				      	</tr>
				     ';
		      $i++;
		      $tamano_maximo = $tamano_maximo - 20;
		     	

		     }
  	}

  	if (!$v->glosa){

  		$guia="";
  		foreach($items->result() as $v){
  			
		$html .= '
		      		
	     <tr>
	      		<td width="50px" height="20px"></td>
	      		<td width="10px" height="20px">Segun Guia : '. $v->num_guia .'</td>
	      		<td width="5px" height="20px"></td>
	      		<td width="278px" height="20px"></td>
	     ';

	     };



		};
  	


  	while($tamano_maximo > 0){
  		$html .= '<tr><td colspan="7" height="20px">&nbsp;</td></tr>';
  		$tamano_maximo = $tamano_maximo - 20;	
  	}
  	
    $html .= ' </table>
		      	</td>
		      </tr>


		      <tr>
		      <td  colspan="3" >
		      	<table width="987px" border="0">
		      	<tr >
		      		<td rowspan="3" width="100px" height="60px">&nbsp;</td>
		      		<td width="420px" height="20px">' . $forma_pago . '</td>
		      		<td rowspan="3" width="348px" height="60px">&nbsp;</td>
		      		<td rowspan="3" width="119px" height="60px">&nbsp;</td>
		      	</tr>		      	
		      	<tr>
			      	<td width="119px" height="20px">&nbsp;</td>
		      	</tr>
		      	<tr>
			      	<td width="119px" height="20px">Fecha Vencimiento: '. $dia.'/'.$mes.'/'.$anio.'</td>
		      	</tr>		      	
		      	</table>
		      </td>

		      </tr>
		      <tr>
		     <tr>
		      <td  colspan="3" >
		      	<table width="987px" border="1">
		      	<tr >
		      		<td rowspan="3" width="100px" height="60px">' . $ticket_text .'</td>
		      		<td rowspan="3" width="420px" height="60px">' . valorEnLetras($totalFactura) . '</td>
		      		<td rowspan="3" width="348px" height="60px">&nbsp;</td>
		      		<td width="119px" height="20px">' . number_format($montoNeto, 0, ',', '.') . '</td>
		      		<td width="119px" height="20px">' . number_format($montoDescuento, 0, ',', '.') . '</td>
		      		<td width="119px" height="20px">' . number_format($montoAfecto, 0, ',', '.') . '</td>
		      	</tr>		      	
		      	<tr>
			      	<td width="119px" height="20px">' . number_format($montoDescuento, 0, ',', '.') . '</td>
		      	</tr>
		      	<tr>
			      	<td width="119px" height="20px">' . number_format($totalFactura, 0, ',', '.') . '</td>
		      	</tr>		      	
		      	</table>
		      </td>

		      </tr>
		      <tr>
		      	<td  colspan="3" >
		      		<table width="987px" border="0">
		      			<tr>
		      				<td width="698px" height="40px">&nbsp;</td>
		      				<td width="289px" height="40px">'. $nom_obs .'</td>
		      			</tr>
		      			<tr>
		      				<td width="698px" height="20px">&nbsp;</td>
		      				<td width="289px" height="20px">'. number_format(substr($rut_obs,0,strlen($rut_obs) - 1),0,".",".")."-".substr($rut_obs,-1) .'</td>
		      			</tr>		      			
		      		</table>
		      	</td>
		      </tr>
		      </table>';
 

    	/*$html .= '<tr>		        
		      	<td >OBSERVACION : '.$observacion.'</td>
		      	</tr>
		      	<tr>
		      	<td >NOMBRE : '.$nom_obs.'</td>
		      	</tr>	
		      	<td >RUT : '.$rut_obs.'</td>
		      	<tr>		       
		        </tr>';
	*/
      
      $html .='
</body>
</html>
		';
		//==============================================================
		//==============================================================
		//==============================================================
		$this->load->library("mpdf");
		//include(defined('BASEPATH')."/libraries/MPDF54/mpdf.php");
		//include(dirname(__FILE__)."/../libraries/MPDF54/mpdf.php");

		$this->mpdf->mPDF(
			'',    // mode - default ''
			'letter',    // format - A4, for example, default ''
			0,     // font size - default 0
			'',    // default font family
			15,    // margin_left
			15,    // margin right
			16,    // margin top
			16,    // margin bottom
			9,     // margin header
			9,     // margin footer
			'L'    // L - landscape, P - portrait
			);  

		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output("CF_{$codigo}.pdf", "I");		
		exit;
		}
	}

	public function exportlotePDF(){

		$idfactura = $this->input->get('idfactura');
		$numero = $this->input->get('numfactura');
		$cabecera = $this->db->get_where('factura_clientes', array('id' => $idfactura));	
		//$tipodocumento = 19;
		$tipodocumento2 = 19;
		foreach($cabecera->result() as $v){  
				$tipodocumento = $v->tipo_documento; 
		}

		if($tipodocumento == 19 or $tipodocumento == 1 ){
				$this->exportFacturalotePDF($idfactura,$numero);

		}else{

				$this->exportBoletaPDF($idfactura,$numero);

		};

	}

	//$idfactura,$numero

	public function exportFacturalotePDF($idfactura,$numero){

		//$idfactura = $this->input->get('idfactura');
		//$numero = $this->input->get('numfactura');

        if ($idfactura){
		$query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, c.fono, p.num_ticket, cp.nombre as cond_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, ma.nombre as com_sucursal FROM factura_clientes acc
			left join preventa p on acc.id = p.id_documento
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.id = '.$idfactura.'');
		}else{
			$query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, ob.nombre as nom_observ, ob.rut as rut_obs, c.fono, p.num_ticket, cp.nombre as cond_pago, cs.direccion as direc_sucursal, sa.nombre as ciu_sucursal, ma.nombre as com_sucursal FROM factura_clientes acc
			left join preventa p on acc.id = p.id_documento
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join clientes_sucursales cs on (acc.id_sucursal = cs.id)
			left join comuna ma on (cs.id_comuna = ma.id)
			left join ciudad sa on (cs.id_ciudad = sa.id)
			left join observacion_facturas ob on (acc.id_observa = ob.id)
			left join cond_pago cp on (acc.id_cond_venta = cp.id)
			WHERE acc.num_factura = '.$numero.'

		');


		}

		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		$fecha = $row->fecha_venc;
		list($anio, $mes, $dia) = explode("-",$fecha);
		$fecha2 = $row->fecha_factura;
		list($anio2, $mes2, $dia2) = explode("-",$fecha2);
		 
		//items
		$items = $this->db->get_where('detalle_factura_glosa', array('id_factura' => $row->id));
		$codigo = $row->num_factura;
		$nombre_contacto = $row->nombre_cliente;
		$observacion = $row->observacion;
		$rut_cliente = $row->rut_cliente;
		$rut_obs = $row->rut_obs;
		$nom_obs = $row->nom_observ;
		$direccion = $row->direccion;
		$comuna = $row->nombre_comuna;
		$ciudad = $row->nombre_ciudad;
		$fecha = $row->fecha_venc;
		$giro = $row->giro;
		$cabecera = $this->db->get_where('factura_clientes', array('id' => $row->id));		
		$montoNeto = 0;
	    $ivaTotal = 0;
		$totalFactura = 0;
		if ($row->direc_sucursal){
			$direccion = $row->direc_sucursal;
			$comuna = $row->com_sucursal;
			$ciudad = $row->ciu_sucursal;	
		}else{
			$direccion = $row->direccion;
			$comuna = $row->nombre_comuna;
			$ciudad = $row->nombre_ciudad;
	    };
		foreach($cabecera->result() as $reg){
			$montoNeto = $reg->neto;
			$ivaTotal = $reg->iva;
			$totalFactura = $reg->totalfactura;
		}
				
		$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
		$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		 

		$html = '
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.cajaInput {
	border: 1px dotted #ED1B24;
}
.style5 {color: #FF0000; font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
.style6 {	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
.colorTextoFijo {	color:#008F9F;
	font-weight: bold;
	font:Arial, Helvetica, sans-serif;
}
.lineaDivisoria {
	border-bottom-style:dotted;
	border-bottom-color:#ED1B24;
	border-bottom-width:1px;
	height: 2px;
}
.cajaInputIzq {
	border-top-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-right-width: 1px;
	border-top-style: dotted;
	border-bottom-style: dotted;
	border-left-style: dotted;
	border-right-style: dotted;
	border-top-color: #ED1B24;
	border-bottom-color: #ED1B24;
	border-left-color: #ED1B24;
	border-right-color: #ED1B24;
}
.style9 {font-size: 8px;
font-family: Arial, Helvetica, sans-serif;
}
.style12 {color: #FFFFFF}
.style13 {font-size: 12px; font-family: Arial, Helvetica, sans-serif;}
.style14 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #FFFFFF; }
.style15 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: bold;
	color: #FFFFFF;
}
-->
</style>
</head>

<body>
   <table width="750" border="0" cellpadding="0" cellspacing="0">
   	 
      <tr>
        <td width="450"><span class="style6">&nbsp;</span><span class="colorTextoFijo"></span></td>
		<td class="style6"><center>'.$codigo.'</center></td>
      </tr>      
    </table>
    <p align="right"><b>'.$dia2.'/'.$mes2.'/'.$anio2.'</b></p>
    <p align="right"><b>'.$dia.'/'.$mes.'/'.$anio.'</b></p>
    <br><br>
   
    <br><br>
     
     <table width="650" border="0" cellpadding="0" cellspacing="0">
       <tr>
      
        <td colspan="6" class="style5">'.$nombre_contacto.'</td>
        <td colspan="6" class="style5">'.$direccion.'</td>
       </tr>
      <tr>
        <td colspan="6" class="style5">'.$rut_cliente.'</td>      
        <td colspan="6" class="style5">'.$comuna.'</td>
        </tr>
      <tr>
        <td colspan="6" class="style5">'.$giro.'</td>
        <td colspan="6" class="style5">'.$ciudad.'</td>
        </tr>
    </table>
  
  <table border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
         </tr>
         <tr>
        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
         </tr>
         <tr>
        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
         </tr>
         <tr>
        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
         </tr>
         <tr>
        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
         </tr>
         <tr>
        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
         </tr>
         <tr>
        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
         </tr>
         <tr>
        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
         </tr>
         <tr>
        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
         </tr>
         <tr>
        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
         </tr>
      ';
      $i = 1;
	foreach($items->result() as $v){
	
	if ($v->num_guia == 0){
		
		$v->num_guia = " ";

	}else{

		$v->glosa = "Segun Guia";
		

	};
			

     $html .= '<tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td ><b>'.($v->glosa).' '.($v->num_guia).'</b></td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td align="right">'.number_format($v->neto, 0, ',', '.').'</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td align="right"></td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>       
        <td align="right"></td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>       
        <td><p align="right">'.number_format($v->iva, 0, ',', '.').'</b></td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        
        <td ><b align="right">'.number_format($v->total, 0, ',', '.').'</b></td>
        <td >&nbsp;</td>
        </tr>';
        $i++;

    }

    if($i < 15){
    	for($j=$i;$j<=15;$j++){
		        $html .= '<tr>
		        
		      	<td >&nbsp;</td>
		      	<td >&nbsp;</td>
		       
		        </tr>';
    	}
    }

    	$html .= '<tr>		        
		      	<td >OBSERVACION : '.$observacion.'</td>
		      	</tr>
		      	<tr>
		      	<td >NOMBRE : '.$nom_obs.'</td>
		      	</tr>	
		      	<td >RUT : '.$rut_obs.'</td>
		      	<tr>		       
		        </tr>';

      
      $html .='<tr>
      	
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>        
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>        
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>        
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>        
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td p align="right"><b>'.number_format($montoNeto, 0, ',', '.').'</b></td>
        </tr>
		<tr>
		<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>        
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>        
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>        
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>        
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td p align="right"><b>'.number_format($ivaTotal, 0, ',', '.').'</b></td>
        </tr>        
		<tr>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>        
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>        
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>        
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>        
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td p align="right"><b>'.number_format($totalFactura, 0, ',', '.').'</b></td>
        </tr>                
</table>
</body>
</html>
		';
		//==============================================================
		//==============================================================
		//==============================================================
		$this->load->library("mpdf");
		//include(defined('BASEPATH')."/libraries/MPDF54/mpdf.php");
		//include(dirname(__FILE__)."/../libraries/MPDF54/mpdf.php");

		$this->mpdf->mPDF(
			'',    // mode - default ''
			'',    // format - A4, for example, default ''
			0,     // font size - default 0
			'',    // default font family
			15,    // margin_left
			15,    // margin right
			16,    // margin top
			16,    // margin bottom
			9,     // margin header
			9,     // margin footer
			'L'    // L - landscape, P - portrait
			);  

		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output("CF_{$codigo}.pdf", "I");

		/*$mpdf= new mPDF(
			'',    // mode - default ''
			'',    // format - A4, for example, default ''
			0,     // font size - default 0
			'',    // default font family
			15,    // margin_left
			15,    // margin right
			16,    // margin top
			16,    // margin bottom
			9,     // margin header
			9,     // margin footer
			'L'    // L - landscape, P - portrait
			);  

		$mpdf->WriteHTML($html);
		$mpdf->Output("CF_{$codigo}.pdf", "I");
		*/
		exit;
	}



	public function exportBoletaPDF($idfactura,$numero){

		//$idfactura = $this->input->get('idfactura');
		//$numero = $this->input->get('numfactura');

        if ($idfactura){
		$query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, p.num_ticket  FROM factura_clientes acc
			left join preventa p on acc.id = p.id_documento
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.id = '.$idfactura.'
		');
		}else{
			$query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor, p.num_ticket  FROM factura_clientes acc
			left join preventa p on acc.id = p.id_documento
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.num_factura = '.$numero.'

		');


		}

		//cotizacion header
		$row = $query->result();
		$row = $row[0];
		//items

		if ($row->forma == 0){

		$items = $this->db->get_where('detalle_factura_cliente', array('id_factura' => $row->id));
		//print_r($items->result());exit;
		//variables generales
		$codigo = $row->num_factura;
		$nombre_contacto = $row->nombre_cliente;
		$rut_cliente = $row->rut_cliente;
		$direccion = $row->direccion;
		$comuna = $row->nombre_comuna;
		$ciudad = $row->nombre_ciudad;
		$giro = $row->giro;
		$fecha = $row->fecha_venc;
		list($anio, $mes, $dia) = explode("-",$fecha);
		$fecha2 = $row->fecha_factura;
		list($anio2, $mes2, $dia2) = explode("-",$fecha2);
		$ticket_text = $row->num_ticket != '' ? "Nro. Vale: ". $row->num_ticket :  "&nbsp;";
		$cabecera = $this->db->get_where('factura_clientes', array('id' => $row->id));		
		$nom_vendedor = $row->nom_vendedor;
		$montoNeto = 0;
		$ivaTotal = 0;
		$totalFactura = 0;
		foreach($cabecera->result() as $reg){
			$montoNeto = $reg->neto;
			$ivaTotal = $reg->iva;
			$totalFactura = $reg->totalfactura;
		}
				
$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 

		$html = '
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.cajaInput {
	border: 1px dotted #ED1B24;
}
.style5 {color: #FF0000; font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
.style6 {	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
.colorTextoFijo {	color:#008F9F;
	font-weight: bold;
	font:Arial, Helvetica, sans-serif;
}
.lineaDivisoria {
	border-bottom-style:dotted;
	border-bottom-color:#ED1B24;
	border-bottom-width:1px;
	height: 2px;
}
.cajaInputIzq {
	border-top-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-right-width: 1px;
	border-top-style: dotted;
	border-bottom-style: dotted;
	border-left-style: dotted;
	border-right-style: dotted;
	border-top-color: #ED1B24;
	border-bottom-color: #ED1B24;
	border-left-color: #ED1B24;
	border-right-color: #ED1B24;
}
.style9 {font-size: 8px;
font-family: Arial, Helvetica, sans-serif;
}
.style12 {color: #FFFFFF}
.style13 {font-size: 12px; font-family: Arial, Helvetica, sans-serif;}
.style14 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #FFFFFF; }
.style15 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: bold;
	color: #FFFFFF;
}
-->
</style>
</head>

<body>
   <table width="987px" border="0">
   	 
      <tr>
      	<td width="987px">
      		<table width="987px" border="0">
      		<tr>
      			<td width="740px" height="25px">&nbsp;</td>
				<td width="247px" height="25px">'.$codigo.'</td>
      		</tr>
      		</table>
      	</td>
      </tr> 
      <tr>
      	<td width="987px">
      		<table width="987px" border="0">
      		<tr>
      			<td width="800px" height="40px">&nbsp;</td>
				<td width="187px" height="40px" style="font-size: 15px;vertical-align:bottom;">'.$dia2.'/'.$mes2.'/'.$anio2.'</td>
      		</tr>
      		</table>
      	</td>
      </tr>  
      <tr>
      	<td width="987px">
      		<table width="987px" border="0">
      		<tr>
      			<td width="50px" height="20px">&nbsp;</td>
				<td width="937px" height="20px" style="font-size: 15px;vertical-align:bottom;">VENDEDOR: '.$nom_vendedor.'</td>
      		</tr>
      		</table>
      	</td>
      </tr>  
      <tr>
      	<td width="987px" height="25px">
      	&nbsp;
      	</td>
      </tr>       
      <tr>
		      	<td width="987px" >
		      	<table width="987px" border="0">';
		 $tamano_maximo = 240;
		 $i = 1;
		foreach($items->result() as $v){      
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];		

		      $html .= '
		      		
				      	<tr>
				      		<td width="160px" height="20px">' . $producto->codigo . '</td>
				      		<td width="380px" height="20px">' . $producto->nombre . '</td>
				      		<td width="50px" height="20px">' . $v->cantidad . '</td>
				      		<td width="58px" height="20px">UNI</td>
				      		<td width="140px" height="20px">' . number_format($v->precio, 0, ',', '.') . '</td>
				      		<td width="70px" height="20px">' . number_format($v->descuento, 0, ',', '.') . '</td>
				      		<td width="99px" height="20px">' . number_format($v->totalproducto, 0, ',', '.') . '</td>
				      	</tr>
				     ';
		      $i++;
		      $tamano_maximo = $tamano_maximo - 20;
  	}

  	while($tamano_maximo > 0){
  		$html .= '<tr><td colspan="7" height="20px">&nbsp;</td></tr>';
  		$tamano_maximo = $tamano_maximo - 20;	
  	}



	$html .= '</table></td></tr>

				<tr>
		      	<td width="987px">
		      		<table width="987px" border="0">
		      		<tr>
		      			<td width="160px" height="20px">&nbsp;</td>
						<td width="827px" height="20px" style="font-size: 15px;vertical-align:bottom;">' . valorEnLetras($totalFactura) .'</td>
		      		</tr>
		      		</table>
		      	</td>
		      </tr>  
		      <tr>
		      	<td width="987px">
		      		<table width="987px" border="0">
		      		<tr>
		      			<td width="500px" height="20px">&nbsp;</td>
		      			<td width="388px" height="20px">' . $ticket_text .'</td>
						<td width="99px" height="20px" style="font-size: 15px;vertical-align:bottom;">' . number_format($totalFactura, 0, ',', '.') .'</td>
		      		</tr>
		      		</table>
		      	</td>
		      </tr>  
		      	

	</table>';
 

    	/*$html .= '<tr>		        
		      	<td >OBSERVACION : '.$observacion.'</td>
		      	</tr>
		      	<tr>
		      	<td >NOMBRE : '.$nom_obs.'</td>
		      	</tr>	
		      	<td >RUT : '.$rut_obs.'</td>
		      	<tr>		       
		        </tr>';
	*/
      
      $html .='
</body>
</html>
		';
		//==============================================================
		//==============================================================
		//==============================================================
		$this->load->library("mpdf");
		//include(defined('BASEPATH')."/libraries/MPDF54/mpdf.php");
		//include(dirname(__FILE__)."/../libraries/MPDF54/mpdf.php");

		$this->mpdf->mPDF(
			'',    // mode - default ''
			'letter',    // format - A4, for example, default ''
			0,     // font size - default 0
			'',    // default font family
			15,    // margin_left
			15,    // margin right
			16,    // margin top
			16,    // margin bottom
			9,     // margin header
			9,     // margin footer
			'L'    // L - landscape, P - portrait
			);  

		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output("CF_{$codigo}.pdf", "I");

		/*$mpdf= new mPDF(
			'',    // mode - default ''
			'',    // format - A4, for example, default ''
			0,     // font size - default 0
			'',    // default font family
			15,    // margin_left
			15,    // margin right
			16,    // margin top
			16,    // margin bottom
			9,     // margin header
			9,     // margin footer
			'L'    // L - landscape, P - portrait
			);  

		$mpdf->WriteHTML($html);
		$mpdf->Output("CF_{$codigo}.pdf", "I");
		*/
		exit;

		}else{


		$items = $this->db->get_where('detalle_factura_glosa', array('id_factura' => $row->id));
		//print_r($items->result());exit;
		//variables generales
		$codigo = $row->num_factura;
		$nombre_contacto = $row->nombre_cliente;
		$rut_cliente = $row->rut_cliente;
		$direccion = $row->direccion;
		$comuna = $row->nombre_comuna;
		$ciudad = $row->nombre_ciudad;
		$giro = $row->giro;
		$fecha = $row->fecha_venc;
		list($anio, $mes, $dia) = explode("-",$fecha);
		$fecha2 = $row->fecha_factura;
		list($anio2, $mes2, $dia2) = explode("-",$fecha2);
		$ticket_text = $row->num_ticket != '' ? "Nro. Vale: ". $row->num_ticket :  "&nbsp;";
		$cabecera = $this->db->get_where('factura_clientes', array('id' => $row->id));		
		$nom_vendedor = $row->nom_vendedor;
		$montoNeto = 0;
		$ivaTotal = 0;
		$totalFactura = 0;
		foreach($cabecera->result() as $reg){
			$montoNeto = $reg->neto;
			$ivaTotal = $reg->iva;
			$totalFactura = $reg->totalfactura;
		}
				
$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
 

		$html = '
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
.cajaInput {
	border: 1px dotted #ED1B24;
}
.style5 {color: #FF0000; font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
.style6 {	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
}
.colorTextoFijo {	color:#008F9F;
	font-weight: bold;
	font:Arial, Helvetica, sans-serif;
}
.lineaDivisoria {
	border-bottom-style:dotted;
	border-bottom-color:#ED1B24;
	border-bottom-width:1px;
	height: 2px;
}
.cajaInputIzq {
	border-top-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-right-width: 1px;
	border-top-style: dotted;
	border-bottom-style: dotted;
	border-left-style: dotted;
	border-right-style: dotted;
	border-top-color: #ED1B24;
	border-bottom-color: #ED1B24;
	border-left-color: #ED1B24;
	border-right-color: #ED1B24;
}
.style9 {font-size: 8px;
font-family: Arial, Helvetica, sans-serif;
}
.style12 {color: #FFFFFF}
.style13 {font-size: 12px; font-family: Arial, Helvetica, sans-serif;}
.style14 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #FFFFFF; }
.style15 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: bold;
	color: #FFFFFF;
}
-->
</style>
</head>

<body>
   <table width="987px" border="0">
   	 
      <tr>
      	<td width="987px">
      		<table width="987px" border="0">
      		<tr>
      			<td width="740px" height="25px">&nbsp;</td>
				<td width="247px" height="25px">'.$codigo.'</td>
      		</tr>
      		</table>
      	</td>
      </tr> 
      <tr>
      	<td width="987px">
      		<table width="987px" border="0">
      		<tr>
      			<td width="800px" height="40px">&nbsp;</td>
				<td width="187px" height="40px" style="font-size: 15px;vertical-align:bottom;">'.$dia2.'/'.$mes2.'/'.$anio2.'</td>
      		</tr>
      		</table>
      	</td>
      </tr>  
      <tr>
      	<td width="987px">
      		<table width="987px" border="0">
      		<tr>
      			<td width="50px" height="20px">&nbsp;</td>
				<td width="937px" height="20px" style="font-size: 15px;vertical-align:bottom;">VENDEDOR: '.$nom_vendedor.'</td>
      		</tr>
      		</table>
      	</td>
      </tr>  
      <tr>
      	<td width="987px" height="25px">
      	&nbsp;
      	</td>
      </tr>       
      <tr>
		      	<td width="987px" >
		      	<table width="987px" border="0">';
		 $tamano_maximo = 240;
		 $i = 1;
		 $cantidad = 1;
		foreach($items->result() as $v){      
			
		      $html .= '
		      		
				      	<tr>
				      	    <td width="160px" height="20px"></td>
				      		<td width="540" height="20px">' . $v->glosa . '</td>
				      		<td width="50px" height="20px">' . $cantidad . '</td>
				      		<td width="58px" height="20px"></td>
				      		<td width="140px" height="20px">' . number_format($v->neto, 0, ',', '.') . '</td>
				      		<td width="70px" height="20px">' . number_format($v->iva, 0, ',', '.') . '</td>
				      		<td width="99px" height="20px">' . number_format($v->total, 0, ',', '.') . '</td>
				      	</tr>
				     ';
		      $i++;
		      $tamano_maximo = $tamano_maximo - 20;
  	}

  	while($tamano_maximo > 0){
  		$html .= '<tr><td colspan="7" height="20px">&nbsp;</td></tr>';
  		$tamano_maximo = $tamano_maximo - 20;	
  	}



	$html .= '</table></td></tr>

				<tr>
		      	<td width="987px">
		      		<table width="987px" border="0">
		      		<tr>
		      			<td width="160px" height="20px">&nbsp;</td>
						<td width="827px" height="20px" style="font-size: 15px;vertical-align:bottom;">' . valorEnLetras($totalFactura) .'</td>
		      		</tr>
		      		</table>
		      	</td>
		      </tr>  
		      <tr>
		      	<td width="987px">
		      		<table width="987px" border="0">
		      		<tr>
		      			<td width="500px" height="20px">&nbsp;</td>
		      			<td width="388px" height="20px">' . $ticket_text .'</td>
						<td width="99px" height="20px" style="font-size: 15px;vertical-align:bottom;">' . number_format($totalFactura, 0, ',', '.') .'</td>
		      		</tr>
		      		</table>
		      	</td>
		      </tr>  
		      	

	</table>';
 

    	/*$html .= '<tr>		        
		      	<td >OBSERVACION : '.$observacion.'</td>
		      	</tr>
		      	<tr>
		      	<td >NOMBRE : '.$nom_obs.'</td>
		      	</tr>	
		      	<td >RUT : '.$rut_obs.'</td>
		      	<tr>		       
		        </tr>';
	*/
      
      $html .='
</body>
</html>
		';
		//==============================================================
		//==============================================================
		//==============================================================
		$this->load->library("mpdf");
		//include(defined('BASEPATH')."/libraries/MPDF54/mpdf.php");
		//include(dirname(__FILE__)."/../libraries/MPDF54/mpdf.php");

		$this->mpdf->mPDF(
			'',    // mode - default ''
			'letter',    // format - A4, for example, default ''
			0,     // font size - default 0
			'',    // default font family
			15,    // margin_left
			15,    // margin right
			16,    // margin top
			16,    // margin bottom
			9,     // margin header
			9,     // margin footer
			'L'    // L - landscape, P - portrait
			);  

		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output("CF_{$codigo}.pdf", "I");

		/*$mpdf= new mPDF(
			'',    // mode - default ''
			'',    // format - A4, for example, default ''
			0,     // font size - default 0
			'',    // default font family
			15,    // margin_left
			15,    // margin right
			16,    // margin top
			16,    // margin bottom
			9,     // margin header
			9,     // margin footer
			'L'    // L - landscape, P - portrait
			);  

		$mpdf->WriteHTML($html);
		$mpdf->Output("CF_{$codigo}.pdf", "I");
		*/
		exit;
		}
	}


        public function exportarPdflibroFacturas()
         {            
            $columnas = json_decode($this->input->get('cols'));
            $fecha = $this->input->get('fecha');
            list($dia, $mes, $anio) = explode("/",$fecha);
            $fecha3 = $anio ."-". $mes ."-". $dia;
            $fecha2 = $this->input->get('fecha2');
            list($dia, $mes, $anio) = explode("/",$fecha2);
            $fecha4 = $anio ."-". $mes ."-". $dia;
            $tipo = 1;
            $tipo2 = 102;
            $tipo3 = 101;
            $tipo4 = 103;
            $this->load->library("mpdf");

			$this->mpdf->mPDF(
				'',    // mode - default ''
				'',    // format - A4, for example, default ''
				8,     // font size - default 0
				'',    // default font family
				10,    // margin_left
				5,    // margin right
				16,    // margin top
				16,    // margin bottom
				9,     // margin header
				9,     // margin footer
				'L'    // L - landscape, P - portrait
				);  
			//echo $html; exit
            $data = array();
                                   
            $this->load->database();
            
            if($fecha){
                          
                $data = array();
                $query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor  FROM factura_clientes acc
                left join clientes c on (acc.id_cliente = c.id)
                left join vendedores v on (acc.id_vendedor = v.id)
                WHERE acc.tipo_documento in ( '.$tipo.','.$tipo2.','.$tipo3.','.$tipo4.') and acc.fecha_factura between "'.$fecha3.'"  AND "'.$fecha4.'"
                order by acc.tipo_documento and acc.fecha_factura' 
                
                );
            

              };


		$header = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Libro de Ventas</title>
		<style type="text/css">
		td {
			font-size: 16px;
		}
		p {
		}
		</style>
		</head>

		<body>
		<table width="987px" height="602" border="0">
		  <tr>
		    <td width="197px"><img src="http://localhost/Infosys_web/Infosys_web/resources/images/logo.jpg" width="150" height="136" /></td>
		    <td width="493px" style="font-size: 14px;text-align:center;vertical-align:text-top"	>
		    <p>AGRICOLA Y COMERCIAL LIRCAY S.A.</p>
		    <p>RUT:96.516.320-4</p>
		    <p>Avda San Miguel Cruce Las Rastras S/N- Talca - Chile</p>
		    <p>Fonos: (71)2 245932-2 2245933</p>
		    <p>http://www.lircay.cl</p>
		    </td>
		    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
		          <p>FECHA EMISION : '.date('d/m/Y').'</p>
			</td>
		  </tr>';              
              
		  $header2 = '<tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h2>LIBRO DE VENTAS</h2></td>
		  </tr>
			<tr><td colspan="3">&nbsp;</td></tr>		  
			';              


		$body_header = '<tr>
		    <td colspan="8" >
		    	<table width="987px" cellspacing="0" cellpadding="0" >
		      <tr>
		        <td width="57"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Dia</td>
		        <td width="40px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Num</td>
		        <td width="90px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Tipo</td>
		        <td width="100px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Rut</td>
		        <td width="350px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Nombre</td>
		        <td width="60px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Exento</td>
		        <td width="90px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Neto</td>
		        <td width="90px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >IVA</td>
		        <td width="90px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Total</td>
		      </tr>';


		      $sub_total = 0;
		      $descuento = 0;
		      $neto = 0;
		      $iva = 0;
		      $cantfact = 0;
		      $cantnc =0;
		      $totalfactura = 0;
              $i = 0;
              $body_detail = '';
              $users = $query->result_array();
		      foreach($users as $v){

		      	    list($anio, $mes, $dia) = explode("-",$v['fecha_factura']);
                    $rutautoriza = $v['rut_cliente'];
				   	if (strlen($rutautoriza) == 8){
				      $ruta1 = substr($rutautoriza, -1);
				      $ruta2 = substr($rutautoriza, -4, 3);
				      $ruta3 = substr($rutautoriza, -7, 3);
				      $ruta4 = substr($rutautoriza, -8, 1);
				      $v['rut_cliente'] = ($ruta4.".".$ruta3.".".$ruta2."-".$ruta1);
				    };
				    if (strlen($rutautoriza) == 9){
				      $ruta1 = substr($rutautoriza, -1);
				      $ruta2 = substr($rutautoriza, -4, 3);
				      $ruta3 = substr($rutautoriza, -7, 3);
				      $ruta4 = substr($rutautoriza, -9, 2);
				      $v['rut_cliente'] = ($ruta4.".".$ruta3.".".$ruta2."-".$ruta1);
				   
				    };
				    if (strlen($rutautoriza) == 2){
				      $ruta1 = substr($rutautoriza, -1);
				      $ruta2 = substr($rutautoriza, -4, 1);
				      $v['rut_cliente'] = ($ruta2."-".$ruta1);
				      				     
				    };

				    if ($v['tipo_documento'] == 102){

				    	$v['neto'] = ($v['neto']/-1);
				    	$v['iva'] = ($v['iva']/-1);
				    	$v['totalfactura'] = ($v['totalfactura']/-1);
				    	$tipo="N/C";

				    };
				     if ($v['tipo_documento'] == 1 or $v['tipo_documento'] == 101){
				      $sub_total += $v['sub_total'];
				      $descuento += $v['descuento'];
				      $neto += $v['neto'];
				      $iva += $v['iva'];
				      $totalfactura += $v['totalfactura'];
				      $cantfact++;
				      $tipo="Fact";
				      };

				       
			      if ($v['tipo_documento'] == 103){
			      $netoex += $v['neto'];
			      $ivaex += $v['iva'];
			      $totalfacturaex += $v['totalfactura'];
			      $cantex++;
			      $tipo="F/Exe";
			      };
			      if ($v['tipo_documento'] == 104){
			      $netond += $v['neto'];
			      $ivand += $v['iva'];
			      $totalfacturand += $v['totalfactura'];
			      $cantnd++;
			      $tipo="N/D";
			      };
			      if ($v['tipo_documento'] == 102){
			      $netonc += $v['neto'];
			      $ivanc += $v['iva'];
			      $totalfacturanc += $v['totalfactura'];
			      $cantnc++;
			      };
				    	      	    

				$body_detail .= '<tr><td colspan="10">&nbsp;</td></tr></table></td>
				  </tr>
				  <tr>
				  	<table width="997" cellspacing="0" cellpadding="0" >
				    <tr>				
					<td width="47px" style="text-align:left">'.$dia.'</td>
					<td width="70px" style="text-align:left">'.$v['num_factura'].'</td>
					<td width="70px" style="text-align:left">'.$tipo.'</td>
					<td width="100px" style="text-align:right">'.$v['rut_cliente'].'</td>
					<td width="10px" style="text-align:left"></td>
					<td width="350px" style="text-align:left">'.$v['nombre_cliente'].'</td>
					<td width="50px" style="text-align:left"></td>
					<td width="100px" style="text-align:right">$ '.number_format($v['neto'], 0, '.', ',').'</td>
					<td width="100px" style="text-align:right">$ '.number_format($v['iva'], 0, '.', ',').'</td>
					<td width="100px" style="text-align:right">$ '.number_format($v['totalfactura'], 0, '.', ',').'</td>
				    </tr>
				    </table>
				  </tr>';
		            
    		        $i++;

		         }  

				$footer .= '<tr><td colspan="8">&nbsp;</td></tr></table></td>
				  </tr>
				  <tr>
				  	<td colspan="3" >
				    	<table width="997px" cellspacing="0" cellpadding="0" >
				      <tr>
				        <td width="517px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;font-size: 14px;" ><b>Totales</b></td>
				        <td width="70px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b></b></td>
				        <td width="60px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b></b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($neto, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($iva, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($totalfactura, 0, ',', '.').'</b></td>
				      </tr>
				      	</table>
				  	</td>
				  </tr></table>
				  <tr><td colspan="10">&nbsp;</td></tr></table></td>
				  </tr>
				  <tr>
				  	<td colspan="3" >
				    	<table width="997px" cellspacing="0" cellpadding="0" >
				      <tr>
				        <td width="517px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;font-size: 14px;" ><b>Totales</b></td>
				        <td width="90px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>Facturas</b></td>
				        <td width="60px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>'.number_format($cantfact, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($neto, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($iva, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($totalfactura, 0, ',', '.').'</b></td>
				      </tr>
				      	</table>
				  	</td>
				  </tr></table>
				  <tr><td colspan="10">&nbsp;</td></tr></table></td>
				  </tr>
				  <tr>
				  	<td colspan="3" >
				    	<table width="997px" cellspacing="0" cellpadding="0" >
				      <tr>
				        <td width="517px"  style="border-bottom:0pt solid black;border-top:0pt solid black;text-align:left;font-size: 14px;" ><b></b></td>
				        <td width="90px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>Facturas Excentas</b></td>
				        <td width="60px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>'.number_format($cantex, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($netoex, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($ivaex, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($totalfacturaex, 0, ',', '.').'</b></td>
				      </tr>
				      	</table>
				  	</td>
				  </tr></table>
				  <tr><td colspan="10">&nbsp;</td></tr></table></td>
				  </tr>
				  <tr>
				  	<td colspan="3" >
				    	<table width="997px" cellspacing="0" cellpadding="0" >
				      <tr>
				        <td width="517px"  style="border-bottom:0pt solid black;border-top:0pt solid black;text-align:left;font-size: 14px;" ><b></b></td>
				        <td width="90px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>Notas Debito</b></td>
				        <td width="60px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>'.number_format($cantnd, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($netond, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($ivand, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($totalfacturand, 0, ',', '.').'</b></td>
				      </tr>
				      	</table>
				  	</td>
				  </tr></table>
				  <tr><td colspan="10">&nbsp;</td></tr></table></td>
				  </tr>
				  <tr>
				  	<td colspan="3" >
				    	<table width="997px" cellspacing="0" cellpadding="0" >
				      <tr>
				         <td width="517px"  style="border-bottom:0pt solid black;border-top:0pt solid black;text-align:left;font-size: 14px;" ><b></b></td>
				        <td width="90px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>Notas de Ctredito</b></td>
				        <td width="60px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>'.number_format($cantnc, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($netonc, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($ivanc, 0, ',', '.').'</b></td>
				        <td width="120px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($totalfacturanc, 0, ',', '.').'</b></td>
				      </tr>
				      </tr>
				      	</table>
				  	</td>
				  </tr></table>
				</body>
				</html>';		              
             
        			  
	        $html = $header.$header2;
	        $html2 =$body_header.$body_detail;
	        $html3 =$footer;
	      	$this->mpdf->WriteHTML($html);
			$this->mpdf->WriteHTML($html2);
			$this->mpdf->WriteHTML($html3);
			$this->mpdf->Output("Ventas.pdf", "I");
            exit;		          

        }


        public function exportarPdfFacturas()
         {

            
            $columnas = json_decode($this->input->get('cols'));
            $fecha = $this->input->get('fecha');
            $nombres = $this->input->get('nombre');
            $opcion = $this->input->get('opcion');
            list($dia, $mes, $anio) = explode("/",$fecha);
            $fecha3 = $anio ."-". $mes ."-". $dia;
            $fecha2 = $this->input->get('fecha2');
            list($dia, $mes, $anio) = explode("/",$fecha2);
            $fecha4 = $anio ."-". $mes ."-". $dia;
            $tipo = 1;
            $tipo2 = 2;
                        

            $data = array();
                                   
            $this->load->database();
            
            if($fecha){
            
            if($opcion == "Rut"){
    
                $query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor  FROM factura_clientes acc
                left join clientes c on (acc.id_cliente = c.id)
                left join vendedores v on (acc.id_vendedor = v.id)
                WHERE acc.tipo_documento in ( '.$tipo.','.$tipo2.') and c.rut = '.$nombres.' and acc.fecha_factura between "'.$fecha3.'"  AND "'.$fecha4.'"
                order by acc.id desc'    

              );

                }else if($opcion == "Nombre"){

                  
                $sql_nombre = "";
                    $arrayNombre =  explode(" ",$nombres);

                    foreach ($arrayNombre as $nombre) {
                      $sql_nombre .= "and c.nombres like '%".$nombre."%' ";
                    }
                            
                $query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor  FROM factura_clientes acc
                left join clientes c on (acc.id_cliente = c.id)
                left join vendedores v on (acc.id_vendedor = v.id)
                WHERE acc.tipo_documento in ( '.$tipo.','.$tipo2.') ' . $sql_nombre . ' and acc.fecha_factura between "'.$fecha3.'"  AND "'.$fecha4.'" 
                order by acc.id desc' 
                
                );
             
              }else if($opcion == "Todos"){

                
                $data = array();
                $query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor  FROM factura_clientes acc
                left join clientes c on (acc.id_cliente = c.id)
                left join vendedores v on (acc.id_vendedor = v.id)
                WHERE acc.tipo_documento in ( '.$tipo.','.$tipo2.') and acc.fecha_factura between "'.$fecha3.'"  AND "'.$fecha4.'"
                order by acc.id desc' 
                
                );
            

              }else{

                
              $data = array();
              $query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor  FROM factura_clientes acc
                left join clientes c on (acc.id_cliente = c.id)
                left join vendedores v on (acc.id_vendedor = v.id)
                WHERE acc.tipo_documento in ( '.$tipo.','.$tipo2.') and acc.fecha_factura between "'.$fecha3.'"  AND "'.$fecha4.'"
                order by acc.id desc' 

                );


              }

            };            
             


		$header = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Untitled Document</title>
		<style type="text/css">
		td {
			font-size: 16px;
		}
		p {
		}
		</style>
		</head>

		<body>
		<table width="987px" height="602" border="0">
		  <tr>
		   <td width="197px"><img src="http://localhost/Infosys_web/Infosys_web/resources/images/logo.jpg" width="150" height="136" /></td>
		    <td width="493px" style="font-size: 14px;text-align:center;vertical-align:text-top"	>
		    <p>AGRICOLA Y COMERCIAL LIRCAY S.A.</p>
		    <p>RUT:96.516.320-4</p>
		    <p>Avda San Miguel Cruce Las Rastras S/N- Talca - Chile</p>
		    <p>Fonos: (71)2 245932-2 2245933</p>
		    <p>http://www.lircay.cl</p>
		    </td>
		    <td width="296px" style="font-size: 16px;text-align:left;vertical-align:text-top"	>
		          <p>FECHA EMISION : '.date('d/m/Y').'</p>
			</td>
		  </tr>';              
              
		  $header2 = '<tr>
			<td style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" colspan="3"><h2>LIBRO DE VENTAS</h2></td>
		  </tr>
			<tr><td colspan="3">&nbsp;</td></tr>		  
			';              


		$body_header = '<tr>
		    <td colspan="3" >
		    	<table width="987px" cellspacing="0" cellpadding="0" border="0">
		      <tr>
		      	<td width="55px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Id</td>
		        <td width="62px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Numero</td>
		        <td width="65px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Fecha</td>
		        <td width="65px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Vencimiento</td>
		        <td width="70px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:center;" >Rut</td>
		        <td width="180px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Nombre</td>
		        <td width="90px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;" >Vendedor</td>
		        <td width="80px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Afecto</td>
		        <td width="80px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Descuento</td>
		        <td width="80px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Neto</td>
		        <td width="80px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >IVA</td>
		        <td width="80px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;" >Total</td>
		      </tr>';


		      $sub_total = 0;
		      $descuento = 0;
		      $neto = 0;
		      $iva = 0;
		      $totalfactura = 0;
              $i = 0;
              $body_detail = '';
              $users = $query->result_array();
		      foreach($users as $v){

					$body_detail .= '<tr>
					<td style="text-align:left;font-size: 14px;">'.$v['id'].'</td>		
					<td style="text-align:left;font-size: 14px;">'.$v['num_factura'].'</td>			
					<td style="text-align:left;font-size: 14px;">'.$v['fecha_factura'].'</td>
					<td style="text-align:right;font-size: 14px;">'.$v['fecha_venc'].'</td>
					<td style="text-align:center;font-size: 14px;">'.$v['rut_cliente'].'</td>
					<td style="text-align:left;font-size: 14px;">'.$v['nombre_cliente'].'</td>
					<td style="text-align:left;font-size: 14px;">'.$v['nom_vendedor'].'</td>
					<td align="right" style="font-size: 14px;">$ '.number_format($v['sub_total'], 0, '.', ',').'</td>
					<td align="right" style="font-size: 14px;">$ '.number_format($v['descuento'], 0, '.', ',').'</td>
					<td align="right" style="font-size: 14px;">$ '.number_format($v['neto'], 0, '.', ',').'</td>
					<td align="right" style="font-size: 14px;">$ '.number_format($v['iva'], 0, '.', ',').'</td>
					<td align="right" style="font-size: 14px;">$ '.number_format($v['totalfactura'], 0, '.', ',').'</td>
					</tr>';
					
			      $sub_total += $v['sub_total'];
			      $descuento += $v['descuento'];
			      $neto += $v['neto'];
			      $iva += $v['iva'];
			      $totalfactura += $v['totalfactura'];

		            $i++;
		         }  

				$footer .= '<tr><td colspan="12">&nbsp;</td></tr></table></td>
				  </tr>
				  <tr>
				  	<td colspan="3" >
				    	<table width="987px" cellspacing="0" cellpadding="0" border="0">
				      <tr>
				        <td width="635px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:left;font-size: 14px;" ><b>Totales</b></td>
				        <td width="80px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($sub_total, 0, ',', '.').'</b></td>
				        <td width="80px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($descuento, 0, ',', '.').'</b></td>
				        <td width="80px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($neto, 0, ',', '.').'</b></td>
				        <td width="80px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($iva, 0, ',', '.').'</b></td>
				        <td width="80px"  style="border-bottom:1pt solid black;border-top:1pt solid black;text-align:right;font-size: 14px;" ><b>$ '.number_format($totalfactura, 0, ',', '.').'</b></td>
				      </tr>
				      	</table>
				  	</td>
				  </tr></table>
				</body>
				</html>';		              
             
        $html = $header.$header2.$body_header.$body_detail.$footer;
        //echo $html; exit;
        //$html = $header.$header2.$body_header.$body_detail.$spaces;
			$this->load->library("mpdf");
			//include(defined('BASEPATH')."/libraries/MPDF54/mpdf.php");
			//include(dirname(__FILE__)."/../libraries/MPDF54/mpdf.php");

			$this->mpdf->mPDF(
				'',    // mode - default ''
				'',    // format - A4, for example, default ''
				8,     // font size - default 0
				'',    // default font family
				10,    // margin_left
				5,    // margin right
				16,    // margin top
				16,    // margin bottom
				9,     // margin header
				9,     // margin footer
				'L'    // L - landscape, P - portrait
				);  
			//echo $html; exit;
			$this->mpdf->WriteHTML($html);
			$this->mpdf->Output("Ventas.pdf", "I");

			exit;            

        }


}









