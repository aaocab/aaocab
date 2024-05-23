<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Beans\common;

/**
 * Description of PageRef
 *
 * @property int $pageSize
 * @property int $pageCount 
 * @author Deepak
 */
class PageRef
{

	public $pageSize;
	public $pageCount;

	public function init($obj = null)
	{
		return $this->fillData($obj);
	}

	public function fillData($row)
	{
		$this->pageSize	 = ($row->pageSize > 0) ? $row->pageSize : 20;
		$this->pageCount = ($row->pageCount > 0) ? $row->pageCount : 0;
	}

	public static function getDefault($row, $size = 20)
	{
		$obj = new PageRef($row);
		$obj->pageSize	 = ($row->pageSize > 0) ? $row->pageSize : $size;
		$obj->pageCount	 = ($row->pageCount > 0) ? $row->pageCount : 0;
		return $obj;
	}

}
