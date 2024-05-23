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
									'id'					 => 'rejectForm', 'enableClientValidation' => true,
									'clientOptions'			 => array(
										'validateOnSubmit'	 => true,
										'errorCssClass'		 => 'has-error',
										'afterValidate'		 => 'js:function(form,data,hasError){
                                            if(!hasError){
                                                $.ajax({
                                                    "type":"POST",
                                                    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/document/rejectdoc', ['doc_id' => $doc_id, 'doc_status' => 2])) . '",
                                                    "data":form.serialize(),
                                                    "dataType": "json",
                                                    "success":function(data1)
                                                    {
                                                        if(data1.success)
                                                        {
                                                            bootbox.hideAll();
															var id = $("#rejectdocid").text();
															var nxtId = $("#"+id).prev().attr("id");
															if(nxtId == "" || nxtId === undefined)
															{
																$("#"+id).parent().find(".label").remove();
															}
															else
															{
																$("#"+nxtId).parent().find(".label").remove();
																$("#"+nxtId).remove();
															}
															$("<span><i>"+$("#Document_doc_remarks").val()+"</i></span>").insertAfter("#"+id);
															$("<span> Rejected</span>").insertAfter("#"+id).addClass("label label-danger");
															$("#"+id).remove();
															
                                                        }
                                                    },
                                                });
                                              }
                                            }'
									),
									'enableAjaxValidation'	 => false,
									'errorMessageCssClass'	 => 'help-block',
									'htmlOptions'			 => array(
										'class' => 'form-horizontal'
									),
								));
								/* @var $form TbActiveForm */
								?>
								<input type="hidden" id="cttid" value="<?= $cttid?>">
								<input type="hidden" id="increment" value="<?= $increment?>">
                                <div class="form-group">
									<?= $form->textAreaGroup($model, 'doc_remarks', array('label' => '', 'rows' => 10, 'cols' => 50)) ?>
                                </div>
                                <div class="Submit-button" style="margin-top: 5px;">
									<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
                                </div>
								<?php $this->endWidget(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>