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
		$id = $this->request->data['_id'];
		$conditions = array('_id'=>$id);
		$fields = array('number');
		$volumes = Volumes::find('first',array(
			"fields"=>$fields,
			"conditions"=>$conditions,
			"order"=>"number ASC"));
		$volume_id = $volumes['number'];
		$pages = Pages::find('all',array('options'=>
								array(
									'fields'=>array('_id','filename','description')
									),
								'conditions'=>array('volume_number'=>$volume_id,'updated'=>'Y'),
								'order'=>array('sort_order'=>'ASC'),
								'limit'=>1
								));
		$numeric_vol = str_pad($volume_id,3,'0', STR_PAD_LEFT);
		$roman_vol = $this->roman($volume_id);

		return compact("pages","numeric_vol","roman_vol");
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