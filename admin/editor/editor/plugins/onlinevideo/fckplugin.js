/*
* FCKEditor OnlineVideo Plugin - Plugin
*/

// Register the related commands.
FCKCommands.RegisterCommand('OnlineVideo', new FCKDialogCommand(FCKLang['DlgOnlineVideoTitle'], FCKLang['DlgOnlineVideoTitle'], FCKConfig.PluginsPath + 'onlinevideo/onlinevideo.html', 450, 350));

// Create the "OnlineVideo" toolbar button.
var oFindItem		= new FCKToolbarButton( 'OnlineVideo', FCKLang['OnlineVideoTip'] ) ;
oFindItem.IconPath	= FCKConfig.PluginsPath + 'onlinevideo/onlinevideo.gif' ;

FCKToolbarItems.RegisterItem( 'OnlineVideo', oFindItem ) ;			// 'OnlineVideo' is the name used in the Toolbar config.
