
Ext.define('Infosys_web.model.Produccion.Item', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id'},
        {name: 'id_produccion'},
        {name: 'id_producto'},
        {name: 'nom_producto'},
        {name: 'id_bodega'},
        {name: 'valor_compra', decimalPrecision:2},        
        {name: 'cantidad', decimalPrecision:2},
        {name: 'valor_produccion', decimalPrecision:2},
        {name: 'porcentaje', decimalPrecision:2},
        {name: 'cantidad_pro', decimalPrecision:2},
        {name: 'valor_produccion', decimalPrecision:2},
        {name: 'porcentaje_pro', decimalPrecision:2}
    ]
});