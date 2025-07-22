<?php if (isset($page_title) && $page_title != 'Register' && $page_title != 'Login'): ?>
    <footer class="site-footer section block bg-light <?php if (isset($page) && $page == 'Auth') {
                                                            echo "d-none";
                                                        } ?>">
        <div class="container py-5 bt-1">
            <div class="row gap-y text-center text-md-start">
                <div class="col-md-4 col-offset-2 me-auto"><img src="<?php echo base_url(settings()->logo) ?>" alt="" class="logo">
                    <p class="fs-15"><?php echo settings()->footer_about ?></p>
                    <div class="">
                        <nav class="nav">
                            <a href="<?php echo prep_url(settings()->facebook) ?>" target="_blank" class="btn btn-circle btn-sm btn-gray text-white me-3"><i class="fab fa-facebook"></i></a>
                            <a href="<?php echo prep_url(settings()->twitter) ?>" target="_blank" class="btn btn-circle btn-sm btn-gray text-white me-3"><i class="fab fa-twitter"></i></a>
                            <a href="<?php echo prep_url(settings()->instagram) ?>" target="_blank" class="btn btn-circle btn-sm btn-gray text-white"><i class="fab fa-instagram"></i></a>
                        </nav>
                    </div>
                </div>


                <div class="col-md-3 text-left">
                    <h6 class="py-2"><?php echo trans('features') ?></h6>
                    <nav class="nav flex-column">
                        <a class="nav-item link-grey mb-1" href="<?php echo base_url('blog') ?>"><?php echo trans('blog') ?></a>
                        <a class="nav-item link-grey mb-1" href="<?php echo base_url('pricing') ?>"><?php echo trans('pricing') ?></a>
                        <a class="nav-item link-grey mb-1" href="<?php echo base_url('faqs') ?>"><?php echo trans('faqs') ?></a>
                        <a class="nav-item link-grey mb-1" href="<?php echo base_url('contact') ?>"><?php echo trans('contact') ?></a>
                    </nav>
                </div>

                <?php if (!empty(get_pages())): ?>
                    <div class="col-md-3">
                        <h6 class="py-2"><?php echo trans('pages') ?></h6>
                        <nav class="nav flex-column">
                            <?php foreach (get_pages() as $page): ?>
                                <a class="nav-item link-grey mb-1" href="<?php echo base_url('page/' . $page->slug) ?>"><?php echo html_escape($page->title) ?></a>
                            <?php endforeach ?>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>

            <hr class="mt-5">
            <div class="row small text-center">
                <p class="mt-2 mb-md-0 text-secondary"><?php echo settings()->copyright ?></p>
            </div>
        </div>
    </footer><!-- themeforest:js -->
<?php endif; ?>



<?php include 'js_msg_list.php'; ?>

<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
<?php $success = $this->session->flashdata('msg'); ?>
<?php $error = $this->session->flashdata('error'); ?>
<input type="hidden" id="success" value="<?php echo html_escape($success); ?>">
<input type="hidden" id="error" value="<?php echo html_escape($error); ?>">

<input type="hidden" class="accept_cookies" value="<?php echo trans('accept-cookies') ?>">
<input type="hidden" class="accept" value="<?php echo trans('accept') ?>">

<script src="<?php echo base_url() ?>assets/front_new/js/jquery.js"></script>
<script src="<?php echo base_url() ?>assets/front_new/js/bootstrap.bundle.js"></script>
<script src="<?php echo base_url() ?>assets/front_new/js/jquery.easing.js"></script>
<script src="<?php echo base_url() ?>assets/front_new/js/jquery.validate.js"></script>
<script src="<?php echo base_url() ?>assets/front_new/js/common.js"></script>
<script src="<?php echo base_url() ?>assets/front_new/js/site.js"></script>
<script src="<?php echo base_url() ?>assets/front/libs/owl-carousel/dist/js/owl.carousel.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/front/js/custom.js?var=1.9&time=<?= time(); ?>"></script>
<script src="<?php echo base_url() ?>assets/front_new/js/aos.js"></script>
<script src="<?php echo base_url() ?>assets/admin/js/sweet-alert.min.js"></script>
<script>
    AOS.init();
</script>


<script type="text/javascript">
    $(document).on('click', ".forgot_pass", function() {
        $('#login-area').slideUp();
        $('#forgot-area').slideDown();
    });

    $(document).on('click', ".back_login", function() {
        $('#login-area').slideDown();
        $('#forgot-area').slideUp();
    });
</script>


<script type="text/javascript">
    $(document).ready(function() {

        var base_url = $('#base_url').val();

        var loader_btn = '<div class="spinners"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>';

        var msg_error = $('.msg_error').val();
        var msg_sorry = $('.msg_sorry').val();
        var msg_success = $('.msg_success').val();
        var msg_signin = $('.msg_signin').val();
        var msg_signing_in = $('.msg_signing_in').val();
        var msg_try = $('.msg_try').val();

        var msg_not_active = $('.msg_not_active').val();
        var msg_account_suspend = $('.msg_account_suspend_msg').val();
        var msg_wrong_access = $('.msg_wrong_access').val();
        var msg_email_not_verified = $('.msg_email_not_verified').val();
        var msg_pass_sent_email = $('.msg_pass_sent_email').val();
        var msg_pass_reset_succ = $('.msg_pass_reset_succ').val();
        var msg_not_valid_user = $('.msg_not_valid_user').val();



        //   $(".agree_btn").on('click', function() {


        //     if ($(".agree_btn").is(":checked")) {
        //         alert("Hello! I am an alert box!!");
        //         $('.submit_btn').prop('disabled', false);
        //     } else {
        //         $('.submit_btn').prop('disabled', true);
        //     }
        // });


        $(document).on('submit', "#login-form", function() {

            $(".signin_btn").html('<span class="spinner-btn-sm"></span> ' + msg_signing_in);
            $(".signin_btn").prop('disabled', true);

            $.post($('#login-form').attr('action'), $('#login-form').serialize(), function(json) {
                if (json.st == 1) {
                    window.location = json.url;
                } else if (json.st == 0) {
                    $(".signin_btn").prop('disabled', false);
                    $(".signin_btn").html(msg_signin);
                    $(".error").show().html('<i class="far fa-times-circle"></i> ' + msg_wrong_access);
                    $('#login_pass').val('');
                } else if (json.st == 2) {
                    $(".signin_btn").prop('disabled', false);
                    $(".signin_btn").html(msg_signin);
                    $(".error").show().html('<i class="far fa-times-circle"></i> ' + msg_not_active);
                } else if (json.st == 3) {
                    $(".signin_btn").prop('disabled', false);
                    $(".signin_btn").html(msg_signin);
                    $(".error").show().html('<i class="far fa-times-circle"></i> ' + msg_account_suspend);
                } else if (json.st == 4) {
                    $(".signin_btn").prop('disabled', false);
                    $(".signin_btn").html(msg_signin);
                    $(".error").show().html('<i class="icon-exclamation"></i> ' + msg_email_not_verified);
                    setTimeout(function() {
                        window.location.href = base_url + "auth/verify_email";
                    }, 2000);
                } else if (json.st == 5) {
                    $(".signin_btn").prop('disabled', false);
                    $(".signin_btn").html(msg_signin);
                    $(".error").show().html('<i class="far fa-arrow-alt-circle-up"></i> ' + "Plan expired. Contact admin to upgrade.");
                }

            }, 'json');
            return false;
        });

        //recover password form
        $(document).on('submit', "#lost-form", function() {
            $.post($('#lost-form').attr('action'), $('#lost-form').serialize(), function(json) {

                if (json.st == 1) {
                    swal({
                        title: msg_pass_reset_succ,
                        text: msg_pass_sent_email,
                        type: "success",
                        showConfirmButton: true
                    }, function() {
                        window.location = json.url;
                    });
                } else if (json.st == 2) {
                    swal({
                        title: "Notice",
                        text: "Plan expired. Contact admin to upgrade.",
                        type: "error",
                        confirmButtonText: msg_try
                    });
                } else {
                    swal({
                        title: msg_sorry,
                        text: msg_not_valid_user,
                        type: "error",
                        confirmButtonText: msg_try
                    });
                }
            }, 'json');
            return false;
        });

    });
</script>

<script type="text/javascript">
    $('.testimonial-carousel').owlCarousel({
        rtl: false,
        loop: true,
        margin: 40,
        dots: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: false
            },
            600: {
                items: 2,
                nav: false
            },
            1000: {
                items: 2,
                nav: false,
                loop: false
            }
        }
    });
</script>

<script type="text/javascript">
    $(".switch_price").on('click', function() {
        var priceVal = $(this).val();
        if (priceVal == 'trail') {
            $('.trial-plan').show();
            $('.paid-plan').hide();
            // $('.trail-plan').hide();
            $('.monthly_show').hide();
            $('.yearly_show').hide();
            $('.price_year').hide();
            $('.price_month').show();
            $('.monthly_row').show();
            $('.yearly_row').hide();
            $('.bill_type').html("Week");
            $('.billing_type').val('week');
        } else if (priceVal == 'monthly') {
            $('.trial-plan').hide();
            $('.paid-plan').show();
            $('.monthly_show').show();
            $('.yearly_show').hide();
            $('.price_year').hide();
            $('.price_month').show();
            $('.monthly_row').show();
            $('.yearly_row').hide();
            $('.bill_type').html(msg_per_month);
            $('.billing_type').val('monthly');
        } else {
            $('.trial-plan').hide();
            $('.paid-plan').show();
            $('.monthly_show').hide();
            $('.yearly_show').show();
            $('.price_month').hide();
            $('.price_year').show();
            $('.yearly_row').show();
            $('.monthly_row').hide();
            $('.bill_type').html(msg_per_year);
            $('.billing_type').val('yearly');
        }
    });

    $('#country').on('change', function() {
        const country_id = $(this).val();
        console.log(country_id);

        // show a loading placeholder
        $('#timezone_select')
            .html('<option value="">Loading…</option>')
            .prop('disabled', true);

        if (country_id) {
            $.ajax({
                url: '<?= base_url('admin/organization_settings/get_timezones_by_country_id') ?>',
                type: 'GET',
                data: {
                    country_id
                },
                dataType: 'json',
                success(res) {
                    if (res.status && Array.isArray(res.data)) {
                        let opts = '<option value="">Select</option>';
                        res.data.forEach(tz => {
                            opts += `<option value="${tz}">${tz}</option>`;
                        });
                        $('#timezone_select').html(opts);
                    } else {
                        $('#timezone_select').html('<option value="">No timezones found</option>');
                    }
                    $('#timezone_select').prop('disabled', false);
                },
                error() {
                    $('#timezone_select')
                        .html('<option value="">Error fetching timezones</option>')
                        .prop('disabled', false);
                }
            });
        } else {
            $('#timezone_select')
                .html('<option value="">Select</option>')
                .prop('disabled', true);
        }
    });
</script>


<!-- endinject -->
<?php $this->load->view('include/stripe-js'); ?>
</body>

</html>