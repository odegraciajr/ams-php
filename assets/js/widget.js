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
	
	$(".reset-widget").click(function(){
		var widget_id = $(this).data("widget-id");
		
		var ask = confirm("Are you sure you want to reset widgets?");
		if (ask == true) {
			var reset = widget.connect.resetUserWidgetSettings();
			reset.done(function(){
				$("#gridsterwidget-"+widget_id).empty().append('<ul class="gridstercontent"></ul>');
				widget.core.loadWidget(widgetTemplate,0);
			});
		}
	});
	$('[data-toggle="tooltip"]').tooltip();
	
	widget.core.init();
	//widget.core.setWidgetIDtoElement("reset-widget");
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		//console.log(e.target); // newly activated tab
		//console.log(e.relatedTarget); // previous active tab
		var widget_id = $(e.target).data("widget-id");
		$(".widget-list-wrap").css("width",$(".tab-content").width());
	});
	$("#test-width").click(function(){
		console.log($(".tab-content").width());
	});
});
