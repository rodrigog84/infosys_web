
Ext.define('Infosys_web.model.existenciasclientes', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id'},
        {name: 'id_producto'},
        {name: 'id_cliente'},
        {name: 'nom_producto'},
        {name: 'cantidad_salida'},
        {name: 'cantidad_entrada'},
        {name: 'nom_tipo_movimiento'},
        {name: 'num_movimiento'},
        {name: 'codigo'},
        {name: 'fecha_movimiento',type:'date',dateFormat:"Y-m-d"},
        {name: 'saldo', decimalPrecision:2},
        {name: 'fecha_vencimiento', type:'date',dateFormat:"Y-m-d"},        
       
    ]
});