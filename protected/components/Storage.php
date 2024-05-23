<?php

use Aws\S3\S3Client;

/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * @property Storage $name Description
 * @property Aws\S3\S3Client $name Description
 */
class Storage
{

	private static $_instance	 = null;
	private static $_spaces		 = [];
	private static $_retry		 = 0;

	/**
	 * @return SpacesS3
	 */
	public static function getInstance()
	{
		if (self::$_instance == null)
		{
			self::$_instance = new \SpacesAPI\Spaces('PPV6F2UL4LN3SQOEMZDD', '/IIzljX4OlqmK0Pj1OU9LEYBFurxmWS2tRbM3iZkjrI', 'sgp1');
		}
		return self::$_instance;
	}

	/** @return \SpacesAPI\Space */
	public static function createSpace($name, $public = false)
	{
		try
		{
			$result = self::getInstance()->create($name, $public);
		}
		catch (Exception $e)
		{
			Logger::exception($e);
		}
		return $result;
	}

	/** @return \SpacesAPI\Space|null */
	public static function getSpace($name, $createIfNotExist = true, $public = false, $refetch = false)
	{
		try
		{
			if (array_key_exists($name, self::$_spaces) && !$refetch)
			{
				$result = self::$_spaces[$name];
				goto end;
			}
			$result = self::getInstance()->space($name);
		}
		catch (\SpacesAPI\Exceptions\SpaceDoesntExistException $e)
		{
			if ($createIfNotExist)
			{
				$result = self::createSpace($name, $public);
			}
			else
			{
				Logger::exception($e);
			}
		}
		catch (Exception $e)
		{
			Logger::exception($e);
		}

		if ($result != null)
		{
			self::$_spaces[$name] = $result;
		}

		end:
		return $result;
	}

	public static function getBucketNameFromPath($path)
	{
		$prefix	 = ROOT_PATH;
		$str	 = preg_replace('/^' . preg_quote($prefix) . '/', '', $path);
		if (substr($str, 0, 1) == DIRECTORY_SEPARATOR)
		{
			$str = substr($str, 1);
		}
		$str = preg_replace("/[^A-Za-z0-9 ]/", '-', $str);
		return $str;
	}

	public static function getRelativePath($path)
	{
		$prefix	 = ROOT_PATH;
		$str	 = preg_replace('/^' . preg_quote($prefix) . '/', '', $path);
		if (substr($str, 0, 1) == DIRECTORY_SEPARATOR)
		{
			$str = substr($str, 1);
		}
		$ds	 = DIRECTORY_SEPARATOR;
		$str = preg_replace("/[^A-Za-z0-9 \/\\", '-', $str);
		$str = str_replace(DIRECTORY_SEPARATOR, "/", $str);
		return $str;
	}

	/**
	 * @param \SpacesAPI\Space $space 
	 * @return SpacesAPI\File */
	public static function getFile($space, $key)
	{
		$file = null;
		try
		{
			if ($space != null)
			{
				$file = $space->file($key);
			}
		}
		catch (Exception $exc)
		{
			ReturnSet::setException(new Exception($exc->getMessage(), ReturnSet::ERROR_NO_RECORDS_FOUND));
		}
		return $file;
	}

	/** @param \SpacesAPI\Space $space */
	public static function removeFile($space, $key)
	{
		$success = false;
		try
		{
			$file	 = $space->file($key);
			$file->delete();
			$success = true;
		}
		catch (Exception $exc)
		{
			ReturnSet::setException(new Exception($exc->getMessage(), ReturnSet::ERROR_NO_RECORDS_FOUND));
		}

		return $success;
	}

	/**
	 * @param \SpacesAPI\Space $space
	 * @param string $key
	 * @param string $localFile Local File Path
	 * @return Stub\common\SpaceFile
	 */
	public static function uploadText($space, $key, $localFile, $removeLocal = true)
	{
		$spaceFile		 = $key;
		$spaceFileObject = null;
		try
		{
			if (!file_exists($localFile))
			{
				throw new Exception("File ({$localFile}) does not exist.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$text			 = file_get_contents($localFile);
			$file			 = $space->uploadText($text, $spaceFile);
			$spaceFileObject = Stub\common\SpaceFile::initFile($space->getName(), $file);
			if ($file != null && $removeLocal == true)
			{
				unlink($localFile);
			}
			self::$_retry = 0;
		}
		catch (\Aws\S3\Exception\S3Exception $exc)
		{
			if (self::$_retry == 0)
			{
				self::$_retry++;
				$spaceFileObject = self::uploadText($space, $key, $localFile, $removeLocal);
			}
			else
			{
				ReturnSet::setException(new Exception("Failed to upload ({$exc->getAwsErrorCode()}):\n\t{$exc->getTraceAsString()}", ReturnSet::ERROR_NO_RECORDS_FOUND));
			}
		}
		catch (SpacesAPI\Exceptions\FileDoesntExistException $exc)
		{
			ReturnSet::setException(new Exception("Space File ({$spaceFile}) doesn't exist", ReturnSet::ERROR_NO_RECORDS_FOUND));
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFileObject;
	}

	/**
	 * @param \SpacesAPI\Space $space
	 * @param string $key
	 * @param string $localFile Local File Path
	 * @return Stub\common\SpaceFile
	 */
	public static function uploadFile($space, $key, $localFile, $removeLocal = true)
	{
		$key = trim($key, "/");

		$spaceFileObject = null;
		try
		{
			if (!file_exists($localFile))
			{
				throw new Exception("File ({$localFile}) does not exist.", ReturnSet::ERROR_NO_RECORDS_FOUND);
			}

			$file			 = $space->uploadFile($localFile, $key);
			$spaceFileObject = Stub\common\SpaceFile::initFile($space->getName(), $file);

			if ($file != null && $removeLocal == true)
			{
				unlink($localFile);
			}
		}
		catch (SpacesAPI\Exceptions\FileDoesntExistException $exc)
		{
			ReturnSet::setException(new Exception("Space File ({$key}) doesn't exist", ReturnSet::ERROR_NO_RECORDS_FOUND));
		}
		catch (Exception $exc)
		{
			ReturnSet::setException($exc);
		}
		return $spaceFileObject;
	}

	public static function getMailSpace()
	{
		return self::getSpace(self::getPrefixSpace() . "gozo-files", true, false);
	}

	public static function getAgentAgreementSpace()
	{
		return self::getSpace(self::getPrefixSpace() . "gozo-docs", true, false);
	}

	public static function getDocumentSpace()
	{
		return self::getSpace(self::getPrefixSpace() . "gozo-docs", true, false);
	}

	public static function getQrSpace()
	{
		return self::getSpace(self::getPrefixSpace() . "gozo-qrcode", true, false);
	}

	public static function getBookingSpace()
	{
		return self::getSpace(self::getPrefixSpace() . "gozo-bookings", true, false);
	}

	public static function getPartnerAPISpace()
	{
		return self::getSpace(self::getPrefixSpace() . "gozo-partners-api", true, false);
	}
	
	public static function getOperatorAPISpace()
	{
		return self::getSpace(self::getPrefixSpace() . "gozo-operator-api", true, false);
	}

	public static function getVehicleDocSpace()
	{
		return self::getSpace(self::getPrefixSpace() . "gozo-vhc-docs", true, false);
	}

	public static function getAudioDocSpace()
	{
		return self::getSpace(self::getPrefixSpace() . "gozo-call-audio", true, false);
	}
        
	public static function getCallBackDocSpace()
	{
		return self::getSpace(self::getPrefixSpace() . "gozo-scq-docs", true, false);
	}
	
	public static function getGPXFileSpace()
	{
		return self::getSpace(self::getPrefixSpace() . "gozo-gpx", true, false);
	}

	public static function getPrefixSpace()
	{
		$prefix = "";
		if (APPLICATION_ENV != 'production')
		{
			$prefix = "dev-";
		}

		return $prefix;
	}

}
