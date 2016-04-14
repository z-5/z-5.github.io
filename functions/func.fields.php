<?php
// Определяем пустое изображение
@$img_pixel = 'templates/' . $_SESSION['admin_theme'] . '/images/blanc.gif';

function get_field_default($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document;
	$res=0;
	switch ($type)
	{
		case 'edit' :
				$field  = '<a name="' . $field_id . '"></a>';
				$field .= '<input id="feld_' . $field_id . '" type="text" style="width:' . $AVE_Document->_field_width . '" name="feld[' . $field_id . ']" value="' . htmlspecialchars($field_value, ENT_QUOTES) . '"> ';
				$res=$field;
			break;

		case 'doc' :
			//$field_value = htmlspecialchars($field_value, ENT_QUOTES);
			$field_value = pretty_chars($field_value);
			$field_value = clean_php($field_value);
			$field_value = str_replace('"', '&quot;', $field_value);
			if (!$tpl_field_empty)
			{
				$field_param = explode('|', $field_value);
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
			}
			$res=$field_value;
			break;

		case 'req' :
			$field_value = clean_php($field_value);
			$field_param = explode('|', $field_value);
			$field_param[1] = isset($field_param[1]) ? $field_param[1] : '';
			$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $document_fields[$rubric_id]['rubric_field_template_request']);
			$res=$field_value;
			break;
	}
	return ($res ? $res : $field_value);
}

//Однострочное
function get_field_kurztext($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document;
	$res=0;
	switch ($type)
	{
		case 'edit' :
				$field  = '<a name="' . $field_id . '"></a>';
				$field .= '<div class="pr12"><input id="feld_' . $field_id . '" type="text" name="feld[' . $field_id . ']" value="' . htmlspecialchars($field_value, ENT_QUOTES) . '"></div> ';
				$res=$field;
			break;

		case 'doc' :
			//$field_value = htmlspecialchars($field_value, ENT_QUOTES);
			$field_value = pretty_chars($field_value);
			$field_value = clean_php($field_value);
			$field_value = str_replace('"', '&quot;', $field_value);
			if (!$tpl_field_empty)
			{
				$field_param = explode('|', $field_value);
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
			}
			$res=$field_value;
			break;

		case 'req' :
			$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
			break;

		case 'name' :
			$res='FIELD_TEXT';
		break;
	}
	return ($res ? $res : $field_value);
}

//Многострочное (Упрощенное)
function get_field_smalltext($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document;
	$res=0;
	switch ($type)
	{
		case 'edit' :
				if (isset($_COOKIE['no_wysiwyg']) && $_COOKIE['no_wysiwyg'] == 1)
				{
					$field  = "<a name=\"" . $field_id . "\"></a>";
					$field .= "<textarea style=\"width:" . $AVE_Document->_textarea_width_small . "; height:" . $AVE_Document->_textarea_height_small . "\"  name=\"feld[" . $field_id . "]\">" . $field_value . "</textarea>";
				}
				else
				{
					switch (@$_SESSION['use_editor']) {
					case '0': // стандартный редактор
						$oFCKeditor = new FCKeditor('feld[' . $field_id . ']') ;
						$oFCKeditor->Height = @$AVE_Document->_textarea_height_small;
						$oFCKeditor->Value  = $field_value;
						$oFCKeditor->ToolbarSet = 'cpengine_small';
						$field = $oFCKeditor->Create($field_id);
						break;
						
					case '1': // Elrte и Elfinder 
						$field  = '<a name="' . $field_id . '"></a>';
						$field  .='<textarea style="width:' . $AVE_Document->_textarea_width_small . ';height:' . $AVE_Document->_textarea_height_small . '" name="feld[' . $field_id . ']" class="small-editor">' . $field_value . '</textarea>';
						break;
						
					case '2': // Innova
						require(BASE_DIR . "/admin/templates/default/liveeditor/f_config/li_set_smf.php");
						$field  = '<a name="' . $field_id . '"></a>';
						$field .= "<textarea style=\"width:" . $AVE_Document->_textarea_width_small . "; height:" . $AVE_Document->_textarea_height_small . "\"  name=\"feld[" . $field_id . "]\" Id=\"small-editor[" . $field_id . "]\">" . $field_value . "</textarea>";
						$field  .= $innova[2];
						break;

					case '3': // CKEditor
						$oCKeditor = new CKeditor();
						$oCKeditor->returnOutput = true;
						$oCKeditor->config['toolbar'] = 'Small';
						$config = array();
						$field = $oCKeditor->editor('feld[' . $field_id . ']', $field_value, $config);
						break;
					default:
						$field=$field_value;
						break;
					}
				}
				$res=$field;
				break;
		case 'doc' :
			$field_value = document_pagination($field_value);
			$field_value = pretty_chars($field_value);
			if (!$tpl_field_empty)
			{
				$field_param = explode('|', $field_value);
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
			}
			$res=$field_value;
			break;

		case 'req' :
			$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
			break;

		case 'name' :
			$res='FIELD_TEXTAREA_S';
		break;
	}
	return ($res ? $res : $field_value);

}

//Многострочное
function get_field_langtext($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document;
	$res=0;
	switch ($type)
	{
		case 'edit' :
				if (isset($_COOKIE['no_wysiwyg']) && $_COOKIE['no_wysiwyg'] == 1)
				{
					$field  = '<a name="' . $field_id . '"></a>';
					$field .= '<textarea style="width:' . $AVE_Document->_textarea_width . ';height:' . $AVE_Document->_textarea_height . '" name="feld[' . $field_id . ']">' . $field_value . '</textarea>';
				}
				else
				{			
					switch ($_SESSION['use_editor']) {
					case '0': // стандартный редактор
						$oFCKeditor = new FCKeditor('feld[' . $field_id . ']') ;
						$oFCKeditor->Height = $AVE_Document->_textarea_height;
						$oFCKeditor->Value  = $field_value;
						$field  = $oFCKeditor->Create($field_id);
						break;

					case '1': // Elrte и Elfinder 
						$field  = '<a name="' . $field_id . '"></a>';
						$field  .='<textarea style="width:' . $AVE_Document->_textarea_width . ';height:' . $AVE_Document->_textarea_height . '" name="feld[' . $field_id . ']" class="editor">' . $field_value . '</textarea></div>';
						break;

					case '2': // Innova
						require(BASE_DIR . "/admin/templates/default/liveeditor/f_config/li_set_mf.php");
						$field  = '<a name="' . $field_id . '"></a>';
						$field  .='<textarea style="width:' . $AVE_Document->_textarea_width . ';height:' . $AVE_Document->_textarea_height . '" name="feld[' . $field_id . ']" Id="editor[' . $field_id . ']">' . $field_value . '</textarea>';
						$field  .= $innova[1];
						break;

					case '3': // CKEditor
						$oCKeditor = new CKeditor(); 
						$oCKeditor->returnOutput = true;
						$oCKeditor->config['toolbar'] = 'Big';
						$oCKeditor->config['height'] = 400;
						$config = array();
						$field = $oCKeditor->editor('feld[' . $field_id . ']', $field_value, $config);
						break;
					}
				}
				$res=$field;
			break;
		case 'doc' :
			$res=get_field_smalltext($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
			break;

		case 'req' :
			$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
			break;

		case 'name' :
			$res='FIELD_TEXTAREA';
		break;
	}
	return ($res ? $res : $field_value);
}

//Изображение
function get_field_bild($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document,$img_pixel;
	$res=0; 
	switch ($type)
	{
		case 'edit' :
				$blanc = 'templates/' . $_SESSION['admin_theme'] . '/images/blanc.gif';
				$massiv = explode('|', $field_value);
				$field = "<div><img id=\"preview__" . $field_id . "\" src=\"" . (!empty($field_value) ? '../' . make_thumbnail(array('link' => $massiv[0])) : $blanc) . "\" class=\"image_field\" alt=\"\" border=\"0\" /></div>";

				switch ($_SESSION['use_editor']) {
					case '0': // стандартный редактор

					case '2':
						$field .= "<input type=\"text\" style=\"width:" . $AVE_Document->_field_width . "\" name=\"feld[" . $field_id . "]\" value=\"" . htmlspecialchars($field_value, ENT_QUOTES) . "\" id=\"image__" . $field_id . "\" />&nbsp;";
						$field .= "<input type=\"button\" class=\"basicBtn\" value=\"...\" title=\"" . $AVE_Template->get_config_vars('MAIN_OPEN_MEDIAPATH') . "\" onclick=\"browse_uploads('image__" . $field_id . "');\" />";
						break;

					case '1': // Elrte и Elfinder
						$field .= "<input class=\"docm finder\" type=\"text\" style=\"width:" . $AVE_Document->_field_width . "\" name=\"feld[" . $field_id . "]\" value=\"" . htmlspecialchars($field_value, ENT_QUOTES) . "\" id=\"img_feld__" . $field_id . "\"/>&nbsp;";
						$field .= "<span class=\"button basicBtn dialog_images\" rel=\"". $field_id ."\">" . $AVE_Template->get_config_vars('MAIN_OPEN_MEDIAPATH') . "</span>";
						break;

					case '3': // CK
						$field .= "<input type=\"text\" style=\"width:" . $AVE_Document->_field_width . "\" name=\"feld[" . $field_id . "]\" value=\"" . htmlspecialchars($field_value, ENT_QUOTES) . "\" id=\"image__" . $field_id . "\" />&nbsp;";
						$field .= "<input type=\"button\" class=\"basicBtn\" value=\"...\" title=\"" . $AVE_Template->get_config_vars('MAIN_OPEN_MEDIAPATH') . "\" onclick=\"browse_uploads('image__" . $field_id . "');\" />";
						break;

				}
				$res=$field;
				break;
		case 'doc' :
			$field_value = clean_php($field_value);
			$field_param = explode('|', $field_value);
			if ($tpl_field_empty)
			{
				$field_value = '<img alt="' . (isset($field_param[1]) ? $field_param[1] : '')
					. '" src="' . ABS_PATH . $field_param[0] . '" border="0" />';
			}
			else
			{
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
				$field_value = preg_replace_callback('/\[tag:([r|c|f|t]\d+x\d+r*):(.+?)]/', 'callback_make_thumbnail', $field_value);
			}
			$res=$field_value;
			break;

		case 'req' :
			$field_value = clean_php($field_value);
			$field_param = explode('|', $field_value);
			$field_param[1] = isset($field_param[1]) ? $field_param[1] : '';
			if ($document_fields[$rubric_id]['tpl_req_empty'])
			{
				$field_value = '<img src="' . ABS_PATH . $field_param[0] . '" alt="' . $field_param[1] . '" border="0" />';
			}
			else
			{
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $document_fields[$rubric_id]['rubric_field_template_request']);
                $field_value = preg_replace_callback('/\[tag:([r|c|f|t]\d+x\d+r*):(.+?)]/', 'callback_make_thumbnail', $field_value);
			}
			$maxlength = '';
			$res=$field_value;
			break;

		case 'name' :
			$res='FIELD_IMAGE';
		break;
	}
	return ($res ? $res : $field_value);
}

//Выпадающий список
function get_field_dropdown($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document;
	$res=0;
	switch ($type)
	{
		case 'edit' :
				$items = explode(',', $dropdown);
				@$field = "<select name=\"feld[" . $field_id . "]\">";
				$cnt = sizeof($items);
				for ($i=0;$i<$cnt;$i++)
				{
					$field .= "<option value=\"" . htmlspecialchars($items[$i], ENT_QUOTES) . "\"" . ((trim($field_value) == trim($items[$i])) ? " selected=\"selected\"" : "") . ">" . htmlspecialchars($items[$i], ENT_QUOTES) . "</option>";
				}
				$field .= "</select>";
				$res=$field;
			break;

		case 'doc' :
			@$field_value = clean_php($field_value);
			if (!$tpl_field_empty)
			{
				$field_param = explode('|', $field_value);
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
			}
			$res=$field_value;
			break;
			
		case 'req' :
			$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
			break;
			
		case 'name' :
			$res='FIELD_DROPDOWN';
		break;

	}
	return ($res ? $res : $field_value);
}

//Чекбокс
function get_field_checkbox($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document;
	$res=0;
	switch ($type)
	{
		case 'edit' :
			$field = "<input type=\"hidden\" name=\"feld[" . $field_id . "]\" value=\"\">
				      <input type=\"checkbox\" name=\"feld[" . $field_id . "]\" value=\"1\"" . (((int)$field_value == 1) ? " checked" : "") . ">";
			$res=$field;
			break;

		case 'doc' :
			$field_value = clean_php($field_value);
			if ((int)$field_value != 1) $field_value = 0;
			if (!$tpl_field_empty)
			{
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_value', $rubric_field_template);
			}
			$res=$field_value;
			break;

		case 'req' :
			$field_value = clean_php($field_value);
			if ((int)$field_value != 1) $field_value = 0;
			$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_value', $document_fields[$rubric_id]['rubric_field_template_request']);
			$res=$field_value;
			break;

		case 'name' :
			$res='FIELD_CHECKBOX';
		break;

	}
	return ($res ? $res : $field_value);
}

//Мульти список
function get_field_multidropdown($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document;
	$res=0;
	switch ($type)
	{
		case 'edit' :
				$items = explode(',', $dropdown);
				@$field_value = unserialize($field_value);
				$field = "<select size=\"10\" class=\"select\" style=\"min-width: 300px; max-width: 600px;\" multiple=\"multiple\" name=\"feld[" . $field_id . "][]\">";
				$cnt = sizeof($items);
				for ($i=0;$i<$cnt;$i++)
				{
					if (@in_array($items[$i], $field_value))
					{
						$field .= "<option value=\"" . htmlspecialchars($items[$i], ENT_QUOTES) .  "\" selected=\"selected\">" . htmlspecialchars($items[$i], ENT_QUOTES) . "</option>";
					}else{
				   		$field .= "<option value=\"" . htmlspecialchars($items[$i], ENT_QUOTES) . "\">" . htmlspecialchars($items[$i], ENT_QUOTES) . "</option>";
					}
				}
				$field .= "</select>";
				$res=$field;
			break;

		case 'doc' :
			@$massa=unserialize($field_value);
			$res='';
			if($massa!=false)
				foreach($massa as $k=>$v)
				{
					$v = clean_php($v);
					$field_param = explode('|', $v);
					if($v){
						if ($tpl_field_empty)
						{
							$v = $field_param[0];
						}
						else
						{
							$v = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
						}
					}
					$res.=$v;
				}
			break;

		case 'req' :
			@$massa=unserialize($field_value);
			$res='';
			if($massa!=false)
				foreach($massa as $k=>$v)
				{
					$v = clean_php($v);
					$field_param = explode('|', $v);
					if($v){
						if ($tpl_field_empty)
						{
							$v = $field_param[0];
						}
						else
						{
							$v = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
						}
					}
					$res.=$v;
				}
			break;

		case 'name' :
			$res='FIELD_MDROPDOWN';
		break;

	}
	return ($res ? $res : $field_value);
}

//Ссылка
function get_field_link($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document;
	$res=0;
	switch ($type)
	{
		case 'edit' :
				$field  = "<a name=\"" . $field_id . "\"></a>";
				$field .= "<input id=\"feld_" . $field_id . "\" type=\"text\" style=\"width:" . $AVE_Document->_field_width . "\" name=\"feld[" . $field_id . "]\" value=\"" . htmlspecialchars($field_value, ENT_QUOTES) . "\">&nbsp;";
				$field .= "<input value=\"" . $AVE_Template->get_config_vars('MAIN_BROWSE_DOCUMENTS') . "\" class=\"basicBtn\" type=\"button\" onclick=\"openLinkWin('feld_" . $field_id . "', 'feld_" . $field_id . "');\" />";
				$res=$field;
			break;

		case 'doc' :
			$field_value = clean_php($field_value);
			$field_param = explode('|', $field_value);
			$field_param[1] = empty($field_param[1]) ? $field_param[0] : $field_param[1];
			if ($tpl_field_empty)
			{
				$field_value = ' <a target="_self" href="' . ABS_PATH . $field_param[0] . '">' . $field_param[1] . '</a>';
			}
			else
			{
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
			}
			$res=$field_value;
			break;

		case 'req' :
			$field_value = clean_php($field_value);
			$field_param = explode('|', $field_value);
			if (empty($field_param[1])) $field_param[1] = $field_param[0];
			if ($document_fields[$rubric_id]['tpl_req_empty'])
			{
				$field_value = " <a target=\"_self\" href=\"" . ABS_PATH . $field_param[0] . "\">" . $field_param[1] . "</a>";
			}
			else
			{
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $document_fields[$rubric_id]['rubric_field_template_request']);
			}
			$maxlength = '';
			$res=$field_value;
			break;

		case 'name' :
			$res='FIELD_LINK';
		break;
	
	}
	return ($res ? $res : $field_value);
}
//Flash-ролик
function get_field_flash($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document,$img_pixel;
	$res=0;
	switch ($type)
	{
		case 'edit' :
				$field  = "<a name=\"" . $field_id . "\"></a>";
				$field .= "<div style=\"display:none\" id=\"feld_" . $field_id . "\"><img style=\"display:none\" id=\"_img_feld__" . $field_id . "\" src=\"". (!empty($field_value) ? htmlspecialchars($field_value, ENT_QUOTES) : $img_pixel) . "\" alt=\"\" border=\"0\" /></div>";
				$field .= "<div style=\"display:none\" id=\"span_feld__" . $field_id . "\"></div>";
				$field .= "<input type=\"text\" style=\"width:" . $AVE_Document->_field_width . "\" name=\"feld[" . $field_id . "]\" value=\"" . htmlspecialchars($field_value, ENT_QUOTES) . "\" id=\"img_feld__" . $field_id . "\" />&nbsp;";
				$field .= "<input value=\"" . $AVE_Template->get_config_vars('MAIN_OPEN_MEDIAPATH') . "\" class=\"basicBtn\" type=\"button\" onclick=\"cp_imagepop('img_feld__" . $field_id . "', '', '', '0');\" />";
				$field .= '<a class="basicBtn topDir" title="'.$AVE_Template->get_config_vars('DOC_FLASH_TYPE_HELP').'" href="#">?</a>';
				$res=$field;
			break;

		case 'doc' :
			$field_value = clean_php($field_value);
			$field_param = explode('|', $field_value);
			$field_param[1] = (!empty($field_param[1]) && is_numeric($field_param[1])) ? $field_param[1] : 470;
			$field_param[2] = (!empty($field_param[2]) && is_numeric($field_param[2])) ? $field_param[2] : 320;
			if ($tpl_field_empty)
			{
				$field_value = '<embed scale="exactfit" width="' . $field_param[1] . '" height="' . $field_param[2]
					. '" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" src="'
					. ABS_PATH . $field_param[0] . '" play="true" loop="true" menu="true"></embed>';
			}
			else
			{
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
			}
			$res=$field_value;
			break;

		case 'req' :
				$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
			break;

		case 'name' :
			$res='FIELD_FLASH';
		break;
	
	}
	return ($res ? $res : $field_value);
}

//Документ из рубрики
function get_field_docfromrub($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_DB,$AVE_Template, $AVE_Core, $AVE_Document;
	$res=0;
	switch ($type)
	{
	case 'edit' :
	    $field  = '<a name="' . $field_id . '"></a>';
	    $sql="SELECT Id, document_parent, document_title from ". PREFIX ."_documents WHERE rubric_id='".$dropdown."' order by document_parent, document_title";
	    $res=$AVE_DB->Query($sql);
	    $field = "<select name=\"feld[" . $field_id . "]\">";
	    $array=array();
	    $items=array();
		if($res){
			while($row = $res->FetchRow()){
				$row->document_title=(!empty($array[$row->document_parent]) ? $array[$row->document_parent].' > '.$row->document_title: $row->document_title);
				$items[$row->document_title]="<option value=\"" . htmlspecialchars($row->Id, ENT_QUOTES) . "\"" . ((trim($field_value) == trim($row->Id)) ? " selected=\"selected\"" : "") . ">" . htmlspecialchars($row->document_title, ENT_QUOTES) . "</option>";
				$array[$row->Id]=$row->document_title;
			}
		}
	    ksort($items);
	    $field.= implode(chr(10),$items);

	    $field .= "</select>";

	    $res=$field;
	break;

	case 'doc' :
		$sql="SELECT document_title from ". PREFIX ."_documents WHERE Id='".$field_value."' LIMIT 1";
		$field_value=$AVE_DB->Query($sql)->GetCell();
		$field_value = htmlspecialchars($field_value, ENT_QUOTES);
		$field_value = pretty_chars($field_value);
		$field_value = clean_php($field_value);
		$field_value = str_replace('"', '&quot;', $field_value);
		if (!$tpl_field_empty)
		{
			$field_param = explode('|', $field_value);
			$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
		}
		$res=$field_value;
	break;

	case 'req' :
		$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
	break;

	case 'name' :
		$res='FIELD_DOCFROMRUB';
	break;
	}
	return ($res ? $res : $field_value);
}


//Документ из рубрики(CHECKBOX)
function get_field_docfromrubcheck($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_DB,$AVE_Template, $AVE_Core, $AVE_Document;

	$res=0;
	switch ($type)
	{
		case 'edit' :
			$array=array();
			$items=array();
			$sql="SELECT Id, document_parent, document_title from ". PREFIX ."_documents WHERE rubric_id='".$dropdown."' order by document_parent, document_title";
			$field_value1=explode(',',$field_value);
			$res=$AVE_DB->Query($sql);
				//$field = "<input id=\"feld_" . $field_id . "\" name=\"feld[" . $field_id . "]\" value=\"".$field_value."\" type=\"hidden\">";
				$field = "<input id=\"feld_" . $field_id . "\" name=\"feld[" . $field_id . "]\" value=\"".$field_value."\" type=\"hidden\">";
				while($row = $res->FetchRow()){
					$row->document_title=($array[$row->document_parent] >'' ? $array[$row->document_parent].' > '.$row->document_title: $row->document_title);
					$items[$row->document_title]="<div class=\"fix\"><input class=\"float field_docfromrubcheck\" value='".$row->Id."' type='checkbox' ".((in_array($row->Id, $field_value1)==false) ? "" : "checked=checked").
					" onchange=\"
					$('#feld_" . $field_id ."').val('');
					var n = $('.field_docfromrubcheck:checked').each(
					function() {
					$('#feld_" . $field_id . "').val($('#feld_" . $field_id . "').val() > '' ?  $('#feld_" . $field_id . "').val()+',' + $(this).val() : $(this).val())
					}
					);
					\"><label>".htmlspecialchars($row->document_title, ENT_QUOTES)."</label></div>";

					$array[$row->Id]=$row->document_title;
				}

		ksort($items);
		$field.= implode(chr(10),$items);

		$res=$field;
		break;

		case 'doc' :
			$field_value1=explode(',',$field_value);
			if(is_array($field_value1)){
				$res=$AVE_DB->Query("SELECT Id,document_title FROM " . PREFIX . "_documents WHERE Id IN (".implode(', ',$field_value1).")");
				$result=Array();
				while ($mfa=$res->FetchArray())$result[$mfa['Id']]=$mfa['document_title'];
				$res='';
				if ($tpl_field_empty)$res.='<ul>';
				foreach($field_value1 as $k=>$v){
					$field_value = htmlspecialchars($v, ENT_QUOTES);
					$field_value = pretty_chars($field_value);
					$field_value = clean_php($field_value);
					if (!$tpl_field_empty)
					{
						$field_param = explode('|', $field_value);
						$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
					}
					else
					{
					  $field_value="<li>".$result[$field_value]."</li>";
					}
					$res.=$field_value;
				}
				if ($tpl_field_empty)$res.='</ul>';
			}
			break;

			case 'req' :
			$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);

			break;
		case 'name' :
			$res='FIELD_DOCFROMRUB_CHECK';
		break;
	}	return ($res ? $res : $field_value);

}

//Дата
function get_field_data($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document;
	$res=0;
	switch ($type)
	{
		case 'edit' :
			$field_value = ($field_value != 0) ? $field_value : '';
			$field = "
			<script type=\"text/javascript\">
			$(document).ready(function(){
				$('#field" . $field_id . "').datetimepicker({
					dateFormat: \"dd-mm-yy\",
				});
			});
			</script>

			<input id=\"feld_" . $field_id . "\" name=\"feld[" . $field_id . "]\" value=\"".$field_value."\" type=\"hidden\">

			<input id=\"field" . $field_id . "\" type=\"text\" name=\"field[" . $field_id . "]\" value=\"" . strftime("%d-%m-%Y %H:%M", $field_value) . "\" style=\"width: 250px\" onchange=\"
					$('#feld_" . $field_id ."').val('');
					$('#feld_" . $field_id . "').val($('#field" . $field_id ."').datetimepicker('getDate')/1000);
			\">";
			$res=$field;
			break;

		case 'doc' :

			$field_value = clean_php($field_value);
			if (!$tpl_field_empty)
			{
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_value', $rubric_field_template);
			}else{
				$field_value = strftime("%d-%m-%Y %H:%M", $field_value);
			}

			$res=$field_value;
			break;

		case 'req' :

			$field_value = clean_php($field_value);
			if ($document_fields[$rubric_id]['tpl_req_empty'])
			{
				$field_value = strftime("%d-%m-%Y %H:%M", $field_value);
			}
			else
			{
                $field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_value', $document_fields[$rubric_id]['rubric_field_template_request']);
			}

			$res=$field_value;
			break;

		case 'name' :
			$res='FIELD_DATA';
		break;

	}
	return ($res ? $res : $field_value);
}


//Каскад изображений
function get_field_bild_multi($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document;
	$res=0;


	switch ($type)
	{
		case 'edit' :
	switch ($_SESSION['use_editor']) {
		case '0': // FCKEditor
		$img_js =
			"'<img class=\"image_field\" id=\"_img_feld__'+field_id+'_'+_id+'\" src=\"'+img_path+'\" alt=\"'+alt+'\" border=\"0\" />' +
			'<div style=\"display: none\" id=\"span_feld__'+field_id+ '_'+_id+'\"></div>' + (field_value ? '<br />' : '') +
			'<input type=\"text\" style=\"width:50%;\" name=\"feld[' + field_id + '][]\" value=\"' + field_value + '\" id=\"img_feld__' + field_id +'_' + _id+'\" />&nbsp;'+
			'<input value=\"".$AVE_Template->get_config_vars('MAIN_OPEN_MEDIAPATH')."\" class=\"basicBtn\" type=\"button\" onclick=\"'+\"cp_imagepop('img_feld__\" + field_id + '_'+_id+\"', '', '', '0');\"+'\" />&nbsp;'";
		break;

		case '1': // Elrte и Elfinder
		$img_js =
			"'<div id=\"images_feld_'+field_id+'_'+_id+'\">' +
				'<img class=\"image_field\" id=\"_img_feld__'+field_id+'_'+_id+'\" src=\"'+img_path+'\" alt=\"'+alt+'\" border=\"0\" />' +
			'</div>' +
			'<input class=\"docm finder\" type=\"text\" style=\"width:70%;\" name=\"feld[' + field_id + '][]\" value=\"' + field_value + '\" id=\"img_feld__' + field_id +'_' + _id+'\" />&nbsp;'+
			'<input type=\"button\" class=\"basicBtn\" onClick=\"dialog_images($(this))\" rel=\"'+ field_id + '_'+_id+'\" value=\"".$AVE_Template->get_config_vars('MAIN_OPEN_MEDIAPATH')."\">&nbsp;'";
		break;

		case '2': // LiveEditor
		$img_js =
			"'<img class=\"image_field\" id=\"_img_feld__'+field_id+'_'+_id+'\" src=\"'+img_path+'\" alt=\"'+alt+'\" border=\"0\" />' +
			'<div style=\"display: none\" id=\"span_feld__'+field_id+ '_'+_id+'\"></div>' + (field_value ? '<br />' : '') +
			'<input type=\"text\" style=\"width:50%;\" name=\"feld[' + field_id + '][]\" value=\"' + field_value + '\" id=\"img_feld__' + field_id +'_' + _id+'\" />&nbsp;'+
			'<input value=\"".$AVE_Template->get_config_vars('MAIN_OPEN_MEDIAPATH')."\" class=\"basicBtn\" type=\"button\" onclick=\"'+\"cp_imagepop('img_feld__\" + field_id + '_'+_id+\"', '', '', '0');\"+'\" />&nbsp;'";
		break;

		case '3': // CKEditor
		$img_js =
			"'<img class=\"image_field\" id=\"_img_feld__'+field_id+'_'+_id+'\" src=\"'+img_path+'\" alt=\"'+alt+'\" border=\"0\" />' +
			'<div style=\"display: none\" id=\"span_feld__'+field_id+ '_'+_id+'\"></div>' + (field_value ? '<br />' : '') +
			'<input type=\"text\" style=\"width:50%;\" name=\"feld[' + field_id + '][]\" value=\"' + field_value + '\" id=\"img_feld__' + field_id +'_' + _id+'\" />&nbsp;'+
			'<input value=\"".$AVE_Template->get_config_vars('MAIN_OPEN_MEDIAPATH')."\" class=\"basicBtn\" type=\"button\" onclick=\"'+\"cp_imagepop('img_feld__\" + field_id + '_'+_id+\"', '', '', '0');\"+'\" />&nbsp;'";
		break;
    }
	$theme_folder =  '/admin/templates/'.DEFAULT_ADMIN_THEME_FOLDER;
	if(empty($AVE_Document->_field_width))$AVE_Document->_field_width=0;
	$jsCode = <<<BLOCK
		<script language="javascript" type="text/javascript">
			function dialog_images (elem_this){
				var id = elem_this.attr("rel");
				$('<div/>').dialogelfinder({
					url : ave_path+'admin/redactor/elfinder/php/connector.php',
					lang : 'ru',
					width : 1100,
					height: 600,
					modal : true,
					title : 'Файловый менеджер',
					getFileCallback : function(files, fm) {
						$("#img_feld__"+id).val(files['url'].slice(1));
						$("#images_feld_"+id).html('<img src='+files['url']+'>');
					},
					commandsOptions : {
						getfile : {
							oncomplete : 'destroy',
							folders : false
						}
					}
				})
			};
			function field_image_multi_add(field_id, field_value, img_path, alt){
				var
					_id = Math.round(Math.random()*1000);
					img_id = '__img_feld__' + field_id + '_' + _id;

				var html={$img_js}+
					'<input type="button" class="blackBtn" value="&#8593;" onclick="field_image_multi_move(' + field_id + ', ' + _id + ', \'up\')" />&nbsp;'+
					'<input type="button" class="blackBtn" value="&#8595;" onclick="field_image_multi_move(' + field_id + ', ' + _id + ', \'down\')" />&nbsp;'+
					'<input type="button" class="blackBtn" value="&#215;" onclick="field_image_multi_delete(' + field_id + ', ' + _id + ')" />'

					element=document.createElement("div");
					element.id=img_id;
					element.innerHTML=html;
					document.getElementById("feld_"+field_id).appendChild(element);
			}

			function field_image_multi_delete(field_id, id){
				jConfirm(
					delCascadConfirm,
					delCascadTitle,
						function(b){
							if (b){
								img_id = '__img_feld__' + field_id + '_' + id;
								element=document.getElementById(img_id);
								element.parentNode.removeChild(element);
		            		}
						}
				);
			}

			function field_image_multi_move(field_id, id, direction){ // direction: {up, down};
				img_id = '__img_feld__' + field_id + '_' + id;
				element=document.getElementById(img_id);

				if(direction=='up')
					neighbour=element.previousSibling;
				else
					neighbour=element.nextSibling;

				if(neighbour){
					if(direction=='up')
						neighbour.parentNode.insertBefore(element.parentNode.removeChild(element), neighbour);
					else{
						if( neighbour.nextSibling )
							neighbour.parentNode.insertBefore(element.parentNode.removeChild(element), neighbour.nextSibling);
						else
							neighbour.parentNode.appendChild(element.parentNode.removeChild(element));
					}
				}
			}

			function field_image_multi_opimport(field_id){
				$("#on"+field_id).hide();
				var html='<br>Указывать нужно папку (Формат: uploads/images/samepath/)<br><input type="text" style="width:{$AVE_Document->_field_width}" value="uploads/images/" id="img_importfeld__' + field_id +'" />&nbsp;'+
					'<input type="button" class="basicBtn topDir" value="..." onclick="browse_uploads(&quot;img_importfeld__' + field_id +'&quot;);" />&nbsp;'+
					'<input type="button" class="basicBtn" onclick="field_image_multi_import(' + field_id + ');" value="Импорт" />';
					element=document.createElement("div");
					element.id=img_id;
					element.innerHTML=html;
					document.getElementById("feld_"+field_id).appendChild(element);
			}

			function field_image_multi_import(field_id){

				var path_import = $("#img_importfeld__"+field_id).val();
				var html = '';

				$.ajax({
					url: ave_path+'admin/index.php?do=docs&action=image_import&ajax=run',
					data: {"path": path_import},
					dataType: "json",
					success: function(data) {
						$.alerts._overlay('hide');
						for (var p = 0, max = data.respons.length; p < max; p++) {
							var field_value = path_import + data.respons[p];
							var img_path = '../index.php?thumb=' + field_value+'&mode=t';
							field_image_multi_add(field_id, field_value, img_path, '');
						}
					}
				});
			}
		</script>
BLOCK;

	static $jsCodeWritten; // статическая переменная, показывающая, были ли уже выведен JS для редактирования поля multi image
			$field='';
			// выводим JS-код, только один раз
			if($jsCodeWritten!==1){
				$field.=$jsCode;
				$jsCodeWritten=1;
			}

			$field.="
				<div id=\"feld_{$field_id}\"></div><br />
				<input type='button' class='basicBtn' onclick=\"field_image_multi_add({$field_id},'','','');\" value='Добавить' /> <input type='button' class='basicBtn' id='on".$field_id."' onclick=\"field_image_multi_opimport({$field_id});\" value='Импорт' />
				<script language=\"javascript\" type=\"text/javascript\">";
				$massa=unserialize($field_value);
				if($massa!=false){
					foreach($massa as $k=>$v){
						$massiv = explode('|', $v);
						if($v){
							$field.="
							field_image_multi_add(
								'{$field_id}',
								'" . htmlspecialchars($v, ENT_QUOTES) . "',
								'" . (!empty($v) ? '../index.php?thumb=' . htmlspecialchars($massiv[0], ENT_QUOTES) : $img_pixel) . "&mode=t',
								'" . (isset($massiv[1]) ? htmlspecialchars($massiv[1], ENT_QUOTES) : '') . "');";
						}
					}
				}
				else{
					$field.="field_image_multi_add({$field_id},'','','');";
				}
				$field.="</script>";
				$res=$field;
			break;

		case 'doc' :
			$massa=unserialize($field_value);
			$res='';
			if($massa!=false)
				foreach($massa as $k=>$v)
				{
					$v = clean_php($v);
					$field_param = explode('|', $v);
					if($v){
						if ($tpl_field_empty)
						{
							$v = '<img src="'.ABS_PATH.$field_param[0].'" alt="'.$field_param[1].'"/>';
						}
						else
						{
							$v = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
							$v = preg_replace_callback('/\[tag:([r|c|f|t]\d+x\d+r*):(.+?)]/', 'callback_make_thumbnail', $v);
						}
					}
					$res.=$v;
				}
			break;

		case 'req' :
			$massa=unserialize($field_value);
			$res='';
			$rubric_field_template_request = $document_fields[$rubric_id]['rubric_field_template_request'];
			if($massa!=false)
				foreach($massa as $k=>$v)
				{
					$v = clean_php($v);
					$field_param = explode('|', $v);
					if($v){
						if (!$rubric_field_template_request)
						{
							$v = '<img src="'.ABS_PATH.@$field_param[0].'" alt="'.@$field_param[1].'"/>';
						}
						else
						{
							$v = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template_request);
							$v = preg_replace_callback('/\[tag:([r|c|f]\d+x\d+r*):(.+?)]/', 'callback_make_thumbnail', $v);
						}
					}
					$res.=$v;
				}
			break;

		case 'name' :
			$res='FIELD_BILD_MULTI';
		break;
	}
	return $res;
}

//Код
function get_field_code($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document;
	$res=0;
	switch ($type)
	{
		case 'edit' :
				$field  = "<a name=\"" . $field_id . "\"></a>";

				$field  .= "
	<link rel=\"stylesheet\" href=\"". ABS_PATH ."admin/codemirror/lib/codemirror.css\">

	<script src=\"". ABS_PATH ."admin/codemirror/lib/codemirror.js\" type=\"text/javascript\"></script>
    <script src=\"". ABS_PATH ."admin/codemirror/mode/xml/xml.js\"></script>
    <script src=\"". ABS_PATH ."admin/codemirror/mode/javascript/javascript.js\"></script>
    <script src=\"". ABS_PATH ."admin/codemirror/mode/css/css.js\"></script>
    <script src=\"". ABS_PATH ."admin/codemirror/mode/clike/clike.js\"></script>
    <script src=\"". ABS_PATH ."admin/codemirror/mode/php/php.js\"></script>

    <style type=\"text/css\">
      .activeline {background: #e8f2ff !important;}
    </style>
";

				$field .= "<textarea id=\"feld_" . $field_id . "\" style=\"width:" . $AVE_Document->_textarea_width . "; height:" . $AVE_Document->_textarea_height . "\"  name=\"feld[" . $field_id . "]\">" . $field_value . "</textarea>";

$field .= '
<script type="text/javascript">
      var editor = CodeMirror.fromTextArea(document.getElementById("feld_' . $field_id . '"), {
        lineNumbers: true,
		lineWrapping: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){editor.save();},
		onCursorActivity: function() {
		  editor.setLineClass(hlLine, null, null);
		  hlLine = editor.setLineClass(editor.getCursor().line, null, "activeline");
		}
      });

      function getSelectedRange() {
        return { from: editor.getCursor(true), to: editor.getCursor(false) };
      }

      function textSelection(startTag,endTag) {
        var range = getSelectedRange();
        editor.replaceRange(startTag + editor.getRange(range.from, range.to) + endTag, range.from, range.to)
        editor.setCursor(range.from.line, range.from.ch + startTag.length);
      }

	  var hlLine = editor.setLineClass(0, "activeline");
</script>
';

				$res=$field;
			break;

		case 'doc' :
			$res=$field_value;
			break;
			case 'req' :
				$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
			break;

		case 'name' :
			$res='FIELD_CODE';
		break;
	}
	return ($res ? $res : $field_value);
}

//Загрузить файл
function get_field_download($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document,$img_pixel;
	$res=0;
	switch ($type)
	{
		case 'edit' :
				$field  = "<div style=\"\" id=\"feld_" . $field_id . "\"><a name=\"" . $field_id . "\"></a>";
				$field .= "<div style=\"display:none\" id=\"feld_" . $field_id . "\">";
				$field .= "<img style=\"display:none\" id=\"_img_feld__" . $field_id . "\" src=\"" . (!empty($field_value) ? htmlspecialchars($field_value, ENT_QUOTES) : $img_pixel) . "\" alt=\"\" border=\"0\" /></div>";
				$field .= "<div style=\"display:none\" id=\"span_feld__" . $field_id . "\"></div>";
				$field .= "<input type=\"text\" style=\"width:" . $AVE_Document->_field_width . "\" name=\"feld[" . $field_id . "]\" value=\"" . htmlspecialchars($field_value, ENT_QUOTES) . "\" id=\"img_feld__" . $field_id . "\" />&nbsp;";
				$field .= "<input value=\"" . $AVE_Template->get_config_vars('MAIN_OPEN_MEDIAPATH') . "\" class=\"basicBtn\" type=\"button\" onclick=\"cp_imagepop('img_feld__" . $field_id . "', '', '', '0');\" />";
				$field .= '<a class="basicBtn topDir" title="'.$AVE_Template->get_config_vars('DOC_FILE_TYPE_HELP').'" href="#">?</a>';
				$field .= '</div>';
				$res=$field;
			break;

		case 'doc' :
			$field_value = clean_php($field_value);
			$field_param = explode('|', $field_value);
			if ($tpl_field_empty)
			{
				$field_value = (!empty($field_param[1]) ? $field_param[1] . '<br />' : '')
					. '<form method="get" target="_blank" action="' . ABS_PATH . $field_param[0]
					. '"><input class="basicBtn" type="submit" value="Скачать" /></form>';
			}
			else
			{
				$field_value = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $rubric_field_template);
			}
			$res=$field_value;
			break;
			case 'req' :
				$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
			break;
		case 'name' :
			$res='FIELD_DOWNLOAD';
		break;

	}
	return ($res ? $res : $field_value);
}

//GPS координаты Yandex
function get_field_gps_yandex($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	$res=0;
	switch ($type)
	{
		case 'edit' :
			$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength).'<input type="button" class="basicBtn" value="+" onclick="SetPlaceMarkCoords();return false;"/>&nbsp;<input type="button" class="basicBtn" value="X" onclick="ErasePlaceMarkCoords();return false;"/>';
			$code='<script src="http://api-maps.yandex.ru/1.1/index.xml?key='.YANDEX_MAP_API_KEY.'" type="text/javascript"></script>
    <script type="text/javascript">
        var map, geoResult, placemark;

        // Создание обработчика для события window.onLoad
        YMaps.jQuery(function () {
            // Создание экземпляра карты и его привязка к созданному контейнеру
            map = new YMaps.Map(YMaps.jQuery("#Map")[0]);

            // Установка для карты ее центра и масштаба
			if("<#--FIELD_VALUE--#>">""){var coord=new YMaps.GeoPoint(<#--FIELD_VALUE--#>);}else{var coord=new YMaps.GeoPoint(49.38,53.52);}
            map.setCenter(coord, 13);

            // Добавление элементов управления
            map.addControl(new YMaps.TypeControl());
			placemark = new YMaps.Placemark(coord, {draggable: true});
            placemark.name = "Результат";
            if("<#--FIELD_VALUE--#>">"")map.addOverlay(placemark);

            // При щелчке на карте показывается балун со значениями координат указателя мыши и масштаба
            YMaps.Events.observe(placemark, placemark.Events.DragEnd, function (obj) {
                // Задаем контент для балуна
				document.getElementById("feld_<#--FIELD_ID--#>").value=placemark.getGeoPoint();
                obj.update();
            });
        });

        // Функция для отображения результата геокодирования
        // Параметр value - адрес объекта для поиска
        function showAddress (value) {
            // Удаление предыдущего результата поиска
            map.removeOverlay(geoResult);

            // Запуск процесса геокодирования
            var geocoder = new YMaps.Geocoder(value, {results: 1, boundedBy: map.getBounds()});

            // Создание обработчика для успешного завершения геокодирования
            YMaps.Events.observe(geocoder, geocoder.Events.Load, function () {
                // Если объект был найден, то добавляем его на карту
                // и центрируем карту по области обзора найденного объекта
                if (this.length()) {
                    geoResult = this.get(0);
                    //map.addOverlay(geoResult);
                    map.setBounds(geoResult.getBounds());
                }else {
                    alert("Ничего не найдено")
                }
            });

            // Процесс геокодирования завершен неудачно
            YMaps.Events.observe(geocoder, geocoder.Events.Fault, function (geocoder, error) {
                alert("Произошла ошибка: " + error);
            })
        }
		function SetPlaceMarkCoords(){
			map.addOverlay(placemark);
			placemark.setGeoPoint(map.getCenter());
			document.getElementById("feld_<#--FIELD_ID--#>").value=placemark.getGeoPoint();
		}
		function ErasePlaceMarkCoords(){
			map.removeOverlay(placemark);
			document.getElementById("feld_<#--FIELD_ID--#>").value=\'\';
		}
    </script>';

            $code.='<p>
			<input type="text" id="address" style="width:525px;" value="" />
            <input class="basicBtn" type="button" value="Искать" onclick="showAddress(document.getElementById(\'address\').value);return false;"/>
            <div id="Map" style="width:600px;height:400px"></div>';
			$res.=str_ireplace('<#--FIELD_ID--#>',$field_id,str_ireplace('<#--FIELD_VALUE--#>',$field_value,$code));
			break;
		case 'doc' :
			$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength);
			break;

		case 'req' :
				$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
			break;
		case 'name' :
			$res='FIELD_GPS_YANDEX';
		break;
	}
	return ($res ? $res : $field_value);
}

//GPS координаты Google
function get_field_gps_google($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	$res=0;
	switch ($type)
	{
		case 'edit' :
			$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength).'<input type="button" class="basicBtn" value="+" onclick="SetPlaceMarkCoords();return false;"/>&nbsp;<input type="button" class="basicBtn" value="X" onclick="ErasePlaceMarkCoords();return false;"/>';
			$code='<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var geocoder = new google.maps.Geocoder();
var map=null;
var marker=null;

function updateMarkerPosition(latLng) {
  marker.setTitle([latLng].join(", "));
  document.getElementById("feld_<#--FIELD_ID--#>").value = [
    latLng.lat(),
    latLng.lng()
  ].join(", ");
}

function initialize() {
  if("<#--FIELD_VALUE--#>">""){
	var latlng = new google.maps.LatLng(<#--FIELD_VALUE--#>);
  }
  else
  {
    var latlng = new google.maps.LatLng(15.870, 100.992);
  }
  map = new google.maps.Map(document.getElementById("Map"), {
    zoom: 5,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  });
  if("<#--FIELD_VALUE--#>">""){
		SetPlaceMarkCoords();
	}
}

  function showAddress(address) {
    geocoder.geocode({
      "address": address,
      "partialmatch": true}, geocodeResult);
  }

  function geocodeResult(results, status) {
    if (status == "OK" && results.length > 0) {
      map.fitBounds(results[0].geometry.viewport);
    }
  }

function parseLatLng(value) {
  value.replace("/\s//g");
  var coords = value.split(",");
  var lat = parseFloat(coords[0]);
  var lng = parseFloat(coords[1]);
  if (isNaN(lat) || isNaN(lng)) {
    return null;
  } else {
    return new google.maps.LatLng(lat, lng);
  }
}


function ErasePlaceMarkCoords(){
   marker.setMap(null);
}
function SetPlaceMarkCoords(){
  if(marker==null){marker = new google.maps.Marker({
    position: map.getCenter(),
    title: "",
    map: map,
    draggable: true
  });}else {
	marker.setMap(map);
	marker.setPosition(map.getCenter());
	}

  // Update current position info.
  updateMarkerPosition(map.getCenter());

  // Add dragging event listeners.

  google.maps.event.addListener(marker, "drag", function() {
    updateMarkerPosition(marker.getPosition());
  });

  google.maps.event.addListener(marker, "dragend", function() {
    updateMarkerPosition(marker.getPosition());
  });
}
// Onload handler to fire off the app.
google.maps.event.addDomListener(window, "load", initialize);



</script>
';
            $code.='<p>
			<input type="text" id="address" style="width:525px;" value="" />
            <input class="basicBtn" type="button" value="Искать" onclick="showAddress(document.getElementById(\'address\').value);return false;"/>
            <div id="Map" style="width:600px;height:400px"></div>';
			$res.=str_ireplace('<#--FIELD_ID--#>',$field_id,str_ireplace('<#--FIELD_VALUE--#>',$field_value,$code));
			break;
		case 'doc' :
			$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength);
			break;

		case 'req' :
				$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
			break;
		case 'name' :
			$res='FIELD_GPS_GOOGLE';
		break;
	}
	return ($res ? $res : $field_value);
}

//Пользовательские поля
if(file_exists(BASE_DIR . '/functions/user.fields.php'))
	require(BASE_DIR . '/functions/user.fields.php');

function get_field_type()
{
	global $AVE_Template;
	static $felder;
	if(is_array($felder))return $felder;
	$arr = get_defined_functions();

	$AVE_Template->config_load(BASE_DIR . '/admin/lang/' . $_SESSION['admin_language'] . '/fields.txt', 'fields');
	$felder_vars = $AVE_Template->get_config_vars();
	$felder=Array();
	foreach($arr['user'] as $k=>$v)
	{
		if(trim(substr($v,0,strlen('get_field_')))=='get_field_')
		{
			$d='';
			$name=@$v('','name','','',0,$d);
			$id=substr($v,strlen ('get_field_'));
			if($name!=false && is_string($name))$felder[]=array('id' => $id,'name' => (isset($felder_vars[$name]) ? $felder_vars[$name] : $name));
		}	
	}
/*	$felder = array(
	);
*/
	return $felder;
}

?>