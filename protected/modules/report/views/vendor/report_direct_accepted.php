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
						<label class="control-label">Date Range</label>
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
				<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p4">   
					<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
				<?php $this->endWidget(); ?>
				<?php
				$checkExportAccess = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					?>
					<div class="col-xs-1">
						<?php
						echo CHtml::beginForm(Yii::app()->createUrl('report/vendor/DirectAcceptReport'), "post", []);
						?>
						<input type="hidden" id="bkg_pickup_date1" name="bkg_pickup_date1" value="<?= $model->bkg_pickup_date1 ?>"/>
						<input type="hidden" id="bkg_pickup_date2" name="bkg_pickup_date2" value="<?= $model->bkg_pickup_date2 ?>"/>
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
					'id'				 => 'vendorListGrid',
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
						array('name'	 => 'bkg_booking_id', 'value'	 => function ($data) {
								if ($data['bkg_booking_id'] != '')
								{
									echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
								}
							},
							'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Booking ID'),
						array('name' => 'vnd_name', 'value' => '$data[vnd_name]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Vendor Name'),
						array('name' => 'bkg_vendor_amount', 'value' => '$data[bkg_vendor_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Vendor Amount'),
						array('name' => 'bkg_total_amount', 'value' => '$data[bkg_total_amount]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Booking Amount'),
						array('name' => 'bkg_pickup_date', 'value' => '$data[bkg_pickup_date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Pickup Date'),
						array('name' => 'blg_created', 'value' => '$data[blg_created]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Assigned Date'),
						array(
							'header'			 => 'Action',
							'class'				 => 'CButtonColumn',
							'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
							'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
							'template'			 => '{admfreeze}',
							'buttons'			 => array(
								'admfreeze' => array(
									'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to freeze this vendor?"); 
                                                              if(con){
                                                        try
                                                            {
                                                            $href = $(this).attr("href");
                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                            {
                                                                bootbox.dialog({ 
                                                                message: data, 
                                                                className:"bootbox-sm",
                                                                title:"Freeze Vendor",
                                                                success: function(result){
                                                                if(result.success){
                                                                       
                                                                    }else{
                                                                        alert(\'Sorry error occured\');
                                                                    }
                                                                },
                                                                error: function(xhr, status, error){
                                                                    alert(\'Sorry error occured\');
                                                                }
                                                            });
                                                            }}); 
                                                            }
                                                            catch(e)
                                                            { alert(e); }
                                                            }
                                                            return false;
                                                            
                                                     }',
									'url'		 => 'Yii::app()->createUrl("admpnl/vendor/administrativefreeze", array("vnd_id" => $data[vnd_id],"vnp_is_freeze"=>0))',
									'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\freeze.png',
									'visible'	 => '($data[vnp_is_freeze] == 0 && Yii::app()->user->checkAccess("vendorChangestatus"))',
									'label'		 => '<i class="fa fa-toggle-off"></i>',
									'options'	 => array('data-toggle'	 => 'ajaxModal',
										'id'			 => 'freeze2',
										'style'			 => '',
										'rel'			 => 'popover',
										'data-placement' => 'left',
										'class'			 => 'btn btn-xs admfreeze p0',
										'title'			 => 'Administrative Freeze'),
								),
							)
						)
				)));
			}
			?>
		</div>  

	</div>  
</div>
</div>

<script>
    var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';
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

    function refreshVendorGrid() {
        $('#vendorListGrid').yiiGridView('update');
    }

</script>