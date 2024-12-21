<?php
require_once '../config/database.php';

try {
    // Check if admin exists
    $stmt = $conn->query("SELECT COUNT(*) FROM registrar_users WHERE username = 'admin'");
    $adminExists = $stmt->fetchColumn();

    if (!$adminExists) {
        // Create admin account
        $username = 'admin';
        $password = password_hash('admin123', PASSWORD_DEFAULT); // Creates a secure hash of 'admin123'
        $fullName = 'System Administrator';
        $email = 'admin@school.edu';
        $role = 'admin';

        $sql = "INSERT INTO registrar_users (username, password, full_name, email, role) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $password, $fullName, $email, $role]);

        echo "Admin account created successfully!<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
        echo "<strong>Please change this password after logging in!</strong>";
    } else {
        echo "Admin account already exists!";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 