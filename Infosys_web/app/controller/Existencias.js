Ext.define('Infosys_web.controller.Existencias', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['Existencias',
             'Existencias2',
             'Existencias3',
             'InventarioSlectivo',
             'Bodegas'],

    models: ['existencias',
             'existencias2',
             'Bodega'],

    views: ['existencia.Principal',
            'existencia.PrincipalSelectivo',
            'existencia.detalle_existencias'],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
       ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'existenciaprincipal',
        selector: 'existenciaprincipal'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{
        ref: 'detalleexistencias',
        selector: 'detalleexistencias'
    },{
        ref: 'existenciaprincipalselectivo',
        selector: 'existenciaprincipalselectivo'
    }
    ],
    //init es lo primero que se ejecuta en el controller
    //especia de constructor
    init: function() {
    	//el <<control>> es el puente entre la vista y funciones internas
    	//del controller
        this.control({
           
            'topmenus menuitem[action=mexistencia]': {
                click: this.mexistencia
            },
            'topmenus menuitem[action=minventarioselectivo]': {
                click: this.minventarioselectivo
            },
            'existenciaprincipal button[action=buscarexistencia]': {
                click: this.buscarexistencia
            },
            'existenciaprincipalselectivo button[action=buscarexistenciaselectivo]': {
                click: this.buscarexistenciaselectivo
            },
            'existenciaprincipalselectivo button[action=cerrarexistenciaselector]': {
                click: this.cerrarexistenciaselector
            },
            'existenciaprincipalselectivo button[action=exportarexcelexistenciaselector]': {
                click: this.exportarexcelexistenciaselector
            },
             'existenciaprincipal button[action=cerrarexistencia]': {
                click: this.cerrarexistencia
            },
            'existenciaprincipal button[action=exportarexcelexistencia]': {
                click: this.exportarexcelexistencia
            },
            'detalleexistencias button[action=exportarexcelexistenciadetalle]': {
                click: this.exportarexcelexistenciadetalle
            },
            'existenciaprincipal button[action=editarexistencia]': {
                click: this.editarexistencia
            },
            'existenciaprincipalselectivo #bodegaId': {
                select: this.buscarDoc
            },
            'existenciaprincipal #bodegaId': {
                select: this.seleccionbodega
            },
           
        });
    },

    seleccionbodega: function(){

        var view = this.getExistenciaprincipal();
        var st = this.getExistenciasStore()
        var bodega = view.down('#bodegaId').getValue();
        st.proxy.extraParams = {bodega : bodega
        }
        st.load();

    },

    buscarDoc: function(){
        
        var view = this.getExistenciaprincipalselectivo();
        var st = this.getInventarioSlectivoStore();
        var opcion = view.down('#bodegaId').getValue();
        st.proxy.extraParams = {opcion : opcion}
        st.load();
    },

    editarexistencia: function(){

        var view = this.getExistenciaprincipal();
        var bodega = view.down('#bodegaId').getValue();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit = Ext.create('Infosys_web.view.existencia.detalle_existencias').show();
            var nombre = (row.get('id_producto'));
            var stock = (row.get('stock'));
            edit.down('#productoId').setValue(nombre);
            edit.down('#stockId').setValue(stock);
            var st = this.getExistencias3Store()
            st.proxy.extraParams = {nombre : nombre,
                                    bodega: bodega}
            st.load();
           
                   
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },


    cerrarexistencia: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
    },

    cerrarexistenciaselector: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
    },
 
    mexistencia: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        var st = this.getExistenciasStore()
        st.load();
        viewport.add({xtype: 'existenciaprincipal'});
        var viewIngresa = this.getExistenciaprincipal();
        var idbodega = "1";
        viewIngresa.down('#bodegaId').setValue(idbodega);
    },

    minventarioselectivo: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        var st = this.getExistenciasStore()
        st.load();
        viewport.add({xtype: 'existenciaprincipalselectivo'});
    },

    exportarexcelexistencia: function(){

        var jsonCol = new Array()
        var i = 0;
        var grid =this.getExistenciaprincipal()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })     
                         
        window.open(preurl + 'adminServicesExcel/exportarExcelExistencia?cols='+Ext.JSON.encode(jsonCol));

   },

   exportarexcelexistenciaselector: function(){


        var view =this.getExistenciaprincipalselectivo()
        var idbodega = view.down('#bodegaId').getValue()
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getExistenciaprincipalselectivo()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })     
                         
        window.open(preurl + 'adminServicesExcel/exportarExcelExistenciaselector?idbodega='+idbodega+'&cols='+Ext.JSON.encode(jsonCol));
        
   },



    exportarexcelexistenciadetalle: function(){

        var view =this.getDetalleexistencias()
        var idproducto = view.down('#productoId').getValue()
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getDetalleexistencias()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })     
                         
        window.open(preurl + 'adminServicesExcel/exportarExcelExistenciadetalle?idproducto='+idproducto+'&cols='+Ext.JSON.encode(jsonCol));
         view.close();

   },

    buscarexistencia: function(){

        var view = this.getExistenciaprincipal()
        var st = this.getExistenciasStore()
        var nombre = view.down('#nombreId').getValue()
        var bodega = view.down('#bodegaId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                bodega : bodega}
        st.load();

   },

    buscarexistenciaselectivo: function(){

        var view = this.getExistenciaprincipalselectivo()
        var st = this.getInventarioSlectivoStore()
        var nombre = view.down('#nombreId').getValue();
        var opcion = view.down('#bodegaId').getValue();
        if (!opcion){
            Ext.Msg.alert('Para Buscar Debe Seleccionar Bodega');
            return; 
        }
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
        st.load();

   },

    
  
});










