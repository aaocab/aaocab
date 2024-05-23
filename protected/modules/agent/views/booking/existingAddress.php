<?php

$data	 = [];
$userId	 = UserInfo::getUserId();
if ($city && $userId > 0)
{
	$data = BookingRoute::getUserAddressesByCity($city, $userId);
}
?>

<div class=''>


<div class='ex-address' id="">
<div class="font-18 weight500 mb5">Your previous addresses</div>
		<div class="list-group" style="overflow: auto; height: 200px">
			<?php
			foreach ($data as $key => $val)
			{
				$jsonVal = CJSON::decode($val);
				$count	 = count(explode(",", $val["text"]));
				if ($count < 3)
				{
					unset($data[$key]);
					continue;
				}
				echo "<button type='button' class='list-group-item list-group-item-action' data-val='{$val["id"]}'>{$val["text"]}</button>";
			}
			?>
		</div>

		<?php echo CHtml::link("<img src=\"/images/bx-plus-circle.svg\" alt=\"img\" width=\"16\" height=\"16\"> Add new address", "javascript:void(0)", ['class' => 'btn btn-1 btn-sm pl-0 PAWToggleLink PAWAddLink', "data-val" => "1"]); ?>
	</div>
	<div class="googleMapAddress" style="display: none">
		<?php
		$this->renderPartial("/booking/bkMapLocation", ["city" => $city, "callback" => $callback,
 "isAirport" => $isAirport,"widgetTextValJson"=>$widgetTextValJson]);
		?>
	</div></div>

<script type="text/javascript">
	$(document).ready(function()
	{
		
<?php
if (count($data) == 0)
{
	?>displayAddressType("1", true);
	<?php
}
?>
	});

	$(".PAWToggleLink").unbind("click").on("click", function()
	{
		let val = $(this).data("val");
		displayAddressType(val);
	});

	$(".ex-address .list-group-item-action").unbind("click").on("click", function()
	{	
		let val = $(this).data("val");
		
		
		//alert(<? //$callback ?>);
	<?= $callback ?>(val);
	});

	function displayAddressType(type, hideBack = false)
	{
	
		if (hideBack)
		{
			$(".googleMapAddress .btnBackToAddress").hide();
		}

		if (type == "1")
		{
			$(".ex-address").hide();
			$(".googleMapAddress").show();
	
		}
		else
		{
			$(".googleMapAddress").hide();
			$(".ex-address").show();
	}
	}
	
	$(".btnBackToAddress").unbind("click").on("click", function(){
        let val = $(this).data("val");
		displayAddressType(val);
});
</script>

