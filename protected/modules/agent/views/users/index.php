<?
//Yii::app()->clientScript->registerPackage('jquery');
//Yii::app()->clientScript->registerPackage('jqueryui');
//Yii::app()->clientScript->registerPackage('style');
?>
<?php
?>
<script>
    $(document).ready(function () {
        $.ajax({type: 'POST', url: '/api/agent/users/profileupdate',
            success: function (data) {
                alert(data);
            }
        });
    });
</script>