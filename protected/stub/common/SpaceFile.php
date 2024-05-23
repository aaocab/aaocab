<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Stub\common;

/**
 * Description of Space
 *
 * @author Admin
 *
 * @property SpaceFileURL[] $urls Description
 */
class SpaceFile
{

	public $bucket, $key, $contentType, $lastModified, $expiredAt;
	public $isPublic = false;

	/** @var SpaceFileURL[] $urls */
	public $urls = [];

	/** @var \SpacesAPI\File $file */
	private $file = null;

	/** @var \SpacesAPI\Space $space */
	private $space = null;

	private $_urlCreated = false;

	//put your code here

	/** @return SpaceFile */
	public static function init($bucket, $key)
	{
		$obj		 = new SpaceFile();
		$obj->bucket = $bucket;
		$obj->key	 = $key;
		return $obj;
	}

	/** @param \SpacesAPI\File $file */
	public static function initFile($bucket, $file)
	{
		$obj				 = SpaceFile::init($bucket, $file->filename);
		$obj->lastModified	 = $file->last_modified;
		$obj->contentType	 = $file->content_type;
		$obj->expiredAt		 = $file->expiration;
		$obj->isPublic		 = $file->isPublic();
		return $obj;
	}

	/** @return SpaceFile */
	public static function populate($jsonString)
	{
		$json		 = json_decode($jsonString);
		$jsonMapper	 = new \JsonMapper();

		/** @var SpaceFile $obj */
		$obj = $jsonMapper->map($json, new SpaceFile());
		return $obj;
	}

	public function addURL($url, $validTill = null, $isSigned = null)
	{
		$urlObj				 = new SpaceFileURL();
		$urlObj->url		 = $url;
		$urlObj->validTill	 = $validTill;
		$urlObj->isSigned	 = $isSigned;
		$timestamp			 = [];
		if ($validTill != null)
		{
			$timestamp = strtotime($validTill);
		}
		$this->urls[] = $urlObj;
		$this->removeExpiredUrls();
	}

	public function removeExpiredUrls()
	{
		$arrObj	 = [];
		$arrURL	 = [];
		foreach ($this->urls as $objURL)
		{
			if (in_array($objURL->url, $arrURL))
			{
				continue;
			}
			if ($objURL->validTill == null || $objURL->validTill > strtotime())
			{
				$arrURL[]			 = $objURL->url;
				$validTill			 = $objURL->validTill;
				$timeDiff			 = $validTill - strtotime();
				$arrObj[$timeDiff]	 = $objURL;
			}
		}
		ksort($arrObj);
		$this->urls = array_values($arrObj);
	}

	public function toJSON()
	{
		$arr = \CJSON::decode(json_encode($this));
		$arr = \Filter::removeNull($arr);
		return json_encode($arr);
	}

	/** @return \SpacesAPI\Space */
	public function getSpace($refetch = false)
	{
		if ($this->bucket != null && ($this->space == null || $refetch))
		{
			$this->space = \Storage::getSpace($this->bucket, false);
		}
		return $this->space;
	}

	/** @return \SpacesAPI\File */
	public function getFile($refetch = false)
	{
		if ($this->file !== null && !$refetch)
		{
			return $this->file;
		}

		$space = \Storage::getSpace($this->bucket, false);
		if ($space != null)
		{
			$this->file = \Storage::getSpace($this->bucket, false)->file($this->key);
		}

		return $this->file;
	}

	public function getURL($validTill = null)
	{
		if ($validTill == null)
		{
			$validTill = strtotime("+24 hour");
		}
		$url = null;
		foreach ($this->urls as $sfURL)
		{
			if ($sfURL->validTill == null || $sfURL->validTill > strtotime("+5 minute"))
			{
				$url = $sfURL->url;
				break;
			}
		}

		if ($url != null)
		{
			goto end;
		}
		$file	 = $this->getFile();
		$signed	 = false;
		if (!$file->isPublic())
		{
			$url	 = $file->getSignedURL($validTill);
			$signed	 = true;
		}
		else
		{
			$url		 = $file->getURL();
			$validTill	 = null;
		}
		$this->_urlCreated = true;
		$this->addURL($url, $validTill, $signed);
		end:
		return $url;
	}

	public function isURLCreated()
	{
		return $this->_urlCreated;
	}

	public function isExist()
	{
		$success = false;

		$file = $this->getFile();
		if ($file != null)
		{
			$success = true;
		}

		return $success;
	}

}

class SpaceFileURL
{

	public $url, $validTill,
			$isSigned;

}
