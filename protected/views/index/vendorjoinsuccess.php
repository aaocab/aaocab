<?
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
$msg = trim($_GET['msg']);
?>
<div class="container">
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="row">
        <?php if ($msg != null) { ?>
        <div class="col-xs-12">
          <h4 style="color: #de6a1e;">
            <?php
            if($msg=='success'){
               echo 'Thanks for your interest in joining Gozo. We have sent you an email with instructions for the next step.'; 
            }else if($msg='error'){
               echo 'Error occured'; 
            }?>
          </h4>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#phone').mask('9999999999');
    });

  
</script>
