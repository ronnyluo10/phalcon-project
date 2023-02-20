<?php
declare(strict_types=1);

class PatientsController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
		try {
			return successResponse(
				\Datatables::of(\Patients::query()),
				"Success retrieve patients data",
			);
		} catch (\Exception $e) {
			return errorResponse(500, $e->getMessage());
		}
    }

    public function storeAction()
    {
    	$patient = new \Patients();

    	if($validate = validateError($patient)) {
    		return $validate;
    	}

    	try {
    		if($patient = $patient->storeData() === false) {
    			throw new \CustomException('Store data patient error. Please try again');
    		}

    		return successResponse([], 'Success store patients data');
    	} catch (\CustomException $e) {
    		return errorResponse(417, $e->errorMessage());
    	} catch(\Exception $e) {
    		return errorResponse(500, $e->getMessage());
    	}
    }

    public function updateAction($id)
    {
    	$patient = new \Patients();
    	$patient->id = $id;

    	if($validate = validateError($patient)) {
    		return $validate;
    	}

    	try {
    		if($patient = $patient->updateDate() === false) {
    			throw new \CustomException('Update data patient error. Please try again');
    		}

    		return successResponse([], 'Success update patients data');
    	} catch (\CustomException $e) {
    		return errorResponse(417, $e->errorMessage());
    	} catch(\Exception $e) {
    		return errorResponse(500, $e->getMessage());
    	}
    }

    public function deleteAction($id)
    {
    	try {
    		$patient = \Patients::findFirst($id);

    		if(!$patient) {
    			throw new \CustomException("Data not found");    			
    		}

    		if($patient->delete() === false) {
    			throw new \CustomException("Delete patient with name ".$patient->name." is error. Please try again.");
    		}

    		return successResponse([], 'Success delete patient with name '.$patient->name);
    	} catch (\CustomException $e) {
    		return errorResponse(417, $e->errorMessage());
    	} catch(\Exception $e) {
    		return errorResponse(500, $e->getMessage());
    	}
    }

    public function masterAction()
    {
    	try {
    		return successResponse([
    			'sex' => \Gender::getArray(),
    			'religion' => \Religion::getArray(),
    		]);
    	} catch (\Exception $e) {
    		return errorResponse(500, $e->getMessage());
    	}
    }

    public function showAction($id)
    {
    	try {
    		$patient = \Patients::findFirst($id);

    		if(!$patient) {
    			throw new \CustomException("Data not found");	
    		}

    		$data = [
    			"name" => $patient->name,
    			"sex_value" => \Gender::getString($patient->sex),
    			"sex" => $patient->sex,
    			"religion_value" => \Religion::getString($patient->religion),
    			"religion" => $patient->religion,
    			"phone" => $patient->phone,
    			"address" => $patient->address,
    			"nik" => $patient->nik,
    		];

    		return successResponse($data);
    	} catch (\CustomException $e) {
    		return errorResponse(404, $e->errorMessage());
    	} catch(\Exception $e) {
    		return errorResponse(500, $e->getMessage());
    	}
    }
}