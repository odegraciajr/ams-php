/**
* AMS Widget, this will handle all javascript request including JSON/AJAX request to PHP.
*/

var projects = {
	"projects":{
		"dataViews":{},
		"grid": {},
		"dataset": {},
		//"columns": [{id:"GMID",value:"Buyer Name"},{id:"BuyerCode",value:"Buyer Code"},{id:"CreativityTeam",value:"Creativity Team"},{id:"SMT",value:"SMT"},{id:"Program",value:"Program"},{id:"Platform",value:"Platform"},{id:"Facility",value:"Facility"},{id:"LBE",value:"LBE"},{id:"ManufacturingDUNS",value:"Manufacturing DUNS"},{id:"ManufacturingSupplier",value:"Manufacturing Supplier"},{id:"ManufacturingSupplierCountry",value:"Manufacturing Supplier Country"},{id:"HeaderDUNS",value:"Header DUNS"},{id:"HeaderSupplier",value:"Header Supplier"},{id:"HeaderSupplierCountry",value:"Header Supplier Country"},{id:"ShipDUNS",value:"Ship DUNS"},{id:"ShipSupplier",value:"Ship Supplier"},{id:"ShipSupplierCountry",value:"Ship Supplier Country"},{id:"UltimateDUNS",value:"Ultimate DUNS"},{id:"UltimateSupplier",value:"Ultimate Supplier"},{id:"UltimateSupplierCountry",value:"Ultimate Supplier Country"} ,{id:"Currency",value:"Currency"}],
		"columns":
		[
			{id: "sel", name: "#", field: "num", behavior: "select", cssClass: "cell-selection", width: 40, resizable: false, selectable: false },
			{id: "title", name: "Title", field: "title", width: 90, minWidth: 90, cssClass: "cell-title"},
			{id: "duration", name: "Duration", field: "duration"},
			{id: "%", name: "% Complete", field: "percentComplete", width: 100, resizable: false},
			{id: "start", name: "Start", field: "start", width: 70},
			{id: "finish", name: "Finish", field: "finish", width: 70},
			{id: "effort-driven", name: "Effort Driven", width: 100, minWidth: 20, maxWidth: 100, cssClass: "cell-effort-driven", field: "effortDriven"}
		],
		
		"fields": [{id:"Contract",column:"Contract" ,editable: -1},{id:"Facility", column: "Facility",editable: -1},{id:"Program", column: "Program" ,editable: -1},{id:"Platform", column:"Platform" },{id:"PartNumber",column:"Part Number"},{id:"PartDescription",column:"Part Description"},{id:"CreativityTeam", column: "Creativity Team"},{id:"SMT", column: "SMT"},{id:"BuyerCode",column: "Buyer Code"}, {id:"BuyerName",column:"Buyer Name"},{id:"LBE",column:"Legal Business Entity"},{id:"IssueDate", column: "Contract Issue Date"},{id:"EffectiveDate", column :"Contract Effective Date"},{id:"ExpirationDate", column: "Contract Expiration Date"},{id:"PriceEffectiveDate",column: "Price Effective Date"},{id:"PriceExpirationDate", column: "Price Expiration Date"},{id:"ManufacturingDUNS", column: "Manufacturing DUNS"},{id:"ManufacturingSupplier", column : "Manufacturing Name"},{id:"ManufacturingSupplierCountry", column : "Manufacturing Country"},{id:"HeaderDUNS", column : "Header DUNS"},{id:"HeaderSupplier", column : "Header Name" },{id:"HeaderSupplierCountry", column : "Header  Country" },{id:"ShipDUNS", column : "Ship From DUNS" },{id:"ShipSupplier", column : "Ship From Name" },{id:"ShipSupplierCountry", column : "Ship From Country"},{id:"UltimateDUNS", column : "Ultimate DUNS"},{id:"UltimateSupplier", column : "Ultimate Name"},{id:"UltimateSupplierCountry", column: "Ultimate  Country" },{id:"Currency", column:"Currency"},{id:"TotalPrice", column: "Total Price"},{id:"BasePrice", column: "Base Price"},{id:"AddonPrice", column: "Add on Price"},{id:"BaseMaterial", column: "Base Material Price"},{id:"APVCY", column: "2014 APV &#128;"},{id:"APVNY",column: "2015 APV &#128;"},{id:"VolumeCY", column: "2014 Volume"},{id:"VolumeNY",column: "2015 Volume"},{id:"ITBCY",column: "2014 ITB &#128;"},{id:"ITBNY", column:"2015 ITB &#128;"}],
		"timeout":null,
		"defaultColumns": [{id: "Contract", column: "Contract" , editable: -1}, {id:"Facility",column: "Facility"}, {id:"Program",column: "Program"},{id:"PartNumber",column:"Part Number"},{id: "APVCY",column: "2014 APV &#128;"}, {id: "APVNY", column: "2015 APV &#128;"}],
		"selectedColumns" :{},
		"filters":{},	
		"filters2": {},
		"pager": {},
		"sql":{},
		"next" :{},
		"previous" :{},
		"paging": {},
		"where": {},
		"populate" :{},
		"datainfo": {},
		"order": {},
		"dataViewSort":{},
		"callback": {},
		"maximize": {},
		"updatePaging": {},
		"copyRecordset": false,
		
		//Load contract details
		"init" : function(id){
			return '<div id="project-grid-'+id+'" style="width:100%;height:200px;"></div><div id="pager-'+id+'" style="width:100%;height:20px;"></div>';
		},
		"render" : function(id){
			var dataView;
			var grid;
			var data = [];
			
			var options = {
				editable: false,
				enableAddRow: false,
				enableCellNavigation: true,
				forceFitColumns:true
			};
			
			function DataItem(i) {
				this.num = i + 1;
				this.id = "id_" + i;
				this.percentComplete = Math.round(Math.random() * 100);
				this.effortDriven = (i % 5 == 0);
				this.start = "01/01/2009";
				this.finish = "01/05/2009";
				this.title = "Project " + i;
				this.duration = Math.round(Math.random() * 10) + " days";
			}
			function myFilter(item, args) {
				return item["percentComplete"] >= args;
			}

			for (var i = 0; i < 500; i++) {
				data[i] = new DataItem(i);
			}
			
			dataView = new Slick.Data.DataView({ inlineFilters: true });
			grid = new Slick.Grid('#project-grid-'+id, dataView, widget.projects.columns, options);
			var pager = new Slick.Controls.Pager(dataView, grid, $("#pager-"+id));

			// wire up model events to drive the grid
			dataView.onRowCountChanged.subscribe(function (e, args) {
			  grid.updateRowCount();
			  grid.render();
			});

			dataView.onRowsChanged.subscribe(function (e, args) {
			  grid.invalidateRows(args.rows);
			  grid.render();
			});
			//grid.autosizeColumns();
			
			dataView.beginUpdate();
			dataView.setItems(data);
			dataView.setFilter(myFilter);
			dataView.setFilterArgs(0);
			dataView.endUpdate();
			
			
		}
	}
}

if (widget && !widget.projects)
	$.extend(widget,projects);