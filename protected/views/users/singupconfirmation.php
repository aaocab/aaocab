<style>
    .congo{
        background: #43b1e5;
        float: none;
        margin: auto;
        color: #FFF;
        font-size: 1.1em;
    }
</style>
<?
if ($refid = $_SESSION['ambid']) {
    unset($_SESSION['ambid']);
} else {
    $refid = '';
}
?>
<div class="searchpanel">
    <div class="container">
        <div style="text-align:center; margin:20px 0;">
            <div><strong style="font-size: 18px;">Congratulations.</strong><br/>
                Your Impind account has been created. <br>You may now check your registered email account and click  on the link provided in our email, to activate your account.</br>

                <?
                if ($refid != '') {
                    ?>
                    <div class="col-xs-10 col-md-5 congo pt10 pb10 mt15 mb15">
                        Your Ambassador referral ID is <span style="font-size: 1.4em;font-weight: bold" >"<?= $refid ?>"</div>

                    <span>           
                        Contact us at <a href="mailto:support@impind.com">support@impind.com</a> and we will be glad to send you cards and flyers you can use to  introduce impind to your friends.<br>
                    </span>
                <? } ?>


                Please feel free to contact us at <a href="mailto:hello@impind.com">hello@impind.com</a>, should you face any difficulty.</div>

        </div>
    </div>
</div>