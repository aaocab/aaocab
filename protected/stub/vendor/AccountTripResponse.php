<?php

namespace Stub\vendor;

/**
 * Description of Account trip Response
 *
 *
 */
class AccountTripResponse
{

	public $bank_charge;
	public $act_date;
	public $act_created;
	public $act_id;
	public $adt_id;
	public $adt_ledger_id;
	public $ven_trans_remarks;
	public $ledgerIds;
	public $ledgerNames;
	public $entityType;
	public $ven_trans_amount;
	public $bkg_pickup_date;
	public $bkg_advance_amount;
	public $adm_name;
	public $from_city;
	public $openBalance;
	public $vnd_security_amount;
	public $vendor_amount;
	public $locked_amount;
	public $vnp_is_freeze;
	public $vnd_active;
	public $vnd_name;
	public $vnd_code;
	public $withdrawable_balance;
	public $vendor_amount_type;
	public $dataList;
	public $vendorAmount;

	public function setData($tripArr, $vendorAmount)
	{

		foreach ($tripArr as $row)
		{
			$obj				 = new \Stub\vendor\AccountTripResponse();
			$obj->fillModelData($row);
			$this->dataList[]	 = $obj;
		}
		$this->vendorAmount				 = new \Stub\vendor\AccountTripResponse();
		$this->vendorAmount->amount($vendorAmount);
	}

	public function fillModelData($row)
	{
		$this->bank_charge			 = $row['bank_charge'];
		$this->act_created			 = $row['act_created'];
		$this->act_date				 = $row['act_date'];
		$this->act_id				 = $row['act_id'];
		$this->adm_name				 = $row['adm_name'];
		$this->adt_id				 = $row['adt_id'];
		$this->adt_ledger_id		 = $row['adt_ledger_id'];
		$this->bank_charge			 = $row['bank_charge'];
		$this->bkg_advance_amount	 = $row['bkg_advance_amount'];
		$this->bkg_pickup_date		 = $row['bkg_pickup_date'];
		$this->entityType			 = $row['entityType'];
		$this->from_city			 = $row['from_city'];
		$this->ledgerIds			 = $row['ledgerIds'];
		$this->ledgerNames			 = $row['ledgerNames'];
		$this->locked_amount		 = $row['locked_amount'];
		$this->openBalance			 = $row['openBalance'];
		$this->ven_trans_amount		 = $row['ven_trans_amount'];
		$this->ven_trans_remarks	 = $row['ven_trans_remarks'];
	}

	public function amount($vendorAmount)
	{
		$this->vnd_security_amount=$vendorAmount['vnd_security_amount'];
		$this->vendor_amount=$vendorAmount['vendor_amount'];
		$this->locked_amount=$vendorAmount['locked_amount'];
		$this->vendor = new \Stub\common\Vendor();
        $this->vendor->basicVendorData($vendorAmount);
		$this->withdrawable_balance=$vendorAmount['withdrawable_balance'];
		$this->vendor_amount_type=$vendorAmount['vendor_amount_type'];

	}

}
