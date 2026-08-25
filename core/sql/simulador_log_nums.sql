-- Agregar columna de números de documentos legibles al log del simulador
ALTER TABLE `simulador_log`
    ADD COLUMN `nums_documentos` TEXT DEFAULT NULL AFTER `ids_documentos`;
