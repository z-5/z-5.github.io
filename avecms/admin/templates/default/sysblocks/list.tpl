<script type="text/javascript" language="JavaScript">
$(document).ready(function(){ldelim}

		$(".AddSysBlock").click( function(e) {ldelim}
			e.preventDefault();
			var user_group = $('#add_sysblock #sysblock_name').fieldValue();
			var title = '{#SYSBLOCK_ADD#}';
			var text = '{#SYSBLOCK_INNAME#}';
			if (user_group == ""){ldelim}
				jAlert(text,title);
			{rdelim}else{ldelim}
				$.alerts._overlay('show');
				$("#add_sysblock").submit();
			{rdelim}
		{rdelim});

	$(".CopyBlock").click( function(e) {ldelim}
		e.preventDefault();
		var href = $(this).attr('href');
		var title = '{#SYSBLOCK_COPY#}';
		var text = '{#SYSBLOCK_COPY_TIP#}';
		jPrompt(text, '', title, function(b){ldelim}
					if (b){ldelim}
						$.alerts._overlay('show');
        				window.location = href + '&sysblock_name=' + b;
						{rdelim}else{ldelim}
							$.jGrowl("{#MAIN_NO_ADD_BLOCK#}", {ldelim}theme: 'error'{rdelim});
						{rdelim}
				{rdelim}
			);
	{rdelim});

{rdelim});
</script>

<div class="title"><h5>{#SYSBLOCK_EDIT#}</h5></div>

<div class="widget" style="margin-top: 0px;">
    <div class="body">
		{#SYSBLOCK_EDIT_TIP#}
    </div>
</div>


<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li>{#SYSBLOCK_EDIT#}</li>
	    </ul>
	</div>
</div>


<div class="widget first">
	<ul class="tabs">
	    <li class="activeTab"><a href="#tab1">{#SYSBLOCK_HEAD#}</a></li>
	    <li class=""><a href="#tab2">{#SYSBLOCK_ADD#}</a></li>
	</ul>

		<div class="tab_container">
			<div id="tab1" class="tab_content" style="display: block;">

<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic mainForm">
		<col width="20">
		<col>
		<col width="200">
		<col width="200">
		<col width="100">
		<col width="20">
		<col width="20">
		<col width="20">
		<thead>
		<tr>
			<td>{#SYSBLOCK_ID#}</td>
			<td>{#SYSBLOCK_NAME#}</td>
			<td>{#SYSBLOCK_AUTHOR#}</td>
			<td>{#SYSBLOCK_DATE#}</td>
			<td>{#SYSBLOCK_TAG#}</td>
			<td colspan="3">{#SYSBLOCK_ACTIONS#}</td>
		</tr>
		</thead>
		<tbody>
		{if $sys_blocks}
		{foreach from=$sys_blocks item=sysblock}
			<tr id="tr{$sysblock->id}">
				<td class="itcen">{$sysblock->id}</td>
				<td>
					<a class="topDir link" title="{#SYSBLOCK_EDIT_HINT#}" href="index.php?do=sysblocks&action=edit&cp={$sess}&id={$sysblock->id}">
						<strong>{$sysblock->sysblock_name|escape}</strong>
					</a>
				</td>
				<td align="center">{$sysblock->sysblock_author_id|escape}</td>

				<td align="center">
					<span class="date_text dgrey">{$sysblock->sysblock_created|date_format:$TIME_FORMAT|pretty_date}</span>
				</td>
				<td>
					<div><input name="textfield" type="text" value="[tag:sysblock:{$sysblock->id}]" readonly style="width: 150px;" /></div>
				</td>
				<td nowrap="nowrap" width="1%" align="center">
				{if check_permission('sysblocks')}
					<a class="topleftDir CopyBlock icon_sprite ico_copy" title="{#SYSBLOCK_COPY#}" href="index.php?do=sysblocks&action=multi&sub=save&id={$sysblock->id}&cp={$sess}"></a>
				{/if}
				</td>
				<td align="center">
				{if check_permission('sysblocks')}
					<a class="topleftDir icon_sprite ico_edit" title="{#SYSBLOCK_EDIT_HINT#}" href="index.php?do=sysblocks&action=edit&cp={$sess}&id={$sysblock->id}"></a>
				{/if}
				</td>
				<td align="center">
				{if check_permission('sysblocks')}
					<a class="topleftDir ConfirmDelete icon_sprite ico_delete" title="{#SYSBLOCK_DELETE_HINT#}" dir="{#SYSBLOCK_DELETE_HINT#}" name="{#SYSBLOCK_DEL_HINT#}" href="index.php?do=sysblocks&action=del&cp={$sess}&id={$sysblock->id}" id="{$sysblock->id}"></a>
				{/if}
				</td>
			</tr>
		{/foreach}
		{else}
			<tr>
				<td colspan="9">
					<ul class="messages">
						<li class="highlight yellow">{#SYSBLOCK_NO_ITEMS#}</li>
					</ul>
				</td>
			</tr>
		{/if}
	</tbody>
</table>
		</div>

			<div id="tab2" class="tab_content" style="display: none;">
					<form id="add_sysblock" method="post" action="index.php?do=sysblocks&action=new&cp={$sess}" class="mainForm">
					<div class="rowElem">
						<label>{#SYSBLOCK_NAME#}</label>
						<div class="formRight"><input name="sysblock_name" type="text" id="sysblock_name" value="" placeholder="{#SYSBLOCK_NAME#}" style="width: 400px">
						&nbsp;<input type="button" class="basicBtn AddSysBlock" value="{#SYSBLOCK_ADD_BUTTON#}" />
						</div>
						<div class="fix"></div>
					</div>
					</form>
			</div>
		</div>
	<div class="fix"></div>
</div>

