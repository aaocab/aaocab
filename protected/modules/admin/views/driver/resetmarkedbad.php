<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    div .comments {
        border-bottom:1px #333 solid;
        padding:3px;
        line-height: 14px;
        font-weight: normal;
    }

    div .comments .comment {
        padding:3px;max-width:200px
    }
    div .comments .footer {
        padding:2px 5px;
        color: #888;
        text-align: right;
        font-style: italic;
        font-size: 0.85em;
        height: auto;
        width: auto;
    }   

    .remarkbox{
        width: 100%; 
        padding: 3px;  
        overflow: auto; 
        line-height: 14px; 
        font: normal arial; 
        border-radius: 5px; 
        -moz-border-radius: 5px; 
        border: 1px #aaa solid;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12"> 
            <div class="panel" >
                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll">
                        <div class="row">
                            <div class="col-xs-12">
								<?php
								$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
									'id'					 => 'reset-vehicle-form', 'enableClientValidation' => true,
									'clientOptions'			 => array(
										'validateOnSubmit'	 => true,
										'errorCssClass'		 => 'has-error',
										'afterValidate'		 => 'js:function(form,data,hasError){
                        if(!hasError){
                            $.ajax({
                            "type":"POST",
                            "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/driver/resetmarkedbad', ['refId' => $refId])) . '",
                            "data":form.serialize(),
							"dataType": "json",
                                                        "success":function(data1){
                                                                    if(data1.success){
                                                                        bootbox.hideAll();
                                                                        refreshDriverGrid();
                                                                    }else{
                                                                          alert(\'Sorry error occured\');
                                                                    }
                                                        
								},
                            });
                            }
                        }'
									),
									// Please note: When you enable ajax validation, make sure the corresponding
									// controller action is handling ajax validation correctly.
									// See class documentation of CActiveForm for details on this,
									// you need to use the performAjaxValidation()-method described there.
									'enableAjaxValidation'	 => false,
									'errorMessageCssClass'	 => 'help-block',
									'htmlOptions'			 => array(
										'class' => 'form-horizontal'
									),
								));
								/* @var $form TbActiveForm */
								?>
                                <div class="form-group">
                                    <label for="delete"><b>Add Remark : </b></label>
									<?=
									$form->textAreaGroup($drvModel, 'drv_reset_desc', array('label'			 => '',
										'widgetOptions'	 => [
											'htmlOptions' => ['placeholder' => 'Please explain why you want to reset the bad mark count']]))
									?>
                                </div>
                                <div class="Submit-button" style="margin-top: 5px;">
									<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
                                </div>
								<?php $this->endWidget(); ?>
                            </div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
