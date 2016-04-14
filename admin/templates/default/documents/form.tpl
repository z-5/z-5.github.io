{if $smarty.session.use_editor == 1}
	<!-- elrte -->
	<link rel="stylesheet" href="{$ABS_PATH}admin/redactor/elrte/css/elrte.full.css" type="text/css" media="screen" />
	<script src="{$ABS_PATH}admin/redactor/elrte/js/elrte.full.js" type="text/javascript"></script>
	<script src="{$ABS_PATH}admin/redactor/elrte/js/i18n/elrte.ru.js" type="text/javascript"></script>

	<!-- elfinder -->
   	<link rel="stylesheet" href="{$ABS_PATH}admin/redactor/elfinder/css/elfinder.full.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="{$ABS_PATH}admin/redactor/elfinder/css/theme.css" type="text/css" media="screen" />
	
	<script src="{$ABS_PATH}admin/redactor/elfinder/js/elfinder.full.js" type="text/javascript"></script>
	<script src="{$ABS_PATH}admin/redactor/elfinder/js/i18n/elfinder.ru.js" type="text/javascript"></script>
	<script src="{$ABS_PATH}admin/redactor/elfinder/js/jquery.dialogelfinder.js" type="text/javascript"></script>

	<script type="text/javascript" src="{$tpl_dir}/js/rle.js"></script>	
{/if}

    <!-- liveeditor -->
{if $smarty.session.use_editor == 2}
{literal}
	<style>
	.istoolbar_container { padding:0; margin:0}
	.istoolbar_container tbody tr { border-top: 0px !important; background:transparent !important}
	.istoolbar_container tbody tr:hover { background:transparent !important}		
	.istoolbar_container tbody td { border-left:0px !important}
	.istoolbar_container tbody td:hover { background:transparent !important}
	</style>
{/literal}
    
    <script src="{$ABS_PATH}admin/liveeditor/scripts/language/ru-RU/editor_lang.js"></script>
    <script src="{$ABS_PATH}admin/liveeditor/scripts/innovaeditor.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/webfont/1.0.30/webfont.js" type="text/javascript"></script>
    <script src="{$ABS_PATH}admin/liveeditor/scripts/common/webfont.js" type="text/javascript"></script>
{/if}

{if $smarty.session.use_editor == 3}
	<script type="text/javascript" src="{$ABS_PATH}admin/ckeditor/ckeditor.js"></script>
{/if}

<script type="text/javascript">
{*
function insertHTML(ed, code) {ldelim}
	document.getElementById('feld['+ed+']___Frame').contentWindow.FCK.InsertHtml(code);
{rdelim}
*}

function openLinkWin(target) {ldelim}
	if (typeof width=='undefined' || width=='') var width = screen.width * 0.6;
	if (typeof height=='undefined' || height=='') var height = screen.height * 0.6;
	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;
	window.open('index.php?do=docs&action=showsimple&target='+target+'&selurl=1&pop=1','pop','left='+left+',top='+top+',width='+Math.min(screen.width, 1000)+',height='+Math.min(screen.height, 600)+',scrollbars=1,resizable=1');
{rdelim}

function openLinkWinId(target,doc) {ldelim}
	if (typeof width=='undefined' || width=='') var width = screen.width * 0.6;
	if (typeof height=='undefined' || height=='') var height = screen.height * 0.6;
	if (typeof doc=='undefined') var doc = 'doc_title';
	if (typeof scrollbar=='undefined') var scrollbar=1;
	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;
	window.open('index.php?idonly=1&do=docs&action=showsimple&doc='+doc+'&target='+target+'&pop=1&cp={$sess}','pop','left='+left+',top='+top+',width='+Math.min(screen.width, 1000)+',height='+Math.min(screen.height, 600)+',scrollbars='+scrollbar+',resizable=1');
{rdelim}

function openFileWin(target,id) {ldelim}
	if (typeof width=='undefined' || width=='') var width = screen.width * 0.6;
	if (typeof height=='undefined' || height=='') var height = screen.height * 0.6;
	if (typeof doc=='undefined') var doc = 'doc_title';
	if (typeof scrollbar=='undefined') var scrollbar=1;
	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;
	window.open('browser.php?id='+id+'&typ=bild&mode=fck&target=navi&cp={$sess}','pop','left='+left+',top='+top+',width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1');
{rdelim}

$(document).ready(function(){ldelim}

	$(".ConfirmRecover").click( function(e) {ldelim}
		e.preventDefault();
		var href = $(this).attr('href');
		var title = $(this).attr('dir');
		var confirm = $(this).attr('name');
		jConfirm(
				confirm,
				title,
				function(b){ldelim}
					if (b){ldelim}
						$.alerts._overlay('show');
						window.location = href;
					{rdelim}
				{rdelim}
			);
	{rdelim});

	$(".СonfirmDeleteRev").click( function(e) {ldelim}
		e.preventDefault();
		var revission = $(this).attr('rev');
		var href = $(this).attr('href');
		var title = $(this).attr('dir');
		var confirm = $(this).attr('name');
		jConfirm(
				confirm,
				title,
				function(b){ldelim}
					if (b){ldelim}
                        $.alerts._overlay('hide');
                        $.alerts._overlay('show');
						$.ajax({ldelim}
						    url: ave_path+'admin/'+href+'&ajax=run',
						    type: 'POST',
						    success: function (data) {ldelim}
						    	$.alerts._overlay('hide');
								$.jGrowl(revission,{ldelim}theme: 'accept'{rdelim});	
								$("#"+revission).remove();
						    {rdelim}
						{rdelim});
					{rdelim}
				{rdelim}
			);
	{rdelim});

	function check(){ldelim}
		$.ajax({ldelim}
			beforeSend: function(){ldelim}
				$("#checkResult").html('');
				{rdelim},
			url: 'index.php',
			data: ({ldelim}
				action: 'checkurl',
				'do': 'docs',
				cp: '{$sess}',
				id: '{$document->Id}',
				alias: $("#document_alias").val()
				{rdelim}),
			timeout:3000,
			dataType: "json",
			success:
				function(data){ldelim}
					$.jGrowl(data[0],{ldelim}theme: data[1]{rdelim});
				{rdelim}
		{rdelim});
	{rdelim};

	$("#translit").click(function(){ldelim}
		$.ajax({ldelim}
			beforeSend: function(){ldelim}
				$("#checkResult").html('');
				{rdelim},
			url:'index.php',
			data: ({ldelim}
				action: 'translit',
				'do': 'docs',
				cp: '{$sess}',
				alias: $("#document_alias").val(),
				title: $("#doc_title").val(),
				prefix: '{$document->rubric_url_prefix}'
				{rdelim}),
			timeout:3000,
			success: function(data){ldelim}
				$("#document_alias").val(data);
				check();
				{rdelim}
		{rdelim});
	{rdelim});

	$("#document_alias").change(function(){ldelim}
		if ($("#document_alias").val()!='') check();
	{rdelim});

	$("#loading")
		.bind("ajaxSend", function(){ldelim}$.alerts._overlay('show'){rdelim})
		.bind("ajaxComplete", function(){ldelim}$.alerts._overlay('hide'){rdelim}
	);

	{if $smarty.request.feld != ''}
		$("#feld_{$smarty.request.feld|escape}").css({ldelim}
			'border' : '2px solid red',
			'font' : '120% verdana,arial',
			'background' : '#ffffff'
		{rdelim});
	{/if}

	$('#document_published').datetimepicker({ldelim}
		changeMonth: true,
    	changeYear: true,
		stepHour: 1,
		stepMinute: 1,

		onClose: function(dateText, inst) {ldelim}
        var endDateTextBox = $('#document_expire');
        if (endDateTextBox.val() != '') {ldelim}
            var testStartDate = new Date(dateText);
            var testEndDate = new Date(endDateTextBox.val());
            if (testStartDate > testEndDate)
                endDateTextBox.val(dateText);
        {rdelim}
        else {ldelim}
            endDateTextBox.val(dateText);
        {rdelim}
	    {rdelim}
	{rdelim});

	$('#document_expire').datetimepicker({ldelim}
		changeMonth: true,
    	changeYear: true,

		stepHour: 1,
		stepMinute: 1,

		onClose: function(dateText, inst) {ldelim}
        var startDateTextBox = $('#document_published');
        if (startDateTextBox.val() != '') {ldelim}
            var testStartDate = new Date(startDateTextBox.val());
            var testEndDate = new Date(dateText);
            if (testStartDate > testEndDate)
                startDateTextBox.val(dateText);
        {rdelim}
        else {ldelim}
            startDateTextBox.val(dateText);
        {rdelim}
    {rdelim},
    onSelect: function (selectedDateTime){ldelim}
        var end = $(this).datetimepicker('getDate');
        $('#document_published').datetimepicker('option', 'maxDate', new Date(end.getTime()) );
    {rdelim}
	{rdelim});

	$(".alias a").click(function() {ldelim}
		var link = $(this).attr("href");
		var parent = $(this).attr("data-id");
		{if $document->rubric_url_prefix == ""}
			$("#document_alias").val(link);
		{else}
			$("#document_alias").val(link+'/{$document->rubric_url_prefix}');
		{/if}
		$("#document_parent").val(parent);
		return false;
	{rdelim});

	$("#document_meta_keywords").autocomplete("index.php?do=docs&action=keywords&ajax=run&cp={$sess}", {ldelim}
		max: 20,
		width: 300,
		highlight: false,
		multiple: true,
		multipleSeparator: ", ",
		autoFill: true,
		scroll: true,
		scrollHeight: 180
	{rdelim});

$('#document_lang').change(function() {ldelim}
    var defaultLang = '{$smarty.session.accept_langs[$smarty.const.DEFAULT_LANGUAGE]}';
    var lang = $('#document_lang option:selected').html();
    var alias = $('#document_alias').val().split('/');
    var languages = [];

    $('#document_lang option').each(function(){ldelim}
        languages.push($(this).html());
    {rdelim});

    if ($.inArray(alias[0], languages) > -1) {ldelim}
        alias.splice(0, 1);
    {rdelim}

    if (lang == defaultLang) {ldelim}
        $('#document_alias').val(alias.join('/'));
    {rdelim} else {ldelim}
        if (alias[0] != "") {ldelim}
			console.log(alias);
            $('#document_alias').val(lang + '/' + alias.join('/'));
        {rdelim} else {ldelim}
            $('#document_alias').val(lang);
        {rdelim}
    {rdelim}
{rdelim});

	
{rdelim});


</script>

{if $smarty.request.action=='edit'}
	<div class="title"><h5>{#DOC_EDIT_DOCUMENT#}</h5></div>
{elseif $smarty.request.action=='copy'}
	<div class="title"><h5>{#DOC_COPY_DOCUMENT#}</h5></div>
{else}
	<div class="title"><h5>{#DOC_ADD_DOCUMENT#}</h5></div>
{/if}

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li><a href="index.php?do=docs&cp={$sess}">{#DOC_SUB_TITLE#}</a></li>
			{if $smarty.request.action=='edit'}
	        <li>{#DOC_EDIT_DOCUMENT#}</li>
			<li><strong>{#DOC_IN_RUBRIK#}</strong> &gt; {$document->rubric_title|escape}</li>
			<li><strong class="code"><a href="{$document->document_alias_breadcrumb}" target="_blank">{$document->document_title}</a></strong></li>
			{else}
	        <li>{#DOC_ADD_DOCUMENT#}</li>
			<li><strong>{#DOC_IN_RUBRIK#}</strong> &gt; {$document->rubric_title|escape}</li>
			<li><strong class="code">{if $smarty.request.document_title != ""}{$smarty.request.document_title}{else}{#DOC_IN_NEW#}{/if}</strong></li>
			{/if}
	    </ul>
	</div>
</div>

<form method="post" name="formDocOption" action="{$document->formaction}" enctype="multipart/form-data" class="mainForm">
<input name="closeafter" type="hidden" id="closeafter" value="{$smarty.request.closeafter}">
<div class="widget first">
<div class="head {if $smarty.request.action == 'edit'}closed{/if}"><h5>{#DOC_OPTIONS#} {if $smarty.request.action == 'edit'}({$smarty.request.Id}){/if}</h5></div>

		<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<col width="250">
		<col>
		<tbody>

		<tr>
			<td class="noborder">{#DOC_CHOOSE_LANG#}</td>
			<td colspan="3">
				<select style="min-width:100px" name="document_lang" id="document_lang">
					{foreach from=$smarty.session.accept_langs key=lang_id item=lang}
						<option value="{$lang_id}"{if $document->document_lang == $lang_id} selected="selected"{/if}>{$lang}</option>
					{/foreach}
				</select>
			</td>
			<td {if $document_alias}rowspan="13"{else}rowspan="12"{/if} valign="top" style="vertical-align: top;">
				<h4>{#DOC_QUERIES#}</h4>
					<br />
					{foreach from=$conditions item=cond}
						<input type="text" readonly style="width:90px" class="query" value="[tag:request:{$cond->Id}]">&nbsp;&nbsp;<a onClick="cp_pop('index.php?do=request&action=edit&Id={$cond->Id}&rubric_id={$cond->rubric_id}&pop=1&cp={$sess}','850','620','1','cond')" title="{$cond->request_description|default:#DOC_REQUEST_NOT_INFO#|escape|stripslashes}" href="javascript:void(0);" class="topDir">{$cond->request_title|escape}</a><br />
					{/foreach}
			</td>
		</tr>

		<tr>
			<td>{#DOC_NAME#}&nbsp;<a href="javascript:void(0);" style="cursor:help;" class="rightDir link btext" title="{#DOC_META_TITLE#}">[?]</a></td>
			<td colspan="3"><div class="pr12"><input name="doc_title" type="text" id="doc_title" size="40" value="{if $smarty.request.action == 'edit'}{$document->document_title|escape}{else}{$smarty.request.document_title}{/if}" /></div></td>
		</tr>

		{if ($smarty.request.Id == 1 || $smarty.request.Id == $PAGE_NOT_FOUND_ID) && $smarty.request.action != 'new'}
			{assign var=dis value='disabled'}
		{/if}

		<tr>
			<td>{#DOC_URL#}&nbsp;<a href="javascript:void(0);" style="cursor:help;" class="rightDir link btext" title="{#DOC_URL_INFO#}">[?]</a></td>
			<td nowrap="nowrap" colspan="3">
				<div class="pr12">
				<input name="document_alias" {$dis} type="text" id="document_alias" size="40" style="width:{if $smarty.request.Id != 1 && $smarty.request.Id != $PAGE_NOT_FOUND_ID}400px{else}100%{/if}" value="{if $smarty.request.action=='edit'}{$document->document_alias}{else}{$document->rubric_url_prefix}{/if}" />{if $smarty.request.Id != 1 && $smarty.request.Id != $PAGE_NOT_FOUND_ID}&nbsp;&nbsp;<input type="button" class="basicBtn" id="translit" value="{#DOC_ALIAS_CREATE#}" />{/if}</div>
				<span id="loading" style="display:none"></span>
				<span id="checkResult"></span>
			</td>
		</tr>
		{if $document_alias}
		<tr>
			<td>{#DOC_URL_LINK#}&nbsp;<a href="javascript:void(0);" style="cursor:help;" class="rightDir link btext" title="{#DOC_USE_RUB_ALIAS#}">[?]</a></td>
			<td nowrap="nowrap" colspan="3">
				<div class="alias">

					{foreach from=$document_alias key=k item=v}
						<div>{$k}</div>
						
						{section name=nov loop=$v}
							<a href="{$v[nov].document_alias}" data-id="{$v[nov].Id}" style="border-bottom:1px dotted #666;margin-right:10px;float:left">{if $v[nov].document_breadcrum_title}{$v[nov].document_breadcrum_title}{else}{$v[nov].document_title}{/if}</a>
						{/section}
						<div class="wrapper"></div><br>
					{/foreach}
				</div>
			</td>
		</tr>
		{/if}
		<tr>
			<td>{#DOC_META_KEYWORDS#}&nbsp;<a href="javascript:void(0);" style="cursor:help;" class="rightDir link btext" title="{#DOC_META_KEYWORDS_INFO#}">[?]</a></td>
			<td colspan="3">
				<div class="pr12">
				<textarea style="width:100%; height:40px" name="document_meta_keywords" id="document_meta_keywords">{$document->document_meta_keywords|escape}</textarea>
				</div>
			</td>
		</tr>

		<tr>
			<td>{#DOC_META_DESCRIPTION#}&nbsp;<a href="javascript:void(0);" style="cursor:help;" class="rightDir link btext" title="{#DOC_META_DESCRIPTION_INFO#}">[?]</a></td>
			<td colspan="3">
				<div class="pr12">
				<textarea style="width:100%; height:40px" name="document_meta_description" id="document_meta_description" >{$document->document_meta_description|escape}</textarea>
				</div>
			</td>
		</tr>

		<tr>
			<td>{#DOC_INDEX_TYPE#}</td>
			<td colspan="3">
				<select style="width:300px" name="document_meta_robots" id="document_meta_robots">
					<option value="index,follow"{if $document->document_meta_robots=='index,follow'} selected="selected"{/if}>{#DOC_INDEX_FOLLOW#}</option>
					<option value="index,nofollow"{if $document->document_meta_robots=='index,nofollow'} selected="selected"{/if}>{#DOC_INDEX_NOFOLLOW#}</option>
					<option value="noindex,nofollow"{if $document->document_meta_robots=='noindex,nofollow'} selected="selected"{/if}>{#DOC_NOINDEX_NOFOLLOW#}</option>
				</select>
			</td>
		</tr>

		<tr>
			<td>{#DOC_START_PUBLICATION#}</td>
			<td>
				<input {$dis} id="document_published" name="document_published" type="text" value="{$document->document_published|date_format:"%d.%m.%Y %H:%M"}" style="width: 150px;" />
			</td>

			<td>{#DOC_END_PUBLICATION#}</td>
			<td>
				<input {$dis} id="document_expire" name="document_expire" type="text" value="{$document->document_expire|date_format:"%d.%m.%Y %H:%M"}" style="width: 150px;" />
			</td>
		</tr>

		<tr>
			<td>{#DOC_CAN_SEARCH#}</td>
			<td colspan="3"><input name="document_in_search" type="checkbox" id="document_in_search" value="1" {if $document->document_in_search==1 || $smarty.request.action=='new'}checked{/if} /><label> </label></td>
		</tr>

		<tr>
			<td>{#DOC_STATUS#}</td>
			<td colspan="3">
				{if $smarty.request.action == 'new'}
					{if  $document->dontChangeStatus==1}
						{assign var=sel_1 value=''}
						{assign var=sel_2 value='selected="selected"'}
					{else}
						{assign var=sel_1 value='selected="selected"'}
						{assign var=sel_2 value=''}
					{/if}
				{else}
					{if $document->document_status==1}
						{assign var=sel_1 value='selected="selected"'}
						{assign var=sel_2 value=''}
					{else}
						{assign var=sel_1 value=''}
						{assign var=sel_2 value='selected="selected"'}
					{/if}
				{/if}
				<select style="width:150px" name="document_status" id="document_status"{if $document->dontChangeStatus==1} disabled="disabled"{/if}>
					<option value="1" {$sel_1}>{#DOC_STATUS_ACTIVE#}</option>
					<option value="0" {$sel_2}>{#DOC_STATUS_INACTIVE#}</option>
				</select>
			</td>
		</tr>

		<tr>
			<td>{#DOC_USE_NAVIGATION#} <a href="javascript:void(0);" style="cursor:help;" class="rightDir link btext" title="{#DOC_NAVIGATION_INFO#}">[?]</a></td>
			<td colspan="3">
				{include file='navigation/tree.tpl'}
			</td>
		</tr>
		<tr>
			<td>{#DOC_BREADCRUMB_TITLE#}</td>
			<td colspan="3"><div class="pr12"><input name="doc_breadcrum_title" type="text" id="doc_breadcrum_title" size="40" value="{if $smarty.request.action == 'edit'}{$document->document_breadcrum_title|escape}{/if}" /></div></td>
		</tr>
		<tr>
			<td>{#DOC_USE_BREADCRUMB#}</td>
			<td colspan="3">
				<input name="document_parent" type="text" id="document_parent" value="{$document->document_parent}" size="4" maxlength="10" style="width: 50px;" />&nbsp;
					<span class="button basicBtn" onClick="openLinkWinId('document_parent','document_parent');">{#DOC_BREADCRUMB_BTN#}</span>
				&nbsp;{if $document->parent}{#DOC_BREADCRUMB_WITH#} «<a href="{$ABS_PATH}index.php?id={$document->parent->Id}" target="_blank">{$document->parent->document_title}</a>»{/if}
			</td>
		</tr>
		</tbody>
	</table>

	<div class="rowElem">
		{*$hidden*}
		{if $smarty.request.action=='edit'}
			<input type="submit" class="basicBtn" value="{#DOC_BUTTON_EDIT_DOCUMENT#}" />
		{else}
			<input type="submit" class="basicBtn" value="{#DOC_BUTTON_ADD_DOCUMENT#}" />
		{/if}
		{#DOC_OR#}
		{if $smarty.request.action=='edit'}
			<input type="submit" class="blackBtn" name="next_edit" value="{#DOC_BUTTON_EDIT_DOCUMENT_NEXT#}" />
		{else}
			<input type="submit" class="blackBtn" name="next_edit" value="{#DOC_BUTTON_ADD_DOCUMENT_NEXT#}" />
		{/if}
	</div>

	<div class="fix"></div>
</div>

<div class="widget first">
<div class="head"><h5>{#DOC_MAIN_CONTENT#}</h5></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<col width="200">
		<col>
		<tbody>
		{foreach from=$document->fields item=document_field}
			<tr class="field_row_{$document_field->Id}">
				<td><strong>{$document_field->rubric_field_title|escape}</strong></td>
				<td colspan="2">{$document_field->Feld}</td>
			</tr>
		{/foreach}
		</tbody>
	</table>

	<div class="rowElem">
		{*$hidden*}
		{if $smarty.request.action=='edit'}
			<input type="submit" class="basicBtn" value="{#DOC_BUTTON_EDIT_DOCUMENT#}" />
		{else}
			<input type="submit" class="basicBtn" value="{#DOC_BUTTON_ADD_DOCUMENT#}" />
		{/if}
		{#DOC_OR#}
		{if $smarty.request.action=='edit'}
			<input type="submit" class="blackBtn" name="next_edit" value="{#DOC_BUTTON_EDIT_DOCUMENT_NEXT#}" />
		{else}
			<input type="submit" class="blackBtn" name="next_edit" value="{#DOC_BUTTON_ADD_DOCUMENT_NEXT#}" />
		{/if}
	</div>

	<div class="fix"></div>
</div>

<div class="widget first">
<div class="head"><h5>{#DOC_REVISSION#}</h5></div>
		<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<col>
		<col>
		<col width="20">
		<col width="20">
		<col width="20">
		<thead>
		<tr>
			<td>{#DOC_REVISSION_DATA#}</td>
			<td>{#DOC_REVISSION_USER#}</td>
			<td colspan="3">{#DOC_ACTIONS#}</td>
		</tr>
		</thead>
		<tbody>
		{if $document_rev}
		{foreach from=$document_rev item=document_rev}
			<tr id="{$document_rev->doc_revision}">
				<td align="center"><span class="date_text dgrey">{$document_rev->doc_revision|date_format:$TIME_FORMAT|pretty_date}</span></td>
				<td align="center">{$document_rev->user_id}</td>
				<td><a class="topleftDir icon_sprite ico_look" title="{#DOC_REVISSION_VIEW#}" href="../?id={$document_rev->doc_id}&revission={$document_rev->doc_revision}" target="_blank"></a></td>
				<td><a class="topleftDir ConfirmRecover icon_sprite ico_copy" title="{#DOC_REVISSION_RECOVER#}" dir="{#DOC_REVISSION_RECOVER#}" name="{#DOC_REVISSION_RECOVER_T#}" href="index.php?do=docs&action=recover&doc_id={$document_rev->doc_id}&revission={$document_rev->doc_revision}&rubric_id={$smarty.request.rubric_id}&cp={$sess}"></a></td>
				<td><a class="topleftDir СonfirmDeleteRev icon_sprite ico_delete" title="{#DOC_REVISSION_DELETE#}" dir="{#DOC_REVISSION_DELETE#}" rev="{$document_rev->doc_revision}" name="{#DOC_REVISSION_DELETE_T#}" href="index.php?do=docs&action=recover_del&doc_id={$document_rev->doc_id}&revission={$document_rev->doc_revision}&rubric_id={$smarty.request.rubric_id}&cp={$sess}"></a></td>
			</tr>
		{/foreach}
		{else}
			<tr>
				<td colspan="5">
					<ul class="messages">
						<li class="highlight yellow">{#DOC_REVISSION_NO_ITEMS#}</li>
					</ul>
				</td>
			</tr>
		{/if}
		</tbody>
	</table>
	<div class="fix"></div>
</div>

</form>