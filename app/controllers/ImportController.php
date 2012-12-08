<?php

namespace app\controllers;

use lithium\action\DispatchException;

use app\models\Volumes;
use app\models\Types;
use app\models\Pages;
use app\models\Files;
use \MongoId;  


set_time_limit(0);
class ImportController extends \lithium\action\Controller {


	public function index() {
		$volumes = Volumes::find('list',array("fields"=>"name","order"=>"number ASC"));
		
		$pageVolumes = Pages::connection()->connection->command(array(
    		'distinct' => 'pages',
	    	'key' => 'volume_number',
		));		
	
		$importedVolumes = array();
		$i = 0;
//		print_r($pageVolumes['values']);
	foreach ($pageVolumes['values'] as $v){
		array_push($importedVolumes, $v);
		$i++;
	}
	$Ivolumes = Volumes::find('all',array(
		'fields'=>'name, number',
		'conditions'=>array(
			'number'=> array('$in'=>$importedVolumes)),
		'order'=>'number ASC'));
//	print_r($Ivolumes);
	
//	print_r($importedVolumes);

		return compact('volumes','Ivolumes');
	}
	public function volume() {
			$id = $this->request->data['_id'];
			$conditions = array('_id'=>$id);
			$fields = array('number','_id');
		$volumes = Volumes::find('first',array(
			"fields"=>$fields,
			"conditions"=>$conditions,
			"order"=>"number ASC"));
		$volume_id = $volumes['_id'];
		$volume_number = $volumes['number'];
		$url = CWMG_VOLUMES_PATH."\\v".str_pad($volume_number,3,"0",STR_PAD_LEFT)."-".$this->roman($volume_number)."\\TXT";
		$count = Pages::count(array('volume_number'=>$volume_number));		
		$numeric_vol = str_pad($volume_number,3,'0', STR_PAD_LEFT);
		$roman_vol = $this->roman($volume_number);
		return compact('count','url','numeric_vol','roman_vol','volume_number');
	}
	public function files() {
	
		$id = $this->request->data['volume_number'];
		$volume_number = $id;
		$url = CWMG_VOLUMES_PATH."\\v".str_pad($id,3,"0",STR_PAD_LEFT)."-".$this->roman($id)."\\TXT";
		$count = Pages::count(array('volume_number'=>$id));		
		$numeric_vol = str_pad( $id,3,'0', STR_PAD_LEFT);
		$roman_vol = $this->roman($id);
		if ($handle = opendir($url)  ) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
				 if(stristr($entry,"-B-")){
				 	$is_roman = "1";
				 }else{
				 	$is_roman = "0";
				 }
					$page = str_replace(".txt","",$entry);
					$page = str_replace("v".$numeric_vol."-".$roman_vol,"",$page);
					$page = str_replace("-A-","",$page);				
					$page = str_replace("-B-","",$page);				
					$page = str_replace("-C-","",$page);				
					$page = str_replace("-D-","",$page);				
					$filename = str_replace(".txt","",$entry);

		$pdfurl = CWMG_VOLUMES_PATH."\\v".str_pad($id,3,"0",STR_PAD_LEFT)."-".$this->roman($id)."\\PDF\\";
//				if (substr($filename,12,3)>500 && substr($filename,12,3)<=600){
					$pdffilename = $filename.".pdf";
					$cmd = '"E:\\MongoDB\\bin\\Mongofiles.exe"  -d CWMG -r put '.$pdfurl.$pdffilename;
					print_r($cmd);
					exec($cmd);
		
					// rename the file in fs.files
					$file = Files::create();
					$data = array('filename'=>$filename.".pdf");
					$dataFS = Files::find('all',array(
						'conditions' => array('filename'=>$pdfurl.$pdffilename)
					))->save($data);
//				}
			}
			}
		}
	}
	
	public function pages() {
		$id = $this->request->data['volume_number'];
		$volume_number = $id;
		$url = CWMG_VOLUMES_PATH."\\v".str_pad($id,3,"0",STR_PAD_LEFT)."-".$this->roman($id)."\\TXT";
		$count = Pages::count(array('volume_number'=>$id));		
		$numeric_vol = str_pad( $id,3,'0', STR_PAD_LEFT);
		$roman_vol = $this->roman($id);

		if($count==0){
			$sortorder = 0;
			if(!empty($id)){
			if ($handle = opendir($url)  ) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
				 if(stristr($entry,"-B-")){
				 	$is_roman = "1";
				 }else{
				 	$is_roman = "0";
				 }
					$page = str_replace(".txt","",$entry);
					$page = str_replace("v".$numeric_vol."-".$roman_vol,"",$page);
					$page = str_replace("-A-","",$page);				
					$page = str_replace("-B-","",$page);				
					$page = str_replace("-C-","",$page);				
					$page = str_replace("-D-","",$page);				
					$filename = str_replace(".txt","",$entry);
//		echo "$page\n";
		
					$contents = file_get_contents($url."\\".$entry);
					
			$data = array(
				'volume_number'  => $id,
				'type.id'  => '',			
				'type.no'  => null,							
				'type.name'  => null,								
				'description'  => $this->clean_hex(str_replace('\•','',strip_tags($contents,'<p><i><ol><li><ul>'))),
				'page_no'  =>  $page,
				'is_roman'  => $is_roman,				
				'sort_order'  => $sortorder+1,				
				'filename'  => $filename,				
				'updated'  => "Y",
 /*
'item.no'  => array("",""),
'item.title'  => array("",""),
'item.to_whom'  => array("",""),
'item.date.actual'  => array("",""),
'item.date.presumed'  => array("",""),
'item.day.actual'  => array("",""),
'item.day.presumed'  => array("",""),
'item.date.day.actual'  => array("",""),
'item.date.day.presumed'  => array("",""),
'item.lunardate.actual'  => array("",""),
'item.lunardate.presumed'  => array("",""),
'item.address'  => array("",""),
'item.from'  => array("",""),
'item.source.name'  => array("",""),
'item.source.type'  => array("",""),
'item.source.language'  => array("",""),
'item.source.group'  => array("",""),
'item.source.no'  => array("",""),
'item.source.date'  => array("",""),
'item.source.courtesy'  => array("",""),

'page.type'=> "",
'page.notes'  => "",
'page.persons'  => array("",""),
'page.places'  => array("",""),
'page.glossary'  => array("",""),
'index.oftitles.entries'  => array("",""),
'index.entries'  => array("",""),
'chronology.entries'  => array("",""),
'sources.entries'  => array("",""), 
*/
				'updated_date'  => gmdate('Y-m-d'),				
				'ip'  => $_SERVER['REMOTE_ADDR']
			);
			$new_page = Pages::create($data)->save();
			$newid = $pages->_id;
		
			// Import the file in fs.files
			$pdfurl = CWMG_VOLUMES_PATH."\\v".str_pad($id,3,"0",STR_PAD_LEFT)."-".$this->roman($id)."\\PDF\\";
			$pdffilename = $filename.".pdf";
			$cmd = '"E:\\MongoDB\\bin\\Mongofiles.exe"  -d CWMG put '.$pdfurl.$pdffilename;
//			exec($cmd);
			
			// rename the file in fs.files
			$file = Files::create();
			$data = array('filename'=>$filename.".pdf");
			$dataFS = Files::find('all',array(
				'conditions' => array('filename'=>$pdfurl.$pdffilename)
			))->save($data);

			$sortorder++;
				}
			}}
			closedir($handle);
			}
		}else{
			$sortorder = 0;
			if ($handle = opendir($url)  ) {
			while (false !== ($entry = readdir($handle))) {
			$sortorder++;
				if ($entry != "." && $entry != "..") {
				 if(stristr($entry,"-B-")){
				 	$is_roman = "1";
				 }else{
				 	$is_roman = "0";
				 }

					$page = str_replace(".txt","",$entry);
					$page = str_replace("v".$numeric_vol."-".$roman_vol,"",$page);
					$page = str_replace("-A-","",$page);				
					$page = str_replace("-B-","",$page);				
					$page = str_replace("-C-","",$page);				
					$page = str_replace("-D-","",$page);				
					$filename = str_replace(".txt","",$entry);
					$contents = file_get_contents($url."\\".$entry);
					$conditions = array('filename'=>$filename);

					$page_id = Pages::find(array('fields'=>'_id'),array('conditions'=>array('filename'=>$filename)));
				
					$data = array(
						'description'  => $this->clean_hex(str_replace('\•','',strip_tags($contents,'<p><i><ol><li><ul>'))),
						'ip'  => $_SERVER['REMOTE_ADDR']
					);
					$conditions = array('_id'=>$page_id['_id']);
//					$new_page = Pages::update($data, $conditions);
					Pages::find(array('fields'=>'_id'),array('conditions'=>array('filename'=>$filename)))->save($data);
//					print_r("volume_number");
				}
				}
			closedir($handle);
			}
			
		}
		$count = Pages::count(array('conditions'=>array('volume_number'=>$id,'type.id'=>'')));		
		return compact('count','numeric_vol','roman_vol','volume_number');

	}
	
	public function Assign(){
		$id = $this->request->data['volume_number'];
//		print_r($id);
		$pagetypes = Pages::find('all',array('options'=>
								array(
									'fields'=>array('_id','filename')
									),
								'conditions'=>array('type.id'=>'','volume_number'=>$id),
								'order'=>array('sort_order'=>'ASC'),
								'limit'=>1
								));
		Pages::meta('key', '_id');
        Pages::meta('title', 'filename');
		$topage = Pages::find('list',array(
								array(
									'fields'=>array('_id','filename')
									),
								'conditions'=>array('type.id'=>'','volume_number'=>$id),
								'order'=>array('sort_order'=>'ASC'),
								)
								);
	$types = Types::find('list',array(
	'fields'=>array('id','name'),
	'order'=>'id'));
//	print_r($types);
	$numeric_vol = str_pad($id,3,'0', STR_PAD_LEFT);
	$roman_vol = $this->roman($id);
	return compact('pagetypes','id','types','topage','numeric_vol','roman_vol');

	}
	
	public function assign_volume_type($From, $To, $VolumeType){
/* 		echo $From;
		echo $To;
		echo $VolumeType;
 */		$From_id = Pages::find(array('fields'=>'_id'),array('conditions'=>array('filename'=>$From)));		

		$To_id = Pages::find(array('fields'=>'_id'),array('conditions'=>array('_id'=>$To)));		
		$Types_id = Types::find(array('fields'=>'_id'),array('conditions'=>array('_id'=>$VolumeType)));		
		
		
		foreach($Types_id as $t){
			$type_id = $t['_id'];
			$type_name = $t['name'];
			$type_no = $t['no'];			
		}
		foreach($From_id as $t){
			$from_sort_order = $t['sort_order'];
			$volume_number = $t['volume_number'];			
		}
		foreach($To_id as $t){
			$to_sort_order = $t['sort_order'];
		}
		
		$data = array(
			'type.no'  => $type_no,		
			'type.id'  => $type_id,
			'type.name'  => $type_name,			
			'ip'  => $_SERVER['REMOTE_ADDR']
		);
		
		for ($i=$from_sort_order;$i<=$to_sort_order;$i++){
			$new_page = Pages::find(array('fields'=>'_id'),
				array('conditions'=>array(
							'sort_order'=>$i,
							'volume_number'=>$volume_number
						)
					)
				)->save($data);		
		}

//		return $this->render(array('json' => $data = array($new_page), 'status'=> 200));
	
	}
	function roman($N){
        $c='IVXLCDM';
        for($a=5,$b=$s='';$N;$b++,$a^=7)
           for($o=$N%$a,$N=$N/$a^0;$o--;$s=$c[$o>2?$b+$N-($N&=-2)+$o=1:$b].$s);
        return ($s);
	}
	function clean_hex($input){
		$clean = preg_replace("![\][xX]([A-Fa-f0-9]{1,3})!", "",$input);
		//reject overly long 2 byte sequences, as well as characters above U+10000 and replace with ?
		$clean = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]'.
		'|[\x00-\x7F][\x80-\xBF]+'.
		'|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'.
		'|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})'.
		'|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
		'', $clean );
		
		//reject overly long 3 byte sequences and UTF-16 surrogates and replace with ?
		$clean = preg_replace('/\xE0[\x80-\x9F][\x80-\xBF]'.
		'|\xED[\xA0-\xBF][\x80-\xBF]/S','', $clean );
		return $clean;
	}

}

?>
