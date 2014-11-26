<div class="content-main">
	<h1>Dashboard SlickGrid Sample</h1>
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">Recent Activity</div>
		<div class="panel-body">
			<div class="list">
				<p><a href="#">user1</a> commented on <a href="#">Project AMS</a>.</p>
				<p><a href="#">userAdmin</a> posted a note on <a href="#">DSS Organization</a>.</p>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
				<!-- Default panel contents -->
				<div class="panel-heading">All Projects</div>
				<div class="panel-body">
					<div id="projectGrid" style="height:400px;"></div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
				<!-- Default panel contents -->
				<div class="panel-heading">Task</div>
				<div class="panel-body">
					<div id="taskGrid" style="height:400px;"></div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	var projects = <?php echo json_encode($allProjects);?>;
</script>