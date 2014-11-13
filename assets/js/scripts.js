jQuery(document).ready(function($){
	$('#myaccount a.tab').click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	});
	
	$('#search_user').keyup(function(){
		var keyword = $(this).val();
		
		if( keyword.length > 2 ){
			var userInvites = $.post('/project/accountsearch/',{keyword:keyword});
			
			userInvites.done(function(users) {
				addUserInvites(users);
			});
		}
		else {
			if( keyword.length == 0 ) {
				addUserInvites(AllProjectUsersForInvite);
			}
		}
	});
	
	$('#reset-all-user-invite').click(function(){
		$('#search_user').val('').focus();
		addUserInvites(AllProjectUsersForInvite);
	});
	
	function addUserInvites(usersData)
	{
		$('#user-invite-select').html("");
		$.each(usersData, function(key, value) {
			if($.inArray(parseInt(value.id),AllProjectMemberUserIds)== -1){

				var name = value.full_name !== null ? value.full_name : value.email;
				
				$('#user-invite-select').append($("<option></option>").attr("value",value.email).text(name)); 
			}
		});
	}
	if(jQuery().datetimepicker) {
		$('#estimate_duration_dummy').datetimepicker({
			format: 'HH:mm',
			pickDate: false,
			pickSeconds: false,
			pick12HourFormat: false
		});
		$('#due_date_dummy,#request_date_dummy').datetimepicker({
			pickTime:false,
			useCurrent: true
		});

		$('#due_time_dummy').datetimepicker({
			pickDate: false
		});

		$("#estimate_duration_dummy").on("dp.change",function (e) {
		   var ndate = e.date;
		   $("#estimate_duration").val(ndate.format("YYYY-MM-DD HH:mm:ss"));
		});

		$("#due_date_dummy").on("dp.change",function (e) {
		   var ndate = e.date;
		   $("#due_date").val(ndate.format("YYYY-MM-DD HH:mm:ss"));
		});

		$("#due_time_dummy").on("dp.change",function (e) {
		   var ndate = e.date;
		   $("#due_time").val(ndate.format("YYYY-MM-DD HH:mm:ss"));
		});

		$("#request_date_dummy").on("dp.change",function (e) {
		   var ndate = e.date;
		   $("#request_date").val(ndate.format("YYYY-MM-DD 00:00:00"));
		});
	}
});