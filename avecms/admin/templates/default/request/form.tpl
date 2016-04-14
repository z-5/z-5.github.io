<link rel="stylesheet" href="{$ABS_PATH}admin/codemirror/lib/codemirror.css">

<script src="{$ABS_PATH}admin/codemirror/lib/codemirror.js" type="text/javascript"></script>
    <script src="{$ABS_PATH}admin/codemirror/mode/xml/xml.js"></script>
    <script src="{$ABS_PATH}admin/codemirror/mode/javascript/javascript.js"></script>
    <script src="{$ABS_PATH}admin/codemirror/mode/css/css.js"></script>
    <script src="{$ABS_PATH}admin/codemirror/mode/clike/clike.js"></script>
    <script src="{$ABS_PATH}admin/codemirror/mode/php/php.js"></script>

{literal}
    <style type="text/css">
      .activeline {background: #e8f2ff !important;}
      .CodeMirror-scroll {height: 450px;}
    </style>
{/literal}

<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function changeRub(select) {ldelim}
	if(select.options[select.selectedIndex].value!='#') {ldelim}

			if(select.options[select.selectedIndex].value!='#') {ldelim}
			{if $smarty.request.action=='new'}
				location.href='index.php?do=request&action=new&rubric_id=' + select.options[select.selectedIndex].value + '{if $smarty.request.request_title_new!=''}&request_title_new={$smarty.request.request_title_new|escape|stripslashes}{/if}';
			{else}
				location.href='index.php?do=request&action=edit&Id={$smarty.request.Id|escape}&rubric_id=' + select.options[select.selectedIndex].value;
			{/if}
			{rdelim}

		else {ldelim}
			document.getElementById('RubrikId_{$smarty.request.rubric_id|escape}').selected = 'selected';
		{rdelim}
	{rdelim}
{rdelim}
/*]]>*/
</script>

	{if $smarty.request.action=='edit'}
		<div class="title"><h5>{#REQUEST_EDIT2#}</h5></div>
		<div class="widget" style="margin-top: 0px;"><div class="body">{#REQUEST_EDIT_TIP#}</div></div>
	{else}
		<div class="title"><h5>{#REQUEST_NEW#}</h5></div>
		<div class="widget" style="margin-top: 0px;"><div class="body">{#REQUEST_NEW_TIP#}</div></div>
	{/if}


<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li><a href="index.php?do=request&amp;cp={$sess}">{#REQUEST_ALL#}</a></li>
				{if $smarty.request.action=='edit'}
					<li>{#REQUEST_EDIT2#}</li>
				{else}
					<li>{#REQUEST_NEW#}</li>
				{/if}
	        <li><strong class="code">{$row->request_title|escape}</strong></li>
	    </ul>
	</div>
</div>


{if $smarty.request.Id==''}
	{assign var=iframe value='no'}
{/if}

{if $smarty.request.action == 'new' && $smarty.request.rubric_id == ''}
	{assign var=dis value='disabled'}
{/if}


<form name="request_tpl" id="request_tpl" method="post" action="{$formaction}" class="mainForm">
<div class="widget first">
	<input name="pop" class="mousetrap" type="hidden" id="pop" value="{$smarty.request.pop|escape}" />

<div class="head"><h5 class="iFrames">{#REQUEST_SETTINGS#}</h5></div>
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<col width="238">

		<tr class="noborder">
			<td>{#REQUEST_NAME2#}</td>
			<td><input {$dis} class="mousetrap" style="width:250px" name="request_title" type="text" id="l_Titel" value="{$smarty.request.request_title_new|stripslashes|default:$row->request_title|escape}"></td>
		</tr>

		<tr>
			<td>{#REQUEST_CACHE#}</td>
			<td><input {$dis} class="mousetrap" style="width:250px" name="request_cache_lifetime" type="text" id="request_cache_lifetime" value="{$smarty.request.request_cache_lifetime|stripslashes|default:$row->request_cache_lifetime|escape}"></td>
		</tr>

		<tr>
			<td>{#REQUEST_SELECT_RUBRIK#}</td>
			<td>
				<select onChange="changeRub(this)" style="width:250px" name="rubric_id" id="rubric_id" class="mousetrap">
					{if $smarty.request.action=='new' && $smarty.request.rubric_id==''}
						<option value="">{#REQUEST_PLEASE_SELECT#}</option>
					{/if}
					{foreach from=$rubrics item=rubric}
						<option id="RubrikId_{$rubric->Id}" value="{$rubric->Id}"{if $smarty.request.rubric_id==$rubric->Id} selected="selected"{/if}>{$rubric->rubric_title|escape}</option>
					{/foreach}
				</select>
			</td>
		</tr>

		<tr>
			<td>{#REQUEST_DESCRIPTION#}<br /><small>{#REQUEST_INTERNAL_INFO#}</small></td>
			<td><textarea class="mousetrap" {$dis} style="width:350px; height:60px" name="request_description" id="request_description">{$row->request_description|escape}</textarea></td>
		</tr>

		<tr>
			<td>{#REQUEST_CONDITION#}</td>
			<td>
				{if $iframe=='no'}
					<input type="checkbox" name="reedit" value="1" checked="checked" class="float mousetrap" /> <label>{#REQUEST_ACTION_AFTER#}</label>
				{/if}
				{if $iframe!='no'}
						<input name="button" type="button" class="basicBtn" onclick="windowOpen('index.php?do=request&action=konditionen&rubric_id={$smarty.request.rubric_id|escape}&Id={$smarty.request.Id|escape}&pop=1&cp={$sess}','960','620','1')" value="{#REQUEST_BUTTON_COND#}" />
				{/if}
			</td>
		</tr>

		<tr>
			<td>{#REQUEST_SORT_BY#}</td>
			<td>
				<select {$dis} style="width:250px" name="request_order_by" id="request_order_by" class="mousetrap">
					<option value="document_published"{if $row->request_order_by=='document_published'} selected="selected"{/if}>{#REQUEST_BY_DATE#}</option>
					<option value="document_changed"{if $row->request_order_by=='document_changed'} selected="selected"{/if}>{#REQUEST_BY_DATECHANGE#}</option>
					<option value="document_title"{if $row->request_order_by=='document_title'} selected="selected"{/if}>{#REQUEST_BY_NAME#}</option>
					<option value="document_author_id"{if $row->request_order_by=='document_author_id'} selected="selected"{/if}>{#REQUEST_BY_EDIT#}</option>
					<option value="document_count_print"{if $row->request_order_by=='document_count_print'} selected="selected"{/if}>{#REQUEST_BY_PRINTED#}</option>
					<option value="document_count_view"{if $row->request_order_by=='document_count_view'} selected="selected"{/if}>{#REQUEST_BY_VIEWS#}</option>
					<option value="RAND()"{if $row->request_order_by=='RAND()'} selected="selected"{/if}>{#REQUEST_BY_RAND#}</option>
				</select>
			</td>
		</tr>

		<tr>
			<td>{#REQUEST_SORT_BY_NAT#}</td>
			<td>
				<select {$dis} style="width:250px" name="request_order_by_nat" id="request_order_by_nat" class="mousetrap">
					<option>&nbsp;</option>
					{foreach from=$tags item=tag}
					  <option value="{$tag->Id}" {if $tag->Id == $row->request_order_by_nat}selected="selected"{/if}>{$tag->rubric_field_title}</option>
					{/foreach}
				</select>
			</td>
		</tr>


		<tr>
			<td>{#REQUEST_ASC_DESC#}</td>
			<td>
				<select {$dis} style="width:150px" name="request_asc_desc" id="request_asc_desc" class="mousetrap">
					<option value="DESC"{if $row->request_asc_desc=='DESC'} selected="selected"{/if}>{#REQUEST_DESC#}</option>
					<option value="ASC"{if $row->request_asc_desc=='ASC'} selected="selected"{/if}>{#REQUEST_ASC#}</option>
				</select>
			</td>
		</tr>

		<tr>
			<td>{#REQUEST_DOC_PER_PAGE#}</td>
			<td>
				<select {$dis} style="width:150px" name="request_items_per_page" id="request_items_per_page" class="mousetrap">
						<option value="0" {if $row->request_items_per_page==all} selected="selected"{/if}>{#REQUEST_DOC_PER_PAGE_ALL#}</option>
					{section name=zahl loop=300 step=1 start=0}
						<option value="{$smarty.section.zahl.index+1}"{if $row->request_items_per_page==$smarty.section.zahl.index+1} selected="selected"{/if}>{$smarty.section.zahl.index+1}</option>
					{/section}
				</select>
			</td>
		</tr>

		<tr>
			<td>{#REQUEST_SHOW_NAVI#}</td>
			<td><input class="mousetrap float" name="request_show_pagination" type="checkbox" id="request_show_pagination" value="1"{if $row->request_show_pagination=='1'} checked="checked"{/if} /><label>&nbsp;</label></td>
		</tr>

		<tr>
			<td>{#REQUEST_USE_LANG#}</td>
			<td><input class="mousetrap float" name="request_lang" type="checkbox" id="request_lang" value="1"{if $row->request_lang=='1'} checked="checked"{/if} /><label>&nbsp;</label></td>
		</tr>

		
</table>
	<div class="fix"></div>
</div>

<div class="widget first">
<div class="head"><h5 class="iFrames">{#REQUEST_TEMPLATE_QUERY#}</h5></div>
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">

	{assign var=js_textfeld value='request_template_main'}
	<col width="230">
<tr class="noborder">
	<td><strong><a title="{#REQUEST_MAIN_CONTENT#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:content]', '');">[tag:content]</a></strong></td>
	<td rowspan="14"><textarea {$dis} name="request_template_main" id="request_template_main" wrap="off" style="width:100%; height:380px">{$row->request_template_main|escape|default:''}</textarea></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_MAIN_NAVI#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:pages]', '');">[tag:pages]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_CDOCID_TITLE#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:pagetitle]', '');">[tag:pagetitle]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_DOC_COUNT#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:doctotal]', '');">[tag:doctotal]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_CDOCID_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:docid]', '');">[tag:docid]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_CDOCDATE_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:docdate]', '');">[tag:docdate]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_CDOCTIME_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:doctime]', '');">[tag:doctime]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_CDATE_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:date:', ']');">[tag:date:X]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_CDOCAUTHOR_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:docauthor]', '');">[tag:docauthor]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_PATH#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:path]', '');">[tag:path]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_MEDIAPATH#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:mediapath]', '');">[tag:mediapath]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_IF_EMPTY#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:if_empty]\n', '\n[/tag:if_empty]');">[tag:if_empty][/tag:if_empty]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_NOT_EMPTY#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:if_notempty]\n', '\n[/tag:if_notempty]');">[tag:if_notempty][/tag:if_notempty]</a></strong></td>
</tr>
<tr>
	<td>
		{if $ddid != ''}
			<strong><a title="{#REQUEST_CONTROL_FIELD#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:dropdown:', '{$ddid}]');">[tag:dropdown:{$ddid}]</a></strong>
		{else}
			<strong><a title="{#REQUEST_CONTROL_FIELD#}" class="rightDir" href="javascript:void(0);" onclick="jAlert('{#REQUEST_NO_DROPDOWN#}','{#REQUEST_TEMPLATE_QUERY#}');">[tag:dropdown:XX,XX...]</a></strong>
		{/if}
	</td>
</tr>
<tr>
	<td>HTML Tags</td>
	<td>
        |&nbsp;
        <a href="javascript:void(0);" onclick="textSelection('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
        <a href="javascript:void(0);" onclick="textSelection('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
        <a href="javascript:void(0);" onclick="textSelection('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection('\t', '');"><strong>TAB</strong></a>&nbsp;|
	</td>
</tr>
</table>
	<div class="fix"></div>
</div>

<div class="widget first">
<div class="head"><h5 class="iFrames">{#REQUEST_TEMPLATE_ITEMS#}</h5></div>
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">

<col width="230">

<tr>
	<td>{#REQUEST_CONDITION_IF#}</td>
	<td>
		|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('[tag:if_first]', '[tag:/if]');"><strong>[tag:if_first]</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('[tag:if_not_first]', '[tag:/if]');"><strong>[tag:if_not_first]</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('[tag:if_last]', '[tag:/if]');"><strong>[tag:if_last]</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('[tag:if_not_last]', '[tag:/if]');"><strong>[tag:if_not_last]</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('[tag:if_every:]', '[tag:/if]');"><strong>[tag:if_every:XXX]</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('[tag:if_not_every:]', '[tag:/if]');"><strong>[tag:if_not_every:XXX]</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('[tag:if_every:2]четный[tag:if_else]нечетный[tag:/if]', '');"><strong>{#REQUEST_SAMPLE#}</strong></a>
		&nbsp;|
	</td>
</tr>
<tr class="noborder">
	<td><strong><a title="{#REQUEST_RUB_INFO#}" class="rightDir" href="javascript:void(0);" onclick="jAlert('{#REQUEST_SELECT_IN_LIST#}','{#REQUEST_TEMPLATE_ITEMS#}');">[tag:rfld:ID][XXX]</a></strong></td>
	<td rowspan="14"><textarea {$dis} name="request_template_item" id="request_template_item" wrap="off" style="width:100%; height:340px">{$row->request_template_item|escape|default:''}</textarea></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_DOCID_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection2('[tag:docid]', '');">[tag:docid]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_DOCTITLE_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection2('[tag:doctitle]', '');">[tag:doctitle]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_LINK_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection2('[tag:link]', '');">[tag:link]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_DOCDATE_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection2('[tag:docdate]', '');">[tag:docdate]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_DOCTIME_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection2('[tag:doctime]', '');">[tag:doctime]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_DATE_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection2('[tag:date:', ']');">[tag:date:X]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_DOCAUTHOR_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection2('[tag:docauthor]', '');">[tag:docauthor]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_VIEWS_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection2('[tag:docviews]', '');">[tag:docviews]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_COMMENTS_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection2('[tag:doccomments]', '');">[tag:doccomments]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_PATH#}" class="rightDir" href="javascript:void(0);" onclick="textSelection2('[tag:path]', '');">[tag:path]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_MEDIAPATH#}" class="rightDir" href="javascript:void(0);" onclick="textSelection2('[tag:mediapath]', '');">[tag:mediapath]</a></strong></td>
</tr>
<tr>
	<td><strong><a title="{#REQUEST_THUMBNAIL#}" class="rightDir" href="javascript:void(0);" onclick="textSelection2('[tag:X000x000:YYY]', '');">[tag:X000x000:[tag:rfld:XXX][XXX]]</a></strong></td>
</tr>
<tr>
	<td></td>
</tr>
<tr>
	<td>HTML Tags</td>
	<td>
        |&nbsp;
        <a href="javascript:void(0);" onclick="textSelection2('<ol>', '</ol>');"><strong>OL</strong></a>
        &nbsp;|&nbsp;
        <a href="javascript:void(0);" onclick="textSelection2('<ul>', '</ul>');"><strong>UL</strong></a>
        &nbsp;|&nbsp;
        <a href="javascript:void(0);" onclick="textSelection2('<li>', '</li>');"><strong>LI</strong></a>
        &nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<strong>', '</strong>');"><strong>B</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<em>', '</em>');"><strong>I</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<h1>', '</h1>');"><strong>H1</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<h2>', '</h2>');"><strong>H2</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<h3>', '</h3>');"><strong>H3</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<h4>', '</h4>');"><strong>H4</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<h5>', '</h5>');"><strong>H5</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<span>', '</span>');"><strong>SPAN</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<pre>', '</pre>');"><strong>PRE</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<br />', '');"><strong>BR</strong></a>
		&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2s('\t', '');"><strong>TAB</strong></a>
		&nbsp;|
	</td>
</tr>
</table>

</div>

<div class="widget first">
<div class="head"><h5 class="iFrames">Поля рубрики</h5></div>

<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<col width="200" />
<col />
<col width="200" />
	<thead>
	<tr>
		<td>{#REQUEST_RUBRIK_FIELD#}</td>
		<td>{#REQUEST_FIELD_NAME#}</td>
		<td>{#REQUEST_FIELD_TYPE#}</td>
	</tr>
	</thead>
	{foreach from=$tags item=tag}
		<tr>
			<td><a title="{#REQUEST_INSERT_INFO#}" href="javascript:void(0);" onclick="textSelection2('[tag:rfld:{if $tag->rubric_field_alias}{$tag->rubric_field_alias}{else}{$tag->Id}{/if}][', '150]');" class="toprightDir"><strong>[tag:rfld:{if $tag->rubric_field_alias}{$tag->rubric_field_alias}{else}{$tag->Id}{/if}][150]</strong></a></td>
			<td><strong>{$tag->rubric_field_title}</strong></td>
			<td>
				{section name=feld loop=$feld_array}
					{if $tag->rubric_field_type == $feld_array[feld].id}
						{$feld_array[feld].name}
					{/if}
				{/section}
			</td>
		</tr>
	{/foreach}
</table>

<div class="rowElem">
	{if $smarty.request.action=='edit'}
		<input {$dis} type="submit" class="basicBtn" value="{#REQUEST_BUTTON_SAVE#}" />
	{else}
		<input {$dis} type="submit" class="basicBtn" value="{#REQUEST_BUTTON_ADD#}" />
	{/if}
	{#REQUEST_OR#}
	{if $smarty.request.action=='edit'}
		<input {$dis} type="submit" class="blackBtn SaveEdit" value="{#REQUEST_BUTTON_SAVE_NEXT#}" />
	{else}
		<input {$dis} type="submit" class="blackBtn" value="{#REQUEST_BUTTON_ADD_NEXT#}" />
	{/if}

</div>

	<div class="fix"></div>
</div>

</form>

    <script language="Javascript" type="text/javascript">
    var sett_options = {ldelim}
		url: "{$formaction}&ajax=run",
		beforeSubmit: Request,
        success: Response,
        dataType:  'json'
	{rdelim}

	function Request(){ldelim}
		$.alerts._overlay('show');
	{rdelim}

	function Response(data){ldelim}
		$.alerts._overlay('hide');
		$.jGrowl(data[0],{ldelim}theme: data[1]{rdelim});
	{rdelim}

	$(document).ready(function(){ldelim}

		Mousetrap.bind(['ctrl+s', 'meta+s'], function(e) {ldelim}
		    if (e.preventDefault) {ldelim}
		        e.preventDefault();
		    {rdelim} else {ldelim}
		        // internet explorer
		        e.returnValue = false;
		    {rdelim}
		    $("#request_tpl").ajaxSubmit(sett_options);
			return false;
		{rdelim});

	    $(".SaveEdit").click(function(e){ldelim}
		    if (e.preventDefault) {ldelim}
		        e.preventDefault();
		    {rdelim} else {ldelim}
		        // internet explorer
		        e.returnValue = false;
		    {rdelim}
		    $("#request_tpl").ajaxSubmit(sett_options);
			return false;
		{rdelim});

	{rdelim});

{literal}
      var editor = CodeMirror.fromTextArea(document.getElementById("request_template_main"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#request_tpl").ajaxSubmit(sett_options);}},
        lineNumbers: true,
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor.save();},
		onCursorActivity: function() {
		  editor.setLineClass(hlLine, null, null);
		  hlLine = editor.setLineClass(editor.getCursor().line, null, "activeline");
		}
      });

      function getSelectedRange() {
        return { from: editor.getCursor(true), to: editor.getCursor(false) };
      }

      function textSelection(startTag,endTag) {
        var range = getSelectedRange();
        editor.replaceRange(startTag + editor.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor.setCursor(range.from.line, range.from.ch + startTag.length);
      }

	  var hlLine = editor.setLineClass(0, "activeline");

      var editor2 = CodeMirror.fromTextArea(document.getElementById("request_template_item"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#request_tpl").ajaxSubmit(sett_options);}},
        lineNumbers: true,
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor2.save();},
		onCursorActivity: function() {
		  editor2.setLineClass(hlLine, null, null);
		  hlLine = editor2.setLineClass(editor2.getCursor().line, null, "activeline");
		}
      });

      function getSelectedRange2() {
        return { from: editor2.getCursor(true), to: editor2.getCursor(false) };
      }

      function textSelection2(startTag,endTag) {
        var range = getSelectedRange2();
        editor2.replaceRange(startTag + editor2.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor2.setCursor(range.from.line, range.from.ch + startTag.length);
      }

      var hlLine = editor2.setLineClass(0, "activeline");
{/literal}
    </script>
