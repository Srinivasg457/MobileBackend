<?php $status_messages = [
    0 => 'Webcam permission denied by system, but the user is online',
    1 => 'Webcam permission denied by system and the user is offline',
    2 => 'Webcam is closed, but the user is online',
    3 => 'Webcam is closed and the user is offline',
    4 => 'Webcam is live and the user is online',
    5 => 'Webcam is live, but the user is offline',
    6 => 'User sign off',
    7 => 'User is inactive for a while',
    8 => 'User is active now',
    9 => 'User not yet logged into Workroom Application'
];
function time_ago($datetime, $status, $full = false)
{
    // If status is 8, return "just now" directly
    if (!in_array($status, [6,9])) {
        return ' just now ';
    }
    $now = new DateTime(get_user_datetime_only("")); // Convert to DateTime
    $ago = new DateTime($datetime);                   // Convert to DateTime

    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = [
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hr',
        'i' => 'min',
        's' => 'sec',
    ];
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

?>
<div class="content-wrapper notificaion_style">
    <section class="content">
        <div class="list_area container">
            <h3><?php echo 'Notification';?>
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
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive p-0 ">
                <table class="table table-hover cushover mt-0 <?php if (count($notifications) > 10) {
                                                                    echo "datatable";
                                                                } ?>" id="dg_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo trans('avatar') ?></th>
                            <th><?php echo 'Employee' ?></th>
                            <th class="text-center"><?php echo trans('status') ?></th>
                            <th class="text-center"><?php echo trans('action') ?></th>
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
                                        <p class="my-3"><?php echo $notification['employee_name']; ?>
                                            <span class="mb-0 text-muted"> (<?php echo time_ago($notification['created_at'], $notification['status']); ?>)</span>
                                        </p>
                                        <p class="mb-0 text-muted"><?php echo $notification['description']; ?></p>
                                    </td>
                                    <td class="text-center">
                                        <?php if (in_array($notification['status'], [0, 2, 4, 8])): ?>
                                            <span class="status online">ONLINE</span>
                                        <?php else: ?>
                                            <span class="status offline">OFFLINE</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if (in_array($notification['status'], [0, 1, 2, 3, 5, 6, 7, 9])): ?>
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