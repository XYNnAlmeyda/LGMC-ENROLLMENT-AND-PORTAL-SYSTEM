<?php
session_start();
require_once '../config/database.php';

// Check if logged in user is admin
if (!isset($_SESSION['registrar_role']) || $_SESSION['registrar_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$success_message = $error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    // Validate input
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        try {
            // Check if username or email already exists
            $stmt = $conn->prepare("SELECT user_id FROM registrar_users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                $error_message = "Username or email already exists!";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user
                $stmt = $conn->prepare("INSERT INTO registrar_users (username, password, full_name, email, role) VALUES (?, ?, ?, ?, ?)");
                
                if ($stmt->execute([$username, $hashed_password, $full_name, $email, $role])) {
                    $success_message = "Registrar account created successfully!";
                } else {
                    $error_message = "Error creating account!";
                }
            }
        } catch(PDOException $e) {
            $error_message = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Registrar Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Registrar Portal</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Create New Registrar Account</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($success_message): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Full Name</label>
                                <input type="text" name="full_name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="registrar">Registrar</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Create Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 