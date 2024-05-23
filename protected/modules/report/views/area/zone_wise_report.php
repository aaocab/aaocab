<div class="container-fluid p0"><div class="panel panel-white"><div class="panel-body">

            <title>Today's Bookings</title>
			<?
			if ($error == 1)
			{
				?>
				<div class="row m0 mt20" id="passwordDiv">
					<form name="tbkg" method="POST" action="<?= Yii::app()->request->url ?>">
				<input type="hidden" name="YII_CSRF_TOKEN" value= "<?= Yii::app()->request->csrfToken ?>">
						<div class="col-xs-offset-4 col-xs-4">   
							<div class="form-group row text-center">
								<input class="form-control" type="password" id="psw" name="psw" value="" placeholder="Password" required/>
							</div>
							<div class="Submit-button row text-center">
								<button type="submit" class="btn btn-primary">SUBMIT</button>
							</div>
						</div>
					</form>
				</div>
			<? } ?>
			<?
			if ($error == 2)
			{
				?>
				<div class="row m0 mt20" id="wrongPassword" style="">
					<div class="col-xs-offset-4 col-xs-4">
						<h3>Wrong Password</h3>
						<img src="http://static.commentcamarche.net/es.ccm.net/pictures/Ud6krzOUaQiVrbx4IWkuzUrMD8vWr4qbG1wMtmWKQ94r7Doi6fybXXnACJoLFtKR-lol.png">
					</div>
				</div>
			<? } ?>
			<?
			if ($error == 0)
			{
				?>
<!--                    <div class="projects">
                    <div class="panel panel-default">
                        <div class="panel-body">
							<?php
							//$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
//								'id'					 => 'routewisecount-form', 'enableClientValidation' => true,
//								'clientOptions'			 => array(
//									'validateOnSubmit'	 => true,
//									'errorCssClass'		 => 'has-error'
//								),
//								// Please note: When you enable ajax validation, make sure the corresponding
//								// controller action is handling ajax validation correctly.
//								// See class documentation of CActiveForm for details on this,
//								// you need to use the performAjaxValidation()-method described there.
//								'enableAjaxValidation'	 => false,
//								'errorMessageCssClass'	 => 'help-block',
//								'htmlOptions'			 => array(
//									'class' => '',
//								),
//							));
							/* @var $form TbActiveForm */
							?>

                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
								<?//= $form->datePickerGroup($model, 'fromdate', array('label' => 'From Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'From Date', 'value' => $qry['fromdate'])), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
								<?//= $form->datePickerGroup($model, 'todate', array('label' => 'To Date', 'widgetOptions' => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('placeholder' => 'To Date', 'value' => $qry['todate'])), 'prepend' => '<i class="fa fa-calendar"></i>')); ?>
                            </div>

                            <div class="col-xs-6 col-sm-2 col-md-2 mt20">   
								<?php //echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
                            </div>
							<?php //$this->endWidget(); ?>
                        </div>
                    </div>
                </div>-->

				<div style="font-size: 9px;">Last refresh at <?= $lastRefeshDate; ?></div>

				<div class="row" id="routewiseDiv" style="margin-top: 10px;">  
					<div class="col-xs-12 col-sm-6">       
							
					<?php
					if (!empty($dataProvider))
						{
							$this->widget('booster.widgets.TbGridView', array(
								'id'				 => 'mbkg-grid',
								'responsiveTable'	 => true,
								'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('xyz/mbkg2', $dataProvider->getPagination()->params)),
								'dataProvider'		 => $dataProvider,
								'template'			 => "
							<table class='table table-bordered mt10'>
							
								<tr style='color: blue;background: whitesmoke'>
									<th colspan='2' class='text-center'><u>Today's Source Zone-wise Count</u></th>
								</tr>
								
								</table>
								<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>Total Bookings Count: $count</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>Total Bookings Count: $count</div></div></div>",
								'itemsCssClass'		 => 'table table-striped table-bordered mb0',
								'htmlOptions'		 => array('class' => 'panel panel-primary'),
								'columns'			 => array(
							array('name' => 'zonName', 'filter' => false, 'value' => '$data[zonName]', 'sortable' => true,  'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Source Zone'),
							array('name' => 'cntBkg1', 'filter' => false, 'value' => '$data[cntBkg1]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Count'),
							)));
				}
				?> 	
					</div>

					<div class="col-xs-12 col-sm-6">  
					<?php
					if (!empty($dataProvider1))
						{
							$this->widget('booster.widgets.TbGridView', array(
								'id'				 => 'mbkg-grid2',
								'responsiveTable'	 => true,
								'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('xyz/mbkg2', $dataProvider1->getPagination()->params)),
								'dataProvider'		 => $dataProvider1,
								'template'			 => "
							<table class='table table-bordered mt10'>
								<tr style='color: blue;background: whitesmoke'>
									<th colspan='2' class='text-center'><u>Today's Dest Zone-wise Count</u></th>
								</tr>
								</table>
								<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>Total Bookings Count: $count1</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>Total Bookings Count: $count1</div></div></div>",
								'itemsCssClass'		 => 'table table-striped table-bordered mb0',
								'htmlOptions'		 => array('class' => 'panel panel-primary'),
								'columns'			 => array(
							array('name' => 'zonName', 'filter' => false, 'value' => '$data[zonName]', 'sortable' => true,  'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Dest Zone'),
							array('name' => 'cntBkg2', 'filter' => false, 'value' => '$data[cntBkg2]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Count'),			
							)));
			
				}
				?> 

					</div>
				
			<? } ?>
		</div>
</div></div>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>