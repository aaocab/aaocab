<style>
    .reportBtn .btn{
        width:250px;
        margin-left: 40px;
    }
</style>
<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/plugins/form-select2/select2.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/bootbox/bootbox.min.js');
$pageno	 = Yii::app()->request->getParam('page');
?>
<div id="content" class="mb20">
    <div class="col-xs-12">
        <div class="actions">
            <ul >
                <li class="col-xs-2"><a href="<?php echo Yii::app()->createUrl('admin/index/dashboard') ?>">Dashboard</a></li>
                <li class="col-xs-2"><a href="<?php echo Yii::app()->createUrl('admin/user/list') ?>">Users</a></li>
                <li class="col-xs-2"><a href="#">Payments</a></li>
                <li class="col-xs-2"><a href="<?php echo Yii::app()->createUrl('admin/index/leads') ?>">Leads</a></li>
                <li class="col-xs-2"><a href="<?php echo Yii::app()->createUrl('admin/index/notifications') ?>"  >Feedbacks</a></li>
                <li class="col-xs-2"><a href="<?php echo Yii::app()->createUrl('admin/user/reports') ?>"  style="background-color:  #FFF;color: #000">Reports</a></li>
            </ul>
        </div>
    </div>


    <div class="col-sm-12 col-xs-12 search-form mt20 ">
		<?php
		$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'citySelection-form-form',
			'action'				 => Yii::app()->createUrl('admin/user/reports', array('page' => $pageno)),
			'enableClientValidation' => true,
			'method'				 => 'get',
			'errorMessageCssClass'	 => 'help-block'));
		?>
        <ul class="col-xs-12" style="list-style: none;">
            <li class="col-xs-4"> <?php
				$arr	 = Yii::app()->request->getParam('Providers');
				echo $form->select2Group($model, 'pvr_city', array('label'			 => '',
					'widgetOptions'	 => array('data'			 => $model->getCityByProvider(),
						'val'			 => $arr['pvr_city'],
						'events'		 => array(' change' => 'js: function(e) { searchby(); }'),
						'htmlOptions'	 => array('style' => 'width: 100%', 'placeholder' => "Please select city")
				)));
				?>
                <input type="hidden" name="noofProvider" id="noofProvider" value="">
                <input type="hidden" name="cityID" id="cityID" value="">
            </li>
            <li class="col-xs-4" id="vendor" style="font-size: 1.4em"><?php
				if ($_GET['noofProvider'] != '')
				{
					echo 'Total Vendors signed up: ' . Yii::app()->request->getParam('noofProvider');
				}
				?></li>

        </ul>
		<?php $this->endWidget(); ?>
    </div>

    <div class="col-sm-12 col-xs-12 search-form mt20 row ">
        <div class="col-xs-12 reportBtn pb20">
            <a  id="showprogressPanel" class="btn btn-primary" style="text-decoration: none;">
                Provider Progress report</a> </div>
        <div id="progress" >
            <div class="projects" >
                <table cellpadding="0" cellspacing="0" >
                    <tbody id="result">
                    </tbody>
                </table>

            </div>
        </div>
        <div class="col-xs-12 reportBtn pb20 hide">
            <a  id="showvendorPanel" class="btn btn-primary" style="text-decoration: none;">Coverage report</a>
            <input type="hidden" name="service" id="service" value=""></div>

        <div id="vendorReport" style="display: none;">
            <div class="col-sm-12 col-xs-12 search-form  ">
				<?php
				$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'serviceSelection-form-form',
					'action'				 => Yii::app()->createUrl('admin/user/reports', array('page' => $pageno)),
					'enableClientValidation' => true,
					'method'				 => 'get',
					'errorMessageCssClass'	 => 'help-block'));
				?>
                <ul class="col-xs-12" style="list-style: none;">
                    <li class="col-xs-4"> <?php
						echo $form->select2Group($servicemodel, 'svc_id', array('label'			 => '',
							'widgetOptions'	 => array('data'			 => $servicemodel->getParentServicesdropdown(),
								'options'		 => ['maximumSelectionSize' => 5],
								'events'		 => array(' change' => 'js: function(e) { searchbyservice(); }'),
								'htmlOptions'	 => array('style' => 'width: 100%', 'placeholder' => "Please select services", 'multiple' => 'multiple')
						)));
						?>
                    </li>
                    <li class="col-xs-4" id="submitService" style="display: none;"><a  id="serviceProcess" class="btn btn-primary" style="text-decoration: none;">Process</a></li>

                </ul>
				<?php $this->endWidget(); ?>
            </div>
            <div class="mb120" id="box">

            </div>
        </div>


        <div class="col-xs-12 reportBtn pb20">
            <a  id="showpindometePanel" class="btn btn-primary" style="text-decoration: none;">Service category report</a>
        </div>
        <div id="showpindometeReport" style="display: none;">
            <div class="projects" >
                <table cellpadding="0" cellspacing="0" >
                    <tbody id="result2">
                    </tbody>
                </table>

            </div>
        </div>



        <div class="col-xs-12 reportBtn pb20">
            <a  id="showengagementPanel" class="btn btn-primary" style="text-decoration: none;">Provider engagement report</a>
        </div>

        <div id="engagementReport" style="display: none;">
            <div class="projects" >
                <table cellpadding="1" cellspacing="1" >

                    <tbody>
                        <tr><th id="htmltop"></th><th></th><th></th><th></th>
                        </tr>
                        <tr>
                            <td>Providers signed up</td>
                            <td id="countvendor"></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Services add status</td>
                            <td id="addcount"></td>
                            <td id="misscount"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Background verification status</td>
                            <td id="done"></td>
                            <td id="inpross"></td>
                            <td id="notstart"></td>
                        </tr>
                        <tr>
                            <td>Average rating <br>(pind-o-meter)</td>
                            <td id="max"></td>
                            <td id="avg"></td>
                            <td id="min"></td>
                        </tr>
                    </tbody>


                </table>

            </div>
        </div>


    </div>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#showprogressPanel").click(function () {
            $("#progress").toggle("slow");
            var cityID = $("#cityID").val();
            var href1 = '<?= Yii::app()->createUrl("admin/user/reports1"); ?>';
            $.ajax({
                url: href1,
                dataType: "json",
                data: {"id": cityID},
                "success": function (data) {
                    $('#result').html('');
                    var items = [];
                    var emaildate = "";
                    var mobiledate = "";
                    var imagedate = "";
                    var bcheckdate = "";
                    var lasttime = "";
                    var lastactive = "";

                    var html = '<tr><th colspan="10"><a href="<?= Yii::app()->createUrl('admin/user/export?cityID='); ?>' + cityID + '">Download CSV</a></th></tr><tr><th>Name</th><th>User ID</th><th>Sign up</th>\n\
        <th>Email verified</th><th>Phone verified</th><th>Provider profile created</th><th>Picture added</th>\n\
<th>Background checked</th><th>Service added</th><th>Inactive since</th></tr>';

                    $.each(data.info, function (i, item) {

                        if (item.usr_email_verify == 1)
                        {
                            if (item.usr_email_verify_date != '' && item.usr_email_verify_date != null)
                            {
                                emaildate = moment(item.usr_email_verify_date).format('MM/DD/YYYY');
                            } else
                            {
                                emaildate = "Yes";
                            }
                        } else
                        {
                            emaildate = "No";
                        }
                        if (item.usr_mobile_verify == 1)
                        {
                            if (item.usr_mobile_verify_date != '' && item.usr_mobile_verify_date != null)
                            {
                                mobiledate = moment(item.usr_mobile_verify_date).format('MM/DD/YYYY');
                            } else
                            {
                                mobiledate = "Yes";
                            }
                        } else
                        {
                            mobiledate = "No";
                        }
                        if (item.pvr_image_modify_date != '' && item.pvr_image_modify_date != null)
                        {
                            imagedate = moment(item.pvr_image_modify_date).format('MM/DD/YYYY');
                        } else
                        {
                            imagedate = "Yes";
                        }
                        if (item.pvr_background_check == 1)
                        {
                            if (item.pvr_background_check_date != '' && item.pvr_background_check_date != null)
                            {
                                bcheckdate = moment(item.pvr_background_check_date).format('MM/DD/YYYY');
                            } else
                            {
                                bcheckdate = "Yes";
                            }
                        } else
                        {
                            bcheckdate = "No";
                        }
                        if (item.lasttime != '' && item.lasttime != null)
                        {
                            lasttime = moment(item.lasttime).format('MM/DD/YYYY');
                        } else
                        {
                            lasttime = "None";
                        }

                        if (item.lastactive != null)
                        {

                            active = parseInt(item.lastactive);
                            if (active > 60)
                            {
                                $hr = parseInt(active / 60);
                                lastactive = $hr + ' hrs';
                            } else if (active < 60 && active > 30)
                            {

                                lastactive = active + ' min';
                            } else
                            {
                                lastactive = 'Active';
                            }
                        } else
                        {
                            lastactive = "Logged in";
                        }


                        items.push('<tr><td>' + item.pvr_name + '</td><td>' + item.pvr_user_id + '</td>\n\
            <td>' + moment(item.usr_created_at).format('MM/DD/YYYY') + '</td><td>' + emaildate + '</td><td>' + mobiledate + '</td>\n\
            <td>' + moment(item.pvr_create_date).format('MM/DD/YYYY') + '</td><td>' + imagedate + '</td><td>' + bcheckdate + '</td><td>' + lasttime + '</td><td>' + lastactive + '</td></tr>');
                    });
                    $('#result').append(html + items.join(''));
                }
            });
        });
        $("#showvendorPanel").click(function () {
            $("#vendorReport").toggle("slow");
        });
        $("#showpindometePanel").click(function () {
            $("#showpindometeReport").toggle("slow");
            var cityID = $("#cityID").val();
            var href2 = '<?= Yii::app()->createUrl("admin/user/reports2"); ?>';
            $.ajax({
                url: href2,
                dataType: "json",
                data: {"id": cityID},
                "success": function (data) {
                    $('#result2').html('');
                    var html = '<tr><th colspan="5"><a href="<?= Yii::app()->createUrl('admin/user/export2?cityID='); ?>' + cityID + '">Download CSV</a></th></tr><tr><th>Service name</th><th>Providers</th><th>Pind-o-meter<br>(Minimum)</th>\n\
        <th>Pind-o-meter<br>(Maximum)</th><th>Pind-o-meter<br>(Average)</th></tr>';
                    var items = [];
                    $.each(data.info, function (i, item) {
                        items.push('<tr><td>' + item.svc_name + '</td><td>' + item.cnt + '</td>\n\
            <td>' + Math.round(item.min1) + '</td><td>' + Math.round(item.max1) + '</td><td>' + Math.round(item.avg1) + '</td></tr>');
                    });
                    $('#result2').append(html + items.join(''));

                }
            });



        });

        $("#serviceProcess").click(function () {

            var serviceID = $("#service").val();
            var href2 = '<?= Yii::app()->createUrl("admin/user/reports3"); ?>';
            $.ajax({
                url: href2,
                dataType: "json",
                data: {"id": serviceID},
                "success": function (data) {
                    // alert(data.info);
                    /*  var servicearr = data.info;
                     
                     
                     
                     
                     $('#basicTable').remove();
                     var tdfirst = '<td>vxcvxc</td>';
                     mytable = $('<table></table>').attr({id: "basicTable"});
                     var rows = 10;
                     var cols = servicearr.length;
                     var tr = [];
                     for (var i = 0; i < rows; i++) {
                     var row = $('<tr></tr>').attr({id: ["trc" + i].join(' ')}).appendTo(mytable);
                     
                     /* for (var j = 0; j < cols; j++) {
                     if (i == 0)
                     {
                     var heading = servicearr[j];
                     } else {
                     heading = "text";
                     }
                     
                     $('<td></td>').text(heading).appendTo(row);
                     
                     }*/
                    /*   var items = [];
                     $.each(servicearr, function(i, item) {
                     // alert(item.svc_name);
                     items.push('<td>' + item.svc_name + '</td>');
                     });
                     
                     //  $('<td></td>').text(heading).appendTo(row);
                     $('<td></td>').html(items.join(''));
                     
                     }
                     
                     console.log("TTTTT:" + mytable.html());
                     mytable.appendTo("#box");
                     
                     */

                    $("#box").text('Comming Soon');
                }
            });
        });

        $("#showengagementPanel").click(function () {
            $("#engagementReport").toggle("slow");
            var cityID = $("#cityID").val();
            var href3 = '<?= Yii::app()->createUrl("admin/user/engagement"); ?>';
            $("#cityID").val(cityID);
            $.ajax({
                url: href3,
                dataType: "json",
                data: {"id": cityID},
                "success": function (data) {



                    var htmltop = '<a href="<?= Yii::app()->createUrl('admin/user/export3?cityID='); ?>' + cityID + '">Download CSV</a>';
                    $("#htmltop").html(htmltop);
                    var misscount = data.info.vendorCount - data.info.servadded;
                    $("#countvendor").text('' + data.info.vendorCount);
                    $("#addcount").text('Added : ' + data.info.servadded);
                    $("#misscount").text('Missing : ' + misscount);
                    $("#done").text('Done:' + data.info.bk3);
                    $("#inpross").text('In process:' + data.info.bk12);
                    $("#notstart").text('Not started:' + data.info.bk0);

                    $("#max").text('Max:' + Math.round(data.rate.max1));
                    $("#avg").text('Average:' + Math.round(data.rate.avg1));
                    $("#min").text('Min:' + Math.round(data.rate.min1));
                }
            });
        });
    });
    function searchby()
    {
        var cityID = $('#<?= CHtml::activeId($model, 'pvr_city') ?>').val();
        var href = '<?= Yii::app()->createUrl("admin/user/providercity"); ?>';
        $("#cityID").val(cityID);
        $.ajax({
            url: href,
            dataType: "json",
            data: {"id": cityID},
            "success": function (data) {
                //alert(data.info);
                $("#vendor").show("slow");
                $("#vendor").text("Providers signed up: " + data.count);
                $("#noofProvider").val(data.count);
            }
        });
    }
    function searchbyservice()
    {
        var ServicesID = 0;
        ServicesID = $('#<?= CHtml::activeId($servicemodel, 'svc_id') ?>').val();
        $("#service").val(ServicesID);
        if (ServicesID != null)
        {
            $("#submitService").show();
        } else {
            $("#submitService").hide();
        }
        //alert($ServicesID.length);
        // alert($CityID);
        /* 
         $('#basicTable').remove();
         mytable = $('<table></table>').attr({id: "basicTable"});
         var rows = 10;
         var cols = $ServicesID.length;
         var tr = [];
         for (var i = 0; i < rows; i++) {
         var row = $('<tr></tr>').attr({class: ["class1", "class2", "class3"].join(' ')}).appendTo(mytable);
         for (var j = 0; j < cols; j++) {
         $('<td></td>').text("text1" + i).appendTo(row);
         }
         
         }
         console.log("TTTTT:" + mytable.html());
         mytable.appendTo("#box");*/
    }

</script>