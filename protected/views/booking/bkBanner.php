<?php
$dboApplicable = Filter::dboApplicable($model);
if ($dboApplicable)
{
	if (!Yii::app()->user->isGuest)
	{
    ?>
		<a href="/terms/doubleback" target="_blank"><img src="/images/doubleback_fares2.jpg" alt=""></a>
    <?php
	}
	else
	{
		echo '<a href="/terms/doubleback" target="_blank"><img src="/images/doubleback_fares.jpg?v=0.3" alt=""></a>';
	}
}
?>