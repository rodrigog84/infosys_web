Ext.define('Diio.controller.Ingreso_diios', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['Ingresos.Items',
             'Tipo_animales',
             'ingresofma',
             'ingresofmapendientes',
             'ingresodiios',
             'transportista'
            ],

    models: ['Ingresos.Item',
             'tipo_animales',
             'Ingresofma',
             'Ingresodiios'

            ],

    views: [ 'Ingreso_diio.Ingreso',
             'Ingreso_diio.Principal',
             'Ingreso_diio.IngresoOtros',
             'Ingreso_diio.Editar',
             'Ingreso_diio.Eliminar',
             'Ingreso_diio.Principalpendientes',
             'Ingreso_diio.BuscarTransportistaingreso'],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
       ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'ingresodiio',
        selector: 'ingresodiio'
    },{
        ref: 'ingresootros',
        selector: 'ingresootros'
    },{
        ref: 'ingresoprincipal',
        selector: 'ingresoprincipal'
    },{
        ref: 'ingresoprincipalpendientes',
        selector: 'ingresoprincipalpendientes'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{
        ref: 'editardiio',
        selector: 'editardiio'
    },{
        ref: 'buscatransingreso',
        selector: 'buscatransingreso'
    },{
        ref: 'formularioexportaringreso',
        selector: 'formularioexportaringreso'
    },{
        ref: 'formularioexportaringreso2',
        selector: 'formularioexportaringreso2'
    },{
        ref: 'animalesingresados',
        selector: 'animalesingresados'
    },{
        ref: 'eliminarfmaingreso',
        selector: 'eliminarfmaingreso'
    },{
        ref: 'eliminarfmaingresopendiente',
        selector: 'eliminarfmaingresopendiente'
    }




    ],
    //init es lo primero que se ejecuta en el controller
    //especia de constructor
    init: function() {
    	
        this.control({
            'topmenus menuitem[action=mingreso]': {
                click: this.mingreso
            },
            'topmenus menuitem[action=mingresopendientes]': {
                click: this.mingresopendientes
            },            
            'ingresodiio button[action=agregarItem]': {
                click: this.agregarItem
            },
            'ingresoprincipal button[action=ingresodiios]': {
                click: this.ingresodiios
            },
            'ingresoprincipal button[action=cerraringreso]': {
                click: this.cerraringreso
            },
            'ingresodiio button[action=validarup]': {
                click: this.validarup
            },
            'ingresootros button[action=validarup]': {
                click: this.validarup2
            },
            'ingresodiio button[action=validaruttransportista]': {
                click: this.validaruttransportista
            },
            'ingresootros button[action=validaruttransportista]': {
                click: this.validaruttransportista2
            },
            'ingresodiio button[action=eliminaritem]': {
                click: this.eliminaritem
            },
            'ingresodiio button[action=grabaringreso]': {
                click: this.grabaringreso
            },
            'ingresoprincipal button[action=generarformulariopdf]': {
                click: this.generarformulariopdf
            },
            'ingresodiio #ruporigenId': {
                select: this.selectItem
            },
            'ingresootros #ruporigenId': {
                select: this.selectItem2
            },
            'ingresoprincipal button[action=ingresootros]': {
                click: this.ingresootros
            },
            'ingresoprincipal button[action=exportarExcelSag]': {
                click: this.exportarExcelSag
            },
            'ingresoprincipal button[action=buscarfmaingreso]': {
                click: this.buscarfmaingreso
            },
            'ingresoprincipalpendientes button[action=buscarfmaingresopendientes]': {
                click: this.buscarfmaingresopendientes
            },
            'ingresootros button[action=grabaringresootros]': {
                click: this.grabaringresootros
            },
            'ingresoprincipal button[action=editarfma]': {
                click: this.editarfma
            },
            'editardiio button[action=validaruttransportista]': {
                click: this.validarut
            },
            'editardiio button[action=grabaringresoeditar]': {
                click: this.grabaringresoeditar
            },
            'editardiio button[action=agregarItem2]': {
                click: this.agregarItem2
            },
            'buscatransingreso button[action=buscartran]': {
                click: this.buscartran
            },
            'buscatransingreso button[action=seleccionartrans]': {
                click: this.seleccionartrans
            },
            'ingresoprincipalpendientes button[action=editarfma2]': {
                click: this.editarfma2
            },
            'ingresoprincipalpendientes button[action=actualizapendientes]': {
                click: this.actualizapendientes
            },
            'formularioexportaringreso button[action=exportarExcelFormularioingreso]': {
                click: this.exportarExcelFormularioingreso
            },
            'ingresoprincipalpendientes button[action=formularioexportar2]': {
                click: this.formularioexportar2
            },
            'ingresoprincipal button[action=formularioexportaringreso]': {
                click: this.formularioexportaringreso
            },
            'ingresoprincipal button[action=animalesingresados]': {
                click: this.animalesingresados
            },
            'ingresoprincipal button[action=eliminaformularioingreso]': {
                click: this.eliminaformularioingreso
            },
            'eliminarfmaingreso button[action=eliminaformulariofma]': {
                click: this.eliminaformulariofma
            },
            'eliminarfmaingreso button[action=eliminaformulariofmano]': {
                click: this.eliminaformulariofmano
            },
            'ingresoprincipalpendientes button[action=buscarpendientes]': {
                click: this.buscarpendientes
            },
            'ingresoprincipalpendientes button[action=eliminaformulariopendiente2]': {
                click: this.eliminaformulariopendiente2
            },
            'eliminarfmaingresopendiente button[action=eliminaformulariofmano2]': {
                click: this.eliminaformulariofmano2
            },
            'eliminarfmaingresopendiente button[action=eliminaformulariofma2]': {
                click: this.eliminaformulariofma2
            }
        });
    },

    agregarItem2: function() {

        var view = this.getEditardiio();
        var num_fma = view.down('#numfmaId').getValue();
        var Idfma = view.down('#Idfma').getValue();
        var stItem = this.getIngresodiiosStore();
        var animal = view.down('#animalId');
        var stCombo = animal.getStore();
        var diioa = view.down('#diioId').getValue();
        var dientes = view.down('#dientesId').getValue();        
        var record = stCombo.findRecord('id', animal.getValue()).data;
        var rup = view.down('#rupId').getValue();
        var exists = 0;
        var total = 0;
        var vacunos = view.down('#vacunosId').getValue();
        var total = view.down('#finaltotalpostId').getValue();
        var nombre = (record.Nombre);
        if(rup.length==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar al Formulario.');
            return false;
        }

         if(num_fma.length==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar al Formulario.');
            return false;
        }

        stItem.each(function(r){
            if(r.data.diio == diioa){
                Ext.Msg.alert('Alerta', 'El registro ya existe.');
                exists = 1;
                cero="";
                view.down('#diioId').setValue(cero);
                return; 
            }
        });

        if(exists == 1)
            return;

        stItem.add(new Diio.model.Ingresodiios({
            id_fma: Idfma, 
            num_fma: num_fma,
            especie: record.id,
            nom_animal: nombre,
            diio: view.down('#diioId').getValue(),
            dientes: view.down('#dientesId').getValue()                      
        }));

        var cero = "";
        var cero1 = 0;
        view.down('#diioId').setValue(cero);
        view.down('#dientesId').getValue(cero);
        view.down('#animalId').getValue(cero1);

        vacunos = vacunos +1;
        total = vacunos;
        view.down('#vacunosId').setValue(Ext.util.Format.number(vacunos, '0,000'));
        view.down('#totalanimalId').setValue(Ext.util.Format.number(total, '0,000'));
        view.down('#finaltotalpostId').setValue(total);
        
    },

    eliminaformulariofmano2: function(){

        var view = this.getEliminarfmaingresopendiente()
        view.close();

    },


    eliminaformulariofma2: function(){

        var view = this.getEliminarfmaingresopendiente()
        var viewprincipal = this.getIngresoprincipalpendientes()
        var idfma = view.down('#id_fmaID').getValue()
        var fecha = viewprincipal.down('#fechaprocesoId').getValue()
        var st = this.getIngresofmapendientesStore();


        Ext.Ajax.request({
            url: preurl + 'ingresos_diios/elimina',
            params: {
                idfma: idfma,
                fecha: Ext.Date.format(fecha,'Y-m-d')                
            },
            success: function(){
                 st.load();
                 Ext.Msg.alert('Datos Eliminados Exitosamente');
                 return;
                 view.close();
        }
        });

        view.close();
        st.load();
    
    },

    buscarpendientes: function(){

        var view = this.getIngresoprincipalpendientes();
        var st = this.getIngresofmapendientesStore();
        var fecha = view.down('#fechaprocesoId').getValue();
        var opcion = "FECHA";        
        st.proxy.extraParams = {fecha: Ext.Date.format(fecha,'Y-m-d'),
                                opcion: opcion}
        st.load();
    },

    eliminaformulariopendiente2: function(){

        var view = this.getIngresoprincipalpendientes()       
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit =   Ext.create('Diio.view.Ingreso_diio.Eliminar2').show();
            edit.down('#id_fmaID').setValue(row.data.id);           
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    eliminaformularioingreso: function(){

        var view = this.getIngresoprincipal()       
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var edit =   Ext.create('Diio.view.Ingreso_diio.Eliminar').show();
            edit.down('#id_fmaID').setValue(row.data.id);
           
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

    },

    eliminaformulariofmano: function(){

        var view = this.getEliminarfmaingreso()
        view.close();

    },


    eliminaformulariofma: function(){

        var view = this.getEliminarfmaingreso()
        var idfma = view.down('#id_fmaID').getValue()
        var st = this.getIngresofmaStore();


        Ext.Ajax.request({
            url: preurl + 'ingresos_diios/elimina',
            params: {

                idfma: idfma
                
            },
            success: function(){
                 st.load();
                 Ext.Msg.alert('Datos Eliminados Exitosamente');
                 return;
                 view.close();
        }
        });

        view.close();
        st.load();
    
    },
    

    animalesingresados: function(){

        var view =this.getIngresoprincipal()
        var fecha = view.down('#fechaprocesoId').getValue();
        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"BUSCANDO INFORMACION..."});
        myMask.show();
        Ext.Ajax.request({
            url: preurl + 'ingresos_fma/buscaranimales',
            params: {
                fechaproceso: Ext.Date.format(fecha,'Y-m-d')
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {
                    var viewIngresa=Ext.create('Diio.view.Ingreso_diio.Animales').show();
                    viewIngresa.down('#vacunosId').setValue(resp.vacunos);
                    viewIngresa.down('#caballaresId').setValue(resp.caballares);
                    viewIngresa.down('#porcinosId').setValue(resp.porcinos);
                    viewIngresa.down('#caprinosId').setValue(resp.caprinos);
                    viewIngresa.down('#ovinosId').setValue(resp.ovinos);
                    viewIngresa.down('#fechaprocesoId').setValue(fecha);
                    myMask.hide();
                }
                
        }
        });
    },

    formularioexportaringreso2: function(){              
        Ext.create('Diio.view.Ingreso_diio.Exportar').show();
    },

    formularioexportaringreso: function(){              
        Ext.create('Diio.view.Ingreso_diio.Exportar').show();
    },

    exportarExcelFormularioingreso: function(){
        
        var view =this.getFormularioexportaringreso()
        var fecha = view.down('#fechaId').getSubmitValue();
        var fecha2 = view.down('#fecha2Id').getSubmitValue();
        /*if (fecha > fecha2) {        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return; 
        };*/
        var ruporigen = view.down('#ruporigenId');
        var stCombo = ruporigen.getStore();
        var record = stCombo.findRecord('id', ruporigen.getValue()).data;
        var rupo = (record.rup);
                         
        window.open(preurl + 'adminServicesExcel/exportarExcelFormularioingreso?fecha='+fecha+'&fecha2='+fecha2+'&rupo='+rupo);
        view.close();
 
    },

    seleccionartrans : function(){

      var view = this.getBuscatransingreso()
        var viewIngresa = this.getEditardiio();
        
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#id_transportista').setValue(row.data.id);
            viewIngresa.down('#ruttransportistaId').setValue(row.data.rut);
            viewIngresa.down('#nombretransportistaId').setValue(row.data.nombre);
            viewIngresa.down('#camionId').setValue(row.data.camion);
            viewIngresa.down('#carroId').setValue(row.data.carro);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    buscartran: function(){

        var view = this.getBuscatransingreso()
        var st = this.getTransportistaStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre}
        st.load();

    },

    grabaringresoeditar: function(){

        var viewIngresa = this.getEditardiio();
        var numfma = viewIngresa.down('#numfmaId').getValue();
        var Idfma = viewIngresa.down('#Idfma').getValue();        
        var ruttitular = viewIngresa.down('#ruttitularId').getValue();
        var rutfma = viewIngresa.down('#rutfmaId').getValue();
        var rutfmaor = rutfma;
        var numguia = viewIngresa.down('#numguiaId').getValue();
        var fechaproceso = viewIngresa.down('#fechaprocesofmaId').getValue();
        var ruporigen = viewIngresa.down('#rupId').getValue();
        var fechasalida = viewIngresa.down('#fechasalidaId').getValue();
        var horasalida = viewIngresa.down('#horasalidaId').getValue();
        var idrupdestino = viewIngresa.down('#ruporigenId').getValue();
        var rutdestino = viewIngresa.down('#ruttitulardestinoId').getValue();
        var fechallegada = viewIngresa.down('#fechallegadaId').getValue();
        var horallegada = viewIngresa.down('#horallegadaId').getValue();
        var idtransportista = viewIngresa.down('#id_transportista').getValue();
        var camion = viewIngresa.down('#camionId').getValue();
        var carro = viewIngresa.down('#carroId').getValue();
        var observaciones = viewIngresa.down('#observacionesId').getValue();
        var vacunos = viewIngresa.down('#vacunosId').getValue();
        var vacunosori = viewIngresa.down('#vacunosoriId').getValue();
        var caballares = viewIngresa.down('#caballaresId').getValue();
        var porcinos = viewIngresa.down('#porcinosId').getValue();
        var ovinos = viewIngresa.down('#ovinosId').getValue();
        var caprinos = viewIngresa.down('#caprinosId').getValue();
        var vali = viewIngresa.down('#valiId').getValue();

        var stIngreso = this.getIngresofmaStore();
        var stIngresop = this.getIngresofmapendientesStore();
        

        if (vali == "ok"){
            
            var vacunos = vacunosori;
        }

        
        if (!rutfmaor){            
            var rutfmaor = rutfma;
        };

        var stItem = Ext.getStore('ingresodiios');
        
        if(numfma==0){
            Ext.Msg.alert('Ingrese AL Formulario');
            return;   
        }

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'ingresos_fma/saveingreso',
            params: {
                items: Ext.JSON.encode(dataItems),
                idfma: Idfma,
                numfma: numfma,
                rutfma: rutfma,
                ruttitular: ruttitular,
                rutguia: ruttitular,
                rutdestino: rutdestino,
                numguia: numguia,
                fechaproceso: fechaproceso,
                ruporigen: ruporigen,
                fechasalida: Ext.Date.format(fechasalida,'Y-m-d'),
                horasalida:  Ext.Date.format(horasalida,'H:i'),
                idrupdestino: idrupdestino,
                fechallegada: Ext.Date.format(fechallegada,'Y-m-d'),
                horallegada: Ext.Date.format(horallegada,'H:i'),
                idtransportista: idtransportista,
                camion: camion,
                carro: carro,
                observaciones: observaciones,
                vacunos: vacunos,
                caballares: caballares,
                porcinos: porcinos,
                ovinos: ovinos,
                caprinos: caprinos
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == false) {
                    Ext.Msg.alert('Rup Origen no Existe');
                 return;

                }else{

                 stIngreso.reload();   

                 Ext.Msg.alert('Datos Grabados Exitosamente');
                 
                 return;
                }

        }

        });

        viewIngresa.close();
        stIngreso.load(); 
        stIngresop.load();  
        stIngreso.reload();   
        

    },

    limpiagrilla: function(){

        console.log("llegamos2");

        var stIngreso = this.getIngresofmaStore();
        var stIngresop = this.getIngresofmapendientesStore();
        stIngreso.load(); 
        stIngresop.load();    
        
    },

    validarut: function(){

        var view =this.getEditardiio();
        var rut = view.down('#ruttransportistaId').getValue();
        var numero = rut.length;
        var cero = "";
      
        if(numero==0){
            var edit = Ext.create('Diio.view.Ingreso_diio.BuscarTransportistaingreso');            
                  
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
            url: preurl + 'transportistas/validaRut?valida='+rut,
            params: {
                id: 1
            },
            success: function(response){
                
                var resp = Ext.JSON.decode(response.responseText);

                if (resp.success == true) {
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down("#id_transportista").setValue(cliente.id)
                        view.down("#nombretransportistaId").setValue(cliente.nombre)
                        view.down("#camionId").setValue(cliente.camion)
                        view.down("#carroId").setValue(cliente.carro)
                        view.down("#ruttransportistaId").setValue(rut)
                                                                   
                }else{
                     var editt = Ext.create('Diio.view.Despacho_diio.Ingresar_transportistas').show();
                     editt.down("#ruttransportistaId").setValue(rut);
                     view.down("#ruttransportistaId").setValue(rut);
                }
                    
                }else{
                       
                       Ext.Msg.alert('Informacion', 'Rut Incorrecto');
                       view.down("#rutId").setValue(cero);
                       return false
                     
                }
                //view.close()

            }

        });       
      }
    },

    editarfma: function() {

        var view = this.getIngresoprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];         
           
            var numfma = row.data.num_fma;
            var numguia = row.data.num_guia;
            var ruporigen = row.data.rup_origen;
            var rupdestino = row.data.rup_destino;
            var rutorigen = row.data.rut_titular_origen;
            var ruttitular = row.data.rut_titular;
            var rutfma = row.data.rut_titular_fma;
            var rutfmaori = row.data.ruttitular;
            var rutdestinomue = "90.380.000-3";
            var rutdestino = "903800003";
            var numfactura = row.data.num_factura;
            var fechaproc = row.data.fecha_proceso;
            var vacunos = (parseInt(row.data.vacunos));
            var caballares = (parseInt(row.data.caballares));
            var porcinos = (parseInt(row.data.porcinos));
            var ovinos = (parseInt(row.data.ovinos));
            var caprinos = (parseInt(row.data.caprinos));
            var fechallegada = row.data.fecha_llegada;
            var horallegada = row.data.hora_llegada;
            var idtransportista = row.data.id_transportista;
            var ruttransportista = row.data.rut_transportista;
            var nomtransportista = row.data.nom_transportista;
            var camion = row.data.camion;
            var carro = row.data.carro;
            var observaciones = row.data.observaciones;
            var total = vacunos + caballares + porcinos + ovinos + caprinos;
        
            var idfma = row.data.id;
            console.log("llegamos");
            Ext.Ajax.request({
            url: preurl + 'ingresos_fma/editafma',
            params: {
                idfma : idfma                
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        var view = Ext.create('Diio.view.Ingreso_diio.Editar').show();
                        view.down('#vacunosId').setValue(Ext.util.Format.number(cliente.vacunos, '0,000'));
                        view.down('#vacunosoriId').setValue(Ext.util.Format.number(cliente.vacunos, '0,000'));
                        view.down('#caballaresId').setValue(Ext.util.Format.number(caballares, '0,000'));
                        view.down('#porcinosId').setValue(Ext.util.Format.number(porcinos, '0,000'));
                        view.down('#ovinosId').setValue(Ext.util.Format.number(ovinos, '0,000'));
                        view.down('#caprinosId').setValue(Ext.util.Format.number(caprinos, '0,000'));
                        view.down('#totalanimalId').setValue(Ext.util.Format.number(total, '0,000'));
                        view.down("#Idfma").setValue(cliente.id);
                        view.down("#numfmaId").setValue(cliente.num_fma);
                        view.down("#numguiaId").setValue(cliente.num_guia);
                        view.down("#ruttitularId").setValue(cliente.rut_titular);
                        view.down("#ruttitularorId").setValue(cliente.rut_titular);
                        view.down("#fechaprocesofmaId").setValue(cliente.fecha_proceso);
                        view.down("#rupId").setValue(ruporigen);
                        if(cliente.rut_fma_origen){
                        view.down("#rutfmaId").setValue(cliente.rut_fma_origen);
                        view.down("#rutfmaorId").setValue(cliente.rut_fma_origen);
                        }else{
                          view.down("#rutfmaId").setValue(rutfmaori);
                          view.down("#rutfmaorId").setValue(rutfmaori);
                            
                        };
                        view.down("#fechasalidaId").setValue(fechallegada);
                        view.down("#horasalidaId").setValue(horallegada);
                        view.down("#fechallegadaId").setValue(fechallegada);
                        view.down("#horallegadaId").setValue(horallegada);

                        view.down("#ruporigenId").setValue(cliente.id_rupdestino);
                        view.down("#ruttitulardestinomueId").setValue(rutdestinomue);
                        view.down("#ruttitulardestinoId").setValue(rutdestino);

                        view.down("#id_transportista").setValue(idtransportista);
                        view.down("#ruttransportistaId").setValue(ruttransportista);
                        view.down("#nombretransportistaId").setValue(nomtransportista);
                        view.down("#camionId").setValue(camion);
                        view.down("#carroId").setValue(carro);
                        view.down("#observacionesId").setValue(observaciones);   
                        
                        }
                }
                 
            }
            });   
            
            var st = this.getIngresodiiosStore()
            st.proxy.extraParams = {idfma : idfma}
            st.load();

            //var view = Ext.create('Diio.view.Ingreso_diio.Editar').show();

            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    actualizapendientes: function(){

        var view = this.getIngresoprincipalpendientes();
        var fecha = view.down('#fechaprocesoId').getValue();
        var stItem = this.getIngresofmapendientesStore();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var idfma = row.data.id;
            var rutguia = row.data.rut_titular;
            var vacdiio = row.data.vacdiio;

           
            //if(vacdiio=="0"){ 
            
            Ext.Ajax.request({
            url: preurl + 'procesocuadraingreso/porcesoingreso',
            params: {
                fechafma : Ext.Date.format(fecha,'Y-m-d'),
                rut : rutguia                
            },
            success: function(){
                 stItem.load();
                 Ext.Msg.alert('Datos Grabados Exitosamente');
                 return;
                //window.open(preurl + 'ingresos_diios/exportPDF/?numfma='+numfma);
            }
            });              
                      
            
       // }else{

          // Ext.Msg.alert('Alerta', 'Registro Diio Vacunos debe estar Cero');
         //  return;


            
        //};
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;

           
        }

       

        
    },

    editarfma2: function() {

        var view = this.getIngresoprincipalpendientes();
       if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];            
           
            var numfma = row.data.num_fma;
            var numguia = row.data.num_guia;
            var ruporigen = row.data.rup_origen;
            var rupdestino = row.data.rup_destino;
            var rutorigen = row.data.rut_titular_origen;
            var ruttitular = row.data.rut_titular;
            var rutfma = row.data.rut_titular_fma;
            var rutfmaori = row.data.ruttitular;
            var rutdestinomue = "90.380.000-3";
            var rutdestino = "903800003";
            var numfactura = row.data.num_factura;
            var fechaproc = row.data.fecha_proceso;
            var vacunos = (parseInt(row.data.vacunos));
            var caballares = (parseInt(row.data.caballares));
            var porcinos = (parseInt(row.data.porcinos));
            var ovinos = (parseInt(row.data.ovinos));
            var caprinos = (parseInt(row.data.caprinos));
            var fechallegada = row.data.fecha_llegada;
            var horallegada = row.data.hora_llegada;
            var idtransportista = row.data.id_transportista;
            var ruttransportista = row.data.rut_transportista;
            var nomtransportista = row.data.nom_transportista;
            var camion = row.data.camion;
            var carro = row.data.carro;
            var observaciones = row.data.observaciones;
            var total = vacunos + caballares + porcinos + ovinos + caprinos;
        
            var idfma = row.data.id;
            console.log("llegamos");
            Ext.Ajax.request({
            url: preurl + 'ingresos_fma/editafma',
            params: {
                idfma : idfma                
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        var view = Ext.create('Diio.view.Ingreso_diio.Editar').show();
                        view.down('#vacunosId').setValue(Ext.util.Format.number(cliente.vacunos, '0,000'));
                        view.down('#vacunosoriId').setValue(Ext.util.Format.number(cliente.vacunos, '0,000'));
                        view.down('#caballaresId').setValue(Ext.util.Format.number(caballares, '0,000'));
                        view.down('#porcinosId').setValue(Ext.util.Format.number(porcinos, '0,000'));
                        view.down('#ovinosId').setValue(Ext.util.Format.number(ovinos, '0,000'));
                        view.down('#caprinosId').setValue(Ext.util.Format.number(caprinos, '0,000'));
                        view.down('#totalanimalId').setValue(Ext.util.Format.number(total, '0,000'));
                        view.down("#Idfma").setValue(cliente.id);
                        view.down("#numfmaId").setValue(cliente.num_fma);
                        view.down("#numguiaId").setValue(cliente.num_guia);
                        view.down("#ruttitularId").setValue(cliente.rut_titular);
                        view.down("#ruttitularorId").setValue(cliente.rut_titular);
                        view.down("#fechaprocesofmaId").setValue(cliente.fecha_proceso);
                        view.down("#rupId").setValue(ruporigen);
                        if(cliente.rut_fma_origen){
                        view.down("#rutfmaId").setValue(cliente.rut_fma_origen);
                        view.down("#rutfmaorId").setValue(cliente.rut_fma_origen);
                        }else{
                          view.down("#rutfmaId").setValue(rutfmaori);
                          view.down("#rutfmaorId").setValue(rutfmaori);
                            
                        };
                        view.down("#fechasalidaId").setValue(fechallegada);
                        view.down("#horasalidaId").setValue(horallegada);
                        view.down("#fechallegadaId").setValue(fechallegada);
                        view.down("#horallegadaId").setValue(horallegada);

                        view.down("#ruporigenId").setValue(cliente.id_rupdestino);
                        view.down("#ruttitulardestinomueId").setValue(rutdestinomue);
                        view.down("#ruttitulardestinoId").setValue(rutdestino);

                        view.down("#id_transportista").setValue(idtransportista);
                        view.down("#ruttransportistaId").setValue(ruttransportista);
                        view.down("#nombretransportistaId").setValue(nomtransportista);
                        view.down("#camionId").setValue(camion);
                        view.down("#carroId").setValue(carro);
                        view.down("#observacionesId").setValue(observaciones);   
                        
                        }
                }
                 
            }
            });   
            
            var st = this.getIngresodiiosStore()
            st.proxy.extraParams = {idfma : idfma}
            st.load();

            //var view = Ext.create('Diio.view.Ingreso_diio.Editar').show();

            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },



    buscarfmaingreso:  function() {
        var view = this.getIngresoprincipal()
        var st = this.getIngresofmaStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var fecha = view.down('#fechaprocesoId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion,
                                fecha: Ext.Date.format(fecha,'Y-m-d'),}
        st.load();
    },

    buscarfmaingresopendientes:  function() {
        var view = this.getIngresoprincipalpendientes()
        var st = this.getIngresofmapendientesStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var fecha = view.down('#fechaprocesoId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion,
                                fecha: Ext.Date.format(fecha,'Y-m-d'),}
        st.load();
    },


    exportarExcelSag: function(){

        var jsonCol = new Array()
        var i = 0;
        var grid =this.getIngresoprincipal();
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })     
                         
        window.open(preurl + 'adminServicesExcel/exportarExcelSag?cols='+Ext.JSON.encode(jsonCol));
 
    },

    selectItem: function() {
        
        var view = this.getIngresodiio();
        var ruporigen = view.down('#ruporigenId');
        var stCombo = ruporigen.getStore();
        var record = stCombo.findRecord('id', ruporigen.getValue()).data;
        view.down('#ruttitulardestinoId').setValue(Ext.util.Format.number(record.rut));
        view.down('#rupdestinoId').setValue(Ext.util.Format.number(record.id));
    },

    selectItem2: function() {
        
        var view = this.getIngresootros();
        var ruporigen = view.down('#ruporigenId');
        var stCombo = ruporigen.getStore();
        var record = stCombo.findRecord('id', ruporigen.getValue()).data;
        view.down('#ruttitulardestinoId').setValue(Ext.util.Format.number(record.rut));
        view.down('#rupdestinoId').setValue(Ext.util.Format.number(record.id));
    },

    grabaringreso: function() {

        var viewIngresa = this.getIngresodiio();
        var numfma = viewIngresa.down('#numfmaId').getValue();
        //var Idfma = viewIngresa.down('#Idfma').getValue();        
        var ruttitular = viewIngresa.down('#ruttitularId').getValue();
        var rutfma = viewIngresa.down('#ruttitularId').getValue();
        var rutfmaor = viewIngresa.down('#ruttitularId').getValue();
        var numguia = viewIngresa.down('#numguiaId').getValue();
        var fechaproceso = viewIngresa.down('#fechaprocesofmaId').getValue();
        var ruporigen = viewIngresa.down('#rupId').getValue();
        var fechasalida = viewIngresa.down('#fechasalidaId').getValue();
        var horasalida = viewIngresa.down('#horasalidaId').getValue();
        var idrupdestino = viewIngresa.down('#ruporigenId').getValue();
        var rutdestino = viewIngresa.down('#ruttitulardestinoId').getValue();
        var fechallegada = viewIngresa.down('#fechallegadaId').getValue();
        var horallegada = viewIngresa.down('#horallegadaId').getValue();
        var idtransportista = viewIngresa.down('#id_transportista').getValue();
        var camion = viewIngresa.down('#camionId').getValue();
        var carro = viewIngresa.down('#carroId').getValue();
        var observaciones = viewIngresa.down('#observacionesId').getValue();

        var vacunos = viewIngresa.down('#vacunosId').getValue();
        var caballares = viewIngresa.down('#caballaresId').getValue();
        var porcinos = viewIngresa.down('#porcinosId').getValue();
        var ovinos = viewIngresa.down('#ovinosId').getValue();
        var caprinos = viewIngresa.down('#caprinosId').getValue();

        var stItem = this.getIngresosItemsStore();
        var stIngreso = this.getIngresofmaStore();

        if(numfma==0){
            Ext.Msg.alert('Ingrese AL Formulario');
            return;   
        }

        if(numguia==0){
            Ext.Msg.alert('Ingrese AL Formulario');
            return;   
        }

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'ingresos_diios/save',
            params: {
                items: Ext.JSON.encode(dataItems),
                //idfma: Idfma,
                numfma: numfma,
                rutfma: rutfmaor,
                ruttitular: ruttitular,
                rutguia: ruttitular,
                rutdestino: rutdestino,
                numguia: numguia,
                fechaproceso: fechaproceso,
                ruporigen: ruporigen,
                fechasalida: Ext.Date.format(fechasalida,'Y-m-d'),
                horasalida:  Ext.Date.format(horasalida,'H:i'),
                idrupdestino: idrupdestino,
                fechallegada: Ext.Date.format(fechallegada,'Y-m-d'),
                horallegada: Ext.Date.format(horallegada,'H:i'),
                idtransportista: idtransportista,
                camion: camion,
                carro: carro,
                observaciones: observaciones,
                vacunos: vacunos,
                caballares: caballares,
                porcinos: porcinos,
                ovinos: ovinos,
                caprinos: caprinos
            },
            success: function(){
                stIngreso.load();
                 Ext.Msg.alert('Datos Grabados Exitosamente');
                 return;
                //window.open(preurl + 'ingresos_diios/exportPDF/?numfma='+numfma);
        }
        });

        viewIngresa.close();
        stIngreso.load();
             
    },

    grabaringresootros: function() {

        var viewIngresa = this.getIngresootros();
        var numfma = viewIngresa.down('#numfmaId').getValue();
        var numguia = viewIngresa.down('#numguiaId').getValue();
        var ruttitular = viewIngresa.down('#ruttitularId').getValue();
        var fechaproceso = viewIngresa.down('#fechaprocesofmaId').getValue();
        var ruporigen = viewIngresa.down('#rupId').getValue();
        var fechasalida = viewIngresa.down('#fechasalidaId').getValue();
        var horasalida = viewIngresa.down('#horasalidaId').getValue();
        var idrupdestino = viewIngresa.down('#rupdestinoId').getValue();
        var fechallegada = viewIngresa.down('#fechallegadaId').getValue();
        var horallegada = viewIngresa.down('#horallegadaId').getValue();
        var idtransportista = viewIngresa.down('#id_transportista').getValue();
        var camion = viewIngresa.down('#camionId').getValue();
        var carro = viewIngresa.down('#carroId').getValue();
        var observaciones = viewIngresa.down('#observacionesId').getValue();
        var caballaresId = viewIngresa.down('#caballaresId').getValue();
        var porcinosId = viewIngresa.down('#porcinosId').getValue();
        var caprinosId = viewIngresa.down('#caprinosId').getValue();
        var lanaresId = viewIngresa.down('#lanaresId').getValue();
        var stIngreso = this.getIngresofmaStore();
            
    
        if(numfma==0){
            Ext.Msg.alert('Ingrese AL Formulario');
            return;   
            }

        if(numguia==0){
            Ext.Msg.alert('Ingrese AL Formulario');
            return;   
            }

        Ext.Ajax.request({
            url: preurl + 'ingresos_diios/saveotros',
            params: {
                numfma: numfma,
                numguia: numguia,
                fechaproceso: fechaproceso,
                ruttitular: ruttitular,
                ruporigen: ruporigen,
                fechasalida: Ext.Date.format(fechasalida,'Y-m-d'),
                horasalida:  Ext.Date.format(horasalida,'H:i'),
                idrupdestino: idrupdestino,
                fechallegada: Ext.Date.format(fechallegada,'Y-m-d'),
                horallegada: Ext.Date.format(horallegada,'H:i'),
                idtransportista: idtransportista,
                camion: camion,
                carro: carro,
                observaciones: observaciones,
                caballaresId: caballaresId,
                lanaresId: lanaresId,
                porcinosId: porcinosId,
                caprinosId: caprinosId,
             
            },
            success: function(){

                 Ext.Msg.alert('Datos Grabados Exitosamente');
                 return;
                 stIngreso.load();
              //window.open(preurl + 'ingresos_diios/exportPDF/?numfma='+numfma);
        }
        });

        viewIngresa.close();
   
    },

    eliminaritem: function() {
        var view = this.getIngresodiio();
        var grid  = view.down('#itemsgridId');
        var stIngreso = view.down('#itemsgridId');
        var tipo = grid.getStore().data.items[0].data.nombre;
       
      
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            grid.getStore().remove(row);
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

        stIngreso.load();
                      
        },

    cerraringreso: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
     
    },

    mingreso: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'ingresoprincipal'});
    },

    mingresopendientes: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'ingresoprincipalpendientes'});
    },

    validarup: function(){

        var view =this.getIngresodiio();
        var rup = view.down('#rupId').getValue();
       
               
        Ext.Ajax.request({
            url: preurl + 'rupoficiales/validaRup?valida='+rup,
            params: {
                id: 1
            },

            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);

                if (resp.success == true) {
                    
                    //var edit = Ext.create('Diio.view.Ingreso_diio.Ingreso');
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down("#ruttitularId").setValue(cliente.rut_titular)
                        
                    }else{
                        view.down("#rupId").setValue(rup)
                    }
                    
                }else{
                      Ext.Msg.alert('Informacion', 'Rup No Existe');
                      return false
                }

                //view.close()

            }

        });       
      
    },

    validarup2: function(){

        var view =this.getIngresootros();
        var rup = view.down('#rupId').getValue();
       
               
        Ext.Ajax.request({
            url: preurl + 'rupoficiales/validaRup?valida='+rup,
            params: {
                id: 1
            },

            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);

                if (resp.success == true) {
                    
                    //var edit = Ext.create('Diio.view.Ingreso_diio.Ingreso');
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down("#ruttitularId").setValue(cliente.rut_titular)
                        
                    }else{
                        view.down("#rupId").setValue(rup)
                    }
                    
                }else{
                      Ext.Msg.alert('Informacion', 'Rup No Existe');
                      return false
                }

                //view.close()

            }

        });       
      
    },

    ingresootros: function(){        
        Ext.create('Diio.view.Ingreso_diio.IngresoOtros').show();
    },


    ingresodiios: function(){        
        Ext.create('Diio.view.Ingreso_diio.Ingreso').show();
    },

    agregarItem: function() {
        var view = this.getIngresodiio();
        var num_fma = view.down('#numfmaId').getValue();
        var stItem = this.getIngresosItemsStore();
        var animal = view.down('#animalId');
        var stCombo = animal.getStore();
        var diioa = view.down('#diioId').getValue();
        var record = stCombo.findRecord('id', animal.getValue()).data;
        var rup = view.down('#rupId').getValue();
        var exists = 0;
        var total = 0;
        var novillo = view.down('#vacunosId').getValue();
        
        if(rup.length==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar al Formulario.');
            return false;
        }

         if(num_fma.length==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar al Formulario.');
            return false;
        }

        stItem.each(function(r){
            if(r.data.diio == diioa){
                Ext.Msg.alert('Alerta', 'El registro ya existe.');
                exists = 1;
                cero="";
                view.down('#diioId').setValue(cero);
                return; 
            }
        });

        if(exists == 1)
            return;

        var nombre = (record.Nombre);

        stItem.add(new Diio.model.Ingresos.Item({
            num_fma: num_fma,
            id_categoria: animal.getValue(),
            nombre: nombre,
            diio: view.down('#diioId').getValue()
                    
        }));

        var cero = "";
        view.down('#diioId').setValue(cero);

       
        total = vacunos + 1;
        view.down('#vacunosId').setValue(Ext.util.Format.number(total, '0,000'));
        view.down('#totalanimalId').setValue(Ext.util.Format.number(total, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0,000'));
        
    },


    generarformulariopdf: function(){
        var view = this.getIngresoprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
        window.open(preurl +'ingresos_diios/exportPDF/?idfma=' + row.data.id)
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

      
    validaruttransportista: function(){

        var view =this.getIngresodiio();
        var rut = view.down('#ruttransportistaId').getValue();
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
                    
                   if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down("#id_transportista").setValue(cliente.id)
                        view.down("#nombretransportistaId").setValue(cliente.nombre)
                        view.down("#camionId").setValue(cliente.camion)
                        view.down("#carroId").setValue(cliente.carro)
                        var numero = resp.numero + 1;
                    }else{
                        view.down("#ruttransportistaId").setValue(rut)
                    }
                    
                }else{
                      Ext.Msg.alert('Informacion', 'Rut Incorrecto');
                      return false
                }

            }

        });       
      
    },

     validaruttransportista2: function(){

        var view =this.getIngresootros();
        var rut = view.down('#ruttransportistaId').getValue();
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
                    
                   if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down("#id_transportista").setValue(cliente.id)
                        view.down("#nombretransportistaId").setValue(cliente.nombre)
                        view.down("#camionId").setValue(cliente.camion)
                        view.down("#carroId").setValue(cliente.carro)
                        var numero = resp.numero + 1;
                    }else{
                        view.down("#ruttransportistaId").setValue(rut)
                    }
                    
                }else{
                      Ext.Msg.alert('Informacion', 'Rut Incorrecto');
                      return false
                }

            }

        });       
      
    },
    
     
  
});










