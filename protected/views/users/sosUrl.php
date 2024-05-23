 <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
 <meta http-equiv="refresh" content="10">
<body >
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">


<script>
    var timer = null;
    function auto_reload()
    {
		var sos = "<?=$_REQUEST['v']?>";
		jQuery.ajax({
		"type": "GET",
		"dataType": "json",
		"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('users/sosUrl')) ?>",
		data: {"v": sos},
		success: function (data)
		{
		   window.location = Yii::app()->params['fullBaseURL'].'e?v='+data;
		}
	});
    }

</script> 


<h1  style="background: #152b57; color: #fff; margin:0;"><center><b>Booking ID <?= $arr['bkgId'] ?>.Last tracked location at <?= $arr['dateTime'] ?></b></center></h1>
 
				<iframe width="100%" height="100%" frameborder="0" scrolling="no"marginheight="0"
						marginwidth="0" src="https://maps.google.com/maps?q=<?= $arr['lat'] . ',' . $arr['lon']; ?>&hl=es;z=14&amp;output=embed">
				</iframe>
			

           
</body>
	

