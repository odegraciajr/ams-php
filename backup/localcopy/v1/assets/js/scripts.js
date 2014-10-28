jQuery(document).ready(function($){
	$('#myaccount a.tab').click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	});
	
	$('#addmembers #search_user').keyup(function(){
		var keyword = $(this).val();
		
		if( keyword.length > 2 ){
			var userInvites = $.post('/account/accountsearch/',{keyword:keyword});
			
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
		$('#addmembers #search_user').val('').focus();
		addUserInvites(AllProjectUsersForInvite);
	});
	
	function addUserInvites(usersData)
	{
		$('#user-invite-select').html("");
		$.each(usersData, function(key, value) {
			if($.inArray(parseInt(value.id),AllProjectMemberUserIds)== -1){
				$('#user-invite-select').append($("<option></option>").attr("value",value.email).text(value.full_name)); 
			}
		});
	}
	
});