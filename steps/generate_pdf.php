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
    // Format the student ID with leading zeros and year
    $formatted_student_id = sprintf("%05d-%s", $student['student_id'], substr(date('Y'), -2));
    $pdf->Cell(30, 5, $formatted_student_id, 0, 1, 'L');

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
    $pdf->Cell(30, 6, $formatted_student_id, 0, 0);
    $pdf->Cell(70, 6, 'School Type:', 0, 0);
    $pdf->Cell(6, 6, $student['school_type'], 0, 1);

    $pdf->Cell(40, 6, 'Semester:', 0, 0);
    $pdf->Cell(14, 6, $student['semester'], 0, 0);
    $pdf->Cell(20,6, 'Course:', 0, 0);
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

    // Student's Information section
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 8, $student['last_name'] . ', ' . $student['first_name'] . ' ' . $student['middle_name'], 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);

    // Left column F: 25 for label, 60 for value
    // Right column starts at 120, 25 for label, rest for value
    $col1_label = 25;
    $col1_value = 27;
    $col2_start = 120;
    $col2_label = 25;
    $line_height = 5;  // Reduced line height for more compact layout

    // Personal Information with fixed layout
    $pdf->Cell($col1_label, $line_height, 'LRN:', 0, 0);
    $pdf->Cell($col1_value, $line_height, $student['lrn_no'], 0, 0);
    $pdf->SetX($col2_start);
    $pdf->Cell($col2_label, $line_height, 'Date of Birth:', 0, 0);
    $pdf->Cell(0, $line_height, $student['date_of_birth'], 0, 1);

    $pdf->Cell($col1_label, $line_height, 'Age:', 0, 0);
    $pdf->Cell($col1_value, $line_height, $student['age'], 0, 0);
    $pdf->SetX($col2_start);
    $pdf->Cell($col2_label, $line_height, 'Sex:', 0, 0);
    $pdf->Cell(0, $line_height, $student['sex'], 0, 1);

    $pdf->Cell($col1_label, $line_height, 'Religion:', 0, 0);
    $pdf->Cell($col1_value, $line_height, $student['religion'], 0, 0);
    $pdf->SetX($col2_start);
    $pdf->Cell($col2_label, $line_height, 'Phone Number:', 0, 0);
    $pdf->Cell(0, $line_height, $student['phone_number'], 0, 1);

    $pdf->Cell($col1_label, $line_height, 'Email:', 0, 0);
    $pdf->Cell(0, $line_height, $student['email_address'], 0, 1);

    // Parents information with fixed layout
    $pdf->Cell(40, $line_height, "Father's Name:", 0, 0);
    $pdf->Cell(0, $line_height, $student['fathers_name'], 0, 1);

    $pdf->Cell(40, $line_height, "Mother's Name:", 0, 0);
    $pdf->Cell(0, $line_height, $student['mothers_maiden_name'], 0, 1);

    // Permanent Address
    $pdf->Ln(2);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, $line_height, 'PERMANENT ADDRESS', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 10);

    $pdf->Cell(45, $line_height, 'House number and street:', 0, 0);
    $pdf->Cell(0, $line_height, $student['house_number_street'], 0, 1);

    $pdf->Cell(45, $line_height, 'City/Municipality:', 0, 0);
    $pdf->Cell(60, $line_height, $student['city_municipality'], 0, 0);
    $pdf->SetX($col2_start);
    $pdf->Cell(35, $line_height, 'Barangay:', 0, 0);
    $pdf->Cell(0, $line_height, $student['barangay'], 0, 1);

    $pdf->Cell(45, $line_height, 'Province:', 0, 0);
    $pdf->Cell(60, $line_height, $student['province'], 0, 0);
    $pdf->SetX($col2_start);
    $pdf->Cell(35, $line_height, 'Region:', 0, 0);
    $pdf->Cell(0, $line_height, $student['region'], 0, 1);

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

    // Charges Section
    $pdf->Ln(2);
    $pdf->SetFillColor(0, 100, 0);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 7, 'CHARGES', 1, 1, 'L', true);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', '', 10);

    // Function to draw charge line with value inside underline
    function drawChargeLine($pdf, $label, $amount, $line_height = 6) {
        $amount_formatted = number_format($amount, 2);
        $pdf->Cell(40, $line_height, $label, 0, 0);
        
        // Create underlined cell with value inside
        $pdf->Cell(100, $line_height, $amount_formatted, 'B', 0, 'R');
        
        // Add some spacing
        $pdf->Cell(0, $line_height, '', 0, 1);
    }

    try {
        // Calculate charges
        $total_units = array_sum(array_column($subjects, 'units'));
        $tuition_fee = $total_units * $student['per_unit_fee'];

        // Calculate laboratory fees - fixed the undefined variable
        $lab_subjects = array_filter($subjects, function($subject) {
            return strpos(strtolower($subject['description']), 'laboratory') !== false;
        });
        $laboratory_fee = count($lab_subjects) * $student['laboratory_fee'];

        $total = $student['entrance_fee'] + 
                 $student['misc_fee'] + 
                 $laboratory_fee + 
                 $tuition_fee + 
                 $student['other_fees'];

        // Draw charges
        drawChargeLine($pdf, 'ENTRANCE FEE', $student['entrance_fee']);
        drawChargeLine($pdf, 'MISC. FEES', $student['misc_fee']);
        drawChargeLine($pdf, 'LABORATORY FEE', $laboratory_fee);
        drawChargeLine($pdf, 'TUITION FEE', $tuition_fee);
        drawChargeLine($pdf, 'OTHERS', $student['other_fees']);
        
        // Total with double underline
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(40, 6, 'TOTAL', 0, 0);
        $pdf->Cell(100, 6, number_format($total, 2), 'B', 0, 'R');
        $pdf->Cell(0, 6, '', 0, 1);
        $pdf->Cell(40, 1, '', 0, 0);
        $pdf->Cell(100, 1, '', 'B', 0); // Second underline
        $pdf->Cell(0, 1, '', 0, 1);
        
        // Deposit line
        $pdf->SetFont('helvetica', '', 10);
        drawChargeLine($pdf, 'DEPOSIT', $student['deposit']);

    } catch (Exception $e) {
        error_log("Error in PDF generation: " . $e->getMessage());
        die("Error generating PDF: " . $e->getMessage());
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