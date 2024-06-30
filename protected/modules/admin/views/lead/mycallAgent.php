<style>
    .dev-height{
        overflow: auto;
        height: 200px;
        border: #dfdfdf 1px solid;
        padding: 15px;
    }
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
//var_dump($vndModel);exit;
if ($model["scq_id"] != '')
{
	$agtModel				 = Agents::model()->findByPk($agentId);
	?>
	<div class="">
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-default main-tab1">
					<div class="panel-body panel-border">
						<div class="row">
							<div class="col-xs-12 col-sm-5 table-responsive">
								<table class="table table-striped table-bordered">
									<tr>
										<td><b>Agent</b></td>
										<td><?php echo $agtModel->agt_fname . ' ' . $agtModel->agt_lname ?> (<?php echo Agents::model()->getAgentType($agtModel->agt_type); ?>)</td>
									</tr>
									<?php
									$companyTypeVal			 = Agents::model()->getCompanyType($agtModel->agt_company_type);
									$companyType			 = ( $agtModel->agt_company_type > 0) ? ' (' . $companyTypeVal . ')' : '';
									?>
									<tr>
										<td><b>Owner Name: </b></td>
										<td><?php echo $agtModel->agt_owner_name; ?></td>
									</tr>
									<tr>
										<td><b>Company Name: </b></td>
										<td><?php echo$agtModel->agt_company . $companyType; ?></td>
									</tr>
									<tr>
										<td><b>phone no.</b></td>
										<td><?php echo ($agtModel->agt_phone == '') ? '' : '+' . $agtModel->agt_phone_country_code . ' ' . $agtModel->agt_phone; ?></td>
									</tr>
									<tr>
										<td><b>Preferred method of contact</b></td>
										<td>Phone</td>
									</tr>                        


								</table>
							</div>
							<div class="col-xs-6" >
								<a class="" onclick="closeCall()">	
									<span class="btn btn-info btn-sm mb5 mr5" >Close Call</span>
								</a>
							</div>
						</div>

					</div>
				</div>
			</div>

		</div>

		<div class="tab-content">        
			<div id="first" class="tab-pane1 fade in active">

				<div class="row">
					<div class="col-xs-12 pt5">
						<div class="row">
							<ul class="nav nav-tabs  " id="myTab">
								<li class='tabactive0' id="tablist_0"><a data-toggle="tab" id="tid_0" class="bg-white" href="#sec0"><?php echo 'Details' ?>  </a></li>
								<li class="tabactive10 " id="tablist_10">
									<?php $tabUrl					 = "data-url=\"" . Yii::app()->createUrl('admin/scq/list', array('refId' => $model['scq_related_bkg_id'], 'fwpId' => $model['scq_id'], 'isMycall' => 1)) . '"';
									?>
									<a data-toggle="tabajax" id="tid_10" <?php echo $tabUrl ?> class="bg-white" href="#sec10"> FollowUp	</a>
								</li>

							</ul>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="tab-content p0">

						<div id="<?php echo 'sec0' ?>" tabid="0" class="tab-pane active">			 

							<?php
							$creditVal				 = Agents::model()->getAgentById([$agentId]);
							$agentAdjustTrans		 = AccountTransDetails::getAdjustableAmount(['agentId' => $agentId]);
							$agtData				 = (($creditVal[0]) ? $creditVal[0] : []) + $agentAdjustTrans;
							$agtData['agtPayable']	 = $agentAdjustTrans['transaction_amount'];
							$agtModel				 = Agents::model()->findByPk($agentId);
							$cttId					 = ContactProfile::getByEntityId($agtModel->agt_id, UserInfo::TYPE_AGENT);
							$cttPhone				 = ContactPhone::getContactNumber($cttId);
							$cttEmail				 = ContactEmail::findPrmryEmailByContactId($cttId);
							echo $this->renderPartial('../agent/view', array(
								'agtData'	 => $agtData,
								'cttPhone'	 => $cttPhone,
								'cttEmail'	 => $cttEmail,
								'agentId'	 => $agentId,
								'agtModel'	 => $agtModel,
								'mycall'	 => 1,
									), false, false);
							?>
						</div>

						<?php
						$paneTab				 = [3, 5, 6, 10, 20];
						foreach ($paneTab as $val)
						{
							?>
							<div id="<?php echo 'sec' . $val ?>" tabid="<?php echo $val ?>" class='<?php echo 'tab-pane tabactive' . $val ?>'>
							</div>
							<?php
						}
						?>
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


<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);

$time = Filter::getExecutionTime();

$GLOBALS['time'][9] = $time;
?>
<script type="text/javascript">
    function closeCall()
    {
        debugger;
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
                debugger;
                if (result)
                {
                    $href = '/aaohome/lead/CloseMyCallAgent';
                    jQuery.ajax({type: 'GET', url: $href, dataType: 'json', data: {},
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
    $('#myTab a[data-toggle="tab"]').click(function (e)
    {
        $tid = $(this).attr('id');
        $idval = $tid.substr(4);
        $('.tab-pane').hide();
        if ($idval == 0)
        {
            $('.tabHide').hide();
            $('#vendorDetails').show();
        }
        $('#sec' + $idval).show();
    });
    $('#viewId a[data-toggle="tab"]').click(function (e)
    {
        tid = $(this).attr('href');
        $('.tabHide').hide();
        $(tid).show();

    });
    $('#myTab a[data-toggle="tabajax"]').click(function (e)
    {
        $tid = $(this).attr('id');
        $idval = $tid.substr(4);
        $('.tab-pane').hide();
        if ($idval == 0)
        {
            $('.tabHide').hide();
            $('#vendorDetails').show();
        }
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
        // ajax load from data-url
        $(href).load(url, function (result)
        {
            pane.tab('show');
            addTabCache($(this).attr('tabid'));
        });
    });
    function autoAllocateLead(admId, type)
    {
        $href = '/aaohome/admin/autoAllocateLead';
        jQuery.ajax({type: 'GET', url: $href, dataType: 'json', data: {'adm_id': admId, 'type': type},

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