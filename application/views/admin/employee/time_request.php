<div class="content-wrapper employee_timeRequest">
    <section class="content">
        <h3><?php echo "Time Requests" ?>
            <a href="#" id="btn-create" class="pull-right btn btn-info btn-sm rounded  mx-5">
                <i class="fa fa-plus"></i> Create Request</a>
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
        <!-- Employee Requests Table -->
        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
            <table class="table table-hover cushover mt-0 <?php if (count($userData) > 10) {
                                                                echo "datatable";
                                                            } ?>" id="dg_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Request Date</th>
                        <th>Purpose/Reason</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th>View</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($userData)) : ?>
                        <?php $i = 1; ?>
                        <?php foreach ($userData as $req) : ?>
                            <tr id="row_<?php echo html_escape($req['manual_id']); ?>">
                                <td><?= $i++ ?></td>
                                <td><?php echo isset($type_labels[$req['type']]) ? $type_labels[$req['type']] : 'Unknown'; ?></td>
                                <td>
                                    <p class="mb-0"><?php echo $req['date_added']; ?></p>
                                    <p class="mb-0 text-muted"><?php echo substr($req['timestamp_start'], 0, 5) ?> → <?= substr($req['timestamp_end'], 0, 5) ?></p>
                                </td>
                                <!-- <td><?= $req['date_added'] ?></td> -->
                                <!-- <td><?= substr($req['timestamp_start'], 0, 5) ?> → <?= substr($req['timestamp_end'], 0, 5) ?></td> -->
                                <td title="<?= htmlspecialchars($req['reason'], ENT_QUOTES) ?>">
                                    <?= strlen($req['reason']) > 50
                                        ? htmlspecialchars(mb_substr($req['reason'], 0, 50), ENT_QUOTES) . '…'
                                        : htmlspecialchars($req['reason'], ENT_QUOTES);
                                    ?>
                                </td>
                                <td><?= $req['created_at'] ?></td>
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
                                        data-range="<?= substr($req['timestamp_start'], 0, 5) ?> → <?= substr($req['timestamp_end'], 0, 5) ?>"
                                        data-date="<?= htmlspecialchars($req['date_added']) ?>"
                                        data-status="<?=
                                                        $req['approved'] == 1 ? 'Approved' : ($req['approved'] == -1 ? 'Declined' : 'Pending');
                                                        ?>"
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
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a
                                            data-val="delete"
                                            data-id="<?php echo $req['manual_id']; ?>"
                                            data-user-id="<?php echo $req['user_id']; ?>"
                                            href="<?php echo base_url('employee/TimeRequest/request_delete/'  . html_escape($req['manual_id'])) ?>"
                                            class="on-default remove-row delete_item"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Delete"
                                            data-original-title="Delete">
                                            <i class="fa fa-trash-o"></i> </a>
                                    <?php elseif ($req['approved'] == 1) : ?>
                                        <a href="#"
                                            class="on-default edit-row"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="You can edit only pending requests"
                                            style="pointer-events: none; cursor: not-allowed; opacity: 0.6;">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a
                                            data-val="delete"
                                            data-id="<?php echo $req['manual_id']; ?>"
                                            data-user-id="<?php echo $req['user_id']; ?>"
                                            href="<?php echo base_url('employee/TimeRequest/request_delete/'  . html_escape($req['manual_id'])) ?>"
                                            class="on-default remove-row delete_item"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Delete"
                                            data-original-title="Delete">
                                            <i class="fa fa-trash-o"></i> </a>
                                    <?php elseif ($req['approved'] == 0) : ?>
                                        <a
                                            data-val="edit request"
                                            data-id="<?= $req['manual_id']; ?>"
                                            data-date="<?= $req['date_added']; ?>"
                                            data-start="<?= substr($req['timestamp_start'], 0, 5) ?>"
                                            data-end="<?= substr($req['timestamp_end'], 0, 5) ?>"
                                            data-reason="<?= htmlspecialchars($req['reason'], ENT_QUOTES) ?>"
                                            href="#"
                                            class="on-default remove-row edit-request"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit"
                                            data-original-title="Edit">
                                            <i class="fa fa-pencil"></i> </a>

                                        <a
                                            data-val="delete"
                                            data-id="<?php echo $req['manual_id']; ?>"
                                            data-user-id="<?php echo $req['user_id']; ?>"
                                            href="<?php echo base_url('employee/TimeRequest/request_delete/'  . html_escape($req['manual_id'])) ?>"
                                            class="on-default remove-row delete_item"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Delete"
                                            data-original-title="Delete">
                                            <i class="fa fa-trash-o"></i> </a>

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
              width: 100%;word-break: break-word;white-space: pre-wrap; overflow-wrap: break-word;">
                                <tbody>
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
                                        <th>Status</th>
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
<div class="modal fade" id="requestFormModal" tabindex="-1" aria-labelledby="requestFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-zoom">
        <div class="modal-content" style="margin-top: 10% !important">
            <div class="modal-header">
                <h5 class="modal-title" id="requestFormModalLabel">Create Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="<?= site_url('employee/TimeRequest/submit') ?>" enctype="multipart/form-data">
                    <input type="hidden" name="manual_id" id="manual_id">
                    <div class="form-row mt-2">
                        <div class="col form-group">
                            <label class="control-label" for="type">Request Type</label>
                            <select name="type" id="type" class="form-control single_select" required>
                                <option value="">-- Select Request Type --</option>
                                <option value="1">Manual Time</option>
                                <option value="2">Client Meeting</option>
                                <option value="3">Training</option>
                                <option value="4">On-site Work</option>
                                <option value="5">Other Offline Work</option>
                                <option value="6">Internet Issues</option>
                            </select>
                        </div>


                        <div class="col form-group">
                            <label for="requested_date" class="control-label">Requested Date</label>
                            <input type="date"
                                name="requested_date"
                                id="requested_date"
                                class="inv-dpick form-control"
                                min="<?= date('Y-m-d', strtotime('-7 days')); ?>"
                                max="<?= date('Y-m-d'); ?>"
                                required>
                        </div>
                    </div>

                    <div class="form-row mt-2">
                        <div class="col form-group">
                            <label class="control-label" for="time_start">Start Time</label>
                            <input type="time" name="time_start" id="time_start" class="form-control" required>
                            <span class="text-danger" id="err_time_start"></span>
                        </div>
                        <div class="col form-group">
                            <label class="control-label" for="time_end">End Time</label>
                            <input type="time" name="time_end" id="time_end" class="form-control" required>
                            <span class="text-danger" id="err_time_end"></span>
                        </div>
                    </div>

                    <div class="form-group mt-2">
                        <label class="control-label" for="reason">Description</label>
                        <textarea name="reason" id="reason" class="form-control" placeholder="please add the description of your request" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-info mt-2">Submit</button>
                    <button type="button" id="close-modal" class="btn btn-default mt-2">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const btnCreate = document.getElementById('btn-create');
    const modal = document.getElementById('request-modal');
    const closeModal = document.getElementById('close-modal');

    btnCreate.addEventListener('click', function() {
        $('#requestFormModal').modal('show');
    });

    closeModal.addEventListener('click', function() {
        $('#requestFormModal').modal('hide');
    });

    $(document).ready(function() {
        // --- Validation on submit ---
        $('form').on('submit', function(e) {
            let valid = true;

            // clear old errors
            $('#err_time_start, #err_time_end').text('');

            const startVal = $('#time_start').val().trim();
            const endVal = $('#time_end').val().trim();

            // Start time required
            if (startVal === '') {
                $('#err_time_start').text('Please enter a start time.');
                valid = false;
            }

            // End time required
            if (endVal === '') {
                $('#err_time_end').text('Please enter an end time.');
                valid = false;
            }

            if (startVal && endVal) {
                // Check end > start
                if (endVal <= startVal) {
                    $('#err_time_end').text('End time must be after start time.');
                    valid = false;
                } else {
                    // Minimum 5-minute difference
                    const start = new Date(`1970-01-01T${startVal}:00`);
                    const end = new Date(`1970-01-01T${endVal}:00`);
                    const diffMinutes = (end - start) / (1000 * 60);

                    if (diffMinutes < 5) {
                        $('#err_time_end').text('The time range must be at least 5 minutes.');
                        valid = false;
                    }
                }
            }

            if (!valid) {
                e.preventDefault(); // stop form submission
            }
        });



        // --- Hide error when the field gains focus ---
        $('#time_start').on('focus', function() {
            $('#err_time_start').text('');
        });

        $('#time_end').on('focus', function() {
            $('#err_time_end').text('');
        });
        $(document).on('click', '.view-request', function(e) {
            e.preventDefault();
            const $el = $(this);
            const statusText = $el.data('status'); // Approved, Declined, Pending
            const $statusSpan = $('#vr_status span');

            // Fill modal with the clicked row's data
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
        $(document).on('click', '.edit-request', function(e) {
            e.preventDefault();
            const row = $(this).closest('tr');

            // fetch details from HTML data attributes or from the table cells
            const id = $(this).data('id'); // set data-id in anchor (see below)
            const type = row.find('td:nth-child(2)').text().trim() === 'Meeting' ? 1 : 0;
            const date = $(this).data('date');
            const start = $(this).data('start');
            const end = $(this).data('end');
            const reason = $(this).data('reason');

            $('#manual_id').val(id);
            $('#type').val(type);
            $('#requested_date').val(date);
            $('#time_start').val(start);
            $('#time_end').val(end);
            $('#reason').val(reason);

            $('#requestFormModalLabel').text('Edit Request');
            $('#requestFormModal').modal('show');
        });
    });
</script>
</script>