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
<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row"> 

					<div class="col-xs-12">
						<?php
						$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
							'id'					 => 'termPointsForm', 'enableClientValidation' => true,
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
						<div class="form-group row">

							<div class="row " >
								<div class="col-xs-6 col-sm-2">
									<a class="btn btn-primary" href="<?= Yii::app()->createUrl('aaohome/terms/addPoints') ?>" style="text-decoration: none;">Add new</a>
								</div>
								<div class="col-xs-6 col-sm-4">

									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'tnp_for',
										'val'			 => $model['tnp_for'],
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression(TncPoints::getTypeJSON())),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Type', 'class' => 'input-group')
									));
									?>

								</div>
								<div class="col-xs-6 col-sm-4">
									<button class="btn btn-info  " type="submit"  name="Search" style="width: 185px;">Search</button>
								</div>
								<div class="col-xs-6 col-sm-2">
									&nbsp;
								</div>
							</div>

						</div>


						<?php $this->endWidget();
						?>
					</div>
					<?php
					$typelist	 = array("" => "Select Category", "1" => "User", "2" => "Vendor", "3" => "Meterdown");
					if (!empty($dataProvider))
					{
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
								array('name'	 => 'tnp_position',
									'value'	 => function($data) 
									{
										echo $data['tnp_position'];
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Position'),
								array('name'	 => 'tnp_for',
									'value'	 => function($data) {
										
										echo TncPoints::showCatType($data['tnp_for']);
										
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Who'),
								array('name' => 'tnp_text', 'value' => '$data[tnp_text]', 'headerHtmlOptions' => array('class' => 'col-xs-6'), 'header' => 'Description'),
								array('name'	 => 'tnp_tier',
									'value'	 => function($data) {
									    if($data['tnp_tier']!=null)
										{
											echo ServiceClass::getTierByIds($data['tnp_tier']);
										}	
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Tier'),
								array('name'	 => 'tnp_trip_type',
									'value'	 => function($data) 
									{
										if($data['tnp_trip_type']!=null)
										{
											echo TncPoints::showTripType($data['tnp_trip_type']);
										}	
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Trip Type'),			
								array('name'	 => 'tnp_c_type',
									'value'	 => function($data) 
									{
										echo TncPoints::showCType($data['tnp_c_type']);
									
									},
									'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'C Type'),			
								array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{edit}{delete}',
									'buttons'			 => array(
										'edit'			 => array(
											'url'		 => 'Yii::app()->createUrl("admin/terms/addPoints", array(\'tnp_id\' => $data[tnp_id]))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-edit"></i>',
											'options'	 => array('style' => 'margin-right: 2px', 'class' => 'btn btn-xs btn-info ignoreJob', 'title' => 'Edit'),
										),
										'delete'		 => array(
											'click'		 => 'function(){
                                                        var con = confirm("Are you sure you want to dacativate this point?");
                                                        return con;
                                                    }',
											'url'		 => 'Yii::app()->createUrl("admin/terms/delpoints", array(\'tnp_id\' => $data[tnp_id]))',
											'imageUrl'	 => false,
											'label'		 => '<i class="fa fa-remove"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 8px', 'class' => 'btn btn-xs btn-danger condelete', 'title' => 'Delete'),
										),
										'htmlOptions'	 => array('class' => 'center'),
									))
						)));
					}
					?> 
                </div> 
            </div>  

        </div>  
    </div>
</div>