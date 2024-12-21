<?php
require_once '../config/database.php';

try {
    // New password will be 'admin123'
    $new_password = 'admin123';
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    // Update admin password
    $sql = "UPDATE registrar_users SET password = ? WHERE username = 'admin'";
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([$hashed_password]);
    
    if ($result) {
        echo "Admin password has been reset successfully!<br>";
        echo "New login credentials:<br>";
        echo "Username: admin<br>";
        echo "Password: admin123<br>";
        
        // For debugging: show the hashed password
        echo "<br>Debug - Hashed password: " . $hashed_password;
    } else {
        echo "Failed to reset password.";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 