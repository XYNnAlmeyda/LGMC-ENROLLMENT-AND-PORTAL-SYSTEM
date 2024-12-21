<?php
session_start();
require_once('../config/database.php');
require_once('../vendor/autoload.php');

// Debug information
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if student_id exists in session
if (!isset($_SESSION['student_id'])) {
    die("Error: No student ID found in session");
}

try {
    // Get the latest student registration
    $stmt = $conn->prepare("
        SELECT s.*, a.*, ai.*
        FROM student_info s
        JOIN academic_info a ON s.id = a.student_id
        LEFT JOIN address_info ai ON s.lrn_no = ai.lrn_no
        WHERE s.id = :student_id
        LIMIT 1
    ");
    
    $stmt->execute(['student_id' => $_SESSION['student_id']]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        die("Error: Student information not found");
    }

    // Get subject loads with correct academic_info_id
    $stmt = $conn->prepare("
        SELECT sl.* 
        FROM subject_loads sl
        WHERE sl.academic_info_id = (
            SELECT id FROM academic_info 
            WHERE student_id = :student_id
            LIMIT 1
        )
    ");
    $stmt->execute(['student_id' => $_SESSION['student_id']]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Debug
    error_log("Found " . count($subjects) . " subjects for student_id: " . $_SESSION['student_id']);

    // Start PDF generation
    ob_clean(); // Clear any output buffers
    
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(215.9, 355.6), true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('LGMCI Enrollment System');
    $pdf->SetAuthor('LGMCI');
    $pdf->SetTitle('Registration Form - ' . $student['last_name']);

    // Set margins
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(0);

    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Add a page
    $pdf->AddPage();

    // Add header with logo and school name
    $pdf->Image('../assets/logo.png', 35, 10, 25); // Adjust logo position and size

    // School Header
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 5, 'LEON GUINTO MEMORIAL COLLEGE, Inc.', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 5, '443 Mabini St. Zone II Atimonan Quezon City', 0, 1, 'C');
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 8, 'LEARNER ENROLLMENT FORM', 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(160, 5, 'Reference Number:', 0, 0, 'R');
    $pdf->Cell(30, 5, $student['student_id'], 0, 1, 'L');

    $pdf->SetFillColor(0, 100, 0);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 7, 'A. ACADEMIC INFORMATION', 1, 1, 'L', true);
    $pdf->SetTextColor(0, 0, 0); 
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(40, 6, 'Year level enrolled:', 0, 0);
    $pdf->Cell(60, 6, $student['year_level'], 0, 0);
    $pdf->Cell(40, 6, 'Last School Attended:', 0, 0);
    $pdf->Cell(0, 6, $student['school_to_enroll'], 0, 1);

    $pdf->Cell(40, 6, 'Year Level Completed:', 0, 0);
    $pdf->Cell(60, 6, $student['last_school_year'], 0, 0);
    $pdf->Cell(40, 6, 'School Address:', 0, 0);
    $pdf->Cell(0, 6, $student['school_address'], 0, 1);

    $pdf->Cell(40, 6, 'Student ID:', 0, 0);
    $pdf->Cell(60, 6, $student['student_id'], 0, 0);
    $pdf->Cell(40, 6, 'School Type:', 0, 0);
    $pdf->Cell(0, 6, $student['school_type'], 0, 1);

    $pdf->Cell(40, 6, 'Semester:', 0, 0);
    $pdf->Cell(60, 6, $student['semester'], 0, 0);
    $pdf->Cell(40, 6, 'Course:', 0, 0);
    $pdf->Cell(0, 6, $student['course'], 0, 1);

    $pdf->Cell(40, 6, 'Block:', 0, 0);
    $pdf->Cell(0, 6, $student['block'], 0, 1);
    $pdf->Ln(5);

    // B. STUDENT'S INFORMATION
    $pdf->SetFillColor(0, 100, 0);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 7, 'B. STUDENT\'S INFORMATION', 1, 1, 'L', true);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', '', 10);

    // Student's full name in larger font
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, $student['last_name'] . ', ' . $student['first_name'] . ' ' . $student['middle_name'], 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);

    // Personal Info Grid
    $pdf->Cell(40, 6, 'LRN:', 0, 0);
    $pdf->Cell(60, 6, $student['lrn_no'], 0, 0);
    $pdf->Cell(40, 6, 'Date of Birth:', 0, 0);
    $pdf->Cell(0, 6, $student['date_of_birth'], 0, 1);

    $pdf->Cell(40, 6, 'Age:', 0, 0);
    $pdf->Cell(60, 6, $student['age'], 0, 0);
    $pdf->Cell(40, 6, 'Sex:', 0, 0);
    $pdf->Cell(0, 6, $student['sex'], 0, 1);

    $pdf->Cell(40, 6, 'Religion:', 0, 0);
    $pdf->Cell(60, 6, $student['religion'], 0, 0);
    $pdf->Cell(40, 6, 'Phone Number:', 0, 0);
    $pdf->Cell(0, 6, $student['phone_number'], 0, 1);

    $pdf->Cell(40, 6, 'Email Address:', 0, 0);
    $pdf->Cell(60, 6, $student['email_address'], 0, 0);
    $pdf->Cell(40, 6, "Father's Name:", 0, 0);
    $pdf->Cell(0, 6, $student['fathers_name'], 0, 1);

    // Address Information
    $pdf->Cell(40, 6, 'PERMANENT ADDRESS', 0, 1, 'L');
    $pdf->Cell(40, 6, 'House number and street:', 0, 0);
    $pdf->Cell(0, 6, $student['house_number_street'], 0, 1);
    $pdf->Cell(40, 6, 'City/Municipality:', 0, 0);
    $pdf->Cell(60, 6, $student['city_municipality'], 0, 0);
    $pdf->Cell(40, 6, 'Barangay:', 0, 0);
    $pdf->Cell(0, 6, $student['barangay'], 0, 1);
    $pdf->Cell(40, 6, 'Province:', 0, 0);
    $pdf->Cell(60, 6, $student['province'], 0, 0);
    $pdf->Cell(40, 6, 'Region:', 0, 0);
    $pdf->Cell(0, 6, $student['region'], 0, 1);
    $pdf->Ln(5);

    // SUBJECT LOADS
    $pdf->SetFillColor(0, 100, 0);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 7, 'SUBJECT LOADS', 1, 1, 'L', true);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', '', 10);

    // Subject table headers
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(30, 7, 'Subject Code', 1, 0, 'C', true);
    $pdf->Cell(70, 7, 'Description', 1, 0, 'C', true);
    $pdf->Cell(15, 7, 'Units', 1, 0, 'C', true);
    $pdf->Cell(30, 7, 'Day/Time', 1, 0, 'C', true);
    $pdf->Cell(40, 7, 'Professor/Instructor', 1, 1, 'C', true);

    // Subject table content
    foreach($subjects as $subject) {
        $pdf->Cell(30, 7, $subject['subject_code'], 1, 0, 'C');
        $pdf->Cell(70, 7, $subject['description'], 1, 0, 'L');
        $pdf->Cell(15, 7, $subject['units'], 1, 0, 'C');
        $pdf->Cell(30, 7, $subject['day'] . ' ' . $subject['time'], 1, 0, 'C');
        $pdf->Cell(40, 7, $subject['professor_instructor'], 1, 1, 'L');
    }

    // Total Units
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(100, 7, 'Total Units', 1, 0, 'R');
    $pdf->Cell(85, 7, array_sum(array_column($subjects, 'units')), 1, 1, 'C');

    // CHARGES section
    $pdf->Ln(5);
    $pdf->SetFillColor(0, 100, 0);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 7, 'CHARGES', 1, 1, 'L', true);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', '', 10);

    // Add charges if you have them in your database
    $charges = [
        'ENTRANCE FEE' => '0.00',
        'MISC. FEES' => '0.00',
        'LABORATORY FEE' => '0.00',
        'TUITION FEE' => '0.00',
        'OTHERS' => '0.00',
        'TOTAL' => '0.00',
        'DEPOSIT' => '0.00'
    ];

    foreach($charges as $label => $amount) {
        $pdf->Cell(100, 6, $label, 0, 0);
        $pdf->Cell(0, 6, number_format((float)$amount, 2), 0, 1, 'R');
    }

    // Student's Oath
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 7, "STUDENT'S OATH", 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->MultiCell(0, 5, "I, " . $student['first_name'] . ' ' . $student['last_name'] . ", as a bonafide student of Leon Guinto Memorial College, shall follow all the rules and regulations set by the department where I belong, particularly in fees refunds and series of payments and refunds, and shall abide by the disciplinary code of the college.", 0, 'L');

    // Output PDF
    $pdf->Output('registration_form.pdf', 'D');

} catch (Exception $e) {
    $_SESSION['error_message'] = "Failed to generate PDF: " . $e->getMessage();
    header("Location: confirmation.php");
    exit();
}
?>