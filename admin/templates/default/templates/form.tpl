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
      .CodeMirror-scroll {height: 640px;}
    </style>
{/literal}

{if $smarty.request.action=='new'}
	<div class="title"><h5>{#TEMPLATES_TITLE_NEW#}</h5></div>
	<div class="widget" style="margin-top: 0px;"><div class="body">{#TEMPLATES_WARNING2#}</div></div>
{else}
	<div class="title"><h5>{#TEMPLATES_TITLE_EDIT#}</h5></div>
	<div class="widget" style="margin-top: 0px;"><div class="body">{#TEMPLATES_WARNING1#}</div></div>
{/if}

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li><a href="index.php?do=templates&cp={$sess}" title="">{#TEMPLATES_SUB_TITLE#}</a></li>
			<li><strong class="code">{$row->template_title|escape}{$smarty.request.TempName|escape}</strong></li>
	    </ul>
	</div>
</div>

{foreach from=$errors item=e}
{assign var=message value=$e}
<ul>
<li>{$message}</li>
</ul>
{/foreach}
{*
{if $smarty.request.action=='new'}
<div class="widget first">
<div class="head"><h5 class="iFrames">{#TEMPLATES_LOAD_INFO#}</h5></div>
	<div class="rowElem noborder">
	<form action="index.php?do=templates&action=new" method="post" class="mainForm">
		<select name="theme_pref" style="width: 250px;">
		<option>&nbsp;</option>
			{$sel_theme}
		</select>
		&nbsp;<input type="hidden" name="TempName" value="{$smarty.request.TempName|escape:html}">
		&nbsp;<input type="submit" class="redBtn" value="{#TEMPLATES_BUTTON_LOAD#}">
	</form>
	</div>
	<div class="fix"></div>
</div>
{/if}
*}
<form name="f_tpl" id="f_tpl" method="post" action="{$formaction}" class="mainForm">

<div class="widget first">
<div class="head"><h5 class="iFrames">{#TEMPLATES_TITLE_EDIT#}</h5></div>

<div class="rowElem noborder">
	<label>{#TEMPLATES_NAME#}</label>
	<div class="formRight"><input name="template_title" type="text" value="{$row->template_title|escape:html}{$smarty.request.TempName|escape:html}" maxlength="50" style="width: 250px;" class="mousetrap" /></div>
	<div class="fix"></div>
</div>

</div>

{if $php_forbidden==1}
<ul>
	<li>{#TEMPLATES_USE_PHP#}</li>
</ul>
{/if}

<div class="widget first">
<div class="head"><h5 class="iFrames">{#TEMPLATES_HTML#}</h5></div>
			<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
				<tbody>
					<tr class="noborder">
						<td style="width: 200px;">{#TEMPLATES_TAGS#}</td>
						<td>{#TEMPLATES_HTML#}</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_THEME_FOLDER#}" href="javascript:void(0);" onclick="textSelection('[tag:theme:',']');">[tag:theme:folder]</a></strong>
						</td>
                        <td rowspan="21"><textarea {$read_only} class="{if $php_forbidden==1}tpl_code_readonly{else}{/if}" wrap="off" style="width:100%; height:100%;" name="template_text" id="template_text">{$row->template_text|default:$prefab|escape}</textarea></td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_PAGENAME#}" href="javascript:void(0);" onclick="textSelection('[tag:sitename]','');">[tag:sitename]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_RUBHEADER#}" href="javascript:void(0);" onclick="textSelection('[tag:rubheader]','');">[tag:rubheader]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_TITLE#}" href="javascript:void(0);" onclick="textSelection('[tag:title]','');">[tag:title]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_KEYWORDS#}" href="javascript:void(0);" onclick="textSelection('[tag:keywords]','');">[tag:keywords]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_DESCRIPTION#}" href="javascript:void(0);" onclick="textSelection('[tag:description]','');">[tag:description]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_INDEXFOLLOW#}" href="javascript:void(0);" onclick="textSelection('[tag:robots]','');">[tag:robots]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_CANONICAL#}" href="javascript:void(0);" onclick="textSelection('[tag:canonical]','');">[tag:canonical]</a></strong>
						</td>
					</tr>
					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_PATH#}" href="javascript:void(0);" onclick="textSelection('[tag:path]','');">[tag:path]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_MEDIAPATH#}" href="javascript:void(0);" onclick="textSelection('[tag:mediapath]','');">[tag:mediapath]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_CSS#}" href="javascript:void(0);" onclick="textSelection('[tag:css:]','');">[tag:css:FFF:P]</a></strong>,&nbsp;&nbsp;
                            <strong><a class="rightDir" title="{#TEMPLATES_JS#}" href="javascript:void(0);" onclick="textSelection('[tag:js:]','');">[tag:js:FFF:P]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_MAINCONTENT#}" href="javascript:void(0);" onclick="textSelection('[tag:maincontent]','');">[tag:maincontent]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_DOCUMENT#}" href="javascript:void(0);" onclick="textSelection('[tag:document]','');">[tag:document]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_PRINTLINK#}" href="javascript:void(0);" onclick="textSelection('[tag:printlink]','');">[tag:printlink]</a></strong>
						</td>

					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_HOME#}" href="javascript:void(0);" onclick="textSelection('[tag:home]','');">[tag:home]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_BREADCRUMB#}" href="javascript:void(0);" onclick="textSelection('[tag:breadcrumb]','');">[tag:breadcrumb]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_VERSION#}" href="javascript:void(0);" onclick="textSelection('[tag:version]','');">[tag:version]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_NAVIGATION#}" href="javascript:void(0);" onclick="textSelection('[tag:navigation:]','');">[tag:navigation:XXX]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_QUICKFINDER#}" href="javascript:void(0);" onclick="textSelection('[mod_quickfinder:]','');">[mod_quickfinder:XXX]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_IF_PRINT#}" href="javascript:void(0);" onclick="textSelection('[tag:if_print]\n','\n[/tag:if_print]');">[tag:if_print][/tag:if_print]</a></strong>
						</td>
					</tr>

					<tr>
						<td>
							<strong><a class="rightDir" title="{#TEMPLATES_DONOT_PRINT#}" href="javascript:void(0);" onclick="textSelection('[tag:if_notprint]\n','\n[/tag:if_notprint]');">[tag:if_notprint][/tag:if_notprint]</a></strong>
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
				</tbody>
			</table>

	<div class="rowElem">
		<input type="hidden" name="Id" value="{$smarty.request.Id}">
		{if $smarty.request.action=='new'}
			<input class="basicBtn" type="submit" value="{#TEMPLATES_BUTTON_ADD#}" />
		{else}
			<input class="basicBtn" type="submit" value="{#TEMPLATES_BUTTON_SAVE#}" />
		{/if}
      	{#TEMPLATES_OR#}
		{if $smarty.request.action=='edit'}
			<input type="submit" class="blackBtn SaveEdit" name="next_edit" value="{#TEMPLATES_BUTTON_SAVE_NEXT#}" />
		{else}
			<input type="submit" class="blackBtn" name="next_edit" value="{#TEMPLATES_BUTTON_ADD_NEXT#}" />
		{/if}
	</div>

</div>
</form>

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
		$.jGrowl('{#TEMPLATES_SAVED#}',{ldelim}theme: 'accept'{rdelim});
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
      var editor = CodeMirror.fromTextArea(document.getElementById("template_text"), {
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

      function getSelectedRange() {
        return { from: editor.getCursor(true), to: editor.getCursor(false) };
      }

      function textSelection(startTag,endTag) {
        var range = getSelectedRange();
        editor.replaceRange(startTag + editor.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor.setCursor(range.from.line, range.from.ch + startTag.length);
      }

	  var hlLine = editor.setLineClass(0, "activeline");
{/literal}
    </script>


