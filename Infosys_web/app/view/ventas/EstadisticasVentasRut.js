Ext.define('Infosys_web.view.ventas.EstadisticasVentasRut' ,{
    extend: 'Ext.form.Panel',
    alias : 'widget.estadisticasventasrut',
    
    requires: ['Ext.form.Panel','Ext.toolbar.Paging'],
    title : 'Estad&iacute;sticas de Ventas Mensuales por RUT',
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


         var annos = Ext.create('Ext.data.Store', {
            fields: ['anno'],
            proxy: {
              type: 'ajax',
                url : preurl +'facturas/get_annos',
                reader: {
                    type: 'json',
                    root: 'data'
                }
            },
            autoLoad: true
        }); 


         var tipoprecio = Ext.create('Ext.data.Store', {
            fields: ['value', 'nombre'],
            data : [
                {"value":'p_ult_compra', "nombre":"Precio Ultima Compra"},
                {"value":'p_may_compra', "nombre":"Precio Mayor Comprado"},
                {"value":'p_promedio', "nombre":"Precion Promedio Ponderado"}
            ]
        });

         var mes_actual = 0;
         var anno_actual = 0;
         var tipoprecio_actual = '';
         var rut_actual = '';


        var estadisticasVentasRut = Ext.create('Ext.data.Store', {
            fields: ['num','codigo','nombre','unidades', 'ventaneta' , 'costo', 'margen' , 'porcmargen'],
            pageSize: 10,
            autoLoad: true,
            proxy: {
              type: 'ajax',
                url : preurl +'reportes/reporte_estadisticas_ventas_rut',
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
                border: false,
                frame: true,
                style: 'background-color: #fff;',
                items: [
                  {
                            xtype: 'textfield',
                            width: 500,
                            fieldLabel: 'RUT Cliente',
                            labelStyle: ' font-weight:bold',
                            labelWidth: 200,
                            emptyText : 'Ej: 12345678-9',
                            itemId : 'rut' ,
                            name : 'rut' ,
                            allowBlank : false,

                    },{
                            xtype: 'combobox',
                            width: 500,
                            store : meses,
                            fieldLabel: 'Mes',
                            labelStyle: ' font-weight:bold',
                            labelWidth: 200,
                            emptyText : 'Seleccionar',
                            editable: false,
                            itemId : 'mes' ,
                            name : 'mes' ,
                            forceSelection: true, 
                            allowBlank : false,
                            displayField : 'nombre',
                            valueField : 'value',

                    },{
                            xtype: 'combobox',
                            width: 500,
                            store : annos,
                            fieldLabel: 'A&ntilde;o',
                            labelStyle: ' font-weight:bold',
                            labelWidth: 200,
                            emptyText : 'Seleccionar',
                            editable: false,
                            itemId : 'anno' ,
                            name : 'anno' ,
                            forceSelection: true, 
                            allowBlank : false,
                            displayField : 'anno',
                            valueField : 'anno',

                    },{
                            xtype: 'combobox',
                            width: 500,
                            store : tipoprecio,
                            fieldLabel: 'Tipo Precio',
                            labelStyle: ' font-weight:bold',
                            labelWidth: 200,
                            emptyText : 'Seleccionar',
                            editable: false,
                            itemId : 'tipoprecio' ,
                            name : 'tipoprecio' ,
                            forceSelection: true, 
                            allowBlank : false,
                            displayField : 'nombre',
                            valueField : 'value',

                    },{
                        xtype: 'toolbar',
                        dock: 'bottom',
                        items: [{
                            iconCls: 'icon-sheet',
                            text: 'Generar Reporte Mensual',
                             handler: function() {

                                var form = this.up('form').getForm();
                                if(form.isValid()){
                                    var rut = me.down('#rut').getValue();
                                    var mes = me.down('#mes').getValue();
                                    var anno = me.down('#anno').getValue();
                                    var tipoprecio = me.down('#tipoprecio').getValue();
                                    estadisticasVentasRut.proxy.extraParams = {
                                                                        rut : rut,
                                                                        mes : mes,
                                                                        anno : anno,
                                                                        tipoprecio : tipoprecio}
                                    estadisticasVentasRut.load();

                                    rut_actual = rut;
                                    mes_actual = mes;
                                    anno_actual = anno;
                                    tipoprecio_actual = tipoprecio;

                                }


                            }                            
                        },{
                            xtype: 'button',
                            iconCls : 'icon-pdf',
                            text: 'Exportar PDF',
                            handler: function() {
                                if(rut_actual != '' && mes_actual != 0 && anno_actual != 0  && tipoprecio_actual !=''){
                                    window.open(preurl +'adminServicesPdf/reporte_estadisticas_ventas_rut/' + encodeURIComponent(rut_actual) + '/' + mes_actual + '/' + anno_actual + '/' + tipoprecio_actual)    
                                }
                                

                            } 


                        },{                
                            xtype: 'button',
                            iconCls : 'icon-exel',
                            text: 'Exportar EXCEL',
                            handler: function() {
                                if(rut_actual != '' && mes_actual != 0 && anno_actual != 0 && tipoprecio_actual !=''){
                                    window.open(preurl +'adminServicesExcel/reporte_estadisticas_ventas_rut/' + encodeURIComponent(rut_actual) + '/' + mes_actual + '/' + anno_actual + '/' + tipoprecio_actual)    
                                }
                                

                            }
                        },{
                            xtype: 'button',
                            iconCls: 'icon-delete',
                            action: 'cerrarfactura',
                            text : 'Cerrar'
                        }]
                    },
                    ]

            },{
                xtype: 'form',
                padding: '5 5 0 5',
                border: true,
                frame: false,
                style: 'background-color: #fff;',
                items: [
                {


                            xtype: 'grid',
                            itemId: 'itemsgridId',
                            store : estadisticasVentasRut,
                            labelWidth: 50,
                            title: 'Estad&iacute;sticas de Ventas por RUT',
                            height: 300,
                            columns: [
                                { text: '#',  dataIndex: 'num', flex: 1},
                                { text: 'Cod. Producto',  dataIndex: 'codigo', flex: 1},
                                { text: 'Descripci&oacute;n Producto',  dataIndex: 'nombre', flex: 1  },
                                { text: 'Unidades',  dataIndex: 'unidades', flex: 1, align: 'left'},
                                { text: 'Venta Neta',  dataIndex: 'ventaneta', flex: 1 },
                                { text: 'Costo Venta',  dataIndex: 'costo', flex: 1 },
                                { text: 'Margen Neto',  dataIndex: 'margen', flex: 1},
                                { text: '% Margen',  dataIndex: 'porcmargen', flex: 1},
                                ]

                }    
                ]    
               },

        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: estadisticasVentasRut,
            displayInfo: true
        }

        ];
        
        this.callParent(arguments);
    }
});
