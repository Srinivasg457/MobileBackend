<?php
session_start();
error_reporting(0);

$db_config_path = '../application/config/database.php';

if (!isset($_SESSION["license_code"])) {
    $_SESSION["error"] = "Invalid purchase code!";
    header("Location: index.php");
    exit();
}

if (isset($_POST["btn_admin"])) {

    $_SESSION["db_host"] = $_POST['db_host'];
    $_SESSION["db_name"] = $_POST['db_name'];
    $_SESSION["db_user"] = $_POST['db_user'];
    $_SESSION["db_password"] = $_POST['db_password'];


    /* Database Credentials */
    defined("DB_HOST") ? null : define("DB_HOST", $_SESSION["db_host"]);
    defined("DB_USER") ? null : define("DB_USER", $_SESSION["db_user"]);
    defined("DB_PASS") ? null : define("DB_PASS", $_SESSION["db_password"]);
    defined("DB_NAME") ? null : define("DB_NAME", $_SESSION["db_name"]);

    /* Connect */
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $connection->query("SET CHARACTER SET utf8");
    $connection->query("SET NAMES utf8");

    /* check connection */
    if (mysqli_connect_errno()) {
        $error = 0;
    } else {
        
        mysqli_query($connection, "UPDATE settings SET version = '2.8' WHERE id = 1;");

        mysqli_query($connection, "ALTER TABLE `settings` ADD `mercado_payment` INT(11) NULL DEFAULT '0' AFTER `razorpay_key_secret`, ADD `mercado_currency` VARCHAR(255) NULL AFTER `mercado_payment`, ADD `mercado_api_key` VARCHAR(255) NULL AFTER `mercado_currency`, ADD `mercado_token` VARCHAR(255) NULL AFTER `mercado_api_key`;");

        mysqli_query($connection, "ALTER TABLE `settings` ADD `global_twilio` INT(11) NULL DEFAULT '0' AFTER `trial_days`, ADD `twillo_account_sid` VARCHAR(255) NULL AFTER `global_twilio`, ADD `twillo_auth_token` VARCHAR(255) NULL AFTER `twillo_account_sid`, ADD `twillo_number` VARCHAR(255) NULL AFTER `twillo_auth_token`;");

        mysqli_query($connection, "ALTER TABLE `users` ADD `twillo_account_sid` VARCHAR(255) NULL AFTER `razorpay_key_secret`, ADD `twillo_auth_token` VARCHAR(255) NULL AFTER `twillo_account_sid`, ADD `twillo_number` VARCHAR(255) NULL AFTER `twillo_auth_token`, ADD `enable_sms_notify` VARCHAR(255) NULL DEFAULT '0' AFTER `twillo_number`;");

        mysqli_query($connection, "ALTER TABLE `users` ADD `enable_sms_alert` VARCHAR(255) NULL DEFAULT '0' AFTER `enable_sms_notify`;");


        // import database table
        $query = '';
          $sqlScript = file('sql/workflows.sql');
          foreach ($sqlScript as $line) {
            
            $startWith = substr(trim($line), 0 ,2);
            $endWith = substr(trim($line), -1 ,1);
            
            if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
              continue;
            }
              
            $query = $query . $line;
            if ($endWith == ';') {
              mysqli_query($connection, $query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
              $query= '';   
            }
        }

        mysqli_query($connection, "INSERT INTO `lang_values` (`type`, `label`, `keyword`, `english`) VALUES
        ('user', 'Workflows', 'workflows', 'Workflows'),
        ('user', 'Add new workflow', 'add-new-workflow', 'Add new workflow'),
        ('user', 'Edit Workflow', 'edit-workflow', 'Edit Workflow'),
        ('user', 'What our awesome community is talking about', 'blog-title', 'What our awesome community is talking about'),
        ('user', 'Company', 'company', 'Company'),
        ('user', 'Product', 'product', 'Product'),
        ('user', 'Log in to', 'log-in-to', 'Log in to'),
        ('user', 'Dont have an account yet?', 'dont-have-an-account-yet', 'Dont have an account yet?'),
        ('user', 'Welcome to ', 'welcome-to', 'Welcome to '),
        ('user', 'Log in to your account', 'log-in-to-your-account', 'Log in to your account'),
        ('user', 'Forgot', 'forgot', 'Forgot'),
        ('user', 'Weve got you covered', 'weve-got-you-covered', 'We have got you covered'),
        ('user', 'Enter your email bellow to retrieve your account or', 'enter-your-email-bellow-to-retrieve-your-account-or', 'Enter your email bellow to retrieve your account or'),
        ('user', 'Your registered email', 'your-registered-email', 'Your registered email'),
        ('user', 'Pricing Plans', 'pricing-plans', 'Pricing Plans'),
        ('user', 'Try It Free', 'try-it-free', 'Try It Free'),
        ('user', 'If you still have any question contact with us.', 'if-you-still-have-any-question-contact-with-us', 'If you still have any question contact with us.'),
        ('user', 'Do you have', 'do-you-have', 'Do you have'),
        ('user', 'questions?', 'questions', 'questions?'),
        ('user', 'Try', 'try', 'Try'),
        ('user', 'free for 15 days!', 'free-for-15-days', 'free for 15 days!'),
        ('user', 'Ready to get started?', 'ready-to-get-started', 'Ready to get started?'),
        ('user', 'Here are the answers to some of the most common questions we hear from our appreciated customers.', 'faq-page-title', 'Here are the answers to some of the most common questions we hear from our appreciated customers.'),
        ('user', 'Common Questions', 'common-questions', 'Common Questions'),
        ('user', 'Contact us', 'contact-us', 'Contact us'),
        ('user', 'Get in touch and let us know how we can help. Fill out the form and we’ll be in touch as soon as possible.', 'contact-title', 'Get in touch and let us know how we can help. Fill out the form and we’ll be in touch as soon as possible.'),
        ('user', 'Thank you!', 'thank-you', 'Thank you!'),
        ('user', 'Your message has been send, we will contact you as soon as possible.', 'your-message-has-been-send-we-will-contact-you-as-soon-as-possible', 'Your message has been send, we will contact you as soon as possible.'),
        ('user', 'Log In', 'log-in', 'Log In'),
        ('user', 'free-for-', 'free-for-', 'free for'),
        ('user', 'Payment gateways are only available with Extended License', 'payment-gateways-are-only-available-with-extended-license', 'Payment gateways are only available with Extended License'),
        ('user', 'Sandbox', 'sandbox', 'Sandbox'),
        ('user', 'Live', 'live', 'Live'),
        ('user', 'Stripe', 'stripe', 'Stripe'),
        ('user', 'Mercado Pogo', 'mercado-pogo', 'Mercado Pogo'),
        ('user', 'Public key', 'public-key', 'Public key'),
        ('user', 'Access Token', 'access-token', 'Access Token'),
        ('user', 'Twilio SMS Settings', 'twilio-sms-settings', 'Twilio SMS Settings'),
        ('user', 'Enable Globally', 'enable-globally', 'Enable Globally'),
        ('user', 'Enable to activate Twilio sms for admin and user side.', 'enable-globally-twilio', 'Enable to activate Twilio sms for admin and user side.'),
        ('user', 'Account SID', 'account-sid', 'Account SID'),
        ('user', 'Auth Token', 'auth-token', 'Auth Token'),
        ('user', 'Sender Number (Twillo)', 'sender-number-tw', 'Sender Number (Twillo)'),
        ('user', 'Enable Booking Confirmation SMS', 'enable-booking-confirmation-sms', 'Enable Booking Confirmation SMS'),
        ('user', 'Enable to send booking notification message to your customers, after make a appointment.', 'enable-booking-con-title', ' Enable to send booking notification message to your customers, after make a appointment.'),
        ('user', 'Import Product/Service', 'import-productservice', 'Import Product/Service'),
        ('user', 'Import', 'import', 'Import'),
        ('user', 'Upload csv file', 'upload-csv-file', 'Upload csv file'),
        ('user', 'Upload', 'upload', 'Upload'),
        ('user', 'Download CSV Template', 'download-csv-template', 'Download CSV Template'),
        ('user', 'I agree with the', 'i-agree-with-the', 'I agree with the'),
        ('user', 'Import Products', 'import-products', 'Import Products'),
        ('user', 'Import Customers', 'import-customers', 'Import Customers'),
        ('user', 'SMALL BUSINESS ACCOUNTING SOFTWARE', 'small-business-accounting-software', 'SMALL BUSINESS ACCOUNTING SOFTWARE'),
        ('user', 'The features you need All in one place', 'home-feature-one-place', 'The <span class=\"text-primary\">features</span> you need <br>  All in one place'),
        ('user', 'Invoicing', 'invoicing', 'Invoicing'),
        ('user', 'Create professional invoices in minutes. Automatically add tracked time and expenses, calculate taxes, and customize your payment options.', 'invoicing-title', 'Create professional invoices in minutes. Automatically add tracked time and expenses, calculate taxes, and customize your payment options.'),
        ('user', 'Billing and <br>Payments', 'billing-and-payments', 'Billing and <br>Payments'),
        ('user', 'Bill fast, get paid even faster, and automate the rest with recurring invoices, online payments, and late payment reminders.', 'billing-and-payments-title', 'Bill fast, get paid even faster, and automate the rest with recurring invoices, online payments, and late payment reminders.'),
        ('user', 'Keep track of your expenses with mobile receipt scanning, bank account imports, and automated expense categorization.', 'expenses-title', 'Keep track of your expenses with mobile receipt scanning, bank account imports, and automated expense categorization.'),
        ('user', 'Generates detailed financial reports, helping your businesses gain insights into revenue, expenses, profits, and other key metrics. These insights aid in informed decision-making.', 'reports-title', 'Generates detailed financial reports, providing insights into revenue, expenses, profits, and key metrics to aid informed decision-making.'),
        ('user', 'More Features', 'more-features', 'More Features');");




      /* close connection */
      mysqli_close($connection);

      $redir = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
      $redir .= "://" . $_SERVER['HTTP_HOST'];
      $redir .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);
      $redir = str_replace('updates/update_v2.7_v2.8/', '', $redir);
      header("refresh:5;url=" . $redir);
      $success = 1;
    }



}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accufy &bull; Update Installer</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/libs/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,500,600,700&display=swap" rel="stylesheet">
    <script src="assets/js/jquery-1.12.4.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-12 col-md-offset-2">

                <div class="row">
                    <div class="col-sm-12 logo-cnt">
                        <p>
                           <img src="assets/img/logo.png" alt="">
                       </p>
                       <h1>Welcome to the update installer</h1>
                   </div>
               </div>

               <div class="row">
                <div class="col-sm-12">

                    <div class="install-box">

                        <div class="steps">
                            <div class="step-progress">
                                <div class="step-progress-line" data-now-value="100" data-number-of-steps="3" style="width: 100%;"></div>
                            </div>
                            <div class="step" style="width: 50%">
                                <div class="step-icon"><i class="fa fa-arrow-circle-right"></i></div>
                                <p>Start</p>
                            </div>
                            <div class="step active" style="width: 50%">
                                <div class="step-icon"><i class="fa fa-database"></i></div>
                                <p>Database</p>
                            </div>
                        </div>

                        <div class="messages">
                            <?php if (isset($message)) { ?>
                            <div class="alert alert-danger">
                                <strong><?php echo htmlspecialchars($message); ?></strong>
                            </div>
                            <?php } ?>
                            <?php if (isset($success)) { ?>
                            <div class="alert alert-success">
                                <strong>Completing Updates ... <i class="fa fa-spinner fa-spin fa-2x fa-fw"></i> Please wait 5 second </strong>
                            </div>
                            <?php } ?>
                        </div>

                        <div class="step-contents">
                            <div class="tab-1">
                                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                                    <div class="tab-content">
                                        <div class="tab_1">
                                            <h1 class="step-title">Database</h1>
                                            <div class="form-group">
                                                <label for="email">Host</label>
                                                <input type="text" class="form-control form-input" name="db_host" placeholder="Host"
                                                value="<?php echo isset($_SESSION["db_host"]) ? $_SESSION["db_host"] : 'localhost'; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Database Name</label>
                                                <input type="text" class="form-control form-input" name="db_name" placeholder="Database Name" value="<?php echo @$_SESSION["db_name"]; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Username</label>
                                                <input type="text" class="form-control form-input" name="db_user" placeholder="Username" value="<?php echo @$_SESSION["db_user"]; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Password</label>
                                                <input type="password" class="form-control form-input" name="db_password" placeholder="Password" value="<?php echo @$_SESSION["db_password"]; ?>">
                                            </div>

                                        </div>
                                    </div>

                                    <div class="buttons">
                                        <a href="index.php" class="btn btn-success btn-custom pull-left">Prev</a>
                                        <button type="submit" name="btn_admin" class="btn btn-success btn-custom pull-right">Finish</button>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
            </div>


        </div>


    </div>


</div>

<?php

unset($_SESSION["error"]);
unset($_SESSION["success"]);

?>

</body>
</html>

