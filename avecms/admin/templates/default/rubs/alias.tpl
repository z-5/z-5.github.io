<div class="first"></div>
<div class="title"><h5>{#RUBRIK_ALIAS_HEAD#}</h5></div>
<div class="widget" style="margin-top: 0px;"><div class="body">{#RUBRIK_ALIAS_HEAD_T#}</div></div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php?pop=1" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li>{#RUBRIK_ALIAS_HEAD#}</li>
	        <li>{#RUBRIK_ALIAS_HEAD_R#} <strong class="code">{$rubric_title|escape}</strong></li>
	        <li>{#RUBRIK_ALIAS_HEAD_F#} <strong class="code">{$rubric_field_title|escape}</strong></li>
	    </ul>
	</div>
</div>


{if $errors}
	<ul class="messages">
		{foreach from=$errors item=error}<li class="highlight red"><strong>{#RUBRIK_ALIAS_ERROR#}</strong> {$error}</li>{/foreach}
	</ul>
{/if}


<form name="alias_check" method="post" action="?do=rubs&action=alias_check&target={$smarty.request.target|escape}&field_id={$smarty.request.field_id|escape}&rubric_id={$smarty.request.rubric_id|escape}&pop=1&cp={$sess}" class="mainForm">

<div class="widget first">
<div class="head"><h5 class="iFrames">{#RUBRIK_ALIAS_ALIAS#}</h5></div>

	<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<col width="200">
		<col>
		<tr class="noborder">
			<td>{#RUBRIK_ALIAS_NAME#}</td>
			<td><div class="pr12"><input type="text" name="rubric_field_alias" value="{if $smarty.request.rubric_field_alias == ""}{$rubric_field_alias|escape|stripslashes}{else}{$smarty.request.rubric_field_alias}{/if}"></div></td>
		</tr>

		<tr>
			<td colspan="2">
				<input class="basicBtn" type="submit" value="{#RUBRIK_ALIAS_BUTT#}" />
			</td>
		</tr>

	</table>


</div>
</form>