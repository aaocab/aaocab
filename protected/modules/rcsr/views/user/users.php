<?php
$pageno	 = Yii::app()->request->getParam('page');
?>
<div class="row">
    <div class="col-sm-offset-1 col-md-offset-2 col-md-8 col-sm-10 col-xs-12">
        <div class="panel panel-white">
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
                    <div class="col-xs-3 col-sm-2 form-group text-center">
						<input class="form-control" type="checkbox" id="searchmarkuser" name="searchmarkuser" <?php
						if ($model->search_marked_bad == 1)
						{
							echo 'checked="checked"';
						}
						?>>&nbsp;Mark Bad
                    </div>                    
                    <div class="col-xs-6 col-sm-2 form-group">
                        <button class="btn btn-info full-width" type="submit"  name="Search">Search</button>
                    </div>
                </div>
            </div>
			<?php $this->endWidget(); ?>
        </div>
    </div>
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
			array('name'	 => 'usr_profile_pic', 'type'	 => 'html', 'value'	 => function ($data) {
					$path = $data["usr_profile_pic_path"];
					echo CHtml::image(($path == '') ? "/images/noimg.gif" : $path, $data["usr_name"], ['style' => 'width: 50px']);
				}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'User Photo'),
			array('name' => 'usr_name', 'value' => '$data["usr_name"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Name'),
			array('name'	 => 'usr_mobile',
				'value'	 => function ($data) {
					if ($data["usr_mobile"] != '')
					{
						echo '+' . $data["usr_country_code"] . $data["usr_mobile"];
					}
				},
				'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Phone'),
			array('name' => 'usr_email', 'value' => '$data["usr_email"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Email'),
			array('name' => 'usr_city', 'value' => '$data["usr_city"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'City'),
			array('name' => 'usr_mobile_verify', 'value' => '($data["usr_mobile_verify"] == 1)?"Yes":"No"', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Phone Verified'),
			array('name' => 'usr_email_verify', 'value' => '($data["usr_email_verify"] == 1)?"Yes":"No"', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Email Verified'),
			array('name'	 => 'usr_created_at',
				'value'	 => function ($data) {
					echo DateTimeFormat::DateTimeToLocale($data["usr_created_at"]);
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Signup Date'),
			array('name'	 => 'last_login', 'value'	 => function($data) {
					if ($data['last_login'] != '')
					{
						echo DateTimeFormat::DateTimeToLocale($data["last_login"]);
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Last Login'),
			array('name'	 => 'is_booking', 'value'	 => function($data) {
					echo ($data['is_booking'] != '') ? $data['is_booking'] : 0;
				}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Total Booking'),
			array('name'	 => 'is_completed', 'value'	 => function($data) {
					echo ($data['is_completed'] != '') ? $data['is_completed'] : 0;
				}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Complete Booking'),
			array('name'	 => 'is_cancelled', 'value'	 => function($data) {
					echo ($data['is_cancelled'] != '') ? $data['is_cancelled'] : 0;
				}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Cancel Booking'),
			array('name' => 'usr_mark_customer_count', 'value' => '$data["usr_mark_customer_count"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Remark Bad'),
			array('name'	 => 'usr_acct_verify', 'value'	 => function($data) {
					echo ($data['usr_acct_verify'] == 1) ? 'Verified' : 'Not Verified';
				}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Account Verify'),
			array(
				'header'			 => 'Action',
				'class'				 => 'CButtonColumn',
				'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
				'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
				'template'			 => '{delete}{history}{markedbadlist}{resetmarkedbad}{addcredits}',
				'buttons'			 => array(
					'delete'		 => array(
						'click'		 => 'function(){
                                                                    var con = confirm("Are you sure you want to delete this user?");
                                                                    return con;
                                                                }',
						'url'		 => 'Yii::app()->createUrl("admin/user/delete", array(\'id\' => $data[\'user_id\']))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\user\customer_cancel.png',
						'label'		 => '<i class="fa fa-remove"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conDelete p0', 'title' => 'Delete User'),
					),
					'history'		 => array(
						'url'		 => 'Yii::app()->createUrl("admin/booking/list", array(\'Booking\' => ["bkg_user_id"=>$data[\'user_id\']]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\user\booking_history.png',
						'label'		 => '<i class="fa fa-navicon"></i>',
						'options'	 => array('target' => '_blank', 'style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Booking History'),
					),
					'markedbadlist'	 => array(
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
					'resetmarkedbad' => array(
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
					'htmlOptions'	 => array('class' => 'center'),
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





        function confirmDelete() {
            if (confirm("Do you really want to delete this user ?")) {
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


</script>
