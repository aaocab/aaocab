<?php
$this->beginContent('//layouts/main1');
?>
<?php echo $content; 
echo $this->renderPartial("/index/footer");
?>

<?php $this->endContent(); ?>