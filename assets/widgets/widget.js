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
			/*var reset = widget.connect.resetUserWidgetSettings();
			reset.done(function(){
				$("#gridstercontent-"+widget_id).empty();
				widget.core.loadWidget(widgetTemplate,widget_id);
			});*/
			//console.log(widget_id);
			widget.connect.resetUserWidgetSettings(widget_id);
		}
	});
	
	$('[data-toggle="tooltip"]').tooltip();
	
	widget.core.init();
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

		var tab_id = $(e.target).data("widget-id");
		var grid_active = $("#tab"+tab_id).data('tab-activated');
		if(grid_active != true){
			widget.core.activateGrid(tab_id);
			widget.core.activateWidget();
		}else{
			widget.core.activateWidget();
		}
	});
	
	$("#widget-add-tab").click(function(){
		var tab_name = prompt("Please enter the name of the tab", "");

		if (tab_name != null) {
			var create = widget.connect.createWidgetSettings(tab_name);
			create.done(function(){
				 location.reload();
			});
		}
	});
});
