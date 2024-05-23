<div class="row m0">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">			
                <div class="row"> 
					<?php
					if (!empty($dataProvider))
					{
						$this->widget('booster.widgets.TbGridView', array(
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
                                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                                    </div></div>
                                                    <div class='panel-body table-responsive'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
							'columns'			 => array(
								array('name' => 'bkg_id', 'value' => '$data[bkg_id]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Lead ID'),
								array('name'	 => 'bkgIds', 'value'	 =>
									function ($data) {
										if ($data['bkgIds'] != '')
										{
											echo CHtml::link($data['bkgIds'], Yii::app()->createUrl("admin/booking/view/", ["id" => $data['bkgIds']]), ["target" => "_blank"]);
										}
										else
										{
											echo 'NA';
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Booking ID'),
								array('name'	 => 'bkg_vehicle_type_id', 'type'	 => 'raw',
									'value'	 => function ($data) {
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
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Pickup Date/Time'),
								array('name' => 'bkg_create_date', 'value' => 'DateTimeFormat::DateTimeToLocale($data[bkg_create_date])', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Create Date/Time'),
								array('name'	 => 'bkg_contact_no',
									'value'	 => function ($data) {
										if ($data['bkg_contact_no'] == '')
										{
											return $data['bkg_log_phone'];
										}
										else
										{
											return $data['bkg_contact_no'];
										}
									},
									'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'User Contact No'),
								array('name'			 => 'bkg_user_email', 'htmlOptions'	 => array('style' => 'word-break: break-all'), 'header'		 => 'User Email',
									'value'			 => function ($data) {
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
								array('name'	 => 'bkg_follow_up_status', 'value'	 => function ($data) {
										echo BookingTemp::model()->getFollowupStatus($data['bkg_follow_up_status']);
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Follow Up Status'),
								array('name'	 => 'FollowUpByadm_fname',
									'value'	 => function ($data) {
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
								array('name'	 => 'isReferBooking', 'value'	 => function ($data) {
										echo $data['isReferBooking'] == 1 ? "YES" : "NO";
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Is Refer Booking'),
								array('name'	 => 'beneficiaryId', 'value'	 => function ($data) {
										if ($data['beneficiaryId'] != '')
										{
											echo CHtml::link($data['beneficiaryId'], Yii::app()->createUrl("admin/user/view/", ["id" => $data['beneficiaryId']]), ["target" => "_blank"]);
										}
										else
										{
											echo 'NA';
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Beneficiary Id'),
								array('name'	 => 'benefactorId', 'value'	 => function ($data) {
										if ($data['beneficiaryId'] != '')
										{
											echo CHtml::link($data['benefactorId'], Yii::app()->createUrl("admin/user/view/", ["id" => $data['benefactorId']]), ["target" => "_blank"]);
										}
										else
										{
											echo 'NA';
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => ''), 'header'			 => 'Benefactor Id'),
						)));
					}
					?> 
                </div> 

            </div>  

        </div>  
    </div>
</div>
