


<?= CHtml::beginForm('', "post", ['id' => "formId", 'accept-charset' => "utf-8"]); ?>
<?
if ($status == 'error')
{
echo "<span style='color:#ff0000;'>Invalid Username or Password.</span>";
}
?>
<div class="form-group">
    <label for="UserUsername">Username</label>
    <input name="txtUsername" class="form-control" maxlength="200" type="text" id="txtUsername">
    <div e_rel="txtUsername"></div>
</div>
<div class="form-group">
    <label for="UserPassword">Password</label>
    <input name="txtPassword" class="form-control " type="password" id="txtPassword">
    <div e_rel="txtPassword"></div>
</div>
<div class="form-group text-center" >
    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-sign-in"></i> Login</button>
</div>
<?= CHtml::endForm() ?>



