<style>
    .dev-height{ overflow: auto; height: 200px; border: #dfdfdf 1px solid; padding: 15px;}
</style>
<?php
$csr		 = UserInfo::getUserId();
$pmodel		 = AdminProfiles::model()->getByAdminID($csr);
?>
<div style="float:right">
	<input type="checkbox" id="auto_allocated" name="auto_allocated" onclick="autoAllocateLead('<?php echo $pmodel->adp_adm_id; ?>', '<?php echo $pmodel->adp_auto_allocated ?>')" value="<?php echo $pmodel->adp_auto_allocated; ?>" <?php echo $pmodel->adp_auto_allocated == 1 ? 'checked="checked"' : ''; ?>>
	<label for="auto_allocated"> <b>Auto Lead Allocate</b></label><br>
</div>
<?php
if ($assignModel["scq_id"] != '')
{
	$outputJs	 = Yii::app()->request->isAjaxRequest;
	$fwpParams	 = array('refId' => 1, 'fwpId' => 1);
	switch ((int) $assignModel["scq_follow_up_queue_type"])
	{
		case 15:
			$fwpParams	 = array('fwpId' => $assignModel["scq_id"], 'isMycall' => 1);
			$calType	 = ServiceCallQueue::getReasonList($assignModel["scq_follow_up_queue_type"]);
			$type		 = "Follow Up - $calType (CALL)";
			break;
	}
	?>
	<div class="">
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-default main-tab1">
					<div class="panel-body panel-border">
						<div class="row">
							<div class="col-xs-12 col-lg-7">
								<p class="mb5"><b>Name:</b>   <?php echo $cModel->ctt_name ?></p>

							</div>
							<div class="col-xs-12 col-lg-5">
								<p class="mb5"><b>Call Queue:</b>  <?php echo $type ?></p>
								<p class="mb5"><b>Call request received at:</b>  <?php echo DateTimeFormat::DateTimeToLocale($assignModel['scq_create_date']) ?></p>

							</div>


							<div class="row" style="float:right; margin-right: 280px; margin-top: 20px;">
								<div class="col-xs-12">


									<div class="col-xs-6" >
										<a class="" onclick="closeCall('<?php echo $csr ?>')">	
											<span class="btn btn-info btn-sm mb5 mr5" >Close Call</span>
										</a>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="tab-content">        
			<div id="first" class="tab-pane1 fade in  ">

				<div class="row">
					<div class="col-xs-12">
						<ul class="nav nav-tabs" id="myTab">
							<li class="tabactive10  " id="tablist_10">
								<?php $tabUrl	 = "data-url=\"" . Yii::app()->createUrl('admin/scq/list', $fwpParams) . '"';
								?>
								<a data-toggle="tabajax" id="tid_10" <?php echo $tabUrl ?> class="bg-white  " href="#sec10"> FollowUp	</a>
							</li>
							<li class="tabactive20 " id="tablist_20">
								<?php $tabUrl	 = "data-url=\"" . Yii::app()->createUrl('admin/vendor/regprogress', array('vnd_id' => $assignModel["scq_to_be_followed_up_with_entity_id"], 'source' => 'mycall')) . '"';
								?>
								<a data-toggle="tabajax" id="tid_20" <?php echo $tabUrl ?> class="bg-white" href="#sec20"> Details	</a>
							</li>

						</ul>
						<div class="tab-content p0" id="details_2">

							<div id="sec10" tabid="10" class='tab-pane    '>
							</div>
							<div id="sec20" tabid="20" class='tab-pane ' >
							</div>


						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	// }
}
else
{
	?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<p><h2>--> Press "REFRESH" in Ops app and then refresh this page.</h2></p>
			</div>
		</div>
	</div>
	<?php
}
?>
<div id="selectedQuote"></div>
<script>
    function closeCall(csrid)
    {
        bootbox.confirm({
            title: "Close Call",
            message: "Are you sure want to close this call?",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirm'
                }
            },
            callback: function (result) {
                if (result)
                {
                    $href = '/admpnl/lead/closeVndApprovalCall';
                    jQuery.ajax({type: 'GET', url: $href, dataType: 'json', data: {"csrid": csrid},
                        success: function (data)
                        {
                            if (data.success == false)
                            {
                                bootbox.alert("You have a pending call back pending to be followed up. Please close it.");
                            } else
                            {
                                bootbox.alert("Successfully call closed.", function () {
                                    window.location.reload();
                                });

                            }

                        }
                    });
                }

            }
        });
    }


    $('#myTab a[data-toggle="tabajax"]').click(function (e)
    {

        $tid = $(this).attr('id');
        $idval = $tid.substr(4);

        $('.tab-pane').hide();
        $('#sec' + $idval).show();

        e.preventDefault();

        var url = $(this).attr("data-url");
        var href = this.hash;
        var pane = $(this);
        if ($tabCache.indexOf($(href).attr('id')) > -1)
        {

            pane.tab('show');
            return;
        }

        $(href).load(url, function (result)
        {
            pane.tab('show');
            addTabCache($(this).attr('tabid'));
        });
    });



    $(document).ready(function () {
        $('[data-toggle="tabajax"]:first').click();
    });
	 function autoAllocateLead(admId, type)
        {
            $href = '/admpnl/admin/autoAllocateLead';
            jQuery.ajax({type: 'GET', url: $href, dataType: 'json', data: {'adm_id' : admId, 'type': type},

                success: function (data)
                {
                    if (data.success == false)
                    {
                        bootbox.alert("Some error occured.");
                    } else
                    {
                        bootbox.alert("Successfully updated", function () {
                            window.location.reload();
                        });

                    }

                }
            });

        }

</script>


<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);

$time = Filter::getExecutionTime();

$GLOBALS['time'][9] = $time;
?>