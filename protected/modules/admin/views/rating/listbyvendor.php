<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    .modal.in .modal-dialog{ width: 90%;}
</style>
<script>
    if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['ratingList<?= $qry['vnd_id'] ?>'] != undefined)
    {
        $(document).off('change.yiiGridView keydown.yiiGridView', $.fn.yiiGridView.settings['ratingList<?= $qry['vnd_id'] ?>'].filterSelector);
    }
</script>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">
            <h3>Rating List - <?= $venModel->vnd_owner; ?> (<?= $venModel->vnd_name ?>)</h3>
            <div class="panel" >
                <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
					<?php
					if (!empty($dataProvider))
					{
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'ratingList' . $qry['vnd_id'],
							'responsiveTable'	 => true,
							'filter'			 => $model,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered mb0',
							'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
							'columns'			 => array(
								array('name'				 => 'rtg_booking_id',
									'value'				 => '$data[bkg_booking_id]',
									'sortable'			 => false,
									'filter'			 => CHtml::activeTextField($model, 'rtg_booking_id', array('class' => 'form-control',)),
									'htmlOptions'		 => array('class' => 'text-center'),
									'headerHtmlOptions'	 => array('class' => 'text-center'),
									'header'			 => 'Booking ID'),
								array('name'	 => 'rtg_driver_name', 'value'	 => function ($data) {
										echo $data['drv_name'];
									},
									'sortable'			 => true,
									'filter'			 => CHtml::activeTextField($model, 'rtg_driver_name', array('class' => 'form-control',)),
									'htmlOptions'		 => array('class' => 'text-center'),
									'headerHtmlOptions'	 => array('class' => 'text-center'),
									'header'			 => 'Driver Name'),
								array('name' => 'rtg_customer_overall', 'value' => '$data[rtg_customer_overall]', 'sortable' => false, 'filter' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Customer Overall Rating'),
								array('name' => 'rtg_customer_driver', 'value' => '$data[rtg_customer_driver]', 'sortable' => false, 'filter' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Customer Driver Rating'),
								array('name' => 'rtg_customer_csr', 'value' => '$data[rtg_customer_csr]', 'sortable' => false, 'filter' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Customer Csr Rating'),
								array('name' => 'rtg_customer_car', 'value' => '$data[rtg_customer_car]', 'sortable' => false, 'filter' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Customer Car Rating'),
								array('name' => 'rtg_customer_review', 'value' => '$data[rtg_customer_review]', 'sortable' => false, 'filter' => false, 'htmlOptions' => array('class' => 'text-center', 'style' => 'min-width:350px'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Customer Review'),
								array('name' => 'rtg_vendor_customer', 'value' => '$data[rtg_vendor_customer]', 'sortable' => false, 'filter' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Vendor Customer'),
								array('name' => 'rtg_vendor_csr', 'value' => '$data[rtg_vendor_csr]', 'sortable' => false, 'filter' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Vendor Csr'),
								array('name' => 'rtg_vendor_review', 'value' => '$data[rtg_vendor_review]', 'sortable' => false, 'filter' => false, 'htmlOptions' => array('class' => 'text-center'), 'headerHtmlOptions' => array('class' => 'text-center'), 'header' => 'Vendor Review'),
								array('name'	 => 'rtg_customer_date', 'value'	 => function ($data) {
										if ($data[rtg_customer_date] != '')
										{
											return DateTimeFormat::DateTimeToLocale($data[rtg_customer_date]);
										}
										else
										{
											return '';
										}
									}, 'sortable'			 => false, 'filter'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Rating Datetime'),
						)));
					}
					?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#ratingList<?= $qry['vnd_id'] ?> .tScore .a1').click(function (e) {
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
