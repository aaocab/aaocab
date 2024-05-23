<div class="row">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-xs-12">
				<?php
				if (Yii::app()->user->hasFlash('notice'))
				{
					?>
					<div class="alert alert-block alert-danger">
						<?php echo Yii::app()->user->getFlash('notice'); ?>
					</div>
					<?php
				}
				if (Yii::app()->user->hasFlash('success'))
				{
					?>
					<div class="alert alert-block alert-success">
						<?php echo Yii::app()->user->getFlash('success'); ?>
					</div>
					<?php
				}
				?>
				<div class = "row">
					<div class = "col-xs-12 text-center h3 mt0">Documents</div>
				</div>
				<div class = "row bordered mt10">
					<?php
					$docType = Document::model()->docType();
					if ($viewType == "driver")
					{
						unset($docType[6]);
					}
					else if ($viewType == "vendor")
					{
						unset($docType[7]);
					}
					?>	
					<div class="col-xs-12">
						<div class="panel panel-default panel-border">
							<div class="panel-body">
								<?php
								foreach ($docType as $key => $data)
								{
									foreach ($docpath as $value)
									{
										?> 
										<div class="row">
											<div class="col-xs-12 col-sm-4">
												<div class="col-xs-12 col-sm-4">
													<label><b><?php echo $docType[$key][1]; ?></b></label>:
												</div>
												<div class="col-xs-12 col-sm-4">
													<?php
													$s3frontdata = $value["doc_front_s3data" . $key];
													$filePath	 = $value["doc_file_front_path" . $key];
													$s3FrontArr	 = json_decode($s3frontdata, true);
													$pathfront	 = "";
													$pathfront	 = Document::getDocPathById($value['doc_id' . $key], 1);

													echo $docFrontLink = ($filePath != '' || $s3frontdata != '') ? '<a href="' . $pathfront . '" target="_blank">Attachment Link</a>' : 'Missing';
													?>
												</div>
											</div>
											<?php
											if ($docType[$key][2] != '')
											{
												?>
												<div class="col-xs-12 col-sm-4">
													<div class="col-xs-12 col-sm-4">
														<label><b><?php echo $docType[$key][2]; ?></b></label>:
													</div>
													<div class="col-xs-12 col-sm-4">
														<?php
														$pathback		 = "";
														$s3Backdata		 = $value["doc_back_s3data" . $key];
														$fileBackPath	 = $value["doc_file_back_path" . $key];
														$s3BackArr		 = json_decode($s3Backdata, true);
														$pathback		 = "";
														$pathback		 = Document::getDocPathById($value['doc_id' . $key], 2);

														echo $docBackLink = ($fileBackPath != '' || $s3Backdata != '') ? '<a href="' . $pathback . '" target="_blank">Attachment Link</a>' : 'Missing';
														?>
													</div>
												</div>
											<?php } ?>
											<?php
											if ($value["doc_status" . $key] != 1)
											{
												?>
												<span id="<?= 'upload' . $key ?>" style="float:left;"><img id="frLicReject" src="<?= Yii::app()->request->baseUrl; ?>/images/icon/uploads.png" alt="Upload" title="Upload" onclick="uploadDocuments('<?= $cttid ?>', '<?= $key; ?>')" style="cursor:pointer;"></span>
											<?php } ?>
										</div> 
										<?php
									}
								}
								?>
							</div>
						</div>
					</div>
					<?php
					if ($countDocToUpload > 0)
					{
						?>
						<div class="col-xs-12  pt10 text-center"><?php echo CHtml::link('Upload Documents', Yii::app()->createUrl('admin/document/uploadDoc/', ['doc_id' => $model->doc_id]), ['class' => 'btn btn-primary mb10', 'target' => "_blank"]) ?></div>
					<?php } ?> 
				</div>
			</div>
		</div>    
	</div>
</div> 
<script  type="text/javascript">
	function uploadDocuments(cttid, doctype)
	{
		var href = '<?= Yii::app()->createUrl("admin/document/upload"); ?>';
		jQuery.ajax({type: 'GET',
			url: href,
			data: {"ctt_id": cttid, "doc_type": doctype, "viewType": '<?= $viewType ?>'},
			success: function (data)
			{
				upsellBox = bootbox.dialog({
					message: data,
					title: 'Add Document',
					onEscape: function () {
						// user pressed escape
					},
				});

			}
		});
	}

//    jQuery(document).ready(function(){
//        var x =  <? //$_REQUEST['vndctt'];      ?>
//        alert('aaaaaaaaa');
//        alert(x);
//        <? //if($_REQUEST['vndctt']=="vndctt"){      ?>
//            $('.bootbox-close-button close').modal('toggle');
//            bootbox.hideAll();
//		    return false;    
//        <? //}      ?>   
//             
//    });

</script>