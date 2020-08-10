Ext.define('Infosys_web.controller.Tipo_movimientos_inventario', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['Tipo_movimiento',
             'Tipo_movimiento_inventario',
             'tipo_movimientos.Items',
             'tipo_movimientos.Selector',
             'Tipo_movimientodiario',
             'Detalle_tipo_movimiento'
            ],

    models: ['Tipo_movimiento',
             'Tipo_movimientos.Item',
             'Tipo_movimientodiario',
             'Detalle_movimiento'],

    views: ['movimiento_diario_inventario.Principal',
            'movimiento_diario_inventario.Ingresar',
            'movimiento_diario_inventario.Desplegar',
            'movimiento_diario_inventario.Exportar'],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
            ref: 'tipomovimientoinventarioprincipal',
            selector: 'tipomovimientoinventarioprincipal'
        },{
            ref: 'topmenus',
            selector: 'topmenus'
        },{
            ref: 'panelprincipal',
            selector: 'panelprincipal'
        },{
            ref: 'movimientodiarioinventario',
            selector: 'movimientodiarioinventario'
        },{
            ref: 'movimientodiariodesplegar',
            selector: 'movimientodiariodesplegar'
        },{
            ref: 'formularioexportarmovimiento',
            selector: 'formularioexportarmovimiento'
        }

    ],
    
    init: function() {
    	
        this.control({

            'topmenus menuitem[action=mtipomovimientoinventario]': {
                click: this.mtipomovimientoinventario
            },
            'movimientodiarioinventario #tipoMovimientoId': {
                select: this.selectItemtipodocumento                
            },
            'tipomovimientoinventarioprincipal button[action=mmovimientoinventario]': {
                click: this.mmovimientoinventario
            },
            'tipomovimientoinventarioprincipal button[action=cerrarmovimiento]': {
                click: this.cerrarmovimiento
            },
            'movimientodiarioinventario #tipoCodigoId': {
                select: this.selectItemtipomovimiento
            },
            'movimientodiarioinventario button[action=grabarmovimiento]': {
                click: this.grabarmovimiento
            },
            'tipomovimientoinventarioprincipal button[action=desplegarmovimiento]': {
                click: this.desplegarmovimiento
            },
             'tipomovimientoinventarioprincipal button[action=exportarexcelmovimientodiario]': {
                click: this.exportarexcelmovimientodiario
            },
            'movimientodiariodesplegar button[action=imprimirdetalle]': {
                click: this.imprimirdetalle
            },
            'movimientodiariodesplegar button[action=envioexcel]': {
                click: this.envioexcel
            },
            'formularioexportarmovimiento button[action=exportarformulariomovimientoexcel]': {
                click: this.exportarformulariomovimientoexcel
            },
            });
    },

    envioexcel: function(){

        var view = this.getMovimientodiariodesplegar();
        var id_mov = view.down('#idId').getValue();
        window.open(preurl +'adminServicesExcel/exportarExcelMovimientoDetalle/?idmov=' + id_mov);       
    },

    imprimirdetalle: function(){

        var view = this.getMovimientodiariodesplegar();
        var id_mov = view.down('#idId').getValue();
        console.log(id_mov);       
        window.open(preurl +'tipo_movimientodiario/exportPDF/?idmov=' + id_mov);       

    },
       
     exportarexcelmovimientodiario: function(){

        var viewnew =this.getTipomovimientoinventarioprincipal()       
        Ext.create('Infosys_web.view.movimiento_diario_inventario.Exportar').show();
    
     },          
        
    exportarformulariomovimientoexcel: function(){

        var jsonCol = new Array()
        var i = 0;
        var grid =this.getTipomovimientoinventarioprincipal()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getFormularioexportarmovimiento()
        var viewnew =this.getTipomovimientoinventarioprincipal()
        var fecha = view.down('#fechaId').getSubmitValue();        
        var fecha2 = view.down('#fecha2Id').getSubmitValue();
        var id_tipom = view.down('#tipoCodigoId').getValue();
        var tipo_documento = view.down('#tipoCodigoId');
        var stCombo = tipo_documento.getStore();
        var record = stCombo.findRecord('id', tipo_documento.getValue()).data;
        var nombremov = (record.nombre);

        if(!id_tipom){
             Ext.Msg.alert('Alerta', 'Debe Seleccionar Tipo de Movimiento');
            return;    
            
        }

        console.log(id_tipom);
                
        if (fecha > fecha2) {
        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };   
        
        window.open(preurl + 'adminServicesExcel/exportarExcelmovimientodiario?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2+'&tipomov='+id_tipom+'&nombremov='+nombremov);
        view.close();    
  
 
    },

    desplegarmovimiento : function() {

        var view = this.getTipomovimientoinventarioprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit = Ext.create('Infosys_web.view.movimiento_diario_inventario.Desplegar').show();
            var nombre = (row.get('id'));
            edit.down('form').loadRecord(row);
            var st = this.getDetalle_tipo_movimientoStore()
            st.proxy.extraParams = {nombre : nombre}
            st.load();
           
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        };
        
        

    },



    grabarmovimiento : function(){

        var view = this.getMovimientodiarioinventario();
        var fecha = view.down('#fechaId').getValue();
        var numero = view.down('#numeroId').getValue();
        var id_tipom = view.down('#tipoMovimientoId').getValue();
        var id_tipomd = view.down('#tipoCodigoId').getValue();

        var tipo_documento = view.down('#tipoCodigoId');
        var stCombo = tipo_documento.getStore();
        var record = stCombo.findRecord('id', tipo_documento.getValue()).data;
        var id_corre = (record.id_correlativo);

        var rut = view.down('#numerorutId').getValue();
        var id_bodegaent = view.down('#tipobodegaId').getValue();
        var id_bodegasal = view.down('#tipobodega2Id').getValue();
        var detalle = view.down('#detalleId').getValue();
        var stItem = view.down('#ingresomovimientosId').getStore();
        var stMovimiento = this.getTipo_movimientodiarioStore();

        
        if (id_tipom == "1"){

            if (!id_bodegaent){

                Ext.Msg.alert('Informacion', 'Ingrese Bodega Entrada');
                return false          

            }
        };

        if (id_tipom == "2"){

            if (!id_bodegasal){

                Ext.Msg.alert('Informacion', 'Ingrese Bodega de Salida');
                return false          

            }
        };

        if (id_tipom == "3"){
            if (!id_bodegasal){

                Ext.Msg.alert('Informacion', 'Ingrese Bodega de Salida');
                return false          

            }
            if (!id_bodegaent){

                Ext.Msg.alert('Informacion', 'Ingrese Bodega Entrada');
                return false          

            }
        };

        if (!detalle){

                Ext.Msg.alert('Informacion', 'Ingrese Detalle');
                return false          

        };


        
        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'tipo_movimientodiario/save',
            params: {
                fecha: fecha,
                numero: numero,
                id_tipom: id_tipom,
                id_tipomd: id_tipomd,
                id_correlativo: id_corre,
                rut: rut,
                id_bodegaent: id_bodegaent,
                id_bodegasal: id_bodegasal,
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

    selectItemtipomovimiento: function() {

        var view = this.getMovimientodiarioinventario();
        var producto = view.down('#tipoCodigoId');
        var stCombo = producto.getStore();
        var record = stCombo.findRecord('id', producto.getValue()).data;
        var nombre = view.down('#tipoMovimientoId').getValue();
       
        if ((nombre)== 1){
            view.down("#tipobodega2Id").setDisabled(true);
             view.down("#tipobodegaId").setDisabled(false);
        };

        if ((nombre)== 2){
            view.down("#tipobodegaId").setDisabled(true);
            view.down("#tipobodega2Id").setDisabled(false);
        };

        if ((record.id_rut)== "off"){
            view.down("#numerorutId").setDisabled(true);
        }else{
            view.down("#numerorutId").setDisabled(false);
        };
        
    },

    mtipomovimientoinventario: function(){
   
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'tipomovimientoinventarioprincipal'});
    },

    selectItemtipodocumento: function() {
        
        var view =this.getMovimientodiarioinventario();
        var st = this.getTipo_movimiento_inventarioStore()
        var nombre = view.down('#tipoMovimientoId').getValue()
        if ((nombre)== 3){
            view.down("#tipobodegaId").setDisabled(false);
            view.down("#tipobodega2Id").setDisabled(false);
        };

        st.proxy.extraParams = {nombre : nombre}
        st.load();
   
    },

    mmovimientoinventario: function(){
        Ext.create('Infosys_web.view.movimiento_diario_inventario.Ingresar').show();

    },
   
    tipomovimientocerrar: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
     
    },

    cerrarmovimiento: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
     
    }, 


});

    









