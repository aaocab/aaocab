<?php

namespace Stub\vendor;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CustomQuoteList
{

	public $dataList, $count, $pageSize, $currentPage;

	public function getListData($quotes,   $totalCount,$pageSize, $pageCount)
	{
		$this->totalCount	 = (int)$totalCount;
		$this->pageSize		 = (int)$pageSize;
		$this->currentPage	 = (int)$pageCount;
		foreach ($quotes as $quote)
		{
			$obj				 = new \Stub\vendor\CustomQuote();
			$obj->fillData($quote);
			$this->dataList[]	 = $obj;
		}
	}

}
