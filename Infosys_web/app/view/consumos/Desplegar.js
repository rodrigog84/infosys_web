Ext.define('Infosys_web.view.consumos.Desplegar', {
    extend: 'Ext.window.Window',
    alias : 'widget.consumodiariodesplegar',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],

    title : 'Movimiento Diario Consumo Produccion',
    autoShow: true,
    width: 1020,
    modal: true,
    iconCls: 'icon-sheet',
    bodyPadding: 7,
    initComponent: function() {
        var me = this;
        this.items = [
            {
                xtype: 'form',
                padding: '5 5 0 5',
                labelWidth: 150,
                
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
                        itemId : 'idId',
                        hidden:true
                    },{
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        labelWidth: 150,
                        items: [{
                            xtype: 'datefield',
                            fieldLabel: 'Fecha',
                            width: 220,
                            name: 'fecha',
                            itemId: 'fechaId',
                            readOnly: true
                        },{
                            xtype: 'displayfield',
                            width: 342                                          
                        },{
                            xtype: 'numberfield',
                            fieldLabel: 'Numero',
                            width: 180,
                            name: 'numero',
                            itemId: 'numeroId',
                            readOnly: true
                            
                        }]
                    },{
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        labelWidth: 150,
                        items: [{
                        xtype: 'combo',
                        itemId: 'tipoMovimientoId',
                        fieldLabel: 'Tipo',
                        name: 'id_tipom',
                        store: 'tipo_movimientos.Selector',
                        queryMode: 'local',
                        forceSelection: true,
                        displayField: 'nombre',
                        valueField: 'id',
                        listConfig: {
                            minWidth: 280
                        },
                        width: 260
                        },{
                        xtype: 'splitter'
                        },{
                        xtype: 'combo',
                        itemId: 'tipoCodigoId',
                        fieldLabel: 'Movimiento',
                        name: 'id_tipomd',
                        store: 'Tipo_movimiento_inventario',
                        queryMode: 'local',
                        forceSelection: true,
                        valueField: 'id',
                        displayField: 'nombre',
                        listConfig: {
                            minWidth: 320
                        },
                        width: 300
                        }, {xtype: 'splitter'},
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Rut',
                            width: 272,
                            name: 'rut',
                            itemId: 'numerorutId'
                        }]
                    },{
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        items: [{
                            xtype: 'combo',
                            itemId: 'tipobodegaId',
                            fieldLabel: 'Bodega Entrada',
                            forceSelection : true,
                            flex: 1,
                            editable : false,
                            name : 'id_bodegaent',
                            valueField : 'id',
                            displayField : 'nombre',
                            emptyText : "Seleccione",
                            store : 'Bodegas'
                        },
                          {xtype: 'splitter'},
                        {
                            xtype: 'combo',
                            itemId: 'tipobodega2Id',
                            fieldLabel: 'Bodega Salida',
                            forceSelection : true,
                            flex: 1,
                            editable : false,
                            name : 'id_bodegasal',
                            valueField : 'id',
                            displayField : 'nombre',
                            emptyText : "Seleccione",
                            store : 'Bodegas'
                        }]
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Detalle',
                        labelWidth: 16,
                        width:'100%',
                        name: 'detalle',
                        itemId: 'detalleId'
                    }

                ]
            }, 
                    {
                        xtype: 'fieldset',
                        title: 'Movimiento Diario',
                        items: [{
                        xtype: 'grid',
                        tbar: [],
                        selModel: {
                        selType: 'cellmodel'
                        },                       
                        itemId: 'ingresomovimientosId',
                        title: 'Consumos Diarios',
                        store: 'consumos_movimientos',
                        height: 300,
                        columns: [
                        { text: 'Codigo',  dataIndex: 'codigo', width: 100 },
                        { text: 'Producto',  dataIndex: 'nom_producto', width: 350 },
                        { text: 'Valor', dataIndex: 'valor', readOnly: true , width: 100, renderer: function(valor){return Ext.util.Format.number((valor),"0,000.00")}, editor: {xtype: 'numberfield', allowBlank: false,minValue: 0,maxValue: 10000000000}},
                        { text: 'Cantidad', dataIndex: 'cantidad', readOnly: true, width: 100, renderer: function(valor){return Ext.util.Format.number((valor),"000,000.00")}},
                        { text: 'Fecha Venc.',  dataIndex: 'fecha', width: 120, renderer:Ext.util.Format.dateRenderer('d/m/Y') },
                        {
                            xtype: 'actioncolumn',
                            width: 80,
                            text: 'Eliminar',
                            align: 'center',
                            hidden: true,
                            items: [{
                                icon: gbl_site + 'Infosys_web/resources/images/delete.png',
                                // Use a URL in the icon config
                                tooltip: 'Eliminar',
                                handler: function (grid, rowIndex, colIndex) {
                                    var rec = grid.getStore().getAt(rowIndex);
                                    if(grid.getStore().getCount()==1){
                                       Ext.Msg.alert('Alerta', 'No puede eliminar el ultimo registro.');

                                       return false; 
                                    }
                                    grid.getStore().remove(rec);
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
            items: [{
                iconCls: 'icon-exel',
                text: 'Excel',
                action: 'envioexcel'
            },'->', {
                iconCls: 'icon-pdf',
                text: 'Imprimir',
                action: 'imprimirdetalle'
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
