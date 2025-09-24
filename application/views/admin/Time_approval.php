<div class="content-wrapper employee_timeRequest Time_Approval">
    <section class="content">

        <h3><?php echo 'Time Approval'; ?>
            <?php if (isset($is_request_page) && $is_request_page) : ?>
                <a href="<?php echo base_url('admin/time_approval/history') ?>" class="pull-right btn btn-info btn-sm rounded upload mx-5">
                    <i class="fa fa-history"></i> History</a>
            <?php else : ?>
                <a href="<?php echo base_url('admin/time_approval') ?>" class="pull-right btn btn-default btn-sm rounded upload mx-5">
                    <i class="fa fa-angle-left"></i> Back</a>
            <?php endif; ?>
        </h3>

        <!-- <?php print_r($time_cards) ?> -->

        <!-- Employee Requests Table -->
        <?php if (isset($is_request_page) && $is_request_page) : ?>
            <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
                <table class="table table-hover cushover mt-0 <?php if (count($time_cards) > 10) {
                                                                    echo "datatable";
                                                                } ?>" id="dg_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo trans('image') ?></th>
                            <th>Employee Name</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Time Range</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($time_cards)) : ?>
                            <?php $i = 1; ?>
                            <?php foreach ($time_cards as $req) : ?>
                                <tr id="row_<?php echo html_escape($req['manual_id']); ?>">
                                    <td><?= $i++ ?></td>
                                    <td>
                                        <?php if (!empty($employee->thumb)) : ?>
                                            <img src="<?php echo base_url($employee->thumb) ?>" style="border-radius: 50px; height: 50px; width: 50px;">
                                        <?php else : ?>
                                            <img src="<?php echo base_url("assets/images/avatar.png") ?>" style="border-radius: 50px; height: 50px; width: 50px;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $req['employee_name'] ?></td>
                                    <td><?= $req['is_meeting'] == 1 ? 'Meeting' : 'Manual' ?></td>
                                    <td><?= $req['reason'] ?></td>
                                    <td><?= substr($req['timestamp_start'], 0, 5) ?> → <?= substr($req['timestamp_end'], 0, 5) ?></td>
                                    <td><?= $req['date_added'] ?></td>
                                    <td>
                                        <?php if ($req['approved'] == -1) : ?>
                                            <span class="status declined">Declined</span>
                                        <?php elseif ($req['approved'] == 1) : ?>
                                            <span class="status approved">Approved</span>
                                        <?php elseif ($req['approved'] == 0) : ?>
                                            <span class="status pending">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="actions view-settings text-center" width="15%">
                                        <?php if ($req['approved'] == -1) : ?>

                                        <?php elseif ($req['approved'] == 1) : ?>
                                            <span class="status approved">Approved</span>
                                        <?php elseif ($req['approved'] == 0) : ?>
                                            <?php if ($can_edit): ?>
                                                <a
                                                    data-val="Approve"
                                                    data-id="<?php echo $req['manual_id']; ?>"
                                                    data-user-id="<?php echo $req['user_id']; ?>"
                                                    href="<?php echo base_url(
                                                                'admin/Timecards_manual/approve_timecard/' .
                                                                    html_escape($req['manual_id']) . '/' .
                                                                    html_escape($req['user_id']) . '/' .
                                                                    rawurlencode(html_escape($req['employee_name'])) . '/' .
                                                                    rawurlencode(html_escape($req['email'])) . '/' .
                                                                    rawurlencode(html_escape($req['reason'] ?? 'NA')) // Ensure it’s not missing
                                                            ); ?>"
                                                    class="on-default remove-row approve_request_item"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Approve"
                                                    data-original-title="Approve">
                                                    <i class="fa fa-check text-success"></i>
                                                </a>


                                                <a
                                                    data-val="decline"
                                                    data-id="<?php echo $req['manual_id']; ?>"
                                                    data-user-id="<?php echo $req['user_id']; ?>"
                                                    href="<?php echo base_url(
                                                                'admin/Timecards_manual/decline_timecard/' .
                                                                    html_escape($req['manual_id']) . '/' .
                                                                    html_escape($req['user_id']) . '/' .
                                                                    rawurlencode(html_escape($req['employee_name'])) . '/' .
                                                                    rawurlencode(html_escape($req['email'])) . '/' .
                                                                    rawurlencode(html_escape($req['reason'] ?? 'NA')) // Ensure it’s not missing
                                                            ); ?>" class="on-default remove-row decline_request_item"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Decline"
                                                    data-original-title="Decline">
                                                    <i class="fa fa-times text-danger"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="#"
                                                    class="on-default edit-row"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Permission denied"
                                                    data-original-title="Approve">
                                                    <i class="fa fa-check text-success"></i>
                                                </a>

                                                <a href="#"
                                                    class="on-default"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Permission denied"
                                                    data-original-title="Decline">
                                                    <i class="fa fa-times text-danger"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="9" class="text-center">No requests found</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
                <table class="table table-hover cushover mt-0 <?php if (count($time_cards) > 10) {
                                                                    echo "datatable";
                                                                } ?>" id="dg_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo trans('image') ?></th>
                            <th>Employee Name</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Time Range</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($time_cards)) : ?>
                            <?php $i = 1; ?>
                            <?php foreach ($time_cards as $req) : ?>
                                <tr id="row_<?php echo html_escape($req['manual_id']); ?>">
                                    <td><?= $i++ ?></td>
                                    <td>
                                        <?php if (!empty($employee->thumb)) : ?>
                                            <img src="<?php echo base_url($employee->thumb) ?>" style="border-radius: 50px; height: 50px; width: 50px;">
                                        <?php else : ?>
                                            <img src="<?php echo base_url("assets/images/avatar.png") ?>" style="border-radius: 50px; height: 50px; width: 50px;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $req['employee_name'] ?></td>
                                    <td><?= $req['is_meeting'] == 1 ? 'Meeting' : 'Manual' ?></td>
                                    <td><?= $req['reason'] ?></td>
                                    <td><?= substr($req['timestamp_start'], 0, 5) ?> → <?= substr($req['timestamp_end'], 0, 5) ?></td>
                                    <td><?= $req['date_added'] ?></td>
                                    <td>
                                        <?php if ($req['approved'] == -1) : ?>
                                            <span class="status declined">Declined</span>
                                        <?php elseif ($req['approved'] == 1) : ?>
                                            <span class="status approved">Approved</span>
                                        <?php elseif ($req['approved'] == 0) : ?>
                                            <span class="status pending">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="9" class="text-center">No requests found</td>
                            </tr>
                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>