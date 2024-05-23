
<style>

    div .comments {
        border-bottom:1px #333 solid;
        padding:3px;
        line-height: 14px;
        font-weight: normal;
    }

    div .comments .comment {
        padding:3px;max-width:200px
    }
    div .comments .footer {
        padding:2px 5px;
        color: #888;
        text-align: right;
        font-style: italic;
        font-size: 0.85em;
        height: auto;
        width: auto;
    }

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<?php
$reasonList = ['' => '< Select a reason >'] + Vendors::model()->getCancelReasonList();
?>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            

            <div class="panel mb0" >                
                <div style="width: 100%; padding: 3px; overflow: auto; line-height: 10px; font: normal arial; border-radius: 5px; -moz-border-radius: 5px; border: 1px #aaa solid;color: #444;">
                    <?= CHtml::beginForm(Yii::app()->createUrl('rcsr/booking/canvendor'), "post", ['accept-charset' => "UTF-8", 'id' => "deleteForm", 'class' => "form", 'data-replace' => ".error-message p"]); ?>
                    <input type="hidden" id="bk_id" name="bk_id" value="<?= $bkid ?>"/>
                    <div class="panel-body panel-no-padding">
                         <div class="form-group">
                            <label for="delete"><b>Reason for cancellation : </b></label>
                            <?= CHtml::dropDownList('bkreason', '', ['' => '< Select a reason >'] + $reasonList, ['id' => "bkreason", 'class' => "form-control", 'required' => true]) ?>
                        </div>
                        <div class="form-group">
                            <label for="delete"><b>Reason for cancellation or deletion : </b></label>
                            <textarea class="form-control" rows="3" cols="50" name="bkreasontext" id="bkreasontext" placeholder="Please write message" required="true"></textarea>           
                        </div>
                         <div class="Submit-button text-center" >
                            <? echo CHtml::submitButton("SUBMIT", ['class' => "btn btn-primary mt10"]); ?>
                        </div>
                    </div>
                    <?= CHtml::endForm() ?>

                </div>
            </div>
        </div>
    </div>
</div>
