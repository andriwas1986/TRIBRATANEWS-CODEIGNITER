<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= esc($title); ?> - Login Aman</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <style id="antiClickjack">body{display:none !important;}</style>
    <script type="text/javascript">
        if (self === top) {
            var antiClickjack = document.getElementById("antiClickjack");
            antiClickjack.parentNode.removeChild(antiClickjack);
        } else {
            top.location = self.location;
        }
    </script>

    <link rel="shortcut icon" type="image/png" href="<?= getFavicon(); ?>"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/admin/plugins/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://ik.imagekit.io/d3nxlzdjsu/login%20web.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }

        .login-box {
            width: 420px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px 35px;
            box-shadow: 0 25px 45px rgba(0,0,0,0.2);
            color: white;
        }

        .login-logo { text-align: center; margin-bottom: 25px; }
        
        /* --- UKURAN LOGO DIPERKECIL DI SINI --- */
        .logo-img {
            max-width: 150px; /* Sebelumnya 230px, sekarang 150px */
            height: auto;
            display: block;
            margin: 0 auto;
            filter: drop-shadow(0 5px 5px rgba(0,0,0,0.3));
        }

        .login-box-msg {
            margin-bottom: 25px;
            text-align: center;
            color: rgba(255,255,255,0.8);
        }

        .form-group { position: relative; margin-bottom: 25px; }

        .form-control {
            background: transparent !important;
            border: none;
            border-bottom: 2px solid rgba(255,255,255,0.3);
            border-radius: 0;
            color: #fff !important;
            padding-left: 5px;
            box-shadow: none;
            height: 45px;
            transition: 0.3s;
        }

        /* Fix Autofill CSS */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px rgba(255, 255, 255, 0) inset !important;
            -webkit-text-fill-color: #fff !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .form-control:focus {
            border-bottom: 2px solid #00c6ff;
        }

        .form-control::placeholder { color: rgba(255,255,255,0.5); }

        .form-control-feedback {
            color: rgba(255,255,255,0.6);
            line-height: 45px;
            height: 45px;
            right: 0;
            pointer-events: none;
            position: absolute;
            top: 0;
        }

        .toggle-password {
            cursor: pointer;
            pointer-events: auto !important;
            z-index: 10;
        }
        .toggle-password:hover { color: #fff; }

        .btn-primary {
            background-image: linear-gradient(to right, #00c6ff 0%, #0072ff 100%);
            border: none;
            border-radius: 50px;
            height: 50px;
            font-weight: 600;
            box-shadow: 0 10px 20px rgba(0, 114, 255, 0.3);
            width: 100%;
            margin-top: 15px;
        }
        .btn-primary:hover { transform: translateY(-2px); }

        .btn-home {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            display: block;
            margin-top: 20px;
            font-size: 14px;
        }
        .btn-home:hover { color: #fff; text-decoration: none; }

        .g-recaptcha {
            margin-bottom: 15px;
            display: flex;
            justify-content: center;
        }
        
        .alert { border-radius: 10px; font-size: 14px; border: none; margin-bottom: 20px;}
        .alert-danger { background: rgba(220, 53, 69, 0.9); color: white; }
    </style>
</head>
<body>

<div class="login-box">
    <div class="login-logo">
        <a href="<?= adminUrl('login'); ?>">
            <img src="<?= base_url('assets/img/logo-login.png'); ?>" alt="Logo" class="logo-img">
        </a>
    </div>
    
    <p class="login-box-msg">Login Pengguna</p>

    <?= view('admin/includes/_messages'); ?>

    <form action="<?= adminUrl('login-post'); ?>" method="post" autocomplete="off" onsubmit="return validateCaptcha()">
        <?= csrf_field(); ?>
        
        <div class="form-group has-feedback">
            <span class="fas fa-envelope form-control-feedback"></span>
            <input type="email" name="email" class="form-control" placeholder="<?= trans("email"); ?>" value="<?= old('email'); ?>" required>
        </div>

        <div class="form-group has-feedback">
            <span id="icon-pass" class="fas fa-eye-slash form-control-feedback toggle-password" onclick="togglePassword()"></span>
            <input type="password" name="password" id="passwordInput" class="form-control" placeholder="<?= trans("password"); ?>" required>
        </div>

        <?php if (!empty($generalSettings->recaptcha_site_key)): ?>
            <div class="g-recaptcha" data-sitekey="<?= $generalSettings->recaptcha_site_key; ?>"></div>
        <?php endif; ?>
        
        <div id="captcha-error" style="color: #ff6b6b; font-size: 12px; text-align: center; display: none; margin-bottom: 10px;">Mohon centang verifikasi keamanan.</div>
        
        <div class="row">
            <div class="col-xs-12 col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    <?= trans("login"); ?> <i class="fas fa-shield-alt ml-2"></i>
                </button>
            </div>
        </div>
    </form>

    <div class="text-center">
        <a class="btn-home" href="<?= langBaseUrl(); ?>">
            <i class="fas fa-arrow-left"></i> <?= trans("btn_goto_home"); ?>
        </a>
    </div>
</div>

<script src="<?= base_url('assets/admin/js/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/admin/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>

<script>
    function togglePassword() {
        var x = document.getElementById("passwordInput");
        var icon = document.getElementById("icon-pass");
        
        if (x.type === "password") {
            x.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            x.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }

    function validateCaptcha() {
        var recaptchaElement = document.querySelector('.g-recaptcha');
        if (recaptchaElement) {
            var response = grecaptcha.getResponse();
            if(response.length == 0) {
                document.getElementById('captcha-error').style.display = 'block';
                return false;
            }
        }
        return true;
    }
</script>

</body>
</html>