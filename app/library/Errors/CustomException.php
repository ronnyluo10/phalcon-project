<?php

class CustomException extends \Exception {
	public function errorMessage() 
	{
		$errorMessage = $this->getMessage();
		$message = "Error detail: <br />";

		if(is_array($errorMessage)) {
			foreach ($errorMessage as $value) {
				$message .= $value."<br />";
			}
		} else {
			$message = $errorMessage;
		}

		return $message;
	}
}