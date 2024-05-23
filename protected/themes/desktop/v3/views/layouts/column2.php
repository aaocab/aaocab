<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main1'); ?>

<!--<div class="row title-widget m0">
    <div class="col-12">
        <div class="container">
            <?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>-->
<div class="container mt30">
    <div class="row">
        <div class="col-lg-3 mb0">
            <div class="sidebar sidenav">
                <?php
                $this->renderPartial('//users/sideprofile');
                ?>

            </div>
        </div>
        <div class="col-lg-9">
<!--            <h4 class="font-22"><?= $this->pageTitle ?></h4>-->
            <?php echo $content; ?>
        </div>
    </div>
</div>
<?php $this->endContent(); ?>