<style>
    .panel-body {
        padding-top: 0;
        padding-bottom: 0;
    }
    .table>tbody>tr>th {
        vertical-align: middle
    }
    .table>tbody>tr>td, .table>tbody>tr>th {
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<?php
Yii::import('zii.widgets.CBaseListView');
?>
<section id="section7">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 book-panel2">
                <div class="container p0">
                    <div class="row">
						<?php
						$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
							'id'					 => 'booking-account-form', 'enableClientValidation' => true,
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
                        <div class="col-xs-12 col-sm-6">
							<?= $form->textFieldGroup($model, 'search', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter booking ID, Customer name, Customer email, Customer phone', 'value' => $searchTxt)))) ?>
                        </div>

                        <div class="col-xs-12 col-sm-3" text-center mt20 p5">
							<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
						</div>
						<?php $this->endWidget(); ?>
						<div class="col-xs-12">
							<div class="profile-right-panel">
								<h3 class="m0 weight400 mb20">Find booking results</h3>
								<div class="row">
									<div class="col-xs-12">
										<button type="submit" class="btn btn-success" onclick="setFlag();">Set accounting flag</button>
										<button type="submit" class="btn btn-success" onclick="unsetFlag();">Clear accounting flag</button>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										<?php
										if (!empty($dataProvider))
										{
											/* @var $dataProvider TbGridView */
											$params									 = array_filter($_REQUEST);
											$dataProvider->getPagination()->params	 = $params;
											$dataProvider->getSort()->params		 = $params;
											$this->widget('booster.widgets.TbGridView', array(
												'responsiveTable'	 => true,
												'dataProvider'		 => $dataProvider,
												'id'				 => 'bookingAccountGrid',
												'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5 table-responsive'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
												'itemsCssClass'		 => 'table-striped table-bordered dataTable mb0',
												'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
												//    'ajaxType' => 'POST',
												'columns'			 => array(
													array(
														'class'			 => 'CCheckBoxColumn',
														'header'		 => 'html',
														'id'			 => 'bkg_account_flag',
														'selectableRows' => '{items}',
														'selectableRows' => 2,
														'value'			 => '$data["bkg_id"]',
														'headerTemplate' => '<label>{item}<span></span></label>',
														'htmlOptions'	 => array('style' => 'width: 20px'),
													),
													array('name'	 => 'bkg_booking_id', 'value'	 => function($data) {
															if ($data['bkg_booking_id'] != '')
															{
																echo CHtml::link($data['bkg_booking_id'], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "return viewBooking(this)"]);
															}
														}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center checkbox1'), 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Booking ID'),
													array('name'	 => 'bkg_account_flag', 'value'	 => function($data) {
															if ($data['bkg_account_flag'] == '0')
															{
																echo "<b>N</b>";
															}
															else if ($data['bkg_account_flag'] == '1')
															{
																echo "<b>Y</b>";
															}
														}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Flag'),
													array('name'	 => 'bkg_status', 'value'	 => function($data) {
															echo Booking::model()->getBookingStatus($data['bkg_status']);
														}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Status'),
													array('name' => 'create_date', 'value' => '$data[create_date]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Booking Date'),
													array('name' => 'pickup_date', 'value' => '$data[pickup_date]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Pickup Date'),
													array('name'	 => 'bkg_contact_no', 'value'	 => function ($data) {
															if ($data['phone'] != '')
															{
																return '+' . $data['countryCode'] . $data['phone'];
															}
														}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Contact No'),
													array('name' => 'name', 'value' => '$data[name]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Customer name'),
													array('name' => 'from_city', 'value' => '$data[from_city]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'From city'),
													array('name' => 'to_city', 'value' => '$data[to_city]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'To city'),
													array('name' => 'vnd_name', 'value' => '$data[vnd_name]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Vendor'),
													array('name' => 'drv_name', 'value' => '$data[drv_name]', 'sortable' => true, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Driver'),
													array('name'	 => 'vct_label', 'value'	 => function($data) {
															if ($data['vct_label'] != '')
															{
																echo $data['vct_label'] . " " . $data['vct_desc'];
															}
														}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Car'),
													array('name'	 => 'bkg_total_amount', 'value'	 => function($data) {
															echo '<i class="fa fa-inr"></i>' . round($data['bkg_total_amount']);
														}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Amount'),
													array('name'	 => 'bkg_advance_amount', 'value'	 => function($data) {
															echo '<i class="fa fa-inr"></i>' . round($data['bkg_advance_amount']);
														}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Advance'),
													array('name'	 => 'bkg_amount_due', 'value'	 => function($data) {
															echo '<i class="fa fa-inr"></i>' . round($data['bkg_due_amount']);
														}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Pending'),
													array(
														'header'			 => 'Action',
														'class'				 => 'CButtonColumn',
														'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
														'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
														'template'			 => '{on}{off}',
														'buttons'			 => array(
															'on'			 => array(
																'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to clear accounting flag for this booking?"); 
                                                              if(con){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "json",
                                                                        success: function(data1){
                                                                                if(data1.success){
                                                                                    reloadBookingAccountGrid();
                                                                                }else{
                                                                                    alert(\'Sorry error occured\');
                                                                                }

                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                                    }
                                                                    return false;
                                                    }',
																'url'		 => 'Yii::app()->createUrl("admpnl/booking/accountflag", array("bkg_id" => $data[bkg_id],"bkg_account_flag"=>$data[bkg_account_flag]))',
																'imageUrl'	 => false,
																'visible'	 => '$data[bkg_account_flag]==1',
																'label'		 => '<i class="fa fa-toggle-on"></i>',
																'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example', 'style' => 'margin-right: 2px;margin-left: 2px;padding: 3px 6px ', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs btn-success conÉnable', 'title' => 'Unset Accounting Flag')
															),
															'off'			 => array(
																'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to set accounting flag for this booking?"); 
                                                              if(con){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "json",
                                                                        success: function(data1){
                                                                                if(data1.success){
                                                                                    reloadBookingAccountGrid();
                                                                                }else{
                                                                                    alert(\'Sorry error occured\');
                                                                                }
                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                                    }
                                                                    return false;
                                                    }',
																'url'		 => 'Yii::app()->createUrl("admpnl/booking/accountflag", array("bkg_id" => $data[bkg_id],"bkg_account_flag"=>$data[bkg_account_flag]))',
																'imageUrl'	 => false,
																'visible'	 => '$data[bkg_account_flag] == 0',
																'label'		 => '<i class="fa fa-toggle-off"></i>',
																'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example', 'style' => 'margin-right: 2px;margin-left: 2px;padding: 3px 6px ', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs btn-primary conDelete', 'title' => 'Set Accounting Flag'),
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
				</div>
			</div>
		</div>
	</div>
</section>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script type="text/javascript">
    function reloadBookingAccountGrid() {
        $.fn.yiiGridView.update('bookingAccountGrid');
    }
    function setFlag() {
        var checked = [];
        $('#bookingAccountGrid input[name="bkg_account_flag[]"]').each(function (i) {
            if (this.checked) {
                checked.push(this.value);
            }
        });

        if (checked.length <= 0)
        {
            alert("Please select at least one bookingId");
            return false;
        }

        var href = '<?= Yii::app()->createUrl("admin/report/setaccountflag"); ?>';
        $.ajax({
            'type': 'GET',
            'url': href,
            'dataType': 'text',
            data: {"bkIds": checked.toString()},
            success: function (data) {
                if (data == '1') {
                    reloadBookingAccountGrid();
                } else {
                    alert("Already Set");
                }
            },
            error: function (x) {
                alert(x);
            }
        }
        );
    }


    function unsetFlag() {
        var checked = [];
        $('#bookingAccountGrid input[name="bkg_account_flag[]"]').each(function (i) {
            if (this.checked) {
                checked.push(this.value);
            }
        });

        if (checked.length <= 0)
        {
            alert("Please select at least one bookingId");
            return false;
        }

        var href = '<?= Yii::app()->createUrl("admin/report/clearaccountflag"); ?>';
        $.ajax({
            'type': 'GET',
            'url': href,
            'dataType': 'text',
            data: {"bkIds": checked.toString()},
            success: function (data) {
                if (data == '1') {
                    reloadBookingAccountGrid();
                } else {
                    alert("Already Unset");
                }
            },
            error: function (x) {
                alert(x);
            }
        }
        );
    }


    $("#bkg_account_flag_all").click(function () {
        if (this.checked) {
            $('#bookingAccountGrid .checker span').addClass('checked');
            $('#bookingAccountGrid input[name="bkg_account_flag[]"]').attr('checked', 'true');
        } else {
            $('#bookingAccountGrid .checker span').removeClass('checked');
            $('#bookingAccountGrid input[name="bkg_account_flag[]"]').attr('checked', 'false');

        }

    })
</script>