<?php /* @var $this Controller */
//echo $this->newHome;
//echo $this->layoutSufix;
?>

<?php
($this->newHome) ? $this->beginContent('//layouts/main_new' . $this->layoutSufix) : $this->beginContent('//layouts/main1' . $this->layoutSufix);
?>

<?php echo $content; ?>

<?php $this->endContent(); ?>