/**
* AMS Widget, this will handle all javascript request including JSON/AJAX request to PHP.
*/
jQuery(document).ready(function($){
	
	$(".gridimg").tooltip();
	$(".gridtitle").mousedown(function(e) {
		$(this).css("cursor","pointer");
	});
	$(".gridtitle").mouseup(function(e) {
		$(this).css("cursor","default");
	});
	
	$("#reset-widget").click(function(){
		//console.log($(this).data("widget-id"));
		var reset = widget.connect.resetUserWidgetSettings();
		reset.done(function(){
			$(".gridster").empty().append('<ul id="gridstercontent"></ul>');
			widget.core.loadWidget(widgetTemplate,0);
		});
	});
	
	widget.core.init();
	widget.core.setWidgetIDtoElement("reset-widget");
	
});

