<style>
    .ques{
        font-weight: bold;

    }
    .ans{
        padding-bottom: 15px;
    }
	.btn-facebook {
    background: #3264a1;
    color: #fff!important;
    padding: 9px 30px;
    font-size: 16px;
    font-weight: 700;
	display: block;
    text-decoration: none;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    border: #293859 1px solid;
    -webkit-box-shadow: 0px 1px 3px -1px rgba(0,0,0,0.75);
    -moz-box-shadow: 0px 1px 3px -1px rgba(0,0,0,0.75);
    box-shadow: 0px 1px 3px -1px rgba(0,0,0,0.75);
}
.google-btn {
    background: #fff;
    color: #606569!important;
    padding: 9px 30px;
    font-size: 16px;
    font-weight: 700;
	display: block;
    text-decoration: none;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    border: #d9d9d9 1px solid;
    -webkit-box-shadow: 0px 1px 3px -1px rgba(0,0,0,0.75);
    -moz-box-shadow: 0px 1px 3px -1px rgba(0,0,0,0.75);
    box-shadow: 0px 1px 3px -1px rgba(0,0,0,0.75);
}
</style>
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
<?php
$user_id =Yii::app()->user->loadUser()->user_id;
if($user_id!="")
{
?>
	<article>
		<section>
			<div class="right_ul">
				<div class="h2 block-color text-center"><?=$title?></div>
				<div class="col-xs-12 col-sm-12 float-none marginauto text-center h5 blue-color" style="line-height: 30px;">
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
			<div class="right_ul row mt40">
				<div class="col-xs-11 col-sm-6 col-md-4 float-none marginauto text-center h4" style="line-height: 30px;">
				<div class="main_time border-greenline p20 pb40">
				<div class="h5 block-color text-center mb20">Welcome to Gozo Cabs. Yay! It looks like you spotted a Gozo Cab on the road. Please login below to register for your chance to rent a Gozo cab for free for 1 day</div>
					<a href='#' class="btn btn-sm btn-social btn-facebook pl15 pr15 mb20"><span class='' onclick='openFbDialog();'><i class="fa fa-facebook pr5" style="font-size: 22px;"></i> Login with Facebook</span></a>
					<a href='#' class="btn btn-sm btn-social google-btn pl15 pr15"><span class='' onclick='openGoogleDialog();'><img src="../images/google_icon.png" alt="Gozocabs"> Login with google</span></a>
				
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
<h6 align="right">*T&Cs apply for participation in win1day program and for use of Gozo coins.</h6>