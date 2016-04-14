<script language="javascript">
$(document).ready(function(){ldelim}
    $(".ConfirmDB").click(function(e){ldelim}
		e.preventDefault();
		var title = '{#DB_BUTTON_ACTION#}';
		var confirm = '{#DB_ACTION_WARNING#}';
		jConfirm(
				confirm,
				title,
				function(b){ldelim}
					if (b){ldelim}
        				$("#dbop").submit();
					{rdelim}
				{rdelim}
			);
	{rdelim});

    $(".ConfirmDBreset").click(function(e){ldelim}
		e.preventDefault();
		var title = '{#DB_BUTTON_ACTION#}';
		var confirm = '{#DB_ACTION_RESET#}';
		jConfirm(
				confirm,
				title,
				function(b){ldelim}
					if (b){ldelim}
        				$("#DBreset").submit();
					{rdelim}
				{rdelim}
			);
	{rdelim});
{rdelim});
</script>

<div class="title"><h5>{#DB_SUB_TITLE#}</h5></div>

<div class="widget" style="margin-top: 0px;">
    <div class="body">
		{#DB_TIPS#}
    </div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
			<li class="firstB"><a href="index.php" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li>{#DB_SUB_TITLE#}</li>
	    </ul>
	</div>
</div>

<div class="widget first">
<form action="index.php?do=dbsettings&cp={$sess}" method="post" name="dbop" id="dbop" class="mainForm">
        	<div class="head"><h5 class="iFrames">{#DB_OPTION_LIST#}</h5></div>

<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
	<tbody>
		<tr>
			<td rowspan="3" style="width: 20%;">
				<select style="width:300px" class="select" size="15" name="ta[]" multiple="multiple">
					{$tables}
				</select>
			</td>
			<td>
				<input style="border:0px" type="radio" name="action" checked="checked" value="optimize" />
			</td>
			<td>
				<h4>{#DB_OPTIMIZE_DATABASE#}</h4>
				<p>{#DB_OPTIMIZE_INFO#}</p>
			</td>
		</tr>
		<tr>
			<td>
				<input style="border:0px" type="radio" name="action" value="repair" />
			</td>
			<td>
				<h4>{#DB_REPAIR_DATABASE#}</h4>
				<p>{#DB_REPAIR_INFO#}</p>
			</td>
		</tr>
		<tr>
			<td>
				<input style="border:0px" type="radio" name="action" value="dump" />
			</td>
			<td>
				<h4>{#DB_BACKUP_DATABASE#}</h4>
				<p>{#DB_BACKUP_INFO#}</p>
			</td>
		</tr>
	</tbody>
</table>
<div class="rowElem">
	{#MAIN_STAT_MYSQL#} <strong><span class="cmsStats">{$db_size}</span></strong>
	<div class="fix"></div>
</div>
<div class="rowElem">
	<input type="submit" id="rest" class="basicBtn ConfirmDB" value="{#DB_BUTTON_ACTION#}" />
	<div class="fix"></div>
</div>
</form>

<div class="fix"></div>
</div>

{if $msg}
<ul class="messages">
	{$msg}
</ul>
{/if}

<div class="widget first">
	<form action="index.php?do=dbsettings&cp={$sess}" method="post" enctype="multipart/form-data" class="mainForm" id="DBreset">
	<div class="head"><h5 class="iFrames">{#DB_RESTORE_TITLE#}</h5></div>

<div class="rowElem">
	<input type="file" name="file" class="fileInput" id="fileInput" />
	<div class="fix"></div>
</div>

<div class="rowElem">
	<input type="submit" id="rest" class="basicBtn ConfirmDBreset" value="{#DB_BUTTON_RESTORE#}" />
	<input type="hidden" name="action" value="restore" />
<div class="fix"></div>
</div>

</form>
<div class="fix"></div>
</div>