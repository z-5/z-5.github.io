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
      .CodeMirror-scroll {height: 200px;}
    </style>
{/literal}


{if $smarty.request.action == 'new'}
<div class="title"><h5>{#NAVI_SUB_TITLE4#}</h5></div>
{else}
<div class="title"><h5>{#NAVI_SUB_TITLE3#}</h5></div>
{/if}
<div class="widget" style="margin-top: 0px;"><div class="body">{#NAVI_TIP_TEMPLATE2#}</div></div>


<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
			<li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li><a href="index.php?do=navigation&cp={$sess}" title="">{#NAVI_SUB_TITLE#}</a></li>
			{if $smarty.request.action == 'new'}
	        <li>{#NAVI_SUB_TITLE4#}</li>
			{else}
	        <li>{#NAVI_SUB_TITLE3#}</li>
	        <li><strong class="code">{$nav->navi_titel|escape}</strong></li>
			{/if}
	    </ul>
	</div>
</div>

<form name="navitemplate" id="navitemplate" method="post" action="{$formaction}" class="mainForm">

<div class="widget first">
<div class="head"><h5 class="iFrames">{#NAVI_SUB_TITLE3#}</h5><div class="num"><a class="basicNum" href="index.php?do=navigation&action=entries&cp={$sess}&id={$smarty.request.id}">{#NAVI_EDIT_ITEMS#}</a></div></div>

	<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<tr class="noborder">
			<td width="200"><strong>{#NAVI_TITLE#}</strong></td>
			<td><input class="mousetrap" style="width:400px" name="navi_titel" type="text" id="navi_titel" value="{$nav->navi_titel|default:$smarty.request.NaviName|escape}"></td>
		</tr>

		<tr>
			<td width="200"><strong>{#NAVI_PRINT_TYPE#}</strong></td>
			<td>
                <select name="navi_expand_ext">
                    <option value="1"{if $nav->navi_expand_ext==1} selected{/if}/>{#NAVI_EXPAND_ALL#}</option>
                    <option value="0"{if $nav->navi_expand_ext==0} selected{/if}/>{#NAVI_EXPAND_WAY#}</option>
                    <option value="2"{if $nav->navi_expand_ext==2} selected{/if}/>{#NAVI_EXPAND_LEVEL#}</option>
                </select></td>
		</tr>

		<tr>
			<td width="200"><strong>{#NAVI_GROUPS#}</strong></td>
			<td>
				<select class="mousetrap select" name="navi_user_group[]" multiple="multiple" size="5" style="width:300px">
					{if $smarty.request.action=='new'}
						{foreach from=$row->AvGroups item=g}
							<option value="{$g->user_group}" selected="selected">{$g->user_group_name|escape}</option>
						{/foreach}
					{else}
						{foreach from=$nav->AvGroups item=g}
							{assign var='sel' value=''}
							{if $g->user_group}
								{if (in_array($g->user_group, $nav->navi_user_group))}
									{assign var='sel' value=' selected="selected"'}
								{/if}
							{/if}
							<option value="{$g->user_group}"{$sel}>{$g->user_group_name|escape}</option>
						{/foreach}
					{/if}
				</select>
			</td>
		</tr>
	</table>
</div>

<div class="widget first">
<div class="head"><h5 class="iFrames">{#NAVI_LEVEL1#}</h5></div>

	<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<tr class="noborder">
			<td><strong>Шаблон уровня</strong><br />
				<strong><a class="rightDir" style="cursor: pointer;" title="Тег для вставки пунктов" onclick="textSelection_1_1('[tag:content]','');">[tag:content]</a></strong></td>
			<td><div class="pr12"><textarea style="width:100%" name="navi_level1begin" rows="12" id="navi_level1tpl">{$nav->navi_level1begin|escape}</textarea></div></td>
		</tr>
        <tr>
			<td>HTML Tags</td>
			<td>
		        |&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_1_1('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_1_1('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_1_1('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_1('\t', '');"><strong>TAB</strong></a>&nbsp;|
			</td>
		</tr>

		<tr>
			<td width="200">
				<strong>{#NAVI_LINK_INACTIVE#}</strong><br />
				<strong><a class="rightDir" style="cursor: pointer;" title="{#NAVI_LINK_ID#}" onclick="textSelection_1_2('[tag:linkid]','');">[tag:linkid]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_NAME#}" onclick="textSelection_1_2('[tag:linkname]','');">[tag:linkname]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_URL#}" onclick="textSelection_1_2('[tag:link]','');">[tag:link]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_TARGET#}" onclick="textSelection_1_2('[tag:target]','');">[tag:target]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Описание пункта меню" onclick="textSelection_1_2('[tag:desc]','');">[tag:desc]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Изображение" onclick="textSelection_1_2('[tag:img]','');">[tag:img]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Изображение активное, в конце названия изображения должно быть _act" onclick="textSelection_1_2('[tag:linkid]','');">[tag:img_act]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Id изображения" onclick="textSelection_1_2('[tag:img_id]','');">[tag:img_id]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Место вставки подуровня" onclick="textSelection_1_2('[tag:level:2]','');">[tag:level:2]</a></strong>
			</td>
			<td><div class="pr12"><textarea style="width:100%" name="navi_level1" rows="12" id="navi_level1">{$nav->navi_level1|escape}</textarea></div></td>
		</tr>
		<tr>
			<td>HTML Tags</td>
			<td>
		        |&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_1_2('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_1_2('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_1_2('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_2('\t', '');"><strong>TAB</strong></a>&nbsp;|
			</td>
		</tr>
		<tr>
			<td width="200">
				<strong>{#NAVI_LINK_ACTIVE#}</strong><br />
				<strong><a class="rightDir" style="cursor: pointer;" title="{#NAVI_LINK_ID#}" onclick="textSelection_1_3('[tag:linkid]','');">[tag:linkid]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_NAME#}" onclick="textSelection_1_3('[tag:linkname]','');">[tag:linkname]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_URL#}" onclick="textSelection_1_3('[tag:link]','');">[tag:link]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_TARGET#}" onclick="textSelection_1_3('[tag:target]','');">[tag:target]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Описание пункта меню" onclick="textSelection_1_3('[tag:desc]','');">[tag:desc]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Изображение" onclick="textSelection_1_3('[tag:img]','');">[tag:img]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Изображение активное, в конце названия изображения должно быть _act" onclick="textSelection_1_3('[tag:linkid]','');">[tag:img_act]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Id изображения" onclick="textSelection_1_3('[tag:img_id]','');">[tag:img_id]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Место вставки подуровня" onclick="textSelection_1_3('[tag:level:2]','');">[tag:level:2]</a></strong>
			</td>
			<td><div class="pr12"><textarea style="width:100%" name="navi_level1active" rows="12" id="navi_level1active">{$nav->navi_level1active|escape}</textarea></div></td>
		</tr>
		<tr>
			<td>HTML Tags</td>
			<td>
		        |&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_1_3('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_1_3('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_1_3('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_1_3('\t', '');"><strong>TAB</strong></a>&nbsp;|
			</td>
		</tr>
	</table>
</div>

<div class="widget first">
<div class="head"><h5 class="iFrames">{#NAVI_LEVEL2#}</h5></div>

	<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<tr class="noborder">
			<td><strong>Шаблон уровня</strong><br />
				<strong><a class="rightDir" style="cursor: pointer;" title="Тег для вставки пунктов" onclick="textSelection_2_1('[tag:content]','');">[tag:content]</a></strong></td>
			<td><div class="pr12"><textarea style="width:100%" name="navi_level2begin" rows="12" id="navi_level2tpl">{$nav->navi_level2begin|escape}</textarea></div></td>
		</tr>
        <tr>
			<td>HTML Tags</td>
			<td>
		        |&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_2_1('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_2_1('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_2_1('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_1('\t', '');"><strong>TAB</strong></a>&nbsp;|
			</td>
		</tr>

		<tr>
			<td width="200">
				<strong>{#NAVI_LINK_INACTIVE#}</strong><br />
				<strong><a class="rightDir" style="cursor: pointer;" title="{#NAVI_LINK_ID#}" onclick="textSelection_2_2('[tag:linkid]','');">[tag:linkid]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_NAME#}" onclick="textSelection_2_2('[tag:linkname]','');">[tag:linkname]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_URL#}" onclick="textSelection_2_2('[tag:link]','');">[tag:link]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_TARGET#}" onclick="textSelection_2_2('[tag:target]','');">[tag:target]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Описание пункта меню" onclick="textSelection_2_2('[tag:desc]','');">[tag:desc]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Изображение" onclick="textSelection_2_2('[tag:img]','');">[tag:img]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Изображение активное, в конце названия изображения должно быть _act" onclick="textSelection_2_2('[tag:linkid]','');">[tag:img_act]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Id изображения" onclick="textSelection_2_2('[tag:img_id]','');">[tag:img_id]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Место вставки подуровня" onclick="textSelection_2_2('[tag:level:3]','');">[tag:level:3]</a></strong>
			</td>
			<td><div class="pr12"><textarea style="width:100%" name="navi_level2" rows="12" id="navi_level2">{$nav->navi_level2|escape}</textarea></div></td>
		</tr>
		<tr>
			<td>HTML Tags</td>
			<td>
		        |&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_2_2('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_2_2('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_2_2('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_2('\t', '');"><strong>TAB</strong></a>&nbsp;|
			</td>
		</tr>
		<tr>
			<td width="200">
				<strong>{#NAVI_LINK_ACTIVE#}</strong><br />
				<strong><a class="rightDir" style="cursor: pointer;" title="{#NAVI_LINK_ID#}" onclick="textSelection_2_3('[tag:linkid]','');">[tag:linkid]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_NAME#}" onclick="textSelection_2_3('[tag:linkname]','');">[tag:linkname]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_URL#}" onclick="textSelection_2_3('[tag:link]','');">[tag:link]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_TARGET#}" onclick="textSelection_2_3('[tag:target]','');">[tag:target]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Описание пункта меню" onclick="textSelection_2_3('[tag:desc]','');">[tag:desc]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Изображение" onclick="textSelection_2_3('[tag:img]','');">[tag:img]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Изображение активное, в конце названия изображения должно быть _act" onclick="textSelection_2_3('[tag:linkid]','');">[tag:img_act]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Id изображения" onclick="textSelection_2_3('[tag:img_id]','');">[tag:img_id]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Место вставки подуровня" onclick="textSelection_2_3('[tag:level:3]','');">[tag:level:3]</a></strong>
			</td>
			<td><div class="pr12"><textarea style="width:100%" name="navi_level2active" rows="12" id="navi_level2active">{$nav->navi_level2active|escape}</textarea></div></td>
		</tr>
		<tr>
			<td>HTML Tags</td>
			<td>
		        |&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_2_3('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_2_3('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_2_3('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_2_3('\t', '');"><strong>TAB</strong></a>&nbsp;|
			</td>
		</tr>
	</table>
</div>

<div class="widget first">
<div class="head"><h5 class="iFrames">{#NAVI_LEVEL3#}</h5></div>

	<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<tr class="noborder">
			<td><strong>Шаблон уровня</strong><br />
				<strong><a class="rightDir" style="cursor: pointer;" title="Тег для вставки пунктов" onclick="textSelection_3_1('[tag:content]','');">[tag:content]</a></strong></td>
			<td><div class="pr12"><textarea style="width:100%" name="navi_level3begin" rows="12" id="navi_level3tpl">{$nav->navi_level3begin|escape}</textarea></div></td>
		</tr>
        <tr>
			<td>HTML Tags</td>
			<td>
		        |&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_3_1('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_3_1('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_3_1('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_1('\t', '');"><strong>TAB</strong></a>&nbsp;|
			</td>
		</tr>

		<tr>
			<td width="200">
				<strong>{#NAVI_LINK_INACTIVE#}</strong><br />
				<strong><a class="rightDir" style="cursor: pointer;" title="{#NAVI_LINK_ID#}" onclick="textSelection_3_2('[tag:linkid]','');">[tag:linkid]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_NAME#}" onclick="textSelection_3_2('[tag:linkname]','');">[tag:linkname]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_URL#}" onclick="textSelection_3_2('[tag:link]','');">[tag:link]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_TARGET#}" onclick="textSelection_3_2('[tag:target]','');">[tag:target]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Описание пункта меню" onclick="textSelection_3_2('[tag:desc]','');">[tag:desc]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Изображение" onclick="textSelection_3_2('[tag:img]','');">[tag:img]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Изображение активное, в конце названия изображения должно быть _act" onclick="textSelection_3_2('[tag:linkid]','');">[tag:img_act]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Id изображения" onclick="textSelection_3_2('[tag:img_id]','');">[tag:img_id]</a></strong>
			</td>
			<td><div class="pr12"><textarea style="width:100%" name="navi_level3" rows="12" id="navi_level3">{$nav->navi_level3|escape}</textarea></div></td>
		</tr>
		<tr>
			<td>HTML Tags</td>
			<td>
		        |&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_3_2('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_3_2('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_3_2('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_2('\t', '');"><strong>TAB</strong></a>&nbsp;|
			</td>
		</tr>
		<tr>
			<td width="200">
				<strong>{#NAVI_LINK_ACTIVE#}</strong><br />
				<strong><a class="rightDir" style="cursor: pointer;" title="{#NAVI_LINK_ID#}" onclick="textSelection_3_3('[tag:linkid]','');">[tag:linkid]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_NAME#}" onclick="textSelection_3_3('[tag:linkname]','');">[tag:linkname]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_URL#}" onclick="textSelection_3_3('[tag:link]','');">[tag:link]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="{#NAVI_LINK_TARGET#}" onclick="textSelection_3_3('[tag:target]','');">[tag:target]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Описание пункта меню" onclick="textSelection_3_3('[tag:desc]','');">[tag:desc]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Изображение" onclick="textSelection_3_3('[tag:img]','');">[tag:img]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Изображение активное, в конце названия изображения должно быть _act" onclick="textSelection_3_3('[tag:linkid]','');">[tag:img_act]</a></strong><br />
				<strong><a class="rightDir"  style="cursor: pointer;" title="Id изображения" onclick="textSelection_3_3('[tag:img_id]','');">[tag:img_id]</a></strong>
			</td>
			<td><div class="pr12"><textarea style="width:100%" name="navi_level3active" rows="12" id="navi_level3active">{$nav->navi_level3active|escape}</textarea></div></td>
		</tr>
		<tr>
			<td>HTML Tags</td>
			<td>
		        |&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_3_3('<ol>', '</ol>');"><strong>OL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_3_3('<ul>', '</ul>');"><strong>UL</strong></a>&nbsp;|&nbsp;
		        <a href="javascript:void(0);" onclick="textSelection_3_3('<li>', '</li>');"><strong>LI</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<p class=&quot;&quot;>', '</p>');"><strong>P</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<strong>', '</strong>');"><strong>B</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<em>', '</em>');"><strong>I</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<h1>', '</h1>');"><strong>H1</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<h2>', '</h2>');"><strong>H2</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<h3>', '</h3>');"><strong>H3</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<h4>', '</h4>');"><strong>H4</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<h5>', '</h5>');"><strong>H5</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<div class=&quot;&quot; id=&quot;&quot;>', '</div>');"><strong>DIV</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<a href=&quot;&quot; title=&quot;&quot;>', '</a>');"><strong>A</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<img src=&quot;&quot; alt=&quot;&quot; />', '');"><strong>IMG</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<span>', '</span>');"><strong>SPAN</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<pre>', '</pre>');"><strong>PRE</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('<br />', '');"><strong>BR</strong></a>&nbsp;|&nbsp;
				<a href="javascript:void(0);" onclick="textSelection_3_3('\t', '');"><strong>TAB</strong></a>&nbsp;|
			</td>
		</tr>
	</table>

<div class="rowElem">
	<input type="submit" class="basicBtn" value="{#NAVI_BUTTON_SAVE#}" />
	{#NAVI_OR_BUTTON#}
	<input type="submit" class="blackBtn SaveEdit" name="next_edit" value="{#NAVI_BUTTON_SAVE_NEXT#}" />

</div>

<div class="fix"></div>

</div>

</form>


    <script language="javascript">
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
		$.jGrowl('{#NAVI_SAVE#}', {ldelim}theme: 'accept'{rdelim});
	{rdelim}

	$(document).ready(function(){ldelim}

		Mousetrap.bind(['ctrl+s', 'meta+s'], function(e) {ldelim}
		    if (e.preventDefault) {ldelim}
		        e.preventDefault();
		    {rdelim} else {ldelim}
		        // internet explorer
		        e.returnValue = false;
		    {rdelim}
		    $("#navitemplate").ajaxSubmit(sett_options);
			return false;
		{rdelim});

	    $(".SaveEdit").click(function(e){ldelim}
		    if (e.preventDefault) {ldelim}
		        e.preventDefault();
		    {rdelim} else {ldelim}
		        // internet explorer
		        e.returnValue = false;
		    {rdelim}
		    $("#navitemplate").ajaxSubmit(sett_options);
			return false;
		{rdelim});

	{rdelim});
{literal}
      var editor_1_1 = CodeMirror.fromTextArea(document.getElementById("navi_level1tpl"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#navitemplate").ajaxSubmit(sett_options);}},
        lineNumbers: true,
        height: "200px",
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor_1_1.save();},
		onCursorActivity: function() {
		  editor_1_1.setLineClass(hlLine1, null, null);
		  hlLine1 = editor_1_1.setLineClass(editor_1_1.getCursor().line, null, "activeline");
		}
      });

      function getSelectedRange_1_1() {
        return { from: editor_1_1.getCursor(true), to: editor_1_1.getCursor(false) };
      }

      function textSelection_1_1(startTag,endTag) {
        var range = getSelectedRange_1_1();
        editor_1_1.replaceRange(startTag + editor_1_1.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor_1_1.setCursor(range.from.line, range.from.ch + startTag.length);
      }

      var editor_1_2 = CodeMirror.fromTextArea(document.getElementById("navi_level1"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#navitemplate").ajaxSubmit(sett_options);}},
        lineNumbers: true,
        height: "200px",
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor_1_2.save();},
		onCursorActivity: function() {
		  editor_1_2.setLineClass(hlLine1, null, null);
		  hlLine1 = editor_1_2.setLineClass(editor_1_2.getCursor().line, null, "activeline");
		}
      });

      function getSelectedRange_1_2() {
        return { from: editor_1_2.getCursor(true), to: editor_1_2.getCursor(false) };
      }

      function textSelection_1_2(startTag,endTag) {
        var range = getSelectedRange_1_2();
        editor_1_2.replaceRange(startTag + editor_1_2.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor_1_2.setCursor(range.from.line, range.from.ch + startTag.length);
      }

      var editor_1_3 = CodeMirror.fromTextArea(document.getElementById("navi_level1active"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#navitemplate").ajaxSubmit(sett_options);}},
        lineNumbers: true,
        height: "200px",
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor_1_3.save();},
		onCursorActivity: function() {
		  editor_1_3.setLineClass(hlLine1, null, null);
		  hlLine1 = editor_1_3.setLineClass(editor_1_3.getCursor().line, null, "activeline");
		}
      });

      function getSelectedRange_1_3() {
        return { from: editor_1_3.getCursor(true), to: editor_1_3.getCursor(false) };
      }

      function textSelection_1_3(startTag,endTag) {
        var range = getSelectedRange_1_3();
        editor_1_3.replaceRange(startTag + editor_1_3.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor_1_3.setCursor(range.from.line, range.from.ch + startTag.length);
      }

      var editor_2_1 = CodeMirror.fromTextArea(document.getElementById("navi_level2tpl"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#navitemplate").ajaxSubmit(sett_options);}},
        lineNumbers: true,
        height: "200px",
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor_2_1.save();},
		onCursorActivity: function() {
		  editor_2_1.setLineClass(hlLine1, null, null);
		  hlLine1 = editor_2_1.setLineClass(editor_2_1.getCursor().line, null, "activeline");
		}
      });

      function getSelectedRange_2_1() {
        return { from: editor_2_1.getCursor(true), to: editor_2_1.getCursor(false) };
      }

      function textSelection_2_1(startTag,endTag) {
        var range = getSelectedRange_2_1();
        editor_2_1.replaceRange(startTag + editor_2_1.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor_2_1.setCursor(range.from.line, range.from.ch + startTag.length);
      }

      var editor_2_2 = CodeMirror.fromTextArea(document.getElementById("navi_level2"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#navitemplate").ajaxSubmit(sett_options);}},
        lineNumbers: true,
        height: "200px",
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor_2_2.save();},
		onCursorActivity: function() {
		  editor_2_2.setLineClass(hlLine1, null, null);
		  hlLine1 = editor_2_2.setLineClass(editor_2_2.getCursor().line, null, "activeline");
		}
      });

      function getSelectedRange_2_2() {
        return { from: editor_2_2.getCursor(true), to: editor_2_2.getCursor(false) };
      }

      function textSelection_2_2(startTag,endTag) {
        var range = getSelectedRange_2_2();
        editor_2_2.replaceRange(startTag + editor_2_2.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor_2_2.setCursor(range.from.line, range.from.ch + startTag.length);
      }

      var editor_2_3 = CodeMirror.fromTextArea(document.getElementById("navi_level2active"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#navitemplate").ajaxSubmit(sett_options);}},
        lineNumbers: true,
        height: "200px",
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor_2_3.save();},
		onCursorActivity: function() {
		  editor_2_3.setLineClass(hlLine1, null, null);
		  hlLine1 = editor_2_3.setLineClass(editor_2_3.getCursor().line, null, "activeline");
		}
      });

      function getSelectedRange_2_3() {
        return { from: editor_2_3.getCursor(true), to: editor_2_3.getCursor(false) };
      }

      function textSelection_2_3(startTag,endTag) {
        var range = getSelectedRange_2_3();
        editor_2_3.replaceRange(startTag + editor_2_3.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor_2_3.setCursor(range.from.line, range.from.ch + startTag.length);
      }
	  
      var editor_3_1 = CodeMirror.fromTextArea(document.getElementById("navi_level3tpl"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#navitemplate").ajaxSubmit(sett_options);}},
        lineNumbers: true,
        height: "200px",
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor_3_1.save();},
		onCursorActivity: function() {
		  editor_3_1.setLineClass(hlLine1, null, null);
		  hlLine1 = editor_3_1.setLineClass(editor_3_1.getCursor().line, null, "activeline");
		}
      });

      function getSelectedRange_3_1() {
        return { from: editor_3_1.getCursor(true), to: editor_3_1.getCursor(false) };
      }

      function textSelection_3_1(startTag,endTag) {
        var range = getSelectedRange_3_1();
        editor_3_1.replaceRange(startTag + editor_3_1.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor_3_1.setCursor(range.from.line, range.from.ch + startTag.length);
      }

      var editor_3_2 = CodeMirror.fromTextArea(document.getElementById("navi_level3"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#navitemplate").ajaxSubmit(sett_options);}},
        lineNumbers: true,
        height: "200px",
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor_3_2.save();},
		onCursorActivity: function() {
		  editor_3_2.setLineClass(hlLine1, null, null);
		  hlLine1 = editor_3_2.setLineClass(editor_3_2.getCursor().line, null, "activeline");
		}
      });

      function getSelectedRange_3_2() {
        return { from: editor_3_2.getCursor(true), to: editor_3_2.getCursor(false) };
      }

      function textSelection_3_2(startTag,endTag) {
        var range = getSelectedRange_3_2();
        editor_3_2.replaceRange(startTag + editor_3_2.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor_3_2.setCursor(range.from.line, range.from.ch + startTag.length);
      }

      var editor_3_3 = CodeMirror.fromTextArea(document.getElementById("navi_level3active"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#navitemplate").ajaxSubmit(sett_options);}},
        lineNumbers: true,
        height: "200px",
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor_3_3.save();},
		onCursorActivity: function() {
		  editor_3_3.setLineClass(hlLine1, null, null);
		  hlLine1 = editor_3_3.setLineClass(editor_3_3.getCursor().line, null, "activeline");
		}
      });

      function getSelectedRange_3_3() {
        return { from: editor_3_3.getCursor(true), to: editor_3_3.getCursor(false) };
      }

      function textSelection_3_3(startTag,endTag) {
        var range = getSelectedRange_3_3();
        editor_3_3.replaceRange(startTag + editor_3_3.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor_3_3.setCursor(range.from.line, range.from.ch + startTag.length);
      }

		var hlLine1 = editor_1_1.setLineClass(0, "activeline");
		var hlLine1 = editor_1_2.setLineClass(0, "activeline");
		var hlLine1 = editor_1_3.setLineClass(0, "activeline");
		var hlLine2 = editor_2_1.setLineClass(0, "activeline");
		var hlLine2 = editor_2_2.setLineClass(0, "activeline");
		var hlLine2 = editor_2_3.setLineClass(0, "activeline");
		var hlLine3 = editor_3_1.setLineClass(0, "activeline");
		var hlLine3 = editor_3_2.setLineClass(0, "activeline");
		var hlLine3 = editor_3_3.setLineClass(0, "activeline");

    </script>
{/literal}