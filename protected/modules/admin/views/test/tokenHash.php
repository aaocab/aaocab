<link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/assets/css/custom.css" rel="stylesheet" type="text/css"/>
<div class="container">
	<form id="tokenhash">
		<div class="row">
			<div class="col-xs-12 col-sm-6 mt10">
				<label for="token">Enter app token</label>
				<br>
				<textarea id="token" name="token" class="form-control" rows="1" cols="50"><?php echo $token ?></textarea>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-10 mt10">
				<label for="jwt">Enter jwt token</label>
				<br>
				<textarea id="jwtoken" class="form-control " name="jwt" rows="6"><?php echo $jwt ?></textarea> 
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6 mt10">
				<input class="btn btn-primary" type="submit">
			</div>
		</div>
	</form> 
	<?php
	if ($error != '')
	{
		echo "<div class='col-xs-12 alert-danger p10'><b>$error</b></div><br><br>";
	}
	if ($jwtoken)
	{
		echo "<br>JWT Token for <b>$token</b> :<br>
<div class=' mt10 mb20 form-control col-xs-12 col-sm-10 alert-success' style=' word-wrap: break-word ;height:auto!important;padding:5px'>$jwtoken </div>";
	}
	if ($tokenDecoded)
	{
		echo "<br>JWT decoded details for <br> <span style='width: 90%; word-wrap: break-word ; font-size:0.8em;line-height:1em '><br>$jwt</span>";
		echo "<div class='row mt10 mb20'> <div class='col-xs-12'><pre class='bg-success'>";
		print_r($tokenDecoded);
		echo "</pre></div></div>";
	}
	?> 
</div>
