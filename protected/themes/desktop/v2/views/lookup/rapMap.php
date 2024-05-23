<html>
	<head>
		<title>Google Map</title>
		<meta name="viewport" content="initial-scale=1.0">
		<meta charset="utf-8">
		<style>		  
		  #map { 
			height: 300px;	
			width: 320px;			
		  }
@media (min-width: 768px) and (max-width: 3600px) {
#map{ height: 300px; width: 507px;}
}	  
		</style>		
	</head>	
	<body>		
		<div>
			<div id="map"></div>
		</div>
		
		<script type="text/javascript">
		var map;
		
		function initMap() {				
			
			var latitude = <?php echo $lat?>; // YOUR LATITUDE VALUE
			var longitude = <?php echo $lng?>;; // YOUR LONGITUDE VALUE
			
			var myLatLng = {lat: latitude, lng: longitude};
			
			map = new google.maps.Map(document.getElementById('map'), {
			  center: myLatLng,
			  zoom: 14					
			});
					
			var marker = new google.maps.Marker({
			  position: myLatLng,
			  map: map,
			  //title: 'Hello World'
			  
			  // setting latitude & longitude as title of the marker
			  // title is shown when you hover over the marker
			  title: latitude + ', ' + longitude 
			});			
		}
		</script>
<!--https://maps.googleapis.com/maps/api/js?key=AIzaSyCJ0Ka_NiM9qdL5rlZGKCwB8CTVDY71NSY&callback=initMap-->
		<script src=""
		async defer></script>
	</body>	
</html>