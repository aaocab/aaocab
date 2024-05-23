<div id="notification-success" class="notification-fixed bg-green-dark">
	<div class="notification-icon">
		<em><i class="fa fa-bell"></i></em>
		<span id="noti_success_bell_msg">Tap to close...</span>
		<a href="#"><i class="fa fa-times-circle"></i></a>
	</div>
	<h1 id="noti_success_title"></h1>
	<p id="noti_success_content"></p>
</div>

<div id="notification-error" class="notification-fixed bg-red-dark" style="z-index:99999">
	<div class="notification-icon">
		<em><i class="fa fa-bell"></i></em>
		<span id="noti_error_bell_msg">Tap to close...</span>
		<a href="#"><i class="fa fa-times-circle"></i></a>
	</div>
	<h1 id="noti_error_title mb3"></h1>
	<p id="noti_error_content" style="white-space: pre-wrap;"></p>
</div>
<div id="notification-info" class="notification-fixed bg-blue-dark">
        <div class="notification-icon">
            <em><i class="fa fa-bell"></i></em>
            <span id="noti_info_bell_msg">Tap to close...</span>
            <a href="#"><i class="fa fa-times-circle"></i></a>
        </div>
        <h1 id="noti_info_title"></h1>
        <p id="noti_info_content"></p>
    </div> 
<a data-notification="notification-error" href="#" id="notify_error" style="display:none;">Error</a> 
<a data-notification="notification-success" href="#" id="notify_success" style="display:none;">Success</a>  
<a data-notification="notification-info" href="#" id="notify_info" style="display:none;">Info</a> 