<?php

/**
 * LibreDTE
 * Copyright (C) SASCO SpA (https://sasco.cl)
 *
 * Este programa es software libre: usted puede redistribuirlo y/o
 * modificarlo bajo los términos de la Licencia Pública General Affero de GNU
 * publicada por la Fundación para el Software Libre, ya sea la versión
 * 3 de la Licencia, o (a su elección) cualquier versión posterior de la
 * misma.
 *
 * Este programa se distribuye con la esperanza de que sea útil, pero
 * SIN GARANTÍA ALGUNA; ni siquiera la garantía implícita
 * MERCANTIL o de APTITUD PARA UN PROPÓSITO DETERMINADO.
 * Consulte los detalles de la Licencia Pública General Affero de GNU para
 * obtener una información más detallada.
 *
 * Debería haber recibido una copia de la Licencia Pública General Affero de GNU
 * junto a este programa.
 * En caso contrario, consulte <http://www.gnu.org/licenses/agpl.html>.
 */

namespace sasco\LibreDTE;

// Directorio para imágenes (no se asume nada)
define ('K_PATH_IMAGES', '');

/**
 * Clase para generar PDFs
 *
 * Los métodos se copiaron desde la clase \sowerphp\general\View_Helper_PDF
 * disponible en:
 *
 * <https://github.com/SowerPHP/extension-general/blob/master/View/Helper/PDF.php>
 *
 * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
 * @version 2015-09-08
 */
class PDF extends \TCPDF
{

    private $footer; ///< Mensaje a colocar en el footer

    /**
     * Constructor de la clase
     * @param o Orientación
     * @param u Unidad de medida
     * @param s Tipo de hoja
     * @param top Margen extra (al normal) para la parte de arriba del PDF
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-20
     */
    public function __construct($o = 'P', $u = 'mm', $s = 'LETTER', $top = 0)
    {
        parent::__construct($o, $u, $s);
        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP+$top, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER+$top);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER+6);
        $this->SetAuthor('Un proyecto de SASCO SpA - https://sasco.cl');
        $this->SetCreator('LibreDTE - https://libredte.cl');
        $this->setFont('helvetica');
    }

    /**
     * Método para evitar que se renderice el de TCPDF
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2015-09-08
     */
    public function Header()
    {
    }

    /**
     * Método que genera el footer del PDF
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2015-03-20
     */
    public function Footer()
    {
        if (is_array($this->footer) and (!empty($this->footer['left']) or !empty($this->footer['right']))) {
            $style = ['width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => [50, 50, 50]];
            $this->Line(0, $this->getY()-1, 290, $this->getY()-2, $style);
            $this->SetFont('', 'B', 6);
            if (empty($this->papelContinuo)) {
                if (!empty($this->footer['left']))
                    $this->Texto($this->footer['left']);
                if (!empty($this->footer['right']))
                    $this->Texto($this->footer['right'], null, null, 'R');
            } else {
                if (!empty($this->footer['left']))
                    $this->Texto($this->footer['left'], null, null, 'C');
                if (!empty($this->footer['right'])) {
                    $this->Ln();
                    $this->Texto($this->footer['right'], null, null, 'C');
                }
            }
        }
    }

    /**
     * Método que asigna el texto que se deberá usar en el footer
     * @param footer =true se asignará texto por defecto. String al lado izquiero o bien arreglo con índices left y right con sus textos
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2016-03-20
     */
    public function setFooterText($footer = true)
    {
        if ($footer) {
            // asignar valor por defecto
            if ($footer===true) {
                $footer = [
                    'left' => 'Infosys SPA',
                    'right' => !$this->papelContinuo ? 'http://www.info-sys.cl' : '',   
                ];
            }
            // si no es arreglo se convierte en uno
            if (!is_array($footer))
                $footer = ['left'=>$footer];
            // asignar footer
            $this->footer = array_merge(['left'=>'', 'right'=>''], $footer);
        } else {
            $this->footer = null;
        }
    }

    /**
     * Agregar una tabla al PDF removiendo aquellas columnas donde no existen
     * datos en la columna para todas las filas
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2015-09-16
     */
    public function addTableWithoutEmptyCols($titles, $data, $options = [])
    {
        $cols_empty = [];
        foreach ($data as $row) {
            foreach ($row as $col => $value) {
                if (empty($value)) {
                    if (!array_key_exists($col, $cols_empty))
                        $cols_empty[$col] = 0;
                    $cols_empty[$col]++;
                }
            }
        }
        $n_rows = count($data);
        $titles_keys = array_flip(array_keys($titles));
        foreach ($cols_empty as $col => $rows) {
            if ($rows==$n_rows) {
                unset($titles[$col]);
                foreach ($data as &$row) {
                    unset($row[$col]);
                }
                if (isset($options['width']))
                    unset($options['width'][$titles_keys[$col]]);
                if (isset($options['align']))
                    unset($options['align'][$titles_keys[$col]]);
            }
        }
        if (isset($options['width'])) {
            $options['width'] = array_slice($options['width'], 0);
            $key_0 = null;
            $suma = 0;
            foreach ($options['width'] as $key => $val) {
                if ($val===0)
                    $key_0 = $key;
                $suma += $val;
            }
            if ($key_0!==null) {
                $options['width'][$key_0] = 190 - $suma;
            }
        }
        if (isset($options['align']))
            $options['align'] = array_slice($options['align'], 0);
        $this->addTable($titles, $data, $options);
    }

    /**
     * Agregar una tabla generada a través de código HTML al PDF
     * @todo Utilizar las opciones para definir estilo de la tabla HTML
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2015-09-16
     */
    public function addTable($headers, $data, $options = [])
    {
        $w = (isset($options['width']) and is_array($options['width'])) ? $options['width'] : null;
        $a = (isset($options['align']) and is_array($options['align'])) ? $options['align'] : [];
        $buffer = '<table style="border:1px solid #333">';
        // Definir títulos de columnas
        $thead = isset($options['width']) and is_array($options['width']) and count($options['width']) == count($headers);
        if ($thead)
            $buffer .= '<thead>';
        $buffer .= '<tr>';
        $i = 0;
        foreach ($headers as &$col) {
            $width = ($w and isset($w[$i])) ? (';width:'.$w[$i].'mm') : '';
            $align = isset($a[$i]) ? $a[$i] : 'center';
            $buffer .= '<th style="border-right:1px solid #333;border-bottom:1px solid #333;text-align:'.$align.$width.'"><strong>'.strip_tags($col).'</strong></th>';
            $i++;
        }
        $buffer .= '</tr>';
        if ($thead)
            $buffer .= '</thead>';
        // Definir datos de la tabla
        if ($thead)
            $buffer .= '<tbody>';


        $num_filas = count($data);


        foreach ($data as &$row) {
            $buffer .= '<tr>';
            $i = 0;
            foreach ($row as &$col) {
                $width = ($w and isset($w[$i])) ? (';width:'.$w[$i].'mm') : '';
                $align = isset($a[$i]) ? $a[$i] : 'center';
                $buffer .= '<td style="border-right:1px solid #333;text-align:'.$align.$width.'">'.$col.'</td>';
                $i++;
            }
            $buffer .= '</tr>';
        }

        //RELLENAR CON FILAS EN BLANCO (PERSONALIZADO A INFOSYS)
        while($num_filas < 25){
            $buffer .= '<tr>';
            foreach ($headers as &$col) {
                $width = ($w and isset($w[$i])) ? (';width:'.$w[$i].'mm') : '';
                $align = isset($a[$i]) ? $a[$i] : 'center';
                $buffer .= '<td style="border-right:1px solid #333;text-align:'.$align.$width.'">&nbsp;</td>';
            }
            $buffer .= '</tr>';
            $num_filas++;
        }

        if ($thead)
            $buffer .= '</tbody>';
        // Finalizar tabla
        $buffer .= '</table>';
        // generar tabla en HTML
        $this->writeHTML($buffer, true, false, false, false, '');
    }

    /**
     * Agregar texto al PDF, es una variación del método Text que permite
     * definir un ancho al texto. Además recibe menos parámetros para ser
     * más simple (parámetros comunes solamente).
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-09-20
     */
    public function Texto($txt, $x=null, $y=null, $align='', $w=0, $link='', $border=0, $fill=false)
    {
        if ($x==null) $x = $this->GetX();
        if ($y==null) $y = $this->GetY();
        $textrendermode = $this->textrendermode;
        $textstrokewidth = $this->textstrokewidth;
        $this->setTextRenderingMode(0, true, false);
        $this->SetXY($x, $y);
        $this->Cell($w, 0, $txt, $border, 0, $align, $fill, $link);
        // restore previous rendering mode
        $this->textrendermode = $textrendermode;
        $this->textstrokewidth = $textstrokewidth;
    }

    /**
     * Método idéntico a Texto, pero en vez de utilizar Cell utiliza
     * MultiCell. La principal diferencia es que este método no permite
     * agregar un enlace y Texto si.
     * @author Esteban De La Fuente Rubio, DeLaF (esteban[at]delaf.cl)
     * @version 2014-09-20
     */
    public function MultiTexto($txt, $x=null, $y=null, $align='', $w=0, $border=0, $fill=false)
    {
        if ($x==null) $x = $this->GetX();
        if ($y==null) $y = $this->GetY();
        $textrendermode = $this->textrendermode;
        $textstrokewidth = $this->textstrokewidth;
        $this->setTextRenderingMode(0, true, false);
        $this->SetXY($x, $y);
        $this->MultiCell($w, 0, $txt, $border, $align, $fill);
        // restore previous rendering mode
        $this->textrendermode = $textrendermode;
        $this->textstrokewidth = $textstrokewidth;
    }

}
