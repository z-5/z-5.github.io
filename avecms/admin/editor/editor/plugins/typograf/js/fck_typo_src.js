var oEditor		= window.parent.InnerDialogLoaded() ;
var FCK			= oEditor.FCK ;
var FCKLang		= oEditor.FCKLang ;
var FCKConfig	= oEditor.FCKConfig ;
var variable 	= null;
var textEdit = null;
var textView = null;
var textOut = null;
var zgIn = null;
var zgOut = null;
var btnEdit  = null;
var btnView  = null;

function urlencode( str ) {
    // http://kevin.vanzonneveld.net
    // +   original by: Philip Peterson
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: AJ
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // %          note: info on what encoding functions to use from: http://xkr.us/articles/javascript/encode-compare/
    // *     example 1: urlencode('Kevin van Zonneveld!');
    // *     returns 1: 'Kevin+van+Zonneveld%21'
    // *     example 2: urlencode('http://kevin.vanzonneveld.net/');
    // *     returns 2: 'http%3A%2F%2Fkevin.vanzonneveld.net%2F'
    // *     example 3: urlencode('http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a');
    // *     returns 3: 'http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a'
                                     
    var histogram = {}, histogram_r = {}, code = 0, tmp_arr = [];
    var ret = str.toString();
    
    var replacer = function(search, replace, str) {
        var tmp_arr = [];
        tmp_arr = str.split(search);
        return tmp_arr.join(replace);
    };
    
    // The histogram is identical to the one in urldecode.
    histogram['!']   = '%21';
    histogram['%20'] = '+';
    
    // Begin with encodeURIComponent, which most resembles PHP's encoding functions
    ret = encodeURIComponent(ret);
    
    for (search in histogram) {
        replace = histogram[search];
        ret = replacer(search, replace, ret) // Custom replace. No regexing
    }
    
    // Uppercase for full PHP compatibility
    return ret.replace(/(\%([a-z0-9]{2}))/g, function(full, m1, m2) {
        return "%"+m2.toUpperCase();
    });
    
    return ret;
}

function OnLoad()
{
	// First of all, translate the dialog box texts.

$('#load_typo').css({"display": "none"});
$('#load_typo').html('<img src="'+oEditor.FCKConfig.PluginsPath+'typograf/loader.gif">'+$('#load_typo').html());
textEdit = document.getElementById('txtEditTypo');
textView = document.getElementById('txtViewTypo');
textOut = document.getElementById('txtOutTypo');
btnEdit  = document.getElementById('btnEdit');
btnView  = document.getElementById('btnView');
zgIn  = document.getElementById('zgIn');
zgOut  = document.getElementById('zgOut');
oEditor.FCKLanguageManager.TranslatePage(document) ;

  textView.style.display = 'block';
  textEdit.style.display = 'none';
  btnView.style.display = 'none';
  zgIn.style.display = 'block';
  zgOut.style.display = 'none';
  textView.innerHTML = FCK.GetHTML();
  textEdit.value = FCK.GetHTML();
}



// typograf text
function typo_text() {
	window.parent.SetOkButton( true ) ;
	variable = urlencode(textView.innerHTML);
	//typograf 1
	if(document.form1.radio[0].checked){
		$.post(oEditor.FCKConfig.PluginsPath + 'typograf/php/typo_off1.php', {text : variable}, show_typo);
	}
	//typograf 2
	if(document.form1.radio[1].checked){
		$.post(oEditor.FCKConfig.PluginsPath + 'typograf/php/typo_off2.php', {text : variable}, show_typo);
	}
	//typograf 3
	if(document.form1.radio[2].checked){
		$.post(oEditor.FCKConfig.PluginsPath + 'typograf/php/typograf_ru.php', {text : variable}, show_typo);
	}
	//typograf 4
	if(document.form1.radio[3].checked){
		$.post(oEditor.FCKConfig.PluginsPath + 'typograf/php/typograf_al.php', {text : variable}, show_typo);
	}

	$('#load_typo').css({"display": "block"});
	textView.style.display = 'none';
	textEdit.style.display = 'none';
	btnView.style.display = '';
	btnEdit.style.display = 'none';
	zgIn.style.display = 'none';
	zgOut.style.display = 'block';
	textOut.style.display = 'block';
}

// edit text
function edit_text() {
	textEdit.style.display = 'block';
	textEdit.value = textView.innerHTML;
	textView.style.display = 'none';
	textOut.style.display = 'none';
	btnView.style.display = '';
	btnEdit.style.display = 'none';
	zgIn.style.display = 'block';
	zgOut.style.display = 'none';
}

function view_text() {
	textView.innerHTML = textEdit.value;
	textOut.style.display = 'none';
	textView.style.display = 'block';
	textEdit.style.display = 'none';
	btnView.style.display = 'none';
	btnEdit.style.display = '';
	zgIn.style.display = 'block';
	zgOut.style.display = 'none';
}


function show_typo(textOut) {
	$('#txtOutTypo').html(textOut);
	$('#load_typo').css({"display": "none"});
}


function Ok() {
	FCK.Focus();
	var B = FCK.SetHTML($('#txtOutTypo').html());
	window.parent.Cancel( true ) ;
}

var oRange = null ;
