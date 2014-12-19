<?php
	$userWidgetData = $this->loadModel('WidgetModel')->getWidgetSettings();
	$userWidgets = isset($userWidgetData['userWidgets']) && !empty($userWidgetData['userWidgets']) && $userWidgetData['userWidgets'] != false ? $userWidgetData['userWidgets'] : false;
	
	
	//var_dump($userWidgets);die;
	
?>
<div class="content-main">
	<!--<div id="widget-new-design">
		<div role="tabpanel">
			<ul id="widget-nav-tabs" class="nav nav-tabs " role="tablist">
				<li class="active"><a href="#tab1" aria-controls="tab1" role="tab" data-widget-id="1" data-toggle="tab">Tab 1</a></li>
				<li><a href="#tab2" aria-controls="tab2" role="tab" data-widget-id="2" data-toggle="tab">Tab 2</a></li>
				<li>
					<a data-toggle="tooltip" data-placement="auto" title="Add new tab" href="#" aria-controls="profile" role="tab">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="tab1">
					<div class="panel panel-default item-display">
						<div class="panel-body">
							<div class="grid-controls text-right">
								<button type="button" data-widget-id="1" class="btn btn-primary reset-widget">Reset Widget</button>
							</div>
							<div id="gridstercontent-1" class="gridster"></div>
						</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="tab2">
					<div class="panel panel-default item-display">
						<div class="panel-body">
							<div class="grid-controls text-right">
								<button type="button" data-widget-id="2" class="btn btn-primary reset-widget">Reset Widget</button>
							</div>
							<div id="gridstercontent-2" class="gridster"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>-->
	<div id="widget-new-design">
		<div role="tabpanel">
			<!-- Nav tabs -->
			<ul id="widget-nav-tabs" class="nav nav-tabs " role="tablist">
				<?php
					if( $userWidgets ):
						foreach($userWidgets as $k=>$v):?>
							<?php $class= $k==0?"active":"";?>
							<li class="<?php echo $class;?>"><a href="#tab<?php echo $v['widget_id'];?>" aria-controls="tab<?php echo $v['widget_id'];?>" role="tab" data-widget-id="<?php echo $v['widget_id'];?>" data-toggle="tab"><?php echo $v['tab_name'];?></a></li>
						<?php endforeach;?>
					<?php else:?>
							<li class="active"><a href="#tab1" aria-controls="tab1" role="tab" data-widget-id="1" data-toggle="tab">Tab 1</a></li>
					<?php endif;?>
				<li>
					<a data-toggle="tooltip" data-placement="auto" id="widget-add-tab" title="Add new tab" href="#" role="tab">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
					</a>
				</li>
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<?php if( $userWidgets ):
					foreach($userWidgets as $k=>$v):?>
						<?php $class= $k==0?"active":"";?>
						<div role="tabpanel" class="tab-pane <?php echo $class;?>" id="tab<?php echo $v['widget_id'];?>">
							<div class="panel panel-default item-display">
								<div class="panel-body">
									<div class="grid-controls text-right">
										<button type="button" data-widget-id="<?php echo $v['widget_id'];?>" class="btn btn-primary reset-widget">Reset Widget</button>
									</div>
									<div data-tab-name="<?php echo $v['tab_name'];?>" data-widget-id="<?php echo $v['widget_id'];?>" id="gridstercontent-<?php echo $v['widget_id'];?>" class="gridster"></div>
								</div>
							</div>
						</div>
					<?php endforeach;?>
				<?php else:?>
						<div role="tabpanel" class="tab-pane active" id="tab1">
							<div class="panel panel-default item-display">
								<div class="panel-body">
									<div class="grid-controls text-right">
										<button type="button" data-widget-id="1" class="btn btn-primary reset-widget">Reset Widget</button>
									</div>
									<div data-widget-id="1" id="gridstercontent-1" class="gridster"></div>
								</div>
							</div>
						</div>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>