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
	
});