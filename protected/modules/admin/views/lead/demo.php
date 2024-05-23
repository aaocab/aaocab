
<style type="text/css">

    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>


<div class="row">
    <div class="col-xs-12">
        <div class="text-right">
        </div>    
        <div class="panel panel-default">
            <div class="panel-body">
                <?php
					$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
						'id'					 => 'admins-register-form', 'enableClientValidation' => true,
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
							'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
						),
					));
					?>
<input type="file" name="file1" accept=".ogg,.flac,.mp3" required="required"/>
<input type="submit" name="submit"/>
</form>
            </div>  </div>
    </div>
</div>