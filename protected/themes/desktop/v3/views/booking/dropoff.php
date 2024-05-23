<style>
.modal-backdrop { z-index: 1;}
.modal{ z-index: 9;}
</style>
<?
//$api			 = Yii::app()->params['googleBrowserApiKey'];
$api				 = Config::getGoogleApiKey('browserapikey');
?>
<!--<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>-->
<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/v3/hyperLocation.js?v=$version");
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/jquery.disableAutoFill.min.js');
?>

<?php
//$user				 = $model->bkgUserInfo->bkg_user_id;
if ($user == "")
{
	$user = UserInfo::getUserId();
}
$i				 = 0;
$requiredFields	 = [];
foreach (json_decode($brtRoutes) as $brtRoute)
{
	$routeModel	 = new BookingRoute();
	$ctr		 = ($brtRoute->brt_id > 0) ? $brtRoute->brt_id : $i;
	$ctr		 = $ctr + 1;
?>


<div class="col-12 <?= $gnowPickupHide ?>">
	

		<?php
										if ($i == 0)
										{

											$requiredFields[]	 = CHtml::activeId($brtRoute, "[" . ($ctr) . "]to_place");
											?>
	<div class="<?= $gnowPickupHide ?>">

				<fieldset class="form-group position-relative mb0">

					<?php
					$requiredFields[]	 = CHtml::activeId($brtRoute, "[$ctr]to_place");
					$this->widget('application.widgets.PlaceAddressGenie',
						['model'		 => $routeModel,
							'attribute'	 => "[$ctr]to_place",
							'city'		 => $brtRoute->brt_to_city_id,
							"user"		 => $user,
							"divValue"	 => "BookingRoute_" . $ctr . "_to_place_".$i]);
					?>
					<div class="form-control-position" style="z-index: 99999;">
						<i class="fas fa-map-marker-alt"></i>
					</div>
				</fieldset>
				<div id="map-marker-content"></div>




			</div>
											<?php
								}}
									
											?>
		
	<script>



</script>
					