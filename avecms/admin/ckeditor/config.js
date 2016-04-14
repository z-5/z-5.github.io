CKEDITOR.editorConfig = function( config ) {

config.protectedSource.push( /<\?[\s\S]*?\?>/g );   // PHP code
config.protectedSource.push( /<%[\s\S]*?%>/g );   // ASP code
config.protectedSource.push( /(]+>[\s|\S]*?<\/asp:[^\>]+>)|(]+\/>)/gi );   // ASP.Net code

config.language = 'ru';

config.emailProtection = 'mt(NAME,DOMAIN,SUBJECT,BODY)';

config.removePlugins = 'scayt,menubutton';

config.toolbarCanCollapse = true;
config.disableNativeSpellChecker = false;
config.scayt_autoStartup = false;

config.autoParagraph = false;
config.autoUpdateElement = true;

config.extraPlugins = 'jwplayer,spoiler,syntaxhighlight,mediaembed';

config.enterMode = CKEDITOR.ENTER_BR;
config.shiftEnterMode = CKEDITOR.ENTER_P;

config.autoGrow_minHeight = 300;

config.toolbar_Big = [
    { name: 'document',    items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
    { name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
    { name: 'editing',     items : [ 'Find','Replace','-','SelectAll' ] },
    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
    '/',
    { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
    { name: 'links',       items : [ 'Link','Unlink','Anchor' ] },
    { name: 'insert',      items : [ 'Image','Flash','jwplayer','MediaEmbed','Table','HorizontalRule','PageBreak','Spoiler','Code' ] },
    '/',
    { name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
    { name: 'colors',      items : [ 'TextColor','BGColor' ] },
    { name: 'tools',       items : [ 'ShowBlocks', 'Maximize' ] }
] ;

config.toolbar_Small = [
  ['Source','-','Save'],
  ['Cut','Copy','Paste','PasteText','PasteFromWord'],
  ['Undo','Redo'],
  ['Bold','Italic','Underline','Strike'],
  ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv','Table'],
  '/',
  ['Format','FontSize'],

  ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
  ['TextColor','BGColor','RemoveFormat'],

  ['Link','Unlink','Anchor'],
  ['Image','Flash','jwplayer'],
 
  ['Spoiler','PageBreak','SpecialChar'],
  ['ShowBlocks','Maximize']
] ;

config.toolbar_Verysmall = [
   ['Source','-','Save'],['Cut','Copy','Paste','PasteText','PasteWord'],['Undo','Redo'],['Bold','Italic','Underline','StrikeThrough'],['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],['Link','Unlink']
] ;

//config.filebrowserBrowseUrl = "../../../../admin/browser.php?typ=bild&mode=fck&target=link";
//config.filebrowserLinkBrowseUrl = "../../../../admin/browser.php?typ=bild&mode=fck&target=link";
//config.filebrowserImageBrowseUrl = "../../../../admin/browser.php?typ=bild&mode=fck&target=txtUrl";
//config.filebrowserFlashBrowseUrl = "../../../../admin/browser.php?typ=bild&mode=fck&target=txtUrl";
//config.filebrowserLinkUploadUrl = "../../../../admin/browser.php?typ=bild&mode=fck&target=link";
//config.filebrowserImageUploadUrl = "../../../../admin/browser.php?typ=bild&mode=fck&target=txtUrl";

config.filebrowserBrowseUrl = '../../../../admin/browser.php?typ=bild&mode=fck&target=txtUrl';
config.filebrowserImageBrowseUrl = '../../../../admin/browser.php?typ=bild&mode=fck&target=txtUrl';
config.filebrowserLinkBrowseUrl = '../../../../admin/index.php?do=docs&action=showsimple&selecturl=1&target=txtUrl&pop=1';

config.removeDialogTabs = 'link:upload;image:Upload';

config.skin = 'moono' ;

config.keystrokes =
[
    [ CKEDITOR.ALT + 121 /*F10*/, 'toolbarFocus' ],
    [ CKEDITOR.ALT + 122 /*F11*/, 'elementsPathFocus' ],

    [ CKEDITOR.SHIFT + 121 /*F10*/, 'contextMenu' ],

    [ CKEDITOR.CTRL + 90 /*Z*/, 'undo' ],
    [ CKEDITOR.CTRL + 89 /*Y*/, 'redo' ],
    [ CKEDITOR.CTRL + CKEDITOR.SHIFT + 90 /*Z*/, 'redo' ],

    [ CKEDITOR.CTRL + 76 /*L*/, 'link' ],

    [ CKEDITOR.CTRL + 66 /*B*/, 'bold' ],
    [ CKEDITOR.CTRL + 73 /*I*/, 'italic' ],
    [ CKEDITOR.CTRL + 85 /*U*/, 'underline' ],

    [ CKEDITOR.ALT + 109 /*-*/, 'toolbarCollapse' ]
];

};
