
<div class="content-boxed-widget">
<h3 class="mb10">Please Enter Places</h3>
                <?php 
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'cityplace-form', 'enableClientValidation' => true,
                   
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
                    <?= $form->textField($model, 'cpl_places', array('label' => "Place Name", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Place Name')))) ?>
                </div>
                 <div class="input-simple-1 has-icon input-blue bottom-30">
                    <?= $form->textField($model, 'cpl_url', array('label' => "Place URL", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Place URL')))) ?>
                </div>
              <div class="text-center pb10">
                    <button class="uppercase btn-green pl10 pr10 mr5" id="sbmtbtn2" type="button" value="Verify">Submit</button>
                </div>
                <?php $this->endWidget(); ?>
           
</div>
