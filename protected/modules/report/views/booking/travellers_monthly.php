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

<div class="row">
    <div class="col-xs-12">      
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row"> 
					<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'monthly-form', 'enableClientValidation' => true,
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
                        <input type="hidden" id="monthcounts" name="monthcounts" value="<?= $model->monthcount ?>"/>
                        <div class="form-group">
                            <label class="control-label">Customers not travelled in Months</label>
							<?=
							$form->numberFieldGroup($model, 'monthcount', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'placeholder' => "Enter month", 'value' => $model->monthcount, 'min' => 0])))
							?>
                        </div> </div>
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
						<?php $this->endWidget(); ?>
                </div>

				<? /* = CHtml::beginForm(Yii::app()->createUrl('admin/report/booking'), "post", ['style' => "margin-bottom: 10px;"]); ?>

				  <input type="hidden" id="monthcounts" name="monthcounts" value="<?= $model->monthcount ?>"/>
				  <button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
				  <?= CHtml::endForm() */ ?>

				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "
                            <div class='panel-heading'><div class='row m0'>
                                <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                            </div></div>
                            <div class='panel-body'>{items}</div>
                            <div class='panel-footer'><div class='row m0'>
                                <div class='col-xs-12 col-sm-6 p5'>{summary}</div>
                                <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                            </div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						//       'ajaxType' => 'POST',
						'columns'			 => array(
							array('name' => 'traveller_name', 'value' => '$data["name"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'User Name'),
							array('name' => 'bkg_contact_no', 'value' => '$data["phone"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Contact'),
							array('name' => 'bkg_user_email', 'value' => '$data["email"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Email'),
							array('name' => 'min_date', 'value' => 'DateTimeFormat::DateTimeToLocale($data["min_date"])', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'First date of pickup'),
							array('name' => 'max_date', 'value' => 'DateTimeFormat::DateTimeToLocale($data["max_date"])', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Last date of pickup'),
							array('name' => 'no_of_days', 'value' => '$data["no_of_days"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'No. of days'),
							array('name' => 'count_trip', 'value' => '$data["count_trip"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Count'),
					)));
				}
				?> 
            </div>  

        </div>  
    </div>
</div>
