<main>
    <div class="container-fluid" id="signup-area">
        <div class="row align-items-center">
            <div class="col-md-7 col-lg-7 mx-auto">

                <div class="signup-form w-lg-50 mx-auto paddingx-m">
                    <p class="mb-3">
                        <a class="site-logo" href="<?php echo base_url() ?>">
                            <img class="img-fluid" width="30%" src="<?php echo base_url($settings->logo) ?>" alt="logo" />
                        </a>
                    </p>

                    <h1 class="text-darker bold">Employee Registration</h1>
                    <p class="text-secondary mt-0 mb-5">
                        Already have an account?
                        <a href="<?php echo base_url('login'); ?>" class="text-primary">Sign In</a>
                    </p>

                    <div class="mb-4 mt-4">
                        <div class="success text-success"></div>
                        <div class="error text-danger bg-danger-soft rounded-1 py-2 px-3" style="display: none;"></div>
                        <div class="warning text-warning"></div>
                    </div>

                    <form class="form cozy" id="signup-form" method="post" action="<?php echo base_url('complete-registration'); ?>" data-validate-on="submit" novalidate>

                        <label class="form-label">Full Name</label>
                        <div class="form-group has-icon">
                            <input type="text" id="name" name="name" class="form-control br-5" value="<?php echo $employee; ?>" readonly required placeholder="Enter full name">
                            <i class="icon bi bi-person-fill text-muted"></i>
                        </div>

                        <!-- Optional: Display email field if needed -->
                        <label class="form-label">Email</label>
                        <div class="form-group has-icon">
                            <input type="email" id="email" name="email" class="form-control br-5" value="<?php echo $email; ?>" readonly required placeholder="">
                            <i class="icon bi bi-envelope-fill text-muted"></i>
                        </div>

                        <!-- Hidden Token Field -->
                        <input type="hidden" id="token" name="token" value="<?php echo $token; ?>">

                        <label class="form-label">Password</label>
                        <div class="form-group has-icon">
                            <input type="password" id="password" name="password" class="form-control br-5" required placeholder="Enter password">
                            <i class="icon bi bi-lock-fill text-muted"></i>
                        </div>

                        <label class="form-label">Confirm Password</label>
                        <div class="form-group has-icon">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control br-5" required placeholder="Confirm password">
                            <i class="icon bi bi-lock-fill text-muted"></i>
                        </div>

                        <div class="form-group d-flex align-items-center justify-content-between">
                            <!-- CSRF Token -->
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <button type="submit" class="btn btn-primary br-5">Register</button>
                        </div>
                    </form>


                </div>
            </div>

            <div class="col-md-5 col-lg-5 hide-m fullscreen-md d-flex justify-content-center align-items-center overlay overlay-primarys alpha-4 image-background cover" style="background-image:url(<?php echo base_url('assets/images/default.jpg') ?>)">
                <div class="content text-center">
                    <h2 class="display-4 display-md-3 display-lg-2 text-contrast mt-5 mt-md-0">
                        Join <span class="bold d-block"><?php echo settings()->site_name ?></span>
                    </h2>
                    <p class="lead text-contrast">Create your employee account and get started!</p>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#signup-form').on('submit', function(e) {
        var name = $('#name').val();
        var token = $('#token').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();
        if (name == "") {
            e.preventDefault(); // stop form submission
            $('.error').text('.').show();
            return false;
        }
        if (password == "") {
            e.preventDefault(); // stop form submission
            $('.error').text('Please enter the password.').show();
            return false;
        }
        if (confirmPassword == "") {
            e.preventDefault(); // stop form submission
            $('.error').text('Please enter the password.').show();
            return false;
        }

        if (password !== confirmPassword) {
            e.preventDefault(); // stop form submission
            $('.error').text('Passwords do not match.').show();
            return false;
        } else {
            $('.error').hide();
            return true; // allow form to submit normally
        }
    });
</script>