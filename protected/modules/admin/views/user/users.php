
<div class="row">
<?php
			$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'searchform', 'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error'
				),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// See class documentation of CActiveForm for details on this,
				// you need to use the performAjaxValidation()-method described there.
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'class' => '',
				),
			));
			/* @var $form TbActiveForm */
			?>
    <div class="col-sm-offset-1 col-md-offset-2 col-md-8 col-sm-10 col-xs-12">
        <div class="panel panel-white">
			
            <div class="panel-body">
                <div class="row mt10" >
                    <div class="col-xs-6 col-sm-4 form-group text-center">
						<?=
						$form->textFieldGroup($model, 'search_name', array('label'			 => '',
							'htmlOptions'	 => array('placeholder' => 'Name'),
							'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Name']]))
						?>
                    </div>
                    <div class="col-xs-6 col-sm-3 form-group text-center">
						<?=
						$form->textFieldGroup($model, 'search_phone', array('label'			 => '',
							'htmlOptions'	 => array('placeholder' => 'Phone'),
							'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Phone']]))
						?>
                    </div>
                    <div class="col-xs-6 col-sm-3 form-group text-center">
						<?=
						$form->textFieldGroup($model, 'search_email', array('label'			 => '',
							'htmlOptions'	 => array('placeholder' => 'Email'),
							'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Email']]))
						?>
                    </div>
<!--                    <div class="col-xs-3 col-sm-2 form-group text-center">
						<input class="form-control" type="checkbox" id="searchmarkuser" name="searchmarkuser" <?php
						//if ($model->search_marked_bad == 1)
						//{
							//echo 'checked="checked"';
						//}
						?>>&nbsp;Mark Bad
                    </div> -->
					<div class="col-xs-6 col-sm-3 form-group">
						<?=$form->dropDownListGroup($model, 'category', ['label' => 'Select Category', 'widgetOptions' => ['data' => ['-1' => 'select category'] + UserCategoryMaster::catDropdownList(), 'htmlOptions' => []]]) ?>
					</div>
					<div class="col-xs-8 col-sm-3 form-group">
						<div class="form-group">
							<label class="control-label">Last Booking created Date Range</label>
							<?php
							$daterang		 = "Select Date Range";
							$last_booking_create_date1 = ($model->last_booking_create_date1 == '') ? '' : $model->last_booking_create_date1;
							$last_booking_create_date2 = ($model->last_booking_create_date1 == '') ? '' : $model->last_booking_create_date1;
							if ($last_booking_create_date1 != '' && $last_booking_create_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($last_booking_create_date1)) . " - " . date('F d, Y', strtotime($last_booking_create_date2));
							}
							?>
							<div id="last_booking_create_date" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
							</div>
							<?= $form->hiddenField($model, 'last_booking_create_date1'); ?>
							<?= $form->hiddenField($model, 'last_booking_create_date2'); ?>
						</div>
					</div>

                    <div class="col-xs-12  form-group text-center">
                        <button class="btn btn-info" type="button" onclick="submitForm();"  name="Search">Search</button>
                    </div>
                </div>
            </div>
			
        </div>
    </div>
<?php 
$checkaccess = Yii::app()->user->checkAccess('userExport');
if($checkaccess){
?>
<div class="col-xs-12  mb10">
	<input type="hidden" id="export1" name="export1" value="0"/>
	<button class="btn btn-default" type="button" style="width: 185px;" onclick="exportData()">Export Below Table</button>
</div>
<?php } $this->endWidget(); ?>
</div>
<?php
if (!empty($dataProvider))
{
	$this->widget('booster.widgets.TbGridView', array(
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'id'				 => 'userListGrid',
		'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered mb0',
		'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
		//    'ajaxType' => 'POST',
		'columns'			 => array(
//			array('name'	 => 'usr_profile_pic', 'type'	 => 'html', 'value'	 => function ($data) {
//					$path = $data["usr_profile_pic_path"];
//					echo CHtml::image(($path == '') ? "/images/noimg.gif" : $path, $data["usr_name"], ['style' => 'width: 50px']);
//				}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'User Photo'),
			array('name'	 => 'usr_name',
				'value'	 => function ($data) {
					$usrName = $data["usr_name"] . ' ' . $data["usr_lname"];
					if ($data["ctt_first_name"] != "" && $data["ctt_last_name"] != "")
					{
						$usrName = $data["ctt_first_name"] . ' ' . $data["ctt_last_name"];
					}
					echo CHtml::link($usrName, Yii::app()->createUrl("admin/user/view", ["id" => $data['user_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']);
					if($data['ctt_tags']!=''){
											$tagList = Tags::getListByids($data['ctt_tags']);
											foreach ($tagList as $tag)
											{
												if($tag['tag_color']!='')
												{
													$tagBtnList .= " <span title='" . $tag['tag_desc'] . "' class='badge badge-pill badge-primary m5 mr0 p5 pb10 pl10 pr10' style='background:".$tag['tag_color']."'>" . $tag['tag_name'] . "</span>";
												}
												else
												{
													$tagBtnList .= " <span title='" . $tag['tag_desc'] . "' class='badge badge-pill badge-primary m5 mr0 p5 pb10 pl10 pr10' >" . $tag['tag_name'] . "</span>";
												}
											}
											echo $tagBtnList;
									  }
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Name'),
			array('name'	 => 'phn_phone_no',
				'value'	 => function ($data) {
					if ($data["phn_phone_no"] != '')
					{
						echo '+' . $data["phn_phone_country_code"] . $data["phn_phone_no"];
					}
				},
				'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Phone'),
			array('name' => 'usr_email', 'value' => '$data["eml_email_address"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Email'),
			array('name' => 'cpr_category', 'value' => function ($data) 
					{ 
						if($data['cpr_category']>0){
						$catCss = UserCategoryMaster::getColorByid($data['cpr_category']);	
						$category = UserCategoryMaster::model()->findByPk($data['cpr_category'])->ucm_label;
						echo "<div class='text-center'><img src='/images/{$catCss}' alt='' width='16' title='$category'></div>";
						}else{
							echo "";
						}

					}, 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Category'),
			array('name' => 'usr_mobile_verify', 'value' => '($data["phn_is_verified"] == 1)?"Yes":"No"', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Phone Verified'),
			array('name' => 'usr_email_verify', 'value' => '($data["eml_is_verified"] == 1)?"Yes":"No"', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Email Verified'),
			array('name'	 => 'usr_created_at',
				'value'	 => function ($data) {
					echo DateTimeFormat::DateTimeToLocale($data["usr_created_at"]);
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Signup Date'),
			array('name'	 => 'urs_last_trip_created',
				'value'	 => function ($data) {
					echo ($data['urs_last_trip_created']!='')?DateTimeFormat::DateTimeToLocale($data['urs_last_trip_created']):"";
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Last Booking Created'),
			array('name'	 => 'usr_acct_verify', 'value'	 => function ($data) {
					echo ($data['usr_acct_verify'] == 1) ? 'Verified' : 'Not Verified';
				}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Account Verify'),
			array(
				'header'			 => 'Action',
				'class'				 => 'CButtonColumn',
				'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
				'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
				'template'			 => '{delete}{markedbadlist}{resetmarkedbad}{addvoucher}{listvoucher}{sendLinkForPasswordReset}',
				'buttons'			 => array(

					
				'delete'		 => array(
					'click'		 => 'function(e)
					{
					  var con = confirm("Are you sure you want to deactive this user?"); 
					  if(con)
					  {
						try
						{
							$href = $(this).attr("href");
							jQuery.ajax({type:"GET",url:$href,success:function(data)
							{
								bootbox.dialog({ 
								message: data, 
								className:"bootbox-sm",
								title:"Deactivate User",
								success: function(result){
								if(result.success)
								{

									}else{
										alert(\'Sorry error occured\');
									}
								},
								error: function(xhr, status, error){
									alert(\'Sorry error occured\');
								}
							});
						}}); 
						}
						catch(e)
						{ alert(e); }
						}
						return false;

				 }',
				'url'		 => 'Yii::app()->createUrl("admpnl/user/deactive", array("user_id" => $data[user_id]))',
				'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\user\customer_cancel.png',
				'visible'	 => '(Yii::app()->user->checkAccess("userDeactive"))',
				'label'		 => '<i class="fa fa-toggle-on"></i>',
				'options'	 => array('data-toggle' => 'ajaxModal', 
										'id' => 'example1',
										'style' => '',
										'rel' => 'popover', 
										'data-placement' => 'left',
										'class' => 'btn btn-xs deactive p0', 
										'title' => 'User to deactive')
				),

					'markedbadlist'				 => array(
						'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                bootbox.dialog({ 
                                                message: data, 
                                                className:"bootbox-lg",
                                                title:"Mark Bad Customers",
                                                size: "large",
                                                callback: function(){   }
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/user/markedbadlist", array("user_id"=>$data[user_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\user\bad_customer.png',
						'label'		 => '<i class="fa fa-thumbs-down"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs markBad p0', 'title' => 'Marked Bad Customer'),
					),
					'resetmarkedbad'			 => array(
						'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data){
                                                        bootbox.dialog({
                                                            message: data,
                                                            title: \'Reset Bad Count For Customer\',
                                                            onEscape: function () {
                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
						'url'		 => 'Yii::app()->createUrl("admin/user/resetmarkedbad", array("refId" =>$data[user_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\user\reset_marked_bad_customer.png',
						'visible'	 => '($data[usr_mark_customer_count]>0)',
						'label'		 => '<i class="fa fa-refresh"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs resetBad p0', 'title' => 'Reset Marked Bad Customer'),
					),
					'addvoucher'				 => array(
						'click'		 => 'function(e){
														try
															{
																$href = $(this).attr("href");
																	jQuery.ajax({
																		type:"GET",
																		"dataType":"html",
																		url:$href,
																		success:function(data)
																		{
																			
																			bootbox.dialog({ 
																				message: data, 
																				title:"Add Voucher To Customer",
																				size: "large",
																				className:"bootbox-lg9",    
																				callback: function(){}
																			});																			
																			jQuery(".bootbox").removeAttr("tabindex");
																		}
																	}); 
																}
																catch(e)
																{ alert(e); }
																return false;
															 }',
						'url'		 => 'Yii::app()->createUrl("admin/user/addvoucher", array("user_id"=>$data[user_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\add.png',
						'visible'	 => '($data[vch_is_all_partner] == 0 )',
						'label'		 => '<i class="fa fa-file-text-o"></i>',
						'options'	 => array('data-toggle'	 => 'ajaxModal',
							'id'			 => 'example23',
							'style'			 => '',
							'rel'			 => 'popover',
							'data-placement' => 'left',
							'class'			 => 'btn btn-xs jobDetail6 p0',
							'title'			 => 'Add Voucher To Customer'),
					),
					'listvoucher'				 => array(
						'click'		 => 'function(e){
														try
															{
																$href = $(this).attr("href");
																	jQuery.ajax({
																		type:"GET",
																		"dataType":"html",
																		url:$href,
																		success:function(data)
																		{
																			
																			bootbox.dialog({ 
																				message: data, 
																				title:"Specific Voucher List",
																				size: "large",
																				className:"bootbox-lg9",    
																				callback: function(){}
																			});
																		}
																	}); 
																}
																catch(e)
																{ alert(e); }
																return false;
															 }',
						'url'		 => 'Yii::app()->createUrl("admin/user/voucherlist", array("user_id"=>$data[user_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\sms_list.png',
						'visible'	 => '($data[vch_is_all_partner] == 0 )',
						'label'		 => '<i class="fa fa-file-text-o"></i>',
						'options'	 => array('data-toggle'	 => 'ajaxModal',
							'id'			 => 'example15',
							'style'			 => '',
							'rel'			 => 'popover',
							'data-placement' => 'left',
							'class'			 => 'btn btn-xs jobDetail7 p0',
							'title'			 => "Specific Voucher List"
						),
					),
					'sendLinkForPasswordReset'	 => array(
						'click'		 => 'function(e){
                                                    try
                                                     {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                bootbox.dialog({ 
                                                message: data, 
                                                className:"bootbox-lg",
                                                title:"Reset Password",
                                                size: "large",
                                                callback: function(){   }
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/user/sendResetPasswordLink", array(\'id\' => $data[\'user_id\']))',
						'label'		 => '<i class="fa fa-key" aria-hidden="true"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs reset p0', 'title' => 'reset Password'),
					),
					'htmlOptions'				 => array('class' => 'center'),
				))
	)));
}
?>

<script>
    function refreshUsersGrid() {
        $('#userListGrid').yiiGridView('update');
    }
</script>
<script>

    $(document).ready(function () {


        var front_end_height = parseInt($(window).outerHeight(true));
        var footer_height = parseInt($("#footer").outerHeight(true));
        var header_height = parseInt($("#header").outerHeight(true));
        var ch = (front_end_height - (header_height + footer_height + 23));
        //console.log("wH: "+front_end_height+" HH : "+header_height+" FH: "+footer_height+"CH :"+ch);
        $("#content").attr("style", "height:" + ch + "px;");





        function confirmDelete() 
		{
            if (confirm("Do you really want to deactive this user ?")) {
                return true;
            } else {
                return false;
            }
        }


        $("#edtbtn").click(function ()
        {
            $("#savbtn").show();
            $(".iselect").show();
            $(".infs").hide();
            $("#edtbtn").hide();
        });
        $("#savbtn").click(function ()
        {
            $('#iform').submit();
            $("#savbtn").hide();
            $(".iselect").hide();
            $(".infs").show();
            $("#edtbtn").show();
        });
    });

 var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
    var end = '<?= date('d/m/Y'); ?>';
    $('#last_booking_create_date').daterangepicker(
            {
                locale: {
                    format: 'DD/MM/YYYY',
                    cancelLabel: 'Clear'
                },
                "showDropdowns": true,
                "alwaysShowCalendars": true,
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(15, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                }
            }, function (start1, end1) {
        $('#Users_last_booking_create_date1').val(start1.format('YYYY-MM-DD'));
        $('#Users_last_booking_create_date2').val(end1.format('YYYY-MM-DD'));
        $('#last_booking_create_date span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
    });
    $('#last_booking_create_date').on('cancel.daterangepicker', function (ev, picker) {
        $('#last_booking_create_date span').html('Select Last Booking Create Date Range');
        $('#Users_last_booking_create_date1').val('');
        $('#Users_last_booking_create_date2').val('');
    });
function exportData()
{ 
	var username = $('#Users_search_name').val();
	var phone = $('#Users_search_phone').val();
	var email = $('#Users_search_email').val();
	var markbad = $('#searchmarkuser').val();
	var category = $('#Users_category').val();
	var create1 = $('#Users_last_booking_create_date1').val();
	var create2 = $('#Users_last_booking_create_date2').val();
	if(username == '' && phone == '' && email == '' && markbad == 'on' && category == '-1' && (create1 == '' || create2 == ''))
	{
		alert("Any of the above filteration is compulsory to export table");
		return;
	}
	$('#export1').val(1);
	$('#searchform').submit();
}
function submitForm()
{
	$('#export1').val(0);
	$('#searchform').submit();
}
</script>