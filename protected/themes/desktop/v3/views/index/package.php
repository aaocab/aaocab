<style>
    .btn-2{ border-radius: 4px; text-transform: uppercase; border: none; color: #FFF; text-align: center; font-size: 12px; font-weight: 700; padding: 10px 10px;}
</style>
<div class="row title-widget">
	<div class="col-12">
		<div class="container">
			<?php echo $this->pageTitle; ?>
		</div>
	</div>
</div>
<div class="container mt30">
        <div class="row">
            <div class="col-12">
                <div class="row">
            <div class="col-12">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'driver-register-form', 'enableClientValidation' => FALSE,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'errorCssClass' => 'has-error'
                    ),
                    'enableAjaxValidation' => false,
                    'errorMessageCssClass' => 'help-block',
                    'htmlOptions' => array(
                        'class' => 'form-horizontal', 'enctype' => 'multipart/form-data', 'autocomplete' => "off",
                    ),
                ));
                /* @var $form CActiveForm */

                $selectizeOptions = ['create' => false, 'persist' => true, 'selectOnTab' => true,
                    'createOnBlur' => true, 'dropdownParent' => 'body',
                    'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField' => 'id',
                    'openOnFocus' => true, 'preload' => false,
                    'labelField' => 'text', 'valueField' => 'id', 'searchField' => 'text', 'closeAfterSelect' => true,
                    'addPrecedence' => false,];
                ?>



                <div class="row">
                    <div class="col-4"> 
                        <label>Going From</label>
                        <?php
                        $this->widget('ext.yii-selectize.YiiSelectize', array(
                            'model' => $model,
                            'attribute' => 'from_city',
                            'useWithBootstrap' => true,
                            "placeholder" => "Select City",
                            'fullWidth' => true,
                            'htmlOptions' => array('width' => '100%',
                            //  'id' => 'from_city_id1'
                            ),
                            'defaultOptions' => $selectizeOptions + array(
                        'onInitialize' => "js:function(){
				  populateSourceCityPackage(this, '{$model->from_city}');
								}",
                        'load' => "js:function(query, callback){
				loadSourceCityPackage(query, callback);
				}",
                        'render' => "js:{
				option: function(item, escape){
				return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
				},
				option_create: function(data, escape){
				return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
				}
				}",
                            ),
                        ));
                        ?>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                <label>Min No. of Nights</label>
                                <? echo $form->numberField($model, 'min_nights', ['placeholder' => "", 'width' => '10px;', 'min' => 0,'class' => 'form-control m0']); ?>
                            </div>
                            <div class="col-6">
                                <label>Max No. of Nights</label>
                                <? echo $form->numberField($model, 'max_nights', ['placeholder' => "", 'width' => '10px;', 'min' => 0,'class' => 'form-control m0']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-2 mt20"><input type="submit" class="btn-orange pl30 pr30" value="SEARCH"></div>
                </div>


            </div>
            <?php $this->endWidget(); ?>
            <div class="col-12">
                <div class="container" id="columns">
                    <div class="row flex">
                        <?php
//print_r($pmodel);
                        foreach ($pmodel as $pck) {
                            ?>
                            <div class="col-12 col-md-4 col-xl-3 mb30">
                                <div class="package-panel">

                                    <?
                                    if ($pck['pci_images'] != '') {
                                        ?>
                                        <div class="img-parent">
                                            <a href="packages/<?= $pck['pck_url'] ?>"><img src="<?= $pck['pci_images'] ?>" class="clickshow" width="100%" height="180"></a>
                                        </div>

    <? } ?>
                                    <?php
                                    if ($pck['prt_package_rate'] != '') {
                                        ?>
                                        <div class="p10 pb0" style="font-size: 16px !important;">
                                            <b>Package Starting rate at </b><span class='float-right pr20'> &#x20b9 <?= $quoteData[$pck['pck_id']]->routeRates->totalAmount; ?></span>
                                        </div> 
    <?php } ?>
                                    <h2 class="p10" style="font-size: 13px !important;">
                                        <a type="button" href="packages/<?= $pck['pck_url'] ?>"> <?= $pck['pck_name'] ?></a>

                                    <!--					<a type="button"   onclick="showDetails(<?= $pck['pck_id'] ?>)"> <?= $pck['pck_name'] ?></a>-->
                                    </h2>
                                    <div class="row m0 mb15"> 
                                        <div class="col-5 pr5 text-center"> 
                                            <div class=" Submit-button"> 
                                                <a href="packages/<?= $pck['pck_url'] ?>"><button type="button" class="btn-2 btn-orange-2 clickshow" >Show Details</button></a>
                                            </div></div>
                                        <div class="col-7 text-center pl5">
    <?php
    $pkid = $pck['pck_id'];
    $form = $this->beginWidget('CActiveForm', array(
        'id' => "book-package-form_$pkid", 'enableClientValidation' => true,
        'clientOptions' => array(
        ),
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // See class documentation of CActiveForm for details on this,
        // you need to use the performAjaxValidation()-method described there.
        'enableAjaxValidation' => false,
        'errorMessageCssClass' => 'help-block',
        'action' => '/bknw',
        'htmlOptions' => array(
            'class' => 'form-horizontal',
        ),
    ));
    /* @var $form CActiveForm */
    $ptimePackage = Yii::app()->params['defaultPackagePickupTime'];

    $defaultDate = date("Y-m-d $ptimePackage", strtotime('+7 days'));
    $pdate = DateTimeFormat::DateTimeToDatePicker($defaultDate);
    $ptime = date('h:i A', strtotime($ptimePackage));
    ?>
                                            <input type="hidden" id="step11" name="step" value="1">
                                            <?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 5, 'id' => 'bkg_booking_type5']); ?>
                                            <?= $form->hiddenField($model, 'bktyp', ['value' => 5, 'id' => 'bktyp5']); ?>
                                            <?= $form->hiddenField($model, 'bkg_package_id', ['value' => $pkid]); ?>  
                                            <?= $form->hiddenField($model, 'bkg_pickup_date_date', ['value' => $pdate]); ?>  
                                            <?= $form->hiddenField($model, 'bkg_pickup_date_time', ['value' => $ptime]); ?>  
                                            <?php
                                            if ($pck['prt_package_rate'] != '') {
                                                ?>
                                                <div class="Submit-button" style=""> <?php echo CHtml::submitButton('Book Package', array('class' => 'btn-2 btn-green-2')); ?> </div>
                                            <?php } else { ?>
                                                <div class="Submit-button" style=""> <a href="tel:+919051877000" class="btn btn-primary" style="color:#fff !important;white-space: pre-wrap; font-size: 12px; padding-bottom: 10px; padding-top: 10px;">Call / Email us to book</a> </div>
                                            <?php } ?>
                                            <?php $this->endWidget(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
<?php } ?>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<?php
if ($pmodel == []) {
    ?>
    <div class="row mb30 mt30">
        <div class="col-12 text-center">
            Didn' t find the package you are looking for? Just call us at <b>90518 77700</b> and we will create your package for you
        </div>
    </div>

    <?
}
?>


<script type="text/javascript">

    function showDetails(id)
    {
        //alert(id);
        $href = '<?= Yii::app()->createUrl('booking/showPackage', ['pck_id' => '']) ?>' + id;
        jQuery.ajax({type: 'GET', url: $href,
            success: function (data) {

                multicitybootbox = bootbox.dialog({
                    message: data,
                    size: 'small',
                    title: 'Package Info',
                    onEscape: function () {

                    },
                });



            }
        });
    }

    $sourceList = null;
    function populateSourceCityPackage(obj, cityId) {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSourceCityPackage(query, callback) {
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/citylistpackage1')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }

</script>