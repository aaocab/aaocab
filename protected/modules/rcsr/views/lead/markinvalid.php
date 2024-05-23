
<style>

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12"> 
            <div class="panel" >

                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll1">
                        <div style="width: 100%; padding: 3px; overflow: auto; line-height: 10px; font: normal arial; border-radius: 5px; -moz-border-radius: 5px; border: 1px #aaa solid;color: #444;">
							<?= CHtml::beginForm(Yii::app()->createUrl('rcsr/booking/canbooking'), "post", ['accept-charset' => "UTF-8", 'id' => "deleteForm", 'class' => "form", 'data-replace' => ".error-message p"]); ?>

                            <input type="hidden" id="bk_id" name="bk_id" value="<?= $bkid ?>"/>
                            <div class="form-group">
                                <label for="delete"><b>Reason for cancellation : </b></label>
                                <textarea class="form-control" rows="3" cols="50" name="bkreason" id="bkreason" placeholder="Please write message" required="true"></textarea>           
                            </div>
                            <div class="Submit-button" style="margin-top: 5px;">
                                <button type="submit" class="btn btn-primary" >SUBMIT</button>
                            </div>
							<?= CHtml::endForm() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
