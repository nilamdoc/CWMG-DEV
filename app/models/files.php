<?php

namespace app\models;

class Files extends \lithium\data\Model {

	protected $_meta = array('source' => 'fs.files');
	protected $_schema = array('_id'=>array('type'=>'id'));
	public $file;
	
	public static function loadFromFile($pdfurl, $pdffilename){

        $name = $pdfurl.$pdffilename;
        $pdf = Files::create(); 
        $pdf->file = file_get_contents($name);
        $pdf->filename = $pdffilename;
        $pdf->metadata = ["filename" => $pdffilename, "pdfurl" => $pdfurl];
//        	print_r($pdf);exit;
		return $pdf;
		
    }
	
}


?>