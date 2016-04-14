<?php

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @filesource
 */

/**
 * Постраничная навигация документа
 *
 * @param string $text	текст многострочной части документа
 * @return string
 */
function document_pagination($text)
{
	global $AVE_Core;

// IE8                    <div style="page-break-after: always"><span style="display: none">&nbsp;</span></div>
// Chrome                 <div style="page-break-after: always; "><span style="DISPLAY:none">&nbsp;</span></div>
// FF                     <div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>
	$pages = preg_split('#<div style="page-break-after:[; ]*always[; ]*"><span style="display:[ ]*none[;]*">&nbsp;</span></div>#i', $text);
	$total_page = @sizeof($pages);
	
	if ($total_page > 1)
	{
		$text = @$pages[get_current_page('artpage')-1];

		$page_nav = ' <a class="pnav" href="index.php?id=' . $AVE_Core->curentdoc->Id
			. '&amp;doc=' . (empty($AVE_Core->curentdoc->document_alias) ? prepare_url($AVE_Core->curentdoc->document_title) : $AVE_Core->curentdoc->document_alias)
			. '&amp;artpage={s}'
//			. ((isset($_REQUEST['apage']) && is_numeric($_REQUEST['apage'])) ? '&amp;apage=' . $_REQUEST['apage'] : '')
//			. ((isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) ? '&amp;page=' . $_REQUEST['page'] : '')
			. '">{t}</a> ';
		$page_nav = get_pagination($total_page, 'artpage', $page_nav, get_settings('navi_box'));

		$text .= rewrite_link($page_nav);
	}
	
	$pages='<?php $GLOBALS[\'page_id\']['.$_REQUEST['id'].'][\'artpage\']='.$total_page.'; ?>';

	return $pages.$text;
}

/**
 * Формирование поля документа в соответствии с шаблоном отображения
 *
 * @param int $field_id	идентификатор поля
 * @return string
 */
function document_get_field($field_id)
{
	global $AVE_Core;
	
	if (is_array($field_id)) $field_id = $field_id[1];
	$document_fields = get_document_fields($AVE_Core->curentdoc->Id);


	if (!is_array($document_fields[$field_id]))$field_id=intval($document_fields[$field_id]);


	if (empty($document_fields[$field_id])) return '';


	$field_value = trim($document_fields[$field_id]['field_value']);

	$tpl_field_empty = $document_fields[$field_id]['tpl_field_empty'];

	if ($field_value == '' && $tpl_field_empty) return '';

	$field_type = $document_fields[$field_id]['rubric_field_type'];

	$rubric_field_template = trim($document_fields[$field_id]['rubric_field_template']);

//	$field_value = parse_hide($field_value);
//	$field_value = ($length != '') ? truncate_text($field_value, $length, '…', true) : $field_value;

	$func='get_field_'.$field_type;
	if(!is_callable($func)) $func='get_field_default';
	$field_value=$func($field_value,'doc',$field_id,$rubric_field_template,$tpl_field_empty);
	
	if (isset($_SESSION['user_adminmode']) && $_SESSION['user_adminmode'] == 1)
	{
		$wysmode = false;

		if (defined('UGROUP') && UGROUP == 1) $wysmode = true;
		elseif (isset($_SESSION[RUB_ID . '_alles'])   && $_SESSION[RUB_ID . '_alles']   == 1) $wysmode = true;
		elseif (isset($_SESSION[RUB_ID . '_editall']) && $_SESSION[RUB_ID . '_editall'] == 1) $wysmode = true;
		elseif (isset($_SESSION[RUB_ID . '_editown']) && $_SESSION[RUB_ID . '_editown'] == 1 &&
				isset($_SESSION['user_id']) && $_SESSION['user_id'] == $document_fields[$field_id]['document_author_id']) $wysmode = true;

		if ($wysmode)
		{
			$f_value .= '<link rel="stylesheet" href="'. ABS_PATH .'inc/stdimage/gear.css" type="text/css" />';
			$f_value .= '<div class="contextual-links-wrapper contextual-links-processed">';
			$f_value .= '<a class="contextual-links-trigger" href="javascript:void(0);" onclick=window.open("'.ABS_PATH.'admin/index.php?do=docs&action=edit&closeafter=1&RubrikId=' . RUB_ID . '&Id=' . ((int)$_REQUEST['id'])
				. '&pop=1&feld=' . $field_id . '#' . $field_id . '","EDIT","left=0,top=0,width=1300,height=900,scrollbars=1");></a>';
			$f_value_end .= '</div>';
		}
	}
	return @$f_value.@$field_value.@$f_value_end;
}

/**
 * Функция получения содержимого поля для обработки в шаблоне рубрики
 *
 * @param int $field_id	идентификатор поля, для [tag:fld:12] $field_id = 12
 * @param int $length	необязательный параметр,
 * 						количество возвращаемых символов содержимого поля.
 * 						если данный параметр указать со знаком минус
 * 						содержимое поля будет очищено от HTML-тегов.
 * @return string
 */
function document_get_field_value($field_id, $length = 0)
{
	if (!is_numeric($field_id)) return '';

	$document_fields = get_document_fields(get_current_document_id());

	$field_value = trim($document_fields[$field_id]['field_value']);

	if ($field_value != '')
	{
	$field_value = strip_tags($field_value, "<br /><strong><em><p><i>");

		if (is_numeric($length) && $length != 0)
		{
			if ($length < 0)
			{
				$field_value = strip_tags($field_value);
				$field_value = preg_replace('/  +/', ' ', $field_value);
				$field_value = trim($field_value);
				$length = abs($length);
			}
			$field_value = truncate_text($field_value, $length, '…', true);
		}
	}

	return $field_value;
}

?>