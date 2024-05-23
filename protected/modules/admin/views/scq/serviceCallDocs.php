<style type="text/css">
    .pic{
        max-width: 100%;
        max-height: 175px;
    }
    .transition {
        -webkit-transform: scale(1.9);
        -moz-transform: scale(1.9);
        -o-transform: scale(1.9);
        transform: scale(1.9);
    }
    .pic-bordered {
        -webkit-transition: all .2s ease-in-out;
        -moz-transition: all .2s ease-in-out;
        -o-transition: all .2s ease-in-out;
        -ms-transition: all .2s ease-in-out;
    }

    .pic-bordered {
        width:240px;
        margin:55px;
    }
</style>
<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div id="list-content">
    <div class="row" >
        <div class="panel">
            <div class="panel-heading text-center text-capitalize">Showing the service call queue uploaded documents</div>
            <div class="panel-body">
                <div class="docgrid">
                    <div class="col-xs-12 text-center">
                        <div class="row">
                            <?php
                            if (!empty($dataProvider))
                            {
                                $params                                = array_filter($_REQUEST);
                                $dataProvider->getPagination()->params = $params;
                                $dataProvider->getSort()->params       = $params;
                                $items                                 = '';
                                $pdfImage                              = "/images/pdf.jpg";
                                $noImage                               = "/images/no-image.png";
                                foreach ($dataProvider->getData() as $doc)
                                {
                                    $picid = $doc['cbd_id'];

                                    if ($doc['cbd_id'] != "")
                                    {
                                        if ($doc['cbd_file_path'] != "")
                                        {
                                            $docPath   = CallBackDocuments::getDocPathById($doc['cbd_id']);
                                            $fileImage = '<a href="' . $docPath . '"  title="Click View" target="_blank"><img src="' . $docPath . '" class="pic-bordered pic btn p0 pt10"></a>';
                                            $filePdf   = '<a href="' . $pdfImage . '" title="Click View" target="_blank"><img src="' . $pdfImage . '" class="pic-bordered pic btn p0 pt10" ></a>';
                                            $filename  = (pathinfo($docPath, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
                                            $items     .= '<span class = "col-md-6 inline-block">' . $filename . '</span>';
                                        }
                                    }
                                }
                                if ($items == "")
                                {
                                    $items .= '<table class="table table-striped table-bordered mb0 table"><tbody><tr><td  class="empty"><span class="empty">No results found.</span></td></tr></tbody></table>';
                                }

                                $this->widget('booster.widgets.TbGridView', array(
                                    'responsiveTable' => true,
                                    'filter'          => $model,
                                    'dataProvider'    => $dataProvider,
                                    'id'              => 'docsListGrid',
                                    'template'        => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 col-md-3 pt5'></div><div class='col-xs-12 col-sm-6 col-md-3 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>$items</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'></div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                                    'itemsCssClass'   => 'table table-striped table-bordered mb0',
                                    'htmlOptions'     => array('class' => 'table-responsive panel panel-primary  compact'),
                                    'emptyText'       => 'We have not found anything related to your query.'
                                ));
                            }
                            ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<script>
    $(document).ready(function () {
        $('.pic-bordered').hover(function () {
            $(".pic-bordered").addClass('transition');

        }, function () {
            $(".pic-bordered").removeClass('transition');
        });
    });
</script>
