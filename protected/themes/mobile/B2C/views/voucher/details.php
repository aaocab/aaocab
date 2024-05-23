<?php
$this->layout = 'column1';
$version = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/voucher.js?v=' . $version);
?>
<style>
.help-block { display: none;}
</style>
<div class="content-boxed-widget p0">
    <div class="content p0 pb20 bottom-0">	
		<?php if (!empty($errors)): 
		$errorList = json_decode($errors);
		?>
		<div class="text-center mt30">
		<div class="bg-danger p10 text-danger" style="font-size: 18px;">
		<ul style="list-style-type:none;" class="red-box"><?php 
			foreach($errorList as $v)
			{				
				foreach($v as $v1)
				{
				?>
					<li style="color: #721c24"><?php echo $v1; ?></li>
			<?php 		
				}					
			}
			 ?>
		</ul>
		</div></div>
		<?php endif; ?>
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'buyForm', 'enableClientValidation' => true,
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
        /* @var $form CActiveForm */
        ?>
        <div class="container content-padding p10 m0 mb20 color-white">
            <div class="above-overlay">
                <h3 class="mb10 uppercase"><?php echo $this->pageTitle; ?> </h3>
                <div class="one-half">
                    <div class="input-simple-1 has-icon input-green bottom-20"><em>Voucher</em>
                        <b><?php echo $voucherModel->vch_code; ?></b> (<?= $voucherModel->vch_title; ?>)
                    </div>
                </div>

                <div class="one-half last-column">
                    <div class="input-simple-1 has-icon input-green bottom-20"><em>Selling Price</em>
                        <h1 class="font-30">&#8377;<b><?php echo $voucherModel->vch_selling_price; ?></b></h1>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="overlay bg-blue-dark opacity-90"></div>
        </div>

        <div class="content">
            <em>Number</em>
            <div class="select-box select-box-1 mt0 mb30">
			<div class="form-group">
                <?php
                $qtyData = [];
                for ($i = 0; $i <= 50; $i++) {
                    $qtyData[$i] = ($i > 0) ? $i : "Select Quantity";
                }
                ?>
                <?= $form->dropDownList($model, 'vsb_qty', array('data' => $qtyData),array('class' => 'form-control','placeholder'=>'Enter Quantity')) ?>
                <?php echo $form->error($model, 'vsb_qty', ['class' => 'help-block error']); ?>
            </div>
            </div>
            
                <div class="input-simple-1 has-icon input-green bottom-20"><em>Name</em>
                <div class="form-group">
                    <?= $form->textField($model, 'vsb_name', array('class' => "form-control border-radius",'placeholder'=>"Name")) ?>
                    <?php echo $form->error($model,'vsb_name',['class' => 'help-block error']);?>
                </div>
                </div>
                
            
                <div class="input-simple-1 has-icon input-green bottom-20"><em>Email</em>
                <div class="form-group">
                    <?= $form->textField($model, 'vsb_email', array('class' => "form-control border-radius",'placeholder'=>"Email")) ?>
                    <?php echo $form->error($model,'vsb_email',['class' => 'help-block error']);?>
                </div>
                </div>				
            					
            <div class="content p0 bottom-0 text-center">				
                <button type="submit" class="uppercase btn-green pl15 pr15 mr20"  name="sub" value="Submit">Buy</button>					
                <a href="<?= Yii::app()->createUrl('voucher') ?>" class="uppercase btn-red pl15 pr15 cancelModal" >Back</a>
            </div>
        </div>  
        <?= $form->hiddenField($model, "vsb_vch_id ", ['value' => 1]); ?>
        <?php $this->endWidget(); ?>
    </div>	
</div>

