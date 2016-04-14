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
      .CodeMirror-scroll {height: 300px;}
    </style>
{/literal}

<script language="Javascript" type="text/javascript">

function openAliasWindow(fieldId, rubId, width, height, target) {ldelim}
	if (typeof width=='undefined' || width=='') var width = screen.width * 0.8;
	if (typeof height=='undefined' || height=='') var height = screen.height * 0.8;
	if (typeof scrollbar=='undefined') var scrollbar=1;
	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;
	window.open('index.php?field_id='+fieldId+'&rubric_id='+rubId+'&target='+target+'&do=rubs&action=alias_add&cp={$sess}&pop=1','pop','left='+left+',top='+top+',width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1').focus();
{rdelim}


$(document).ready(function(){ldelim}
	$('tr.tpls').hide();

	$("#selall").click(function(){ldelim}
		if ($("#selall").is(":checked")){ldelim}
			$(".checkbox").removeAttr("checked");
			$("#Fields a.jqTransformCheckbox").removeClass("jqTransformChecked");
		{rdelim}else{ldelim}
	   		$(".checkbox").attr("checked","checked");
			$("#Fields a.jqTransformCheckbox").addClass("jqTransformChecked");
		{rdelim}
	{rdelim});

	$('.collapsible').collapsible({ldelim}
		defaultOpen: 'opened',
		cssOpen: 'inactive',
		cssClose: 'normal',
		cookieName: 'collaps_rub',
		cookieOptions: {ldelim}
	        expires: 7,
			domain: ''
    	{rdelim},
		speed: 200
	{rdelim});

{rdelim});
</script>


<div class="title"><h5>{#RUBRIK_EDIT_FIELDS#}</h5></div>
	{if !$rub_fields}
<div class="widget" style="margin-top: 0px;"><div class="body">{#RUBRIK_NO_FIELDS#}</div></div>
	{else}
<div class="widget" style="margin-top: 0px;"><div class="body">{#RUBRIK_FIELDS_INFO#}</div></div>
	{/if}

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li><a href="index.php?do=rubs&cp={$sess}">{#RUBRIK_SUB_TITLE#}</a></li>
	        <li>{#RUBRIK_EDIT_FIELDS#}</li>
	        <li><strong class="code">{$rubric->rubric_title}</strong></li>
	    </ul>
	</div>
</div>

<form action="index.php?do=rubs&action=edit&Id={$smarty.request.Id|escape}&cp={$sess}" method="post" class="mainForm" id="RubricDescription">
<div class="widget first">
<div class="head"><h5 class="iFrames">{#RUBRIK_DESCRIPTION#}</h5></div>
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic" id="Fields">
	<tr>
		<td>
			<div class="pr12"><textarea wrap="off" placeholder="{#RUBRIK_DESCRIPTION#}" style="width:100%; height:40px" name="rubric_description">{$rubric->rubric_description|escape}</textarea></div>
		</td>
	</tr>
	<tr>
		<td>
			<input type="hidden" name="submit" value="" id="nd_sub" />
			<input type="submit" class="basicBtn" value="{#RUBRIK_BUTTON_SAVE#}" onclick="document.getElementById('nd_sub').value='description'" />
		</td>
	</tr>
</table>
</div>

</form>

{if $rub_fields}

<form action="index.php?do=rubs&action=edit&Id={$smarty.request.Id|escape}&cp={$sess}" method="post" class="mainForm" id="Rubric">

<div class="widget first">
<div class="head"><h5 class="iFrames">{#RUBRIK_FIELDS_TITLE#}</h5><div class="num"><a class="basicNum" href="index.php?do=rubs&action=template&Id={$smarty.request.Id|escape}&cp={$sess}">{#RUBRIK_EDIT_TEMPLATE#}</a></div></div>

	{assign var=js_form value='kform'}
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic" id="Fields">
		<col width="20">
		<col width="40">
		<col width="175">
		<col width="220">
		<col width="220">
		<col>
		<col width="72">
		<col width="20">
		<thead>
		<tr>
			<td align="center"><div align="center"><input type="checkbox" id="selall" value="1" /></div></td>
			<td>{#RUBRIK_ID#}</td>
			<td>{#RUBRIK_FIELD_ALIAS#}</td>
			<td>{#RUBRIK_FIELD_NAME#}</td>
			<td>{#RUBRIK_FIELD_TYPE#}</td>
			<td>{#RUBRIK_FIELD_DEFAULT#}</td>
			<td>{#RUBRIK_POSITION#}</td>
			<td align="center">
				<div align="center"><a class="topleftDir icon_sprite ico_template" title="{#RUBRIK_TEMPLATE_HIDE#}" href="javascript:void(0);" onclick="$('tr.tpls').hide();"></a></div>
			</td>
		</tr>
		</thead>
		<tbody>
		{foreach from=$rub_fields item=rf}
			<tr>
				<td align="center"><input title="{#RUBRIK_MARK_DELETE#}" name="del[{$rf->Id}]" type="checkbox" id="del[{$rf->Id}]" value="1" class="checkbox topDir" /></td>
				<td align="center">{$rf->Id}</td>
				<td nowrap>
					<input name="alias[{$rf->Id}]" type="text" id="alias_{$rf->Id}" value="{$rf->rubric_field_alias|escape}" style="width: 100px;" readonly />&nbsp;
					<input type="button" class="button blackBtn topDir" onclick="openAliasWindow('{$rf->Id}','{$smarty.request.Id|escape}','750','400','alias_{$rf->Id}')" value="...">
				</td>
				<td><div class="pr12"><input name="title[{$rf->Id}]" type="text" id="title[{$rf->Id}]" value="{$rf->rubric_field_title|escape}" style="width:100%;" /></div></td>
				<td>
					<select name="rubric_field_type[{$rf->Id}]" id="rubric_field_type[{$rf->Id}]" style="width: 250px;">
						{section name=feld loop=$felder}
							<option value="{$felder[feld].id}" {if $rf->rubric_field_type==$felder[feld].id}selected{/if}>{$felder[feld].name}</option>
						{/section}
					</select>
				</td>
				<td><div class="pr12"><input name="rubric_field_default[{$rf->Id}]" type="text" id="rubric_field_default[{$rf->Id}]" value="{$rf->rubric_field_default}" style="width:100%;" /></div></td>
				<td>
					<div class="pr12"><input name="rubric_field_position[{$rf->Id}]" type="text" id="rubric_field_position[{$rf->Id}]" value="{$rf->rubric_field_position}" size="4" maxlength="5" autocomplete="off" /></div>
				</td>
				<td align="center">
					<a class="topleftDir icon_sprite ico_template" title="{#RUBRIK_TEMPLATE_TOGGLE#}" href="javascript:void(0);" onclick="$('#tpl_{$rf->Id}').toggle();"></a>
				</td>
			</tr>
			<tr id="tpl_{$rf->Id}" class="tpls">
				<td colspan="8">

				<div style="padding-right: 12px">
				<div style="width:50%; float:left;">
					<div><strong>{#RUBRIK_FIELDS_TPL#}</strong></div>
					<textarea wrap="off" style="width:100%; height:70px" name="rubric_field_template[{$rf->Id}]" id="rubric_field_template[{$rf->Id}]">{$rf->rubric_field_template|escape}</textarea>
                    	|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_insert('[tag:parametr:]', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>[tag:parametr:XXX]</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_insert('[tag:X000x000:[tag:parametr:]]', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>[tag:X000x000:[tag:rfld:XXX][XXX]]</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_insert('[tag:path]', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>[tag:path]</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_insert('[tag:docid]', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>[tag:docid]</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_tag('tag:if_empty', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>[tag:if_empty]&nbsp;[/tag:if_empty]</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_tag('tag:if_notempty', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>[tag:if_notempty]&nbsp;[/tag:if_notempty]</strong></a>
						&nbsp;|

						<br />

						|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('div', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>DIV</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('p', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>P</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('strong', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>B</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('em', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>I</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('h1', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>H1</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('h2', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>H2</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('h3', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>H3</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('pre', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>PRE</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_insert('<br />', 'rubric_field_template[{$rf->Id}]', 'Rubric');"><strong>BR</strong></a>
						&nbsp;|
				</div>
				<div style="width:50%; float:left;">
					<div><strong>{#RUBRIK_REQUEST_TPL#}</strong></div>
					<textarea wrap="off" style="width:100%; height:70px" name="rubric_field_template_request[{$rf->Id}]" id="rubric_field_template_request[{$rf->Id}]">{$rf->rubric_field_template_request|escape}</textarea>
                    	|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_insert('[tag:parametr:]', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>[tag:parametr:XXX]</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_insert('[tag:X000x000:[tag:parametr:]]', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>[tag:X000x000:[tag:parametr:XXX]]</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_insert('[tag:path]', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>[tag:path]</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_insert('[tag:docid]', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>[tag:docid]</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_tag('tag:if_empty', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>[tag:if_empty]&nbsp;[/tag:if_empty]</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_tag('tag:if_notempty', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>[tag:if_notempty]&nbsp;[/tag:if_notempty]</strong></a>
						&nbsp;|

						<br />

						|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('div', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>DIV</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('p', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>P</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('strong', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>B</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('em', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>I</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('h1', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>H1</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('h2', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>H2</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('h3', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>H3</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_code('pre', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>PRE</strong></a>&nbsp;|&nbsp;
						<a class="docname" href="javascript:void(0);" onclick="javascript:cp_insert('<br />', 'rubric_field_template_request[{$rf->Id}]', 'Rubric');"><strong>BR</strong></a>
						&nbsp;|
				</div>
				</div>
				</td>
			</tr>
		{/foreach}
		<tr>
			<td colspan="8">
				<input type="hidden" name="submit" value="" id="nf_save_next" />
				<input type="submit" class="basicBtn" value="{#RUBRIK_BUTTON_SAVE#}" onclick="document.getElementById('nf_save_next').value='save'" />&nbsp;
				<input type="submit" class="redBtn" value="{#RUBRIK_BUTTON_TEMPL#}" onclick="document.getElementById('nf_save_next').value='next'" />
			</td>
		</tr>
		</tbody>
	</table>

</div>
</form>
{/if}

<div class="widget first">
	<div class="head collapsible" id="opened"><h5>{#RUBRIK_NEW_FIELD#}</h5></div>
	<div style="display: block;">
	<div class="body">{#RUBRIK_NEW_FIEL_TITLE#}</div>
	<form id="newfld" action="index.php?do=rubs&action=edit&Id={$smarty.request.Id|escape}&cp={$sess}" method="post" class="mainForm">
	<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<col width="356" class="second">
		<col width="220" class="second">
		<col class="second">
		<col width="100" class="second">
		<thead>
		<tr>
			<td>{#RUBRIK_FIELD_NAME#}</td>
			<td>{#RUBRIK_FIELD_TYPE#}</td>
			<td>{#RUBRIK_FIELD_DEFAULT#}</td>
			<td>{#RUBRIK_POSITION#}</td>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>
				<div class="pr12"><input name="TitelNew" type="text" id="TitelNew" value="" style="width:100%;" /></div>
			</td>

			<td>
				<select name="RubTypNew" id="RubTypNew" style="width: 250px;">
					{section name=feld loop=$felder}
						<option value="{$felder[feld].id}">{$felder[feld].name}</option>
					{/section}
				</select>
			</td>

			<td>
				<div class="pr12"><input name="StdWertNew" type="text" id="StdWertNew" value="" style="width:100%;" /></div>
			</td>

			<td>
				<div class="pr12"><input name="rubric_field_position_new" type="text" id="rubric_field_position_new" value="100" size="4" maxlength="5" autocomplete="off" /></div>
			</td>
		</tr>

		<tr>
			<td colspan="4" class="third">
				<input type="hidden" name="submit" value="" id="nf_hidd" />
				<input class="basicBtn" type="submit" value="{#RUBRIK_BUTTON_ADD#}" onclick="document.getElementById('nf_hidd').value='newfield'" /><br />
			</td>
		</tr>
		</tbody>
	</table>
</form>
</div>
</div>

<div class="widget first">
	<div class="head closed active"><h5>{#RUBRIK_LINK#}</h5></div>
	<div style="display: block;">
	<form id="newfld" action="index.php?do=rubs&action=edit&Id={$smarty.request.Id|escape}&cp={$sess}" method="post" class="mainForm">
	<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<col width="50%">
		<col width="50%">
		<tbody>
		<tr>
			<td>
				<div class="pr12">
					{#RUBRIK_LINK_DESC#}
				</div>
			</td>
			<td>
				<div class="pr12">
					{foreach from=$rubs item=rub}
					<div class="fix">
						<input type="checkbox" class="float" {if @in_array($rub->Id, $rubric->rubric_linked_rubric)}checked="checked"{/if} name="rubric_linked[]" value="{$rub->Id}" /> 
						<label>{$rub->rubric_title}</label>
					</div>
					{/foreach}
				</div>
			</td>
		</tr>

		<tr>
			<td colspan="4" class="third">
				<input type="hidden" name="submit" value="linked_rubric" id="linked_rubric" />
				<input class="basicBtn" type="submit" value="{#RUBRIK_BUTTON_SAVE#}" /><br />
			</td>
		</tr>
		</tbody>
	</table>
</form>
</div>
</div>

<div class="widget first">
	<div class="head closed active"><h5>{#RUBRIK_CODE#}</h5></div>
	<div style="display: block;">
	<form id="newfld" action="index.php?do=rubs&action=edit&Id={$smarty.request.Id|escape}&cp={$sess}" method="post" class="mainForm">
	<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<col width="50%">
		<col width="50%">
		<thead>
		<tr>
			<td>{#RUBRIK_CODE_START#}</td>
			<td>{#RUBRIK_CODE_END#}</td>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>
				<div class="pr12">
					<textarea name="rubric_code_start" type="text" id="code_start" value="" style="height:300px;" />{$rubric->rubric_code_start}</textarea>
				</div>
			</td>

			<td>
				<div class="pr12">
					<textarea name="rubric_code_end" type="text" id="code_end" value="" style="height:300px;" />{$rubric->rubric_code_end}</textarea>
				</div>
			</td>
		</tr>

		<tr>
			<td colspan="4" class="third">
				<input type="hidden" name="submit" value="code" id="code" />
				<input class="basicBtn" type="submit" value="{#RUBRIK_BUTTON_SAVE#}" /><br />
			</td>
		</tr>
		</tbody>
	</table>
</form>
</div>
</div>


{if check_permission('rubric_perms')}


<div class="widget first">
	<div class="head closed active"><h5>{#RUBRIK_SET_PERMISSION#}</h5></div>
	<div style="display: block;">
	<form id="rubperm" action="index.php?do=rubs&action=edit&Id={$smarty.request.Id|escape}&cp={$sess}" method="post" class="mainForm">
	<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<col width="28%">
		<col width="12%">
		<col width="12%">
		<col width="12%">
		<col width="12%">
		<col width="12%">
		<col width="12%">
		<thead>
		<tr>
			<td>{#RUBRIK_USER_GROUP#}</td>
			<td align="center">{#RUBRIK_DOC_READ#} <a href="javascript:void(0);" class="topDir link" style="cursor: help;" title="{#RUBRIK_VIEW_TIP#}">[?]</a></td>
			<td align="center">{#RUBRIK_ALL_PERMISSION#} <a href="javascript:void(0);" class="topDir link" style="cursor: help;" title="{#RUBRIK_ALL_TIP#}">[?]</a></td>
			<td align="center">{#RUBRIK_CREATE_DOC#} <a href="javascript:void(0);" class="topDir link" style="cursor: help;" title="{#RUBRIK_DOC_TIP#}">[?]</a></td>
			<td align="center">{#RUBRIK_CREATE_DOC_NOW#} <a href="javascript:void(0);" class="topDir link" style="cursor: help;" title="{#RUBRIK_DOC_NOW_TIP#}">[?]</a></td>
			<td align="center">{#RUBRIK_EDIT_OWN#} <a href="javascript:void(0);" class="topDir link" style="cursor: help;" title="{#RUBRIK_OWN_TIP#}">[?]</a></td>
			<td align="center">{#RUBRIK_EDIT_OTHER#} <a href="javascript:void(0);" class="topleftDir link" style="cursor: help;" title="{#RUBRIK_OTHER_TIP#}">[?]</a></td>
		</tr>
		</thead>
		<tbody>
		{foreach from=$groups item=group}
			{assign var=doall value=$group->doall}
			<tr>
				<td>
					{$group->user_group_name|escape:html}
				</td>

				<td align="center">
					{if $group->doall_h==1}
						<input type="hidden" name="perm[{$group->user_group}][]" value="docread" />
						<input name="perm[{$group->user_group}][]" type="checkbox" value="docread" checked="checked" disabled="disabled" />
					{else}
						<input name="perm[{$group->user_group}][]" type="checkbox" value="docread"{if in_array('docread', $group->permissions) || in_array('alles', $group->permissions)} checked="checked"{/if} />
					{/if}
				</td>

				<td align="center">
					{if $group->doall_h==1}
						<input type="hidden" name="perm[{$group->user_group}][]" value="alles" />
						<input name="perm[{$group->user_group}][]" type="checkbox" value="alles" checked="checked" disabled="disabled" />
					{else}
						<input name="perm[{$group->user_group}][]" type="checkbox" value="alles"{if in_array('alles', $group->permissions)} checked="checked"{/if}{if $group->user_group==2} disabled="disabled"{/if} />
					{/if}
				</td>

				<td align="center">
					<input type="hidden" name="user_group[{$group->user_group}]" value="{$group->user_group}" />
					{if $group->doall_h==1}
						<input name="{$group->user_group}" type="checkbox" value="1"{$doall} />
						<input type="hidden" name="perm[{$group->user_group}][]" value="new" />
					{else}
						<input onclick="document.getElementById('newnow_{$group->user_group}').checked = '';" id="new_{$group->user_group}" name="perm[{$group->user_group}][]" type="checkbox" value="new"{if in_array('new', $group->permissions) || in_array('alles', $group->permissions)} checked="checked"{/if}{if $group->user_group==2} disabled="disabled"{/if} />
					{/if}
				</td>

				<td align="center">
					<input type="hidden" name="user_group[{$group->user_group}]" value="{$group->user_group}" />
					{if $group->doall_h==1}
						<input name="{$group->user_group}" type="checkbox" value="1"{$doall} />
						<input type="hidden" name="perm[{$group->user_group}][]" value="newnow" />
					{else}
						<input onclick="document.getElementById('new_{$group->user_group}').checked = '';" id="newnow_{$group->user_group}" name="perm[{$group->user_group}][]" type="checkbox" value="newnow"{if in_array('newnow', $group->permissions) || in_array('alles', $group->permissions)} checked="checked"{/if}{if $group->user_group==2} disabled="disabled"{/if} />
					{/if}
				</td>

				<td align="center">
					{if $group->doall_h==1}
						<input name="{$group->user_group}" type="checkbox" value="1"{$doall} />
						<input type="hidden" name="perm[{$group->user_group}][]" value="editown" />
					{else}
						<input id="editown_{$group->user_group}" name="perm[{$group->user_group}][]" type="checkbox" value="editown"{if in_array('editown', $group->permissions) || in_array('alles', $group->permissions)} checked="checked"{/if}{if $group->user_group==2} disabled="disabled"{/if} />
					{/if}
				</td>

				<td align="center">
					{if $group->doall_h==1}
						<input name="{$group->user_group}" type="checkbox" value="1"{$doall} />
					{else}
						<input name="perm[{$group->user_group}][]" type="checkbox" value="editall"{if in_array('editall', $group->permissions) || in_array('alles', $group->permissions)} checked="checked"{/if}{if $group->user_group==2} disabled="disabled"{/if} />
					{/if}
				</td>
			</tr>
		{/foreach}
		<tr>
			<td colspan="7" class="third">
				<input type="hidden" name="submit" value="" id="nf_sub" />
				<input type="submit" class="basicBtn" value="{#RUBRIK_BUTTON_PERM#}" onclick="document.getElementById('nf_sub').value='saveperms'" />
			</td>
		</tr>
		</tbody>
	</table>
</form>
</div></div>

{/if}

    <script language="Javascript" type="text/javascript">
{literal}
      var editor = CodeMirror.fromTextArea(document.getElementById("code_start"), {
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

      var editor2 = CodeMirror.fromTextArea(document.getElementById("code_end"), {
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