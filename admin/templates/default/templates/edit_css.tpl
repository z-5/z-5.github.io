<link rel="stylesheet" href="{$ABS_PATH}admin/codemirror/lib/codemirror.css">

<script src="{$ABS_PATH}admin/codemirror/lib/codemirror.js" type="text/javascript"></script>
<script src="{$ABS_PATH}admin/codemirror/mode/css/css.js"></script>

{literal}
    <style type="text/css">
      .activeline {background: #e8f2ff !important;}
    </style>
{/literal}

<div class="title"><h5>{#TEMPLATES_CSS_EDITOR#}</h5></div>

<div class="widget" style="margin-top: 0px;">
    <div class="body">
		{#TEMPLATES_CSS_TITLE#}
    </div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li><a href="index.php?do=templates&cp={$sess}" title="">{#TEMPLATES_SUB_TITLE#}</a></li>
	        <li>{#TEMPLATES_CSS_EDITOR#}</li>
			<li><strong class="code">{$smarty.request.name_file|escape}</strong></li>
	    </ul>
	</div>
</div>

<form id="code_templ" method="post" action="{$formaction}" class="mainForm">

<div class="widget first">
<div class="head"><h5 class="iFrames">{$smarty.request.name_file|escape}</h5></div>

<div class="rowElem" style="padding: 0">
					<textarea id="code_text" name="code_text">{$code_text|escape}</textarea>
	<div class="fix"></div>
</div>

<div class="rowElem">
<button class="basicBtn">{if $smarty.request.action=='new'}{#TEMPLATES_BUTTON_ADD#}{else}{#TEMPLATES_BUTTON_SAVE#}{/if}</button>
{#TEMPLATES_OR#}
<input type="submit" class="blackBtn SaveEdit" name="next_edit" value="{#TEMPLATES_BUTTON_SAVE_NEXT#}" />
	<div class="fix"></div>
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
		$.jGrowl('{#TEMPLATES_FILE_SAVED#}',{ldelim}theme: 'accept'{rdelim});
	{rdelim}

	$(document).ready(function(){ldelim}

		Mousetrap.bind(['ctrl+s', 'meta+s'], function(e) {ldelim}
		    if (e.preventDefault) {ldelim}
		        e.preventDefault();
		    {rdelim} else {ldelim}
		        // internet explorer
		        e.returnValue = false;
		    {rdelim}
		    $("#code_templ").ajaxSubmit(sett_options);
			return false;
		{rdelim});

	    $(".SaveEdit").click(function(e){ldelim}
		    if (e.preventDefault) {ldelim}
		        e.preventDefault();
		    {rdelim} else {ldelim}
		        // internet explorer
		        e.returnValue = false;
		    {rdelim}
		    $("#code_templ").ajaxSubmit(sett_options);
			return false;
		{rdelim});

	{rdelim});

{literal}
      var editor = CodeMirror.fromTextArea(document.getElementById("code_text"), {
      	extraKeys: {"Ctrl-S": function(cm){$("#code_templ").ajaxSubmit(sett_options);}},
        lineNumbers: true,
		lineWrapping: true,
        matchBrackets: true,
        mode: "text/css",
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