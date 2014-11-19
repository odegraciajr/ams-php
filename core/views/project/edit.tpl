<div class="content-main">
	<h1>Edit Project</h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('project');?>">My Project</a></li>
			<li><a href="<?php echo $this->createUrl('project/view/'.$projInfo['id']);?>"><?php echo App::Tools()->sanitize_text($projInfo['name']);?></a></li>
			<li class="active">Edit Project</li>
		</ol>
	</div>
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-body">
			<?php $this->errorMessage($error_message,'info',$error_message);?>
			<p class="text-right">
				<a href="<?php echo $this->createUrl('project/view/'.$projInfo['id'])?>" class="btn btn-warning btn-lg">Cancel</a>
			</p>
			<form method="post" class="form-horizontal" role="form">
			  <div class="form-group">
				<label for="name" class="col-sm-2 control-label">Name</label>
				<div class="col-sm-10">
				  <input value="<?php echo App::Tools()->sanitize_text($projInfo['name']);?>" type="text" class="form-control" id="name" name="name" placeholder="Project Name">
				</div>
			  </div>
			  <div class="form-group">
				<label for="description" class="col-sm-2 control-label">Description</label>
				<div class="col-sm-10">
				  <textarea id="description" name="description" class="form-control" rows="3"><?php echo App::Tools()->sanitize_text($projInfo['description']);?></textarea>
				</div>
			  </div>
			  <div class="form-group">
				<label for="organization" class="col-sm-2 control-label">Organization</label>
				<div class="col-sm-10">
					<select class="form-control" name="organization" id="organization">
						<option value="0">None</option>
						<?php if(is_array($myOrg) && count($myOrg)> 0):?>
							<?php foreach( $myOrg as $org ):?>
								<option <?php App::Tools()->setActiveNav($org['id'],$projInfo['organization_id'],true,'selected="selected"');?> value="<?php echo $org['id'];?>"><?php echo $org['name'];?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="hidden" value="do_update_project" name="action_post"/>
					<p class="text-right reset">
						<button type="submit" class="btn btn-primary">Update Project</button>
					</p>
				</div>
			  </div>
			</form>
		</div>
	</div>
</div>