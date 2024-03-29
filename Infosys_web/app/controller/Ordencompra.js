Ext.define('Infosys_web.controller.Ordencompra', {
    extend: 'Ext.app.Controller',

    stores: ['Orden_compra',
             'Orden_compratodas',
             'Proveedores',
             'Productosf',
             'Productosfo',
             'ordencompra.Items',
              'Recepcion', 'Orden_compradetalle', 
              'Ordencomprarecepcion',
              'Ordencompraforzada',
              'Contacto_clientes',
              'Orden_compradetallerec',
              'estados',
              'ordencompra.Selector',
              'Tipo_documento.Selector3',
              'Ordeneditar',
              'Tabladescuento',
              'transportista'],

    models: ['Orden_compra', 'ordencompra.Item'],

    views: [
        'ordencompra.Ingresar',
        'ordencompra.Recepcionforzada',
        'ordencompra.BusquedaProveedor',
        'ordencompra.Principal',
        'ordencompra.Principaltodas',
        'ordencompra.Principal_recepcion',
        'ordencompra.Principal_forzada',
        'TopMenus',
        'ordencompra.BuscarProductos',
        'ordencompra.Buscarcontactos',
        'ordencompra.Exportar1',
        'ordencompra.Exportar2',
        'ordencompra.Editar',
        'ordencompra.BuscarProductos2',
        'ordencompra.OrdencompraMail',
        'ordencompra.BuscarTransportista',
        'ordencompra.BuscarTransportista2',
        'ordencompra.Observaciones',
        'ordencompra.Observaciones2'
    ],

    refs: [{
        ref: 'ordencompraingresar',
        selector: 'ordencompraingresar'
    },{
        ref: 'busquedaproveedor',
        selector: 'busquedaproveedor'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{
        ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'ordencompraprincipal',
        selector: 'ordencompraprincipal'
    },{
        ref: 'ordencompraprincipalrecepcion',
        selector: 'ordencompraprincipalrecepcion'
    },{
        ref: 'ordencomprarecepcion',
        selector: 'ordencomprarecepcion'
    },{
        ref: 'ordencomprarecepcionfinal',
        selector: 'ordencomprarecepcionfinal'
    },{
        ref: 'buscarproductos',
        selector: 'buscarproductos'
    },{
        ref: 'formularioexportar',
        selector: 'formularioexportar'
    },{
        ref: 'ordencompraprincipalforzada',
        selector: 'ordencompraprincipalforzada'
    },{
        ref: 'ordencomprarecepcionforzada',
        selector: 'ordencomprarecepcionforzada'
    },{
        ref: 'buscarcontactos',
        selector: 'buscarcontactos'
    },{
        ref: 'formularioexportarordencompra',
        selector: 'formularioexportarordencompra'
    },{
        ref: 'ordencompraprincipaltodas',
        selector: 'ordencompraprincipaltodas'
    },{
        ref: 'formularioexportarordencompra2',
        selector: 'formularioexportarordencompra2'
    },{
        ref: 'ordencompraeditar',
        selector: 'ordencompraeditar'
    },{
        ref: 'buscarproductos2',
        selector: 'buscarproductos2'
    },{
        ref: 'ordencompramail',
        selector: 'ordencompramail'
    },{
        ref: 'formularioexportarordencompra3',
        selector: 'formularioexportarordencompra3'
    },{
        ref: 'buscatrans',
        selector: 'buscatrans'
    },{
        ref: 'buscatrans2',
        selector: 'buscatrans2'
    },{
        ref: 'observacionesordencompra',
        selector: 'observacionesordencompra'
    },{
        ref: 'observacionesordencompra2',
        selector: 'observacionesordencompra2'
    }


       
    ],

    init: function() {

        this.control({
        	'ordencompraingresar button[action=grabar]': {
        		click: this.grabar
        	},
            'ordencompraingresar button[action=wbuscarproveedor]': {
                click: this.wbuscarproveedor
            },
            'busquedaproveedor button[action=buscarproveedor]': {
                click: this.buscarproveedor
            },
            'busquedaproveedor button[action=seleccionarproveedor]': {
                click: this.seleccionarproveedor
            },
            'ordencompraingresar button[action=agregarItem]': {
                click: this.agregarItem
            },
            'ordencompraingresar #productoId': {
                select: this.selectItem
            },
            'ordencompraingresar button[action=eliminaritem]': {
                click: this.eliminaritem
            },
            'ordencompraeditar button[action=eliminaritem2]': {
                click: this.eliminaritem2
            },
            'ordencompraingresar #finaldescuentoId': {
                change: this.changedctofinal
            },
            'ordencompraingresar button[action=buscarproductosl]': {
                click: this.buscarproductosl
            },
            'ordencompraingresar #codigoId': {
                specialkey: this.special3
            },
            'ordencompraprincipal button[action=agregarordencompra]': {
                click: this.agregarordencompra
            },
            'ordencompraprincipal button[action=recepcionarordencompra]': {
                click: this.recepcionarordencompra
            },
            'ordencompraprincipal button[action=recepcionforzada]': {
                click: this.recepcionforzada
            },
            'ordencompraprincipalrecepcion button[action=recepcionarordencompradef]': {
                click: this.recepcionarordencompradef
            },

            'ordencompraprincipal button[action=exportarordencompra]': {
                click: this.exportarordencompra
            },

            'ordencompraprincipaltodas button[action=exportarordencompra]': {
                click: this.exportarordencompra2
            },
            'topmenus menuitem[action=mordencompra]': {
                click: this.mordencompra
            },
            'topmenus menuitem[action=rordencompra]': {
                click: this.rordencompra
            },
            'topmenus menuitem[action=mordencomprarec]': {
                click: this.mordencomprarec
            },
            'topmenus menuitem[action=mordencomprafor]': {
                click: this.mordencomprafor
            },

            'ordencomprarecepcion button[action=grabarrecepcion]': {
                click: this.grabarrecepcion
            },
            'ordencomprarecepcionforzada button[action=grabarrecepcionforzada]': {
                click: this.grabarrecepcionforzada
            },
            'ordencomprarecepcionfinal button[action=grabarrecepcionfinal]': {
                click: this.grabarrecepcionfinal
            },
            'buscarproductos button[action=seleccionarproductos2]': {
                click: this.seleccionarproductos2
            },
            'buscarproductos button[action=buscarPR]': {
                click: this.buscarPR
            },
            'buscarproductos2 button[action=buscarp3]': {
                click: this.buscarp3
            },
            
            'ordencompraprincipalrecepcion button[action=exportarordencomprarecepcion]': {
                click: this.exportarordencomprarecepcion
            },
            'formularioexportar button[action=exportarExcelrecepcion]': {
                click: this.exportarExcelrecepcion
            },
            'ordencompraprincipal button[action=cerrarordendecompra]': {
                click: this.cerrarordendecompra
            },
            'ordencompraprincipal button[action=editarorden]': {
                click: this.editarorden
            },
            'ordencompraprincipalrecepcion button[action=cerrarordendecompra]': {
                click: this.cerrarordendecompra
            },
            'ordencompraprincipalforzada button[action=cerrarordendecompraforzada]': {
                click: this.cerrarordendecompraforzada
            },
            'ordencompraprincipalrecepcion button[action=generapdf]': {
                click: this.generapdf
            },
            'ordencompraprincipalrecepcion button[action=generapdforiginal]': {
                click: this.generapdforiginal
            },
             'ordencompraingresar button[action=buscarcontactos]': {
                click: this.buscarcontactos
            },
             'buscarcontactos button[action=seleccionarcontactos]': {
                click: this.seleccionarcontactos
            },
            'ordencompraprincipal button[action=exportarexcelordencomprap]': {
                click: this.exportarexcelordencomprap
            },
            'ordencompraprincipaltodas button[action=exportarexcelordencompra]': {
                click: this.exportarexcelordencompra
            },
            'formularioexportarordencompra button[action=exportarExcelFormulariototal]': {
                click: this.exportarExcelFormulariototal
            },
            'formularioexportarordencompra2 button[action=exportarExcelFormulario2]': {
                click: this.exportarExcelFormulario2
            },
            'formularioexportarordencompra3 button[action=exportarExcelFormulario3]': {
                click: this.exportarExcelFormulario3
            },
            'ordencompraprincipal button[action=buscarorden]': {
            click: this.buscarorden
            },
            'ordencompraprincipalrecepcion button[action=buscarordenrecepcion]': {
            click: this.buscarordenrecepcion
            },
            'ordencompraprincipaltodas button[action=buscarorden]': {
            click: this.buscarorden2
            },
            'buscarproductos #nombreId': {
                specialkey: this.special2
            },
            'busquedaproveedor #bproveedornombreId': {
                specialkey: this.special4
            },
            'busquedaproveedor #bproveedorrutId': {
                specialkey: this.special4
            },
            'ordencompraeditar button[action=grabareditar]': {
                click: this.grabareditar
            },
            'ordencompraeditar button[action=editaritem2]': {
                click: this.editaritem2
            },
            'ordencompraingresar button[action=editaritem]': {
                click: this.editaritem
            },
            'ordencompraeditar button[action=agregarItem2]': {
                click: this.agregarItem2
            },
            'ordencompraeditar #codigoId': {
                specialkey: this.special5
            },
            'ordencompraeditar button[action=buscarproductos2]': {
                click: this.buscarproductos2
            },
            'buscarproductos2 button[action=seleccionarproductos3]': {
                click: this.seleccionarproductos3
            },
            'ordencompraingresar #DescuentoproId': {
                change: this.changedctofinal3
            },
            'ordencompraeditar #DescuentoproId': {
                change: this.changedctofinal4
            },
            'ordencompraingresar #tipoDescuentoId': {
                change: this.changedctofinal
            },
            'ordencompraprincipal button[action=enviaremail]': {
                click: this.enviaremail
            },
             'ordencompramail button[action=ordencompraemail]': {
                click: this.ordencompraemail
            },
             'ordencompraprincipal button[action=actualizarOrden]': {
                click: this.actualizarOrden
            },
            'ordencomprarecepcionfinal button[action=tbuscar2]': {
                click: this.tbuscar2
            },
            'buscatrans button[action=buscartran]': {
                click: this.buscartran
            },
            'buscatrans button[action=seleccionartrans]': {
                click: this.seleccionartrans
            },

            'ordencomprarecepcion button[action=tbuscar]': {
                click: this.tbuscar
            },
            'buscatrans2 button[action=buscartran2]': {
                click: this.buscartran2
            },
            'buscatrans2 button[action=seleccionartrans2]': {
                click: this.seleccionartrans2
            },
            'pedidosprincipal button[action=generafactura]': {
                click: this.generafactura
            },
            'ordencompraingresar button[action=observaciones]': {
                click: this.agregarobserva
            },
            'observacionesordencompra button[action=ingresaobs]': {
                click: this.ingresaobs
            },
             'observacionesordencompra2 button[action=ingresaobs2]': {
                click: this.ingresaobs2
            },
             'ordencompraeditar button[action=observaciones2]': {
                click: this.agregarobserva2
            },
          

          
        });
    },

    ingresaobs2: function(){

        var view = this.getObservacionesordencompra2();
        var viewIngresar = this.getOrdencompraeditar();                
        var observa = view.down('#observaId').getValue();
        
        if (!observa){
            Ext.Msg.alert('Alerta', 'Ingrese Observaciones');
            return;
        }else{
            viewIngresar.down("#obsId").setValue(observa);
            view.close();
        };
    },


    ingresaobs: function(){

        var view = this.getObservacionesordencompra();
        var viewIngresar = this.getOrdencompraingresar();                
        var observa = view.down('#observaId').getValue();
        
        if (!observa){
            Ext.Msg.alert('Alerta', 'Ingrese Observaciones');
            return;
        }else{
            viewIngresar.down("#obsId").setValue(observa);
            view.close();
        };
    },

     agregarobserva: function(){

        var viewIngresa = this.getOrdencompraingresar();
        var observa = viewIngresa.down('#obsId').getValue();
        if (!observa){
            var view = Ext.create('Infosys_web.view.ordencompra.Observaciones').show();
            //view.down("#numpedidoId").setValue(numpedidos);
        }else{
            var view = Ext.create('Infosys_web.view.ordencompra.Observaciones').show();
            view.down('#observaId').setValue(observa);
        };
       
    },

     agregarobserva2: function(){

        var viewIngresa = this.getOrdencompraeditar();
        var observa = viewIngresa.down('#obsId').getValue();
        if (!observa){
            var view = Ext.create('Infosys_web.view.ordencompra.Observaciones2').show();
            //view.down("#numpedidoId").setValue(numpedidos);
        }else{
            var view = Ext.create('Infosys_web.view.ordencompra.Observaciones2').show();
            view.down('#observaId').setValue(observa);
        };
       
    },

    seleccionartrans : function(){

        var view = this.getBuscatrans()
        var viewIngresa = this.getOrdencomprarecepcion();        
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#idtransportista').setValue(row.data.id);
            viewIngresa.down('#nombre_choferId').setValue(row.data.nombre);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    seleccionartrans2 : function(){

        var view = this.getBuscatrans2()
        var viewIngresa = this.getOrdencomprarecepcionfinal();        
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#idtransportista').setValue(row.data.id);
            viewIngresa.down('#nombre_choferId').setValue(row.data.nombre);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    buscartran : function(){

        var view = this.getBuscatrans()
        var st = this.getTransportistaStore()
        var nombre = view.down('#nombreId').getValue()
        var patente = view.down('#patenteId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                patente : patente}
        st.load();

    },

    buscartran2 : function(){

        var view = this.getBuscatrans2()
        var st = this.getTransportistaStore()
        var nombre = view.down('#nombreId').getValue()
        var patente = view.down('#patenteId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                patente : patente}
        st.load();

    },

    tbuscar: function(){
        var edit = Ext.create('Infosys_web.view.ordencompra.BuscarTransportista');        
    },

    tbuscar2: function(){
        var edit = Ext.create('Infosys_web.view.ordencompra.BuscarTransportista2');        
    },

    actualizarOrden : function(){

        Ext.Ajax.request({
            url: preurl + 'ordencompra/actualizaocompra',
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) { 

                    Ext.Msg.alert('Informacion', 'Actualizada Correctamente');
                    return;
                    
                }
            }

        });        
    },

    ordencompraemail : function(){
        
        var view =this.getOrdencompramail();
        var email = view.down('#email').getValue();
        var mensaje = view.down('#mensaje').getValue();
        var idcotiza = view.down('#idCotizaId').getValue();
       
        form   = view.down('form');
        if(!form.getForm().isValid()){
            Ext.Msg.alert('Informacion', 'Rellene todos los campos correctamente');
            return false
        }else{
          var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Enviado mail..."});
          myMask.show();
          Ext.Ajax.request({
             url: preurl + 'ordencompra/envioEmailPDF',
                  params: {
                      email: email,
                      idcotiza : idcotiza,
                      mensaje : mensaje
                  },
             success: function(response, opts) {             
                myMask.hide();
                Ext.Msg.alert('Informacion', 'El correo ha sido enviado exitosamente');
                view.close();             
                
             },
             failure: function(response, opts) {
                myMask.hide();
                console.log('server-side failure with status code ' + response.status);
             }
          });  

        }       
    },

    enviaremail: function(){

        var view = this.getOrdencompraprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var idorden = row.data.id;
            var email = row.data.mail_contacto;
            edit =   Ext.create('Infosys_web.view.ordencompra.OrdencompraMail').show();
            edit.down('#idCotizaId').setValue(idorden);
            edit.down('#email').setValue(email);
            edit.down("#mensaje").focus();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }   
      },


    recalcular: function(){

        var view = this.getOrdencompraeditar();
        var stItem = this.getOrdeneditarStore();
        var grid2 = view.down('#itemsgridId');
        var pretotal = 0;
        var total = 0;
        var iva = 0;
        var neto = 0;
        var dcto = view.down('#finaldescuentoId').getValue();

        stItem.each(function(r){
            pretotal = (parseInt(pretotal) + parseInt(r.data.total))
            iva = (parseInt(iva) + parseInt(r.data.iva))
            neto = (parseInt(neto) + parseInt(r.data.neto))
        });
        pretotalfinal = ((pretotal * dcto)  / 100);
        total = ((pretotal) - parseInt(pretotalfinal));
        afecto = neto;
        
        //iva = (total - afecto);
        view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));
        //view.down('#finalpretotalId').setValue(Ext.util.Format.number(pretotal, '0,000'));
    },

    recalculardescuento: function(){

        var view = this.getOrdencompraingresar();
        var pretotal = view.down('#finalafectoId').getValue();
        var total = view.down('#finaltotalpostId').getValue();
        var iva = view.down('#finaltotalivaId').getValue();
        var neto = view.down('#finaltotalnetoId').getValue();
        var descuento = view.down('#tipoDescuentoId');
        var stCombo = descuento.getStore();
        var record = stCombo.findRecord('id', descuento.getValue()).data;
        var dcto = (record.porcentaje);
       
        pretotalfinal = ((total * dcto)  / 100);
        total = ((total) - parseInt(pretotalfinal));
        afecto = (parseInt(total / 1.19));
        iva = (total - afecto);

        view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));
        view.down('#descuentovalorId').setValue(Ext.util.Format.number(pretotalfinal, '0'));
    },

    changedctofinal: function(){
        this.recalculardescuento();
    },

    changedctofinal3: function(){
        this.recalculardescuentopro();
    },
    changedctofinal4: function(){
        this.recalculardescuentopro2();
    },

    recalculardescuentopro2: function(){

        var view = this.getOrdencompraeditar();
        var precio = view.down('#precioId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var total = (precio * cantidad);
        var desc = view.down('#DescuentoproId').getValue();
        if (desc){
        var descuento = view.down('#DescuentoproId');
        var stCombo = descuento.getStore();
        var record = stCombo.findRecord('id', descuento.getValue()).data;
        var dcto = (record.porcentaje);
        totaldescuento = ((parseInt(total * dcto)  / 100));
        view.down('#totdescuentoId').setValue(totaldescuento);
        };      
    },

    recalculardescuentopro: function(){

        var view = this.getOrdencompraingresar();
        var precio = view.down('#precioId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var total = (precio * cantidad);
        var desc = view.down('#DescuentoproId').getValue();
        if (desc){
        var descuento = view.down('#DescuentoproId');
        var stCombo = descuento.getStore();
        var record = stCombo.findRecord('id', descuento.getValue()).data;
        var dcto = (record.porcentaje);
        totaldescuento = ((parseInt(total * dcto)  / 100));
        view.down('#totdescuentoId').setValue(totaldescuento);
        };      
    },

    seleccionarproductos3: function(){

        var view = this.getBuscarproductos2();        
        var viewIngresa = this.getOrdencompraeditar();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id);
            viewIngresa.down('#nombreproductoId').setValue(row.data.nombre);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);
            //viewIngresa.down('#precioId').setValue(row.data.p_venta);
            viewIngresa.down('#cantidadOriginalId').setValue(row.data.stock);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }   
    },

    agregarItem2: function() {

        var view = this.getOrdencompraeditar();
        var stItem = this.getOrdeneditarStore();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var producto = view.down('#productoId').getValue();
        var nombre = view.down('#nombreproductoId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var precioun = ((view.down('#precioId').getValue()));
        var descuento = view.down('#totdescuentoId').getValue(); 
        var iddescuento = view.down('#DescuentoproId').getValue();
        var bolEnable = true;
        var totalnue = view.down('#finaltotalpostId').getValue();
        var netonue = view.down('#finaltotalnetoId').getValue();
        var ivanue = view.down('#finaltotalivaId').getValue();
        var afectonue = view.down('#finalafectoId').getValue();
        
        if (descuento == 1){            
            var descuento = 0;
            var iddescuento = 0;
        };
              
        var neto = ((cantidad * precio) - descuento);
        var tot = (Math.round(neto * 1.19));
        var exists = 0;
        var iva = (tot - neto);
        var neto = (tot - iva);
        var total = ((neto + iva ));    
        
        if(!producto){            
            Ext.Msg.alert('Alerta', 'Debe Seleccionar un Producto');
            return false;
        }

        if(precio==0){

            Ext.Msg.alert('Alerta', 'Debe Ingresar Precio Producto');
            return false;
        }
        if(cantidad==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Cantidad.');
            return false;
        }        
        if(rut.length==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Datos a la Orden.');
            return false;           
        }

        cero="";
        cero1=0;
        cero2=1;

        stItem.each(function(r){
            if(r.data.id == producto){
                Ext.Msg.alert('Alerta', 'El registro ya existe.');
                exists = 1;
                view.down('#codigoId').setValue(cero);
                view.down('#productoId').setValue(cero);
                view.down('#nombreproductoId').setValue(cero);
                view.down('#cantidadId').setValue(cero2);
                view.down('#precioId').setValue(cero);
                view.down('#cantidadOriginalId').setValue(cero);
                view.down('#totdescuentoId').setValue(cero1);
                view.down('#DescuentoproId').setValue(cero);
                return; 
            }
        });
        if(exists == 1)
            return;
        
        stItem.add(new Infosys_web.model.ordencompra.Item({
            id: producto,
            id_producto: producto,
            id_descuento: iddescuento,
            nombre: nombre,
            precio: precio,
            cantidad: cantidad,
            neto: neto,
            total: total,
            iva: iva,
            dcto: descuento
        }));
        
        var totalnue = totalnue + total;
        var ivanue = ivanue + iva;
        var afectonue = afectonue + neto;
        var netonue = netonue + neto;

              
        view.down('#finaltotalId').setValue(Ext.util.Format.number(totalnue, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalnue, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netonue, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivanue, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afectonue, '0'));
        view.down('#descuentovalorId').setValue(Ext.util.Format.number(cero));
       
        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#nombreproductoId').setValue(cero);
        view.down('#cantidadId').setValue(cero2);
        view.down('#precioId').setValue(cero);
        view.down('#cantidadOriginalId').setValue(cero);
        view.down('#totdescuentoId').setValue(cero1);
        view.down('#DescuentoproId').setValue(cero);
    },

    editaritem2: function() {
        var view = this.getOrdencompraeditar();
        var grid  = view.down('#itemsgridId');
        var total = view.down('#finaltotalpostId').getValue();
        var neto = view.down('#finaltotalnetoId').getValue();
        var iva = view.down('#finaltotalivaId').getValue();
        var afecto = view.down('#finalafectoId').getValue();
        var descuento = view.down('#descuentovalorId').getValue();

        var cero = "";
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_producto = row.data.id_producto;
            var totalnue = total - (row.data.total);
            var ivanue = iva - (row.data.iva);
            var afectonue = afecto - (row.data.neto);
            var netonue = neto - (row.data.neto);
            var neto = (row.data.neto);
            console.log(neto);

            Ext.Ajax.request({
            url: preurl + 'productos/buscarp?nombre='+id_producto,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) { 
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down('#precioId').setValue(neto);
                        view.down('#productoId').setValue(row.data.id_producto);
                        view.down('#nombreproductoId').setValue(row.data.nombre);
                        view.down('#codigoId').setValue(cliente.codigo);
                        view.down('#cantidadOriginalId').setValue(cliente.stock);
                        view.down('#cantidadId').setValue(row.data.cantidad);
                        view.down('#totdescuentoId').setValue(row.data.dcto);
                        if ((row.data.id_descuento)==0){
                            view.down('#DescuentoproId').setValue(cero);
                        }else{
                            view.down('#DescuentoproId').setValue(row.data.id_descuento);
                        }
                        
                        view.down('#finaltotalId').setValue(Ext.util.Format.number(totalnue, '0,000'));
                        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalnue, '0'));
                        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netonue, '0'));
                        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivanue, '0'));
                        view.down('#finalafectoId').setValue(Ext.util.Format.number(afectonue, '0'));
                        view.down('#descuentovalorId').setValue(Ext.util.Format.number(cero));

                             
                    }
                }
            }

        });       
        grid.getStore().remove(row);
        //this.recalcularFinal2();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
       
    },

    editaritem: function() {

        var view = this.getOrdencompraingresar();
        var grid  = view.down('#itemsgridId');
        var cero = "";
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_producto = row.data.id_producto;
            var neto = (row.data.neto);
            Ext.Ajax.request({
            url: preurl + 'productos/buscarp?nombre='+id_producto,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) { 
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down('#precioId').setValue(neto);
                        view.down('#productoId').setValue(row.data.id_producto);
                        view.down('#nombreproductoId').setValue(row.data.nombre);
                        view.down('#codigoId').setValue(cliente.codigo);
                        view.down('#cantidadOriginalId').setValue(cliente.stock);
                        view.down('#cantidadId').setValue(row.data.cantidad);
                        view.down('#totdescuentoId').setValue(row.data.dcto);
                        if ((row.data.id_descuento)==0){
                            view.down('#DescuentoproId').setValue(cero);
                        }else{
                            view.down('#DescuentoproId').setValue(row.data.id_descuento);
                        }       
                    }
                }
            }

        });
        grid.getStore().remove(row);
        this.recalcularFinal();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    grabareditar: function() {

        var view = this.getOrdencompraeditar();

        var idproveedor = view.down('#idproveedor').getValue();
        var neto = view.down('#finaltotalnetoId').getValue();
        var iva = view.down('#finaltotalivaId').getValue();
        var total = view.down('#finaltotalpostId').getValue();
        var afecto = view.down('#finalafectoId').getValue();
        var observacion = view.down('#obsId').getValue();
        var descuentofinal = view.down('#descuentovalorId').getValue();
        var stItem = this.getOrdeneditarStore();
        var stCotiz = this.getOrden_compraStore();
        var idId = view.down('#idId').getValue();
        
        if (!idproveedor){
            
             Ext.Msg.alert('Debe Ingresar Datos del Proveedor');
             return;

        }

        var dataproveedor = {
            mail_contacto: view.down('#mail_contactoId').getValue(),
            nombre_contacto: view.down('#nombre_contactoId').getValue(),
            telefono_contacto: view.down('#fono_contactoId').getValue(),
        };

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        if (!total){
            
             Ext.Msg.alert('Debe Ingresar Productos');
             return;

        }

        Ext.Ajax.request({
            url: preurl + 'ordencompra/save2',
            params: {
                idproveedor: idproveedor,
                id_orden: idId,
                dataproveedor: Ext.JSON.encode(dataproveedor),
                items: Ext.JSON.encode(dataItems),
                afecto : afecto,
                observacion : observacion,
                neto: neto,
                iva: iva,
                descuento: descuentofinal,
                total: view.down('#finaltotalpostId').getValue()
            },
            success: function(response){
                var text = response.responseText;
                Ext.Msg.alert('Informacion', 'Creada Exitosamente.');
                view.close();
                stCotiz.load();
            }
        });

    },

    editarorden: function(){

        var stItms = Ext.getStore('Ordeneditar');
        stItms.removeAll();
       
        var view = this.getOrdencompraprincipal();       
                   
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var view = this.getOrdencompraeditar();
            var stItem = this.getOrdeneditarStore();
            var idorden = row.data.id;
            var neto = row.data.neto;
            var iva = row.data.iva;
            var total = row.data.total;
            var descuento = row.data.descuento;
            var afecto = row.data.afecto;
            var cero = 0;
            stItem.proxy.extraParams = {idorden : idorden};
            stItem.load();
           
            Ext.Ajax.request({
            url: preurl +'ordencompra/edita/?idorden=' + row.data.id,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {                    
                    var view = Ext.create('Infosys_web.view.ordencompra.Editar').show();                   
                    var cliente = resp.cliente; 
                    view.down('#idproveedor').setValue(cliente.id_proveedor);
                    view.down('#idId').setValue(cliente.id);
                    view.down('#nombreId').setValue(cliente.empresa);
                    view.down('#fechaordenId').setValue(cliente.fecha);
                    view.down('#num_ordenId').setValue(cliente.num_orden);                    
                    view.down('#direccionId').setValue(cliente.direccion);
                    view.down('#rutId').setValue(cliente.rut);
                    view.down('#fechaordenId').setValue(cliente.fecha);                    
                    view.down('#giroId').setValue(cliente.id_giro);                    
                    view.down('#nom_giroId').setValue(cliente.nombre_giro);
                    view.down('#fonoId').setValue(cliente.fono);
                    view.down('#nombre_contactoId').setValue(cliente.nombre_contacto);
                    view.down('#fono_contactoId').setValue(cliente.telefono_contacto);
                    view.down('#mail_contactoId').setValue(cliente.mail_contacto);
                    view.down('#obsId').setValue(cliente.observacion);
                    
                    var total = (cliente.total);
                    var neto = (cliente.neto);
                    var iva = (cliente.total - cliente.neto);
                    view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
                    view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
                    view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
                    view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
                    view.down('#finalafectoId').setValue(Ext.util.Format.number(neto, '0'));
                    view.down('#descuentovalorId').setValue(Ext.util.Format.number(cliente.desc, '0'));
                    //view.down('#observacionesId').setValue(cliente.observaciones);
                                                       
                }
            }
        });

        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }       
    },

     buscarproductosl: function(){

        var viewIngresa = this.getOrdencompraingresar();
        var codigo = viewIngresa.down('#codigoId').getValue();

        if (!codigo){
            var st = this.getProductosfoStore()
            st.load();
            var view = Ext.create('Infosys_web.view.ordencompra.BuscarProductos').show();
            view.down("#nombreId").focus();
        }else{

            Ext.Ajax.request({
            url: preurl + 'productos/buscacodigo?codigo='+codigo,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cero = "";
                if (resp.success == true){                    
                    if(resp.cliente){
                        var cliente = resp.cliente;                        
                        viewIngresa.down('#productoId').setValue(cliente.id);
                        viewIngresa.down('#nombreproductoId').setValue(cliente.nombre);
                        viewIngresa.down('#codigoId').setValue(codigo);
                        viewIngresa.down('#uloteId').setValue(cliente.u_lote);
                        viewIngresa.down('#cantidadOriginalId').setValue(cliente.stock);
                        viewIngresa.down('#ultimoloteId').setValue(cliente.u_lote);
                        viewIngresa.down("#precioId").focus();
                                             
                    }
                }else{

                      var view = Ext.create('Infosys_web.view.ordencompra.BuscarProductos').show();
                      view.down("#nombreId").setValue(codigo);
                      
                }

              
            }

        });           

        }

        
       


    },

    buscarproductos2: function(){

        var viewIngresa = this.getOrdencompraeditar();
        var codigo = viewIngresa.down('#codigoId').getValue();

        if (!codigo){
            var st = this.getProductosfoStore()
            st.load();
            var view = Ext.create('Infosys_web.view.ordencompra.BuscarProductos2').show();
            view.down("#nombreId").focus();
        }else{

            Ext.Ajax.request({
            url: preurl + 'productos/buscacodigo?codigo='+codigo,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cero = "";
                if (resp.success == true){                    
                    if(resp.cliente){
                        var cliente = resp.cliente;                        
                        viewIngresa.down('#productoId').setValue(cliente.id);
                        viewIngresa.down('#nombreproductoId').setValue(cliente.nombre);
                        viewIngresa.down('#codigoId').setValue(codigo);
                        viewIngresa.down('#cantidadOriginalId').setValue(cliente.stock);
                        viewIngresa.down("#precioId").focus();
                                             
                    }
                }else{

                      var view = Ext.create('Infosys_web.view.ordencompra.BuscarProductos2').show();
                      view.down("#nombreId").setValue(codigo);
                      
                }

              
            }

        });           

        }

        
       


    },


    buscarproductos: function(){


        var st = this.getProductosfoStore()
        st.load();
        var view = Ext.create('Infosys_web.view.ordencompra.BuscarProductos2').show();
        view.down("#nombreId").focus();


    },

    special2 : function(f,e){
        if (e.getKey() == e.ENTER) {
            this.buscarp2()
        }
    },

    special3 : function(f,e){
        if (e.getKey() == e.ENTER) {
            this.buscarproductosl()
        }
    },

    special5 : function(f,e){
        if (e.getKey() == e.ENTER) {
            this.buscarproductos2()
        }
    },

    special4 : function(f,e){
        if (e.getKey() == e.ENTER) {
            this.buscarproveedor()
        }
    },

    buscarp2: function(){
        var view = this.getBuscarproductospreventa2();
        var st = this.getProductosfStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre}
        st.load();
    },

    buscarPR: function(){
        var view = this.getBuscarproductos();        
        var st = this.getProductosfStore()
        var nombre = view.down('#nombreId').getValue();
        var codigo = view.down('#codigoId').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                codigo : codigo}
        st.load();
    },

    buscarp3: function(){
        var view = this.getBuscarproductos2();
        var st = this.getProductosfoStore()
        var nombre = view.down('#nombreId').getValue()
        var codigo = view.down('#codigoId').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                codigo: codigo}
        st.load();
    },

    buscacodigo : function() {

        var viewIngresa = this.getOrdencompraingresar();
        var codigo = viewIngresa.down('#codigoId').getValue();
        var cero = " ";
        var cero1= 0;
        var cero2= 1;

        Ext.Ajax.request({
            url: preurl + 'productos/buscacodigo',
            params: {
                codigo: codigo
            },
            success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 if (resp.success == true){
                     var cliente= resp.cliente;
                     viewIngresa.down('#productoId').setValue(cliente.id);
                     viewIngresa.down('#nombreproductoId').setValue(cliente.nombre);
                     viewIngresa.down('#codigoId').setValue(cliente.codigo);
                     viewIngresa.down('#precioId').setValue(cliente.p_venta);
                     viewIngresa.down("#cantidadId").focus();
                 }else{

                    Ext.Msg.alert('Alerta', 'Codigo producto no existe');
                    viewIngresa.down('#codigoId').setValue(cero);
                    viewIngresa.down('#productoId').setValue(cero);
                    viewIngresa.down('#nombreproductoId').setValue(cero);
                    viewIngresa.down('#cantidadId').setValue(cero2);
                    viewIngresa.down('#descuentoId').setValue(cero1);
                    viewIngresa.down('#precioId').setValue(cero1);
                    return;
                }
                               
            }
           
        });        
    },

    buscacodigo2 : function() {

        var viewIngresa = this.getOrdencompraeditar();
        var codigo = viewIngresa.down('#codigoId').getValue();
        var cero = " ";
        var cero1= 0;
        var cero2= 1;

        Ext.Ajax.request({
            url: preurl + 'productos/buscacodigo',
            params: {
                codigo: codigo
            },
            success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 if (resp.success == true){
                     var cliente= resp.cliente;
                     viewIngresa.down('#productoId').setValue(cliente.id);
                     viewIngresa.down('#nombreproductoId').setValue(cliente.nombre);
                     viewIngresa.down('#codigoId').setValue(cliente.codigo);
                     viewIngresa.down('#precioId').setValue(cliente.p_venta);
                     viewIngresa.down("#cantidadId").focus();
                 }else{

                    Ext.Msg.alert('Alerta', 'Codigo producto no existe');
                    viewIngresa.down('#codigoId').setValue(cero);
                    viewIngresa.down('#productoId').setValue(cero);
                    viewIngresa.down('#nombreproductoId').setValue(cero);
                    viewIngresa.down('#cantidadId').setValue(cero2);
                    viewIngresa.down('#descuentoId').setValue(cero1);
                    viewIngresa.down('#precioId').setValue(cero1);
                    return;
                }
                               
            }
           
        });        
    },

    buscarorden: function(){
        
        var view = this.getOrdencompraprincipal();
        var st = this.getOrden_compraStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
        st.load();

    },

    buscarordenrecepcion: function(){
        
        var view = this.getOrdencompraprincipalrecepcion();
        var st = this.getOrdencomprarecepcionStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
        st.load();

    },

    buscarorden2: function(){
        
        var view = this.getOrdencompraprincipaltodas();
        var st = this.getOrden_compratodasStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
        st.load();

    },

    exportarexcelordencompra: function(){
              
           Ext.create('Infosys_web.view.ordencompra.Exportar1').show();
    },

    exportarexcelordencomprap: function(){
              
           Ext.create('Infosys_web.view.ordencompra.Exportar3').show();
    },

    exportarexcelordencompra2: function(){
              
           Ext.create('Infosys_web.view.ordencompra.Exportar2').show();
    },

    exportarExcelFormulariototal: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getOrdencompraprincipaltodas()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getFormularioexportarordencompra();
        var viewnew = this.getOrdencompraprincipaltodas();
        var fecha = view.down('#fechaId').getSubmitValue();
        var opcion = viewnew.down('#tipoSeleccionId').getValue();
        var nombre = viewnew.down('#nombreId').getSubmitValue();
        var fecha2 = view.down('#fecha2Id').getSubmitValue();

        if (fecha > fecha2) {
        
            Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };

        window.open(preurl + 'adminServicesExcel/exportarexcelordencompratotal?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2+'&opcion='+opcion+'&nombre='+nombre);
        view.close();
 
    },

    exportarExcelFormulario2: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getOrdencompraprincipaltodas()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getFormularioexportarordencompra2();
        var viewnew =this.getOrdencompraprincipaltodas();
        var fecha = view.down('#fechaId').getSubmitValue();
        var opcion = viewnew.down('#tipoSeleccionId').getValue();
        var nombre = viewnew.down('#nombreId').getSubmitValue();
        var fecha2 = view.down('#fecha2Id').getSubmitValue();

        if (fecha > fecha2) {
        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };

        window.open(preurl + 'adminServicesExcel/exportarexcelordencompra?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2+'&opcion='+opcion+'&nombre='+nombre);
        view.close();
 
    },

    exportarExcelFormulario3: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getOrdencompraprincipal()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getFormularioexportarordencompra3();
        var viewnew =this.getOrdencompraprincipal();
        var fecha = view.down('#fechaId').getSubmitValue();
        var opcion = viewnew.down('#tipoSeleccionId').getValue();
        var nombre = viewnew.down('#nombreId').getSubmitValue();
        var fecha2 = view.down('#fecha2Id').getSubmitValue();

        if (fecha > fecha2) {
        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };

        window.open(preurl + 'adminServicesExcel/exportarexcelordencompra?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2+'&opcion='+opcion+'&nombre='+nombre);
        view.close();
 
    },

    grabarrecepcionforzada: function(){

        var view = this.getOrdencomprarecepcionforzada();
        var id = view.down('#Id').getValue();
        var stOrden = this.getOrden_compraStore();
        var stforzada = this.getOrdencompraforzadaStore();
               
        Ext.Ajax.request({
            url: preurl + 'ordencompra/updateforzada',
            params: {
                id: id,               
            },
            success: function(response){
                var text = response.responseText;
                Ext.Msg.alert('Informacion', 'Grabada Exitosamente.');
                stOrden.load();
                stforzada.load();
                view.close();
            }
        });

    },

    generapdf: function(){

        var view = this.getOrdencompraprincipalrecepcion();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            window.open(preurl +'ordencompra/exportPDF/?idordencompra=' + row.data.id)
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
        

    },

    generapdforiginal: function(){

        var view = this.getOrdencompraprincipalrecepcion();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            window.open(preurl +'ordencompra/exportPDForiginal/?idordencompra=' + row.data.id)
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
        

    },

    cerrarordendecompra: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
    },

    cerrarordendecompraforzada: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
    },

    exportarExcelrecepcion: function(){

        var view =this.getFormularioexportar()
        var idestado = view.down('#estadosId').getValue()
        if (!idestado){

             Ext.Msg.alert('Alerta', 'Selecciona un Estado.');
            return;
        }
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getOrdencompraprincipalrecepcion()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })  
        
        window.open(preurl + 'adminServicesExcel/exportarExcelordenrecepcion?estado='+idestado+'&cols='+Ext.JSON.encode(jsonCol));   
        view.close();                 
        },



    exportarordencomprarecepcion: function(){
        Ext.create('Infosys_web.view.ordencompra.Exportar').show();
    },

    buscarproductos: function(){

        var view = Ext.create('Infosys_web.view.ordencompra.BuscarProductos').show();
        view.down("#nombreId").focus();


    },

    buscarcontactos: function(){

        var view = this.getOrdencompraingresar();
        var idCliente = view.down('#idproveedor').getValue();

      if (idCliente){
         var edit = Ext.create('Infosys_web.view.ordencompra.Buscarcontactos').show();
          var st = this.getContacto_clientesStore();
          st.proxy.extraParams = {nombre : idCliente};
          st.load();
       }else {
          Ext.Msg.alert('Alerta', 'Debe seleccionar Proveedor.');
            return;
       }

       view.down("#nombreId").focus();


        
    },

    seleccionarcontactos: function(){

        var view = this.getBuscarcontactos();
        var viewIngresa = this.getOrdencompraingresar();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#nombre_contactoId').setValue(row.data.nombre);
            viewIngresa.down('#fono_contactoId').setValue(row.data.fono);
            viewIngresa.down('#mail_contactoId').setValue(row.data.email);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }       
       
    },

    seleccionarproductos2: function(){

        var view = this.getBuscarproductos();        
        var viewIngresa = this.getOrdencompraingresar();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id);
            viewIngresa.down('#nombreproductoId').setValue(row.data.nombre);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);
            viewIngresa.down('#cantidadOriginalId').setValue(row.data.stock);
            viewIngresa.down('#uloteId').setValue(row.data.u_lote);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    grabarrecepcion: function(){

        var view = this.getOrdencomprarecepcion();
        var bolEnable = true;
         var bolDisable = false;
        view.down('#grabaorden').setDisabled(bolEnable);
        var id = view.down('#Id').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        var numero = view.down('#numeroId').getValue();
        var idproveedor = view.down('#idproveedor').getValue();
        var numerodoc = view.down('#numdocId').getValue();
        var recepcion = view.down('#recepcionId').getValue();
        var chofer = view.down('#nombre_choferId').getValue();
        var id_chofer = view.down('#idtransportista').getValue();
        var fecha = view.down('#fechaordenId').getValue();
        var idprovee = view.down('#idproveedor').getValue();


        

        if (!idbodega){
             view.down('#grabaorden').setDisabled(bolDisable);
             Ext.Msg.alert('Debe Ingresar Bodega');
             return;
            

        };
        
        if (!recepcion){
             view.down('#grabaorden').setDisabled(bolDisable);
             Ext.Msg.alert('Debe Ingresar Docuemnto Recepcion');
             return;          

        };

        var stOrden = this.getOrden_compraStore();
        var stItem = this.getOrden_compradetalleStore();
      
        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data);
            cant = (r.data.stock)
            fechavenc = (r.data.fecha_vencimiento)
            lote = (r.data.lote)
            cant_rec = (r.data.val_real)

            if (!cant){
             view.down('#grabaorden').setDisabled(bolDisable);
             Ext.Msg.alert('Recepcione producto');
             return;
            };
            if (!fechavenc){
             view.down('#grabaorden').setDisabled(bolDisable);
             Ext.Msg.alert('Ingrese Fecha Vencimiento');
             return;
            };
            if (!lote){
                 view.down('#grabaorden').setDisabled(bolDisable);
                 Ext.Msg.alert('Ingrese Lote');
                 return;
            };
            if (cant_rec==0){
                 view.down('#grabaorden').setDisabled(bolDisable);
                 Ext.Msg.alert('Ingrese Cantidad Final');
                 return;
            };
        });

        
       
        Ext.Ajax.request({
            url: preurl + 'ordencompra/update',
            params: {
                items: Ext.JSON.encode(dataItems),
                id: id,
                idprovee: idproveedor,
                idbodega: idbodega,
                numero: numero,
                numerodoc: numerodoc,
                recepcion: recepcion,
                idproveedor: idproveedor,
                fecha: fecha,
                chofer: chofer,
                id_chofer: id_chofer
            },
            success: function(response){
                var text = response.responseText;
                Ext.Msg.alert('Informacion', 'Grabada Exitosamente.');
                stOrden.load();
                view.close();
            }
        });

    },

    grabarrecepcionfinal: function(){

        var view = this.getOrdencomprarecepcionfinal();
        var bolEnable = true;
        var bolDisable = false;
        view.down('#grabaorden').setDisabled(bolEnable);
        var id = view.down('#Id').getValue();
        var idproveedor = view.down('#idproveedor').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        var numero = view.down('#numeroId').getValue();
        var numerodoc = view.down('#numdocId').getValue();
        var recepcion = view.down('#recepcionId').getValue();
        var chofer = view.down('#nombre_choferId').getValue();
        var id_chofer = view.down('#idtransportista').getValue();
        var stItem = view.down('#itemsgridId');

        var fecha = view.down('#fechaordenId').getValue();

        if (!idbodega){
            view.down('#grabaorden').setDisabled(bolDisable);
             Ext.Msg.alert('Debe Ingresar Bodega');
             return; 
        };

        if (!recepcion){
            view.down('#grabaorden').setDisabled(bolDisable);
             Ext.Msg.alert('Debe Ingresar Docuemnto Recepcion');
             return;
        };

        var stOrden = this.getOrdencomprarecepcionStore();
        var stItem = this.getOrden_compradetallerecStore();
      
        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data);
            cant = (r.data.stock)
            fechavenc = (r.data.fecha_vencimiento)
            lote = (r.data.lote)
            cant_rec = (r.data.val_real)
            if(r.data.existe==true){
                if (!cant){
                    view.down('#grabaorden').setDisabled(bolDisable);
                    Ext.Msg.alert('Recepcione producto');
                    return;
                    };
                    if (!fechavenc){
                    view.down('#grabaorden').setDisabled(bolDisable);
                    Ext.Msg.alert('Ingrese Fecha Vencimiento');
                    return;
                    };
                    if (!lote){
                     view.down('#grabaorden').setDisabled(bolDisable);
                     Ext.Msg.alert('Ingrese Lote');
                     return;
                    };
                    if (cant_rec==0){
                     view.down('#grabaorden').setDisabled(bolDisable);
                     Ext.Msg.alert('Ingrese Cantidad Final');
                     return;
                    };                
            }

        });        
                       
        Ext.Ajax.request({
            url: preurl + 'ordencompra/updatefinal',
            params: {
                items: Ext.JSON.encode(dataItems),
                id: id,
                idbodega: idbodega,
                idprovee: idproveedor,
                numero: numero,
                numerodoc: numerodoc,
                recepcion: recepcion,
                fecha: fecha,
                chofer: chofer,
                id_chofer: id_chofer
            },
            success: function(response){
                var text = response.responseText;
                Ext.Msg.alert('Informacion', 'Grabada Exitosamente.');
                stOrden.load();
                view.close();
            }
        });

    },

    mordencompra: function(){

        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'ordencompraprincipal'});
        var view = this.getOrdencompraprincipal();        
        view.down("#nombreId").focus();
    },

    rordencompra: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'ordencompraprincipaltodas'});
        var view = this.getOrdencompraprincipaltodas();        
        view.down("#nombreId").focus();
    },

    mordencomprarec: function(){

        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'ordencompraprincipalrecepcion'});
        var view = this.getOrdencompraprincipalrecepcion();        
        view.down("#nombreId").focus();
    },

    mordencomprafor: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'ordencompraprincipalforzada'});
        var view = this.getOrdencompraprincipalforzada();        
        view.down("#nombreId").focus();
    },

    exportarordencompra: function(){
        var view = this.getOrdencompraprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            window.open(preurl +'ordencompra/exportPDF/?idordencompra=' + row.data.id)
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

     exportarordencompra2: function(){
        var view = this.getOrdencompraprincipaltodas();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            window.open(preurl +'ordencompra/exportPDF/?idordencompra=' + row.data.id)
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    agregarordencompra: function(){
        Ext.create('Infosys_web.view.ordencompra.Ingresar').show();
    },

    recepcionarordencompra: function(){

        var view = this.getOrdencompraprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit = Ext.create('Infosys_web.view.ordencompra.Recepcion').show();
            var nombre = (row.get('id'));
            edit.down('form').loadRecord(row);
            var st = this.getOrden_compradetalleStore()
            st.proxy.extraParams = {nombre : nombre}
            st.load();
           
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        };
        
    },

    recepcionforzada: function(){

        var view = this.getOrdencompraprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit = Ext.create('Infosys_web.view.ordencompra.Recepcionforzada').show();
            var nombre = (row.get('id'));
            edit.down('form').loadRecord(row);
            var st = this.getOrden_compradetalleStore()
            st.proxy.extraParams = {nombre : nombre}
            st.load();
           
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        };
        
    },

    recepcionarordencompradef: function(){

        var view = this.getOrdencompraprincipalrecepcion();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var nombre = (row.get('id'));
            var cumplida = (row.get('cumplida'));
            if (cumplida=="SI"){

            Ext.Msg.alert('Alerta', 'Orden de Compra Recepcionada');
            return;                

            }else{
                var edit = Ext.create('Infosys_web.view.ordencompra.Recepcionfinal').show();
                edit.down('form').loadRecord(row);
                var st = this.getOrden_compradetallerecStore()
                st.proxy.extraParams = {nombre : nombre}
                st.load();
           

            };
           
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        };
        
    },

    changedctofinal: function(){
        this.recalcularFinal();
    },

    recalcularFinal: function(){

        var view = this.getOrdencompraingresar();
        var stItem = this.getOrdencompraItemsStore();
        var pretotal = 0;
        var total = 0;
        var iva = 0;
        var neto = 0;
        var dcto = view.down('#descuentovalorId').getValue();

       stItem.each(function(r){
            pretotal = pretotal + r.data.total
            iva = iva + r.data.iva
            neto = neto + r.data.neto
        });
        pretotalfinal = ((pretotal * dcto)  / 100);
        total = ((pretotal) - parseInt(pretotalfinal));
        afecto = neto;
        
        view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));
        view.down('#descuentovalorId').setValue(Ext.util.Format.number(pretotalfinal, '0'));  
          
    },

    recalcularFinal2: function(){

        var view = this.getOrdencompraeditar();
        var stItem = this.getOrdencompraItemsStore();
        var pretotal = 0;
        var total = 0;
        var iva = 0;
        var neto = 0;
        var dcto = view.down('#descuentovalorId').getValue();

        stItem.each(function(r){
            pretotal = pretotal + r.data.total
            iva = iva + r.data.iva
            neto = neto + r.data.neto
        });
        pretotalfinal = ((pretotal * dcto)  / 100);
        total = ((pretotal) - parseInt(pretotalfinal));
        afecto = neto;
        
        view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));
        view.down('#descuentovalorId').setValue(Ext.util.Format.number(pretotalfinal, '0'));  
          
    },

    

    eliminaritem: function() {

        var view = this.getOrdencompraingresar();
        var grid  = view.down('#itemsgridId');
        var totalnue = view.down('#finaltotalpostId').getValue();
        var netonue = view.down('#finaltotalnetoId').getValue();
        var ivanue = view.down('#finaltotalivaId').getValue();
        var afectonue = view.down('#finalafectoId').getValue();
        var descuentonue = view.down('#descuentovalorId').getValue();
        cero1 = "";
        cero = 0;
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var totalnue = totalnue - (row.data.total);
           
            if (totalnue ==0){
                Ext.Msg.alert('Alerta', 'No puede Eliminar Ultimo Registro');
                return;                
            }else{
                var ivanue = ivanue - (row.data.iva);
                var afectonue = afectonue - (row.data.neto);
                var netonue = netonue - (row.data.neto);
                grid.getStore().remove(row);
            };
            view.down('#DescuentoproId').setValue(cero1);
            view.down('#finaltotalId').setValue(Ext.util.Format.number(totalnue, '0,000'));
            view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalnue, '0'));
            view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netonue, '0'));
            view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivanue, '0'));
            view.down('#finalafectoId').setValue(Ext.util.Format.number(afectonue, '0'));
            view.down('#descuentovalorId').setValue(Ext.util.Format.number(cero));

        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    eliminaritem2: function() {

        var view = this.getOrdencompraeditar();
        var grid  = view.down('#itemsgridId');
        var totalnue = view.down('#finaltotalpostId').getValue();
        var netonue = view.down('#finaltotalnetoId').getValue();
        var ivanue = view.down('#finaltotalivaId').getValue();
        var afectonue = view.down('#finalafectoId').getValue();
        var descuentonue = view.down('#descuentovalorId').getValue();
        cero1 = "";
        cero = 0;
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var totalnue = totalnue - (row.data.total);
           
            if (totalnue ==0){
                Ext.Msg.alert('Alerta', 'No puede Eliminar Ultimo Registro');
                return;                
            }else{
                var ivanue = ivanue - (row.data.iva);
                var afectonue = afectonue - (row.data.neto);
                var netonue = netonue - (row.data.neto);
                grid.getStore().remove(row);
            };
            view.down('#DescuentoproId').setValue(cero1);
            view.down('#finaltotalId').setValue(Ext.util.Format.number(totalnue, '0,000'));
            view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalnue, '0'));
            view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netonue, '0'));
            view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivanue, '0'));
            view.down('#finalafectoId').setValue(Ext.util.Format.number(afectonue, '0'));
            view.down('#descuentovalorId').setValue(Ext.util.Format.number(cero));

        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
        
    },

    selectItem: function() {

        var view = this.getOrdencompraingresar();
        var producto = view.down('#productoId');
        var stCombo = producto.getStore();
        var record = stCombo.findRecord('id', producto.getValue()).data;

        view.down('#precioId').setValue(record.p_venta);
    },

    agregarItem: function() {

        var view = this.getOrdencompraingresar();
        var stItem = this.getOrdencompraItemsStore();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var lote = view.down('#uloteId').getValue();
        var producto = view.down('#productoId').getValue();
        var nombre = view.down('#nombreproductoId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var precioun = ((view.down('#precioId').getValue())/ 1.19);
        var descuento = view.down('#totdescuentoId').getValue(); 
        var iddescuento = view.down('#DescuentoproId').getValue();
        var bolEnable = true;
        cero="";
        cero1=0;
        cero2=1;

        console.log(precio);
        
        if (descuento == 1){            
            var descuento = 0;
            var iddescuento = 0;
        };
              
        var neto = ((cantidad * precio) - descuento);
        var tot = (Math.round(neto * 1.19));
        var exists = 0;
        var iva = (tot - neto);
        var neto = (tot - iva);
        var total = ((neto + iva ));        
        
        if(!producto){
            
            Ext.Msg.alert('Alerta', 'Debe Seleccionar un Producto');
            return false;

        }

       
        if(!precio){

            Ext.Msg.alert('Alerta', 'Debe Ingresar Precio Producto');
            return false;
        }
        if(cantidad==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Cantidad.');
            return false;
        }        
        if(rut.length==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Datos a la Orden.');
            return false;           
        }

        stItem.each(function(r){
            if(r.data.id == producto){
                Ext.Msg.alert('Alerta', 'El registro ya existe.');
                exists = 1;
                view.down('#codigoId').setValue(cero);
                view.down('#productoId').setValue(cero);
                view.down('#nombreproductoId').setValue(cero);
                view.down('#cantidadId').setValue(cero2);
                view.down('#precioId').setValue(cero);
                view.down('#uloteId').setValue(cero);
                view.down('#cantidadOriginalId').setValue(cero);
                view.down('#totdescuentoId').setValue(cero1);
                view.down('#DescuentoproId').setValue(cero);
                return; 
            }
        });
        if(exists == 1)
            return;
        
        stItem.add(new Infosys_web.model.ordencompra.Item({
            id: producto,
            id_producto: producto,
            id_descuento: iddescuento,
            nombre: nombre,
            precio: precio,
            cantidad: cantidad,
            u_lote: lote,
            neto: neto,
            total: total,
            iva: iva,
            dcto: descuento
        }));
        this.recalcularFinal();
       
        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#nombreproductoId').setValue(cero);
        view.down('#cantidadId').setValue(cero2);
        view.down('#precioId').setValue(cero);
        view.down('#cantidadOriginalId').setValue(cero);
        view.down('#totdescuentoId').setValue(cero1);
        view.down('#DescuentoproId').setValue(cero);
    },

    seleccionarproveedor: function() {

        var viewIngresa = this.getOrdencompraingresar();
        var view = this.getBusquedaproveedor();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#idproveedor').setValue(row.data.id);
            viewIngresa.down('#direccionId').setValue(row.data.direccion);
            viewIngresa.down('#nom_giroId').setValue(row.data.giro);
            viewIngresa.down('#giroId').setValue(row.data.id_giro);
            viewIngresa.down('#mail_contactoId').setValue(row.data.e_mail_contacto);
            viewIngresa.down('#nombre_contactoId').setValue(row.data.nombre_contacto);
            viewIngresa.down('#nombreId').setValue(row.data.nombres);
            viewIngresa.down('#rutId').setValue(row.data.rut);
            viewIngresa.down('#fono_contactoId').setValue(row.data.fono_contacto);
            viewIngresa.down('#fonoId').setValue(row.data.fono);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    buscarproveedor: function() {
        var view = this.getBusquedaproveedor();
        var st = this.getProveedoresStore()
        var nombre = view.down('#bproveedornombreId').getValue();
        var rut = view.down('#bproveedorrutId').getValue();
        if (nombre==""){            
            var opcion = "Rut";
            var nombre = view.down('#bproveedorrutId').getValue();
        };

        if (rut==""){            
            var opcion = "Nombre";
            var nombre = view.down('#bproveedornombreId').getValue();
        };
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
        st.load();
        
    },

    wbuscarproveedor: function() {
        var view = Ext.create('Infosys_web.view.ordencompra.BusquedaProveedor').show();
        view.down("#bproveedornombreId").focus();
    },

    grabar: function() {

        var view = this.getOrdencompraingresar();
        var bolEnable = true;
        var bolDisable = false;
        view.down('#grabaorden').setDisabled(bolEnable);
        var idproveedor = view.down('#idproveedor').getValue();
        var neto = view.down('#finaltotalnetoId').getValue();
        var iva = view.down('#finaltotalivaId').getValue();
        var total = view.down('#finaltotalpostId').getValue();
        var afecto = view.down('#finalafectoId').getValue();
        var observacion = view.down('#obsId').getValue();
        var descuentofinal = view.down('#descuentovalorId').getValue();
        var stItem = this.getOrdencompraItemsStore();
        var stCotiz = this.getOrden_compraStore();

        if (!idproveedor){            
             
             view.down('#grabaorden').setDisabled(bolDisable);
             Ext.Msg.alert('Debe Ingresar Datos del Proveedor');
             return;
        }

        var dataproveedor = {
            mail_contacto: view.down('#mail_contactoId').getValue(),
            nombre_contacto: view.down('#nombre_contactoId').getValue(),
            telefono_contacto: view.down('#fono_contactoId').getValue(),
        };

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        if (!neto){
              view.down('#grabaorden').setDisabled(bolDisable);           
             Ext.Msg.alert('Debe Ingresar Productos');
             return;
        }


        Ext.Ajax.request({
            url: preurl + 'ordencompra/save',
            params: {
                idproveedor: idproveedor,
                dataproveedor: Ext.JSON.encode(dataproveedor),
                items: Ext.JSON.encode(dataItems),
                afecto : afecto,
                neto: neto,
                observacion: observacion,
                iva: iva,
                descuento: descuentofinal,
                total: view.down('#finaltotalpostId').getValue()
            },
            success: function(response){
                var text = response.responseText;
                Ext.Msg.alert('Informacion', 'Creada Exitosamente.');
                view.close();
                stCotiz.load();
            }
        });

    }
});










