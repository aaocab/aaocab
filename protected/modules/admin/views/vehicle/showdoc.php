<style type="text/css">
    .picform div.row{
        background-color: #EEEEFE;
        padding-top:3px;
        padding-bottom: 3px
    }
</style>

<?
/* @var $vmodel VehicleDocs  */
$model		 = Vehicles::model()->resetScope()->findByPk($vmodel->vhd_vhc_id);
$vtypeList	 = VehicleTypes::model()->getParentVehicleTypes(2);
$vTypeData	 = VehicleTypes::model()->getJSON($vtypeList);
?>
<div class="panel">
    <div class="panel-body p0">
        <div class="col-xs-12">
            <div class="row">
               
                    <div class="col-xs-12">
                        <div class="row">
							<?
                                                        $picfile	 = VehicleDocs::getDocPathById($vmodel->vhd_id);
							$filePdf	 = '<a href="' . $picfile . '"  target="_blank"> <img src="/images/pdf.jpg"  height="100%"><br>Click to see file</a>';
							$fileImage	 = '<a href="' . $picfile . '"  target="_blank" id="vhdimage"> <img src="' . $picfile . '"  width="100%" id="vhdimage"></a>';
							echo (pathinfo($vmodel->vhd_file, PATHINFO_EXTENSION) == 'pdf') ? $filePdf : $fileImage;
							?>

                        </div>
                    </div>
					
                
              
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
   

</script>