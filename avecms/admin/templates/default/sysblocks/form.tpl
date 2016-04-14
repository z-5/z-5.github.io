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

<div class="title"><h5>{#SYSBLOCK_INSERT_H#}</h5></div>

<div class="widget" style="margin-top: 0px;">
    <div class="body">
		{#SYSBLOCK_INSERT#}
    </div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li><a href="index.php?do=sysblocks&cp={$sess}" title="">{#SYSBLOCK_LIST_LINK#}</a></li>
	        <li>{if $smarty.request.id != ''}{#SYSBLOCK_EDIT_H#}{else}{#SYSBLOCK_INSERT_H#}{/if}</li>
	        <li><strong class="code">{if $smarty.request.id != ''}{$sysblock_name|escape}{else}{$smarty.request.sysblock_name}{/if}</strong></li>
	    </ul>
	</div>
</div>

<form id="sysblock" action="index.php?do=sysblocks&action=save&cp={$sess}" method="post" class="mainForm">

<div class="widget first">
<div class="head"><h5 class="iFrames">{if $smarty.request.id != ''}{#SYSBLOCK_EDIT_H#}{else}{#SYSBLOCK_INSERT_H#}{/if}</h5></div>


<div class="rowElem noborder">
	<label>{#SYSBLOCK_NAME#}</label>
	<div class="formRight"><input name="sysblock_name" class="mousetrap" type="text" value="{if $smarty.request.id != ''}{$sysblock_name|escape}{else}{$smarty.request.sysblock_name}{/if}" size="80" /></div>
	<div class="fix"></div>
</div>

</div>

<div class="widget first">
<div class="head"><h5 class="iFrames">{#SYSBLOCK_HTML#}</h5></div>

<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<tbody>
	<tr class="noborder">
		<td style="width: 200px;">{#SYSBLOCK_TAGS#}</td>
		<td>{#SYSBLOCK_HTML#}</td>
	</tr>


		<tr>
			<td>
				<a class="rightDir" title="{#SYSBLOCK_MEDIAPATH#}" href="javascript:void(0);" onclick="textSelection('[tag:mediapath]','');"><strong>[tag:mediapath]</strong></a>
			</td>
                     <td rowspan="6"><textarea class="mousetrap" id="sysblock_text" name="sysblock_text" style="width: 100%; height: 400px;">{$sysblock_text|escape}</textarea></td>
		</tr>

		<tr>
			<td>
				<a class="rightDir" title="{#SYSBLOCK_PATH#}" href="javascript:void(0);" onclick="textSelection('[tag:path]','');"><strong>[tag:path]</strong></a>
			</td>
		</tr>
		<tr>
			<td>
				<a class="rightDir" title="{#SYSBLOCK_HOME#}" href="javascript:void(0);" onclick="textSelection('[tag:home]','');"><strong>[tag:home]</strong></a>
			</td>
		</tr>
		<tr>
			<td>
				<a class="rightDir" title="{#SYSBLOCK_DOCID_INFO#}" href="javascript:void(0);" onclick="textSelection('[tag:docid]','');"><strong>[tag:docid]</strong></a>
			</td>
		</tr>
		<tr>
			<td>
				<a class="rightDir" title="{#SYSBLOCK_BREADCRUMB#}" href="javascript:void(0);" onclick="textSelection('[tag:breadcrumb]','');"><strong>[tag:breadcrumb]</strong></a>
			</td>
		</tr>

		<tr>
			<td>

			</td>
		</tr>


    <tr>
        <td>{#SYSBLOCK_TAGS_2#}</td>
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
				{if $smarty.request.id != ''}
					<input type="hidden" name="id" value="{$id}">
					<input name="submit" type="submit" class="basicBtn" value="{#SYSBLOCK_SAVEDIT#}" />
				{else}
					<input name="submit" type="submit" class="basicBtn" value="{#SYSBLOCK_SAVE#}" />
				{/if}

				{#SYSBLOCK_OR#}

				{if $smarty.request.action=='edit'}
					<input type="submit" class="blackBtn SaveEdit" name="next_edit" value="{#SYSBLOCK_SAVEDIT_NEXT#}" />
				{else}
					<input type="submit" class="blackBtn" name="next_edit" value="{#SYSBLOCK_SAVE_NEXT#}" />
				{/if}

	<div class="fix"></div>
</div>

</div>

</form>
<script language="javascript">

    var sett_options = {ldelim}
		url: 'index.php?do=sysblocks&action=save&cp={$sess}',
		beforeSubmit: Request,
        success: Response,
		error: Error
	{rdelim}

	function Request(){ldelim}
		$.alerts._overlay('show');
	{rdelim}

	function Response(){ldelim}
		$.alerts._overlay('hide');
		$.jGrowl('{#SYSBLOCK_SAVED#}', {ldelim}theme: 'accept'{rdelim});
	{rdelim}

	function Error(){ldelim}
		$.alerts._overlay('hide');
		$.jGrowl('Запрос не выполнен', {ldelim}theme: 'error'{rdelim});
	{rdelim}

	$(document).ready(function(){ldelim}

		Mousetrap.bind(['ctrl+s', 'meta+s'], function(e) {ldelim}
		    if (e.preventDefault) {ldelim}
		        e.preventDefault();
		    {rdelim} else {ldelim}
		        // internet explorer
		        e.returnValue = false;
		    {rdelim}
		    $("#sysblock").ajaxSubmit(sett_options);
			return false;
		{rdelim});

	    $(".SaveEdit").click(function(e){ldelim}
		    if (e.preventDefault) {ldelim}
		        e.preventDefault();
		    {rdelim} else {ldelim}
		        // internet explorer
		        e.returnValue = false;
		    {rdelim}
		    $("#sysblock").ajaxSubmit(sett_options);
			return false;
		{rdelim});

	{rdelim});

	{literal}
      var editor = CodeMirror.fromTextArea(document.getElementById("sysblock_text"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#sysblock").ajaxSubmit(sett_options);}},
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

