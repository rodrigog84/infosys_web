Ext.define('Infosys_web.view.ventas.Saldodocfecha' ,{
    extend: 'Ext.form.Panel',
    alias : 'widget.saldodocfecha',
    
    requires: ['Ext.form.Panel','Ext.toolbar.Paging'],
    title : 'Saldo Documentos por Fecha',
    autoHeight: false,

    autoShow: true,
    width: 700,
    height: 200,

    initComponent: function() {
        me = this;
         var meses = Ext.create('Ext.data.Store', {
            fields: ['value', 'nombre'],
            data : [
                {"value":'01', "nombre":"Enero"},
                {"value":'02', "nombre":"Febrero"},
                {"value":'03', "nombre":"Marzo"},
                {"value":'04', "nombre":"Abril"},
                {"value":'05', "nombre":"Mayo"},
                {"value":'06', "nombre":"Junio"},
                {"value":'07', "nombre":"Julio"},
                {"value":'08', "nombre":"Agosto"},
                {"value":'09', "nombre":"Septiembre"},
                {"value":'10', "nombre":"Octubre"},
                {"value":'11', "nombre":"Noviembre"},
                {"value":'12', "nombre":"Diciembre"}
            ]
        });


         var dias = Ext.create('Ext.data.Store', {
            fields: ['value', 'nombre'],
            data : [
                {"value":'01', "nombre":"01"},
                {"value":'02', "nombre":"02"},
                {"value":'03', "nombre":"03"},
                {"value":'04', "nombre":"04"},
                {"value":'05', "nombre":"05"},
                {"value":'06', "nombre":"06"},
                {"value":'07', "nombre":"07"},
                {"value":'08', "nombre":"08"},
                {"value":'09', "nombre":"09"},
                {"value":'10', "nombre":"10"},
                {"value":'11', "nombre":"11"},
                {"value":'12', "nombre":"12"},
                {"value":'13', "nombre":"13"},
                {"value":'14', "nombre":"14"},
                {"value":'15', "nombre":"15"},
                {"value":'16', "nombre":"16"},
                {"value":'17', "nombre":"17"},
                {"value":'18', "nombre":"18"},
                {"value":'19', "nombre":"19"},
                {"value":'20', "nombre":"20"},
                {"value":'21', "nombre":"21"},
                {"value":'22', "nombre":"22"},
                {"value":'23', "nombre":"23"},
                {"value":'24', "nombre":"24"},
                {"value":'25', "nombre":"25"},
                {"value":'26', "nombre":"26"},
                {"value":'27', "nombre":"27"},
                {"value":'28', "nombre":"28"},
                {"value":'29', "nombre":"29"},
                {"value":'30', "nombre":"30"},
                {"value":'31', "nombre":"31"},
            ]
        });


         var annos = Ext.create('Ext.data.Store', {
            fields: ['value', 'nombre'],
            data : [
                {"value":'2022', "nombre":"2022"},
                {"value":'2023', "nombre":"2023"},
                {"value":'2024', "nombre":"2024"},
                {"value":'2025', "nombre":"2025"},
                {"value":'2026', "nombre":"2026"},
                {"value":'2027', "nombre":"2027"},
                {"value":'2028', "nombre":"2028"},
                {"value":'2029', "nombre":"2029"},
                {"value":'2030', "nombre":"2030"}
            ]
        });



         var f = new Date();
         var mes_actual = f.getMonth() + 1;
         if(mes_actual < 10){
            mes_actual = "0" + mes_actual;
         }
         
         var anno_actual = f.getFullYear();
         var day_actual = f.getDate();
         console.log(day_actual)
         if(day_actual < 10){
            day_actual = "0" + day_actual;
         }

         var fec_actual = day_actual+'/'+mes_actual+'/'+anno_actual;

         //console.log(day_actual+'/'+mes_actual+'/'+anno_actual)


         //var mes_actual = '09';
         //var anno_actual = 0;
         var tipoprecio_actual = '';

        this.items = [
            {
                xtype: 'form',
                padding: '5 5 0 5',
                border: false,
                frame: true,
                style: 'background-color: #fff;',
                items: [
                {
                            xtype: 'datefield',
                            name: 'fec_rendicion',
                            itemId : 'fec_rendicion',
                            fieldLabel : 'Fecha Rendici&oacute;n',
                            labelStyle: ' font-weight:bold',
                            value : new Date(),
                            labelWidth: 150,
                            allowBlank : false                        

                    },{
                        xtype: 'toolbar',
                        dock: 'bottom',
                        items: [{
                            xtype: 'button',
                            iconCls : 'icon-pdf',
                            text: 'Exportar PDF',
                            handler: function() {

                                var fec_rendicion = Ext.util.Format.date(me.down('#fec_rendicion').getValue(), 'Ymd');
                              //  console.log(Ext.getCmp('#fec_rendicion').getValue().format('m/d/Y'));
                                window.open(preurl +'adminServicesPdf/reporte_rendicion_caja/' + fec_rendicion)    
                                

                            } 


                        },{
                            xtype: 'button',
                            iconCls: 'icon-delete',
                            action: 'cerrarfactura',
                            text : 'Cerrar'
                        }]
                    },
                    ]

            }

        ];
        
        this.callParent(arguments);
    }
});