
<script>
	function openFbDialog()
    {
        var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook', 'isFlexxi' => true)); ?>';
        var fbWindow = window.open(href, 'Gozocabs', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');
		var timer = setInterval(function() 
		{ 
			if(fbWindow.closed) {
				clearInterval(timer);
				location.reload();
			}
		}, 500);
    }
	function openGoogleDialog()
	{
		var href = '<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google', 'isFlexxi' => true)); ?>';
        var win = window.open(href, 'Gozocabs', 'left=20,top=20,width=500,height=500,toolbar=1,resizable=0');
		var timer = setInterval(function() 
		{ 
			if(win.closed) {
				clearInterval(timer);
				location.reload();
			}
		}, 500);
	}
</script>
<div class="container-fluid mt15 n">
<div class="row">
    <div class="col-12 bg-black mb30 p0 text-center">
        <img src="/images/win_one_day.jpg?v=0.1" alt="We can customize the Gozo business travel program just for you!" title="We can customize the Gozo business travel program just for you!" class="img-fluid">
    </div>
</div>
</div>
<div class="container">
<div class="row">
<div class="col-12">
<?php
$user_id =Yii::app()->user->loadUser()->user_id;
if($user_id!="")
{
?>
	<article>
		<section>
			<div class="lst-1 mt40">
				<div class="heading-inner merriw text-center"><b><?=$title?></b></div>
				<div class="col-12 text-center font-18" style="line-height: 30px;">
					<?=$message;?>
				</div>
				<p></p>
				<hr>
			</div>
		</section>
	</article>
<?php
}
else
{
?>
<article>
		<section>
			<div class="row mt40 justify-center">
				<div class="col-12 col-md-7 text-center font-18">
<div class="card">
<div class="card-body text-center">
				<div class="text-center mb20">Welcome to Gozo Cabs. Yay! It looks like you spotted a Gozo Cab on the road. Please login below to register for your chance to rent a Gozo cab for free for 1 day</div>
					<a href='#' class="btn btn-outline-primary mb-1 ml5 mr5"><span class='' onclick='openFbDialog();'><img src="/images/bxl-facebook-circle2.svg" alt="img" width="14" height="14" class="mr5"> Login with Facebook</span></a>
					<a href='#' class="btn btn-outline-primary ml5 mr5 mb-1"><span class='' onclick='openGoogleDialog();'><img src="/images/bxl-google.svg.svg" alt="img" width="14" height="14" class="mr5"> Login with google</span></a>
				
			</div>
</div>
</div>
			</div>
			<hr>
		</section>
	</article>
<?PHP

}
?>
<br>
<br>
<p class="text-center mb30 font-13 text-m">*T&Cs apply for participation in win1day program and for use of Gozo coins.</p>
</div>
</div>
</div>