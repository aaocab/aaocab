<style type="text/css">
    @media (min-width: 992px){
        .modal-lg {
            width: 95%!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
</style>
<?
$checkContactAccess	 = Yii::app()->user->checkAccess("bookingContactAccess");
$bookingType = Booking::model()->getBookingType();
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel mb0">
            <div class="panel-body p0">
				<?
				if (!empty($dataProvider))
				{
					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'relatedLead',
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
					<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
					</div></div>
					<div class='panel-body'>{items}</div>
					<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered mb0',
						'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  mb0 compact'),
						// 'ajaxType' => 'POST',
						'columns'			 => array(
							array('name' => 'bkg_id', 'value' => '$data["bkg_id"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Lead Id'),
							array('name' => 'bkg_user_name', 'value' => '$data["bkg_user_name"]." ".$data["bkg_user_lname"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'User Name'),
							array('name'	 => 'bkg_contact_no',
								'visible'	 => $checkContactAccess,
								'value'	 => function($data) {
									$contact = ($data["bkg_contact_no"] != '') ? "+" . $data["bkg_country_code"] . $data["bkg_contact_no"] : '';
									echo $contact;
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Contact'),
							array('name' => 'bkg_user_email', 'visible'	 => $checkContactAccess, 'value' => '$data["bkg_user_email"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'htmlOptions' => array('style' => 'word-break: break-all'), 'header' => 'Email'),
							array('name'	 => 'bkg_create_date', 'value'	 => function($data) {
									echo $data["bkg_create_date_date"] . '<br>' . $data["bkg_create_date_time"];
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Booking Date'),
							array('name' => 'bkg_platform', 'value' => '$data["bkg_platform"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Platform'),
							array('name' => 'bkg_booking_type', 'value' => '$data["bkg_booking_type"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Type'),
							array('name'	 => 'bkg_user_city', 'type'	 => 'raw',
								'value'	 => function ($data) {
									$city = '';
									if ($data['bkg_user_city'] != '')
									{
										$city = $data['bkg_user_city'] . ', ' . $data['bkg_user_country'] . '<br><span style = "word-break: break-all">(' . $data['bkg_user_ip'] . ')</span>';
									}
									return $city;
								},
								'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'User City'),
							array('name' => 'bkg_from_city', 'value' => '$data["bkg_from_city_name"]." - ".$data["bkg_to_city_name"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Route'),
							array('name'	 => 'bkg_pickup_date', 'value'	 => function($data) {
									echo $data["bkg_pickup_date_date"] . '<br>' . $data["bkg_pickup_date_time"];
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Pickup Date'),
							array('name'	 => 'bkg_follow_up_status', 'value'	 => function($data) {
									$adm = ( $data["adm_fname"] != '') ? '<br> by ' . $data["adm_fname"] . ' ' . $data["adm_lname"] .
											'<br> on ' . $data["bkg_follow_up_on_date"] . ' ' . $data["bkg_follow_up_on_time"] : '';
									echo $data["bkg_follow_up_status_name"] . $adm;
								}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Last Follow Up Status'),
							array('name'	 => 'bkg_follow_up_reminder', 'value'	 => function($data) {
									echo $data["bkg_follow_up_reminder_date"] . '<br>' . $data["bkg_follow_up_reminder_time"];
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Reminder Date'),
							array(
								'header'			 => 'Action',
								'class'				 => 'CButtonColumn',
								'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
								'headerHtmlOptions'	 => array('class' => 'text-center', 'style' => 'min-width: 50px;'),
								'template'			 => '{inactive}',
								'buttons'			 => array(
									'inactive'		 => array(
										'url'		 => 'Yii::app()->createUrl("admin/lead/deactivate", array("bkg_id" => $data["bkg_id"]))',
										'imageUrl'	 => Yii::app()->request->baseUrl . '/images/customer_cancel.png',
										'visible'	 => '$data["activeLeads"]==1?true:false;',
										'label'		 => '<i class="fa fa-unlock"></i>',
										'options'	 => array('data-toggle' => 'ajaxModal', 'onclick' => 'return deactivateLead(this)', 'style' => '', 'class' => 'btn btn-xs deactivate p0', 'title' => 'Mark Duplicate'),
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
<script type="text/javascript">
    function deactivateLead(obj)
    {
        var con = confirm("Do you want to deactivate this lead?");
        if (con) {
            $href = $(obj).attr('href');
            $.ajax({
                url: $href,
                success: function (result) {
                    if (result)
                    {
                        $(obj).hide();
                        updateRelGrid();
                    } else {
                        alert('Sorry error occured');
                    }

                },
                error: function (xhr, status, error) {
                    alert('Sorry error occured');
                }
            });
        }
        return false;
    }
    function updateRelGrid()
    {
        $.fn.yiiGridView.update('relatedLead');
        $(document).off('click.yiiGridView', $.fn.yiiGridView.settings['relatedLead'].updateSelector);

    }
</script>