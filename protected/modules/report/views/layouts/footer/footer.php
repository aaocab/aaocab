

<div class="footer">
    <div class="container innfooter">
        <p class="subnavi">
            <a class="fmodal" href="<?= Yii::app()->createUrl('index/about') ?>">About</a> - 
            <a class="fmodal" href="<?= Yii::app()->createUrl('index/privacy') ?>">Privacy</a> - 
            <a class="fmodal" href="<?= Yii::app()->createUrl('index/tns') ?>">Terms</a> - 
            <a class="fmodal" href="<?= Yii::app()->createUrl('index/contact') ?>">Contact</a>
        </p>
    </div>
</div>
<script>
    $(document).ready(function () {

<?php
$imglogo = ASSETS_URL . '/img/logo.png';
?>
        $("a.fmodal").click(
                function (e)
                {
                    jQuery.ajax({
                        type: 'GET',
                        url: $(this).attr('href'),
                        success: function (data) {
                            bootbox.dialog({
                                style: 'z-index:9999',
                                message: data,
                                title: '<img src="<?php echo $imglogo ?>" alt="logo" width="80" style="margin:-5px 0;padding:0">',
                                size: 'large'
                            });
                        }
                    });
                    e.preventDefault();
                });


    })
</script>
