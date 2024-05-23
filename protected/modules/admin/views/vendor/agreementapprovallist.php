<style type="text/css">
    .pic{
        max-width: 100%;
        max-height: 175px;
    }
</style>
<?php
$pageno				 = Yii::app()->request->getParam('page');
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];

?>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');

$docType=Document::model()->documentType();
?>
<div id="list-content">
    <div class="row" >
        <div class="panel">
            <div class="panel-heading">Pending Vendor Document to Approve</div>
            <div class="panel-body">
                
                  <div class="col-xs-12">
                        <?php
                        $form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
                                'id'		 => 'agreementapprovalist-form', 'enableClientValidation' => true,
                                'clientOptions'	 => array(
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
                                        'class' => '',
                                ),
                        ));
                        ?>
                    <div class="row">
                          <div class="col-xs-6 col-sm-3">
								   <label class="control-label">Vendor Name</label>
								<?php
								$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
						$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'vag_vnd_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Vendor",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
                                              populateVendor(this, '{$model->vag_vnd_id }');
                        }",
				'load'			 => "js:function(query, callback){
                                            loadVendor(query, callback);
                        }",
				'render'		 => "js:{
                                                option: function(item, escape){
                                                    return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                                },
                                                option_create: function(data, escape){
                                                    return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                                }
                                            }",
					),
				));
				?>
                                <span class="has-error"><? echo $form->error($model, 'vag_vnd_id'); ?></span>
                            </div>
                        <div class="col-xs-12 col-sm-3 text-center mt20 pt5">
                            <button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
                        </div>
                    </div>
			<?php $this->endWidget(); ?>
                </div>
                
                <div class="docgrid">
                    <div class="col-xs-12">
                          <div class="row">
                            <?php
                            
                            if (!empty($dataProvider))
                            {
                                    $params = array_filter($_REQUEST);
                                    $dataProvider->getPagination()->params = $params;
                                    $dataProvider->getSort()->params = $params;
                                    $items	 = '';
                                    $pdfImage	 = "/images/pdf.jpg";
                                    $noImage	 = "/images/no-image.png";
                                    foreach ($dataProvider->getData() as $doc)
                                    {
                                        $name="";
                                        if($doc['name'] !='')
                                        {
                                          $name=$doc['name'];
                                        }
                                        $picid	= $doc['vnd_contact_id'];
                                        //$agreementFile	 = ($doc['vag_soft_path'] != '') ? $doc['vag_soft_path'] : $doc['vag_digital_agreement'];
                                        $Url="";
                                        //$agreementFile  = $doc['vag_digital_sign'];
                                        $Url = VendorAgreement::getPathById($doc['vag_id'], VendorAgreement::DIGITAL_SIGN);
//                                        if (substr_count($agreementFile,"attachments")>0) {
//                                        $Url.=$agreementFile;
//                                        }
//                                        else
//                                        {
//                                            $Url.=AttachmentProcessing::ImagePath($agreementFile);
//                                        }
                                        $fileImage	 = '<img src="' . $Url . '" id="agreementId" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . $picid .',' . $doc['vnd_id'] .' )">';
                                        $filePdf	 = '<img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" onclick="openpic(' . $picid .',' . $doc['vnd_id'] .' )">';
                                        $filename	 = (pathinfo($Url, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
                                        $items		 .= '<div><div class="col-xs-3 mt30"><div class="text-center">Contact Name : <b>' .  $name . '</b><br>Document Name : <b>' . '(Agreement Info)</b><br>' . $filename . '</div></div></div>';

                                    }
                              if($items==""){
                                      $items.='<table class="table table-striped table-bordered mb0 table"><tbody><tr><td  class="empty"><span class="empty">No results found.</span></td></tr></tbody></table>';
                              }

                                    $this->widget('booster.widgets.TbGridView', array(
                                            'responsiveTable'	 => true,
                                            'filter'		 => $model,
                                            'dataProvider'	 => $dataProvider,
                                            'id'		 => 'agreementListGrid',
                                            'template'		 => "<div class='panel-heading'><div class='row m0'>
                <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
        </div></div>
        <div class='panel-body'>$items</div>
        <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                                            'itemsCssClass'		 => 'table table-striped table-bordered mb0',
                                            'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
                                            'emptyText' => 'We have not found anything related to your query.'
                                    ));
                            }else{
                                echo "Empty List";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function openpic(cid, vndid)
    {
        var title = "Approve Digital Agreement";
        $.ajax({
            "type": "GET",
            "dataType": "html",
            "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/vendor/agreementShowPic')) ?>",
            "data": {"ctt_id": cid,"vnd_id":vndid},
           "success": function (data) {
                box = bootbox.dialog({
                    message: data,
                    className: "bootbox-xs",
                    title: "<span class='text-center'>" + title + "</span>",
                    size: "large",
                    onEscape: function () {
                        box.modal('hide');
                    }
                });
            }
        });
    }
 
</script>