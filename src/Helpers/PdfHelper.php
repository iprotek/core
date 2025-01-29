<?php
namespace iProtek\Core\Helpers;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Dompdf\Dompdf;

class PdfHelper
{ 
    public static function loadPdf($view, $data, $size="A4", $orientation = "portrait", $saveAsName=""){
        
        $html = $this->view($view, $data)->render();

                
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true); // Enable remote content
        $options->set('isHtml5ParserEnabled', true);
        //$options->set('defaultFont', 'Consolas');
        // Instantiate Dompdf
        $dompdf = new Dompdf($options);

        // Load HTML content
        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        $dompdf->setPaper($size, $orientation);

        // Render the PDF
        $dompdf->render();
        $pdfPath = "";
        if($saveAsName && trim($saveAsName)){
            $pdfPath = storage_path('app/pdf/'.$saveAsName);
            file_put_contents($pdfPath, $dompdf->output());
        }

        return [ "path"=>$pdfPath, "dompdf"=> $dompdf ];
    }
}