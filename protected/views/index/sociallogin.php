<style>
    .form-group {
        margin-bottom: 7px;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

</style>
<? $imgVer	 = Yii::app()->params['imageVersion']; ?>
<div class="row mt20 flex">
    <div class="col-xs-8 col-sm-8 col-md-8 book-panel2 padding_zero">
        <div class="panel panel-primary">
            <div class="panel-body">
             
               
                <div class="row" style="text-align: center;">
                                    <div class="col-xs-12 col-md-6 col-md-offset-3 fbook-btn mb20">
                                        <a class="btn btn-lg btn-social btn-facebook pl15 pr15" target="_blank" href="<?= Yii::app()->createUrl('index/oauth', array('provider' => 'Facebook')); ?>"><i class="fa fa-facebook pr5" style="font-size: 22px;"></i> Connect with Facebook</a>
                                    </div>
                                    <div class="col-xs-12 col-md-6 col-md-offset-3 google-btn">
                                        <a class="btn btn-lg btn-social btn-googleplus pl15 pr15" target="_blank"  href="<?= Yii::app()->createUrl('index/oauth', array('provider' => 'Google')); ?>"><img src="../images/google_icon.png" alt="Gozocabs"> Connect with Google</a>
                                    </div>
                                    <a class="btn btn-lg btn-social btn-linkedin pl15 pr15 hide" target="_blank" href="<?= Yii::app()->createUrl('index/oauth', array('provider' => 'LinkedIn')); ?>"><i class="fa fa-linkedin"></i></a>
                                    
            </div>
              
  

            </div>
           
        </div>
    </div>
   

</div>

<script type="text/javascript">
    $(document).ready(function () {
    });
    function validateCheckHandlerss() {
        if ($("#formId").validation({errorClass: 'validationErr'})) {
            return true;
        } else {
            return false;
        }
    }
</script>
