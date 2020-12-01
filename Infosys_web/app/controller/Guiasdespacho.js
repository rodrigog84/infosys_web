Ext.define('Infosys_web.controller.Guiasdespacho', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['Guiasdespacho',
             'Guiasdespachopendientes',
             'Guiasdespachopendientes2',
             'guiasdespacho.Items',
             'Clientes',
             'clientes.Selector2',
             'productos.Items',
             'Sucursales_clientes',
             'Factura4',
             'Despachafactura',
             'guiasdespacho.Selector',
             'Productosf',
             'Existencias4',
             'facturaglosa.Items',
             'transportista'],

    models: ['Guiasdespacho',
             'Producto',
             'facturaglosa.Item',
             'Facturaglo',
             'facturaglosa.Item',
             'Guiasdespacho.Item'],

    views: ['guiasdespacho.Principalguias',
            'guiasdespacho.Principalguiaspendientes',
            'guiasdespacho.Facturaguias',
            'guiasdespacho.BuscarClientes',
            'guiasdespacho.BuscarClientes2',
            'guiasdespacho.BuscarClientes3',
            'guiasdespacho.BuscarGuias',
            'guiasdespacho.Observaciones',
            'guiasdespacho.Despachafactura',
            'guiasdespacho.BuscarFacturas',
            'guiasdespacho.BuscarSucursales',
            'guiasdespacho.BuscarProductos',
            'guiasdespacho.Exportar',
            'guiasdespacho.BuscarSucursales2',
            'guiasdespacho.GuiasDespacho',
            'guiasdespacho.PrincipalguiasDespacho',
            'productos.BuscarProductos2',
            'guiasdespacho.BuscarProductos4',
            'ventas.detalle_stock2',
            'ventas.Adicional2',
            'guiasdespacho.Observacionesguias',
            'guiasdespacho.Observacionesguiasglosa',
            'guiasdespacho.Guiaglosa',
            'guiasdespacho.BuscarClientes4',
            'guiasdespacho.BuscarSucursales4',
            'guiasdespacho.Anular',
            'guiasdespacho.BuscarTransportista'],
           
    
    refs: [{
       ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{
        ref: 'guiasprincipal',
        selector: 'guiasprincipal'
    },{
        ref: 'guiasprincipalpendientes',
        selector: 'guiasprincipalpendientes'
    },{
        ref: 'facturaguias',
        selector: 'facturaguias'
    },{
        ref: 'guiasdespachobuscarclientes',
        selector: 'guiasdespachobuscarclientes'
    },{
        ref: 'guiasdespachobuscarclientes2',
        selector: 'guiasdespachobuscarclientes2'
    },{
        ref: 'buscarguias',
        selector: 'buscarguias'
    },{
        ref: 'observacionesfacturasguias',
        selector: 'observacionesfacturasguias'
    },{
        ref: 'despachofactura',
        selector: 'despachofactura'
    },{
        ref: 'buscarfacturasdespacho',
        selector: 'buscarfacturasdespacho'
    },{
        ref: 'buscarsucursalesclientesfacturas',
        selector: 'buscarsucursalesclientesfacturas'
    },{
        ref: 'buscarproductosfacturadirecta',
        selector: 'buscarproductosfacturadirecta'
    },{
        ref: 'formularioexportarguias',
        selector: 'formularioexportarguias'
    },{
        ref: 'buscarsucursalesclientesfacturas2',
        selector: 'buscarsucursalesclientesfacturas2'
    },{
        ref: 'guiasdespachoingresar',
        selector: 'guiasdespachoingresar'
    },{
        ref: 'guiasprincipaldespacho',
        selector: 'guiasprincipaldespacho'
    },{
        ref: 'guiasdespachobuscarclientes3',
        selector: 'guiasdespachobuscarclientes3'
    },{
        ref: 'buscarproductos25',
        selector: 'buscarproductos25'
    },{
        ref: 'detallestock2',
        selector: 'detallestock2'
    },{
        ref: 'nobreadicional2',
        selector: 'nobreadicional2'
    },{
        ref: 'guiasdespachoingresar',
        selector: 'guiasdespachoingresar'
    },{
        ref: 'guiasglosaingresar',
        selector: 'guiasglosaingresar'
    },{
        ref: 'guiasdespachobuscarclientes4',
        selector: 'guiasdespachobuscarclientes4'
    },{
        ref: 'buscarsucursalesclientesguias4',
        selector: 'buscarsucursalesclientesguias4'
    },{
        ref: 'buscarproductosguiasdirecta',
        selector: 'buscarproductosguiasdirecta'
    },{
        ref: 'observacionesguias',
        selector: 'observacionesguias'
    },{
        ref: 'observacionesguiasglosa',
        selector: 'observacionesguiasglosa'
    },{
        ref: 'anulaguias',
        selector: 'anulaguias'
    },{
        ref: 'buscatransguia',
        selector: 'buscatransguia'
    }

    ],
    
    init: function() {
    	
        this.control({ 
                   
            'topmenus menuitem[action=mguias]': {
                click: this.mguias
            },
            'topmenus menuitem[action=fguias]': {
                click: this.fguias
            },
            'guiasprincipal button[action=cerrarguia]': {
                click: this.cerrarguia
            },
            'guiasprincipal #bodegaId': {
                select: this.despliegadocumentos
            },
            'guiasprincipal button[action=buscar]': {
                click: this.buscar
            },
            'guiasprincipalpendientes button[action=factguia]': {
                click: this.factguia
            },
            'guiasprincipalpendientes #bodegaId': {
                select: this.despliegadocumentos2
            },
            'guiasprincipal button[action=despachofactura]': {
                click: this.despachofactura
            },           
            'facturaguias button[action=validarut21]': {
                click: this.validarut21
            },
            'facturaguias #rutId': {
                specialkey: this.special
            },
            'guiasdespachoingresar #rutId': {
                specialkey: this.special5
            },
           'guiasdespachobuscarclientes button[action=seleccionarclienteguias]': {
                click: this.seleccionarclienteguias
            },
            'guiasdespachobuscarclientes2 button[action=seleccionarclienteguias2]': {
                click: this.seleccionarclienteguias2
            },
            'facturaguias button[action=agregarItem]': {
                click: this.agregarItem
            },
            'buscarguias button[action=seleccionarguias]': {
                click: this.seleccionarguias
            },
            'buscarguias button[action=buscarguiasdespacho]': {
                click: this.buscarguias2
            },
            'buscarguias button[action=seleccionartodas]': {
                click: this.seleccionartodas
            },
            'facturaguias button[action=buscarguias]': {
                click: this.buscarguias
            },
            'guiasdespachobuscarclientes button[action=buscarclientes]': {
                click: this.buscarclientes
            },
            'guiasdespachobuscarclientes2 button[action=buscarclientes2]': {
                click: this.buscarclientes2
            },
            'facturaguias button[action=grabarfactura]': {
                click: this.grabarfactura
            },
            'facturaguias #tipocondpagoId': {
                select: this.selecttipocondpago
            },
            'facturaguias button[action=observaciones]': {
                click: this.observaciones
            },
            'observacionesfacturasguias button[action=ingresaobs]': {
                click: this.ingresaobs
            },
            'observacionesfacturasguias #rutId': {
                specialkey: this.special6
            },
            'despachofactura #rutId': {
                specialkey: this.special3
            },
            'despachofactura button[action=validarut3]': {
                click: this.validarut3
            },            
            'despachofactura button[action=buscarfactura]': {
                click: this.buscarfactura
            },
            'facturaguias button[action=buscarsucursaldespacho]': {
                click: this.buscarsucursaldespacho
            },
            'despachofactura button[action=buscarsucursalfacturadespacho]': {
                click: this.buscarsucursalfacturadespacho
            },
            'buscarsucursalesclientesfacturas button[action=seleccionarsucursalcliente]': {
                click: this.seleccionarsucursalcliente
            },
            'buscarsucursalesclientesfacturas2 button[action=seleccionarsucursalcliente2]': {
                click: this.seleccionarsucursalcliente2
            },
            'despachofactura button[action=buscarproductos]': {
                click: this.buscarproductos
            },
            'buscarproductosfacturadirecta button[action=seleccionarproductos]': {
                click: this.seleccionarproductos
            },
            'buscarfacturasdespacho button[action=seleccionarfactura]': {
                click: this.seleccionarfactura
            },
            'despachofactura button[action=agregarItem2]': {
                click: this.agregarItem2
            },
            'despachofactura button[action=grabarfacturadespacho]': {
                click: this.grabarfacturadespacho
            },
            'buscarproductosfacturadirecta button[action=seleccionartodas2]': {
                click: this.seleccionartodas2
            },
            'guiasprincipal button[action=generarguiaspdf]': {
                click: this.generarguiaspdf
            },
            'guiasprincipaldespacho button[action=generarguiaspdf2]': {
                click: this.generarguiaspdf2
            },  
            'guiasprincipal button[action=exportarexcelguias]': {
                click: this.exportarexcelguias
            },
            'formularioexportarguias button[action=exportarExcelFormulario]': {
                click: this.exportarExcelFormulario
            },
            'despachofactura button[action=eliminaritem]': {
                click: this.eliminaritem
            },
            'despachofactura #tipocondpagoId': {
                select: this.selecttipocondpago2
            },
            'guiasprincipalpendientes button[action=marcaguia]': {
                click: this.marcarguias
            },
            'facturaguias button[action=cancelar]': {
                click: this.cancelar
            },
            'facturaguias button[action=eliminaritem3]': {
                click: this.eliminaritem3
            },
            'topmenus menuitem[action=iguias]': {
                click: this.iguias
            },
            'guiasprincipaldespacho button[action=iguiasdespacho]': {
                click: this.iguiasdespacho
            },
            'guiasprincipaldespacho #bodegaId': {
                select: this.despliegadocumentos3
            },
            'guiasprincipaldespacho button[action=buscarguiasdirectas]': {
                click: this.buscarguiasdirectas
            },
            'guiasdespachoingresar button[action=cancelar2]': {
                click: this.cancelar2
            },
            'guiasdespachoingresar button[action=validarutD]': {
                click: this.validarutD
            },
            'guiasdespachobuscarclientes3 button[action=seleccionarclienteguias3]': {
                click: this.seleccionarclienteguias3
            },
             'guiasdespachobuscarclientes4 button[action=seleccionarclienteguias4]': {
                click: this.seleccionarclienteguias4
            },
            'guiasdespachobuscarclientes3 button[action=buscarclientes3]': {
                click: this.buscarclientes3
            },
            'guiasdespachoingresar #codigoId': {
                specialkey: this.special7
            },
            'guiasdespachoingresar button[action=buscarproductos7]': {
                click: this.buscarproductos7
            },
            'buscarproductos25 button[action=seleccionarproductos]': {
                click: this.seleccionarproductos7
            },
            'buscarproductos25 button[action=buscarproguias]': {
                click: this.buscarproguias
            },
            'detallestock2 button[action=seleccionarproductosstock]': {
                click: this.seleccionarproductosstock
            },
            'nobreadicional2 button[action=grabaradicional]': {
                click: this.selectvistaadicional
            }, 
            'nobreadicional2 button[action=cancelaadicional]': {
                click: this.cancelaadicional2
            },
            'guiasdespachoingresar button[action=agregarItem3]': {
                click: this.agregarItem3
            }, 
            'guiasdespachoingresar button[action=eliminaritem3]': {
                click: this.eliminaritem3
            },
            'guiasdespachoingresar button[action=editaritem3]': {
                click: this.editaritem3
            },
            'guiasdespachoingresar button[action=observacionesguia]': {
                click: this.observacionesguias
            },
            'guiasglosaingresar button[action=observacionesglosa]': {
                click: this.observacionesglosa
            },
            'observacionesguias button[action=ingresaobsguias]': {
                click: this.ingresaobsguias
            },
            'observacionesguias button[action=validar]': {
                click: this.validarut8
            },
            'observacionesguiasglosa #rutId': {
                specialkey: this.special10
            },
            'guiasdespachoingresar button[action=grabarguiadirecta]': {
                click: this.grabarguiadirecta
            },
            'observacionesguiasglosa button[action=ingresaobsguias]': {
                click: this.ingresaobsguias2
            },
            'observacionesguiasglosa button[action=validar]': {
                click: this.validarut10
            },
            'observacionesguias #rutId': {
                specialkey: this.special8
            },
            'guiasprincipaldespacho button[action=mguiasglosa]': {
                click: this.mguiasglosa
            },            
            'guiasglosaingresar #rutId': {
                specialkey: this.special9
            },
            'guiasdespachobuscarclientes4 button[action=seleccionarcliente4]': {
                click: this.seleccionarcliente4
            },
            'guiasdespachobuscarclientes4 button[action=buscar4]': {
                click: this.buscar4
            },
            'guiasglosaingresar button[action=buscarsucursalguiaglosa]': {
                click: this.buscarsucursalguiaglosa
            },
            'buscarsucursalesclientesguias4 button[action=seleccionarsucursalcliente4]': {
                click: this.seleccionarsucursalcliente4
            },
            'guiasglosaingresar button[action=validarut22]': {
                click: this.validarut9
            },
            'guiasglosaingresar button[action=cancelar4]': {
                click: this.cancelar4
            },
            'guiasglosaingresar button[action=grabarguiaglosa]': {
                click: this.grabarguiaglosa
            },
            'guiasglosaingresar button[action=agregarItem4]': {
                click: this.agregarItem4
            },
            'guiasglosaingresar button[action=eliminaritem4]': {
                click: this.eliminaritem4
            },
            'guiasglosaingresar #netoId': {
                specialkey: this.calculaiva
            },
            'guiasprincipaldespacho button[action=anular]': {
                click: this.anular
            },
            'anulaguias button[action=anularguia]': {
                click: this.anularguia
            }, 
             'anulaguias button[action=salirguias]': {
                click: this.salirguias
            },
            'facturaguias #fechafacturaId': {
                select: this.selecttipocondpago
            }, 
            'guiasdespachoingresar #fechafacturaId': {
                select: this.selecttipocondpago3
            },
            'ordencomprarecepcion button[action=tbuscar]': {
                click: this.tbuscar
            },
            'buscatransguia button[action=buscartran]': {
                click: this.buscartran
            },
            'buscatransguia button[action=seleccionartrans]': {
                click: this.seleccionartrans
            },       

        });
    },

    buscartran : function(){

        var view = this.getBuscatransguia()
        var st = this.getTransportistaStore()
        var nombre = view.down('#nombreId').getValue()
        var patente = view.down('#patenteId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                patente : patente}
        st.load();

    },

     seleccionartrans : function(){

        var view = this.getBuscatransguia()
        var viewIngresa = this.getObservacionesguias();        
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#transportistaId').setValue(row.data.id);
            viewIngresa.down('#rutId').setValue(row.data.rut);
            viewIngresa.down('#nombreId').setValue(row.data.nombre);
            viewIngresa.down('#camionId').setValue(row.data.camion);
            viewIngresa.down('#carroId').setValue(row.data.carro);            
            view.close();
            this.validarut8()
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

       
    },

    salirguias: function(){
        var view = this.getAnulaguias();
        view.close();        
    },

    anular: function(){

        var view = this.getGuiasprincipaldespacho();
        var edit = this.getGuiasprincipaldespacho();
        var idbodega = edit.down('#bodegaId').getValue();
        if(!idbodega){
            Ext.Msg.alert('Alerta', 'Debe Elegir Bodega');
            return;            
        }else{
            if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var idfactura=(row.data.id);
            var edit = Ext.create('Infosys_web.view.guiasdespacho.Anular');
            edit.down('#facturaId').setValue(idfactura);
                
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
            
        }
        

         
    },

    anularguia: function(){
         var view = this.getAnulaguias();
         var edit = this.getGuiasprincipaldespacho();
         var idbodega = edit.down('#bodegaId').getValue();
         var idfactura = view.down('#facturaId').getValue();
         console.log(idfactura);
         console.log(idbodega);
         var stFactura = this.getGuiasdespachoStore();

         Ext.Ajax.request({
            url: preurl + 'facturas/anulaguias',
            params: {               
                idfactura: idfactura,
                idbodega: idbodega               
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {
                 Ext.Msg.alert('Informacion', 'Creada Exitosamente.');
                 view.close();
                 stFactura.reload();

             }               
            }
           
        });           
       
    },

    calculaiva: function(){

        var view = this.getGuiasglosaingresar();
        var tipo_documento = 105;
        if (tipo_documento == 19 || tipo_documento == 103 ){
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
        var iva = (((neto * 19) / 100));
        var total = (neto + iva);
        view.down('#ivaId').setValue(iva);
        view.down('#totalId').setValue(total);
        };
    },

    eliminaritem4: function() {
        var view = this.getGuiasglosaingresar();
        var total = view.down('#finaltotalpostId').getValue();
        var neto = view.down('#finaltotalnetoId').getValue();
        var iva = view.down('#finaltotalivaId').getValue();
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var total = ((total) - (row.data.total));
            var neto = ((neto) - (row.data.neto));
            var iva = ((iva) - (row.data.iva));
            var afecto = neto;
            view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
            view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
            view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
            view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
            view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));

            grid.getStore().remove(row);

        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

    },



    agregarItem4: function() {

        var view = this.getGuiasglosaingresar();
        var tipo_documento = view.down('#tipoDocumentoId').getValue();
        var rut = view.down('#rutId').getValue();
        var stItem = this.getFacturaglosaItemsStore();;        
        var glosa = view.down('#glosaId').getValue();
        var neto = view.down('#netoId').getValue();
        var iva = view.down('#ivaId').getValue();
        var total = view.down('#totalId').getValue();
        var totalfin = view.down('#finaltotalpostId').getValue();
        var netofin = view.down('#finalafectoId').getValue();
        var ivafin = view.down('#finaltotalivaId').getValue();


        if(!glosa){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Glosa.');
            return false;
        };
        
        if (tipo_documento == 19  || tipo_documento == 103){
        
        if(neto==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Valores.');
            return false;
        }; 
        
        if(total==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Valores.');
            return false;
        };
        
        }else if (tipo_documento == 2){

             if(neto==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Valores.');
            return false;
            }; 
            
            if(total==0 ){  // se validan los datos sólo si es factura
                Ext.Msg.alert('Alerta', 'Debe Ingresar Valores.');
                return false;
            };         


        }else{

            if(neto==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Valores.');
            return false;
            }; 
            
            if(iva==0 ){  // se validan los datos sólo si es factura
                Ext.Msg.alert('Alerta', 'Debe Ingresar Valores.');
                return false;
            }; 

            if(total==0 ){  // se validan los datos sólo si es factura
                Ext.Msg.alert('Alerta', 'Debe Ingresar Valores.');
                return false;         


        };
        
        };  
        
                   
        if(rut.length==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Datos a la Factura.');
            return false;
        };

        if (tipo_documento == 2){
             var iva = 0;
             var total = neto;
        };          

        stItem.add(new Infosys_web.model.facturaglosa.Item({
                    glosa: glosa,
                    neto: neto,
                    iva: iva,
                    total: total             
        }));
       
        cero="";
        cero2=0;
        view.down('#glosaId').setValue(cero);
        view.down('#netoId').setValue(cero2);
        view.down('#ivaId').setValue(cero2);
        view.down('#totalId').setValue(cero2);

        if (tipo_documento = 2){
        
            totalfin = totalfin + total;
            ivafin = ivafin + iva;
            netofin = netofin + neto;

        }else{
            
            totalfin = totalfin + total;
            ivafin = ivafin + iva;
            netofin = netofin + neto;

        };
      
        view.down('#finaltotalId').setValue(Ext.util.Format.number(totalfin, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalfin, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netofin, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivafin, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(netofin, '0'));
    },

    grabarguiaglosa: function() {

        var viewIngresa = this.getGuiasglosaingresar();
        var tipo_documento = viewIngresa.down('#tipoDocumentoId').getValue();
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var idtipo= viewIngresa.down('#tipoDocumentoId').getValue();
        var idsucursal= viewIngresa.down('#id_sucursalID').getValue();
        var idcondventa= viewIngresa.down('#tipocondpagoId').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId').getValue();
        var ordencompra = viewIngresa.down('#ordencompraId').getValue();
        var numdocumento = viewIngresa.down('#numfacturaId').getValue();
        var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
        var fechavenc = viewIngresa.down('#fechavencId').getValue();
        var idbodega = viewIngresa.down('#bodegaId').getValue();
        var idobserva = viewIngresa.down('#obsId').getValue();
        var observacion = viewIngresa.down('#observaId').getValue();
        var stItem = this.getFacturaglosaItemsStore();
        var stFactura = this.getGuiasdespachoStore();       
        
        if(numdocumento==0){
            Ext.Msg.alert('Ingrese Datos a La Factura');
            return;   
        }

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'facturaglosa/save',
            params: {
                idcliente: idcliente,
                numdocumento: numdocumento,
                idsucursal: idsucursal,
                idcondventa: idcondventa,
                idtipo: idtipo,
                idbodega: idbodega,
                ordencompra: ordencompra,
                items: Ext.JSON.encode(dataItems),
                vendedor : vendedor,
                idobserva: idobserva,
                observacion: observacion, 
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
                 stFactura.reload();
                 window.open(preurl + 'facturaglosa/exportPDF/?idfactura='+idfactura);

            }
           
        });

        var view = this.getGuiasprincipaldespacho();
        var st = this.getGuiasdespachoStore();
        st.proxy.extraParams = {documento: 105,
                                idbodega: idbodega}
        st.load();        
        
    },

     cancelar4: function(){

        var viewIngresa = this.getGuiasglosaingresar();
        var view = this.getGuiasprincipaldespacho();
        var idbodega = view.down('#bodegaId').getValue();
        var documento = 101;
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

     validarut21: function(){

        var view =this.getGuiasglosaingresar();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;       
        if(numero==0){
            var edit = Ext.create('Infosys_web.view.guiasdespacho.BuscarClientes4');            
                  
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
         }

        });       
        }
    },

    buscar4: function(){

        var view = this.getGuiasdespachobuscarclientes4()
        var st = this.getClientesStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : "Nombre"}
        st.load();
    },

    seleccionarsucursalcliente4: function(){

        var view = this.getBuscarsucursalesclientesguias4();
        var viewIngresa = this.getGuiasglosaingresar();
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


    buscarsucursalguiaglosa: function(){

       var busca = this.getGuiasglosaingresar()
       var nombre = busca.down('#id_cliente').getValue();       
       if (nombre){
         var edit = Ext.create('Infosys_web.view.guiasdespacho.BuscarSucursales4').show();
          var st = this.getSucursales_clientesStore();
          st.proxy.extraParams = {nombre : nombre};
          st.load();
       }else {
          Ext.Msg.alert('Alerta', 'Debe seleccionar Cliente.');
            return;
       }
      
    },



    seleccionarcliente4: function(){

        var view = this.getGuiasdespachobuscarclientes4();
        var viewIngresa = this.getGuiasglosaingresar();
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
        };

              
    },

    special9 : function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut9()
        }
    },

    special10 : function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut10()
        }
    },

     validarut9: function(){

        var view =this.getGuiasglosaingresar();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

       
        if(numero==0){
            var edit = Ext.create('Infosys_web.view.guiasdespacho.BuscarClientes4');            
                  
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
                        view.down("#tipocondpagoId").setValue(cliente.id_pago)
                        view.down("#direccionId").setValue(cliente.direccion)
                        view.down("#rutId").setValue(rut)  
                        var condicion = viewIngresa.down('#tipocondpagoId');
            var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
            var stCombo = condicion.getStore();
            var record = stCombo.findRecord('id', condicion.getValue()).data;
            dias = record.dias;
            var bolEnable = false;
            if (row.data.id_pago == 1){

                viewIngresa.down('#DescuentoproId').setDisabled(bolEnable);
                viewIngresa.down('#tipoDescuentoId').setDisabled(bolEnable);
                viewIngresa.down('#descuentovalorId').setDisabled(bolEnable);
                
            };
            if (row.data.id_pago == 6){

                 viewIngresa.down('#DescuentoproId').setDisabled(bolEnable);
                 viewIngresa.down('#tipoDescuentoId').setDisabled(bolEnable);
                 viewIngresa.down('#descuentovalorId').setDisabled(bolEnable);
                
            };
            if (row.data.id_pago == 7){

                 view.down('#DescuentoproId').setDisabled(bolEnable);
                 view.down('#tipoDescuentoId').setDisabled(bolEnable);
                 view.down('#descuentovalorId').setDisabled(bolEnable);
                
            };          
            

            if (dias > 0){
        
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

   validarut10: function(){

        var view = this.getObservacionesguiasglosa();
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

    mguiasglosa: function(){
      
        var view = this.getGuiasprincipaldespacho();
        var idbodega = view.down('#bodegaId').getValue();
        if(!idbodega){
            Ext.Msg.alert('Alerta', 'Debe Elegir Bodega');
            return;    
        }else{ 

        var nombre = 105;
        var descripcion = "GUIA DESPACHO ELECTRONICA";    
        habilita = false;
        if(nombre == 101 || nombre == 103 || nombre == 105){ // FACTURA ELECTRONICA o FACTURA EXENTA

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
                    var view = Ext.create('Infosys_web.view.guiasdespacho.Guiaglosa').show();
                    view.down('#numfacturaId').setValue(nuevo_folio);
                    view.down('#nomdocumentoId').setValue(descripcion);
                    view.down('#idfolio').setValue(id_folio);
                    view.down('#tipodocumentoId').setValue(nombre);
                    view.down('#tipoDocumentoId').setValue(nombre);                    
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


    ingresaobsguias2: function(){

        var view = this.getObservacionesguiasglosa();
        var viewIngresar = this.getGuiasglosaingresar();                
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

    ingresaobsguias: function(){

        var view = this.getObservacionesguias();
        var viewIngresar = this.getGuiasdespachoingresar();                
        var rut = view.down('#rutmId').getValue();
        var nombre = view.down('#nombreId').getValue();
        var camion = view.down('#camionId').getValue();
        var fono = view.down('#fonoId').getValue();
        var carro = view.down('#carroId').getValue();
        var observa = view.down('#observaId').getValue();
        var destino = view.down('#destinoId').getValue();
        var idtransportista = view.down('#transportistaId').getValue();
        //var idobserva = view.down('#obsId').getValue();
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
                destino: destino, 
                idtransportista: idtransportista,
                numero: numero
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idobserva = resp.idobserva; 
                var idtransp = resp.idtransp;         
                view.close();
                viewIngresar.down("#observaId").setValue(observa);
                viewIngresar.down("#permiteId").setValue(permite);
                viewIngresar.down("#idtransportista").setValue(idtransp);
                viewIngresar.down("#obsId").setValue(idobserva);               

            }
           
        });
    },

    special8: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut8()
        }
    },

    validarut8: function(){

        var view = this.getObservacionesguias();
        var rut = view.down('#rutId').getValue();
        var okey = "SI";
        var cero = " ";
        
        if (!rut){
            var edit = Ext.create('Infosys_web.view.guiasdespacho.BuscarTransportista');                
        }else{;

        Ext.Ajax.request({
            url: preurl + 'facturasvizualiza/validaRutobserva?valida='+rut,
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
                        //var edit = Ext.create('Infosys_web.view.guiasdespacho.BuscarTransportista'); 
                    } 
                    
                }else{

                      Ext.Msg.alert('Informacion', 'Rut Incorrecto');                      
                      return false;                     
                      
                }
               
            }

        });
    }
    },

    observacionesguias: function(){

        var viewIngresa = this.getGuiasdespachoingresar();
        var numfactura = viewIngresa.down('#numfacturaId').getValue();
        var idobserva = viewIngresa.down('#obsId').getValue();          
        if (idobserva){
        Ext.Ajax.request({
        url: preurl + 'facturasvizualiza/validabserva?valida='+idobserva,
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
                 var view = Ext.create('Infosys_web.view.guiasdespacho.Observacionesguias').show();
                 view.down("#nombreId").setValue(observa.nombre);
                 view.down("#rutId").setValue(observa.rut);
                 view.down("#rutmId").setValue(observa.rutm);
                 view.down("#camionId").setValue(observa.pat_camion);
                 view.down("#carroId").setValue(observa.pat_carro);
                 view.down("#fonoId").setValue(observa.fono);
                 //view.down("#validaId").setValue(okey);
                 view.down("#observaId").setValue(observa.observacion);
                }             
                };                               
            }           
        }
        });            
        }else{
           var view = Ext.create('Infosys_web.view.guiasdespacho.Observacionesguias').show();
           view.down("#rutId").focus();
           view.down("#idfactura").setValue(numfactura);            
        };
    },

    grabarguiadirecta: function() {
        var viewIngresa = this.getGuiasdespachoingresar();
        var view = this.getGuiasprincipaldespacho();
        var tipo_documento = 105;
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        var idtipo= viewIngresa.down('#tipoDocumentoId').getValue();
        var idsucursal= viewIngresa.down('#id_sucursalID').getValue();
        var idcondventa= viewIngresa.down('#tipocondpagoId').getValue();
        var idtransportista= viewIngresa.down('#idtransportista').getValue();        
        var ordencompra= viewIngresa.down('#ordencompraId').getValue();
        var pedido= viewIngresa.down('#pedidoId').getValue();
        var idfactura = viewIngresa.down('#idfactura').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId').getValue();
        var observa = viewIngresa.down('#observaId').getValue();
        var idobserva = viewIngresa.down('#obsId').getValue();
        var numfactura = viewIngresa.down('#numfacturaId').getValue();
        var sucursal = viewIngresa.down('#id_sucursalID').getValue();
        var formadepago = viewIngresa.down('#tipocondpagoId').getValue();
        var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
        var fechavenc = viewIngresa.down('#fechavencId').getValue();
        var stItem = this.getProductosItemsStore();
        var stFactura = this.getGuiasdespachoStore();
        var totalfact = viewIngresa.down('#finaltotalId').getValue();

        

        if(!totalfact){
            Ext.Msg.alert('Ingrese Detalle a la Factura');
            return;              
        }; 
        if(vendedor==0  && tipo_documento.getValue() == 1){
            Ext.Msg.alert('Ingrese Datos del Vendedor');
            return;   
        };
        if(numfactura==0){
            Ext.Msg.alert('Ingrese Datos a La Factura');
            return;   
        };
        if(numfactura==0){
            Ext.Msg.alert('Ingrese Datos a La Factura');
            return;   
        };

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data);          
        });          

        Ext.Ajax.request({
            url: preurl + 'facturas/save',
            params: {
                idcliente: idcliente,
                idfactura: idfactura,                
                idsucursal: idsucursal,
                idbodega: idbodega,
                idcondventa: idcondventa,
                idtipo:idtipo,
                items: Ext.JSON.encode(dataItems),
                observacion: observa,
                idtransportista: idtransportista,
                idobserva: idobserva,
                ordencompra: ordencompra,
                pedido: pedido,
                vendedor : vendedor,
                sucursal : sucursal,
                numfactura : numfactura,
                fechafactura : fechafactura,
                fechavenc: fechavenc,
                formadepago: formadepago,
                tipodocumento : tipo_documento,
                netofactura: viewIngresa.down('#finaltotalnetoId').getValue(),
                ivafactura: viewIngresa.down('#finaltotalivaId').getValue(),
                afectofactura: viewIngresa.down('#finalafectoId').getValue(),
                descuentofactura : viewIngresa.down('#descuentovalorId').getValue(),
                totalfacturas: viewIngresa.down('#finaltotalpostId').getValue()
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idfactura= resp.idfactura;
                 viewIngresa.close();
                 st.reload();
                 //window.open(preurl + 'facturas/exportTXT/?idfactura='+idfactura);
                 window.open(preurl + 'facturas/exportPDF/?idfactura='+idfactura);              

            }
           
        });

        var view = this.getGuiasprincipaldespacho();
        var st = this.getGuiasdespachoStore();
        var idtipo = 105;
        st.proxy.extraParams = {documento: idtipo,
                                idbodega: idbodega}
        st.load(); 
      
        
    },

    editaritem3: function() {

        var view = this.getGuiasdespachoingresar();
        var grid  = view.down('#itemsgridId');
        var idbodega = view.down('#bodegaId').getValue();
        var fechafactura = view.down('#fechafacturaId').getValue();
        var cero = "";

        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_producto = row.data.id_producto;
            var precio = row.data.id_producto;

            Ext.Ajax.request({
            url: preurl + 'facturas/stock2',
            params: {
                idbodega: idbodega,
                id: row.data.id_existencia,
                cantidad: row.data.cantidad,
                producto: row.data.id_producto,
                fechafactura: fechafactura
               },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                 if(resp.cliente){
                    var cliente = resp.cliente;
                    var stock = resp.saldo;
                    view.down('#productoId').setValue(row.data.id_producto);
                    view.down('#idpId').setValue(row.data.id_existencia);
                    view.down('#nombreproductoId').setValue(row.data.nombre);
                    view.down('#codigoId').setValue(row.data.codigo);
                    view.down('#precioId').setValue(row.data.precio);
                    view.down('#preciopromId').setValue(row.data.p_promedio);
                    view.down('#cantidadOriginalId').setValue(resp.saldo);
                    view.down('#cantidadId').setValue(row.data.cantidad);
                    view.down('#stock').setValue(resp.saldo);
                    view.down('#loteId').setValue(row.data.lote);
                    view.down('#fechavencimientoId').setValue(cliente.fecha_vencimiento);
                    view.down('#stock_critico').setValue(cliente.stock_critico);
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

    recalcularFinal: function(){

        var view = this.getGuiasdespachoingresar();
        var stItem = this.getProductosItemsStore();
        var grid2 = view.down('#itemsgridId');
        var pretotal = 0;
        var total = 0;
        var iva = 0;
        var neto = 0;
        var dcto = view.down('#finaldescuentoId').getValue();

        
        stItem.each(function(r){
            pretotal = pretotal + (parseInt(r.data.total))
            //iva = iva + r.data.iva
            //neto = neto + r.data.neto
        });

        neto = (Math.round(pretotal /1.19));
        iva = ((pretotal - neto));
        afecto = neto;
        neto = neto;
        pretotalfinal = pretotal;
        
        view.down('#finaltotalId').setValue(Ext.util.Format.number(pretotalfinal, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(pretotalfinal, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));
        view.down('#descuentovalorId').setValue(Ext.util.Format.number(dcto, '0'));
          
    },

    eliminaritem3: function() {
        var view = this.getGuiasdespachoingresar();
        var grid  = view.down('#itemsgridId');
        var idbodega = view.down('#bodegaId').getValue();
        var fechafactura = view.down('#fechafacturaId').getValue();
        var cero = "";
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];

            var id_producto = row.data.id_producto;
            var precio = row.data.id_producto;

            Ext.Ajax.request({
            url: preurl + 'facturas/stock2',
            params: {
                idbodega: idbodega,
                id: row.data.id_existencia,
                cantidad: row.data.cantidad,
                producto: row.data.id_producto,
                fechafactura: fechafactura
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
        this.recalcularFinal();
    },

    agregarItem3: function() {

        var view = this.getGuiasdespachoingresar();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var stItem = this.getProductosItemsStore();
        var producto = view.down('#productoId').getValue();
        var nombre = view.down('#nombreproductoId').getValue();
        var codigo = view.down('#codigoId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var precioprom  = ((view.down('#preciopromId').getValue()));
        var precioun = ((view.down('#precioId').getValue())/ 1.19);
        var descuento = view.down('#totdescuentoId').getValue(); 
        var iddescuento = view.down('#DescuentoproId').getValue();
        var stock = view.down('#stock').getValue();
        var stockcritico = view.down('#stock_critico').getValue();
        var fechavenc = view.down('#fechavencimientoId').getValue();
        var fechafactura = view.down('#fechafacturaId').getValue();
        var lote = view.down('#loteId').getValue();
        var id = view.down('#idpId').getValue();
        var idbodega = view.down('#bodegaId').getValue();

        var bolEnable = true;

        var stock = stock - cantidad;

        if(fechavenc){
           Ext.Ajax.request({
             url: preurl + 'productos/enviarMail',
                  params: {
                      id:1,
                      nombre: nombre,
                      producto: producto,
                      fecha: fechavenc 
                  },
             success: function(response, opts) {             
               
             }
          });              
            //return false;
        };

        if(stockcritico > 0){

        if(stockcritico > stock ){
            Ext.Msg.alert('Alerta', 'Producto Con Stock Critico Informar ');        
            Ext.Ajax.request({
             url: preurl + 'produccion/enviarMail',
                  params: {
                      id:1,
                      nombre: nombre,
                      producto: producto
                  },
             success: function(response, opts) {             
               
             }
          });              
            //return false;
        };            
        };
       
                 
        if(!producto){            
            Ext.Msg.alert('Alerta', 'Debe Seleccionar un Producto');
            return false;
        };

        if(precio==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Precio Producto');
            return false;
        };

        if(cantidad>cantidadori){
            Ext.Msg.alert('Alerta', 'Cantidad Ingresada de Productos Supera El Stock');
            return false;
        };

        if(cantidad==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Cantidad.');
            return false;
        };
        
        if(rut.length==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Datos a la Factura.');
            return false;           
        };

        Ext.Ajax.request({
            url: preurl + 'facturas/stock',
            params: {
                idbodega: idbodega,
                id: id,
                cantidad: cantidad,
                producto: producto,
                fechafactura: fechafactura
               },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                        
            }
           
        });
               
        if (descuento == 1){            
            var descuento = 0;
            var iddescuento = 0;
        };
        if (descuento > 0){            
            view.down('#tipoDescuentoId').setDisabled(bolEnable);
            view.down('#descuentovalorId').setDisabled(bolEnable);
        };
        if (tipo_documento.getValue() == 2){

             var neto = (Math.round(cantidad * precio) - descuento);
             var iva = 0;
             var total = (Math.round(neto * 1.19));

        }else{
        
        var neto = ((cantidad * precio));
        var tot = (Math.round(neto * 1.19));
        var exists = 0;
        var iva = (tot - neto);
        var neto = (tot - iva);
        var total = ((neto + iva ));     

        };      
                
        stItem.add(new Infosys_web.model.Productos.Item({
            id_existencia: id,
            id_producto: producto,
            id_descuento: iddescuento,
            codigo: codigo,
            fecha_vencimiento: fechavenc,
            nombre: nombre,
            precio: precio,
            p_promedio: precioprom,
            cantidad: cantidad,
            neto: neto,
            stock_critico: stockcritico,
            total: total,
            lote: lote,
            iva: iva,
            dcto: descuento
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
        view.down('#totdescuentoId').setValue(cero1);
        view.down('#DescuentoproId').setValue(cero);
        view.down("#buscarproc").focus();
    },

    cancelaadicional2: function(){

            var view = this.getNobreadicional2();
            var viewnew = this.getDetallestock2();
            var viewIngresa = this.getGuiasdespachoingresar();
            var idproducto = view.down('#productoId').getValue();
            var idpid = view.down('#idpId').getValue();
            var nom_producto = view.down('#nombreproductoId').getValue();
            var codigo = view.down('#codigoId').getValue();
            var valor_producto = view.down('#precioId').getValue();
            var p_promedio = view.down('#preciopromId').getValue();
            var saldo = view.down('#cantidadOriginalId').getValue();
            var lote = view.down('#loteId').getValue();
            var fecha_vencimiento = view.down('#fechavencimientoId').getValue();
            var stock_critico = view.down('#stock_critico').getValue();

            viewIngresa.down('#productoId').setValue(idproducto);
            viewIngresa.down('#idpId').setValue(idpid);
            viewIngresa.down('#nombreproductoId').setValue(nom_producto);
            viewIngresa.down('#codigoId').setValue(codigo);
            viewIngresa.down('#precioId').setValue(valor_producto);
            viewIngresa.down('#preciopromId').setValue(p_promedio);
            viewIngresa.down('#cantidadOriginalId').setValue(saldo);
            viewIngresa.down('#stock').setValue(saldo);
            viewIngresa.down('#loteId').setValue(lote);
            viewIngresa.down('#fechavencimientoId').setValue(fecha_vencimiento);
            viewIngresa.down('#stock_critico').setValue(stock_critico);
            view.close();
            viewnew.close();

        

    },

    selectvistaadicional: function(){

            var view = this.getNobreadicional2();
            var nom_producto = view.down('#nombreadicionalId').getValue();

            if (!nom_producto){
                Ext.Msg.alert('Alerta', 'Debe Ingresar Nombre Producto');
                return;                
            }else{
            var viewnew = this.getDetallestock2();
            var viewIngresa = this.getGuiasdespachoingresar();
            var idproducto = view.down('#productoId').getValue();
            var idpid = view.down('#idpId').getValue();
            var nom_producto = view.down('#nombreadicionalId').getValue();
            var codigo = view.down('#codigoId').getValue();
            var valor_producto = view.down('#precioId').getValue();
            var p_promedio = view.down('#preciopromId').getValue();
            var saldo = view.down('#cantidadOriginalId').getValue();
            var lote = view.down('#loteId').getValue();
            var fecha_vencimiento = view.down('#fechavencimientoId').getValue();
            var stock_critico = view.down('#stock_critico').getValue();

            viewIngresa.down('#productoId').setValue(idproducto);
            viewIngresa.down('#idpId').setValue(idpid);
            viewIngresa.down('#nombreproductoId').setValue(nom_producto);
            viewIngresa.down('#codigoId').setValue(codigo);
            viewIngresa.down('#precioId').setValue(valor_producto);
            viewIngresa.down('#preciopromId').setValue(p_promedio);
            viewIngresa.down('#cantidadOriginalId').setValue(saldo);
            viewIngresa.down('#stock').setValue(saldo);
            viewIngresa.down('#loteId').setValue(lote);
            viewIngresa.down('#fechavencimientoId').setValue(fecha_vencimiento);
            viewIngresa.down('#stock_critico').setValue(stock_critico);
            view.close();
            viewnew.close();                
            }
    },

    seleccionarproductosstock: function(){

        var view = this.getDetallestock2();
        var viewIngresa = this.getGuiasdespachoingresar();
        var tipo = viewIngresa.down('#tipoDocumentoId').getValue();
        var grid  = view.down('grid');
        
        if (grid.getSelectionModel().hasSelection()) {

            if (tipo==105){

                var row = grid.getSelectionModel().getSelection()[0];              

                vista = Ext.create('Infosys_web.view.ventas.Adicional2').show();

                vista.down('#productoId').setValue(row.data.id_producto);
                vista.down('#idpId').setValue(row.data.id);
                vista.down('#nombreproductoId').setValue(row.data.nom_producto);
                vista.down('#codigoId').setValue(row.data.codigo);
                if (tipo==2){
                    vista.down('#precioId').setValue(row.data.valor_producto);
                }else{
                    vista.down('#precioId').setValue(row.data.valor_producto);
                };
                vista.down('#preciopromId').setValue(row.data.p_promedio);
                vista.down('#cantidadOriginalId').setValue(row.data.saldo);
                vista.down('#stock').setValue(row.data.saldo);
                vista.down('#loteId').setValue(row.data.lote);
                vista.down('#fechavencimientoId').setValue(row.data.fecha_vencimiento);
                vista.down('#stock_critico').setValue(row.data.stock_critico);
                               
            }else{
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id_producto);
            viewIngresa.down('#idpId').setValue(row.data.id);
            viewIngresa.down('#nombreproductoId').setValue(row.data.nom_producto);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);
            if (tipo==2){
                viewIngresa.down('#precioId').setValue(row.data.valor_producto);
            }else{
                viewIngresa.down('#precioId').setValue(row.data.valor_producto);
            };
            viewIngresa.down('#preciopromId').setValue(row.data.p_promedio);
            viewIngresa.down('#cantidadOriginalId').setValue(row.data.saldo);
            viewIngresa.down('#stock').setValue(row.data.saldo);
            viewIngresa.down('#loteId').setValue(row.data.lote);
            viewIngresa.down('#fechavencimientoId').setValue(row.data.fecha_vencimiento);
            viewIngresa.down('#stock_critico').setValue(row.data.stock_critico);
            view.close();
            }
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    seleccionarproductos7 : function(){

        var view = this.getBuscarproductos25();
        var viewIngresa1 = this.getGuiasdespachoingresar();
        var bodega = viewIngresa1.down('#bodegaId').getValue();
        var tipo = viewIngresa1.down('#tipoDocumentoId').getValue();
        if(!tipo){
            tipo=1;
        };
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id = (row.data.id);
            var st = this.getExistencias4Store();
            st.proxy.extraParams = {id : id,
                                    bodega : bodega}
            st.load();
            viewIngresa = Ext.create('Infosys_web.view.ventas.detalle_stock2').show();
            viewIngresa.down('#stockId').setValue(row.data.stock);
            if (tipo==2){
                viewIngresa.down('#pventaId').setValue(row.data.p_neto);
            }else{
                viewIngresa.down('#pventaId').setValue(row.data.p_venta);
            };            
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    buscarproductos7: function(){

        var viewIngresa = this.getGuiasdespachoingresar();
        var tipo = viewIngresa.down('#tipoDocumentoId').getValue();
        var codigo = viewIngresa.down('#codigoId').getValue();
        var id = viewIngresa.down('#productoId').getValue();
        if(!codigo){
            var st = this.getProductosfStore();
            Ext.create('Infosys_web.view.productos.BuscarProductos2').show();
            st.load();
        };

        if(codigo){

            var st = this.getExistencias4Store();                 

            Ext.Ajax.request({
            url: preurl + 'productos/buscacodigo?codigo='+codigo,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cero = "";
                var bodega = 1;
                if (resp.success == true){                    
                    if(resp.cliente){
                        var cliente = resp.cliente;                        
                        var id = (cliente.id);
                        viewIngresa.down('#productoId').setValue(cliente.id);
                        viewIngresa.down('#precioId').setValue(cliente.p_venta);
                        viewIngresa.down('#cantidadOriginalId').setValue(cliente.stock);
                        viewIngresa.down('#nombreproductoId').setValue(cliente.nombre); 
                    }
                        st.proxy.extraParams = {id : id,
                                                bodega : bodega}
                        st.load();
                        if(id){
                        viewIngresa = Ext.create('Infosys_web.view.ventas.detalle_stock2').show();
                        viewIngresa.down('#stockId').setValue(cliente.stock);
                        if (tipo==2){
                        viewIngresa.down('#pventaId').setValue(cliente.p_venta);
                        }else{
                        viewIngresa.down('#pventaId').setValue(cliente.p_venta);
                        };  
                        };
                    
                }else{

                      var view = Ext.create('Infosys_web.view.productos.Ingresar').show();
                      view.down("#codigoId").setValue(codigo);
                      
                }

              
            }

        });           

        }
        //this.seleccionarproductos2();
        

        
    },

    special7: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.buscarproductos4()
        }
    },

     buscarproductos4: function(){

        console.log("llegamos");
        
        var viewIngresa = this.getGuiasdespachoingresar();
        var tipo = viewIngresa.down('#tipoDocumentoId').getValue();
        var codigo = viewIngresa.down('#codigoId').getValue();
        var id = viewIngresa.down('#productoId').getValue();
        if(!codigo){
            var st = this.getProductosfStore();
            Ext.create('Infosys_web.view.guiasdespacho.BuscarProductos4').show();
            st.load();
        };

        if(codigo){

            var st = this.getExistencias4Store();                 

            Ext.Ajax.request({
            url: preurl + 'productos/buscacodigo?codigo='+codigo,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cero = "";
                var bodega = 1;
                if (resp.success == true){                    
                    if(resp.cliente){
                        var cliente = resp.cliente;                        
                        var id = (cliente.id);
                        viewIngresa.down('#productoId').setValue(cliente.id);
                        viewIngresa.down('#precioId').setValue(cliente.p_venta);
                        viewIngresa.down('#cantidadOriginalId').setValue(cliente.stock);
                        viewIngresa.down('#nombreproductoId').setValue(cliente.nombre); 
                    }
                        st.proxy.extraParams = {id : id,
                                                bodega : bodega}
                        st.load();
                        if(id){
                        viewIngresa = Ext.create('Infosys_web.view.ventas.detalle_stock2').show();
                        viewIngresa.down('#stockId').setValue(cliente.stock);
                        if (tipo==2){
                        viewIngresa.down('#pventaId').setValue(cliente.p_venta);
                        }else{
                        viewIngresa.down('#pventaId').setValue(cliente.p_venta);
                        };  
                        };
                    
                }else{

                      var view = Ext.create('Infosys_web.view.productos.Ingresar').show();
                      view.down("#codigoId").setValue(codigo);
                      
                }

              
            }

        });           

        }
        //this.seleccionarproductos2();
        

        
    },

    buscarclientes3 : function(){

        var view = this.getGuiasdespachobuscarclientes3()
        var st = this.getClientesStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : "Nombre"}
        st.load();
    },

    buscarclientes4 : function(){

        var view = this.getGuiasdespachobuscarclientes4()
        var st = this.getClientesStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : "Nombre"}
        st.load();
    },

    seleccionarclienteguias3: function(){

        var view = this.getGuiasdespachobuscarclientes3();
        var viewIngresa = this.getGuiasdespachoingresar();
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
            var bolEnable = false;
            if (row.data.id_pago == 1){

                viewIngresa.down('#DescuentoproId').setDisabled(bolEnable);
                viewIngresa.down('#tipoDescuentoId').setDisabled(bolEnable);
                viewIngresa.down('#descuentovalorId').setDisabled(bolEnable);
                
            };
            if (row.data.id_pago == 6){

                 viewIngresa.down('#DescuentoproId').setDisabled(bolEnable);
                 viewIngresa.down('#tipoDescuentoId').setDisabled(bolEnable);
                 viewIngresa.down('#descuentovalorId').setDisabled(bolEnable);
                
            };
            if (row.data.id_pago == 7){

                 view.down('#DescuentoproId').setDisabled(bolEnable);
                 view.down('#tipoDescuentoId').setDisabled(bolEnable);
                 view.down('#descuentovalorId').setDisabled(bolEnable);
                
            };          
            

            if (dias > 0){
        
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

        };
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },    


seleccionarclienteguias4: function(){

        var view = this.getGuiasdespachobuscarclientes4();
        var viewIngresa = this.getGuiasglosaingresar();
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
            var bolEnable = false;
            if (row.data.id_pago == 1){

                viewIngresa.down('#DescuentoproId').setDisabled(bolEnable);
                viewIngresa.down('#tipoDescuentoId').setDisabled(bolEnable);
                viewIngresa.down('#descuentovalorId').setDisabled(bolEnable);
                
            };
            if (row.data.id_pago == 6){

                 viewIngresa.down('#DescuentoproId').setDisabled(bolEnable);
                 viewIngresa.down('#tipoDescuentoId').setDisabled(bolEnable);
                 viewIngresa.down('#descuentovalorId').setDisabled(bolEnable);
                
            };
            if (row.data.id_pago == 7){

                 view.down('#DescuentoproId').setDisabled(bolEnable);
                 view.down('#tipoDescuentoId').setDisabled(bolEnable);
                 view.down('#descuentovalorId').setDisabled(bolEnable);
                
            };          
            

            if (dias > 0){
        
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

        };
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },    


     validarutD: function(){

        var view =this.getGuiasdespachoingresar();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

        if(numero==0){
            var edit = Ext.create('Infosys_web.view.guiasdespacho.BuscarClientes3');            
                  
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
                if (resp.success == true){                    
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        if (cliente.estado=="3"){
                            view.down("#rutId").setValue(cero);
                            Ext.Msg.alert('Cliente Bloqueado');
                            return;
                        };
                        if (cliente.estado=="4"){
                            view.down("#rutId").setValue(cero);
                            Ext.Msg.alert('Cliente Protestos Vigentes');
                            return;                            
                        };
                        view.down("#id_cliente").setValue(cliente.id)
                        view.down("#nombre_id").setValue(cliente.nombres)
                        view.down("#tipoCiudadId").setValue(cliente.nombre_ciudad)
                        view.down("#tipoComunaId").setValue(cliente.nombre_comuna)
                        view.down("#tipoVendedorId").setValue(cliente.id_vendedor)
                        view.down("#giroId").setValue(cliente.giro)
                        view.down("#direccionId").setValue(cliente.direccion)    
                        view.down("#rutId").setValue(rut)
                        view.down("#tipocondpagoId").setValue(cliente.id_pago)                        
                        view.down("#buscarproc").focus()  
                        var condicion = view.down('#tipocondpagoId');
                        var fechafactura = view.down('#fechafacturaId').getValue();
                        var stCombo = condicion.getStore();
                        var record = stCombo.findRecord('id', condicion.getValue()).data;
                        dias = record.dias;            

                        if (dias > 0){

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
                                             
                    }else{
                         var viewedit = Ext.create('Infosys_web.view.clientes.Ingresar').show();                        
                         viewedit.down("#rutId").setValue(rut);  
                    }
                    
                }else{
                      Ext.Msg.alert('Informacion', 'Rut Incorrecto');
                      view.down("#rutId").setValue(cero);
                      return;
                      
                }

              
            }

        });       
        }
    },

    cancelar2: function(){

        var viewIngresa = this.getGuiasdespachoingresar();
        var view = this.getGuiasprincipaldespacho();
        var idbodega = view.down('#bodegaId').getValue();
        var documento = 105;
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

   buscarguiasdirectas: function(){

        var view = this.getGuiasprincipaldespacho();
        var st = this.getGuiasdespachoStore();
        var tipo = 105;
        var opcion = view.down('#tipoSeleccionId').getValue();
        var nombre = view.down('#nombreId').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion,
                                documento: tipo,
                                idbodega: idbodega}
        st.load();

        
    },

    despliegadocumentos3: function(){

        var view = this.getGuiasprincipaldespacho();
        var idbodega = view.down('#bodegaId').getValue();
        var idtipo = 105;
        var st = this.getGuiasdespachoStore();
        st.proxy.extraParams = {documento: idtipo,
                                idbodega: idbodega }
        st.load();       
    },

    iguias: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'guiasprincipaldespacho'});
    },

    iguiasdespacho: function(){

        var view = this.getGuiasprincipaldespacho();
        var idbodega = view.down('#bodegaId').getValue();
        if(!idbodega){
            Ext.Msg.alert('Alerta', 'Debe Elegir Bodega');
            return;    
        }else{ 

        var nombre = 105;
        var descripcion = "GUIA DESPACHO ELECTRONICA";    
        habilita = false;
        if(nombre == 101 || nombre == 103 || nombre == 105){ // FACTURA ELECTRONICA o FACTURA EXENTA

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
                    var view = Ext.create('Infosys_web.view.guiasdespacho.GuiasDespacho').show();
                    view.down('#numfacturaId').setValue(nuevo_folio);
                    view.down('#nomdocumentoId').setValue(descripcion);
                    view.down('#idfolio').setValue(id_folio);
                    view.down('#tipodocumentoId').setValue(nombre);
                    view.down('#tipoDocumentoId').setValue(nombre);                    
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

    cancelar: function(){

        var viewIngresa = this.getFacturaguias();
        var view = this.getGuiasprincipalpendientes();
        var idbodega = view.down('#bodegaId').getValue();
        var documento = 101;
        var numero = viewIngresa.down('#numfacturaId').getValue();
        var folio = viewIngresa.down('#idfolio').getValue();
        
        console.log(documento);    

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

    despliegadocumentos: function(){

        var view = this.getGuiasprincipal();
        var idbodega = view.down('#bodegaId').getValue();
        var idtipo = 105;
        var st = this.getGuiasdespachoStore();
        st.proxy.extraParams = {documento: idtipo,
                                idbodega: idbodega }
        st.load();       
    },

    despliegadocumentos2: function(){

        var view = this.getGuiasprincipalpendientes();
        var idbodega = view.down('#bodegaId').getValue();
        var idtipo = 3;
        var st = this.getGuiasdespachopendientes2Store();
        st.proxy.extraParams = {documento: idtipo,
                                idbodega: idbodega }
        st.load();       
    },

    marcarguias: function(){

        Ext.Ajax.request({
            url: preurl + 'guias/procesomarca',
            params: {
                id : 1
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {

                     Ext.Msg.alert('Alerta', 'Proceso Correcto');
                        return; 

                }else{

                        Ext.Msg.alert('Alerta', 'Proceso InCorrecto');
                return; 

                }                
            }
           
        });
        

    },


    eliminaritem: function() {

        var view = this.getDespachofactura();
        var grid  = view.down('#itemsgridId');
        var totalnue = view.down('#finaltotalpostId').getValue();
        var netonue = view.down('#finaltotalnetoId').getValue();
        var ivanue = view.down('#finaltotalivaId').getValue();
        var afectonue = view.down('#finalafectoId').getValue();
        cero1 = "";
        cero = 0;
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var totalnue = totalnue - (row.data.totaliva);
            if (totalnue ==0){
                Ext.Msg.alert('Alerta', 'No puede Eliminar Ultimo Registro');
                return;                
            }else{
                var ivanue = ivanue - (row.data.iva);
                var afectonue = afectonue - (row.data.neto);
                var netonue = netonue - (row.data.neto);
                grid.getStore().remove(row);
            };
            view.down('#finaltotalId').setValue(Ext.util.Format.number(totalnue, '0,000'));
            view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalnue, '0'));
            view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netonue, '0'));
            view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivanue, '0'));
            view.down('#finalafectoId').setValue(Ext.util.Format.number(afectonue, '0'));
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    exportarexcelguias: function(){
              
           Ext.create('Infosys_web.view.guiasdespacho.Exportar').show();
    },

    generarguiaspdf: function(){
        var view = this.getGuiasprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            window.open(preurl +'facturas/exportPDF/?idfactura=' + row.data.id)            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    generarguiaspdf2: function(){
        var view = this.getGuiasprincipaldespacho();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            window.open(preurl +'facturas/exportPDF/?idfactura=' + row.data.id)            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    exportarExcelFormulario: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getGuiasprincipal()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getFormularioexportarguias()
        var viewnew =this.getGuiasprincipal()
        var fecha = view.down('#fechaId').getSubmitValue();
        var opcion = viewnew.down('#tipoSeleccionId').getValue()
        var nombre = viewnew.down('#nombreId').getSubmitValue();
        var fecha2 = view.down('#fecha2Id').getSubmitValue();
        var opcion = view.down('#tipoId').getSubmitValue();

        console.log(opcion)

        if (fecha > fecha2) {
        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };

        if (opcion == "LIBRO GUIAS"){

            window.open(preurl + 'adminServicesExcel/exportarExcellibroGuias?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2);
            view.close();
            
            

        }else{

            window.open(preurl + 'adminServicesExcel/exportarExcelGuias?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2+'&opcion='+opcion+'&nombre='+nombre);
            view.close();

          

        }

       
 
    },

    seleccionartodas2: function(){

        var stItem1 = this.getDespachafacturaStore();
        var stItem = this.getProductosItemsStore();
        var view = this.getBuscarproductosfacturadirecta();
        var viewIngresa = this.getDespachofactura();
        var totalfin = viewIngresa.down('#finaltotalpostId').getValue();
        var netofin = viewIngresa.down('#finalafectoId').getValue();
        var ivafin = viewIngresa.down('#finaltotalivaId').getValue();
        var totalfactura = viewIngresa.down('#totalId').getValue();
        var netofactura = viewIngresa.down('#netofacId').getValue();
        var ivafactura = viewIngresa.down('#ivafacId').getValue();
        var descuento = viewIngresa.down('#descuentoId').getValue();       
        
        
        stItem1.each(function(r){

            producto = r.data.id_producto,
            nomproducto = r.data.nombre,
            precio = r.data.p_venta,
            cantidad = r.data.stock,
            neto = ((cantidad * precio)),
            tot = ((cantidad * precio)),
            neto = (parseInt(neto / 1.19)),
            iva = (tot - neto ),
            total = ((neto + iva )),
            neto = (total - iva),
            
            stItem.add(new Infosys_web.model.Productos.Item({
                id: producto,
                idproducto: producto,
                nombre: nomproducto,
                precio: precio,
                cantidad: cantidad,
                neto: neto,
                totaliva: total,
                iva: iva          
            }));

            totalfin = totalfin + parseInt(total),
            netofin = netofin + parseInt(neto),
            ivafin = ivafin + parseInt(iva)
                      
        });

        if (totalfactura < totalfin){
            viewIngresa.down('#descuentofinalId').setValue(Ext.util.Format.number(descuento, '0'));
            totalfin = totalfactura;
            netofin = netofactura;
            ivafin = ivafactura;
        };

        viewIngresa.down('#finaltotalId').setValue(Ext.util.Format.number(totalfin, '0,000'));
        viewIngresa.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalfin, '0'));
        viewIngresa.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netofin, '0'));
        viewIngresa.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivafin, '0'));
        viewIngresa.down('#finalafectoId').setValue(Ext.util.Format.number(netofin, '0'));
          
        view.close();
             
    },

    grabarfacturadespacho: function() {

        var viewIngresa = this.getDespachofactura();
        var tipo_documento = viewIngresa.down('#tipodocumentoId').getValue();
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var idbodega = viewIngresa.down('#bodegaId').getValue();
        var idtipo= viewIngresa.down('#tipodocumentoId').getValue();
        var idsucursal= viewIngresa.down('#id_sucursalID').getValue();
        var idcondventa= viewIngresa.down('#tipocondpagoId').getValue();
        var idfactura = viewIngresa.down('#numfacturaId').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId').getValue();
        var ordencompra= viewIngresa.down('#ordencompraId').getValue();
        var numdocumento = viewIngresa.down('#numfacturaId').getValue();
        var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
        var numfactura_asoc = viewIngresa.down('#numfactId').getValue();
        var idfactura_asoc = viewIngresa.down('#factId').getValue();
        
        var fechavenc = viewIngresa.down('#fechavencId').getValue();
        var stItem = this.getProductosItemsStore();
        var stGuias = this.getGuiasdespachoStore();

        if(numdocumento==0){
            Ext.Msg.alert('Ingrese Datos a La Factura');
            return;   
        }

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'facturas/save5',
            params: {
                idcliente: idcliente,
                idbodega: idbodega,
                idfactura: idfactura,
                numdocumento: numdocumento,
                idsucursal: idsucursal,
                idcondventa: idcondventa,
                ordencompra: ordencompra,
                idtipo: idtipo,
                items: Ext.JSON.encode(dataItems),
                vendedor : vendedor,
                numfactura_asoc : numfactura_asoc,
                idfactura_asoc : idfactura_asoc,
                fechafactura : fechafactura,
                fechavenc: fechavenc,
                tipodocumento : tipo_documento,
                netofactura: viewIngresa.down('#finaltotalnetoId').getValue(),
                ivafactura: viewIngresa.down('#finaltotalivaId').getValue(),
                afectofactura: viewIngresa.down('#finalafectoId').getValue(),
                totalfacturas: viewIngresa.down('#finaltotalpostId').getValue(),
                totaldescuento: viewIngresa.down('#descuentofinalId').getValue()
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idfactura= resp.idfactura;
                 viewIngresa.close();
                 stGuias.load();
                 window.open(preurl + 'facturas/exportPDF/?idfactura='+idfactura);

            }
           
        });      
        
    },

    agregarItem2: function() {

        var view = this.getDespachofactura();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var stItem = this.getProductosItemsStore();
        var producto = view.down('#productoId').getValue();
        var nomproducto = view.down('#nomproductoId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var idfactura = view.down('#factId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var precioun = ((view.down('#precioId').getValue())/ 1.19);
        var totalfactura = view.down('#totalId').getValue();
        var netofactura = view.down('#netofacId').getValue();
        var ivafactura = view.down('#ivafacId').getValue();
        var descuento = view.down('#descuentoId').getValue();       
        var neto = ((cantidad * precio));
        var tot = ((cantidad * precio));
        var neto = (parseInt(neto / 1.19));
        var exists = 0;
        var iva = (tot - neto );
        var totaliva = ((neto + iva ));
        var neto = (totaliva - iva);

        var totalfin = view.down('#finaltotalpostId').getValue();
        var netofin = view.down('#finalafectoId').getValue();
        var ivafin = view.down('#finaltotalivaId').getValue();
        
        if(precio==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Precio Producto');
            return false;
        };

        if(cantidad>cantidadori){
            Ext.Msg.alert('Alerta', 'Cantidad Ingresada de Productos Supera El Stock');
            return false;
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
                    url: preurl + 'notacredito/validaproducto',
                params: {
                    idproducto: producto,
                    idfactura : idfactura
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
                    view.down('#cantidadId').setValue(cero);
                    view.down('#precioId').setValue(cero2);
                    view.down('#cantidadOriginalId').setValue(cero);
                    view.down("#buscarproc").focus();

                    Ext.Msg.alert('Alerta', 'Producto No corresponde a Factura');
                    return false;
                    

                   }else{

                    if(cantidad>canti){

                        cero="";
                        cero2=0;
                        view.down('#codigoId').setValue(cero);
                        view.down('#productoId').setValue(cero);
                        view.down('#cantidadId').setValue(cero);
                        view.down('#precioId').setValue(cero2);
                        view.down('#cantidadOriginalId').setValue(cero);
                        view.down("#buscarproc").focus();


                        Ext.Msg.alert('Alerta', 'Cantidad de Producto Mayor a lo Vendido');
                        return false;

                    }else{
                    

                    stItem.each(function(r){
                    if(r.data.id == producto){
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
                        id: producto,
                        idproducto: producto,
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
                    
                    if (totalfactura < totalfin){
                        view.down('#descuentofinalId').setValue(Ext.util.Format.number(descuento, '0'));
                        totalfin = totalfactura;
                        netofin = netofactura;
                        ivafin = ivafactura;
                    };
                  
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

            Ext.Msg.alert('Alerta', 'Debe Seleccionar un Factura');
            return false;           

        };
    },

    seleccionarfactura: function(){

        var view = this.getBuscarfacturasdespacho();
        var viewIngresa = this.getDespachofactura();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#facturaId').setValue(row.data.id);
            viewIngresa.down('#numfactId').setValue(row.data.num_factura);
            viewIngresa.down('#descuentoId').setValue(row.data.descuento);
            viewIngresa.down('#netofacId').setValue(row.data.neto);
            viewIngresa.down('#ivafacId').setValue(row.data.iva);
            viewIngresa.down('#totalId').setValue(row.data.totalfactura);
            viewIngresa.down('#totfactId').setValue(row.data.totalfactura);
            viewIngresa.down('#factId').setValue(row.data.id);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

    },

    seleccionarproductos: function(){

        var view = this.getBuscarproductosfacturadirecta();
        var viewIngresa = this.getDespachofactura();
        var idfactura = viewIngresa.down('#factId').getValue();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id_producto);
            var idproducto = (row.data.id_producto);
            var nomproducto = (row.data.nombre);

            Ext.Ajax.request({
                    url: preurl + 'notacredito/validaproducto',
                params: {
                    idproducto: idproducto,
                    idfactura : idfactura
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
                      viewIngresa.down('#cantidadId').setValue(canti);
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

    buscarproductos: function(){

        var view = this.getDespachofactura();
        var st = this.getDespachafacturaStore()
        var nombre = view.down('#facturaId').getValue()
        st.proxy.extraParams = {nombre : nombre}
        st.load();
        Ext.create('Infosys_web.view.guiasdespacho.BuscarProductos').show();
    },

    seleccionarsucursalcliente: function(){

        var view = this.getBuscarsucursalesclientesfacturas();
        var viewIngresa = this.getDespachofactura();
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

    seleccionarsucursalcliente2: function(){

        var view = this.getBuscarsucursalesclientesfacturas2();
        var viewIngresa = this.getFacturaguias();
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

    buscarsucursaldespacho: function(){

       var busca = this.getFacturaguias()
       var nombre = busca.down('#id_cliente').getValue();
       
       if (nombre){
         var edit = Ext.create('Infosys_web.view.guiasdespacho.BuscarSucursales2').show();
          var st = this.getSucursales_clientesStore();
          st.proxy.extraParams = {nombre : nombre};
          st.load();
       }else {
          Ext.Msg.alert('Alerta', 'Debe seleccionar Cliente.');
            return;
       }
      
    },

    buscarsucursalfacturadespacho: function(){

       var busca = this.getDespachofactura()
       var nombre = busca.down('#id_cliente').getValue();
       
       if (nombre){
         var edit = Ext.create('Infosys_web.view.guiasdespacho.BuscarSucursales').show();
          var st = this.getSucursales_clientesStore();
          st.proxy.extraParams = {nombre : nombre};
          st.load();
       }else {
          Ext.Msg.alert('Alerta', 'Debe seleccionar Cliente.');
            return;
       }
      
    },

    buscarfactura : function() {

       var busca = this.getDespachofactura()
       var nombre = busca.down('#id_cliente').getValue();
    
       if (nombre){
          var edit =  Ext.create('Infosys_web.view.guiasdespacho.BuscarFacturas').show();
          var st = this.getFactura4Store();
          st.proxy.extraParams = {nombre : nombre};
          st.load();
       }else {
          Ext.Msg.alert('Alerta', 'Debe seleccionar Cliente.');
            return;
       }

    },

    

     special3: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut3()
        }
    },

    validarut3: function(){

        var view =this.getDespachofactura();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;
       
        if(numero==0){
            var edit = Ext.create('Infosys_web.view.guiasdespacho.BuscarClientes');            
                  
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
                        view.down("#tipocondpagoId").setValue(cliente.id_pago)
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

    special6: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut2()
        }
    },

    validarut2: function(){

        var view = this.getObservacionesfacturasguias();
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

    ingresaobs: function(){

        var view = this.getObservacionesfacturasguias();
        var viewIngresar = this.getFacturaguias();                
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

    observaciones: function(){

        var viewIngresa = this.getFacturaguias();
        var numfactura = viewIngresa.down('#numfacturaId').getValue();
        var view = Ext.create('Infosys_web.view.guiasdespacho.Observaciones').show();
        view.down("#rutId").focus();
        view.down("#FactId").setValue(numfactura);

    },

    observacionesglosa: function(){

        var viewIngresa = this.getGuiasglosaingresar();
        var numfactura = viewIngresa.down('#numfacturaId').getValue();
        var view = Ext.create('Infosys_web.view.guiasdespacho.Observacionesguiasglosa').show();
        view.down("#rutId").focus();
        view.down("#FactId").setValue(numfactura);

    },

    selecttipocondpago: function() {        
        
        var view =this.getFacturaguias();
        var condicion = view.down('#tipocondpagoId');
        var fechafactura = view.down('#fechafacturaId').getValue();              

        var stCombo = condicion.getStore();
        var record = stCombo.findRecord('id', condicion.getValue()).data;
        dias = record.dias;


        if (dias > 0){
        
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

            var fecha_final = fechafactura;
            view.down("#fechavencId").setValue(fecha_final);


        };
       
            
    },

     selecttipocondpago3: function() {        
        
        var view =this.getGuiasdespachoingresar();
        var condicion = view.down('#tipocondpagoId');
        var fechafactura = view.down('#fechafacturaId').getValue();              

        var stCombo = condicion.getStore();
        var record = stCombo.findRecord('id', condicion.getValue()).data;
        dias = record.dias;


        if (dias > 0){
        
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

            var fecha_final = fechafactura;
            view.down("#fechavencId").setValue(fecha_final);


        };
       
            
    },

    selecttipocondpago2: function() {
        
        
        var view =this.getDespachofactura();
        var condicion = view.down('#tipocondpagoId');
        var fechafactura = view.down('#fechafacturaId').getValue();
                

        var stCombo = condicion.getStore();
        var record = stCombo.findRecord('id', condicion.getValue()).data;
        dias = record.dias;


        if (dias > 0){
        
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

            var fecha_final = fechafactura;
            view.down("#fechavencId").setValue(fecha_final);


        };
       
            
    },

    grabarfactura: function() {

        var viewIngresa = this.getFacturaguias();
        var tipo_documento = viewIngresa.down('#tipoDocumentoId');
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var idbodega = viewIngresa.down('#bodegaId').getValue();
        var idsucursal= viewIngresa.down('#id_sucursalID').getValue();
        var idcondventa= viewIngresa.down('#tipocondpagoId').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId').getValue();
        var numfactura = viewIngresa.down('#numfacturaId').getValue();
        var ordencompra= viewIngresa.down('#ordencompraId').getValue();
        var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
        var observa = viewIngresa.down('#observaId').getValue();
        var idobserva = viewIngresa.down('#obsId').getValue();
        var fechavenc = viewIngresa.down('#fechavencId').getValue();
        var stItem = this.getGuiasdespachoItemsStore();
        var stFactura = this.getGuiasdespachopendientesStore();
        var stFactura = this.getGuiasdespachopendientes2Store();

        if(vendedor==0  && tipo_documento.getValue() == 1){
            Ext.Msg.alert('Ingrese Datos del Vendedor');
            return;   
        }

        if(numfactura==0){
            Ext.Msg.alert('Ingrese Datos a La Factura');
            return;   
            }

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'guias/save',
            params: {
                idcliente: idcliente,
                idbodega: idbodega,
                idsucursal: idsucursal,
                idcondventa: idcondventa,
                items: Ext.JSON.encode(dataItems),
                observacion: observa,
                ordencompra: ordencompra,
                idobserva: idobserva,
                vendedor : vendedor,
                numfactura : numfactura,
                fechafactura : fechafactura,
                fechavenc: fechavenc,
                netofactura: viewIngresa.down('#finaltotalnetoId').getValue(),
                ivafactura: viewIngresa.down('#finaltotalivaId').getValue(),
                afectofactura: viewIngresa.down('#finalafectoId').getValue(),
                totalfacturas: viewIngresa.down('#finaltotalpostId').getValue(),
               
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idfactura= resp.idfactura;
                viewIngresa.close();
                stFactura.reload();
                window.open(preurl + 'facturas/exportPDF/?idfactura='+idfactura);
            }
           
        });

        var stItem2 = this.getGuiasdespachopendientes2Store();
        stItem2.reload();
        
    },

    seleccionartodas: function(){

        var stItem1 = this.getGuiasdespachopendientesStore();
        var stItem2 = this.getGuiasdespachopendientes2Store();
        var stItem = this.getGuiasdespachoItemsStore();
        var view = this.getBuscarguias();
        var viewIngresa = this.getFacturaguias();
        var totalfin = viewIngresa.down('#finaltotalpostId').getValue();
        var netofin = viewIngresa.down('#finalafectoId').getValue();
        var ivafin = viewIngresa.down('#finaltotalivaId').getValue();
        console.log("llegamos");

        
        stItem1.each(function(r){

            idguia = r.data.id,
            numguia = r.data.num_factura,
            neto = r.data.neto,
            iva = r.data.iva,
            total = r.data.totalfactura,

            stItem.add(new Infosys_web.model.Guiasdespacho.Item({
            id_guia: idguia,
            num_guia: numguia,
            neto: neto,
            iva: iva,
            total: total
            }));

            totalfin = totalfin + parseInt(r.data.totalfactura),
            netofin = netofin + parseInt(r.data.neto),
            ivafin = ivafin + parseInt(r.data.iva)
                      
        });

        viewIngresa.down('#finaltotalId').setValue(Ext.util.Format.number(totalfin, '0,000'));
        viewIngresa.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalfin, '0'));
        viewIngresa.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netofin, '0'));
        viewIngresa.down('#finaltotalnetodId').setValue(Ext.util.Format.number(netofin, '0,000'));
        viewIngresa.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivafin, '0'));
        viewIngresa.down('#finaltotalivadId').setValue(Ext.util.Format.number(ivafin, '0,000'));
        viewIngresa.down('#finalafectoId').setValue(Ext.util.Format.number(netofin, '0'));
        viewIngresa.down('#finalafectodId').setValue(Ext.util.Format.number(netofin, '0,000'));
               
        view.close();
        stItem2.reload();
       
    },

    seleccionarguias: function(){

        var view = this.getBuscarguias();
        var viewIngresa = this.getFacturaguias();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#idguiaId').setValue(row.data.id);
            viewIngresa.down('#numguiaId').setValue(row.data.num_factura);
            viewIngresa.down('#netoId').setValue(row.data.neto);
            viewIngresa.down('#ivaId').setValue(row.data.iva);
            viewIngresa.down('#totalId').setValue(row.data.totalfactura);
            grid.getStore().remove(row);
            view.close(); 
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },   

    buscarguias : function() {

       var busca = this.getFacturaguias()
       var nombre = busca.down('#id_cliente').getValue();
       var bodega = busca.down('#bodegaId').getValue();
       var opcion = "Id";
       console.log("llegamos a buscar guias");
       if (nombre){
          var st = this.getGuiasdespachopendientesStore();          
          var edit =  Ext.create('Infosys_web.view.guiasdespacho.BuscarGuias').show();
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

    buscarguias2 : function() {

       var busca = this.getBuscarguias()
       var id_cliente = busca.down('#clienteId').getValue();
       var nombre = busca.down('#nombreId').getValue();
       var opcion = "Numero";
       if (nombre){
          var st = this.getGuiasdespachopendientesStore();
          st.proxy.extraParams = {nombre : nombre,
                                  opcion : opcion,
                                  idcliente: id_cliente};
          st.load();
       }

    },

    eliminaritem3: function() {

        console.log("LLegamos Eliminar Guias");
        var espacios = "";
        var ceros= 0;
        var view = this.getFacturaguias();
        var totalfin = view.down('#finaltotalpostId').getValue();
        var netofin = view.down('#finalafectoId').getValue();
        var ivafin = view.down('#finaltotalivaId').getValue();
       
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var idguia = row.data.id_guia;
            var neto = row.data.neto;
            var iva = row.data.iva;
            var total = row.data.total;
            var totalfin = totalfin - total;
            var netofin = netofin - neto;
            var ivafin= ivafin - iva;
            grid.getStore().remove(row); 
                    
            Ext.Ajax.request({
                url: preurl + 'guias/desmarcarguias',
                params: {
                    factura: idguia,
                },
                success: function(response){
                   var resp = Ext.JSON.decode(response.responseText);
                   if (resp.success == true) {
                    view.down('#idguiaId').setValue(espacios);
                    view.down('#numguiaId').setValue(espacios);
                    view.down('#ivaId').setValue(ceros);
                    view.down('#totalId').setValue(ceros);
                    view.down('#netoId').setValue(ceros);                                    
                    view.down('#finaltotalId').setValue(Ext.util.Format.number(totalfin, '0,000'));
                    view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalfin, '0'));
                    view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netofin, '0'));
                    view.down('#finaltotalnetodId').setValue(Ext.util.Format.number(netofin, '0,000'));
                    view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivafin, '0'));
                    view.down('#finaltotalivadId').setValue(Ext.util.Format.number(ivafin, '0,000'));
                    view.down('#finalafectoId').setValue(Ext.util.Format.number(netofin, '0'));
                    view.down('#finalafectodId').setValue(Ext.util.Format.number(netofin, '0,000'));
                }
            }

            });
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

    },    

    agregarItem: function() {

        var view = this.getFacturaguias();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var neto = view.down('#netoId').getValue();
        var idguia = view.down('#idguiaId').getValue();
        var numguia = view.down('#numguiaId').getValue();
        var iva = view.down('#ivaId').getValue();
        var total = view.down('#totalId').getValue();
        var stItem = this.getGuiasdespachoItemsStore();
        var totalfin = view.down('#finaltotalpostId').getValue();
        var netofin = view.down('#finalafectoId').getValue();
        var ivafin = view.down('#finaltotalivaId').getValue();
        var totalfin = totalfin + total;
        var netofin = netofin + neto;
        var ivafin= ivafin + iva;
        var espacios = "";
        var ceros = 0;
        console.log("Leegamos Agregar Guias");       
                
        if(neto==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Valores');
            return false;
        };       
                    
        if(rut.length==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Datos a la Factura.');
            return false;
        };
  
        stItem.add(new Infosys_web.model.Guiasdespacho.Item({
            id_guia: idguia,
            num_guia: numguia,
            neto: neto,
            iva: iva,
            total: total
        }));
       
        view.down('#finaltotalId').setValue(Ext.util.Format.number(totalfin, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalfin, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netofin, '0'));
        view.down('#finaltotalnetodId').setValue(Ext.util.Format.number(netofin, '0,000'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivafin, '0'));
        view.down('#finaltotalivadId').setValue(Ext.util.Format.number(ivafin, '0,000'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(netofin, '0'));
        view.down('#finalafectodId').setValue(Ext.util.Format.number(netofin, '0,000'));

       Ext.Ajax.request({
                url: preurl + 'guias/marcarguias',
                params: {
                    factura: idguia,
                },
                success: function(response){
                   var resp = Ext.JSON.decode(response.responseText);
                   if (resp.success == true) {
                    view.down('#idguiaId').setValue(espacios);
                    view.down('#numguiaId').setValue(espacios);
                    view.down('#ivaId').setValue(ceros);
                    view.down('#totalId').setValue(ceros);
                    view.down('#netoId').setValue(ceros);
                }
            }
           
        });
        
    },

    eliminaritem2: function() {
        var view = this.getCotizacioneditar();
        var nueneto = view.down('#finalpretotalnId').getValue();
        var nueiva =  view.down('#ivadId').getValue();
        var nuetotal = view.down('#finaltotalpostId').getValue();
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var nuetotal = (parseInt(nuetotal) - parseInt(row.data.total));
            var neto = (parseInt((row.data.total)/1.19));
            var nueneto = nueneto - neto;
            var nueiva = nuetotal - nueneto;
            view.down('#finaltotalId').setValue(Ext.util.Format.number(nuetotal, '0,000'));
            view.down('#finaltotalpostId').setValue(Ext.util.Format.number(nuetotal, '0'));
            view.down('#finalpretotalId').setValue(Ext.util.Format.number(nueneto, '0'));
            view.down('#ivaId').setValue(Ext.util.Format.number(nueiva, '0'));
            view.down('#ivadId').setValue(Ext.util.Format.number(nueiva, '0'));
            view.down('#finalpretotalnId').setValue(Ext.util.Format.number(nueneto, '0'));       

            grid.getStore().remove(row);
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
      
    },

    seleccionarclienteguias: function(){

        var view = this.getGuiasdespachobuscarclientes();
        var viewIngresa = this.getDespachofactura();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            console.log(row.data.id);
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

            if (dias > 0){
        
            Ext.Ajax.request({
                url: preurl + 'facturas/calculofechas',
                params: {
                    factura: dias,
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
        }
       
    },

    seleccionarclienteguias2: function(){

        var view = this.getGuiasdespachobuscarclientes2();
        var viewIngresa = this.getFacturaguias();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            console.log(row.data.id);
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

            if (dias > 0){
        
            Ext.Ajax.request({
                url: preurl + 'facturas/calculofechas',
                params: {
                    factura: dias,
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
        }
       
    },

    buscarclientes : function(){

        var view = this.getGuiasdespachobuscarclientes()
        var st = this.getClientesStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : "Nombre"}
        st.load();
    },

    buscarclientes2 : function(){

        var view = this.getGuiasdespachobuscarclientes2()
        var st = this.getClientesStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : "Nombre"}
        st.load();
    },


    special: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut()
        }
    },

    special5: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarutD()
        }
    },

    validarut: function(){

        var view =this.getFacturaguias();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

       
        if(numero==0){
            var edit = Ext.create('Infosys_web.view.guiasdespacho.BuscarClientes');            
                  
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
                        view.down("#tipocondpagoId").setValue(cliente.id_pago)
                        view.down("#direccionId").setValue(cliente.direccion)
                        view.down("#rutId").setValue(rut)
                        var condicion = view.down('#tipocondpagoId');
                        var fechafactura = view.down('#fechafacturaId').getValue();
                        var stCombo = condicion.getStore();
                        var record = stCombo.findRecord('id', condicion.getValue()).data;
                        dias = record.dias;
                        if (dias > 0){

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

    validarut21: function(){

        var view =this.getFacturaguias();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

       
        if(numero==0){
            var edit = Ext.create('Infosys_web.view.guiasdespacho.BuscarClientes2');            
                  
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
                        view.down("#tipocondpagoId").setValue(cliente.id_pago)
                        view.down("#direccionId").setValue(cliente.direccion)
                        view.down("#rutId").setValue(rut)
                        var condicion = view.down('#tipocondpagoId');
                        var fechafactura = view.down('#fechafacturaId').getValue();
                        var stCombo = condicion.getStore();
                        var record = stCombo.findRecord('id', condicion.getValue()).data;
                        dias = record.dias; 
                        if (dias > 0){
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



    factguia: function(){

        var view = this.getGuiasprincipalpendientes();
        var idbodega = view.down('#bodegaId').getValue();
        if(!idbodega){
            Ext.Msg.alert('Alerta', 'Debe Elegir Bodega');
            return;    
        }else{ 

        var nombre = 101;
        var descripcion = "FACTURA ELECTRONICA";    
        habilita = false;
        if(nombre == 101 || nombre == 103 || nombre == 105){ // FACTURA ELECTRONICA o FACTURA EXENTA

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
                    var view = Ext.create('Infosys_web.view.guiasdespacho.Facturaguias').show();
                    view.down('#numfacturaId').setValue(nuevo_folio);
                    view.down('#nomdocumentoId').setValue(descripcion);
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

    despachofactura: function(){

        var view = this.getGuiasprincipal();
        var idbodega = view.down('#bodegaId').getValue();
        var nombre = 101;
        if(!idbodega){
            Ext.Msg.alert('Alerta', 'Debe Elegir Bodega');
            return;    
        }else{
        var descripcion = "FACTURA ELECTRONICA";    
        habilita = false;
        if(nombre == 101 || nombre == 103 || nombre == 105){ // FACTURA ELECTRONICA o FACTURA EXENTA

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
                    var view = Ext.create('Infosys_web.view.guiasdespacho.Despachafactura').show();
                    view.down('#numfacturaId').setValue(nuevo_folio);
                    view.down('#nomdocumentoId').setValue(descripcion);
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
   
    fguias: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'guiasprincipalpendientes'});
    },

    mguias: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'guiasprincipal'});
    },

    buscar: function(){

        var view = this.getGuiasprincipal()
        var st = this.getGuiasdespachoStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
        st.load();
    },

    buscarproguias: function(){ 
         
        var view = this.getBuscarproductos25();
        var st = this.getProductosfStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre}
        st.load();
   
    },

    
    cerrarguia: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
    },

   
});










