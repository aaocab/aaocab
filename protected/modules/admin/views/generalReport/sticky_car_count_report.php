<div class="panel">  
    <div class="panel-body">
        <div class="mb10"><b>
				<div class="row"> 
					<?php
					$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'drvAppUsage-form', 'enableClientValidation' => true,
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

                    <div class="col-xs-12 col-sm-4 col-md-4" style="">
                        <div class="form-group">
                            <label class="control-label">Date Range (Default: Last 7 days)</label>
							<?php
							$daterang			 = "Select Date Range";
							$bkg_pickup_date1	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
							$bkg_pickup_date2	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
							if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
							}
							?>
                            <div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
							<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>

                        </div></div>
					<div class="col-xs-12 col-sm-4 col-md-3"> 
					</div>     
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
					<?php $this->endWidget(); ?>
                </div>
			</b></div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th> </th>
                    <th>NORTH</th>
                    <th>SOUTH</th>
                    <th>CENTRAL</th>
                    <th>WEST</th>
					<th>EAST</th>
                    <th>NORTH-EAST</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $stickyCarScore[0]['h0']; ?></td>  
					<td><?= $stickyCarScore[0]['North'] != null ? $stickyCarScore[0]['North'] : 0; ?></td>
					<td><?= $stickyCarScore[0]['West'] != null ? $stickyCarScore[0]['West'] : 0; ?></td>
					<td><?= $stickyCarScore[0]['Central'] != null ? $stickyCarScore[0]['Central'] : 0; ?></td>
					<td><?= $stickyCarScore[0]['South'] != null ? $stickyCarScore[0]['South'] : 0; ?></td>
					<td><?= $stickyCarScore[0]['East'] != null ? $stickyCarScore[0]['East'] : 0; ?></td>
					<td><?= $stickyCarScore[0]['NorthEast'] != null ? $stickyCarScore[0]['NorthEast'] : 0; ?></td>
                </tr>
				<tr>
                    <td><?= $stickyCarScore[1]['h1']; ?></td>  
                    <td><?= $stickyCarScore[1]['North'] != null ? $stickyCarScore[1]['North'] : 0; ?></td>
					<td><?= $stickyCarScore[1]['West'] != null ? $stickyCarScore[1]['West'] : 0; ?></td>
					<td><?= $stickyCarScore[1]['Central'] != null ? $stickyCarScore[1]['Central'] : 0; ?></td>
					<td><?= $stickyCarScore[1]['South'] != null ? $stickyCarScore[1]['South'] : 0; ?></td>
					<td><?= $stickyCarScore[1]['East'] != null ? $stickyCarScore[1]['East'] : 0; ?></td>
					<td><?= $stickyCarScore[1]['NorthEast'] != null ? $stickyCarScore[1]['NorthEast'] : 0; ?></td>
                </tr>
				<tr>
                    <td><?= $stickyCarScore[2]['h2']; ?></td>  
                    <td><?= $stickyCarScore[2]['North'] != null ? $stickyCarScore[2]['North'] : 0; ?></td>
					<td><?= $stickyCarScore[2]['West'] != null ? $stickyCarScore[2]['West'] : 0; ?></td>
					<td><?= $stickyCarScore[2]['Central'] != null ? $stickyCarScore[2]['Central'] : 0; ?></td>
					<td><?= $stickyCarScore[2]['South'] != null ? $stickyCarScore[2]['South'] : 0; ?></td>
					<td><?= $stickyCarScore[2]['East'] != null ? $stickyCarScore[2]['East'] : 0; ?></td>
					<td><?= $stickyCarScore[2]['NorthEast'] != null ? $stickyCarScore[2]['NorthEast'] : 0; ?></td>
                </tr>
				<tr>
                    <td><?= $stickyCarScore[3]['h3']; ?></td>  
					<td><?= $stickyCarScore[3]['North'] != null ? $stickyCarScore[3]['North'] : 0; ?></td>
					<td><?= $stickyCarScore[3]['West'] != null ? $stickyCarScore[3]['West'] : 0; ?></td>
					<td><?= $stickyCarScore[3]['Central'] != null ? $stickyCarScore[3]['Central'] : 0; ?></td>
					<td><?= $stickyCarScore[3]['South'] != null ? $stickyCarScore[3]['South'] : 0; ?></td>
					<td><?= $stickyCarScore[3]['East'] != null ? $stickyCarScore[3]['East'] : 0; ?></td>
					<td><?= $stickyCarScore[3]['NorthEast'] != null ? $stickyCarScore[3]['NorthEast'] : 0; ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    var start = '<?= date('d/m/Y', strtotime($model->bkg_pickup_date1)); ?>';
    var end = '<?= date('d/m/Y', strtotime($model->bkg_pickup_date2)); ?>';
    function setFilter(obj)
    {
        $('#export_filter1').val(obj.value);
    }
    $('#bkgPickupDate').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
                "showDropdowns": true,
                "alwaysShowCalendars": true,
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(15, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
        $('#Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
        $('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
        $('#bkgPickupDate span').html('Select Pickup Date Range');
        $('#Booking_bkg_pickup_date1').val('');
        $('#Booking_bkg_pickup_date2').val('');
    });
</script>


