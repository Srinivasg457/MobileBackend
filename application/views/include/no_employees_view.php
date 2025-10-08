<div class="content-wrapper">

    <section class="content">
        <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-10 col-md-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <?php if ($no_active_package == true): ?>
                        <div class="card-body text-center p-5 m-5">
                            <!-- Icon -->
                            <div class="mb-4">
                                <i class="fa fa-lock text-secondary" style="font-size: 50px;"></i>
                            </div>

                            <!-- Message -->
                            <h4 class="mb-4">No Upgrade Plan</h4>
                            <p class="text-muted">
                                Upgrade plan is currently disabled. Please contact your administrator for details.
                            </p>

                            <!-- CTA Button -->
                            <a href="<?php echo base_url('admin/subscription/current_plan') ?>" class="btn btn-default btn-sm rounded">
                                <i class="fa fa-file-text-o me-3"></i> View Plan
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="card-body text-center p-5 m-5">
                            <!-- Icon -->
                            <div class="mb-4">
                                <i class="fa fa-users text-secondary" style="font-size: 50px;"></i>
                            </div>

                            <!-- Message -->
                            <h4 class="mb-4">No Employees Added Yet</h4>
                            <p class="text-muted">
                                Your organization hasn’t added any employees.
                                Start by adding your first employee to begin tracking.
                            </p>

                            <!-- CTA Button -->
                            <a href="<?php echo base_url('admin/hrm/employees') ?>" class="btn btn-info mt-3 px-4">
                                <i class="fa fa-user-plus me-2"></i> Add Employee
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>