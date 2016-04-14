<link rel="stylesheet" type="text/css" href="{$ABS_PATH}admin/templates/default/liveeditor/css/mod_liveeditor.css" media="screen" />
<script type="text/javascript" language="JavaScript">
$(document).ready(function(){ldelim}

		$(".AddLiveEditor").click( function(e) {ldelim}
			e.preventDefault();
			var user_group = $('#add_liveeditor #liveeditor_name').fieldValue();
			var title = '{#LIVEEDITOR_ADD#}';
			var text = '{#LIVEEDITOR_INNAME#}';
			if (user_group == ""){ldelim}
				jAlert(text,title);
			{rdelim}else{ldelim}
				$.alerts._overlay('show');
				$("#add_liveeditor").submit();
			{rdelim}
		{rdelim});

{rdelim});
</script>

<div class="title"><h5>{#LIVEEDITOR_EDIT#}&nbsp;|&nbsp;<a class="topDir" style="color:#FAFAFA; text-decoration:none" title="{#LIVEEDITOR_DESIGN#}" target="_blank" href="http://www.webstudio3v.ru">{#LIVEEDITOR_COPY#}</a></h5></div>

<div class="widget" style="margin-top: 0px;">
    <div class="body">
		{#LIVEEDITOR_EDIT_TIP#}
    </div>
</div>


<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li>{#LIVEEDITOR_EDIT#}</li>
	    </ul>
	</div>
</div>


<div class="widget first">
	<ul class="tabs">
	    <li class="activeTab"><a href="#tab1">{#LIVEEDITOR_EDIT#}</a></li>
	    
	</ul>

		<div class="tab_container">
			<div id="tab1" class="tab_content" style="display: block;">
<div class="head collapsible" id="opened"><h5 class="iFrames"><span class="rightDir mod_le_col" title="{#LIVEEDITOR_DP_ST#}">{#LIVEEDITOR_DP_PN#}</span></h5></div> 

<div style="background:#FFF" class="head">{if $reg_license_f == 0}<div class="iFrames Registr">{if $smarty.request.id != ''}{#LIVEEDITOR_EDIT_H#}{else}&nbsp;&nbsp;{#LIVEEDITOR_DP#}&nbsp;{/if}<span class="mod_le_active">&nbsp;{#LIVEEDITOR_DP_END#}&nbsp;<span>{$show_date_trial}</span>&nbsp;|&nbsp;&nbsp;{#LIVEEDITOR_DP_DEL_GET#}&nbsp;&nbsp;&nbsp;{#LIVEEDITOR_DP_DEL#}</span><form class="form_registr" id="form_registr" action="index.php?do=liveeditor&action=reg&cp={$sess}" method="post"><input class="input_registr" name="input_registr" id="form_registr_name" type="text" value="" /><input class="basicBtn li_btn_Reg" value="{#LIVEEDITOR_DP_BTN#}" type="button"/></form></div>{else}<div class="iFrames Registr">{#LIVEEDITOR_REG_REG_OK#}</div>{/if}</div>
 
 <script type="text/javascript" language="JavaScript">
        $(document).ready(function(){ldelim}
		$(".li_btn_Reg").click( function(e) {ldelim}
		e.preventDefault();
		$('#form_registr #form_registr_name').fieldValue();
		$.alerts._overlay('show');
		$("#form_registr").submit();
		{rdelim});
		$.jGrowl('{$status_pas}');
        {rdelim});
 </script> 
 <script type="text/javascript" language="JavaScript">
 	{literal}
		$('.collapsible').collapsible({
			defaultOpen: 'opened',
			cssOpen: 'inactive',
			cssClose: 'normal',
			cookieName: 'collaps_reg',
			cookieOptions: {
		        expires: 7,
				domain: ''
	    	},
			speed: 200
		});
		{/literal}
 </script>      

<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic mainForm">
		<col width="20">
		<col>
		<col width="100">
		<col width="20">
		<col width="20">
		<thead>
		<tr>
			<td>{#LIVEEDITOR_ID#}</td>
			<td>{#LIVEEDITOR_NAME#}</td>
			<td>{#LIVEEDITOR_STATUS#}</td>
			<td colspan="2">{#LIVEEDITOR_ACTIONS#}</td>
		</tr>
		</thead>
		<tbody>
		{if $live_editors}
		{foreach from=$live_editors item=liveeditor}
			<tr id="tr{$liveeditor->id}">
				<td class="itcen">{$liveeditor->id}</td>
				<td>
					<a class="link mod_liveeditor_activated"  href="index.php?do=liveeditor&action=edit&cp={$sess}&id={$liveeditor->id}">
					{$liveeditor->liveeditor_name|escape}</a>
				</td>
              <td><div class="" style="width: 368px; height:25px;">{if $liveeditor->liveeditor_status==1}<span class="mod_liveeditor_activated">{#LIVEEDITOR_ACTIVATED#}</span>{else}<span class="mod_liveeditor_deactivated">{#LIVEEDITOR_DEACTIVATED#}</span>{/if}
        {if $liveeditor->liveeditor_fields==1}<span class="mod_liveeditor_m_field">{#LIVEEDITOR_M_FIELD#}</span>
        {else}<span class="mod_liveeditor_sm_field">{#LIVEEDITOR_SM_FIELD#}</span>{/if}</div>
				</td>
				<td align="center">
				{if check_permission('liveeditor')}
					<a class="topleftDir icon_sprite ico_edit" title="{#LIVEEDITOR_EDIT_HINT#}" href="index.php?do=liveeditor&action=edit&cp={$sess}&id={$liveeditor->id}"></a>
				{/if}
				</td>
				<td align="center">
				{if check_permission('liveeditor')}
					<span title="" class="topleftDir icon_sprite ico_delete_no"></span>
				{/if}
				</td>
			</tr>
		{/foreach}
		{else}
			<tr>
				<td colspan="9">
					<ul class="messages">
						<li class="highlight yellow">{#LIVEEDITOR_NO_ITEMS#}</li>
					</ul>
				</td>
			</tr>
		{/if}
	</tbody>
</table>
		</div>

			<div id="tab2" class="tab_content" style="display: none;">
					<form id="add_liveeditor" method="post" action="index.php?do=liveeditor&action=new&cp={$sess}" class="mainForm">
					<div class="rowElem">
						<label>{#LIVEEDITOR_NAME#}</label>
						<div class="formRight"><input name="liveeditor_name" type="text" id="liveeditor_name" value="" placeholder="{#LIVEEDITOR_NAME#}" style="width: 400px">
						&nbsp;<input type="button" class="basicBtn AddLiveEditor" value="{#LIVEEDITOR_ADD_BUTTON#}" />
						</div>
						<div class="fix"></div>
					</div>
					</form>
			</div>
		</div>
	<div class="fix"></div>
</div>

