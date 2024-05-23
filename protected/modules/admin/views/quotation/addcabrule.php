<style>
    .checkbox-inline{
        padding-left: 0 !important;
    }
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
    .new-booking-list label{ font-size: 11px;}
	.usertype,
	.cash,
	.coin,
	.fixed{ 
		padding: 10px; 
		margin: 10px; 
		border: 1px solid silver; 
	}
</style>
<?php
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/plugins/form-typeahead/typeahead.bundle.min.js');
?> 
<div class="row">
	<?php
	if (Yii::app()->user->hasFlash('success'))
	{
		?>
		<div class="alert alert-block alert-success">
			<?php echo Yii::app()->user->getFlash('success'); ?>
		</div>
	<?php } ?>
    <div class="col-xs-12 col-md-11 col-lg-11  new-booking-list" style="float: none; margin: auto">
		<?php
		$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
			'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
			'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
			'openOnFocus'		 => true, 'preload'			 => false,
			'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
			'addPrecedence'		 => false,];
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'email-form',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => '',
			),
		));
		/* @var $form TbActiveForm */
		?>
		<?= CHtml::errorSummary($model); ?> 
		<div class="row">
            <div class="col-xs-12 col-lg-10 col-lg-offset-1">
                <div class="panel panel-default panel-border">
                    <div class="panel-body">
						<?php if ($model->isNewRecord)
						{ ?>	<div class="row mb15">

								<div class="col-xs-12 col-sm-4">
									<label>Area Type *</label>
									<?php
									$filters = [
										1	 => 'Zone',
										2	 => 'State',
										3	 => 'City',
										4	 => 'Region',
									];
									$dataPay = ServiceClassRule::model()->getJSON($filters);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'scr_area_type',
										'val'			 => $model->scr_area_type,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
										'htmlOptions'	 => array('required' => true, 'class' => 'p0', 'style' => 'width:100%', 'onChange' => 'getAreaName(this)', 'placeholder' => 'Select Types','id'=>'scr_area_type')
									));
									?>		
									<span id="areaTypeErr"></span>
								</div>

								<div class="col-xs-12 col-sm-4">
									<label>Area Name *</label>
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'scr_area_id',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select Area",
										'fullWidth'			 => false,
										'options'			 => array('allowClear' => true),
										'htmlOptions'		 => array('width'	 => '100%',
											'id'	 => 'scr_area_id'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                  populateSource(this, '{$model->scr_area_id}');
                                                }",
									'load'			 => "js:function(query, callback){
                        loadSource(query, callback);
                        }",
									'render'		 => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }", 'allowClear'	 => true
										),
									));
									?>
	<?php echo $form->error($model, 'scr_area_id'); ?>
								</div>
								<div class="col-xs-12 col-sm-4">
									<label>Cab Name *</label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'scr_scv_id',
										'val'			 => $model->scr_scv_id,
										'asDropDownList' => FALSE,
										'options'		 => array(
											'data'		 => new CJavaScriptExpression(SvcClassVhcCat::model()->getJSON(SvcClassVhcCat::model()->getVctSvcList())),
											'allowClear' => true
										),
										'htmlOptions'	 => array('required' => true, 'class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Cab Name')
									));
									?>
									<span id="cabNameErr"></span>
								</div>

							</div>
							<?}?>
	                        <div class="row mb15">
	<?php if ($model->isNewRecord)
	{ ?>	
									<div class="col-xs-12 col-sm-4">
										<label>Trip Type *</label>
										<?php
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'scr_trip_type',
											'val'			 => $model->scr_trip_type,
											'asDropDownList' => FALSE,
											'options'		 => array(
												'data'		 => new CJavaScriptExpression(SvcClassVhcCat::model()->getJSON(Filter::bookingTypes())),
												'allowClear' => true
											),
											'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Trip Type')
										));
										?>
										<span id="tripTypeErr"></span>
									</div><?}?>
									<div class="col-xs-12 col-sm-4">
										<label>Markup Type *</label>
										<?php
										$filters = [
											1	 => 'Percentage',
											2	 => 'Value',
										];
										$dataPay = ServiceClassRule::model()->getJSON($filters);
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $model,
											'attribute'		 => 'scr_markup_type',
											'val'			 => $model->scr_markup_type,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
											'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%;margin-left:5px;', 'placeholder' => 'Select Types')
										));
										?>	
										<span id="markTypeErr"></span>
									</div>
									<div class="col-xs-12 col-sm-4"><label>Markup Amount *</label>							
		<?= $form->textFieldGroup($model, 'scr_markup', array('label' => '', 'required' => 'required')) ?>					
									</div>
								</div>
								<div class="row mb15">
									<div class="col-xs-12 col-md-4"><label>Is Allow</label>							
		<?= $form->radioButtonListGroup($model, 'scr_is_allowed', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => $isAllow), 'inline' => true, 'required' => 'required')) ?>					
									</div>
								</div>

							</div>

							<!--  -->
							<div class="row">
								<div class="col-xs-12 text-center pb10">
									<input type="submit" value="Create" name="yt0" id="promosubmit" class="btn btn-primary pl30 pr30">
								</div>
							</div>				
						</div>
					</div>
				</div>
			</div>
		<?php $this->endWidget(); ?>
		</div>
		</div>

		<script>
		    $("#cabRules-form").submit(function () {
		        var markupType = $("#ServiceClassRule_scr_markup_type").val();
		        if (markupType == '') {
		            $("#markTypeErr").text("Markup Type must be filled out");
		            $("#markTypeErr").css({"color": "red", "font-weight": "bold"});
		            return false;
		        }
		        return true;

		    });

		    function getAreaName(obj = "", parent_id = 0, areaId = 0)
		    {
		        var parent_id = (parent_id > 0) ? parent_id : $(obj).val();
		        var selectize = $("#scr_area_id")[0].selectize;
		        selectize.clear();
		        selectize.clearOptions();
		        if (parent_id == 3)
		        {
		            $.ajax({
		                url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>',
		                dataType: 'json',
		                data: {
		                    // city: cityId
		                },
		                //  async: false,
		                success: function (results) {
		                    selectize.load(function (callback) {
		                        callback(results);
		                    });
		                    if (areaId > 0) {
		                        selectize.setValue(areaId);
		                    }
		                    return;
		                },
		                error: function () {
		                    callback();
		                }
		            });
		            return;
		        }
		        var url = "<?= Yii::app()->createAbsoluteUrl('/admin/quotation/getareaname') ?>" + "?areaType=" + parent_id;
        $.ajax({
            type: "GET",
            url: url,
            cache: false,
            success: function (data) {
                var obj = $.parseJSON(data);
                var arr = [];


                $.each(obj, function (key, value)
                {
                    if (parent_id == 1)
                    {
                        arr.push({
                            id: value.zon_id,
                            text: value.zon_name
                        });
                    }
                    if (parent_id == 2)
                    {
                        arr.push({
                            id: value.stt_id,
                            text: value.stt_name
                        });
                    }

                    if (parent_id == 4)
                    {
                        arr.push({
                            id: key,
                            text: value
                        });
                    }
                });
                selectize.load(function (callback)
                {
                    callback(arr);
                });
                if (areaId > 0) {
                    selectize.setValue(areaId);
                }
            }
        });
    }
	function populateSource(obj, areaId) {
        obj.load(function (callback) {
            var obj = this;
            obj.enable();
			var parent_id = $("#scr_area_type").val();
            if (parent_id > 0 && areaId > 0) {
            getAreaName(obj, parent_id, areaId);
        }
        });
    }
	 function loadSource(query, callback) {
        if (!query.length)
            return callback();
        var parent_id = $('#scr_area_type').val();
        if (parent_id == 3) {
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
    }
</script>