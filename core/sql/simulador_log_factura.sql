-- ============================================================
-- Simulador de Intereses — Agregar columnas de factura al log
-- Ejecutar en la base de datos infosys_web
-- ============================================================

ALTER TABLE `simulador_log`
    ADD COLUMN `id_factura_generada`  INT(11)      DEFAULT NULL AFTER `id_usuario`,
    ADD COLUMN `num_factura_generada` VARCHAR(20)  DEFAULT NULL AFTER `id_factura_generada`,
    ADD KEY `idx_factura` (`id_factura_generada`);
