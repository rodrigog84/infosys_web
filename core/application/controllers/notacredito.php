<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notacredito extends CI_Controller {



	public function __construct()
	{
		parent::__construct();
		$this->load->helper('format');
		$this->load->database();
	}

	public function exportTXT(){

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
            $nomcliente = $v->nombre_cliente;
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
            $giro = $v->giro;
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
            $file_content .= $idbodega.$this->crearespacios($espacios21 - strlen( $idbodega)); //razon social
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
        
        $nombre_archivo = "61_NPG_".str_pad($numdocumento,10,"0",STR_PAD_LEFT).".spf";
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

	public function save2(){
		
		$resp = array();

		$numfactura_asoc = $this->input->post('numfactura_asoc'); //ID OBTENIDO PARA REBAJAR EN CUENTA CORRIENTE

		$idcliente = $this->input->post('idcliente');
		$numfactura = $this->input->post('docurelacionado');
		$numdocuemnto = $this->input->post('numdocumento');
		$idfactura = $this->input->post('idfactura');
            $idbodega = $this->input->post('idbodega');
            $fechafactura = $this->input->post('fechafactura');
		$fechavenc = $this->input->post('fechavenc');
		$vendedor = $this->input->post('idvendedor');
		$datacliente = json_decode($this->input->post('datacliente'));
		$items = json_decode($this->input->post('items'));
		$neto = $this->input->post('netofactura');
		$fiva = $this->input->post('ivafactura');
            $relacionado = $this->input->post('docurelacionado');
		$fafecto = $this->input->post('afectofactura');
		$ftotal = $this->input->post('totalfacturas');
		$tipodocumento = $this->input->post('tipodocumento');
		//$tipodocumento = 11;

		$data3 = array(
	         'correlativo' => $numdocuemnto
	    );
	    $this->db->where('id', $tipodocumento);
	  
	    $this->db->update('correlativos', $data3);
			
		$factura_cliente = array(
		  'tipo_documento' => $tipodocumento,
	        'id_cliente' => $idcliente,
              'id_bodega' => $idbodega,
	        'num_factura' => $numdocuemnto,
	        'id_vendedor' => $vendedor,
	        'sub_total' => $neto,
	        'neto' => $neto,
	        'iva' => $fiva,
	        'totalfactura' => $ftotal,
	        'fecha_factura' => $fechafactura,
	        'id_factura' => $numfactura,
	        'fecha_venc' => $fechavenc,
              'forma' => 1
	          
		);

		$this->db->insert('factura_clientes', $factura_cliente); 
		$idfactura = $this->db->insert_id();

		foreach($items as $v){
			$factura_clientes_item = array(
		        'id_factura' => $idfactura,
		        'glosa' => $v->glosa,
		        'neto' => $v->neto,
		        'iva' => $v->iva,
		        'total' => $v->total
			);

		$this->db->insert('detalle_factura_glosa', $factura_clientes_item);
    	
		}

		$data3 = array(
	         'id_factura' => $relacionado,
		);

		   
		    $this->db->where('id', $idfactura);
		  
		    $this->db->update('factura_clientes', $data3);
		


		/******* CUENTAS CORRIENTES ****/

     $query = $this->db->query("SELECT cc.id as idcuentacontable FROM cuenta_contable cc WHERE cc.nombre = 'FACTURAS POR COBRAR'");
         $row = $query->result();
         $row = $row[0];
         $idcuentacontable = $row->idcuentacontable;    



            // VERIFICAR SI CLIENTE YA TIENE CUENTA CORRIENTE
         $query = $this->db->query("SELECT co.idcliente, co.id as idcuentacorriente  FROM cuenta_corriente co
                                    WHERE co.idcuentacontable = '$idcuentacontable' and co.idcliente = '" . $idcliente . "' limit 1");
         $row = $query->row();
         $idcuentacorriente =  $row->idcuentacorriente; 


        if($query->num_rows() > 0){ 
            $query = $this->db->query("UPDATE detalle_cuenta_corriente SET saldo = saldo - " . $ftotal . " where idctacte = " .  $row->idcuentacorriente . " and numdocumento = " . $numfactura_asoc);
            //$idcuentacorriente =  $row->idcuentacorriente;
            $query_factura = $this->db->query("SELECT tipo_documento FROM factura_clientes 
            WHERE num_factura = " . $numfactura_asoc . " and id_cliente = " . $idcliente . " limit 1");
            $tipodocumento_asoc = $query_factura->row()->tipo_documento;

            $query = $this->db->query("UPDATE cuenta_corriente SET saldo = saldo - " . $ftotal . " where id = " .  $row->idcuentacorriente );
            $idcuentacorriente =  $row->idcuentacorriente;

            $saldoctacte=$row->saldo;

            $saldoctacte=$saldoctacte - $ftotal;

            $sadoctacte = array(
            'cred_util' => $ftotal
            );
            $this->db->where('id', $idcliente);

            $this->db->update('clientes', $sadoctacte);
        
           
            $cartola_cuenta_corriente = array(
                'idctacte' => $idcuentacorriente,
                'idcuenta' => $idcuentacontable,
                'tipodocumento' => $tipodocumento,
                'numdocumento' => $numdocuemnto,
                'tipodocumento_asoc' => $tipodocumento_asoc,
                'numdocumento_asoc' => $numfactura_asoc,
                'glosa' => 'Registro de Nota de Crédito en Cuenta Corriente',
                'fecvencimiento' => $fechavenc,
                'valor' => $ftotal,
                'origen' => 'VENTA',
                'fecha' => date('Y-m-d H:i:s')
            );

            $this->db->insert('cartola_cuenta_corriente', $cartola_cuenta_corriente); 
        }  		

	

        /*****************************************/

     if($tipodocumento == 102){  // SI ES NOTA DE CREDITO ELECTRONICA
            header('Content-type: text/plain; charset=ISO-8859-1');
            $this->load->model('facturaelectronica');
            $config = $this->facturaelectronica->genera_config();
            include $this->facturaelectronica->ruta_libredte();

            $tipo_nota_credito = 2;
            $glosa = 'Correccion factura '. $numfactura_asoc;

            $empresa = $this->facturaelectronica->get_empresa();
            $datos_empresa_factura = $this->facturaelectronica->get_empresa_factura($idfactura);

            //$detalle_factura = $this->facturaelectronica->get_detalle_factura($idfactura);
            $detalle_factura = $this->facturaelectronica->get_detalle_factura_glosa($idfactura);

            $lista_detalle = array();
            $i = 0;
            foreach ($detalle_factura as $detalle) {

				$lista_detalle[$i]['NmbItem'] = $detalle->glosa;
				$lista_detalle[$i]['QtyItem'] = 1;
                $lista_detalle[$i]['PrcItem'] = floor($detalle->neto);
            
                $i++;
            }



            // datos
            $nota_credito = [
                'Encabezado' => [
                    'IdDoc' => [
                        'TipoDTE' => 61,
                        'Folio' => $numdocuemnto,
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
                        'GiroRecep' => substr($datos_empresa_factura->giro,0,40), //LARGO DEL GIRO NO PUEDE SER SUPERIOR A 40 CARACTERES
                        'DirRecep' => substr($datos_empresa_factura->direccion,0,70), //LARGO DE DIRECCION NO PUEDE SER SUPERIOR A 70 CARACTERES
                        'CmnaRecep' => substr($datos_empresa_factura->nombre_comuna,0,20), //LARGO DE COMUNA NO PUEDE SER SUPERIOR A 20 CARACTERES
                    ],
                    'Totales' => [
                        // estos valores serán calculados automáticamente
                        'MntNeto' => 0,
                        'TasaIVA' => \sasco\LibreDTE\Sii::getIVA(),
                        'IVA' => 0,
                        'MntTotal' => 0,
                    ],                  
                ],
                'Detalle' => $lista_detalle,
                'Referencia' => [
                    'TpoDocRef' => 33,
                    'FolioRef' => $numfactura,
                    'CodRef' => $tipo_nota_credito,
                    'RazonRef' => $glosa,
                ]               
            ];          


            //FchResol y NroResol deben cambiar con los datos reales de producción
            $caratula = [
                //'RutEnvia' => '11222333-4', // se obtiene de la firma
                'RutReceptor' => '60803000-K',
                'FchResol' => $empresa->fec_resolucion,
                'NroResol' => $empresa->nro_resolucion
            ];

            $Firma = new sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital        
            $caf = $this->facturaelectronica->get_content_caf_folio($numdocuemnto,61);
            $Folios = new sasco\LibreDTE\Sii\Folios($caf->caf_content);

            $DTE = new \sasco\LibreDTE\Sii\Dte($nota_credito);

            $DTE->timbrar($Folios);
            $DTE->firmar($Firma);       

            // generar sobre con el envío del DTE y enviar al SII
            $EnvioDTE = new \sasco\LibreDTE\Sii\EnvioDte();

            $EnvioDTE->agregar($DTE);
            $EnvioDTE->setFirma($Firma);
            $EnvioDTE->setCaratula($caratula);
            $EnvioDTE->generar();
            if ($EnvioDTE->schemaValidate()) { // REVISAR PORQUÉ SE CAE CON ESTA VALIDACION
                
                $track_id = 0;
                $xml_dte = $EnvioDTE->generar();

                $tipo_envio = $this->facturaelectronica->busca_parametro_fe('envio_sii'); //ver si está configurado para envío manual o automático

                if($tipo_envio == 'automatico'){
                    $track_id = $EnvioDTE->enviar();
                }               

                //$track_id = 0;

                $nombre_dte = $numdocuemnto."_61_".$idfactura."_".date("His").".xml"; // nombre archivo
                $path = date('Ym').'/'; // ruta guardado
                if(!file_exists('./facturacion_electronica/dte/'.$path)){
                    mkdir('./facturacion_electronica/dte/'.$path,0777,true);
                }               
                $f_archivo = fopen('./facturacion_electronica/dte/'.$path.$nombre_dte,'w');
                fwrite($f_archivo,$xml_dte);
                fclose($f_archivo);


                $this->db->where('f.folio', $numdocuemnto);
                $this->db->where('c.tipo_caf', 61);
                $this->db->update('folios_caf f inner join caf c on f.idcaf = c.id',array('dte' => $xml_dte,
                                                                                          'estado' => 'O',
                                                                                          'idfactura' => $idfactura,
                                                                                          'path_dte' => $path,
                                                                                          'archivo_dte' => $nombre_dte,
                                                                                          'trackid' => $track_id
                                                                                          )); 




				if($track_id != 0 && $datos_empresa_factura->e_mail != ''){ //existe track id, se envía correo
						$this->facturaelectronica->envio_mail_dte($idfactura);
				}

            }                   
            
        }

        $resp['success'] = true;
		$resp['idfactura'] = $idfactura;

		$this->Bitacora->logger("I", 'factura_clientes', $idfactura);	
        

        echo json_encode($resp);
	}

	public function marca(){

		$resp = array();
		$idp = $this->input->post('idp'); //ID OBTENIDO PARA 

		$query = $this->db->query('SELECT * FROM detalle_factura_cliente WHERE id="'.$idp.'"');
		 if($query->num_rows()>0){

		 	$datos5 = array(
			'id_notacredito' => 1
			);

			$this->db->where('id', $idp);
			$this->db->update('detalle_factura_cliente', $datos5);

		};

		echo json_encode($resp);
		

	}

	public function desmarca(){

		$resp = array();
		$idp = $this->input->post('idp'); //ID OBTENIDO PARA 

		$query = $this->db->query('SELECT * FROM detalle_factura_cliente WHERE id="'.$idp.'"');
		 if($query->num_rows()>0){

		 	$datos5 = array(
			'id_notacredito' => 0
			);

			$this->db->where('id', $idp);
			$this->db->update('detalle_factura_cliente', $datos5);

		};

		echo json_encode($resp);
		

	}

	public function save(){
		//print_r($_POST); exit;
		$resp = array();
		$numfactura_asoc = $this->input->post('numfactura_asoc'); //ID OBTENIDO PARA REBAJAR EN CUENTA CORRIENTE


		$idcliente = $this->input->post('idcliente');
            $idbodega = $this->input->post('idbodega');
            //$idbodega = 1;
		$numfactura = $this->input->post('numfactura_asoc');
		$numdocuemnto = $this->input->post('numdocumento');
		$idfactura = $this->input->post('idfactura');
            $idsucursal = $this->input->post('idsucursal');
            $fechafactura = $this->input->post('fechafactura');
		$fechavenc = $this->input->post('fechavenc');
		$vendedor = $this->input->post('idvendedor');
            $condpago = $this->input->post('idcondventa');
		$datacliente = json_decode($this->input->post('datacliente'));
		$items = json_decode($this->input->post('items'));
		$neto = $this->input->post('netofactura');
		$fiva = $this->input->post('ivafactura');
		$fafecto = $this->input->post('afectofactura');
		$ftotal = $this->input->post('totalfacturas');
		$tipodocumento = $this->input->post('tipodocumento');
            $tipo = 101;
            $tipo2 = 120;

            $query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor    FROM factura_clientes acc
            left join clientes c on (acc.id_cliente = c.id)
            left join vendedores v on (acc.id_vendedor = v.id)
            WHERE acc.num_factura = '.$numfactura.' AND acc.tipo_documento in ('.$tipo.','. $tipo2 .') order by id desc');

            if($query->num_rows()>0){

                $row = $query->first_row();

                $forma = ($row->forma);

            }else{
                $forma = 0;
            }



		$data3 = array(
	         'correlativo' => $numdocuemnto
	    );
	    $this->db->where('id', $tipodocumento);
	  
	    $this->db->update('correlativos', $data3);
			
		$factura_cliente = array(
		  'tipo_documento' => $tipodocumento,
              'id_bodega' => $idbodega,
	        'id_cliente' => $idcliente,
              'id_sucursal' => $idsucursal,
	        'num_factura' => $numdocuemnto,
	        'id_vendedor' => $vendedor,
              'id_cond_venta' => $condpago,
	        'sub_total' => $neto,
	        'descuento' => ($neto - $fafecto),
	        'neto' => $neto,
	        'iva' => $fiva,
	        'totalfactura' => $ftotal,
	        'fecha_factura' => $fechafactura,
	        'id_factura' => $numfactura,
	        'fecha_venc' => $fechavenc
	          
		);

		$this->db->insert('factura_clientes', $factura_cliente); 
		$idfactura = $this->db->insert_id();

		foreach($items as $v){

			$factura_clientes_item = array(
		        'id_producto' => $v->id_producto,
		        'id_factura' => $idfactura,
		        'num_factura' => $numdocuemnto,
		        'precio' => $v->precio,
		        'cantidad' => $v->cantidad,
		        'neto' => $v->total,
		        'descuento' => $v->dcto,
		        'iva' => $v->iva,
		        'totalproducto' => $v->totaliva,
		        'fecha' => $fechafactura
			);

		$producto = $v->id_producto;
		$idp = $v->id;

		$this->db->insert('detalle_factura_cliente', $factura_clientes_item);

		$query = $this->db->query('SELECT * FROM detalle_factura_cliente WHERE id="'.$idp.'"');
		 if($query->num_rows()>0){

		 	$datos5 = array(
			'id_notacredito' => $idfactura
			);

			$this->db->where('id', $idp);
			$this->db->update('detalle_factura_cliente', $datos5);

		};

			
		$query = $this->db->query('SELECT * FROM productos WHERE id="'.$producto.'"');
		 if($query->num_rows()>0){

		 	$row = $query->first_row();

		 	$saldo = ($row->stock)+($v->cantidad); 

		 };

                  $query = $this->db->query('SELECT * FROM existencia WHERE id_producto="'.$producto.'" and id_bodega='.$idbodega.'');
                  $row = $query->result();
                  $row = $row[0];
	 
                  if ($query->num_rows()>0){

                  if ($producto==($row->id_producto)){
                      $datos3 = array(
                  	'stock' => $saldo,
                    'fecha_ultimo_movimiento' => date('Y-m-d H:i:s')
                  	);

                  	$this->db->where('id_producto', $producto);

                      $this->db->update('existencia', $datos3);
                  }else{

                  	$datos3 = array(
                  	'id_producto' => $producto,
                    'stock' =>  $saldo,
                    'fecha_ultimo_movimiento' =>date('Y-m-d H:i:s'),
                    'id_bodega' => 1

                  	);
                  	$this->db->insert('existencia', $datos3);
                   	}
                  }else{
                        if ($producto==($row->id_producto)){
                            $datos3 = array(
                        	'stock' => $saldo,
                          'fecha_ultimo_movimiento' => date('Y-m-d H:i:s')
                        	);

                        	$this->db->where('id_producto', $producto);

                            $this->db->update('existencia', $datos3);
                        }else{

                              $datos3 = array(
                              'id_producto' => $producto,
                              'stock' =>  $saldo,
                              'fecha_ultimo_movimiento' =>date('Y-m-d H:i:s'),
                              'id_bodega' => 1

                              );
                              $this->db->insert('existencia', $datos3);
                        }
                  }
            $datos2 = array(

            'num_movimiento' => $numdocuemnto,
            'id_producto' => $v->id_producto,
            'id_bodega' => $idbodega,
            'id_tipo_movimiento' => $tipodocumento,
            'valor_producto' =>  $v->precio,
            'cantidad_entrada' => $v->cantidad,
            'fecha_movimiento' => $fechafactura
            );

            $this->db->insert('existencia_detalle', $datos2);

            $datos = array(
            'stock' => $saldo,
            );

          	$this->db->where('id', $producto);

          	$this->db->update('productos', $datos);

            if ($forma==3){
                  
            }else{
                  $queryt = $this->db->query('SELECT * FROM existencia_detalle WHERE id='.$v->id_existencia.' and id_bodega='.$idbodega.'');
                  $row = $queryt->result();
                  if ($queryt->num_rows()>0){
                  $row = $row[0];
                  $saldoext=($row->saldo + $v->cantidad);                 
                  $datos5 = array(
                  'saldo' => $saldoext
                  );   
                  $this->db->where('id', $v->id_existencia);
                  $this->db->update('existencia_detalle', $datos5);   
                  };                  
            }           
    	
		}


		/******* CUENTAS CORRIENTES ****/

            $query = $this->db->query("SELECT cc.id as idcuentacontable FROM cuenta_contable cc WHERE cc.nombre = 'FACTURAS POR COBRAR'");
            $row = $query->result();
            $row = $row[0];
            $idcuentacontable = $row->idcuentacontable;
			
            $query = $this->db->query("SELECT co.idcliente, co.id as idcuentacorriente, co.saldo  FROM cuenta_corriente co
            WHERE co.idcuentacontable = '$idcuentacontable' and co.idcliente = '" . $idcliente . "' limit 1");
            $row = $query->row();
            $idcuentacorriente =  $row->idcuentacorriente;

           
		if($query->num_rows() > 0){ //
			//se rebaja cuenta corriente 
			$query = $this->db->query("UPDATE cuenta_corriente SET saldo = saldo - " . $ftotal . " where id = " .  $row->idcuentacorriente );
			
                  $saldoctacte=$row->saldo;
                  $saldoctacte=$saldoctacte - $ftotal;

                  $sadoctacte = array(
                  'cred_util' => $saldoctacte
                  );
                  $this->db->where('id', $idcliente);

                  $this->db->update('clientes', $sadoctacte);

			$query = $this->db->query("UPDATE detalle_cuenta_corriente SET saldo = saldo - " . $ftotal . " where idctacte = " .  $row->idcuentacorriente . " and numdocumento = " . $numfactura_asoc);
			//$idcuentacorriente =  $row->idcuentacorriente;
			 $query_factura = $this->db->query("SELECT tipo_documento  FROM factura_clientes 
			 							WHERE num_factura = " . $numfactura_asoc . " and id_cliente = " . $idcliente . " limit 1");
			 $tipodocumento_asoc = $query_factura->row()->tipo_documento;

			$cartola_cuenta_corriente = array(
		        'idctacte' => $idcuentacorriente,
		        'idcuenta' => $idcuentacontable,
		        'tipodocumento' => $tipodocumento,
		        'numdocumento' => $numdocuemnto,
		        'tipodocumento_asoc' => $tipodocumento_asoc,
		        'numdocumento_asoc' => $numfactura_asoc,
		        'glosa' => 'Registro de Nota de Crédito en Cuenta Corriente',
		        'fecvencimiento' => $fechavenc,
		        'valor' => $ftotal,
		        'origen' => 'VENTA',
		        'fecha' => date('Y-m-d H:i:s')
			);

			$this->db->insert('cartola_cuenta_corriente', $cartola_cuenta_corriente); 
		}			

		/*****************************************/


		if($tipodocumento == 102){  // SI ES NOTA DE CREDITO ELECTRONICA
			header('Content-type: text/plain; charset=ISO-8859-1');
			$this->load->model('facturaelectronica');
			$config = $this->facturaelectronica->genera_config();
			include $this->facturaelectronica->ruta_libredte();

			$tipo_nota_credito = $this->input->post('tipo_nota_credito');
            $tipo_doc_glosa = $tipodocumento_asoc == 120 ? 'boleta' : 'factura';

            $glosa = $tipo_nota_credito == 1 ? 'Anula ' . $tipo_doc_glosa .' '. $numfactura_asoc : 'Correccion '. $tipo_doc_glosa . ' ' . $numfactura_asoc;

			$empresa = $this->facturaelectronica->get_empresa();
			$datos_empresa_factura = $this->facturaelectronica->get_empresa_factura($idfactura);

			$detalle_factura = $this->facturaelectronica->get_detalle_factura($idfactura);
    		$lista_detalle = array();
			$i = 0;
			foreach ($detalle_factura as $detalle) {
				$lista_detalle[$i]['NmbItem'] = $detalle->nombre;
				$lista_detalle[$i]['QtyItem'] = $detalle->cantidad;
				//$lista_detalle[$i]['PrcItem'] = floor($detalle->precio/1.19);
				//$lista_detalle[$i]['PrcItem'] = round($detalle->precio/1.19,0);
                $lista_detalle[$i]['PrcItem'] = round($detalle->precio,0);

				if($detalle->descuento != 0){
					//$porc_descto = round(($detalle->descuento/($detalle->cantidad*$lista_detalle[$i]['PrcItem'])*100),0);
					//$lista_detalle[$i]['DescuentoPct'] = $porc_descto;		
					//$lista_detalle[$i]['PrcItem'] =- $lista_detalle[$i]['PrcItem']*$porc_descto;
					$total_sin_iva = round($detalle->totalproducto/1.19,0);
					$descuento = abs(($lista_detalle[$i]['PrcItem']*$detalle->cantidad) - $total_sin_iva);
					$lista_detalle[$i]['DescuentoMonto'] = $descuento;
				}				
				//$lista_detalle[$i]['DescuentoMonto'] = $detalle->descuento;
				$i++;
			}

            $TpoDocRef = tdtocaf($tipodocumento_asoc);

            $rutCliente = substr($datos_empresa_factura->rut_cliente,0,strlen($datos_empresa_factura->rut_cliente) - 1)."-".substr($datos_empresa_factura->rut_cliente,-1);
             $dir_cliente = is_null($datos_empresa_factura->dir_sucursal) ? permite_alfanumerico($datos_empresa_factura->direccion) : permite_alfanumerico($datos_empresa_factura->dir_sucursal);
             $nombre_comuna = is_null($datos_empresa_factura->com_sucursal) ? permite_alfanumerico($datos_empresa_factura->nombre_comuna) : permite_alfanumerico($datos_empresa_factura->com_sucursal);

			// datos
			$nota_credito = [
			    'Encabezado' => [
			        'IdDoc' => [
			            'TipoDTE' => 61,
			            'Folio' => $numdocuemnto,
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
                        'TasaIVA' => \sasco\LibreDTE\Sii::getIVA(),
                        'IVA' => isset($datos_factura->iva) ? $datos_factura->iva : 0,
                        'MntTotal' => isset($datos_factura->totalfactura) ? $datos_factura->totalfactura : 0,                        
		            ],			        
			    ],
				'Detalle' => $lista_detalle,
		        'Referencia' => [
		            'TpoDocRef' => $TpoDocRef,
		            'FolioRef' => $numfactura,
		            'CodRef' => $tipo_nota_credito,
		            'RazonRef' => $glosa,
		        ]				
			];			


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


			$Firma = new sasco\LibreDTE\FirmaElectronica($config['firma']); //lectura de certificado digital		
			$caf = $this->facturaelectronica->get_content_caf_folio($numdocuemnto,61);
			$Folios = new sasco\LibreDTE\Sii\Folios($caf->caf_content);

			$DTE = new \sasco\LibreDTE\Sii\Dte($nota_credito);

			$DTE->timbrar($Folios);
			$DTE->firmar($Firma);		

			// generar sobre con el envío del DTE y enviar al SII
			$EnvioDTE = new \sasco\LibreDTE\Sii\EnvioDte();

			$EnvioDTE->agregar($DTE);
			$EnvioDTE->setFirma($Firma);
			$EnvioDTE->setCaratula($caratula);
			$EnvioDTE->generar();
			if ($EnvioDTE->schemaValidate()) { // REVISAR PORQUÉ SE CAE CON ESTA VALIDACION
				
				$track_id = 0;
			    $xml_dte = $EnvioDTE->generar();

                 #GENERACIÓN DTE CLIENTE
                $EnvioDTE_CLI = new \sasco\LibreDTE\Sii\EnvioDte();
                $EnvioDTE_CLI->agregar($DTE);
                $EnvioDTE_CLI->setFirma($Firma);
                $EnvioDTE_CLI->setCaratula($caratula_cliente);
                $xml_dte_cliente = $EnvioDTE_CLI->generar();    

			    $tipo_envio = $this->facturaelectronica->busca_parametro_fe('envio_sii'); //ver si está configurado para envío manual o automático

			    if($tipo_envio == 'automatico'){
				    $track_id = $EnvioDTE->enviar();
			    }			    


                $dte = $this->facturaelectronica->crea_archivo_dte($xml_dte,$idfactura,61,'sii');
                $dte_cliente = $this->facturaelectronica->crea_archivo_dte($xml_dte_cliente,$idfactura,61,'cliente');



			

                $this->db->where('f.folio', $numdocuemnto);
                $this->db->where('c.tipo_caf', 61);
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
						//$this->facturaelectronica->envio_mail_dte($idfactura);
				}

			}				
			
		}


        $resp['success'] = true;
		$resp['idfactura'] = $idfactura;

		$this->Bitacora->logger("I", 'factura_clientes', $idfactura);

		
        

        echo json_encode($resp);
	}


	
	public function getAllnc(){
		
		$resp = array();
		$start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $opcion = $this->input->get('opcion');
        $nombres = $this->input->get('nombre');
        $tipo = "11";
        $tipo2 = "102";

		$countAll = $this->db->count_all_results("factura_clientes");
		$data = array();
		$total = 0;

		if($opcion == "Rut"){
		
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, td.descripcion as tipo_doc	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			WHERE acc.tipo_documento in ('.$tipo.','.$tipo2.') and c.rut = '.$nombres.'
			order by acc.id desc		
			limit '.$start.', '.$limit.''		 

		);

	    }else if($opcion == "Nombre"){

	    	
			$sql_nombre = "";
	        $arrayNombre =  explode(" ",$nombres);

	        foreach ($arrayNombre as $nombre) {
	        	$sql_nombre .= "and c.nombres like '%".$nombre."%' ";
	        }
	        	    	
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, td.descripcion as tipo_doc	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			WHERE acc.tipo_documento in ('.$tipo.','.$tipo2.') ' . $sql_nombre . '
			order by acc.id desc		
			limit '.$start.', '.$limit.''	
			
			);
	 
		}else if($opcion == "Todos"){

			
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, td.descripcion as tipo_doc	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			WHERE acc.tipo_documento in ('.$tipo.','.$tipo2.')
			order by acc.id desc		
			limit '.$start.', '.$limit.''	
			
			);
	

		}else{

			
		$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor, td.descripcion as tipo_doc	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			left join tipo_documento td on (acc.tipo_documento = td.id)
			WHERE acc.tipo_documento in ('.$tipo.','.$tipo2.')
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
			$countAll = $total;
		}

        $resp['success'] = true;
        $resp['total'] = $countAll;
        $resp['data'] = $data;

        echo json_encode($resp);
	}

	public function validaproducto(){
		
		$resp = array();
		$idproducto = $this->input->post('idproducto');
		$idfactura = $this->input->post('idfactura');

		if ($idfactura){

		$query = $this->db->query('SELECT * FROM detalle_factura_cliente 
		WHERE id_producto like '.$idproducto.' AND id_factura like '.$idfactura.'');
    	       $row = $query->first_row();
		
		if($query->num_rows()>0){
			$resp['success'] = true;		 	
		 }else {
                  $query = $this->db->query('SELECT * FROM detalle_factura_glosa 
                  WHERE id_producto like '.$idproducto.' AND id_factura like '.$idfactura.'');
                  $row = $query->first_row();            
                  if($query->num_rows()>0){
                    $resp['success'] = true;
                  }else{
                    $resp['success'] = false;                        
                  }
		};

		$resp['cliente'] = $row;
	    }else{

	    	$resp['success'] = true;
	    	
	    };
        
        echo json_encode($resp);
	}

	
	public function exportNotacreditoPDF(){

		$idfactura = $this->input->get('idfactura');
		$numero = $this->input->get('numfactura');

        if ($idfactura){
		$query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)		
			WHERE acc.id = '.$idfactura.'');
		}else{
			$query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor FROM factura_clientes acc
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
		$fecha = $row->fecha_venc;
		$numfact = $row->id_factura;
		list($anio, $mes, $dia) = explode("-",$fecha);
		$fecha2 = $row->fecha_factura;
		list($anio2, $mes2, $dia2) = explode("-",$fecha2);
		 
		//items
		$items = $this->db->get_where('detalle_factura_cliente', array('id_factura' => $row->id));
		//print_r($items->result());exit;
		//variables generales
		$codigo = $row->num_factura;
		$nombre_contacto = $row->nombre_cliente;
		$rut_cliente = $row->rut_cliente;
		$direccion = $row->direccion;
		$comuna = $row->nombre_comuna;
		$ciudad = $row->nombre_ciudad;
		$fecha = $row->fecha_venc;
		$giro = $row->giro;
		$cabecera = $this->db->get_where('factura_clientes', array('id' => $row->id));		
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
            <td width="740px" height="80px">&nbsp;</td>
	        <td width="247px" height="80px" style="font-size: 25px;vertical-align:bottom;"> N°'.$codigo.'</td>
          </tr>
          </table>
        </td>
      </tr> 
      <tr>
        <td width="987px">
          <table width="987px" border="0">
          <tr>
            <td width="90px" height="60px">&nbsp;</td>
	        <td width="90px" height="60px" style="vertical-align:bottom;">'.$dia2.'</td>
	        <td width="350px" height="60px" style="vertical-align:bottom;">'.month2string($mes2).'</td>
	        <td width="70px" height="60px" style="vertical-align:bottom;">'.$anio2.'</td>
	        <td width="387px" height="60px" style="vertical-align:bottom;">&nbsp;</td>
          </tr>
          </table>
        </td>
      </tr>       
      <tr>
        <td width="987px">
          <table width="987px" border="0">
          <tr>
            <td width="120px" height="20px">&nbsp;</td>
	        <td width="700px" height="20px" style="vertical-align:bottom;">'.$nombre_contacto.'</td>
	        <td width="100px" height="20px" style="vertical-align:bottom;">'.number_format(substr($rut_cliente,0,strlen($rut_cliente) - 1),0,".",".")."-".substr($rut_cliente,-1).'</td>
	        <td width="67px" height="20px" style="vertical-align:bottom;">&nbsp;</td>
          </tr>
          </table>
        </td>
      </tr>             
      <tr>
        <td width="987px">
          <table width="987px" border="0">
          <tr>
            <td width="120px" height="20px">&nbsp;</td>
	        <td width="700px" height="20px" style="vertical-align:bottom;">'.$direccion.'</td>
	        <td width="100px" height="20px" style="vertical-align:bottom;">'.$ciudad.'</td>
	        <td width="67px" height="20px" style="vertical-align:bottom;">&nbsp;</td>
          </tr>
          </table>
        </td>
      </tr>                   
      <tr>
      <td width="987px">
         <table width="987px" border="0">
          <tr>
	      	<td width="120px" height="20px">&nbsp;</td>
	        <td width="867px">' . $giro . '</td>
          </tr>
          </table>	        
       </td>
      </tr> 
      <tr>
      <td  width="120px" height="50px">&nbsp;</td>
      </tr>                  
      <tr>
            <td width="987px" >
            <table width="987px" border="0">';
     $tamano_maximo = 180;
     $i = 1;
    foreach($items->result() as $v){      
      $this->db->where('id', $v->id_producto);
      $producto = $this->db->get("productos");  
      $producto = $producto->result();
      $producto = $producto[0];   

          $html .= '
              
                <tr>
                  <td width="50px" height="20px">&nbsp;</td>
                  <td width="100px" height="20px">' . $v->cantidad . '</td>
                  <td width="600px" height="20px">' . $producto->nombre . '</td>
                  <td width="150px" height="20px">' . number_format($v->precio, 0, ',', '.') . '</td>                  
                  <td width="87px" height="20px">' . number_format($v->totalproducto, 0, ',', '.') . '</td>
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
	      	<td width="150px" height="20px">&nbsp;</td>
	        <td width="750px" height="20px">' . valorEnLetras($totalFactura) . '</td>
	        <td width="87px"  height="20px">' . number_format($montoNeto, 0, ',', '.') . '</td>
          </tr>
          </table>	        
       </td>
      </tr> 
      <tr>
      <td width="987px">
         <table width="987px" border="0">
          <tr>
	      	<td width="150px" height="20px">&nbsp;</td>
	        <td width="750px" height="20px">&nbsp;</td>
	        <td width="87px"  height="20px">' . number_format($ivaTotal, 0, ',', '.') . '</td>
          </tr>
          </table>	        
       </td>
      </tr> 
      <tr>
      <td width="987px">
         <table width="987px" border="0">
          <tr>
	      	<td width="150px" height="20px">&nbsp;</td>
	        <td width="750px" height="20px">&nbsp;</td>
	        <td width="87px"  height="20px">' . number_format($totalFactura, 0, ',', '.') . '</td>
          </tr>
          </table>	        
       </td>
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


	public function exportBoletaPDF($idfactura,$numero){

		//$idfactura = $this->input->get('idfactura');
		//$numero = $this->input->get('numfactura');

        if ($idfactura){
		$query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join cod_activ_econ e on (c.id_giro = e.id)
			left join comuna m on (c.id_comuna = m.id)
			left join ciudad s on (c.id_ciudad = s.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.id = '.$idfactura.'
		');
		}else{
			$query = $this->db->query('SELECT acc.*, c.direccion as direccion, e.nombre as giro, c.nombres as nombre_cliente, c.rut as rut_cliente, m.nombre as nombre_comuna, s.nombre as nombre_ciudad, v.nombre as nom_vendedor FROM factura_clientes acc
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

		$cabecera = $this->db->get_where('factura_clientes', array('id' => $row->id));		
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
   <table width="650" border="0" cellpadding="0" cellspacing="0">
   	  
      <tr>
        <td width="450"><span class="style6">&nbsp;</span><span class="colorTextoFijo"></span></td>
		<td class="style6"><center>'.$codigo.'</center></td>
      </tr>
    </table>
    <p align="right"><b>'.$dia2.'/'.$mes2.'/'.$anio2.'</b></p>
    <p align="right"><b>'.$dia.'/'.$mes.'/'.$anio.'</b></p>
    <br><br>
  <table>
  <tr>
    <td>&nbsp;</td>
   
    <td>&nbsp;</td>
  </tr>
  </table>

  <table border="0" cellspacing="0" cellpadding="0">
  		<tr>
         <td>&nbsp;</td>        
        </tr>
        <tr>
         <td>&nbsp;</td>        
        </tr>
        <tr>
         <td>&nbsp;</td>        
        </tr>
        <tr>
         <td>&nbsp;</td>        
        </tr>
      ';
      $i = 1;
	foreach($items->result() as $v){      
			$this->db->where('id', $v->id_producto);
			$producto = $this->db->get("productos");	
			$producto = $producto->result();
			$producto = $producto[0];

     $html .= '<tr>
        <td >'.$v->cantidad.'</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td ><b>'.$producto->nombre.'</b></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      	<td >&nbsp;</td>
      	<td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        
        <td >'.number_format($v->precio, 0, ',', '.').'</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >'.number_format($v->descuento, 0, ',', '.').'</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
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
        <td ><b>'.number_format($v->totalproducto, 0, ',', '.').'</b></td>
        </tr>';
        $i++;

    }

    if($i < 15){
    	for($j=$i;$j<=15;$j++){
		        $html .= '<tr>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        <td >&nbsp;</td>
		        
		        </tr>';
    	}
    }

      
      $html .='
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
        <td ><b>'.number_format($totalFactura, 0, ',', '.').'</b></td>
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
}











