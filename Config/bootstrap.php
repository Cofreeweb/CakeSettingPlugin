<?php

App::uses( 'Settings', 'Setting.Lib');


Configure::write( 'Admin.actionOverrides', (array)Configure::read('Admin.actionOverrides') + array(
	'Setting.Setting' => array(
		'update' => array( 
		  'admin' => true,
		  'plugin' => 'setting', 
		  'controller' => 'settings', 
		  'action' => 'admin_update'
	  )
	)
));