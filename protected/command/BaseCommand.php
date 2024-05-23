<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaseCommand
 *
 * @author Admin
 */
class BaseCommand extends CConsoleCommand
{

	public function beforeAction($action, $params)
	{
		Logger::setCommandCategory($this->name, $action);
		return true;
	}

	public function afterAction($action, $params)
	{
		Logger::unsetCommandCategory($this->name, $action);
	}

}
