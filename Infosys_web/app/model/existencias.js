
Ext.define('Infosys_web.model.existencias', {
    extend: 'Ext.data.Model',
    fields: [
         {name: 'id'},
        {name: 'id_producto'},
        {name: 'nom_producto'},
        {name: 'stock', decimalPrecision: 3},
        {name: 'id_bodega'},
        {name: 'nom_bodega'},
        {name: 'nom_tipo_movimiento'},
        {name: 'p_costo'},        
        {name: 'p_venta'}, 
        {name: 'fecha_ultimo_movimiento',type:'date',dateFormat:"Y-m-d"},
       
    ]
});