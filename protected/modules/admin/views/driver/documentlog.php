<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">          
            <div class="panel" >
                    <div class="">
                        <div >
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'driverlog-grid' . $qry['booking_id'],
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
										array('name'	 => 'name', 'filter' => FALSE, 'value'	 => function($data) {									
													echo $data['name'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'User'),
										array('name'	 => 'type', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['type'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'User Type'),
										array('name'	 => 'clg_desc', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['clg_desc'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Description'),
										array('name'	 => 'clg_event_id', 'filter' => FALSE, 'value'	 => function($data) {
												echo ContactLog::model()->getEventByEventId($data['clg_event_id']);
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Event'),
										array('name'				 => 'clg_created', 'filter'			 => FALSE, 'value'				 => 'date("d/M/Y h:i A", strtotime($data[clg_created]))',
											'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'),
											'header'			 => 'Created')
								)));
							}
							?>
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