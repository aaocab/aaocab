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
									'id'				 => 'bookinglog-grid-' . $qry['zone_id'],
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
										array('name' => 'vhcModel', 'filter' => FALSE, 'value' => $data['vhcModel'], 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Vehicle Model'),
										array('name' => 'vhc_number', 'value' => $data["vhc_number"], 'filter' => FALSE, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Vehicle Number'),
										array('name' => 'vhc_code', 
										 'value'	 => function ($data) {
												echo CHtml::link($data["vhc_code"], Yii::app()->createUrl("admin/vehicle/view", ["id" => $data['vhc_id']]), ["class" => "", "onclick" => "return viewDetail(this)"])."<br>";
											},
										'filter' => FALSE, 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Vehicle Code'),
										array('name' => 'vhc_end_odometer', 'filter' => FALSE, 'value' => $data['vhc_end_odometer'], 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Odometer Reading'),
										array('name' => 'vhcYear', 'filter' => FALSE, 'value' => $data['vhcYear'], 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Vehicle Year')
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
function viewDetail(obj)
    {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Vehicle Details',
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