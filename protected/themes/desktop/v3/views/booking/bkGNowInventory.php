<div class="container mb-2">
	<div class="row">

		<div class="col-12 col-lg-6 offset-lg-3 mt-1 text-center font-16">
			<p class="weight600 h3">Inventory is limited & prices are changing too fast for your date & time of travel</p>
			<p >On the next screen, we will show you price ranges for cars. As always, we will provide you a final price before you book.</p>
			<p class="weight400 hide">Dear customer, since your current pick up time falls during off hours, a cab may not be available. 
				You may change the pickup time to 8:00 a.m. or later to ensure the cab is confirmed.</p>
		</div>
		<div class="col-xl-12 text-center mt-5 mb-4">
			<?php
			/** @var CActiveForm $form */
			$form = $this->beginWidget('CActiveForm', array(
				'id'					 => 'inventoryShow',
				'enableClientValidation' => false,
				'clientOptions'			 => [
					'validateOnSubmit'	 => false,
					'errorCssClass'		 => 'has-error',
				],
				'enableAjaxValidation'	 => false,
				'errorMessageCssClass'	 => 'help-block',
				//'action'				 => Yii::app()->createUrl('booking/bkGNowInventory'),
				'htmlOptions'			 => array(
					'class'			 => 'form-horizontal',
					'autocomplete'	 => 'off',
				),
			));
			?>
			<input type="button" value="Go back" rid="<?= $rid ?>"  step="<?= $pageid ?>" name="yt0" class="btn btn-light backButton">
			<input type="hidden" name="pageID" id="pageID" value="8">
			<input type="hidden" name="step" value="<?= $pageid ?>">
			<input type="hidden" name="showPriceRange" id="showPriceRange" value="1">
			<input type="submit" value="Next" name="yt0" id="servicetypebtn" class="btn btn-primary pl-5 pr-5 showcabdetails">
			<input type="hidden" name="rdata" value="<?= $this->pageRequest->getEncrptedData() ?>">
			<?php $this->endWidget(); ?>
        </div>
	</div>
</div>


<script type="text/javascript">
	$(document).ready(function ()
	{

		step = <?= $step ?>;
		tabURL = window.location.href;
		pageTitle = "";
		tabHead = "Gozo Now Inventory";
		toggleStep(step, 7, tabURL, pageTitle, tabHead, true, <?= $this->pageRequest->step ?>);

	});


	$('form#inventoryShow').on('submit', function ()
	{
		checkTierQuotes();
		return false;
	});

	function checkTierQuotes()
	{
		var form = $("form#inventoryShow");
		$.ajax({
			"type": "POST",
			"dataType": "html",
			"url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/tierQuotes')) ?>",
			"data": form.serialize(),
			"beforeSend": function ()
			{
				blockForm(form);
			},
			"complete": function ()
			{
				unBlockForm(form);
			},
			"success": function (data2)
			{
//				debugger;
				var data = "";
				var isJSON = false;
				try
				{
					data = JSON.parse(data2);
					isJSON = true;
				} catch (e)
				{

				}
				if (!isJSON)
				{
					$("#tab9").html(data2);
				} else
				{
					if (data.success)
					{
						location.href = data.data.url;
						return;
					}

					var errors = data.errors;
					msg = JSON.stringify(errors);
					settings = form.data('settings');
					$.each(settings.attributes, function (i)
					{
						$.fn.yiiactiveform.updateInput(settings.attributes[i], errors, form);
					});
					$.fn.yiiactiveform.updateSummary(form, errors);
					messages = errors;
					content = '';
					var summaryAttributes = [];
					for (var i in settings.attributes)
					{
						if (settings.attributes[i].summary)
						{
							summaryAttributes.push(settings.attributes[i].id);
						}
					}
					displayFormError(form, messages);
				}
			},
			error: function (xhr, ajaxOptions, thrownError)
			{
				alert(xhr.status);
				alert(thrownError);
			}
		});
		return false;
	}
</script>