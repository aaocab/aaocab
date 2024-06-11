<script src="https://apis.google.com/js/platform.js" async defer></script>
<!-- test url -->
<?php
//fb share
$fbshareUrl = //mail to outlook
        $mailBody = 'Dear Friend,%0D%0DI wanted to introduce you to aaocab.com. I used it recently for my long distance taxi travel. You may find them useful to address your long distance travel needs and quality service.%0D
aaocab is India’s leader in long distance taxi travel. Please visit  ' . 'http://www.aaocab.com/invite/' . $refCode . '  to register and get a credit of ' . $amount . ' points towards your future travel needs.%0D
%0D%0D%0D
Regards,%0D
aaocab Team';
?>

<div class="row">
    <div class="col-12 mb30">
        <div class="bg-white-box">
            <div class="row mt30">
                <div class="col-6 text-right mb20">       
                    <a href="<?= Yii::app()->createAbsoluteUrl('users/FbShareTemplate', ['refcode' => $refCode]); ?>" target="_blank" class="social-1 hvr-push" style="color: #fff" rel="nofollow">
                        <button class="btn btn-lg" type="button" style="color: #fff;background: #3b5a9b">
                            <i class="fab fa-facebook-f mr15"></i>SHARE
                        </button>
                    </a>
                </div>
                <div class="col-6">  
                    <a href='MAILTO:?subject= Gozo Referral&body= <?= $mailBody ?>'> <button class="btn bg-orange btn-lg mb20" type="button" style="color: #fff"><i class="fa fa-envelope mr10"></i>SEND MAIL</button></a>        
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 text-center">  
        <!--            <a href="https://plus.google.com/share?url=<?= Yii::app()->createAbsoluteUrl('users/gsharetemplate', ['refCode' => $refCode, 'amount' => $amount]) ?>" onclick="javascript:window.open(this.href,
                                    '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                            return false;"><button class="btn btn-lg social-3" type="button" style="color: #fff" ><i class="fa fa-google-plus"></i> SHARE</button></a>
                    --> </div>
                <div class="col-12 text-center mt10 mb30"><span class="bg-or">OR</span></div>
                <div class="col-sm-12 text-center mb20">   
                    <b>Invite  link</b><br>
                    <span><?= 'http://www.aaocab.com/invite/' . $refCode; ?></span>
                </div>
                <div class="col-12 text-center mt20 mb30"><span class="bg-or">OR</span></div>
                <div class="col-sm-12 text-center">
                    <b>Send Your Referral code</b><br>
                    <span class="bg-refer"><?= $refCode ?></span>
                </div>       
            </div>
            <div class="row">
                <div class="col-12 mb20 mt30 text-center">
                    <img src="<?= Yii::app()->createAbsoluteUrl('images/refer_friend.jpg') ?>?v1.2" class="p0">
                </div>
            </div>
        </div>

    </div>
</div>