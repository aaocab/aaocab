<?php

namespace Stub\common;

class CancellationList
{

	public $id;
	public $text;
	public $placeholder;

	public function setData($model)
	{
		$this->id			 = $model['id'];
		$this->text			 = $model['text'];
		$this->placeholder	 = $model['placeholder'];
	}

}
