<?php
session_start();
require_once '../config/database.php';

// Add authentication check for registrar
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'registrar') {
    header('Location: ../login.php');
    exit();
}

// Handle approval action
if (isset($_POST['action']) && isset($_POST['student_id'])) {
    $action = $_POST['action'];
    $student_id = $_POST['student_id'];
    
    $stmt = $conn->prepare("UPDATE enrollment_status SET 
        registration_status = ?, 
        approved_by = ?, 
        approved_at = CURRENT_TIMESTAMP 
        WHERE student_id = ?");
    
    $status = ($action === 'approve') ? 'approved' : 'rejected';
    $stmt->execute([$status, $_SESSION['user_id'], $student_id]);
    
    // Redirect to avoid form resubmission
    header('Location: pending_registrations.php');
    exit();
}

// Get pending registrations
$stmt = $conn->prepare("
    SELECT s.*, es.registration_status, es.created_at
    FROM students s
    JOIN enrollment_status es ON s.student_id = es.student_id
    WHERE es.registration_status = 'pending'
    ORDER BY es.created_at DESC
");
$stmt->execute();
$pending_registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Registrations - Registrar View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Pending Registrations</h2>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Course</th>
                        <th>Submitted Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_registrations as $registration): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($registration['student_id']); ?></td>
                        <td>
                            <?php echo htmlspecialchars($registration['last_name'] . ', ' . 
                                                      $registration['first_name'] . ' ' . 
                                                      $registration['middle_name']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($registration['course']); ?></td>
                        <td><?php echo date('M d, Y g:i A', strtotime($registration['created_at'])); ?></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary" 
                                        onclick="viewDetails(<?php echo $registration['student_id']; ?>)">
                                    View Details
                                </button>
                                <form method="POST" class="d-inline" onsubmit="return confirm('Approve this registration?');">
                                    <input type="hidden" name="student_id" value="<?php echo $registration['student_id']; ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                </form>
                                <form method="POST" class="d-inline" onsubmit="return confirm('Reject this registration?');">
                                    <input type="hidden" name="student_id" value="<?php echo $registration['student_id']; ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for viewing details -->
    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Registration Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function viewDetails(studentId) {
        const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
        fetch(`get_student_details.php?id=${studentId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('modalContent').innerHTML = html;
                modal.show();
            });
    }
    </script>
</body>
</html> 