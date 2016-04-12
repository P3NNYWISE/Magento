jQuery(document).ready(function() {
	var popupWindow = null;
	var width = 650;
	var height = 350;
	var left = parseInt((jQuery(window).width() - width) / 2);
	var top = parseInt((jQuery(window).height() - height) / 2);
	var params = [
		'resizable=yes',
		'scrollbars=no',
		'toolbar=no',
		'menubar=no',
		'location=no',
		'directories=no',
		'status=yes',
		'width='+ width,
		'height='+ height,
		'left='+ left,
		'top='+ top
	];
	if(popupWindow) {
		popupWindow.close();
	}
	jQuery(document).on('click', '.a-fbconnect', function() {
		var $link = jQuery(this);
		if($link.attr('href')) {
			popupWindow = window.open($link.attr('href'), 'login_popup', params.join(','));
			popupWindow.focus();
			var loaderText = 'Loading...';
			var html = '<!DOCTYPE html><html style="height: 100%;"><head><meta name="viewport" content="width=device-width, initial-scale=1"><title>'+ loaderText +'</title></head>';
			html += '<body style="height: 100%; margin: 0; padding: 0;">';
			html += '<div style="text-align: center; height: 100%;"><div id="loader" style="top: 50%; position: relative; margin-top: -50px; color: #646464; height:25px; font-size: 25px; text-align: center; font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;">'+ loaderText +'</div></div>';
			html += '</body></html>';
			jQuery(popupWindow.document).contents().html(html);
		}
		return false;
	});
});