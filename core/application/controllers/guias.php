<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Guias extends CI_Controller {



	public function __construct()
	{
		parent::__construct();
		
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
		    $file_content .= $nomclienteinicio;  //razon social
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
        
        $nombre_archivo = "33_NPG_".str_pad($numdocumento,10,"0",STR_PAD_LEFT).".spf";
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

	public function procesomarca(){

		$resp = array();
		$query = $this->db->query('SELECT * FROM detalle_factura_glosa ');

		if($query->num_rows()>0){

			foreach ($query->result() as $row){
					
					$idguia = $row->id_guia;

			   		$data3 = array(
			         'id_despacho' => $row->id_factura
				    );
					$this->db->where('id_factura', $idguia);		  
		    		$this->db->update('detalle_factura_cliente', $data3);
		    		$data[] = $row;    			

	    	};

	    	$resp['success'] = true;
	    	$resp['data'] = $data;

		}else{
			
			$resp['success'] = false;

		};
		 
		
	    echo json_encode($resp);

	}

	public function marcarguias(){

		$resp = array();
		$idfactura = $this->input->post('factura');
		$tipo = 3;

		$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
		left join clientes c on (acc.id_cliente = c.id)
		left join vendedores v on (acc.id_vendedor = v.id)
		WHERE acc.tipo_documento in ( '.$tipo.') and acc.id = '.$idfactura.''			
		);

		if($query->num_rows()>0){
	   		$row = $query->first_row();
	   		$id = ($row->id);

	   		$data3 = array(
	         'id_factura' => 1,
		    );

		    $this->db->where('id', $id);
		  
		    $this->db->update('factura_clientes', $data3);

		    $this->Bitacora->logger("M", 'factura_clientes', $id); 
			
			

	     };

	     $resp['success'] = true;
	     echo json_encode($resp);

		
	}

	public function numerofactura(){

		$resp = array();
		$factura = $this->input->get('factura');
       		
		$query = $this->db->query('SELECT * FROM correlativos WHERE id like "'.$factura.'"');

		$id = $data->id;
		$data = array(
	        'nombre' => strtoupper($data->nombre),
	        'correlativo' => $data->correlativo
	    );
		$this->db->where('id', $id);
		
		$this->db->update('correlativos', $data); 

        $resp['success'] = true;

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

	
	public function getAll(){
		
		$resp = array();
		$start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $opcion = $this->input->get('opcion');
        $nombres = $this->input->get('nombre');
        $tipo = 3;
        $countAll = $this->db->count_all_results("factura_clientes");
		$data = array();
		$total = 0;
	

        if($opcion == "Rut"){
		
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.tipo_documento in ( '.$tipo.') and c.rut = '.$nombres.'
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
	        	    	
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.tipo_documento in ( '.$tipo.') ' . $sql_nombre . '
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
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.tipo_documento in ( '.$tipo.')
			order by acc.id desc'	
			
			);


			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;
	

		}else{

			
		$data = array();
		$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.tipo_documento in ( '.$tipo.')
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

	public function pendientes(){
		
		$resp = array();
		$start = $this->input->get('start');
        $limit = $this->input->get('limit');
        $opcion = $this->input->get('opcion');
        $nombres = $this->input->get('nombre');
        $idcliente = $this->input->get('idcliente');
        $tipo = 3;
        $countAll = $this->db->count_all_results("factura_clientes");
		$data = array();
		$total = 0;
	

        if($opcion == "Rut"){
		
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.tipo_documento in ('.$tipo.') and c.rut = '.$nombres.'
			and acc.id_factura = 0
			order by acc.id desc		
			limit '.$start.', '.$limit.''		 

		);

		$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;

	    }if($opcion == "Numero"){
		
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.tipo_documento in ('.$tipo.') and acc.num_factura = '.$nombres.'
			and acc.id_factura = 0'		 

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
	        	    	
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.tipo_documento in ( '.$tipo.') ' . $sql_nombre . '
			and acc.id_factura = 0
			order by acc.id desc		
			limit '.$start.', '.$limit.''
						
			);

			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;
	 
		}else if($opcion == "Id"){
			
			$data = array();
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.tipo_documento in ('.$tipo.') and acc.id_cliente = '.$nombres.'
			and acc.id_factura = 0
			order by acc.id desc'
			);



			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;
	

		}else if($opcion == "Todos"){

			
			$data = array();
			$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.tipo_documento in ( '.$tipo.') and acc.id_factura = 0
			order by acc.id desc'	
			
			);


			$total = 0;

		  foreach ($query->result() as $row)
			{
				$total = $total +1;
			
			}

			$countAll = $total;
	

		}else{

			
		$data = array();
		$query = $this->db->query('SELECT acc.*, c.nombres as nombre_cliente, c.rut as rut_cliente, v.nombre as nom_vendedor	FROM factura_clientes acc
			left join clientes c on (acc.id_cliente = c.id)
			left join vendedores v on (acc.id_vendedor = v.id)
			WHERE acc.tipo_documento in ( '.$tipo.') and acc.id_factura = 0
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

	public function save(){
		
		$resp = array();

		$idcliente = $this->input->post('idcliente');
		$numfactura = $this->input->post('numfactura');
		$fechafactura = $this->input->post('fechafactura');
		$sucursal = $this->input->post('idsucursal');
		$observacion = $this->input->post('observacion');
		$idobserva = $this->input->post('idobserva');
		$fechavenc = $this->input->post('fechavenc');
		$vendedor = $this->input->post('idvendedor');
		$items = json_decode($this->input->post('items'));
		$neto = $this->input->post('netofactura');
		$fiva = $this->input->post('ivafactura');
		$fafecto = $this->input->post('afectofactura');
		$ftotal = $this->input->post('totalfacturas');
		$descuento = $this->input->post('descuentofactuta');
		$tipodocumento = 1;

		$data3 = array(
	         'correlativo' => $numfactura
	    );
	    $this->db->where('id', $tipodocumento);
	  
	    $this->db->update('correlativos', $data3);
			
		$factura_cliente = array(
			'tipo_documento' => $tipodocumento,
	        'id_cliente' => $idcliente,
	        'num_factura' => $numfactura,
	        'id_vendedor' => $vendedor,
	        'id_sucursal' => $sucursal,
	        'sub_total' => $neto,
	        'observacion' => $observacion,
	        'id_observa' => $idobserva,
	        'descuento' => $descuento,
	        'neto' => $fafecto,
	        'iva' => $fiva,
	        'totalfactura' => $ftotal,
	        'fecha_factura' => $fechafactura,
	        'fecha_venc' => $fechavenc,
	        'forma' => 1
		);

		$this->db->insert('factura_clientes', $factura_cliente); 
		$idfactura = $this->db->insert_id();

		foreach($items as $v){
			$factura_clientes_item = array(
		        'id_guia' => $v->id_guia,
		        'id_factura' => $idfactura,
		        'num_guia' => $v->num_guia,
		        'neto' => $v->neto,
		        'iva' => $v->iva,
		        'total' => $v->total
			);


		
		$this->db->insert('detalle_factura_glosa', $factura_clientes_item);

			$data3 = array(
	         'id_factura' => $idfactura,
		    );

		    $data4 = array(
	         'id_despacho' => $idfactura,
		    );

		    $this->db->where('id', $v->id_guia);
		  
		    $this->db->update('factura_clientes', $data3);

		    $this->db->where('num_factura', $v->num_guia);
		  
		    $this->db->update('detalle_factura_cliente', $data4);

		}

		/******* CUENTAS CORRIENTES ****/

		 $query = $this->db->query("SELECT cc.id as idcuentacontable FROM cuenta_contable cc WHERE cc.nombre = 'FACTURAS POR COBRAR'");
		 $row = $query->result();
		 $row = $row[0];
		 $idcuentacontable = $row->idcuentacontable;	


			// VERIFICAR SI CLIENTE YA TIENE CUENTA CORRIENTE
		 $query = $this->db->query("SELECT co.idcliente, co.id as idcuentacorriente  FROM cuenta_corriente co
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


		}else{
			$row = $row[0];
			$query = $this->db->query("UPDATE cuenta_corriente SET saldo = saldo + " . $ftotal . " where id = " .  $row->idcuentacorriente );
			$idcuentacorriente =  $row->idcuentacorriente;
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


        $resp['success'] = true;
		$resp['idfactura'] = $idfactura;

		$this->Bitacora->logger("I", 'factura_clientes', $idfactura);

		
        

        echo json_encode($resp);
	}

	

	

}









