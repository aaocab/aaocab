                       
<?php
	$pData	 = [];
	$dData	 = [];
	if(empty($model))
	{
		return false;
	}
	foreach ($model as $doc)
	{
		$picid		 = $doc['cmg_ctt_id'];
		$docType	 = intval($doc["doc_type"]);
		$labelFront	 = $labelBack	 = $refValue	 = '';
		$lastId = 0;
		$pCount = 0;
		switch ($docType)
		{
			case Document::Document_Voter:
				$headerValue = "Voter Data";
				break;

			case Document::Document_Aadhar:
				$headerValue = "Aadhaar Data";
				break;

			case Document::Document_Licence:
				$headerValue = "License Data";
				break;

			case Document::Document_Pan:
				$headerValue = "Pan Data";
				break;

			default:
				break;
		}

		if (($doc["ctt_id"] == $doc["ctt_ref_code"]) && !$pCount)
		{
			array_push($pData, $doc);
			$pCount++;
		}
		else
		{
			array_push($dData, $doc);
		}
	}
?>	

	<div class="row" >
        <div class="panel">
            <div class="panel-body">
                <div class="docgrid">
					<h2><?= $headerValue ?></h2>
					<p>This sections shows the <?= $headerValue ?>. Please approve the details to keep</p>  
					<hr/>
                    <div class="col-xs-12">
						<div class="row">
							<div class="col-md-6">
								<h4 style="text-align: center;">Primary Doc Data</h4>
								<?= $this->renderPartial("docPData", ['model' => $pData]); ?>
							</div>
							<div class="col-md-6">
								<h4 style="text-align: center;">Duplicate Doc Data</h4>
								<?= $this->renderPartial("docPData", ['model' => $dData]); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



<script>
    function sendData(docid, docType, primary, duplicate, status) 
	{
        
        $.ajax(
		{
            "type": "POST",
            "dataType": "json",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/contact/UpdateContact')) ?>",
            "data": 
			{
				"docid": docid, 
				"docType": docType, 
				"primaryCttId": primary, 
				"duplicateCttId": duplicate, 
				"status": status,
				"YII_CSRF_TOKEN": "<?= Yii::app()->request->csrfToken ?>"
			},
            "success": function (response)
            {
				//debugger;
				console.log(response);
				//let res = JSON.parse(response);
                if(response.success)
				{
					alert(response.message);
					window.location.reload();
				}
				else
				{
					alert(response.message);
				}
            }
        });
    }
</script>
