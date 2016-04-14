function cp_code(v, feldname, form) {
	if (document.selection) {
		var str = document.selection.createRange().text;
		document.getElementById(feldname).focus();
		var sel = document.selection.createRange();
		sel.text = "<" + v + ">" + str + "</" + v + ">";
		return;
	}
	else if (document.getElementById && !document.all) {
		var txtarea = document.forms[form].elements[feldname];
		var selLength = txtarea.textLength;
		var selStart = txtarea.selectionStart;
		var selEnd = txtarea.selectionEnd;
		if (selEnd == 1 || selEnd == 2)
		selEnd = selLength;
		var s1 = (txtarea.value).substring(0,selStart);
		var s2 = (txtarea.value).substring(selStart, selEnd)
		var s3 = (txtarea.value).substring(selEnd, selLength);
		txtarea.value = s1 + '<' + v + '>' + s2 + '</' + v + '>' + s3;
		return;
	}
	else {
		cp_insert('<' + v + '></' + v + '> ');
	}
}

function cp_tag(v, feldname, form) {
	if (document.selection) {
		var str = document.selection.createRange().text;
		document.getElementById(feldname).focus();
		var sel = document.selection.createRange();
		sel.text = "[" + v + "]" + str + "[/" + v + "]";
		return;
	}
	else if (document.getElementById && !document.all) {
		var txtarea = document.forms[form].elements[feldname];
		var selLength = txtarea.textLength;
		var selStart = txtarea.selectionStart;
		var selEnd = txtarea.selectionEnd;
		if (selEnd == 1 || selEnd == 2)
		selEnd = selLength;
		var s1 = (txtarea.value).substring(0,selStart);
		var s2 = (txtarea.value).substring(selStart, selEnd)
		var s3 = (txtarea.value).substring(selEnd, selLength);
		txtarea.value = s1 + '[' + v + ']' + s2 + '[/' + v + ']' + s3;
		return;
	}
	else {
		cp_insert('[' + v + '][/' + v + '] ');
	}
}

function cp_insert(what,feldname, form) {
	if (document.getElementById(feldname).createTextRange) {
		document.getElementById(feldname).focus();
		document.selection.createRange().duplicate().text = what;
	}
	else if (document.getElementById && !document.all) {
		var tarea = document.forms[form].elements[feldname];
		var selEnd = tarea.selectionEnd;
		var txtLen = tarea.value.length;
		var txtbefore = tarea.value.substring(0,selEnd);
		var txtafter =  tarea.value.substring(selEnd, txtLen);
		tarea.value = txtbefore + what + txtafter;
	}
	else {
		document.entryform.text.value += what;
	}
}

function browse_uploads(target, width, height, scrollbar) {
	if (typeof width=='undefined' || width=='') var width = screen.width * 0.8;
	if (typeof height=='undefined' || height=='') var height = screen.height * 0.8;
	if (typeof scrollbar=='undefined') var scrollbar=0;
	var targetVal = document.getElementById(target).value;
	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;
	window.open('browser.php?typ=bild&mode=fck&target='+target+'&tval='+targetVal,'imgpop','left='+left+',top='+top+',width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1');
}

function windowOpen(url, width, height, scrollbar, winname) {
	if (typeof width=='undefined' || width=='') var width = screen.width * 0.8;
	if (typeof height=='undefined' || height=='') var height = screen.height * 0.8;
	if (typeof scrollbar=='undefined') var scrollbar=1;
	if (typeof winname=='undefined') var winname='pop';
	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;
	window.open(url,winname,'left='+left+',top='+top+',width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1').focus();
}

function cp_imagepop(url, width, height, scrollbar) {
	if (typeof width=='undefined' || width=='') var width = screen.width * 0.8;
	if (typeof height=='undefined' || height=='') var height = screen.height * 0.8;
	if (typeof scrollbar=='undefined') var scrollbar=1;
	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;
	window.open('browser.php?typ=bild&mode=fck&target='+url+'','imgpop','left='+left+',top='+top+',width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1');
}

function cp_pop(url, width, height, scrollbar, winname) {
	if (typeof width=='undefined' || width=='') var width = screen.width * 0.8;
	if (typeof height=='undefined' || height=='') var height = screen.height * 0.8;
	if (typeof scrollbar=='undefined') var scrollbar=1;
	if (typeof winname=='undefined') var winname='pop';
	var left = ( screen.width - width ) / 2;
	var top = ( screen.height - height ) / 2;
	window.open(url,winname,'left='+left+',top='+top+',width='+width+',height='+height+',scrollbars='+scrollbar+',resizable=1').focus();
}
function confirmDelete(){
    $(".ConfirmDelete").click(function(e){
		e.preventDefault();
		var href = $(this).attr('href');
		var title = $(this).attr('dir');
		var confirm = $(this).attr('name');
		jConfirm(
				confirm,
				title,
				function(b){
					if (b){
						$.alerts._overlay('show');
						window.location = href;
					}
				}
			);
	});
}

$(function() {
    $.ajaxSetup({
    	cache: false,
        error: function(jqXHR, exception) {
            if (jqXHR.status === 0) {
            	$.alerts._overlay('hide');
                $.jGrowl(ajaxErrorStatus,{theme: 'error'});
            } else if (jqXHR.status == 404) {
            	$.alerts._overlay('hide');
                $.jGrowl(ajaxErrorStatus404,{theme: 'error'});
            } else if (jqXHR.status == 401) {
            	$.alerts._overlay('hide');
                $.jGrowl(ajaxErrorStatus401,{theme: 'error'});
            } else if (jqXHR.status == 500) {
            	$.alerts._overlay('hide');
                $.jGrowl(ajaxErrorStatus500,{theme: 'error'});
            } else if (exception === 'parsererror') {
            	$.alerts._overlay('hide');
                $.jGrowl(ajaxErrorStatusJSON,{theme: 'error'});
            } else if (exception === 'timeout') {
            	$.alerts._overlay('hide');
				$.jGrowl(ajaxErrorStatusTimeOut,{theme: 'error'});
            } else if (exception === 'abort') {
            	$.alerts._overlay('hide');
                $.jGrowl(ajaxErrorStatusAbort,{theme: 'error'});
            } else {
            	$.alerts._overlay('hide');
                $.jGrowl(ajaxErrorStatusMess + jqXHR.responseText,{theme: 'error'});
            }
        }
    });
});

$(document).ready(function(){

	confirmDelete();

		if (typeof width=='undefined' || width=='') var width = screen.width * 0.7;
		if (typeof height=='undefined' || height=='') var height = screen.height * 0.7;

	$("a.iframe").fancybox({
		padding: 0,
		margin: 0,
		width: width,
		height: height,
		autoScale: true,
		speedIn: 100,
		speedOut: 100,
		overlayOpacity: 0.2,
		overlayColor: "#000",
		centerOnScroll: true
	});  

	$('.actions a').hover(function(){
		$(this).animate({opacity: 1.0},100);
			},function(){
		$(this).animate({opacity: 0.5},100);
	});

    //===== Преобразование форм =====//
    $(".mainForm").jqTransform({imgPath:"../images"});

    //===== Выход =====//
	$(".ConfirmLogOut").click( function(e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var title = logoutTitle;
		var confirm = logoutConfirm;
		jConfirm(
				confirm,
				title,
				function(b){
					if (b){
                        window.location = href;
            		}
				}
			);
	});

    //===== Окно очистки кэша =====//
	$(".clearCache").click( function(e) {
		e.preventDefault();
		var title = clearCacheTitle;
		var confirm = clearCacheConfirm;
		jConfirm(
				confirm,
				title,
				function(b){
					if (b){
                        $.alerts._overlay('hide');
                        $.alerts._overlay('show');
						$.ajax({
						    url: ave_path+'admin/index.php?do=settings&sub=clearcache&ajax=run',
						    type: 'POST',
						    dataType: "json",
						    data: ({
						    	templateCache: 1,
						    	templateCompiledTemplate: 1,
						    	moduleCache: 1,
						    	sqlCache: 1
						    	}),
						    success: function (data) {
						    	$.alerts._overlay('hide');
	                            $.jGrowl(data[0],{theme: data[1]});
	                            $('#cachesize').html('0 Kb');
								$('.cachesize').html('0 Kb');
						    }
						});

            		}
				}
			);
	});

    //===== Окно очистки кэша + Сессий =====//
	$(".clearCacheSess").click( function(e) {
		e.preventDefault();
		var title = clearCacheSessTitle;
		var confirm = clearCacheSessConfirm;
		jConfirm(
				confirm,
				title,
				function(b){
					if (b){
                        $.alerts._overlay('hide');
                        $.alerts._overlay('show');
						$.ajax({
						    url: ave_path+'admin/index.php?do=settings&sub=clearcache&ajax=run',
						    type: 'POST',
						    dataType: "json",
						    data: ({
						    	templateCache: 1,
						    	templateCompiledTemplate: 1,
						    	moduleCache: 1,
						    	sqlCache: 1,
						    	sessionUsers: 1
						    	}),
						    success: function (data) {
						    	$.alerts._overlay('hide');
	                            $.jGrowl(data[0],{theme: data[1]});
	                            $('#cachesize').html('0 Kb');
								$('.cachesize').html('0 Kb');
						    }
						});
            		}
				}
			);
	});

    //===== Окно очистки миниатюр изображений =====//
	$(".clearThumb").click( function(e) {
		e.preventDefault();
		var title = clearThumbTitle;
		var confirm = clearThumbConfirm;
		jConfirm(
				confirm,
				title,
				function(b){
					if (b){
						$.ajax({
						    url: ave_path+'admin/index.php?do=settings&sub=clearthumb&ajax=run',
						    type: 'POST',
						    dataType: "json",
						    success: function (data) {
						    	$.alerts._overlay('hide');
	                            $.jGrowl(data[0],{theme: data[1]});
						    }
						});
            		}
				}
			);
	});

    //===== Показать размер кэша =====//
	$("#cacheShow").click( function(e, x) {
		e.preventDefault();
		var title = cacheShowTitle;
		var confirm = cacheShowConfirm;
		jConfirm(
				confirm,
				title,
				function(b){
					if (b){
                        $.alerts._overlay('hide');
                        $.alerts._overlay('show');
						$.ajax({
						    url: ave_path+'admin/index.php?do=settings&sub=showcache&ajax=run',
						    type: 'POST',
						    dataType: "json",
						    data: ({
						    	showCache: 1
						    	}),
						    success: function (data) {
	                            $.alerts._overlay('hide');
	                            $('#cachesize').html(data[0]);
						    }
						});
            		}
				}
			);
	});

	//===== ToTop =====//
	$().UItoTop({ easingType: 'easeOutQuart' });

	//===== UI dialog =====//
	$( "#dialog-message" ).dialog({
		autoOpen: false,
		modal: true
	});

	$( "#opener" ).click(function() {
		$( "#dialog-message" ).dialog( "open" );
		return false;
	});

	$(".dropdown").on("mouseenter mouseleave", function(event) {
		var ul = $(this).children("ul");

		ul.stop(true, true);
		if (event.type === "mouseenter") {
			ul.slideToggle(10);
		} else {
			ul.hide(10);
		}
	});

	$( "#dialog-message" ).dialog({
		autoOpen: false,
		modal: true,
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});

	//===== Custom single file input =====//
	$("input.fileInput").filestyle({
		imageheight : 26,
		imagewidth : 98,
		width : 296
	});


	//===== Dynamic Tables =====//
	oTable = $('#dinamTable').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
        "aaSorting": [[ 0, "desc" ]],
		"sDom": '<""f>t<"F"lp>'
	});


	//===== Placeholder for all browsers =====//
	$('input[placeholder], textarea[placeholder]').placeholder();

	//===== Information boxes =====//
	$(".hideit").click(function() {
		$(this).fadeOut(400);
	});


	//===== Tabs =====//
	$.fn.simpleTabs = function(){

		//On Click Event
		$("ul.tabs li").click(function() {
			$(this).parent().parent().find("ul.tabs li").removeClass("activeTab"); //Remove any "active" class
			$(this).addClass("activeTab"); //Add "active" class to selected tab
			$(this).parent().parent().find(".tab_content").hide(); //Hide all tab content
			var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
			$(activeTab).show(); //Fade in the active content
			return false;
		});

	};//end function

	$("div[class^='widget']").simpleTabs(); //Run function on any div with class name of "Simple Tabs"


	//===== Tooltip =====//
	$('.topleftDir').tipsy({fade: false, gravity: 'se', opacity: 0.9});
	$('.toprightDir').tipsy({fade: false, gravity: 'sw', opacity: 0.9});
	$('.leftDir').tipsy({fade: false, gravity: 'e', opacity: 0.9});
	$('.rightDir').tipsy({fade: false, gravity: 'w', opacity: 0.9});
	$('.topDir').tipsy({fade: false, gravity: 's', opacity: 0.9});
	$('.botDir').tipsy({fade: false, gravity: 'n', opacity: 0.9});

	//===== Collapsible elements management =====//
	$('.opened').collapsible({
		defaultOpen: 'opened',
		cssOpen: 'inactive',
		cssClose: 'normal',
		cookieName: 'opened',
		cookieOptions: {
	        expires: 7,
			domain: ''
    	},
		speed: 200
	});

	$('.closed').collapsible({
		defaultOpen: '',
		cssOpen: 'inactive',
		cssClose: 'normal',
		cookieName: 'closed',
		cookieOptions: {
	        expires: 7,
			domain: ''
    	},
		speed: 200
	});
});

$(document).keydown(function(event) {
var numberOfOptions= $("#rubric_id > option").length;
var selectedIndex = $("#rubric_id option:selected").val();

switch (event.keyCode) {
    case 38: // UP Key
        if(selectedIndex > 0){
            $("#rubric_id").val(parseInt($("#rubric_id option:selected").val()) - 1);   
        }
        break;
    case 40: // DOWN Key
        if(selectedIndex < numberOfOptions - 1){
            $("#rubric_id").val(parseInt($("#rubric_id option:selected").val()) + 1);   
        }
        break;
}

});


(function ($) {
    $.fn.extend({
        limit: function (limit, element) {
            var interval, f;
            var self = $(this);
            $(this).focus(function () {
                interval = window.setInterval(substring, 100)
            });
            $(this).blur(function () {
                clearInterval(interval);
                substring()
            });
            substringFunction = "function substring(){ var val = $(self).val();var length = val.length;if(length > limit){$(self).val($(self).val().substring(0,limit));}";
            if (typeof element != 'undefined') substringFunction += "if($(element).html() != limit-length){$(element).html((limit-length<=0)?'0':limit-length);}";
            substringFunction += "}";
            eval(substringFunction);
            substring()
        }
    })
});