<?php

$router = $di->getRouter();

// Define your routes here

$router->addPost('/api/patients/list', [
	'controller' => 'patients',
	'action' => 'index'
]);

$router->addPost('/api/patients/store', [
	'controller' => 'patients',
	'action' => 'store'
]);

$router->add('/api/patients/update/{id}', [
	'controller' => 'patients',
	'action' => 'update'
])->via([
	'PUT',
]);

$router->add('/api/patients/delete/{id}', [
	'controller' => 'patients',
	'action' => 'delete'
])->via([
	'DELETE'
]);

$router->add('/api/master/data', [
	'controller' => 'patients',
	'action' => 'master'
]);

$router->add('/api/patients/show/{id}', [
	'controller' => 'patients',
	'action' => 'show'
]);

$router->handle($_SERVER['REQUEST_URI']);
