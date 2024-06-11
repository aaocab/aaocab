<link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/assets/css/custom.css" rel="stylesheet" type="text/css"/>
<div class="container">
	<form id="tokenhash" method="POST">
		<input type="hidden" name="YII_CSRF_TOKEN">  
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
				<textarea id="jwtoken" class="form-control mb5" name="jwt" rows="6"><?php echo $jwt ?></textarea> 

			</div>
			<div class="col-xs-12 col-sm-10 mt10 text-right">
				<a type="button" class="btn btn-primary" onclick="clearJWtoken()" id="btnJwtClear">Clear All</a> 
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-6 mt10">
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
		?><a class = "btn btn-primary" onclick = "copyJWtokenToClipboard()" id = "btnJwt">Copy JWtoken</a>
	<?
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
<script >
	$(document).ready(function ()
	{
		$('input[name=YII_CSRF_TOKEN]').val("<?php echo $this->renderDynamicDelay('Filter::getToken'); ?>");
	});

	this.copyJWtokenToClipboard = function ()
	{
		if ('<?php echo $jwtoken; ?>' == '') {
			alert('Nothing to copy');
			return;
		}
		var $temp = $("<textarea>");
		var brRegex = /<br\s*[\/]?>/gi;
		$("body").append($temp);
		$temp.val('<?php echo $jwtoken ?>'.replace(brRegex, "\r\n")).select();
		document.execCommand("copy");
		$temp.remove();
		$("#btnJwt").text('Ready to paste');
		$("#btnJwt").addClass("btn-success");
		return;
	};
	function clearJWtoken() {
		$("#jwtoken").val('');
		$("#token").val('');
	}
</script>