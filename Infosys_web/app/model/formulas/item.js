Ext.define('Infosys_web.model.formulas.Item', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id'},
        {name: 'id_formula'},
        {name: 'id_producto'},
        {name: 'id_bodega'},
        {name: 'codigo'},
        {name: 'nombre_formula'},
        {name: 'nombre_producto'},
        {name: 'cantidad', decimalPrecision:4},
        {name: 'porcentaje', decimalPrecision:4},
        {name: 'valor_compra', decimalPrecision:4},
        {name: 'valor_produccion', decimalPrecision:4},
       
    ]
});