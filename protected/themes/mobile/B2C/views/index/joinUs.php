<div id="joinus">
	<?php
		$form = $this->beginWidget('CActiveForm', array(
			'id'					 => 'agentJoin-form',
			'enableClientValidation' => false,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'action' => array('/join/vendor'),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => 'form-horizontal'
			),
		));
		/* @var $form CActiveForm */
	?>
	<div class="content-boxed-widget text-center">
		<div id="VendorOuterDiv">
			<h3 style="color: #000000;"><span id="AgentOuterDivText"></span></h3>
		</div>  
		<div id="agentJoin">
			<div class="Submit-button">
				<button type="submit" class="p50 light-blue-bg color-black">If you want Gozo to give you business <br> <b>Join as a Taxi operator </b> and attach your taxi <br><br/> अगर आप चाहते हैं कि Gozo आपको बुकिंग दे तो एक <br> टैक्सी ऑपरेटर के रूप में हमसे अपनी टैक्सी जोड़ें </button>
			</div>
		</div>
	</div>
	<?php $this->endWidget(); ?>
	<?php
		$form = $this->beginWidget('CActiveForm', array(
			'id'					 => 'agentJoin-form',
			'enableClientValidation' => false,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'action' => array('/join/agent'),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => 'form-horizontal'
			),
		));
		/* @var $form CActiveForm */
	?>
	<div class="content-boxed-widget text-center">
		<div id="VendorOuterDiv">
			<div>
				<h3 style="color: #000000;"><span id="VendorOuterDivText"></span></h3>
			</div>
		</div>  
		<div id="vendorJoin">
			<div class="Submit-button">
				<button type="submit" class = "p50 light-orang-bg color-black">If you will create bookings <br> for GOZO  <strong>Join US as an AGENT</strong> <br><br/> यदि आप Gozo के लिए बुकिंग बनाएंगे <br> तो एक एजेंट (booking agent) के रूप में हमसे जुड़ें </button>
			</div>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>