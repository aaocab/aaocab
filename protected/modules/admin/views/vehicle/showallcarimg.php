<style type="text/css">
    .picform div.row{
        background-color: #EEEEFE;
        padding-top:3px;
        padding-bottom: 3px
    }
    .modal-header{
        padding:10px;
    }
</style>
<div class="panel"  id="showBoostDoc">
    <div class="panel-body">
        <div class="col-xs-12">
			<?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'all-verify-form', 'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => false,
							'errorCssClass'		 => 'has-error',
							'afterValidate'		 => 'js:function(form,data,hasError){
				if(!hasError){

				}
			    }'
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
			<div class="row"> 
			 <?php
                if (count($bmodel) == 0) {
                    echo "No Related document found. ";
                }else{?>
           <div class="row">
              <?php
                foreach ($bmodel as $value) {
                    $arr = VehicleDocs::model()->doctypeTxt;
                    $baseURL = Yii::app()->params['fullBaseURL'];
					$vehicleModel = Vehicles::model()->findByPk($value['vhc_id']);
					$flag = 0;
					if(in_array($value['bpay_type'], [8, 9, 10, 11]))
					{
					   $checkVerification = VehicleStats::model()->checkVerification($value['vhc_id'], $value['bpay_type']);
					}
					else
					{
						$checkVerification = '';
					}
					$bookingId = Booking::model()->getCodeById($value['bpay_bkg_id']);
					if($checkVerification == 'Cab Verification'){
						$title= "Verify that pictures are matching cab number ". $vehicleModel->vhc_number . " against booking : ".$bookingId;
						$flag = 1;
					}
                    else {
						 $title= "Verify that car has all the boost related stickers also verify that pictures are matching cab number  ". $vehicleModel->vhc_number . "  against booking : ".$bookingId;
                                                $flag = 2;
					}
//                    if (strpos($value['bpay_image'], 'attachments') !== false) {
//                        $url = Yii::app()->request->hostInfo;
//                        $ImagePath = $url . $value['bpay_image'];
//                    } else {
//                        $DS = DIRECTORY_SEPARATOR;
//                        $boostImage = $value['bpay_image'];
//                        $ImagePath = AttachmentProcessing::ImagePath($boostImage);
//                    }
					$ImagePath	 = BookingPayDocs::getDocPathById($value['bpay_id']);
                    ?>
                <div class="col-xs-3 mt30">
                    <div class="text-center">
                        <b><?php echo $arr[$value['bpay_type']]; ?></b>
                        <br>
                        <div id="boostPic">
                        <img  src="<?php echo $ImagePath; ?>" id="boostImg" onclick="showpic('<?= $value['bpay_id']; ?>','dgdg', '<?= $title ?>','<?= $value['bpay_type']; ?>','<?= $value['vhc_id']; ?>')" class="pic-bordered pic btn p0 pt10"> 
                        </div>                        
                    </div>
                </div>
              
			   <?php } ?>
          </div>
			
			 <div  class="row">
			 <?php if ($flag == 2){ ?>
			 <div class="col-xs-12 mt30" id="new_msg1">
			    <p id="new_msg2" style="display:none;">
                Need proper pictures for car verification <br>
				Full Car picture is needed with front license plate<br>
				Full car picture is needed with back license plate.<br>
				</p>
				<p>
				Please make sure you have stickers on the car if you want to enable Gozo boost. Gozo boost give you many benefit. See video to learn more</p>
				<ol type="1">
				<li> 1) <a target="_blank" href="https://youtu.be/h_E4qB164ho">https://youtu.be/h_E4qB164ho</a></li>
				<li> 2) <a target="_blank" href="https://youtu.be/8PLKYZM_5v4">https://youtu.be/8PLKYZM_5v4</a></li>
				<li> 3) <a target="_blank" href="https://youtu.be/TcLqWIKkFPI">https://youtu.be/TcLqWIKkFPI</a></li>
                </ol>
				<p>Please see these above videos it’s helpful to understand all things about boost.				
				</p>
			</div>	
			 <?php } ?>
			 <div class="col-xs-12 mt30">
		        <textarea class="form-control" name="remarks"  name="remarks" id="remarks"  placeholder="Please write remarks"></textarea>
			 </div>
			 </div><br>
		  <div class="row text-center mb5">
			  <?php
			  if ($flag == 1){ ?>
				<a class="btn btn-success btn-xs pl5 pr5" id="btnAllCarAppr" name="btnAllCarAppr" onclick="btnStatus('<?= $value['vhc_id']; ?>', '1', '<?= $value['bpay_bkg_id']; ?>', '<?=$flag?>')">Approve All</a>
				<a class="btn btn-danger btn-xs pl5 pr5" id="btnAllCarDspr" name="btnAllCarDspr" onclick="btnStatus('<?= $value['vhc_id']; ?>', '2', '<?= $value['bpay_bkg_id']; ?>', '<?=$flag?>')">Disapprove All</a>
			 <?php } ?>
				<?php
			  if ($flag == 2){ ?>
				<a class="btn btn-success btn-xs pl5 pr5" id="btnAllBoostAppr" name="btnAllBoostAppr" onclick="btnStatus('<?= $value['vhc_id']; ?>', '1', '<?= $value['bpay_bkg_id']; ?>', '<?=$flag?>')">Approve All</a>
				 <a class="btn btn-warning btn-xs pl5 pr5" id="btnCarVerifiedBoostUnverified" name="btnCarVerifiedBoostUnverified" onclick="btnStatus('<?= $value['vhc_id']; ?>', '3', '<?= $value['bpay_bkg_id']; ?>', '<?=$flag?>')">Disapprove boost & approve cab</a>
                                <a class="btn btn-danger btn-xs pl5 pr5" id="btnAllBoostDspr" name="btnAllBoostDspr" onclick="btnStatus('<?= $value['vhc_id']; ?>', '2', '<?= $value['bpay_bkg_id']; ?>', '<?=$flag?>')">Disapprove boost & cab both </a>
			   
			  <?php } ?>
          </div>
			 <?php } ?>
			</div>
			 <?php $this->endWidget(); ?>
		  </div>
    </div>
</div>
<script type="text/javascript">
        function showpic(pid, doctype, vnum, type, vhcid) {
         if(type ==8 || type == 9 || type==10 || type==11)
        {
          var title = vnum ;   
        }
        else
        {
           var title = doctype + " - " + vnum; 
        }
        $.ajax({
            "type": "GET",
            "dataType": "html",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/showcarimg')) ?>",
            "data": {"bpayId": pid, "vhcId": vhcid},

            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    className: "bootbox-xs",
                    title: "<span class='text-center'>" + title +  "</span>",
                    size: "large",
                    onEscape: function () {
                        box.modal('hide');
                    }
                }).on('shown.bs.modal', function () {
					box.removeAttr("tabindex");
                });
            }
        });
    }
	
	function btnStatus(vhcid, status, bkgid, verificationtype) {
	//var bkgid = 1871706;
	//var vhcid = 66213;
	 var $adminUrl = "<?= Yii::app()->createAbsoluteUrl('admin') ?>";
	
	var remarks = $('textarea#remarks').val();
	  if(status ==1)
	  {
		var message = "Do you want to approve all Images ?";
		$("#new_msg1").hide();
	  }
	   else  if(status ==2)
	  {
        var message = "Do you want to reject all Images ?";
		$("#new_msg2").show();
	  }
	  else
	  {
		  var message = "Do you want to car verify, boost unverify?"; 
		}
	  if(verificationtype == 1)
	  {
		var title = "Car Verification";
	  }
	  else
	  {
		var title = "Boost Verification";  
	  }
	   if (remarks.trim() == '' && status ==2) {
		   alert("Please Enter Remarks.");
		}
		else
		{
		   bootbox.confirm({
			title: "<span class='text-center'>" + title +  "</span>",
            message: "<span class='text-center'>" + message +  "</span>",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn-info'
                },
                cancel: {
                    label: 'CANCEL',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    var href1 = '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/approveallcarimagesnew')) ?>';
                    jQuery.ajax({'type': 'GET', 'url': href1,
                        'data': {"btntype": status, "vhcId": vhcid, "remarks": remarks, "bkgId": bkgid, "verificationType": verificationtype},
                        success: function (data1)
                        {
                         // alert($adminUrl);
                            bootbox.hideAll();
                            location.href = $adminUrl+'/vehicle/carverifydoclist?page='+<?php echo $rootPage;?>;
                            // $('#docgrid').yiiGridView('update');
                           $("#"+bkgid+"update").css('border', '1px solid #ccc');
                          //window.location.reload(true);
                        }
                    });
                }
            }
        });
		}
	 }
</script>