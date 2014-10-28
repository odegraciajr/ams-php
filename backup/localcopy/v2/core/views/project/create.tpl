<div class="content-main">
	<h1>Project</h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('organization');?>">My Project</a></li>
			<li class="active">Create New</li>
		</ol>
	</div>
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-body">
			<form method="post" class="form-horizontal" role="form">
			  <div class="form-group">
				<label for="name" class="col-sm-2 control-label">Name</label>
				<div class="col-sm-10">
				  <input type="text" class="form-control" id="name" name="name" placeholder="Project Name">
				</div>
			  </div>
			  <div class="form-group">
				<label for="description" class="col-sm-2 control-label">Description</label>
				<div class="col-sm-10">
				  <textarea id="description" name="description" class="form-control" rows="3"></textarea>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="hidden" value="do_project" name="action_post"/>
					<p class="text-right reset">
						<button type="submit" class="btn btn-primary">Create Project</button>
					</p>
				</div>
			  </div>
			</form>
		</div>
	</div>
</div>