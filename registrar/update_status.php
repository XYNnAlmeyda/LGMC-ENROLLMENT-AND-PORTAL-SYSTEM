<?php
session_start();
require_once '../config/database.php';

// Check if registrar is logged in
if (!isset($_SESSION['registrar_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$status_id = $data['status_id'];
$status = $data['status'];
$remarks = $data['remarks'];
$registrar_id = $_SESSION['registrar_id'];

try {
    // First, get the student's email
    $sql = "SELECT s.email, s.first_name, s.last_name, es.reference_number 
            FROM students s 
            JOIN enrollment_status es ON s.student_id = es.student_id 
            WHERE es.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$status_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    // Update the enrollment status
    $updateSql = "UPDATE enrollment_status 
                  SET registration_status = ?, 
                      remarks = ?, 
                      registrar_id = ?,
                      updated_at = CURRENT_TIMESTAMP 
                  WHERE id = ?";

    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->execute([$status, $remarks, $registrar_id, $status_id]);

    // Send email notification
    if ($student && $student['email']) {
        $to = $student['email'];
        $subject = "Registration Status Update - " . ucfirst($status);
        
        $message = "Dear " . $student['first_name'] . " " . $student['last_name'] . ",\n\n";
        $message .= "Your registration (Reference Number: " . $student['reference_number'] . ") has been " . $status . ".\n\n";
        
        if ($status === 'approved') {
            $message .= "You may now proceed with the next steps of the enrollment process.\n";
        } elseif ($status === 'rejected') {
            $message .= "Reason for rejection: " . $remarks . "\n";
            $message .= "Please contact the registrar's office for more information.\n";
        }
        
        $message .= "\nBest regards,\nLGMC Registrar's Office";
        
        $headers = "From: noreply@lgmc.edu.ph";
        
        mail($to, $subject, $message, $headers);
    }

    echo json_encode(['success' => true]);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 