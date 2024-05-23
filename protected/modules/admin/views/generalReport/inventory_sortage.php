<?php      
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');    
if($_REQUEST['Booking']['bkg_cancel_id']=='')
{$reason = [9,17];}else{
$reason=$_REQUEST['Booking']['bkg_cancel_id'];
}   
$model->zero_percent; 

?>

<style>
    .ml-20{
        margin-left : 20px;
    }
</style>
<div class="row">

    <div class="col-xs-12">      
        <div class="panel panel-default">
            <div class="panel-body">
              <div class="row">
                  <div class="col-xs-12  pb10">
                      <a href="<?= Yii::app()->createAbsoluteUrl('admin/generalReport/zoneCsv') ?>" target="_blank"> No of Zones of NMI: <?=$countZone?></a>
                      <a href="<?= Yii::app()->createUrl('admin/generalReport/zonesupplydensity') ?>" target="_blank" class="ml-20"> Zone Supply Density Report</a>
                  </div>
              </div>
				<!----------------------------------------------------------------------------->
				<?php


				$model		 = new Booking();
				$model->bkgInvoice->bkg_advance_amount;

				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'booking-form', 'enableClientValidation' => true,
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
				<div class="row"> 
					<div class="col-xs-6 col-sm-3 col-lg-3">
						<?php

						$daterang	 = "Select Date Range";
						$createdate1 = $_REQUEST['Booking']['bkg_create_date1'];
						$createdate2 = $_REQUEST['Booking']['bkg_create_date2'];
						if ($createdate1 != '' && $createdate2 != '')
						{
							$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						}
						if($_REQUEST["YII_CSRF_TOKEN"]=="")
						{
							//$createdate1 = date('Y-m-d', strtotime('-3 months'));
						//	$createdate2 = date('Y-m-d');
							//$daterang	 = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						}
						if($_REQUEST["YII_CSRF_TOKEN"]!="" && $_REQUEST['Booking']['bkg_create_date1']=="")
						{
//                            $createdate1 = "";
//							$createdate2 ="";
//							$daterang	 = "";
						}
						?>
						<label  class="control-label">From & To Create Date Selection</label>
						<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
							<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
							<span><?= $daterang ?></span> <b class="caret"></b>
						</div>
						
						<input name="Booking[bkg_create_date1]" id="Booking_bkg_create_date1" type="hidden" value="<?= $_REQUEST['Booking']['bkg_create_date1'] ?>">
						<input name="Booking[bkg_create_date2]" id="Booking_bkg_create_date2" type="hidden" value="<?= $_REQUEST['Booking']['bkg_create_date2'] ?>">
	




</div>
					<div class="col-xs-12 col-sm-3 col-md-3" style="">
                        <div class="form-group">
                            <label class="control-label">From & To Pickup Date Selection</label>
							<?php

//	$model->bkg_pickup_date1 = date('Y-m-d', strtotime("first day of this month"));
//			$model->bkg_pickup_date2 = date('Y-m-d', strtotime("last day of this month"));



							$daterang			 = "Select Pickup Date Range";
							$bkg_pickup_date1	 = ($_REQUEST['Booking']['bkg_pickup_date1']=='') ? date('Y-m-d', strtotime("first day of this month")) :$_REQUEST['Booking']['bkg_pickup_date1'];
							$bkg_pickup_date2	 = ($_REQUEST['Booking']['bkg_pickup_date2']=='') ? date('Y-m-d', strtotime("last day of this month")) :$_REQUEST['Booking']['bkg_pickup_date2'];

							if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
							}


							if ($_REQUEST["YII_CSRF_TOKEN"] == "")
							{
								//$bkg_pickup_date1	 = date('Y-m-d', strtotime('-3 months'));
								//$bkg_pickup_date2	 = date('Y-m-d');
								//$daterang			 = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
							}
							if ($_REQUEST["YII_CSRF_TOKEN"] != "" && $_REQUEST['Booking']['bkg_pickup_date1'] == "")
							{
//								$bkg_pickup_date1	 = "";
//								$bkg_pickup_date2	 = "";
//								$daterang			 = "";
							}
							?>
                            <div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>

							<input name="Booking[bkg_pickup_date1]" id="Booking_bkg_pickup_date1" type="hidden" value="<?= $_REQUEST['Booking']['bkg_pickup_date1'] ?>">
							<input name="Booking[bkg_pickup_date2]" id="Booking_bkg_pickup_date2" type="hidden" value="<?= $_REQUEST['Booking']['bkg_pickup_date2'] ?>">
                        </div>
					</div>
					<div class="col-xs-12 col-sm-3 col-md-4" >
						<div class="form-group">
							<label class="control-label">Cancel Reasons</label>
                   <?php

if ($_REQUEST['Booking']['bkg_cancel_id'] != '')
				   {
					   $arr = $_REQUEST['Booking']['bkg_cancel_id'];
				   }
				   else
				   {
					   $arr = [9, 17];
				   }

				   $dataCancel = CancelReasons::model()->getlist();
				   $this->widget('booster.widgets.TbSelect2', array(
					   'model'			 => $model,
					   'attribute'		 => 'bkg_cancel_id',
					   'val'			 => $arr,
					   'data'			 => $dataCancel,
					   'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
						   'placeholder'	 => 'Cancel Reason')
				   ));
				   ?>
						</div> 
					</div>

<div class="col-xs-12 col-sm-3 col-md-2 text-center mt20 p5" ><?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
                </div>
				<div class="row"> 
					<div class="col-xs-6 col-sm-3 col-lg-3">

						<label  class="control-label"> DemSup_misfire greater than >= <input name="Booking[dem_sup_misfireCount]" id="Booking_dem_sup_misfireCount" type="text" value="<?php echo ($_REQUEST['Booking']['dem_sup_misfireCount']=='') ? 10 : ($_REQUEST['Booking']['dem_sup_misfireCount']);?>" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 20%"></label>

					</div>
					<div class="col-xs-6 col-sm-3 col-lg-3">

						<label  class="control-label">Total Completed >= <input name="Booking[total_completedCount]" id="Booking_total_completedCount" type="text" value="<?php echo $_REQUEST['Booking']['total_completedCount']?>" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 20%"></label>

					</div>
					<div class="col-xs-6 col-sm-3 col-lg-3">

						<label  class="control-label">Hide value with 0


							<input class="" type="checkbox" id="zero_percent" name="Booking[zero_percent]" <?php
							if ($model->zero_percent == 1 || $_REQUEST['Booking']['zero_percent'] == 'on'  )
							{
								echo 'checked="checked"';
							}
							?> >
							</div>

				</div>
	<div class="row"><br /></div>
			<?php $this->endWidget(); ?>
			<!------------------------------------------------------------------------------------------------------------->
				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'filter' => $model,
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
							array('name' => 'fzoneName', 'filter' => false, 'value' => '$data[fzoneName]', 'sortable' => true,  'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'From Zone'),
							array('name' => 'tzoneName', 'filter' => false, 'value' => '$data[tzoneName]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'To Zone'),
							array('name' => 'cntdemsup', 'filter' => false, 'value' => '$data[cntdemsup]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Count Of booking with DemSup_misfire=1'),
							array('name' => 'cntnmi', 'filter' => false, 'value' => '$data[cntnmi]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Count of booking with Need more supply'),
							array('name' => 'cntreason', 'filter' => false, 'value' => '$data[cntreason]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Count of booking with selected cancelletions reasons'),
                            array('name' => 'tot', 'filter' => false, 'value' => '$data[tot]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Total(NMI+DemSup+Cancel)'),
                            array('name' => 'complete', 'filter' => false, 'value' => '$data[complete]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Total Completed'),
array('name' => 'percentage', 'filter' => false, 'value' => '$data[percentage]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'percentage'),
							)));
				}
				?> 
		</div>  

	</div>  
</div>
</div>
<script>
    $(document).ready(function () {

        var start = '<?= date('1/m/Y'); ?>';
        var end = '<?= date('d/m/Y', strtotime('+1 month')); ?>';

        $('#bkgCreateDate').daterangepicker(
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
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
            $('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));

            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Date Range');
            $('#Booking_bkg_create_date1').val('');
            $('#Booking_bkg_create_date2').val('');

        });


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
                        'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                        'Next 7 Days': [moment(), moment().add(6, 'days')],
                        'Next 15 Days': [moment(), moment().add(15, 'days')],
						'Last 30 Days': [moment().subtract(30, 'days'), moment()],
						'Last 60 Days': [moment().subtract(60, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
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
    });

</script>
