<?php $status_messages = [
    0 => 'Webcam permission denied by system, but the user is online',
    1 => 'Webcam permission denied by system and the user is offline',
    2 => 'Webcam is closed, but the user is online',
    3 => 'Webcam is closed and the user is offline',
    4 => 'Webcam is live and the user is online',
    5 => 'Webcam is live, but the user is offline',
    6 => 'User sign off',
    7 => 'User is inactive for a while',
    8 => 'User is active now'
]; ?>
<div class="content-wrapper notificaion_style">
    <section class="content">
        <div class="list_area container">
            <h3><?php echo 'Notification' ?>
            </h3>
            <div class="container mt-50">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs admin">
                                <li>
                                    <a class="<?php if (isset($navbar) && $navbar == 'webcam') echo 'active'; ?>"
                                        href="<?php echo base_url('admin/notification/webcam') ?>">
                                        <i class="fa fa-camera"></i>
                                        <span class="hidden-xs"><?php echo "Webcam" ?></span>
                                    </a>
                                </li>

                                <li>
                                    <a class="<?php if (isset($navbar) && $navbar == 'desktop') echo 'active'; ?>"
                                        href="<?php echo base_url('admin/notification/desktop') ?>">
                                        <i class="fa fa-desktop"></i>
                                        <span class="hidden-xs"><?php echo "Desktop" ?></span>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>
                    <!-- <div class="col-sm-6">
                        <div class="row">
                            <div class="form-group col-lg-2  my-3">
                            </div>
                            <div class="form-group col-lg-6  my-3">
                                <div class="input-group">
                                    <input type="text" class="search-input form-control" placeholder="Search employees...">
                                </div>
                            </div>
                            <div class="form-group col-lg-4 my-3">
                                <div class="input-group">
                                    <select class="form-control single_select" id="sortSelect">
                                        <option value="employeeName">sort by</option>
                                        <option value="active">Active Hours</option>
                                        <option value="inactive">Inactive Hours</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <!-- <div class="row m-5 mt-20">
                        <div class="col-md-8 box"> -->
            <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive p-0 ">
                <table class="table table-hover cushover mt-0 <?php if (count($notifications) > 10) {
                                                                            echo "datatable";
                                                                        } ?>" id="dg_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo trans('avatar') ?></th>
                            <th><?php echo trans('user') ?></th>
                            <th><?php echo trans('status') ?></th>
                            <th><?php echo trans('action') ?></th>
                        </tr>
                    </thead>
                    </thead>
                    <tbody>
                        <?php if (empty($notifications)): ?>
                            <tr>
                                <td>notification not found</td>
                            </tr>
                        <?php else: ?>
                            <?php $i = 1;
                            foreach ($notifications as $notification): ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td> <img src="<?php echo base_url("assets/images/avatar.png") ?>" style="border-radius: 50px; height: 50px; width: 50px;">
                                    </td>
                                    <td>
                                        <p class="mb-0"><?php echo $notification['employee_name']; ?></p>
                                        <p class="mb-0 text-muted">Message: <?php echo $notification['description']; ?></p>
                                    </td>
                                    <td>
                                        <?php if (in_array($notification['status'], [0, 1, 2, 4, 6, 7])): ?>
                                            <span class="status online">ONLINE</span>
                                        <?php else: ?>
                                            <span class="status offline">OFFLINE</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (in_array($notification['status'], [0, 1, 2, 3, 5, 6, 7])): ?>
                                            <?php if ($can_edit): ?>
                                                <button class="send-email btn btn-default btn-sm rounded" data-id="<?php echo $notification['employee_id']; ?>" data-name="<?php echo $notification['employee_name']; ?>" data-email="<?php echo $notification['email']; ?>" data-description="<?php echo $notification['description']; ?>" style="margin-top:5px;"><i class="fa fa-envelope-o"></i> Send Email</button>
                                            <?php else: ?>
                                                <button class="btn btn-default rounded" data-toggle="tooltip" data-placement="top" title="permission denied to send mail" style="margin-top:5px;">Send Email</button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                            <?php $i++;
                            endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- </div>
                    </div> -->
        </div>
    </section>
</div>
<script>
    $(document).on('click', '.send-email', function() {
        const $btn = $(this);
        const employeeId = $btn.data('id');
        const employeeName = $btn.data('name');
        const employeeEmail = $btn.data('email');
        const description = $btn.data('description');
        console.log(employeeEmail);


        // Disable button and add loader
        $btn.prop('disabled', true);
        const originalText = $btn.html();
        $btn.html('<i class="fa fa-envelope-o"></i> Sending.....');

        $.ajax({
            url: "<?= base_url('admin/Notification/send_alert_mail') ?>",
            type: "POST",
            data: {
                employee_id: employeeId,
                employee_name: employeeName,
                employee_email: employeeEmail,
                message: description
            },
            success: function(response) {
                swal("Success", "Alert email sent successfully!", "success");
            },
            error: function(xhr, status, error) {
                swal("Error", "Failed to send alert email.", "error");
            },
            complete: function() {
                // Restore original button state
                $btn.html(originalText);
                $btn.prop('disabled', false);
            }
        });
    });
</script>