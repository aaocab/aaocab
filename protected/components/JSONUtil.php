<?php

/**
 * Description of JSONUtil
 *
 * @link http://www.yiiframework.com/forum/index.php/topic/41922-convert-model-with-relations-to-php-array-and-json/
 * @author
 */
class JSONUtil
{

	/**
	 * Converting a Yii model with all relations to a an array.
	 * @param mixed $models A single model or an array of models for converting to array.
	 * @param array $filterAttributes should be like array('table name'=>'column names','user'=>'id,firstname,lastname'
	 * 'comment'=>'*') to filter attributes. Also can use alias for column names by using AS with the column name just
	 * like in SQL.
	 * @param array $ignoreRelations an array contains the model names in relations that will not be converted to array
	 * @return array array of converted model with all related relations.
	 */
	public static function convertModelToArray($models, array $filterAttributes = null, array $ignoreRelations = array())
	{
		if ((!is_array($models)) && (is_null($models)))
			return null;

		if (is_array($models))
			$arrayMode = TRUE;
		else
		{
			$models		 = array($models);
			$arrayMode	 = FALSE;
		}

		$result = array();
		foreach ($models as $model)
		{
			$arrProperty = get_object_vars($model);
			$attributes	 = $model->getAttributes();

			if (isset($filterAttributes) && is_array($filterAttributes))
			{
				foreach ($filterAttributes as $key => $value)
				{

					if (strtolower($key) == strtolower($model->tableName()))
					{
						$arrColumn = explode(",", $value);

						if (strpos($value, '*') === FALSE)
						{
							$attributes = array();
						}

						foreach ($arrColumn as $column)
						{
							$columnNameAlias = array_map('trim', preg_split("/[aA][sS]/", $column));

							$columnName	 = '';
							$columnAlias = '';

							if (count($columnNameAlias) === 2)
							{
								$columnName	 = $columnNameAlias[0];
								$columnAlias = $columnNameAlias[1];
							}
							else
							{
								$columnName = $columnNameAlias[0];
							}

							if (($columnName != '') && ($column != '*'))
							{
								if ($columnAlias !== '')
								{
									$attributes[$columnAlias] = $model->$columnName;
								}
								else
								{
									$attributes[$columnName] = $model->$columnName;
								}
							}
						}
					}
				}
			}
			$attributes	 = array_merge($arrProperty, $attributes);
			$relations	 = array();
			$key_ignores = array();

			if ($modelClass = get_class($model))
			{
				if (array_key_exists($modelClass, $ignoreRelations))
				{
					$key_ignores = explode(',', $ignoreRelations[$modelClass]);
				}
			}

			foreach ($model->relations() as $key => $related)
			{

				if ($model->hasRelated($key))
				{
					if (!in_array($key, $key_ignores))
						$relations[$key] = $model->$key instanceof CActiveRecord ? self::convertModelToArray($model->$key, $filterAttributes, $ignoreRelations) : $model->$key;
				}
			}
			$all = array_merge($attributes, $relations);

			if ($arrayMode)
				array_push($result, $all);
			else
				$result = $all;
		}
		return $result;
	}

}

?>