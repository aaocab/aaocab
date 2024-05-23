<?php

namespace Stub\common;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Qr
{

	public $id, $name, $entityType, $entityId, $entityName, $qr_contact_name, $qr_contact_number, $qr_email;

	public function set($dataArr)
	{

		foreach ($dataArr as $row)
		{
			$obj = new \Stub\common\Qr();

			$obj->fillData($row);
			$this->data[] = $obj;
		}
		return $this->data;
	}

	public function fillData($row)
	{
		$this->id	 = (int) $row['qrc_id'];
		$link		 = "https://gozo.cab/c/";
		$this->text	 = $row['qrc_code'];
		$this->url	 = $link . $row['qrc_code'];
	}

	public function showAllocatedTo($data, $price, $id)
	{
		$res->entityType	 = (int) $data['qrc_ent_type'];
		$res->entityId		 = (int) $data['qrc_ent_id'];
		$res->is_activated	 = ($data['qrc_status'] == 3 ? true : false);
		$res->qr_id			 = (int) $id;

		switch ((int) $data['qrc_ent_type'])
		{
			case 1:
				$model = \Users::findByPK($data['qrc_ent_id']);

				$res->entityName = $model->usr_name;
				break;
			case 2:
				if ($vendorsModel == null)
				{
					$vendorsModel	 = \Vendors::model()->findByPk($res->entityId);
					$res->entityName = $vendorsModel->vnd_name;
				}
				break;
			case 3:
				$model			 = \Drivers::model()->findByPk($res->entityId);
				$res->entityName = $model->drv_name;
				break;
			case 4:

				$model = \Admins::model()->findByPk($res->entityId);

				$res->entityName = $model->adm_fname . " " . $model->adm_lname;
				break;
			case 5:

				$agentmodel = \Agents::model()->getById($res->entityId);

				$res->entityName = $agentmodel['agt_fname'] . ' ' . $agentmodel['agt_lname'];
				break;
			default:
				break;
		}

		$res->instruction = "Let gozospot owner know that people can book cabs by scanning this code. Everytime a customer books a cab, we will credit gozo points worth ₹100 to the gozospot owner account. Once the gozo spot agent has accumulated ₹1000 they can request payment to be sent to them via UPI / other. Gozo spot agent shoud consider this as our rent payment for letting us put the sticker at your business location";

		return $res;
	}

	public static function setData($value)
	{
		$obj					 = new Qr();
		$obj->qr_contact_name	 = trim($value['ctt_first_name']) . ' ' . trim($value['ctt_last_name']);
		$obj->qr_contact_number	 = trim($value['phn_phone_country_code']) . trim($value['phn_phone_no']);
		$obj->qr_email			 = trim($value['eml_email_address']);
		return \Filter::removeNull($obj);
	}

}
