
Ext.define('Infosys_web.model.Productos.Item', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id'},
        {name: 'id_existencia'},
        {name: 'nombre'},
        {name: 'id_producto'},
        {name: 'id_descuento'},
        {name: 'codigo'},
        {name: 'p_ult_compra'},
        {name: 'p_may_compra'},
        {name: 'p_venta'},
        {name: 'p_costo'},
        {name: 'nom_uni_medida'},
        {name: 'id_marca'},
        {name: 'u_lote'},
        {name: 'nom_marca'},
        {name: 'id_uni_medida'},
        {name: 'nom_ubi_prod'},
        {name: 'id_ubi_prod'},
        {name: 'p_promedio'},
        {name: 'nom_familia'},
        {name: 'id_familia'},
        {name: 'id_bodega'},
        {name: 'id_agrupacion'},
        {name: 'id_subfamilia'},
        {name: 'foto'},
        {name: 'nom_agrupacion'},
        {name: 'nom_subfamilia'},
        {name: 'stock' ,decimalPrecision:4},
        {name: 'lote'},
        {name: 'fecha_vencimiento', type:'date',dateFormat:"Y-m-d"},
        {name: 'nom_bodega'},
        {name: 'cantidad', decimalPrecision:4},
        {name: 'cantidad_real', decimalPrecision:4},
        {name: 'dcto', decimalPrecision:3},
        {name: 'total'},
        {name: 'iva'},
        {name: 'neto'},
        {name: 'totaliva'},
        {name: 'precio', decimalPrecision:2},
        {name: 'valor_compra', decimalPrecision:2},
        {name: 'clasificacion'},
        {name: 'stock_critico', decimalPrecision:4},
        {name: 'diasvencimiento'},

        
        
        
        
        
        

    ]
});