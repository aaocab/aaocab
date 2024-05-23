<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>	
		
<?
$response	 = Contact::referenceUserData($model->bkgUserInfo->bui_id, 3);
if ($response->getStatus())
{
	$contactNo	 = $response->getData()->phone['number'];
	$countryCode	 = $response->getData()->phone['ext'];
	$email		 = $response->getData()->email['email'];
}
if ($isredirct != 1 )
{
	?>
			
<? }?>			
			
			
                        <div class="demo-header header-line-1 header-logo-app mb0">
                            <a href="Javascript:void(0)" onclick="$jsBookNow.goToPrevTab(<?php echo $prevStep; ?>);" class="header-logo-title" style="white-space: nowrap">Confirm booking</a>
                            <a href="Javascript:void(0)" onclick="$jsBookNow.goToPrevTab(<?php echo $prevStep; ?>);" class="header-icon header-icon-1"><i class="fa fa-angle-left"></i></a>
                        </div>
<?php $this->renderPartial("bkBanner", ['model' => $model]); ?>
