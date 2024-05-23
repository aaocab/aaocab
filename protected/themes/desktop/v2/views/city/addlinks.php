<style>
    .full-width {
        width: 100% !important;
    }
</style>

<div class="row mb10">
    <div class="col-xs-12">
        <p class="weight400 text-center">Please enter Resources. <br>
            
        </p>   </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 book-panel2 float-none marginauto">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php 
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'citylinks-form', 'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'errorCssClass' => 'has-error',
                        'afterValidate' => 'js:function(form,data,hasError){
                                  if(!hasError){
                                                $.ajax({
						"type":"POST",
						"dataType":"json",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('city/citylinks')) . '",
						"data":form.serialize(),
						"success":function(data2){   
                                                        if(data2.success){
                                                               alert("Your resources has been added successfully.");
                                                               bootbox.hideAll();
                                                        }else{
                                                                alert("Please add at least one Resource.");

                                                        }

                                                },
                                                error: function (xhr, ajaxOptions, thrownError) 
                                                {
                                                                alert(xhr.status);
                                                                alert(thrownError);
                                                }
                                          });
                             }
                    }'
                    ),
                    'enableAjaxValidation' => false,
                    'errorMessageCssClass' => 'help-block',
                    'htmlOptions' => array(
                        'class' => 'form-inline',
                    ),
                ));
                /* @var $form TbActiveForm */
                ?> 
                <input type="hidden" name="city_id" value="<?= $cdata['cid']; ?>">
                <input type="hidden" name="cat" value="<?= $cdata['cat']; ?>">
                <div class="col-sm-5">
                    <?= $form->textFieldGroup($model, 'cln_title', array('label' => "Link Title", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Link Title')))) ?>
                </div>   
                <div class="col-sm-5">
                    <?= $form->textFieldGroup($model, 'cln_url', array('label' => "Link URL", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Link URL')))) ?>
                </div> 
                <div class="col-sm-3" style="padding-top: 12px;">
                    <button class="btn btn-success border-radius full-width  border-none" id="sbmtbtn" type="submit" value="Verify">Submit</button>
                </div>
                <?php $this->endWidget(); ?>
                
            </div>
        </div> 
    </div>
    
</div>
