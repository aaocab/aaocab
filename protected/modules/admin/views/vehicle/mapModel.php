
<?php
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array
	(
		'id' => 'mapmodel-form', 'enableClientValidation' => true,
		'clientOptions'			 => array
		(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation'	 => true,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array
		(
			'class' => '',
		),
	));
	/* @var $form TbActiveForm */

	$headerName = $dataToRender["data"]["headerType"];
	$headerRowData = $dataToRender["data"]["headerRow"];
	$rowLevelData = $dataToRender["data"]["rowData"];

	$createButtons = $dataToRender["data"]["createButtons"];

//	echo print_r($rowLevelData, true);
//	exit;
?>
<input type="hidden" id="dataMapping" name="dataToMap"/>
<div class="row">
	<div class="col-xs-12">
		<button class="btn btn-info" type="submit"  name="Update Mapping" style="width: 185px;" onclick="updateMapping()">Update Mapping</button>
<?php
	if(!empty($createButtons))
	{
		foreach($createButtons as $button)
		{
?>			<a class="<?php echo $button -> class ?>" target="<?php echo $button -> target ?>" href="<?= Yii::app()->createUrl($button -> url) ?>" style="<?php echo $button -> style ?>"><?php echo $button -> name ?> </a>
<?php
		}
	}
?>
	</div>
</div>
<div class="row">
    <div class="col-xs-12">
		<br/>
		<table class="table table-bordered table-container">
			<tr>
               <td><?php echo $headerName; ?></td>
<?php
		
		$headerIndex = 0; 
		foreach ($headerRowData as $header)
		{
?>		       <td><?php echo $header["headerLabel"]; ?></td>
<?php		
		}	
		$headerIndex++;
?>
	        </tr>
<?php
		foreach ($rowLevelData as $rowData)
		{
?>			<tr>
				<td><?php echo $rowData["keyDesc"] ?></td>
<?php
				$subCatData = $rowData["subCat"];
				
				foreach ($subCatData as $subData)
				{
					$mapKeyId = $subData["mapKeyId"];
					$keyId = $subData["keyId"];
					$relationKeyId = $subData["relationKeyId"];
					$isMap = $subData["isMap"];
					$isActive = $subData["isActive"];

					$elementId = "checked_" . $mapKeyId . "_" . $keyId . "_" . $relationKeyId;
					$isChecked = "";
					if($mapKeyId && $isActive)
					{
						$isChecked = "checked";
					}
?>					<td> <input id="<?php echo $elementId ?>" type="checkbox"  <?php echo $isChecked ;?>/> </td>
					
<?php			}
?>
			</tr>		
<?php	}
?>

		</table>
	</div>
</div>
		<?php $this->endWidget();?>
<script>
	function updateMapping()
	{
		//e.preventDefault();
		
		let newMapping = [];
		let updateMapping = [];
		$("input[id^='checked_']").each(function( index ) 
		{
			let checkbox = this.id.split("_");
			if(this.checked && checkbox[1] == 0)
			{
				let temp = {};
				
				temp.mapKeyId = 0;
				temp.keyId = checkbox[2];
				temp.relationKeyId = checkbox[3];
				
				newMapping.push(temp);
			}
			
			if(checkbox[1] > 0)
			{
				let temp = {};
				
				temp.mapKeyId = checkbox[1];
				temp.keyId = checkbox[2];
				temp.relationKeyId = checkbox[3];
				
				if(this.checked)
				{
					temp.isActive = 1;
				}
				else
				{
					temp.isActive = 0;
				}
				
				updateMapping.push(temp);
			}
		});
		
		let url = window.location.href.split("?")[0];
		
		let data = [];
		
		let finalTemp = {};
		
		finalTemp.newMapping = newMapping;
		finalTemp.updateMapping = updateMapping;
		finalTemp.type = window.location.href.split("?")[1].split("=")[1];
		
		//data.push(finalTemp);
		
		//alert(JSON.stringify(finalTemp));
		//return false;
		
		$("#dataMapping").val(JSON.stringify(finalTemp));
		
		$.ajax
		({
            "type": "POST",
            "dataType": "json",
            "url": url,
            "data": $("#mapmodel-form").serialize(),
            "success": function (response)
            {
                if (response.success)
                {
                }
            },
            "error": function (error) 
			{
                alert(error);
            }
        });
	}
</script>