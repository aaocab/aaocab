<div class="container-fluid mt15 n">
	<div class="row">
		<div class="col-12 bg-black mb30 p0 text-center">
			<img src="/images/refer_friend.jpg?v=0.7" alt="Refer a friend, get cash back - it's a win-win!" title="Refer a friend, get cash back - it's a win-win!" class="img-fluid">
		</div>
	</div>
</div>
<?php
$this->renderPartial('partialReferFriend', [], false, false);
?>
<script>
    window.onload = function () {
		 showLogin(function () {
                window.location.href = '<?= Yii::app()->createUrl('users/refer') ?>';
            });
    }
</script>



