<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HttpsFilter extends CFilter
{

	public $bypass = FALSE;

	protected function preFilter($filterChain)
	{
		if ((!Yii::app()->getRequest()->isSecureConnection) && (!$this->bypass) && Yii::app()->params['https'] == true)
		{
			# Redirect to the secure version of the page.
			$url = 'https://' .
					$_SERVER['HTTP_HOST'] .
					Yii::app()->getRequest()->requestUri;
			Yii::app()->request->redirect($url);
			return false;
		}
		return true;
	}

}
