Ext.define('Infosys_web.controller.Notacredito', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['productos.Items',
             'notacredito.Items',
             'Notacredito',
             'Notacreditop',
             'Clientes',
             'Factura2',
             'Productosf',
             'Productos',
             'Tipo_documento',
             'Sucursales_clientes',
             'Tipo_documento.Selectornc'],

    models: ['Notacredito.Item',
             'Notacredito',
             'Notacreditop',
             'Nota.Item2',
             'Tipo_documento',
             'Productos.Item',
             'Sucursales_clientes'],

    views: ['notacredito.Notacredito',
            'notacredito.Principal',
            'notacredito.BuscarClientes',
            'notacredito.BuscarProductos',
            'notacredito.BuscarSucursales',
            'notacredito.BuscarFacturas',
            'notacredito.BuscarFacturas2',
            'notacredito.Exportar',
            'notacredito.Notacreditoglosa',
            'notacredito.BuscarClientes2'],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
       ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'notacreditoingresar',
        selector: 'notacreditoingresar'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{
        ref: 'notacreditoprincipal',
        selector: 'notacreditoprincipal'
    },{
        ref: 'notacreditobuscarclientes',
        selector: 'notacreditobuscarclientes'
    },{
        ref: 'buscarproductosnotacredito',
        selector: 'buscarproductosnotacredito'
    },{
        ref: 'buscarsucursalesclientesnotacredito',
        selector: 'buscarsucursalesclientesnotacredito'
    },{
        ref: 'buscarfacturas',
        selector: 'buscarfacturas'
    },{
        ref: 'formularioexportarnotacredito',
        selector: 'formularioexportarnotacredito'
    },{
        ref: 'notacreditoglosa',
        selector: 'notacreditoglosa'
    },{
        ref: 'buscarfacturas2',
        selector: 'buscarfacturas2'
    },{
        ref: 'notacreditobuscarclientes2',
        selector: 'notacreditobuscarclientes2'
    }
   
    ],
    //init es lo primero que se ejecuta en el controller
    //especia de constructor
    init: function() {
    	//el <<control>> es el puente entre la vista y funciones internas
    	//del controller
        this.control({

            'notacreditoingresar #rutId': {
                specialkey: this.special
            },

            'notacreditoingresar #numfactId': {
                specialkey: this.special2
            },

            'notacreditoprincipal button[action=mnotacredito]': {
                click: this.mnotacredito
            },
            'notacreditoprincipal button[action=mnotacreditoglosa]': {
                click: this.mnotacreditoglosa
            },           
            'topmenus menuitem[action=meNotacredito]': {
                click: this.meNotacredito
            },
            'notacreditoingresar #tipodocumentoId': {
                select: this.selectItemdocuemento
            },
            'notacreditoglosa #tipodocumentoId': {
                select: this.selectItemdocuementoglosa
            },
            'notacreditoingresar button[action=notacreditobuscarclientes]': {
                click: this.notacreditobuscarclientes
            },
            'notacreditoingresar button[action=buscarfactura]': {
                click: this.buscarfactura
            },
            'notacreditoingresar button[action=buscarsucursalnotacredito]': {
                click: this.buscarsucursalnotacredito
            },
            'notacreditoingresar button[action=buscarvendedor]': {
                click: this.buscarvendedor
            },
            'notacreditoingresar button[action=buscarproductos]': {
                click: this.buscarproductos
            },
            'notacreditoingresar #nombreId': {
                click: this.special
            },

            'notacreditoingresar button[action=validarut]': {
                click: this.validarut
            },
            'notacreditoingresar button[action=grabarnotacredito]': {
                click: this.grabarnotacredito
            },
            'notacreditoprincipal button[action=cerrarfactura]': {
                click: this.cerrarfactura
            },
            'notacreditoprincipal button[action=generarfacturapdf]': {
                click: this.generarfacturapdf
            },
            'notacreditobuscarclientes button[action=buscar]': {
                click: this.buscar
            },
            'notacreditobuscarclientes button[action=seleccionarcliente]': {
                click: this.seleccionarcliente
            },
            'buscarproductosnotacredito button[action=seleccionarproductos]': {
                click: this.seleccionarproductos
            },
            'buscarproductosnotacredito button[action=seleccionarTodos]': {
                click: this.seleccionarTodos
            },
            'buscarproductosnotacredito button[action=buscar]': {
                click: this.buscarp
            },
            'buscarsucursalesclientesnotacredito button[action=seleccionarsucursalcliente]': {
                click: this.seleccionarsucursalcliente
            },
            'notacreditoingresar #tipocondpagoId': {
                select: this.selecttipocondpago
            },
            'notacreditoglosa #tipocondpagoId': {
                select: this.selecttipocondpago2
            },
            'notacreditoingresar button[action=agregarItem]': {
                click: this.agregarItem
            }, 
            'notacreditoingresar button[action=eliminaritem]': {
                click: this.eliminaritem
            },           
            'buscarfacturas button[action=seleccionarfactura]': {
                click: this.seleccionarfactura
            },
            'notacreditoprincipal button[action=exportarexcelnotacredito]': {
                click: this.exportarexcelnotacredito
            },
            'formularioexportarnotacredito button[action=exportarExcelFormulario]': {
                click: this.exportarExcelFormulario
            },
            'notacreditoprincipal button[action=buscarnota]': {
            click: this.buscarnota
            },
            'notacreditoglosa button[action=validarutglosa]': {
                click: this.validarutglosa
            },
            'notacreditobuscarclientes2 button[action=seleccionarcliente2]': {
                click: this.seleccionarcliente2
            },
            'notacreditoglosa button[action=buscarfactura2]': {
                click: this.buscarfactura2
            },
            'buscarfacturas2 button[action=seleccionarfactura2]': {
                click: this.seleccionarfactura2
            },
            'notacreditoglosa button[action=agregarItem2]': {
                click: this.agregarItem2
            },
            'notacreditoglosa #netoId': {
                specialkey: this.calculaiva
            },
            'notacreditoglosa button[action=grabarnotacredito2]': {
                click: this.grabarnotacredito2
            },
            'buscarfacturas button[action=buscar]': {
                click: this.buscarfact
            },
            'notacreditoingresar button[action=cancelar]': {
                click: this.cancelar
            },
            'notacreditoglosa button[action=cancelarglosa]': {
                click: this.cancelarglosa
            },   

            
        });
    },

    cancelar: function(){

        var viewIngresa = this.getNotacreditoingresar();
        var view = this.getNotacreditoprincipal();
        var idbodega = view.down('#bodegaId').getValue();
        var documento = 102;
        var numero = viewIngresa.down('#numfacturaId').getValue();
        var folio = viewIngresa.down('#idfolio').getValue();
        var stItem = this.getProductosItemsStore();

        if(stItem){

         var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'facturas/stock3',
            params: {               
                items: Ext.JSON.encode(dataItems),
                idbodega: idbodega                
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
               
            }
           
        });

        };

        if(documento){

        if (documento != 2){
             Ext.Ajax.request({
            url: preurl + 'facturas/folio_documento_electronico2',
            params: {
                id_folio: folio
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
            }
           
        });            
        }
        };       

        viewIngresa.close();        
    },

    cancelarglosa: function(){

        var viewIngresa = this.getNotacreditoglosa();
        var view = this.getNotacreditoprincipal();
        var idbodega = view.down('#bodegaId').getValue();
        var documento = 102;
        var numero = viewIngresa.down('#numfacturaId').getValue();
        var folio = viewIngresa.down('#idfolio').getValue();
        var stItem = this.getProductosItemsStore();

        if(stItem){

         var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'facturas/stock3',
            params: {               
                items: Ext.JSON.encode(dataItems),
                idbodega: idbodega                
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
               
            }
           
        });

        };

        if(documento){

        if (documento != 2){
             Ext.Ajax.request({
            url: preurl + 'facturas/folio_documento_electronico2',
            params: {
                id_folio: folio
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
            }
           
        });            
        }
        };       

        viewIngresa.close();        
    },

    selectItemdocuemento: function() {
        

        var view =this.getNotacreditoingresar();
        var tipo_documento = view.down('#tipodocumentoId');
        var stCombo = tipo_documento.getStore();

        var record = stCombo.findRecord('id', tipo_documento.getValue()).data;
        //console.log(record);
        var nombre = (record.id);    
        if(nombre == 102){ // NOTA DE CREDITO ELECTRONICA

            // se valida que exista certificado
            response_certificado = Ext.Ajax.request({
            async: false,
            url: preurl + 'facturas/existe_certificado/'});

            var obj_certificado = Ext.decode(response_certificado.responseText);

            if(obj_certificado.existe == true){


                //buscar folio factura electronica
                // se buscan folios pendientes, o ocupados hace más de 4 horas

                response_folio = Ext.Ajax.request({
                async: false,
                url: preurl + 'facturas/folio_documento_electronico/'+nombre});  
                var obj_folio = Ext.decode(response_folio.responseText);
                id_folio = obj_folio.idfolio; 
                nuevo_folio = obj_folio.folio;
                if(nuevo_folio != 0){
                    view.down('#numfacturaId').setValue(nuevo_folio);  
                    view.down('#idfolio').setValue(id_folio);  
                    //habilita = true;
                }else{
                    Ext.Msg.alert('Atención','No existen folios disponibles');
                    view.down('#numfacturaId').setValue('');  

                    //return
                }

            }else{
                    Ext.Msg.alert('Atención','No se ha cargado certificado');
                    view.down('#numfacturaId').setValue('');  
            }


        }else{

            Ext.Ajax.request({

                url: preurl + 'correlativos/generancred?valida='+nombre,
                params: {
                    id: 1
                },
                success: function(response){
                    var resp = Ext.JSON.decode(response.responseText);

                    if (resp.success == true) {
                        var cliente = resp.cliente;
                        var correlanue = cliente.correlativo;
                        //var descripcion = cliente.nombre;
                        //var id = cliente.id;
                        correlanue = (parseInt(correlanue)+1);
                        var correlanue = correlanue;
                        //var view = Ext.create('Infosys_web.view.notacredito.Notacredito').show();
                        view.down('#numfacturaId').setValue(correlanue);
                        //view.down('#nomdocumentoId').setValue(descripcion);
                        //view.down('#tipodocumentoId').setValue(id);
                        
                    }else{
                        Ext.Msg.alert('Correlativo YA Existe');
                        return;
                    }



                }            
            });            
        }
        var grid  = view.down('#itemsgridId');        
        grid.getStore().removeAll();  
        //var controller = this.getController('Productos');
        this.recalcularFinal();

    },

    selectItemdocuementoglosa: function() {
        

        var view =this.getNotacreditoglosa();
        var tipo_documento = view.down('#tipodocumentoId');
        var stCombo = tipo_documento.getStore();

        var record = stCombo.findRecord('id', tipo_documento.getValue()).data;
        //console.log(record);
        var nombre = (record.id);    
        if(nombre == 102){ // NOTA DE CREDITO ELECTRONICA

            // se valida que exista certificado
            response_certificado = Ext.Ajax.request({
            async: false,
            url: preurl + 'facturas/existe_certificado/'});

            var obj_certificado = Ext.decode(response_certificado.responseText);

            if(obj_certificado.existe == true){


                //buscar folio factura electronica
                // se buscan folios pendientes, o ocupados hace más de 4 horas

                response_folio = Ext.Ajax.request({
                async: false,
                url: preurl + 'facturas/folio_documento_electronico/'+nombre});  
                var obj_folio = Ext.decode(response_folio.responseText);
                nuevo_folio = obj_folio.folio;
                if(nuevo_folio != 0){
                    view.down('#numfacturaId').setValue(nuevo_folio);  
                    //habilita = true;
                }else{
                    Ext.Msg.alert('Atención','No existen folios disponibles');
                    view.down('#numfacturaId').setValue('');  

                    //return
                }

            }else{
                    Ext.Msg.alert('Atención','No se ha cargado certificado');
                    view.down('#numfacturaId').setValue('');  
            }


        }else{

            Ext.Ajax.request({

                url: preurl + 'correlativos/generancred?valida='+nombre,
                params: {
                    id: 1
                },
                success: function(response){
                    var resp = Ext.JSON.decode(response.responseText);

                    if (resp.success == true) {
                        var cliente = resp.cliente;
                        var correlanue = cliente.correlativo;
                        //var descripcion = cliente.nombre;
                        //var id = cliente.id;
                        correlanue = (parseInt(correlanue)+1);
                        var correlanue = correlanue;
                        //var view = Ext.create('Infosys_web.view.notacredito.Notacredito').show();
                        view.down('#numfacturaId').setValue(correlanue);
                        //view.down('#nomdocumentoId').setValue(descripcion);
                        //view.down('#tipodocumentoId').setValue(id);
                        
                    }else{
                        Ext.Msg.alert('Correlativo YA Existe');
                        return;
                    }



                }            
            });            
        }
        var grid  = view.down('#itemsgridId');        
        grid.getStore().removeAll();  
        //var controller = this.getController('Productos');
        //this.recalcularFinal2();

    },

    buscarfact: function(){

        var view = this.getBuscarfacturas()
        var st = this.getFactura2Store()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {numero : nombre}
        st.load();       

    },

    grabarnotacredito2: function() {

        var viewIngresa = this.getNotacreditoglosa();
        var view = this.getNotacreditoprincipal();
        var idbodega = view.down('#bodegaId').getValue();
        var tipo_documento = viewIngresa.down('#tipodocumentoId').getValue();
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var idtipo= viewIngresa.down('#tipodocumentoId').getValue();
        var idsucursal= viewIngresa.down('#id_sucursalID').getValue();
        var idcondventa= viewIngresa.down('#tipocondpagoId').getValue();
        var idfactura = viewIngresa.down('#numfacturaId').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId').getValue();
        var numdocumento = viewIngresa.down('#numfacturaId').getValue();
        var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
        var numfactura_asoc = viewIngresa.down('#numfactId').getValue();
        var docurelacionado = viewIngresa.down('#factId').getValue();       
        
        var fechavenc = viewIngresa.down('#fechavencId').getValue();
        var stItem = this.getNotacreditoItemsStore();
        var stnotacredito = this.getNotacreditoStore();
        viewIngresa.down("#grabarfactura").setDisabled(true);



        if(!docurelacionado){
            var docurelacionado =1;
        };

        if(numdocumento==0){
            Ext.Msg.alert('Ingrese Datos a La Factura');
            return;   
            }

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        var loginMask = new Ext.LoadMask(Ext.getBody(), {msg:"Generando Documento ..."});

        loginMask.show();        

        Ext.Ajax.request({
            url: preurl + 'notacredito/save2',
            params: {
                idcliente: idcliente,
                idfactura: idfactura,
                numdocumento: numdocumento,
                docurelacionado: docurelacionado,
                idsucursal: idsucursal,
                idbodega: idbodega,
                idcondventa: idcondventa,
                idtipo: idtipo,
                items: Ext.JSON.encode(dataItems),
                vendedor : vendedor,
                numfactura_asoc : numfactura_asoc,
                fechafactura : fechafactura,
                fechavenc: fechavenc,
                tipodocumento : tipo_documento,
                netofactura: viewIngresa.down('#finaltotalnetoId').getValue(),
                ivafactura: viewIngresa.down('#finaltotalivaId').getValue(),
                afectofactura: viewIngresa.down('#finalafectoId').getValue(),
                totalfacturas: viewIngresa.down('#finaltotalpostId').getValue()
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idfactura= resp.idfactura;
                 viewIngresa.close();
                 stnotacredito.load();
                 //window.open(preurl + 'notadebito/exportnotadebitoPDF/?idfactura='+idfactura);
                 window.open(preurl + 'facturas/exportTXTNCGLO/?idfactura='+idfactura);
                 loginMask.hide();   
            }
           
        });      
        
    },

    calculaiva: function(){
        var view = this.getNotacreditoglosa();
        var neto = view.down('#netoId').getValue();
        var iva = (parseInt((neto * 19) / 100));
        var total = (neto + iva);
        view.down('#ivaId').setValue(iva);
        view.down('#totalId').setValue(total);
    },

    agregarItem2: function() {

        var view = this.getNotacreditoglosa();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var stItem = this.getNotacreditoItemsStore();;        
        var glosa = view.down('#glosaId').getValue();
        var neto = view.down('#netoId').getValue();
        var iva = view.down('#ivaId').getValue();
        var total = view.down('#totalId').getValue();
        var idfactura = view.down('#factId').getValue();
        var totfactura = view.down('#totfactId').getValue();
        var totalfin = view.down('#finaltotalpostId').getValue();
        var netofin = view.down('#finalafectoId').getValue();
        var ivafin = view.down('#finaltotalivaId').getValue();

        if(!glosa){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Glosa.');
            return false;
        };

        if(glosa.length > 250){
            Ext.Msg.alert('Alerta', 'GLOSA SOBREPASA CANTIDAD DE CARACTERES POR LINEA');
            return false;            
        };
        
        if(!neto ){  // se validan los datos sólo si es factura
           var neto=0;
        }; 
        
        if(!iva ){  // se validan los datos sólo si es factura
            var iva=0;
        }; 

        if(!total ){  // se validan los datos sólo si es factura
            var total=0;
        };        
        
                   
        if(rut.length==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Datos a la Factura.');
            return false;
        };   

        if(totfactura){

            totalfin = totalfin + total;
            ivafin = ivafin + iva;
            netofin = netofin + neto;
      

        

        if(totalfin>totfactura ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Valor no Puedes Ser Superior a Factura.');
            return false;
        }else{

            stItem.add(new Infosys_web.model.Nota.Item2({
                    glosa: glosa,
                    neto: neto,
                    iva: iva,
                    total: total             
            }));
        }; 
       
        cero="";
        cero2=0;
        view.down('#glosaId').setValue(cero);
        view.down('#netoId').setValue(cero2);
        view.down('#ivaId').setValue(cero2);
        view.down('#totalId').setValue(cero2);
        
        view.down('#finaltotalId').setValue(Ext.util.Format.number(totalfin, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalfin, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netofin, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivafin, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(netofin, '0'));
                
        }else{

            Ext.Msg.alert('Alerta', 'Debe Seleccionar un Factura');
            return false;           

        };

             
    },

    seleccionarfactura2 : function(){

        var view = this.getBuscarfacturas2();
        var viewIngresa = this.getNotacreditoglosa();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#facturaId').setValue(row.data.id);
            viewIngresa.down('#numfactId').setValue(row.data.num_factura);
            viewIngresa.down('#totfactId').setValue(row.data.totalfactura);
            viewIngresa.down('#factId').setValue(row.data.id);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }


        

    },


     buscarfactura2 : function() {

       var busca = this.getNotacreditoglosa()
       var nombre = busca.down('#id_cliente').getValue();
           
       if (nombre){
          var edit =  Ext.create('Infosys_web.view.notacredito.BuscarFacturas2').show();
          var st = this.getFactura2Store();
          st.proxy.extraParams = {nombre : nombre};
          st.load();
       }else {
          Ext.Msg.alert('Alerta', 'Debe seleccionar Cliente.');
            return;
       }

    },


    seleccionarcliente2: function(){

        var view = this.getNotacreditobuscarclientes2();
        var viewIngresa = this.getNotacreditoglosa();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#id_cliente').setValue(row.data.id);
            viewIngresa.down('#nombre_id').setValue(row.data.nombres);
            viewIngresa.down('#tipoCiudadId').setValue(row.data.nombre_ciudad);
            viewIngresa.down('#tipoComunaId').setValue(row.data.nombre_comuna);
            viewIngresa.down('#tipoVendedorId').setValue(row.data.id_vendedor);
            viewIngresa.down('#giroId').setValue(row.data.giro);
            viewIngresa.down('#direccionId').setValue(row.data.direccion);
            viewIngresa.down('#rutId').setValue(row.data.rut);
            viewIngresa.down('#tipoVendedorId').setValue(row.data.id_vendedor);
            viewIngresa.down('#tipocondpagoId').setValue(row.data.id_pago);
            view.close();
            var condicion = viewIngresa.down('#tipocondpagoId');
            var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
            var stCombo = condicion.getStore();
            var record = stCombo.findRecord('id', condicion.getValue()).data;
            dias = record.dias;
        
            Ext.Ajax.request({
                url: preurl + 'facturas/calculofechas',
                params: {
                    dias: dias,
                    fechafactura : fechafactura
                },
                success: function(response){
                   var resp = Ext.JSON.decode(response.responseText);
                   var fecha_final= resp.fecha_final;
                   viewIngresa.down("#fechavencId").setValue(fecha_final);
                               
            }
           
        });
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    validarutglosa : function(){

        var view =this.getNotacreditoglosa();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

       
        if(numero==0){
            var edit = Ext.create('Infosys_web.view.notacredito.BuscarClientes2');            
                  
        }else{
       
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
                var cero = "";
                if (resp.success == true) {
                    
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down("#id_cliente").setValue(cliente.id)
                        view.down("#nombre_id").setValue(cliente.nombres)
                        view.down("#tipoCiudadId").setValue(cliente.nombre_ciudad)
                        view.down("#tipoComunaId").setValue(cliente.nombre_comuna)
                        view.down("#tipoVendedorId").setValue(cliente.id_vendedor)
                        view.down("#giroId").setValue(cliente.giro)
                        view.down("#direccionId").setValue(cliente.direccion)
                        view.down("#rutId").setValue(rut)
                        view.down("#numfactId").focus()                       
                    }else{
                         Ext.Msg.alert('Rut No Exite');
                         view.down("#rutId").setValue(cero); 
                        return;   
                    }
                    
                }else{
                      Ext.Msg.alert('Informacion', 'Rut Incorrecto');
                      view.down("#rutId").setValue(cero);
                      return;
                      
                }

                //view.close();

            }

        });       
        }
    },

    mnotacreditoglosa: function(){
      
        var viewIngresa = this.getNotacreditoprincipal();
        var idbodega = viewIngresa.down('#bodegaId').getValue();
        if(!idbodega){
            Ext.Msg.alert('Alerta', 'Debe Elegir Bodega');
            return;    
        }else{ 

        var nombre = "102";
        var descripcion = "NOTA DE CREDITO ELECTRONICA";    
        habilita = false;
        if(nombre == 102){ // FACTURA ELECTRONICA o FACTURA EXENTA

            response_certificado = Ext.Ajax.request({
            async: false,
            url: preurl + 'facturas/existe_certificado/'});

            var obj_certificado = Ext.decode(response_certificado.responseText);

            if(obj_certificado.existe == true){

                response_folio = Ext.Ajax.request({
                async: false,
                url: preurl + 'facturas/folio_documento_electronico/'+nombre});  
                var obj_folio = Ext.decode(response_folio.responseText);
                id_folio = obj_folio.idfolio;
                nuevo_folio = obj_folio.folio;
                if(nuevo_folio != 0){
                    var view = Ext.create('Infosys_web.view.notacredito.Notacreditoglosa').show();                   
                    view.down('#numfacturaId').setValue(nuevo_folio);
                    view.down('#idfolio').setValue(id_folio);
                    view.down('#tipodocumentoId').setValue(nombre);                    
                    view.down('#bodegaId').setValue(idbodega);
          
                    habilita = true;
                }else{
                    Ext.Msg.alert('Atención','No existen folios disponibles');
                    view.down('#numfacturaId').setValue('');  

                    //return
                }

            }else{
                    Ext.Msg.alert('Atención','No se ha cargado certificado');
                    view.down('#numfacturaId').setValue('');  
            }


        }
        
        };

        
    },

   
    special: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.buscarproductos()
        }
    },

    buscarnota: function(){
        
        var view = this.getNotacreditoprincipal();
        var st = this.getNotacreditoStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
        st.load();
    },

    exportarexcelnotacredito: function(){
              
           Ext.create('Infosys_web.view.notacredito.Exportar1').show();
    },

    exportarExcelFormulario: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getNotacreditoprincipal()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getFormularioexportarnotacredito()
        var viewnew =this.getNotacreditoprincipal()
        var fecha = view.down('#fechaId').getSubmitValue();
        var opcion = viewnew.down('#tipoSeleccionId').getValue()
        var nombre = viewnew.down('#nombreId').getSubmitValue();
        var fecha2 = view.down('#fecha2Id').getSubmitValue();

        if (fecha > fecha2) {
        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };

        window.open(preurl + 'adminServicesExcel/exportarExcelNotacredito?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2+'&opcion='+opcion+'&nombre='+nombre);
        view.close();
 
    },




    recalcularFinal: function(){
        var view = this.getNotacreditoingresar();
        var stItem = this.getProductosItemsStore();
        var pretotal = 0;
        var total = 0;
        
        stItem.each(function(r){
            pretotal = (parseInt(pretotal) + parseInt(r.data.totaliva))
          
        });
        total = pretotal;
        neto = (total / 1.19);
        afecto = neto;
        iva = total - neto;
        
        //iva = (total - afecto);
        view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));
          
    },

    changedctofinal: function(){
        this.recalcularFinal();
    },


    agregarItem: function() {

        var view = this.getNotacreditoingresar();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var stItem = this.getProductosItemsStore();
        var producto = view.down('#productoId').getValue();
        var idp = view.down('#pId').getValue();
        var idext = view.down('#idext').getValue();
        var nomproducto = view.down('#nomproductoId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var idfactura = view.down('#factId').getValue();
        var idfacturaval = view.down('#factactId').getValue();
        var numfactura = view.down('#numfactId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var precioun = ((view.down('#precioId').getValue())/ 1.19);        
        
        var neto = ((cantidad * precio));
        var tot = (Math.round(neto * 1.19));
        var exists = 0;
        var iva = (tot - neto);
        var neto = (tot - iva);
        var totaliva = ((neto + iva ));   
       
        var totalfin = view.down('#finaltotalpostId').getValue();
        var netofin = view.down('#finalafectoId').getValue();
        var ivafin = view.down('#finaltotalivaId').getValue();
        
        if(precio==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Precio Producto');
            return false;
        };

        if (cantidadori){
            if(parseFloat(cantidad)>parseFloat(cantidadori)){
                Ext.Msg.alert('Alerta', 'Cantidad Ingresada de Productos Supera El Stock');
                return false;
            };

        };

        if(cantidad==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Cantidad.');
            return false;
        };
                    
        if(rut.length==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Datos a la Factura.');
            return false;
        };
        
             

        if(idfactura){
            Ext.Ajax.request({
                    url: preurl + 'notacredito/marca',
                params: {
                    idp: idp
                },
                success: function(response){
                   var resp = Ext.JSON.decode(response.responseText);   
               }
           });

            Ext.Ajax.request({
                    url: preurl + 'notacredito/validaproducto',
                params: {
                    idproducto: producto,
                    idfactura : idfacturaval
                },
                success: function(response){
                   var resp = Ext.JSON.decode(response.responseText);                

                   if(resp.cliente){

                      var cliente = resp.cliente;
                      var canti = cliente.cantidad;
                    }

                   if (resp.success == false) {

                    cero="";
                    cero2=0;
                    view.down('#codigoId').setValue(cero);
                    view.down('#productoId').setValue(cero);
                    view.down('#cantidadId').setValue(cero2);
                    view.down('#precioId').setValue(cero2);
                    view.down('#cantidadOriginalId').setValue(cero);
                    view.down("#buscarproc").focus();

                    Ext.Msg.alert('Alerta', 'Producto No corresponde a Factura');
                    return false;
                    

                   }else{


        console.log(cantidad)
        console.log(canti)     
        console.log(parseFloat(cantidad))
        console.log(parseFloat(canti))                    

                    if(cantidad>canti){

                        cero="";
                        cero2=0;
                        view.down('#codigoId').setValue(cero);
                        view.down('#productoId').setValue(cero);
                        view.down('#cantidadId').setValue(cero2);
                        view.down('#precioId').setValue(cero2);
                        view.down('#cantidadOriginalId').setValue(cero);
                        view.down("#buscarproc").focus();


                        Ext.Msg.alert('Alerta', 'Cantidad de Producto Mayor a lo Vendido');
                        return false;

                    }else{
                    

                    stItem.each(function(r){
                    if(r.data.id_producto == producto){
                        Ext.Msg.alert('Alerta', 'El registro ya existe.');
                        exists = 1;
                        cero="";
                        view.down('#codigoId').setValue(cero);
                        view.down('#productoId').setValue(cero);
                        view.down('#cantidadId').setValue(cero);
                        view.down('#precioId').setValue(cero);

                        return; 
                    }
                    });
                    if(exists == 1)
                    return;

                    stItem.add(new Infosys_web.model.Productos.Item({
                        id: idp,
                        id_existencia: idext, 
                        id_producto: producto,
                        nombre: nomproducto,
                        precio: precio,
                        cantidad: cantidad,
                        neto: neto,
                        totaliva: totaliva,
                        iva: iva          
                    }));

                    cero="";
                    cero2=0;
                    view.down('#codigoId').setValue(cero);
                    view.down('#productoId').setValue(cero);
                    view.down('#pId').setValue(cero);
                    view.down('#cantidadId').setValue(cero2);
                    view.down('#precioId').setValue(cero2);
                    view.down('#cantidadOriginalId').setValue(cero);
                    view.down("#buscarproc").focus();
                    totalfin = totalfin + totaliva;
                    ivafin = ivafin + iva;
                    netofin = netofin + neto;
                  
                    view.down('#finaltotalId').setValue(Ext.util.Format.number(totalfin, '0,000'));
                    view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalfin, '0'));
                    view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netofin, '0'));
                    view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivafin, '0'));
                    view.down('#finalafectoId').setValue(Ext.util.Format.number(netofin, '0'));
          
                }
                    }
                    
                               
                    }
                });
        }else{

            if (numfactura){

            stItem.add(new Infosys_web.model.Productos.Item({
                        id: producto,
                        id_producto: producto,
                        nombre: nomproducto,
                        precio: precio,
                        cantidad: cantidad,
                        neto: neto,
                        totaliva: totaliva,
                        iva: iva          
                    }));

            
                    cero="";
                    cero2=0;
                    view.down('#codigoId').setValue(cero);
                    view.down('#productoId').setValue(cero);
                    view.down('#cantidadId').setValue(cero2);
                    view.down('#precioId').setValue(cero2);
                    view.down('#cantidadOriginalId').setValue(cero);
                    view.down("#buscarproc").focus();
                    totalfin = totalfin + totaliva;
                    ivafin = ivafin + iva;
                    netofin = netofin + neto;
                  
                    view.down('#finaltotalId').setValue(Ext.util.Format.number(totalfin, '0,000'));
                    view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalfin, '0'));
                    view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netofin, '0'));
                    view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivafin, '0'));
                    view.down('#finalafectoId').setValue(Ext.util.Format.number(netofin, '0'));
          
            }else{

            Ext.Msg.alert('Alerta', 'Debe Seleccionar un Factura');
            return false;
            
            };           

        };
        
    },

    eliminaritem: function() {
        var view = this.getNotacreditoingresar();
        var total = view.down('#finaltotalpostId').getValue();
        var neto = view.down('#finaltotalnetoId').getValue();
        var iva = view.down('#finaltotalivaId').getValue();
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var total = (parseInt(total) - parseInt(row.data.totaliva));
            var neto = (parseInt(neto) - parseInt(row.data.neto));
            var iva = (parseInt(iva) - parseInt(row.data.iva));
            var idp = row.data.id
            var afecto = neto;
            view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
            view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
            view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
            view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
            view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));
            Ext.Ajax.request({
                    url: preurl + 'notacredito/desmarca',
                params: {
                    idp: idp
                },
                success: function(response){
                   var resp = Ext.JSON.decode(response.responseText);   
               }
           });

            grid.getStore().remove(row);

        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

    },

    seleccionarfactura: function(){

        var view = this.getBuscarfacturas();
        var viewIngresa = this.getNotacreditoingresar();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            console.log(row.data)
            viewIngresa.down('#facturaId').setValue(row.data.id);
            viewIngresa.down('#numfactId').setValue(row.data.num_factura);
            viewIngresa.down('#totfactId').setValue(row.data.totalfactura);
            viewIngresa.down('#factId').setValue(row.data.id);
            var tipo_documento = viewIngresa.down('#tipodocumentoId').getValue();
            if(tipo_documento == 102){
                viewIngresa.down('#tipoNotaCredito').setDisabled(false);
            }            
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }


        

    },

    special: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut()
        }
    },

    special2: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validafactura()
        }
    },

    validafactura: function() {

        Ext.Msg.alert('Alerta', 'Factura no Existe');
            return;

    },

    buscarfactura : function() {

       var busca = this.getNotacreditoingresar()
       var nombre = busca.down('#id_cliente').getValue();
           
       if (nombre){
          var edit =  Ext.create('Infosys_web.view.notacredito.BuscarFacturas').show();
          var st = this.getFactura2Store();
          st.proxy.extraParams = {nombre : nombre};
          st.load();
       }else {
          Ext.Msg.alert('Alerta', 'Debe seleccionar Cliente.');
            return;
       }
       

    },

    meNotacredito: function() { 
    
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'notacreditoprincipal'});
    },

    selecttipocondpago: function() {
        
        var view =this.getNotacreditoingresar();
        var condicion = view.down('#tipocondpagoId');
        var fechafactura = view.down('#fechafacturaId').getValue();
                

        var stCombo = condicion.getStore();
        var record = stCombo.findRecord('id', condicion.getValue()).data;
        dias = record.dias;
        
        Ext.Ajax.request({
            url: preurl + 'facturas/calculofechas',
            params: {
                dias: dias,
                fechafactura : fechafactura
            },
            success: function(response){
               var resp = Ext.JSON.decode(response.responseText);
               var fecha_final= resp.fecha_final;
               view.down("#fechavencId").setValue(fecha_final);
                           
            }
           
        });
       
            
    },

    selecttipocondpago2: function() {
        
        var view =this.getNotacreditoglosa();
        var condicion = view.down('#tipocondpagoId');
        var fechafactura = view.down('#fechafacturaId').getValue();
                

        var stCombo = condicion.getStore();
        var record = stCombo.findRecord('id', condicion.getValue()).data;
        dias = record.dias;
        
        Ext.Ajax.request({
            url: preurl + 'facturas/calculofechas',
            params: {
                dias: dias,
                fechafactura : fechafactura
            },
            success: function(response){
               var resp = Ext.JSON.decode(response.responseText);
               var fecha_final= resp.fecha_final;
               view.down("#fechavencId").setValue(fecha_final);
                           
            }
           
        });
       
            
    },

    seleccionarsucursalcliente: function(){

        var view = this.getBuscarsucursalesclientesnotacredito();
        var viewIngresa = this.getNotacreditoingresar();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#id_sucursalID').setValue(row.data.id);
            viewIngresa.down('#direccionId').setValue(row.data.direccion);
            viewIngresa.down('#tipoCiudadId').setValue(row.data.nombre_ciudad);
            viewIngresa.down('#tipoComunaId').setValue(row.data.nombre_comuna);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    buscar: function(){

        var view = this.getNotacreditobuscarclientes()
        var st = this.getClientesStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : "Nombre"}
        st.load();
    },

    buscarsucursalnotacredito: function(){

       var busca = this.getNotacreditoingresar()
       var nombre = busca.down('#id_cliente').getValue();
       
       if (nombre){
         var edit = Ext.create('Infosys_web.view.ventas.BuscarSucursales').show();
          var st = this.getSucursales_clientesStore();
          st.proxy.extraParams = {nombre : nombre};
          st.load();
       }else {
          Ext.Msg.alert('Alerta', 'Debe seleccionar Cliente.');
            return;
       }
      
    },

    seleccionarcliente: function(){

        var view = this.getNotacreditobuscarclientes();
        var viewIngresa = this.getNotacreditoingresar();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#id_cliente').setValue(row.data.id);
            viewIngresa.down('#nombre_id').setValue(row.data.nombres);
            viewIngresa.down('#tipoCiudadId').setValue(row.data.nombre_ciudad);
            viewIngresa.down('#tipoComunaId').setValue(row.data.nombre_comuna);
            viewIngresa.down('#tipoVendedorId').setValue(row.data.id_vendedor);
            viewIngresa.down('#giroId').setValue(row.data.giro);
            viewIngresa.down('#direccionId').setValue(row.data.direccion);
            viewIngresa.down('#rutId').setValue(row.data.rut);
            viewIngresa.down('#tipoVendedorId').setValue(row.data.id_vendedor);
            viewIngresa.down('#tipocondpagoId').setValue(row.data.id_pago);
            view.close();
            var condicion = viewIngresa.down('#tipocondpagoId');
            var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
            var stCombo = condicion.getStore();
            var record = stCombo.findRecord('id', condicion.getValue()).data;
            dias = record.dias;
        
            Ext.Ajax.request({
                url: preurl + 'facturas/calculofechas',
                params: {
                    dias: dias,
                    fechafactura : fechafactura
                },
                success: function(response){
                   var resp = Ext.JSON.decode(response.responseText);
                   var fecha_final= resp.fecha_final;
                   viewIngresa.down("#fechavencId").setValue(fecha_final);
                               
            }
           
        });
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },
    

    generarfacturapdf: function(){
        var view = this.getNotacreditoprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
             if(row.data.tipo_documento == 102){ // NOTA DE CREDITO ELECTRONICA
                window.open(preurl +'facturas/exportFePDF/' + row.data.id);   
             }else{
                if (row.data.forma==0){
                window.open(preurl +'facturas/exportPDF/?idfactura=' + row.data.id)
                };
                if (row.data.forma==1){
                window.open(preurl +'facturas/exportPDF/?idfactura=' + row.data.id)
                };
                if (row.data.forma==2){
                window.open(preurl +'facturaganado/exportfacturaganadoPDF/?idfactura=' + row.data.id)
                };
             }            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    
    grabarnotacredito: function() {

        var viewIngresa = this.getNotacreditoingresar();
        var view = this.getNotacreditoprincipal();
        var tipo_documento = viewIngresa.down('#tipodocumentoId').getValue();
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var idbodega = viewIngresa.down('#bodegaId').getValue();
        var idtipo= viewIngresa.down('#tipodocumentoId').getValue();
        var idsucursal= viewIngresa.down('#id_sucursalID').getValue();
        var idcondventa= viewIngresa.down('#tipocondpagoId').getValue();
        var idfactura = viewIngresa.down('#numfacturaId').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId').getValue();
        var numdocumento = viewIngresa.down('#numfacturaId').getValue();
        var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
        var numfactura_asoc = viewIngresa.down('#numfactId').getValue();
        
        var fechavenc = viewIngresa.down('#fechavencId').getValue();
        var stItem = this.getProductosItemsStore();
        var stNotacredito = this.getNotacreditoStore();


        viewIngresa.down("#grabarfactura").setDisabled(true); 


        if(!vendedor){
            Ext.Msg.alert('Atención','Ingrese Vendedor');
            viewIngresa.down("#grabarfactura").setDisabled(false); 
            return;   
            }

        if(!idcondventa){
            Ext.Msg.alert('Atención','Ingrese Condicion Venta');
            viewIngresa.down("#grabarfactura").setDisabled(false); 
            return;   
            }


        if(numdocumento==0){
            Ext.Msg.alert('Atención','Ingrese Datos a La Factura');
            viewIngresa.down("#grabarfactura").setDisabled(false); 
            return;   
            }

        if(!idbodega){
            Ext.Msg.alert('Atención','Debe Seleccionar Bodega de destino');
            viewIngresa.down("#grabarfactura").setDisabled(false); 
            return;   
            }


         var tipo_nota_credito = 0;
        if(tipo_documento == 102){
            var tipo_nota_credito = viewIngresa.down('#tipoNotaCredito').getValue();
            if(tipo_nota_credito==0 || tipo_nota_credito == null || tipo_nota_credito == ''){
                Ext.Msg.alert('Atención','Debe seleccionar tipo de nota de crédito');
                viewIngresa.down("#grabarfactura").setDisabled(false); 
                return;   
                }


        }  

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });


        var loginMask = new Ext.LoadMask(Ext.getBody(), {msg:"Generando Documento ..."});

         loginMask.show();
        
        Ext.Ajax.request({
            url: preurl + 'notacredito/save',
            params: {
                idcliente: idcliente,
                idfactura: idfactura,
                idsucursal: idsucursal,
                idbodega: idbodega,
                numdocumento: numdocumento,
                idsucursal: idsucursal,
                idcondventa: idcondventa,
                idtipo: idtipo,
                items: Ext.JSON.encode(dataItems),
                vendedor : vendedor,
                numfactura_asoc : numfactura_asoc,
                fechafactura : fechafactura,
                fechavenc: fechavenc,
                tipodocumento : tipo_documento,
                tipo_nota_credito : tipo_nota_credito,
                netofactura: viewIngresa.down('#finaltotalnetoId').getValue(),
                ivafactura: viewIngresa.down('#finaltotalivaId').getValue(),
                afectofactura: viewIngresa.down('#finalafectoId').getValue(),
                totalfacturas: viewIngresa.down('#finaltotalpostId').getValue()
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idfactura= resp.idfactura;
                 viewIngresa.close();
                 stNotacredito.load();
                if(tipo_documento == 102){ // NOTA DE CREDITO ELECTRONICA
                    window.open(preurl +'facturas/exportFePDF/' + idfactura);   
                 }else{
                    window.open(preurl + 'facturas/exportPDF/?idfactura='+idfactura);
                 }  

                 loginMask.hide();  
            }
           
        });    
        
    },

    cerrarfactura: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
    },

   

    validarut: function(){

        var view =this.getNotacreditoingresar();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

       
        if(numero==0){
            var edit = Ext.create('Infosys_web.view.notacredito.BuscarClientes');            
                  
        }else{
       
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
                var cero = "";
                if (resp.success == true) {
                    
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down("#id_cliente").setValue(cliente.id)
                        view.down("#nombre_id").setValue(cliente.nombres)
                        view.down("#tipoCiudadId").setValue(cliente.nombre_ciudad)
                        view.down("#tipoComunaId").setValue(cliente.nombre_comuna)
                        view.down("#tipoVendedorId").setValue(cliente.id_vendedor)
                        view.down("#giroId").setValue(cliente.giro)
                        view.down("#direccionId").setValue(cliente.direccion)
                        view.down("#rutId").setValue(rut)
                        view.down("#numfactId").focus()                       
                    }else{
                         Ext.Msg.alert('Rut No Exite');
                         view.down("#rutId").setValue(cero); 
                        return;   
                    }
                    
                }else{
                      Ext.Msg.alert('Informacion', 'Rut Incorrecto');
                      view.down("#rutId").setValue(cero);
                      return;
                      
                }

                //view.close();

            }

        });       
        }
    },

     mnotacredito: function(){
      
        var viewIngresa = this.getNotacreditoprincipal();
        var idbodega = viewIngresa.down('#bodegaId').getValue();
        if(!idbodega){
            Ext.Msg.alert('Alerta', 'Debe Elegir Bodega');
            return;    
        }else{ 

        var nombre = "102";
        var descripcion = "NOTA DE CREDITO ELECTRONICA";    
        habilita = false;
        if(nombre == 102){ // FACTURA ELECTRONICA o FACTURA EXENTA

            response_certificado = Ext.Ajax.request({
            async: false,
            url: preurl + 'facturas/existe_certificado/'});

            var obj_certificado = Ext.decode(response_certificado.responseText);

            if(obj_certificado.existe == true){

                response_folio = Ext.Ajax.request({
                async: false,
                url: preurl + 'facturas/folio_documento_electronico/'+nombre});  
                var obj_folio = Ext.decode(response_folio.responseText);
                id_folio = obj_folio.idfolio;
                nuevo_folio = obj_folio.folio;
                if(nuevo_folio != 0){
                    var view = Ext.create('Infosys_web.view.notacredito.Notacredito').show();
                    view.down('#numfacturaId').setValue(nuevo_folio);
                    view.down('#idfolio').setValue(id_folio);
                    view.down('#tipodocumentoId').setValue(nombre);                    
                    //view.down('#bodegaId').setValue(idbodega);
          
                    habilita = true;
                }else{
                    Ext.Msg.alert('Atención','No existen folios disponibles');
                    view.down('#numfacturaId').setValue('');  

                    //return
                }

            }else{
                    Ext.Msg.alert('Atención','No se ha cargado certificado');
                    view.down('#numfacturaId').setValue('');  
            }


        }
        
        };

        
    },
    

    buscarvendedor: function(){
        Ext.create('Infosys_web.view.vendedores.BuscarVendedor').show();
    },

    buscarproductos: function(){

        var view = this.getNotacreditoingresar();
        var st = this.getNotacreditopStore()
        var nombre = view.down('#facturaId').getValue()
      
       if (!nombre){

         Ext.Msg.alert('Alerta', 'Debe seleccionar Factura.');
            return;
           
       }else{
        st.proxy.extraParams = {nombre : nombre}
        st.load();
        Ext.create('Infosys_web.view.notacredito.BuscarProductos').show();
       };
    },

    seleccionarTodos: function(){

        var viewIngresa = this.getBuscarproductosnotacredito();
        var view = this.getNotacreditoingresar();
        var nombre = view.down('#facturaId').getValue()
        var stItem = this.getNotacreditopStore();
        var stItem2 = this.getProductosItemsStore();
        
        var totalfin = view.down('#finaltotalpostId').getValue();
        var netofin = view.down('#finalafectoId').getValue();
        var ivafin = view.down('#finaltotalivaId').getValue();
        
        
        stItem.each(function(r){
            var cantidad = r.data.stock
            var id = r.data.id_producto
            var idp = r.data.id
            var precio = r.data.p_venta
            var neto = ((r.data.stock * r.data.p_venta))
            var nomproducto = r.data.nombre
            var nomproducto = r.data.id
            var tot = (Math.round(neto * 1.19))
            var iva = (tot - neto)
            var neto = (tot - iva)
            var totaliva = ((neto + iva ))

            stItem2.add(new Infosys_web.model.Productos.Item({
                id: idp,
                id_producto: id,
                nombre: nomproducto,
                precio: precio,
                cantidad: cantidad,
                neto: neto,
                totaliva: totaliva,
                iva: iva          
            }));

            Ext.Ajax.request({
                    url: preurl + 'notacredito/marca',
                params: {
                    idp: idp
                },
                success: function(response){
                   var resp = Ext.JSON.decode(response.responseText);   
               }
           });

            totalfin = totalfin + totaliva;
            ivafin = ivafin + iva;
            netofin = netofin + neto;
          
        });
                  
        view.down('#finaltotalId').setValue(Ext.util.Format.number(totalfin, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalfin, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netofin, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivafin, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(netofin, '0'));
        viewIngresa.close();
    },

    seleccionarproductos: function(){

        var view = this.getBuscarproductosnotacredito();
        var viewIngresa = this.getNotacreditoingresar();
        //var idfactura = viewIngresa.down('#factId').getValue();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id_producto);
            var idproducto = (row.data.id_producto);
            var nomproducto = (row.data.nombre);
            var idfactura = (row.data.id_factura);
            var idp = (row.data.id);
            var idexistencia = (row.data.id_existencia);

            Ext.Ajax.request({
                    url: preurl + 'notacredito/validaproducto',
                params: {
                    idproducto: idproducto,
                    idfactura : idfactura,
                    idlinea: idp
                },
                success: function(response){
                   var resp = Ext.JSON.decode(response.responseText);                

                   if(resp.cliente){
                      var cliente = resp.cliente;
                      var canti = cliente.cantidad;
                      viewIngresa.down('#cantidadOriginalId').setValue(canti);
                      viewIngresa.down('#productoId').setValue(idproducto);
                      viewIngresa.down('#nomproductoId').setValue(nomproducto);
                      viewIngresa.down('#codigoId').setValue(row.data.codigo);
                      viewIngresa.down('#precioId').setValue(row.data.p_venta);
                      viewIngresa.down('#factactId').setValue(row.data.id_factura);
                      viewIngresa.down('#pId').setValue(row.data.id);
                      viewIngresa.down('#idext').setValue(row.data.id_existencia);
                      view.close();
                    }else{
                      viewIngresa.down('#productoId').setValue(idproducto);
                      viewIngresa.down('#pId').setValue(id);
                      viewIngresa.down('#idext').setValue(id_existencia);
                      viewIngresa.down('#nomproductoId').setValue(nomproducto);
                      viewIngresa.down('#codigoId').setValue(row.data.codigo);
                      viewIngresa.down('#precioId').setValue(row.data.p_venta);
                      viewIngresa.down('#factactId').setValue(row.data.id_factura);
                      view.close();                        
                    }

                   if (resp.success == false) {

                    cero="";
                    cero2=0;
                    viewIngresa.down('#codigoId').setValue(cero);
                    viewIngresa.down('#productoId').setValue(cero);
                    viewIngresa.down('#cantidadId').setValue(cero2);
                    viewIngresa.down('#precioId').setValue(cero2);
                    viewIngresa.down('#cantidadOriginalId').setValue(cero);
                    viewIngresa.down("#buscarproc").focus();

                    Ext.Msg.alert('Alerta', 'Producto No corresponde a Factura');
                    return false;
                    

                   }
                               
                    }
                });
           
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

     buscarp: function(){
        var view = this.getBuscarproductosnotacredito();
        var st = this.getNotacreditopStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {codigo : nombre}
        st.load();
    },
  
});










