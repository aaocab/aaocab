<div class="row">
    <div class="col-xs-12">
		<?php
		$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
			'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
			'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
			'openOnFocus'		 => true, 'preload'			 => false,
			'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
			'addPrecedence'		 => false,];
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'email-form',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => '',
			),
		));
		/* @var $form TbActiveForm */
		?>
		<div class="well pb20">
			<input type="hidden" value="<?= Yii::app()->request->baseUrl ?>" id="baseUrl">
			<div class="col-xs-12 col-sm-6 col-md-4"> 
				<?= $form->textFieldGroup($model, 'search', array('label' => 'Search', 'htmlOptions' => array('placeholder' => 'search by booking id or other information'))) ?>
            </div>		
			<div class="col-xs-12 col-md-4 mt20 pt5 mb10 text-center">
				<?php
				if (Yii::app()->request->isAjaxRequest)
				{
					echo CHtml::Button("Search", array('class' => 'btn btn-primary search'));
				}
				else
				{
					?>
					<button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
				<?php } ?>			
			</div>
        </div>
		<?php $this->endWidget(); ?>
		</div>
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'id'				 => 'giftcarduser',
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					
					array('name' => 'Agent Id', 
						 'filter' => false,
						'value' =>function($data) {
							echo $data['agt_id'];
							
						},
						'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Agent Id'),
					array('name' => 'agtname', 'value' => '$data[agt_name]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Agent Name'),
					array('name' => 'agtphone', 'value' => '$data[agt_phone]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Agent Phone'),
					array('name' => 'agtemail', 'value' => '$data[agt_email]', 'sortable' => true, 'headerHtmlOptions'	 => array(), 'header' => 'Email'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{activate}{deactivate}',
						'buttons'			 => array(
						'deactivate'	 => array(
						'click'		 => 'function(e){                                                        
                                    try
                                    {
										
										
										var self = $(this);
										var baseUrl = $("#baseUrl").val();
                                        $href = $(this).attr("href");
                                        jQuery.ajax({type:"GET",url:$href,success:function(data)
                                        {
											data=JSON.parse(data);
                                            if(data.success)
											{
                                                if(data.msg!="")
                                                {
												    alert(data.msg);
                                                }
                                               // alert(baseUrl+data.imgpath);
												self.find("img").attr("src",baseUrl+data.imgpath);
											}
											else
											{
												alert("Some error occurred");
											}
                                        }}); 
                                    }
                                    catch(e)
                                    { 
                                        alert(e); 
                                    }
                                    return false;

                                }',
						'url'		 => 'Yii::app()->createUrl("admin/promos/updategftpartnerstatus", array(\'agt_id\' => $data[agt_id], \'promoId\' => $data[promo_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\active.png',
						'visible'	 => '($data[prp_active] == 1)',
						'label'		 => '<i class="fa fa-toggle-on"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'pruActive', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs pruDeactive p0', 'title' => 'Remove User')
					),
					'activate'		 => array(
						'click'		 => 'function(e){                                                        
                                    try
                                    {
										var self = $(this);
										var baseUrl = $("#baseUrl").val();
										$href = $(this).attr("href");
										jQuery.ajax({type:"GET",url:$href,data:{},
										success:function(data)
										{
											data=JSON.parse(data);
											if(data.success)
											{
													if(data.msg!="")
													{
														alert(data.msg);
													}
													//alert(baseUrl+data.imgpath);
													self.find("img").attr("src",baseUrl+data.imgpath);
											}
											else
											{
												alert("Failed to add user.");
											}
										}}); 
                                    }
                                    catch(e)
                                    { 
                                        alert(e); 
                                    }
                                    return false;

                                }',
						'url'		 => 'Yii::app()->createUrl("admin/promos/updategftpartnerstatus", array(\'agt_id\' => $data[agt_id], \'promoId\' => $data[promo_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
						'visible'	 => '($data[prp_active] == 0)',
						'label'		 => '<i class="fa fa-toggle-on"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'pruActive', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs pruActive p0', 'title' => 'Add user.')
					),
							'htmlOptions'		 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(".search").click(function () {
            $.fn.yiiGridView.update('giftcarduser', {data: $('#email-form').serialize()});
        });
    });
</script>