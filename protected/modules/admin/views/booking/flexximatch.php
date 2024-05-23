<h4>Match Booking</h4>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
						<?php
						if ($seat == true)
						{
							if (!empty($dataProvider))
							{
								?>
								<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
									<input type="hidden" value="<?= $bkgId ?>" id="bookingId"/>
									<?php
									$this->widget('booster.widgets.TbGridView', array(
										'id'				 => 'listtobematched',
										'responsiveTable'	 => true,
										'dataProvider'		 => $dataProvider,
										'template'			 => "<div class='panel-heading'><div class='row m0'>
								   <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
								   </div></div>
								   <div class='panel-body'>{items}</div>
								   <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
										'itemsCssClass'		 => 'table table-striped table-bordered mb0',
										'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
										'columns'			 => array(
											array('name' => 'bkg_booking_id', 'value' => '$data["bkg_booking_id"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Booking ID'),
											array('name' => 'bkg_from_city_id', 'value' => '$data["fromcity"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'From'),
											array('name' => 'bkg_to_city_id', 'value' => '$data["tocity"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'To'),
											array('name' => 'bkg_no_person', 'value' => '$data["bkg_no_person"]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Remaining Seats'),
											array('name' => 'bkg_pickup_date', 'value' => 'DateTimeFormat::DateTimeToDatePicker($data["bkg_pickup_date"])." ".DateTimeFormat::DateTimeToTimePicker($data["bkg_pickup_date"])', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Pickup Date'),
											array('name'	 => 'bkg_total_amount', 'value'	 => function($data)
												{
													echo '<i class="fa fa-inr"></i>' . $data['bkg_total_amount'];
												}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Total Amount',),
											//array('class' => 'CCheckBoxColumn', 'id' => 'drv_checked[]',),
											/* array(
											  'class'			 => 'CCheckBoxColumn',
											  'header'		 => 'Match',
											  'id'			 => 'booking_id',
											  'selectableRows' => '{items}',
											  'selectableRows' => null,
											  'value'			 => '$data["bkg_id"]',
											  'headerTemplate' => '<b>{item}<span></span></b>',
											  'visible'		 => true,
											  'htmlOptions'	 => array('style' => 'width: 20px'),
											  ), */
											array('name'	 => 'bkg_booking_id', 'type'	 => 'raw', 'value'	 => function($data)
												{
													if ($data['bkg_booking_id'] != '')
													{
														echo CHtml::link('Match', Yii::app()->createUrl("admin/booking/flexximatch", ["match" => $data['bkg_id']]), ["class" => "viewBooking", "onclick" => "return matched(this)"]);
													}
												}, 'sortable'			 => true, 'htmlOptions'		 => array('class' => 'text-center', 'style' => 'word-break: break-word'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'Match')
									)));
									?>
								</div>
								<?php
							}
							
						}
						else
						{?>
						<h4 class="text-center text-danger">NO SEAT AVAILABLE TO MATCH !!!</h4>
					<?	}
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function matched(obj)
    {
        var href2 = $(obj).attr("href");
        var id = $('#bookingId').val();
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "json",
            "data": {"id": id},
            "success": function (data)
            {
                alert(data.message);
                if (data.success == true)
                {
                    $('.bootbox').modal('hide');
                    // window.location.href = '<?php //echo Yii::app()->createUrl('admin/booking/list');    ?>;
                    window.location.reload(true);
                }
            },
            "error": function (err)
            {
                alert(err);
                $('.bootbox').modal('hide');
            }
        });
        return false;
    }
</script>
