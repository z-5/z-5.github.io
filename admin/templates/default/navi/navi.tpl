{if check_permission('documents')}
	{include file='documents/nav.tpl'}
{/if}

{if check_permission('rubrics')}
	{include file='rubs/nav.tpl'}
{/if}

{if check_permission('request')}
	{include file='request/nav.tpl'}
{/if}

{if check_permission('navigation')}
	{include file='navigation/nav.tpl'}
{/if}

{if check_permission('sysblocks')}
	{include file='sysblocks/nav.tpl'}
{/if}

{if check_permission('liveeditor') &&  $use_editor == 2}
	{include file='liveeditor/templates/nav.tpl'}
{/if}

{if check_permission('template')}
	{include file='templates/nav.tpl'}
{/if}

{if check_permission('finder')}
	{include file='finder/nav.tpl'}
{/if}

{if check_permission('modules')}
	{include file='modules/nav.tpl'}
{/if}

{if check_permission('user')}
	{include file='user/nav.tpl'}
{/if}

{if check_permission('group')}
	{include file='groups/nav.tpl'}
{/if}

{if check_permission('gen_settings')}
	{include file='settings/nav.tpl'}
{/if}

{if check_permission('dbactions')}
	{include file='dbactions/nav.tpl'}
{/if}

{if check_permission('logs')}
	{include file='logs/nav.tpl'}
{/if}
