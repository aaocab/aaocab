<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body p0">
                    <div class="">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'bidlog-grid' . $qry['booking_id'],
									'responsiveTable'	 => true,
									// 'filter' => FALSE,
									'dataProvider'		 => $dataProvider,
									'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'>
                                        <div class='row m0'>
                                          
                                         </div>
                                     </div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name'	 => 'bkg_booking_id', 'filter' => FALSE, 'value'	 => function($data)
											{
												echo CHtml::link($data["bkg_booking_id"], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']) . "<br>";
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Booking Id'),
										array('name'	 => 'bkg_trip_id', 'filter' => FALSE, 'value'	 => function($data)
											{
												echo $data['trip_id'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Trip Id'),
										array('name'	 => 'vnd_code', 'filter' => FALSE, 'value'	 => function($data)
											{
												echo $data['vnd_code'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Vendor Code'),
										array('name'	 => 'Bid Amount', 'filter' => FALSE, 'value'	 => function($data)
											{
												if(Yii::app()->user->checkAccess('showBidAmount'))
												{
													echo $data['bvr_bid_amount'];
												}
												else{
													echo 'Bid amount set by the vendor.';
												}
												
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Bid Amount'),
										array('name'	 => 'Bid Accept Status', 'filter' => FALSE, 'value'	 => function($data)
											{

												if ($data['bvr_accepted'] == 1)
												{
													echo "Bid set";
												}
												else if ($data['bvr_assigned'] == 1)
												{
													echo "Bid accepted";
												}
												else if ($data['bvr_accepted'] == 2)
												{
													echo "Bid denied ";
												}
												else
												{
													echo "Bid lost ";
												}
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Bid Status'),
										array('name'				 => 'bvr_accepted_at',
											'filter'			 => FALSE,
											'value'				 => 'date("d/M/Y h:i A", strtotime($data[bvr_accepted_at]))',
											'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2')
											, 'header'			 => 'Bid Accepted At'),
										array('name'				 => 'bvr_created_at',
											'filter'			 => FALSE,
											'value'				 => 'date("d/M/Y h:i A", strtotime($data[bvr_created_at]))',
											'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2')
											, 'header'			 => 'Bid Created At')
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
    $('#bidlog-grid-<?= $qry['booking_id'] ?> .tScore .a1').click(function (e) {
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