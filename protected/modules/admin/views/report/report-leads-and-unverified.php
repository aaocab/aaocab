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
						'id'					 => 'leadsUnverifiedForm', 'enableClientValidation' => true,
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
					<div class="col-xs-6 col-sm-4 col-lg-3">
						<?
						//$daterang = date('F d, Y') . " - " . date('F d, Y');
						$daterang	 = "Select Date Range";
						$createdate1 = ($model->lfu_from_date == '') ? '' : $model->lfu_from_date;
						$createdate2 = ($model->lfu_to_date == '') ? '' : $model->lfu_to_date;
						if ($createdate1 != '' && $createdate2 != '')
						{
							$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
						}
						?>
						<label  class="control-label">From & To Date Selection</label>
						<div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
							<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
							<span><?= $daterang ?></span> <b class="caret"></b>
						</div>
						<?
						echo $form->hiddenField($model, 'lfu_from_date');
						echo $form->hiddenField($model, 'lfu_to_date');
						?>
					</div>
                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?></div>
						<?php $this->endWidget(); ?>
                </div>
				<?php
				//$checkContactAccess = Yii::app()->user->checkAccess("bookingContactAccess");
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'>
													<div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div>
													</div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
						'columns'			 => array
						(
							array('name'	 => 'user_fullname', 'value'	 => '$data["user_fullname"]','sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Customer Name'),
//							array('name'	 => 'user_phone', 'value'	 => '$data["user_phone"]','sortable'			 => true,
//								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
//								'header'			 => 'Phone'),
//							array('name'				 => 'user_email', 'value'  => '$data["user_email"]', 'sortable'			 => true,
//								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
//								'header'			 => 'Email'),
							array('name'				 => 'booking_id', 'value'  => '$data["booking_id"]', 'sortable'			 => true,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Lead/Booking Id'),
							array('name'				 => 'fromCity', 'value'	=> '$data["fromCity"]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'From'),
							array('name'				 => 'toCity', 'value'	=> '$data["toCity"]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'To'),
							array('name'				 => 'bkgAmount', 'value'	=> '$data["bkgAmount"]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Bkg Amount'),
							array('name'				 => 'pickupDate', 'value'	=> function($data)
							{
								echo date('d/m/Y h:i A', strtotime($data['pickupDate']));
								
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Travel Date'),
							array('name'				 => 'price_was_high_comment', 'value'	=> '$data["price_was_high_comment"]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Competitor Quote'),
							array('name'				 => 'price_was_high', 'value'	=> '$data["price_was_high"]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Price Was High'),
							array('name'				 => 'will_book_later', 'value'	=> '$data["will_book_later"]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Will Book Later'),
							array('name'				 => 'will_book_later_tentative', 'value'	=> '$data["will_book_later_tentative"]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Tentative Requested'),
							array('name'				 => 'call_me_please', 'value'	=> '$data["call_me_please"]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Call me please'),
							array('name'				 => 'other_comment', 'value'	=> '$data["other_comment"]', 'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
								'header'			 => 'Other Comments'),
							array('name'				 => 'createDate', 'value'	=> function($data)
							{
								echo date('d/m/Y h:i A', strtotime($data['createDate']));
								
							}, 'sortable'			 => true,
							'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Create Date'),		
							
							
					)));
				}
				?> 
            </div>  

        </div>  
    </div>
</div>
<script>
    $(document).ready(function () {


        var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
        var end = '<?= date('d/m/Y'); ?>';

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
            $('#LeadFollowup_lfu_from_date').val(start1.format('YYYY-MM-DD'));
            $('#LeadFollowup_lfu_to_date').val(end1.format('YYYY-MM-DD'));

            $('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#bkgCreateDate span').html('Select Date Range');
            $('#LeadFollowup_lfu_from_date').val('');
            $('#LeadFollowup_lfu_to_date').val('');

        });


    });
    function viewDetail(obj) {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Booking Details',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
                if ($('body').hasClass("modal-open"))
                {
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }

            }
        });
        return false;
    }
</script>
