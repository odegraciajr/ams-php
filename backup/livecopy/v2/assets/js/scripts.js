jQuery(document).ready(function($){
	$('#myaccount a.tab').click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	});
});