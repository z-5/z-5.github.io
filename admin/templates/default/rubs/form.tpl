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
    </style>
{/literal}

{if $smarty.request.action=='new'}
	<div class="title"><h5>{#RUBRIK_TEMPLATE_NEW#}</h5></div>
{else}
	<div class="title"><h5>{#RUBRIK_TEMPLATE_EDIT#}</h5></div>
{/if}
<div class="widget" style="margin-top: 0px;"><div class="body">{#RUBRIK_TEMPLATE_TIP#}</div></div>


<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li><a href="index.php?do=rubs&cp={$sess}">{#RUBRIK_SUB_TITLE#}</a></li>
			{if $smarty.request.action=='new'}
	        <li>{#RUBRIK_TEMPLATE_NEW#}</li>
			<li><strong class="code">{$row->rubric_title|escape}</strong></li>
			{else}
	        <li>{#RUBRIK_TEMPLATE_EDIT#}</li>
			<li><strong class="code">{$row->rubric_title|escape}</strong></li>
			{/if}
	    </ul>
	</div>
</div>

<form name="f_tpl" id="f_tpl" method="post" action="{$formaction}" class="mainFrom">

{if $php_forbidden==1}
	<div class="infobox_error">{#RUBRIK_PHP_DENIDED#} </div>
{/if}

{if $errors}
	{foreach from=$errors item=e}
		{assign var=message value=$e}
		<ul>
			<li>{$message}</li>
		</ul>
	{/foreach}
{/if}

<div class="widget first">
<div class="head closed active"><h5>{#RUBRIK_HTML_2#}</h5></div>
	<div style="display: block;">
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<col width="18%" />
<col width="82%" />
	<thead>
		<tr class="noborder">
			<td>{#RUBRIK_TAGS#}</td>
			<td>{#RUBRIK_HTML_T#}</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<strong><a class="rightDir" title="{#RUBRIK_TEMPLATES_THEME_FOLDER#}" href="javascript:void(0);" onclick="textSelection2('[tag:theme:',']');">[tag:theme:folder]</a></strong>
			</td>
            <td rowspan="9" colspan="2"><textarea {$read_only} class="{if $php_forbidden==1}tpl_code_readonly{else}{/if}" style="width:100%; height:200px" name="rubric_header_template" id="rubric_header_template">{$row->rubric_header_template|default:$prefab|escape:html}</textarea></td>
		</tr>

		<tr>
			<td>
				<strong><a class="rightDir" title="{#RUBRIK_TEMPLATES_PAGENAME#}" href="javascript:void(0);" onclick="textSelection2('[tag:sitename]','');">[tag:sitename]</a></strong>
			</td>
		</tr>

		<tr>
			<td>
				<strong><a class="rightDir" title="{#RUBRIK_TEMPLATES_TITLE#}" href="javascript:void(0);" onclick="textSelection2('[tag:title]','');">[tag:title]</a></strong>
			</td>
		</tr>

		<tr>
			<td>
				<strong><a class="rightDir" title="{#RUBRIK_TEMPLATES_KEYWORDS#}" href="javascript:void(0);" onclick="textSelection2('[tag:keywords]','');">[tag:keywords]</a></strong>
			</td>
		</tr>

		<tr>
			<td>
				<strong><a class="rightDir" title="{#RUBRIK_TEMPLATES_DESCRIPTION#}" href="javascript:void(0);" onclick="textSelection2('[tag:description]','');">[tag:description]</a></strong>
			</td>
		</tr>

		<tr>
			<td>
				<strong><a class="rightDir" title="{#RUBRIK_TEMPLATES_INDEXFOLLOW#}" href="javascript:void(0);" onclick="textSelection2('[tag:robots]','');">[tag:robots]</a></strong>
			</td>
		</tr>

		<tr>
			<td>
				<strong><a class="rightDir" title="{#RUBRIK_TEMPLATES_CSS#}" href="javascript:void(0);" onclick="textSelection2('[tag:css:]','');">[tag:css:FFF:P]</a></strong>,&nbsp;&nbsp;
                <strong><a class="rightDir" title="{#RUBRIK_TEMPLATES_JS#}" href="javascript:void(0);" onclick="textSelection2('[tag:js:]','');">[tag:js:FFF:P]</a></strong>
			</td>
		</tr>

		<tr>
			<td>
				<strong><a class="rightDir" title="{#RUBRIK_TEMPLATES_PATH#}" href="javascript:void(0);" onclick="textSelection2('[tag:path]','');">[tag:path]</a></strong>
			</td>
		</tr>

		<tr>
			<td>
				<strong><a class="rightDir" title="{#RUBRIK_TEMPLATES_MEDIAPATH#}" href="javascript:void(0);" onclick="textSelection2('[tag:mediapath]','');">[tag:mediapath]</a></strong>
			</td>
		</tr>
    <tr>
    	<td><strong>HTML Tags</strong></td>
    	<td>
        |&nbsp;
        <a href="javascript:void(0);" onclick="textSelection2('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
        <a href="javascript:void(0);" onclick="textSelection2('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
        <a href="javascript:void(0);" onclick="textSelection2('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection2('\t', '');"><strong>TAB</strong></a>&nbsp;|
    	</td>
    </tr>
</tbody>
</table>


<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<col width="3%" />
<col width="15%" />
<col width="25%" />
<col width="57%" />
	<thead>
		<tr>
			<td align="center"><strong>{#RUBRIK_ID#}</strong></td>
			<td align="center"><strong>{#RUBRIK_TAGS#}</strong></td>
			<td align="center"><strong>{#RUBRIK_FIELD_NAME#}</strong></td>
			<td align="center"><strong>{#RUBRIK_FIELD_TYPE#}</strong></td>
		</tr>
	</thead>
	<tbody>
		{foreach from=$tags item=tag}
			<tr>
				<td align="center">{$tag->Id}</td>
				<td width="10%"><a class="rightDir" title="{#RUBRIK_INSERT_HELP#}" href="javascript:void(0);" onclick="textSelection2('[tag:rfld:{if $tag->rubric_field_alias}{$tag->rubric_field_alias}{else}{$tag->Id}{/if}][150]', '');"><strong>[tag:rfld:{if $tag->rubric_field_alias}{$tag->rubric_field_alias}{else}{$tag->Id}{/if}][150]</strong></a></td>
				<td width="10%"><strong>{$tag->rubric_field_title}</strong></td>
				<td width="10%">
					{section name=feld loop=$feld_array}
						{if $tag->rubric_field_type == $feld_array[feld].id}{$feld_array[feld].name}{/if}
					{/section}
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>
		<div class="rowElem">
			<input type="hidden" name="Id" value="{$smarty.request.Id|escape}" />
			<input class="basicBtn" type="submit" value="{#RUBRIK_BUTTON_TPL#}" />
			&nbsp;или&nbsp;
			<input type="submit" class="blackBtn SaveEdit" name="next_edit" value="{#RUBRIK_BUTTON_TPL_NEXT#}" />
		</div>
	<div class="fix"></div>
</div>
</div>

<div class="widget first">
<div class="head"><h5>{#RUBRIK_HTML#}</h5><div class="num"><a class="basicNum" href="index.php?do=rubs&action=edit&Id={$smarty.request.Id|escape}&cp={$sess}">{#RUBRIK_EDIT#}</a></div></div>

<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<col width="18%" />
<col width="82%" />
	<thead>
		<tr class="noborder">
			<td>{#RUBRIK_TAGS#}</td>
			<td>{#RUBRIK_HTML_T#}</td>
		</tr>
	</thead>
<tbody>
	<tr>
		<td>
    	<a class="rightDir" title="{#RUBRIK_DOCID_INFO#}" href="javascript:void(0);" onclick="textSelection('[tag:docid]', '');"><strong>[tag:docid]</strong></a>
		</td>
        <td rowspan="13"><textarea {$read_only} class="{if $php_forbidden==1}tpl_code_readonly{else}{/if}" style="width:100%; height:350px" name="rubric_template" id="rubric_template">{$row->rubric_template|default:$prefab|escape:html}</textarea></td>
	</tr>

	<tr>
		<td>
	<a class="rightDir" title="{#RUBRIK_DOCDATE_INFO#}" href="javascript:void(0);" onclick="textSelection('[tag:docdate]', '');"><strong>[tag:docdate]</strong></a>
		</td>
	</tr>
	<tr>
		<td>
	<a class="rightDir" title="{#RUBRIK_DOCTIME_INFO#}" href="javascript:void(0);" onclick="textSelection('[tag:doctime]', '');"><strong>[tag:doctime]</strong></a>
		</td>
	</tr>
	<tr>
		<td>
	<a class="rightDir" title="{#RUBRIK_DATE_INFO#}" href="javascript:void(0);" onclick="textSelection('[tag:date:', ']');"><strong>[tag:date:XXX]</strong></a>
		</td>
	</tr>
	<tr>
		<td>
	<a class="rightDir" title="{#RUBRIK_DOCAUTHOR_INFO#}" href="javascript:void(0);" onclick="textSelection('[tag:docauthor]', '');"><strong>[tag:docauthor]</strong></a>
		</td>
	</tr>
	<tr>
		<td>
	<a class="rightDir" title="{#RUBRIK_VIEWS_INFO#}" href="javascript:void(0);" onclick="textSelection('[tag:docviews]', '');"><strong>[tag:docviews]</strong></a>
		</td>
	</tr>
	<tr>
		<td>
	<a class="rightDir" title="{#RUBRIK_TITLE_INFO#}" href="javascript:void(0);" onclick="textSelection('[tag:title]', '');"><strong>[tag:title]</strong></a>
		</td>
	</tr>
	<tr>
		<td>
	<a class="rightDir" title="{#RUBRIK_PATH_INFO#}" href="javascript:void(0);" onclick="textSelection('[tag:path]', '');"><strong>[tag:path]</strong></a>
		</td>
	</tr>
	<tr>
		<td><strong><a title="{#RUBRIK_LINK_HOME#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:home]', '');">[tag:home]</a></strong></td>
	</tr>
	<tr>
		<td>
	<a class="rightDir" title="{#RUBRIK_MEDIAPATH_INFO#}" href="javascript:void(0);" onclick="textSelection('[tag:mediapath]', '');"><strong>[tag:mediapath]</strong></a>
		</td>
	</tr>
	<tr>
		<td>
	<a class="rightDir" title="{#RUBRIK_HIDE_INFO#}" href="javascript:void(0);" onclick="textSelection('[tag:hide:', ']\n\n[/tag:hide]');"><strong>[tag:hide:X,X][/tag:hide]</strong></a>
		</td>
	</tr>
	<tr>
		<td>
	<a class="rightDir" title="{#RUBRIK_BREADCRUMB#}" href="javascript:void(0);" onclick="textSelection('[tag:breadcrumb]', '');"><strong>[tag:breadcrumb]</strong></a>
		</td>
	</tr>
	<tr>
		<td><strong><a title="{#RUBRIK_THUMBNAIL#}" class="rightDir" href="javascript:void(0);" onclick="textSelection('[tag:X000x000:YYY]', '');">[tag:X000x000:[tag:rfld:XXX][XXX]]</a></strong></td>
	</tr>

    <tr>
    	<td><strong>HTML Tags</strong></td>
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
</tbody>
</table>
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<col width="3%" />
<col width="15%" />
<col width="25%" />
<col width="57%" />
	<thead>
		<tr>
			<td align="center"><strong>{#RUBRIK_ID#}</strong></td>
			<td align="center"><strong>{#RUBRIK_TAGS#}</strong></td>
			<td align="center"><strong>{#RUBRIK_FIELD_NAME#}</strong></td>
			<td align="center"><strong>{#RUBRIK_FIELD_TYPE#}</strong></td>
		</tr>
	</thead>
	<tbody>
		{foreach from=$tags item=tag}
			<tr>
				<td align="center">{$tag->Id}</td>
				<td width="10%"><a class="rightDir" title="{#RUBRIK_INSERT_HELP#}" href="javascript:void(0);" onclick="textSelection('[tag:fld:{if $tag->rubric_field_alias}{$tag->rubric_field_alias}{else}{$tag->Id}{/if}]', '');"><strong>[tag:fld:{if $tag->rubric_field_alias}{$tag->rubric_field_alias}{else}{$tag->Id}{/if}]</strong></a></td>
				<td width="10%"><strong>{$tag->rubric_field_title}</strong></td>
				<td width="10%">
					{section name=feld loop=$feld_array}
						{if $tag->rubric_field_type == $feld_array[feld].id}{$feld_array[feld].name}{/if}
					{/section}
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>
		<div class="rowElem">
			<input type="hidden" name="Id" value="{$smarty.request.Id|escape}" />
			<input class="basicBtn" type="submit" value="{#RUBRIK_BUTTON_TPL#}" />
			{#RUBRIK_OR#}
			<input type="submit" class="blackBtn SaveEdit" name="next_edit" value="{#RUBRIK_BUTTON_TPL_NEXT#}" />
		</div>
	<div class="fix"></div>
</div>


<div class="widget first">
<div class="head{if $row->rubric_teaser_template == ""} closed active{/if}"><h5>{#RUBRIK_HTML_3#}</h5></div>
<div style="display: block;">
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<col width="18%" />
<col width="82%" />
	<thead>
		<tr class="noborder">
			<td>{#RUBRIK_TAGS#}</td>
			<td>{#RUBRIK_HTML_T#}</td>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td><strong><a title="{#REQUEST_RUB_INFO#}" class="rightDir" href="javascript:void(0);" onclick="jAlert('{#REQUEST_SELECT_IN_LIST#}','{#REQUEST_TEMPLATE_ITEMS#}');">[tag:rfld:ID][XXX]</a></strong></td>
		<td rowspan="13"><textarea {$dis} name="rubric_teaser_template" id="rubric_teaser_template" wrap="off" style="width:100%; height:340px">{$row->rubric_teaser_template|escape|default:''}</textarea></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_DOCID_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection3('[tag:docid]', '');">[tag:docid]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_DOCTITLE_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection3('[tag:doctitle]', '');">[tag:doctitle]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_LINK_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection3('[tag:link]', '');">[tag:link]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_DOCDATE_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection3('[tag:docdate]', '');">[tag:docdate]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_DOCTIME_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection3('[tag:doctime]', '');">[tag:doctime]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_DATE_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection3('[tag:date:', ']');">[tag:date:X]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_DOCAUTHOR_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection3('[tag:docauthor]', '');">[tag:docauthor]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_VIEWS_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection3('[tag:docviews]', '');">[tag:docviews]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_COMMENTS_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection3('[tag:doccomments]', '');">[tag:doccomments]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_PATH#}" class="rightDir" href="javascript:void(0);" onclick="textSelection3('[tag:path]', '');">[tag:path]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_MEDIAPATH#}" class="rightDir" href="javascript:void(0);" onclick="textSelection3('[tag:mediapath]', '');">[tag:mediapath]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_THUMBNAIL#}" class="rightDir" href="javascript:void(0);" onclick="textSelection3('[tag:X000x000:YYY]', '');">[tag:X000x000:[tag:rfld:XXX][XXX]]</a></strong></td>
	</tr>
    <tr>
    	<td><strong>HTML Tags</strong></td>
    	<td>
        |&nbsp;
        <a href="javascript:void(0);" onclick="textSelection3('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
        <a href="javascript:void(0);" onclick="textSelection3('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
        <a href="javascript:void(0);" onclick="textSelection3('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection3('\t', '');"><strong>TAB</strong></a>&nbsp;|
    	</td>
    </tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<col width="3%" />
<col width="15%" />
<col width="25%" />
<col width="57%" />
	<thead>
		<tr>
			<td align="center"><strong>{#RUBRIK_ID#}</strong></td>
			<td align="center"><strong>{#RUBRIK_TAGS#}</strong></td>
			<td align="center"><strong>{#RUBRIK_FIELD_NAME#}</strong></td>
			<td align="center"><strong>{#RUBRIK_FIELD_TYPE#}</strong></td>
		</tr>
	</thead>
	<tbody>
		{foreach from=$tags item=tag}
			<tr>
				<td align="center">{$tag->Id}</td>
				<td><a class="rightDir" title="{#RUBRIK_INSERT_HELP#}" href="javascript:void(0);" onclick="textSelection3('[tag:rfld:{if $tag->rubric_field_alias}{$tag->rubric_field_alias}{else}{$tag->Id}{/if}][150]', '');"><strong>[tag:rfld:{if $tag->rubric_field_alias}{$tag->rubric_field_alias}{else}{$tag->Id}{/if}][150]</strong></a></td>
				<td><strong>{$tag->rubric_field_title}</strong></td>
				<td>
					{section name=feld loop=$feld_array}
						{if $tag->rubric_field_type == $feld_array[feld].id}{$feld_array[feld].name}{/if}
					{/section}
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>
		<div class="rowElem">
			<input type="hidden" name="Id" value="{$smarty.request.Id|escape}" />
			<input class="basicBtn" type="submit" value="{#RUBRIK_BUTTON_TPL#}" />
			{#RUBRIK_OR#}
			<input type="submit" class="blackBtn SaveEdit" name="next_edit" value="{#RUBRIK_BUTTON_TPL_NEXT#}" />
		</div>

	<div class="fix"></div>
</div>
</div>

<div class="widget first">
<div class="head closed active"><h5>{#RUBRIK_HTML_4#}</h5></div>
<div style="display: block;">
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<col width="18%" />
<col width="82%" />
	<thead>
		<tr class="noborder">
			<td>{#RUBRIK_TAGS#}</td>
			<td>{#RUBRIK_HTML_T#}</td>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td><strong><a title="{#REQUEST_RUB_INFO#}" class="rightDir" href="javascript:void(0);" onclick="jAlert('{#REQUEST_SELECT_IN_LIST#}','{#REQUEST_TEMPLATE_ITEMS#}');">[tag:rfld:ID][XXX]</a></strong></td>
		<td rowspan="13"><textarea {$dis} name="rubric_admin_teaser_template" id="rubric_admin_teaser_template" wrap="off" style="width:100%; height:340px">{$row->rubric_admin_teaser_template|escape|default:''}</textarea></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_DOCID_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection4('[tag:docid]', '');">[tag:docid]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_DOCTITLE_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection4('[tag:doctitle]', '');">[tag:doctitle]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_LINK_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection4('[tag:link]', '');">[tag:link]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_DOCDATE_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection4('[tag:docdate]', '');">[tag:docdate]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_DOCTIME_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection4('[tag:doctime]', '');">[tag:doctime]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_DATE_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection4('[tag:date:', ']');">[tag:date:X]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_DOCAUTHOR_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection4('[tag:docauthor]', '');">[tag:docauthor]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_VIEWS_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection4('[tag:docviews]', '');">[tag:docviews]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_COMMENTS_INFO#}" class="rightDir" href="javascript:void(0);" onclick="textSelection4('[tag:doccomments]', '');">[tag:doccomments]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_PATH#}" class="rightDir" href="javascript:void(0);" onclick="textSelection4('[tag:path]', '');">[tag:path]</a></strong></td>
	</tr>
	<tr>
		<td><strong><a title="{#REQUEST_MEDIAPATH#}" class="rightDir" href="javascript:void(0);" onclick="textSelection4('[tag:mediapath]', '');">[tag:mediapath]</a></strong></td>
	</tr>
	<tr>
		<td>
			<strong><a title="{#REQUEST_THUMBNAIL#}" class="rightDir" href="javascript:void(0);" onclick="textSelection4('[tag:X000x000:YYY]', '');">[tag:X000x000:[tag:rfld:XXX][XXX]]</a></strong>
		</td>
	</tr>
    <tr>
    	<td><strong>HTML Tags</strong></td>
    	<td>
        |&nbsp;
        <a href="javascript:void(0);" onclick="textSelection4('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
        <a href="javascript:void(0);" onclick="textSelection4('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
        <a href="javascript:void(0);" onclick="textSelection4('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('\t', '');"><strong>TAB</strong></a>&nbsp;|&nbsp;
		<a href="javascript:void(0);" onclick="textSelection4('<img src=&quot;[tag:c50x50:[tag:rfld:XXX][img]]&quot; style=&quot;float: left; margin-right: 15px;&quot; alt=&quot;&quot; class=&quot;rounded&quot;/>\r\n<h6>[tag:doctitle]</h6>\r\n[tag:rfld:XXX][-100]\r\n', '');"><strong>Default Teaser</strong></a>&nbsp;|
    	</td>
    </tr>
</tbody>
</table>

<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<col width="3%" />
<col width="15%" />
<col width="25%" />
<col width="57%" />
	<thead>
		<tr>
			<td align="center"><strong>{#RUBRIK_ID#}</strong></td>
			<td align="center"><strong>{#RUBRIK_TAGS#}</strong></td>
			<td align="center"><strong>{#RUBRIK_FIELD_NAME#}</strong></td>
			<td align="center"><strong>{#RUBRIK_FIELD_TYPE#}</strong></td>
		</tr>
	</thead>
	<tbody>
		{foreach from=$tags item=tag}
			<tr>
				<td align="center">{$tag->Id}</td>
				<td><a class="rightDir" title="{#RUBRIK_INSERT_HELP#}" href="javascript:void(0);" onclick="textSelection4('[tag:rfld:{if $tag->rubric_field_alias}{$tag->rubric_field_alias}{else}{$tag->Id}{/if}][150]', '');"><strong>[tag:rfld:{if $tag->rubric_field_alias}{$tag->rubric_field_alias}{else}{$tag->Id}{/if}][150]</strong></a></td>
				<td><strong>{$tag->rubric_field_title}</strong></td>
				<td>
					{section name=feld loop=$feld_array}
						{if $tag->rubric_field_type == $feld_array[feld].id}{$feld_array[feld].name}{/if}
					{/section}
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>
		<div class="rowElem">
			<input type="hidden" name="Id" value="{$smarty.request.Id|escape}" />
			<input class="basicBtn" type="submit" value="{#RUBRIK_BUTTON_TPL#}" />
			{#RUBRIK_OR#}
			<input type="submit" class="blackBtn SaveEdit" name="next_edit" value="{#RUBRIK_BUTTON_TPL_NEXT#}" />
		</div>

	<div class="fix"></div>
</div>
</div>
</form>
<div class="fix"></div>
    <script language="Javascript" type="text/javascript">
    var sett_options = {ldelim}
		url: '{$formaction}',
		beforeSubmit: Request,
        success: Response
	{rdelim}

	function Request(){ldelim}
		$.alerts._overlay('show');
	{rdelim}

	function Response(){ldelim}
		$.alerts._overlay('hide');
		$.jGrowl('{#RUBRIK_TEMPLATE_SAVED#}',{ldelim}theme: 'accept'{rdelim});
	{rdelim}

	$(document).ready(function(){ldelim}

		Mousetrap.bind(['ctrl+s', 'meta+s'], function(e) {ldelim}
		    if (e.preventDefault) {ldelim}
		        e.preventDefault();
		    {rdelim} else {ldelim}
		        // internet explorer
		        e.returnValue = false;
		    {rdelim}
		    $("#f_tpl").ajaxSubmit(sett_options);
			return false;
		{rdelim});

	    $(".SaveEdit").click(function(e){ldelim}
		    if (e.preventDefault) {ldelim}
		        e.preventDefault();
		    {rdelim} else {ldelim}
		        // internet explorer
		        e.returnValue = false;
		    {rdelim}
		    $("#f_tpl").ajaxSubmit(sett_options);
			return false;
		{rdelim});

	{rdelim});

{literal}
      var editor = CodeMirror.fromTextArea(document.getElementById("rubric_template"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#f_tpl").ajaxSubmit(sett_options);}},
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
	  editor.setSize("100%", 400);
      function getSelectedRange() {
        return { from: editor.getCursor(true), to: editor.getCursor(false) };
      }

      function textSelection(startTag,endTag) {
        var range = getSelectedRange();
        editor.replaceRange(startTag + editor.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor.setCursor(range.from.line, range.from.ch + startTag.length);
      }

	  var hlLine = editor.setLineClass(0, "activeline");


      var editor2 = CodeMirror.fromTextArea(document.getElementById("rubric_header_template"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#f_tpl").ajaxSubmit(sett_options);}},
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
		  editor2.setLineClass(hlLine2, null, null);
		  hlLine2 = editor2.setLineClass(editor2.getCursor().line, null, "activeline");
		}
      });
	  editor2.setSize("100%", 280);
      function getSelectedRange2() {
        return { from: editor2.getCursor(true), to: editor2.getCursor(false) };
      }

      function textSelection2(startTag,endTag) {
        var range = getSelectedRange2();
        editor2.replaceRange(startTag + editor2.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor2.setCursor(range.from.line, range.from.ch + startTag.length);
      }

      var hlLine2 = editor2.setLineClass(0, "activeline");

      var editor3 = CodeMirror.fromTextArea(document.getElementById("rubric_teaser_template"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#f_tpl").ajaxSubmit(sett_options);}},
        lineNumbers: true,
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor3.save();},
		onCursorActivity: function() {
		  editor3.setLineClass(hlLine3, null, null);
		  hlLine3 = editor3.setLineClass(editor3.getCursor().line, null, "activeline");
		}
      });
	  editor3.setSize("100%", 420);
      function getSelectedRange3() {
        return { from: editor3.getCursor(true), to: editor3.getCursor(false) };
      }

      function textSelection3(startTag,endTag) {
        var range = getSelectedRange3();
        editor3.replaceRange(startTag + editor3.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor3.setCursor(range.from.line, range.from.ch + startTag.length);
      }

      var hlLine3 = editor3.setLineClass(0, "activeline");

      var editor4 = CodeMirror.fromTextArea(document.getElementById("rubric_admin_teaser_template"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#f_tpl").ajaxSubmit(sett_options);}},
        lineNumbers: true,
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor4.save();},
		onCursorActivity: function() {
		  editor4.setLineClass(hlLine4, null, null);
		  hlLine4 = editor4.setLineClass(editor4.getCursor().line, null, "activeline");
		}
      });
	  editor4.setSize("100%", 420);
      function getSelectedRange4() {
        return { from: editor4.getCursor(true), to: editor4.getCursor(false) };
      }

      function textSelection4(startTag,endTag) {
        var range = getSelectedRange4();
        editor4.replaceRange(startTag + editor4.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor4.setCursor(range.from.line, range.from.ch + startTag.length);
      }

      var hlLine4 = editor4.setLineClass(0, "activeline");
{/literal}
    </script>

