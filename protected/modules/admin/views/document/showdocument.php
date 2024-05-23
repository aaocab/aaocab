<style type="text/css">
    .picform div.row{
        background-color: #EEEEFE;
        padding-top:3px;
        padding-bottom: 3px
    }
</style>
<?php
$version = Yii::app()->params['customVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>

<div class="panel">
    <div class="panel-body p0">
        <div class="col-xs-12">
            <div class="row">
               
                    <div class="col-xs-12">
                        <div class="row">			
<?php           
				//$url= $document['doc_file_front_path'];
                                $imageUrl = Document::getDocPathById($document['doc_id'], 1);
                                $fileImage	 = '<a href="' . $imageUrl . '"  target="_blank" id="vhdimage"> <img src="'. $imageUrl . '"  width="100%" id="vhdimage"></a>';
				if($fileImage !='')
				{
				    echo  $fileImage;
				}
				else
				{
				    echo "No image Found.";
				}
				?>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
   
</script>