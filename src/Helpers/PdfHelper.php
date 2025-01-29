<?php
namespace iProtek\Core\Helpers;

use DB; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

class PdfHelper
{ 
    public static function loadPdf($view, $data, $size="A4", $orientation = "portrait", $saveAsName=""){
        
        $html = View::make($view, $data)->render();

                
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true); // Enable remote content
        $options->set('isHtml5ParserEnabled', true);
        //$options->set('defaultFont', 'Consolas');
        // Instantiate Dompdf
        $dompdf = new Dompdf($options);

        // Load HTML content
        $dompdf->loadHtml($html);

        // (Optional) Set paper size and orientation
        if(is_array($size))
            $dompdf->setPaper([0,0,500, 700]);
        else
            $dompdf->setPaper($size, $orientation);

        // Render the PDF
        $dompdf->render();
        $pdfPath = "";
        if($saveAsName && trim($saveAsName)){
            Storage::disk('local')->put("pdf/".$saveAsName, $dompdf->output());
            $pdfPath = Storage::disk('local')->path("pdf/".$saveAsName);
            //$pdfPath = storage_path('app/pdf/'.$saveAsName);
            //file_put_contents($pdfPath, $dompdf->output());
        }

        return [ "path"=>$pdfPath, "dompdf"=> $dompdf ];
    }
}