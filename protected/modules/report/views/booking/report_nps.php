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
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'nps-form', 'enableClientValidation' => true,
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

                    <div class="col-xs-12 col-sm-4 col-md-3">
						<?= $form->datePickerGroup($model, 'rtg_date1', array('label' => 'From Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date')), 'prepend' => '<i class="fa fa-calendar"></i>')); ?></div>
                    <div class="col-xs-12 col-sm-4 col-md-3">
						<?=
						$form->datePickerGroup($model, 'rtg_date2', array('label'			 => 'To Date',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>  
                    </div>
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
					<?php $this->endWidget(); ?>
					<?php
					$checkExportAccess	 = false;
					if ($roles['rpt_export_roles'] != null)
					{
						$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
					}
					if ($checkExportAccess)
					{
						?>
						<div class="col-xs-2 pt5">
							<?php
							echo CHtml::beginForm(Yii::app()->createUrl('report/booking/Npsscore'), "post", []);
							?>
							<input type="hidden" id="rtg_date1" name="rtg_date1" value="<?= $model->rtg_date1 ?>"/>
							<input type="hidden" id="rtg_date2" name="rtg_date2" value="<?= $model->rtg_date2 ?>"/>
							<input type="hidden" id="export" name="export" value="true"/>
							<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
							<?php echo CHtml::endForm(); ?>	
						</div>	
					<?php } ?>
                </div>

				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
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
						'columns'			 => array(
							array('name' => 'responded', 'value' => '$data[responded]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Responded'),
							array('name' => 'detractors', 'value' => '$data[detractors]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Detractors'),
							array('name' => 'passives', 'value' => '$data[passives]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Passives'),
							array('name' => 'promotors', 'value' => '$data[promotors]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Promotors'),
							array('name' => 'nps_score', 'value' => '$data[nps]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'NPS Score'),
					)));
				}
				?>
            </div>  

        </div>  
    </div>
</div>









