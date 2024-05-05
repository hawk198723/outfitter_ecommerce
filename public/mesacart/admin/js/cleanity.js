$(function(){	
	var navSelector = "ul#menu li";/** define the main navigation selector **/
	/** set up rounded corners for the selected elements **/
	$('.box-container').corners("5px bottom");
	 $('.box h4').corners("5px top");
	 $('ul.tab-menu li ').corners("5px top");
	 $('textarea#wysiwyg').wysiwyg();
	/** $("div#sys-messages-container a, div#to-do-list ul li a").colorbox({fixedWidth:"50%", transitionSpeed:"100", inline:true, href:"#sample-modal"}); /** jquery colorbox modal boxes for system
	 messages and to-do list - see colorbox help docs for help: http://colorpowered.com/colorbox/ **/
	$('#to-do').tabs();		 
	$("#calendar").datepicker();/** jquery ui calendar/date picker - see jquery ui docs for help: http://jqueryui.com/demos/ **/
	$("ul.list-links").accordion();/** side menu accordion - see jquery ui docs for help:  http://jqueryui.com/demos/  **/
 
	});