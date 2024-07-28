<div class="col-xs-12 col-sm-6 col-md-6">
    <div class="form-group">
        <label class="control-label" for="">Email*<strong style="font-size:10px ">(Click plus icons to add the email and then save)</strong></label><input value="" placeholder="Email"   class="form-control ContactEmail"></div>
    <div class="alert alert-block alert-danger messageEmail" style="display:none"></div>
    <div class="row float-right " style="white-space: nowrap">
        <div class="col-xs-12">
            <a class="btn btn-primary btn-xs  weight400 font-bold " onclick="AddEmail()"title="Add More"><i class="fa fa-plus" aria-hidden="true"></i></a>
        </div>
    </div>
    <div class="tableEmail">
		<?php
		$ceModels = $model->contactEmails;
		if ($ceModels == [])
		{
			$ceModels[] = new ContactEmail();
		}
		$isPrimaryEmail	 = "";
		$email			 = "";
		for ($i = 0; $i < count($ceModels); $i++)
		{
			if ($ceModels[$i]->eml_email_address != "")
			{
				$btnEmail = $i == 0 ? 'btn btn-success btn-xs emailPrimary ' : 'btn btn-primary btn-xs emailPrimary ';
				if ($i == 0)
				{
					?>
					<table class="table table-hover"><thead><tr><th>Email</th><th>Primary</th><th>Remove</th></tr></thead>
						<tbody class="addemail">
						<?php } ?>
						<tr>
							<?php
							echo $form->hiddenField($ceModels[$i], "[$i]eml_email_address", array('type' => "hidden", 'class' => "email_address", 'value' => $uvrid != NULL ? $model->email_address : $ceModels[$i]->eml_email_address));
							echo $form->hiddenField($ceModels[$i], "[$i]eml_is_primary", array('type' => "hidden", 'class' => "email_primary", 'value' => $uvrid != NULL ? 1 : count($ceModels) == 1 ? 1 : $ceModels[$i]->eml_is_primary));
							echo $form->hiddenField($ceModels[$i], "[$i]eml_type", array('type' => "hidden", 'class' => "email_type", 'value' => $uvrid != NULL ? 1 : count($ceModels) == 1 ? 1 : $ceModels[$i]->eml_type));
							?>
							<td style="word-break:break-all;"><?php echo $ceModels[$i]->eml_email_address ?> <span><?php echo $ceModels[$i]->eml_is_verified == 0 ? '<img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="20">' : '<img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Contact Verified" width="20">' ?></span></td>
							<td><button type="button" class="<?php echo $btnEmail; ?>" <?php
					if ($i == 0)
					{
						echo "disabled";
					}
							 ?>><i class="<?php echo ($ceModels[$i]->eml_is_primary == 1) ? 'fa fa-check-square-o':'fa fa-square-o';?>" aria-hidden="true"></i></button></td>
							<td ><button  type="button" class="btn btn-danger btn-xs removeEmail" onclick="removeEmail(<?php echo $ceModels[$i]->eml_contact_id ?>,'<?php echo $ceModels[$i]->eml_email_address ?>')"><i class="fa fa-times" aria-hidden="true"></i></button></td>  
							
							<td><button type="button" onclick="resendEmailLink(<?php echo $ceModels[$i]->eml_contact_id ?>,'<?php echo $ceModels[$i]->eml_email_address ?>')"><i class="fa fa-share"></i></button></td>
							
						</tr>
						<?php
						if ((count($ceModels) - 1) == $i)
						{
							?> </tbody> </table>	
							<?php
				}
			}
		}
		?>

    </div>
</div>
<script type="text/javascript">
    let contactArray = "";
    var rowCounterEmail = '<?php echo count($ceModels); ?>';
    $(document).ready(function () {
        $(document).on('click', '.emailPrimary', function () {
            $('.emailPrimary').prop("disabled", false);
            $(this).prop("disabled", true);
            $('.email_primary').each(function () {
                $(".email_primary").val(0);
                $('.emailPrimary').removeClass('btn-success');
                $('.emailPrimary').addClass('btn-primary');
                $('.emailPrimary').children('i').removeClass('fa-check-square-o');
                $('.emailPrimary').children('i').addClass('fa-square-o');
            });
            $(this).closest('tr').children('.email_primary').val(1);
            $(this).closest('td').children('.emailPrimary').removeClass('btn-primary');
            $(this).closest('td').children('.emailPrimary').addClass('btn-success');
            $(this).closest('td').children('.emailPrimary').children('i').removeClass('fa-square-o');
            $(this).closest('td').children('.emailPrimary').children('i').addClass('fa-check-square-o');

        });
        $(document).on('click', '.removeEmail', function () {
            $(this).parents("tr").remove();
            if ($('.addemail tr').length == 1)
            {
                $(".email_primary").val(1);
                $(".emailPrimary").prop("disabled", true);
                $('.addemail td').children('.emailPrimary').removeClass('btn-primary');
                $('.addemail td').children('.emailPrimary').addClass('btn-success');
                $('.addemail td').children('.emailPrimary').children('i').removeClass('fa-square-o');
                $('.addemail td').children('.emailPrimary').children('i').addClass('fa-check-square-o');
            }
        });
    });
   
    function AddEmail()
    {
        
        var duplicateEmailCount = 0;
        $("#msg").html("");
        var email_address = $(".ContactEmail").val();
        var regExpression = /\b[a-zA-Z0-9\u00C0-\u017F._%+-]+@[a-zA-Z0-9\u00C0-\u017F.-]+\.[a-zA-Z]{2,}\b/;
        if (email_address == "")
        {
            $(".messageEmail").show();
            $(".messageEmail").html('<p>Please provide your email!</p>');
            return false;
        } else if (!regExpression.test(email_address))
        {
            $(".messageEmail").show();
            $(".messageEmail").html('<p>Please provide correct email format!</p>');
            return false;
        }
        $('.email_address').each(function ()
        {
            if (email_address.trim() == $(this).val().trim())
            {
                duplicateEmailCount++;
            }
        });

        if (duplicateEmailCount > 0)
        {
            $(".messageEmail").show();
            $(".messageEmail").html('<p>This email is already registered!</p>');
            return false;
        }

        let emailArray = [];
        emailArray.push(email_address);

        var href = '<?= Yii::app()->createUrl("admin/contact/checkEmail") ?>';
        $.ajax(
                {
                    "url": href,
                    "type": "GET",
                    "dataType": "json",
                    "data":
                            {
                                "email_address": emailArray,

                                "YII_CSRF_TOKEN": "<?= Yii::app()->request->csrfToken ?>"
                            },
                    "success": function (response)
                    {
                        //debugger
                        //console.log(JSON.stringify(response));
                        if (response.success)
                        {
                            let status = "1";
                            let emailList = response.data;
                            contactArray = response.data;
                            if (emailList.length > 0)
                            {
                                let appendHtml =
                                        "<table class='table table-hover table-bordered'>\n\
                                            <caption float = 'right'><button class='btn btn-primary  weight400 font-bold addContact' onClick = 'addcontact(\"" + email_address + "\",\"" + status + "\")' name='" + email_address + "' ><i class='fa fa-email'></i>Add new Contact</button></caption>\n\
                                            <thead>\n\
                                                <th>Contact Name</th>\n\
                                                <th>Contact Email</th>\n\
                                                <th>Is Driver</th>\n\
                                                <th>Is Vendor</th>\n\
                                                <th>Action</th>\n\
                                            </thead>\n\
                                            <tbody>";
                                for (let index = 0; index < emailList.length; index++)
                                {
                                    let contactName = emailList[index].ctt_first_name + " " + emailList[index].ctt_last_name;
                                    if (emailList[index].hasOwnProperty("ctt_business_name") && emailList[index].ctt_business_name.length > 0)
                                    {
                                        contactName = emailList[index].ctt_business_name;
                                    }

                                    let isDriver = "No";
                                    let isVendor = "No";

                                    if (emailList[index].hasOwnProperty("cr_is_driver"))
                                    {
                                        isDriver = "Yes" + "(" + emailList[index].drv_code + ")";
                                    }

                                    if (emailList[index].hasOwnProperty("cr_is_vendor"))
                                    {
                                        isVendor = "Yes" + "(" + emailList[index].vnd_code + ")";
                                    }


                                    appendHtml +=
                                            "<tr>\n\
                                                    <td>" + contactName + "</td>\n\
                                                    <td>" + emailList[index].eml_email_address + "</td>\n\
                                                    <td>" + isDriver + "</td>\n\
                                                    <td>" + isVendor + "</td>\n\
                                                     <td>\n\
                                                        <button class='btn btn-info searchContact' title='Assign' onclick='sendContactDetails(\"" + emailList[index].eml_contact_id + "\",\"" + emailList[index].eml_email_address + "\",\"" + contactName + "\",\"" + emailList[index].ctt_license_no + "\")'><i class='fa fa-check-square-o' aria-hidden='true'></i> Select  </button></td>\n\
                                                    </td>\n\
                                                </tr>";
                                }

                                appendHtml += "</table>";

                                emailListBox = bootbox.dialog
                                        ({
                                            message: appendHtml,
                                            title: "Existing Contacts",
                                            size: 'big',
                                            class: 'panel panel-default ',
                                            onEscape: function ()
                                            {
                                                emailListBox.modal('hide');
                                            }
                                        });
                            }
                        } else
                        {
                            if (response.hasOwnProperty("errorCode"))
                            {
                                $(".messageEmail").show();
                                $(".messageEmail").html('<p>Please provide correct email format!</p>');
                                return false;
                            }
                            addcontact(email_address, 0);
                        }
                    }
                });
    }


    /**
     * Append contact In contact Form
     * @param {type} email_address Email Address
     * @param {type} status - 1: hide model box , 0: append Contact in form
     */
    function addcontact(email_address, status)
    {
        $(".messageEmail").html("");
        $(".messageEmail").hide();
        var rowCount = $('.addemail tr').length == 0 ? 0 : rowCounterEmail;

        if (rowCount == 0)
        {
            $(".tableEmail").html("");
            var html = '<table class="table table-hover"> <thead> <tr> <th>Email</th> <th>Primary</th> <th>Remove</th> </tr></thead> <tbody class="addemail"> <tr> <input class="email_address" type="hidden" value="' + email_address + '" name="ContactEmail[' + rowCount + '][eml_email_address]"> <input class="email_primary" type="hidden" value="1" name="ContactEmail[' + rowCount + '][eml_is_primary]"> <input class="email_type" type="hidden" value="1" name="ContactEmail[' + rowCount + '][eml_type]"><td style="word-break:break-all;">' + email_address + '<span><img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="20"></span></td><td><button type="button" class="btn btn-success btn-xs emailPrimary" disabled><i class="fa fa-check-square-o" aria-hidden="true"></i></button></td><td><button type="button" class="btn btn-danger btn-xs removeEmail"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr></tbody></table>';
            $(".tableEmail").append(html);
        } else
        {
            var html = '<tr><td  style="word-break:break-all;">' + email_address + '<span><img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="20"></span></td> <input class="email_address" type="hidden" value="' + email_address + '" name="ContactEmail[' + rowCount + '][eml_email_address]"> <input class="email_primary" type="hidden" value="0" name="ContactEmail[' + rowCount + '][eml_is_primary]"><input class="email_type" type="hidden" value="1" name="ContactEmail[' + rowCount + '][eml_type]"> <td><button type="button" class="btn btn-primary btn-xs emailPrimary" ><i class="fa fa-square-o" aria-hidden="true"></i></button></td><td><button type="button" class="btn btn-danger btn-xs removeEmail"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>';
            $(".addemail").append(html);
        }
        $(".ContactEmail").val('');
        rowCounterEmail++;
        if (status)
        {
            emailListBox.modal('hide');
        }
    }
    /**
     * 
     * @param {type} cttId - ContactId
     * @param {type} email - Email Address
     * @param {type} name - Contact Name
     * @param {type} licence - Licence Number
     * @returns {Boolean}         */
    function sendContactDetails(cttId, email, name, licence)
    {
        let drvId = "";
        for (let index = 0; index < contactArray.length; index++)
        {
            let vendorFlag = 0;
            let driverFlag = 0;
            let contactFlag = 0;
            drvId = contactArray[index].cr_is_driver;
            var vndId = "";
            if (window.location.href.indexOf("vendor") > -1)
            {
                vendorFlag = 1;
            } else if (window.location.href.indexOf("contact") > -1)
            {
                contactFlag = 1;
                let url = window.location.href;
                let splitUrl = url.split("?");
                let arrCttId = splitUrl[1].split("=");

                urlCttId = arrCttId[1];
            } else
            {
                driverFlag = 1;
            }

            if (contactArray[index].eml_contact_id == cttId)
            {
                if (contactFlag && cttId != urlCttId)
                {
                    let alertString = "This Email address belongs to a different contact Id. To claim this, Click Add as new contact Button";
                    alert(alertString);
                    return false;
                }
                if (vendorFlag && (contactArray[index].hasOwnProperty("cr_is_vendor")))
                {
                    let alertString = "Already registered as a vendor. Cannot register again. Please use existing vendor account " + contactArray[index].vnd_code + " for " + contactArray[index].eml_email_address;
                    alert(alertString);
                    return false;
                }

                if (driverFlag && (contactArray[index].hasOwnProperty("cr_is_driver")))
                {
                    for (let i in contactArray[index].mapVendors)
                    {
                        vndId = contactArray[index].mapVendors[i].vnd_id;
                        // && contactArray[index].hasOwnProperty("eml_is_verified")
                        if (($("#Drivers_drv_vendor_id1").val() === vndId))
                        {
                            let alertString = "Already registered as a driver in this vendor. Cannot register again. Please use existing driver account " + contactArray[index].drv_code + " for " + contactArray[index].eml_email_address;
                            alert(alertString);
                            return false;
                        }
                    }
                }
            }

            $("#contactDetails").html(name + ' | ' + email + '  | License: ' + licence);
            $('#Drivers_drv_contact_id').val(cttId);
            $('#Vendors_vnd_contact_id').val(cttId);
            $('#Drivers_drv_contact_name').val(name);
            $('#Vendors_vnd_contact_name').val(name);
            $('#Drivers_drv_id').val(drvId);
            bootbox.hideAll();
            $(".contact_div_details").removeClass('hide');
            return false;
        }
    }

function resendEmailLink(con,email)
    {
		bootbox.confirm({
            message: "Are you sure want to resend link ?",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn-info'
                },
                cancel: {
                    label: 'CANCEL',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                $.ajax({
                        type: 'POST',
                        url: '<?php echo Yii::app()->createUrl('admin/contact/resendEmailVerification'); ?>',
                         data: {"email": email,"contact": con, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
                        dataType: 'json',
                         success: function (data)
                        {
                           alert(data);
                        }
                });
                }
            }
        });
    }
	
	function removeEmail(con,email)
    {
		bootbox.confirm({
            message: "Are you sure want to remove email ?",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn-info'
                },
                cancel: {
                    label: 'CANCEL',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                $.ajax({
                        type: 'POST',
                        url: '<?php echo Yii::app()->createUrl('admin/contact/removeEmail'); ?>',
                         data: {"email": email,"contact": con, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
                        dataType: 'json',
                         success: function (data)
                        {
                           alert(data);
                        }
                });
                }
            }
        });
    }


</script>