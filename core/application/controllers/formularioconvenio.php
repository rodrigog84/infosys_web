<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formularioconvenio extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->database();
	}

     public function valida2(){

      $resp = array();
      $id = $this->input->post('id');  
      $tipo = $this->input->post('tipo');  
      $resp['success'] = true;
            
      $query = $this->db->query('SELECT * FROM clientes WHERE id like "'.$id.'"');                  
      if($query->num_rows()>0){
            $row = $query->first_row();
            $convenio = $row->convenio;
            $acuerdosuscrito = $row->acuerdosuscrito;
            $notificacion = $row->notificacion;
            
            if ($tipo=="1"){
               if ($convenio==""){
                  $resp['success'] = false;                        
                };              
            };
            if ($tipo=="2"){
               if ($acuerdosuscrito==""){
                  $resp['success'] = false;
                  };              
            };
            if ($tipo=="3"){
               if ($notificacion==""){
                  $resp['success'] = false;
                  };              
            };
        };
        echo json_encode($resp);      
    }

    public function rescatar(){

        $resp = array();

        $fecha = $this->input->post('fecha_subida');
        $fecha2 = $this->input->post('fecha_subida');
        $id = $this->input->post('id_cliente');
        $tipo = $this->input->post('id');
        $usuario = $this->input->post('usuario');         
        list($dia, $mes, $anio) = explode("/",$fecha);
        $fecha = $anio ."-". $mes ."-". $dia;     

        $query = $this->db->query('SELECT * FROM clientes WHERE id like "'.$id.'"');                  
        if($query->num_rows()>0){
              $row = $query->first_row();
              $rut = $row->rut;
              if($tipo==1){
                $config['file_name'] = 'convenio'.$rut; 
                $nota="Se Firma Acuerdo de Pago con Fecha".$fecha2;  
              };
               if($tipo==2){
                $config['file_name'] = 'conveniosuscrito'.$rut;
                $nota="Se Ingresa Convenio de Pago con Fecha".$fecha2;
              };
               if($tipo==3){
                $config['file_name'] = 'notificacion'.$rut;
                $nota="Se recibe autorizacion de Convenio de Pago con Fecha".$fecha2;
              };
              $config['upload_path'] = "./cargas/convenio/"  ;              
              $config['allowed_types'] = "*";
              $config['max_size'] = "10240";
              $config['overwrite'] = TRUE;

              $data2 = array(
                  'tipo_acuerdo' => 1                    
              );
              $this->db->where('id', $id);
              $this->db->update('clientes', $data2);
              
              /* $query2 = $this->db->query('SELECT * FROM usuario WHERE id like "'.$usuario.'"');                  
               if($query2->num_rows()>0){
                  $row = $query2->first_row();
                  $nombre = $row->nombre;
                  $apellido = $row->apellido;
               };

               $autoriza=$nombre." ".$apellido."/"."RICARDO POZO";

               $data3 = array(
                  'id_cliente' => $id,
                  'observaciones' => $nota,
                  'fecha' => $fecha,
                  'hora' => date("h:i:s A"),
                  'autoriza' => $autoriza               
              );
              $this->db->insert('notas_clientes', $data3);*/
                      
              
        };      
       
        
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload("archivo")) {
            print_r($this->upload->data()); 
            print_r($this->upload->display_errors());
            $error = true;
            $message = "Error en subir archivo.  Intente nuevamente";
        };
        //print_r($this->upload->data()); 

       $data_file_upload = $this->upload->data();

       $nombre_archivo = $config['upload_path'].$config['file_name'].$data_file_upload['file_ext'];

       $nombre_grabar = $config['file_name'].$data_file_upload['file_ext'];

       if($tipo==1){
          $data3 = array(
              'convenio' => $nombre_grabar,
              'fecha_acuerdo' => $fecha                              
          );          
          $this->db->where('id', $id);
          $this->db->update('clientes', $data3);           
                
       };
       if($tipo==2){
          $data3 = array(
              'acuerdosuscrito' => $nombre_grabar,
              'fecha_acuerdo' => $fecha
              );          
          $this->db->where('id', $id);
          $this->db->update('clientes', $data3);           
                
       };
       if($tipo==3){
          $data3 = array(
              'notificacion' => $nombre_grabar,
              'fecha_acuerdo' => $fecha                            
          );          
          $this->db->where('id', $id);
          $this->db->update('clientes', $data3);           
                
       };

       $resp['success'] = true;
       echo json_encode($resp);      

    }

    public function compromisopdf1(){

      $id = $this->input->get('id');        
      $query = $this->db->query('SELECT * FROM clientes WHERE id like "'.$id.'"');                  
      if($query->num_rows()>0){
            $row = $query->first_row();
            $convenio = $row->convenio;
            $mi_pdf = './cargas/convenio/'.$convenio; //$convenio
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="'.$mi_pdf.'"');
            readfile($mi_pdf);
            exit;            
      };

      }  

      public function compromisopdf4(){

      $id = $this->input->get('id'); 
      $query = $this->db->query('SELECT * FROM clientes WHERE id like "'.$id.'"');                  
      if($query->num_rows()>0){
          $row = $query->first_row();
          $acuerdosuscrito = $row->acuerdosuscrito;
          $mi_pdf = './cargas/convenio/'.$acuerdosuscrito; //$convenio
          header('Content-type: application/pdf');
          header('Content-Disposition: attachment; filename="'.$mi_pdf.'"');
          readfile($mi_pdf);
          exit;   
      };

      }  

      public function compromisopdf3(){

      $id = $this->input->get('id');      
      $query = $this->db->query('SELECT * FROM clientes WHERE id like "'.$id.'"');                  
      if($query->num_rows()>0){
            $row = $query->first_row();
            $notificacion = $row->notificacion;
            $mi_pdf = './cargas/convenio/'.$notificacion; //$convenio
            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="'.$mi_pdf.'"');
            readfile($mi_pdf);
            exit;       
      };
      }

	public function compromisopdf()
      {
      $id = $this->input->get('id');   
      $giro = $this->input->get('giro');
      $rut1 = $this->input->get('rut1');
      $rut2 = $this->input->get('rut2');
      $fechad = $this->input->get('fecha');  
      $ciudad = $this->input->get('ciudad');
      $correo = $this->input->get('correo');           
      $fecha = $fechad;
      list($dia, $mes, $anio) = explode("/",$fecha);    
      
      if ($mes=="01"){
        $mes="Enero";
      }

      if ($mes=="02"){
        $mes="Febrero";
      }

       if ($mes=="03"){
        $mes="Marzo";
      }
       if ($mes=="04"){
        $mes="Abril";
      }
       if ($mes=="05"){
        $mes="Mayo";
      }
       if ($mes=="06"){
        $mes="Junio";
      }
       if ($mes=="07"){
        $mes="Julio";
      }
       if ($mes=="08"){
        $mes="Agosto";
      }
       if ($mes=="01"){
        $mes="Enero";
      }
       if ($mes=="09"){
        $mes="Septiembre";
      }
       if ($mes=="10"){
        $mes="Octubre";
      }
       if ($mes=="11"){
        $mes="Noviembre";
      }
      if ($mes=="12"){
        $mes="Diciembre";
      }

      $representante1 = $this->input->get('represent1');
      $representante2 = $this->input->get('represent2');
    
      if(!$representante1){
            $representante1= "";
      }

      if(!$representante2){
            $representante2= "";
      }

      if(!$correo){
            $correo= "";
      }              
               

      $query = $this->db->query('SELECT * FROM clientes WHERE id like "'.$id.'"');                  
      if($query->num_rows()>0){
            $row = $query->first_row();
            $nombre = $row->nombres;  
            $direccion = $row->direccion;  
            $rut_titular = $row->rut;
            $id_giro = $row->id_giro; 
            $id_comuna = $row->id_comuna; 
            $id_ciudad = $row->id_ciudad; 
            if(!$correo){          
            $correo = $row->mail;
            };

            $query2 = $this->db->query('SELECT * FROM comuna WHERE id like "'.$id_comuna.'"');

            if($query2->num_rows()>0){              
               $row2 = $query2->first_row();
               $comuna = $row2->nombre;
            }

            $query3 = $this->db->query('SELECT * FROM ciudad WHERE id like "'.$id_ciudadgiro.'"');

            if($query3->num_rows()>0){              
               $row3 = $query3->first_row();
               $ciudad = $row3->nombre;
            }

            if($id_giro){
            $query = $this->db->query('SELECT * FROM cod_activ_econ WHERE id like "'.$id_giro.'"');

            if($query->num_rows()>0){              
               $row = $query->first_row();
               $giro = $row->nombre;
            }else{
               $giro = "";  
            };              
            }else{
              $giro = $row->nombre;              
            };
      }
       


      $rutautoriza = $rut_titular;
      if (strlen($rutautoriza) == 8){
      $rut1z = substr($rutautoriza, -1);
      $rut2z = substr($rutautoriza, -4, 3);
      $rut3z = substr($rutautoriza, -7, 3);
      $rut4z = substr($rutautoriza, -8, 1);
    };
    if (strlen($rutautoriza) == 9){
      $rut1z = substr($rutautoriza, -1);
      $rut2z = substr($rutautoriza, -4, 3);
      $rut3z = substr($rutautoriza, -7, 3);
      $rut4z = substr($rutautoriza, -9, 2);
   
    }; 

    if($rut1){

      $rutautoriza = $rut1;
      if (strlen($rutautoriza) == 8){
      $rut1a = substr($rutautoriza, -1);
      $rut2a = substr($rutautoriza, -4, 3);
      $rut3a = substr($rutautoriza, -7, 3);
      $rut4a = substr($rutautoriza, -8, 1);
    };
    if (strlen($rutautoriza) == 9){
      $rut1a = substr($rutautoriza, -1);
      $rut2a = substr($rutautoriza, -4, 3);
      $rut3a = substr($rutautoriza, -7, 3);
      $rut4a = substr($rutautoriza, -9, 2);
   
    }; 
          
    }else{

      $rut1a = " ";
      $rut2a = " ";
      $rut3a = " ";      
      $rut4a = " "; 
          
    }   

     if($rut2){

      $rutautoriza = $rut2;
      if (strlen($rutautoriza) == 8){
      $rut1b = substr($rutautoriza, -1);
      $rut2b = substr($rutautoriza, -4, 3);
      $rut3b = substr($rutautoriza, -7, 3);
      $rut4b = substr($rutautoriza, -8, 1);
    };
    if (strlen($rutautoriza) == 9){
      $rut1b = substr($rutautoriza, -1);
      $rut2b = substr($rutautoriza, -4, 3);
      $rut3b = substr($rutautoriza, -7, 3);
      $rut4b = substr($rutautoriza, -9, 2);
   
    }; 
          
    }else{

      $rut1b = " ";
      $rut2b = " ";
      $rut3b = " ";      
      $rut4b = " "; 
          
    };

    $espacios = "  ";

                   
            
    $html = '     
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Ingreso de Animal a Feria</title>
      <link href="estilo_formulario.css" rel="stylesheet" type="text/css" />
      </head>
      <body>
      <table border="2">
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
      <tr>
      <td class="negro"  align="center" width="660"><H4> 
      ACUERDO DE PAGO EN PLAZO EXCEPCIONAL
      (LEY Nº 19.983)</H4> </td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>    
      <tr>
      <td class="negro"  align="justify" width="660"> 
      En '.$ciudad.' de Chile, a '.$dia.' '.$espacios.'de'.$espacios.' '.$mes.' '.$espacios.'de'.$espacios.' '.$anio.', S.A. Feria de Los Agricultores, sociedad chilena del giro de Feria de animales, martillero público, remates al por mayor y menor de animales en pie, Rol Único Tributario Nº90.380.000-3, acuerdos-clientes-excepcional@agricultorestalca.cl representada por don Ricardo José Pozo Luco, ingeniero comercial, cédula de identidad número 10.022.349-K, ambos domiciliados para estos efectos en Avenida Las Rastras número 948, Talca, por una parte, y por otra:
      </td>
      <tr>
      <td width="100">&nbsp;</td>
      </tr>
      <tr>
      <td class="negro"  align="left" width="660"> 
      Nombre / Razòn Social: '.$nombre.'</td>
      <tr>
      <td class="negro"  align="left" width="660"> 
      Rut: '.$rut4z.'.'.$rut3z.'.'.$rut2z.'-'.$rut1z.'</td>
       </tr>
      <tr>
      <td class="negro"  align="left" width="660"> 
      Domicilio:  '.$direccion.' Comuna: '.$comuna.' Ciudad: '.$ciudad.'</td>
      </tr>
      <tr>
      <td class="negro"  align="left" width="660"> 
      Giro: '.$giro.'</td>
       </tr>
      <tr>
      <td class="negro"  align="left" width="660"> 
      Correo Elèctrinico: '.$correo.'</td>
       </tr>   
      <tr>
       <tr>
      <td class="negro"  align="justify" width="660"> 
      En adelante cada una “Parte” y conjuntamente, las “Partes”, han convenido en celebrar el siguiente Acuerdo de Pago en Plazo Excepcional, en adelante el “Acuerdo”:
      </td>
      <tr>
      <td class="negro"  align="justify" width="660"><h4> 
      PRIMERO: Antecedentes.</h4></td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      I)    Con fecha 16 de enero de 2019, se publicó la Ley Nº 21.131, que establece el pago a treinta días de las facturas, sustituyendo el artículo 2º de la Ley Nº 19.983, que regula transferencia y otorga merito ejecutivo a copia de la factura.
       </td>
      <tr>
       <td class="negro"  align="justify" width="660"> 
      II)   El artículo 2º de la Ley Nº 19.983 autoriza a las partes a que, en casos excepcionales, puedan establecer de común acuerdo un plazo de pago de las facturas que exceda los treinta días corrido contados desde su recepción, según lo dispuesto en el inciso primero del referido artículo, debiendo constar por escrito dicho acuerdoartículo.
       </td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      III)  A la fecha, las partes tienen una relación comercial en virtud de la cual S.A. Feria de Los Agricultores vende animales vivos a '.$nombre.', a través del remate en pública subasta.
       </td>
      <tr>
       <tr>
      <td class="negro"  align="justify" width="660"><h4>
      SEGUNDO: Acuerdo de pago en plazo excepcional.</h4></td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      Por el presente instrumento, las partes acuerdan que, en lo sucesivo, las facturas emitidas por S.A. Feria de Los Agricultores a '.$nombre.' serán pagadas a en el plazo máximo de 360 días o el plazo máximo menor que se indique en la factura respectiva, en vez lugar de los 30 días contemplados por la Ley Nº 21.131.
       </td>
      <tr>
       <td class="negro"  align="justify" width="660">Las partes dejan constancia de que la ampliación excepcional del plazo de pago de las facturas tiene su fundamento en la operación propia de la industria en la que ambas partes operan, generando flujos de acuerdo a los ciclos de la producción agrícola y estando ambas partes de acuerdo en que la facturación deba efectuarse dentro de los plazos antes señalados y no en un plazo de 30 días. Asimismo, las Partes declaran su conformidad en cuanto a que el Acuerdo no es, en caso alguno, abusivo.
       </td>
      <tr>
      <td class="negro"  align="justify" width="660"><h4>
      TERCERO: Materias de las facturas sujetas al Acuerdo:</h4></td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      Las Partes acuerdan que las materias de las facturas sujetas al Acuerdo serán todas aquellas que surjan a partir de las ventas de toda clase de animales vivos, efectuadas en pública subasta, en alguno de los recintos de remate de propiedad de S.A. Feria de Los Agricultores.
      </td>
      <tr>
       <td class="negro"  align="justify" width="660"><h4>
      CUARTO: Vigencia.</h4></td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      El presente Acuerdo y las obligaciones contenidas en el permanecerán vigentes por un periodo de un año a partir de esta fecha.
       </td>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
      
      <tr>
      <td class="negro"  align="justify" width="660"> <h4>
      QUINTO: Jurisdicción.</h4></td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      Cualquier disputa respecto de la aplicación de este Acuerdo, será sometido a los tribunales ordinarios de justicia de la comuna y ciudad de Talca.
      </td>
      <tr>
      <td class="negro"  align="justify" width="660"><h4>
      SEXTO: Legislación aplicable y domicilio especial.</h4></td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      El presente Acuerdo se rige por las leyes chilenas. Para todos los efectos derivados de este instrumento, las Partes fijan su domicilio en la ciudad y comuna de Talca, sometiéndose a la competencia de sus Tribunales Ordinarios de justicia en todas aquellas materias que no fueren de competencia arbitral.
      </td>
      <tr>
       <td class="negro"  align="justify" width="660"><h4>
      SEPTIMO: Personerías.</h4></td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      La personería de don  Ricardo José Pozo Luco para actuar en representación de S.A. Feria de Los Agricultores consta en acta de fecha diecinueve de diciembre de dos mil catorce, reducida a escritura pública con fecha quince de mayo de dos mil quince ante la Notario Angelita de la Paz Hormazábal Alegría, Notario Público Titular de Talca, Rep. N°2.664-2015, inscrita a fojas mil cincuenta con el número cuatrocientos veinticuatro en el Registro de Comercio, del Conservador de Bienes Raíces de Talca, correspondiente al año dos mil quince.
      </td>
      <tr>
       <td class="negro"  align="justify" width="660"> 
      El o los apoderados(s) de '.$nombre.' declaran contar con las atribuciones y poderes suficientes para la celebración de este Acuerdo.
      </td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      El presente Acuerdo se firma en dos ejemplares de igual fecha y tenor quedando uno en poder de cada una de las Partes.
      </td>
      <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
      <tr>
      <td class="negro"  align="center" width="660"> ____________________</td>
      <tr>
      <td class="negro"  align="center" width="660">Ricardo José Pozo Luco</td>
      <tr>
      <td class="negro"  align="center" width="660">pp. S.A. Feria de Los 
      Agricultores</td> 
      <tr>
      </td>
      <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
      <tr>
      <td class="negro"  align="center" width="660">_______________________________________</td>
      </tr>
      <tr>
      <td class="negro"  align="center" width="200">Firma</td>
      </tr>
      <tr>
      <td class="negro"  align="center" width="660">'.$nombre.'</td>
      </tr>
      <tr>
       <td class="negro"  align="center" width="660">Rut : '.$rut4z.'.'.$rut3z.'.'.$rut2z.'-'.$rut1z.'</td>
      <tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>      
      <tr>
      <td width="100">&nbsp;</td>
      </tr>
      <tr>
      <td width="100">&nbsp;</td>
      </tr>     
      </tr>


      </td>
      </tr>
      </tr>
      </table>
      </body>';

     include(dirname(__FILE__)."/../libraries/mpdf60/mpdf.php");
     //$this->load->library("mpdf");

     $mpdf= new mPDF(
            '',    // mode - default ''
            '',    // format - A4, for example, default ''
            0,     // font size - default 0
            '',    // default font family
            15,    // margin_left
            15,    // margin right
            6,    // margin top
            16,    // margin bottom
            9,     // margin header
            9,     // margin footer
            'L'    // L - landscape, P - portrait
            );  

    $mpdf->WriteHTML($html);
    $mpdf->Output("CF_{$codigo}.pdf", "I");
          
          exit;
    }

    public function compromisopdf2()
      {

      $id = $this->input->get('id');   
      $giro = $this->input->get('giro');
      $rut1 = $this->input->get('rut1');
      $rut2 = $this->input->get('rut2');
      $fechad = $this->input->get('fecha');  
      $ciudad = $this->input->get('ciudad');
      $correo = $this->input->get('correo');      

      $fecha = $fechad;
      list($dia, $mes, $anio) = explode("/",$fecha);    
      //print_r($anio);
      //exit;
      
      
      if ($mes=="01"){
        $mes="Enero";
      }

      if ($mes=="02"){
        $mes="Febrero";
      }

       if ($mes=="03"){
        $mes="Marzo";
      }
       if ($mes=="04"){
        $mes="Abril";
      }
       if ($mes=="05"){
        $mes="Mayo";
      }
       if ($mes=="06"){
        $mes="Junio";
      }
       if ($mes=="07"){
        $mes="Julio";
      }
       if ($mes=="08"){
        $mes="Agosto";
      }
       if ($mes=="01"){
        $mes="Enero";
      }
       if ($mes=="09"){
        $mes="Septiembre";
      }
       if ($mes=="10"){
        $mes="Octubre";
      }
       if ($mes=="11"){
        $mes="Noviembre";
      }
      if ($mes=="12"){
        $mes="Diciembre";
      }

          

      $representante1 = $this->input->get('represent1');
      $representante2 = $this->input->get('represent2');
      $correo = $this->input->get('correo');

      
      if(!$representante1){
            $representante1= "";
      }

      if(!$representante2){
            $representante2= "";
      }          
               

      $query = $this->db->query('SELECT * FROM clientes WHERE id like "'.$id.'"');                  
      if($query->num_rows()>0){
            $row = $query->first_row();
            $nombre = $row->nombres;  
            $direccion = $row->direccion;  
            $rut_titular = $row->rut;
            $id_giro = $row->id_giro;
            $id_comuna = $row->id_comuna; 
            $id_ciudad = $row->id_ciudad;
            if(!$correo){          
            $correo = $row->mail;
            };

             $query2 = $this->db->query('SELECT * FROM comuna WHERE id like "'.$id_comuna.'"');

            if($query2->num_rows()>0){              
               $row2 = $query2->first_row();
               $comuna = $row2->nombre;
            }

            $query3 = $this->db->query('SELECT * FROM ciudad WHERE id like "'.$id_ciudad.'"');

            if($query3->num_rows()>0){              
               $row3 = $query3->first_row();
               $ciudad = $row3->nombre;
            }

            if($id_giro){
            $query = $this->db->query('SELECT * FROM cod_activ_econ WHERE id like "'.$id_giro.'"');

            if($query->num_rows()>0){              
               $row = $query->first_row();
               $giro = $row->nombre;
            }else{
               $giro = "";  
            };              
            }else{
              $giro = $row->nombre;              
            };
      }
       


      $rutautoriza = $rut_titular;
      if (strlen($rutautoriza) == 8){
      $rut1z = substr($rutautoriza, -1);
      $rut2z = substr($rutautoriza, -4, 3);
      $rut3z = substr($rutautoriza, -7, 3);
      $rut4z = substr($rutautoriza, -8, 1);
    };
    if (strlen($rutautoriza) == 9){
      $rut1z = substr($rutautoriza, -1);
      $rut2z = substr($rutautoriza, -4, 3);
      $rut3z = substr($rutautoriza, -7, 3);
      $rut4z = substr($rutautoriza, -9, 2);
   
    }; 

    if($rut1){

      $rutautoriza = $rut1;
      if (strlen($rutautoriza) == 8){
      $rut1a = substr($rutautoriza, -1);
      $rut2a = substr($rutautoriza, -4, 3);
      $rut3a = substr($rutautoriza, -7, 3);
      $rut4a = substr($rutautoriza, -8, 1);
    };
    if (strlen($rutautoriza) == 9){
      $rut1a = substr($rutautoriza, -1);
      $rut2a = substr($rutautoriza, -4, 3);
      $rut3a = substr($rutautoriza, -7, 3);
      $rut4a = substr($rutautoriza, -9, 2);
   
    }; 
          
    }else{

      $rut1a = " ";
      $rut2a = " ";
      $rut3a = " ";      
      $rut4a = " "; 
          
    }   

     if($rut2){

      $rutautoriza = $rut2;
      if (strlen($rutautoriza) == 8){
      $rut1b = substr($rutautoriza, -1);
      $rut2b = substr($rutautoriza, -4, 3);
      $rut3b = substr($rutautoriza, -7, 3);
      $rut4b = substr($rutautoriza, -8, 1);
    };
    if (strlen($rutautoriza) == 9){
      $rut1b = substr($rutautoriza, -1);
      $rut2b = substr($rutautoriza, -4, 3);
      $rut3b = substr($rutautoriza, -7, 3);
      $rut4b = substr($rutautoriza, -9, 2);
   
    }; 
          
    }else{

      $rut1b = " ";
      $rut2b = " ";
      $rut3b = " ";      
      $rut4b = " "; 
          
    };    
    
    $espacios = "  ";               
            
    $html = '     
      <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
      <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Ingreso de Animal a Feria</title>
      <link href="estilo_formulario.css" rel="stylesheet" type="text/css" />
      </head>
      <body>
      <table border="2">
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
      <tr>
      <td class="negro"  align="center" width="660"><H4> 
      ACUERDO DE PAGO EN PLAZO EXCEPCIONAL
      (LEY Nº 19.983)</H4> </td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>    
      <tr>
      <td class="negro"  align="justify" width="660"> 
      En '.$ciudad.' de Chile, a '.$dia.' '.$espacios.'de'.$espacios.' '.$mes.' '.$espacios.'de'.$espacios.' '.$anio.', S.A. Feria de Los Agricultores, sociedad chilena del giro de Feria de animales, martillero público, remates al por mayor y menor de animales en pie, Rol Único Tributario Nº90.380.000-3, acuerdos-clientes-excepcional@agricultorestalca.cl representada por don Ricardo José Pozo Luco, ingeniero comercial, cédula de identidad número 10.022.349-K, ambos domiciliados para estos efectos en Avenida Las Rastras número 948, Talca, por una parte, y por otra:
      </td>
      <tr>
      <td width="100">&nbsp;</td>
      </tr>
      <tr>
      <td class="negro"  align="left" width="660"> 
      Nombre / Razòn Social: '.$nombre.'</td>
      <tr>
      <td class="negro"  align="left" width="660"> 
      Rut: '.$rut4z.'.'.$rut3z.'.'.$rut2z.'-'.$rut1z.'</td>
       </tr>
      <tr>
      <td class="negro"  align="left" width="660"> 
      Domicilio:  '.$direccion.' Comuna: '.$comuna.' Ciudad: '.$ciudad.'</td>
      </tr>
      <tr>
      <td class="negro"  align="left" width="660"> 
      Giro: '.$giro.'</td>
       </tr>
      <tr>
      <td class="negro"  align="left" width="660"> 
      Correo Elèctronico: '.$correo.'</td>
       </tr>
      <tr>
      <td class="negro"  align="left" width="660"> 
      REPRESENTANTE LEGAL 1:  '.$representante1.'</td>
       </tr>
      <tr>
      <td class="negro"  align="left" width="660">      
      RUT:  '.$rut4a.'.'.$rut3a.'.'.$rut2a.'-'.$rut1a.'</td>
       </tr>
      <tr>
      <td class="negro"  align="left" width="660"> 
      REPRESENTANTE LEGAL 2: '.$representante2.'</td> 
       </tr>
      <tr> 
      <td class="negro"  align="left" width="660">       
      RUT: '.$rut4b.'.'.$rut3b.'.'.$rut2b.'-'.$rut1b.'</td>  
       </tr>
      <tr>
       <tr>
      <td class="negro"  align="justify" width="660"> 
      En adelante cada una “Parte” y conjuntamente, las “Partes”, han convenido en celebrar el siguiente Acuerdo de Pago en Plazo Excepcional, en adelante el “Acuerdo”:
      </td>
      <tr>
      <td class="negro"  align="justify" width="660"><h4>PRIMERO: Antecedentes.</h4>
      </td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      I)    Con fecha 16 de enero de 2019, se publicó la Ley Nº 21.131, que establece el pago a treinta días de las facturas, sustituyendo el artículo 2º de la Ley Nº 19.983, que regula transferencia y otorga merito ejecutivo a copia de la factura.
       </td>
      <tr>
       <td class="negro"  align="justify" width="660"> 
      II)   El artículo 2º de la Ley Nº 19.983 autoriza a las partes a que, en casos excepcionales, puedan establecer de común acuerdo un plazo de pago de las facturas que exceda los treinta días corrido contados desde su recepción, según lo dispuesto en el inciso primero del referido artículo, debiendo constar por escrito dicho acuerdoartículo.
       </td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      III)  A la fecha, las partes tienen una relación comercial en virtud de la cual S.A. Feria de Los Agricultores vende animales vivos a '.$nombre.', a través del remate en pública subasta.
       </td>
      <tr>
       <tr>
      <td class="negro"  align="justify" width="660"><h4>SEGUNDO: Acuerdo de pago en plazo excepcional.</h4></td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      Por el presente instrumento, las partes acuerdan que, en lo sucesivo, las facturas emitidas por S.A. Feria de Los Agricultores a '.$nombre.' serán pagadas a en el plazo máximo de 360 días o el plazo máximo menor que se indique en la factura respectiva, en vez lugar de los 30 días contemplados por la Ley Nº 21.131.
       </td>
      <tr>
       <td class="negro"  align="justify" width="660">Las partes dejan constancia de que la ampliación excepcional del plazo de pago de las facturas tiene su fundamento en la operación propia de la industria en la que ambas partes operan, generando flujos de acuerdo a los ciclos de la producción agrícola y estando ambas partes de acuerdo en que la facturación deba efectuarse dentro de los plazos antes señalados y no en un plazo de 30 días. Asimismo, las Partes declaran su conformidad en cuanto a que el Acuerdo no es, en caso alguno, abusivo.
       </td>
      <tr>
      <td class="negro"  align="justify" width="660"><h4>TERCERO: Materias de las facturas sujetas al Acuerdo:</h4></td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      Las Partes acuerdan que las materias de las facturas sujetas al Acuerdo serán todas aquellas que surjan a partir de las ventas de toda clase de animales vivos, efectuadas en pública subasta, en alguno de los recintos de remate de propiedad de S.A. Feria de Los Agricultores.
       </td>
        <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
      <tr>
       <td class="negro"  align="justify" width="660"><h4>CUARTO: Vigencia.</h4></td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      El presente Acuerdo y las obligaciones contenidas en el permanecerán vigentes por un periodo de un año a partir de esta fecha.
       </td>
      <tr>
      <td class="negro"  align="justify" width="660"><h4>QUINTO: Jurisdicción.</h4>
      </td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      Cualquier disputa respecto de la aplicación de este Acuerdo, será sometido a los tribunales ordinarios de justicia de la comuna y ciudad de Talca.
      </td>
      <tr>
      <td class="negro"  align="justify" width="660"><h4>SEXTO: Legislación aplicable y domicilio especial.</h4></td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      El presente Acuerdo se rige por las leyes chilenas. Para todos los efectos derivados de este instrumento, las Partes fijan su domicilio en la ciudad y comuna de Talca, sometiéndose a la competencia de sus Tribunales Ordinarios de justicia en todas aquellas materias que no fueren de competencia arbitral.
      </td>
      <tr>
       <td class="negro"  align="justify" width="660"><h4>SEPTIMO: Personerías.</h4>
      </td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      La personería de don  Ricardo José Pozo Luco para actuar en representación de S.A. Feria de Los Agricultores consta en acta de fecha diecinueve de diciembre de dos mil catorce, reducida a escritura pública con fecha quince de mayo de dos mil quince ante la Notario Angelita de la Paz Hormazábal Alegría, Notario Público Titular de Talca, Rep. N°2.664-2015, inscrita a fojas mil cincuenta con el número cuatrocientos veinticuatro en el Registro de Comercio, del Conservador de Bienes Raíces de Talca, correspondiente al año dos mil quince.
      </td>
      <tr>
       <td class="negro"  align="justify" width="660"> 
      El o los apoderados(s) de la sociedad '.$nombre.' declaran contar con las atribuciones y poderes suficientes para la celebración de este Acuerdo.
      </td>
      <tr>
      <td class="negro"  align="justify" width="660"> 
      El presente Acuerdo se firma en dos ejemplares de igual fecha y tenor quedando uno en poder de cada una de las Partes.
      </td>
      <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
      <tr>
      <td class="negro"  align="center" width="660"> ____________________</td>
      <tr>
      <td class="negro"  align="center" width="660">Ricardo José Pozo Luco</td>
      <tr>
      <td class="negro"  align="center" width="660">pp. S.A. Feria de Los 
      Agricultores</td> 
      <tr>
      </td>
      <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
      <tr>
      <td class="negro"  align="left" width="660">Firma _____________________________</td>
      </tr>
      <tr>
      <td width="100">&nbsp;</td>
      </tr>
     <tr>
      <td class="negro"  align="left" width="660">'.$representante1.'</td>
       </tr>
       <tr>
       <td class="negro"  align="left" width="660">Rut '.$rut4a.'.'.$rut3a.'.'.$rut2a.'-'.$rut1a.'</td>
      <tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
      <tr>
      <td class="negro"  align="left" width="660">'.$representante2.'</td>
       </tr>
       <tr>
       <td class="negro"  align="left" width="660">Firma ___________________________</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
      <td width="100">&nbsp;</td>
      </tr>
       <tr>
       <td class="negro"  align="left" width="660">Rut '.$rut4b.'.'.$rut3b.'.'.$rut2b.'-'.$rut1b.'</td>
         

      </tr>
      </tr>


      </td>
      </tr>
      </tr>
      </table>
      </body>';

     include(dirname(__FILE__)."/../libraries/mpdf60/mpdf.php");
     //$this->load->library("mpdf");

     $mpdf= new mPDF(
            '',    // mode - default ''
            '',    // format - A4, for example, default ''
            0,     // font size - default 0
            '',    // default font family
            15,    // margin_left
            15,    // margin right
            6,    // margin top
            16,    // margin bottom
            9,     // margin header
            9,     // margin footer
            'L'    // L - landscape, P - portrait
            );  

    $mpdf->WriteHTML($html);
    $mpdf->Output("CF_{$codigo}.pdf", "I");
          
          exit;
    }
}
