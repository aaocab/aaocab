<div id="forgot-form">
    <?= CHtml::beginForm('', "post", ['id' => "forgetpasscode", 'accept-charset' => "utf-8"]); ?>
    <div class="form-title">
        <span class="form-title"><?= $message ?></span>
    </div>
    <div class="form-group">
        <input type="hidden" name="matchcode" value="1">
        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" id="email" /> 
    </div>
    <div class="form-group">
        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Code" name="code" id="code" /> 
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary uppercase pull-right">Submit</button>
    </div>
    <?= CHtml::endForm() ?>

</div>
