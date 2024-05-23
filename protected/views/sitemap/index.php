<style>
    .sitemap .row div{  background: rgba(0, 0, 0, 0) url("/images/arrow1.png") no-repeat scroll left top; padding-left: 5px}
    .sitemap .row div a{ 
        line-height: 26px;

        color: #282828;
        padding: 10px 10px 0;
    }
    .sitemap .row div a:hover{ color: #f85d09;
    }

</style>
<section id="section7l">
    <div class="">
        <div class="panel panel-white">
            <div class="panel-heading"><h1 class="">Site Map</h1></div>
            <div class="panel-body pl30 pr30">
                <div class="row">
                    <div class="col-xs-12 sitemap">
                        <h4>Gozo Cabs</h4>
                        <? $i = 0; ?>
                        <div class="row">
                            <?php foreach ($mainList as $row): ?>
                                <? $i++; ?>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><a href="<?php echo CHtml::encode($row['url']); ?>"><?php echo CHtml::encode($row['title']); ?></a></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-xs-12 sitemap pt10">
                        <h4>Other Link</h4>
                        <div class="row">
                            <?php foreach ($otherList as $row): ?>
                                <? $i++; ?>
                                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><a href="<?php echo CHtml::encode($row['url']); ?>"><?php echo CHtml::encode($row['title']); ?></a></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-xs-12 sitemap pt10">
                        <h4>Routes</h4>
                        <? // var_dump($routeList) ?>
                        <div class="row">
                            <?php 
                            $app = Yii::app();
                                foreach ($routeList as $route): ?>                        
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3"><a href="<?php echo CHtml::encode($app->createAbsoluteUrl('/book-taxi/' . $route['rut_name'])); ?>"><?php echo CHtml::encode($route['from_city_name'] . ' to ' . $route['to_city_name']); ?></a></div>
                            <?php endforeach; ?>                            
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <?php
                        // the pagination widget with some options to mess
                        $this->widget('booster.widgets.TbPager', array('pages' => $usersList->pagination));
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
