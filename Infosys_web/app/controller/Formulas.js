Ext.define('Infosys_web.controller.Formulas', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['Productosf',
             'Formulas'],

    models: ['Formulas',
             'formulas.Item'],

    views: ['formulas.Principal'],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
       ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{
        ref: 'formulasprincipal',
        selector: 'formulasprincipal'
    }

    ],
    //init es lo primero que se ejecuta en el controller
    //especia de constructor
    init: function() {
    	//el <<control>> es el puente entre la vista y funciones internas
    	//del controller
        this.control({
           
            'topmenus menuitem[action=mformulas]': {
                click: this.mformulas
            },
            'formulasprincipal button[action=agregarformula]': {
                click: this.agregarformula
            },
            'formulasprincipal button[action=buscarformulas]': {
                click: this.buscarformulas
            },
            'formulasprincipal button[action=cerrarformulas]': {
                click: this.cerrarformulas
            },
            'formulasprincipal button[action=cerrarformulas]': {
                click: this.cerrarformulas
            },
            
        });
    },

    agregarformula: function(){
        

    },    
    
    cerrarformulas: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
    },
 
    mcambios: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'cambiosprincipal'});
    },

   
    buscarformulas: function(){

        var view = this.getCambiosprincipal()
        var st = this.getCambiosStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
    },

    
  
});










