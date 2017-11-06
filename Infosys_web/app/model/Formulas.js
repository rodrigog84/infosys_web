Ext.define('Infosys_web.model.Formulas', {
    extend: 'Ext.data.Model',
    fields: [
       {name: 'id'},
        {name: 'id_cliente'},
        {name: 'nom_cliente'},
        {name: 'rut_cliente'},
        {name: 'id_producto'},
        {name: 'nombre_formula'},
        {name: 'num_formula'},
        {name: 'codigo'},
        {name: 'fecha_formula', type:'date',dateFormat:"Y-m-d"},
        {name: 'cantidad'},
        {name: 'valor', decimalPrecision:2}          
    ]
});