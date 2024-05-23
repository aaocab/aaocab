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
                if (count($vmodel) == 0) {
                    echo "No Related document found. ";
                }else{?>
           <div class="row">
              <?php
                foreach ($vmodel as $value) {
                    $arr = VehicleDocs::model()->doctypeTxt;
                    $baseURL = Yii::app()->params['fullBaseURL'];
					$vehicleModel = Vehicles::model()->findByPk($value['vhd_vhc_id']);
					$flag = 0;
					if(in_array($value['vhd_type'], [8, 9, 10, 11]))
					{
					   $checkVerification = VehicleStats::model()->checkVerification($value['vhd_vhc_id'], $value['vhd_type']);
					}
					else
					{
						$checkVerification = '';
					}
					if($checkVerification == 'Cab Verification'){
						$title= "Verify that pictures are matching cab number ". $vehicleModel->vhc_number . " ";
						$flag = 1;
					}
                                        else {
						 $title= "Verify that car has all the boost related stickers also Verify that pictures are matching cab number  ". $vehicleModel->vhc_number . " ";
                                                $flag = 2;
					}
                    $filePath = VehicleDocs::getDocPathById($value['vhd_id']);                    
                    if (strpos($value['vhd_file'], 'attachments') !== false) {
                        $url = Yii::app()->request->hostInfo;
                        
                        $ImagePath = $url . $filePath;
                    } else {
                        $DS = DIRECTORY_SEPARATOR;
                        $boostImage = $filePath;
                        $ImagePath = AttachmentProcessing::ImagePath($boostImage);
                    }
					if ($boost == 1){
						$title= "Verify that car has all the boost related stickers also Verify that pictures are matching cab number  ". $vehicleModel->vhc_number . " ";
					}
                    ?>
                <div class="col-xs-3 mt30">
                    <div class="text-center">
                        <b><?php echo $arr[$value['vhd_type']]; ?></b>
                        <br>
                        <div id="boostPic">
                        <img  src="<?php echo $ImagePath; ?>" id="boostImg" onclick="showpic('<?= $value['vhd_id']; ?>','dgdg', '<?= $title ?>','<?= $value['vhd_type']; ?>')" class="pic-bordered pic btn p0 pt10"> 
                        </div>                        
                    </div>
                </div>
              
			   <?php } ?>
          </div>
			
			 <div  class="row">
			 <?php if ($boost == 1){ ?>
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
			  if ($flag == 1 && $boost == 0){ ?>
				<a class="btn btn-primary btn-xs pl5 pr5" id="btnAllAppr" name="btnAppr" onclick="btnStatus('<?= $value['vhd_vhc_id']; ?>', '1', '<?= $flag ?>')">Approve</a>
				<a class="btn btn-danger btn-xs pl5 pr5" id="btnAllDspr" name="btnDspr" onclick="btnStatus('<?= $value['vhd_vhc_id']; ?>', '2', '<?= $flag ?>')">Disapprove</a>
			 <?php } ?>
			 <?php if ($boost == 1){ ?>
				<a class="btn btn-success btn-xs pl5 pr5" id="btnAllBoostAppr" name="btnAllBoostAppr" onclick="btnStatus2('<?= $value['vhd_vhc_id']; ?>', '1', '2')">Approve All</a>
				<a class="btn btn-warning btn-xs pl5 pr5" id="btnCarVerifiedBoostUnverified" name="btnCarVerifiedBoostUnverified" onclick="btnStatus2('<?= $value['vhd_vhc_id']; ?>', '3','2')">Disapprove boost & approve cab</a>
				<a class="btn btn-danger btn-xs pl5 pr5" id="btnAllBoostDspr" name="btnAllBoostDspr" onclick="btnStatus2('<?= $value['vhd_vhc_id']; ?>', '2','2')">Disapprove boost & cab both </a>
				<div class="col-xs-12  bg-gray"></div>
			 <?php } ?>
          </div>
			 <?php } ?>
			</div>			
			 <?php $this->endWidget(); ?>
		  </div>
    </div>
</div>
<script type="text/javascript">
     function showpic(pid, doctype, vnum, type) {
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
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/showdocimg')) ?>",
            "data": {"vhdid": pid,"boost":1},

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
	
	function btnStatus(vhcid, status, verificationType) {
	var remarks = $('textarea#remarks').val();
	  if(status ==1)
	  {
		var message = "Do you want to approve all Images successfully?";
	  }
	   else
	  {
        var message = "Do you want to reject all Images successfully?";
	  }
	   if (remarks.trim() == '' && status ==2) {
		   alert("Please Enter Remarks.");
		}
		else
		{
		   bootbox.confirm({
			title: "<span class='text-center'>" + "Car verification" +  "</span>",
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
                    var href1 = '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/approveallcarimages')) ?>';
                    jQuery.ajax({'type': 'GET', 'url': href1,
                        'data': {"btntype": status, "vhcId": vhcid, "remarks": remarks, "verificationType": verificationType},
                        success: function (data1)
                        {
                            bootbox.hideAll()
                            window.location.reload(true);

                        }
                    });
                }
            }
        });
		}
	 }
	 
	function btnStatus2(vhcid, status, verificationtype) 
	{ 	
		var remarks = $('textarea#remarks').val();
		var title = "Boost Verification";		
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
		if (remarks.trim() == '') {
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
                    var href1 = '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vehicle/approveboostcarimages')) ?>';
                    jQuery.ajax({'type': 'GET', 'url': href1,
                        'data': {"btntype": status, "vhcId": vhcid, "remarks": remarks, "verificationType": verificationtype},
                        success: function (data1)
                        {
                            bootbox.hideAll()
                            window.location.reload(true);
                        }
                    });
                }
            }
        });
		}
	 }
</script>