<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2012, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\controllers;

use lithium\action\DispatchException;
use lithium\data\Connections;
use app\models\Volumes;
use app\models\Types;
use app\models\Pages;
use app\models\Files;
use \MongoId;  

/**
 * This controller is used for serving static pages by name, which are located in the `/views/pages`
 * folder.
 *
 * A Lithium application's default routing provides for automatically routing and rendering
 * static pages using this controller. The default route (`/`) will render the `home` template, as
 * specified in the `view()` action.
 *
 * Additionally, any other static templates in `/views/pages` can be called by name in the URL. For
 * example, browsing to `/pages/about` will render `/views/pages/about.html.php`, if it exists.
 *
 * Templates can be nested within directories as well, which will automatically be accounted for.
 * For example, browsing to `/pages/about/company` will render
 * `/views/pages/about/company.html.php`.
 */
class PagesController extends \lithium\action\Controller {

	public function index(){
	
	$mongodb = Connections::get('default')->connection;
	$pages = Pages::connection()->connection->command(array(
	  'aggregate' => 'pages',
	  'pipeline' => array( 
	  array('$match'=>array('volume_number'=>'22')),
		array( 
		  '$group' => array( 
			'_id' => array('type'=>'$type.name','volume_number'=>'$volume_number','type_id'=>'$type._id'),
			'count' => array( '$sum' => 1) ,
		  ),
		),
		  array('$sort' => array( 'type_id' => 1)),
	  )
	));
	
	return compact('pages');
	}

	public function view() {
		$options = array();
		$path = func_get_args();

		if (!$path || $path === array('home')) {
			$path = array('home');
			$options['compiler'] = array('fallback' => true);
		}

		$options['template'] = join('/', $path);
		return $this->render($options);
	}

}

?>