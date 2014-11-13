<div id="messages" class="content-main">
	<h1>Messages</h1>
	<?php if(isset($error_add_member)):?>
		<div class="alert alert-info" role="alert"><?php echo $error_add_member_message;?></div>
	<?php endif;?>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('/project');?>">My Project</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id);?>"><?php echo App::Tools()->sanitize_text($projInfo['name']);?></a></li>
			<li class="active">Activity</li>
		</ol>
	</div>
	<div class="item-menu">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id)?>">Main</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/members')?>">Members</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages')?>">Messages</a></li>
			<li class="active"><a href="<?php echo $this->createUrl('/project/view/'.$id.'/activity')?>">Activity</a></li>
			<?php if($userRole >= 8):?>
				<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/invite')?>">Invite</a></li>
			<?php endif;?>
		</ul>
	</div>
	<div class="panel panel-default item-display">
		<div class="panel-body">
			<?php if($userRole >= 8):?>
				<p class="text-right">
					<a href="<?php echo $this->createUrl('/project/view/'.$id.'/newactivity')?>" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-plus"></span> Create new activity</a>
				</p>
			<?php endif;?>
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
							</tr>
						</thead>
						<tbody>
							<?php if(is_array($activities) && count($activities)>0):?>
								<?php foreach( $activities as $act ):?>
									<tr>
										<td>
											<?php
												if($act['status']==1){
													echo '<span class="label label-primary">Active</span>';
												}
												else if($act['status']==2){
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
			<?php// var_dump($activities);die;?>
		</div>
	</div>
</div>