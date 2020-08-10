
Ext.define('Infosys_web.model.existencias2', {
    extend: 'Ext.data.Model',
    fields: [       
        {name: 'id'},
        {name: 'id_producto'},
        {name: 'id_bodega'},
        {name: 'nom_bodega'},
        {name: 'codigo'},
        {name: 'nom_producto'},
        {name: 'tipo_movimiento'},
        {name: 'nom_tipo_movimiento'},
        {name: 'stock'},
        {name: 'num_movimiento'},
        {name: 'cantidad_entrada', decimalPrecision: 4},
        {name: 'cantidad_salida', decimalPrecision: 4},
        {name: 'fecha_movimiento',type:'date',dateFormat:"Y-m-d"},
        {name: 'fecha_ultimo_movimiento',type:'date',dateFormat:"Y-m-d"},
	{name: 'valor_producto', decimalPrecision: 3},
        {name: 'valor_producto_neto', decimalPrecision: 3},
        {name: 'saldo', decimalPrecision:4},
        {name: 'fecha_vencimiento', type:'date',dateFormat:"Y-m-d"},  
        {name: 'lote'}, 
        {name: 'stock_critico', decimalPrecision:4}, 
        {name: 'p_promedio'}, 
             

            
    ]
});