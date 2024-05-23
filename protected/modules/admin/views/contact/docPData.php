<?php
	
	$pdfImage	 = "/images/pdf.jpg";
	$noImage	 = "/images/no-image.png";
	foreach ($model as $doc)
	{
		$items		 = '';
		$fileImage = "";
		$filePdf = "";
		$docType = intval($doc["doc_type"]);
		$labelFront	 = $labelBack	 = $refValue	 = "";
		
		switch ($docType)
		{
			case Document::Document_Voter:
				$refValue	 = $doc['ctt_voter_no'];
				$labelFront	 = "(Voter Card Front Side)";
				$labelBack	 = "(Voter Card Back Side)";
				break;

			case Document::Document_Aadhar:
				$refValue	 = $doc['ctt_aadhaar_no'];
				$labelFront	 = "(Aadhar Card Front Side)";
				$labelBack	 = "(Aadhar Card Back Side)";
				break;

			case Document::Document_Licence:
				$refValue	 = $doc['ctt_license_no'];
				$labelFront	 = "(License Card Front Side)";
				$labelBack	 = "(License Card Back Side)";
				break;

			case Document::Document_Pan:
				$refValue	 = $doc['ctt_pan_no'];
				$labelFront	 = "(Pan Card Front Side)";
				$labelBack	 = "(Pan Card Back Side)";
				break;

			default:
				break;
		}



		if ($doc['doc_file_front_path'] != "")
		{
			$Url = "";
			if (substr_count($doc['doc_file_front_path'], "attachments") > 0)
			{
				$Url .= $doc['doc_file_front_path'];
			}
			else
			{
				$Url .= AttachmentProcessing::ImagePath($doc['doc_file_front_path']);
			}
			$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10">';
			$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10">';
			$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
			$items		 .= '<div>Document Name : <b>' . $refValue . $labelFront .'</b><br>'. $filename. '</div>';
		}
		if ($doc['doc_file_back_path'] != "")
		{
			$Url = "";
			if (substr_count($doc['doc_file_back_path'], "attachments") > 0)
			{
				$Url .= $doc['doc_file_back_path'];
			}
			else
			{
				$Url .= AttachmentProcessing::ImagePath($doc['doc_file_back_path']);
			}
			$fileImage	 = '<img src="' . $Url . '" class="pic-bordered pic btn p0 pt10" >';
			$filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10">';
			$filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
			$items		 .= '<div>Document Name : <b>' . $refValue . $labelBack .'</b><br>'. $filename. '</div>';
		}
	
?>
		<div class="row">
			<div class="col-xs-12 col-md-7 pl0 text-center">
				<?= $items ?>
				<div class="row">
					<a class="btn btn-primary" id="rtleft" val="<?= $doc['doc_id'] ?>" onclick="sendData(<?= $doc['doc_id'] ?>,<?= $docType ?>,<?= $doc['ctt_ref_code'] ?>,<?= $doc['ctt_id'] ?>, '1')">Approve <i class="fa fa-check"></i></a>
					<a class="btn btn-primary" id="rtright" val="" onclick="sendData(<?= $doc['doc_id'] ?>,<?= $docType ?>,<?= $doc['ctt_ref_code'] ?>,<?= $doc['ctt_id'] ?>, '0')" >Reject <i class="fa fa-times "></i></a>
				</div>
			</div>
		</div>
<?php 
	}
?>
