<?php
 $small = '
<script type="text/javascript">
var oEdit' . $field_id . ' = new InnovaEditor("oEdit' . $field_id . '");
oEdit' . $field_id . '.css = ["/admin/liveeditor/styles/default.css","/admin/liveeditor/scripts/common/bootstrap/css/bridge.css"]; 
oEdit' . $field_id . '.arrCustomButtons = [["Snippets", "modalDialog(\'/admin/liveeditor/scripts/common/bootstrap/snippets.htm\',900,658,\'Insert Snippets\');", "Bootstrap", "btnContentBlock.gif"]];
oEdit' . $field_id . '.returnKeyMode = 1;
oEdit' . $field_id . '.pasteTextOnCtrlV = true;
oEdit' . $field_id . '.enableFlickr = true;
oEdit' . $field_id . '.flickrUser = "ysw.insite";
oEdit' . $field_id . '.enableCssButtons = true;
oEdit' . $field_id . '.enableTableAutoformat = true;
oEdit' . $field_id . '.styleSelectorPrefix = "";
oEdit' . $field_id . '.disableFocusOnLoad = true;
oEdit' . $field_id . '.fileBrowser = "/admin/liveeditor/assetmanager/asset.php";
oEdit' . $field_id . '.width = "' . $AVE_Document->_textarea_width_small . '";
oEdit' . $field_id . '.height = "' . $AVE_Document->_textarea_height_small . '";
oEdit' . $field_id . '.groups = [["group1", "", [""]]];
oEdit' . $field_id . '.REPLACE("small-editor[' . $field_id . ']");
</script>
';
 $innova = array (2 =>"$small");
 ?>