<script type="text/javascript" language="JavaScript">
$(document).ready(function(){ldelim}

	{if check_permission('template_new')}
		$(".AddTempl").click( function(e) {ldelim}
			e.preventDefault();
			var user_group = $('#add_templ #TempName').fieldValue();
			var title = '{#TEMPLATES_TITLE_NEW#}';
			var text = '{#TEMPLATES_TIP3#}';
			if (user_group == ""){ldelim}
				jAlert(text,title);
			{rdelim}else{ldelim}
				$.alerts._overlay('show');
				$("#add_templ").submit();
			{rdelim}
		{rdelim});
	{/if}

	$(".CopyTempl").click( function(e) {ldelim}
		e.preventDefault();
		var href = $(this).attr('href');
		var title = '{#TEMPLATES_COPY#}';
		var text = '{#TEMPLATES_TIP3#}';
		jPrompt(text, '', title, function(b){ldelim}
					if (b){ldelim}
						$.alerts._overlay('show');
        				window.location = href + '&template_title=' + b;
					{rdelim}
				{rdelim}
			);
	{rdelim});

{rdelim});
</script>

<div class="title"><h5>{#TEMPLATES_SUB_TITLE#}</h5></div>

<div class="widget" style="margin-top: 0px;">
    <div class="body">
		{#TEMPLATES_TIP1#}
    </div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li>{#TEMPLATES_SUB_TITLE#}</li>
	    </ul>
	</div>
</div>



<div class="widget first">
	<ul class="tabs">
	    <li class="activeTab"><a href="#tab1">{#TEMPLATES_ALL#}</a></li>
	    <li class=""><a href="#tab2">{#TEMPLATES_TITLE_NEW#}</a></li>
	    <li class=""><a href="#tab3">{#TEMPLATES_CSS_FILES#}</a></li>
	    <li class=""><a href="#tab4">{#TEMPLATES_JS_FILES#}</a></li>
	    <li class=""><a href="#tab5">{#TEMPLATES_FILES#}</a></li>
	</ul>

	<div class="tab_container">
		<div id="tab1" class="tab_content" style="display: block;">

			<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
            	<thead>
                	<tr>
                        <td width="40">{#TEMPLATES_ID#}</td>
                        <td>{#TEMPLATES_NAME#}</td>
                        <td width="200">{#TEMPLATES_AUTHOR#}</td>
						<td width="150">{#TEMPLATES_DATE#}</td>
						<td width="50" colspan="3">{#TEMPLATES_ACTION#}</td>
                    </tr>
                </thead>
				<tbody>
	{foreach from=$items item=tpl}
		<tr>
			<td width="10" align="center">{$tpl->Id}</td>
			<td><strong>{if check_permission('template_edit')}<a title="{#TEMPLATES_EDIT#}" href="index.php?do=templates&action=edit&Id={$tpl->Id}&cp={$sess}" class="topDir link">{$tpl->template_title|escape}</a>{else}{$tpl->template_title|escape}{/if}</strong></td>
			<td align="center">{$tpl->template_author}</td>
			<td align="center"><span class="date_text dgrey">{$tpl->template_created|date_format:$TIME_FORMAT|pretty_date}</span></td>
			<td nowrap="nowrap" width="1%" align="center">
				{if check_permission('template_edit')}
					<a title="{#TEMPLATES_EDIT#}" href="index.php?do=templates&action=edit&Id={$tpl->Id}&cp={$sess}" class="topDir icon_sprite ico_edit"></a>
				{else}
					<a title="{#TEMPLATES_NO_CHANGE#}" href="javascript:void(0);" class="topleftDir icon_sprite ico_edit_no"></a>
				{/if}
			</td>
			<td nowrap="nowrap" width="1%" align="center">
				{if check_permission('template_multi')}
					<a title="{#TEMPLATES_COPY#}" href="index.php?do=templates&action=multi&sub=save&Id={$tpl->Id}&cp={$sess}" class="topleftDir CopyTempl icon_sprite ico_copy"></a>
				{else}
					<a title="{#TEMPLATES_NO_COPY#}" href="javascript:void(0);" class="topleftDir icon_sprite ico_copy_no"></a>
				{/if}
			</td>
			<td nowrap="nowrap" width="1%" align="center">
				{if $tpl->Id == 1}
				   <span title="" class="topleftDir icon_sprite ico_delete_no"></span>
				{else}
					{if $tpl->can_deleted==1}
						{if check_permission('template_del')}
							<a title="{#TEMPLATES_DELETE#}" dir="{#TEMPLATES_DELETE#}" name="{#TEMPLATES_DELETE_CONF#}" href="index.php?do=templates&action=delete&Id={$tpl->Id}&cp={$sess}" class="topleftDir ConfirmDelete icon_sprite ico_delete"></a>
						{else}
							<a title="{#TEMPLATES_NO_DELETE3#}" href="javascript:void(0);" class="topleftDir icon_sprite ico_delete_no"></a>
						{/if}
					{else}
						<a title="{#TEMPLATES_NO_DELETE2#}" href="javascript:void(0);" class="topleftDir icon_sprite ico_delete_no"></a>
					{/if}
				{/if}
			</td>
		</tr>
	{/foreach}
				</tbody>
			</table>
		</div>

		<div id="tab2" class="tab_content" style="display:none;">
		{if check_permission('template_new')}
			<form id="add_templ" method="post" action="index.php?do=templates&action=new&cp={$sess}" class="mainForm">
			<div class="rowElem">
				<label>{#TEMPLATES_NAME3#}</label>
				<div class="formRight"><input placeholder="{#TEMPLATES_NAME#}" name="TempName" type="text" id="TempName" value="{$g_name|escape}" style="width: 400px">
				&nbsp;<input type="button" class="basicBtn AddTempl" value="{#TEMPLATES_BUTTON_ADD#}" />
				</div>
				<div class="fix"></div>
			</div>
			</form>
		{/if}
		</div>

		<div id="tab3" class="tab_content" style="display:none;">
		<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<tbody>
			{if check_permission('template_edit')}
			{if $css_files}
			{foreach from=$css_files item=css_files}
			<tr>
				<td>
					<a title="{#TEMPLATES_EDIT_FILE#}" href="index.php?do=templates&action=edit_css&sub=edit&name_file={$css_files}&cp={$sess}" class="toprightDir link"><strong>{$css_files}</strong></a>
				</td>
				<td nowrap="nowrap" width="1%" align="center">
					<a title="{#TEMPLATES_EDIT_FILE#}" href="index.php?do=templates&action=edit_css&sub=edit&name_file={$css_files}&cp={$sess}" class="topDir icon_sprite ico_edit"></a>
				</td>

				<td nowrap="nowrap" width="1%" align="center">
					<a title="{#TEMPLATES_DEL_FILE#}" dir="{#TEMPLATES_DEL_FILE#}" name="{#TEMPLATES_DELETE_CONF#}" href="index.php?do=templates&action=edit_css&sub=delete&name_file={$css_files}&cp={$sess}" class="topleftDir ConfirmDelete icon_sprite ico_delete"></a>
				</td>
			</tr>
			{/foreach}
			{else}
			<tr>
				<td colspan="3">
					<ul class="messages">
						<li class="highlight yellow">{#TEMPLATES_NO_ITEMS#}</li>
					</ul>
				</td>
			</tr>
			{/if}
			{/if}
		</tbody>
		</table>
		</div>

		<div id="tab4" class="tab_content" style="display:none;">
		<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
			{if check_permission('template_edit')}
			{if $js_files}
			{foreach from=$js_files item=js_files}
			<tr>
				<td>
					<a title="{#TEMPLATES_EDIT_FILE#}" href="index.php?do=templates&action=edit_js&sub=edit&name_file={$js_files}&cp={$sess}" class="toprightDir link"><strong>{$js_files}</strong></a>
				</td>

				<td nowrap="nowrap" width="1%" align="center">
					<a title="{#TEMPLATES_EDIT_FILE#}" href="index.php?do=templates&action=edit_js&sub=edit&name_file={$js_files}&cp={$sess}" class="topDir icon_sprite ico_edit"></a>
				</td>

				<td nowrap="nowrap" width="1%" align="center">
					<a title="{#TEMPLATES_DEL_FILE#}" dir="{#TEMPLATES_DEL_FILE#}" name="{#TEMPLATES_DELETE_CONF#}" href="index.php?do=templates&action=edit_js&sub=delete&name_file={$js_files}&cp={$sess}" class="topleftDir ConfirmDelete icon_sprite ico_delete"></a>
				</td>
			</tr>
			{/foreach}
			{else}
			<tr>
				<td colspan="3">
					<ul class="messages">
						<li class="highlight yellow">{#TEMPLATES_NO_ITEMS#}</li>
					</ul>
				</td>
			</tr>
			{/if}
			{/if}
		</table>
		</div>

		<div id="tab5" class="tab_content" style="display:none;">
	<link rel="stylesheet" href="{$ABS_PATH}admin/redactor/elfinder/css/elfinder.full.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="{$ABS_PATH}admin/redactor/elfinder/css/theme.css" type="text/css" media="screen" charset="utf-8" />
	<script src="{$ABS_PATH}admin/redactor/elfinder/js/elfinder.full.js" type="text/javascript" charset="utf-8"></script>
	<script src="{$ABS_PATH}admin/redactor/elfinder/js/i18n/elfinder.ru.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="{$tpl_dir}/js/filemanager_template.js"></script>

	<div id="finder">finder</div>
		</div>


	</div>

<div class="fix"></div>
</div>



	{if $page_nav}
		<div class="pagination">
		<ul class="pages">
			{$page_nav}
		</ul>
		</div>
	{/if}