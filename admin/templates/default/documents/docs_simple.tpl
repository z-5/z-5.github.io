<script language="javascript" type="text/javascript">

function getUrlParam(paramName)
{ldelim}
  var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
  var match = window.location.search.match(reParam) ;
 
  return (match && match.length > 1) ? match[1] : '' ;
{rdelim}

function insertLink(o) {ldelim}
	for (var key in o) {ldelim}
		$('#'+key, window.opener.document).val(o[key]);
	{rdelim}
	window.close();
{rdelim}

function insertLinkCK(data) {ldelim}
	var funcNum = getUrlParam('CKEditorFuncNum');
	//window.opener.CKEDITOR.tools.callFunction(funcNum, "data");
	var fileUrl = data;
	window.opener.CKEDITOR.tools.callFunction(funcNum, fileUrl);
	window.close();
{rdelim}

</script>

<div class="first"></div>

<div class="title"><h5>{#DOC_SUB_TITLE#}</h5></div>

<div class="widget" style="margin-top: 0px;">
    <div class="body">
		{#DOC_INSERT_LINK_TIP#}
    </div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
			<li class="firstB"><a href="index.php?pop=1" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li>{#DOC_SUB_TITLE#}</li>
	    </ul>
	</div>
</div>

{include file='documents/doc_search.tpl'}

<form enctype="multipart/form-data" class="mainFrom">
<div class="widget first">
	<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<col width="30" />
		<col width="30" />
		<col />
		<col width="150" />
		<col width="75" />
		<thead>
		<tr>
			<td><a href="{$link}&sort=id{if $smarty.request.sort=='id'}_desc{/if}&page={$smarty.request.page|escape|default:'1'}&cp={$sess}">{#DOC_ID#}</a></td>
			<td>&nbsp;</td>
			<td><a href="{$link}&sort=title{if $smarty.request.sort=='title'}_desc{/if}&page={$smarty.request.page|escape|default:'1'}&cp={$sess}">{#DOC_TITLE#}</a></td>
			<td><a href="{$link}&sort=rubric{if $smarty.request.sort=='rubric'}_desc{/if}&page={$smarty.request.page|escape|default:'1'}&cp={$sess}">{#DOC_IN_RUBRIK#}</a></td>
			<td>&nbsp;</td>
		</tr>
		</thead>
		<tbody>
		{foreach from=$docs item=item}
			<tr>
				<td>{$item->Id}</td>
				<td>
					{if $item->document_published < $smarty.now && ($item->document_expire == '0' || $item->document_expire > $smarty.now)}
						<a title="{#DOC_SHOW2_TITLE#}" href="/{if $item->Id!=1}{$item->document_alias}{/if}" target="_blank" class="toprightDir icon_sprite ico_look"></a>
					{else}
						<span class="icon_sprite ico_blanc"></span>
					{/if}
				</td>
				<td><strong>{$item->document_title}</strong><br />{$item->document_alias}</td>
				<td nowrap="nowrap">{$item->RubName|escape}</td>
				<td nowrap="nowrap">
					{if $smarty.request.idonly==1}
						<input onclick="insertLink({ldelim}{$smarty.request.target|escape}:'{$item->Id}'{rdelim});" class="whiteBtn" type="button" value="{#DOC_BUTTON_INSERT_LINK#}" />
					{elseif $smarty.request.selurl==1}
						<input onclick="insertLink({ldelim}{$smarty.request.target|escape}:'index.php?id={$item->Id}&doc={$item->document_alias}'{rdelim});" class="whiteBtn" type="button" value="{#DOC_BUTTON_INSERT_LINK#}" />
					{elseif $smarty.request.selecturl==1}
						<input onclick="insertLinkCK('index.php?id={$item->Id}&doc={$item->document_alias}');" class="whiteBtn" type="button" value="{#DOC_BUTTON_INSERT_LINK#}" />
					{else}
						<input onclick="insertLink({ldelim}{$smarty.request.target|escape}:'index.php?id={$item->Id}',{$smarty.request.doc|escape}:'{$item->document_title}',{$smarty.request.document_alias|escape}:'{$item->document_alias}'{rdelim});" class="whiteBtn" type="button" value="{#DOC_BUTTON_INSERT_LINK#}" />&nbsp;
						<input onclick="insertLink({ldelim}{$smarty.request.target|escape}:'javascript:popup(\'index.php?id={$item->Id}&doc={$item->document_alias}\',\'{$item->document_title}\',\'800\',\'700\')',{$smarty.request.doc|escape}:'{$item->document_title}',{$smarty.request.document_alias|escape}:'{$item->document_alias}'{rdelim});" class="whiteBtn" type="button" value="{#DOC_BUTTON_LINK_POPUP#}" />
					{/if}
				</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
	<div class="fix"></div>
</div>

</form>

{if $page_nav}
	<div class="pagination">
	<ul class="pages">
		{$page_nav}
	</ul>
	</div>
{/if}

<br />