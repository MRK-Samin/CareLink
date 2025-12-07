<?php
require_once 'backend/includes/config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: backend/admin/dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = sha1($_POST['password']);
    
    $query = "SELECT * FROM his_admin WHERE ad_email = '$username' AND ad_pwd = '$password'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $admin['ad_id'];
        $_SESSION['admin_name'] = $admin['ad_fname'] . ' ' . $admin['ad_lname'];
        $_SESSION['admin_email'] = $admin['ad_email'];
        header("Location: backend/admin/dashboard.php");
        exit();
    } else {
        $error = "Invalid administrator credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Login - CareLink HMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0f0c29;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated background particles */
        body::before,
        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 15s infinite ease-in-out;
        }
        
        body::before {
            background: #667eea;
            top: -100px;
            left: -100px;
            animation-delay: 0s;
        }
        
        body::after {
            background: #764ba2;
            bottom: -100px;
            right: -100px;
            animation-delay: 5s;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(50px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-30px, 30px) scale(0.9);
            }
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 50px 40px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.8s ease;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .admin-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .logo {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .logo i {
            font-size: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            display: block;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        .logo h1 {
            font-size: 32px;
            color: #1a1a2e;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .logo p {
            color: #666;
            font-size: 15px;
        }
        
        .security-note {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .security-note i {
            font-size: 24px;
            color: #667eea;
        }
        
        .security-note p {
            color: #666;
            font-size: 13px;
            margin: 0;
        }
        
        .error-msg {
            background: linear-gradient(135deg, #fee, #fcc);
            color: #c33;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-left: 4px solid #c33;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .form-group input {
            width: 100%;
            padding: 16px 50px 16px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            font-family: inherit;
            background: white;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }
        
        .input-icon {
            position: absolute;
            right: 20px;
            top: 46px;
            color: #999;
            font-size: 18px;
        }
        
        .btn-login {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
            margin-top: 30px;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.5);
        }
        
        .btn-login:active {
            transform: translateY(-1px);
        }
        
        .login-footer {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px solid #f0f0f0;
            text-align: center;
        }
        
        .demo-credentials {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            font-size: 13px;
        }
        
        .demo-credentials strong {
            display: block;
            color: #1a1a2e;
            margin-bottom: 12px;
            font-size: 14px;
        }
        
        .demo-credentials p {
            color: #666;
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .demo-credentials i {
            color: #667eea;
            width: 20px;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 40px 25px;
            }
            
            .logo h1 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <span class="admin-badge">
                <i class="fas fa-shield-alt"></i> Administrator Access
            </span>
            <i class="fas fa-user-shield"></i>
            <h1>Admin Portal</h1>
            <p>CareLink Hospital Management System</p>
        </div>
        
        <div class="security-note">
            <i class="fas fa-lock"></i>
            <p><strong>Secure Access:</strong> This portal is restricted to authorized administrators only. All login attempts are logged.</p>
        </div>
        
        <?php if ($error): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?php echo $error; ?></span>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" id="adminLoginForm">
            <div class="form-group">
                <label>
                    <i class="fas fa-envelope"></i>
                    Administrator Email
                </label>
                <input type="email" name="username" required 
                       placeholder="Enter your admin email"
                       autocomplete="email">
                <i class="fas fa-user-shield input-icon"></i>
            </div>
            
            <div class="form-group">
                <label>
                    <i class="fas fa-key"></i>
                    Secure Password
                </label>
                <input type="password" name="password" required 
                       placeholder="Enter your password"
                       autocomplete="current-password"
                       id="password-input">
                <i class="fas fa-lock input-icon"></i>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> 
                Secure Login
            </button>
        </form>
        
        <div class="login-footer">
            <div class="demo-credentials">
                <strong><i class="fas fa-info-circle"></i> Demo Credentials</strong>
                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> admin@ccbd.com</p>
                <p><i class="fas fa-key"></i> <strong>Password:</strong> admin123</p>
            </div>
        </div>
    </div>
    
    <script>
        // Add loading animation on submit
        document.getElementById('adminLoginForm').addEventListener('submit', function() {
            const btn = document.querySelector('.btn-login');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
            btn.disabled = true;
        });
        
        // Log access attempt (for demonstration)
        console.log('%c ADMINISTRATOR ACCESS PORTAL ', 'background: linear-gradient(135deg, #667eea, #764ba2); color: white; font-size: 14px; padding: 10px; border-radius: 5px;');
        console.log('%c This is a secure area. Unauthorized access is prohibited. ', 'color: #c33; font-size: 12px; padding: 5px;');
    </script>
</body>
</html>