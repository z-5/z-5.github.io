<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

	<title>{#MAIN_PAGE_TITLE#} - {*#SUB_TITLE#*} ({$smarty.session.user_name|escape})</title>

	<meta name="robots" content="noindex, nofollow">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="generator" content="Notepad" >
	<meta name="Expires" content="Mon, 06 Jan 1990 00:00:01 GMT">

	<!-- CSS Files -->
	<link href="{$tpl_dir}/css/reset.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="{$tpl_dir}/css/main.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="{$tpl_dir}/css/data_table.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="{$tpl_dir}/css/jquery-ui_custom.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="{$tpl_dir}/css/color_{$smarty.const.DEFAULT_THEME_FOLDER_COLOR}.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="{$tpl_dir}/css/browser.css" rel="stylesheet" type="text/css" media="screen" />

	<!-- JS files -->
	{include file="../scripts.tpl"}

	<script src="{$tpl_dir}/js/main.js" type="text/javascript"></script>

	<!-- JS Scripts -->
    <script>
      var ave_path = "{$ABS_PATH}";
      var ave_theme = "{$smarty.const.DEFAULT_THEME_FOLDER}";
      var ave_admintpl = "{$tpl_dir}";
	  var path_upload = "{$smarty.request.pfad|escape}";
    </script>

	<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
	<script type="text/javascript" src="{$ABS_PATH}lib/scripts/uploader/plupload.full.js"></script>
	<script type="text/javascript" src="{$ABS_PATH}lib/scripts/uploader/i18n/{$smarty.session.admin_language}.js"></script>
	<script type="text/javascript" src="{$ABS_PATH}lib/scripts/uploader/jquery.plupload.queue.js"></script>

<script language="javascript">
{literal}
$(document).ready(function(){
	//===== File uploader =====//

	$("#uploader").pluploadQueue({
		runtimes : 'html5,flash',
		url : '../inc/upload.php?path_upload='+path_upload,
		max_file_size : '150mb',
		unique_names : true,
		filters : [
			{title : "Image files", extensions : "jpg,jpeg,jpe,gif,png"},
			{title : "Video files", extensions : "mp4,avi,mov,wmv,wmf"},
			{title : "Music files", extensions : "mp3"},
			{title : "Documents", extensions : "doc,xls,pdf"},
			{title : "Zip files", extensions : "zip,rar"}
		],
        // Flash settings
        flash_swf_url : '{/literal}}{literal}/lib/scripts/uploader/plupload.flash.swf',
	});

	// Client side form validation
	$('form').submit(function(e) {
        var uploader = $('#uploader').pluploadQueue();
        // Files in queue upload them first

            // When all files are uploaded submit form
            uploader.bind('StateChanged', function() {
                if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
                    $('form')[0].submit();
                }
            });
            uploader.start();

        return false;
    });

});
{/literal}
</script>

</head>

<body>

<!-- Wrapper -->
<div class="wrapper">
	<!-- Content -->
    <div class="content" id="contentPage">

		<form class="mainForm" action="browser.php?typ={$smarty.request.typ|escape}&action=upload2&pfad={$smarty.request.pfad|escape}" method="post" enctype="multipart/form-data" name="upform" id="upform" style="display:inline;">
		<input name="fromuploader" type="hidden" id="fromuploader" value="1" />

		<fieldset>
		<div class="widget">
		      <div class="head">
		        <h5>{#MAIN_MP_SELECT_FILES#}</h5>
		      </div>
		      <div id="uploader" style="position: relative;">
					<p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
			  </div>
		</div>
		</fieldset>

		{if $smarty.request.typ=='bild'}
		<!--
		<div class="rowElem">
		<input name="w" type="text" value="" size="3" style="width: 50px;" />&nbsp;{#MAIN_MP_IMAGE_WIDTH#}&nbsp;&nbsp;&nbsp;
		<input name="h" type="text" value="" size="3" style="width: 50px;"  />&nbsp;{#MAIN_MP_IMAGE_HEIGHT#}
		</div>
		-->
		{/if}

		<div class="rowElem">
			<input name="button" type="submit" class="basicBtn" value="{#MAIN_BUTTON_UPLOAD#}" />
		</div>

		</form>

    </div>
    <div class="fix"></div>
</div>

<!-- Footer -->
<div id="footer">
	<div class="wrapper">
    	<span>{$smarty.const.APP_INFO} | {$smarty.const.APP_NAME} {$smarty.const.APP_VERSION} rev. {$smarty.const.BILD_VERSION}</span>
    </div>
</div>
</body>
</html>