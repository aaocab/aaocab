<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }

</style>
<?php
$pageno				 = Yii::app()->request->getParam('page');
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/city.js?v=' . $version);
$areatype		 = DestinationNote::model()->areatype;
$showNoteType	 = DestinationNote::model()->showNoteType;
$area			 = 0;
?>
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
               <?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'noteForm', 'enableClientValidation' => true,
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
						'class' => '',
					),
				));
				/* @var $form TbActiveForm */
				?>
				<div class="row mt10" >
				
						<div class="col-xs-12 col-md-3">
							<label class="control-label" id="errMsg1"> Select Area Type  </label>
							<?php
                                                       
							$dataAreaType	 = VehicleTypes::model()->getJSON($areatype);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'dnt_area_type',
								'val'			 => ($qry['area_type']==""?5:$qry['area_type']),
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataAreaType)),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area Type', 'id' => 'DestinationNote_dnt_area_type')
							));
							?>
							<?php echo $form->error($model, 'dnt_area_type'); ?>
						</div>
				   
                                                <?php if($model->dnt_area_type !=0){?>
						<div class="col-xs-12 col-md-3" id="fromArea">
							<div class="form-group">
								<label class="control-label" id="errMsg2" >Select Area </label>
								<?php
								$areaFromArr	 = '[]';
								   
								if ($model->dnt_area_type == 1)
								{
									$areaFromArr = Zones::model()->getJSON();
									$arr= json_decode($areaFromArr);
									//print_r($arr);
									
								}
								else if ($model->dnt_area_type == 2)
								{
									$areaFromArr = States::model()->getJSON();
								}
								else if ($model->dnt_area_type == 3)
								{
									$areaFromArr = Cities::getAllCityListDrop();
								}
								else if ($model->dnt_area_type == 4)
								{
									$areaFromArr = Promos::getRegionJSON();
								}
                                                                
                                                               
                                                                
                                                                 
                                                                
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'dnt_area_id',
									'val'			 =>  $model->dnt_area_id,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($areaFromArr), 'multiple' => true),
									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Area', 'multiple'=> 'multiple', 'id' => 'DestinationNote_dnt_area_id')
								));
								?>
								<?php echo $form->error($model, 'dnt_area_id'); ?>
							</div>
						</div>
					   <?php }?>
				           
                                       <div class="col-xs-4 col-sm-3">	
						<?= $form->datePickerGroup($model, 'dnt_created_date', array('label' => 'Created Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Created Date','value' => $qry['createdDate'])), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
					</div>
					<div class="col-xs-4 col-sm-3">	
						<?= $form->datePickerGroup($model, 'dnt_valid_from_date', array('label' => 'Valid From Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Validated From', 'value'=>$qry['fromDate'])), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
					</div>
					<div class="col-xs-4 col-sm-3">	
							<?= $form->datePickerGroup($model, 'dnt_valid_to_date', array('label' => 'Valid To Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Validated To','value'=>$qry['todate'])), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
					</div>
					 <div class="col-xs-12 col-md-3">
						<label class="control-label" id="errMsg1"> Select Note To <?php //print_r($model['dnt_created_by_role']);?></label>
						<?php
                                           # echo $qry['show_note_to'];
						$dataAreaType	 = VehicleTypes::model()->getJSON($showNoteType);
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'dnt_show_note_to',
							'val'			 => $model['dnt_created_by_role'],
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($dataAreaType), 'multiple' => true),
							'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Note To', 'multiple'=> 'multiple','id' => 'DestinationNote_dnt_select_note_to')
						));
						?>
						<?php echo $form->error($model, 'dnt_show_note_to'); ?>
					</div>
                                    <div class="col-xs-4 col-sm-3">
                                        <br>
                                       
                                                <?= $form->checkboxGroup($model, 'dnt_status', ['label' => 'Pending Approval', 'widgetOptions' => ['htmlOptions' => ['checked' => $qry['dnt_status']]], 'inline' => true]); ?>
                                    </div>
					<div class="col-xs-4 col-sm-3">	
                                            <br>
					<button class="btn btn-primary full-width" type="submit"  name="noteSearch">Search</button>
					</div>
				    <a class="btn btn-primary pull-right mt20 mr10" style="text-decoration:none;" href="<?= Yii::app()->createUrl('admin/notes/add')?>" >Add Note</a>
				    
				    </div>
				<?php $this->endWidget(); ?>
                <div class="row"> 
					<!--- -->
					<?php
					//$typelist = array("" => "Select Category", "1" => "User", "2" => "Vendor", "3" => "Meterdown");
					
					if (!empty($dataProvider))
					{
						 if($data["dnt_area_type"]==3)
						 {
							$areaValue = $data["cty_name"];
						 }
						 if($data["dnt_area_type"]==2)
						 {
							$areaValue = $data["dnt_state_name"];
						 }
					
						$this->widget('booster.widgets.TbGridView', array(
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
							//    'ajaxType' => 'POST',
							
							'columns'			 => array(
								
								array('name' => 'cty_name', 'value' => '($data["dnt_area_type"]==3?$data["cty_name"]:(($data["dnt_area_type"]==2)?$data["dnt_state_name"]:(($data["dnt_area_type"]==0)?"Applicable to all.":(($data["dnt_area_type"]==1)? $data["dnt_zone_name"]:Promos::$region[$data["dnt_area_id"]]))))', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Area'),
								array('name' => 'dnt_note', 'value' => '$data["dnt_note"]', 'headerHtmlOptions' => array('class' => 'col-xs-5'), 'header' => 'Notes'),
								array('name' => 'dnt_valid_from', 'value' => '(DateTimeFormat::DateTimeToLocale($data["dnt_valid_from"]))', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Valid from'),
								array('name' => 'dnt_to', 'value' => '(DateTimeFormat::DateTimeToLocale($data["dnt_valid_to"]))', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Valid to'),
								
array('name'	 => 'dnt_show_note_to', 'filter' => CHtml::activeDropDownList($model, 'dnt_show_note_to', array('1' => 'Consumer', '2' => 'Vendor', '3' => 'Driver'),['class' => 'form-control']), 'value'	 => function($data) {
							$showNoteTos = explode(",", $data["dnt_show_note_to"]);
						        foreach($showNoteTos as $showNoteTo){
							    if ($showNoteTo == 1)
							     {
								     echo "Consumer".", ";
							     }
							     else if($showNoteTo == 2)
							     {
								     echo "Vendor".", ";
							     }
							     else if($showNoteTo == 3)
							     {
								     echo "Driver".", ";
							     }
								 //destination notes by Rituparana
								 //else if($showNoteTo == 5)
							     //{
								    // echo "Agent".", ";
							     //}
							     else 
							     {
								     echo "";
							     }
							}
							
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class'=>'col-xs-2','title' => "A = Consumer , V = Vendor, D = Driver"), 'header'			 => $model->getAttributeLabel('Show Note To')),		
						
							    array('name'	 => 'dnt_status', 'filter' => CHtml::activeDropDownList($model, 'dnt_status', array('0' => 'Pending', '1' => 'Approved', '2' => 'Rejected'),['class' => 'form-control']), 'value'	 => function($data) {
						    $label= "";
						    if ($data["dnt_status"] == 1)
							{
								echo "Approved";
							}
							else if($data["dnt_status"] == 0)
							{
							
							$noteId = $data["dnt_id"];
                                                            echo "Pending";
							    
							//echo "<a href='javascript:void()' title='Click here to Approved Status' style='text-decoration:none;' id='btnApproved' onclick='statusChange(1,$noteId)'><strong>Pending</strong></a>";
							    $label= '<img src="\images\approve.png" alt="<i class=&quot;fa fa-check&quot;></i>" tittle="Approve Note" onclick="statusModify(1,'.$noteId.')">';
							   }
							else
							{
								echo "Unapproved";
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class'=>'col-xs-1','title' => "P = Pending , A = Approved, R = Rejected"), 'header'			 => $model->getAttributeLabel('status')),
						array('name' => 'dnt_approve_by', 'value' => '$data["dnt_approve_by"]', 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Approved By'),
								
array('name'	 => 'dnt_created_by_role', 'filter' => CHtml::activeDropDownList($model, 'dnt_created_by_role', array('1' => 'Admin', '2' => 'Vendor', '3' => 'Driver'),['class' => 'form-control']), 'value'	 => function($data) {
							if ($data["dnt_created_by_role"] == 1)
							{
								echo "Admin". " (". ($data["dnt_approve_name"]).")";
							}
							else if($data["dnt_created_by_role"] == 2)
							{
								echo "Vendor". " (". ($data["dnt_vnd_approve_name"]).")";
							}
							else
							{
								echo "Driver". " (". ($data["dnt_drv_approve_name"]).")";
							}
							
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class'=>'col-xs-2','title' => "A = Admin , V = Vendor, D = Driver"), 'header'			 => $model->getAttributeLabel('created by')),		
						array('name'=>'dnt_created_date', 'value'=>'(DateTimeFormat::DateTimeToLocale($data["dnt_created_date"]))', 'sortable'=>true, 'headerHtmlOptions'=>array(), 'header'=>'Created Date'),	
						array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{edit}{delete}{activate}',
									'buttons'			 => array(
										'edit'			 => array(
											
											'url'		 => 'Yii::app()->createUrl("admin/notes/modifyNote", array(\'dnt_id\' => $data["dnt_id"]))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-edit"></i>',
											'options'	 => array('style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-info ignoreJob', 'title' => 'Edit'),
										),
									    
										'delete'		 => array(
											'click'		 => 'function(){
                                                        var con = confirm("Are you sure you want to dacativate this note?");
                                                        return con;
                                                    }',
											'url'		 => 'Yii::app()->createUrl("admin/notes/delNotes", array(\'note_id\' => $data["dnt_id"] ))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-remove"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-danger condelete', 'title' => 'Delete'),
										),
									    
										'activate'		 => array(
											'click'		 => 'function(){
											var con = confirm("Are you sure you want to acativate this note?");
											return con;
										    }',
											'url'		 => 'Yii::app()->createUrl("admin/notes/editStatus", array(\'note_id\' => $data["dnt_id"] ))',
											'imageUrl'	 => false,
											'visible'	 => '($data["dnt_status"] == 0)',
											'label'		 => '<i class="fa fa-check"></i>',
                                                                                        'options'	 => array('style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-success condelete', 'title' => 'Approve Note'),
											
										),
										'htmlOptions'	 => array('class' => 'center')))
						)));
						
					}
					?> 
                </div> 
				
            </div>  

        </div>  
    </div>
</div>
<script type="text/javascript">
    $sourceList = null;
	var city = new City();
	

	$('#DestinationNote_dnt_area_type').change(function ()
	{
		var model = {}
		var area = $('#<?= CHtml::activeId($model, 'dnt_area_type') ?>').val();

		model.area = area;
		model.id = 'DestinationNote_dnt_area_id';
		model.multiple = true;
		$('#DestinationNote_dnt_area_id').val="";
		city.model = model;
		city.showArea();
		
	});
    function populateSource(obj, cityId) {

        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
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
    
    function statusModify(obj, noteId)
    {
        bootbox.confirm({
            message: "Do you want to approve this note ?",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn-info'
                },
                cancel: {
                    label: 'CANCEL',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    
		var href = '<?= Yii::app()->createUrl("admin/notes/editStatus"); ?>';
		$.ajax({
                "url": href,
                "type": "GET",
                 "dataType": "text",
                "data": {"noteId": noteId, "dnt_status": obj},
	        "async": false,
                        success: function (data)
                        {

                            bootbox.hideAll()
                            window.location.reload(true);

                        }
                    });
                }
            }
        });

    }
    
    
	 $('#<?= CHtml::activeId($model, 'dnt_area_type') ?>').click(function ()
    {
       var areaType = $('#<?= CHtml::activeId($model, 'dnt_area_type') ?>').val();
	   if(areaType == 5 || areaType == 6){
	   $('#fromArea').addClass('hide');
        }
		else{
			 $('#fromArea').removeClass('hide');
		}
    });
</script>