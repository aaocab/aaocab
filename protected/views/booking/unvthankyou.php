<style>
    .rating-cancel {
        display: none !important;
        visibility: hidden !important;
    }
    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;
    }
    .padded {
        padding-bottom: 5px;
        padding-top: 5px;
    }
    .fset {
        padding: 5px;
        margin:5px;
        border:1px solid #ddd;
    }
    .lgend {
        border-bottom: 0;
        font-size: 1em;
        width: 78px;
        padding-left: 2px
    }
    .review {
        margin-top: 20px;
        color: #f00;
        font-size: 13px;
        display: none;
        text-align: center;
    }
</style>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<?php
/* @var $model LeadFollowup */
if($model->lfu_type==1)
{
    $message = 'Thanks for telling us that our price was higher. At Gozo we want to give you good prices with great qualiity. Sometimes you can get a better price than us and we will investigate and see how our pricing can be lowered without impacting quality. Our goal is to give you great service at good prices. Our attention to great service at fair prices makes us the #1 choice for lacs of customers across India.';
}
else if($model->lfu_type==2)
{
    $message = 'We are looking forward to serve you on your trip.';
    if($model->lfu_bkg_tentative_booking==1)
    {
        $message .= ' We will create your tentative booking & be in touch shortly.';
    }
}
else if($model->lfu_type==3)
{
    $message = 'Thanks for telling us how we can do better. We will be in touch.';
}
else if($model->lfu_type==4)
{
	$message = 'We have recorded your input. Someone will be calling you soon.';
}
?>
<section style="color:#555555">
    <div class="container">
        <div class="row">
			<div class="col-lg-12"><h3 class="text-uppercase text-center m0 mb0 weight400 mt10 text-success">Thank You</h3></div>
            <div class="col-lg-12 float-none marginauto text-center p5">
                <div class="panel" style="">
                    <div class="panel-body ">
                        <div class="panel-scroll1">
                            <div style="text-align: center;"><b><?=$message;?></b></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>