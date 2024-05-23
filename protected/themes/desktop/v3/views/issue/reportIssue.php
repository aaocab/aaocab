<style>
.list-unstyled li a{ display: block; text-align: left;}
</style>
<div class="contain-issue">
<div class="alert alert-danger mb-2 text-center alertcabclass hide" role="alert"></div>
<?php 
    $accordianNo = 23;
    $reportIssueType = $model->reportIssueType;
    
    $form					 = $this->beginWidget('CActiveForm', array(
	'id'					 => 'bookingreportissue', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => ''
	),
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'		 => '', 'enctype'	 => 'multipart/form-data'
	),
		));
    /* @var $form CActiveForm */
    
    $reportIssueType  = ReportIssue::getType();
    $reportIssueArray  = json_decode($reportIssueType, true);
    
    foreach ($reportIssueArray as $issuekey => $reportIssue)
    {
       // echo $issuekey.''.$reportIssue;
?>
<div class="card collapse-header reportissue<?= $issuekey ?>" style="margin-bottom: 8px;" onclick="reportAissue('<?= $issuekey ?>','<?= $reportIssue ?>','<?= $accordianNo ?>');">
    <div id="heading<?= $accordianNo ?>" class="btn btn-light-secondary text-left font-14 border-none p10" data-toggle="collapse" role="button" data-target="#accordion<?= $accordianNo ?>" aria-expanded="false" aria-controls="accordion<?= $accordianNo ?>">
        <span class="collapse-title">
            <span class="align-middle weight500"><?php echo $reportIssue ?></span>
        </span>
    </div>
    <div id="accordion<?= $accordianNo ?>" role="tabpanel" aria-labelledby="heading<?= $accordianNo ?>" class="collapse" aria-expanded="false">
       
        <?php 
         $rpiDetails =  ReportIssue::getDetails($issuekey);
        ?>
        <div class="card-body p0 font-12">
            <ul class="list-unstyled mb-0">
            <?php
                 $rpiDetails = json_decode($rpiDetails, true);   
                 foreach ($rpiDetails as $rpi_id => $rpi_name)
                 {
            ?>  
            <li style="margin-bottom: 8px;">
            <a href="javascript:void(0);" onclick="reportIssueCMB(<?php echo $rpi_id; ?>,<?php echo $issuekey; ?>,<?php echo $bkgId; ?>)" class="btn btn-light-secondary font-14 mb5 p10 weight500" title="Click here to report issue"><?php echo $rpi_name; ?></a>
            </li>
            <?php   
               }  
            ?>
            </ul>
        </div>

    </div>
</div>
<?php 
        $accordianNo = $accordianNo + 1 ; 
    } 
    ?>



 <?php   
    $this->endWidget();
?>
</div>
<script type="text/javascript">
    function reportIssueCMB(rpiId,rpiType,bkgId)
    {  //debugger; 
        if(rpiType == 1 && rpiId == 7)
        {
            sosCall(1);
            return false;
        }
       let href = "<?php echo Yii::app()->createUrl('issue/reportIssue'); ?>";
       var csrf = $('#bookingreportissue').find("INPUT[name=YII_CSRF_TOKEN]").val();
       $.ajax({
           type: 'POST',
           url: href,
           data: {'rpi_id':rpiId,'rpi_type':rpiType, 'booking_id': bkgId, YII_CSRF_TOKEN: csrf},
           success: function(data2)
           {  
                 //debugger;
                 var data = "";
                 var isJSON = false;
                 
                 try
                 {
                     data = JSON.parse(data2);
                     isJSON = true;
                 }
                 catch(exception)
                 {
                     
                 }
                 
                 if(!isJSON)
                 {
                    $('#reportIssueModal').removeClass('fade');
                    $('#reportIssueModal').css('display', 'block');
                    $('#reportIssueModelContent').html(data2);
                    $('#reportIssueModelContent').removeClass("hide");
                    $('#reportIssueModal').modal('show');
                 }
                 else
                 {
                     
                    //$('#reportIssueModal').addClass('fade');
                    //$('#reportIssueModal').css('display', 'none');
                    //$('#reportIssueModal').modal('hide');
                    if(data.success)
                    {
//                        message = "Issue reported successfully. You will receive a call back shortly.";
//                        toastr['info'](message, {
//                         closeButton: true,
//                         tapToDismiss: false,
//                         timeout: 500000
//                        });
                        if(!$('.alertcabclass').hasClass('hide'))
                        {
                           $('.alertcabclass').html('');
                           $('.alertcabclass').addClass('hide');
                        }
                       //$('#reportIssueModal').addClass('fade');
                       // $('#reportIssueModal').css('display', 'none');
                       // $('#reportIssueModal').modal('hide');
                    }
                    else
                    {
                        var error = data.errors;
//                        message = error;
//                        toastr['error'](message, 'Failed to process!', {
//                            closeButton: true,
//                            tapToDismiss: false,
//                            timeout: 500000
//                        });
                        
                        if($('.alertcabclass').hasClass('hide'))
                        {
                           $('.alertcabclass').html(error);
                           $('.alertcabclass').removeClass('hide');
                        }
                    }
                    return false;
                 }
            },
           error: function(xhr, ajaxOptions, thrownError)
           {
               if(xhr.status == "403")
               {
                   handleException(xhr, function()
                   {
                       
                   });
               }
           }
       });
    }
    
    function reportAissue(key, issue, accno)
    {   //debugger;
        let issueId = key;
        let issueName = issue;
        var issueCategory = <?php echo json_encode($reportIssueArray) ?>;
        $.each(issueCategory, function(index, value ) {// debugger;
            //alert( index + ": " + value );
            if(issueId != index)
            {
                $('.reportissue'+index).addClass('hide');
            }
            
            if(issueId == index)
            {
                $('#heading'+accno).addClass('hide');
                $('.reportIssueCatagory').html('('+issueName+')').removeClass('hide');
            }
            $('.reportissue'+index).removeClass('card');
        });
        $('.reportissuelist').removeClass('hide');
    }
</script>