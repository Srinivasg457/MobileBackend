
<div class="content-headers" style="padding: 0 15px; margin-top: 70px;">
  <?php if (isset($page_title)): ?>
  	<div class="flex-parent-between" style="width:100%">
  		<div>
        <?php if ($page_title == 'User Dashboard'): ?>
          <h1>Dashboard</h1>
        <?php else: ?>
          <h1 class="pt-2"><?php echo $page_title; ?></h1>
        <?php endif ?>
  			
  		</div>

  		<div class="pt-sm">
        <?php if (check_payment_status() == FALSE || user()->user_type == 'trial'): ?>
  			  <a href="<?php echo base_url('admin/subscription/upgrade_plan') ?>" class="btn btn-info pull-right">
            <i class="fa fa-rocket"></i> <span><?php echo trans('upgrade') ?></span>
          </a>
        <?php endif ?>
  		</div>
  	</div>
  <?php endif ?>
</div>
