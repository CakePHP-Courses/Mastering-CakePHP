<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
 
 Router::parseExtensions('rss', 'json');
 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	App::uses('I18nRoute', 'I18n.Routing/Route');
	App::uses('I18nSluggableRoute', 'I18n.Routing/Route');

	Router::connect('/photos/:Photo', array('controller' => 'photos', 'action' => 'view'), array(
		'routeClass' => 'I18nSluggableRoute',
		'models' => array('Photo')
	));
	Router::connect('/photos/:year/:month/:day', array('controller' => 'photos', 'action' => 'archive'), array(
		'year' => Router::YEAR,
		'month' => Router::MONTH,
		'day' => Router::DAY,
		'pass' => array('year', 'month', 'day'),
		'routeClass' => 'I18nRoute',
	));
	Router::connect('/photos/:year/:month', array('controller' => 'photos', 'action' => 'archive'), array(
		'year' => Router::YEAR,
		'month' => Router::MONTH,
		'pass' => array('year', 'month'),
		'routeClass' => 'I18nRoute',
	));
	Router::connect('/photos/:year', array('controller' => 'photos', 'action' => 'archive'), array(
		'year' => Router::YEAR,
		'pass' => array('year'),
		'routeClass' => 'I18nRoute',
	));
		



/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
	//require CAKE . 'Config' . DS . 'routes.php';
