<style>
	#map1 {
		height: 300px;
		width: 100%;
	}
</style>
<div class="row">
	<div class="col-12">
		<div class="row">
			<div class="col-12 ">
				<p class="weight600 mb-1">Add more details for this</p>
			</div>
			<div class="col-12 mt-1">
				<div id="map1"></div>
			</div>
			<div class="col-12 col-xl-6">
				<fieldset class="position-relative">
					<input type="text" class="form-control mt-1" id="txtAddress1" placeholder="COMPLETE THIS ADDRESS (HOUSE NO., LANE NO, ETC.)">
				</fieldset>
			</div>
			<div class="col-12 col-xl-6">
				<fieldset class="position-relative">
					<input type="text" class="form-control mt-1" id="txtAddress2" placeholder="LANDMARK, ADDITIONAL INFO, ETC (OPTIONAL)">
				</fieldset>
			</div>
			<div class="col-12 mt-1">
			<div class="mt-1 mapAddress head weight600"><?= $address; ?></div>
			</div>
			<div class="col-12">
				<button class="btn btn-sm btn-light btnBackToGoogleAddress mt-1 mb-1 mr-2">Back</button>
				<button type="button" class="btn btn-sm btn-primary mt-1 mb-1 confirm text-uppercase">Confirm your address</button>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
	var map;
	var mapAddress = "";
	function setMapValue(val)
	{
		mapAddress = val;
		initStaticMap(val.coordinates.latitude, val.coordinates.longitude);
		$(".mapAddress").html(val.address);
	}
	function initStaticMap(lat, long)
	{
		var latitude = lat; // YOUR LATITUDE VALUE
		var longitude = long; // YOUR LONGITUDE VALUE

		var myLatLng = {lat: latitude, lng: longitude};

		map = new google.maps.Map(document.getElementById('map1'), {
			center: myLatLng,
			zoom: 20
		});

		var marker = new google.maps.Marker({
			position: myLatLng,
			map: map,
			title: latitude + ', ' + longitude
		});
	}
	$('.confirm').unbind("click").click(function()
	{
		//let prevCity = <?= $city ?>;
		let address1 = $("#txtAddress1").val();
		let address2 = $("#txtAddress2").val();
		if (address1 != '')
		{
			mapAddress.address = address1 + ", " + mapAddress.address;
		}
		if (address2 != '')
		{
			mapAddress.address = address2 + ", " + mapAddress.address;
		}

	<?= $callback ?>(mapAddress);
	});

	$(".btnBackToGoogleAddress").unbind("click").on("click", function()
	{

		$("#mapAutoComplete").show();
		$("#mapAddressForm").hide();
	});
   
</script>