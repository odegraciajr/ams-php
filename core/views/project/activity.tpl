<div id="messages" class="content-main">
	<h1>Messages</h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('/project');?>">My Project</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id);?>"><?php echo App::Tools()->sanitize_text($projInfo['name']);?></a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages')?>">Messages</a></li>
			<li class="active"></li>
		</ol>
	</div>
	<div class="item-menu">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id)?>">Main</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/members')?>">Members</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages')?>">Messages</a></li>
			<li  class="active"><a href="<?php echo $this->createUrl('/project/view/'.$id.'/activity')?>">Activity</a></li>
			<?php if($userRole >= 8):?>
				<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/invite')?>">Invite</a></li>
			<?php endif;?>
		</ul>
	</div>
	<div class="panel panel-default item-display">
		<div class="panel-body">
			<p class="text-right">
				<a href="<?php echo $this->createUrl('/project/view/'.$id.'/activity')?>" class="btn btn-primary btn-lg">Activity List</a>
			</p>
			<div class="main-activty">
				<form role="form" class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Name</label>
						<div class="col-sm-8">
							<p class="form-control-static"><?php echo App::Tools()->sanitize_text($activity['name']);?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Description</label>
						<div class="col-sm-8">
							<p class="form-control-static"><?php echo App::Tools()->sanitize_text($activity['description']);?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Type</label>
						<div class="col-sm-8">
							<p class="form-control-static"><?php echo App::Tools()->sanitize_text($activity['type_name']);?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Owner</label>
						<div class="col-sm-8">
							<p class="form-control-static">
								<a href="<?php echo $this->getProfileURL($activity['owner_id']);?>">
									<?php echo App::Tools()->sanitize_text($activity['owner_name']);?>
								</a>
							</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Requestor</label>
						<div class="col-sm-8">
							<p class="form-control-static">
								<a href="<?php echo $this->getProfileURL($activity['requestor']);?>">
									<?php echo App::Tools()->sanitize_text($activity['requestor_name']);?>
								</a>
							</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Request date</label>
						<div class="col-sm-8">
							<p class="form-control-static"><?php echo App::Tools()->sanitize_text(date("F j, Y",strtotime($activity['request_date'])));?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Due date</label>
						<div class="col-sm-8">
							<p class="form-control-static"><?php echo App::Tools()->sanitize_text(date("F j, Y",strtotime($activity['due_date'])));?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Due time</label>
						<div class="col-sm-8">
							<p class="form-control-static"><?php echo App::Tools()->sanitize_text(date("g:i A",strtotime($activity['due_time'])));?></p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Estimate duration</label>
						<div class="col-sm-8">
							<p class="form-control-static"><?php echo App::Tools()->sanitize_text(date("H:m",strtotime($activity['estimate_duration'])));?> Hours</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Parent activity</label>
						<div class="col-sm-8">
							<p class="form-control-static">
								<?php echo App::Tools()->sanitize_text($activity['parent_activity']);?>
							</p>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-left">Assinged users</label>
						<div class="col-sm-8">
							<div class="form-control-static assigned_users_list">
								<?php echo $assignedUsers;?>
							</div>
						</div>
					</div>
					
				</form>
				<br/>
				<br/>
				<br/>
				<h3>Prerequisite Activities</h3>
				<div class="list-activity">
					<div class="list-wrap">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Status</th>
									<th>Activity</th>
									<th>Due Date</th>
									<th>Due Time</th>
									<th>Owner</th>
									<th>Requestor</th>
									<th>Request Date</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php if(is_array($prereqActivities) && count($prereqActivities)>0):?>
									<?php foreach( $prereqActivities as $act ):?>
										<tr>
											<td>
												<?php
													if($act['status']==1){
														echo '<span class="label label-primary">Active</span>';
													}
													else if($act['status']==2){
														echo '<span class="label label-success">Completed</span>';
													}
													else if($act['status']==0){
														echo '<span class="label label-default">Pending</span>';
													}
													else{
														echo '<span class="label label-danger">Disabled</span>';
													}
												?>
											</td>
											<td><?php echo App::Tools()->sanitize_text($act['name']);?></td>
											<td><?php echo App::Tools()->sanitize_text(date("d/m/Y",strtotime($act['due_date'])));?></td>
											<td><?php echo App::Tools()->sanitize_text(date("g:i A",strtotime($act['due_time'])));?></td>
											<td>
												<a href="<?php echo $this->getProfileURL($act['owner_id']);?>">
													<?php echo App::Tools()->sanitize_text($act['owner_name']);?>
												</a>
											</td>
											<td>
												<a href="<?php echo $this->getProfileURL($act['requestor']);?>">
													<?php echo App::Tools()->sanitize_text($act['requestor_name']);?>
												</a>
											</td>
											<td><?php echo App::Tools()->sanitize_text(date("d/m/Y",strtotime($act['request_date'])));?></td>
											<td><a class="btn btn-primary btn-xs" href="<?php echo $this->createUrl('/project/activity/'.$id.'/'.$act['id'])?>">View</a></td>
										</tr>
									<?php endforeach;?>
								<?php else:?>
									<tr>
										<td colspan="7">No results</td>
									</tr>
								<?php endif;?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>