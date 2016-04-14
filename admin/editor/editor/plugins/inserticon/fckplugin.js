/* Plugin for FSKEditor ----- Insert Icon v1.0 -----
 * This plugin created by Aleksandr Salnikov
 * Nick Name: Repellent, team Overdoze.ru 
 * This file was created August 28, 2011
 */
FCKCommands.RegisterCommand( 'InsertIcon'		, new FCKDialogCommand( FCKLang['InsertIconTitle']	, FCKLang['InsertIconTitle']		, FCKConfig.PluginsPath + 'inserticon/inserticon.html'	, 630, 500 ) ) ;
var oInsertIcon		= new FCKToolbarButton( 'InsertIcon', FCKLang['ToolbarInsertIconTitle'] ) ;
oInsertIcon.IconPath	= FCKConfig.PluginsPath + 'inserticon/inserticon.gif' ;
FCKToolbarItems.RegisterItem( 'InsertIcon', oInsertIcon ) ;			
FCKConfig.InsertIconPath	    = '/uploads/insert_icons/' ;
FCKConfig.InsertIconPathPreview  = '/uploads/insert_icons/' ;
FCKConfig.InsertIconColumns = 10 ;
FCKConfig.InsertIconPathSize = ['size_48x48', 'size_32x32', 'size_24x24', 'size_16x16'];
 // Создаем массив названий файлов, находящихся в папке uploads/insert_icons/preview/   
FCKConfig.InsertIconImages  = [ '0','1','2','3','4','5','6','7','8','9','alarme','ampersand','application','applications','arrow1down','arrow1downleft','arrow1downright','arrow1left','arrow1right','arrow1up','arrow1upleft','arrow1upright','arrow2down','arrow2downleft','arrow2downright','arrow2left','arrow2right','arrow2up','arrow2upleft','arrow2upright','arrow3down','arrow3left','arrow3right','arrow3up','attach','audiomessage','back','backtop','bubble1','bubble3','burn','calc','calendar','cancel','car','card1','card2','card3','card4','cart','cart2','cd','clipboardcopy','clipboardcut','clipboardpaste','clock','computer','contact','copyright','cube','currencydollar','currencyeuro','currencypound','database','directiondiag1','directiondiag2','directionhorz','directions','directionvert','discuss','document','document2','documentnew','dots','dotsdown','dotsup','download','email','exclamation','fbook','flag','folder','folder2','folder3','footprint','forbidden','fullscreen','fullsize','game','gear','globe','goin','goout','graph','hand','hdd','hddnetwork','health','heart','home','home2','info','info2','ipod','key','light','link','lock','lockopen','loop','luggage','mail','man','microphone','minus','mobile','mouse','movie','music','music2','nuke','ok','paragraph','percent','phone','photo','picture','playereject','playerfastfwd','playerfastrev','playernext','playerpause','playerplay','playerprevious','playerrecord','playerstop','plus','podcast','pointer','poll','printer','puzzle','question','reducedsize','refresh','rss1','rss2','save','screen','search','security','sitemap','sizediag1','sizediag2','sizehorz','sizevert','sleep','smiley1','smiley2','smiley3','soundminus','soundoff','soundon','soundplus','standby','star','start','stats','stats2','stats3','table','tag','tape','target','textlarge','textmeduim','textminus','textplus','textsmall','thumbdown','thumbup','tool','tool2','trackback','trash','travel','tree','tv','user','video','wait','warning','weathercloud','weathercouldsun','weatherrain','weathersnow','weathersun','wizard','woman','wordpress','write','write2','write3','zoomin','zoomout','zzzaccept','zzzadd','zzzadd_to_shopping_cart', 
'zzzapple','zzzattachment','zzzback','zzzbooks','zzzbox','zzzbrush','zzzcalculator','zzzcalendar','zzzcamera','zzzcard','zzzchart','zzzchart_down','zzzchart_up', 
'zzzchess','zzzclock','zzzcloud_comment','zzzcoffee','zzzcomment','zzzcut','zzzdatabase','zzzdelete','zzzdollar_currency_sign','zzzdown','zzzdownload','zzzemail', 
'zzzempty_calendar','zzzeuro_currency_sign','zzzfavorite','zzzfind','zzzfolder','zzzfull_screen','zzzgames','zzzglobe','zzzgreen_flag','zzzheart','zzzhelp','zzzhome', 
'zzzid_card','zzzinfo','zzzinsert_to_shopping_cart','zzziphone','zzzipod','zzzkey','zzzkeyboard','zzzlaptop','zzzlight_bulb','zzzlock','zzzmagnet','zzzmail', 
'zzzmail_receive','zzzmail_search','zzzmail_send','zzzmap','zzzmap_blue','zzzmap_red','zzzmegaphone','zzzmicrophone','zzzmobile_phone','zzzmonitor','zzzmouse', 
'zzzmusic_note','zzznews','zzznext','zzzpage','zzzpencil','zzzpicture','zzzpie_chart','zzzprint','zzzprocess','zzzpromo_green','zzzpromo_orange','zzzpromo_red', 
'zzzpromo_turquoise','zzzpromo_violet','zzzpromotion','zzzpromotion_new','zzzprotection','zzzpuzzle','zzzred_flag','zzzrefresh','zzzremove', 
'zzzremove_from_shopping_cart','zzzrss','zzzsave','zzzschool_board','zzzsearch','zzzsecurity','zzzshopping_cart','zzzshopping_cart_accept','zzzshut_down','zzzsms', 
'zzzsound','zzzstar_empty','zzzstar_full','zzzstar_half_full','zzzsterling_pound_currency_sign','zzztag','zzztarget','zzztelephone','zzztelevision','zzztoolbox', 
'zzztools','zzztrash','zzzunlock','zzzup','zzzupload','zzzuser','zzzusers','zzzvideo','zzzvideo_camera','zzzwarning','zzzwebcam','zzzwired','zzzwireless', 
'zzzyen_currency_sign','zzzzoom_in','zzzzoom_out' ] ;