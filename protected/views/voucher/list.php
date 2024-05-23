<style>
    .voucher-panel{
        margin: 15px 0; padding: 35px 25px 15px 25px; border-radius: 5px; height: 100%; color: #fff; border: #151515 1px dashed;
        -webkit-box-shadow: 0px 4px 8px 1px rgba(0,0,0,0.27);
-moz-box-shadow: 0px 4px 8px 1px rgba(0,0,0,0.27);
box-shadow: 0px 4px 8px 1px rgba(0,0,0,0.27);
        background: rgba(71,71,71,1);
        background: -moz-linear-gradient(-45deg, rgba(71,71,71,1) 0%, rgba(71,71,71,1) 1%, rgba(21,21,21,1) 67%, rgba(242,93,1,1) 67%, rgba(240,8,8,1) 99%, rgba(240,8,8,1) 100%);
        background: -webkit-gradient(left top, right bottom, color-stop(0%, rgba(71,71,71,1)), color-stop(1%, rgba(71,71,71,1)), color-stop(67%, rgba(21,21,21,1)), color-stop(67%, rgba(242,93,1,1)), color-stop(99%, rgba(240,8,8,1)), color-stop(100%, rgba(240,8,8,1)));
        background: -webkit-linear-gradient(-45deg, rgba(71,71,71,1) 0%, rgba(71,71,71,1) 1%, rgba(21,21,21,1) 67%, rgba(242,93,1,1) 67%, rgba(240,8,8,1) 99%, rgba(240,8,8,1) 100%);
        background: -o-linear-gradient(-45deg, rgba(71,71,71,1) 0%, rgba(71,71,71,1) 1%, rgba(21,21,21,1) 67%, rgba(242,93,1,1) 67%, rgba(240,8,8,1) 99%, rgba(240,8,8,1) 100%);
        background: -ms-linear-gradient(-45deg, rgba(71,71,71,1) 0%, rgba(71,71,71,1) 1%, rgba(21,21,21,1) 67%, rgba(242,93,1,1) 67%, rgba(240,8,8,1) 99%, rgba(240,8,8,1) 100%);
        background: linear-gradient(135deg, rgba(71,71,71,1) 0%, rgba(71,71,71,1) 1%, rgba(21,21,21,1) 67%, rgba(242,93,1,1) 67%, rgba(240,8,8,1) 99%, rgba(240,8,8,1) 100%);
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#474747', endColorstr='#f00808', GradientType=1 );
    }
    .btn-buy{ margin: 20px 0 0 0;}
    .btn-buy a{ background: #121311; padding: 4px 12px; text-transform: uppercase; border-radius: 4px; color: #fff; font-weight: 500; font-size: 16px;}
    .btn-buy a:hover{ background: #0c4ba9; text-decoration: none;}
</style>
<section>
    <div class="row m0 mb20 flex">
        <div class="col-xs-12 h3 m0 text-uppercase text-center">Buy Voucher</div>
    </div>
    <div class="row">
        <?php
        foreach ($data as $d) {
            $hashVoucherId = Yii::app()->shortHash->hash($d['vch_id']);
            ?>
            <div class="col-xs-12 col-sm-4">
                <div class="voucher-panel">
                    <div class="row">
                        <div class="col-lg-8">
                            <?= $d['vch_code'] . " ( " . $d['vch_title'] . " )"; ?>
                        </div>
                        <div class="col-lg-4 text-right btn-buy">
                            <a href="<?= Yii::app()->createUrl('voucher/detail', ['voucherId' => $hashVoucherId]) ?>" id="Buy Voucher" title="Buy Voucher">Buy</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
        ?>
    </div>
</section>
