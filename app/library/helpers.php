<?php

use Phalcon\Http\Response;

if(!function_exists('errorResponse')) {
	function errorResponse($code = 404, $message = '', $results = []) {
		return (new Response())->setJsonContent(
            [
                "status" => [
                	"code" => $code,
                	"response" => "error",
                	"message" => $message,
                ],
                "result" => $results,
            ]
    	);
	}
}

if(!function_exists('successResponse')) {
	function successResponse($results = [], $message = '') {
		return (new Response())->setJsonContent(
			[
				"status" => [
					"code" => 200,
					"response" => "success",
					"message" => $message,
				],
				"result" => $results,
			]
		);
	}
}

if(!function_exists('validateError')) {
	function validateError($model) {
    	if(!$model) {
    		return errorResponse(404, 'The data not found');
    	}

    	$validateErrors = $model->validation();
    	
    	if(count($validateErrors)) {
    		return errorResponse(422, 'The request was well-formed but was unable to be followed due to semantic errors', $validateErrors);
    	}

    	return null;
	}
}