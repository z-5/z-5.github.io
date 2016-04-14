<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="ru-RU">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

	<title>{$version_setup}</title>

		<meta name="robots" content="noindex, nofollow" />
		<meta http-equiv="pragma" content="no-cache" />
		<meta name="generator" content="" />
		<meta name="Expires" content="Mon, 06 Jan 1990 00:00:01 GMT" />

	<!-- CSS files -->
	<link type="text/css" href="/install/tpl/css/reset.css" rel="stylesheet" media="screen" />
	<link type="text/css" href="/install/tpl/css/gs.css" rel="stylesheet" media="screen" />
    <link type="text/css" href="/install/tpl/css/styles.css" rel="stylesheet" media="screen" />

	<!-- IE "fixes" -->
	<!--[if gte IE 7]>
		<link type="text/css" href="/install/tpl/css/styles_ie.css" rel="stylesheet" media="screen" />
	<![endif]-->

	<!--[if IE 7]>
		<link type="text/css" href="/install/tpl/css/styles_ie7.css" rel="stylesheet" media="screen" />
	<![endif]-->

	<!-- JS files are loaded at the top of the page -->
	<script src="/install/tpl/js/jquery.js" type="text/javascript"></script>

	<script type="text/javascript">
	{literal}
    $(document).ready(function(){
		//
        $("input:checkbox[name=force]").change(function(){
            if($(this).is(":checked")){  
              $(':button[name=Submit]').attr('disabled', false).removeClass("disabled").addClass("blue");
			  $('#warning').show();
            } else {
              $(':button[name=Submit]').attr('disabled', true).removeClass("blue").addClass("disabled");
			  $('#warning').hide();
            }
        });
		//
		$('#ask-cancel').click(function(e) {
			e.preventDefault();
			thisHref = '/install/exit.html';
			if(confirm('{/literal}{$la.confirm_exit}{literal}')) {
				window.location = thisHref;
			}
		});
    });
	{/literal}
    </script>
	
	<style type="text/css">
	{literal}
		div p {padding:10px;}
	{/literal}
	</style>
</head>

<body>

<div id="container">

<div class="container container_14">

<div class="grid_14">
	<p></p>
</div>

<div class="grid_14">
    <div class="logo_header fleft"><img src="images/logo.png" alt="AVE.cms" /></div>
    <div class="text_header fleft"><h1>{$la.install} {$version_setup}</h1></div>
    <div class="text_header_step fright"><h1>{$error_header}</h1></div>
</div>

<div class="grid_14">
	<p></p>
</div>

	{foreach from=$error_is_required item="inc"}
	<div class="grid_14">
	<ul class="messages">
		<li class="highlight red">
		{$inc}
		</li>
	</ul>
	</div>
	<div class="grid_14">
		<p></p>
	</div>
	{/foreach}
	{if $config_isnt_writeable == 1}
	<div class="grid_14">
	<ul class="messages">
		<li class="highlight red">
		{$la.config_isnt_writeable}
		</li>
	</ul>
	</div>
	{/if}

	<div class="grid_14">
	<ul class="messages">
		<li class="highlight yellow">
		{$la.secondchance}
		</li>
	</ul>
	</div>
	<div class="grid_14">
		<p></p>
	</div>
	<div id="warning" class="grid_14" style="display:none;">
	<ul class="messages">
		<li class="highlight red">
		{$la.warning_force}
		</li>
	</ul>
	</div>
	

	<form action="index.php" method="post" enctype="multipart/form-data" name="s" id="s" onSubmit="return defaultagree(this)">
<div class="grid_14">
		<div class="form_checkbox">
			<input type="checkbox" name="force" type="checkbox" id="force" value="1" />&nbsp;&nbsp;<label for="force"><span>{$la.force} {if $config_isnt_writeable == 1}{$la.force_impossibly}{/if}</span></label>
		</div>
		{if $config_isnt_writeable != 1}
			<input name="force" type="hidden" id="force" value="{$smarty.request.force|escape|stripslashes}" />
		{/if}
		<input name="step" type="hidden" id="step" value="{$smarty.request.step|default:'1'}" />
		<div class="form_buttons" id="confirmBox">
			<button type="submit" name="Submit" class="buttons disabled" disabled="disabled">{$la.error_reload}<span></span></button>
			<button id="ask-cancel" type="button" class="buttons gray">{$la.exit}<span></span></button>
		</div>
</div>
	</form>
</div>
<!-- /Content -->
</body>
</html>