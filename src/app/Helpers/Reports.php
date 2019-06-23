<?php 

namespace Solunes\Master\App\Helpers;

class Reports {

    public static function check_report_view($view, $array) {
        if(request()->has('download-pdf')){
            $array['pdf'] = true;
            $array['dt'] = 'view';
            $array['header_title'] = 'Reporte generado';
            $array['title'] = 'Reporte generado';
            $array['site'] = \Solunes\Master\App\Site::find(1);
            $pdf = \PDF::loadView($view, $array);
            $pdf = \Asset::apply_pdf_template($pdf, $variables['header_title'], ['margin-top'=>'35mm','margin-bottom'=>'25mm','margin-right'=>'25mm','margin-left'=>'25mm']);
            return $pdf->stream('reporte_'.date('Y-m-d').'.pdf');
        } else {
            return view($view, $array);
        } 
    }

}