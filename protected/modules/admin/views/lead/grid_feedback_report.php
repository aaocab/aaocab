<?php

$GLOBALS['time'][$status . "_81"] = [];
?><?php

$dataProvider	 = $provider['data'];
$label			 = '';
$assignCSR		 = Yii::app()->user->checkAccess("leadAssigncsr");
if (!empty($dataProvider))
{
	$params1			 = $dataProvider->getPagination()->params;
	$checkContactAccess	 = Yii::app()->user->checkAccess("bookingContactAccess");
	$this->widget('booster.widgets.TbGridView', array(
		'id'				 => 'leadGrid' . $status,
		'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/lead/report', $params1)),
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'template'			 => "<div class='panel-heading'><div class='row m0'>
    <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
    </div></div>
    <div class='panel-body table-responsive'>{items}</div>
    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
		'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
		//          'ajaxType' => 'POST',
		'columns'			 => array(
			array('name' => 'bkg_id', 'value' => '$data[bkg_id]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Lead ID'),
			array('name'	 => 'bkg_vehicle_type_id', 'type'	 => 'raw',
				'value'	 => function($data) {
					$cab		 = ($data['bkg_vehicle_type_id'] != '') ? $data['vct_label'] . '(<strong>' . $data['scc_label'] . '</strong>)<br>' : '';
					$bookingType = ($data['bkg_booking_type'] != '') ? "(" . BookingTemp::model()->getBookingType($data['bkg_booking_type']) . ")" : "";
					return $cab . $bookingType;
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Cab Type'),
			array('name' => 'from_city_name', 'value' => '$data[from_city_name]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'From city'),
			array('name' => 'to_city_name', 'value' => '$data[to_city_name]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'To city'),
			array('name'	 => 'bkg_pickup_date',
				'value'	 => function ($data) {
					if ($data['bkg_pickup_date'] != '')
					{
						return DateTimeFormat::DateTimeToLocale($data['bkg_pickup_date']);
					}
					else
					{
						return '';
					}
				}
				, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header' => 'Pickup Date/Time'),
			array('name' => 'bkg_create_date', 'value' => 'DateTimeFormat::DateTimeToLocale($data[bkg_create_date])', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Create Date/Time'),
			array('name'	 => 'bkg_lead_source', 'type'	 => 'raw',
				'value'	 => function($data) {
					$val = '';
					if ($data['bkg_lead_source'] == '')
					{
						$val = 'Incomplete booking';
					}
					else
					{
						$val = BookingTemp::model()->getSourceName($data['bkg_lead_source']);
					}
					return CHtml::tag("span", array("title" => $data['bkg_log_comment']), CHtml::encode($val));
				},
				'sortable'			 => true, 'htmlOptions'		 => array(), 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Source'),
			array('name'		 => 'bkg_contact_no',  'visible'	 => $checkContactAccess,
				'value'		 => function($data) {
					/* @var $data BookingTemp */
					$assignCSR	 = Yii::app()->user->checkAccess("leadAssigncsr");
					$csrId		 = Yii::app()->user->getId();
					$assignedLeadCsr = ServiceCallQueue::validateAssignedLeadCSR($data['bkg_id'], $csrId);
					if (!$assignCSR && $csrId != $data['bkg_assigned_to'] && $assignedLeadCsr == 0 && !$checkContactAccess)
					{
						return "";
					}
					if ($data['bkg_contact_no'] == '' || $assignedLeadCsr == 0)
					{
						return $data['bkg_log_phone'];
					}
					else
					{
						return $data['bkg_contact_no'];
					}
				},
				'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header' => 'User Contact No'),

			array('name'			 => 'bkg_user_email', 'htmlOptions'	 => array('style' => 'word-break: break-all'),'header' => 'User Email',
				'value'			 => function($data) {
					$csrId		 = Yii::app()->user->getId();
					if ($data['bkg_user_email'] == '')
					{
						return $data['bkg_log_email'];
					}
					else
					{
						return $data['bkg_user_email'];
					}
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => '')),
			array('name' => 'AssignedToadm_fname', 'value' => '$data[AssignedToadm_fname]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Assigned to'),
			array('name'	 => 'bkg_user_city', 'type'	 => 'raw',
				'value'	 => function ($data) {
					$city = '';
					if ($data['bkg_user_city'] != '')
					{
						$city = $data['bkg_user_city'] . ', ' . $data['bkg_user_country'] . '<br><span style = "word-break: break-all">(' . $data['bkg_user_ip'] . ')</span>';
					}
					echo $city;
				},
				'sortable'			 => false, 'htmlOptions'		 => array('class' => 'text-center'), 'headerHtmlOptions'	 => array('class' => 'text-center'), 'header'			 => 'User City'),
			array('name'	 => 'bkg_follow_up_status', 'value'	 => function ($data) {
					echo BookingTemp::model()->getFollowupStatus($data['bkg_follow_up_status']);
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Follow Up Status'),
			array('name'	 => 'FollowUpByadm_fname',
				'value'	 => function($data) {
					$valueType	 = $data["bkg_follow_up_by"];
					$lockedBy	 = $data["bkg_locked_by"];
					$followDate	 = '';
					if ($data['bkg_follow_up_on'] != '')
					{
						$followDate = " on " . DateTimeFormat::DateTimeToLocale($data['bkg_follow_up_on']);
					}
					if ($valueType > 0)
					{
						$valueType = trim(ucfirst($data['FollowUpByadm_fname']) . ' ' . ucfirst($data['FollowUpByadm_lname'])) . $followDate . '';
					}
					else
					{
						$valueType = '';
					}
					if ($lockedBy > 0 && date('YmdHis', strtotime($data['bkg_lock_timeout'])) > date('YmdHis'))
					{
						$lockedBy = '<br> Lead is locked by ' . BookingTemp::model()->findAdminNameList($data["bkg_locked_by"]) . " until " . DateTimeFormat::DateTimeToLocale($data['bkg_lock_timeout']);
					}
					else
					{
						$lockedBy = '';
					}
					echo trim($valueType . $lockedBy, '<br>');
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Last Followed up By'),
			array('name'	 => 'remindDate', 'header' => 'Reminder Time', 'value'	 => function ($data) {
					if ($data['bkg_follow_up_reminder'] != '')
					{
						return DateTimeFormat::DateTimeToLocale($data['bkg_follow_up_reminder']);
					}
					else
					{
						return '';
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => '')),
			array(
				'header'			 => 'Action',
				'class'				 => 'CButtonColumn',
				'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: left', 'class' => 'action_box'),
				'headerHtmlOptions'	 => array('class' => 'text-center', 'style' => 'min-width: 100px;'),
				'template'			 => '{follow}{leadtobooking}{followup}{showlog}<br>{assign}{isrelated}{lock}{release}{mark_invalid}',
				'buttons'			 => array(
					'leadtobooking'	 => array(
						'url'		 => 'Yii::app()->createUrl("admin/booking/convert", array("lead_id" => $data[bkg_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/lead_report/convert_lead_booking.png',
						'label'		 => '<i class="fa fa-retweet"></i>',
						'visible'	 => 'ServiceCallQueue::validateAssignedLeadCSR($data[bkg_id], Yii::app()->user->getId()) > 0 ? true : false',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'data-placement' => 'left', 'class' => 'btn btn-xs convert p0', 'title' => 'Convert lead to booking'),
					),
					'showlog'		 => array(
						'url'		 => 'Yii::app()->createUrl("admin/lead/showlog", array("booking_id" => $data[bkg_id], "tab"=>"' . $status . '"))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/lead_report/show_log.png',
						'label'		 => '<i class="fa fa-list"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'onclick' => 'return showLog(this)', 'data-placement' => 'left', 'class' => 'btn btn-xs showlog p0', 'title' => 'Show Log'),
					),
					'assign'		 => array(
						'url'		 => 'Yii::app()->createUrl("admin/lead/showcsr", array("booking_id" => $data[bkg_id],"tab"=>"' . $status . '"))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/lead_report/assign_CSR.png',
						'label'		 => '<i class="fa fa-check"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'onclick' => 'return addCsr(this)', 'data-placement' => 'left', 'class' => 'btn btn-xs assign p0', 'title' => 'Assign CSR'),
					),
					'isrelated'		 => array(
						'url'		 => 'Yii::app()->createUrl("admin/lead/related", array("bkg_id" => $data[bkg_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/related_bookings.png',
						'visible'	 => '$data[bkg_is_related_lead] > 0?true:false;',
						'label'		 => '<i class="fa fa-unlock"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'onclick' => 'return showRelated(this)', 'style' => '', 'class' => 'btn btn-xs related p0', 'title' => 'Related Leads'),
					),
					'lock'			 => array(
						'url'		 => 'Yii::app()->createUrl("admin/lead/locklead", array("bkg_id" => $data[bkg_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/lead_report/lock_the_lead.png',
						'visible'	 => 'BookingTemp::model()->isLeadlocked($data["bkg_lock_timeout"]) == 0 ? true : false',
						'label'		 => '<i class="fa fa-unlock"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'onclick' => 'return changeLock(this, "lock",' . $status . ')', 'style' => '', 'class' => 'btn btn-xs unlocked p0', 'title' => 'Lock the lead'),
					),
					'release'		 => array(
						'url'		 => 'Yii::app()->createUrl("admin/lead/unlocklead", array("bkg_id" => $data[bkg_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/lead_report/release.png',
						'visible'	 => 'BookingTemp::model()->isLeadlocked($data["bkg_lock_timeout"]) == 0 ? false:true;',
						'label'		 => '<i class="fa fa-lock"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'onclick' => 'return changeLock(this, "release",' . $status . ')', 'style' => '', 'class' => 'btn btn-xs locked p0', 'title' => 'Locked'),
					),
					'markread'		 => array(
						'url'		 => 'Yii::app()->createUrl("admin/lead/markread", array("bkg_id" => $data[bkg_id], "tab"=>"' . $status . '"))',
						'imageUrl'	 => false,
						'visible'	 => '$data[bkg_follow_up_status]==0?true:false;',
						'visible'	 => 'false',
						'label'		 => '<i class="fa fa-square-o"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'onclick' => 'return markRead(this)', 'class' => 'btn btn-xs btn-info markRead p0', 'title' => 'Mark as read'),
					),
					'read'			 => array(
						'imageUrl'	 => false,
						'visible'	 => '$data[bkg_follow_up_status]==0?false:true;',
						'visible'	 => 'false',
						'label'		 => '<i class="fa fa-check-square-o"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs btn-success readAlready p0', 'title' => 'Read'),
					),
					'follow'		 => array(
						'url'		 => 'Yii::app()->createUrl("admin/lead/leadfollow", array("bkg_id"=>$data[bkg_id],"tab"=>"' . $status . '"))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/lead_report/follow.png',
						'label'		 => '<i class="fa fa-file-text-o"></i>',
						'visible'	 => 'ServiceCallQueue::validateAssignedLeadCSR($data[bkg_id], Yii::app()->user->getId()) > 0 ? true : false',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'onclick' => 'return follow(this)', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs leadfollow p0', 'title' => 'Follow'),
					),
					'mark_invalid'	 => array(
						'url'		 => 'Yii::app()->createUrl("admin/lead/markinvalid", array("bkg_id"=>$data[bkg_id], "tab"=>"' . $status . '"))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/lead_report/mark_invalid.png',
						'visible'	 => '$data[bkg_lead_status]==2?false:true',
						'visible'	 => 'false',
						'label'		 => '<i class="fa fa-file-text-o "></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'onclick' => 'return markInvalid(this)', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs markInvalid p0', 'title' => 'Mark Invalid'),
					),
					'followup'		 => array(
						'url'		 => 'Yii::app()->createUrl("admin/lead/addfollowup", array("bkg_id"=>$data[bkg_id], "tab"=>"' . $status . '"))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '/images/icon/add_follow_up.png',
						'label'		 => '<i class="fa fa-file-text-o"></i>',
						'visible'	 => 'ServiceCallQueue::validateAssignedLeadCSR($data[bkg_id], Yii::app()->user->getId()) > 0 ? true : false',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'rel' => 'popover', 'onclick' => 'return followUp(this)', 'data-placement' => 'left', 'class' => 'btn btn-xs leadfollowup p0', 'title' => 'Follow up'),
					),
					'htmlOptions'	 => array('class' => 'center'),
				))
	)));
}
?>