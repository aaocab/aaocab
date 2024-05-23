



<div class="footer">
    <div style="margin: auto; font-size: 14px; margin-top: 15px;">
        <a class="fmodal" href="<?= Yii::app()->createUrl('index/about') ?>">About</a> - 
        <a class="fmodal" href="<?= Yii::app()->createUrl('index/privacy') ?>">Privacy</a> - 
        <a class="fmodal" href="<?= Yii::app()->createUrl('index/tns') ?>">Terms</a> - 
        <a class="fmodal" href="<?= Yii::app()->createUrl('index/contact') ?>">Contact</a>

    </div>
</div>
<script>
    $(document).ready(function () {

<?php
$imglogo = Yii::app()->baseUrl . 'assets/img/logo.png';
?>
        $("a.fmodal").click(
                function (e)
                {
                    jQuery.ajax({
                        type: 'GET',
                        url: $(this).attr('href'),
                        success: function (data) {
                            bootbox.dialog({
                                message: data,
                                title:
                                        '<img src="<?php echo $imglogo ?>" alt="logo" width="80" style="margin:-5px 0;padding:0">',
                                size: 'large'
                            });
                        }
                    });
                    e.preventDefault();
                });


    })
</script>
