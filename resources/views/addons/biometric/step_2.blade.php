<!DOCTYPE html>
<html lang="en">
<head>
    <title>PeoplePro Biometric Attendance Auto Installer | Step-2</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('install-assets/images/favicon.ico') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('install-assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('install-assets/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('install-assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div class="col-md-6 offset-md-3">
        <div class='wrapper'>
            <header>
	            <img src="{{ asset('install-assets/images/logo.png') }}" alt="Logo"/>
                <h1 class="text-center">PeoplePro Biometric Attendance Auto Installer</h1>
            </header>
            <hr>
            <div class="content">
                <?php

                $passed = '';
                $ltext = '';
                if (version_compare(PHP_VERSION, '8.0') >= 0) {
                    $ltext .= '<i class="fa fa-check"></i>Your PHP Version is: ' . PHP_VERSION . '<br/>';
                    $passed .= '1';

                } else {
                    $ltext .= '<i class="fa fa-close"></i>PeoplePro needs at least PHP version  7.4, Your PHP Version is: ' . PHP_VERSION . '<br/>';
                    $passed .= '0';
                }

                if (extension_loaded('PDO')) {
                    $ltext .= '<i class="fa fa-check"></i>PDO is installed on your server.' . '<br/>';
                    $passed .= '1';
                } else {
                    $ltext = '<i class="fa fa-close"></i>PDO is not installed on your server.' . '<br/>';
                    $passed .= '0';
                }

                if (extension_loaded('pdo_mysql')) {
                    $ltext .= '<i class="fa fa-check"></i>PDO MySQL driver is enabled on your server.' . '<br/>';
                    $passed .= '1';
                } else {
                    $ltext .= '<i class="fa fa-close"></i>PDO MySQL driver is not enabled on your server.' . '<br/>';
                    $passed .= '0';
                }

                if (extension_loaded('curl')) {
                    $ltext .= '<i class="fa fa-check"></i>php-curl extension is enabled on your server.' . '<br/>';
                    $passed .= '1';
                } else {
                    $ltext .= '<i class="fa fa-close"></i>php-curl extension is not enabled on your server.' . '<br/>';
                    $passed .= '0';
                }
                if (extension_loaded('fileinfo')) {
                    $ltext .= '<i class="fa fa-check"></i>php fileinfo extension is installed on your server.' . '<br/>';
                    $passed .= '1';
                } else {
                    $ltext = '<i class="fa fa-close"></i>php fileinfo extension is not installed on your server.' . '<br/>';
                    $passed .= '0';
                }

                if (extension_loaded('gd')) {
                    $ltext .= '<i class="fa fa-check"></i>gd extension is installed on your server.' . '<br/>';
                    $passed .= '1';
                } else {
                    $ltext = '<i class="fa fa-close"></i>gd extension is not installed on your server.' . '<br/>';
                    $passed .= '0';
                }

                if (extension_loaded('zip')) {
                    $ltext .= '<i class="fa fa-check"></i>zip extension is installed on your server.' . '<br/>';
                    $passed .= '1';
                } else {
                    $ltext = '<i class="fa fa-close"></i>zip extension is not installed on your server.' . '<br/>';
                    $passed .= '0';
                }
                ?>

                <?php if ($passed == '1111111'): ?>
                    <br/><?php echo $ltext; ?><br/>
                    <h4>Great! System Test Completed. You can install on your server. Click Continue For Next Step.</h4>
                    <a href="{{ route('biometric-install-step-3') }}" class="btn btn-primary">Continue</a>

                <?php else: ?>

                <br/><?php echo $ltext; ?><br/>Sorry. The requirements to install is not available on your server. Please contact with us- hello@lion-coders.com with this code- <?php echo $passed; ?> Or contact with your server administrator.<br><br>
                <a href="#" class="btn btn-primary disabled">Correct The Problem To Continue</a>

                <?php endif ?>

            </div>
            <hr>
            <footer>Copyright &copy; lionCoders. All Rights Reserved.</footer>
        </div>
    </div>
</body>
</html>
