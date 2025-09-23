<div class="content-wrapper employee_timeRequest">
    <section class="content">
        <h3><?php echo "Time Requests" ?>
            <a href="#" id="btn-create" class="pull-right btn btn-info btn-sm rounded  mx-5">
                <i class="fa fa-plus"></i> Create Request</a>
        </h3>

        <!-- Employee Requests Table -->
        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive mt-20 p-0">
            <table class="table table-hover cushover mt-0 <?php if ($userData > 10) {
                                                                echo "datatable";
                                                            } ?>" id="dg_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Requested Date</th>
                        <th>Time Range</th>
                        <th>Purpose/Reason</th>
                        <th>Status</th>
                        <th>Admin Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($userData)) : ?>
                        <?php $i = 1; ?>
                        <?php foreach ($userData as $req) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $req['is_meeting'] == 1 ? 'Meeting' : 'Manual' ?></td>
                                <td><?= $req['date_added'] ?></td>
                                <td><?= substr($req['timestamp_start'], 0, 5) ?> → <?= substr($req['timestamp_end'], 0, 5) ?></td>
                                <td><?= $req['reason'] ?></td>
                                <td>
                                    <?php if ($req['declined'] == 1) : ?>
                                        <span class="status declined">Declined</span>
                                    <?php elseif ($req['approved'] == 1) : ?>
                                        <span class="status approved">Approved</span>
                                    <?php else : ?>
                                        <span class="status pending">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $req['admin_note'] ?? 'No note provided' ?></td>
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
                    <div class="form-group">
                        <label class="control-label" for="type">Type</label>
                        <select name="type" id="type" class="form-control single_select" required>
                            <option value="">-- Select Type --</option>
                            <option value="1">Meeting</option>
                            <option value="0">Manual Time</option>
                        </select>

                    </div>

                    <div class="form-group">
                        <label for="requested_date" class="control-label">Requested Date</label>
                        <input type="date"
                            name="requested_date"
                            id="requested_date"
                            class="inv-dpick form-control"
                            min="<?= date('Y-m-d'); ?>"
                            required>
                    </div>

                    <div class="form-row">
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

            // Start time required
            if ($('#time_start').val().trim() === '') {
                $('#err_time_start').text('Please enter a start time.');
                valid = false;
            }

            // End time required
            if ($('#time_end').val().trim() === '') {
                $('#err_time_end').text('Please enter an end time.');
                valid = false;
            }

            // End time must be after start time
            if (
                $('#time_start').val() &&
                $('#time_end').val() &&
                $('#time_end').val() <= $('#time_start').val()
            ) {
                $('#err_time_end').text('End time must be after start time.');
                valid = false;
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

        // --- (Optional) Double-check date on change in case JS is disabled ---
        $('#requested_date').on('change', function() {
            const today = new Date().toISOString().split('T')[0];
            if (this.value < today) {
                alert('You cannot select a past date.');
                this.value = today; // reset to today
            }
        });
    });
</script>