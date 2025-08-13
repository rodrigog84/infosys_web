SELECT 			0 id 
					, 0 AS iddetalle
					, '00000000' as codigo 
					,d.glosa AS nombre 
					,f.tipo_documento 
					,CASE WHEN f.tipo_documento = 102 then d.cantidad*(-1) ELSE d.cantidad END as cantidad 
					,CASE WHEN f.tipo_documento = 102 then d.neto*(-1) ELSE d.neto END AS ventaneta 
					,d.precios as tipo_precio 
					, 'SERVICIOS' as familia 
					,f.num_factura 
					,0 as id_guia
FROM 				(detalle_factura_glosa d) 
JOIN 				factura_clientes f ON d.id_factura = f.id 
WHERE 			1 = 1 
AND 				month(f.fecha_factura) = '06' 
AND 				year(f.fecha_factura) = '2025' 
AND 				f.tipo_documento in (101,120,102,104,106) 
AND 				d.id_guia = 0 
AND 				id_producto = 0 
UNION

SELECT 			0 id 
				, 0 AS iddetalle
				, '00000000' as codigo 
				,'COMISION GANADO' as nombre 
				,f.tipo_documento 
				,0 AS cantidad 
				,CASE WHEN f.tipo_documento = 102 THEN f.comisionganado*(-1) ELSE f.comisionganado END AS ventaneta 
				,0 as tipo_precio 
				, 'SERVICIOS' as familia 
				,f.num_factura 
				,0 as id_guia
FROM 				factura_clientes f 
WHERE 			1 = 1 
AND 				month(f.fecha_factura) = '06' 
AND 				year(f.fecha_factura) = '2025' 
AND 				f.tipo_documento in (101,120,102,104,106) 
AND 				f.comisionganado > 0 

UNION 

SELECT 			0 id 
				, 0 AS iddetalle
				, '00000000' as codigo 
				,'COSTO MAYOR PLAZO' as nombre 
				,f.tipo_documento 
				,0 AS cantidad 
				,CASE WHEN f.tipo_documento = 102 THEN f.costomayorplazo*(-1) ELSE f.costomayorplazo END AS ventaneta 
				,0 as tipo_precio 
				, 'SERVICIOS' as familia 
				,f.num_factura 
				,0 as id_guia
FROM 				factura_clientes f 
WHERE 			1 = 1 
AND 				month(f.fecha_factura) = '06' 
AND 				year(f.fecha_factura) = '2025' 
AND 				f.tipo_documento in (101,120,102,104,106) 
AND 				f.costomayorplazo > 0 

UNION 

SELECT 			0 id 
				, 0 AS iddetalle
				, '00000000' as codigo 
				,'OTROS CARGOS' as nombre 
				,f.tipo_documento 
				,0 AS cantidad 
				,CASE WHEN f.tipo_documento = 102 THEN f.otroscargos*(-1) ELSE f.otroscargos END AS ventaneta 
				,0 as tipo_precio 
				, 'SERVICIOS' as familia 
				,f.num_factura 
				,0 as id_guia
FROM 				factura_clientes f 
WHERE 			1 = 1 
AND 				month(f.fecha_factura) = '06' 
AND 				year(f.fecha_factura) = '2025' 
AND 				f.tipo_documento in (101,120,102,104,106) 
AND 				f.otroscargos > 0 


UNION

SELECT 			distinct p.id 
					, 0 AS iddetalle
					, p.codigo 
					, p.nombre 
					,f.tipo_documento 
					,CASE WHEN f.tipo_documento = 102 then d.cantidad*(-1) ELSE d.cantidad END AS cantidad 
					,CASE WHEN f.tipo_documento = 102 then d.precio*d.cantidad*(-1) ELSE d.neto END AS ventaneta 
					,p.p_ult_compra as tipo_precio 
					,tf.nombre as familia 
					,f.num_factura 
					,0 as id_guia
FROM 				(detalle_factura_cliente d) 
JOIN 				factura_clientes f ON d.id_factura = f.id 
LEFT JOIN 		factura_clientes fg ON f.id = fg.id_factura 
left JOIN 		detalle_factura_glosa g ON f.id = g.id_factura 
AND 				g.id_guia != 0 
JOIN 				productos p ON d.id_producto = p.id 
JOIN 				familias tf ON p.id_familia = tf.id 
WHERE 			1 = 1 
AND 				month(f.fecha_factura) = '06' 
AND 				year(f.fecha_factura) = '2025' 
AND 				f.tipo_documento in (101,120,102,104,106) 
AND 				g.id_factura IS null 
AND 				CASE WHEN fg.guiatraslado = 1 then 0 ELSE 1 END = 1 

UNION all 

SELECT 			distinct p.id 
					, d.id AS iddetalle
					, p.codigo 
					, p.nombre 
					,f.tipo_documento 
					,CASE WHEN f.tipo_documento = 102 then d.cantidad*(-1) ELSE d.cantidad END AS cantidad 
					,CASE WHEN f.tipo_documento = 102 then d.precio*d.cantidad*(-1) ELSE d.neto END AS ventaneta 
					,p.p_ult_compra as tipo_precio 
					,tf.nombre as familia 
					,f.num_factura 
					,g.id_guia
FROM 				(detalle_factura_cliente d) 
INNER JOIN 		detalle_factura_glosa g ON d.id_factura = g.id_guia 
JOIN 				factura_clientes f ON g.id_factura = f.id 
LEFT JOIN 		factura_clientes fg ON f.id = fg.id_factura 
JOIN 				productos p ON d.id_producto = p.id 
JOIN 				familias tf ON p.id_familia = tf.id 
WHERE 			1 = 1 
AND 				month(f.fecha_factura) = '06' 
AND 				year(f.fecha_factura) = '2025' 
AND 				f.tipo_documento in (101,120,102,104,106) 
AND 				CASE WHEN fg.guiatraslado = 1 then 0 ELSE 1 END = 1 

UNION ALL 

SELECT 			p.id 
					, 0 AS iddetalle
					,p.codigo 
					,p.nombre 
					,f.tipo_documento 
					,CASE WHEN f.tipo_documento = 102 THEN d.cantidad*(-1) ELSE d.cantidad END AS cantidad 
					,CASE WHEN f.tipo_documento = 102 THEN d.precios*d.kilos*(-1) ELSE d.neto END AS ventaneta 
					,p.p_ult_compra as tipo_precio 
					,tf.nombre AS familia 
					,f.num_factura 
					,0 as id_guia
FROM 				(detalle_factura_glosa d) 
JOIN 				factura_clientes f ON d.id_factura = f.id 
JOIN 				productos p ON d.id_producto = p.id 
JOIN 				familias tf ON p.id_familia = tf.id 
WHERE 			1 = 1 
AND 				month(f.fecha_factura) = '06' 
AND 				year(f.fecha_factura) = '2025' 
AND 				f.tipo_documento in (101,120,102,104,106) 