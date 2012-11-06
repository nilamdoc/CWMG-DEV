<?php

namespace app\controllers;

use lithium\action\DispatchException;
use app\models\Volumes;
use app\models\Types;
use app\models\Pages;
use \MongoId;  

class ImportController extends \lithium\action\Controller {
	public function index() {
		$volumes = Volumes::find('list',array("fields"=>"name","order"=>"number ASC"));
		return compact('volumes');
	}

}

?>