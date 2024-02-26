Ext.define('Infosys_web.controller.Produccion', {
    extend: 'Ext.app.Controller',

    
    models: ['Pedidos',
             'Formulas',
             'pedidos.valida',
             'valida',
             'Produccion',
             'Produccion.Item',
             'Consumo_diario'
             ],

    stores: ['Pedidos',
             'Produccion',
             'PedidosProduccion',
             'PedidosFormula',
             'Valida',
             'ProduccionTermino',
             'Bodegas',
             'consumos',
             'Existencias4',
             'Productosf',
             'Tipo_produccion',
             'clientes.Selector3'
             ],
    
    views: ['Produccion.Principal',
            'Produccion.Produccion',
            'Produccion.BuscarPedidos',
            'Produccion.ValidaStock',
            'Produccion.ProduccionTermino',
            'Produccion.BuscarProductos',
            'Produccion.detalle_stock',
            'Produccion.Exportar',
            'Produccion.EditaProduccionTermino',
            'Produccion.BuscarProductos2',
            'Produccion.detalle_stock2',
            'Produccion.ProduccionFormula',
            'Produccion.BuscarPedidos2'
            ],
   
    refs: [{    
       ref: 'produccionprincipal',
        selector: 'produccionprincipal'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{    
        ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{    
        ref: 'produccioningresar',
        selector: 'produccioningresar'
    },{    
        ref: 'buscarpedidosproduccion',
        selector: 'buscarpedidosproduccion'
    },{    
        ref: 'buscarpedidosproduccion2',
        selector: 'buscarpedidosproduccion2'
    },{    
        ref: 'validastock',
        selector: 'validastock'
    },{    
        ref: 'producciontermino',
        selector: 'producciontermino'
    },{    
        ref: 'buscarproductosconsumoproduccion',
        selector: 'buscarproductosconsumoproduccion'
    },{    
        ref: 'detallestock4',
        selector: 'detallestock4'
    },{    
        ref: 'formularioexportarproduccion',
        selector: 'formularioexportarproduccion'
    },{    
        ref: 'editaproducciontermino',
        selector: 'editaproducciontermino'
    },{    
        ref: 'buscarproductosconsumoproduccion2',
        selector: 'buscarproductosconsumoproduccion2'
    },{    
        ref: 'detallestock5',
        selector: 'detallestock5'
    },{    
        ref: 'produccioningresarformula',
        selector: 'produccioningresarformula'
    }

    ],    
    init: function() {
    	
        this.control({
            'topmenus menuitem[action=mProduccion]': {
                click: this.mProduccion
            },
            'produccionprincipal button[action=cerrarproduccion]': {
                click: this.cerrarproduccion
            },
            'produccionprincipal button[action=terminoproduccion]': {
                click: this.terminoproduccion
            },
            'produccionprincipal button[action=generaproduccion]': {
                click: this.generaproduccion
            },
            'produccionprincipal button[action=generaproduccionformula]': {
                click: this.generaproduccionformula
            },
            'produccionprincipal button[action=buscarpedidoprincipal]': {
                click: this.buscarpedidoprincipal
            },
            'produccioningresar button[action=buscarpedidopro]': {
                click: this.buscarpedidopro
            },
            'produccioningresarformula button[action=buscarpedidopro2]': {
                click: this.buscarpedidopro2
            },
            'buscarpedidosproduccion button[action=buscarclienteproduccion]': {
                click: this.buscarclienteproduccion
            },
            'buscarpedidosproduccion2 button[action=buscarclienteproduccion2]': {
                click: this.buscarclienteproduccion2
            },
            'buscarpedidosproduccion button[action=seleccionarpedidoproduccion]': {
                click: this.seleccionarpedidoproduccion
            },
            'buscarpedidosproduccion2 button[action=seleccionarpedidoproduccion2]': {
                click: this.seleccionarpedidoproduccion2
            },
            'produccioningresar button[action=grabarproduccion]': {
                click: this.grabarproduccion
            },
             'produccioningresarformula button[action=grabarproduccion5]': {
                click: this.grabarproduccion5
            },
            'validastock button[action=Salir]': {
                click: this.Salir
            },  
            'producciontermino button[action=Salir]': {
                click: this.Salir
            },
            'producciontermino button[action=editaritem]': {
                click: this.editaritem
            },
            'producciontermino button[action=agregarItem]': {
                click: this.agregarItem
            },
            'producciontermino button[action=grabarproduccion2]': {
                click: this.grabarproduccion2
            },
            'editaproducciontermino button[action=grabarproduccion3]': {
                click: this.grabarproduccion3
            },
            'produccionprincipal button[action=exportarproduccion]': {
                click: this.exportarproduccion
            },
            'produccionprincipal button[action=exportarexcelproduccion]': {
                click: this.exportarexcelproduccion
            },
            'producciontermino #horaterminoId': {
                change: this.horatermino                
            },
            'producciontermino button[action=buscarproductosconsumopro]': {
                click: this.buscarproductos4
            },
            'editaproducciontermino button[action=buscarproductosconsumopro2]': {
                click: this.buscarproductos5
            },
            'producciontermino #codigoId': {
                specialkey: this.special7
            },
            'editaproducciontermino #codigoId': {
                specialkey: this.special8
            },
            'buscarproductosconsumoproduccion button[action=seleccionarproductosproduccion]': {
                click: this.seleccionarproductos7
            },
            'buscarproductosconsumoproduccion2 button[action=seleccionarproductosproduccion2]': {
                click: this.seleccionarproductos8
            },
            'detallestock4 button[action=seleccionarproductosstock4]': {
                click: this.seleccionarproductosstock
            },
            'detallestock5 button[action=seleccionarproductosstock5]': {
                click: this.seleccionarproductosstock2
            },
            'buscarproductosconsumoproduccion button[action=buscarconsumopro]': {
                click: this.buscarconsumopro
            },
            'buscarproductosconsumoproduccion2 button[action=buscarconsumopro2]': {
                click: this.buscarconsumopro2
            },
            'producciontermino button[action=cancelar]': {
                click: this.cancelar
            },
            'formularioexportarproduccion button[action=exportarformularioproduccionexcel]': {
                click: this.exportarformularioproduccionexcel
            },
            'produccionprincipal button[action=EditarProduccion]': {
                click: this.editarproduccion
            },
            'editaproducciontermino button[action=agregarItem2]': {
                click: this.agregarItem2
            },    
        });
    },

    buscarpedidoprincipal: function() {

        var view = this.getProduccionprincipal(); 
        var tipo = view.down('#tipoSeleccionId').getValue();
        var nombre = view.down('#nombreId').getValue();
        var stItem = this.getProduccionStore();
        stItem.proxy.extraParams = {tipo : tipo,
                                    nombre: nombre};
        stItem.load();      
    },

    buscarproductos5: function(){       
        var viewIngresa = this.getEditaproducciontermino();
        var codigo = viewIngresa.down('#codigoId').getValue();
        var id = viewIngresa.down('#productoId').getValue();
        if(!codigo){
            var st = this.getProductosfStore();
            Ext.create('Infosys_web.view.Produccion.BuscarProductos2').show();
            st.load();
        };
        if(codigo){
            var st = this.getExistencias4Store();
            Ext.Ajax.request({
            url: preurl + 'productos/buscacodigo?codigo='+codigo,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cero = "";
                var bodega = 1;
                if (resp.success == true){                    
                    if(resp.cliente){
                        var cliente = resp.cliente;                        
                        var id = (cliente.id);
                        viewIngresa.down('#productoId').setValue(cliente.id);
                        viewIngresa.down('#precioId').setValue(cliente.p_promedio);
                        viewIngresa.down('#cantidadoriId').setValue(cliente.stock);
                        viewIngresa.down('#stockcriticoId').setValue(cliente.stock_critico);
                        viewIngresa.down('#nombreproductoforId').setValue(cliente.nombre); 
                    }
                        st.proxy.extraParams = {id : id,
                                                bodega : bodega}
                        st.load();
                        if(id){
                        viewIngresa = Ext.create('Infosys_web.view.Produccion.detalle_stock2').show();
                        viewIngresa.down('#stockId').setValue(cliente.stock);
                        viewIngresa.down('#stockcriticoId').setValue(cliente.stock_critico);
                        viewIngresa.down('#pventaId').setValue(cliente.p_promedio);
                        };                    
                }else{
                      var view = Ext.create('Infosys_web.view.productos.Ingresar').show();
                      view.down("#codigoId").setValue(codigo);                      
                }              
            }
        });
        }
    },

    agregarItem2: function() {

        var view = this.getEditaproducciontermino();
        var stItem = this.getProduccionTerminoStore();
        var producto = view.down('#productoId').getValue();
        var nombre = view.down('#nombreproductoforId').getValue();
        var codigo = view.down('#codigoId').getValue();
        var cantidad = view.down('#cantidadoproId').getValue();
        var cantidadpro = view.down('#cantidadproduccalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var stockcritico = view.down('#stockcriticoId').getValue();
        var stock = view.down('#cantidadoriId').getValue();
        var cantidadori = view.down('#cantidadoriId').getValue();
        var fechamov = view.down('#fechadocumId').getValue();
        var fechavenc = view.down('#fechavencimientoId').getValue();
        var lote = view.down('#loteId').getValue();
        var idexistencia = view.down('#idpId').getValue();
        var id = view.down('#corrId').getValue();
        var idbodega = 1;
        var cero="";
        var cero2=0;
        var id = id +1;
        var cantidadpro = cantidadpro + cantidad

        //var stItem = this.getProductosItemsStore();

        if(!fechamov){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Fecha Movimiento');
            return false;            
        }

        if(!fechavenc){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Fecha Vencimient');
            return false;            
        }

        var stock = stock - cantidad;

        if(fechavenc){
           Ext.Ajax.request({
             url: preurl + 'productos/enviarMail',
                  params: {
                      id:1,
                      nombre: nombre,
                      producto: producto,
                      fecha: fechavenc 
                  },
             success: function(response, opts) {             
               
             }
          });              
            //return false;
        };   
       
                 
        if(!producto){            
            Ext.Msg.alert('Alerta', 'Debe Seleccionar un Producto');
            return false;
        };

        if(precio==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Precio Producto');
            return false;
        };

        if(cantidad>cantidadori){
            Ext.Msg.alert('Alerta', 'Cantidad Ingresada de Productos Supera El Stock');
            return false;
        };

        if(!cantidad){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Cantidad.');
            return false;
        };       
       
        Ext.Ajax.request({
            url: preurl + 'facturas/stock',
            params: {
                idbodega: idbodega,
                id: idexistencia,
                cantidad: cantidad,
                producto: producto,
                fechafactura: fechamov
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var stock = resp.saldoext;
                if(stockcritico > 0){
                if(stockcritico > stock ){
                    Ext.Msg.alert('Alerta', 'Producto Con Stock Critico Informar ');        
                    Ext.Ajax.request({
                     url: preurl + 'produccion/enviarMail',
                          params: {
                              id:1,
                              nombre: nombre,
                              producto: producto
                          },
                     success: function(response, opts) {
                     }
                  });              
                    //return false;
                };            
                };                      
            }           
        });   
                
        stItem.add(new Infosys_web.model.Consumo_diario({
            id:id,
            id_existencia: idexistencia,
            id_producto: producto,
            id_bodega : idbodega,
            codigo: codigo,
            fecha_vencimiento: fechavenc,
            nom_producto: nombre,
            valor_compra: precio,
            cantidad: cantidad,
            lote: lote,
        }));

        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#idpId').setValue(cero);
        view.down('#nombreproductoforId').setValue(cero);
        view.down('#cantidadoproId').setValue(cero2);
        view.down('#stockcriticoId').setValue(cero2);
        view.down('#cantidadoriId').setValue(cero2);        
        view.down('#precioId').setValue(cero);
        view.down('#corrId').setValue(id);
        view.down('#cantidadproduccalId').setValue(cantidadpro);
    },

     editarproduccion: function(){

        var view = this.getProduccionprincipal(); 
        var id_bodega = "1";                  
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var view = this.getEditaproducciontermino();
            var idproduccion = row.data.id;
            var stItem = this.getProduccionTerminoStore();
            stItem.proxy.extraParams = {idproduccion : idproduccion};
            stItem.load();
            //var estado = row.data.estado;
            stItem.load;
            Ext.Ajax.request({
            url: preurl +'produccion/estado/?idproduccion=' + row.data.id,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) { 
                    var view = Ext.create('Infosys_web.view.Produccion.EditaProduccionTermino').show();                   
                    var cliente = resp.produccion;
                    view.down('#idId').setValue(cliente.id);
                    view.down('#ticketId').setValue(cliente.num_produccion);
                    view.down('#npedidoId').setValue(cliente.num_pedido); 
                    view.down('#fechadocumId').setValue(cliente.fecha_produccion);
                    view.down('#id_cliente').setValue(cliente.id_cliente);
                    view.down('#rutId').setValue(cliente.rut_cliente);
                    view.down('#nombre_id').setValue(cliente.nom_cliente);
                    view.down('#cantidadId').setValue(cliente.cantidad);
                    view.down('#cantidadproducId').setValue(cliente.cant_real);
                    view.down('#cantidadproduccalId').setValue(cliente.cantidad_prod);
                    view.down('#numLoteId').setValue(cliente.lote); 
                    view.down('#nombreproductoId').setValue(cliente.nom_producto);
                    view.down('#productoproId').setValue(cliente.id_producto);
                    view.down('#fechavencId').setValue(cliente.fecha_vencimiento); 
                    view.down('#horainicioId').setValue(cliente.hora_inicio);    
                    view.down('#horaterminoId').setValue(cliente.hora_termino);                     
                    view.down('#fechainicioId').setValue(cliente.fecha_produccion);  
                    view.down('#encargadoId').setValue(cliente.encargado);  
                    view.down('#bodegaId').setValue(id_bodega);                  
                         
                }else{
                    Ext.Msg.alert('No apto para Corregir');
                    return;
                }

            }
            
        });  
                
            
                

        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
              
    },

    exportarformularioproduccionexcel: function(){

        var jsonCol = new Array()
        var i = 0;
        var grid =this.getProduccionprincipal()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getFormularioexportarproduccion()
        var viewnew =this.getProduccionprincipal()
        var fecha = view.down('#fechaId').getSubmitValue();        
        var fecha2 = view.down('#fecha2Id').getSubmitValue();
        var id_tipom = view.down('#tipoCodigoId').getValue();
        var tipo_documento = view.down('#tipoCodigoId');
        var stCombo = tipo_documento.getStore();
        var record = stCombo.findRecord('id', tipo_documento.getValue()).data;
        var nombremov = (record.nombre);

        if(!id_tipom){
             Ext.Msg.alert('Alerta', 'Debe Seleccionar Tipo de Movimiento');
            return;    
            
        }

        console.log(id_tipom);
                
        if (fecha > fecha2) {
        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };   
        
        window.open(preurl + 'adminServicesExcel/exportarExcelproducciondiario?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2+'&tipomov='+id_tipom+'&nombremov='+nombremov);
        view.close();
    },

    exportarexcelproduccion: function(){

        var viewnew =this.getProduccionprincipal()       
        Ext.create('Infosys_web.view.Produccion.Exportar').show();
    
        
    },
    cancelar: function(){

        var viewIngresa = this.getProducciontermino();
        var view = this.getProduccionprincipal();
        var idbodega = 1;
        var stItem = this.getProduccionTerminoStore();
        
        if(stItem){

         var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'facturas/stock5',
            params: {               
                items: Ext.JSON.encode(dataItems),
                idbodega: idbodega                
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
               
            }
           
        });
        };     

        viewIngresa.close();        
    },

    editaritem: function() {

        var view = this.getProducciontermino();
        var grid  = view.down('#itemsgridId');
        var idbodega = view.down('#bodegaId').getValue();
        var fechafactura = view.down('#fechadocumId').getValue();
        var cantidadproducida = view.down('#cantidadproduccalId').getValue();        
        var cero = "";
        
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_producto = row.data.id_producto;
            var precio = row.data.id_producto;

            Ext.Ajax.request({
            url: preurl + 'facturas/stock2',
            params: {
                idbodega: idbodega,
                id: row.data.id_existencia,
                cantidad: row.data.cantidad_real,
                producto: row.data.id_producto,
                fechafactura: fechafactura
               },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cantidad_real=0;
                 if(resp.cliente){
                    var cliente = resp.cliente;
                    var stock = resp.saldo;

                    console.log(cliente.fecha_vencimiento);

                    if(cliente.fecha_vencimiento === undefined){
                        fecvencimiento = new Date();
                    }else{
                        fecvencimiento = cliente.fecha_vencimiento;
                    }
                    
                    //var cantidadproducida = cantidadproducida - row.data.cantidad_real;                    
                   
                    view.down('#productoId').setValue(row.data.id_producto);
                    view.down('#idpId').setValue(row.data.id_existencia);
                    view.down('#nombreproductoforId').setValue(row.data.nom_producto);
                    view.down('#codigoId').setValue(row.data.codigo);
                    view.down('#precioId').setValue(row.data.valor_compra);
                    view.down('#cantidadoproId').setValue(row.data.cantidad_real);
                    view.down('#cantidadforId').setValue(row.data.cantidad);
                    view.down('#cantidadoriId').setValue(resp.saldo);
                    view.down('#stockTotalId').setValue(resp.saldo);
                    view.down('#loteId').setValue(row.data.lote);
                    view.down('#fechavencimientoId').setValue(fecvencimiento);
                    //view.down('#cantidadproduccalId').setValue(cantidadproducida);
                    view.down('#stockcriticoId').setValue(cliente.stock_critico);
                }                                        
            }           
            });
        grid.getStore().remove(row);
        this.recalcularFinal();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }       
    },

    recalcularFinal: function(){

        var view = this.getProducciontermino();
        var stItem = this.getProduccionTerminoStore();
        var pretotal = 0;
        var total = 0;
        
        stItem.each(function(r){
            pretotal = (pretotal + parseInt(r.data.cantidad_real))
            
        });
        total = pretotal;

        console.log(total);
             
        view.down('#cantidadproduccalId').setValue(total);

    },



    buscarconsumopro: function(){

        var view = this.getBuscarproductosconsumoproduccion();
        var st = this.getConsumosStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : "Nombre"}
        st.load();
        
    },

     buscarconsumopro2: function(){

        var view = this.getBuscarproductosconsumoproduccion2();
        var st = this.getConsumosStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : "Nombre"}
        st.load();
        
    },

    seleccionarproductosstock: function(){

        var view = this.getDetallestock4();
        var viewIngresa = this.getProducciontermino();
        var stockcriticoid = view.down('#stockcriticoId').getValue();
        var stocktotal = view.down('#stockId').getValue();
        var grid  = view.down('grid');        
        if (grid.getSelectionModel().hasSelection()) {          
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id_producto);
            viewIngresa.down('#idpId').setValue(row.data.id);
            viewIngresa.down('#nombreproductoforId').setValue(row.data.nom_producto);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);  
            viewIngresa.down('#cantidadoriId').setValue(row.data.saldo);        
            viewIngresa.down('#precioId').setValue(row.data.p_promedio);
            //console.log(row.data.lote);
            viewIngresa.down('#loteId').setValue(row.data.lote);
            viewIngresa.down('#stockcriticoId').setValue(stockcriticoid);
            viewIngresa.down('#fechavencimientoId').setValue(row.data.fecha_vencimiento);
            viewIngresa.down('#stockTotalId').setValue(row.data.stocktotal);            
            view.close();
           
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    seleccionarproductosstock2: function(){

        var view = this.getDetallestock5();
        var viewIngresa = this.getEditaproducciontermino();
        var stockcriticoid = view.down('#stockcriticoId').getValue();
        var grid  = view.down('grid');        
        if (grid.getSelectionModel().hasSelection()) {          
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id_producto);
            viewIngresa.down('#idpId').setValue(row.data.id);
            viewIngresa.down('#nombreproductoforId').setValue(row.data.nom_producto);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);  
            viewIngresa.down('#cantidadoriId').setValue(row.data.saldo);        
            viewIngresa.down('#precioId').setValue(row.data.valor_producto);
            viewIngresa.down('#loteId').setValue(row.data.lote);
            viewIngresa.down('#stockcriticoId').setValue(stockcriticoid);
            viewIngresa.down('#fechavencimientoId').setValue(row.data.fecha_vencimiento);
            view.close();
           
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

     seleccionarproductos7 : function(){

        var view = this.getBuscarproductosconsumoproduccion();
        var viewIngresa = this.getProducciontermino();
        var bodega = 1;
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id = (row.data.id);
            var st = this.getExistencias4Store();
            st.proxy.extraParams = {id : id,
                                    bodega : bodega}
            st.load();
            viewIngresa = Ext.create('Infosys_web.view.Produccion.detalle_stock').show();
            viewIngresa.down('#stockId').setValue(row.data.stock);
            viewIngresa.down('#stockcriticoId').setValue(row.data.stock_critico);
            viewIngresa.down('#pventaId').setValue(row.data.p_neto);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    seleccionarproductos8 : function(){

        var view = this.getBuscarproductosconsumoproduccion2();
        var viewIngresa = this.getEditaproducciontermino();
        var bodega = 1;
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id = (row.data.id);
            var st = this.getExistencias4Store();
            st.proxy.extraParams = {id : id,
                                    bodega : bodega}
            st.load();
            viewIngresa = Ext.create('Infosys_web.view.Produccion.detalle_stock2').show();
            viewIngresa.down('#stockId').setValue(row.data.stock);
            viewIngresa.down('#stockcriticoId').setValue(row.data.stock_critico);
            viewIngresa.down('#pventaId').setValue(row.data.p_neto);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

     buscarproductos4: function(){       
        var viewIngresa = this.getProducciontermino();
        var codigo = viewIngresa.down('#codigoId').getValue();
        var id = viewIngresa.down('#productoId').getValue();
        if(!codigo){
            var st = this.getProductosfStore();
            Ext.create('Infosys_web.view.Produccion.BuscarProductos').show();
            st.load();
        };
        if(codigo){
            var st = this.getExistencias4Store();
            Ext.Ajax.request({
            url: preurl + 'productos/buscacodigo?codigo='+codigo,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cero = "";
                var bodega = 1;
                if (resp.success == true){                    
                    if(resp.cliente){
                        var cliente = resp.cliente;                        
                        var id = (cliente.id);
                        viewIngresa.down('#productoId').setValue(cliente.id);
                        viewIngresa.down('#precioId').setValue(cliente.p_promedio);
                        viewIngresa.down('#cantidadoriId').setValue(cliente.stock);
                        viewIngresa.down('#stockcriticoId').setValue(cliente.stock_critico);
                        viewIngresa.down('#nombreproductoforId').setValue(cliente.nombre); 
                    }
                        st.proxy.extraParams = {id : id,
                                                bodega : bodega}
                        st.load();
                        if(id){
                        viewIngresa = Ext.create('Infosys_web.view.Produccion.detalle_stock').show();
                        viewIngresa.down('#stockId').setValue(cliente.stock);
                        viewIngresa.down('#stockcriticoId').setValue(cliente.stock_critico);
                        viewIngresa.down('#pventaId').setValue(cliente.p_promedio);
                        };                    
                }else{
                      var view = Ext.create('Infosys_web.view.productos.Ingresar').show();
                      view.down("#codigoId").setValue(codigo);                      
                }              
            }
        });
        }
    },

    special8: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.buscarproductos4()
        }
    },

    horatermino: function(){

        var view = this.getProducciontermino();
        var fechaproduccion = view.down('#fechadocumId').getValue();
        var fechainicio = view.down('#fechainicioId').getValue();
        var horainicio = view.down('#horainicioId').getValue();
        var horatermino = view.down('#horaterminoId').getValue();
        var idproduccion = view.down('#idId').getValue();
        var idproducto = view.down('#productoId').getValue();
        var dias = view.down('#diasvencId').getValue();
        
        var dero="";

        Ext.Ajax.request({
            url: preurl + 'facturas/calculofechas',
            params: {
                dias: dias,
                fechafactura : fechaproduccion
            },
            success: function(response){
               var resp = Ext.JSON.decode(response.responseText);
               var fecha_final= resp.fecha_final;
               view.down("#fechavencId").setValue(fecha_final);
                           
            }
           
        });

                    
        /*if (fechainicio != fechaproduccion ){

            if (horainicio < horatermino){
                view.down('#horaterminoId').setValue(dero);
                Ext.Msg.alert('Alerta', 'Hora no Puede ser Menor que Hora de Termino');
            return;               

            }
            
        };*/

       

        
    },

    exportarproduccion: function(){

        var view = this.getProduccionprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var estado = row.data.estado;
            console.log(estado);
            if (estado == 2){
                window.open(preurl +'produccion/exportPDF2/?idproduccion=' + row.data.id)
            }
            if (estado == 4 ){
                window.open(preurl +'produccion/exportPDF4/?idproduccion=' + row.data.id)
            }
            if (estado == 1 ){
                window.open(preurl +'produccion/exportPDF3/?idproduccion=' + row.data.id)
            }
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
        
    },

    grabarproduccion3: function(){

        var view = this.getEditaproducciontermino();
        var fechaproduccion = view.down('#fechadocumId').getValue();
        var cantidadproduccion = view.down('#cantidadproducId').getValue();
        var cantidadproduccioncal = view.down('#cantidadproduccalId').getValue();
        var idcliente = view.down('#id_cliente').getValue();
        var idproducto = view.down('#productoproId').getValue();
        var numproduccion = view.down('#ticketId').getValue();
        var idproduccion = view.down('#idId').getValue();
        //var idpedido = view.down('#pedidoId').getValue();
        var horatermino = view.down('#horaterminoId').getValue();
        var horainicio = view.down('#horainicioId').getValue();
        var fechavenc = view.down('#fechavencId').getValue();
        var lote = view.down('#numLoteId').getValue();
        var bodega = view.down('#bodegaId').getValue();
        var stItem = this.getProduccionTerminoStore();
        var stProduccion = this.getProduccionStore();
        //view.down("#grabarproduccion3").setDisabled(true);

        if(!cantidadproduccion){
            //view.down("#grabarproduccion3").setDisabled(false);
            Ext.Msg.alert('Debe Ingresar Cantidad de produccion Real');
            return;
        }

        if(!lote){
            //view.down("#grabarproduccion3").setDisabled(false);
             Ext.Msg.alert('Debe Ingresar Lote');
            return;            
        };

        if(!horatermino){
            //view.down("#grabarproduccion3").setDisabled(false);
             Ext.Msg.alert('Debe Ingresar Hora Termino');
            return;            
        };
        if(!bodega){
             //view.down("#grabarproduccion3").setDisabled(false);
             Ext.Msg.alert('Debe Asignar Bodega');
            return;            
        };
        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'produccion/save3',
            params: {
                fechaproduccion: Ext.Date.format(fechaproduccion,'Y-m-d'),
                cantidadproduccion: cantidadproduccion,
                cantidadproduccioncal : cantidadproduccioncal, 
                idproducto: idproducto,
                idbodega: bodega,
                //idpedido: idpedido,
                idcliente: idcliente,
                numproduccion: numproduccion,
                idproduccion: idproduccion,
                lote: lote,
                fechavenc: Ext.Date.format(fechavenc,'Y-m-d'),
                horatermino: Ext.Date.format(horatermino,'H:i'),
                horainicio: Ext.Date.format(horainicio,'H:i'),

                items: Ext.JSON.encode(dataItems),
            },
             success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 var idproduccion= resp.idproduccion;
                 view.close();
                 stProduccion.reload();
                 window.open(preurl + 'produccion/exportPDF2/?idproduccion='+idproduccion);
            }           
        });    
    },


    grabarproduccion2: function(){

        var view = this.getProducciontermino();
        var fechaproduccion = view.down('#fechadocumId').getValue();
        var cantidadproduccion = view.down('#cantidadproducId').getValue();
        var cantidadproduccioncal = view.down('#cantidadproduccalId').getValue();
        var idcliente = view.down('#id_cliente').getValue();
        var idproducto = view.down('#productoproId').getValue();
        var numproduccion = view.down('#ticketId').getValue();
        var idproduccion = view.down('#idId').getValue();
        var idpedido = view.down('#pedidoId').getValue();
        var horatermino = view.down('#horaterminoId').getValue();
        var horainicio = view.down('#horainicioId').getValue();
        var fechavenc = view.down('#fechavencId').getValue();
        var lote = view.down('#numLoteId').getValue();
        var bodega = view.down('#bodegaId').getValue();
        var stItem = this.getProduccionTerminoStore();
        var stProduccion = this.getProduccionStore();
        view.down("#grabarproduccion2").setDisabled(true);

        if(!cantidadproduccion){
            view.down("#grabarproduccion2").setDisabled(false);
            Ext.Msg.alert('Debe Ingresar Cantidad de produccion Real');
            return;
        };

        if(!lote){
            view.down("#grabarproduccion2").setDisabled(false);
             Ext.Msg.alert('Debe Ingresar Lote');
            return;            
        };

        if(!horatermino){
            view.down("#grabarproduccion2").setDisabled(false);
             Ext.Msg.alert('Debe Ingresar Hora Termino');
            return;            
        };
        if(!bodega){
             view.down("#grabarproduccion2").setDisabled(false);
             Ext.Msg.alert('Debe Asignar Bodega');
            return;            
        };
        cantreal=0;
        var dataItems = new Array();
        var permite_guardado = true;
        stItem.each(function(r){
            dataItems.push(r.data)

            cantreal=r.data.cantidad_real
            if(cantreal==0){
                view.down("#grabarproduccion2").setDisabled(false);
                 Ext.Msg.alert('Debe Ingresar Toda la Produccion');
                 permite_guardado = false;
                 return false;
                
            }
        });

        
        if(permite_guardado){

                Ext.Ajax.request({
            url: preurl + 'produccion/save2',
            params: {
                fechaproduccion: Ext.Date.format(fechaproduccion,'Y-m-d'),
                cantidadproduccion: cantidadproduccion,
                cantidadproduccioncal : cantidadproduccioncal, 
                idproducto: idproducto,
                idbodega: bodega,
                idpedido: idpedido,
                idcliente: idcliente,
                numproduccion: numproduccion,
                idproduccion: idproduccion,
                lote: lote,
                fechavenc: Ext.Date.format(fechavenc,'Y-m-d'),
                horatermino: Ext.Date.format(horatermino,'H:i'),
                horainicio: Ext.Date.format(horainicio,'H:i'),

                items: Ext.JSON.encode(dataItems),
            },
            success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 var idproduccion= resp.idproduccion;
                 view.close();
                 stProduccion.reload();
                 window.open(preurl + 'produccion/exportPDF2/?idproduccion='+idproduccion);
            }           
                });


        }
        

            
    },

    agregarItem: function() {

        var view = this.getProducciontermino();
        var stItem = this.getProduccionTerminoStore();
        var producto = view.down('#productoId').getValue();
        var nombre = view.down('#nombreproductoforId').getValue();
        var codigo = view.down('#codigoId').getValue();
        var cantidad = view.down('#cantidadoproId').getValue();
        var cantidadfor = view.down('#cantidadforId').getValue();
        var cantidadpro = view.down('#cantidadproduccalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var stockcritico = view.down('#stockcriticoId').getValue();
        var stocktotal = view.down('#stockTotalId').getValue();
        var stock = view.down('#cantidadoriId').getValue();
        var cantidadori = view.down('#cantidadoriId').getValue();
        var fechamov = view.down('#fechadocumId').getValue();
        var fechavenc = view.down('#fechavencimientoId').getValue();
        //var fechavenc = view.down('#fechavencId').getValue();

        
        var lote = view.down('#loteId').getValue();
        var idexistencia = view.down('#idpId').getValue();
        var id = view.down('#corrId').getValue();
        var idbodega = 1;
        var cero="";
        var stockt=0;
        var cero2=0;
        var id = id +1;
        var cantidadpro = cantidadpro + cantidad

        if(!fechamov){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Fecha Movimiento');
            return false;            
        }

        if(!fechavenc){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Fecha Vencimient');
            return false;            
        }

        var stockt = stocktotal - cantidad;

        if(fechavenc){
           Ext.Ajax.request({
             url: preurl + 'productos/enviarMail',
                  params: {
                      id:1,
                      nombre: nombre,
                      producto: producto,
                      fecha: fechavenc 
                  },
             success: function(response, opts) {             
               
             }
          });              
            //return false;
        };

      
       
                 
        if(!producto){            
            Ext.Msg.alert('Alerta', 'Debe Seleccionar un Producto');
            return false;
        };

        if(precio==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Precio Producto');
            return false;
        };

        if(cantidad>cantidadori){
            Ext.Msg.alert('Alerta', 'Cantidad Ingresada de Productos Supera El Stock');
            return false;
        };

        if(!cantidad){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Cantidad.');
            return false;
        };       
       
        Ext.Ajax.request({
            url: preurl + 'facturas/stock',
            params: {
                idbodega: idbodega,
                id: idexistencia,
                cantidad: cantidad,
                producto: producto,
                fechafactura: fechamov
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var stockt = resp.saldoext;
                if(stockcritico > 0){
                if(stockcritico > stockt ){
                Ext.Msg.alert('Alerta', 'Producto Con Stock Critico Informar ');        
                Ext.Ajax.request({
                url: preurl + 'produccion/enviarMail',
                  params: {
                      id:1,
                      nombre: nombre,
                      producto: producto
                  },
                success: function(response, opts) {             

                }
                });              
                //return false;
                };            
                };                       
            }           
        });

        if(!cantidadfor){
            var cantidadfor = view.down('#cantidadoproId').getValue();            
        }; 
        
        if(!cantidadfor){
            var cantidadfor = view.down('#cantidadoproId').getValue();            
        };
        var cantidadf=1;

        var dataItems = new Array();
        stItem.each(function(r){
        dataItems.push(r.data)
        idver=r.id_producto
        if (idver==producto){
            var cantidadf = 0;            
        }
        });

        if (cantidadf==0){
            var cantidadfor=0;
            
        };
        
         stItem.add(new Infosys_web.model.Consumo_diario({
                id:id,
                id_existencia: idexistencia,
                id_producto: producto,
                id_bodega : idbodega,
                codigo: codigo,
                fecha_vencimiento: fechavenc,
                nom_producto: nombre,
                valor_compra: precio,
                cantidad: cantidadfor,
                cantidad_real: cantidad,
                lote: lote,
            }));             

        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#idpId').setValue(cero);
        view.down('#nombreproductoforId').setValue(cero);
        view.down('#cantidadoproId').setValue(cero2);
        view.down('#stockcriticoId').setValue(cero2);
        view.down('#cantidadoriId').setValue(cero2);        
        view.down('#cantidadforId').setValue(cero2);        
        view.down('#precioId').setValue(cero);
        view.down('#corrId').setValue(id);
        view.down('#cantidadproduccalId').setValue(cantidadpro);
    },

    recalcular: function() {

        var view = this.getProducciontermino();
        var stItem = this.getProduccionTerminoStore();
        var pretotal = 0;
        var total = 0;
        
        stItem.each(function(r){
            pretotal = (pretotal + parseInt(r.data.cantidad_pro))
            console.log(pretotal)
          
        });
        total = pretotal;
             
        view.down('#cantidadproducId').setValue(total);

    },

    editaritem2: function() {
        var view = this.getProducciontermino();
        var cantidadformdet = view.down('#cantidadId').getValue();
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_producto = row.data.id_producto;
            var valorcompra = row.data.valor_compra;
            var cantidad = row.data.cantidad;
            var cantidad_pro = row.data.cantidad_pro;
            var porcentaje = row.data.porcentaje;
            var porcentaje_pro = row.data.porcentaje_pro;
            var cantidaddet = (cantidadformdet - cantidad );
            var nombre = row.data.nom_producto;
            console.log(valorcompra);
            Ext.Ajax.request({
            url: preurl + 'productos/buscarp?nombre='+id_producto,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) { 
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down('#precioId').setValue(valorcompra);
                        view.down('#productoforId').setValue(id_producto);
                        view.down('#nombreproductoforId').setValue(nombre);                        
                        view.down('#codigoId').setValue(cliente.codigo);
                        //view.down('#precioId').setValue(porcentaje);
                        //view.down('#valorporproId').setValue(porcentaje_pro);
                        view.down('#cantidadforId').setValue(cantidad);
                        //view.down('#cantidadoproId').setValue(cantidad_pro); 
                                  
                    }
                    
                }
            }

        });
        grid.getStore().remove(row);
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },
   
    terminoproduccion: function(){
       
        var view = this.getProduccionprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var stItem = this.getProduccionTerminoStore();
            var idproduccion = row.data.id;
            var estado = row.data.estado;
            if (estado=="1"){
                        var stItms = Ext.getStore('ProduccionTermino');
                        stItms.removeAll();
            };
            if(estado=="2"){
                Ext.Msg.alert('Produccion Realizada');
            return;               

            }else{
            stItem.proxy.extraParams = {idproduccion : idproduccion};
            stItem.load();            
            Ext.Ajax.request({
            url: preurl +'produccion/termino/?idproduccion=' + row.data.id,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {                    
                    var cliente = resp.cliente;
                    
                    if (cliente.estado=="2"){
                        Ext.Msg.alert('Produccion Realizada');
                        return;                           
                    }else{ 
                    var view = Ext.create('Infosys_web.view.Produccion.ProduccionTermino').show();                   
                    view.down("#ticketId").setValue(cliente.num_produccion);
                    view.down("#idId").setValue(idproduccion);                    
                    view.down("#npedidoId").setValue(cliente.num_pedido);
                    view.down("#horainicioId").setValue(cliente.hora_inicio);
                    view.down("#fechainicioId").setValue(cliente.fecha_produccion);
                    view.down("#pedidoId").setValue(cliente.id_pedido);
                    view.down("#numLoteId").setValue(cliente.lote);
                    view.down("#rutId").setValue(cliente.rut_cliente);                                       
                    view.down("#id_cliente").setValue(cliente.id_cliente);
                    view.down("#nombre_id").setValue(cliente.nom_cliente);
                    view.down("#nombreformulaId").setValue(cliente.nom_formula);
                    view.down("#cantidadId").setValue(cliente.cantidad);
                    view.down("#formulaId").setValue(cliente.id_formula_pedido);
                    view.down("#productoproId").setValue(cliente.id_producto);
                    view.down("#nombreproductoId").setValue(cliente.nom_producto);
                    view.down("#encargadoId").setValue(cliente.encargado);
                    view.down("#diasvencId").setValue(cliente.dias);
                    
                    };                    
                }else{
                    Ext.Msg.alert('Correlativo no Existe');
                    return;
                }

            }
            
        });
        };

        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

        var stProduccion = this.getProduccionStore();
        stProduccion.load();
        
    },

    Salir : function(){
        var view = this.getValidastock();
        var viewnew = this.getProduccioningresar();
        view.close();
        viewnew.close();        
    },

    grabarproduccion: function(){

        var view = this.getProduccioningresar();
        var numproduccion = view.down('#ticketId').getValue();
        var fechaproduccion = view.down('#fechadocumId').getValue();
        var idpedido = view.down('#pedidoId').getValue();
        var numpedido = view.down('#npedidoId').getValue();
        var idcliente = view.down('#id_cliente').getValue();
        //var idformula = view.down('#formulaId').getValue();
        //var nombreformula = view.down('#nombreformulaId').getValue();
        var cantidadproduccion = view.down('#cantidadId').getValue();
        var lote = view.down('#numLoteId').getValue();
        var nombreproducto = view.down('#nombreproductoId').getValue();
        var codigoproducto = view.down('#codigoId').getValue();
        var idproducto = view.down('#productoId').getValue();
        var horainicio = view.down('#horainicioId').getValue();
        var encargado = view.down('#encargadoId').getValue();
        //var stItem = this.getPedidosFormulaStore();
        //var stProduccion = this.getProduccionStore();
        view.down("#grabarproduccion").setDisabled(true);

        if(!encargado){
            view.down("#grabarproduccion").setDisabled(false);
             Ext.Msg.alert('Debe Asignar Encargado');
            return; 
            
        }

        /*if(!lote){
             Ext.Msg.alert('Debe Asignar Lote');
            return; 
            
        }*/

        if(!numpedido){
            view.down("#grabarproduccion").setDisabled(false);
             Ext.Msg.alert('Debe Asignar Pedido');
            return;            
        }

        /*var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });*/

        Ext.Ajax.request({
            url: preurl + 'produccion/save',
            params: {
                numproduccion: numproduccion,
                fechaproduccion: Ext.Date.format(fechaproduccion,'Y-m-d'),
                idpedido: idpedido,
                numpedido: numpedido, 
                numproduccion: numproduccion,           
                idcliente: idcliente,
                //idformula: idformula,
                //nombreformula: nombreformula,
                cantidadproduccion: cantidadproduccion,
                lote: lote,
                codigoproducto: codigoproducto,
                nombreproducto: nombreproducto,
                idproducto: idproducto,
                horainicio: Ext.Date.format(horainicio,'H:i'),
                encargado: encargado,                
                //items: Ext.JSON.encode(dataItems),
            },
             success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 var idproduccion= resp.idproduccion;
                 view.close();
                 //stProduccion.load();
                 window.open(preurl + 'produccion/exportPDF3/?idproduccion='+idproduccion);
            }
           
        });
        
               
    },

    grabarproduccion5: function(){

        var view = this.getProduccioningresarformula();
        var fechaproduccion = view.down('#fechadocumId').getValue();
        var cantidadproduccion = view.down('#cantidadId').getValue();
        var cantidadproduccioncal = view.down('#cantidadPROId').getValue();
        var idcliente = view.down('#id_cliente').getValue();
        var idproducto = view.down('#productoId').getValue();
        var numproduccion = view.down('#ticketId').getValue();
        var numpedido = view.down('#npedidoId').getValue();
        var idformula = view.down('#formulaId').getValue();
        var nombreformula = view.down('#nombreformulaId').getValue();
        var codigoproducto = view.down('#codigoId').getValue();
        var nombreproducto = view.down('#nombreproductoId').getValue();
        //var idproduccion = view.down('#idId').getValue();
        var idpedido = view.down('#pedidoId').getValue();
        //var horatermino = view.down('#horaterminoId').getValue();
        var horainicio = view.down('#horainicioId').getValue();
        //var fechavenc = view.down('#fechavencId').getValue();
        var lote = view.down('#numLoteId').getValue();
        var bodega = 1;
        var encargado = view.down('#encargadoId').getValue();
        
        var stItem = this.getPedidosFormulaStore();
        var stProduccion = this.getProduccionStore();
       
        view.down("#grabarproduccion5").setDisabled(true);

        if(!encargado){
            view.down("#grabarproduccion5").setDisabled(false);
             Ext.Msg.alert('Debe Asignar Encargado');
            return; 
            
        }

        /*if(!lote){
            view.down("#grabarproduccion5").setDisabled(false);
             Ext.Msg.alert('Debe Asignar Lote');
            return; 
            
        }*/

        if(!numpedido){
            view.down("#grabarproduccion5").setDisabled(false);
             Ext.Msg.alert('Debe Asignar Pedido');
            return;            
        }

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'produccion/save4',
            params: {
                numproduccion: numproduccion,
                fechaproduccion: Ext.Date.format(fechaproduccion,'Y-m-d'),
                idpedido: idpedido,
                numpedido: numpedido, 
                numproduccion: numproduccion,           
                idcliente: idcliente,
                idformula: idformula,
                nombreformula: nombreformula,
                cantidadproduccion: cantidadproduccion,
                lote: lote,
                codigoproducto: codigoproducto,
                nombreproducto: nombreproducto,
                idproducto: idproducto,
                horainicio: Ext.Date.format(horainicio,'H:i'),
                encargado: encargado,                
                items: Ext.JSON.encode(dataItems),
            },
             success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 var idproduccion= resp.idproduccion;
                 view.close();
                 stProduccion.load();
                 window.open(preurl + 'produccion/exportPDF4/?idproduccion='+idproduccion);
            }
           
        });        
               
    },

    seleccionarpedidoproduccion: function(){
        
        var view = this.getBuscarpedidosproduccion();
        var viewIngresa = this.getProduccioningresar();
        var grid  = view.down('grid');
        var validar="SI";
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_pedido = row.data.id;
            viewIngresa.down('#pedidoId').setValue(row.data.id);
            viewIngresa.down('#npedidoId').setValue(row.data.num_pedido);
            viewIngresa.down('#rutId').setValue(row.data.rut_cliente);
            viewIngresa.down('#id_cliente').setValue(row.data.id_cliente);
            viewIngresa.down('#nombre_id').setValue(row.data.nombre_cliente);
            //viewIngresa.down('#nombreformulaId').setValue(row.data.nom_formula);
            //viewIngresa.down('#formulaId').setValue(row.data.id_formula);
            viewIngresa.down('#cantidadId').setValue(row.data.cantidad);
            viewIngresa.down('#nombreproductoId').setValue(row.data.nom_producto);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);
            viewIngresa.down('#productoId').setValue(row.data.id_producto);
            view.close();
            /*var st = this.getPedidosFormulaStore()
            st.proxy.extraParams = {pedido : id_pedido}
            st.load();
            this.validaStock(); */      

        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    seleccionarpedidoproduccion2: function(){
        
        var view = this.getBuscarpedidosproduccion2();
        var viewIngresa = this.getProduccioningresarformula();
        var grid  = view.down('grid');
        var validar="SI";
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_pedido = row.data.id;
            viewIngresa.down('#pedidoId').setValue(row.data.id);
            viewIngresa.down('#npedidoId').setValue(row.data.num_pedido);
            viewIngresa.down('#rutId').setValue(row.data.rut_cliente);
            viewIngresa.down('#id_cliente').setValue(row.data.id_cliente);
            viewIngresa.down('#nombre_id').setValue(row.data.nombre_cliente);
            viewIngresa.down('#nombreformulaId').setValue(row.data.nom_formula);
            viewIngresa.down('#formulaId').setValue(row.data.id_formula);
            viewIngresa.down('#cantidadId').setValue(row.data.cantidad);
            viewIngresa.down('#nombreproductoId').setValue(row.data.nom_producto);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);
            viewIngresa.down('#productoId').setValue(row.data.id_producto);
            view.close();
            var st = this.getPedidosFormulaStore()
            st.proxy.extraParams = {pedido : id_pedido}
            st.load();
            //this.validaStock();   

        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    validaStock: function(){

        var view = this.getProduccioningresar();
        var idpedido = view.down('#pedidoId').getValue();
        var sta = this.getValidaStore();
        sta.proxy.extraParams = {pedido : idpedido};
        sta.load();  
        Ext.Ajax.request({
        url: preurl + 'pedidos/validaformula?pedido='+idpedido,
        params: {
            id: 1
        },
        success: function(response){
            var resp = Ext.JSON.decode(response.responseText);
            if (resp.success == true) {
                Ext.create('Infosys_web.view.Produccion.ValidaStock').show();
                                             
            };
            }            
        });
       
    },

    buscarpedidopro: function(){

        var st = this.getPedidosProduccionStore();
        st.load();                
        Ext.create('Infosys_web.view.Produccion.BuscarPedidos').show();            
      
        
    },

    buscarpedidopro2: function(){

        var st = this.getPedidosProduccionStore();
        st.load();                
        Ext.create('Infosys_web.view.Produccion.BuscarPedidos2').show();            
      
        
    },

    generaproduccion: function(){

         var viewIngresa = this.getProduccionprincipal();
         var nombre = "23";    
         Ext.Ajax.request({

            url: preurl + 'correlativos/genera?valida='+nombre,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);

                if (resp.success == true) {
                    var view = Ext.create('Infosys_web.view.Produccion.Produccion').show();                   
                    var cliente = resp.cliente;
                    var correlanue = cliente.correlativo;
                    correlanue = (parseInt(correlanue)+1);
                    var correlanue = correlanue;
                    view.down("#ticketId").setValue(correlanue);
                }else{
                    Ext.Msg.alert('Correlativo YA Existe');
                    return;
                }
            }            
        });       
    },

     generaproduccionformula: function(){

         var viewIngresa = this.getProduccionprincipal();
         var nombre = "23";    
         Ext.Ajax.request({

            url: preurl + 'correlativos/genera?valida='+nombre,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);

                if (resp.success == true) {
                    var view = Ext.create('Infosys_web.view.Produccion.ProduccionFormula').show();                   
                    var cliente = resp.cliente;
                    var correlanue = cliente.correlativo;
                    correlanue = (parseInt(correlanue)+1);
                    var correlanue = correlanue;
                    view.down("#ticketId").setValue(correlanue);
                }else{
                    Ext.Msg.alert('Correlativo YA Existe');
                    return;
                }
            }            
        });
        
         
       
    },
    
    mProduccion: function(){       
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'produccionprincipal'});
    },

    
    cerrarproduccion: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
     
    },
  
});










