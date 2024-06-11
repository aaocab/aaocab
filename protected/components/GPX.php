<?php

use phpGPX\Models\GpxFile;
use phpGPX\Models\Link;
use phpGPX\Models\Metadata;
use phpGPX\Models\Point;
use phpGPX\Models\Segment;
use phpGPX\Models\Track;

class GPX
{

	public static function create($points, $params)
	{
		if (count($points) <= 0)
		{
			return false;
		}

		$bkgId				 = $params['bkgId'];
		$bookingId			 = $params['bookingId'];
		$bkgPickupDate		 = $params['pickupDate'];
		$arrRouteCityNames	 = (($params['routeCityNames'] != '') ? json_decode($params['routeCityNames']) : '');

		$routeCityNames = implode(' - ', $arrRouteCityNames);

		// Creating sample link object for metadata
		$link		 = new Link();
		$link->href	 = Yii::app()->params['fullBaseURL'];
		$link->text	 = 'aaocab';

		// GpxFile contains data and handles serialization of objects
		$gpx_file = new GpxFile();

		// Creating sample Metadata object
		$gpx_file->metadata = new Metadata();

		// Time attribute is always \DateTime object!
		$gpx_file->metadata->time = new \DateTime();

		// Description of GPX file
		$gpx_file->metadata->description = "aaocab: {$bookingId}";

		// Adding link created before to links array of metadata
		// Metadata of GPX file can contain more than one link
		$gpx_file->metadata->links[] = $link;

		// Creating track
		$track = new Track();

		// Name of track
		$track->name = $routeCityNames;

		// Type of data stored in track
		$track->type = 'DRIVE';

		// Source of GPS coordinates
		$track->source = "Mobile Device";

		// WayPoints
		$wp = self::getPoints($points, Point::WAYPOINT);
		if (count($wp) > 0)
		{
			$gpx_file->waypoints = $wp;
		}

		// Creating Track segment
		$segment = new Segment();

		// TrackPoints
		$tp = self::getPoints($points, Point::TRACKPOINT);

		$segment->points = $tp;

		// Add segment to segment array of track
		$track->segments[] = $segment;

		// Add track to file
		$gpx_file->tracks[] = $track;

		// File
		$fileName	 = $bookingId . '.gpx';
		$dirPath	 = self::getFilePath($bkgId, $bkgPickupDate);
		$fullPath	 = Yii::app()->basePath . $dirPath;

		// Folder
		$folder = Filter::createFolderPath($fullPath);
		if ($folder)
		{
			$returnFilePath	 = $dirPath . $fileName;
			$filePath		 = $fullPath . $fileName;

			// GPX output
			$gpx_file->save($filePath, \phpGPX\phpGPX::XML_FORMAT);
			return $returnFilePath;
		}

		return false;
	}

	public static function getFilePath($bkgId, $bkgPickupDate)
	{
		$dirFinal = Filter::getBookingFilePath(Config::getServerID(), 'bookings', $bkgId, $bkgPickupDate);

		return $dirFinal;
	}

	public static function getPoints($points, $type)
	{
		$arrPoints = [];
		foreach ($points as $point)
		{
			if ($point['name'] != '' || $type == Point::TRACKPOINT)
			{
				$objPoint			 = new Point($type);
				$objPoint->latitude	 = $point['latitude'];
				$objPoint->longitude = $point['longitude'];
				$objPoint->time		 = new \DateTime($point['time']);
				$objPoint->name		 = $point['name'];

				$arrPoints[] = $objPoint;
			}
		}

		return $arrPoints;
	}

}
