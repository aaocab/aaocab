<div role="alert" class="alert alert-success"> 
    <strong>Transaction was successful. Thank you for your order.</strong>
</div>
<?
$this->renderPartial('summary', ['model' => $model, 'succ' => true]);
