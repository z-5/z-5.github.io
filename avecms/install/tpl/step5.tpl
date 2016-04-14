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
	
	<script type="text/javascript">
	{literal}
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
	{/literal}
    </script>
	
	<style type="text/css">
	{literal}
		div p {padding:10px;}
	{/literal}
	</style>

</head>
<body>
<div class="container container_14">

<div class="grid_14">
	<p></p>
</div>

<div class="grid_14">
    <div class="logo_header fleft" ><img src="tpl/images/logo.png" alt="AVE.cms" /></div>
    <div class="text_header fleft"><h1>{$la.install} {$version_setup}</h1></div>
    <div class="text_header_step fright"><h1>{$la.install_step} 5</h1></div>
</div>

<div class="grid_14">
	<p></p>
</div>

<div class="grid_14">
    <div id="bread">
    	<div class="br_blue_1"><span class="first">{$la.bread_lictexttitle}</span></div>
        <div class="br_blue_blue_1"></div>
        <div class="br_blue_2"><span class="second_b">{$la.bread_database_setting}</span></div>
        <div class="br_blue_blue_2"></div>
        <div class="br_blue_3"><span class="second_b2">{$la.bread_install_type}</span></div>
        <div class="br_blue_blue_3"></div>
        <div class="br_blue_4"><span class="second_b">{$la.bread_stepstatus}</span></div>
        <div class="br_blue_blue_4"></div>
        <div class="br_blue_5"><span class="second_b">{$la.bread_install_finish}</span></div>
    </div>
</div>

<div class="grid_14">
	<p></p>
</div>

<div class="grid_14">
	{$la.install_finish_body}
</div>

</div>
</body>
</html>