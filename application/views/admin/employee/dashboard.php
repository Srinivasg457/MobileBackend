<style>
    .card-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        margin-top: 20px;
    }

    @media (max-width: 1200px) {
        .card-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .card-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .card-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<div class="content-wrapper">

    <section class="content">
        <div class="d-flex justify-content-between align-items-baseline mb-3">
            <h3 class="mb-0">Dashboard</h3>
            <div class="form-group col-lg-3">
                <form class="user_filter_form" role="search" autocomplete="off" action="<?php echo base_url('employee/dashboard') ?>" method="post">

                    <!-- <label class="control-label">Date</label> -->
                    <!-- <input type="date" class="form-control" name="date" id="datePicker" value="<?php echo $chart_data['date'] ?>"> -->
                    <div class="input-group">
                        <input type="text" id="datePicker" class="inv-dpick form-control datepicker" value="<?php echo $chart_data['date'] ?>">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </form>
            </div>
        </div>

        <div class=" mt-20">
            <div class="card-grid">
                <div class="card counts">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="ml-20 align-self-center">
                                <h4 class="text-muteds m-b-0"><?php echo "Productive Hours" ?></h4>
                                <h2 class="m-b-0"><?php echo $chart_data['total_active'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card counts">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="ml-20 align-self-center">
                                <h4 class="text-muteds m-b-0"><?php echo "Unproductive Hours" ?></h4>
                                <h2 class="m-b-0"><?php echo $chart_data['total_idle'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card counts">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="ml-20 align-self-center">
                                <h4 class="text-muteds m-b-0"><?php echo "Shift Time" ?></h4>
                                <h2 class="m-b-0"><?php echo $chart_data['shift_time'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card counts">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="ml-20 align-self-center">
                                <h4 class="text-muteds m-b-0"><?php echo "Key Stroke" ?></h4>
                                <h2 class="m-b-0"><?php echo $chart_data['total_keystrokes'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card counts">
                    <div class="card-body">
                        <div class="d-flex flex-row">
                            <div class="ml-20 align-self-center">
                                <h4 class="text-muteds m-b-0"><?php echo "Mouse Activity" ?></h4>
                                <h2 class="m-b-0"><?php echo $chart_data['total_mouse_movements'] ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-20">
            <div class="col-sm-4">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Overall Productivity" ?></h3>
                    </div>
                    <div class="box-body">
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Last 4 Weeks Report"  ?></h3>
                    </div>
                    <div class="box-body">
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20">
            <div class="col-sm-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Focus or Action" ?></h3>
                    </div>
                    <div class="box-body">
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo "Insights (AI Generated)" ?></h3>
                    </div>
                    <div class="box-body">
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
<script>
    $(document).ready(function() {
                $(document).on('change', "#datePicker", function() {
                    $('.user_filter_form').submit();
                });
            });
</script>