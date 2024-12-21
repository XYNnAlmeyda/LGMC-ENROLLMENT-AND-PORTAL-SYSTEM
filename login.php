<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            $_SESSION['enrollment_step'] = 1;
            
            header("Location: enrollment.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGMC Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8f0;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
        }
        .login-form {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-header img {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .btn-login {
            background-color: #004d00;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background-color: #006600;
            transform: translateY(-2px);
        }
        .error-message {
            color: #dc3545;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <div class="login-header">
                <img src="lgmc_logo.png" alt="LGMC Logo">
                <h2>LGMC Enrollment System</h2>
                <p class="text-muted">Please login to continue</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-login mb-3">Login</button>
            </form>

            <div class="text-center">
                <p>Don't have an account? 
                    <a href="register.php" class="text-success fw-bold text-decoration-none">Register here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
