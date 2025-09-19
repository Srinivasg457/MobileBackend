<div class="content-wrapper">
    <section class="content" style="position: relative;">
        <h3>Time Requests</h3>

        <!-- Create Request Button (top-right) -->
        <button id="btn-create" class="btn btn-primary" style="position:absolute; top:20px; right:20px;">Create Request</button>

        <!-- Employee Requests Table -->
        <div class="col-md-12 col-sm-12 col-xs-12 scroll table-responsive p-0" style="margin-top:50px" ;>
            <table class="table table-hover cushover mt-0" id="dg_table">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Type</th>
                        <th>Requested Date</th>
                        <th>Time Range</th>
                        <th>Purpose/Reason</th>
                        <th>Status</th>
                        <th>Admin Note</th>
                        <th>Created At</th>
                        <th>Attachment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($requests)) : ?>
                        <?php $i = 1; // Initialize serial number 
                        ?>
                        <?php foreach ($requests as $req) : ?>
                            <tr>
                                <td><?= $i++ ?></td> <!-- Auto increment S.No -->
                                <td><?= $req->type ?></td>
                                <td><?= $req->requested_date ?></td>
                                <td><?= substr($req->time_start, 0, 5) ?> → <?= substr($req->time_end, 0, 5) ?></td>
                                <td><?= $req->reason ?></td>
                                <td>
                                    <?php if ($req->status == 'Pending') : ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php elseif ($req->status == 'Approved') : ?>
                                        <span class="badge badge-success">Approved</span>
                                    <?php else : ?>
                                        <span class="badge badge-danger">Declined</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $req->admin_note ?? '-' ?></td>
                                <td><?= $req->created_at ?></td>
                                <td><?= $req->attachment ? '📎 View' : '-' ?></td>
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

        <!-- Modal Form -->
        <div id="request-modal" style="
            display:none;
            position:fixed;
            top:50%;
            left:50%;
            transform:translate(-50%,-50%);
            background:#fff;
            padding:20px;
            z-index:1001;
            width:400px;
            border-radius:8px;
            box-shadow:0 5px 15px rgba(0,0,0,0.3);
        ">
            <h4>Create Request</h4>
            <form method="post" action="<?= site_url('employee/TimeRequest/submit') ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="type">Type:</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="Meeting">Meeting</option>
                        <option value="Manual Time">Manual Time</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="requested_date">Requested Date:</label>
                    <input type="date" name="requested_date" id="requested_date" class="form-control" required>
                </div>

                <div class="form-row">
                    <div class="col">
                        <label for="time_start">Start Time:</label>
                        <input type="time" name="time_start" id="time_start" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="time_end">End Time:</label>
                        <input type="time" name="time_end" id="time_end" class="form-control" required>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label for="reason">Purpose / Reason:</label>
                    <textarea name="reason" id="reason" class="form-control" required></textarea>
                </div>

                <button type="submit" class="btn btn-success mt-2">Submit Request</button>
                <button type="button" id="close-modal" class="btn btn-danger mt-2">Close</button>
            </form>
        </div>

        <!-- Background overlay -->
        <div id="modal-overlay" style="
            display:none;
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index:1000;
        "></div>

    </section>
</div>

<script>
    const btnCreate = document.getElementById('btn-create');
    const modal = document.getElementById('request-modal');
    const overlay = document.getElementById('modal-overlay');
    const closeModal = document.getElementById('close-modal');

    btnCreate.addEventListener('click', function() {
        modal.style.display = 'block';
        overlay.style.display = 'block';
    });

    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
        overlay.style.display = 'none';
    });

    // Optional: close modal when clicking overlay
    overlay.addEventListener('click', function() {
        modal.style.display = 'none';
        overlay.style.display = 'none';
    });
</script>