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
<link type="text/css" href="tpl/css/reset.css" rel="stylesheet" media="screen" />
<link type="text/css" href="tpl/css/gs.css" rel="stylesheet" media="screen" />
<link type="text/css" href="tpl/css/styles.css" rel="stylesheet" media="screen" />

<!-- IE "fixes" -->
<!--[if gte IE 7]>
    <link type="text/css" href="tpl/css/styles_ie.css" rel="stylesheet" media="screen" />
<![endif]-->

<!--[if IE 7]>
    <link type="text/css" href="tpl/css/styles_ie7.css" rel="stylesheet" media="screen" />
<![endif]-->

<!-- JS files are loaded at the top of the page -->
<script src="tpl/js/jquery.js" type="text/javascript"></script>
<script src="tpl/js/jquery.tipsy.js" type="text/javascript"></script>

{literal}
<script type="text/javascript">
$(document).ready(function(){
    //
    $('#ask-cancel').click(function(e) {
        e.preventDefault();
        thisHref = 'exit.html';
        if(confirm('{/literal}{$la.confirm_exit}{literal}')) {
            window.location = thisHref;
        }
    });
    $('.container [title]').tipsy({gravity: 'w'});
});
</script>

<style type="text/css">
    div p {padding:10px;}
</style>
{/literal}

</head>

<body>
<div class="container container_14">

<div class="grid_14">
	<p></p>
</div>

<div class="grid_14">
    <div class="logo_header fleft" ><img src="tpl/images/logo.png" alt="AVE.cms" /></div>
    <div class="text_header fleft"><h1>{$la.install} {$version_setup}</h1></div>
    <div class="text_header_step fright"><h1>{$la.install_step} 2</h1></div>
</div>

<div class="grid_14">
	<p></p>
</div>

<div class="grid_14">
    <div id="bread">
    	<div class="br_blue_1"><span class="first">{$la.bread_lictexttitle}</span></div>
        <div class="br_blue_blue_1"></div>
        <div class="br_blue_2"><span class="second_b">{$la.bread_database_setting}</span></div>
        <div class="br_blue_grey_2"></div>
        <div class="br_grey_3"><span class="second_a2">{$la.bread_install_type}</span></div>
        <div class="br_grey_grey_3"></div>
        <div class="br_grey_4"><span class="second_a">{$la.bread_stepstatus}</span></div>
        <div class="br_grey_grey_4"></div>
        <div class="br_grey_5"><span class="second_a">{$la.bread_install_finish}</span></div>
    </div>
</div>

<div class="grid_14">
	<p></p>
</div>

<div class="grid_14">
	{if $warnnodb}
	<ul class="messages">
		<li class="highlight red">
		{$warnnodb}
		</li>
	</ul>
	{elseif $installed_q}
	<ul class="messages">
		<li class="highlight red">
		{$installed_q}
		</li>
	</ul>
	{else}
	<ul class="messages">
		<li class="highlight yellow">
		{$la.database_setting_desc}
		</li>
	</ul>
	{/if}
</div>

<div class="clear"></div>
	<form action="index.php" method="post" enctype="multipart/form-data" name="s" id="s">
<div class="grid_14">
	<p></p>
</div>

<div class="grid_4">
		<label for="dbhost" class="step2">{$la.dbserver}</label>
		<input name="dbhost" size="30" type="text" id="dbhost" value="{$smarty.request.dbhost|escape|stripslashes|default:'localhost'}" />
		<span><img class="tip_help" title="{$la.olh_host}" src="tpl/images/info.png" alt="" width="16" height="16" /></span>
</div>

<div class="grid_4">
		<label for="dbname" class="step2">{$la.dbname}</label>
		<input size="30" name="dbname" type="text" id="dbname" value="{$smarty.request.dbname|escape|stripslashes}" />
		<img class="tip_help" title="{$la.olh_name}" src="tpl/images/info.png" alt="" width="16" height="16" />
</div>

<div class="grid_4">
		<label for="dbprefix" class="step2">{$la.dbprefix}</label>
		<input name="dbprefix" size="30" type="text" id="dbprefix" value="{$smarty.request.dbprefix|escape|stripslashes|default:$dbpref}" />
		<img class="tip_help" title="{$la.olh_prf}" src="tpl/images/info.png" alt="" width="16" height="16" />
</div>

<div class="clear"></div>
<div class="grid_14">
	<p></p>
</div>
<div class="clear"></div>

<div class="grid_4">
		<label for="dbuser" class="step2">{$la.dbuser}</label>
		<input name="dbuser" size="30" type="text" id="dbuser" value="{$smarty.request.dbuser|escape|stripslashes|default:root}" />
		<img class="tip_help" title="{$la.olh_user}" src="tpl/images/info.png" alt="" width="16" height="16" />
</div>

<div class="grid_4">
		<label for="dbpass" class="step2">{$la.dbpass}</label>
		<input name="dbpass" size="30" type="text" id="dbpass" value="" />
		<img class="tip_help" title="{$la.olh_pass}" src="tpl/images/info.png" alt="" width="16" height="16" />
</div>

<div class="grid_4">

</div>

<div class="clear"></div>

<div class="grid_14">
	<p></p>
</div>

<div class="grid_14">
	<ul class="messages">
		<li class="highlight grey">
		{$la.database_setting_foot}
		</li>
	</ul>
</div>

<div class="grid_14">
		<div class="form_buttons" id="confirmBox">
			{if $installed_q}<button type="submit" class="buttons blue" formaction="index.php?clean_db=1">{$la.database_clean_and_save}<span></span></button>{/if}
			<button type="submit" name="Submit" class="buttons blue">{$la.database_setting_save}<span></span></button>
			<button id="ask-cancel" type="button" class="buttons gray">{$la.exit}<span></span></button>
		</div>
</div>
		<input name="force" type="hidden" id="force" value="{$smarty.request.force|escape|stripslashes}" />
		<input name="step" type="hidden" id="step" value="2" />
	</form>

</div>
</body>
</html>