jQuery(document).ready(function($){
	
	function reportFormatter(row, cell, value, columnDef, dataContext) {
		return '<a data-id="'+value+'" class="btn btn-primary btn-xs view-task-trigger" href="#">View </a>';
	}
	
	var grid,taskGrid;
	var columns = [
		{id: "title", name: "Title", field: "title",sortable:true,width:220},
		{id: "description", name: "Description", sortable:true,field: "description",width:260},
		{id: "task", name: "Task", field: "task",cssClass: "column-center",formatter:reportFormatter}
	];
	
	var taskColumns = [
		{id: "title", name: "Title", field: "title"},
		{id: "description", name: "Description", field: "description"},
		{id: "owner_name", name: "Owner", field: "owner_name"},
		{id: "due_date", name: "Due Date", field: "due_date"},
		
	];

	var options = {
		rowHeight:40,
		enableCellNavigation: true,
		enableColumnReorder: false
	};
  
	var data = [];
	
	$.each(projects, function( i, value ) {
		data[i] = {
			title: value.name,
			description: value.description,
			task: value.id
		};
	});

	
    grid = new Slick.Grid("#projectGrid", data, columns, options);
	
	grid.onSort.subscribe(function (e, args) {
		currentSortCol = args.sortCol;
		isAsc = args.sortAsc;
		grid.invalidateAllRows();
		grid.render();
	});
	
	grid.onClick.subscribe(function (e,args) {
		e.preventDefault();
		
		var viewTask = $(e.target);
		if (viewTask.hasClass('view-task-trigger')) {
			var proj_id = viewTask.data('id');
			
			var data = {
					proj_id: proj_id
				};
			var getTask = $.post('/ajax/project/gettasklist',data);
			
			getTask.done(function( data ) {
				var taskData = [];
				if(data.success && (data.tasks).length){
					var tasks = data.tasks;
					
					$.each(tasks, function( i, value ) {
						taskData[i] = {
							title: value.name,
							description: value.description,
							owner_name: value.owner_name,
							due_date: value.nice_due_date
						};
					});
					
					taskGrid = new Slick.Grid("#taskGrid", taskData, taskColumns, options);
					taskGrid.autosizeColumns();
				}
				else{
					$("#taskGrid").html('<p>No Results</p>');
				}
			});
			
		}
    });
	
	
	
	grid.autosizeColumns();
});

