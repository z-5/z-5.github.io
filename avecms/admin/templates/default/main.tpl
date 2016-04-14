<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

	<title>{#MAIN_PAGE_TITLE#} - {*#SUB_TITLE#*} ({$smarty.session.user_name|escape})</title>

	<meta name="robots" content="noindex, nofollow">
	<meta http-equiv="pragma" content="no-cache">
	<meta name="generator" content="Notepad" >
	<meta name="Expires" content="Mon, 06 Jan 1990 00:00:01 GMT">

    <!-- Favicon -->
    {if $admin_favicon}
    <link rel="icon" type="image/vnd.microsoft.icon" href="/admin.favicon.ico">
    <link rel="SHORTCUT ICON" href="/admin.favicon.ico">
    {/if}

	<!-- CSS Files -->
	<link href="{$tpl_dir}/css/reset.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="{$tpl_dir}/css/main.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="{$tpl_dir}/css/data_table.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="{$tpl_dir}/css/jquery-ui_custom.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="{$tpl_dir}/css/jquery.fancybox.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="{$tpl_dir}/css/color_{$smarty.const.DEFAULT_THEME_FOLDER_COLOR}.css" rel="stylesheet" type="text/css" media="screen" />

	<!-- JS files -->
	{include file='scripts.tpl'}
	
	<script type="text/javascript" src="{$ABS_PATH}admin/lang/{$smarty.session.admin_language}/scripts.js"></script>
	<script src="{$tpl_dir}/js/main.js" type="text/javascript"></script>

	<!-- JS Scripts -->
    <script>
      var ave_path = "{$ABS_PATH}";
      var ave_theme = "{$smarty.const.DEFAULT_THEME_FOLDER}";
      var ave_admintpl = "{$tpl_dir}";
    </script>

	<script type="text/javascript" language="JavaScript">
	$(document).ready(function(){ldelim}


    {literal}

	var admin = {
		Toggle : function(){
			$.each(["LeftMenu"], function(key, value) {
				//Считываем cookie
				var toggle = $.cookie(value);
				//Проверяем cookie
				if (toggle == 'hidden') {
					$(".leftNav").addClass("hidden");
					$(".dd_page").css("display","");
				} else {
	                $("#leftNav_show span").addClass("close");
					$(".dd_page").css("display","none");
				}

				$("[id='toggle-"+this+"']").click(function() {
					if ($(".leftNav").hasClass('hidden')) {
						$(".dd_page").css("display","none");
						$(".leftNav").removeClass('hidden').addClass('visible');
	                    $("#leftNav_show span").addClass("close");
						$.cookie(value, 'visible');
					} else {
						$(".dd_page").css("display","");
						$(".leftNav").removeClass('visible').addClass('hidden');
	                    $("#leftNav_show span").removeClass("close");
						$.cookie(value, 'hidden');
					}
				});
			});
	  	}
	}

	if($("[id^='toggle']").length){admin.Toggle();}

    {/literal}

		{if $smarty.const.ADMIN_MENU}
		$("#menu").sticky({ldelim}topSpacing:56{rdelim});
		{/if}

		{if check_permission('group_new')}
		$(".ulAddGroup").click( function(e) {ldelim}
			e.preventDefault();
			var title = '{#MAIN_ADD_NEW_GROUP#}';
			var text = '{#MAIN_ADD_NEW_GROUP_NAME#}';
			jPrompt(text, '', title, function(b){ldelim}
						if (b){ldelim}
	                        $.alerts._overlay('hide');
	                        $.alerts._overlay('show');
							window.location = ave_path+'admin/index.php?do=groups&action=new&cp={$sess}'+ '&user_group_name=' + b;
						{rdelim}else{ldelim}
							$.jGrowl("{#MAIN_NO_ADD_GROUP#}", {ldelim}theme: 'error'{rdelim});
						{rdelim}
					{rdelim}
				);
		{rdelim});
		{/if}

		{if check_permission('user_new')}
		$(".ulAddUser").click( function(e) {ldelim}
			e.preventDefault();
			var title = '{#MAIN_ADD_NEW_USER#}';
			var text = '{#MAIN_ADD_NEW_USER_NAME#}';
			jPrompt(text, '', title, function(b){ldelim}
						if (b){ldelim}
	                        $.alerts._overlay('hide');
	                        $.alerts._overlay('show');
							window.location = ave_path+'admin/index.php?do=user&action=new&cp={$sess}'+ '&user_name=' + b;
						{rdelim}else{ldelim}
							$.jGrowl("{#MAIN_NO_ADD_USER#}", {ldelim}theme: 'error'{rdelim});
						{rdelim}
					{rdelim}
				);
		{rdelim});
		{/if}

		{if check_permission('navigation_new')}
		$(".ulAddNav").click( function(e) {ldelim}
			e.preventDefault();
			var title = '{#MAIN_ADD_NEW_NAV#}';
			var text = '{#MAIN_ADD_NEW_NAV_NAME#}';
			jPrompt(text, '', title, function(b){ldelim}
						if (b){ldelim}
	                        $.alerts._overlay('hide');
	                        $.alerts._overlay('show');
							window.location = ave_path+'admin/index.php?do=navigation&action=new&cp={$sess}'+ '&NaviName=' + b;
						{rdelim}else{ldelim}
							$.jGrowl("{#MAIN_NO_ADD_NAV#}", {ldelim}theme: 'error'{rdelim});
						{rdelim}
					{rdelim}
				);
		{rdelim});
		{/if}

		{if check_permission('template_new')}
		$(".ulAddTempl").click( function(e) {ldelim}
			e.preventDefault();
			var title = '{#MAIN_ADD_NEW_TEMPL#}';
			var text = '{#MAIN_ADD_NEW_TEMPL_NAME#}';
			jPrompt(text, '', title, function(b){ldelim}
						if (b){ldelim}
	                        $.alerts._overlay('hide');
	                        $.alerts._overlay('show');
							window.location = ave_path+'admin/index.php?do=templates&action=new&cp={$sess}'+ '&TempName=' + b;
						{rdelim}else{ldelim}
							$.jGrowl("{#MAIN_NO_ADD_TEMPL#}", {ldelim}theme: 'error'{rdelim});
						{rdelim}
					{rdelim}
				);
		{rdelim});
		{/if}

		{if check_permission('request_new')}
		$(".ulAddRequest").click( function(e) {ldelim}
			e.preventDefault();
			var title = '{#MAIN_ADD_NEW_REQUEST#}';
			var text = '{#MAIN_ADD_NEW_REQUEST_NAME#}';
			jPrompt(text, '', title, function(b){ldelim}
						if (b){ldelim}
	                        $.alerts._overlay('hide');
	                        $.alerts._overlay('show');
							window.location = ave_path+'admin/index.php?do=request&action=new&cp={$sess}'+ '&request_title_new=' + b;
						{rdelim}else{ldelim}
							$.jGrowl("{#MAIN_NO_ADD_QUERY#}", {ldelim}theme: 'error'{rdelim});
						{rdelim}
					{rdelim}
				);
		{rdelim});
		{/if}

		{if check_permission('rubric_new')}
		$(".ulAddRub").click( function(e) {ldelim}
			e.preventDefault();
			var title = '{#MAIN_ADD_NEW_RUB#}';
			var text = '{#MAIN_ADD_NEW_RUB_NAME#}';
			jPrompt(text, '', title, function(b){ldelim}
						if (b){ldelim}
	                        $.alerts._overlay('hide');
	                        $.alerts._overlay('show');
							window.location = ave_path+'admin/index.php?do=rubs&action=new&cp={$sess}'+ '&rubric_title=' + b;
						{rdelim}else{ldelim}
							$.jGrowl("{#MAIN_NO_ADD_RUB#}", {ldelim}theme: 'error'{rdelim});
						{rdelim}
					{rdelim}
				);
		{rdelim});
		{/if}

		{if check_permission('sysblock')}
		$(".ulAddBlock").click( function(e) {ldelim}
			e.preventDefault();
			var title = '{#MAIN_ADD_NEW_BLOCK#}';
			var text = '{#MAIN_ADD_NEW_BLOCK_NAME#}';
			jPrompt(text, '', title, function(b){ldelim}
						if (b){ldelim}
	                        $.alerts._overlay('hide');
	                        $.alerts._overlay('show');
							window.location = ave_path+'admin/index.php?do=sysblocks&action=new&cp={$sess}'+ '&sysblock_name=' + b;
						{rdelim}else{ldelim}
							$.jGrowl("{#MAIN_NO_ADD_BLOCK#}", {ldelim}theme: 'error'{rdelim});
						{rdelim}
					{rdelim}
				);
		{rdelim});
		{/if}

	{rdelim});
	</script>

</head>

<body>

<div id="leftNav_show">
    <a href="javascript:void(0);" id="toggle-LeftMenu"><span class="rightDir {if $smarty.cookies.LeftMenu != "hidden"}close{/if}" title="Показать/Скрыть меню"></span></a>
</div>


<!-- Top Menu -->
<div id="topNav">
    <div class="fixed">
        <div class="wrapper">
			<div class="welcome">
            	{if $user_avatar}
            		<img src="{$user_avatar}" class="avatar" alt="{$smarty.session.user_name|escape}" />
				{else}
					<img src="{$tpl_dir}/images/userPic.png" class="avatar" alt="" />
            	{/if}
            	<span>{#MAIN_USER_ONLINE#} <strong>{$smarty.session.user_name|escape}</strong></span>
			</div>
			<div class="userNav">
                <ul>

                    <li class="dropdown"><a title=""><img src="{$tpl_dir}/images/icons/add.png" alt="" /><span>Добавить</span></a>
                        <ul>
							 {if check_permission('documents')}<li><a onclick="cp_pop('index.php?do=docs&action=add_new&pop=1&cp={$sess}','750','500','1')" href="javascript:void(0);">Документ</a></li>{/if}
							 {if check_permission('rubric_new')}<li><a class="ulAddRub" href="index.php?do=rubs&action=new&cp={$sess}">Рубрику</a></li>{/if}
							 {if check_permission('request_new')}<li><a class="ulAddRequest" href="index.php?do=request&action=new&cp={$sess}">Запрос</a></li>{/if}
							 {if check_permission('sysblocks')}<li><a class="ulAddBlock" href="index.php?do=sysblocks&action=new&cp={$sess}">Системный блок</a></li>{/if}
							 {if check_permission('template_new')}<li><a class="ulAddTempl" href="index.php?do=templates&action=new&cp={$sess}">Шаблон</a></li>{/if}
							 {if check_permission('navigation_new')}<li><a class="ulAddNav" href="index.php?do=navigation&action=new&cp={$sess}">Навигацию</a></li>{/if}
							 {if check_permission('user_new')}<li><a class="ulAddUser" href="index.php?do=user&action=new&cp={$sess}">Пользователя</a></li>{/if}
							 {if check_permission('group_new')}<li><a class="ulAddGroup" href="index.php?do=groups&action=new&cp={$sess}">Группу</a></li>{/if}
                        </ul>
					</li>

                    <li class="dropdown dd_page {if $smarty.cookies.LeftMenu == "visible"}hidden{/if}"><a title=""><img src="{$tpl_dir}/images/icons/tasks.png" alt="" /><span>Разделы</span></a>
                        <ul class="menu_page">
							 {$navi_top}
                        </ul>
					</li>

                    <!-- <li><img src="{$tpl_dir}/images/icons/messages.png" alt="" /><span>Messages</span><span class="numberTop">8</span></li> -->
                    {if check_permission('modules')}
					{if $modules}
					<li class="dropdown"><a title=""><img src="{$tpl_dir}/images/icons/subInbox.png" alt="" /><span>{#MAIN_LINK_MODULES_H#}</span></a>
                        <ul>
						{if $modules && check_permission('modules')}
								{foreach from=$modules item=modul}
										<li><a href="index.php?do=modules&action=modedit&mod={$modul->ModuleSysName}&moduleaction=1&cp={$sess}">{$modul->ModuleName}</a></li>
								{/foreach}
						{/if}
                        </ul>
                    </li>
					{/if}
					{/if}
                    <li class="dropdown"><a title=""><img src="{$tpl_dir}/images/icons/settings.png" alt="" /><span>{#MAIN_LINK_SETTINGS_H#}</span></a>
                        <ul>
							 {if check_permission('gen_settings')}<li><a href="index.php?do=settings&cp={$sess}">{#MAIN_SETTINGS_EDIT_1#}</a></li>
							 <li><a href="index.php?do=settings&sub=case&cp={$sess}">{#MAIN_SETTINGS_EDIT_2#}</a></li>
							 <li><a href="index.php?do=settings&sub=countries&cp={$sess}">{#MAIN_SETTINGS_EDIT_3#}</a></li>{/if}
                             {if check_permission('dbactions')}<li><a href="index.php?do=dbsettings&action=dump_top&cp={$sess}">{#MAIN_SETTINGS_EDIT_4#}</a></li>{/if}
                        </ul>
					</li>
					{if check_permission('session_clear')}<li><a href="javascript:void(0);" class="clearCache" title="{#MAIN_STAT_CLEAR_CACHE#}"><img src="{$tpl_dir}/images/icons/subTrash.png" alt="" /><span>{#MAIN_STAT_CLEAR_CACHE#}</span></a></li>{/if}
                    {*<li><a href="#" title="{#MAIN_LOGIN_HELP#}"><img src="{$tpl_dir}/images/icons/help.png" alt="" /><span>{#MAIN_LOGIN_HELP#}</span></a></li>*}
					{if $login_menu}
					<li class="dropdown"><a title=""><img src="{$tpl_dir}/images/icons/preview.png" alt="" /><span>{#MAIN_LINK_SITE#}</span></a>
					{else}
					<li><a href="../" title="{#MAIN_LINK_SITE#}" target="_blank"><img src="{$tpl_dir}/images/icons/preview.png" alt="" /><span>{#MAIN_LINK_SITE#}</span></a>
					{/if}
						{if $login_menu}
						<ul>
							 <li><a href="../index.php?module=login&action=wys_adm&sub=on" target="_blank">{#MAIN_LINK_SITE_ON#}</a></li>
							 <li><a href="../index.php?module=login&action=wys_adm&sub=off" target="_blank">{#MAIN_LINK_SITE_OFF#}</a></li>
                        </ul>
						{/if}
					</li>
                    <li><a href="admin.php?do=logout" class="ConfirmLogOut" title="{#MAIN_BUTTON_LOGOUT#}"><img src="{$tpl_dir}/images/icons/logout.png" alt="" /><span>{#MAIN_BUTTON_LOGOUT#}</span></a></li>
                </ul>
            </div>
            <div class="fix"></div>
        </div>
    </div>
</div>

<!-- Header -->
<div id="header" class="wrapper">
    <!-- <div class="logo"><a href="index.php" class="box"></a></div> -->
    <div class="fix"></div>
</div>

<!-- Wrapper -->
<div class="wrapper">

	<!-- Left navigation -->
    <div class="leftNav {if $smarty.cookies.LeftMenu == "hidden"}hidden{/if}">
		{*<div class="logo"><a href="index.php" class="box"></a></div>*}

		<ul id="menu">
			<li><a href="index.php" {if $smarty.request.do == ''}class="active collapse-close"{/if}><span>{#MAIN_LINK_HOME#}</span></a></li>
			{$navi}
		</ul>
    </div>

	<!-- Content -->
    <div class="content" id="contentPage">
		{$content}
    </div>

    <div class="fix"></div>
</div>



<!-- Footer -->
<div id="footer">
	<div class="wrapper">
    	<span>{$smarty.const.APP_INFO} | {$smarty.const.APP_NAME} v{$smarty.const.APP_VERSION}</span>
    </div>
</div>

</body>
</html>