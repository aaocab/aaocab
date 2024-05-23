<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ReturnSet implements JsonSerializable
{

	private $_success	 = false;
	private $_data;
	private $_errors;
	private $_errorCode	 = 0;
	private $_message;

	const ERROR_FAILED				 = 101;
	const ERROR_SERVER				 = 102;
	const ERROR_UNKNOWN				 = 103;
	const ERROR_VALIDATION			 = 104;
	const ERROR_INVALID_DATA			 = 105;
	const ERROR_NO_RECORDS_FOUND		 = 106;
	const ERROR_REQUEST_CANNOT_PROCEED = 107;
	const ERROR_UNAUTHORISED			 = 108;
	const ERROR_DUPLCATE_DATA			 = 109;
	const ERROR_NULL					 = 'NULL';
    const ERROR_EMAILEXIST = 1001;
    const ERROR_PHONEEXIST = 1002;

	public function setStatus(bool $status)
	{
		$this->_success = $status;
	}

	public function getStatus()
	{
		return $this->_success;
	}

	public function isSuccess()
	{
		return $this->_success;
	}

	public function setData($data, $removeNull = true)
	{
		if ($removeNull)
		{
			$data = Filter::removeNull($data);
		}

		$this->_data = $data;
	}

	public function getData()
	{
		return $this->_data;
	}

	public function setMessage($message)
	{
		$this->_message = $message;
	}

	public function getMessage()
	{
		return $this->_message;
	}

	public function setErrors($errors, $errorCode = null)
	{
		if ($errorCode !== null)
		{
			$this->setErrorCode($errorCode);
		}
		$this->_errors = $errors;
	}

	public function setErrorCode($errorCode)
	{
		$this->_errorCode = $errorCode;
	}

	public function getErrorCode()
	{
		return $this->_errorCode;
	}

	public function getErrors()
	{
		return $this->_errors;
	}

	public function getError($key)
	{
		return $this->_errors[$key];
	}

	public function addError($error, $key = null)
	{
		if ($this->_errors == null)
		{
			$this->_errors = [];
		}
		$this->_errors[$key] = $error;
	}

	public function hasErrors()
	{
		return ($this->_errors != null || $this->_errorCode != 0);
	}

	public function jsonSerialize()
	{
		$arr			 = [];
		$arr['success']	 = $this->getStatus();
		if ($this->_message != '')
		{
			$arr['message'] = $this->_message;
		}

		if ($this->getErrorCode() !== 0)
		{
			$arr['errorCode']	 = $this->getErrorCode();
			$arr['errors']		 = $this->getErrors();
		}
		if ($this->getData() != null)
		{
			$arr['data'] = $this->getData();
		}
		$arr = Filter::removeNull($arr);
		return $arr;
	}
	
	
	/** @return Exception */
	public function getException()
	{
		$message = "";
		
		if($this->hasErrors())
		{
			$message = json_encode($this->getErrors());
		}
		
		return new Exception($message, $this->getErrorCode());
	}

	public static function setException(Exception $ex)
	{
		$returnSet = new ReturnSet();
		$returnSet->setErrorCode($ex->getCode());
		if ($ex instanceof JsonMapper_Exception)
		{
			$returnSet->setErrorCode(self::ERROR_INVALID_DATA);
		}

		$errors = Filter::getNestedValues(CJSON::decode($ex->getMessage(), true));
		if ($returnSet->getErrorCode() == self::ERROR_VALIDATION)
		{
			$returnSet->setErrors(array_unique($errors));
			Logger::trace(json_encode($returnSet->getErrors()));
			goto skipModelError;
		}

		if ($ex->getCode() === 1)
		{
			$returnSet->setErrors(CJSON::decode($ex->getMessage()), $ex->getCode());
			Logger::trace(json_encode($returnSet->getErrors()));
			goto skipModelError;
		}
		if (!in_array(Logger::getErrorCode($ex), [ReturnSet::ERROR_NO_RECORDS_FOUND, ReturnSet::ERROR_VALIDATION, ReturnSet::ERROR_PHONEEXIST, ReturnSet::ERROR_EMAILEXIST, ReturnSet::ERROR_DUPLCATE_DATA, ReturnSet::ERROR_REQUEST_CANNOT_PROCEED, 400, 401, 403]))
		{
			Logger::exception($ex);
		}
		else
		{
			Logger::trace(Logger::getExceptionString($ex));
		}

		if(is_array($errors))
		{
			$returnSet->setErrors($errors, $ex->getCode());
		}
		else
		{
			$returnSet->setErrors([$ex->getMessage()], $ex->getCode());
		}
		
		skipModelError:
		return $returnSet;
	}

	public static function renderJSONException($ex)
	{
		$returnSet = ReturnSet::setException($ex);

		if (Yii::app()->request->isAjaxRequest)
		{
			echo CJSON::encode($returnSet);
			Yii::app()->end();
		}
		return $returnSet;
	}
	
	/** @param CActiveRecord $model */
	public static function getModelValidationException($model)
	{
		if(!($model instanceof CActiveRecord))
		{
			return new Exception("Invalid model type");
		}
		
		if($model->hasErrors())
		{
			return new Exception(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
		}

		return null;
	}

}
