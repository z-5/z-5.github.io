<script type="text/javascript" language="JavaScript">
$(document).ready(function(){ldelim}
	{if check_permission('rubric_new')}
		$(".AddRub").click( function(e) {ldelim}
			e.preventDefault();
			var user_group = $('#add_rub #rubric_title').fieldValue();
			var title = '{#RUBRIK_NEW#}';
			var text = '{#RUBRIK_ENTER_NAME#}';
			if (user_group == ""){ldelim}
				jAlert(text,title);
			{rdelim}else{ldelim}
				$.alerts._overlay('show');
				$("#add_rub").submit();
			{rdelim}
		{rdelim});
	{/if}

	$(".CopyRub").click( function(e) {ldelim}
		e.preventDefault();
		var href = $(this).attr('href');
		var title = '{#REQUEST_COPY#}';
		var text = '{#REQUEST_PLEASE_NAME#}';
		jPrompt(text, '', title, function(b){ldelim}
					if (b){ldelim}
						$.alerts._overlay('show');
        				window.location = href + '&cname=' + b;
					{rdelim}
				{rdelim}
			);
	{rdelim});

{rdelim});
</script>

<div class="title"><h5>{#RUBRIK_SUB_TITLE#}</h5></div>

<div class="widget" style="margin-top: 0px;">
    <div class="body">
		{#RUBRIK_TIP#}
    </div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li>{#RUBRIK_SUB_TITLE#}</li>
	    </ul>
	</div>
</div>



<div class="widget first">
	<ul class="tabs">
	    <li class="activeTab"><a href="#tab1">{#RUBRIK_ALL#}</a></li>
	    <li class=""><a href="#tab2">{#RUBRIK_NEW#}</a></li>
	</ul>

		<div class="tab_container">
			<div id="tab1" class="tab_content" style="display: block;">
<div class="body">
<strong>{#RUBRIK_FORMAT#}</strong><br />
<strong>%d-%m-%Y</strong> - {#RUBRIK_FORMAT_TIME#}<br />
<strong>%id</strong> - {#RUBRIK_FORMAT_ID#}
</div>
<form class="mainForm" method="post" action="index.php?do=rubs&cp={$sess}&sub=quicksave{if $smarty.request.page!=''}&page={$smarty.request.page|escape}{/if}">
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
	<col width="20">
	<col width="60">
	<col>
	<col width="200">
	<col width="200">
	<col width="140">
	<col width="50">
	<col width="20">
	<col width="20">
	<col width="20">
	<col width="20">
	<thead>
	<tr>
		<td>{#RUBRIK_ID#}</td>
		<td>{#RUBRIK_POSITION#}</td>
		<td>{#RUBRIK_NAME#}</td>
		<td>{#RUBRIK_URL_PREFIX#}</td>
		<td>{#RUBRIK_TEMPLATE_OUT#}</td>
		<td align="center">{#RUBRIK_COUNT_DOCS#}</td>
		<td align="center"><div align="center"><a href="javascript:void(0);" class="topDir link" style="cursor: help;" title="{#RUBRIK_DOCS_VI#}">[?]</a></div></td>
		<td align="center" colspan="4">{#RUBRIK_ACTION#}</td>
	</tr>
	</thead>
	<tbody>
	{foreach from=$rubrics item=rubric}
		<tr>
			<td align="center">
				{if $rubric->rubric_description}
					<a href="javascript:void(0);" class="toprightDir link" style="cursor: help;" title="{$rubric->rubric_description|escape}"><strong>[{$rubric->Id}]</strong></a>
				{else}
					<strong>{$rubric->Id}</strong>
				{/if}
			</td>
			<td><div class="pr12"><input type="text" name="rubric_position[{$rubric->Id}]" value="{$rubric->rubric_position|escape}" /></div></td>
			<td>
				{if check_permission('rubric_edit')}
					<div class="pr12"><input style="width:100%" type="text" name="rubric_title[{$rubric->Id}]" value="{$rubric->rubric_title|escape}" /></div>
				{else}
					<strong>{$rubric->rubric_title|escape}</strong>
				{/if}
			</td>

			<td>
				{if check_permission('rubric_edit')}
					<div class="pr12"><input style="width:100%" type="text" name="rubric_alias[{$rubric->Id}]" value="{$rubric->rubric_alias|escape}" /></div>
				{else}
					<strong>{$rubric->rubric_alias|escape}</strong>
				{/if}
			</td>

			<td>
				{if check_permission('rubric_edit')}
					<select name="rubric_template_id[{$rubric->Id}]" style="min-width: 180px">
						{foreach from=$templates item=template}
							<option value="{$template->Id}" {if $template->Id==$rubric->rubric_template_id}selected="selected" {/if}/>{$template->template_title|escape}</option>
						{/foreach}
					</select>
				{else}
					{foreach from=$templates item=template}
						{if $template->Id==$rubric->rubric_template_id}{$template->template_title|escape}{/if}
					{/foreach}
				{/if}
			</td>

			<td align="center"><strong>{$rubric->doc_count}</strong></td>
			<td align="center"><input type="checkbox" name="rubric_docs_active[{$rubric->Id}]" value="1" {if $rubric->rubric_docs_active == 1}checked="checked"{/if}></td>
			<td align="center">
				{if check_permission('rubric_edit')}
					<a class="topleftDir icon_sprite ico_edit" title="{#RUBRIK_EDIT#}" href="index.php?do=rubs&action=edit&Id={$rubric->Id}&cp={$sess}"></a>
				{else}
					<a title="{#RUBRIK_NO_CHANGE1#}" href="javascript:void(0);" class="topleftDir icon_sprite ico_edit_no"></a>
				{/if}
			</td>

			<td align="center">
				{if check_permission('rubric_edit')}
					<a class="topleftDir icon_sprite ico_template" title="{#RUBRIK_EDIT_TEMPLATE#}" href="index.php?do=rubs&action=template&Id={$rubric->Id}&cp={$sess}"></a>
				{else}
					<a title="{#RUBRIK_NO_CHANGE2#}" href="javascript:void(0);" class="topleftDir icon_sprite ico_template_no"></a>
				{/if}
			</td>

			<td align="center">
				{if check_permission('rubric_multi')}
					<a class="topleftDir icon_sprite ico_copy" title="{#RUBRIK_MULTIPLY#}" href="javascript:void(0);" onclick="cp_pop('index.php?do=rubs&action=multi&Id={$rubric->Id}&pop=1&cp={$sess}','850','500','1','pop')"></a>
				{else}
					<a title="{#RUBRIK_NO_MULTIPLY#}" href="javascript:void(0);" class="topleftDir icon_sprite ico_copy_no"></a>
				{/if}
			</td>
			<td align="center">
				{if $rubric->Id != 1}
					{if $rubric->doc_count==0}
						{if check_permission('rubric_del')}
							<a class="topleftDir ConfirmDelete icon_sprite ico_delete" title="{#RUBRIK_DELETE#}" dir="{#RUBRIK_DELETE#}" name="{#RUBRIK_DELETE_CONFIRM#}" href="index.php?do=rubs&action=delete&Id={$rubric->Id}&cp={$sess}"></a>
						{else}
							<a title="{#RUBRIK_NO_PERMISSION#}" href="javascript:void(0);" class="topleftDir icon_sprite ico_delete_no"></a>
						{/if}
					{else}
						<a title="{#RUBRIK_USE_DOCUMENTS#}" href="javascript:void(0);" class="topleftDir icon_sprite ico_delete"></a>
					{/if}
				{else}
					<span title="" class="topleftDir icon_sprite ico_delete_no"></span>
				{/if}
			</td>
		</tr>
	{/foreach}
	</tbody>
</table>

{if check_permission('rubric_edit')}
<div class="rowElem">
	<input class="basicBtn" type="submit" value="{#RUBRIK_BUTTON_SAVE#}" />
</div>
{/if}

</form>

			</div>

<div id="tab2" class="tab_content" style="display: none;">
				{if check_permission('rubric_new')}
					<form id="add_rub" method="post" action="index.php?do=rubs&action=new&cp={$sess}" class="mainForm">
					<div class="rowElem">
						<label>{#RUBRIK_NAME2#}</label>
						<div class="formRight"><input placeholder="{#RUBRIK_NAME#}" name="rubric_title" type="text" id="rubric_title" value="" style="width: 400px">
						&nbsp;<input type="button" class="basicBtn AddRub" value="{#RUBRIK_BUTTON_NEW#}" />
						</div>
						<div class="fix"></div>
					</div>
					</form>
				{/if}
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

<br /><br /><br />



