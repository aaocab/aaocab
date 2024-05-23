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
					<?php
					$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'vendor-form', 'enableClientValidation' => true,
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
					<?php   
					?>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
						<?= $form->datePickerGroup($model, 'vnd_create_date1', array('label' => 'From Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?></div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
						<?=
						$form->datePickerGroup($model, 'vnd_create_date2', array('label' => 'To Date',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>  
                    </div>

					<div class="col-xs-12 col-sm-3  col-md-2 col-lg-1 mt20 pt5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
					<?php $this->endWidget(); ?>
                </div>
                
				<?php
				$checkExportAccess = Yii::app()->user->checkAccess("Export");
				if ($checkExportAccess)
				{
					echo CHtml::beginForm(Yii::app()->createUrl('admin/report/vendortds'), "post", ['style' => "margin-bottom: 10px;margin-top: 10px;"]);
					?>

					<input type="hidden" id="export2" name="export2" value="true"/>
					<input type="hidden" id="export_from2" name="export_from2" value="<?= $model->vnd_create_date1 ?>"/>
					<input type="hidden" id="export_to2" name="export_to2" value="<?= $model->vnd_create_date2 ?>"/>
					<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
					<?php
					echo CHtml::endForm();
				}

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
//							array('header' => 'Sl No.',
//								'value'	 => '++$row',
//							),

							array('name' => 'vnd_name', 'value' => '$data[vnd_name]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Vendor Name'),
							array('name' => 'ctt_name', 'value' => '$data[ctt_name]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Contact Name'),
							array('name' => 'ctt_pan_no', 'value' => '$data[ctt_pan_no]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Pan No'),
							array('name' => 'totalTds', 'value' => '$data[totalTds]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'),'htmlOptions' => array('class' => 'text-center'), 'header' => 'Total TDS'),
                                                        array('name' => 'totalTripPurchased', 'value' => '$data[totalTripPurchased]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'),'htmlOptions' => array('class' => 'text-center'), 'header' => 'Total Trip Purchased'),
                                                    
					)));
				}
				?> 
            </div>  

        </div>  
    </div>
</div>





