<div class="title"><h5>{#SETTINGS_MAIN_TITLE#}</h5></div>

<div class="widget" style="margin-top: 0px;">
	<div class="body">
		{#SETTINGS_SAVE_INFO#}
	</div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
		<ul>
			<li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
			<li>{#SETTINGS_MAIN_TITLE#}</li>
		</ul>
	</div>
</div>



<form id="settings" name="settings" method="post" action="index.php?do=settings&cp={$sess}&sub=save" class="mainForm">
<fieldset>

<div class="widget first">

	<ul class="inact_tabs">
	    <li class="activeTab"><a href="index.php?do=settings&cp={$sess}">{#SETTINGS_MAIN_SETTINGS#}</a></li>
	    <li><a href="index.php?do=settings&sub=case&cp={$sess}">{#SETTINGS_CASE_TITLE#}</a></li>
	    <li><a href="index.php?do=settings&sub=countries&cp={$sess}">{#MAIN_COUNTRY_EDIT#}</a></li>
	    <li><a href="index.php?do=settings&sub=language&cp={$sess}">{#SETTINGS_LANG_EDIT#}</a></li>
	    <div class="num"><a class="basicNum clearCacheSess" href="javascript:void(0);">{#MAIN_STAT_CLEAR_CACHE_FULL#}</a></div>
	    <div class="num"><a class="basicNum clearThumb" href="javascript:void(0);">{#MAIN_STAT_CLEAR_THUMB#}</a></div>
	</ul>

<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<col width="300" />
<col />

<thead>
<tr>
	<td>{#SETTINGS_NAME#}</td>
	<td><div class="pr12">{#SETTINGS_VALUE#}</div></td>
</tr>
</thead>

<tbody>
<tr>
	<td>{#SETTINGS_SITE_NAME#}</td>
	<td><div class="pr12"><input type="text" name="site_name" id="site_name" value="{$row.site_name}" maxlength="200" class="mousetrap"></div></td>
</tr>

<tr>
	<td>{#SETTINGS_SITE_COUNTRY#}</td>
	<td>
		<div class="pr12">
	<select name="default_country" style="width: 300px;">
	{foreach from=$available_countries item=land}
		<option value="{$land->country_code}"{if $row.default_country==$land->country_code} selected{/if}>{$land->country_name}</option>
	{/foreach}
	</select>
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_DATE_FORMAT#}</td>
	<td>
		<div class="pr12">
	<select name="date_format" style="width: 300px;">
	{foreach from=$date_formats item=date_format}
		<option value="{$date_format}"{if $row.date_format==$date_format} selected{/if}>{$smarty.now|date_format:$date_format|pretty_date}</option>
	{/foreach}
	</select>
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_TIME_FORMAT#}</td>
	<td>
		<div class="pr12">
	<select name="time_format" style="width: 300px;">
	{foreach from=$time_formats item=time_format}
		<option value="{$time_format}"{if $row.time_format==$time_format} selected{/if}>{$smarty.now|date_format:$time_format|pretty_date}</option>
	{/foreach}
	</select>
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_USE_DOCTIME#}</td>
	<td>
		<div class="pr12">
			<input type="radio" name="use_doctime" value="1"{if $row.use_doctime==1} checked{/if} /><label style="cursor: pointer;">{#SETTINGS_YES#}</label> <input type="radio" name="use_doctime" value="0"{if $row.use_doctime==0} checked{/if} /><label style="cursor: pointer;">{#SETTINGS_NO#}</label>
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_USE_EDITOR#}</td>
	<td>
		<div class="pr12">
			<input type="radio" name="use_editor" value="0"{if $row.use_editor==0} checked{/if} /><label style="cursor: pointer;">{#SETTINGS_EDITOR_STANDART#}</label>
			<input type="radio" name="use_editor" value="1"{if $row.use_editor==1} checked{/if} /><label style="cursor: pointer;">{#SETTINGS_EDITOR_ELFINDER#}</label>
			<input type="radio" name="use_editor" value="3"{if $row.use_editor==3} checked{/if} /><label style="cursor: pointer;">{#SETTINGS_EDITOR_CKEDITOR#}</label>
			<input type="radio" name="use_editor" value="2"{if $row.use_editor==2} checked{/if} /><label style="cursor: pointer;" class="topDir" title="{#SETTINGS_EDITOR_INNOVA_SET#}">{#SETTINGS_EDITOR_INNOVA#}</label>
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_ERROR_PAGE#}</td>
	<td>
		<div class="pr12">
			<input name="page_not_found_id" type="text" id="page_not_found_id" value="{$row.page_not_found_id}" size="4" maxlength="10" readonly style="width: 200px" class="mousetrap" />&nbsp;<input onClick="openLinkWindow('page_not_found_id','page_not_found_id');" type="button" class="basicBtn" value="... " />&nbsp;&nbsp;&nbsp;{#SETTINGS_PAGE_DEFAULT#}
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_TEXT_PERM#}</td>
	<td>
		<div class="pr12">
			<textarea name="message_forbidden" id="message_forbidden" rows="8" cols class="mousetrap">{$row.message_forbidden|stripslashes}</textarea>
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_HIDDEN_TEXT#}</td>
	<td>
		<div class="pr12">
			<textarea name="hidden_text" id="hidden_text" rows="8" cols class="mousetrap">{$row.hidden_text|stripslashes}</textarea>
		</div>
	</td>
</tr>
</tbody>
</table>
</div>

<div class="widget first">
<div class="head"><h5 class="iFrames">{#SETTINGS_MAIN_MAIL#}</h5></div>
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<col width="300" />
<col />

<thead>
<tr>
	<td>{#SETTINGS_NAME#}</td>
	<td><div class="pr12">{#SETTINGS_VALUE#}</div></td>
</tr>
</thead>
<tbody>
<tr>
	<td>{#SETTINGS_EMAIL_NAME#}</td>
	<td>
		<div class="pr12">
		  <input type="text" name="mail_from_name" id="mail_from_name" value="{$row.mail_from_name}" style="width: 250px;" class="mousetrap">
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_EMAIL_SENDER#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="mail_from" id="mail_from" value="{$row.mail_from}" style="width: 250px;" class="mousetrap">
			<input type="hidden" name="mail_content_type" id="mail_content_type" value="text/plain" />
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_TEXT_EMAIL#}<br /><small>{#SETTINGS_TEXT_INFO#}</small></td>
	<td>
		<div class="pr12">
			<textarea name="mail_new_user" id="mail_new_user" rows="12" cols class="mousetrap">{$row.mail_new_user|stripslashes}</textarea>
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_EMAIL_FOOTER#}</td>
	<td>
		<div class="pr12">
			<textarea name="mail_signature" id="mail_signature" rows="8" cols class="mousetrap">{$row.mail_signature|stripslashes}</textarea>
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_SYMBOL_BREAK#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="mail_word_wrap" id="mail_word_wrap" value="{$row.mail_word_wrap}" max="1000" style="width: 50px;float:left;" class="mousetrap">
            <label>{#SETTINGS_SYMBOL_BREAK_INFO#}</label>
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_MAIL_TRANSPORT#}</td>
	<td>
		<div class="pr12">
			<select name="mail_type" id="mail_type" style="width: 250px;">
				<option value="mail"{if $row.mail_type=='mail'} selected{/if}>{#SETTINGS_MAIL#}</option>
				<option id="smtp" value="smtp"{if $row.mail_type=='smtp'} selected{/if}>{#SETTINGS_SMTP#}</option>
				<option value="sendmail"{if $row.mail_type=='sendmail'} selected{/if}>{#SETTINGS_SENDMAIL#}</option>
			</select>
		</div><div id="div_select"></div>
	</td>
</tr>

<tr class="smtp_group">
	<td>{#SETTINGS_SMTP_SERVER#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="mail_host" value="{$row.mail_host}" style="width: 250px;" class="mousetrap">
		</div>
	</td>
</tr>

<tr class="smtp_group">
	<td>{#SETTINGS_MAIL_PORT#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="mail_port" value="{$row.mail_port}" maxlength="5" style="width: 250px;" class="mousetrap">
		</div>
	</td>
</tr>

<tr class="smtp_group">
	<td>{#SETTINGS_SMTP_NAME#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="mail_smtp_login" value="{$row.mail_smtp_login}" style="width: 250px;" class="mousetrap">
		</div>
	</td>
</tr>

<tr class="smtp_group">
	<td>{#SETTINGS_SMTP_PASS#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="mail_smtp_pass" value="{$row.mail_smtp_pass}" style="width: 250px;" class="mousetrap">
		</div>
	</td>
</tr>

<tr class="smtp_group">
	<td>{#SETTINGS_SMTP_ENCRYPT#}</td>
	<td>
		<div class="pr12">
			<select name="mail_smtp_encrypt" style="width: 250px;" class="mousetrap">
              <option value="">{#SETTINGS_SMTP_NOENCRYPT#}</option>
              <option value="tls"{if $row.mail_smtp_encrypt=='tls'} selected="selected"{/if}>TLS</option>
              <option value="ssl"{if $row.mail_smtp_encrypt=='ssl'} selected="selected"{/if}>SSL</option>
            </select>
		</div>
	</td>
</tr>

<tr class="sendmail_group">
	<td>{#SETTINGS_MAIL_PATH#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="mail_sendmail_path" id="mail_sendmail_path" value="{$row.mail_sendmail_path}" style="width: 250px;" class="mousetrap">
		</div>
	</td>
</tr>
</tbody>
</table>
</div>

<div class="widget first">
<div class="head"><h5 class="iFrames">{#SETTINGS_MAIN_PAGENAVI#}</h5></div>
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
<col width="300" />
<col />

<thead>
<tr>
	<td>{#SETTINGS_NAME#}</td>
	<td><div class="pr12">{#SETTINGS_VALUE#}</div></td>
</tr>
</thead>
<tbody>
<tr>
	<td>{#SETTINGS_NAVI_BOX#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="navi_box" id="navi_box" value="{$row.navi_box|escape|stripslashes}" class="mousetrap">
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_PAGE_BEFORE#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="total_label" id="total_label" value="{$row.total_label|escape|stripslashes}" style="width: 250px;" class="mousetrap">
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_PAGE_START#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="start_label" id="start_label" value="{$row.start_label|stripslashes}" style="width: 250px;" class="mousetrap">
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_PAGE_END#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="end_label" id="end_label" value="{$row.end_label|stripslashes}" style="width: 250px;" class="mousetrap">
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_PAGE_SEPARATOR#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="separator_label" id="separator_label" value="{$row.separator_label|stripslashes}" style="width: 250px;" class="mousetrap">
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_PAGE_NEXT#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="next_label" id="next_label" value="{$row.next_label|stripslashes}" style="width: 250px;" class="mousetrap">
		</div>
	</td>
</tr>

<tr>
	<td>{#SETTINGS_PAGE_PREV#}</td>
	<td>
		<div class="pr12">
			<input type="text" name="prev_label" id="prev_label" value="{$row.prev_label|stripslashes}" style="width: 250px;" class="mousetrap">
		</div>
	</td>
</tr>

<tr>
	<td colspan="2"><input type="submit" class="basicBtn SaveSettings" value="{#SETTINGS_BUTTON_SAVE#}" /></td>
</tr>
</tbody>
</table>


</div>
</fieldset>
</form>

<script language="javascript">
$("#mail_type").change(function () {ldelim}
	if ($("#mail_type option:selected").val() == "mail") {ldelim}
		$(".smtp_group").hide();
		$(".sendmail_group").hide();
	{rdelim}
	else if ($("#mail_type option:selected").val() == "smtp") {ldelim}
		$(".smtp_group").show();
		$(".sendmail_group").hide();
	{rdelim}
	else if ($("#mail_type option:selected").val() == "sendmail") {ldelim}
		$(".smtp_group").hide();
		$(".sendmail_group").show();
	{rdelim}	
{rdelim}).trigger('change');

$(document).ready(function(){ldelim}
	
	if ($("#mail_type option:selected").val() != "smtp") {ldelim}
		$(".smtp_group").hide();
	{rdelim}
	if ($("#mail_type option:selected").val() != "sendmail") {ldelim}
		$(".sendmail_group").hide();
	{rdelim}
	

	var sett_options = {ldelim}
		url: 'index.php?do=settings&cp={$sess}&sub=save',
	//	target: '#contentPage',
		beforeSubmit: Request,
		success: Response
	{rdelim}

	$(".SaveSettings").click(function(e){ldelim}
		e.preventDefault();
		var title = '{#SETTINGS_BUTTON_SAVE#}';
		var confirm = '{#SETTINGS_SAVE_CONFIRM#}';
		jConfirm(
				confirm,
				title,
				function(b){ldelim}
					if (b){ldelim}
						$("#settings").ajaxSubmit(sett_options);
					{rdelim}
				{rdelim}
			);
	{rdelim});

		Mousetrap.bind(['ctrl+s', 'meta+s'], function(e) {ldelim}
			if (e.preventDefault) {ldelim}
				e.preventDefault();
			{rdelim} else {ldelim}
				// internet explorer
				e.returnValue = false;
			{rdelim}
			$("#settings").ajaxSubmit(sett_options);
			return false;
		{rdelim});

{rdelim});

function Request(){ldelim}
	$.alerts._overlay('show');
	$('html, body').animate({ldelim}scrollTop:0{rdelim});
{rdelim}

function Response(){ldelim}
	$.alerts._overlay('hide');
	$.jGrowl('{#SETTINGS_SAVED#}',{ldelim}theme: 'accept'{rdelim});
{rdelim}

function openLinkWindow(target,doc) {ldelim}
	if (typeof width=='undefined' || width=='') var width = screen.width * 0.6;
	if (typeof height=='undefined' || height=='') var height = screen.height * 0.6;
	if (typeof doc=='undefined') var doc = 'title';
	if (typeof scrollbar=='undefined') var scrollbar=1;
	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;
	window.open('index.php?idonly=1&doc='+doc+'&target='+target+'&do=docs&action=showsimple&cp={$sess}&pop=1','pop','left='+left+',top='+top+',width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1');
{rdelim}
</script>