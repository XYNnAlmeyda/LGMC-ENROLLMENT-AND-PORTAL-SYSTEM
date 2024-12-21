<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGMC Enrollment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f8f0;
            line-height: 1.6;
        }

        .header-section {
            background: linear-gradient(135deg, #004d00 0%, #006600 100%);
            color: white;
            padding: 40px 20px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .header-section h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header-section h2 {
            font-weight: 500;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .header-section .btn-primary {
            background-color: #ffffff;
            color: #004d00;
            border: none;
            padding: 10px 30px;
            font-weight: 600;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .header-section .btn-primary:hover {
            background-color: #e6ffe6;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .step-box {
            border: 2px solid #004d00;
            border-radius: 15px;
            padding: 25px;
            margin: 15px 0;
            background-color: #ffffff;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .step-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .step-box.active {
            background-color: #e6ffe6;
            border-color: #006600;
        }

        .step-number {
            font-size: 28px;
            font-weight: bold;
            color: #004d00;
            margin-bottom: 10px;
        }

        .requirements-box {
            background: linear-gradient(135deg, #004d00 0%, #006600 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin: 15px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .requirements-box h4 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 10px;
        }

        .requirements-box ul {
            padding-left: 20px;
        }

        .requirements-box li {
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 2rem;
            }
            .header-section h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="header-section text-center">
        <img src="lgmc_logo.png" alt="LGMC Logo" style="max-height: 100px;">
        <h1>LEON GUINTO MEMORIAL COLLEGE, INC.</h1>
        <h2>HIGHER EDUCATION DEPARTMENT</h2>
        <a href="login.php" class="btn btn-primary mt-3">Login Here</a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <!-- Enrollment Steps -->
                <?php
                $steps = [
                    1 => [
                        'title' => 'ADMISSION',
                        'office' => "REGISTRAR'S OFFICE/ADMISSION OFFICE",
                        'substeps' => [
                            '1.1. Issuance of Pre-Registration Form',
                            '1.2. Receiving & Checking of PR Form',
                            '1.3. Receiving & Checking of Requirements for Enrollment'
                        ]
                    ],
                    2 => [
                        'title' => 'ADVISING/EVALUATING',
                        'office' => 'COLLEGE DEAN',
                        'substeps' => [
                            '2.1. Advising and Evaluation of Subjects to be taken'
                        ]
                    ],
                    3 => [
                        'title' => 'PRINTING OF ENROLLMENT FORMS',
                        'office' => "REGISTRAR'S OFFICE",
                        'substeps' => [
                            '3.1. Printing of Enrollment Form'
                        ]
                    ],
                    4 => [
                        'title' => 'ASSESSMENT OF FEES',
                        'office' => 'ACCOUNTING OFFICE',
                        'substeps' => [
                            '4.1. Submission of Enrollment Form for Assessment of Fees'
                        ]
                    ],
                    5 => [
                        'title' => 'PAYMENT OF FEES',
                        'office' => 'CASHIER OFFICE',
                        'substeps' => [
                            "5.1. Payment and acceptance of school's Official Receipt"
                        ]
                    ],
                    6 => [
                        'title' => 'VERIFICATION & CONFIRMATION OF ENROLLMENT',
                        'office' => "REGISTRAR'S OFFICE",
                        'substeps' => [
                            "6.1. Student submission of Registrar's Copy of Enrollment Form"
                        ]
                    ],
                    7 => [
                        'title' => 'CLASS CARD ISSUANCE',
                        'office' => 'ACCOUNTING OFFICE',
                        'substeps' => [
                            '7.1. Issuance of Class Card',
                            '7.2. Receiving of Enrollment Form (Accounting Copy)'
                        ]
                    ],
                    8 => [
                        'title' => 'ID PRINTING & ISSUANCE',
                        'office' => 'ICT DEPARTMENT',
                        'substeps' => [
                            '8.1. ID Printing and Issuance'
                        ]
                    ]
                ];

                foreach ($steps as $stepNum => $step) {
                    $activeClass = isset($_GET['step']) && $_GET['step'] == $stepNum ? 'active' : '';
                    echo "<div class='step-box {$activeClass}'>";
                    echo "<div class='step-number'>0{$stepNum}</div>";
                    echo "<h4>{$step['title']}</h4>";
                    echo "<p class='text-muted'>{$step['office']}</p>";
                    echo "<ul>";
                    foreach ($step['substeps'] as $substep) {
                        echo "<li>{$substep}</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
                ?>
            </div>
            <div class="col-md-4">
                <!-- Requirements Section -->
                <div class="requirements-box">
                    <h4>REQUIREMENTS (FOR NEW STUDENTS)</h4>
                    <ul>
                        <li>ORIGINAL COPY OF REPORT CARD (SF-10)</li>
                        <li>CERTIFICATE OF LIVE BIRTH (ORIGINAL PSA COPY)</li>
                        <li>2 X 2 LATEST PICTURE (WHITE BACKGROUND)</li>
                        <li>1 LONG BROWN ENVELOPE</li>
                    </ul>
                </div>

                <div class="requirements-box">
                    <h4>REQUIREMENTS (FOR TRANSFEREE & UNITING)</h4>
                    <ul>
                        <li>HONORABLE DISMISSAL (LAST SCHOOL ATTENDED)</li>
                        <li>INFORMATIVE COPY OF TRANSCRIPT OF RECORDS</li>
                        <li>CERTIFICATE OF LIVE BIRTH (ORIGINAL PSA COPY)</li>
                        <li>2 X 2 LATEST PICTURE (WHITE BACKGROUND)</li>
                        <li>1 LONG BROWN ENVELOPE</li>
                    </ul>
                </div>

                <div class="requirements-box">
                    <h4>OFFICE/S LOCATION</h4>
                    <ul>
                        <li>REGISTRAR'S OFFICE - 2ND FLOOR, ADMIN BLDG.</li>
                        <li>COLLEGE DEPARTMENT HEAD - 3RD FLOOR, MAIN BLDG.</li>
                        <li>CTE CHAIRMAN - 3RD FLOOR, MAIN BLDG.</li>
                        <li>CBA CHAIRMAN - 2ND FLOOR, ADMIN BLDG.</li>
                        <li>ACCOUNTING OFFICE - 2ND FLOOR, ADMIN BLDG.</li>
                        <li>CASHIER OFFICE - GROUND FLOOR, ADMIN BLDG.</li>
                        <li>ICT DEPARTMENT - GROUND FLOOR, ADMIN BLDG.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
