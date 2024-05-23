<?php
use Ramsey\Uuid\Uuid;
class Ramsey
{
	public static function init()
	{
		return $uuid = Uuid::uuid4();
	}

}

?>