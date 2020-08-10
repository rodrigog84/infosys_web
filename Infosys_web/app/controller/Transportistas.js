Ext.define('Infosys_web.controller.Transportistas', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['Transportistas.Items',
             'transportista'
            ],

    models: ['transportistas.Item',
             'transportista'

            ],

    views: [ 'Transportes.Ingresar',
             'Transportes.Principal',
             'Transportes.Editar',
                
          
            ],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
       ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'transportistasingresar',
        selector: 'transportistasingresar'
    },{
        ref: 'transportesprincipal',
        selector: 'transportesprincipal'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{
        ref: 'transportistaseditar',
        selector: 'transportistaseditar'
    },
    

    ],
    //init es lo primero que se ejecuta en el controller
    //especia de constructor
    init: function() {
    	
        this.control({

            'topmenus menuitem[action=tingreso]': {
                click: this.tingreso
            },
            'transportistasingresar button[action=grabartransportistas]': {
                click: this.grabartransportistas
            },
            'transportistaseditar button[action=grabareditartransportistas]': {
                click: this.grabareditartransportistas
            },
            'transportesprincipal button[action=ingresotransportes]': {
                click: this.ingresotransportes
            },
            'transportesprincipal button[action=cerrartransportistas]': {
                click: this.cerrartransportistas
            },
            'transportistasingresar button[action=validarut]': {
                click: this.validarut
            },
            'transportesprincipal button[action=agregartransportista]': {
                click: this.agregartransportista
            },
            
            'transportesprincipal button[action=buscartransportista]': {
                click: this.buscartransportista
            },
            'transportesprincipal button[action=editartransportistas]': {
                click: this.editartransportistas
            },
            'transportesprincipal button[action=exportarexceltransportista]': {
                click: this.exportarexceltransportista
            }


        });
    },

    exportarexceltransportista: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getTransportesprincipal()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })     
                         
        window.open(preurl + 'adminServicesExcel/exportarExcelTransportista?cols='+Ext.JSON.encode(jsonCol));
 
    },

    editartransportistas: function(){
 
        var view = this.getTransportesprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit = Ext.create('Infosys_web.view.Transportes.Editar').show();
            edit.down('form').loadRecord(row);
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

    },

    buscartransportista: function(){
        var view = this.getTransportesprincipal();
        var st = this.getTransportistaStore();
        var nombre = view.down('#nombreId').getValue();
        var patente = view.down('#patenteId').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                patente : patente
                               };
        st.load();
    },

    grabartransportistas: function() {

        var view = this.getTransportistasingresar();  
        var rut = view.down('#rutId').getValue();
        var id = view.down('#id').getValue()
        var nombre = view.down('#nombreId').getValue();
        var pat_camion = view.down('#camionId').getValue();
        var ciudad = view.down('#ciudadId').getValue();             
        var pat_carro = view.down('#carroId').getValue();
        var telefono = view.down('#fonoId').getValue();
        var st = this.getTransportistaStore();
        
        if (id){            
            Ext.Ajax.request({           
            url: preurl + 'transportistas/update',
            params: {
                id: id,
                rut: rut,
                nombre: nombre,
                camion: pat_camion,
                ciudad: ciudad,
                carro: pat_carro,
                fono: telefono
            },

            success: function(response){
            //var resp = Ext.JSON.decode(response.responseText);  

            }
            }); 
            
        }else{
            Ext.Ajax.request({           
            url: preurl + 'transportistas/save',
            params: {
                id: id,
                rut: rut,
                nombre: nombre,
                camion: pat_camion,
                ciudad: ciudad,
                carro: pat_carro,
                fono: telefono
            },

            success: function(response){
            //var resp = Ext.JSON.decode(response.responseText);  

            }
            }); 
            
        }     
        view.close();
        st.sync();  
    },

    grabareditartransportistas: function() {

        var win    = this.getTransportistaseditar(),
            form   = win.down('form'),
            record = form.getRecord(),
            values = form.getValues();

        var st = this.getTransportistaStore();
        
        var nuevo = false;
        
        if (values.id > 0){
            record.set(values);
        } else{
            record = Ext.create('Infosys_web.model.transportista');
            record.set(values);
            st.add(record);
            nuevo = true;
        }
        
        win.close();
        st.sync();

        if (nuevo){ 
            st.load();
        }


       
    },
   
    cerrartransportistas: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
     
    },

    tingreso: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'transportesprincipal'});
    },

    validarut: function(){

       
        var view = this.getTransportistasingresar();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;
       
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
           
            url: preurl + 'transportistas/validaRut?valida='+rut,
            params: {
                id: 1
            },

            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);

                if (resp.success == true) {
                    view.close();
                    var edit = Ext.create('Infosys_web.view.Transportes.Ingresar');
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        edit.down("#id").setValue(cliente.id)
                        edit.down("#nombreId").setValue(cliente.nombre)
                        edit.down("#ciudadId").setValue(cliente.ciudad)                        
                        edit.down("#rutId").setValue(cliente.rut)
                        edit.down("#camionId").setValue(cliente.camion)
                        edit.down("#carroId").setValue(cliente.carro)
                        edit.down("#fechaId").setValue(cliente.fecha)
                        edit.down("#fonoId").setValue(cliente.fono)
                  }else{
                        edit.down("#rutId").setValue(rut)
                    }
                    
                }else{
                      Ext.Msg.alert('Informacion', 'Rut Incorrecto');
                      return false
                }

                view.close()

            }

        });       
      
    },


    agregartransportista: function(){        
        Ext.create('Infosys_web.view.Transportes.Ingresar').show();
    },

    

  
  
});










