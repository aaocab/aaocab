<style>
    input[type=checkbox], input[type=radio]{
		margin-right: 5px;
	}
    .checkbox-inline, .radio-inline{
		padding-left: 0;
	}
</style>
<?php
$userID	 = $drvId	 = $vndId	 = "";
if ($bkgId)
{
	$bkgmodel	 = Booking::model()->findByPk($bkgId);
	$userID		 = $bkgmodel->bkgUserInfo->bkg_user_id;
	$drvId		 = $bkgmodel->bkgBcb->bcb_driver_id;
	$vndId		 = $bkgmodel->bkgBcb->bcb_vendor_id;
}
$csr				 = UserInfo::getUserId();
$csrTeam			 = Admins::getTeamid($csr);
$personoptions		 = [1 => "Customer", 4 => 'Agent', 2 => 'Vendor', 3 => 'Driver'];
$tmoptions			 = ['1' => 'By a Gozo Team', '2' => 'By a Gozen'];
$bkgoptions			 = ['1' => 'Yes', '2' => 'No'];
$followupdate		 = date('Y-m-d H:i:s', strtotime('+1 hour'));
$dtoptions			 = ['1' => 'Immediately', '2' => 'As soon as you get to it', 3 => 'Specific date / time'];
$adminModel			 = Admins::model()->findByPk(Yii::app()->user->getId());
$teamDisplay		 = ($scq->scq_to_be_followed_up_by_type == 0 || $scq->scq_to_be_followed_up_by_type == 2) ? "none" : "block";
$GozenDisplay		 = ($scq->scq_to_be_followed_up_by_type == 0 || $scq->scq_to_be_followed_up_by_type == 1) ? "none" : "block";
$BookingDisplay		 = ($scq->isBooking == 2 || $scq->isBooking == 0) ? "none" : "block";
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="row pl15 pr15 pt15" id="followupResponse"></div>
<div class="row">

    <div class="col-md-12 col-sm-10 col-xs-12">
        <div class="panel panel-white mb10" id="scqForm">
			<?php
			$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'dashboardFollowUp',
				'enableClientValidation' => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => true,
					'errorCssClass'		 => 'has-error'),
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				'htmlOptions'			 => array(
					'enctype'	 => 'multipart/form-data',
					'class'		 => '',
				),
			));
			/* @var $form TbActiveForm */
			?>
			<?php
			echo
			$form->errorSummary($scq);
			?>



            <div class="panel-body panel-white pb5 pt15">
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="mt0 mb10"><b>Create a Service Request</b></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-3">Related to a booking ID?
                        <p>Set by  <b><?= $adminModel->adm_fname . " " . $adminModel->adm_lname; ?></b></p></div>
                    <div class="col-xs-12 col-md-3"><?php echo $form->radioButtonListGroup($scq, 'isBooking', array('label' => '', 'widgetOptions' => array('data' => $bkgoptions,), 'inline' => true)); ?>
                    </div>
                    <div class="col-xs-12 col-md-3" id="isBooking" style="display: <?= $BookingDisplay; ?>">
						<?php echo $form->textFieldGroup($scq, 'scq_related_bkg_id', array('label' => '')) ?>
						<!--id="checkBkg" style="display: none;"-->
                        <a class="" onclick="bkgDetails()">Check details</a>
                    </div>
                    <div class="col-xs-12 col-md-4s"> <span id="bkgDesc"></span>
						<input type="hidden" id='scq_related_vnd_id' value="" name="scq_related_vnd_id">
					</div>
                </div>
            </div>


            <div class="panel-body" style="background: #f4f8fb;">

                <div class="row">
                    <div class="col-xs-12 col-md-3">When to followup :</div>

                    <div class="col-xs-12 col-md-9"><?php
						echo $form->radioButtonListGroup($scq, 'followUpTimeOpt', array(
							'label'			 => '', 'widgetOptions'	 => array(
								'data' => $dtoptions,
							),
							'inline'		 => true,
								)
						);
						?>
                    </div>
                    <div class="col-xs-12 col-md-3 col-md-offset-3" id="isfollowdt" style="display: none">
						<?=
						$form->datePickerGroup($scq, 'locale_followup_date', array('label'			 => '',
							'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date('Y-m-d H:i:s'), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'Reminder Date', 'value' => DateTimeFormat::DateTimeToDatePicker($followupdate))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
						?>

                    </div>
                    <div class="col-xs-12 col-md-3"  id="isfollowtime" style="display: none">
						<?php
						echo $form->timePickerGroup($scq, 'locale_followup_time', array('label'			 => '',
							'widgetOptions'	 => array('id' => CHtml::activeId($scq, "locale_followup_time"), 'options' => array('autoclose' => true), 'htmlOptions' => array('placeholder' => 'Reminder Time', 'value' => date('h:i A', strtotime($followupdate))))));
						?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-3">Follow up by :</div>
                    <div class="col-xs-12 col-md-4">


						<?php
						echo $form->radioButtonListGroup($scq, 'followUpby', array(
							'label'			 => '', 'widgetOptions'	 => array(
								'data' => $tmoptions,
							),
							'inline'		 => true,
								)
						);
						?>


                    </div>

                    <div class="col-xs-12 col-md-4">
                        <div  id="gozoTeam" style="display: <?= $teamDisplay ?>"><?php
							$teamarr1			 = Teams::getByLive();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $scq,
								'attribute'		 => 'scq_to_be_followed_up_by_id',
								'val'			 => explode(',', $scq->scq_to_be_followed_up_by_id),
								'data'			 => $teamarr1,
								'htmlOptions'	 => array('style'			 => 'width:100%',
									'placeholder'	 => 'Select team(s)')
							));
							?></div>
                        <div class="cityinput" id="gozoens" style="display: <?= $GozenDisplay ?>">

							<?php
							$model				 = Admins::model()->findByPk($csr);
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'adm_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Gozens",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'scq_to_be_followed_up_by_id'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateGozen(this, '{$model->adm_id}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadGozen(query, callback);
                                            }",
							'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
								),
							));
							?><span id ="scq_to_be_followed_up_by_id"></span>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-1"></div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-3">Follow up type:</div>
                    <div class="col-xs-12 col-md-6">



						<?php
						$options			 = ['1' => 'Followp Internally', '2' => 'Requires contact to an external party'];
//scq_to_be_followed_up_with_entity_type
						echo $form->radioButtonListGroup($scq, 'scqType', array(
							'label'			 => '', 'widgetOptions'	 => array(
								'data' => $options,
							),
							'inline'		 => true,
								)
						);
						?>





                    </div>

                </div>
                <div class="row" id='followupPerson' style="display: none">
                    <div class="col-xs-12 col-md-3"></div>
                    <div class="col-xs-12 col-md-4">

                        <div class="form-group" >
							<?php
							$form->widget('booster.widgets.TbSelect2', array(
								'model'			 => $scq,
								'attribute'		 => 'followupPerson',
								'val'			 => $scq->followupPerson,
								'data'			 => $personoptions, 'htmlOptions'	 => array('style'			 => 'width:100%',
									'placeholder'	 => 'Person')
							));
							?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">	<div id="followVnd" style="display:none">

							<?php
							$vndmodel			 = new Vendors();
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $vndmodel,
								'attribute'			 => 'vnd_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Vendors",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'ServiceCallQueue_scq_to_be_followed_up_with_entity_id'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateVnds(this, '{$vndId}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadVnds(query, callback);
                                            }",
							'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
								),
							));
							?>
                        </div>
                        <div id="followDrv" style="display:none">
							<?php
							$drvmodel			 = new Drivers();
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $drvmodel,
								'attribute'			 => 'drv_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Drivers",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'ServiceCallQueue_scq_to_be_followed_up_with_drv'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateDrvs(this, '{$drvId}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadDrvs(query, callback);
                                            }",
							'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
								),
							));
							?>
                        </div>

                        <div id="followAgt"  style="display: none;">

							<?php
							$agtmodel			 = new Agents();
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $agtmodel,
								'attribute'			 => 'agt_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Agents",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'ServiceCallQueue_scq_to_be_followed_up_with_agt'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populatePartner(this, '{$bkgmodel->bkg_agent_id}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadPartner(query, callback);
                                            }",
							'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
								),
							));
							?>
                        </div>
                        <div id="followCust"  style="display: none;">
							<?php
							$usrmodel			 = new Users();
							if ($bkgmodel->bkg_agent_id != 18190)
							{
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $usrmodel,
									'attribute'			 => 'user_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Customers",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('width'	 => '100%',
										'id'	 => 'ServiceCallQueue_scq_to_be_followed_up_with_cust'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
											populateCustomer(this, '{$userID}');
                                                }",
								'load'			 => "js:function(query, callback){
                                            loadCustomer(query, callback);
                                            }",
								'render'		 => "js:{
                                            option: function(item, escape){
                                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                            },
                                            option_create: function(data, escape){
                                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                            }
                                            }",
									),
								));
							}
							else
							{
								echo "MMT Customer.";
							}
							?>
                        </div></div>
                </div>
				<div class="row">
                    <div class="col-xs-12 col-md-3">Call Back File:</div>
                    <div class="col-xs-12 col-md-6">
						<div class="form-group" >
							<?php
							$this->widget('CMultiFileUpload', array(
								'model'		 => $callBackModel,
								'attribute'	 => 'cbd_file_path',
								'accept'	 => 'jpg|gif|png|jpeg',
								'options'	 => array(
									'afterFileAppend'	 => 'function(e, v, m){ alert("file has been added successfully!!  "+v) }',
									'afterFileRemove'	 => 'function(e, v, m){ alert("file has been removed successfully - "+v) }',
								),
								'denied'	 => 'File is not allowed',
								'max'		 => 10, // max 10 files
							));
							?>
						</div>
					</div>

                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-3"><span id="heading" ></span>Followup instruction:</div>
                    <div class="col-xs-12 col-md-6">	<?= $form->textAreaGroup($scq, 'scq_creation_comments', array('label' => '', 'rows' => 10, 'cols' => 50, 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'IF YOU NEED THEM TO CONTACT A VENDOR, DRIVER OR CUSTOMER >>> CREATE EXTERNAL FOLLOWUP')))) ?></div>
                </div>





                <div class="row">
					<div class="col-xs-12 col-md-3"></div>
                    <div class="col-xs-12 col-md-3" >
                        <input type="hidden" name="YII_CSRF_TOKEN" value="<?= Yii::app()->request->csrfToken ?>" >  
						<button class="btn btn-info full-width mb0" id="btnSubmit" type=""  name="btnSubmit"  style="background: #0c4ba8;"> Create Service Request</button>	
						<br />
					</div>
					<div class="col-xs-12 col-md-3"><span class="btn btn-info full-width mb0" onclick="cnlSubmit()" type=""  name="" style="background: #CC0000;"> Cancel</span></div>
                    <div class="col-xs-12 col-md-3"></div>
				</div>
            </div>
			<?php $this->endWidget(); ?>
        </div>
    </div>


	<div class="row pl15 pr15 pt15" id="followupSecP2" style="display:none"></div>
	<div class="row pl15 pr15 pt15" id="followupSecP1" style="display:none">
		<div class="col-xs-12 pl15 pr15">	
		</div>
	</div>
</div>

<script>



    $("#btnSubmit").click(function (e) {

        var form = $(this);

        $followUp = new FollowUp();

        var bookidy = $('#ServiceCallQueue_isBooking_0').is(':checked');
        var bookidn = $('#ServiceCallQueue_isBooking_1').is(':checked');
        var bookingid = $('#ServiceCallQueue_scq_related_bkg_id').val();
        var followupimd = $('#ServiceCallQueue_followUpTimeOpt_0').is(':checked');
        var followupsoon = $('#ServiceCallQueue_followUpTimeOpt_1').is(':checked');
        var followupspec = $('#ServiceCallQueue_followUpTimeOpt_2').is(':checked');
        var followupbyteam = $('#ServiceCallQueue_followUpby_0').is(':checked');
        var followupbygozen = $('#ServiceCallQueue_followUpby_1').is(':checked');
        var followupbytypeint = $('#ServiceCallQueue_scqType_0').is(':checked');
        var followupbytypeext = $('#ServiceCallQueue_scqType_1').is(':checked');

        var teamId = $('#<?= CHtml::activeId($scq, "scq_to_be_followed_up_by_id") ?>').val();
        var followupexttype = $('#<?= CHtml::activeId($scq, "followupPerson") ?>').val();
        var followupcust = $('#ServiceCallQueue_scq_to_be_followed_up_with_cust').val();
        var followupvnd = $('#ServiceCallQueue_scq_to_be_followed_up_with_entity_id').val();
        var followupagt = $('#ServiceCallQueue_scq_to_be_followed_up_with_agt').val();
        var followupdrv = $('#ServiceCallQueue_scq_to_be_followed_up_with_drv').val();

        var gozenId = $('#scq_to_be_followed_up_by_id').val();
        if (bookidy == false && bookidn == false)
        {
            bootbox.alert("Please select related booking id");
            return false;
        }
        if (bookidy == true)
        {
            if (bookingid == '')
            {
                bootbox.alert("Please enter booking id");
                return false;
            }
        }
        if (followupimd == false && followupsoon == false && followupspec == false)
        {
            bootbox.alert("Please choose when to followup");
            return false;
        }

        if (followupbyteam == false && followupbygozen == false)
        {
            bootbox.alert("Please choose followup by");
            return false;
        }

        if (followupbyteam == true)
        {
            if (teamId == '')
            {
                bootbox.alert("Please choose followup team");
                return false;
            }
        }

        if (followupbygozen == true)
        {
            if (gozenId == '')
            {
                bootbox.alert("Please choose followup gozen");
                return false;
            }
        }

        if (followupbytypeint == false && followupbytypeext == false)
        {
            bootbox.alert("Please choose followup type");
            return false;
        }

        if (followupbytypeext == true)
        {
            if (followupexttype == '')
            {
                bootbox.alert("Please choose followup type person (external party)");
                return false;
            }

            if (followupexttype == 1)
            {
                if (followupcust == '')
                {
                    bootbox.alert("Please choose followup customer");
                    return false;
                }
            }

            if (followupexttype == 2)
            {
                if (followupvnd == '')
                {
                    bootbox.alert("Please choose followup vendor");
                    return false;
                }
            }

            if (followupexttype == 4)
            {
                if (followupagt == '')
                {
                    bootbox.alert("Please choose followup agent");
                    return false;
                }
            }

            if (followupexttype == 3)
            {
                if (followupdrv == '')
                {
                    bootbox.alert("Please choose followup driver");
                    return false;
                }
            }
        }

        if ($("#ServiceCallQueue_scq_creation_comments").val() == '')
        {
            bootbox.alert("Please enter followup instruction");
            return false;
        }
       
		
		$("#dashboardFollowUp").submit();
		$('#btnSubmit').prop('disabled', true);
		ajaxindicatorstart('loading data.. please wait..');


    });


    $vendor = null;
    $gozenList = null;
    $vndList = null;
    $drvList = null;
    $custList = null;
    $followUp = new FollowUp();
    $(document).ready(function () {



        $("#isfollowdt").hide("slow");
        $("#isfollowtime").hide("slow");

        $('#ServiceCallQueue_isBooking_0').change(function () {
            $("#isBooking").show("slow");
        });
        $('#ServiceCallQueue_isBooking_1').change(function () {
            $("#isBooking").hide("hide");
        });

        $('#ServiceCallQueue_followUpby_1').change(function () {
            $("#gozoTeam").hide("hide");
            $("#gozoens").show("slow");
        });
        $('#ServiceCallQueue_followUpby_0').change(function () {

            $("#gozoTeam").show("slow");
            $("#gozoens").hide("hide");
        });

        $('#ServiceCallQueue_followUpTimeOpt_2').change(function () {
            $("#isfollowdt").show("slow");
            $("#isfollowtime").show("slow");
        });

        $('#ServiceCallQueue_followUpTimeOpt_1').change(function () {
            $("#isfollowdt").hide("slow");
            $("#isfollowtime").hide("slow");
        });

        $('#ServiceCallQueue_followUpTimeOpt_0').change(function () {
            $("#isfollowdt").hide("slow");
            $("#isfollowtime").hide("slow");
        });

        $('#ServiceCallQueue_followupPerson').change(function () {


            var person = $('#ServiceCallQueue_followupPerson').val();

            if (person == 4)
            {
                $("#followVnd").hide("slow");
                $("#followDrv").hide("slow");
                $("#followAgt").show("slow");
                $("#followCust").hide("slow");
            }
            if (person == 1)
            {
                $("#followVnd").hide("slow");
                $("#followDrv").hide("slow");
                $("#followAgt").hide("slow");
                $("#followCust").show("slow");
            }
            if (person == 2)
            {
                $vendor = $("#scq_related_vnd_id").val();
                $("#followVnd").show("slow");
                $("#followDrv").hide("slow");
                $("#followAgt").hide("slow");
                $("#followCust").hide("slow");
            }
            if (person == 3)
            {
                $("#followVnd").hide("slow");
                $("#followDrv").show("slow");
                $("#followAgt").hide("slow");
                $("#followCust").hide("slow");
            }
        });


//        $("#ServiceCallQueue_scq_related_bkg_id").keypress(function (e) {
//           $("#checkBkg").show();
//        });

        $('#ServiceCallQueue_scqType_1').change(function () {
            $("#followupPerson").show("slow");
        });
        $('#ServiceCallQueue_scqType_0').change(function () {
            $("#followupPerson").hide("slow");
        })
    });


    function bkgDetails()
    {
        var id = $("#ServiceCallQueue_scq_related_bkg_id").val();
        $followUp.bkgDetails(id);
        $vendor = $("#scq_related_vnd_id").val();
        //populateVnds(obj, $vendor);

    }

    function populateGozen(obj, vndId)
    {
        $followUp.populateGozen(obj, vndId);
    }
    function loadGozen(query, callback)
    {
        $followUp.loadGozen(query, callback);
    }



    function populateVnds(obj, vndId)
    {
        $followUp.populateVnds(obj, vndId);
    }
    function loadVnds(query, callback)
    {
        $followUp.loadVnds(query, callback);
    }


    function populateDrvs(obj, drvId)
    {
        $followUp.populateDrvs(obj, drvId);
    }
    function loadDrvs(query, callback)
    {
        $followUp.loadDrvs(query, callback);
    }


    function populateCustomer(obj, cust)
    {

        $followUp.populateCustomer(obj, cust);
    }
    function loadCustomer(query, callback)
    {
        $followUp.loadCustomer(query, callback);
    }




</script> 