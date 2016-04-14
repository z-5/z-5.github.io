<div class="first"></div>

<div class="title"><h5>{#REQUEST_CONDITIONS#}</h5></div>

<div class="widget" style="margin-top: 0px;">
    <div class="body">
		{#REQUEST_CONDITION_TIP#}
    </div>
</div>

<div class="breadCrumbHolder module">
	<div class="breadCrumb module">
	    <ul>
	        <li class="firstB"><a href="index.php?pop=1" title="{#MAIN_PAGE#}">{#MAIN_PAGE#}</a></li>
	        <li>{#REQUEST_CONDITIONS#}</li>
			<li>{$request_title|escape|stripslashes}</li>
	    </ul>
	</div>
</div>


<form class="mainForm" action="index.php?do=request&action=konditionen&sub=save&rubric_id={$smarty.request.rubric_id|escape}&Id={$smarty.request.Id|escape}&pop=1&cp={$sess}" method="post">
{if $afkonditionen}
<div class="widget first">
<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">

		<thead>
			<tr>
				<td width="1"><div align="center"><span class="icon_sprite ico_delete"></span></div></td>
				<td>{#REQUEST_FROM_FILED#}</td>
				<td>{#REQUEST_OPERATOR#}</td>
				<td>{#REQUEST_VALUE#}</td>
			</tr>
		</thead>

		{foreach name=cond from=$afkonditionen item=condition}
			<tr>
				<td width="1"><input title="{#REQUEST_MARK_DELETE#}" name="del[{$condition->Id}]" type="checkbox" id="del_{$condition->Id}" value="1" class="toprightDir float" /></td>

				<td width="300">
					<select name="condition_field_id[{$condition->Id}]" id="Feld_{$condition->Id}" style="width:300px">
						{foreach from=$fields item=field}
							<option value="{$field->Id}" {if $condition->condition_field_id==$field->Id}selected{/if}>{$field->rubric_field_title|escape}</option>
						{/foreach}
					</select>
				</td>

				<td width="300">
					<select style="width:300px" name="condition_compare[{$condition->Id}]" id="Operator_{$condition->Id}">
						<option value="==" {if $condition->condition_compare=='=='}selected{/if}>{#REQUEST_COND_SELF#}</option>
						<option value="!=" {if $condition->condition_compare=='!='}selected{/if}>{#REQUEST_COND_NOSELF#}</option>
						<option value="%%" {if $condition->condition_compare=='%%'}selected{/if}>{#REQUEST_COND_USE#}</option>
						<option value="--" {if $condition->condition_compare=='--'}selected{/if}>{#REQUEST_COND_NOTUSE#}</option>
						<option value="%" {if $condition->condition_compare=='%'}selected{/if}>{#REQUEST_COND_START#}</option>
						<option value="<=" {if $condition->condition_compare=='<='}selected{/if}>{#REQUEST_SMALL1#}</option>
						<option value=">=" {if $condition->condition_compare=='>='}selected{/if}>{#REQUEST_BIG1#}</option>
						<option value="<" {if $condition->condition_compare=='<'}selected{/if}>{#REQUEST_SMALL2#}</option>
						<option value=">" {if $condition->condition_compare=='>'}selected{/if}>{#REQUEST_BIG2#}</option>

						<option value="N==" {if $condition->condition_compare=='N=='}selected{/if}>{#REQUEST_N_COND_SELF#}</option>
						<option value="N<=" {if $condition->condition_compare=='N<='}selected{/if}>{#REQUEST_N_SMALL1#}</option>
						<option value="N>=" {if $condition->condition_compare=='N>='}selected{/if}>{#REQUEST_N_BIG1#}</option>
						<option value="N<" {if $condition->condition_compare=='N<'}selected{/if}>{#REQUEST_N_SMALL2#}</option>
						<option value="N>" {if $condition->condition_compare=='N>'}selected{/if}>{#REQUEST_N_BIG2#}</option>

						<option value="IN=" {if $condition->condition_compare=='IN='}selected{/if}>{#REQUEST_IN_NUM#}</option>
						<option value="ANY" {if $condition->condition_compare=='ANY'}selected{/if}>{#REQUEST_ANY_NUM#}</option>
						<option value="FRE" {if $condition->condition_compare=='FRE'}selected{/if}>{#REQUEST_FREE#}</option>

					</select>
				</td>

				<td><div class="pr12"><input name="condition_value[{$condition->Id}]" type="text" id="Wert_{$condition->Id}" value="{$condition->condition_value|escape}" /> {if !$smarty.foreach.cond.last}{if $condition->condition_join=='AND'}{#REQUEST_CONR_AND#}{else}{#REQUEST_CONR_OR#}{/if}{/if}</div></td>
			</tr>
		{/foreach}
	</table>
	<div class="fix"></div>
</div>
{/if}
<div class="widget first">
<div class="head"><h5 class="iFrames">{#REQUEST_NEW_CONDITION#}</h5></div>

	<table cellpadding="0" cellspacing="0" width="100%" class="tableStatic">
		<thead>
		<tr>
			<td>{#REQUEST_FROM_FILED#}</td>
			<td>{#REQUEST_OPERATOR#}</td>
			<td colspan="2">{#REQUEST_VALUE#}</td>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td width="300">
				<select name="Feld_Neu" id="Feld_Neu" style="width:300px">
					{foreach from=$fields item=field}
						<option value="{$field->Id}">{$field->rubric_field_title|escape}</option>
					{/foreach}
				</select>
			</td>

			<td width="300">
				<select style="width:300px" name="Operator_Neu" id="Operator_Neu">
					<option value="==" selected>{#REQUEST_COND_SELF#}</option>
					<option value="!=">{#REQUEST_COND_NOSELF#}</option>
					<option value="%%">{#REQUEST_COND_USE#}</option>
					<option value="--">{#REQUEST_COND_NOTUSE#}</option>
					<option value="%">{#REQUEST_COND_START#}</option>
					<option value="<=">{#REQUEST_SMALL1#}</option>
					<option value=">=">{#REQUEST_BIG1#}</option>
					<option value="<">{#REQUEST_SMALL2#}</option>
					<option value=">">{#REQUEST_BIG2#}</option>
					<option value="N==">{#REQUEST_N_COND_SELF#}</option>
					<option value="N<=">{#REQUEST_N_SMALL1#}</option>
					<option value="N>=">{#REQUEST_N_BIG1#}</option>
					<option value="N<">{#REQUEST_N_SMALL2#}</option>
					<option value="N>">{#REQUEST_N_BIG2#}</option>
					<option value="IN=">{#REQUEST_IN_NUM#}</option>
					<option value="ANY">{#REQUEST_ANY_NUM#}</option>
					<option value="FRE">{#REQUEST_FREE#}</option>
				</select>
			</td>

			<td style="width:60px">
				<select style="width:60px" name="Oper_Neu" id="Oper_Neu">
					<option value="OR" {if $condition->condition_join=='OR'}selected{/if}>{#REQUEST_CONR_OR#}</option>
					<option value="AND" {if $condition->condition_join=='AND'}selected{/if}>{#REQUEST_CONR_AND#}</option>
				</select>
			</td>
			<td>
				<div class="pr12"><input name="Wert_Neu" type="text" id="Wert_Neu" value="" /></div>
			</td>
		</tr>

		<tr>
			<td colspan="4">
				<input type="submit" value="{#BUTTON_SAVE#}" class="basicBtn" />
				<input onclick="self.close();" type="button" class="redBtn" value="{#REQUEST_BUTTON_CLOSE#}" />
			</td>
		</tr>
		</tbody>
	</table>
	<div class="fix"></div>
</div>
</form>

<br /><br /><br />