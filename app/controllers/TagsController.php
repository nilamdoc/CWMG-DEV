<?php
namespace app\controllers;

use lithium\action\DispatchException;
use app\models\Volumes;
use app\models\Types;
use app\models\Pages;
use \MongoId;  

class TagsController extends \lithium\action\Controller {
	public function index() {
		$volumes = Volumes::find('list',array("fields"=>"name","order"=>"number ASC"));
		return compact('volumes');
	}
	public function volume(){
if (!empty($this->request->params['args'][0])){
$filename = $this->request->params['args'][0];
	$volume_id = strval(floatval(substr(substr($this->request->params['args'][0],0,4),1,3)));
	$Pagesconditions = array('volume_number'=>$volume_id,'filename'=>$this->request->params['args'][0]);
}else{
		$id = $this->request->data['_id'];
		$conditions = array('_id'=>$id);
		$fields = array('number');
		$volumes = Volumes::find('first',array(
			"fields"=>$fields,
			"conditions"=>$conditions,
			"order"=>"number ASC"));
		$volume_id = strval($volumes['number']);
	$Pagesconditions = array('volume_number'=>$volume_id,'updated'=>'Y');
}
		$pages = Pages::find('all',array('options'=>
								array(
									'fields'=>array('_id','filename','description')
									),
								'conditions'=>$Pagesconditions,
								'order'=>array('sort_order'=>'ASC'),
								'limit'=>1
								));
		$numeric_vol = str_pad($volume_id,3,'0', STR_PAD_LEFT);
		$roman_vol = $this->roman($volume_id);
		Pages::meta('key', '_id');
        Pages::meta('title', 'filename');
		$topage = Pages::find('list',array(
								array(
									'fields'=>array('_id','filename')
									),
								'conditions'=>array('volume_number'=>$volume_id),
								'order'=>array('sort_order'=>'ASC'),
								)
								);
		
			return compact("pages","numeric_vol","roman_vol","topage");
	}


		function roman($N){
        $c='IVXLCDM';
        for($a=5,$b=$s='';$N;$b++,$a^=7)
           for($o=$N%$a,$N=$N/$a^0;$o--;$s=$c[$o>2?$b+$N-($N&=-2)+$o=1:$b].$s);
        return ($s);
	}
	public function save(){
//	print_r($this->request->data);
	$contents = $this->request->data['content'];
	$filename = $this->request->data['filename'];
	$data = array(
		'description'  => $contents,
		'ip'  => $_SERVER['REMOTE_ADDR'],
		'update_date' => gmdate('Y-m-d h:i:s'),
		'updated' => 'T',
	);
	$this->findTags($contents, $filename);
	$findPage = Pages::find(array('fields'=>'_id'),
				array('conditions'=>array(
							'filename'=>$filename
						)
					))->save($data);

	$this->redirect(array('controller'=>'Tags','action'=>'Volume/'.$this->request->data['filename']));
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

	public function findTags($Content, $filename){

	$itemno_p = "/<itemno style=\"background-color:#006600;color:white;\">(.*?)<\/itemno>/s";
	$itemtitle_p = "/<itemtitle style=\"background-color:#008800;color:white;\">(.*?)<\/itemtitle>/s";	
	$itemtowhom_p = "/<itemtowhom style=\"background-color:#993300;color:white;\">(.*?)<\/itemtowhom>/s";

	$itemdateactual_p = "/<itemdateactual style=\"background-color:#ff3300;color:white;\">(.*?)<\/itemdateactual>/s";
	$itemdatepresumed_p = "/<itemdatepresumed style=\"background-color:#ff6600;color:white;\">(.*?)<\/itemdatepresumed>/s";	

	$itemdayactual_p = "/<itemdayactual style=\"background-color:#ff3300;color:white;\">(.*?)<\/itemdayactual>/s";
	$itemdaypresumed_p = "/<itemdaypresumed style=\"background-color:#ff6600;color:white;\">(.*?)<\/itemdaypresumed>/s";	

	$itemdatedayactual_p = "/<itemdatedayactual style=\"background-color:#ff3300;color:white;\">(.*?)<\/itemdatedayactual>/s";
	$itemdatedaypresumed_p = "/<itemdatedaypresumed style=\"background-color:#ff6600;color:white;\">(.*?)<\/itemdatedaypresumed>/s";	

	$itemlunardateactual_p = "/<itemlunardateactual style=\"background-color:#ff3300;color:white;\">(.*?)<\/itemlunardateactual>/s";
	$itemlunardatepresumed_p = "/<itemlunardatepresumed style=\"background-color:#ff6600;color:white;\">(.*?)<\/itemlunardatepresumed>/s";	


	$itemaddress_p = "/<itemaddress style=\"background-color:#000066;color:white;\">(.*?)<\/itemaddress>/s";
	$itemfrom_p = "/<itemfrom style=\"background-color:#0000ff;\">(.*?)<\/itemfrom>/s";	
	

	$itemsource_p = "/<itemsource style=\"background-color:#666600;color:white;\">(.*?)<\/itemsource>/s";
	$itemsourcetype_p = "/<itemsourcetype style=\"background-color:#777700;color:white;\">(.*?)<\/itemsourcetype>/s";
	$itemsourcelanguage_p = "/<itemsourcelanguage style=\"background-color:#888800;color:white;\">(.*?)<\/itemsourcelanguage>/s";
	$itemsourcegroup_p = "/<itemsourcegroup style=\"background-color:#999900;color:black;\">(.*?)<\/itemsourcegroup>/s";
	$itemsourceno_p = "/<itemsourceno style=\"background-color:#aaaa00;color:black;\">(.*?)<\/itemsourceno>/s";
	$itemsourcedate_p = "/<itemsourcedate style=\"background-color:#bbbb00;color:black;\">(.*?)<\/itemsourcedate>/s";
	$itemsourcecourtesy_p = "/<itemsourcecourtesy style=\"background-color:#cccc00;color:black;\">(.*?)<\/itemsourcecourtesy>/s";

	$notes_p = "/<notes style=\"background-color:#cccccc;color:black;\">(.*?)<\/notes>/s";
	$person_p = "/<person style=\"background-color:#00cc99;color:black;\">(.*?)<\/person>/s";
	$place_p = "/<place style=\"background-color:#000033;color:white;\">(.*?)<\/place>/s";
	$glossary_p = "/<glossary style=\"background-color:#000000;color:white;\">(.*?)<\/glossary>/s";

	$indexoftitlesentries_p = "/<indexoftitlesentries style=\"background-color:#000000;color:white;\">(.*?)<\/indexoftitlesentries>/s";
	$indexentries_p = "/<indexentries style=\"background-color:#000000;color:white;\">(.*?)<\/indexentries>/s";	

	$chronologyentries_p = "/<chronologyentries style=\"background-color:#000000;color:white;\">(.*?)<\/chronologyentries>/s";
	$sourcesentries_p = "/<sourcesentries style=\"background-color:#000000;color:white;\">(.*?)<\/sourcesentries>/s";	

	$contentitemrow_p = "/<contentitemrow style=\"background-color:#0033ff;color:white;\">(.*?)<\/contentitemrow>/s";	
		

						
//		echo $Content;
		$records = $this->getTextBetweenTags($Content, $itemno_p); 
		if(!empty($records)){
			print_r('item.no');
			$this->dosave($records, $filename, 'item.no');
		}
		$records = $this->getTextBetweenTags($Content, $itemtitle_p); 
		if(!empty($records)){
			print_r('item.title');		
			$this->dosave($records, $filename, 'item.title');		
		}
		$records = $this->getTextBetweenTags($Content, $itemtowhom_p); 
		if(!empty($records)){
			print_r('item.towhom');		
			$this->dosave($records, $filename, 'item.towhom');		
		}
		$records = $this->getTextBetweenTags($Content, $itemdateactual_p); 
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.date.actual');		
		}
		$records = $this->getTextBetweenTags($Content, $itemdatepresumed_p); 
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.date.presumed');		
		}
		$records = $this->getTextBetweenTags($Content, $itemdayactual_p); 
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.day.actual');		
		}
		$records = $this->getTextBetweenTags($Content, $itemdaypresumed_p); 
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.day.presumed');		
		}
		$records = $this->getTextBetweenTags($Content, $itemdatedayactual_p); 
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.date.day.actual');		
		}
		$records = $this->getTextBetweenTags($Content, $itemdatedaypresumed_p);	
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.date.day.presumed');		
		}
		$records = $this->getTextBetweenTags($Content, $itemlunardateactual_p); 
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.lunar.date.actual');		
		}
		$records = $this->getTextBetweenTags($Content, $itemlunardatepresumed_p); 
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.lunar.date.presumed');		
		}
		$records = $this->getTextBetweenTags($Content, $itemaddress_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.address');		
		}
		$records = $this->getTextBetweenTags($Content, $itemfrom_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.from');		
		}
		$records = $this->getTextBetweenTags($Content, $itemsource_p); 
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.source.name');		
		}
		$records = $this->getTextBetweenTags($Content, $itemsourcetype_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.source.type');		
		}
		$records = $this->getTextBetweenTags($Content, $itemsourcelanguage_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.source.language');		
		}
		$records = $this->getTextBetweenTags($Content, $itemsourcegroup_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.source.group');		
		}
		$records = $this->getTextBetweenTags($Content, $itemsourceno_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.source.no');		
		}
		$records = $this->getTextBetweenTags($Content, $itemsourcedate_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.source.date');		
		}
		$records = $this->getTextBetweenTags($Content, $itemsourcecourtesy_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'item.source.courtesy');		
		}
		$records = $this->getTextBetweenTags($Content, $notes_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'page.notes');		
		}
		$records = $this->getTextBetweenTags($Content, $person_p); 
		if(!empty($records)){
			$this->dosave($records, $filename, 'page.person');		
		}
		$records = $this->getTextBetweenTags($Content, $place_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'page.place');		
		}
		$records = $this->getTextBetweenTags($Content, $glossary_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'page.glossary');		
		}
		$records = $this->getTextBetweenTags($Content, $indexoftitlesentries_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'index.oftitles.entries');		
		}
		$records = $this->getTextBetweenTags($Content, $indexentries_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'index.entries');		
		}
		$records = $this->getTextBetweenTags($Content, $chronologyentries_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'chronology.entries');		
		}
		$records = $this->getTextBetweenTags($Content, $sourcesentries_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'sources.entries');		
		}
//		exit;
		$records = $this->getTextBetweenTags($Content, $contentitemrow_p);  
		if(!empty($records)){
			$this->dosave($records, $filename, 'content.item.row');		
		}

	}


	function getTextBetweenTags($string, $item_p){
		preg_match_all($item_p, $string, $matches);
		return $matches[1];
	 }

	function dosave($records, $filename, $tagname){
	// Find the record for the $filename to modify
	$findItem = Pages::find('all',
				array('conditions'=>array(
							'filename'=>$filename
						)
					));
	$variable = array();
	// check  for the tagname, if present, assign the value to the variable...
	foreach($findItem as $f){
		foreach($f->$tagname as $key=>$val){
			array_push($variable,$key=$val);
		}
	} 

	// add all values from $record (input by client) to $variable (data in Mongo)
	foreach ($records as $r){
		 array_push($variable,$r);
	}
//	print_r($variable);exit;
	array_filter($variable);
	// add it to the original tagname as an array
$data = array($tagname=>array_unique($variable));


//print_r($data);
//exit;
	// find the pagename with the file and save the $data....
	$findPage = Pages::find(array('fields'=>'_id'),
				array('conditions'=>array(
							'filename'=>$filename
						)
					))->save($data);

	}

}
?>