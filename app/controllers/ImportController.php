<?php

namespace app\controllers;

use lithium\action\DispatchException;
use app\models\Volumes;
use app\models\Types;
use app\models\Pages;
use \MongoId;  

class ImportController extends \lithium\action\Controller {
	public function index() {
		$volumes = Volumes::all(array('order'=> array('_id'=>'ASC')));

		return compact('volumes');
	}
}

?>