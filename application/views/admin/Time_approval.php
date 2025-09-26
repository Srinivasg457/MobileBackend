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
        <?php
        $type_labels = [
            1 => 'Manual Time',
            2 => 'Client Meeting',
            3 => 'Training',
            4 => 'On-site Work',
            5 => 'Other Offline Work',
            6 => 'Internet Issues'
        ]; ?>
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
                            <th>Name</th>
                            <th>Request Date</th>
                            <th>Reason</th>
                            <th>Created Date</th>
                            <th>Verification</th>
                            <th>Approval Status</th>
                            <th>View</th>
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
                                    <!-- <td><?= $req['is_meeting'] == 1 ? 'Meeting' : 'Manual' ?></td> -->
                                    <td>
                                        <p class="mb-0"><?php echo $req['date_added']; ?></p>
                                        <p class="mb-0 text-muted"><?php echo substr($req['timestamp_start'], 0, 5) ?> → <?= substr($req['timestamp_end'], 0, 5) ?></p>
                                    </td>
                                    <td title="<?= htmlspecialchars($req['reason'], ENT_QUOTES) ?>">
                                        <p class="mb-0"><?php echo isset($type_labels[$req['type']]) ? $type_labels[$req['type']] : 'Unknown'; ?> </p>

                                        <p class="mb-0 text-muted"> <?= strlen($req['reason']) > 20
                                                                        ? htmlspecialchars(mb_substr($req['reason'], 0, 20), ENT_QUOTES) . '…'
                                                                        : htmlspecialchars($req['reason'], ENT_QUOTES);
                                                                    ?> </p>
                                    </td>
                                    <td><?= date('Y-m-d', strtotime($req['created_at'])) ?></td>
                                    <td>
                                        <?php if ($req['verification_status'] == -1) : ?>
                                            <span class="validation_status validation_invalid">Invalid</span>
                                        <?php elseif ($req['verification_status'] == 1) : ?>
                                            <span class="validation_status validation_valid">Valid</span>
                                        <?php elseif ($req['verification_status'] == 0) : ?>
                                            <span class="validation_status validation_review">Review</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($req['approved'] == -1) : ?>
                                            <span class="status declined">Declined</span>
                                        <?php elseif ($req['approved'] == 1) : ?>
                                            <span class="status approved">Approved</span>
                                        <?php elseif ($req['approved'] == 0) : ?>
                                            <span class="status pending">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="#"
                                            class="view-request"
                                            data-employee="<?= htmlspecialchars($req['employee_name']) ?>"
                                            data-reason="<?= htmlspecialchars($req['reason']) ?>"
                                            data-type="<?= isset($type_labels[$req['type']]) ? $type_labels[$req['type']] : 'Unknown' ?>"
                                            data-range="<?= substr($req['timestamp_start'], 0, 5) ?> → <?= substr($req['timestamp_end'], 0, 5) ?>"
                                            data-date="<?= htmlspecialchars($req['date_added']) ?>"
                                            data-status="<?= $req['approved'] == 1 ? 'Approved' : ($req['approved'] == -1 ? 'Declined' : 'Pending') ?>"
                                            title="View Request">
                                            <i class="bi bi-eye-fill text-primary" style="font-size:1.5rem;"></i>
                                        </a>

                                    </td>
                                    <td class="actions view-settings text-center" width="15%">
                                        <?php if ($req['approved'] == -1) : ?>
                                            <a href="#"
                                                class="on-default edit-row"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="You can edit only pending requests"
                                                style="pointer-events: none; cursor: not-allowed; opacity: 0.6;">
                                                <i class="fa fa-check text-success"></i>
                                            </a>
                                            <a href="#"
                                                class="on-default edit-row"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="You can edit only pending requests"
                                                style="pointer-events: none; cursor: not-allowed; opacity: 0.6;">
                                                <i class="fa fa-times text-danger"></i>
                                            </a>
                                        <?php elseif ($req['approved'] == 1) : ?>
                                            <a href="#"
                                                class="on-default edit-row"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="You can edit only pending requests"
                                                style="pointer-events: none; cursor: not-allowed; opacity: 0.6;">
                                                <i class="fa fa-check text-success"></i>
                                            </a>
                                            <a href="#"
                                                class="on-default edit-row"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="You can edit only pending requests"
                                                style="pointer-events: none; cursor: not-allowed; opacity: 0.6;">
                                                <i class="fa fa-times text-danger"></i>
                                            </a> <?php elseif ($req['approved'] == 0) : ?>
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
                                                                    rawurlencode(html_escape($req['reason'] ?? 'NA')) . '/' .
                                                                    rawurlencode(html_escape($req['timestamp_start'])) . '/' .
                                                                    rawurlencode(html_escape($req['timestamp_end']))
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
                                                                    rawurlencode(html_escape($req['reason'] ?? 'NA')) . '/' .
                                                                    rawurlencode(html_escape($req['timestamp_start'])) . '/' .
                                                                    rawurlencode(html_escape($req['timestamp_end']))
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
                            <th>Name</th>
                            <!-- <th>Type</th> -->
                            <th>Request Date</th>
                            <th>Reason</th>
                            <th>Created Date</th>
                            <th>Verfication</th>
                            <th>Approval Status</th>
                            <th>View</th>
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
                                    <!-- <td><?= $req['is_meeting'] == 1 ? 'Meeting' : 'Manual' ?></td> -->
                                    <!-- <td><?= $req['reason'] ?></td> -->
                                    <td>
                                        <p class="mb-0"><?php echo $req['date_added']; ?></p>
                                        <p class="mb-0 text-muted"><?php echo substr($req['timestamp_start'], 0, 5) ?> → <?= substr($req['timestamp_end'], 0, 5) ?></p>
                                    </td>
                                    <td title="<?= htmlspecialchars($req['reason'], ENT_QUOTES) ?>">
                                        <p class="mb-0"> <?php echo isset($type_labels[$req['type']]) ? $type_labels[$req['type']] : 'Unknown'; ?> </p>

                                        <p class="mb-0 text-muted"> <?= strlen($req['reason']) > 20
                                                                        ? htmlspecialchars(mb_substr($req['reason'], 0, 20), ENT_QUOTES) . '…'
                                                                        : htmlspecialchars($req['reason'], ENT_QUOTES);
                                                                    ?> </p>
                                    </td>
                                    <td><?= date('Y-m-d', strtotime($req['created_at'])) ?></td>
                                    <td>
                                        <?php if ($req['verification_status'] == -1) : ?>
                                            <span class="validation_status validation_invalid">Invalid</span>
                                        <?php elseif ($req['verification_status'] == 1) : ?>
                                            <span class="validation_status validation_valid">Valid</span>
                                        <?php elseif ($req['verification_status'] == 0) : ?>
                                            <span class="validation_status validation_review">Review</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($req['approved'] == -1) : ?>
                                            <span class="status declined">Declined</span>
                                        <?php elseif ($req['approved'] == 1) : ?>
                                            <span class="status approved">Approved</span>
                                        <?php elseif ($req['approved'] == 0) : ?>
                                            <span class="status pending">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="#"
                                            class="view-request"
                                            data-employee="<?= htmlspecialchars($req['employee_name']) ?>"
                                            data-reason="<?= htmlspecialchars($req['reason']) ?>"
                                            data-type="<?= isset($type_labels[$req['type']]) ? $type_labels[$req['type']] : 'Unknown' ?>"
                                            data-range="<?= substr($req['timestamp_start'], 0, 5) ?> → <?= substr($req['timestamp_end'], 0, 5) ?>"
                                            data-date="<?= htmlspecialchars($req['date_added']) ?>"
                                            data-status="<?= $req['approved'] == 1 ? 'Approved' : ($req['approved'] == -1 ? 'Declined' : 'Pending') ?>"
                                            title="View Request">
                                            <i class="bi bi-eye-fill text-primary" style="font-size:1.5rem;"></i>
                                        </a>

                                    </td>
                                    <td class="actions view-settings text-center" width="15%">
                                        <?php if ($req['approved'] == -1) : ?>
                                            <a href="#"
                                                class="on-default edit-row"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="You can edit only pending requests"
                                                style="pointer-events: none; cursor: not-allowed; opacity: 0.6;">
                                                <i class="fa fa-check text-success"></i>
                                            </a>
                                            <a href="#"
                                                class="on-default edit-row"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="You can edit only pending requests"
                                                style="pointer-events: none; cursor: not-allowed; opacity: 0.6;">
                                                <i class="fa fa-times text-danger"></i>
                                            </a>
                                        <?php elseif ($req['approved'] == 1) : ?>
                                            <a href="#"
                                                class="on-default edit-row"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="You can edit only pending requests"
                                                style="pointer-events: none; cursor: not-allowed; opacity: 0.6;">
                                                <i class="fa fa-check text-success"></i>
                                            </a>
                                            <a href="#"
                                                class="on-default edit-row"
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="You can edit only pending requests"
                                                style="pointer-events: none; cursor: not-allowed; opacity: 0.6;">
                                                <i class="fa fa-times text-danger"></i>
                                            </a>
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
                                                                    rawurlencode(html_escape($req['reason'] ?? 'NA')) . '/' .
                                                                    rawurlencode(html_escape($req['timestamp_start'])) . '/' .
                                                                    rawurlencode(html_escape($req['timestamp_end']))
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
                                                                    rawurlencode(html_escape($req['reason'] ?? 'NA')) . '/' .
                                                                    rawurlencode(html_escape($req['timestamp_start'])) . '/' .
                                                                    rawurlencode(html_escape($req['timestamp_end']))
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
        <?php endif; ?>
        <!-- View Request Modal -->
        <div class="modal fade" id="viewRequestModal" tabindex="-1" aria-labelledby="viewRequestLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content" style="margin-top: 10% !important">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewRequestLabel">Request Details</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive p-0">
                            <table class="table table-bordered table-striped" style="table-layout: fixed; 
              width: 100%;
              word-break: break-word;
              white-space: pre-wrap;
              overflow-wrap: break-word;">
                                <tbody>
                                    <tr>
                                        <th>Employee</th>
                                        <td id="vr_employee"></td>
                                    </tr>
                                    <tr>
                                        <th>Type</th>
                                        <td id="vr_type"></td>
                                    </tr>
                                    <tr>
                                        <th>Reason</th>
                                        <td id="vr_reason"></td>
                                    </tr>
                                    <tr>
                                        <th>Time Range</th>
                                        <td id="vr_range"></td>
                                    </tr>
                                    <tr>
                                        <th>Date</th>
                                        <td id="vr_date"></td>
                                    </tr>
                                    <tr>
                                        <th>Approval Status</th>
                                        <td id="vr_status"><span class="status"></span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>


<script>
    $(document).on('click', '.view-request', function(e) {
        e.preventDefault();
        const $el = $(this);
        const statusText = $el.data('status'); // Approved, Declined, Pending
        const $statusSpan = $('#vr_status span');

        // Fill modal with the clicked row's data
        $('#vr_employee').text($el.data('employee'));
        $('#vr_type').text($el.data('type'));
        $('#vr_reason').text($el.data('reason'));
        $('#vr_range').text($el.data('range'));
        $('#vr_date').text($el.data('date'));
        $statusSpan
            .removeClass('approved declined pending') // remove old classes
            .addClass(
                statusText === 'Approved' ? 'approved' :
                statusText === 'Declined' ? 'declined' :
                'pending'
            )
            .text(statusText);
        // Show modal
        $('#viewRequestModal').modal('show');
    });
</script>