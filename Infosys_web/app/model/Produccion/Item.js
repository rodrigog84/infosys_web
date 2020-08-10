Ext.define('Infosys_web.model.Produccion.Item', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id'},
        {name: 'id_existencia'},
        {name: 'id_produccion'},
        {name: 'id_producto'},
        {name: 'nom_producto'},
        {name: 'id_bodega'},
        {name: 'codigo'},
        {name: 'lote'},
        {name: 'valor_compra', decimalPrecision:3},  
        {name: 'precio', decimalPrecision:3},        
        {name: 'cantidad', decimalPrecision:4},
        {name: 'cantidad_real', decimalPrecision:4},
        {name: 'valor_produccion', decimalPrecision:3},
        {name: 'porcentaje', decimalPrecision:4},
        {name: 'cantidad_pro', decimalPrecision:4},
        {name: 'valor_produccion', decimalPrecision:3},
        {name: 'porcentaje_pro', decimalPrecision:4},
        {name: 'fecha_vencimiento', type:'date',dateFormat:"Y-m-d"},
        {name: 'saldo', decimalPrecision:3},
    ]
});