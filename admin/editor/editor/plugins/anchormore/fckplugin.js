var InsertAnchorMoreCommand = function() {
};

InsertAnchorMoreCommand.GetState = function() {
	return FCK_TRISTATE_OFF;
}

InsertAnchorMoreCommand.Execute = function() {
	FCK.InsertHtml('<a name="more"></a>');
}

FCKCommands.RegisterCommand('AnchorMore', InsertAnchorMoreCommand);

var oAnchorMore = new FCKToolbarButton('AnchorMore', 'Якорь More');

oAnchorMore.IconPath = FCKConfig.PluginsPath + 'anchormore/anchormore.gif';

FCKToolbarItems.RegisterItem('AnchorMore', oAnchorMore);