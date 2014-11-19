<div class="content-main">
	<h1>Edit Organization</h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('organization');?>">My Organization</a></li>
			<li><a href="<?php echo $this->createUrl('organization/view/'.$orgInfo['id']);?>"><?php echo App::Tools()->sanitize_text($orgInfo['name']);?></a></li>
			<li class="active">Edit Organization</li>
		</ol>
	</div>
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-body">
			<?php $this->errorMessage($error_message,'info',$error_message);?>
			<p class="text-right">
				<a href="<?php echo $this->createUrl('organization/view/'.$orgInfo['id'])?>" class="btn btn-warning btn-lg">Cancel</a>
			</p>
			<form method="post" class="form-horizontal" role="form">
			  <div class="form-group">
				<label for="name" class="col-sm-2 control-label">Name</label>
				<div class="col-sm-10">
				  <input value="<?php echo App::Tools()->sanitize_text($orgInfo['name']);?>" type="text" class="form-control" id="name" name="name" placeholder="Oranization Name">
				</div>
			  </div>
			  <div class="form-group">
				<label for="description" class="col-sm-2 control-label">Description</label>
				<div class="col-sm-10">
				  <textarea id="description" name="description" class="form-control" rows="3"><?php echo App::Tools()->sanitize_text($orgInfo['description']);?></textarea>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="hidden" value="do_update_organization" name="action_post"/>
					<p class="text-right reset">
						<button type="submit" class="btn btn-primary">Update Organization</button>
					</p>
				</div>
			  </div>
			</form>
		</div>
	</div>
</div>