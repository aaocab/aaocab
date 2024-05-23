<style type="text/css">
    .pic{
        max-width: 100%;
        max-height: 175px;
    }
</style>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');

$docType = Document::model()->documentType();
unset($docType[1]);
unset($docType[7]);
?>
<div id="list-content">
    <div class="row" >
        <div class="panel">
            <div class="panel-heading">Pending vendor document to approve</div>
            <div class="panel-body">
                <div class="col-xs-12">
					<?php
					$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'vendortype-form',
						'enableClientValidation' => true,
						'clientOptions'			 => array(
							'validateOnSubmit'	 => true,
							'errorCssClass'		 => 'has-error'
						),
						'enableAjaxValidation'	 => false,
						'errorMessageCssClass'	 => 'help-block',
						'htmlOptions'			 => array(
							'class' => '',
						),
					));
					?>
                    <div class="row">

                        <div class="col-xs-6 col-sm-3">
							<?= $form->textFieldGroup($model, 'contactname', array('label' => 'Contact Name', 'widgetOptions' => array('htmlOptions' => array('value' => $model->contactname, 'placeholder' => "Contact Name")))); ?>
                        </div>
						<!--						<div class="col-xs-6 col-sm-3">-->
						<!--									<label class="control-label">Vendor Name</label>-->
						<?php
//									$dataVendor = Vendors::model()->getJSON($vendorList);
//									$this->widget('booster.widgets.TbSelect2', array(
//										'model'			 => $modelVendor,
//										'attribute'		 => 'vnd_id',
//										'val'			 => $modelVendor->vnd_id,
//										'asDropDownList' => FALSE,
//										'options'		 => array('data' => new CJavaScriptExpression($dataVendor)),
//										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
//									));
						?>

						<!--						</div>-->
						<div class="col-xs-6 col-sm-3">
							<label class="control-label">Vendor Name</label>
							<?php
//								$data	 = Vendors::model()->getJSON();
//								$this->widget('booster.widgets.TbSelect2', array(
//									'model'			 => $model,
//									'attribute'		 => 'vhc_vendor_id1',
//									'val'			 => $model->vhc_vendor_id1,
//									'asDropDownList' => FALSE,
//									'options'		 => array('data' => new CJavaScriptExpression($data)),
//									'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
//								));

							$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
								'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
								'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
								'openOnFocus'		 => true, 'preload'			 => false,
								'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
								'addPrecedence'		 => false,];
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $modelVendor,
								'attribute'			 => 'vnd_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Vendor",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%'),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                              populateVendor(this, '{$modelVendor->vnd_id}');
                        }",
							'load'			 => "js:function(query, callback){
                                            loadVendor(query, callback);
                        }",
							'render'		 => "js:{
                                                option: function(item, escape){
                                                    return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                                },
                                                option_create: function(data, escape){
                                                    return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                                }
                                            }",
								),
							));
							?>
							<span class="has-error"><? echo $form->error($model, 'vnd_id'); ?></span>
						</div>

                        <div class="col-xs-6 col-sm-3">
                            <label class="control-label">Document Type </label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'doc_type',
							'val'			 => $model->doc_type,
							'data'			 => $docType,
							'options'		 => ['allowClear' => true],
							'htmlOptions'	 => array('style'			 => 'width:100%',
							'placeholder'	 => 'Select Document Type')
							));
							?>
                        </div>
                        <div class="col-xs-12 col-sm-3 text-center mt20 pt5">
                            <button class="btn btn-primary" type="submit" style="width: 185px;"  name="documentSearch">Search</button>
                        </div>
                    </div>
					<?php $this->endWidget(); ?>
                </div>
                <div class="docgrid">
                    <div class="col-xs-12">
						<div class="row">
							<?php
							if (!empty($dataProvider))
							{
								$params									 = array_filter($_REQUEST);
								$dataProvider->getPagination()->params	 = $params;
								$dataProvider->getSort()->params		 = $params;
								$items									 = '';
								$pdfImage								 = "/images/pdf.jpg";
								$noImage								 = "/images/no-image.png";
								foreach ($dataProvider->getData() as $doc)
								{
									$name = "";
									if ($doc['ctt_user_type'] == 1)
									{
										$name = $doc['ctt_first_name'] . " " . $doc['ctt_last_name'];
									}
									else
									{
										$name = $doc['ctt_business_name'];
									}
									$picid = $doc['ctt_id'];
									if ($model->doc_type != "")
									{
										if ($model->doc_type == 2)
										{
											if ($doc['doc_id2'] != "")
											{
												if ($doc['doc_file_front_path2'] != "")
												{
													$pathfront		 = Document::getDocPathById($doc['doc_id2'], 1);
													$fileImage	 = '<img src="' . $pathfront . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','0'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','0'" . ')">';
													$filename	 = (pathinfo($pathfront, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_voter_no'] . ' (Voter Card Front Side)</b><br>' . $filename . '</div></div></div>';
												}
												if ($doc['doc_file_back_path2'] != "")
												{

													$pathback = Document::getDocPathById($doc['doc_id2'], 2);
													$fileImage	 = '<img src="' . $pathback . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','1'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','1'" . ')">';
													$filename	 = (pathinfo($pathback, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_voter_no'] . ' (Voter Card Back Side)</b><br>' . $filename . '</div></div></div>';
												}
											}
										}
										if ($model->doc_type == 3)
										{
											if ($doc['doc_id3'] != "")
											{

												if ($doc['doc_file_front_path3'] != "")
												{
													$pathfront		 = Document::getDocPathById($doc['doc_id3'], 1);
													$fileImage	 = '<img src="' . $pathfront . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','0'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','0'" . ')">';
													$filename	 = (pathinfo($pathfront, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_aadhaar_no'] . ' (Aadhaar Card Front Side)</b><br>' . $filename . '</div></div></div>';
												}

												if ($doc['doc_file_back_path3'] != "")
												{
													$pathback = Document::getDocPathById($doc['doc_id3'], 2);
													$fileImage	 = '<img src="' . $pathback . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','1'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','1'" . ')">';
													$filename	 = (pathinfo($pathback, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_aadhaar_no'] . ' (Aadhaar Card Back Side)</b><br>' . $filename . '</div></div></div>';
												}
											}
										}
										if ($model->doc_type == 4)
										{
											if ($doc['doc_id4'] != "")
											{

												if ($doc['doc_file_front_path4'] != "")
												{
													$pathfront		 = Document::getDocPathById($doc['doc_id4'], 1);
													$fileImage	 = '<img src="' . $pathfront . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','0'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','0'" . ')">';
													$filename	 = (pathinfo($pathfront, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_pan_no'] . ' (Pan Card Front Side)</b><br>' . $filename . '</div></div></div>';
												}

												if ($doc['doc_file_back_path4'] != "")
												{
													$pathback = Document::getDocPathById($doc['doc_id4'], 2);
													$fileImage	 = '<img src="' . $pathback . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','1'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','1'" . ')">';
													$filename	 = (pathinfo($pathback, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_pan_no'] . ' (Pan Card Back Side)</b><br>' . $filename . '</div></div></div>';
												}
											}
										}
										if ($model->doc_type == 5)
										{
											if ($doc['doc_id5'] != "")
											{
												if ($doc['doc_file_front_path5'] != "")
												{
													$pathfront		 = Document::getDocPathById($doc['doc_id5'], 1);
													$fileImage	 = '<img src="' . $pathfront . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','0'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','0'" . ')">';
													$filename	 = (pathinfo($pathfront, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_license_no'] . ' (License Card Front Side)</b><br>' . $filename . '</div></div></div>';
												}

												if ($doc['doc_file_back_path5'] != "")
												{
													$pathback = Document::getDocPathById($doc['doc_id5'], 2);
													$fileImage	 = '<img src="' . $pathback . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','1'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','1'" . ')">';
													$filename	 = (pathinfo($pathback, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_license_no'] . ' (License Card Back Side)</b><br>' . $filename . '</div></div></div>';
												}
											}
										}
										if ($model->doc_type == 6)
										{
											if ($doc['doc_id6'] != "")
											{
												if ($doc['doc_file_front_path6'] != "")
												{
													$pathfront		 = Document::getDocPathById($doc['doc_id6'], 1);
													$fileImage	 = '<img src="' . $pathfront . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id6'] . "','','6','0'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id6'] . "','','6','0'" . ')">';
													$filename	 = (pathinfo($pathfront, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b> (Memorandum)</b><br>' . $filename . '</div></div></div>';
												}
											}
										}
									}
									else
									{
										if ($doc['doc_id2'] != "")
										{
											if ($doc['doc_file_front_path2'] != "")
											{
												$pathfront		 = Document::getDocPathById($doc['doc_id2'], 1);
												$fileImage	 = '<img src="' . $pathfront . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','0'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','0'" . ')">';
												$filename	 = (pathinfo($pathfront, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_voter_no'] . ' (Voter Card Front Side)</b><br>' . $filename . '</div></div></div>';
											}


											if ($doc['doc_file_back_path2'] != "")
											{
												$pathback = Document::getDocPathById($doc['doc_id2'], 2);
												$fileImage	 = '<img src="' . $pathback . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','1'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','1'" . ')">';
												$filename	 = (pathinfo($pathback, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_voter_no'] . ' (Voter Card Back Side)</b><br>' . $filename . '</div></div></div>';
											}
										}

										if ($doc['doc_id3'] != "")
										{
											if ($doc['doc_file_front_path3'] != "")
											{
												$pathfront		 = Document::getDocPathById($doc['doc_id3'], 1);
												$fileImage	 = '<img src="' . $pathfront . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','0'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','0'" . ')">';
												$filename	 = (pathinfo($pathfront, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_aadhaar_no'] . ' (Aadhaar Card Front Side)</b><br>' . $filename . '</div></div></div>';
											}
											if ($doc['doc_file_back_path3'] != "")
											{
												$pathback = Document::getDocPathById($doc['doc_id3'], 2);
												$fileImage	 = '<img src="' . $pathback . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','1'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','1'" . ')">';
												$filename	 = (pathinfo($pathback, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_aadhaar_no'] . ' (Aadhaar Card Back Side)</b><br>' . $filename . '</div></div></div>';
											}
										}

										if ($doc['doc_id4'] != "")
										{
											if ($doc['doc_file_front_path4'] != "")
											{
												$pathfront		 = Document::getDocPathById($doc['doc_id4'], 1);
												$fileImage	 = '<img src="' . $pathfront . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','0'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','0'" . ')">';
												$filename	 = (pathinfo($pathfront, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_pan_no'] . ' (Pan Card Front Side)</b><br>' . $filename . '</div></div></div>';
											}

											if ($doc['doc_file_back_path4'] != "")
											{
												$pathback = Document::getDocPathById($doc['doc_id4'], 2);
												$fileImage	 = '<img src="' . $pathback . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','1'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','1'" . ')">';
												$filename	 = (pathinfo($pathback, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_pan_no'] . ' (Pan Card Back Side)</b><br>' . $filename . '</div></div></div>';
											}
										}

										if ($doc['doc_id5'] != "")
										{
											if ($doc['doc_file_front_path5'] != "")
											{
												$pathfront		 = Document::getDocPathById($doc['doc_id5'], 1);
												$fileImage	 = '<img src="' . $pathfront . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','0'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','0'" . ')">';
												$filename	 = (pathinfo($pathfront, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_license_no'] . ' (License Card Front Side)</b><br>' . $filename . '</div></div></div>';
											}
											if ($doc['doc_file_back_path5'] != "")
											{
												$pathback = Document::getDocPathById($doc['doc_id5'], 2);
												$fileImage	 = '<img src="' . $pathback . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','1'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','1'" . ')">';
												$filename	 = (pathinfo($pathback, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_license_no'] . ' (License Card Back Side)</b><br>' . $filename . '</div></div></div>';
											}
										}

										if ($doc['doc_id6'] != "")
										{
											if ($doc['doc_file_front_path6'] != "")
											{
												$pathfront		 = Document::getDocPathById($doc['doc_id6'], 1);
												$fileImage	 = '<img src="' . $pathfront . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id6'] . "','','6','0'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id6'] . "','','6','0'" . ')">';
												$filename	 = (pathinfo($pathfront, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>(Memorandum)</b><br>' . $filename . '</div></div></div>';
											}
										}
									}
								}
								if ($items == "")
								{
									$items .= '<table class="table table-striped table-bordered mb0 table"><tbody><tr><td  class="empty"><span class="empty">No results found.</span></td></tr></tbody></table>';
								}

								$this->widget('booster.widgets.TbGridView', array(
									'responsiveTable'	 => true,
									'filter'			 => $model,
									'dataProvider'		 => $dataProvider,
									'id'				 => 'vendorListGrid',
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>$items</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'emptyText'			 => 'We have not found anything related to your query.'
								));
							}
							?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<script type="text/javascript">
    function openpic(pid, docid, dnam, doctype, sidetype) {
        $.ajax({
            "type": "GET",
            "dataType": "html",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/document/showdoc')) ?>",
            "data": {"ctt_id": pid, "docid": docid, 'doctype': doctype, 'sidetype': sidetype, 'page': 'vendor'},
            "success": function (data) {
                box = bootbox.dialog({
                    message: data,
                    className: "bootbox-xs",
                    title: "<span class='text-center'>" + doctype + " - " + dnam + "</span>",
                    size: "large",
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }

</script>