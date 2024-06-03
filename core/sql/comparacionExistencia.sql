
SELECT 	p.id
			,p.codigo
			,p.nombre
			,p.stock_tabla_productos
			,de.cantidad_entrada
			,de.cantidad_salida
			,de.saldo_detalle_existencias
			,e.stock AS stock_tabla_existencia
			,CASE WHEN  p.stock_tabla_productos = de.saldo_detalle_existencias THEN 'SI' ELSE 'NO' END AS igual_stock
FROM 		(
			SELECT 	p.id
						,p.codigo
						,p.nombre
						,p.stock AS stock_tabla_productos
			FROM 		productos p
			WHERE 	p.codigo LIKE '302%'
			)p
LEFT JOIN (
			select 	e.id_producto
						, SUM(cantidad_entrada_tarjeta) AS cantidad_entrada
						, SUM(cantidad_salida) AS cantidad_salida
						, (SUM(cantidad_entrada_tarjeta)  - SUM(cantidad_salida)) AS saldo_detalle_existencias
			FROM 		existencia_detalle e
			INNER JOIN productos p ON e.id_producto = p.id
			WHERE 	p.codigo LIKE '302%'
			GROUP BY e.id_producto
			)de ON p.id = de.id_producto
LEFT JOIN existencia e ON p.id = e.id_producto
#WHERE p.stock_tabla_productos != e.stock 
#WHERE p.stock_tabla_productos = de.saldo_detalle_existencias