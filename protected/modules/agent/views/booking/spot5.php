
<?
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask1.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/booking.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/route.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
$selectizeOptions = ['create' => false, 'persist' => true, 'selectOnTab' => true,
    'createOnBlur' => true, 'dropdownParent' => 'body',
    'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField' => 'id',
    'openOnFocus' => true, 'preload' => false,
    'labelField' => 'text', 'valueField' => 'id', 'searchField' => 'text', 'closeAfterSelect' => true,
    'addPrecedence' => false];
?>
<div class="container mt50 p30">
<!--    <div class="row">
        <div class="col-xs-12 text-center"><img src="/images/logo2.png" alt="Gozocabs:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews."></div>
    </div>-->
    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'spot5', 'enableClientValidation' => FALSE,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'errorCssClass' => 'has-error'
        ),
        'enableAjaxValidation' => false,
        'errorMessageCssClass' => 'help-block',
        'action' => Yii::app()->createUrl('agent/booking/spot'),
        'htmlOptions' => array(
            'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'
        ),
    ));
    /* @var $form TbActiveForm */
    ?>
    <div class="row spot-panel">
<?php if($model->bkg_booking_type == 5)
{
?>
  <div class="col-xs-12 col-sm-4 mt30">

            <div class="form-group">
                <label class="col-md-12 control-label p0"><h3>Depart from City</h3></label>
                <div class="col-md-12 p0">
                    <input type="hidden" name="step" value="5">
                    <?php
                    echo $form->hiddenField($model, 'preData', ['value' => json_encode($model->preData)]);
                    echo $form->hiddenField($model, 'bkg_booking_type');
                    echo $form->hiddenField($model, 'bkg_from_city_id');
                    echo $form->hiddenField($model, 'bkg_to_city_id');
                    $this->widget('ext.yii-selectize.YiiSelectize', array(
                        'model' => $model,
                        'attribute' => 'bkg_from_city_id1',
                        'useWithBootstrap' => true,
                        "placeholder" => "Select Source City",
                        'fullWidth' => false,
                        'htmlOptions' => array('width' => '100%',
                            'id' => 'Booking_bkg_from_city_id1'
                        ),
                        'defaultOptions' => $selectizeOptions + array(
                    'onInitialize' => "js:function(){
                                            populateSource(this, '{$model->bkg_from_city_id}');
                                                }",
                    'load' => "js:function(query, callback){
                                            loadSource(query, callback);
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
                     <span class="has-error"><? echo $form->error($model, 'bkg_from_city_id'); ?></span>
                </div>
            </div>
        </div>
 <div class="col-xs-12 col-sm-4 mt30 col-sm-offset-4">
            <div class="form-group">
                <label class="col-md-12 control-label p0"><h3>Select length of time for which you require a cab</h3></label>
                <div class="col-md-12 p0">
                    <?
						$rentalTypeArr	 = Booking::model()->rental_types;
						echo CHtml::activeDropDownList($model, "bkg_booking_type", $rentalTypeArr,
								array('style'			 => 'width:100%', 'class'			 => 'form-control', 'placeholder'	 => 'Hr - Km',
									'onChange'		 => 'setBookingType(this)', 'id'			 => 'BookingTemp_bkg_booking_type_rental'));

					?>
<input type="hidden" id="Booking_bkg_to_city_id1" name="Booking_bkg_to_city_id1" value=""/>
					

                    <span class="has-error"><?// echo $form->error($model, 'bkg_to_city_id'); ?></span>
                </div>
            </div>
        </div>
<?php
 } else { 
?>
        <div class="col-xs-12 col-sm-4 mt30">

            <div class="form-group">
                <label class="col-md-12 control-label p0"><h3>Depart from City</h3></label>
                <div class="col-md-12 p0">
                    <input type="hidden" name="step" value="5">
                    <?php
                    echo $form->hiddenField($model, 'preData', ['value' => json_encode($model->preData)]);
                    echo $form->hiddenField($model, 'bkg_booking_type');
                    echo $form->hiddenField($model, 'bkg_from_city_id');
                    echo $form->hiddenField($model, 'bkg_to_city_id');
                    $this->widget('ext.yii-selectize.YiiSelectize', array(
                        'model' => $model,
                        'attribute' => 'bkg_from_city_id1',
                        'useWithBootstrap' => true,
                        "placeholder" => "Select Source City",
                        'fullWidth' => false,
                        'htmlOptions' => array('width' => '100%',
                            'id' => 'Booking_bkg_from_city_id1'
                        ),
                        'defaultOptions' => $selectizeOptions + array(
                    'onInitialize' => "js:function(){
                                            populateSource(this, '{$model->bkg_from_city_id}');
                                                }",
                    'load' => "js:function(query, callback){
                                            loadSource(query, callback);
                                            }",
                    'onChange' => "js:function(value) {
                                                                    changeDestination(value, \$dest_city);
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
                     <span class="has-error"><? echo $form->error($model, 'bkg_from_city_id'); ?></span>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4 mt30 col-sm-offset-4">
            <div class="form-group">
                <label class="col-md-12 control-label p0"><h3>Arrive at City</h3></label>
                <div class="col-md-12 p0">
                    <?
                        $this->widget('ext.yii-selectize.YiiSelectize', array(
                            'model' => $model,
                            'attribute' => 'bkg_to_city_id1',
                            'useWithBootstrap' => true,
                            "placeholder" => "Select Destination",
                            'fullWidth' => true,
                            'htmlOptions' => array('id' => 'bkg_to_city_id1', 'class' => 'form-control ctyDrop ctySelect2'),
								'defaultOptions' => $selectizeOptions + array(
                        'onInitialize' => "js:function(){
                        \$dest_city=this;
                        }",
                    'load' => "js:function(query, callback){
                                            loadSource(query, callback);
                                            }",

                        'render' => "js:{
                                option: function(item, escape){                      
                                        return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';                          
                                },
                                option_create: function(data, escape){
                                     return '<div>' +'<span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(data.text) + '</span></div>';
                               }
                           }",
                            ),
                        ));
                    ?>
                    <span class="has-error"><?// echo $form->error($model, 'bkg_to_city_id'); ?></span>
                </div>
            </div>
        </div>
<?php } ?>
        <div class="col-xs-12 text-right mt30">
            <button type="submit" class="btn btn-primary btn-lg pl50 pr50 pt30 pb30" name="step5submit"><b>Next <i class="fa fa-arrow-right"></i></b></button>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
<script src="/js2/isotope.js"></script>
<script src="/js2/imagesloaded.js"></script>
<script src="/js2/smoothscroll.js"></script>
<script src="/js2/wow.js"></script>
<script src="/js2/custom.js"></script>
<script>
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    $sourceList = null;
    function populateSource(obj, cityId) {

        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1])) ?>',
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
    function loadSource(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
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

    function changeDestination(value, obj) {
        if (!value.length)
            return;
        obj.disable();
        obj.clearOptions();
        obj.load(function (callback) {
            //  xhr && xhr.abort();
            xhr = $.ajax({
                url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/nearestcitylist')) ?>/source/' + value,
                dataType: 'json',
                success: function (results)
                {
                    obj.enable();
                    callback(results);
                },
                error: function () {
                    callback();
                }
            });
        });
    }
    $('#Booking_bkg_from_city_id1').change(function () {
        $('#Booking_bkg_from_city_id').val($('#Booking_bkg_from_city_id1').val()).change();
    });
    $('#bkg_to_city_id1').change(function () {
		
        $('#Booking_bkg_to_city_id').val($('#bkg_to_city_id1').val()).change();
    });
</script>