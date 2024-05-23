<style>
    .new-booking-list .form-horizontal .form-group{ margin-left: 0; margin-right: 0;}
</style>
<?
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
?>
<div class="panel panel-default">
    <div class="panel-body pt0 new-booking-list">
        <div class="row register_path">
            <div id="VendorInnerDiv">
                <div class="col-xs-12 col-sm-9"> 
                    <h3 class="mb10 pb5 border-bottom weight400 text-uppercase">Vendor Aborted</h3>
                    <div>
                        <ul>
                            <li>
                                Operator Application Id : <?= dechex($model->vnd_id); ?> 
                            </li>
                            <li>
                                Your Operator application is now aborted
                            </li>
                         
                        </ul>
                    </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#phone').mask('9999999999');

        $('#VendorOuterDiv').hide();

    });

    function opentns() {


        $href = '<?= Yii::app()->createUrl('index/termsvendor') ?>';
        jQuery.ajax({type: 'GET', url: $href,
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

    function validateCheckHandlerss() {
        if ($('#email').val() != "") {
            var pattern = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/
            var retVal = pattern.test($('#email').val());
            if (retVal == false)
            {
                $('#errId').html("The email address you have entered is invalid.");
                return false;
            } else
            {
                $('#errId').html("");
                return true;
            }
        }
        return true;

    }



</script>
