Ext.define('Infosys_web.controller.ProductosControl', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['Productos',
             'productos.Selector',
             'Existencias2'
             ],

    models: ['Producto',
             'existencias2'],

    views: ['productoscontrol.Principal', 'productoscontrol.Desplegar',
            'productoscontrol.detalle_existenciasproductos' ],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
    
        ref: 'productoscontrolprincipal',
        selector: 'productoscontrolprincipal'
    },{
        ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{    
        ref: 'busquedaproductos',
        selector: 'busquedaproductos'
    },{
    
        ref: 'productoscontroldesplegar',
        selector: 'productoscontroldesplegar'
    },{
    
        ref: 'detalleexistenciascontrolproductos',
        selector: 'detalleexistenciascontrolproductos'
    }

    ],
    //init es lo primero que se ejecuta en el controller
    //especia de constructor
    init: function() {
    	//el <<control>> es el puente entre la vista y funciones internas
    	//del controller
        this.control({

            'topmenus menuitem[action=mcontrolproductos]': {
                click: this.mcontrolproductos
            },
            'productoscontrolprincipal button[action=buscarcontrolproductos]': {
                click: this.buscarcontrolproductos
            },            
            'productoscontrolprincipal button[action=exportarexcelproductos]': {
                click: this.exportarexcelproductos
            },
            'productoscontroldesplegar button[action=grabarcontrolproductos]': {
                click: this.grabarcontrolproductos
            },
            'productoscontrolprincipal button[action=editarcontrolproductos]': {
                click: this.editarcontrolproductos
            },
            'productoscontrolprincipal button[action=cerrarcontrolproductos]': {
                click: this.cerrarcontrolproductos
            },
            'productoscontrolprincipal button[action=detalleexistenciascontrolproductos]': {
                click: this.detalleexistenciascontrolproductos
            },
            'productoscontroldesplegar button[action=listaprecios]': {
                click: this.buscarprecios
            },
            'productoscontroldesplegar #tipobodegaId': {
                select: this.selectItem
            },
            'productoscontroldesplegar #costoId': {
                 specialkey: this.special,
            },
            'productoscontroldesplegar #valvulaId': {
                 specialkey: this.special,
            },
            'productoscontroldesplegar #maestroId': {
                 specialkey: this.special,
            },
            'productoscontroldesplegar #vendedorId': {
                 specialkey: this.special,
            },
            'productoscontroldesplegar #adicionalId': {
                 specialkey: this.special,
            },
            'productoscontroldesplegar #margenId': {
                 specialkey: this.special,
            },
            'productoscontroldesplegar button[action=calcularprecio]': {
                click: this.calculacosto
            },           
                        
        });
    },

    special: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.calculacosto()
        };
        if (e.getKey() == e.TAB) {
           this.calculacosto()
        };
    },

    calculacosto: function(){
    
        var view = this.getProductoscontroldesplegar();
        var costo = view.down('#costoId').getValue();
        var valvula = view.down('#valvulaId').getValue();
        var maestro = view.down('#maestroId').getValue();
        var vendedor = view.down('#vendedorId').getValue();
        var adicional = view.down('#adicionalId').getValue();
        var margen = view.down('#margenId').getValue();
        
        var pventa = (costo + valvula + maestro + vendedor);
        if(adicional){
            var adicional = ((pventa * adicional)/100);            
        };
        if(margen){
             var margen = (((pventa + adicional) * margen)/100);            
        };
        var pventafinal = (pventa + adicional + margen);
        var pventaiva = (pventafinal * 1.19);

        view.down('#preciosinivaId').setValue(pventafinal);
        view.down('#precioivaId').setValue(pventaiva);
        view.down('#pventaId').setValue(pventaiva);
                
    },



    selectItem: function() {
        var view = this.getProductoscontroldesplegar();
        var idproducto = view.down('#Id').getValue();
        var bodega = view.down('#tipobodegaId');
        var stCombo = bodega.getStore();
        var record = stCombo.findRecord('id', bodega.getValue()).data;
        var id_bodega = record.id;
        Ext.Ajax.request({
            url: preurl + 'existencias2/getAll2',
            params: {
                nombre : idproducto,
                id_bodega: id_bodega
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);                
                if (resp.success == true) {                    
                    var stock = resp.stock;
                    view.down('#bodegaId').setValue(stock);                                           
                }                    
            }

        });    
                  
    },

    mcontrolproductos: function(){

        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'productoscontrolprincipal'});
        var view = this.getProductoscontrolprincipal();
        view.down("#nombreId").focus();
    },
    
    
    
    detalleexistenciascontrolproductos : function(){

        var view = this.getProductoscontrolprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit = Ext.create('Infosys_web.view.productoscontrol.detalle_existenciasproductos').show();
            var nombre = (row.get('id'));
            edit.down('#productoId').setValue(nombre);            
            var st = this.getExistencias2Store();
            st.proxy.extraParams = {nombre : nombre}
            st.load();
            Ext.Ajax.request({
            url: preurl + 'existencias2/getAll2',
            params: {
                id: 1,
                nombre: nombre
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);                
                if (resp.success == true) {                    
                    var stock = resp.stock;
                    edit.down('#stockId').setValue(stock);                                           
                }                    
            }

            });           
           
           
                   
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

     exportarexcelexistenciadetalleproducto : function(){

        var view =this.getDetalleexistenciascontrolproductos()
        var idproducto = view.down('#productoId').getValue()
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getDetalleexistenciascontrolproductos()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })     
                         
        window.open(preurl + 'adminServicesExcel/exportarExcelExistenciadetalle?idproducto='+idproducto+'&cols='+Ext.JSON.encode(jsonCol));
        view.close();

   },

    exportarexcelproductos: function(){

        var view = this.getProductoscontrolprincipal();
        var nombre = view.down('#nombreId').getValue();
        opcion="";
        if(nombre){
            opcion="Nombre";
        };        
        console.log(nombre)
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getProductoscontrolprincipal()       
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })     
                         
        window.open(preurl + 'adminServicesExcel/exportarExcelProductos?cols='+Ext.JSON.encode(jsonCol)+'&opcion='+opcion+'&nombre='+nombre);
 
    },

    buscarcontrolproductos: function(){
        
        var view = this.getProductoscontrolprincipal();
        var st = this.getProductosStore();
        var cero ="";
        var nombre = view.down('#nombreId').getValue();        
        var familia = view.down('#tipofamiliaId').getValue();        
        var agrupacion = view.down('#tipoagrupacionId').getValue();        
        var subfamilia = view.down('#tiposubfamiliaId').getValue();        
        var opcion = view.down('#tipoSeleccionId').getValue();                
        st.proxy.extraParams = {nombre : nombre, 
                                familia : familia, 
                                agrupacion : agrupacion,
                                subfamilia : subfamilia,
                                opcion : opcion}
        var tipo = "Nombre";
        //view.down('#nombreId').setValue(cero);
        view.down('#tipofamiliaId').setValue(cero);
        view.down('#tipoagrupacionId').setValue(cero);
        view.down('#tiposubfamiliaId').setValue(cero);
        view.down('#tipoSeleccionId').setValue(tipo);
        view.down("#nombreId").focus();
        var tipo = "Nombre";
        st.load();

    },

    grabarcontrolproductos: function(){

        var win    = this.getProductoscontroldesplegar(),
            form   = win.down('form'),
            record = form.getRecord(),
            values = form.getValues();
       
        var pcosto = win.down('#pventaId').getValue();
        var scritico = win.down('#stockcriticoId').getValue();        
          
        if(!pcosto){
            Ext.Msg.alert('Informacion', 'Ingrese precio Venta');
            return false
        };

        if(!scritico){
            Ext.Msg.alert('Informacion', 'Ingrese Stock Critico');
            return false
        };
        
        var st = this.getProductosStore();

        form.getForm().submit({
          
            url: preurl + 'productos/update2',
            success: function(){
                st.load();
                win.close();
            }

        });
    },
        
    editarcontrolproductos: function(){
          
        var view = this.getProductoscontrolprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit = Ext.create('Infosys_web.view.productoscontrol.Desplegar').show();
            edit.down('form').loadRecord(row);
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    cerrarcontrolproductos: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
     
    },
  
});










