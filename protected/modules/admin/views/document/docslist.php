<style type="text/css">
    .pic{
        max-width: 100%;
        max-height: 175px;
    }
</style>
<?php
$docType = Document::model()->documentType();
unset($docType[1]);
?>
<div id="list-content">
    <div class="row" >
        <div class="panel">
            <div class="panel-heading">Pending Document to approve</div>
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

						<div class="col-xs-6 col-sm-3">
                            <label class="control-label">Document Type </label>
							<?	$this->widget('booster.widgets.TbSelect2', array(
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
													$Url = "";
//													if (substr_count($doc['doc_file_front_path2'], "attachments") > 0)
//													{
//														$Url .= $doc['doc_file_front_path2'];
//													}
//													else
//													{
//														$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path2']);
//													}
                                                                                                        $Url = Document::getDocPathById($doc['doc_id2'], 1);
													$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','0'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','0'" . ')">';
													$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_voter_no'] . ' (Voter Card Front Side)</b><br>' . $filename . '</div></div></div>';
												}
												if ($doc['doc_file_back_path2'] != "")
												{
													$Url = "";
//													if (substr_count($doc['doc_file_back_path2'], "attachments") > 0)
//													{
//														$Url .= $doc['doc_file_back_path2'];
//													}
//													else
//													{
//														$Url .= AttachmentProcessing::ImagePath($doc['doc_file_back_path2']);
//													}
                                                                                                        $Url = Document::getDocPathById($doc['doc_id2'], 2);
													$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','1'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','1'" . ')">';
													$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
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
													$Url = "";
//													if (substr_count($doc['doc_file_front_path3'], "attachments") > 0)
//													{
//														$Url .= $doc['doc_file_front_path3'];
//													}
//													else
//													{
//														$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path3']);
//													}
                                                                                                        $Url = Document::getDocPathById($doc['doc_id3'], 1);
													$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','0'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','0'" . ')">';
													$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_aadhaar_no'] . ' (Aadhaar Card Front Side)</b><br>' . $filename . '</div></div></div>';
												}

												if ($doc['doc_file_back_path3'] != "")
												{
													$Url = "";
//													if (substr_count($doc['doc_file_back_path3'], "attachments") > 0)
//													{
//														$Url .= $doc['doc_file_back_path3'];
//													}
//													else
//													{
//														$Url .= AttachmentProcessing::ImagePath($doc['doc_file_back_path3']);
//													}
                                                                                                        $Url = Document::getDocPathById($doc['doc_id3'], 2);
													$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','1'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','1'" . ')">';
													$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
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
													$Url = "";
//													if (substr_count($doc['doc_file_front_path4'], "attachments") > 0)
//													{
//														$Url .= $doc['doc_file_front_path4'];
//													}
//													else
//													{
//														$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path4']);
//													}
                                                                                                        $Url = Document::getDocPathById($doc['doc_id4'], 1);
													$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','0'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','0'" . ')">';
													$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_pan_no'] . ' (Pan Card Front Side)</b><br>' . $filename . '</div></div></div>';
												}

												if ($doc['doc_file_back_path4'] != "")
												{


													$Url = "";
//													if (substr_count($doc['doc_file_back_path4'], "attachments") > 0)
//													{
//														$Url .= $doc['doc_file_back_path4'];
//													}
//													else
//													{
//														$Url .= AttachmentProcessing::ImagePath($doc['doc_file_back_path4']);
//													}
                                                                                                        $Url = Document::getDocPathById($doc['doc_id4'], 2);
													$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','1'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','1'" . ')">';
													$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
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
													$Url = "";
//													if (substr_count($doc['doc_file_front_path5'], "attachments") > 0)
//													{
//														$Url .= $doc['doc_file_front_path5'];
//													}
//													else
//													{
//														$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path5']);
//													}
                                                                                                        $Url = Document::getDocPathById($doc['doc_id5'], 1);
													$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','0'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','0'" . ')">';
													$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_license_no'] . ' (License Card Front Side)</b><br>' . $filename . '</div></div></div>';
												}

												if ($doc['doc_file_back_path5'] != "")
												{
													$Url = "";
//													if (substr_count($doc['doc_file_back_path5'], "attachments") > 0)
//													{
//														$Url .= $doc['doc_file_back_path5'];
//													}
//													else
//													{
//														$Url .= AttachmentProcessing::ImagePath($doc['doc_file_back_path5']);
//													}
                                                                                                        $Url = Document::getDocPathById($doc['doc_id5'], 2);
													$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','1'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','1'" . ')">';
													$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
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
													$Url = "";
//													if (substr_count($doc['doc_file_front_path6'], "attachments") > 0)
//													{
//														$Url .= $doc['doc_file_front_path6'];
//													}
//													else
//													{
//														$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path6']);
//													}
                                                                                                        $Url = Document::getDocPathById($doc['doc_id6'], 1);
													$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id6'] . "','','6','0'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id6'] . "','','6','0'" . ')">';
													$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b> (Memorandum)</b><br>' . $filename . '</div></div></div>';
												}
											}
										}
										if ($model->doc_type == 7)
										{
											if ($doc['doc_id7'] != "")
											{
												if ($doc['doc_file_front_path7'] != "")
												{
													$Url = "";
//													if (substr_count($doc['doc_file_front_path7'], "attachments") > 0)
//													{
//														$Url .= $doc['doc_file_front_path7'];
//													}
//													else
//													{
//														$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path7']);
//													}
                                                                                                        $Url = Document::getDocPathById($doc['doc_id7'], 1);
													$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id7'] . "','','7','0'" . ')">';
													$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id7'] . "','','7','0'" . ')">';
													$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
													$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b> (Police Verification File)</b><br>' . $filename . '</div></div></div>';
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
												$Url = "";
//												if (substr_count($doc['doc_file_front_path2'], "attachments") > 0)
//												{
//													$Url .= $doc['doc_file_front_path2'];
//												}
//												else
//												{
//													$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path2']);
//												}
                                                                                                $Url = Document::getDocPathById($doc['doc_id2'], 1);
												$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','0'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','0'" . ')">';
												$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_voter_no'] . ' (Voter Card Front Side)</b><br>' . $filename . '</div></div></div>';
											}


											if ($doc['doc_file_back_path2'] != "")
											{
												$Url = "";
//												if (substr_count($doc['doc_file_back_path2'], "attachments") > 0)
//												{
//													$Url .= $doc['doc_file_back_path2'];
//												}
//												else
//												{
//													$Url .= AttachmentProcessing::ImagePath($doc['doc_file_back_path2']);
//												}
                                                                                                $Url = Document::getDocPathById($doc['doc_id2'], 2);
												$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','1'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id2'] . "','" . $doc['ctt_voter_no'] . "','2','1'" . ')">';
												$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_voter_no'] . ' (Voter Card Back Side)</b><br>' . $filename . '</div></div></div>';
											}
										}
										if ($doc['doc_id3'] != "")
										{
											if ($doc['doc_file_front_path3'] != "")
											{
												$Url = "";
//												if (substr_count($doc['doc_file_front_path3'], "attachments") > 0)
//												{
//													$Url .= $doc['doc_file_front_path3'];
//												}
//												else
//												{
//													$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path3']);
//												}
                                                                                                $Url = Document::getDocPathById($doc['doc_id3'], 1);
												$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','0'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','0'" . ')">';
												$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_aadhaar_no'] . ' (Aadhaar Card Front Side)</b><br>' . $filename . '</div></div></div>';
											}
											if ($doc['doc_file_back_path3'] != "")
											{
												$Url = "";
//												if (substr_count($doc['doc_file_back_path3'], "attachments") > 0)
//												{
//													$Url .= $doc['doc_file_back_path3'];
//												}
//												else
//												{
//													$Url .= AttachmentProcessing::ImagePath($doc['doc_file_back_path3']);
//												}
                                                                                                $Url = Document::getDocPathById($doc['doc_id3'], 2);
												$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','1'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id3'] . "','" . $doc['ctt_aadhaar_no'] . "','3','1'" . ')">';
												$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_aadhaar_no'] . ' (Aadhaar Card Back Side)</b><br>' . $filename . '</div></div></div>';
											}
										}
										if ($doc['doc_id4'] != "")
										{
											if ($doc['doc_file_front_path4'] != "")
											{
												$Url = "";
//												if (substr_count($doc['doc_file_front_path4'], "attachments") > 0)
//												{
//													$Url .= $doc['doc_file_front_path4'];
//												}
//												else
//												{
//													$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path4']);
//												}
                                                                                                $Url = Document::getDocPathById($doc['doc_id4'], 1);
												$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','0'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','0'" . ')">';
												$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_pan_no'] . ' (Pan Card Front Side)</b><br>' . $filename . '</div></div></div>';
											}

											if ($doc['doc_file_back_path4'] != "")
											{
												$Url = "";
//												if (substr_count($doc['doc_file_back_path4'], "attachments") > 0)
//												{
//													$Url .= $doc['doc_file_back_path4'];
//												}
//												else
//												{
//													$Url .= AttachmentProcessing::ImagePath($doc['doc_file_back_path4']);
//												}
                                                                                                $Url = Document::getDocPathById($doc['doc_id4'], 2);
												$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','1'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id4'] . "','" . $doc['ctt_pan_no'] . "','4','1'" . ')">';
												$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_pan_no'] . ' (Pan Card Back Side)</b><br>' . $filename . '</div></div></div>';
											}
										}
										if ($doc['doc_id5'] != "")
										{
											if ($doc['doc_file_front_path5'] != "")
											{
												$Url = "";
//												if (substr_count($doc['doc_file_front_path5'], "attachments") > 0)
//												{
//													$Url .= $doc['doc_file_front_path5'];
//												}
//												else
//												{
//													$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path5']);
//												}
                                                                                                $Url = Document::getDocPathById($doc['doc_id5'], 1);
												$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','0'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','0'" . ')">';
												$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_license_no'] . ' (License Card Front Side)</b><br>' . $filename . '</div></div></div>';
											}
											if ($doc['doc_file_back_path5'] != "")
											{
												$Url = "";
//												if (substr_count($doc['doc_file_back_path5'], "attachments") > 0)
//												{
//													$Url .= $doc['doc_file_back_path5'];
//												}
//												else
//												{
//													$Url .= AttachmentProcessing::ImagePath($doc['doc_file_back_path5']);
//												}
                                                                                                $Url = Document::getDocPathById($doc['doc_id5'], 2);
												$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','1'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id5'] . "','" . $doc['ctt_license_no'] . "','5','1'" . ')">';
												$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>' . $doc['ctt_license_no'] . ' (License Card Back Side)</b><br>' . $filename . '</div></div></div>';
											}
										}
										if ($doc['doc_id6'] != "")
										{
											if ($doc['doc_file_front_path6'] != "")
											{
												$Url = "";
//												if (substr_count($doc['doc_file_front_path6'], "attachments") > 0)
//												{
//													$Url .= $doc['doc_file_front_path6'];
//												}
//												else
//												{
//													$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path6']);
//												}
                                                                                                $Url = Document::getDocPathById($doc['doc_id6'], 1);
												$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id6'] . "','','6','0'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id6'] . "','','6','0'" . ')">';
												$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>(Memorandum)</b><br>' . $filename . '</div></div></div>';
											}
										}
										if ($doc['doc_id7'] != "")
										{
											if ($doc['doc_file_front_path7'] != "")
											{
												$Url = "";
//												if (substr_count($doc['doc_file_front_path7'], "attachments") > 0)
//												{
//													$Url .= $doc['doc_file_front_path7'];
//												}
//												else
//												{
//													$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path7']);
//												}
                                                                                                $Url = Document::getDocPathById($doc['doc_id7'], 1);
												$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id7'] . "','','7','0'" . ')">';
												$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . "'$picid','" . $doc['doc_id7'] . "','','7','0'" . ')">';
												$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
												$items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' . $name . '</b><br>Document Name : <b>(Police Verification File)</b><br>' . $filename . '</div></div></div>';
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
	    "data": {"ctt_id": pid,"docid":docid,'doctype':doctype,'sidetype':sidetype},
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