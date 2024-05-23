<?php
($this->newHome) ? $this->beginContent('//layouts/main_new') : $this->beginContent('//layouts/main1');
?>

<?php echo $content; ?>

<?php $this->endContent(); ?>