

<?
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/plugins/form-typeahead/typeahead.bundle.min.js');
?>
<div class="row">
    <div class="col-xs-8">

        <?
        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id' => 'user-place-form', 'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error'
            ),
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // See class documentation of CActiveForm for details on this,
            // you need to use the performAjaxValidation()-method described there.
            'enableAjaxValidation' => false,
            'errorMessageCssClass' => 'help-block',
            'htmlOptions' => array(
                'class' => 'form-horizontal',
            ),
        ));
        /* @var $form TbActiveForm */
        ?>
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                <?php echo CHtml::errorSummary($model); ?>
                    
                <div class="col-md-offset-2 col-lg-8 col-md-9 col-xs-12">
                    
                    <?= $form->textFieldGroup($model, 'name', array('label' => 'Name of Place', 'widgetOptions' => array('htmlOptions' => ['required' => true, 'class' => 'border-none ', 'value' => $model->name]))) ?>

                </div>




                <div class="col-md-offset-2 col-lg-8 col-md-9 col-xs-12">
                    
                    <?= $form->textFieldGroup($model, 'address1', array('label' => 'Address', 'widgetOptions' => array('htmlOptions' => ['required' => true, 'class' => 'border-none', 'value' => $model->address1]))) ?>
                    <?= $form->textFieldGroup($model, 'address2', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'class' => 'border-none', 'value' => $model->address2]))) ?>   
                    <?= $form->textFieldGroup($model, 'address3', array('label' => '', 'widgetOptions' => array('htmlOptions' => [ 'class' => 'border-none', 'value' => $model->address3]))) ?>
                </div>




                <div class="form-group">
                    <div class="col-md-offset-2 col-lg-8 col-md-9 col-xs-12 pl10 pr10"> 
                        <label class="control-label" for="ffcity">City</label>
                        <?php
                        $CJson = Cities::model()->getJSON();

//                        $cityList1 = $model1->getServiceCity();
//                        $cityList = CHtml::listData($cityList1, 'cty_id', 'cty_name');

                        $this->widget('booster.widgets.TbSelect2', array(
                            'model' => $model,
                            'attribute' => 'city',
                            'val' => $model->city,
                            'asDropDownList' => FALSE,
                            'options' => array('data' => new CJavaScriptExpression($CJson)),
                            'htmlOptions' => array('placeholder' => 'Select City', 'class' => 'border-none', 'style' => 'width:100%'),
                                )
                        );
                        ?>            

                    </div>              
                </div>

                <div class="col-md-offset-2 col-lg-8 col-md-9 col-xs-12">
                  
                    <?= $form->textFieldGroup($model, 'zip', array('label' => 'Zip Code', 'widgetOptions' => array('htmlOptions' => ['required' => true, 'class' => 'border-none', 'value' => $model->zip]))) ?>

                </div>
            </div>
            <div class="panel-footer text-center">
                <input class="btn btn-primary"  type="submit" name="sub" value="Submit" />
            </div>
        </div></div>
        <?php $this->endWidget(); ?>
    </div>
</div>

