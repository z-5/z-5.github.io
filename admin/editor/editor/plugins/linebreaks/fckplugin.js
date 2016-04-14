var My_FCKLBreaksCommand = function()
{

}

My_FCKLBreaksCommand.prototype.Execute = function()
{
    FCK.InsertHtml('<br style="clear:both;" />');
}

My_FCKLBreaksCommand.prototype.GetState = function()
{
    return FCK_TRISTATE_OFF; 
}

// Register the related command.
FCKCommands.RegisterCommand('linebreaks', new My_FCKLBreaksCommand());

// Create the LineBreaks toolbar button.
var LBreaksButton = new FCKToolbarButton("linebreaks", FCKLang.LBreaksButton);
LBreaksButton.IconPath = FCKPlugins.Items['linebreaks'].Path + 'linebreaks.gif';
FCKToolbarItems.RegisterItem('linebreaks', LBreaksButton);