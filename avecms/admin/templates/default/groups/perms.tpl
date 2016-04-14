<div class="title"><h5>{#UGROUP_TITLE2#}</h5></div>

<div class="widget" style="margin-top: 0px;">
    <div class="body">
	{if $own_group}
		{#UGROUP_YOUR_NOT_CHANGE#}
	{else}
		{#UGROUP_WARNING_TIP#}
		{if $no_group}
			{#UGROUP_NOT_EXIST#}
		{/if}
	{/if}
    </div>
</div>


{if !$no_group && !$own_group}

<form method="post" action="index.php?do=groups&action=grouprights&cp={$sess}&Id={$smarty.request.Id|escape}&sub=save" class="mainForm">
<fieldset>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php">Главное меню</a> </li>
	        <li><a href="index.php?do=groups&cp={$sess}">{#UGROUP_TITLE#}</a></li>
	        <li><strong class="code">{$g_name|escape}</strong></li>
	    </ul>
	</div>
</div>


<div class="widget first">
<div class="head"><h5 class="iFrames">{$g_name|escape}</h5></div>

<div class="rowElem noborder">
	<label>{#UGROUP_NAME#}</label>
	<div class="formRight"><input name="user_group_name" type="text" value="{$g_name|escape}" size="40" maxlength="40" /></div>
	<div class="fix"></div>
</div>


<div class="rowElem">
	<label>{#UGROUP_MODULES_RIGHT#}</label>
	<div class="formRight">
				<select name="perms[]" style="width:300px" size="12" multiple="multiple" id="xxx" class="select">
					{foreach from=$modules item=module}
						{if $module->mod_path != 'mod_navigation'}
							<option value="{$module->mod_path}"{if in_array($module->mod_path, $g_group_permissions) || in_array('alles', $g_group_permissions)} selected="selected"{/if}{if $smarty.request.Id == 1 || $smarty.request.Id == $PAGE_NOT_FOUND_ID || in_array('alles', $g_group_permissions)} disabled="disabled"{/if}>{$module->ModuleName|escape}</option>
						{/if}
					{/foreach}
				</select>
	</div>
	<div class="fix"></div>
</div>

			<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
					<tbody>
{foreach from=$g_all_permissions item=perm}
			<tr>
				<td width="20" align="center">
					<input type="checkbox" name="perms[]" value="{$perm}"{if in_array($perm, $g_group_permissions) || in_array('alles', $g_group_permissions)} checked="checked"{/if}{if $smarty.request.Id == 1 || $smarty.request.Id == $PAGE_NOT_FOUND_ID || in_array('alles', $g_group_permissions)} disabled="disabled"{/if} />
				</td>

				<td>
					{$smarty.config.$perm}
				</td>
			</tr>
{/foreach}

					</tbody>
            </table>


<div class="rowElem">
	<input type="submit" class="basicBtn ConfirmSettings" value="{#UGROUP_BUTTON_SAVE#}" />
</div>

</div>
</fieldset>
</form>

{/if}