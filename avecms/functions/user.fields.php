<?php
//Видео в формате MOV
function get_field_video_mov($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	global $AVE_Template, $AVE_Core, $AVE_Document,$img_pixel;
	$res=0;
	switch ($type)
	{
		case 'edit' :
				$field  = "<div style=\"\" id=\"feld_" . $field_id . "\"><a name=\"" . $field_id . "\"></a>";
				$field .= "<div style=\"display:none\" id=\"feld_" . $field_id . "\"><img style=\"display:none\" id=\"_img_feld__" . $field_id . "\" src=\"". (!empty($field_value) ? htmlspecialchars($field_value, ENT_QUOTES) : $img_pixel) . "\" alt=\"\" border=\"0\" /></div>";
				$field .= "<div style=\"display:none\" id=\"span_feld__" . $field_id . "\"></div>";
				$field .= "<input type=\"text\" style=\"width:" . $AVE_Document->_field_width . "\" name=\"feld[" . $field_id . "]\" value=\"" . htmlspecialchars($field_value, ENT_QUOTES) . "\" id=\"img_feld__" . $field_id . "\" />&nbsp;";
				$field .= "<input value=\"" . $AVE_Template->get_config_vars('MAIN_OPEN_MEDIAPATH') . "\" class=\"basicBtn\" type=\"button\" onclick=\"cp_imagepop('img_feld__" . $field_id . "', '', '', '0');\" />";
				$field .= '<a class="basicBtn" title="'.$AVE_Template->get_config_vars('DOC_VIDEO_TYPE_HELP').'" href="#">?</a>';
				$field .= '</div>';
				$res=$field;
			break;

		case 'doc' :
			$field_value = clean_php($field_value);
			$field_param = explode('|', $field_value);
			$field_param[1] = (!empty($field_param[1]) && is_numeric($field_param[1])) ? $field_param[1] : 470;
			$field_param[2] = (!empty($field_param[2]) && is_numeric($field_param[2])) ? $field_param[2] : 320;
			if ($tpl_field_empty)
			{
				$field_value = '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" width="' . $field_param[1]
						. '" height="' . $field_param[2] . '" codebase="http://www.apple.com/qtactivex/qtplugin.cab">'
					. '<param name="src" value="' . ABS_PATH . $field_param[0] . '">'
					. '<param name="autoplay" value="false">'
					. '<param name="controller" value="true">'
					. '<param name="target" value="myself">'
					. '<param name="type" value="video/quicktime">'
					. '<embed target="myself" src="' . ABS_PATH . $field_param[0] . '" width="' . $field_param[1] . '" height="' . $field_param[2]
						. '" autoplay="false" controller="true" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/">'
					. '</embed>'
					. '</object>';
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
			$res='FIELD_VIDEO_MOV';
		break;
	}
	return ($res ? $res : $field_value);
}

//Видео в формате AVI
function get_field_video_avi($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	$res=0;
	switch ($type)
	{
		case 'edit' :
			$res=get_field_video_mov($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength);
			break;
		case 'doc' :
			$field_value = clean_php($field_value);
			$field_param = explode('|', $field_value);
			$field_param[1] = (!empty($field_param[1]) && is_numeric($field_param[1])) ? $field_param[1] : 470;
			$field_param[2] = (!empty($field_param[2]) && is_numeric($field_param[2])) ? $field_param[2] : 320;
			if ($tpl_field_empty)
			{
				$field_value = '<object id="MediaPlayer" classid="CLSID:6BF52A52-394A-11d3-B153-00C04F79FAA6" '
					. 'codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,7,1112" height="'
						. $field_param[2] . '" width="' . $field_param[1] . '">'
					. '<param name="animationatStart" value="false">'
					. '<param name="autostart" value="false">'
					. '<param name="url" value="' . ABS_PATH . $field_param[0] . '">'
					. '<param name="volume" value="-200">'
					. '<embed type="application/x-mplayer2" pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" name="MediaPlayer" src="'
						. ABS_PATH . $field_param[0] . '" autostart="0" displaysize="0" showcontrols="1" showdisplay="0" showtracker="1" showstatusbar="1" height="'
						. $field_param[2] . '" width="' . $field_param[1] . '">'
					. '</object>';
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
			$res='FIELD_VIDEO_AVI';
		break;

	}
	return ($res ? $res : $field_value);
}

//Видео в формате WMF
function get_field_video_wmf($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	$res=0;
	switch ($type)
	{
		case 'edit' :
			$res=get_field_video_mov($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength);
			break;
		case 'doc' :
			$res=get_field_video_avi($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength);
			break;

		case 'req' :
				$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
			break;
		case 'name' :
			$res='FIELD_VIDEO_WMF';
		break;
	}

	return ($res ? $res : $field_value);
}

//Видео в формате WMV
function get_field_video_wmv($field_value,$type,$field_id='',$rubric_field_template='',$tpl_field_empty=0,&$maxlength = '',$document_fields=0,$rubric_id=0,$dropdown=''){
	$res=0;
	switch ($type)
	{
		case 'edit' :
			$res=get_field_video_mov($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength);
			break;
		case 'doc' :
			$res=get_field_video_avi($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength);
			break;

		case 'req' :
				$res=get_field_default($field_value,$type,$field_id,$rubric_field_template,$tpl_field_empty,$maxlength,$document_fields,$rubric_id);
			break;
		case 'name' :
			$res='FIELD_VIDEO_WMV';
		break;
	}
	return ($res ? $res : $field_value);
}
?>