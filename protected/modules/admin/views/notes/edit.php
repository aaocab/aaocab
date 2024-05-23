<style type="text/css">
    .modal {  overflow-y:auto;}
    .flex {
		display: -webkit-box;
		display: -webkit-flex;
		display: -ms-flexbox;
		display: flex;
		flex-wrap: wrap;
	}
    .rounded-margin{ margin: 0 15px;}
    @media (min-width: 992px){
        .modal-lg {
            width: calc(56.55% - 10px)!important;
        }
    }
    @media (min-width: 768px){
        .modal-lg {
            width: 100%;
        }
    }
    .control-label{
        font-weight: bold
    }   
    .box-design1{ background: #8DCF8A; color: #000; padding: 10px;}
    .box-design1a{ background: #ccffcc; color: #000;}
    .box-design2{ background: #F8A6AC; color: #000;  padding: 10px;}
    .box-design2a{ background: #ffcccc; color: #000; }
    .label-tab label{ margin:0 17%!important}
    .label-tab .form-group{ margin-bottom: 0;}
    .bordered {
        border: 1px solid #ddd;
        min-height: 45px;
        line-height: 1.2em;
        margin-bottom: 10px;
        margin-left: 10px;
        margin-right: 10px;
        padding-bottom: 10px;
    }
</style>
<?php

$version = Yii::app()->params['customVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);

$time = Filter::getExecutionTime();

$GLOBALS['time'][9] = $time;
//print_r($model['dnt_id']);exit;

?>
<div class="row bordered">
    <div class="col-xs-12 col-sm-12 col-md-12">
	<?php if (Yii::app()->user->hasFlash('success'))
	{ ?>
    	<div class="alert alert-block alert-success">
	    <?php echo Yii::app()->user->getFlash('success'); ?>
    	</div>
	<?php } ?>

	<?php
	$selectizeOptions	 = ['create'		 => false, 'persist'		 => true, 'selectOnTab'		 => true,
	    'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	    'optgroupValueField'	 => 'id', 'optgroupLabelField'	 => 'text', 'optgroupField'		 => 'id',
	    'openOnFocus'		 => true, 'preload'		 => false,
	    'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	    'addPrecedence'		 => false,];
	$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	    'id'			 => 'edit-note-form',
	    'enableClientValidation' => true,
	    'clientOptions'		 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	    ),
	    'enableAjaxValidation'	 => false,
	    'errorMessageCssClass'	 => 'help-block',
	    'htmlOptions'		 => array(
		'class' => '',
	    ),
	));
	/* @var $form TbActiveForm */
	?>
    <div class="col-xs-12 pb10">
	    
		<div class="col-xs-12 col-sm-12 mt20">
			<label>Notes *</label>
			<?= $form->textFieldGroup($model, 'dnt_note', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Note', 'id'=>'DestinationNote_dnt_note')))) ?>
		</div>
		
		<div class="col-xs-4 col-md-4 mt20 mb10 text-center">
            <a class="btn btn-success btn-sm mb5" id="btnEditNote" onclick="editNote()" title="Edit" style="width:150px;">Edit</a>	
        </div>
	</div>
<?php $this->endWidget(); ?>
    </div>

</div>
<script>
   function editNote()
    {
	dnt_id = '<?= $model->dnt_id ?>';
	var href = '<?= Yii::app()->createUrl("admin/notes/edit"); ?>';
	var loadPageUrl = '<?= Yii::app()->createUrl("admin/notes/list"); ?>';
	//dnt_note = $('#DestinationNote_dnt_note').val();
	
	$.ajax({
            "url": href,
            "type": "GET",
           "dataType": "text",
            "data": {"dnt_id": dnt_id, "dnt_note": $('#DestinationNote_dnt_note').val()},
	        "async": false,
        "success": function (data) {
		    bootbox.alert("Edit Note sucessfully."),
            location.href = loadPageUrl; 
	    },
	    error: function (data) {
			
			alert('Sorry error occured');
				
        }
	})
    }
    
</script>
