<div class="row">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
					<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'vehicletype-form', 'enableClientValidation' => true,
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
                    <div class="col-xs-12 col-sm-3 col-md-2"> 
                        <div class="form-group">
							<?=
							$form->datePickerGroup($model, 'approve_from_date', array('label'			 => 'From Date',
								'widgetOptions'	 => array('options'		 => array(
										'autoclose'	 => true,
										'startDate'	 => date(),
										'format'	 => 'dd/mm/yyyy'),
									'htmlOptions'	 => array('placeholder' => 'From Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
							?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-2"> 
                        <div class="form-group">
							<?=
							$form->datePickerGroup($model, 'approve_to_date', array('label'			 => 'To Date',
								'widgetOptions'	 => array('options'		 => array(
										'autoclose'	 => true,
										'startDate'	 => date(),
										'format'	 => 'dd/mm/yyyy'),
									'htmlOptions'	 => array('placeholder' => 'To Date')), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
							?>
                        </div>
                    </div>
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20">
                        <button class="btn btn-primary" type="submit" style="width: 185px;">Search</button>
                    </div>
					<?php $this->endWidget(); ?>
					<div class="col-xs-1">
					<?php
					$checkExportAccess	 = false;
					if ($roles['rpt_export_roles'] != null)
					{
						$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
					}
					if ($checkExportAccess)
					{
						echo CHtml::beginForm(Yii::app()->createUrl('report/driver/csrApproveList'), "post", []);
						?>
						<input type="hidden" id="approve_from_date" name="approve_from_date" value="<?= $model->approve_from_date ?>"/>
						<input type="hidden" id="approve_to_date" name="approve_to_date" value="<?= $model->approve_to_date ?>"/>
						<input type="hidden" id="export" name="export" value="true"/>
						<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
						<?php echo CHtml::endForm(); ?>	
					<?php } ?>
				</div>
                </div>
				
				
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6 col-md-6 table-responsive" style="float: left; padding-left: 25px;">
				<div class="panel panel-default">
					<div class="panel-body ">
                        <table class="table table-bordered align-center">
                            <tr class="blue2 white-color">
                                <td align="center"><b>Member</b></td>
                                <td align="center"><b>Cars Approved</b></td>
                                <td align="center"><b>Drivers Approved</b></td>
                            </tr>
							<?php
							$carCnt		 = $driverCnt	 = 0;
							if (count($driverDataProvider) > 0)
							{
								foreach ($driverDataProvider as $data)
								{
									$carCnt		 = ($carCnt + $data['totalCarApprove']);
									$driverCnt	 = ($driverCnt + $data['toatalDrvApprove']);
									?>
									<tr>
										<td><?php echo $data['csr']; ?></td>
										<td align="right"><?php echo $data['totalCarApprove']; ?></td>
										<td align="right"><?php echo $data['toatalDrvApprove']; ?></td>

									</tr>
									<?php
									$ctr		 = ($ctr + 1);
								}
								?>
								<tr>
									<td><b>Total</b></td>
									<td align="right"><?php echo $carCnt; ?></td>
									<td align="right"><?php echo $driverCnt; ?></td>
								</tr>
								<?php
							}
							else
							{
								?>
								<tr><td colspan="10">No Records Yet Found.</td></tr>
								<?php
							}
							?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
