// В файле /admin/editor/fckconfig.js указать путь к этому файлу настроек
// FCKConfig.CustomConfigurationsPath = '/admin/editor/aveconfig.js' ;

FCKConfig.SkinPath = FCKConfig.BasePath + 'skins/avenew/' ;

FCKConfig.ProtectedSource.Add( /<\?[\s\S]*?\?>/g ) ; // PHP style server side code

FCKConfig.AutoDetectLanguage = false ;
FCKConfig.DefaultLanguage    = 'ru' ;
FCKConfig.FillEmptyBlocks    = false ;

FCKConfig.ToolbarSets["cpengine"] = [
  ['Source','-','Save','Preview'],
  ['Cut','Copy','Paste','PasteText','PasteWord'],
  ['Undo','Redo'],['Bold','Italic','Underline','StrikeThrough'],
  ['OrderedList','UnorderedList','-','Outdent','Indent','Blockquote','CreateDiv'],
  ['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],['Link','Unlink','Anchor'], ['Image','Flash','Table','Rule','SpecialChar'], '/',
  ['FontFormat','FontName','FontSize'],['TextColor','BGColor','RemoveFormat'], ['FitWindow','ShowBlocks'], ['AnchorMore','PageBreak','linebreaks','typograf','SyntaxHighLight2','googlemaps','OnlineVideo','flvS3Player','Switchtoolbar']  // Не ставить запятую в последней строке.
] ;

FCKConfig.ToolbarSets["cpengine_small"] = [
  ['Source','-','Save'],
  ['Cut','Copy','Paste','PasteText','PasteWord'],
  ['Undo','Redo'],['Bold','Italic','Underline','StrikeThrough'],['OrderedList','UnorderedList'],
  ['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],['Link','Unlink','Anchor','Image'],['AnchorMore','PageBreak','typograf','SyntaxHighLight2','googlemaps','OnlineVideo','flvS3Player','Switchtoolbar']  // Не ставить запятую в последней строке.
] ;

FCKConfig.ToolbarSets["Simple"] = [
  ['Source','Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','-','Image','-','RemoveFormat','-','Preview'],
  ['FontFormat','FontName','FontSize','AnchorMore','PageBreak'],
  ['typograf','SyntaxHighLight2','googlemaps','OnlineVideo','flvS3Player','Switchtoolbar']
] ;

FCKConfig.SwitchtoolbarCycle = ['cpengine','cpengine_small','Simple'];


FCKConfig.EnterMode = 'br' ;       // p | div | br
FCKConfig.ShiftEnterMode = 'p' ;  // p | div | br

FCKConfig.FontFormats = 'div;p;h1;h2;h3;h4;h5;h6;pre;address' ;

FCKConfig.LinkBrowserURL    = "../../../../admin/browser.php?typ=bild&mode=fck&target=link" ;
FCKConfig.LinkBrowserLnkUrl = "../../../../admin/browser.php?typ=bild&mode=fck&target=link_image" ;
FCKConfig.ImageBrowserURL   = "../../../../admin/browser.php?typ=bild&mode=fck&target=txtUrl" ;
FCKConfig.FlashBrowserURL   = "../../../../admin/browser.php?typ=bild&mode=fck&target=txtUrl";
FCKConfig.ImagesBrowserURL  = "../../../../admin/browser.php?typ=bild&mode=fck&target=txtImgURL";
FCKConfig.StyleBrowserURL   = "../../../../admin/browser.php?typ=bild&mode=fck&target=txtExternalStyleURL";
FCKConfig.CommentBrowserURL   = "../../../../admin/browser.php?typ=bild&mode=fck&target=txtComURL";
FCKConfig.PlayBrowserURL   = "../../../../admin/browser.php?typ=bild&mode=fck&target=txtPlayURL";

FCKConfig.LinkUpload  = false ;
FCKConfig.ImageUpload = false ;
FCKConfig.FlashUpload = false ;
FCKConfig.ImagesUpload = false ;
FCKConfig.StyleUpload = false ;
FCKConfig.CommentUpload = false ;
FCKConfig.PlayUpload = false ;

FCKConfig.EMailProtection = 'encode' ; // none | encode | function

FCKConfig.SpellChecker	= 'SCAYT' ; // 'WSC' | 'SCAYT' | 'SpellerPages' | 'ieSpell'
FCKConfig.FirefoxSpellChecker = true ;

FCKConfig.MaxUndoLevels = 25 ;

FCKConfig.LinkDlgHideAdvanced = true ;

FCKConfig.BackgroundBlockerColor = '#000000' ;

//Подключаем Якорь More
//Для добавления кнопки в панель редактора испозуем 'AnchorMore'
FCKConfig.Plugins.Add('anchormore');

//Подключаем Google Maps
//Для добавления кнопки в панель редактора испозуем 'googlemaps'
FCKConfig.Plugins.Add('googlemaps', 'ru,en,de,es,fr,it,nl,no,zh');
// The most important part is your GoogleMaps key. It must be set properly for the plugin to work,
// or Google will refuse to serve the maps data.
// You must get one for each server where you want to use the plugin, just get the key for free here
// after agreeing to the Terms of Use of the GoogleMaps API: http://www.google.com/apis/maps/signup.html
// For example the key for "localhost" is
//	ABQIAAAAlXu5Pw6DFAUgqM2wQn01gxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxSy5hTGQdsosYD3dz6faZHVrO-02A
FCKConfig.GoogleMaps_Key = 'ABQIAAAAlXu5Pw6DFAUgqM2wQn01gxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxSy5hTGQdsosYD3dz6faZHVrO-02A';

//Подключаем SyntaxHighlight 2
//Для добавления кнопки в панель редактора используем 'SyntaxHighLight2'
FCKConfig.Plugins.Add( 'syntaxhighlight2', 'ru,en');

//Подключаем Typograf
//Для добавления кнопки в панель редактора используем 'typograf'
FCKConfig.Plugins.Add ('typograf', 'ru,en');

//Подключаем OnlineVideo
//Для добавления кнопки в панель редактора используем 'OnlineVideo'
FCKConfig.Plugins.Add('onlinevideo', 'ru,en');
//Подключаем OnlineVideo

//Для добавления кнопки в панель редактора используем 'flvS3Player'
FCKConfig.Plugins.Add( 'flvS3Player','ru,en');

//Переключатель панелей редактора 'Switchtoolbar'
FCKConfig.Plugins.Add('Switchtoolbar', 'ru,en,uk');

//Кнопка разрыва строки 'linebreaks'
FCKConfig.Plugins.Add( 'linebreaks', 'ru') ;