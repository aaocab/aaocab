

<div class="container">
	<?php
	$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'promotion-form', 'enableClientValidation' => false,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// See class documentation of CActiveForm for details on this,
		// you need to use the performAjaxValidation()-method described there.
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data',
		),
	));
	/* @var $form TbActiveForm */
	?>


    <div class="row">
		<?php // echo CHtml::errorSummary($model);   ?>
        <div class="col-md-9">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <h3 class="pl15 pb10"></h3>
                        <div class="panel-body pt0">

                            <div class="row">
                                <div class="col-sm-12"><label>Image</label>
									<?= $form->fileFieldGroup($model, 'pci_images', ['label' => '']); ?>        
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-sm-12">
									<div class="input-group p5">
										<?= $form->textAreaGroup($model, 'pci_tourist_attractions', array('label' => "Description*", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Attractions')))) ?>    
									</div>  </div>

                            </div>
						</div>
                    </div>
                </div>
            </div>



        </div>

    </div>
    <div class="row">
        <div class="col-xs-12 text-center pb10">
			<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>

        </div>
    </div>
    <div id="driver1"></div>
	<?php $this->endWidget(); ?>


</div>




<script type="text/javascript">
    $(document).ready(function () {
    });

</script>