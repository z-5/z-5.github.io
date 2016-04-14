<script type="text/javascript" language="JavaScript">
$(document).ready(function(){ldelim}

{if check_permission('user_new')}
	$(".AddUser").click( function(e) {ldelim}
		e.preventDefault();
		var user_group = $('#add_user #user_name').fieldValue();
		var title = '{#USER_NEW_ADD#}';
		var text = '{#USER_NO_FIRSTNAME#}';
		if (user_group == ""){ldelim}
			jAlert(text,title);
		{rdelim}else{ldelim}
			$.alerts._overlay('show');
			$("#add_user").submit();
		{rdelim}
	{rdelim});
{/if}

{rdelim});
</script>

<div class="title"><h5>{#USER_SUB_TITLE#}</h5></div>

<div class="widget" style="margin-top: 0px;">
    <div class="body">
		{#USER_TIP1#}
    </div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li>{#USER_SUB_TITLE#}</li>
	    </ul>
	</div>
</div>

<div class="widget first">
	<div class="head closed active"><h5>{#MAIN_SEARCH_USERS#}</h5></div>
	<div style="display: block;">
<form action="index.php?do=user&cp={$sess}" method="post" class="mainForm">

<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
	<thead>
		<tr>
			<td>{#MAIN_USER_PARAMS#}</td>
			<td style="width: 250px;">{#MAIN_USER_GROUP#}</td>
			<td style="width: 250px;">{#MAIN_ALL_USER_GROUP#}</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><div style="margin-right: 10px"><input name="query" type="text" value="{$smarty.request.query|escape|stripslashes}" /></div></td>
			<td>
				<select style="width: 250px;" name="user_group">
					<option value="0">{#MAIN_ALL_USER_GROUP#}</option>
					{foreach from=$ugroups item=g}
						<option value="{$g->user_group}"{if $g->user_group==$smarty.request.user_group} selected="selected"{/if}>{$g->user_group_name|escape}</option>
					{/foreach}
				</select>
			</td>
			<td>
				<select style="width: 250px;" name="status">
					<option value="all"{if $smarty.request.status=='all'} selected="selected"{/if}>{#MAIN_USER_STATUS_ALL#}</option>
					<option value="1"{if $smarty.request.status=='1'} selected="selected"{/if}>{#MAIN_USER_STATUS_ACTIVE#}</option>
					<option value="0"{if $smarty.request.status=='0'} selected="selected"{/if}>{#MAIN_USER_STATUS_INACTIVE#}</option>
				</select>
			</td>
		</tr>
	</tbody>
</table>

<div class="rowElem"><input type="submit" class="basicBtn" value="{#MAIN_BUTTON_SEARCH#}" /></div>

</form>
	</div>
</div>

<div class="widget first">

	<ul class="tabs">
	    <li class="activeTab"><a href="#tab1">{#USER_ALL#}</a></li>
	    <li class=""><a href="#tab2">{#USER_NEW_ADD#}</a></li>
	</ul>

	<div class="tab_container">
		<div id="tab1" class="tab_content" style="display: block;">

	{if !$users}
		<ul class="messages first">
			<li class="highlight red"><strong>Ошибка:</strong> {#USER_LIST_EMPTY#}</li>
		</ul>
	{else}

	{if check_permission('user_edit')}
		<form method="post" action="index.php?do=user&cp={$sess}&action=quicksave" class="mainForm">
	{/if}



			<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
	<col width="20">
	<col width="20">
	<col width="50">
	<col>
	<col width="220">
	<col width="150">
	<col width="150">
	<col width="16">
	<col width="16">
	<col width="16">
	<col width="16">
				<thead>
                	<tr>
			<td>{#USER_ID#}</td>
			<td>-</td>
			<td>{#USER_AVATAR#}</td>
			<td>{#USER_NAME#}</td>
			<td>{#USER_GROUP#}</td>
			<td>{#USER_LAST_VISIT#}</td>
			<td>{#USER_REGISTER_DATE#}</td>
			<td colspan="4"><div align="center">{#USER_ACTION#}</div></td>
                    </tr>
				</thead>
				<tbody>



		{foreach from=$users item=user}
			<tr>
				<td align="center">{$user->Id}</td>
				<td align="center"><input title="{#USER_MARK_DELETE#}" class="topDir" name="del[{$user->Id}]" type="checkbox" id="del[{$user->Id}]" value="1" {if !check_permission('user_loesch') || $user->user_group==1 || $user->Id==$smarty.session.user_id}disabled="disabled"{/if} /></td>
				<td align="center">{if $user->avatar}<img src="{$user->avatar}" class="rounded">{else}<img src="{$tpl_dir}/images/user.png" class="rounded" alt="" />{/if}</td>
				<td>
					{if check_permission('user_edit')}
						<a title="{#USER_EDIT#}" href="index.php?do=user&action=edit&Id={$user->Id}&cp={$sess}" class="topDir link">
					{/if}
					<strong>{$user->user_name|escape}{if $user->firstname && $user->lastname} ({$user->firstname|escape} {$user->lastname|escape}){/if}</strong>
					{if check_permission('user_edit')}</a>{/if}<br /><small>{$user->email|escape} (IP:{$user->reg_ip|escape})</small>
				</td>

				<td>
					{if !$user->status}
						{#USER_STATUS_WAIT#}
					{else}
						<select name="user_group[{$user->Id}]" style="width: 200px;">
							{foreach from=$ugroups item=g}
								{if $g->user_group!=2}
									<option value="{$g->user_group}" {if $user->Id==1 && $g->user_group!=1} disabled{else}{if $g->user_group==$user->user_group}selected{/if}{/if}>{$g->user_group_name|escape}</option>
								{/if}
							{/foreach}
						</select>
					{/if}
				</td>

				<td align="center">
					{if $user->status AND $user->last_visit>0}
						<span class="date_text dgrey">{$user->last_visit|date_format:$TIME_FORMAT|pretty_date}</span>
					{else}
						-
					{/if}
				</td>

				<td align="center"><span class="date_text dgrey">{$user->reg_time|date_format:$TIME_FORMAT|pretty_date}</span></td>

				<td nowrap="nowrap" align="center" width="20">
					{if check_permission('user_edit')}
						<a title="{#USER_EDIT#}" href="index.php?do=user&action=edit&Id={$user->Id}&cp={$sess}" class="topDir icon_sprite ico_edit"></a>
					{else}
						<a title="{#USER_NO_CHANGE#}" href="javascript:void(0);" class="topleftDir icon_sprite ico_edit_no"></a>
					{/if}
				</td>

				<td nowrap="nowrap" align="center" width="20">
					{if $user->Id != 1}
						{if check_permission('user_loesch') && $user->Id!=$smarty.session.user_id}
							<a title="{#USER_DELETE#}" dir="{#USER_DELETE#}" name="{#USER_DELETE_CONFIRM#}" href="index.php?do=user&action=delete&Id={$user->Id}&cp={$sess}" class="topDir ConfirmDelete icon_sprite ico_delete"></a>
						{else}
							<span title="" class="topleftDir icon_sprite ico_delete_no"></span>
						{/if}
					{else}
						<span title="" class="topleftDir icon_sprite ico_delete_no"></span>
					{/if}
				</td>

				<td width="20">
					{if $user->IsShop && $user->Orders}
						<a title="{#USER_ORDERS#}" href="javascript:void(0)" onclick="window.open('index.php?do=modules&action=modedit&mod=shop&moduleaction=showorders&cp={$sess}&search=1&Query={$user->Id}&start_Day=1&start_Month=1&start_Year=2005&pop=1','best','left=0,top=0,width=960,height=700,scrollbars=1,resizable=1');" class="topleftDir icon_sprite ico_shop"></a>
					{else}
						<span title="" class="topleftDir icon_sprite ico_blanc"></span>
					{/if}
				</td>

				<td width="20">
					{if $user->IsShop}
						<a title="{#USER_DOWNLOADS#}" href="javascript:void(0);" onclick="window.open('index.php?do=modules&action=modedit&mod=shop&moduleaction=shop_downloads&cp={$sess}&Id={$i.Id}&pop=1&User={$user->Id}&N={$user->lastname|urlencode}','sd','top=0,left=0,height=600,width=970,scrollbars=1');" class="topleftDir icon_sprite ico_install"></a>
					{else}
						<span title="" class="topleftDir icon_sprite ico_blanc"></span>
					{/if}
				</td>
			</tr>
		{/foreach}
				</tbody>
			</table>

			{if check_permission('user_edit')}
			<div class="rowElem">
				<input type="submit" class="basicBtn ConfirmSettings" value="{#USER_BUTTON_SAVE#}" />
			</div>
			</form>
			{/if}

{/if}

		</div>

		<div id="tab2" class="tab_content" style="display: none;">
			<form id="add_user" method="post" action="index.php?do=user&action=new&cp={$sess}" class="mainForm">
			<div class="rowElem">
				<label>{#USER_FIRSTNAME_ADD#}</label>
				<div class="formRight"><input placeholder="{#USER_NAME2#}" name="user_name" type="text" id="user_name" value="" style="width: 400px">
				&nbsp;<input type="button" class="basicBtn AddUser" value="{#USER_BUTTON_ADD#}" />
				</div>
				<div class="fix"></div>
			</div>
			</form>
		</div>

	</div>

	<div class="fix"></div>
</div>

{if $page_nav}
	<div class="pagination">
	<ul class="pages">
		{$page_nav}
	</ul>
	</div>
{/if}
