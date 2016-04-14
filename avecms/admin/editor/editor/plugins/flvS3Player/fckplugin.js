// Register the related commands.
var dialogPath = FCKConfig.PluginsPath + 'flvS3Player/flvS3Player.html';
var flvS3PlayerDialogCmd = new FCKDialogCommand( FCKLang["DlgFLVS3PlayerTitle"], FCKLang["DlgFLVS3PlayerTitle"], dialogPath, 650, 460);
FCKCommands.RegisterCommand( 'flvS3Player', flvS3PlayerDialogCmd ) ;

// Create the Flash toolbar button.
var oFlvS3PlayerItem		= new FCKToolbarButton( 'flvS3Player', FCKLang["DlgFLVS3PlayerTitle"]) ;
oFlvS3PlayerItem.IconPath	= FCKPlugins.Items['flvS3Player'].Path + 'flvS3Player.gif' ;

FCKToolbarItems.RegisterItem( 'flvS3Player', oFlvS3PlayerItem ) ;			
// 'Flash' is the name used in the Toolbar config.

