Ext.define('Infosys_web.controller.Clientes', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['Clientes',
            'Cod_activ',
            'Sucursales_clientes',
            'clientes.Clientes',
            'clientes.Selector',
            'clientes.Selector1',
            'clientes.Selector2',
            'clientes.Selector6',
            'clientes.Selector7',
            'clientes.Selector4',
            'Contacto_clientes',
            'clientes.Activo',
            'clientes.Credito',
            'clientes.Tipo_cliente',
             ],

    models: ['Cliente',
            'Sucursales_clientes',
            'Contacto_clientes'],

    views: ['clientes.Principal',
            'clientes.BusquedaClientes',
            'clientes.Ingresar',
            'clientes.Desplegar',
            'clientes.Validar',
            'clientes.SubirConvenio',
            'clientes.Acuerdo',
            'clientes.Acuerdo2',
            'clientes.Eliminar',
            'clientes.Desplieguesucursales',
            'clientes.IngresarSucursales',
            'clientes.Desplieguecontactos',
            'clientes.IngresarContactos',
            'clientes.Autoriza',
            'clientes.Exportar'],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
    
        ref: 'clientesprincipal',
        selector: 'clientesprincipal'
    },{
        ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
    
        ref: 'clientesingresar',
        selector: 'clientesingresar'
    },{
    
        ref: 'busquedaclientes',
        selector: 'busquedaclientes'
    },{
    
        ref: 'clientesdesplegar',
        selector: 'clientesdesplegar'
    },{
        ref: 'clientesvalidar',
        selector: 'clientesvalidar'
    },{
        ref: 'desplegarsucursales',
        selector: 'desplegarsucursales'
    },{
        ref: 'sucursalesingresarclientes',
        selector: 'sucursalesingresarclientes'
    },{
        ref: 'desplegarcontactosclientes',
        selector: 'desplegarcontactosclientes'
    },{
        ref: 'contactoingresarclientes',
        selector: 'contactoingresarclientes'
    },{
        ref: 'eliminarclientes',
        selector: 'eliminarclientes'
    },{
        ref: 'autorizacionclientes',
        selector: 'autorizacionclientes'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{
        ref: 'exportarseguro',
        selector: 'exportarseguro'
    },{
        ref: 'subirConvenio',
        selector: 'subirConvenio'
    },{
        ref: 'acuerdodepagoclientes',
        selector: 'acuerdodepagoclientes'
    },{
        ref: 'acuerdodepagoconcretado',
        selector: 'acuerdodepagoconcretado'
    }




    ],
    //init es lo primero que se ejecuta en el controller
    //especia de constructor
    init: function() {
    	//el <<control>> es el puente entre la vista y funciones internas
    	//del controller
        this.control({

            'clientesprincipal button[action=validar]': {
                click: this.validar
            },
            'clientesvalidar button[action=validarut]': {
                click: this.validarut
            },
            'desplegarcontactosclientes button[action=agregacontactoclientes]': {
                click: this.agregacontactoclientes
            },
            'clientesprincipal button[action=despliegacontactosclientes]': {
                click: this.despliegacontactosclientes
            }, 

            'clientesprincipal button[action=buscarclientes]': {
                click: this.buscarclientes
            },
            
            'clientesprincipal button[action=exportarexcelclientes]': {
                click: this.exportarexcelclientes
            },
            'clientesprincipal button[action=filtroClientes]': {
                click: this.filtroClientes
            },
            'clientesingresar button[action=grabarclientes]': {
                click: this.grabarclientes
            },
            
           'clientesprincipal button[action=agregarclientes]': {
                click: this.agregarclientes
            },
            'clientesprincipal button[action=editarclientes]': {
                click: this.editarclientes
            },
            'clientesprincipal button[action=cerrarclientes]': {
                click: this.cerrarclientes
            },
            'clientesprincipal button[action=despliegasucursales]': {
                click: this.despliegasucursales
            },  
            'clientesdesplegar button[action=desplegarclientes]': {
                click: this.desplegarclientes
            },  
            'sucursalesingresarclientes button[action=grabarsucursales]': {
                click: this.grabarsucursales
            },  
            'desplegarsucursales button[action=agregaSucursalesclientes]': {
                click: this.agregaSucursalesclientes
            },
            'contactoingresarclientes button[action=grabarcontactosclientes]': {
                click: this.grabarcontactosclientes
            },
            ' eliminarclientes button[action=salirclientes]': {
                click: this.salirclientes
            },
            ' eliminarclientes button[action=eliminar]': {
                click: this.eliminar
            },
            'clientesprincipal button[action=eliminarclientes]': {
                click: this.eliminarclientes
            },
            'clientesdesplegar #tipoEstadoId': {
                select: this.tipoestado
            },
            'autorizacionclientes button[action=autorizaclientes]': {
                click: this.autorizaclientes
            },
            'exportarseguro button[action=exportarformularioseguros]': {
                click: this.exportarformularioseguros
            },
            'clientesprincipal button[action=exportarexcelseguros]': {
                click: this.exportarexcelseguros
            },
            'clientesdesplegar button[action=subirconvenio]': {
                click: this.subirconvenio
            },
            'subirConvenio button[action=Subironvenio2]': {
                click: this.Subironvenio2
            },
            'clientesprincipal button[action=acuerdodepago]': {
                click: this.acuerdodepago
            },
            'acuerdodepagoclientes button[action=generaacuerdopdf]': {
                click: this.generaacuerdopdf
            },  
            'acuerdodepagoconcretado button[action=generarpdf]': {
                click: this.generarpdf
            },  
        });
    },

    generarpdf : function() {

        var view = this.getAcuerdodepagoconcretado();
        var tiposelec = view.down('#tipoSeleccionAId').getValue();
        var id = view.down('#id_cliente').getValue();
        if (!tiposelec){
             Ext.Msg.alert('Debe Selecionar el Tipo de Acuerdo');
            return;
            
        };
        if (tiposelec==1){

            Ext.Ajax.request({
                url: preurl + 'formularioconvenio/valida2',
                params: {
                id: id,
                tipo: tiposelec
                },
                success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == false) {
                    view.close();
                    Ext.Msg.alert('Documento no Disponible');
                    return;
                }else{
                  window.open(preurl + 'formularioconvenio/compromisopdf1/?id='+id);  
           
                }
                }
            });

            
        };
        if (tiposelec==2){

            Ext.Ajax.request({
                url: preurl + 'formularioconvenio/valida2',
                params: {
                    id: id,
                    tipo: tiposelec
                },
                success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == false) {
                    view.close();
                    Ext.Msg.alert('Documento no Disponible');
                    return;
                }else{
                   window.open(preurl + 'formularioconvenio/compromisopdf4/?id='+id);  
            
                }
                }
            });           
        };
        if (tiposelec==3){

              Ext.Ajax.request({
                url: preurl + 'formularioconvenio/valida2',
                params: {

                id: id,
                tipo: tiposelec

                },
                success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == false) {
                    view.close();
                    Ext.Msg.alert('Documento no Disponible');
                    return;
                }else{
                   window.open(preurl + 'formularioconvenio/compromisopdf3/?id='+id);  
            
                }
                }
            });
        };        

        view.close();
        
    },

    generaacuerdopdf : function() {

        var view = this.getAcuerdodepagoclientes();
        var giro = view.down('#giroId').getValue();
        var rut1 = view.down('#rutId').getValue();
        var ciudad = view.down('#tipoSeleccionId').getValue();
        var fecha = view.down('#fechaId').getSubmitValue();  
        var represent1 = view.down('#prepresentaId').getValue();
        var rut2 = view.down('#rut2Id').getValue();
        var represent2 = view.down('#representante2Id').getValue();
        var id = view.down('#idId').getValue();
        var correo = view.down('#correoId').getValue();
        var opcion = view.down('#tipoSeleccion2Id').getValue();
        if (!opcion){
             Ext.Msg.alert('Debe Selecionar Tipo de Acuerdo');
            return;   
            
        }
        if (!ciudad){
             Ext.Msg.alert('Debe Selecionar Ciudad Donde se Firma el Acuerdo');
            return;   
            
        }
        if (opcion=="Persona Natural"){
             window.open(preurl + 'formularioconvenio/compromisopdf/?giro='+giro+'&rut1='+rut1+'&rut2='+rut2+'&id='+id+'&represent1='+represent1+'&represent2='+represent2+'&correo='+correo+'&fecha='+fecha+'&ciudad='+ciudad);
        }else{
             if (!represent1){
                 Ext.Msg.alert('Debe Selecionar Al menos un representante Legal');
              return;}
              if (!rut1){
                 Ext.Msg.alert('Debe Ingresar Rut De Representante Legal');
              return;}

            window.open(preurl + 'formularioconvenio/compromisopdf2/?giro='+giro+'&rut1='+rut1+'&rut2='+rut2+'&id='+id+'&represent1='+represent1+'&represent2='+represent2+'&correo='+correo+'&fecha='+fecha+'&ciudad='+ciudad);
        };
        view.close();
    },


    acuerdodepago: function() {

        var viewedit = this.getClientesprincipal();
        var grid =this.getClientesprincipal()     
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var acuerdo = (row.get('tipo_acuerdo'));
            var id = (row.get('id'));
            console.log(acuerdo);
            if (acuerdo==0){
                var edit = Ext.create('Infosys_web.view.clientes.Acuerdo').show();               
                var nombre = (row.get('nombre'));
                var correo = (row.get('mail'));
                edit.down('#idId').setValue(id); 
                edit.down('#nombreId').setValue(nombre);
                edit.down('#correoId').setValue(correo);
            };
            if (acuerdo==1){
               var edit = Ext.create('Infosys_web.view.clientes.Acuerdo2').show();
               edit.down('#id_cliente').setValue(id);
            }
            if (acuerdo==2){
                   var edit = Ext.create('Infosys_web.view.clientes.Acuerdo2').show();
                   edit.down('#id_cliente').setValue(id);
            }                 
                   
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        };

       
       
    },

    subirconvenio : function() {        

        var view = this.getClientesdesplegar();
        var id = view.down('#id_cliente').getValue();
        //var usuario = view.down('#iduSUARIO').getValue();
        view.close();
        var edit = Ext.create('Infosys_web.view.clientes.SubirConvenio').show();
        edit.down('#id_cliente').setValue(id);
        console.log("Llegamos")
        //edit.down('#iduSUARIO').setValue(usuario);

    },

    Subironvenio2 : function(){

        var view = this.getSubirConvenio();
        console.log("Llegamos2")
        var tiposelec = view.down('#tipoSeleccionAId').getValue();
        var archivo = view.down('#archivoId').getValue();
        var fecha = view.down('#fechasubidaId').getSubmitValue();  
        var id = view.down('#id_cliente').getValue();
        var form = view.down('form').getForm();
        if(form.isValid()){
            form.submit({
                url:preurl + 'formularioconvenio/rescatar',
                waitMsg: 'Cargando Archivo...',                    
                success: function(success) {
                   Ext.Msg.alert('Atención', 'Subio Correctamente');
                   view.close();                        
                }
            });
        }        
    },


     exportarexcelseguros: function(){

        //var viewnew =this.getTipomovimientoinventarioprincipal()       
        Ext.create('Infosys_web.view.clientes.Exportar').show();
    
     },  

     exportarformularioseguros: function(){

        var jsonCol = new Array()
        var i = 0;
        var grid =this.getClientesprincipal()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getExportarseguro()
        var viewnew =this.getClientesprincipal()
        var fecha = view.down('#fechaId').getSubmitValue();        
        var fecha2 = view.down('#fecha2Id').getSubmitValue();
                
        if (fecha > fecha2) {
        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };   
        
        window.open(preurl + 'adminServicesExcel/exportarExcelseguros?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2);
        view.close();    
  
 
    },

    autorizaclientes: function(){

       var view = this.getClientesdesplegar()
       var viewn = this.getTopmenus();
       var idnue = viewn.down('#IdUsuario').getValue();
       var opcion = view.down('#estadocId').getValue();
       var estado = view.down('#tipoEstadoId').getValue();
       var rut = view.down('#rutId').getValue();
       var nombre = view.down('#nombre_id').getValue();
       var clave = this.getAutorizacionclientes()
       
       var usua = clave.down('#enterId').getValue();
       var observacion = clave.down('#observaId').getValue();       
       if(!observacion){            
            Ext.Msg.alert('Debe Agregar Observaciones');
            return;            
       }; 
       Ext.Ajax.request({
            url: preurl + 'clientes/autoriza',
            params: {
                usua: usua,
                observacion: observacion,
                idusuario: idnue,
                tipo: 1                
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == false) {
                    clave.close();
                    view.down('#tipoEstadoId').setValue(opcion);
                    Ext.Msg.alert('Modificacion no autorizada');
                    return;                                

                }else{
                    clave.close();
                    view.down('#estadocId').setValue(estado);                    
                }
        }
        });       
        
    },

    tipoestado: function(){
       var view = this.getClientesdesplegar()
       var rut = view.down('#rutId').getValue();
       var nombre = view.down('#nombre_id').getValue();
       var opcion = view.down('#tipoEstadoId').getValue();

        var estado = view.down('#tipoEstadoId');
        var stCombo = estado.getStore();
        var record = stCombo.findRecord('id', estado.getValue()).data;
        var nomestado = (record.nombre);

       var observacion = nombre + "Rut : " + rut + "   " + nomestado;
       var view = Ext.create('Infosys_web.view.clientes.Autoriza').show();
       var view = this.getAutorizacionclientes();
       view.down('#observaId').setValue(observacion);
       view.down('#enterId').focus();
    },

    eliminarclientes: function(){

        var view = this.getClientesprincipal()
       
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit =   Ext.create('Infosys_web.view.clientes.Eliminar').show();
            edit.down('#idclienteID').setValue(row.data.id);
           
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
        
    },

    salirclientes: function(){

       var view = this.getEliminarclientes()
       view.close();

    },

    eliminar: function(){

        var view = this.getEliminarclientes()
        var idcliente = view.down('#idclienteID').getValue()
        var st = this.getClientesStore();


        Ext.Ajax.request({
            url: preurl + 'clientes/elimina',
            params: {

                idcliente: idcliente
                
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {
                    view.close();
                    st.load(); 
                    Ext.Msg.alert('Datos Eliminados Exitosamente');
                    return; 
                                   

                 }else{

                    view.close();
                    st.load();
                    Ext.Msg.alert('Datos No Eliminados Cliente con Movimientos');
                    return;
                     
                 };
        }
        });

        view.close();
        st.load();            
    },

    grabarcontactosclientes : function(){

        var viewIngresa = this.getContactoingresarclientes();
        var idcliente = viewIngresa.down('#id_clienteID').getValue();
        var email = viewIngresa.down('#emailId').getValue();
        var nombre = viewIngresa.down('#nombreId').getValue();
        var fono = viewIngresa.down('#fonoId').getValue();

        Ext.Ajax.request({
            url: preurl + 'contacto_clientes/save',
            params: {
                idcliente: idcliente,
                email: email,
                nombre: nombre,
                fono: fono
            },
            success: function(response){
                var text = response.responseText;
                Ext.Msg.alert('Informacion', 'Creada Exitosamente.');
                viewIngresa.close();
               
            }
        });

        var edit = this.getDesplegarcontactosclientes();
        edit.close();       
    
    },

    agregacontactoclientes : function(){

        var edit = Ext.create('Infosys_web.view.clientes.IngresarContactos').show();
        edit.close()
        var view = this.getDesplegarcontactosclientes();
        var idCliente = view.down('#id_clienteID').getValue();
        var viewedit = Ext.create('Infosys_web.view.clientes.IngresarContactos').show();
        viewedit.down("#id_clienteID").setValue(idCliente);

    },


    despliegacontactosclientes: function(){
        
        var view = this.getClientesprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit = Ext.create('Infosys_web.view.clientes.Desplieguecontactos').show();
            var nombre = (row.get('id'));
            var razon = (row.get('nombres'));
            edit.down("#id_clienteID").setValue(nombre);
            edit.down("#nombreId").setValue(razon);
            var st = this.getContacto_clientesStore();
            st.proxy.extraParams = {nombre : nombre};
            st.load();          
                   
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    agregaSucursalesclientes : function(){

        var edit = Ext.create('Infosys_web.view.clientes.IngresarSucursales').show();
        edit.close()
        var view = this.getDesplegarsucursales();
        var idCliente = view.down('#id_clienteID').getValue();
        var viewedit = Ext.create('Infosys_web.view.clientes.IngresarSucursales').show();
        viewedit.down("#id_clienteID").setValue(idCliente);

    },

    grabarsucursales : function(){

        var viewIngresa = this.getSucursalesingresarclientes();
        var idcliente = viewIngresa.down('#id_clienteID').getValue();
        var direccion = viewIngresa.down('#direccionId').getValue();
        var ciudad = viewIngresa.down('#tipoCiudadId').getValue();
        var comuna = viewIngresa.down('#tipoComunaId').getValue();
        var email = viewIngresa.down('#emailId').getValue();
        var contacto = viewIngresa.down('#contactoId').getValue();
        var fono = viewIngresa.down('#fonoId').getValue();

        Ext.Ajax.request({
            url: preurl + 'sucursales_clientes/save',
            params: {
                idcliente: idcliente,
                direccion: direccion,
                ciudad: ciudad,
                comuna: comuna,
                email: email,
                contacto: contacto,
                fono: fono
            },
            success: function(response){
                var text = response.responseText;
                Ext.Msg.alert('Informacion', 'Creada Exitosamente.');
                viewIngresa.close();
               
            }
        });

        var edit = this.getDesplegarsucursales();
        edit.close();

       
    
    },
    
    despliegasucursales: function(){
        
        var view = this.getClientesprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit = Ext.create('Infosys_web.view.clientes.Desplieguesucursales').show();
            var nombre = (row.get('id'));
            var razon = (row.get('nombres'));
            edit.down("#id_clienteID").setValue(nombre);
            edit.down("#nombreId").setValue(razon);
            var st = this.getSucursales_clientesStore();
            st.proxy.extraParams = {nombre : nombre};
            st.load();
           
                   
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    validar: function(){
        
    var view = Ext.create('Infosys_web.view.clientes.Validar').show();
    view.down("#rutId").focus();


    },

    validarut: function(){

        var view =this.getClientesvalidar();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

        if(numero == 9){
                var1 = rut.substr(0,2);
                var2 = rut.substr(2,3);
                var3 = rut.substr(5,3);
                var4 = rut.substr(8,1);
                rutdes = var1 + "." +var2 + "." + var3 + "-" + var4;
                
        }

        if(numero == 8){
                var1 = rut.substr(0,1);
                var2 = rut.substr(1,3);
                var3 = rut.substr(4,3);
                var4 = rut.substr(7,1);
                rutdes = var1 + "." +var2 + "." + var3 + "-" + var4 ;
                
        }
       
        if(numero>9){            
            Ext.Msg.alert('Rut Erroneo Ingrese Sin Puntos');
            return;            
        }else{
            if(numero>13){
            Ext.Msg.alert('Rut Erroneo Ingrese Sin Puntos');
            return;   
            }
        }
        
        
        Ext.Ajax.request({
            url: preurl + 'clientes/validaRut?valida='+rut,
            params: {
                id: 1
            },
            
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);

                if (resp.success == true) {                   
                    
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        var edit = Ext.create('Infosys_web.view.clientes.Desplegar').show();
                        edit.down("#id_cliente").setValue(cliente.id)
                        edit.down("#nombre_id").setValue(cliente.nombres)
                        edit.down("#tipoCiudadId").setValue(cliente.id_ciudad)
                        edit.down("#tipoComunaId").setValue(cliente.id_comuna)
                        edit.down("#tipoVendedorId").setValue(cliente.id_vendedor)
                        edit.down("#fonoId").setValue(cliente.fono)
                        edit.down("#giroId").setValue(cliente.id_giro)
                        edit.down("#descuentoId").setValue(cliente.descuento)
                        edit.down("#direccionId").setValue(cliente.direccion)
                        edit.down("#e_mailId").setValue(cliente.e_mail)
                        edit.down("#fecha_incripcionId").setValue(cliente.fecha_incripcion)
                        edit.down("#fecha_ult_actualizId").setValue(cliente.fecha_ult_actualiz)
                        edit.down("#tipopagoId").setValue(cliente.id_pago)
                        edit.down("#tipoEstadoId").setValue(cliente.estado)
                        edit.down("#estadocId").setValue(cliente.estado)
                        edit.down("#tipoClienteId").setValue(cliente.tipo)
                        edit.down("#rutId").setValue(rutdes)
                        edit.down("#disponibleId").setValue(cliente.cupo_disponible)
                        edit.down("#impuestoId").setValue(cliente.imp_adicional)
                        edit.down("#tipocredId").setValue(cliente.id_credito)
                        edit.down("#ufaprobId").setValue(cliente.uf_cred)
                        edit.down("#credutilId").setValue(cliente.cred_util)
                    }else{
                        console.log("llegamos")
                        var edit = Ext.create('Infosys_web.view.clientes.Ingresar').show();
                        edit.down("#rutId").setValue(rut)
                    }
                    
                }else{
                      Ext.Msg.alert('Informacion', 'Rut Incorrecto');
                      return false
                }

                view.close()
            }

        });       
      
    },

    exportarexcelclientes: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getClientesprincipal()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })     
                         
        window.open(preurl + 'adminServicesExcel/exportarExcelClientes?cols='+Ext.JSON.encode(jsonCol));
 
    },

    buscarclientes: function(){
        
        var view = this.getClientesprincipal()
        var st = this.getClientesStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
        st.load();
    },

    
    grabarclientes: function(){
        var view = this.getClientesingresar();
        var rut = view.down('#rutId').getValue();
        var nombre = view.down('#nombre_id').getValue();
        var idcliente = view.down('#id_cliente').getValue();
        var direccion = view.down('#direccionId').getValue();
        var ciudad = view.down('#tipoCiudadId').getValue();
        var comuna = view.down('#tipoComunaId').getValue();
        var giro = view.down('#giroId').getValue();
        var fono = view.down('#fonoId').getValue();
        var mail = view.down('#e_mailId').getValue();
        var vendedor = view.down('#tipoVendedorId').getValue();
        var descuento = view.down('#descuentoId').getValue();
        var tipopago = view.down('#tipopagoId').getValue();
        var disponible = view.down('#disponibleId').getValue();
        var impuesto = view.down('#impuestoId').getValue();
        var fechaincorporacion = view.down('#fecha_incripcionId').getValue();
        var fechaactualiza = view.down('#fecha_ult_actualizId').getValue();
        var estado = view.down('#tipoEstadoId').getValue();
        var tipocliente = view.down('#tipoClienteId').getValue();
        var id_credito = view.down('#tipocredId').getValue();
        var ufcredito = view.down('#ufaprobId').getValue();
        var tcliente = view.down('#tipoclientId').getValue();
        var st = this.getClientesStore();

         Ext.Ajax.request({
            url: preurl + 'clientes/save',
            params: {
                rut: rut,
                nombre: nombre,
                idcliente: idcliente,
                direccion: direccion,
                ciudad: ciudad,
                comuna: comuna,
                giro : giro,
                fono : fono,
                mail : mail,
                tcliente: tcliente,
                vendedor : vendedor,
                descuento: descuento,
                tipopago: tipopago,
                disponible: disponible,
                impuesto: impuesto,
                fechaincorporacion : fechaincorporacion,
                fechaactualiza : fechaactualiza,
                estado : estado,
                tipocliente : tipocliente,
                id_credito: id_credito,
                ufcredito: ufcredito
            },
             success: function(response){
                view.close();
                st.load();

            }
           
        });
    },

    desplegarclientes: function(){

        var view = this.getClientesdesplegar();
        var rut = view.down('#rutId').getValue();
        var nombre = view.down('#nombre_id').getValue();
        var idcliente = view.down('#id_cliente').getValue();
        var direccion = view.down('#direccionId').getValue();
        var ciudad = view.down('#tipoCiudadId').getValue();
        var comuna = view.down('#tipoComunaId').getValue();
        var giro = view.down('#giroId').getValue();
        var fono = view.down('#fonoId').getValue();
        var mail = view.down('#e_mailId').getValue();
        var vendedor = view.down('#tipoVendedorId').getValue();
        var descuento = view.down('#descuentoId').getValue();
        var tipopago = view.down('#tipopagoId').getValue();
        var disponible = view.down('#disponibleId').getValue();
        var impuesto = view.down('#impuestoId').getValue();
        var fechaincorporacion = view.down('#fecha_incripcionId').getValue();
        var fechaactualiza = view.down('#fecha_ult_actualizId').getValue();
        var estado = view.down('#tipoEstadoId').getValue();
        var tipocliente = view.down('#tipoClienteId').getValue();
        var id_credito = view.down('#tipocredId').getValue();
        var ufcredito = view.down('#ufaprobId').getValue();
        var tcliente = view.down('#tipoclientId').getValue();
        var morapermitida = view.down('#mora_permitida').getValue();
        //console.log(morapermitida);
        var st = this.getClientesStore();

         Ext.Ajax.request({
            url: preurl + 'clientes/update',
            params: {
                rut: rut,
                nombre: nombre,
                idcliente: idcliente,
                direccion: direccion,
                ciudad: ciudad,
                comuna: comuna,
                giro : giro,
                fono : fono,
                mail : mail,
                tcliente: tcliente,
                vendedor : vendedor,
                descuento: descuento,
                tipopago: tipopago,
                disponible: disponible,
                impuesto: impuesto,
                fechaincorporacion : fechaincorporacion,
                fechaactualiza : fechaactualiza,
                estado : estado,
                tipocliente : tipocliente,
                id_credito: id_credito,
                ufcredito: ufcredito,
                morapermitida: morapermitida
            },
             success: function(response){
                view.close();
                st.load();

            }
           
        });

    },
        
    editarclientes: function(){
        
        var view = this.getClientesprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            console.log(row)
            var edit = Ext.create('Infosys_web.view.clientes.Desplegar').show();
            edit.down('form').loadRecord(row);
           
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    agregarclientes: function(){
        
        Ext.create('Infosys_web.view.clientes.Ingresar').show();
        
    },

    cerrarclientes: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
     
    },
  
});










