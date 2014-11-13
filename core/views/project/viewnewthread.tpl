<div class="content-main">
	<h1>Messages</h1>
	<div class="breadcrumb-wrap">
		<ol class="breadcrumb">
			<li><a href="<?php echo $this->createUrl('/project');?>">My Project</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id);?>"><?php echo App::Tools()->sanitize_text($projInfo['name']);?></a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages')?>">Messages</a></li>
			<li class="active">New Thread</li>
		</ol>
	</div>
	<div class="item-menu">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id)?>">Main</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/members')?>">Members</a></li>
			<li class="active"><a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages')?>">Messages</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/activity')?>">Activity</a></li>
			<li><a href="<?php echo $this->createUrl('/project/view/'.$id.'/invite')?>">Invite</a></li>
		</ul>
	</div>
	<div class="panel panel-default item-display">
		<div class="panel-body">
			<?php if($error_message):?>
				<div class="alert alert-info" role="alert"><?php echo $error_message;?></div>
			<?php endif;?>
			<p class="text-right">
				<a href="<?php echo $this->createUrl('/project/view/'.$id.'/messages')?>" class="btn btn-primary btn-lg">Thread List</a>
			</p>
			<form method="post" class="form-horizontal" role="form">
			  <div class="form-group">
				<label for="subject" class="col-sm-2 control-label">Subject</label>
				<div class="col-sm-10">
				  <input type="text" class="form-control" id="subject" name="subject">
				</div>
			  </div>
			  <div class="form-group">
				<label for="message" class="col-sm-2 control-label">Message</label>
				<div class="col-sm-10">
				  <textarea id="message" name="message" class="form-control" rows="3"></textarea>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="hidden" value="do_thread" name="action_post"/>
					<p class="text-right reset">
						<button type="submit" class="btn btn-primary">Create Thread</button>
					</p>
				</div>
			  </div>
			</form>
		</div>
	</div>
</div>