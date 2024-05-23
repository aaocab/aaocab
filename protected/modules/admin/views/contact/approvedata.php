<style type="text/css">
    .pic
	{
        max-width: 100%;
        max-height: 175px;
    }
</style>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
$docType = Document::model()->documentType();
unset($docType[1]);
unset($docType[6]);
?>

<?php
$pData	 = [];
$dData	 = [];
if (!empty($dataProvider))
{
	$lastId = 0;
	$pCount = 0;
	foreach ($dataProvider->getData() as $conData)
	{
		$cttId = intval($conData["ctt_id"]);
		if($lastId == $cttId)
		{
			continue;
		}

		if (($conData["ctt_id"] == $conData["ctt_ref_code"]) && !$pCount)
		{
			array_push($pData, $conData);
			$pCount++;
		}
		else
		{
			array_push($dData, $conData);
		}

		$lastId = $cttId;
	}
}
?>		

<div id="list-content">
    <div class="row" >
        <div class="panel">
            <div class="panel-body">
                <div class="docgrid">
                    <div class="col-xs-12">
						<div class="row">
							<div class="col-md-4">
								<?= $this->renderPartial("primaryData", ['model' => $pData]); ?>
							</div>
							<div class="col-md-8">
								<div class="row">
									<?= $this->renderPartial("duplicateData", ['model' => $dData]); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	$aDoc = $pDoc = $vDoc = $lDoc = [];
	if ($dataProvider->getData())
	{
		foreach ($dataProvider->getData() as $doc)
		{
			$docType = intval($doc["doc_type"]);
			switch ($docType)
			{
				case Document::Document_Voter:
					array_push($vDoc, $doc);
					break;

				case Document::Document_Aadhar:
					array_push($aDoc, $doc);
					break;

				case Document::Document_Licence:
					array_push($lDoc, $doc);
					break;

				case Document::Document_Pan:
					array_push($pDoc, $doc);
					break;

				default:
					break;
			}
		}
		//echo "<pre>";print_r($lDoc);exit;
		$this->renderPartial("docData", ['model' => $lDoc]);
		$this->renderPartial("docData", ['model' => $vDoc]);
		$this->renderPartial("docData", ['model' => $aDoc]);
		$this->renderPartial("docData", ['model' => $pDoc]);
	}
	?>

</div>
