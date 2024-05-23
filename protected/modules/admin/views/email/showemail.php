<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body ">
                    <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
						<?php
						/** @var EmailLog $model */
						if ($model)
						{
							$link		 = $model->elg_file_path;
							$basePath	 = yii::app()->basePath;
							$file		 = $basePath . DIRECTORY_SEPARATOR . 'doc' . $link;
							echo $model->getContents();
						}
						else
						{
							if ($row['elg_s3_data'] != '{}' && $row['elg_s3_data'] != '')
							{
								$objSpaceFile	 = Stub\common\SpaceFile::populate($row['elg_s3_data']);
								$file			 = Storage::getFile($objSpaceFile->getSpace(), $objSpaceFile->key);
								echo $file->getContents();
							}else{
							?>

						<table style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">

								<tr><td><h3><?php echo $row['elg_subject']; ?></h3></td></tr>

								<tr><td><?php echo nl2br($row['elg_content']); ?></td></tr>

							</table>
							<?php } }
					?>
                    </div>
                    <div>&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
</div> 