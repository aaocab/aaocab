<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main1'); ?>

<div class="">
    <div class="register_path row">
        <div class="col-xs-12 book-panel2 padding_zero">
            <div class="p0 mt20 margin_zero">
                <div class="col-xs-12 col-sm-3 col-md-2 profile-left-panel p0">
                    <div class="sidebar">
                        <?php
                        $this->renderPartial('//users/sideprofile');
                        ?>

                    </div>
                </div>
                <div class="col-xs-12 col-sm-9 col-md-10 padding_zero">
                    <div class="profile-right-panel p20 padding_zero">
                        <h4 class="m0 mb20 weight400"><?= $this->pageTitle ?></h4>
                        <?php echo $content; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endContent(); ?>