<!doctype html>
<html lang="en" dir="<?php echo text_dir(); ?>">

<head>
  <meta charset="utf-8">
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge" /><![endif]-->
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi" />

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi">

  <!-- Primary SEO Meta Tags -->
  <title>Workroom - Employee Productivity Monitoring Software | Screen Capture & Activity Tracking</title>
  <meta name="description" content="Workroom is the leading employee monitoring software that tracks productivity, captures screenshots, and analyzes work activity. Perfect alternative to TimeCamp, TeamLogger, and other workforce analytics tools.">
  <meta name="keywords" content="employee monitoring software, productivity tracking tool, screenshot capture software, work activity tracker, team productivity software, time tracking software, workforce analytics, employee productivity monitoring, workroom alternative, timecamp competitor, teamlogger competitor">
  <!-- <meta name = "viewport" content = "width=device-width, minimum-scale=1.0, maximum-scale = 1.0, user-scalable = no"> -->
  <link rel="apple-touch-icon" href="apple-touch-icon.png">
  <link rel="icon" href="<?php echo base_url(settings()->favicon) ?>">
  <link href="https://fonts.googleapis.com/css?family=Poppins:100,300,400,500,700,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Caveat" rel="stylesheet">
  <title><?php echo settings()->site_name ?> - <?php echo settings()->site_title ?></title><!-- themeforest:css -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/fontawesome.css">
  <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/aos.css"> -->
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/cookieconsent.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/magnific-popup.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/odometer-theme-minimal.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/prism-okaidia.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/simplebar.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/smart_wizard_all.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/swiper-bundle.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/main.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/rtl.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/demo.css">

  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front/libs/owl-carousel/dist/css/owl.carousel.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front/libs/owl-carousel/dist/css/owl.theme.default.min.css">
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/front_new/css/aos.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/fonts/bootstrap/bootstrap-icons.css">
  <link href="<?php echo base_url() ?>assets/admin/css/sweet-alert.css" rel="stylesheet" />

  <?php if (text_dir() == 'rtl'): ?>
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/admin/css/custom-rtl.css">
        <link rel="stylesheet" href="<?php echo base_url()?>assets/admin/css/bootstrap-rtl.min.css" crossorigin="anonymous">
  <?php endif ?>

  <?php $rgb = hex2rgb(settings()->site_color) ?>
  <link href="<?php echo base_url() ?>assets/front_new/css/style-over.php?color=<?php echo settings()->site_color; ?>&rgb=<?php echo $rgb ?>" rel="stylesheet">


  <?php if (isset($page_title) && $page_title == 'Register'): ?>

        <link rel="stylesheet" href="<?php echo base_url() ?>assets/front/css/cristal.min.css?var=<?=settings()->version;?>&time=<?=time();?>" type="text/css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/front/css/style.min.css?var=<?=settings()->version;?>&time=<?=time();?>" type="text/css">
    <link href="<?php echo base_url() ?>assets/front/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/front/css/simple-line-icons.css">

  <?php endif ?>

  <style type="text/css">
    /* Emoji container
  .chat-bot  #emoji-container {
      position: relative;
      display: flex;
      align-items: center;
      cursor: pointer;
      margin-right: 5px;
      font-size: 20px;
    }

    Emoji button
  .chat-bot  #emoji-btn {
      padding: 4px;
      border-radius: 50%;
      transition: background 0.2s;
    }

   .chat-bot #emoji-btn:hover {
      background: #f0f0f0;
    }

    Emoji picker hidden by default
   .chat-bot #emoji-picker {
      display: none;
      position: absolute;
      bottom: 35px;
      right: 0;
      background: #fff;
      border: 1px solid #ccc;
      padding: 6px;
      border-radius: 8px;
      font-size: 20px;
      z-index: 1000;
      width: 180px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      white-space: normal;
    }

 .chat-bot   #emoji-picker span {
      cursor: pointer;
      padding: 4px;
    }

 .chat-bot   #emoji-picker span:hover {
      background: #eee;
      border-radius: 5px;
    }

    Hover tooltip preview
 .chat-bot   #emoji-btn::after {
      content: "😀 😁 😂";
      position: absolute;
      bottom: 35px;
      right: -20px;
      background: #333a3d;
      color: #fff;
      padding: 4px 6px;
      border-radius: 5px;
      font-size: 14px;
      opacity: 0;
      transform: translateY(10px);
      transition: opacity 0.3s ease, transform 0.3s ease;
      white-space: nowrap;
    }

  .chat-bot  #emoji-btn:hover::after {
      opacity: 1;
      transform: translateY(0);
    } */

    /* chatbot */

    .chat-bot #chat-icon {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 50px;
      height: 50px;
      background-color: #0ca374;
      border-radius: 50%;
      cursor: pointer;
      z-index: 9999;
      display: flex;
      justify-content: center;
      align-items: center;
      color: white;
      font-size: 24px;
      transition: transform 0.3s ease;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
      }

      70% {
        box-shadow: 0 0 0 15px rgba(255, 255, 255, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
      }
    }

    .chat-bot .chat-bot-menu-itme {
      font-size: 11px;
      border: none !important;
      padding: 10px 0px !important;
      text-decoration: underline;
    }

    .chat-bot .chat-bot-main-menu {
      font-size: 15px;
    }

    .chat-bot .chat-bot-main-menu-tap {
      background-color: #ffffff;
      max-width: 100%;
      padding: 8px 12px;
      border-radius: 12px;
      margin-left: -10px;
      box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
    }

    .chat-bot .chat-bot-menu-itme:hover {
      cursor: pointer;
    }

    /* Hover tooltip */
    .chat-bot #chat-icon::after {
      content: "Need Help?";
      position: absolute;
      bottom: 70px;
      background: #0ca374;
      color: #fff;
      padding: 4px 8px;
      border-radius: 5px;
      font-size: 12px;
      opacity: 0;
      transform: translateY(10px);
      transition: opacity 0.3s ease, transform 0.3s ease;
      white-space: nowrap;
    }

    .chat-bot #chat-icon:hover::after {
      opacity: 1;
      transform: translateY(0);
    }

    /* Chat Window */
    .chat-bot #chat-window {
      display: none;
      position: fixed;
      bottom: 90px;
      right: 20px;
      width: 320px;
      height: 515px;
      background: #fdfdfd;
      border: 1px solid #ccc;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
      z-index: 9999;
      overflow: hidden;
      font-family: Arial, sans-serif;
      animation: slideUp 0.3s ease forwards;
    }

    @keyframes slideUp {
      from {
        transform: translateY(50px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .chat-bot #chat-window .header {
      background: #0ca374;
      color: black;
      padding: 10px;
      text-align: center;
      position: relative;
      font-weight: bold;
    }

    .chat-bot #new-chat {
      position: absolute;
      left: 10px;
      top: 5px;
      cursor: pointer;
      font-size: 14px;
      background: #0ca374;
      color: #fff;
      border: none;
      padding: 1px 15px;
      border-radius: 5px;
    }

    .chat-bot #chat-content {
      padding: 10px;
      height: 362px;
      overflow-y: auto;
      background: #f1f7fc;
    }

    /* Message Bubbles */
    .chat-bot .message {
      max-width: 70%;
      margin: 5px 0;
      padding: 8px 12px;
      border-radius: 12px;
      clear: both;
    }

    .chat-bot .bot-message {
      background: #e5e5e5;
      float: left;
      text-align: left;
    }

    .chat-bot .user-message {
      background: #0ca374;
      color: #fff;
      float: right;
      text-align: right;
    }

    .chat-bot #quick-replies {
      padding: 10px;
      display: flex;
      gap: 5px;
      flex-wrap: wrap;
      background: #f9f9f9;
    }

    .chat-bot #quick-replies button {
      background: #0ca374;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 15px;
      cursor: pointer;
      font-size: 13px;
    }

    .chat-bot #chat-input {
      padding: 10px;
      display: flex;
      gap: 5px;
      background: #fff;
      border-top: 1px solid #ddd;
    }

    .chat-bot #chat-input input {
      flex: 1;
      padding: 6px;
      border-radius: 5px;
      outline: none;
      border: 1px solid #ccc;
    }

    .chat-bot #chat-input button {
      padding: 6px 12px;
      background: #0ca374;
      color: #fff;
      border: none;
      outline: none;
      cursor: pointer;
      border-radius: 5px;
    }

    .chat-bot #chat-input button:hover {
      background: rgba(12, 163, 116, 0.8);
    }

    /* Tooltip on hover */
    .chat-bot #chat-input button::after {
      content: "Send";
      position: absolute;
      bottom: 70px;
      right: 10px;
      background: #0ca374;
      color: #fff;
      padding: 4px 8px;
      border-radius: 5px;
      font-size: 12px;
      opacity: 0;
      transform: translateY(10px);
      transition: opacity 0.3s ease, transform 0.3s ease;
      white-space: nowrap;
      pointer-events: none;
    }

    .chat-bot #chat-input button:hover::after {
      opacity: 1;
      transform: translateY(0);
    }

    /* Responsive adjustments */
    @media screen and (max-width: 480px) {

      /* Small mobile devices */
      .chat-bot #chat-window {
        width: 90%;
        height: 400px;
        bottom: 80px;
        right: 5%;
      }

      .chat-bot #chat-content {
        height: 253px;
        padding: 8px;
      }

      .chat-bot .message {
        max-width: 80%;
        font-size: 14px;
        padding: 6px 10px;
      }

      .chat-bot #quick-replies button {
        font-size: 11px;
        padding: 4px 8px;
      }

      .chat-bot #chat-input input {
        font-size: 14px;
      }

      .chat-bot #chat-input button {
        font-size: 14px;
        padding: 6px 10px;
      }
    }

    @media screen and (min-width: 481px) and (max-width: 768px) {

      /* Tablets */
      .chat-bot #chat-window {
        width: 350px;
        height: 414px;
        bottom: 90px;
        right: 20px;
      }

      .chat-bot #chat-content {
        height: 264px;
      }

      .chat-bot .message {
        max-width: 75%;
        font-size: 15px;
      }

      .chat-bot #quick-replies button {
        font-size: 12px;
        padding: 5px 10px;
      }

      .chat-bot #chat-input input {
        font-size: 15px;
      }

      .chat-bot #chat-input button {
        font-size: 15px;
        padding: 6px 12px;
      }
    }

    @media screen and (min-width: 769px) {

      /* Desktop */
      .chat-bot #chat-window {
        width: 320px;
        height: 515px;
      }

      .chat-bot #chat-content {
        height: 362px;
      }

      .chat-bot .message {
        max-width: 70%;
        font-size: 15px;
      }

      .chat-bot #quick-replies button {
        font-size: 13px;
      }
    }

    /* Optional: Make chat icon slightly smaller on small screens */
    @media screen and (max-width: 480px) {
      .chat-bot #chat-icon {
        width: 45px;
        height: 45px;
        font-size: 20px;
      }
    }
  </style>
</head>

<body>

  <!-- ./Making stripe menu navigation -->

  <nav class="st-nav navbar main-nav navigation fixed-top  <?php if (isset($page) && $page == 'Auth') {
                                                              echo "d-none";
                                                            } ?>" id="main-nav">
    <div class="container">
      <ul class="st-nav-menu nav navbar-nav">

        <li class="st-nav-section nav-item">
          <a href="<?php echo base_url() ?>" class="navbar-brand">
            <img width="140px" src="<?php echo base_url(settings()->logo) ?>" alt="Accufy" class="logo logo-sticky d-inline-block d-md-none hide-m">

            <img width="140px" src="<?php echo base_url(settings()->logo) ?>" alt="Accufy" class="logo logo-sticky show-m d-md-none">

            <img width="140px" src="<?php echo base_url(settings()->logo) ?>" alt="Accufy" class="logo d-none d-md-inline-block">
          </a>
        </li>

        <li class="st-nav-section st-nav-primary nav-item">
          <a class="st-root-link item-shop st-has-dropdown nav-link <?php if (isset($page_title) && $page_title == 'Home') {
                                                                      echo 'active';
                                                                    } ?>" href="<?php echo base_url() ?>"><?php echo trans('home') ?></a>
        </li>

        <li class="st-nav-section st-nav-primary nav-item">
          <a class="st-root-link item-shop st-has-dropdown nav-link <?php if (isset($page_title) && $page_title == 'Features') {
                                                                      echo 'active';
                                                                    } ?>" href="<?php echo base_url('features') ?>"><?php echo trans('features') ?></a>
        </li>



        <li class="st-nav-section st-nav-primary nav-item">
          <a class="st-root-link item-shop st-has-dropdown nav-link <?php if (isset($page_title) && $page_title == 'Pricing') {
                                                                      echo "active";
                                                                    } ?>" href="<?php echo base_url('pricing') ?>"><?php echo trans('pricing') ?></a>
        </li>

        <li class="st-nav-section st-nav-primary nav-item ">
          <a class="st-root-link item-shop st-has-dropdown nav-link <?php if (isset($page_title) && $page_title == 'Blog Posts') {
                                                                      echo 'active';
                                                                    } ?>" href="<?php echo base_url('blog') ?>"><?php echo trans('blog') ?></a>
        </li>

        <li class="st-nav-section st-nav-primary nav-item ">
          <a class="st-root-link item-shop st-has-dropdown nav-link <?php if (isset($page_title) && $page_title == 'Faqs') {
                                                                      echo "active";
                                                                    } ?>" href="<?php echo base_url('faqs') ?>"><?php echo trans('faqs') ?></a>
        </li>

        <li class="st-nav-section st-nav-primary nav-item ">
          <a class="st-root-link item-shop st-has-dropdown nav-link <?php if (isset($page_title) && $page_title == 'Contact') {
                                                                      echo "active";
                                                                    } ?>" href="<?php echo base_url('contact') ?>"><?php echo trans('contact') ?></a>
        </li>

        <?php if (!empty(get_pages())): ?>
          <li class="cdropdown st-nav-section st-nav-primary nav-item">
            <a href="javascript:void(0);" class="dropbtns st-root-link item-shop st-has-dropdown nav-link"><?php echo trans('pages') ?>
            </a>
            <div class="cdropdown-content">
              <?php foreach (get_pages() as $page): ?>
                <a href="<?php echo base_url('page/' . $page->slug) ?>"><?php echo html_escape($page->title) ?></a>
              <?php endforeach ?>
            </div>
          </li>
        <?php endif ?>

        <?php if (settings()->enable_multilingual == 1): ?>
          <li class="cdropdown st-nav-section st-nav-primary nav-item">
            <a href="javascript:void(0);" class="dropbtns st-root-link item-shop st-has-dropdown nav-link"><?php echo lang_short_form(); ?>
            </a>
            <div class="cdropdown-content">
              <?php foreach (get_language() as $lang): ?>
                <a href="<?php echo base_url('home/switch_lang/' . $lang->slug) ?>"><?php echo html_escape($lang->name) ?></a>
              <?php endforeach ?>
            </div>
          </li>
        <?php endif ?>



        <li class="st-nav-section st-nav-secondary nav-item">

          <?php if (is_admin()): ?>
            <a class="btn btn-outline me-2 px-3" href="<?php echo base_url('auth/logout') ?>"><i class="bi bi-box-arrow-right d-none d-md-inline me-md-0 me-lg-1"></i> <span class="d-md-none d-lg-inline"><?php echo trans('logout') ?></span> </a>

            <a class="btn btn-solid px-3" href="<?php echo base_url('admin/dashboard') ?>"><i class="bi bi-speedometer2 d-none d-md-inline me-md-0 me-lg-1"></i> <span class="d-md-none d-lg-inline"><?php echo trans('dashboard') ?></span></a>
          <?php elseif (is_user()): ?>
            <a class="btn btn-outline me-2 px-3" href="<?php echo base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt d-none d-md-inline me-md-0 me-lg-1"></i> <span class="d-md-none d-lg-inline"><?php echo trans('logout') ?></span> </a>

            <?php $diff = date_difference(user()->created_at); ?>
            <?php if (user()->email_verified == 0 && settings()->enable_email_verify == 1 && $diff < 2): ?>
              <a class="btn btn-solid px-3" href="<?php echo base_url('auth/verify_email') ?>"><i class="far check-circle d-none d-md-inline me-md-0 me-lg-1"></i> <span class="d-md-none d-lg-inline"><?php echo trans('verify-account') ?></span></a>
            <?php else: ?>
              <a class="btn btn-solid px-3" href="<?php echo base_url('admin/dashboard/business') ?>"><i class="fas fa-tachometer-alt d-none d-md-inline me-md-0 me-lg-1"></i> <span class="d-md-none d-lg-inline"><?php echo trans('dashboard') ?></span></a>
            <?php endif ?>
          <?php else: ?>
            <a class="btn btn-outline me-2 px-3" href="<?php echo base_url('login') ?>"><i class="bi bi-box-arrow-in-left d-none d-md-inline me-md-0 me-lg-1"></i> <span class="d-md-none d-lg-inline"><?php echo trans('sign-in') ?></span> </a>

            <a class="btn btn-solid px-3" href="<?php echo base_url('register') ?><?php if (settings()->trial_days != 0) {
                                                                                    echo '?trial=start';
                                                                                  } ?>"><i class="bi bi-person-add d-none d-md-inline me-md-0 me-lg-1"></i> <span class="d-md-none d-lg-inline"><?php echo trans('create-account') ?></span></a>
          <?php endif ?>

        </li>




        <!-- Mobile Navigation -->
        <li class="st-nav-section st-nav-mobile nav-item d-md-none">
          <button class="st-root-link navbar-toggler mobile-vab" type="button"><span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>
          <div class="st-popup">
            <div class="st-popup-container"><a class="st-popup-close-button">Close</a>
              <div class="st-dropdown-content-group">
                <a class="regular" href="<?php echo base_url('features') ?>"> <?php echo trans('features') ?> </a>

                <a class="regular" href="<?php echo base_url('pricing') ?>"> <?php echo trans('pricing') ?> </a>

                <a class="regular" href="<?php echo base_url('blog') ?>"> <?php echo trans('blog') ?> </a>

                <a class="regular" href="<?php echo base_url('faqs') ?>"> <?php echo trans('faqs') ?> </a>

                <a class="regular" href="<?php echo base_url('contact') ?>"> <?php echo trans('contact') ?></a>
              </div>
              <div class="st-dropdown-content-group b-t d-flex justify-content-start">
                <?php if (is_admin()): ?>
                  <a class="btn btn-outline me-3 px-3" href="<?php echo base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt d-md-inline me-md-0 me-lg-2"></i> <span class="d-md-none d-lg-inline"><?php echo trans('logout') ?></span> </a>

                  <a class="btn btn-solid px-3" href="<?php echo base_url('admin/dashboard') ?>"><i class="fas fa-tachometer-alt  d-md-inline me-md-0 me-lg-2"></i> <span class="d-md-none d-lg-inline"><?php echo trans('dashboard') ?></span></a>

                <?php elseif (is_user()): ?>

                  <a class="btn btn-outline me-3 px-3" href="<?php echo base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt  d-md-inline me-md-0 me-lg-2"></i> <span class="d-md-none d-lg-inline"><?php echo trans('logout') ?></span> </a>


                  <?php $diff = date_difference(user()->created_at); ?>
                  <?php if (user()->email_verified == 0 && settings()->enable_email_verify == 1 && $diff < 2): ?>
                    <a class="btn btn-solid px-3" href="<?php echo base_url('auth/verify_email') ?>"><i class="far check-circle  d-md-inline me-md-0 me-lg-2"></i> <span class="d-md-none d-lg-inline"><?php echo trans('verify-account') ?></span></a>
                  <?php else: ?>
                    <a class="btn btn-solid px-3" href="<?php echo base_url('admin/dashboard/business') ?>"><i class="fas fa-tachometer-alt d-none d-md-inline me-md-0 me-lg-2"></i> <span class=" d-lg-inline"><?php echo trans('dashboard') ?></span></a>
                  <?php endif ?>

                <?php else: ?>
                  <a class="btn btn-outline me-3 px-3" href="<?php echo base_url('login') ?>"><i class="fas fa-sign-in-alt d-none d-md-inline me-md-0 me-lg-2"></i> <span class="d-md-none d-lg-inline"><?php echo trans('sign-in') ?></span> </a>

                  <a class="btn btn-solid px-3" href="<?php echo base_url('register') ?><?php if (settings()->trial_days != 0) {
                                                                                          echo '?trial=start';
                                                                                        } ?>"><i class="fas fa-user-plus d-none d-md-inline me-md-0 me-lg-2"></i> <span class="d-md-none d-lg-inline"><?php echo trans('create-account') ?></span></a>
                <?php endif ?>
              </div>
            </div>
          </div>
        </li>




      </ul>
    </div>

  </nav>

  <main class="overflow-hidden">