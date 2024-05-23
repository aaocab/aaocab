<style>
    .social-btn {
        color: #fff!important;
        padding: 9px 30px;
        font-size: 16px;
        text-decoration: none;
        display: block;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
    .social-btn:hover{ text-decoration: none; background: #000;}
    .bg-facebook {
        background: #3264a1;
    }
    .bg-google {
        background: #e13f2a;
    }
</style>
<div class="row title-widget">
    <div class="col-xs-12">
        <p class="form-title text-center mt0"><?php echo $this->pageTitle; ?></p>
    </div>
</div>
<div class="row bg-gray pb30">
    <div class="col-xs-12">
        <div class="bg-white-box p0">
            <div class="row">
                <div class="col-xs-12">

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel">
                                <div class="row text-center pt40 pb40 m0">
                                    <!--<div class="col-xs-12 col-lg-10 col-lg-offset-1 mb20">
                                        <a class="social-btn bg-facebook" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Facebook')); ?>"><i class="fa fa-facebook-f pr5" style="font-size: 22px;"></i> Connect with Facebook</a>
                                    </div>-->
                                    <div class="col-xs-12 col-lg-10 col-lg-offset-1">
                                        <a class="social-btn bg-google" target="_blank"  href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'Google')); ?>"><i class="fa fa-google mr10" aria-hidden="true"></i> Connect with Google</a>
                                    </div>
                                    <a class="btn btn-lg btn-social btn-linkedin pl15 pr15 hide" target="_blank" href="<?= Yii::app()->createUrl('users/oauth', array('provider' => 'LinkedIn')); ?>"><i class="fa fa-linkedin"></i></a>

                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="row">
                                    <div class="col-xs-6 pl0">
                                        <div class="forget-password-block">
                                            <a href="/vendor/join" class="forget-password">Become a Gozo Partner</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

