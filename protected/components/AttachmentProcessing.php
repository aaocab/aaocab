<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AttachmentProcessing
{

	public function ImagePath($filename)
	{
		$path = Yii::app()->basePath . $filename;
		if (file_exists($path) && $filename != NULL)
		{
			$yourImageUrl = Yii::app()->assetManager->publish($path);
			return $yourImageUrl;
		}
		else
		{
			return '/images/no-image.png';
		}
	}

	public static function publish($filePath)
	{
		if (file_exists($filePath) && $filePath != '')
		{
			$yourImageUrl = Yii::app()->assetManager->publish($filePath);
			return $yourImageUrl;
		}
		else
		{
			return '/images/no-image.png';
		}
	}

	public function ImageDelete($filename)
	{
		$path = Yii::app()->basePath . $filename;
		if (file_exists($path))
		{
			$sPublishedPath				 = Yii::app()->assetManager->getPublishedPath($path);
			$sCachePathTobeDelete		 = str_replace("\\", "/", $sPublishedPath);
			$sProtectedPathToBeDelete	 = str_replace("\\", "/", $path);

			if (file_exists($sCachePathTobeDelete) && file_exists($sProtectedPathToBeDelete))
			{
				unlink($sProtectedPathToBeDelete);
				unlink($sCachePathTobeDelete);
			}
		}
	}

}

?>
