var $ = jQuery;
var widget = {
	core:{
	
		/**
		 * Initial function: use for loading initial settings and function on widget.
		 * @params none;
		 * @return none
		 */ 
		init: function(){
			var widgetAjax = widget.connect.userWidgetSettings;
			
			widgetAjax.done(function(obj){
				if(obj.status && obj.userWidgets && obj.userWidgets.length > 0){
					
					//widget.core.loadWidget(obj.results,obj.widget_id);
					
					$.each(obj.userWidgets,function(key,val){
						//console.log(val.widget_id);
						if(val.settings){
							widget.core.loadWidget(val,val.widget_id);
						}
						else{
							widget.core.loadWidget({settings:widgetTemplate},val.widget_id);
						}
					});
				}
				else{
					widget.core.loadWidget({settings:widgetTemplate},1);
				}
			});
		},
		loadWidget: function (widgets,widget_id){
			var gridx = $(".tab-content").width() /4-17;
			var gridy = $(document).height()/8 -10;

			var widgetul = $('<ul></ul>').addClass('widget-list-wrap');
			
			$.each( widgets.settings, function( key, value ) {
				var htmlInner = "";
				var widgetli = $('<li class="li"></li>');
				widgetli.attr("id","grid-"+value.id)
					.attr("title",value.title)
					.attr("data-row",value.settings.row)
					.attr("data-col",value.settings.col)
					.attr("data-sizex",value.settings.sizex)
					.attr("data-sizey",value.settings.sizey)
					.attr("data-min-sizex",value.settings.minsizex)
					.attr("data-min-sizey",value.settings.minsizey);
					
				if(value.settings.active==0){
					widgetli.addClass('disabled');
				}
				
				if( typeof value.html == "undefined" ){
					var temp = $.grep(widgetTemplate, function(e){ return e.id == value.id; });
					htmlInner = temp[0].html;
				}
				else{
					htmlInner = value.html;
				}
				
				widgetli.append(htmlInner.replace(/\\\\/g,"\\").replace(/&#34;/g,'"').replace(/&#39;/g, "'"));
				
				widgetul.append(widgetli);
				$("#gridstercontent-"+widget_id).append(widgetul).data("widget-id",widget_id);
			});
			
			$(document).on("click",".gridster li .grid-remove",function(e){
				e.preventDefault();
				var p = $(this).parent().parent();
				p.fadeOut(function(){
					p.addClass('disabled');
					//widget.connect.saveUserWidgetSettings();
				});
			});
			
			$(".gridster ul").gridster({
				widget_margins: [2,3],
				widget_base_dimensions: [gridx,gridy],
				resize: {
					enabled: true,
					stop: function(e, ui, $widget) {
						//widget.connect.saveUserWidgetSettings();
					}
				},
				draggable: {
					handle: ".gridtitle, .gridtext",
					stop: function(e){
						//widget.connect.saveUserWidgetSettings();
					}
				}
			});
			$(".gridster").show();
		},
		setWidgetIDtoElement: function(elem){
			$("#"+elem).data("widget-id",widget.core.getCurrentWidgetID());
		},
		getCurrentWidgetID: function(){
			return $("#gridstercontent-1").data("widget-id");
		}
	},
	/**
	 * Send an AJAX request to a URL using jQuery $.post function(http://api.jquery.com/jquery.post/)
	 * @params String (url), Array (data)
	 * @return Object jQuery $.post
	 */ 
	connect:{
		userWidgetSettings:new function(){
			return $.post('/dashboard/getuserwidgetsettings');
		},
		saveUserWidgetSettings:function(){
			var widgetSettings = {};

			$(".gridster ul li").each(function(i, el){
				var li = $(this);
				var gridID = li.attr("id");
				
				var active = 1;
				if(li.hasClass('disabled')){
					active=0;
				}
				if( typeof gridID != "undefined" ){
				
					var id = gridID.replace("grid-","");
					widgetSettings[i] = {
						id: id,
						title: li.attr("title"),
						settings: {row:li.attr("data-row"),col:li.attr("data-col"),sizex:li.attr("data-sizex"),sizey:li.attr("data-sizey"),active:active}
					}
				}
			});
			$.post('/dashboard/saveuserwidgetsettings',{settings:widgetSettings});
		},
		resetUserWidgetSettings:function(id,reset){
			widget_id = 0;
			if( typeof id != "undefined"){
				widget_id = id;
			}
			return $.post('/dashboard/saveuserwidgetsettings',{widget_id:widget_id,settings:''});
		}
		
	}
}