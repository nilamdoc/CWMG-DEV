<?php

namespace app\models;

class Volumes extends \lithium\data\Model {
	public $_meta = array('connection' => 'default',array(
		'key' => '_id',
		'locked' => true
		));
	public $validates = array();

	protected $_schema = array(
		'_id'	=>	array('type' => 'id'),
		'name'	=>	array('type' => 'string', 'null' => false),
	);

}

?>