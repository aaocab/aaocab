<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		body{
			font-family: 'Arial';
			font-size: 12px;
			line-height: 20px;
			margin: 0;
			padding: 0;
		}
		.gray-color{
			color: #aeaeae;
		}
		p{
			font-size: 12px;
		}
		h1{
			font-size: 22px;
			line-height: 22px;
		}
		h2{
			font-size: 16px;
			line-height: 22px;
			font-weight: normal;
		}
		h3{
			font-size: 15px;
			line-height: 22px;
		}
		.text-center{
			text-align: center;
		}
		.main-div{
			width:720px;
			margin: auto;
			font-size: 12px!important;
		}
		.border-bottom{
			border-bottom: #ededed 1px solid;
		}
		.list_type li{
			padding: 8px 0;
		}
		.list_table table{
			width: 100%;
		}
		.list_table td{
			padding: 8px;
			border: #d3d3d3 1px solid;
			font-family: 'Arial';
			font-size: 12px;
			line-height: 20px;
		}
		.blue-color{
			color: #1a4ea2;
		}
		.orange-color{
			color: #f36c31;
		}
		a{
			color: #f36c31;
		}
		.btn-primary {
			border-color: #2c6de9 !important;
			background-color: #5A8DEE !important;
			color: #fff!important;
		}
		.btn {
			display: inline-block;
			font-weight: 400;
			color: #727E8C;
			text-align: center;
			vertical-align: middle;
			user-select: none;
			background-color: transparent;
			border: 0 solid transparent;
			padding: 0.467rem 1.5rem;
			font-size: 1rem;
			line-height: 1.6rem;
			border-radius: 0.267rem;
			cursor: pointer;
			transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
		}
		.help-block{
			color:#ff4243 !important;
		}
		.card{
			box-shadow: -8px 12px 18px 0 rgb(25 42 70 / 13%);
			padding: 15px;
			width: 80%;
			margin: 0 auto;
			margin-top: 30px;
		}
		.card h3{
			font-size: 18px;
		}
		label{
			display: block;
			font-size: 16px;
		}
		textarea{
			font-family: 'Arial';
			padding: 10px;
		}
		@media (min-width: 320px) and (max-width: 767px) {
			.main-div{
				width:94%!important;
				margin: auto!important;
				font-size: 12px!important;
			}
		}

		.error {
			color: red;
		}



	</style>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head><!-- comment -->
<div class="card">
	<?php
	if ($error != "")
	{	
		Yii::app()->user->logout();
	?>
		<div class="error"><h2><?php echo $error;?></h2></div>
	<?php
	}
	else
	{
		if ($data['usr_name'] != '' && $data['usr_lname'] != '')
		{
			?>
			<p style="font-size:16px;"><img src="/images/img-2022/user.svg" width="14"> <?= $data['usr_name'] ?>&nbsp;<?= $data['usr_lname'] ?></p>
			<?php
		}
		if ($data['usr_mobile'] != '')
		{
			?>
			<p style="font-size:16px;"><img src="/images/call.png" width="14"> <?= $data['usr_mobile'] ?></p> 
			<?php
		}
		if ($data['usr_email'] != '')
		{
			?>
			<p style="font-size:16px;"><img src="/images/mail.png" width="14"> <?= $data['usr_email'] ?></p>
		<?php }
		?>
		<p style="font-size:16px;">Are you wish to delete your Gozo account ?  </p>
		<div>
			<?php
			$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'partnerDeactivateForm',
				'enableAjaxValidation'	 => true,
				'clientOptions'			 => array(
					'validateOnSubmit'	 => false,
					'afterValidate'		 => 'js:function(form,data,hasError){
											
											
											
                                        }'
				),
			));
			?>
			<div style="min-height:100px">
				<div class="form-group pt20">
					<?= $form->textAreaGroup($model, 'usr_deactivate_reason', array('widgetOptions' => array('htmlOptions' => array('style' => 'width : 100%; height : 100px;')))) ?>

				</div> 
				<div id="deactivate_reason" class="deactivate_reason"></div>
				<div style="margin-top: 15px;">
					<button type="submit" class="btn btn-primary deactivate" value="Submit" tabindex="2" >Continue to Delete</button>
				</div>
			</div>
			<?php $this->endWidget(); ?>
		</div>

		<?php
	}
	?>	
</div>


<script>

    $(document).ready(function ()
    {
        $('.deactivate_reason').hide();
    });


    $(".deactivate").click(function (e) {

        confirmDelete(e);
    });


    function confirmDelete(event)
    {
        var $form = $('#partnerDeactivateForm');
        var data = $form.serialize();
        var retVal = confirm("Do you want to deactivate the user?");
        if (retVal == false)
        {
            return false;
        } else
        {
            event.preventDefault();
            $.ajax({
                url: '/users/partnerDeactive',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function (response)
                {
                    if (response.success)
                    {
                        var msg = response.message;
                        if (response.hasOwnProperty("data") && response.data.userId)
                        {
                            var userId = response.data.userId;
                        }
                        window.location.href = "/users/deactiveV1?userId=" + userId + "&message=" + msg;
                        return true;
                    } else if (response.errors)
                    {
                        var errors = response.errors;
                        var txt = "";
                        $.each(errors, (key, value) =>
                        {
                            txt += value;
                        });
                        $('.deactivate_reason').text(txt);
                        $('.deactivate_reason').addClass('error');
                        $('.deactivate_reason').show();
                    }
                },
                error: function (xhr, ajaxOptions, thrownError)
                {
                    if (xhr.status == "403")
                    {
                        handleException(xhr, function () {
                        });
                    }
                }
            });

            return false;
        }
        // prevent default submit
        return false;
    }

</script>