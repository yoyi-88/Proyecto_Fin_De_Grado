<?php
// Asegúrate de requerir la librería FPDF original aquí (ajusta la ruta según la tengas)
require_once 'vendor/autoload.php'; 

class pdf_factura extends FPDF
{
    // Cabecera de página (Se repite en todas las páginas)
    function Header()
    {
        // Logo si lo tuvieras: $this->Image('public/images/logo.png',10,8,33);
        $this->SetFont('Arial', 'B', 20);
        // Título Empresa
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'De Mi Casa a la Tuya - Chef Privado'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'CIF: 12345678Z | C/ Gastronomía, 12, 28000 Madrid'), 0, 1, 'C');
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        // Posición a 1,5 cm del final
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Método personalizado para imprimir los datos de la factura
    function titulo($cita)
    {
        $this->SetFont('Arial', 'B', 12);
        
        // Bloques de datos (Izquierda Cliente, Derecha Factura)
        $this->Cell(100, 8, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'DATOS DEL CLIENTE:'), 0, 0);
        $this->Cell(90, 8, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'DETALLES DE FACTURA:'), 0, 1);

        $this->SetFont('Arial', '', 11);
        $this->Cell(100, 6, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Nombre: ' . $cita->cliente_nombre), 0, 0);
        $this->Cell(90, 6, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Factura Nº: FCT-' . date('Y') . '-' . str_pad($cita->id, 4, '0', STR_PAD_LEFT)), 0, 1);
        
        $this->Cell(100, 6, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Email: ' . $cita->cliente_email), 0, 0);
        $this->Cell(90, 6, iconv('UTF-8', 'ISO-8859-1//IGNORE', 'Fecha de Emisión: ' . date('d/m/Y')), 0, 1);
        
        $this->Ln(10);
    }
}
?>