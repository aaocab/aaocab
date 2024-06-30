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
					$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'booking-form', 'enableClientValidation' => true,
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
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-2">
						<?php
						$dataYear		 = BookingSub::model()->getYear();
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'year',
							'val'			 => $model->year,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($dataYear)),
							'htmlOptions'	 => array('style' => 'width:50%', 'placeholder' => 'Year')
						));
						$datamonth		 = BookingSub::model()->getMonth();
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'month',
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($datamonth)),
							'htmlOptions'	 => array('style' => 'width:50%', 'placeholder' => 'Month')
						));
						?>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Category</label>
						<?php
						$dataCategory	 = BookingSub::model()->getCategory();
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'category',
							'val'			 => $model->category,
							'asDropDownList' => FALSE,
							'options'		 => array('data' => new CJavaScriptExpression($dataCategory), 'allowClear' => true),
							'htmlOptions'	 => array('style' => 'width:25%', 'placeholder' => 'Select Category')
						));
						?>
                    </div>
                    <div class="col-xs-12 col-sm-3  col-md-2 col-lg-1 mt20 pt5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
                    </div>
					<?php $this->endWidget(); ?>
                </div>

				<div class="panel panel-primary  compact" id="yw3">
					<div class="panel-heading"><div class="row m0">

							<?php
							if (count($dataProvider)!= 0)
							{
								?>
								<div class="col-xs-12 col-sm-6 pt5"><div class="summary">Displaying 1-<?php echo count($dataProvider) ?> of <?php echo count($dataProvider) ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0"></div>
<?php } ?>

						</div></div>
					<div class="panel-body table-responsive"><table class="table table-striped table-bordered dataTable mb0 table">
							<thead>
								<tr>
									<th class="col-xs-2" id="yw3_c0">Category</th><th class="col-xs-2" id="yw3_c1">Month</th><th class="col-xs-2" id="yw3_c2">Current Count</th><th class="col-xs-2" id="yw3_c3">Future Count</th><th class="col-xs-2" id="yw3_c4">Total Count</th><th class="col-xs-2" id="yw3_c5">Current Amount</th><th class="col-xs-2" id="yw3_c6">Future Amount</th><th class="col-xs-2" id="yw3_c7">Total Vendor Amount</th><th class="col-xs-2" id="yw3_c8">Gozo Amount</th><th class="col-xs-2" id="yw3_c9">GST</th></tr>
							</thead>
							<tbody>
								<?php
								for ($i = 0; $i < count($dataProvider); $i++)
								{
									?>
									<tr class="<?php $i % 2 == 0 ? 'even' : 'odd' ?>">
										<td><?php echo $dataProvider[$i][0]['category'] ?></td>
										<td><?php echo $dataProvider[$i][0]['month'] ?></td>
										<td><?php echo $dataProvider[$i][0]['currentCount1'] ?></td>
										<td><?php echo $dataProvider[$i][0]['fCount'] ?></td>
										<td><?php echo $dataProvider[$i][0]['totalCount1'] ?></td>
										<td><?php echo $dataProvider[$i][0]['CurrentAmount'] ?></td>
										<td><?php echo $dataProvider[$i][0]['FutureAmount'] ?></td>
										<td><?php echo $dataProvider[$i][0]['totalVendorAmount'] ?></td>
										<td><?php echo $dataProvider[$i][0]['GozoAmount'] ?></td>
										<td><?php echo $dataProvider[$i][0]['ServiceTax'] ?></td>
									</tr>
									<?php
								}
								if (count($dataProvider) == 0)
								{
									?>
									<tr><td colspan="10" class="empty"><span class="empty">No results found.</span></td></tr>
								<?php }
								?>
							</tbody>
						</table></div>

					<?php
					if (count($dataProvider) != 0)
					{
						?>
						<div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"><div class="summary">Displaying 1-<?php echo count($dataProvider) ?> of <?php echo count($dataProvider) ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="keys" style="display:none" title="/aaohome/report/money"><span></span><span></span><span></span><span></span><span></span><span></span></div>
<?php } ?>
				</div>
            </div>  

        </div>  
    </div>
</div>









