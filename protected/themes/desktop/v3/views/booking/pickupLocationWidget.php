<?php
$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'newAddressForm',
	'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class' => 'form-horizontal',
	),
		));
/* @var $form TbActiveForm */

/** @var Booking $model */
$brtRoutes = $model->bookingRoutes;

if ($model instanceof Booking)
{
	$user = $model->bkgUserInfo->bkg_user_id;
}
else if ($model instanceof BookingTemp)
{
	$user = $model->bkg_user_id;
}
if ($user == "")
{
	$user = UserInfo::getUserId();
}

$i				 = 0;
$requiredFields	 = [];
foreach ($brtRoutes as $brtRoute)
{
	$ctr = ($brtRoute->brt_id > 0) ? $brtRoute->brt_id : $i;
	if ($i == 0)
	{
		$requiredFields[]	 = CHtml::activeId($brtRoute, "[" . ($ctr) . "]from_place");
		?>
		<div class="row">
                        <div class="col-12 col-xl-6">
                            <label for="iconLeft">We need your pickup address <?//= $brtRoute->brtFromCity->cty_display_name ?></label>
                            <p class="mb5"><small class="form-text">Location</small></p>
                            <fieldset class="form-group position-relative has-icon-left">
<!--                                <input type="text" class="form-control" id="iconLeft" placeholder="Use my current location">-->
									<?php
										$requiredFields[]	 = CHtml::activeId($brtRoute, "[$ctr]from_place");
										$this->widget('application.widgets.PlaceAddress',
										  ['model' => $brtRoute, 'attribute' => "[$ctr]from_place", 'city' => $brtRoute->brt_from_city_id, "user" => $user]);
									?>
                                <div class="form-control-position">
<!--                                    <i class="fas fa-map-marker-alt"></i>-->
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-12 col-xl-6">
                            <label for="iconLeft">Your drop address <?//= $brtRoute->brtToCity->cty_display_name ?></label>
                            <p class="mb5"><small class="form-text">Location</small></p>
                            <fieldset class="form-group position-relative has-icon-left">
									<?php
										$this->widget('application.widgets.PlaceAddress',
										['model'		 => $brtRoute, 'attribute'	 => "[$ctr]to_place",
											'city'		 => $brtRoute->brt_to_city_id, "user"		 => $user]);
									?>	
                                <div class="form-control-position">
<!--                                    <i class="fas fa-map-marker-alt"></i>-->
                                </div>
                            </fieldset>
                        </div>
                    </div>


		<?php
	}
	?>

	<?php
	$i++;
}
$requiredFields[] = CHtml::activeId($brtRoute, "[" . ($ctr) . "]to_place");
?>

<?= $form->hiddenField($model, "bkg_id"); ?>
<?= $form->hiddenField($model, "hash", ['value' => Yii::app()->shortHash->hash($model->bkg_id)]); ?>

<?php $this->endWidget(); ?>
<a href="#" data-menu="map-marker" class="hide" id="booknow-map-marker"></a>

<script type="text/javascript">
	$(document).ready(function(){
		$('[data-toggle="tooltip"]').tooltip();   
	  });



    

</script>
