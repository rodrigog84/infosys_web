Ext.define('Infosys_web.controller.Emailautorizados', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['emailautorizados',
             'Bitacora_aviso'                   
             ],

    models: ['Emailautorizados',
             'bitacora_avisos'
             ],

    views: ['Email_autorizados.Principal',
            'Email_autorizados.bitacora_avisos'
            ],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
    
       ref: 'emailautorizadosprincipal',
        selector: 'emailautorizadosprincipal'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{    
        ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{    
        ref: 'bitacoraaviso',
        selector: 'bitacoraavisos'
    }
  
    ],
    
    init: function() {
    	
        this.control({

            'topmenus menuitem[action=memailautorizados]': {
                click: this.memailautorizados
            },
            'emailautorizadosprincipal button[action=cerraremail]': {
                click: this.cerraremail
            },
            'emailautorizadosprincipal button[action=bitacoraaviso]': {
                click: this.bitacoraaviso
            },            
        });
    },
    
    bitacoraaviso: function(){

        var view = this.getEmailautorizadosprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var nombre = (row.get('id'));
            var usua = (row.get('nombre'));
            var st = this.getBitacora_avisoStore();
            st.proxy.extraParams = {nombre : nombre};
            st.load();  
            var edit = Ext.create('Infosys_web.view.Email_autorizados.bitacora_avisos').show();
            edit.down("#nom_email").setValue(usua);
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
        
    },
   
    memailautorizados: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'emailautorizadosprincipal'});
    }, 
    
    cerraremail: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
     
    },
  
});










