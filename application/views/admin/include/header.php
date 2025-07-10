<!DOCTYPE html>
<?php $settings = get_settings(); ?>

<html lang="en" dir="<?php echo ($settings->dir); ?>">

<head>

  <?php $user = get_logged_user($this->session->userdata('id')); ?>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="<?php echo base_url($settings->favicon) ?>">

  <title><?php echo html_escape($settings->site_name); ?> &bull; <?php if (isset($this->business->name)) {
                                                                    echo html_escape($this->business->name) . ' &bull;';
                                                                  } ?> <?php if (isset($page_title)) {
                                                                          echo html_escape($page_title);
                                                                        } else {
                                                                          echo "Dashboard";
                                                                        } ?></title>

  <!-- Bootstrap 4.0-->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/bootstrap.min.css">
  <!-- Bootstrap 4.0-->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/bootstrap-extend.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/font-awesome.min.css">
  <link href="<?php echo base_url() ?>assets/admin/css/toast.css" rel="stylesheet" />
  <link href="<?php echo base_url() ?>assets/admin/css/bootstrap-tagsinput.css" rel="stylesheet" />
  <link href="<?php echo base_url() ?>assets/admin/css/sweet-alert.css" rel="stylesheet" />
  <link href="<?php echo base_url() ?>assets/admin/css/animate.min.css" rel="stylesheet" />
  <!-- DataTables -->
  <link href="<?php echo base_url() ?>assets/admin/js/jquery.dataTables.min.css" type="text/css" />
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/admin_style.css?var=<?php echo settings()->version ?>&time=<?= time(); ?>">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/skins/theme_<?php echo settings()->theme ?>.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/custom.css">

  <?php if (text_dir() == 'rtl'): ?>
    <?php if (settings()->theme == 1): ?>
      <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/custom-rtl.css">
    <?php else: ?>
      <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/custom-rtl-dark.css">
    <?php endif ?>
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/bootstrap-rtl.min.css" crossorigin="anonymous">
  <?php endif ?>


  <link href="<?php echo base_url() ?>assets/admin/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <link href="<?php echo base_url() ?>assets/admin/css/icons.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front/css/simple-line-icons.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front/font/flaticon.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/fonts/bootstrap/bootstrap-icons.css">
  <link href="<?php echo base_url() ?>assets/admin/css/bootstrap-switch.min.css" rel="stylesheet">
  <link href="<?php echo base_url() ?>assets/admin/css/select2.min.css" rel="stylesheet" />
  <link href="<?php echo base_url() ?>assets/admin/css/themify.min.css" rel="stylesheet" />
  <link href="<?php echo base_url() ?>assets/admin/css/bootstrap4-toggle.min.css" rel="stylesheet" />
  <link href="<?php echo base_url() ?>assets/admin/css/summernote.css" rel="stylesheet" />
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">


  <?php if (settings()->theme == 2): ?>
    <?php $rgb = hex2rgb(settings()->site_color) ?>
    <link href="<?php echo base_url() ?>assets/admin/css/style-over.php?color=<?php echo settings()->site_color; ?>&rgb=<?php echo $rgb ?>" rel="stylesheet">
  <?php endif ?>

  <?php
  // for security purpose
  if (!$this->session->userdata('logged_in') && !$this->session->userdata('employee_logged_in')) {
    redirect('login');
  }
  ?>
  <style type="text/css">
    /* notification custom style */
    .notificaion_style {
      .form-group label {
        font-weight: bolder;
        font-size: 16px;
        color: #333;
      }

      .send-email {
        padding: 4px 8px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
      }

      .send-email:hover {
        background-color: #0056b3;
      }


      .notification {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        border-bottom: 1px solid #eee;
        padding: 16px 0;
      }

      .notification {
        display: grid;
        grid-template-columns: 2fr 1fr;
        /* Example: image | details | right section */
        align-items: start;
        padding: 16px 0;
        border-bottom: 1px solid #eee;
        gap: 16px;
        /* spacing between columns */
      }


      .notification:last-child {
        border-bottom: none;
      }

      .profile {
        display: flex;
        align-items: center;
      }

      .profile img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        margin-right: 12px;
        object-fit: cover;
      }

      .details {
        display: flex;
        flex-direction: column;
      }

      .name {
        font-weight: bold;
        margin-bottom: 2px;
      }

      .desc {
        color: #888;
        font-size: 14px;
      }

      .right {
        text-align: center;
      }

      .status {
        display: inline-block;
        margin-top: 8px;
        padding: 4px 12px;
        font-size: 13px;
        border-radius: 12px;
        width: max-content;
      }

      .right {
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
        /* Adds small space between status and time */
      }

      .status {
        margin-top: 0;
        /* Remove the previous margin */
      }

      .time {
        color: #aaa;
        font-size: 12px;
        /* Slightly smaller for better hierarchy */
      }

      .online {
        background-color: #dcfce7;
        color: #166534;
        border: 2px solid #166534;
      }

      .offline {
        background-color: #fee2e2;
        color: #991b1b;
        border: 2px solid #991b1b;
      }

      .time {
        color: #aaa;
        font-size: 16px;
      }

      .loading {
        text-align: center;
        padding: 20px;
        color: #888;
      }

      .error-message {
        color: #cc4c4c;
        text-align: center;
        padding: 20px;
      }

      .button-loader {
        border: 2px solid #f3f3f3;
        border-top: 2px solid #3498db;
        border-radius: 50%;
        width: 14px;
        height: 14px;
        animation: spin 0.8s linear infinite;
        display: inline-block;
        vertical-align: middle;
        margin-left: 6px;
      }

      @keyframes spin {
        0% {
          transform: rotate(0deg);
        }

        100% {
          transform: rotate(360deg);
        }
      }


      .box {
        width: 150px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .desktop {
        color: black;
        background-color: rgb(233, 233, 233);
        /* border: 1px solid #2c3e50; */
      }

      .desktop:hover {
        color: #2c3e50;
        background-color: #EBEBEB;
        /* border: 1px solid #2c3e50; */
      }

      .webcam {
        color: black;
        background-color: rgb(233, 233, 233);
        /* border: 1px solid #2c3e50; */
      }

      .box.active {
        /* background-color: #3498db;
        color: white;
        border-color: #3498db;
        font-weight: bold; */
        color: #fff;
        background-color: #0FB783;
        border-color: #0FB783;
      }

      @media (max-width: 768px) {
        .notification {
          grid-template-columns: 1fr;
          grid-template-rows: auto auto;
        }

        .notification .profile,
        .notification .details,
        .notification .right {
          grid-column: 1 / -1;
        }

        .notification .right {
          margin-top: 12px;
          text-align: left;
        }
      }
    }

    /* role management custom style */
    .role_management {

      .rotate-icon {
        font-size: x-large !important;
      }

      .box-header:hover {
        background-color: whitesmoke;
        border-radius: 5px;
        cursor: pointer;
      }
    }

    /* role permission custom style */
    .role_permission {
      .actions {
        display: flex;
        gap: 10px;

        >a {
          color: black;

          >i {
            transition: color 0.3s;
            font-size: 20px;
            margin-top: 5px;
          }
        }

        >a:hover>i {
          color: green;
        }

        >a:nth-child(2):hover>i {
          color: red;
        }
      }
    }


    .org-settings,
    .org_exception_settings {
      .toggle-switch {
        >.toggle {
          width: 90px !important;
          height: 35px !important;
        }
      }
    }

    /* .content-wrapper,
    body,
    .wrapper {
      overflow-x: hidden !important;
      overflow-y: unset !important;
    } */

    .toast {
      padding: 10px;
      margin: 5px;
      border-radius: 4px;
      color: #fff;
      min-width: 200px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .toast-success {
      background-color: #28a745;
    }

    .toast-error {
      background-color: #e74c3c;
    }

    #toast-container {
      position: fixed;
      top: 10px;
      right: 10px;
      z-index: 9999;
    }

    .radio input[type="radio"],
    .radio-inline input[type="radio"],
    .checkbox input[type="checkbox"],
    .checkbox-inline input[type="checkbox"] {
      margin-right: -20px !important;
    }

    <?php if (auth('role') == 'viewer'): ?>a.on-default {
      display: none;
    }

    .add_btn {
      display: none;
    }

    .btn {
      display: none;
    }

    .hide_viewer {
      display: none;
    }

    <?php endif ?>@media (max-width: 480px) {
      #toast-container {
        left: 10px;
        right: 10px;
        top: 10px;
      }

      .toast {
        min-width: auto;
        width: calc(100% - 20px);
      }
    }

    ul.sidebar-menu.tree li a i {
      font-size: 14px;
      margin-right: 0px;
      font-weight: 400;
      color: #fafafa;
    }

    .select2 {
      width: 100% !important;
    }
  </style>

  <!-- Color picker plugins css -->
  <link href="<?php echo base_url() ?>assets/admin/plugins/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">

  <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/bootstrap-colorpicker.min.css">

  <script type="text/javascript">
    var csrf_token = '<?php echo $this->security->get_csrf_hash(); ?>';
    var token_name = '<?php echo $this->security->get_csrf_token_name(); ?>'
  </script>


</head>

<body class="hold-transition skin-blue-light sidebar-mini">

  <!-- Preloader -->
  <div class="preloader">
    <div class="container text-center">
      <div class="spinner-llg"></div>
    </div>
  </div>
  <!-- Preloader -->

  <!-- Site wrapper -->
  <div class="wrapper">

    <?php if (isset($page_title) && $page_title != 'Online Payment'): ?>
      <header class="main-header">
        <?php if (is_employee()): ?>
          <a href="#" class="switch_businesss logo text-centers">
            <span class="logo-lg">
              <img width="50px" class="mr-5" src="<?php echo base_url($settings->favicon) ?>" alt="<?php echo $this->session->userdata("employee_name"); ?>"> <span data-toggle="tooltip" data-placement="top" title="<?php echo $this->session->userdata("employee_name"); ?>" class="ml-20"><?php echo $this->session->userdata("employee_name"); ?></span>
            </span>
          </a>
        <?php else :  ?>
          <?php if (is_admin()): ?>
            <a target="_blank" href="<?php echo base_url() ?>" class="switch_businesss logo text-centers">
              <span class="logo-lg">
                <img width="50px" class="mr-5" src="<?php echo base_url($settings->favicon) ?>" alt="<?php echo html_escape($settings->site_name); ?>"> <span class="ml-20"><?php echo html_escape($settings->site_name); ?></span>
              </span>
            </a>
          <?php else: ?>

            <a href="#" class="switch_business logo text-centers">
              <span class="logo-lg">
                <img width="40px" src="<?php echo base_url($settings->favicon) ?>" alt="<?php echo html_escape($settings->site_name); ?>">
                <span>
                  <?php
                  // Load the admin model if not already loaded
                  $this->load->model('Admin_Model');
                  // Get current user ID - you might need to adjust this based on your auth system
                  $current_user_id = $this->session->userdata('user_id') ?? $this->session->userdata('id');
                  // Get all users
                  $users = $this->admin_model->get_users();
                  // Find current user
                  $current_user = array_filter($users, function ($user) use ($current_user_id) {
                    return $user->id == $current_user_id;
                  });
                  // Get the first match (if any)
                  $current_user = reset($current_user);
                  // Display the name if found, otherwise fall back to business name
                  echo html_escape($current_user ? $current_user->name : $this->business->name);
                  ?>
                </span>
              </span>
              <span class="buss-arrow pull-right"><i class="icon-arrow-right"></i></span>
            </a>

            <div class="business_switch_panel animate-ltr" style="display: none;">
              <div class="buss_switch_panel_header">
                <img width="30px" src="<?php echo base_url($settings->favicon) ?>" alt="<?php echo html_escape($settings->site_name); ?>">
                <span class="acc"> <?php echo html_escape($settings->site_name); ?> <?php echo trans('accounts') ?></span>
                <span class="business_close pull-<?php echo ($settings->dir == 'rtl') ? 'left' : 'right'; ?>">×</span>
              </div>

              <div class="buss_switch_panel_body">
                <ul class="switcher_business_menu pb-20">
                  <?php foreach (get_my_business() as $mybuss): ?>
                    <li class="business_menu_item <?php if ($this->business->uid == $mybuss->uid) {
                                                    echo "default";
                                                  } ?>">
                      <a class="business_menu_item_link" href="<?php echo base_url('admin/profile/switch_business/' . $mybuss->uid) ?>">
                        <span class="business-menu_item_label">
                          <?php echo $mybuss->name ?>
                          <?php if ($this->business->uid == $mybuss->uid): ?>
                            <span class="is_default pull-right"><i class="flaticon-checked text-success"></i></span>
                          <?php endif ?>
                        </span>
                      </a>
                    </li>
                  <?php endforeach ?>
                </ul>

                <div class="seperater"></div>

                <?php if (auth('role') == 'user' || auth('role') == 'subadmin'): ?>
                  <a class="new_business_link" href="<?php echo base_url('admin/business') ?>"><i class="icon-briefcase"></i> <span><?php echo trans('manage-business') ?></span></a>

                  <a class="new_business_link" href="<?php echo base_url('admin/role_management') ?>"><i class="icon-people"></i> <span><?php echo trans('manage-users') ?></span></a>

                  <!-- <a class="new_business_link" href="<?php echo base_url('admin/business/invoice_customize') ?>"><i class="fa fa-paint-brush"></i> <span><?php echo trans('invoice-customization') ?></span></a> -->
                <?php endif; ?>

                <a class="new_business_link" href="<?php echo base_url('admin/profile') ?>"><i class="flaticon-user-1"></i> <span><?php echo trans('manage-profile') ?></span></a>

                <a class="new_business_link" href="<?php echo base_url('auth/logout') ?>"><i class="icon-logout"></i> <span><?php echo trans('sign-out') ?></span></a>
              </div>

              <div class="buss_switch_panel_footer">

              </div>
            </div>
          <?php endif; ?>
        <?php endif; ?>
        <nav class="navbar navbar-static-top hidden-md">
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
        </nav>

      </header>
    <?php endif; ?>
    <div id="toast-container"></div>
    <script>
      function showToast(message, type) {
        const toast = $(`<div class="toast toast-${type}">${message}</div>`);
        $('#toast-container').append(toast);
        setTimeout(() => toast.fadeOut(500, () => toast.remove()), 1000);
      }

      function showConfirmationAlert(message, status = "warning", confirmCallback) {
        swal({
          title: "Confirm Action",
          text: message,
          type: status,
          showCancelButton: true,
          cancelButtonText: "Cancel",
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, Proceed",
          closeOnConfirm: false
        }, function(isConfirm) {
          if (isConfirm && typeof confirmCallback === "function") {
            confirmCallback();
          }
        });
      }
    </script>