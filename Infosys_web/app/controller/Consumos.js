Ext.define('Infosys_web.controller.Consumos', {
    extend: 'Ext.app.Controller',

    stores: ['Consumo_diario',
             'consumos',
             'Existencias4',
             'Productosf',
             'ProduccionTermino',
             'tipo_movimientos.Selector',
             'Consumo_movimientodiario',
             'consumos_movimientos'],

    models: ['Consumo_diario',
             'Producto',
             'existencias2'],

    views: ['consumos.Principal',
            'consumos.Consumodiario',
            'consumos.BuscarProductos',
            'consumos.detalle_stock',
            'consumos.Desplegar',
            'consumos.Exportar'],
    
    refs: [{
       ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{
        ref: 'consumosprincipal',
        selector: 'consumosprincipal'
    },{
        ref: 'consumodiario',
        selector: 'consumodiario'
    },{
        ref: 'buscarproductosconsumo',
        selector: 'buscarproductosconsumo'
    },{
        ref: 'detallestock3',
        selector: 'detallestock3'
    },{
        ref: 'consumodiariodesplegar',
        selector: 'consumodiariodesplegar'
    },{
        ref: 'formularioexportarconsumos',
        selector: 'formularioexportarconsumos'
    }

    ],
    
    init: function() {
    	
        this.control({
           
            'topmenus menuitem[action=mconsumo]': {
                click: this.mconsumo
            },
            'consumosprincipal button[action=consumodiario]': {
                click: this.consumodiario
            },
            'consumodiario button[action=buscarproductosconsumo]': {
                click: this.buscarproductos4
            },
            'consumodiario #codigoId': {
                specialkey: this.special7
            },
            'buscarproductosconsumo button[action=seleccionarproductos]': {
                click: this.seleccionarproductos7
            },
            'detallestock3 button[action=seleccionarproductosstock3]': {
                click: this.seleccionarproductosstock
            },
            'buscarproductosconsumo button[action=buscarconsumo]': {
                click: this.buscarconsumo
            },
            'consumodiario button[action=agregarItem]': {
                click: this.agregarItem
            }, 
            'consumodiario button[action=grabarconsumo]': {
                click: this.grabarconsumo
            },
            'consumosprincipal button[action=desplegarmovimiento]': {
                click: this.desplegarmovimiento2
            },
            'consumodiariodesplegar button[action=envioexcel]': {
                click: this.envioexcel
            },
            'consumodiariodesplegar button[action=imprimirdetalle]': {
                click: this.imprimirdetalle
            },
            'consumosprincipal button[action=exportarexcelcdiario]': {
                click: this.exportarexcelmovimientodiario
            },
            'formularioexportarconsumos button[action=exportarformularioconsumosexcel]': {
                click: this.exportarformularioconsumosexcel
            },       
            
        });
    },

    exportarformularioconsumosexcel: function(){

        
        var view =this.getFormularioexportarconsumos()
        var viewnew =this.getConsumosprincipal()
        var fecha = view.down('#fechaId').getSubmitValue();        
        var fecha2 = view.down('#fecha2Id').getSubmitValue();
        
        window.open(preurl + 'adminServicesExcel/exportarExcelconsumosdiario?fecha='+fecha+'&fecha2='+fecha2);
        view.close();    
  
 
    },

    exportarexcelmovimientodiario: function(){

        var viewnew =this.getConsumosprincipal()       
        Ext.create('Infosys_web.view.consumos.Exportar').show();
    
     },

    imprimirdetalle: function(){
        var view = this.getConsumodiariodesplegar();
        var id_mov = view.down('#idId').getValue();
        console.log(id_mov);       
        window.open(preurl +'tipo_movimientodiario/exportPDF2/?idmov=' + id_mov);
    },

    envioexcel: function(){

        var view = this.getConsumodiariodesplegar();
        var id_mov = view.down('#idId').getValue();
        window.open(preurl +'adminServicesExcel/exportarExcelConsumoDetalle/?idmov=' + id_mov);       

    },

    desplegarmovimiento2 : function() {

        var view = this.getConsumosprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit = Ext.create('Infosys_web.view.consumos.Desplegar').show();
            var nombre = (row.get('id'));
            edit.down('form').loadRecord(row);
            var st = this.getConsumos_movimientosStore()
            st.proxy.extraParams = {nombre : nombre}
            st.load();
           
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        };   
        

    },

    grabarconsumo: function(){

        var view = this.getConsumodiario();
        var fecha = view.down('#fechadocumId').getValue();
        var tipomov = view.down('#tipomovId').getValue();       
        var detalle = "Consumo Produccion"
        var stItem = view.down('#itemsgridId').getStore();
        var stMovimiento = this.getConsumo_movimientodiarioStore();
                
        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'tipo_movimientodiario/saveconsumo',
            params: {
                fecha: fecha,
                id_tipom: tipomov,
                detalle: detalle,
                items: Ext.JSON.encode(dataItems)
            },
            success: function(response){
                var text = response.responseText;
                Ext.Msg.alert('Informacion', 'Creada Exitosamente.');
                view.close();
                stMovimiento.load();
            }
        });
    },

    agregarItem: function() {

        var view = this.getConsumodiario();
        var stItem = this.getProduccionTerminoStore();
        var producto = view.down('#productoId').getValue();
        var nombre = view.down('#nombreproductoforId').getValue();
        var codigo = view.down('#codigoId').getValue();
        var cantidad = view.down('#cantidadoproId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var stockcritico = view.down('#stockcriticoId').getValue();
        var stock = view.down('#cantidadoriId').getValue();
        var cantidadori = view.down('#cantidadoriId').getValue();
        var fechamov = view.down('#fechadocumId').getValue();
        var fechavenc = view.down('#fechavencimientoId').getValue();
        var lote = view.down('#loteId').getValue();
        var idexistencia = view.down('#idpId').getValue();
        var id = view.down('#corrId').getValue();
        var idbodega = 1;
        var cero="";
        var cero2=0;
        var id = id +1;

        if(!fechamov){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Fecha Movimiento');
            return false;            
        }

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

        /*if(precio==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Precio Producto');
            return false;
        };*/

        if(cantidad>cantidadori){
            Ext.Msg.alert('Alerta', 'Cantidad Ingresada de Productos Supera El Stock');
            return false;
        };

        if(!cantidad){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Cantidad.');
            return false;
        };       
       
        Ext.Ajax.request({
            url: preurl + 'facturas/stock',
            params: {
                idbodega: idbodega,
                id: idexistencia,
                cantidad: cantidad,
                producto: producto,
                fechafactura: fechamov
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                        
            }
           
        });     
                
        stItem.add(new Infosys_web.model.Consumo_diario({
            id:id,
            id_existencia: idexistencia,
            id_producto: producto,
            id_bodega : idbodega,
            codigo: codigo,
            fecha_vencimiento: fechavenc,
            nom_producto: nombre,
            precio: precio,
            cantidad: cantidad,
            lote: lote,
        }));

        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#idpId').setValue(cero);
        view.down('#nombreproductoforId').setValue(cero);
        view.down('#cantidadoproId').setValue(cero2);
        view.down('#stockcriticoId').setValue(cero2);
        view.down('#cantidadoriId').setValue(cero2);        
        view.down('#precioId').setValue(cero);
        view.down('#corrId').setValue(id);
        //view.down("#buscarproc").focus();
    },


    buscarconsumo: function(){

        var view = this.getBuscarproductosconsumo();
        var st = this.getConsumosStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : "Nombre"}
        st.load();
        
    },

    seleccionarproductos7 : function(){

        var view = this.getBuscarproductosconsumo();
        var viewIngresa = this.getConsumodiario();
        var bodega = 1;
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id = (row.data.id);
            var st = this.getExistencias4Store();
            st.proxy.extraParams = {id : id,
                                    bodega : bodega}
            st.load();
            viewIngresa = Ext.create('Infosys_web.view.consumos.detalle_stock').show();
            viewIngresa.down('#stockId').setValue(row.data.stock);
            viewIngresa.down('#stockcriticoId').setValue(row.data.stock_critico);
            viewIngresa.down('#pventaId').setValue(row.data.p_neto);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    seleccionarproductosstock: function(){

        var view = this.getDetallestock3();
        var viewIngresa = this.getConsumodiario();
        var stockcriticoid = view.down('#stockcriticoId').getValue();
        var grid  = view.down('grid');        
        if (grid.getSelectionModel().hasSelection()) {          
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id_producto);
            viewIngresa.down('#idpId').setValue(row.data.id);
            viewIngresa.down('#nombreproductoforId').setValue(row.data.nom_producto);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);  
            viewIngresa.down('#cantidadoriId').setValue(row.data.saldo);        
            viewIngresa.down('#precioId').setValue(row.data.valor_producto);
            viewIngresa.down('#loteId').setValue(row.data.lote);
            viewIngresa.down('#stockcriticoId').setValue(stockcriticoid);
            viewIngresa.down('#fechavencimientoId').setValue(row.data.fecha_vencimiento);
            view.close();
           
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },



    special7: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.buscarproductos4()
        }
    },

    buscarproductos4: function(){       
        var viewIngresa = this.getConsumodiario();
        var codigo = viewIngresa.down('#codigoId').getValue();
        var id = viewIngresa.down('#productoId').getValue();
        if(!codigo){
            var st = this.getProductosfStore();
            Ext.create('Infosys_web.view.consumos.BuscarProductos').show();
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
                        viewIngresa.down('#cantidadoriId').setValue(cliente.stock);
                        viewIngresa.down('#stockcriticoId').setValue(cliente.stock_critico);
                        viewIngresa.down('#nombreproductoforId').setValue(cliente.nombre); 
                    }
                        st.proxy.extraParams = {id : id,
                                                bodega : bodega}
                        st.load();
                        if(id){
                        viewIngresa = Ext.create('Infosys_web.view.consumos.detalle_stock').show();
                        viewIngresa.down('#stockId').setValue(cliente.stock);
                        viewIngresa.down('#stockcriticoId').setValue(cliente.stock_critico);
                        viewIngresa.down('#pventaId').setValue(cliente.p_venta);
                        };                    
                }else{
                      var view = Ext.create('Infosys_web.view.productos.Ingresar').show();
                      view.down("#codigoId").setValue(codigo);                      
                }              
            }
        });
        }
    },
    
    consumodiario: function(){
        var viewIngresa = this.getConsumodiario();
        var tipo = 2;
        var nombre = "SALIDA";
        var viewIngresa = Ext.create('Infosys_web.view.consumos.Consumodiario').show();
        viewIngresa.down('#tipomovId').setValue(tipo);
        viewIngresa.down('#nomtipomovId').setValue(nombre);
    }, 

    mconsumo: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'consumosprincipal'});
    },
  
});










