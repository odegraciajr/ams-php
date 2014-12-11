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
	//load widgets positions
	
	var widgetTemplate = [

		{
			id: 'projects',
			title: 'Projects',
			html: '<div class="gridtitle"><a class="gridtext"><span class="glyphicon glyphicon glyphicon-file aria-hidden="true"></span> Projects</a><span class="glyphicon glyphicon-remove gridtoolbar" aria-hidden="true"></span></i></div>',
			settings: {row: 1,col: 1,sizex:2,sizey:2}
		},
		{
			id: 'organization',
			title: 'Organization',
			html: '<div class="gridtitle"><a class="gridtext"><span class="glyphicon glyphicon glyphicon-user aria-hidden="true"></span> Organization</a><span class="glyphicon glyphicon-remove gridtoolbar" aria-hidden="true"></span></i></div>',
			settings: {row: 2,col: 1,sizex:2,sizey:2}
		},
		{
			id: 'recentactivity',
			title: 'Recent Activity',
			html: '<div class="gridtitle"><a class="gridtext"><span class="glyphicon glyphicon glyphicon-th-large aria-hidden="true"></span> Recent Activity</a><span class="glyphicon glyphicon-remove gridtoolbar" aria-hidden="true"></span></i></div>',
			settings: {row: 2,col: 2,sizex:2,sizey:2}
		},
		{
			id: 'overview',
			title: 'Overview',
			html: '<div class="gridtitle"><a class="gridtext"><span class="glyphicon glyphicon glyphicon-eye-open aria-hidden="true"></span> Overview</a><span class="glyphicon glyphicon-remove gridtoolbar" aria-hidden="true"></span></i></div>',
			settings: {row: 1,col: 2,sizex:2,sizey:2}
		}
	];

	function loadWidget(widgets){
		$.each( widgets, function( key, value ) {
			var htmlInner = "";
			var widgetli = $('<li class="li" data-min-sizex="2" data-min-sizey="2"></li>');
			widgetli.attr("id","grid-"+value.id)
				.attr("title",value.title)
				.attr("data-row",value.settings.row)
				.attr("data-col",value.settings.col)
				.attr("data-sizex",value.settings.sizex)
				.attr("data-sizey",value.settings.sizey);
			
			if( typeof value.html == "undefined" ){
				var temp = $.grep(widgetTemplate, function(e){ return e.id == value.id; });
				htmlInner = temp[0].html;
			}
			else{
				htmlInner = value.html;
			}
			
			widgetli.append(htmlInner.replace(/\\\\/g,"\\").replace(/&#34;/g,'"').replace(/&#39;/g, "'"));
			$("#gridstercontent").append(widgetli);
		});
		
		var gridx = $(document).width()/4-17;
		var gridy = $(document).height()/8 -10;
		
		$(".gridster ul").gridster({
			widget_margins: [2,3],
			widget_base_dimensions: [gridx,gridy],
			resize: {
				enabled: true,
				stop: function(e, ui, $widget) {
					var widgetSettings = {};
					
					$(".gridster ul li").each(function(i, el){
					
						var li = $(this);
						var gridID = li.attr("id");
						
						if( typeof gridID != "undefined" ){
						
							var id = gridID.replace("grid-","");
							widgetSettings[i] = {
								id: id,
								title: li.attr("title"),
								settings: {row:li.attr("data-row"),col:li.attr("data-col"),sizex:li.attr("data-sizex"),sizey:li.attr("data-sizey")}
							}
						}
					});
					//save user settings;
					$.post('/dashboard/saveuserwidgetsettings',{settings:widgetSettings});
				}
			},
			draggable: {
				handle: ".gridtitle, .gridtext",
				stop: function(e){
				
					var widgetSettings = {};
					
					$(".gridster ul li").each(function(i, el){
						var li = $(this);
						var gridID = li.attr("id");
						
						if( typeof gridID != "undefined" ){
						
							var id = gridID.replace("grid-","");
							widgetSettings[i] = {
								id: id,
								title: li.attr("title"),
								settings: {row:li.attr("data-row"),col:li.attr("data-col"),sizex:li.attr("data-sizex"),sizey:li.attr("data-sizey")}
							}
						}
					});
					//save user settings;
					$.post('/dashboard/saveuserwidgetsettings',{settings:widgetSettings});
				}
			}
		}).data('gridster');
		
		$(".gridster").show();
	}
	
	function widgetInit(){
		var widgetAjax = $.post('/dashboard/getuserwidgetsettings');
		
		widgetAjax.done(function(obj){
			if(obj.status && obj.results){
				loadWidget(obj.results);
			}
			else{
				loadWidget(widgetTemplate);
			}
		});
	}
	widgetInit();
});