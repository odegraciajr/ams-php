<div id="messages" class="content-main">
	<h1>Edit Activity</h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('/project');?>">My Project</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id);?>"><?php echo App::Tools()->sanitize_text($projInfo['name']);?></a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/activity');?>">Activity</a></li>
			<li><a href="<?php echo $this->createUrl('/project/activity/'.$id.'/'.$activity['id']);?>"><?php echo App::Tools()->sanitize_text($activity['name']);?></a></li>
			<li>Edit Activity</li>
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
			<?php $this->errorMessage($error_message,'info',$error_message);?>
			<p class="text-right">
				<a href="<?php echo $this->createUrl('/project/view/'.$id.'/activity')?>" class="btn btn-primary btn-lg">Activity List</a>
				<a href="<?php echo $this->createUrl('/project/activity/'.$id.'/'.$activity['id'])?>" class="btn btn-warning btn-lg">Cancel</a>
			</p>
			<div class="main-activty">
				<form method="post" class="form-horizontal" role="form">
				  <div class="form-group">
					<label for="name" class="col-sm-2 control-label">Name</label>
					<div class="col-sm-10">
					  <input type="text" value="<?php echo App::Tools()->sanitize_text($activity['name']);?>" class="form-control" id="name" name="name">
					</div>
				  </div>
				  <div class="form-group">
					<label for="description" class="col-sm-2 control-label">Description</label>
					<div class="col-sm-10">
					  <textarea id="description" name="description" class="form-control" rows="3"><?php echo App::Tools()->sanitize_text($activity['description']);?></textarea>
					</div>
				  </div>
				  <div class="form-group">
					<label for="request_date_dummy" class="col-sm-2 control-label">Date of Request</label>
					<div class="col-sm-10">
						<div class='input-group date'>
							<input value="<?php echo App::Tools()->sanitize_text(date("m/d/Y",strtotime($activity['request_date'])));?>" id="request_date_dummy" type='text' class="form-control" data-date-format="MM/DD/YYYY"/>
							<input value="<?php echo App::Tools()->sanitize_text(date("Y-m-d 00:00:00",strtotime($activity['request_date'])));?>" name="request_date" id="request_date" type='hidden'/>
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				  </div>
				  <div class="form-group">
					<label for="requestor" class="col-sm-2 control-label">Requestor</label>
					<div class="col-sm-10">
						<select class="form-control" name="requestor" id="requestor">
							<?php if(is_array($projMembers) && count($projMembers)> 0):?>
								<?php foreach( $projMembers as $proj ):?>
									<option <?php App::Tools()->setActiveNav($proj['user_id'],$activity['requestor'],true,'selected="selected"');?> value="<?php echo $proj['user_id'];?>"><?php echo $proj['full_name'];?></option>
								<?php endforeach;?>
							<?php endif;?>

						</select>
					</div>
				  </div>
				  <div class="form-group">
					<label for="type_id" class="col-sm-2 control-label">Type of Activity</label>
					<div class="col-sm-10">
						<select class="form-control" name="type_id" id="type_id">
							<?php if(is_array($activity_types) && count($activity_types)> 0):?>
								<?php foreach( $activity_types as $type ):?>
									<option <?php App::Tools()->setActiveNav($type['id'],$activity['type_id'],true,'selected="selected"');?> value="<?php echo $type['id'];?>"><?php echo $type['name'];?></option>
								<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				  </div>
				  <div class="form-group">
					<label for="parent_activity" class="col-sm-2 control-label">Parent Activity</label>
					<div class="col-sm-10">
						<select class="form-control" name="parent_activity" id="parent_activity">
							<option value="0">None</option>
							<?php if(is_array($activities) && count($activities)> 0):?>
								<?php foreach( $activities as $act ):?>
									<?php if($act['id']!=$act_id):?>
										<option <?php App::Tools()->setActiveNav($act['id'],$activity['parent_activity'],true,'selected="selected"');?> value="<?php echo $act['id'];?>"><?php echo $act['name'];?></option>
									<?php endif;?>
								<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				  </div>
				  <div class="form-group">
					<label for="wbs" class="col-sm-2 control-label">WBS Code</label>
					<div class="col-sm-10">
					  <input value="<?php echo App::Tools()->sanitize_text($activity['wbs']);?>" type="text" class="form-control" id="wbs" name="wbs">
					</div>
				  </div>
				  <div class="form-group">
					<label for="start_date_dummy" class="col-sm-2 control-label">Start date</label>
					<div class="col-sm-10">
						<div class='input-group date'>
							<input value="<?php echo App::Tools()->sanitize_text(date("m/d/Y",strtotime($activity['start_date'])));?>" id="start_date_dummy" type='text' class="form-control" data-date-format="MM/DD/YYYY"/>
							<input value="<?php echo App::Tools()->sanitize_text(date("Y-m-d 00:00:00",strtotime($activity['start_date'])));?>" name="start_date" id="start_date" type='hidden'/>
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				  </div>
				  <div class="form-group">
					<label for="start_time_dummy" class="col-sm-2 control-label">Start time</label>
					<div class="col-sm-10">
						<div class='input-group date'>
							<input value="<?php echo App::Tools()->sanitize_text(date("g:i A",strtotime($activity['start_time'])));?>" id="start_time_dummy" type='text' class="form-control" />
							<input value="<?php echo App::Tools()->sanitize_text(date("Y-m-d H:m:s",strtotime($activity['start_time'])));?>" name="start_time" id="start_time" type='hidden'/>
							<span id="get_start_time" class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
							</span>
						</div>
					</div>
				  </div>
				  <div class="form-group">
					<label for="due_date_dummy" class="col-sm-2 control-label">Due date</label>
					<div class="col-sm-10">
						<div class='input-group date'>
							<input value="<?php echo App::Tools()->sanitize_text(date("m/d/Y",strtotime($activity['due_date'])));?>" id="due_date_dummy" type='text' class="form-control" data-date-format="MM/DD/YYYY"/>
							<input value="<?php echo App::Tools()->sanitize_text(date("Y-m-d 00:00:00",strtotime($activity['due_date'])));?>" name="due_date" id="due_date" type='hidden'/>
							<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				  </div>
				  <div class="form-group">
					<label for="due_time_dummy" class="col-sm-2 control-label">Due time</label>
					<div class="col-sm-10">
						<div class='input-group date'>
							<input value="<?php echo App::Tools()->sanitize_text(date("g:i A",strtotime($activity['due_time'])));?>" id="due_time_dummy" type='text' class="form-control" />
							<input value="<?php echo App::Tools()->sanitize_text(date("Y-m-d H:m:s",strtotime($activity['due_time'])));?>" name="due_time" id="due_time" type='hidden'/>
							<span id="get_due_time" class="input-group-addon"><span class="glyphicon glyphicon-time"></span>
							</span>
						</div>
					</div>
				  </div>
				<div class="form-group">
					<?php
						$e_duration = $this->loadModel('ActivityModel')->estimateDurationToArr($activity['estimate_duration']);
					?>
					<label for="estimate_duration_dummy" class="col-sm-2 control-label">Estimated duration</label>
					<div class="col-sm-2">
						<div class='input-group'>
							<input value="<?php echo $e_duration['days'];?>" maxlength="2" min="0" max="99" name="estimate_duration[days]" placeholder="0" type="number" class="form-control" />
							<span class="input-group-addon">Days</span>
						</div>
					</div>
					<div class="col-sm-2">
						<div class='input-group'>
							<input value="<?php echo $e_duration['hours'];?>" maxlength="2" min="0" max="23" name="estimate_duration[hours]" placeholder="0" type="number" class="form-control" />
							<span class="input-group-addon">Hours</span>
						</div>
					</div>
					<div class="col-sm-2">
						<div class='input-group'>
							<input value="<?php echo $e_duration['mins'];?>" maxlength="2" min="0" max="59" name="estimate_duration[minutes]" placeholder="0" type="number" class="form-control" />
							<span class="input-group-addon">Mins</span>
						</div>
					</div>
				</div>
				  <div class="form-group">
					<label for="assign_user" class="col-sm-2 control-label">Assign User</label>
						<div class="col-sm-10">
							<div class="input-group">
								<select class="form-control" id="assign_user_list">
									<option value="0">None</option>
									<?php if(is_array($projMembers) && count($projMembers)> 0):?>
										<?php foreach( $projMembers as $proj ):?>
											<option value="<?php echo $proj['user_id'];?>"><?php echo $proj['full_name'];?></option>
										<?php endforeach;?>
									<?php endif;?>
								</select>
								<div class="input-group-btn">
								<button tabindex="-1" class="btn btn-primary" id="add_to_assign_user" type="button">Assign</button>
								</div>
							</div>
							<div id="assigned_users">
								<ul>
									<?php if(is_array($activityAssignment) && count($activityAssignment)> 0):?>
										<?php foreach( $activityAssignment as $act_ass ):?>
											<li>
												<span class="name"><?php echo $act_ass['full_name'];?></span>
												<button class="btn btn-danger btn-xs remove_assigned_user" type="button">&times;</button>
												<input type="hidden" value="<?php echo $act_ass['user_id'];?>" name="assigned_user[]">
											</li>
										<?php endforeach;?>
									<?php endif;?>
								</ul>	
							</div>
						</div>
				  </div>
				  <div class="form-group">
					<label for="assign_user" class="col-sm-2 control-label">Prerequisites</label>
						<div class="col-sm-10">
							<div class="input-group">
								<select class="form-control" id="prerequisites_list">
								<option value="0">None</option>
									<?php if(is_array($activities) && count($activities)> 0):?>
										<?php foreach( $activities as $act ):?>
											<?php if( $act['id'] != $act_id ):?>
												<option value="<?php echo $act['id'];?>"><?php echo $act['name'];?></option>
											<?php endif;?>
										<?php endforeach;?>
									<?php endif;?>
								</select>
								<div class="input-group-btn">
								<button tabindex="-1" class="btn btn-primary" id="add_prerequisites_activity" type="button">Add</button>
								</div>
							</div>
							<div id="prerequisites_activity">
								<ul>
									<?php if(is_array($activityPredecessor) && count($activityPredecessor)> 0):?>
										<?php foreach( $activityPredecessor as $pred ):?>
											<li>
												<span class="name"><?php echo $pred['name'];?></span>
												<button class="btn btn-danger btn-xs remove_assigned_user" type="button">&times;</button>
												<input type="hidden" value="<?php echo $pred['predecessor_id'];?>" name="prereq_act[]">
											</li>
										<?php endforeach;?>
									<?php endif;?>
								</ul>	
							</div>
						</div>
				  </div>
				  <div class="form-group">
					<label for="comment" class="col-sm-2 control-label">Additional Comment</label>
					<div class="col-sm-10">
					  <textarea id="comment" name="comment" class="form-control" rows="3"><?php echo App::Tools()->sanitize_text($activity['comment']);?></textarea>
					</div>
				  </div>
				  <div class="form-group">
					<label for="Priority" class="col-sm-2 control-label">Priority</label>
					<div class="col-sm-10">
						<select class="form-control" name="priority" id="priority">
							<?php if(is_array($activityPriority) && count($activityPriority)> 0):?>
								<?php foreach( $activityPriority as $key=>$value ):?>
									<option <?php App::Tools()->setActiveNav($key,$activity['priority'],true,'selected="selected"');?> value="<?php echo $key;?>"><?php echo $value;?></option>
								<?php endforeach;?>
							<?php endif;?>
						</select>
					</div>
				  </div>
				  <div class="form-group">
					<label for="status" class="col-sm-2 control-label">Status</label>
					<div class="col-sm-10">
						<select class="form-control" name="status" id="status">
							<?php if(is_array($activityStatus) && count($activityStatus)> 0):?>
								<?php foreach( $activityStatus as $key=>$value ):?>
									<option <?php App::Tools()->setActiveNav($key,$activity['status'],true,'selected="selected"');?> value="<?php echo $key;?>"><?php echo $value;?></option>
								<?php endforeach;?>
							<?php endif;?>
							
						</select>
					</div>
				  </div>
				  <div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<input type="hidden" value="do_updateactivity" name="action_post"/>
						<p class="text-right reset">
							<button type="submit" class="btn btn-primary">Update Activity</button>
						</p>
					</div>
				  </div>
				</form>
			</div>
		</div>
	</div>
</div>