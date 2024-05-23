<style>
    .full-width {
        width: 100% !important;
    }
</style>

<div class="row mb10">
    <div class="col-xs-12">
        <p class="weight400 text-center">Please enter Places. <br>
        </p></div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 book-panel2 float-none marginauto">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php 
                $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                    'id' => 'cityplace-form', 'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                        'errorCssClass' => 'has-error',
                        'afterValidate' => 'js:function(form,data,hasError){
                                  if(!hasError){
                                                $.ajax({
						"type":"POST",
						"dataType":"json",
						"url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('city/cityplaces')) . '",
						"data":form.serialize(),
						"success":function(data2){   
                                                        if(data2.success){
                                                               alert("Your place has been added successfully.");
                                                               bootbox.hideAll();
                                                        }else{
                                                                alert("Please add at least one place.");

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
                    <?= $form->textFieldGroup($model, 'cpl_places', array('label' => "Place Name", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Place Name')))) ?>
                </div>
                <div class="col-sm-5">
                    <?= $form->textFieldGroup($model, 'cpl_url', array('label' => "Place URL", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Place URL')))) ?>
                </div>
                <div class="col-sm-3" style="padding-top: 22px;">
                    <button class="btn btn-success border-radius full-width  border-none" id="sbmtbtn" type="submit" value="Verify">Submit</button>
                </div>
                <?php $this->endWidget(); ?>
                
            </div>
        </div> 
    </div>
    
</div>
