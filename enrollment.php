<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize enrollment step if not set
if (!isset($_SESSION['enrollment_step'])) {
    $_SESSION['enrollment_step'] = 1;
}

$current_step = $_SESSION['enrollment_step'];
$total_steps = 7;

$step_titles = [
    1 => "Online Registration ",
    2 => "Confirmation",
    3 => "Printing of Enrollment Form",
    4 => "Submission to registrar (manual)",
    5 => "Payment of Enrollment (manual)",
    6 => "Officially enrolled",
    7 => "Student portal account",
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGMC Enrollment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8f0;
        }

        .header-section {
            background: linear-gradient(135deg, #004d00 0%, #006600 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            text-align: center;
        }

        .header-content h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header-content p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .step-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
            position: relative;
            padding: 0 20px;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 40px;
            right: 40px;
            height: 2px;
            background: #e0e0e0;
            z-index: 1;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 80px;
        }

        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid #ddd;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .step.active .step-number {
            background-color: #004d00;
            border-color: #004d00;
            color: white;
            box-shadow: 0 0 0 3px rgba(0, 77, 0, 0.2);
        }

        .step.completed .step-number {
            background-color: #006600;
            border-color: #006600;
            color: white;
        }

        .step-title {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
            font-weight: 500;
        }

        .step.active .step-title {
            color: #004d00;
            font-weight: 600;
        }

        .office-location {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            border-left: 4px solid #004d00;
        }

        .office-location i {
            color: #004d00;
            margin-right: 8px;
        }

        .step-content {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .btn-next {
            background-color: #004d00;
            color: white;
            padding: 10px 30px;
            border-radius: 25px;
            border: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-next:hover {
            background-color: #006600;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .step-indicator {
                overflow-x: auto;
                padding-bottom: 1rem;
            }
            
            .step {
                min-width: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="header-section">
        <div class="container">
            <div class="header-content">
                <h1>LEON GUINTO MEMORIAL COLLEGE</h1>
                <p>Online Enrollment System</p>
            </div>
        </div>
    </div>
            <!-- Step Content -->
            <div class="step-content">
                <?php
                switch($current_step) {
                    case 1:
                        include 'steps/registration_form.php';
                        break;
                    case 2:
                        include 'steps/confirmation.php';
                        break;
                    case 3:
                        include 'steps/status.php';
                        break;
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 