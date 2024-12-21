<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    try {
        // Debug: Print submitted credentials (remove in production)
        echo "Attempting login with username: " . $username . "<br>";
        
        $sql = "SELECT * FROM registrar_users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Debug: Check if user was found (remove in production)
        if ($user) {
            echo "User found in database<br>";
            if (password_verify($password, $user['password'])) {
                echo "Password verified successfully<br>";
                $_SESSION['registrar_id'] = $user['user_id'];
                $_SESSION['registrar_name'] = $user['full_name'];
                $_SESSION['registrar_role'] = $user['role'];
                
                // Update last login time
                $updateSql = "UPDATE registrar_users SET last_login = CURRENT_TIMESTAMP WHERE user_id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->execute([$user['user_id']]);
                
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Password verification failed<br>";
                $error = "Invalid username or password";
            }
        } else {
            echo "No user found with this username<br>";
            $error = "Invalid username or password";
        }
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Registrar Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>