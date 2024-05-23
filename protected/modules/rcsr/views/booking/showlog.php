<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<script>
    if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['bookinglog-grid-<?= $qry['booking_id'] ?>'] != undefined) {
        $(document).off('change.yiiGridView keydown.yiiGridView', $.fn.yiiGridView.settings['bookinglog-grid-<?= $qry['booking_id'] ?>'].filterSelector);
    }
</script>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id' => 'bookinglog-grid-' . $qry['booking_id'],
									'responsiveTable' => true,
									'filter' => $model,
									'dataProvider' => $dataProvider,
									'template' => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass' => 'table table-striped table-bordered mb0',
									'htmlOptions' => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns' => array(
										array('name' => 'blgAdmin.adm_fname', 'value' => function($data) {
												$UserData = $data->getUserdataByType($data->blg_user_type);
												echo $UserData['user'];
											}, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'User'),
										array('name' => 'blg_user_type', 'filter' => CHtml::activeDropDownList($model, 'blg_user_type', ['' => ''] + BookingLog::model()->logList(), array('class' => 'form-control',)), 'value' => function($data) {
										$UserData = $data->getUserdataByType($data->blg_user_type);
										echo $UserData['user_type'];
									}, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $model->getAttributeLabel('blg_user_type')),
										array('name' => 'blg_desc', 'filter' => CHtml::activeTextField($model, 'blg_desc', array('class' => 'form-control',)), 'value' => function($data) {
										echo $data->blg_desc;
									}, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-4'), 'header' => $model->getAttributeLabel('blg_desc')),
										array('name' => 'blg_event_id','headerHtmlOptions' => array('class' => 'col-xs-4'),
											'filter' => CHtml::activeDropDownList($model, 'blg_event_id', ['' => ''] + BookingLog::model()->eventList(), array('class' => 'form-control',)),
											'value' => function($data) {
										switch ($data->blg_event_id)
										{
											case '13':
												$href = Yii::app()->createUrl("/rcsr/booking/listlogdetails", array("refId" => $data->blg_ref_id, "eventId" => $data->blg_event_id));
												echo CHtml::link(BookingLog::model()->getEventByEventId($data->blg_event_id), $href, ['refId' => $data->blg_ref_id, 'eventId' => $data->blg_event_id, 'blgId' => $data->blg_id, 'class' => 'a1']);
												  if( $data->blg_booking_id>0 && $data->blg_ref_id>0){
                                                                                                   $refTypes1 = SmsLog::model()->showRefTypes($data->blg_booking_id,$data->blg_ref_id);
                                                                                                   echo $refTypes1;
                                                                                                }
                                                                                                break;
											case '14':
												$href = Yii::app()->createUrl("/rcsr/booking/listlogdetails", array("refId" => $data->blg_ref_id, "eventId" => $data->blg_event_id));
												echo CHtml::link(BookingLog::model()->getEventByEventId($data->blg_event_id), $href, ['refId' => $data->blg_ref_id, 'eventId' => $data->blg_event_id, 'blgId' => $data->blg_id, 'class' => 'a1']);
                                                                                                echo '<br>';
                                                                                                if($data->blg_booking_id>0 && $data->blg_ref_id>0 && $data->blgBooking->bkg_agent_id>0){
                                                                                                   $refTypes = EmailLog::model()->showRefTypesAgent($data->blg_booking_id,$data->blg_ref_id); 
                                                                                                   echo $refTypes;
                                                                                                   break;
                                                                                                }
                                                                                                if( $data->blg_booking_id>0 && $data->blg_ref_id>0){
                                                                                                   $refTypes = EmailLog::model()->showRefTypes($data->blg_booking_id,$data->blg_ref_id);
                                                                                                   echo $refTypes;
                                                                                                }
												break;
											case '54':
												$href = Yii::app()->createUrl("/rcsr/booking/listlogdetails", array("refId" => $data->blg_ref_id, "eventId" => $data->blg_event_id));
												echo CHtml::link(BookingLog::model()->getEventByEventId($data->blg_event_id), $href, ['refId' => $data->blg_ref_id, 'eventId' => $data->blg_event_id, 'blgId' => $data->blg_id, 'class' => 'a1']);
												break;
											case '55':
												$href = Yii::app()->createUrl("/rcsr/booking/listlogdetails", array("refId" => $data->blg_ref_id, "eventId" => $data->blg_event_id));
												echo CHtml::link(BookingLog::model()->getEventByEventId($data->blg_event_id), $href, ['refId' => $data->blg_ref_id, 'eventId' => $data->blg_event_id, 'blgId' => $data->blg_id, 'class' => 'a1']);
												break;
											case '56':
												$href = Yii::app()->createUrl("/rcsr/booking/listlogdetails", array("refId" => $data->blg_ref_id, "eventId" => $data->blg_event_id));
												echo CHtml::link(BookingLog::model()->getEventByEventId($data->blg_event_id), $href, ['refId' => $data->blg_ref_id, 'eventId' => $data->blg_event_id, 'blgId' => $data->blg_id, 'class' => 'a1']);
												break;
                                                                                        case '57':
                                                                                            $href = Yii::app()->createUrl("/rcsr/booking/listlogdetails", array("refId" => $data->blg_ref_id, "eventId" => $data->blg_event_id));
                                                                                            echo CHtml::link(BookingLog::model()->getEventByEventId($data->blg_event_id), $href, ['refId' => $data->blg_ref_id, 'eventId' => $data->blg_event_id, 'blgId' => $data->blg_id, 'class' => 'a1']);
                                                                                            break;
                                                                                        case '58':
                                                                                            $href = Yii::app()->createUrl("/rcsr/booking/listlogdetails", array("refId" => $data->blg_ref_id, "eventId" => $data->blg_event_id));
                                                                                            echo CHtml::link(BookingLog::model()->getEventByEventId($data->blg_event_id), $href, ['refId' => $data->blg_ref_id, 'eventId' => $data->blg_event_id, 'blgId' => $data->blg_id, 'class' => 'a1']);
                                                                                            break;
											case '70':
												echo BookingLog::model()->getEventByEventId($data->blg_event_id);
												if ($data->blg_mark_car == '1')
												{
													echo " ( Car )";
												}
												else if ($data->blg_mark_driver == '1')
												{
													echo " ( Driver )";
												}
												else if ($data->blg_mark_customer == '1')
												{
													echo " ( Customer )";
												}
												else if ($data->blg_mark_vendor == '1')
												{
													echo " ( Vendor )";
												}
                                                                                                
												break;
											default :
												echo BookingLog::model()->getEventByEventId($data->blg_event_id);
                                                                                                if($data->blg_ref_id >= 200 AND $data->blg_ref_id <=299)
                                                                                                {
                                                                                                    echo " - ".BookingLog::model()->getRefEventByRefEventId($data->blg_ref_id);                                                                                                    
                                                                                                }
												break;
										}
									},
											'sortable' => false,
											'htmlOptions' => array('class' => 'tScore'),
											'headerHtmlOptions' => array('class' => 'col-xs-2'),
											'header' => $model->getAttributeLabel('blg_event_id'),
										),
										array('name' => 'blg_created', 'filter' => FALSE, 'value' => 'date("d/M/Y h:i A", strtotime($data->blg_created))', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => $model->getAttributeLabel('blg_created'))
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
<script>
    $('#bookinglog-grid-<?= $qry['booking_id'] ?> .tScore .a1').click(function (e) {
        e.preventDefault();
        return showReturnDetails(this);
    });
    function showReturnDetails(obj, type) {
        var span = 8;
        var that = $(obj);
        var status = that.data('status');
        var rowid = that.attr('blgId');
        var tr = $('#relatedinfo_' + type + '_' + rowid);
        var parent = that.parents('tr').eq(0);
        if (status && status == 'on') {
            return;
        }
        that.data('status', 'on');
        if (tr.length && !tr.is(':visible'))
        {
            tr.slideDown();
            that.data('status', 'off');
            return false;
        } else if (tr.length && tr.is(':visible'))
        {
            tr.slideUp();
            that.data('status', 'off');
            return false;
        }

        if (tr.length)
        {
            tr.find('td').html('<?= $loadingPic ?>');
            if (!tr.is(':visible')) {
                tr.slideDown();
            }
        } else
        {
            var td = $('<td/>').html('<?= $loadingPic ?>').attr({'colspan': span});
            tr = $('<tr/>').prop({'id': 'relatedinfo_' + type + '_' + rowid}).append(td);
            /* we need to maintain zebra styles :) */
            var fake = $('<tr class="hide"/>').append($('<td/>').attr({'colspan': span}));
            parent.after(tr);
            tr.after(fake);
        }
//	var data = $.extend({$data}, {id:rowid});
        $href = that.attr('href');
        $.ajax({
            url: $href,
            success: function (data) {
                tr.find('td').html(data);
                that.data('status', 'off');
            },
            error: function ()
            {
                tr.find('td').html('{$this->ajaxErrorMessage}');
                that.data('status', 'off');
            }
        });
        return false;
    }
</script>       
