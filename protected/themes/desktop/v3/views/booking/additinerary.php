
<?php
/* @var $model BookingRoute */
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/v3/bookingRoute.js?v=$version");

$selectizeOptions = ['create'								 => false,
	'persist'								 => true,
	'selectOnTab'							 => true,
	'createOnBlur'							 => true,
	'dropdownParent'						 => 'body',
	'optg$selectizeOptionsroupValueField'	 => 'id',
	'optgroupLabelField'					 => 'text',
	'optgroupField'							 => 'id',
	'openOnFocus'							 => true,
	'preload'								 => false,
	'labelField'							 => 'text',
	'valueField'							 => 'id',
	'searchField'							 => 'text',
	'closeAfterSelect'						 => true,
	'addPrecedence'							 => false,
	];
if ($sourceCity == "")
{
	$cityList	 = Cities::model()->getJSONAirportCitiesAll();
	$pcityList	 = $cityList;
}
else
{
	$model->brt_from_city_id = $sourceCity;
	$cmodel					 = Cities::model()->getDetails($sourceCity);
	$sourceCityName			 = $cmodel->cty_name . ', ' . $cmodel->ctyState->stt_name;
	$pcityList				 = Cities::model()->getJSONNearestAll($previousCity);
}
if ($model->brt_from_city_id != '')
{
	$cityList = Cities::model()->getJSONNearestAll($model->brt_from_city_id);
}
$sourceDivClass	 = 'col';
$dateDivClass	 = 'col';
if ($btype == 2)
{
	$sourceDivClass	 = 'col-md-6';
	$dateDivClass	 = 'col-md-12';
}
if ($btype == 3)
{
	$mcitiesDiv = "  col-md-4";
}
//echo $model->estArrTime[$index];
$ctr	 = rand(0, 99) . date('mdhis');
$btype	 = ($btype == 0) ? $bmodel->bkg_booking_type : $btype;
?>

<?php
//echo $cookieActive;
        if (Yii::app()->request->cookies->contains('itineraryCookie'))
       {

           $var     = Yii::app()->request->cookies['itineraryCookie']->value;
//           echo "<pre>";
//           print_r($var);
           
           
           $dateVar = explode(" ", Filter::getDateFormatted($var->pickupTime));
           if ($cookieActive != false)
    {
        if ($var->source->city->id > 0)
        {
            $cookieSourceCity = ($var->source->city->id > 0) ? ($var->source->city->id) : ($var[0]->source->city->id);
        }
        else
        {
            $cookieSourceCity = 0;
        }
        if ($var->destination->city->id > 0)
        {
            $cookieDestinationCity = ($var->destination->city->id > 0) ? ($var->destination->city->id) : ($var[0]->destination->city->id);
        }
        else
        {
            $cookieDestinationCity = 0;
        }
    }
    
    
    //echo $cookieDestinationCity;
}
?>





 <input type="hidden" name="cookieDate" id="cookieDate" value="<?php if($cookieActive!= false){echo $dateVar[0];}?>">
<?php
if ($btype == 7)
{

	$minDate = ($model->brt_min_date != '') ? $model->brt_min_date : date('Y-m-d');
	$this->renderPartial("bkTypeShuttle", ['model' => $model, 'minDate' => $minDate, 'ctr' => $ctr]);
}
else if ($btype == 1 && ($bmodel->bkg_transfer_type == 1 || $bmodel->bkg_transfer_type == 2))
{
	$this->renderPartial('bkTypeAirportTransferOutstation', ['brtRoute' => $model, 'transfertype' => $bmodel->bkg_transfer_type, 'form' => $form, 'selectizeOptions' => $selectizeOptions, 'tncArr' => $tncArr], false, false);
}
else if ($btype == 1)
{
	$this->renderPartial("bkTypeOneWay", ['model' => $model, 'ctr' => $ctr, 'tncArr' => $tncArr ,'cookieSourceCity'=>$cookieSourceCity ,'cookieDestinationCity'=>$cookieDestinationCity]);
}
else if ($btype == 2)
{
	$this->renderPartial("bkTypeRoundWay", ['model' => $model, 'ctr' => $ctr, 'btype' => $btype, 'arvlcnt' => $arvlcnt, 'tncArr' => $tncArr,'cookieSourceCity'=>$cookieSourceCity ,'cookieDestinationCity'=>$cookieDestinationCity]);
}
else if ($btype == 3)
{
	$this->renderPartial("bkTypeMultiWay", ['model' => $model, 'ctr' => $ctr, 'btype' => $btype, 'tncArr' => $tncArr,'cookieSourceCity'=>$cookieSourceCity ,'cookieDestinationCity'=>$cookieDestinationCity]);
}
else if ($btype == 9 || $btype == 10 || $btype == 11)
{
	$this->renderPartial('bkTypeDayRental', ['model' => $model, 'btype' => $btype, 'pcityList' => $pcityList, 'cityList' => $cityList, 'index' => 0, 'bkgTempModel' => $bkgTempModel, 'form' => $form, 'selectizeOptions' => $selectizeOptions, 'tncArr' => $tncArr,'cookieSourceCity'=>$cookieSourceCity ,'cookieDestinationCity'=>$cookieDestinationCity], false, false);
}
else if ($btype == 4 || $btype == 12)
{
	$this->renderPartial('bkTypeAirportTransfer', ['brtRoute' => $model, 'transfertype' => $bmodel->bkg_transfer_type, 'pcityList' => $pcityList, 'cityList' => $cityList, 'btype' => $btype, 'index' => 0, 'bkgTempModel' => $bkgTempModel, 'form' => $form, 'selectizeOptions' => $selectizeOptions, 'tncArr' => $tncArr,'isAgent'=>$isAgent], false, false);
}
else if ($btype == 14)
{

	$this->renderPartial('bkTypePointToPoint', ['brtRoute' => $model, 'pcityList' => $pcityList, 'cityList' => $cityList, 'btype' => $btype, 'index' => 0, 'bkgTempModel' => $bkgTempModel, 'form' => $form, 'selectizeOptions' => $selectizeOptions, 'tncArr' => $tncArr,'bmodel' => $bmodel], false, false);
}
else if ($btype == 15)
{
	$this->renderPartial('bkTypeRailwayBusTransfer', ['brtRoute' => $model, 'transfertype' => $bmodel->bkg_transfer_type, 'pcityList' => $pcityList, 'cityList' => $cityList, 'btype' => $btype, 'index' => 0, 'bkgTempModel' => $bkgTempModel, 'form' => $form, 'selectizeOptions' => $selectizeOptions, 'tncArr' => $tncArr], false, false);
}
?>

<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
<script>
    $(document).ready(function()
	{	
              
        var date1 = new Date("<?php echo Filter::getDBDateTime(); ?>");
        var date2 = new Date("<?php echo $var->pickupTime; ?>");
        var diffDays = parseInt((date2 - date1) / (1000 * 60 * 60 * 24), 10); 

       var btype = <?=$btype;?>;
    //    && btype!=3
        if(diffDays>0)
        {
        $('input[name="BookingRoute[brt_pickup_date_date]"]').val('<?php echo $dateVar[0]; ?>');       
        }   
        });
    </script>