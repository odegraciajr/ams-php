var $ = jQuery;
var widget = {
	core:{
	
		/**
		 * Initial function: use for loading initial settings and function on widget.
		 * @params none;
		 * @return none
		 */ 
		init: function(){
			var widgetAjax = widget.connect.userWidgetSettings();
			
			widgetAjax.done(function(obj){
				if(obj.status && obj.userWidgets && obj.userWidgets.length > 0){
					
					//widget.core.loadWidget(obj.results,obj.widget_id);
					
					$.each(obj.userWidgets,function(key,val){
						if(val.settings){
							widget.core.loadWidget(val,val.widget_id);
						}
						else{
							widget.core.loadWidget({settings:widgetTemplate},val.widget_id);
						}
						if(key==0){
							widget.core.activateGrid(val.widget_id);
						}
					});
				}
				else{
					widget.core.loadWidget({settings:widgetTemplate},1);
					widget.core.activateGrid(1);
					widget.connect.saveUserWidgetSettings(1);
					
				}
			});
		},
		loadWidget: function (widgets,widget_id){
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
					widgetli.addClass('disabled').hide();
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
				$("#gridstercontent-"+widget_id).append(widgetul).data("widget-id",widget_id).data('grid-active',false);
			});
			
			//$(document).on("click",".gridster li .grid-remove",function(e){
			$("#gridstercontent-"+widget_id+" span.grid-remove").click(function(e){
				e.preventDefault();
				e.stopPropagation();
				
				var p = $(this).parent().parent();
				var li = p.parent().parent();
				var widget_id = li.data('widget-id');
				p.fadeOut(400,function(){
					p.addClass('disabled');
					widget.connect.saveUserWidgetSettings(widget_id);
				});
			});
		},
		activateGrid: function(widget_id){
			var gridx = $(".tab-content").width() /4-17;
			var gridy = $(document).height()/8 -10;
			
			$("#gridstercontent-"+widget_id+" ul").gridster({
				widget_margins: [2,3],
				widget_base_dimensions: [gridx,gridy],
				resize: {
					enabled: true,
					stop: function(e, ui, $widget) {
						widget.connect.saveUserWidgetSettings(widget_id);
					}
				},
				draggable: {
					handle: ".gridtitle, .gridtext",
					stop: function(e){
						widget.connect.saveUserWidgetSettings(widget_id);
					}
				}
			}).data('grid-active',true);
			//$("#gridstercontent-"+widget_id+" ul").show();
		}
	},
	/**
	 * Send an AJAX request to a URL using jQuery $.post function(http://api.jquery.com/jquery.post/)
	 * @params String (url), Array (data)
	 * @return Object jQuery $.post
	 */ 
	connect:{
		userWidgetSettings:function(){
			return $.post('/dashboard/getuserwidgetsettings');
		},
		createWidgetSettings:function(tab_name){
			return $.post('/dashboard/createuserwidgetsettings',{settings:null,tab_name:tab_name});
		},
		saveUserWidgetSettings:function(widget_id){
			var widgetSettings = {};
			var tab_name = $("#gridstercontent-"+widget_id).data('tab-name');
			
			$("#gridstercontent-"+widget_id+" li").each(function(i, el){
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
			//console.log({settings:widgetSettings,widget_id:widget_id});
			$.post('/dashboard/saveuserwidgetsettings',{settings:widgetSettings,widget_id:widget_id,tab_name:tab_name});
		},
		resetUserWidgetSettings:function(widget_id){
			var theGrid = $("#gridstercontent-"+widget_id);
			
			var tab_name = theGrid.data('tab-name');
			
			theGrid.draggable().draggable("destroy");
			theGrid.removeData();
			theGrid.empty();
			
			widget.core.loadWidget({settings:widgetTemplate},widget_id);
			widget.core.activateGrid(widget_id);
			
			var reset = $.post('/dashboard/saveuserwidgetsettings',{settings:null,widget_id:widget_id,tab_name:tab_name});
			//console.log(widgetTemplate);
		}
		
	}
}