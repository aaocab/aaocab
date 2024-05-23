<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModelValidationException
 *
 * @author Admin
 */
class ModelValidationException extends Exception
{
	public function __construct(CModel $model)
	{
		parent::__construct(json_encode($model->getErrors()), ReturnSet::ERROR_VALIDATION);
	}

}
