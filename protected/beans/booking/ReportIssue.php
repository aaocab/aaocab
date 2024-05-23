<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

namespace Beans\booking;

class ReportIssue
{

	public $id;
	public $type;
	public $name;
	public $desc;
	public $render;

	/** @var \Beans\Booking $booking */
	public $booking;

	/**
	 * 
	 * @param  $obj
	 * @return \ReportIssue
	 */
	public static function setModelData($obj)
	{
		$model = \ReportIssue::model()->findByPk($obj->id);
		if (!$model)
		{
			$model						 = new \ReportIssue();
			$model->rpi_id				 = $obj->id;
		}
		$model->rpi_type			 = $obj->type;
		$model->rpi_name			 = $obj->name;
		$model->report_issue_desc	 = $obj->desc;
		return $model;
	}

	/**
	 * 
	 * @param integer $obj
	 * @return boolean|model
	 */
	public static function setData($obj)
	{
		$id		 = $obj->booking->id;
		$model	 = \Booking::model()->findByPk($id);
		if (!$model)
		{
			return false;
		}
		return $model;
	}

	/**
	 * 
	 * @return Array
	 */
	public static function getData()
	{
		$data		 = [];
		$reportIssue = \ReportIssue::getType();
		$reportIssue = json_decode($reportIssue, true);
		foreach ($reportIssue as $issueKey => $reportIssue)
		{
			$items			 = [];
			$reportDetails	 = \ReportIssue::getDetails($issueKey);
			$reportDetails	 = json_decode($reportDetails, true);
			foreach ($reportDetails as $issueDetailKey => $reportIssueDetail)
			{
				$res	 = \ReportIssue::getResponseByIssueId($issueDetailKey);
				$items[] = ['id' => (int) $issueDetailKey, 'name' => $reportIssueDetail, 'render' => $res];
			}
			$data[] = ['id' => (int) $issueKey, 'name' => $reportIssue, 'items' => $items];
		}
		return $data;
	}

}
