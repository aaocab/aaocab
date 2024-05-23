
<div class="content-boxed-widget">
<h3 class="mb10">Please Enter Resources</h3>
                <?php 
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'citylinks-form', 'enableClientValidation' => true,
                    
                    'enableAjaxValidation' => false,
                    'errorMessageCssClass' => 'help-block',
                    'htmlOptions' => array(
                        'class' => 'form-inline',
                    ),
                ));
                /* @var $form CActiveForm */
                ?> 
                <input type="hidden" name="city_id" value="<?= $cdata['cid']; ?>">
                <input type="hidden" name="cat" value="<?= $cdata['cat']; ?>">
                <div class="input-simple-1 has-icon input-blue bottom-30">
                    <?= $form->textField($model, 'cln_title', array('label' => "Link Title" , 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Link Title')))) ?>
                </div>   
                <div class="input-simple-1 has-icon input-blue bottom-30">
                    <?= $form->textField($model, 'cln_url', array('label' => "Link URL", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Link URL')))) ?>
                </div> 
                 <div class="text-center pb10">
                    <button class="uppercase btn-green pl10 pr10 mr5" id="sbmtbtn" type="button" value="Verify">Submit</button>
                </div>
                <?php $this->endWidget(); ?>

</div>
