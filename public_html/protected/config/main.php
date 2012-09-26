<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Notification service',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.components.rest.*',
        'application.modules.rights.*', 
        'application.modules.rights.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'v1',
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'gii_pwd',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
       'rights'=>array(
			'superuserName'=>'Admin',					// Name of the role with super user privileges.
			'authenticatedName'=>'Authenticated',		// Name of the authenticated user role.
			'userClass' => 'User',					// Name of the usrs table in the database
			'userIdColumn'=>'id',						// Name of the user id column in the database.
			'userNameColumn'=>'name',					// Name of the user name column in the database.
			'install'=>false,							// Whether to install rights.
			'layout'=>'rights.views.layouts.main',		// Layout to use for displaying Rights.
			'appLayout'=>'application.views.layouts.column1', // Application layout.
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
        
        'user'=>array( 
            'class'=>'RWebUser', 
            ), 
            'authManager'=>array( 
                'class'=>'RDbAuthManager', 
            ),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<module:\w+>/<controller:\w+>/<id:\d+>'=>'<module>/<controller>/view',
				'<module:\w+>/<controller:\w+>/<action:\w+>/<id:\w+>'=>'<module>/<controller>/<action>',
				'<module:\w+>/<controller:\w+>/<action:\w+>'=>'<module>/<controller>/<action>',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				array('<module>/<controller>/post/', 'pattern'=>'<module:\w+>/<controller:\w+>.<format:\w+>','verb'=>'POST'),
				array('<module>/<controller>/get/', 'pattern'=>'<module:\w+>/<controller:\w+>.<format:\w+>','verb'=>'GET'),
				
			),
			'showScriptName' => false,
		),
		
        /*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
         */
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=web31',
			'emulatePrepare' => true,
            'tablePrefix' => 'web31',
			'username' => 'web31',
			'password' => '6raSpaju',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);