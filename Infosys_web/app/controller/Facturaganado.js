Ext.define('Infosys_web.controller.Facturaganado', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['facturaganado.Items',
             'Clientes',
             'Factura',
             'Productosf',
             'Tipo_documento',
             'Guiasdespachopendientes3',
             'Guiasdespachopendientes4',
             'Sucursales_clientes',
             'Tipo_documento.Selector4'],

    models: ['facturaganado.Item',
             'Tipo_documento',
             'Guiasdespacho',
             'Sucursales_clientes'],

    views: ['facturaganado.Facturaganado',
            'facturaganado.BuscarClientes',
            'facturaganado.BuscarSucursales',
            'facturaganado.BuscarProductos',
            'facturaganado.Selecionadetalle',
            'facturaganado.BuscarGuias',
            'ventas.Principalfactura',
            'facturaganado.Observaciones',
            'facturaganado.Cobrosadic'],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
       ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'facturaganado',
        selector: 'facturaganado'
    },{
        ref: 'facturaganadoclientes',
        selector: 'facturaganadoclientes'
    },{
        ref: 'buscarsucursalesclientesfacturaganado',
        selector: 'buscarsucursalesclientesfacturaganado'
    },{
        ref: 'facturasprincipal',
        selector: 'facturasprincipal'
    },{
        ref: 'buscarproductosganado',
        selector: 'buscarproductosganado'
    },{
        ref: 'observacionesfacturasganado',
        selector: 'observacionesfacturasganado'
    },{
        ref: 'cobrosadicfacturasganado',
        selector: 'cobrosadicfacturasganado'
    },{
        ref: 'buscarguiasganado',
        selector: 'buscarguiasganado'
    },{
        ref: 'selecionadetalle',
        selector: 'selecionadetalle'
    }

    ],
    //init es lo primero que se ejecuta en el controller
    //especia de constructor
    init: function() {
    	//el <<control>> es el puente entre la vista y funciones internas
    	//del controller
        this.control({

            'facturaganado #rutId': {
                specialkey: this.special
            },
            'facturaganado #numfactId': {
                specialkey: this.special2
            },
            'facturasprincipal button[action=mfacturaganado]': {
                click: this.mfacturaganado
            },                       
            'facturaganado button[action=facturaganadobuscarclientes]': {
                click: this.facturaganadobuscarclientes
            },
            'facturaganado button[action=buscarfactura]': {
                click: this.buscarfactura
            },
            'facturaganado button[action=buscarsucursalfacturaganado]': {
                click: this.buscarsucursalfacturaganado
            },
            'facturaganado button[action=buscarvendedor]': {
                click: this.buscarvendedor
            },
            'facturaganado button[action=buscarproductos]': {
                click: this.buscarproductos
            },
            'facturaganado #nombreId': {
                click: this.special
            },
            'facturaganado #netoId': {
                specialkey: this.calculaiva
            },
            'facturaganado #precioId': {
                specialkey: this.calculaneto
            },
            'facturaganado button[action=validarut]': {
                click: this.validarut
            },
            'facturaganado button[action=grabarfacturaganado]': {
                click: this.grabarfacturaganado
            },
            'facturaganadoclientes button[action=buscar]': {
                click: this.buscar
            },
            'facturaganadoclientes button[action=seleccionarcliente]': {
                click: this.seleccionarcliente
            },
            'buscarsucursalesclientesfacturaganado button[action=seleccionarsucursalganado]': {
                click: this.seleccionarsucursalganado
            },
            'facturaganado #tipocondpagoId': {
                select: this.selecttipocondpago                
            },
            'facturaganado #fechafacturaId': {
                select: this.selecttipocondpago
            },
            'facturaganado button[action=agregarItem]': {
                click: this.agregarItem
            }, 
            'facturaganado button[action=eliminaritem]': {
                click: this.eliminaritem
            },
            'facturaganado #tipoDocumentoId': {
                select: this.selectItemdocuemento
            },
            'buscarproductosganado button[action=seleccionarproductos]': {
                click: this.seleccionarproductos
            },
            'buscarproductosganado button[action=buscar]': {
                click: this.buscarp
            },
            'facturaganado button[action=observaciones]': {
                click: this.observaciones
            },
            'facturaganado button[action=cobrosadic]': {
                click: this.cobrosadic
            },            
            'observacionesfacturasganado button[action=ingresaobs]': {
                click: this.ingresaobs
            },

            'cobrosadicfacturasganado button[action=ingresacobadic]': {
                click: this.ingresacobadic
            },


            'observacionesfacturasganado #rutId': {
                specialkey: this.special6
            },
            'observacionesfacturasganado button[action=validar]': {
                click: this.validarut2
            },
            'buscarguiasganado button[action=buscarguiasdespacho]': {
                click: this.buscarguiasganado2
            },
            'buscarguiasganado button[action=seleccionarguias]': {
                click: this.seleccionarguias
            },
            'facturaganado button[action=buscarguias]': {
                click: this.buscarguiasganado
            },
            'selecionadetalle button[action=seleccionartodas]': {
                click: this.seleccionartodas
            },
            'facturaganado button[action=EditarItem]': {
                click: this.editaritem
            },
            'facturaganado button[action=cancelar]': {
                click: this.cancelar
            },
            
        });
    },

    cancelar: function(){

        var viewIngresa = this.getFacturaganado();
        var view = this.getFacturasprincipal();
        var idbodega = view.down('#bodegaId').getValue();
        var documento = viewIngresa.down('#tipoDocumentoId').getValue();
        var numero = viewIngresa.down('#numfacturaId').getValue();
        var folio = viewIngresa.down('#idfolio').getValue();       

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

     editaritem: function() {

        var view = this.getFacturaganado();
        var grid  = view.down('#itemsgridId');
        var cero = "";
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_producto = row.data.id_producto;
            var codigo = row.data.codigo;
            var nombre = row.data.nombre;
            var cantidad = row.data.cantidad;
            var precio = row.data.precio;
            var kilos = row.data.kilos;
            view.down('#precioId').setValue(precio);
            view.down('#productoId').setValue(id_producto);
            view.down('#nombreproductoId').setValue(nombre);
            view.down('#codigoId').setValue(codigo);
            view.down('#kilosId').setValue(kilos);
            view.down('#cantidadOriginalId').setValue(cantidad);
            view.down('#cantidadId').setValue(cantidad);
            
        grid.getStore().remove(row);
        this.recalcularFinal();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

     seleccionartodas : function() {

        var stItem1 = this.getGuiasdespachopendientes4Store();
        var stItem = this.getFacturaganadoItemsStore();
        var view = this.getSelecionadetalle();
        var viewIngresa = this.getFacturaganado();

        var totalfin = viewIngresa.down('#finaltotalpostId').getValue();
        var netofin = viewIngresa.down('#finalafectoId').getValue();
        var ivafin = viewIngresa.down('#finaltotalivaId').getValue();
        var totalfactura = viewIngresa.down('#totalId').getValue();
        
        
        stItem1.each(function(r){
            //console.log(r.data);
            producto = r.data.id_producto,
           // nomproducto = r.data.nombre_producto,
            nomproducto = "Guia Nro. " + r.data.num_factura, 
            precio = r.data.precios,
            cantidad = r.data.cantidad,
            kilos = r.data.kilos,
            neto = 0, //r.data.neto,
            total = 0, //r.data.total,
            iva = 0,//r.data.iva,
            codigo = r.data.codigo,
             
            stItem.add(new Infosys_web.model.facturaganado.Item({
                id_producto: producto,
                nombre: nomproducto,
                codigo: codigo,
                precio: precio,
                cantidad: cantidad,
                kilos: kilos,
                neto: neto,
                total: total,
                iva: iva,
            }));

            totalfin = totalfin + parseInt(total),
            netofin = netofin + parseInt(neto),
            ivafin = ivafin + parseInt(iva)
                      
        });

        
        viewIngresa.down('#finaltotalId').setValue(Ext.util.Format.number(totalfin, '0,000'));
        viewIngresa.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalfin, '0'));
        viewIngresa.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netofin, '0'));
        viewIngresa.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivafin, '0'));
        viewIngresa.down('#finalafectoId').setValue(Ext.util.Format.number(netofin, '0'));
          
        view.close();
     },



    buscarguiasganado : function() {

       var busca = this.getFacturaganado()
       var nombre = busca.down('#id_cliente').getValue();
       var bodega = busca.down('#bodegaId').getValue();
       var opcion = "Id";
       if (nombre){
          var st = this.getGuiasdespachopendientes3Store();          
          var edit =  Ext.create('Infosys_web.view.facturaganado.BuscarGuias').show();
          edit.down('#clienteId').setValue(nombre);
          st.proxy.extraParams = {nombre : nombre,
                                  opcion : opcion,
                                  idbodega : bodega};
          st.load();
       }else {
          Ext.Msg.alert('Alerta', 'Debe seleccionar Cliente.');
            return;
       }

    },

    seleccionarguias: function(){

        var view = this.getBuscarguiasganado();
        var viewIngresa = this.getFacturaganado();
        var grid  = view.down('grid');
        var st = this.getGuiasdespachopendientes4Store();        
                    
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var numguia = row.data.num_factura;
            viewIngresa.down("#guiasdespachoId").setValue(numguia);
            viewIngresa.down("#idguiasdespachoId").setValue(row.data.id);
            Ext.Ajax.request({
            url: preurl +'guias/edita/?guidespacho=' + row.data.id,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {                    
                    var idfactura = resp.idfactura;
                    //var idfactura = (cliente.id_factura);
                    console.log(idfactura);
                    var edit =  Ext.create('Infosys_web.view.facturaganado.Selecionadetalle').show();
                    st.proxy.extraParams = {factura : idfactura};
                    st.load();  
                    view.close();                                            

            }
        }
            
        });       
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },   

     buscarguiasganado2 : function() {

       var busca = this.getBuscarguiasganado()
       var id_cliente = busca.down('#clienteId').getValue();
       var nombre = busca.down('#nombreId').getValue();
       var opcion = "Numero";
       if (nombre){
          var st = this.getGuiasdespachopendientes3Store();          
          st.proxy.extraParams = {nombre : nombre,
                                  opcion : opcion,
                                  idcliente: id_cliente};
          st.load();
       }

    },

    special6: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut2()
        }
    },

    observaciones: function(){

        var viewIngresa = this.getFacturaganado();
        var idobserva = viewIngresa.down('#obsId').getValue();
        var observatex = viewIngresa.down('#observaId').getValue();
        if(!idobserva){
        var numfactura = viewIngresa.down('#numfacturaId').getValue();
        var view = Ext.create('Infosys_web.view.facturaganado.Observaciones').show();
        view.down("#rutId").focus();
        view.down("#FactId").setValue(numfactura);
        }else{
            Ext.Ajax.request({
            url: preurl + 'facturasvizualiza/leeobserva',
            params: {
                observa : idobserva
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var observa = resp.observa;         
                var view = Ext.create('Infosys_web.view.facturaganado.Observaciones').show();
        
                view.down("#rutmId").setValue(observa.rut);
                view.down("#rutId").setValue(observa.rutm);
                view.down("#nombreId").setValue(observa.nombre);
                view.down("#camionId").setValue(observa.pat_camion);  
                view.down("#carroId").setValue(observa.pat_carro);  
                view.down("#fonoId").setValue(observa.fono);
                view.down("#observaId").setValue(observatex);               

            }
           
        });
       }
    },
           




    cobrosadic: function(){

        var viewIngresa = this.getFacturaganado();
        var view = Ext.create('Infosys_web.view.facturaganado.Cobrosadic').show();
        var mcomisionganado = viewIngresa.down('#mcomisionganado').getValue();
        var mcostomayorplazo = viewIngresa.down('#mcostomayorplazo').getValue();
        var motroscargos = viewIngresa.down('#motroscargos').getValue();

        view.down('#comisionganado').setValue(mcomisionganado);
        view.down('#costomayorplazo').setValue(mcostomayorplazo);
        view.down('#otroscargos').setValue(motroscargos);
    },
            




ingresacobadic: function(){

        //console.log('llega');
        //console.log(this.getCobrosadicfacturasganado());
        var view = this.getCobrosadicfacturasganado();
        var viewIngresa = this.getFacturaganado();

        var comisionganado = view.down('#comisionganado').getValue();
        var costomayorplazo = view.down('#costomayorplazo').getValue();
        var otroscargos = view.down('#otroscargos').getValue();


        viewIngresa.down('#mcomisionganado').setValue(comisionganado);
        viewIngresa.down('#mcostomayorplazo').setValue(costomayorplazo);
        viewIngresa.down('#motroscargos').setValue(otroscargos);

        view.close()

        this.recalcularFinal();


    },

    ingresaobs: function(){

        var view = this.getObservacionesfacturasganado();
        var viewIngresar = this.getFacturaganado();                
        var rut = view.down('#rutmId').getValue();
        var nombre = view.down('#nombreId').getValue();
        var camion = view.down('#camionId').getValue();
        var fono = view.down('#fonoId').getValue();
        var carro = view.down('#carroId').getValue();
        var observa = view.down('#observaId').getValue();
        var valida = view.down('#validaId').getValue();
        var numero = view.down('#FactId').getValue();      
        
        var permite = "SI"

        if (valida == "NO"){
             Ext.Msg.alert('Alerta', 'Debe Validar Rut');
                 return;
        };        
        
        if (!rut){
             Ext.Msg.alert('Alerta', 'Debe Ingresar Rut');
                 return;
        };
        if (!nombre){
             Ext.Msg.alert('Alerta', 'Debe Ingresar Nombre');
                 return;
        };       
       
        Ext.Ajax.request({
            url: preurl + 'facturasvizualiza/saveobserva',
            params: {
                rut: rut,
                nombre: nombre,
                camion: camion,
                carro : carro,
                fono : fono,
                observa : observa,
                numero: numero
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idobserva = resp.idobserva;         
                view.close();
                viewIngresar.down("#observaId").setValue(observa);
                viewIngresar.down("#permiteId").setValue(permite);
                viewIngresar.down("#obsId").setValue(idobserva);               

            }
           
        });
    },

    validarut2: function(){

        var view = this.getObservacionesfacturasganado();
        var rut = view.down('#rutId').getValue();
        var okey = "SI";
        var cero = " ";
        
        if (!rut){
             Ext.Msg.alert('Alerta', 'Debe Ingresar Rut');
                 return;
        };

        Ext.Ajax.request({
            url: preurl + 'facturasvizualiza/validaRut?valida='+rut,
            params: {
                id: 1
            },
            
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {
                    var rutm = resp.rut;
                    if (resp.existe == true){
                        var observa = resp.observa;
                        if (observa){
                         view.down("#nombreId").setValue(observa.nombre);
                         view.down("#rutId").setValue(observa.rut);
                         view.down("#rutmId").setValue(rut);
                         view.down("#camionId").setValue(observa.pat_camion);
                         view.down("#carroId").setValue(observa.pat_carro);
                         view.down("#fonoId").setValue(observa.fono);
                         view.down("#validaId").setValue(okey);
                         view.down("#observaId").focus();
                    }             
                    };
                    if (resp.existe == false){
                        view.down("#nombreId").focus();
                         view.down("#rutId").setValue(rutm);
                         view.down("#rutmId").setValue(rut);
                         view.down("#validaId").setValue(okey);
                    }  
                    
                }else{

                      Ext.Msg.alert('Informacion', 'Rut Incorrecto');                      
                      return false;
                     
                      
                }
               
            }

        });
    },

    seleccionarproductos: function(){

        var view = this.getBuscarproductosganado();
        var viewIngresa = this.getFacturaganado();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id);
            viewIngresa.down('#nombreproductoId').setValue(row.data.nombre);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);
            viewIngresa.down('#precioId').setValue(row.data.p_venta);
            viewIngresa.down('#cantidadOriginalId').setValue(row.data.stock);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    buscarp: function(){
        var view = this.getBuscarproductosganado();
        var st = this.getProductosfStore();
        var familia = 4;
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                familia : familia}
        st.load();
    },

    buscarproductos: function(){

        var st = this.getProductosfStore();
        var familia = 4;
        var opcion = "Todos";
        Ext.create('Infosys_web.view.facturaganado.BuscarProductos').show();
        st.proxy.extraParams = {opcion :opcion,
                                familia : familia}
        st.load();
    },

    validaboleta: function(){

        var view =this.getFacturaganado();
        var rut = view.down('#rutId').getValue();
        var cero = "";
        var cero1 = "";
        
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
                        view.down("#tipocondpagoId").setValue(cliente.id_pago)
                        view.down("#rutId").setValue(rut)
                        view.down('#finaltotalId').setValue(Ext.util.Format.number(cero, '0,000'));
                        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(cero1, '0'));
                        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(cero1, '0'));
                        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(cero1, '0'));
                        view.down('#finalafectoId').setValue(Ext.util.Format.number(cero1, '0'));                      
                    }
                    
                }
            }

        });       
       
    },

    selectItemdocuemento: function() {
        
        var view =this.getFacturaganado();        
        var tipo_documento = view.down('#tipoDocumentoId');
        var fecha_factura = view.down('#fechafacturaId').getSubmitValue(); 
        var stCombo = tipo_documento.getStore();
        var record = stCombo.findRecord('id', tipo_documento.getValue()).data;
        var nombre = (record.id);
        var bolEnable = true;
        var bolDisable = false;    

        if (nombre == 105){            
            view.down('#guiasdespacho2Id').setDisabled(bolEnable);
        }else{
            view.down('#guiasdespacho2Id').setDisabled(bolDisable);            
        };          
        habilita = false;
        if(nombre == 101 || nombre == 103 || nombre == 105 || nombre == 107 ){ // FACTURA ELECTRONICA o FACTURA EXENTA

            // se valida que exista certificado
            response_certificado = Ext.Ajax.request({
            async: false,
            url: preurl + 'facturas/existe_certificado/'});

            var obj_certificado = Ext.decode(response_certificado.responseText);

            if(obj_certificado.existe == true){

                response_folio = Ext.Ajax.request({
                async: false,
                url: preurl + 'facturas/folio_documento_electronico/'+nombre});  
                var obj_folio = Ext.decode(response_folio.responseText);               
                nuevo_folio = obj_folio.folio;
                fecha_venc = obj_folio.fecha_venc;
                id_folio = obj_folio.idfolio; 
                valida = obj_folio.valida;
                console.log(valida);
                if (valida == "SI"){

                    view.close();
                    Ext.Msg.alert('Atención','Folios Vencidos');
                    return;
                    
                }else{
                     
                if(nuevo_folio != 0){
                    view.down('#numfacturaId').setValue(nuevo_folio);  
                    view.down('#idfolio').setValue(id_folio); 
                    habilita = true;
                }else{
                    Ext.Msg.alert('Atención','No existen folios disponibles');
                    view.down('#numfacturaId').setValue(''); 
                    view.close();
                    return;
                }
                }

               

            }else{
                    Ext.Msg.alert('Atención','No se ha cargado certificado');
                    view.down('#numfacturaId').setValue('');  
            }


        }
        

    },

    calculaneto: function(){

        var view = this.getFacturaganado();
        var precio = view.down('#precioId').getValue();
        var kilos = view.down('#kilosId').getValue();
        var neto = (parseInt(precio * kilos));
        view.down('#netoId').setValue(neto);
        this.calculaiva();
       
    },

           
    calculaiva: function(){

        var view = this.getFacturaganado();
        var tipo_documento = view.down('#tipoDocumentoId').getValue();
        if (tipo_documento == 19 ){
            var iva = 0;
            var neto = view.down('#netoId').getValue();
            view.down('#totalId').setValue(neto);
            view.down('#ivaId').setValue(iva);
        }else if (tipo_documento == 2 ){
            
            var iva = 0;
            var neto = view.down('#netoId').getValue();
            view.down('#totalId').setValue(neto);
            view.down('#ivaId').setValue(iva);

        }else{
        var neto = view.down('#netoId').getValue();
        var iva = (parseInt((neto * 19) / 100));
        var total = (neto + iva);
        view.down('#ivaId').setValue(iva);
        view.down('#totalId').setValue(total);
        };
    },

    recalcularFinal: function(){
        var view = this.getFacturaganado();
        var stItem = this.getFacturaganadoItemsStore();
        var pretotal = 0;
        var total = 0;
        
        stItem.each(function(r){
            pretotal = (parseInt(pretotal) + parseInt(r.data.neto))
          
        });

        mcomisionganado = view.down('#mcomisionganado').getValue();
        mcostomayorplazo = view.down('#mcostomayorplazo').getValue();
        motroscargos = view.down('#motroscargos').getValue();

        /*var val_comisionganado = parseInt(pretotal * (mcomisionganado / 100));
        var val_netocomision = pretotal + val_comisionganado;




        var val_costomayorplazo = parseInt(val_netocomision * (mcostomayorplazo / 100));

        var val_netocomisioncosto = val_netocomision + val_costomayorplazo;


        var val_netocomisioncostoiva = parseInt(val_netocomisioncosto * 1.19);

        var val_otroscargos = parseInt(val_netocomisioncostoiva * (motroscargos / 100));

        neto = pretotal;
        pretotal = pretotal + val_comisionganado + val_costomayorplazo + val_otroscargos;*/

        var val_comisionganado = parseInt(pretotal * (mcomisionganado / 100));
        var val_netocomision = pretotal + val_comisionganado;

        var val_netocomisioniva = parseInt(val_netocomision * 1.19);

        var val_otroscargos = parseInt(val_netocomisioniva * (motroscargos / 100));


        var val_netocomisioncargos = val_netocomision + val_otroscargos;
        var val_netocomisioncargosiva = parseInt(val_netocomisioncargos * 1.19);

        var val_costomayorplazo = parseInt(val_netocomisioncargosiva * (mcostomayorplazo / 100));

        neto = pretotal;
        pretotal = pretotal + val_comisionganado + val_costomayorplazo + val_otroscargos;


        total = (pretotal * 1.19);
        afecto = pretotal;
        iva = total - pretotal;
        
        //iva = (total - afecto);
        view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));


        view.down('#finalcomisionganadoId').setValue(Ext.util.Format.number(val_comisionganado, '0'));
        view.down('#finalcostomayorplazoId').setValue(Ext.util.Format.number(val_costomayorplazo, '0'));
        view.down('#finalotroscargosId').setValue(Ext.util.Format.number(val_otroscargos, '0'));
          
    },

    changedctofinal: function(){
        this.recalcularFinal();
    },


    agregarItem: function() {

        var view = this.getFacturaganado();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var stItem = this.getFacturaganadoItemsStore();
        var producto = view.down('#productoId').getValue();
        var nombre = view.down('#nombreproductoId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var kilos = ((view.down('#kilosId').getValue()));
        var neto = (view.down('#netoId').getValue());
        var bolEnable = true;
        var st = this.getProductosfStore();
        
        if (tipo_documento.getValue() == 2){
             var neto = ((kilos * precio));
             var iva = 0;
             var total = neto;

        }else{
        
        var tot = (parseInt(neto * 1.19));
        var exists = 0;
        var iva = (tot - neto );
        var total = ((neto + iva ));

        };

        
        if(!producto){
            
            Ext.Msg.alert('Alerta', 'Debe Seleccionar un Producto');
            return false;

        }

        if(precio==0){

            Ext.Msg.alert('Alerta', 'Debe Ingresar Precio Producto');
            return false;
            

        }

        if(kilos==0){

            Ext.Msg.alert('Alerta', 'Debe Ingresar Kilos Producto');
            return false;
            

        }

        if(cantidad>cantidadori){

            Ext.Msg.alert('Alerta', 'Cantidad Ingresada de Productos Supera El Stock');
            return false;
            

        }

        if(cantidad==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Cantidad.');
            return false;
        }

        
        if(rut.length==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Datos a la Factura.');
            return false;
           
        }
       
                
        stItem.add(new Infosys_web.model.facturaganado.Item({
            id_producto: producto,
            nombre: nombre,
            precio: precio,
            cantidad: cantidad,
            kilos: kilos,
            neto: neto,
            total: total,
            iva: iva,
        }));
        this.recalcularFinal();

        cero="";
        cero1=0;
        cero2=1;
        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#nombreproductoId').setValue(cero);
        view.down('#cantidadId').setValue(cero2);
        view.down('#precioId').setValue(cero);
        view.down('#cantidadOriginalId').setValue(cero);
        view.down('#kilosId').setValue(cero);
        view.down('#netoId').setValue(cero);
        view.down("#buscarproc").focus();
    },

    eliminaritem: function() {
        var view = this.getFacturaganado();
        var total = view.down('#finaltotalpostId').getValue();
        var neto = view.down('#finaltotalnetoId').getValue();
        var iva = view.down('#finaltotalivaId').getValue();
        var grid  = view.down('#itemsgridId');
        var st = this.getProductosfStore();
       
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var total = (parseInt(total) - parseInt(row.data.total));
            var neto = (parseInt(neto) - parseInt(row.data.neto));
            var iva = (parseInt(iva) - parseInt(row.data.iva));
            var afecto = neto;
            view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
            view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
            view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
            view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
            view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));

            grid.getStore().remove(row);

            var producto = (row.data.id_producto);
            var cantidad = (row.data.cantidad);           


            Ext.Ajax.request({
            url: preurl + 'facturaganado/agregaproducto',
            params: {
                idproducto: producto,
                cantidad: cantidad
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                st.load();
                
            }
           
        });

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
  
    
    selecttipocondpago: function() {
        
        var view =this.getFacturaganado();
        var condicion = view.down('#tipocondpagoId').getValue();
        var fechafactura = view.down('#fechafacturaId').getValue();

        if (!condicion){

            Ext.Msg.alert('Alerta', 'Selecciona Forma de Pago.');
            return;
            
        }else{
        
        var condicion = view.down('#tipocondpagoId');
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

        };
       
            
    },

    seleccionarsucursalganado: function(){

        var view = this.getBuscarsucursalesclientesfacturaganado();
        var viewIngresa = this.getFacturaganado();
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

        var view = this.getFacturaganadoclientes()
        var st = this.getClientesStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : "Nombre"}
        st.load();
    },

    buscarsucursalfacturaganado: function(){

       var busca = this.getFacturaganado()
       var nombre = busca.down('#id_cliente').getValue();
       
       if (nombre){
         var edit = Ext.create('Infosys_web.view.facturaganado.BuscarSucursales').show();
          var st = this.getSucursales_clientesStore();
          st.proxy.extraParams = {nombre : nombre};
          st.load();
       }else {
          Ext.Msg.alert('Alerta', 'Debe seleccionar Cliente.');
            return;
       }
      
    },

    seleccionarcliente: function(){

        var view = this.getFacturaganadoclientes();
        var viewIngresa = this.getFacturaganado();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
             var estado = (row.data.estado);
            if (estado == 3) {
                Ext.Msg.alert('Cliente Bloqueado');
                view.close();
                return;                  
            }else if (estado == 4){
                 Ext.Msg.alert('Cliente protestos Vigentes');
                 view.close();
            return;
            }else {
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

        };
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        };

              
    },
           
    grabarfacturaganado : function() {

        var viewIngresa = this.getFacturaganado();
        var view = this.getFacturasprincipal();
        var idbodega = view.down('#bodegaId').getValue();
        var tipo_documento = viewIngresa.down('#tipoDocumentoId').getValue();
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var observa = viewIngresa.down('#observaId').getValue();
        var idtipo= viewIngresa.down('#tipoDocumentoId').getValue();
        var idsucursal= viewIngresa.down('#id_sucursalID').getValue();
        var idcondventa= viewIngresa.down('#tipocondpagoId').getValue();
        var guiadespacho= viewIngresa.down('#guiasdespachoId').getValue();
        var idguiadespacho= viewIngresa.down('#idguiasdespachoId').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId').getValue();
        var numdocumento = viewIngresa.down('#numfacturaId').getValue();
        var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
        var fechavenc = viewIngresa.down('#fechavencId').getValue();

        //COBROS ADICIONALES
        var mcomisionganado = viewIngresa.down('#mcomisionganado').getValue();
        var mcostomayorplazo = viewIngresa.down('#mcostomayorplazo').getValue();
        var motroscargos = viewIngresa.down('#motroscargos').getValue();


        var valcomisionganado = viewIngresa.down('#finalcomisionganadoId').getValue();
        var valcostomayorplazo = viewIngresa.down('#finalcostomayorplazoId').getValue();
        var valotroscargos = viewIngresa.down('#finalotroscargosId').getValue();        

        var stItem = this.getFacturaganadoItemsStore();
        var stFactura = this.getFacturaStore();  

        viewIngresa.down("#grabarfactura").setDisabled(true);
        viewIngresa.down("#observaciones").setDisabled(true);
        viewIngresa.down("#cobrosadic").setDisabled(true);

        

        
        if(numdocumento==0){
            Ext.Msg.alert('Ingrese Datos a La Factura');
            return;   
        }

        if (tipo_documento != 105){
            if(!guiadespacho){
                Ext.Msg.alert('Ingrese Guias Despacho Referencial');
                return;   
            }
        };

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        var loginMask = new Ext.LoadMask(Ext.getBody(), {msg:"Generando Documento ..."});

        loginMask.show();

        Ext.Ajax.request({
            url: preurl + 'facturaganado/save',
            params: {
                idcliente: idcliente,
                numdocumento: numdocumento,
                idsucursal: idsucursal,
                idbodega: idbodega,
                idcondventa: idcondventa,
                idobserva: observa,
                idtipo: idtipo,
                ordencompra: guiadespacho,
                idguiadespacho: idguiadespacho,
                items: Ext.JSON.encode(dataItems),
                vendedor : vendedor,
                fechafactura : fechafactura,
                fechavenc: fechavenc,
                tipodocumento : tipo_documento,
                netofactura: viewIngresa.down('#finaltotalnetoId').getValue(),
                ivafactura: viewIngresa.down('#finaltotalivaId').getValue(),
                afectofactura: viewIngresa.down('#finalafectoId').getValue(),
                totalfacturas: viewIngresa.down('#finaltotalpostId').getValue(),
                mcomisionganado: mcomisionganado,
                mcostomayorplazo: mcostomayorplazo,
                motroscargos: motroscargos,
                valcomisionganado: valcomisionganado,
                valcostomayorplazo: valcostomayorplazo,
                valotroscargos: valotroscargos,                
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idfactura= resp.idfactura;
                 viewIngresa.close();
                 stFactura.load();
                  window.open(preurl + 'facturas/exportPDF/?idfactura='+idfactura);
                 //window.open(preurl + 'facturas/exportPDF/?idfactura='+idfactura);      
                 /*if (tipo_documento == 1){
                 window.open(preurl + 'facturas/exportTXTGanado/?idfactura='+idfactura);
                 };
                 if (tipo_documento == 3){
                 window.open(preurl + 'facturas/exportTXTGDGanado/?idfactura='+idfactura);
                 };*/
                 loginMask.hide();
            }
           
        });
        
        var view = this.getFacturaganado();
        var st = this.getFacturaStore();
        st.proxy.extraParams = {documento: idtipo,
                                idbodega: idbodega}
        st.load();       
        
    },

    
    validarut: function(){

        var view =this.getFacturaganado();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

       
        if(numero==0){
            var edit = Ext.create('Infosys_web.view.facturaganado.BuscarClientes');            
                  
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
                         view.down('#tipocondpagoId').setValue(cliente.id_pago);
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

    mfacturaganado: function(){

        var viewIngresa = this.getFacturasprincipal();
         var idbodega = viewIngresa.down('#bodegaId').getValue();
         if(!idbodega){
            Ext.Msg.alert('Alerta', 'Debe Elegir Bodega');
            return;    
         }else{
            var view = Ext.create('Infosys_web.view.facturaganado.Facturaganado').show();
             
         }
         view.down('#bodegaId').setValue(idbodega);

       
    },

    buscarvendedor: function(){

        Ext.create('Infosys_web.view.vendedores.BuscarVendedor').show();
    },

      
});










