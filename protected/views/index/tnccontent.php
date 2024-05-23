<?= html_entity_decode(stripcslashes($model->tnc_text)); ?> 
<script type="text/javascript">
    function showTcGozoCoins() {
        var href1 = '<?= Yii::app()->createUrl('index/tnsgozocoins') ?>';
        jQuery.ajax({type: 'GET', url: href1,
            success: function (data) {
                box = bootbox.dialog({
                    message: data,
                    title: '',
                    size: 'large',
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }
</script>