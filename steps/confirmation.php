<?php
// Only start session if one hasn't been started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Store the check result in a variable instead of redirecting immediately
$should_redirect = !isset($_SESSION['success_message']);

// Get session variables
$success_message = $_SESSION['success_message'] ?? '';
$student_email = $_SESSION['student_email'] ?? '';

// If we need to redirect, do it using JavaScript instead
if ($should_redirect) {
    echo "<script>window.location.href = 'registration_form.php';</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .confirmation-container {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 90%;
            margin: 2rem auto;
        }
        .success-icon {
            color: #28a745;
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }
        .btn-download {
            background-color: #28a745;
            color: white;
            padding: 12px 35px;
            border-radius: 30px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        .btn-download:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
            color: white;
        }
        .email-confirmation {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 2rem 0;
        }
        .alert-info {
            background-color: #e7f5ff;
            border: none;
            border-radius: 10px;
            padding: 1.5rem;
        }
        .alert-info ol {
            margin-bottom: 0;
        }
        .alert-info li {
            margin-bottom: 0.5rem;
        }
        .alert-info li:last-child {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="confirmation-container text-center">
        <i class="fas fa-check-circle success-icon"></i>
        <h2 class="mb-4">Registration Successful!</h2>

        <div class="alert alert-info" role="alert">
            <h5 class="mb-3">Next Steps:</h5>
            <ol class="text-start">
                <li>Download and print your registration form</li>
                <li>Prepare required documents</li>
                <li>Visit the registrar's office for document verification</li>
                <li>Proceed with payment at the cashier's office</li>
            </ol>
        </div>

        <div class="mt-4">
            <a href="generate_pdf.php" class="btn btn-download" target="_blank">
                <i class="fas fa-download me-2"></i> Download Registration Form
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Clear session messages after displaying
unset($_SESSION['success_message']);
?>