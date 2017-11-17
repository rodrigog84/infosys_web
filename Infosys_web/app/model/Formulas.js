Ext.define('Infosys_web.model.Formulas', {
    extend: 'Ext.data.Model',
    fields: [
       {name: 'id'},
        {name: 'id_cliente'},
        {name: 'nom_cliente'},
        {name: 'rut_cliente'},
        {name: 'id_producto'},
        {name: 'nombre_formula'},
        {name: 'nombre'},
        {name: 'nombre_producto'},
        {name: 'num_formula'},
        {name: 'codigo'},
        {name: 'fecha_formula', type:'date',dateFormat:"Y-m-d"},
        {name: 'cantidad'},
        {name: 'valor', decimalPrecision:2},
        {name: 'valor_compra', decimalPrecision:2},
        {name: 'valor_produccion', decimalPrecision:2},
        {name: 'porcentaje', decimalPrecision:2}        
    ]
});