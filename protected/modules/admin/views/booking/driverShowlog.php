<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body ">
                    <div class="">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'vendorlog-grid' . $qry['booking_id'],
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
                                            <div class='col-xs-12 col-sm-6 p5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                         </div>
                                     </div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name'	 => 'ttg_bkg_id', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['ttg_bkg_id'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Booking Id'),
										array('name'	 => 'ttg_latitude', 'filter' => FALSE, 'value'	 => function($data) {
												$url = 'http://maps.google.com/';
												$val = "?q=" . $data['ttg_latitude'] . "," . $data['ttg_longitude'];
												$map = $url . $val;
												echo "<a href='$map' target='_blank' >" . $data['ttg_latitude'] . "," . $data['ttg_longitude'] . "</a>";
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Latitude & Longitude'),
										array('name'	 => 'ttg_event_type', 'filter' => FALSE, 'value'	 => function($data) {
												$eventType = BookingLog::model()->driverEventList($data['ttg_event_type']);
												echo $eventType['event_type'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Event'),
										array('name'				 => 'ttg_created',
											'filter'			 => FALSE,
											'value'				 => 'date("d/M/Y h:i A", strtotime($data[ttg_created]))',
											'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2')
											, 'header'			 => 'Created')
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
    $('#vendorlog-grid-<?= $qry['booking_id'] ?> .tScore .a1').click(function (e) {
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