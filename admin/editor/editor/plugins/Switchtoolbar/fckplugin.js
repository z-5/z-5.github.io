// Create a command object
function FCKCommand_Switchtoolbar() {
    // This is it!
    this.SourceView = true;
    this.Execute = function() {
        var oldToolbar = FCKURLParams.Toolbar;
        // Get the current toolbar cycle index
        var idx = false;
        for (var i in FCK.Config.SwitchtoolbarCycle) {
            if (FCK.Config.SwitchtoolbarCycle[i] == oldToolbar) {
                idx = i;
            }
        }
        if (idx) {
            idx++;
        } else {
            idx = 0;
        }
        if (typeof(FCK.Config.SwitchtoolbarCycle[idx]) != 'undefined') {
            var newToolbar = FCK.Config.SwitchtoolbarCycle[idx];
        } else if (typeof(FCK.Config.SwitchtoolbarCycle[0]) != 'undefined') {
            var newToolbar = FCK.Config.SwitchtoolbarCycle[0];
        } else {
            var newToolbar = 'Default';
        }
        if (oldToolbar != newToolbar) {
            FCKURLParams.Toolbar = newToolbar;
            FCK.ToolbarSet.Load(FCKURLParams.Toolbar);
        }
    };
    this.GetState = function() {return 0;};
}

// Register the related command.
// RegisterCommand takes the following arguments: CommandName, DialogCommand
FCKCommands.RegisterCommand( 'Switchtoolbar', new FCKCommand_Switchtoolbar() ) ;

// Create the toolbar button.
// FCKToolbarButton takes the following arguments: CommandName, Button Caption
var oSwitchtoolbarItem = new FCKToolbarButton( 'Switchtoolbar', FCKLang.SwitchtoolbarBtn ) ;
oSwitchtoolbarItem.IconPath = FCKPlugins.Items['Switchtoolbar'].Path + 'Switchtoolbar.gif' ;
FCKToolbarItems.RegisterItem( 'Switchtoolbar', oSwitchtoolbarItem ) ;

//End code