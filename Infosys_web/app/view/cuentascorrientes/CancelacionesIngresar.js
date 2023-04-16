Ext.define('Infosys_web.view.cuentascorrientes.CancelacionesIngresar', {
    extend: 'Ext.window.Window',
    alias : 'widget.cancelacionesingresar',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],

    title : 'Ingreso de Cancelaci&oacute;n',
    autoShow: true,
    width: 1160,
    modal: true,
    iconCls: 'icon-sheet',
    bodyPadding: 7,
    initComponent: function() {
        var me = this;

         var cuentascontablecancela = Ext.create('Ext.data.Store', {
            model: 'Infosys_web.model.cuentascontable',
            proxy: {
              type: 'ajax',
                url : preurl +'cuentacorriente/cuentascancela',
                reader: {
                    type: 'json',
                    root: 'data'
                }
            },
            autoLoad: true
        });   


         var cuentasDocumentos = Ext.create('Ext.data.Store', {
            fields: ['id', 'nombre', 'saldo', 'documento'],
            proxy: {
              type: 'ajax',
                actionMethods:  {
                    read: 'POST'
                 },              
                url : preurl +'cuentacorriente/getDocumentosByCtacte',
                reader: {
                    type: 'json',
                    root: 'data'
                }
            },
            autoLoad: true            
        });              
       
        this.items = [
            {
                xtype: 'form',
                padding: '5 5 0 5',
                labelWidth: 150,
                //url: preurl + 'cuentacorriente/saveCuentaCorriente',
                border: false,
                style: 'background-color: #fff;',
                fieldDefaults: {
                    labelAlign: 'left',
                    combineErrors: true,
                    msgTarget: 'side'
                },

                items: [
                    {
                        xtype: 'numberfield',
                        name : 'id',
                        hidden:true
                    },
                    {
                        xtype: 'numberfield',
                        itemId : 'ctacteId',
                        name: 'ctacteId',
                        hidden:true
                    },{
                        xtype: 'numberfield',
                        itemId : 'totalinteres',
                        name: 'totalinteres',
                        hidden:true
                    },{
                        xtype: 'textfield',
                        itemId : 'titlepanel',
                        name: 'titlepanel',
                        hidden:true
                    },{
                        xtype: 'numberfield',
                        itemId : 'totaldebe',
                        name: 'totaldebe',
                        hidden:true
                    },{
                        xtype: 'numberfield',
                        itemId : 'totalhaber',
                        name: 'totalhaber',
                        hidden:true
                    },{
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        labelWidth: 150,
                        items: [{
                            xtype: 'numberfield',
                            fieldLabel: 'Folio',
                            width: 250,
                            name: 'numero',
                            itemId: 'numeroId',
                            readOnly: true
                        },{
                        xtype: 'splitter'
                        },{
                            xtype: 'datefield',
                            fieldLabel: 'Fecha',
                            width: 250,
                            name: 'Fecha',
                            itemId: 'fechaId',
                            value: new Date()
                        }]
                    },{
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        labelWidth: 150,
                        items: [{
                            xtype: 'textfield',
                            fieldLabel: 'Tipo',
                            width: 250,
                            name: 'tipoComprobante',
                            itemId: 'tipoComprobante',
                            value: "INGRESO",
                            readOnly: true
                        }
                        ]
                    },{
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        labelWidth: 150,
                        items: [{
                            xtype: 'textfield',
                            fieldLabel: 'Observaci&oacute;n',
                            width: 500,
                            name: 'detalle',
                            itemId: 'detalleId'
                        },{
                        xtype: 'splitter'
                        },{
                            xtype: 'numberfield',
                            fieldLabel: 'Tasa Inter&eacute;s',
                            width: 250,
                            name: 'tasainteres',
                            itemId: 'tasainteres',
                            readOnly: true
                        },{
                        xtype: 'splitter'
                        },{
                            xtype: 'numberfield',
                            fieldLabel: 'D&iacute;as Cobro',
                            width: 250,
                            allowDecimals : false,
                            name: 'diascobro',
                            itemId: 'diascobro',
                            readOnly: true
                        },{
                        xtype: 'splitter'
                        },{
                            xtype: 'button',
                            text: 'Modificar',
                            maxHeight: 25,
                            width: 80,
                            allowBlank: true,
                            //disabled : true,                                            
                            action: 'modificatasa',
                            itemId: 'modificaTasa'
                        }]
                    },{
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        labelWidth: 250,
                        items: [{
                            xtype: 'textfield',
                            fieldLabel: 'Glosa Factura',
                            width: 500,
                            name: 'glosafact',
                            itemId: 'glosafact',
                            readOnly: true
                        },{
                        xtype: 'displayfield',
                        itemId : 'mensaje_glosafact',
                        fieldLabel : '',
                        labelStyle: ' font-weight:bold',
                        fieldStyle: 'font-weight:bold;background-color:yellow;',
                        value : '',
                        labelWidth: 200,
                        hidden : false
                    }]
                    },{
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        labelWidth: 250,
                        items: [{
                                    xtype: 'combo',
                                    itemId: 'tipogastoId',
                                    width: 450,
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    fieldLabel: '<b>TIPO INGRESO</b>',
                                    forceSelection : true,
                                    name : 'id_tipogasto',
                                    valueField : 'id',
                                    displayField : 'nombre',
                                    emptyText : "Seleccione",
                                    store : 'Tipo_gasto',
                                    readOnly: true
                                    //disabled : true, 
                                },{
                                    xtype: 'combo',
                                    itemId: 'tipocondpagoId',
                                    width: 300,
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    fieldLabel: '<b>COND.PAGO</b>',
                                    forceSelection : true,
                                    name : 'id_condpago',
                                    valueField : 'id',
                                    displayField : 'nombre',
                                    emptyText : "Seleccione",
                                    store : 'Cond_pago',
                                    readOnly: true
                                    //disabled : true, 
                                } ,{
                                            xtype: 'datefield',
                                            fieldCls: 'required',
                                            maxHeight: 25,
                                             labelWidth: 100,
                                            width: 300,
                                            fieldLabel: '<b>VENC</b>',
                                            itemId: 'fechavencId',
                                            name: 'fecha_venc',
                                            value: new Date(),
                                            readOnly: true

                                }
                             ]
                    }                   

                ]
            }, 
                    {
                        xtype: 'fieldset',
                        title: 'Detalle',
                        items: [{
                        xtype: 'grid',
                        tbar: [],
                        selModel: {
                        selType: 'cellmodel'
                        },
                        plugins: [
                            Ext.create('Ext.grid.plugin.CellEditing', {
                                clicksToEdit: 1,
                                listeners: {
                                
                                    beforeedit: function(e, editor){

                                       var idctacte = me.down("#ctacteId").getValue()
                                       var fechaId = me.down("#fechaId").getValue()
                                       var tasainteres = me.down("#tasainteres").getValue()
                                       var diascobro = me.down("#diascobro").getValue()

                                        cuentasDocumentos.proxy.extraParams = {
                                                                                idcuentacorriente : idctacte,
                                                                                feccancelacion: fechaId,
                                                                                tasainteres: tasainteres,
                                                                                diascobro: diascobro
                                                                            }
                                        cuentasDocumentos.load();
                                                                                
                                        if(editor.field == 'cuenta'){  // SI ES CUENTA SELECCIONA NORMAL

                                            return true;
                                        }else if(editor.field == 'documento'){
                                            var record = editor.record;
                                            if(record.get('cuenta') == 0 || record.get('cuenta') == null){
                                                Ext.Msg.alert('Alerta', 'Debe seleccionar una cuenta.');
                                                return false;
                                            }else{
                                                reccuenta = cuentascontablecancela.findRecord('id', record.get('cuenta'));
                                                if(reccuenta.get('cancelaabono') == 0){
                                                    Ext.Msg.alert('Alerta', 'Cuenta Seleccionada no permite asociar a un documento.');
                                                    return false;
                                                }
                                            }

                                        }else if(editor.field == 'docpago'){ 
                                            var record = editor.record;
                                            if(record.get('cuenta') == 0 || record.get('cuenta') == null){
                                                Ext.Msg.alert('Alerta', 'Debe seleccionar una cuenta.');
                                                return false;
                                            }else{
                                                if(record.get('cuenta') != 7){ // solo aplica a cuenta banco
                                                    Ext.Msg.alert('Alerta', 'Nro. documento solo aplica a cancelaciones con banco');
                                                    return false;
                                                }
                                            }




                                        }else if(editor.field == 'debe' || editor.field == 'haber'){ 
                                             var record = editor.record;
                                             if(record.get('cuenta') != 0 && record.get('cuenta') != null){
                                                if(editor.field == 'debe' && record.get('haber') > 0){
                                                    Ext.Msg.alert('Alerta', 'Ya existe un valor asociado al Haber.');
                                                    return false;  
                                                }else if(editor.field == 'haber' && record.get('debe') > 0){
                                                    Ext.Msg.alert('Alerta', 'Ya existe un valor asociado al Debe.');
                                                    return false; 
                                                }else if(editor.field == 'debe' && record.get('saldo') > 0 && record.get('tipodocumento') != 11){
                                                    Ext.Msg.alert('Alerta', 'Abonos para el documento se realizan en el haber.');
                                                    return false;       
                                                }else if(editor.field == 'haber' && record.get('saldo') > 0 && record.get('tipodocumento') == 11){
                                                    Ext.Msg.alert('Alerta', 'Abonos para el documento se realizan en el debe.');
                                                    return false;                                                           
                                                }else if(editor.field == 'haber' && record.get('saldo') == 0){
                                                    Ext.Msg.alert('Alerta', 'Cargos se realizan en el debe.');
                                                    return false;
                                                }else{        
                                                    reccuenta = cuentascontablecancela.findRecord('id', record.get('cuenta'));
                                                    if(editor.field == 'debe' && reccuenta.get('cancelaabono') == 1 && record.get('tipodocumento') != 11){
                                                        Ext.Msg.alert('Alerta', 'Abonos se realizan en el haber.');
                                                        return false;
                                                    }else if(editor.field == 'haber' && reccuenta.get('cancelaabono') == 1 && record.get('tipodocumento') == 11){
                                                        Ext.Msg.alert('Alerta', 'Abonos se realizan en el debe.');
                                                        return false;                                                        
                                                    }else if(editor.field == 'haber' && reccuenta.get('cancelacargo') == 1){
                                                        Ext.Msg.alert('Alerta', 'Cargos se realizan en el debe.');
                                                    }else{
                                                        return true;
                                                    }                                                                                                                                                
                                                }
                                             }else{

                                               Ext.Msg.alert('Alerta', 'Seleccione una cuenta.');
                                               return false;                                             
                                             }
                                     
                                        }

                                        console.log('beforeedit')
                                        var title = me.down('#titlepanel').getValue();
                                        var monto_saldo = 0;
                                        var monto_cancelado = 0;
                                        var monto_interes_cancelado = 0;
                                        var dataItems = new Array();
                                        var stItem = me.down("grid").getStore();
                                        stItem.each(function(r){
                                            dataItems.push(r.data);
                                            monto_saldo += r.data.saldo === undefined || r.data.saldo == '' ? 0 : Math.round(parseFloat(r.data.saldo));
                                            monto_cancelado += r.data.haber;
                                            monto_interes_cancelado += r.data.interes === undefined || r.data.interes == '' ? 0 : Math.round(parseFloat( ( Math.round(parseFloat(r.data.interes)) / monto_saldo  )*Math.round(parseFloat(r.data.haber)) ));

                                        });
                                        title += ' . | Saldo Acumulado : $ ' + monto_cancelado  + '.  Inter&eacute;s Acumulado : $ ' + monto_interes_cancelado;
                                        me.down('#ingresoDetalleCancelacionId').setTitle(title);
                                        me.down('#totalinteres').setValue(monto_interes_cancelado);        
                                        console.log(stItem.count())                                                                       
                                        if(stItem.count() > 1){
                                            me.down('#modificaTasa').setDisabled(true)

                                        }     

                                        if(monto_interes_cancelado > 0){
                                             me.down('#glosafact').setReadOnly(false)
                                             me.down('#mensaje_glosafact').setValue('&nbsp;&nbsp;Se generar&aacute; factura de glosa correspondiente a los intereses pagados')
                                             me.down('#tipogastoId').setReadOnly(false)
                                             me.down('#tipocondpagoId').setReadOnly(false)


                                        }else{
                                             me.down('#glosafact').setReadOnly(true)
                                             me.down('#glosafact').setValue('')
                                             me.down('#mensaje_glosafact').setValue('')
                                             me.down('#tipogastoId').setValue('')
                                             me.down('#tipocondpagoId').setValue('')
                                             me.down('#tipogastoId').setReadOnly(true)
                                             me.down('#tipocondpagoId').setReadOnly(true)
                                             me.down('#fechavencId').setValue(new Date())


                                        }
                                       
                                        
                                    },

                                    validateedit: function(e, editor, eop){
                                        var grid = me.down('grid')

                                   
                                        var record = editor.record; 
                                        //console.log(record)  
                                        if(editor.field == "debe" || editor.field == "haber"){
                                            if(editor.value != null && editor.value != 0){


                                                if(editor.field == "haber" && record.get('saldo') != 0 && parseInt(editor.value) > parseInt(record.get('saldo'))  ){
                                                        Ext.Msg.alert('Alerta', 'Monto no puede ser mayor que el saldo de la cuenta.');
                                                        editor.record.set({haber: 0});  
                                                        return false;
                                                }
                                            }
                                        }else if(editor.field == "cuenta"){
                                                editor.record.set({documento : 0, docpago: 0, saldo : 0, interes: 0, interes_cancelado: 0, debe: 0, haber : 0});  

                                                if(editor.value == null || editor.value == 0){
                                                    editor.record.set({cuenta: 0});  
                                                }else{
                                                    var idctacte = me.down("#ctacteId").getValue()
                                                    cuentasDocumentos.proxy.extraParams = {
                                                                                            idcuentacorriente : idctacte}
                                                    cuentasDocumentos.load();
                                                }

                                        }

                                        console.log('validateedit')
                                        var title = me.down('#titlepanel').getValue();
                                        var monto_saldo = 0;
                                        var monto_cancelado = 0;
                                        var monto_interes_cancelado = 0;
                                        var dataItems = new Array();
                                        var stItem = me.down("grid").getStore();
                                        stItem.each(function(r){
                                            dataItems.push(r.data);
                                            monto_saldo += r.data.saldo === undefined || r.data.saldo == '' ? 0 : Math.round(parseFloat(r.data.saldo));
                                            monto_cancelado += r.data.haber;
                                            monto_interes_cancelado += r.data.interes === undefined || r.data.interes == '' ? 0 : Math.round(parseFloat( ( Math.round(parseFloat(r.data.interes)) / monto_saldo  )*Math.round(parseFloat(r.data.haber)) ));
                                            console.log(r.data.saldo)
                                            console.log(r.data.haber)
                                            console.log(r.data)
                                            console.log(r.data.haber)
                                        });
                                        title += ' . | Saldo Acumulado : $ ' + monto_cancelado  + '.  Inter&eacute;s Acumulado : $ ' + monto_interes_cancelado;
                                        me.down('#ingresoDetalleCancelacionId').setTitle(title);
                                        me.down('#totalinteres').setValue(monto_interes_cancelado);
                                        console.log(stItem.count())  
                                        if(stItem.count() > 1){
                                            me.down('#modificaTasa').setDisabled(true)

                                        }                                      
                                        

                                        if(monto_interes_cancelado > 0){
                                             me.down('#glosafact').setReadOnly(false)
                                             me.down('#mensaje_glosafact').setValue('&nbsp;&nbsp;Se generar&aacute; factura de glosa correspondiente a los intereses pagados')
                                             me.down('#tipogastoId').setReadOnly(false)
                                             me.down('#tipocondpagoId').setReadOnly(false)

                                        }else{
                                             me.down('#glosafact').setReadOnly(true)
                                             me.down('#glosafact').setValue('')
                                             me.down('#mensaje_glosafact').setValue('')
                                             me.down('#tipogastoId').setValue('')
                                             me.down('#tipocondpagoId').setValue('')
                                             me.down('#tipogastoId').setReadOnly(true)
                                             me.down('#tipocondpagoId').setReadOnly(true)      
                                             me.down('#fechavencId').setValue(new Date())                                   

                                        }
                                       
                                       


                                        return true;
                                    },
                                    edit : function(ed, editor) {
                                        var grid = me.down('grid');
                                        var record = editor.record;

                                                                                  

                                        if(editor.field == "cuenta"){

                                            // SI ES UNA CUENTA DE CUADRATURA, SE CANCELA DE INMEDIATO
                                            if(editor.value != null && editor.value != 0){
                                                reccuenta = cuentascontablecancela.findRecord('id', record.get('cuenta'));
                                                if(reccuenta.get('cancelaabono') == 0){
                                                            var diff = parseInt(me.down("#totalhaber").getValue()) - parseInt(me.down('#totaldebe').getValue());
                                                            if(diff > 0){
                                                                editor.record.set({debe: diff}); 
                                                            }
                                                            var numcolumn = record.get('cuenta') == 7 ? 2 : 3;

                                                            grid.plugins[0].startEditByPosition({
                                                                row: editor.rowIdx,
                                                                column: numcolumn
                                                            });    

                                                }else{

                                                            grid.plugins[0].startEditByPosition({
                                                                row: editor.rowIdx,
                                                                column: 1
                                                            });  

                                                }
                                            }
                                        }else if(editor.field == 'docpago'){ 

                                                            grid.plugins[0].startEditByPosition({
                                                                row: editor.rowIdx,
                                                                column: 3
                                                            });                                             

                                        }else if(editor.field == "debe" || editor.field == "haber"){
                                            if(editor.value != null && editor.value != 0){


                                                var sumdebe = 0;
                                                var sumhaber = 0;
                                                stItem = grid.getStore();
                                                
                                                stItem.each(function(r){
                                                    sumdebe += parseInt(r.data.debe);
                                                    sumhaber += parseInt(r.data.haber);
                                                });      

                                                me.down("#totaldebe").setValue(sumdebe);                                      
                                                me.down("#totalhaber").setValue(sumhaber); 

                                                var store = grid.getStore();
                                                if(store.count()-1!=editor.rowIdx){
                                                    if((store.count() - editor.rowIdx) > 1){
                                                                grid.plugins[0].startEditByPosition({
                                                                    row: editor.rowIdx + 1,
                                                                    column: 0
                                                                });  
                                                    }
                                                    return true;
                                                }


                                                store.insert(store.count(), {cuenta:0, documento: 0, docpago:0, glosa : '',interes: 0, interes_cancelado: 0, debe: 0,haber: 0});
                                                var newRow = store.getCount()-1;
                                                grid.plugins[0].startEditByPosition({
                                                    row: newRow,
                                                    column: 0
                                                });
                                            }

                                        }else if(editor.field == "documento"){
                                            editor.record.set({debe: 0});  
                                            editor.record.set({haber: 0});  

                                            var fechaId = me.down("#fechaId").getValue()
                                            var tasainteres = me.down("#tasainteres").getValue()
                                            var diascobro = me.down("#diascobro").getValue()

                                            if(editor.value != null && editor.value != 0){
                                               Ext.Ajax.request({
                                                   //url: preurl + 'cuentacorriente/getCuentaCorriente/' + record.get('cuenta') + '/' + editor.value ,
                                                   url: preurl + 'cuentacorriente/getDocumentoById/',
                                                        params: {
                                                            idDocumento: editor.value,
                                                            feccancelacion: fechaId,
                                                            tasainteres : tasainteres,
                                                            diascobro : diascobro
                                                        },                                                     
                                                   success: function(response, opts) {
                                                      var obj = Ext.decode(response.responseText);
                                                      //console.log(obj)
                                                       // REVISAR QUE EN CASO QUE NO EXISTA CUENTA, DESPLEGAR UN CERO
                                                      editor.record.set({saldo: obj.data[0].saldo});  
                                                      editor.record.set({interes: obj.data[0].monto_interes});  
                                                      editor.record.set({tipodocumento: obj.data[0].tipodocumento});  
                                                      if(obj.data[0].tipodocumento == 11 || obj.data[0].tipodocumento == 102){
                                                        editor.record.set({debe: parseInt(obj.data[0].saldo)});  
                                                        grid.plugins[0].startEditByPosition({
                                                            row: editor.rowIdx,
                                                            column: 5
                                                        });    

                                                      }else{
                                                        editor.record.set({haber: parseInt(obj.data[0].saldo)});  
                                                        grid.plugins[0].startEditByPosition({
                                                            row: editor.rowIdx,
                                                            column: 6
                                                        });    
                                                      }


                                                   },
                                                   failure: function(response, opts) {
                                                      console.log('server-side failure with status code ' + response.status);
                                                   }
                                                });   

                                            }else{
                                                editor.record.set({saldo: 0});  
                                                editor.record.set({tipodocumento: 0});
                                            }



                                        }else if(editor.field == "glosa"){
                                            reccuenta = cuentascontablecancela.findRecord('id', record.get('cuenta'));
                                            if(reccuenta.get('cancelaabono') == 0){                                                
                                                    grid.plugins[0].startEditByPosition({
                                                        row: editor.rowIdx,
                                                        column: 5
                                                    });                                                    
                                            }else{
                                                    if(record.get('tipodocumento') == 11 || record.get('tipodocumento') == 102){
                                                        grid.plugins[0].startEditByPosition({
                                                            row: editor.rowIdx,
                                                            column: 5
                                                        });
                                                    }else{
                                                        grid.plugins[0].startEditByPosition({
                                                                row: editor.rowIdx,
                                                                column: 6
                                                            });
                                                    }               

                                            }
                                        }
                                             



                                        /**** GUARDAR AQUI *******/
                                        //console.log(me.down('#ingresoDetalleCancelacionId').title)
                                        console.log('edit')
                                        var title = me.down('#titlepanel').getValue();
                                        var monto_saldo = 0;
                                        var monto_cancelado = 0;
                                        var monto_interes_cancelado = 0;
                                        var dataItems = new Array();
                                        var stItem = me.down("grid").getStore();
                                        stItem.each(function(r){
                                            dataItems.push(r.data);
                                            monto_saldo += r.data.saldo === undefined || r.data.saldo == '' ? 0 : Math.round(parseFloat(r.data.saldo));
                                            monto_cancelado += r.data.haber;
                                            monto_interes_cancelado += r.data.interes === undefined || r.data.interes == '' ? 0 : Math.round(parseFloat( ( Math.round(parseFloat(r.data.interes)) / monto_saldo  )*Math.round(parseFloat(r.data.haber)) ));

                                        });
                                        title += ' . | Saldo Acumulado : $ ' + monto_cancelado  + '.  Inter&eacute;s Acumulado : $ ' + monto_interes_cancelado;
                                        me.down('#ingresoDetalleCancelacionId').setTitle(title);
                                        me.down('#totalinteres').setValue(monto_interes_cancelado);
                                        console.log(stItem.count())
                                        if(stItem.count() > 1){
                                            me.down('#modificaTasa').setDisabled(true)

                                        }     

                                        if(monto_interes_cancelado > 0){
                                             me.down('#glosafact').setReadOnly(false)
                                             me.down('#mensaje_glosafact').setValue('&nbsp;&nbsp;Se generar&aacute; factura de glosa correspondiente a los intereses pagados')
                                             me.down('#tipogastoId').setReadOnly(false)
                                             me.down('#tipocondpagoId').setReadOnly(false)
                                        }else{
                                             me.down('#glosafact').setReadOnly(true)
                                             me.down('#glosafact').setValue('')
                                             me.down('#mensaje_glosafact').setValue('')
                                             me.down('#tipogastoId').setValue('')
                                             me.down('#tipocondpagoId').setValue('')
                                             me.down('#tipogastoId').setReadOnly(true)
                                             me.down('#tipocondpagoId').setReadOnly(true)    
                                             me.down('#fechavencId').setValue(new Date())                                             
                                        }
                                        
                                        var interes_linea = record.get('interes') === undefined ||  record.get('interes') == '' ? 0 : Math.round(parseFloat( ( Math.round(parseFloat(record.get('interes'))) / record.get('saldo')  )*Math.round(parseFloat(record.get('haber'))) ));
                                       
                                        record.set({interes_cancelado: interes_linea});  
                                       
                                        Ext.Ajax.request({
                                           //url: preurl + 'cuentacorriente/getCuentaCorriente/' + record.get('cuenta') + '/' + editor.value ,
                                           url: preurl + 'cuentacorriente/saveCancelacionParcial/',
                                                params: {
                                                     ctacteId: me.down("#ctacteId").getValue(),
                                                     fecha: me.down('#fechaId').getValue(),
                                                     numero: me.down('#numeroId').getValue(),
                                                     tipoComprobante: me.down('#tipoComprobante').getValue(),
                                                     detalle: me.down('#detalleId').getValue(),
                                                     items: Ext.JSON.encode(dataItems),
                                                     origen : 'CANCELACION'
                                                },                                                     
                                           success: function(response, opts) {

                                           },
                                           failure: function(response, opts) {
                                              console.log('server-side failure with status code ' + response.status);
                                           }
                                        });   

                                        /********************************/                                                   
                                        

                                    }                                    

                                }
                            })
                        ],


                        itemId: 'ingresoDetalleCancelacionId',
                        //title: 'Cancelaci&oacute;n',
                        store: Ext.create('Ext.data.Store', {
                        autoDestroy: true,
                        fields: ['cuenta', 'tipodocumento', 'documento', 'docpago', 'glosa', 'debe', 'haber','saldo',],
                        data: [
                        {cuenta:0, tipodocumento: 0 , documento: 0, docpago: 0, glosa : '', debe: 0,haber: 0, saldo :0, }
                        ]
                        }),
                        height: 300,
                        /*features: [{
                            ftype: 'summary'
                        }],*/                        
                        columns: [
                        { 
                            text: 'Cuenta',  
                            dataIndex: 'cuenta', 
                            width: 230,
                            editor: {
                                xtype: 'combo',
                                typeAhead: true,
                                displayField : 'nombre',
                                valueField : 'id', 
                                triggerAction: 'all',
                                selectOnFocus: true,
                                store : cuentascontablecancela,
                                editable: false,
                                queryMode: 'local',
                                forceSelection: true,  
                                itemId : 'ccuenta',
                                listConfig: {
                                        width :  300,
                                        minWidth : 300
                                }                                  
                            },
                            // esto se ejecuta después de marcar
                            renderer: function(value,metaData,r) {
                                if(value) {
                                    var record = cuentascontablecancela.findRecord('id', value);
                                    return record ? record.get('nombre'): '';
                                } else return "";
                            }
                        },
                        { 
                            text: 'Documento a cancelar',  
                            dataIndex: 'documento', 
                            width:230,
                            editor: {
                                xtype: 'combo',
                                typeAhead: true,
                                displayField: 'documento',
                                valueField: 'id',
                                triggerAction: 'all',
                                selectOnTab: true,
                                editable: false,
                                queryMode: 'remote',
                                store: cuentasDocumentos,
                                itemId: 'cuentaDocto',
                                listConfig: {
                                        width :  500,
                                        minWidth : 300
                                },
                            },
                              renderer: function(value,metaData,r) {
                                    if(value) {
                                        var record = cuentasDocumentos.findRecord('id', value);
                                        return record ? record.get('documento'): '';
                                    } else return "";
                                }                            
                        },
                        { 
                            text: 'Doc. Pago',  
                            dataIndex: 'docpago', 
                            width: 88,
                            editor: {
                                xtype: 'numberfield', 
                                value : 0,
                                minValue: 0,
                                maxValue: 10000000000
                            }
                         },                        
                        { 
                            text: 'Glosa',  
                            dataIndex: 'glosa', 
                            width: 230,
                            editor: {
                                xtype: 'textfield', 
                                allowBlank: true,
                                value : '',
                            }                            
                        },                        
                        { 
                            text: 'Saldo',
                            dataIndex: 'saldo',   
                            width: 85,
                            xtype: 'numbercolumn', 
                            format: '0,000.'
                        },                                               
                        { 
                            text: 'Debe',  
                            dataIndex: 'debe', 
                            width: 88,
                            editor: {
                                xtype: 'numberfield', 
                                allowBlank: false,
                                value : 0,
                                minValue: 0,
                                maxValue: 10000000000,
                                format: '0,000.'
                            }
                         },
                        { 
                            text: 'Haber',  
                            dataIndex: 'haber', 
                            width: 88,
                            editor: {
                                xtype: 'numberfield', 
                                allowBlank: false,
                                value : 0,
                                minValue: 0,
                                maxValue: 10000000000,
                                format: '0,000.'
                            }
                        }
                        ,
                     
                        /*{ text: 'Valor', dataIndex: 'valor', width: 100, renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")}, editor: {xtype: 'numberfield', allowBlank: false,minValue: 0,maxValue: 10000000000}},
                        { text: 'Cantidad', dataIndex: 'cantidad', width: 100, renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")}, editor: {xtype: 'numberfield', allowBlank: false,minValue: 0,maxValue: 10000000000}},*/
                        {
                            xtype: 'actioncolumn',
                            width: 64,
                            text: 'Eliminar',
                            align: 'center',
                            items: [{
                                icon: gbl_site + 'Infosys_web/resources/images/delete.png',
                                // Use a URL in the icon config
                                tooltip: 'Eliminar',
                                handler: function (grid, rowIndex, colIndex) {
                                    var rec = grid.getStore().getAt(rowIndex);
                                    if(grid.getStore().getCount()==1){
                                        console.log(rec.get('haber'))

                                        rec.set('cuenta',0)
                                        rec.set('tipodocumento',0)
                                        rec.set('documento',0)
                                        rec.set('interes',0)
                                        rec.set('interes_cancelado',0)
                                        rec.set('docpago',0)
                                        rec.set('glosa','')
                                        rec.set('saldo',0)
                                        rec.set('debe',0)
                                        rec.set('haber',0)
                                        me.down('#modificaTasa').setDisabled(false)

                                        var title = me.down('#titlepanel').getValue();
                                        title += ' . | Saldo Acumulado : $ 0.  Inter&eacute;s Acumulado : $ 0';
                                        me.down('#ingresoDetalleCancelacionId').setTitle(title);
                                        me.down('#totalinteres').setValue(0);
                                       //Ext.Msg.alert('Alerta', 'No puede eliminar el ultimo registro.');
                                        me.down('#glosafact').setReadOnly(true)
                                        me.down('#glosafact').setValue('')
                                        me.down('#mensaje_glosafact').setValue('')
                                         me.down('#tipogastoId').setValue('')
                                         me.down('#tipocondpagoId').setValue('')
                                         me.down('#tipogastoId').setReadOnly(true)
                                         me.down('#tipocondpagoId').setReadOnly(true)
                                         me.down('#fechavencId').setValue(new Date())    
                                       //return false;

                                      // agrega_vacia = true;
                                    }else{
                                        grid.getStore().remove(rec);

                                       var stItem = me.down("grid").getStore();
                                        var title = me.down('#titlepanel').getValue();
                                        var monto_saldo = 0;
                                        var monto_cancelado = 0;
                                        var monto_interes_cancelado = 0;

                                        stItem.each(function(r){
                                            dataItems.push(r.data);
                                            monto_saldo += r.data.saldo === undefined || r.data.saldo == '' ? 0 : Math.round(parseFloat(r.data.saldo));
                                            monto_cancelado += r.data.haber;
                                            monto_interes_cancelado += r.data.interes === undefined || r.data.interes == '' ? 0 : Math.round(parseFloat( ( Math.round(parseFloat(r.data.interes)) / monto_saldo  )*Math.round(parseFloat(r.data.haber)) ));

                                        });
                                        title += ' . | Saldo Acumulado : $ ' + monto_cancelado  + '.  Inter&eacute;s Acumulado : $ ' + monto_interes_cancelado;
                                        me.down('#ingresoDetalleCancelacionId').setTitle(title);
                                        me.down('#totalinteres').setValue(monto_interes_cancelado);

                                 

                                        if(monto_interes_cancelado > 0){
                                             me.down('#glosafact').setReadOnly(false)
                                             me.down('#mensaje_glosafact').setValue('&nbsp;&nbsp;Se generar&aacute; factura de glosa correspondiente a los intereses pagados')
                                             me.down('#tipogastoId').setReadOnly(false)
                                             me.down('#tipocondpagoId').setReadOnly(false)                                             

                                        }else{
                                             me.down('#glosafact').setReadOnly(true)
                                             me.down('#glosafact').setValue('')
                                             me.down('#mensaje_glosafact').setValue('')
                                             me.down('#tipogastoId').setValue('')
                                             me.down('#tipocondpagoId').setValue('')
                                             me.down('#tipogastoId').setReadOnly(true)
                                             me.down('#tipocondpagoId').setReadOnly(true)     
                                             me.down('#fechavencId').setValue(new Date())                                            
                                        }
                                       
                                    }


                                }
                            }]
                        }

                        ]
                        }],
                    }
                ];
        
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'bottom',
            id:'buttons',
            ui: 'footer',
            items: ['->', {
                iconCls: 'icon-save',
                text: 'Grabar',
                action: 'cancelacioningresargrabar',
                itemId: 'grabarcancelacion',
            },'-',{
                iconCls: 'icon-reset',
                text: 'Cancelar',
                scope: this,
                handler: this.close
            }]
        }];

        this.callParent(arguments);
    }
});
