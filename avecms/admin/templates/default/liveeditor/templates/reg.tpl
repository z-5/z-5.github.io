<link rel="stylesheet" type="text/css" href="{$ABS_PATH}admin/templates/default/liveeditor/css/mod_liveeditor.css" media="screen" />
<div class="title"><h5>{#LIVEEDITOR_DP_ML#}&nbsp;|&nbsp;<a class="topDir" style="color:#FAFAFA; text-decoration:none" title="{#LIVEEDITOR_DESIGN#}" target="_blank" href="http://www.webstudio3v.ru">{#LIVEEDITOR_COPY#}</a></h5></div>
<div class="widget" style="margin-top: 0px;">
    <div class="body">
		{#LIVEEDITOR_REG_SET_LIC#}
    </div>
</div>

<div class="widget first">
	<ul class="tabs">
	    <li class="activeTab"><a href="#tab1">{#LIVEEDITOR_REG_EDIT_LIC#}</a></li>
	   
	</ul>
<div class="tab_container">
			<div id="tab1" class="tab_content" style="display: block;">
<div class="head"><h5 class="iFrames"><span class="toprightDir mod_le_col" >{#LIVEEDITOR_REG_ACT_LIC#}</span></h5></div>

<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic mainForm">
		<col width="20">
		<col>
		<col width="100">
		<col width="20">
		<col width="20">
		<thead>
		<tr>
			<td>{#LIVEEDITOR_LIC_N#}</td>
			<td>{#LIVEEDITOR_LIC_NAME#}</td>
			<td>{#LIVEEDITOR_STATUS#}</td>
			<td colspan="2">{#LIVEEDITOR_ACTIONS#}</td>
		</tr>
		</thead>
		<tbody>
		
		
			<tr id="">
				<td class="itcen tpl_reg">{$ftl_array[3]}</td>
				<td class="tpl_reg">{if $end_time_trial_lic[0]!=1}<span style="padding-right:5px;">{$ftl_array[0]}{$ftl_array[4]}</span>{else}<span style="padding-right:5px;">{#LIVEEDITOR_L_TR_S#}{$ftl_array[0]}{#LIVEEDITOR_L_TR_SS#}</span>{/if}</td>
				<td class="tpl_reg" style=" width:330px;">{if $end_time_trial_lic[0]!=1}{#LIVEEDITOR_L_TR_E#}{else}{#LIVEEDITOR_DP_BTN_T_E#}{/if}<span style="padding-left:5px;">{$ftl_array[1]}</span></td>
				<td align="center">
					<span class="topleftDir icon_sprite ico_edit_no" title=""></span>
				</td>
				<td align="center">
					<span title="" class="topleftDir icon_sprite ico_delete_no"></span>
				</td>
			</tr>
		      <tr>
				<td colspan="8">
					<span style=" padding-left:15px; padding-right:10px; font-size:14px;" class="tpl_reg">{#LIVEEDITOR_L_REC#}</span>
				</td>
			</tr>
		
	</tbody>
   </table>

<div class="head"><h5 class="iFrames"><span style="float:left; padding-left:10px; padding-right:10px;" class="itcen tpl_reg">{#LIVEEDITOR_REG_REG#}</span><form style="float:left !important;  padding-top:7px;" class="form_registr" id="form_registr" action="index.php?do=liveeditor&action=reg&cp={$sess}" method="post"><input class="input_registr" name="input_registr" id="form_registr_name" type="text" value="" /><input class="basicBtn li_btn_Reg" value="{#LIVEEDITOR_DP_BTN#}" type="button"/>
</form>
{if $end_time_trial_lic[0]!=1}
{#LIVEEDITOR_DP_BTN_OR#}
<form style="padding-top:8px;" class="form_delete" id="form_delete" action="index.php?do=liveeditor&action=reg&cp={$sess}" method="post"><input class="input_delete" name="input_delete" id="form_delete_name" type="hidden" value="delete" /><input class="basicBtn li_btn_Del" value="{#LIVEEDITOR_DP_BTN_NEXT#}" type="button"/>{else}{/if}</form>

</h5></div>

</div>
</div>
<div class="fix"></div>
</div>        
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
        $(document).ready(function(){ldelim}
		$(".li_btn_Del").click( function(r) {ldelim}
		r.preventDefault();
		$('#form_delete #form_delete_name').fieldValue();
		$.alerts._overlay('show');
		$("#form_delete").submit();
		{rdelim});
		
        {rdelim});
		</script>