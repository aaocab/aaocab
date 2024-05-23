
<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
	.btnSubmit{
		width:150px;text-transform: uppercase;padding:10px;margin-top:20px;
	}
	#Note .form-group.has-error .form-control {
		width:97%!important;
	}
	.hide{
		display :block;
	}
        .form-horizontal .form-group{ margin: 0;}
</style>
<?php 
$version			 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false, 'maxItems' => null];
if ($error != '')
{
	?>  
	<div class="col-xs-12 text-danger text-center"><?= $error ?></div> 
	<?php
}
else
{
	$areatype	 = DestinationNote::model()->areatype;
        $showNoteType	 = DestinationNote::model()->showNoteType;
	$area			 = 0;

?>
<div class="row">
    <div class="col-xs-12">
		<?php //echo CHtml::errorSummary($model);?>
		
		<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'note-add-form', 'enableClientValidation' => TRUE,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error'
						),
						// Please note: When you enable ajax validation, make sure the corresponding
						// controller action is handling ajax validation correctly.
						// See class documentation of CActiveForm for details on this,
						// you need to use the performAjaxValidation()-method described there.
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => 'form-horizontal',
						),
					));
					/* @var $form TbActiveForm */
					?>


		<div class="col-lg-6 col-md-8 col-sm-12 col-lg-offset-3">
			<div class="panel panel-default panel-border">
				<div class="panel-body">
					<div class="row mb15">
						
						<div class="col-xs-12 col-md-6">
						    <input type="hidden" id="noteId" value=" <?=$notes->dnt_id?>">
							<label class="control-label" id="errMsg1"> Select Area Type </label>
							<?php
							$dataAreaType	 = VehicleTypes::model()->getJSON($areatype);

							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $notes,
								'attribute'		 => 'dnt_area_type',
								'val'			 => $notes->dnt_area_type,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataAreaType)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area Type', 'multiple' => '','id' => 'DestinationNote_dnt_area_type')
							));
							?>
												<?php echo $form->error($notes, 'dnt_area_type'); ?>
						</div>

						<?php if($notes->dnt_area_type !=0){?>
						<div class="col-xs-12 col-md-6" id="fromArea">
								<label class="control-label" id="errMsg2" >Select One or More Area(s)</label>
								<div id="get_area1">
								<?php
								$areaFromArr	 = '[]';
								   
								if ($notes->dnt_area_type == 1)
								{
									$areaFromArr = Zones::model()->getJSON();
								}
								else if ($notes->dnt_area_type == 2)
								{
									$areaFromArr = States::model()->getJSON();
								}
								/*else if ($model->dnt_area_type == 3)
								{
									$areaFromArr = Cities::getAllCityListDrop();
								}*/
								else if ($notes->dnt_area_type == 4)
								{
									$areaFromArr = Promos::getRegionJSON();
								}

								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $notes,
									'attribute'		 => 'dnt_area_id',
									'val'			 => $notes->dnt_area_id,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($areaFromArr), 'multiple' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select One or More Area(s)', 'multiple'=> 'multiple', 'id' => 'DestinationNote_dnt_area_id1')
								));
								?>
							</div>
								
								<?php echo $form->error($notes, 'dnt_area_id'); ?>
							</div>
						</div>
				             <?php }?>
						
                                    <div class="row mb15">
							<div class="col-xs-12">
								<label>Notes *</label>
								<?= $form->textAreaGroup($notes, 'dnt_note', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Note', 'id'=>'Note', 'style' => 'height:100px;')))) ?>
							</div>
                                        </div>
                                    <div class="row mb15">
							<div class="col-xs-12">
                                                            <div class="row">
							    <div class="col-xs-12 col-sm-6">
									    <?php $datefrom	 = $notes->dnt_valid_from != '' ? $notes->dnt_valid_from : date('Y-m-d H:i:s');?>
									<?= $form->datePickerGroup($notes, 'dnt_valid_from_date', array('label' => 'Valid From Date', 
										'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date('d/m/Y'), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($datefrom)))), 'prepend' => '<i class="fa fa-calendar"></i>')); 
									?>
								</div>
								
								<div class="col-xs-12 col-sm-6">
									<?php
									
										if ($notes->dnt_valid_from != '')
										{
											$ptime = date('h:i A', strtotime($notes->dnt_valid_from));
										}
										else
										{
											$ptime = date('h:i A', strtotime(now));
										}
										
										 $fromTimeArr = Filter::getTimeDropArr($ptime);
										 
										?>
									<?=
									$form->timePickerGroup($notes, 'dnt_valid_from_time', array('label' => 'Valid From Time',
										'widgetOptions'	 => array('options' => array('autoclose' => true), 'htmlOptions' => array('required' => true, 'value' =>$ptime))));
									?>

								</div>
                                                            </div>
							</div>
                                    </div>
                                    <div class="row mb15">
                           
							<div class="col-xs-12">
                                                            <div class="row mb15">
								<div class="col-xs-12 col-sm-6">
									 <?php $dateto	 = $notes->dnt_valid_to != '' ? $notes->dnt_valid_to : date('Y-m-d H:i:s');?>
									<?= $form->datePickerGroup($notes, 'dnt_valid_to_date', array('label' => 'Valid To Date', 
										'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date('d/m/Y'), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($dateto)))), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
								</div>
								
								<div class="col-xs-12 col-sm-6">
									<?php
										if ($notes->dnt_valid_to != '')
										{
											$ptime = date('h:i A', strtotime($notes->dnt_valid_to));
										}
										else 
										{
											$ptime =date('h:i A', strtotime(now));
										}
										$toTimeArr = Filter::getTimeDropArr($ptime);
										?>
									<?=
									$form->timePickerGroup($notes, 'dnt_valid_to_time', array('label'=> 'Valid To Time',
										'widgetOptions'	 => array('options' => array('autoclose' => true), 'htmlOptions' => array('required' => true, 'value' => $ptime))));
									?>
								</div>
                                                         
					           <div class="col-xs-12 mt10">
							<label class="control-label" id="errMsg1"> Select Show Note To </label>
							<?php
							
							$dataAreaType	 = VehicleTypes::model()->getJSON($showNoteType);
							
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $notes,
								'attribute'		 => 'dnt_show_note_to',
								'val'			 => '',
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataAreaType), 'multiple' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Show Note To',  'multiple'=> 'multiple', 'id' => 'DestinationNote_dnt_show_note_to')
							));
							?>
							<?php echo $form->error($notes, 'dnt_show_note_to'); ?>
						</div>
                                                                </div>
                                                        </div>
                                    </div>
						<div class="col-xs-12 text-center">
							<input type="submit" value="Submit" name="yt0" id="notesubmit" class="btn btn-primary pl30 pr30 btnSubmit">
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php $this->endWidget(); ?>
    </div>

</div>

<script type="text/javascript">
var city = new City();

		$('#DestinationNote_dnt_area_type').change(function ()
		{
			
			var model = {}
			var area = $('#<?= CHtml::activeId($notes, 'dnt_area_type') ?>').val();
			
			model.area = area;
			model.id = 'DestinationNote_dnt_area_id';

			model.multiple = true;

			city.model = model;
			city.showAreaForNotes();
		});
  
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
	
	 $('#<?= CHtml::activeId($notes, 'dnt_area_type') ?>').click(function ()
    {
       var areaType = $('#<?= CHtml::activeId($notes, 'dnt_area_type') ?>').val();
	   if(areaType == 0){
	   $('#fromArea').addClass('hide');
        }
		else{
			 $('#fromArea').removeClass('hide');
		}
    });
	
	
</script>
<?php

}?>
<script type="text/javascript">
function populateSource(obj, cityId)
    {

        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1])) ?>',
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback)
    {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    }   
</script>