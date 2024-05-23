<?php

class CityCommand extends BaseCommand
{

    public function actionUpdateNearestCityPlace()
    {
        $sql  = "SELECT cities.cty_id FROM `cities` WHERE cities.cty_active=1 AND cities.cty_is_airport <>1 LIMIT 0,10";
        $rows = Yii::app()->db->createCommand($sql)->queryAll();
        if (count($rows) > 0)
        {
            $ctr = 0;
            foreach ($rows as $row)
            {
                $success = CityMatchPlaces::model()->insertUpdateNearestCityPlace($row['cty_id']);
                $ctr++;
            }
        }
    }

    public function actionUpdateJSON()
    {
        $listener = new \JsonStreamingParser\Listener\GeoJsonListener(function ($item): void {

                    $arr                 = [];
                    $arr["type"]         = $item["type"];
                    $arr["geometryType"] = $item["geometry"]["type"];
                    $arr["geometryText"] = json_encode($item["geometry"]);
                    $properties          = $item["properties"];
                    $arr["properties"]   = json_encode($properties);
                    $arr["admin_level"]  = $properties["admin_level"];
                    $arr["name"]         = $properties["name"];
                    $arr["local_name"]   = $properties["local_name"];
                    $arr["name_en"]      = $properties["name_en"];
                    DBUtil::getINStatement($arr, $bindString, $params);
                    $sql                 = "INSERT INTO geojson1 (" . implode(",", array_keys($arr)) . ") VALUES ($bindString)";
                    DBUtil::execute($sql, $params);
                    //print_r($arr);
                    echo "\n\n";
                });
        $stream = fopen("D:\\data.gov\\GeoJSON-1405856708a8bf4733bdf9e472730c0c.geojson", 'r');
        try
        {
            $parser = new \JsonStreamingParser\Parser($stream, $listener);
            $parser->parse();
            fclose($stream);
        }
        catch (Exception $e)
        {
            fclose($stream);
            throw $e;
        }
    }

    public function actionUpdateGeoArea()
    {
        $rows = DBUtil::query("SELECT id FROM geojson1 WHERE geometryType='MultiPolygon'");

        foreach ($rows as $row)
        {
            $data     = DBUtil::queryRow("SELECT *, ROUND(ST_AREA(coordinates2)*111*111) as area2 FROM geojson1 WHERE id=:id", null, ["id" => $row["id"]]);
            $geo      = geoPHP::load($data["geometryText"]);
            $geometry = new \Spinen\Geometry\Geometry(new geoPHP(), new Spinen\Geometry\Support\TypeMapper());
            $obj      = $geometry->parseJson($data["geometryText"]);
            try
            {
                $area = abs(round($obj->getSquareMeters() / (1000 * 1000)));
                DBUtil::execute("UPDATE geojson1 SET `area`=$area WHERE id={$row['id']}");
                echo "SUCCESS::{$data['id']}::{$data["name"]}::{$data["area2"]}::{$area}\n";
            }
            catch (Exception $e)
            {
                echo $error = "SUCCESS::{$data['id']}::{$data["name"]}::{$data["area2"]}::" . $e->getMessage();
                Logger::error($error);
            }
        }
    }

    public function actionTopCitiesByBookingCount()
    {
        $citiesDetails = Cities::getTopCitiesByBookingCount();
        Yii::app()->cache->set(CacheDependency::buildCacheId(CacheDependency::Type_Cities), time());
        foreach ($citiesDetails as $value)
        {
            try
            {
                $localtionDetails = Location::getVendorDriverByLatLong($value['bkg_from_city_id'], $value['cty_lat'], $value['cty_long']);
                if ($localtionDetails['cntVendor'] > 0 || $localtionDetails['cntDriver'] > 0)
                {
                    Topbookingcities::add($value['bkg_from_city_id'], $value['cty_lat'], $value ['cty_long'], $localtionDetails['cntVendor'], $localtionDetails['cntDriver']);
                }
            }
            catch (Exception $ex)
            {
                Filter::writeToConsole($ex->getMessage());
                Logger::writeToConsole($ex->getMessage());
            }
        }
    }

    public function actionCityMaster()
    {
        $result = Cities::getAllCity();
        foreach ($result as $value)
        {
            try
            {
                $fromCity = $value['cty_id'];
                $region   = States::model()->getZoenId($fromCity);
                $state    = States::model()->getByCityId($fromCity);
                $zone     = Zones::model()->getByCityId($fromCity);
                $mzone    = ZoneCities::model()->getMZoneByCityId($fromCity);
                $model    = CityMaster::model()->getByCity($fromCity);
                if (!$model)
                {
                    $model                  = new CityMaster();
                    $model->ctm_city_id     = $fromCity;
                    $model->ctm_create_date = DBUtil::getCurrentTime();
                }
                $model->ctm_state_id    = $state;
                $model->ctm_region_id   = $region;
                $model->ctm_zone_id     = $zone;
                $model->ctm_mzone_id    = $mzone;
                $model->ctm_active      = 1;
                $model->ctm_update_date = DBUtil::getCurrentTime();
                if (!$model->save())
                {
                    throw new Exception(json_encode($model->errors), ReturnSet::ERROR_INVALID_DATA);
                }
            }
            catch (Exception $ex)
            {
                Logger::writeToConsole($ex->getMessage());
            }
        }
    }

	public function actionAutocompleteTransfer()
	{
		$check = Filter::checkProcess("autocomplete address");
		if (!$check)
		{
			return;
		}

		Autocomplete::importFromMaster();
	}

}
