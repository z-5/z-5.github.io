<script type="text/javascript">
	$(document).ready(function(){ldelim}
		$('#document_published').datepicker({ldelim}
			changeMonth: true,
        	changeYear: true,

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
		    {rdelim},
		    onSelect: function (selectedDateTime){ldelim}
		        var start = $(this).datetimepicker('getDate');
		        $('#document_expire').datetimepicker('option', 'minDate', new Date(start.getTime()));
		    {rdelim}
		{rdelim});

		{literal}
		$('.collapsible').collapsible({
			defaultOpen: 'opened',
			cssOpen: 'inactive',
			cssClose: 'normal',
			cookieName: 'collaps_doc',
			cookieOptions: {
		        expires: 7,
				domain: ''
	    	},
			speed: 200
		});
		{/literal}

		$('#document_expire').datepicker({ldelim}
			changeMonth: true,
        	changeYear: true,

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
	{rdelim});
</script>


<form method="get" id="doc_search" action="index.php" class="mainForm">
	<input type="hidden" name="do" value="docs" />
	{if $smarty.request.action}<input type="hidden" name="action" value="{$smarty.request.action}" />
	{/if}{if $smarty.request.target}<input type="hidden" name="target" value="{$smarty.request.target}" />
	{/if}{if $smarty.request.doc}<input type="hidden" name="doc" value="{$smarty.request.doc}" />
	{/if}{if $smarty.request.document_alias}<input type="hidden" name="document_alias" value="{$smarty.request.document_alias}" />
	{/if}{if $smarty.request.selurl}<input type="hidden" name="selurl" value="{$smarty.request.selurl}" />
	{/if}{if $smarty.request.idonly}<input type="hidden" name="idonly" value="{$smarty.request.idonly}" />
	{/if}{if $smarty.request.sort}<input type="hidden" name="sort" value="{$smarty.request.sort}" />
	{/if}{if $smarty.request.pop}<input type="hidden" name="pop" value="{$smarty.request.pop}" />
	{/if}<input type="hidden" name="TimeSelect" value="1" />

<div class="widget first">
	<div class="head collapsible" id="opened"><h5>{#MAIN_SEARCH_DOCUMENTS#}</h5></div>
	<div style="display: block;">

<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<col width="150">
		<col width="120">
		<col width="160">
		<col>
		<col width="120">
		<col width="400">
		<tr class="noborder">
			<td rowspan="2"><strong>{#MAIN_TIME_PERIOD#}</strong></td>
			<td>
				<div class="pr12"><input id="document_published" name="document_published" type="text" value="{$smarty.request.document_published|date_format:"%d.%m.%Y"}" placeholder="{#MAIN_TIME_START#}" /></div>
			</td>
			<td><strong>{#MAIN_TITLE_SEARCH#}&nbsp;<a href="javascript:void(0);" style="cursor:help;"  class="topDir link" title="{#MAIN_SEARCH_HELP#}">[?]</a></strong></td>
			<td>
				<div class="pr12"><input type="text" name="QueryTitel" value="{$smarty.request.QueryTitel|escape|stripslashes}" placeholder="{#MAIN_TITLE_DOC_NAME#}" /></div>
			</td>
			<td><strong>{#MAIN_SELECT_RUBRIK#}</strong></td>
			<td>
				<select name="rubric_id" id="rubric_id">
					<option value="all">{#MAIN_ALL_RUBRUKS#}</option>
					{foreach from=$rubrics item=rubric}
						<option value="{$rubric->Id}" {if $smarty.request.rubric_id==$rubric->Id}selected{/if}>{$rubric->rubric_title|escape}</option>
					{/foreach}
				</select>
			</td>
		</tr>

		<tr>
			<td>
				<div class="pr12"><input id="document_expire" name="document_expire" type="text" value="{$smarty.request.document_expire|date_format:"%d.%m.%Y"}" placeholder="{#MAIN_TIME_END#}" /></div>
			</td>
			<td><strong>{#MAIN_ID_SEARCH#}</strong></td>
			<td><input style="width:80px" type="text" name="doc_id" value="{$smarty.request.doc_id|escape|stripslashes}" placeholder="{#MAIN_TITLE_DOC_ID#}" /></td>
			<td><strong>{#MAIN_DOCUMENT_STATUS#}</strong></td>
			<td>
				<select style="width:185px" name="status">
					<option value="All">{#MAIN_ALL_DOCUMENTS#}</option>
					<option value="Opened" {if $smarty.request.status=='Opened'}selected{/if}>{#MAIN_DOCUMENT_ACTIVE#}</option>
					<option value="Closed" {if $smarty.request.status=='Closed'}selected{/if}>{#MAIN_DOCUMENT_INACTIVE#}</option>
					<option value="Deleted" {if $smarty.request.status=='Deleted'}selected{/if}>{#MAIN_TEMP_DELETE_DOCS#}</option>
				</select>
			</td>
		</tr>

		<tr>
			<td colspan="6"><label>{#MAIN_RESULTS_ON_PAGE#}</label>&nbsp;
				<select style="width:70px" name="Datalimit">
					{section loop=150 name=dl step=15}
						<option value="{$smarty.section.dl.index+15}" {if $smarty.request.Datalimit==$smarty.section.dl.index+15}selected{/if}>{$smarty.section.dl.index+15}</option>
					{/section}
				</select>
				&nbsp;
				<input type="submit" class="basicBtn" value="{#MAIN_BUTTON_SEARCH#}" />
			</td>
		</tr>
	</table>
	<input type="hidden" name="cp" value="{$sess}" />

	</div>
</div>

</form>
