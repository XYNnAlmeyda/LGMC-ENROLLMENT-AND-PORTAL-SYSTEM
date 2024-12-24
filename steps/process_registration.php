<?php
session_start();
require_once(__DIR__ . '/../config/database.php');

try {
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Begin transaction
    $conn->beginTransaction();
    
    // Debug: Print POST data
    error_log("POST Data: " . print_r($_POST, true));
    
    // Insert student info
    $student_sql = "INSERT INTO student_info (lrn_no, last_name, first_name, middle_name, date_of_birth, age, sex, religion, email_address, phone_number, fathers_name, mothers_maiden_name) 
                    VALUES (:lrn_no, :last_name, :first_name, :middle_name, :date_of_birth, :age, :sex, :religion, :email_address, :phone_number, :fathers_name, :mothers_maiden_name)";
    
    $student_stmt = $conn->prepare($student_sql);
    
    $student_data = [
        ':lrn_no' => $_POST['lrn_no'],
        ':last_name' => $_POST['last_name'],
        ':first_name' => $_POST['first_name'],
        ':middle_name' => $_POST['middle_name'],
        ':date_of_birth' => $_POST['date_of_birth'],
        ':age' => $_POST['age'],
        ':sex' => $_POST['sex'],
        ':religion' => $_POST['religion'],
        ':email_address' => $_POST['email_address'],
        ':phone_number' => $_POST['phone_number'],
        ':fathers_name' => $_POST['fathers_name'],
        ':mothers_maiden_name' => $_POST['mothers_maiden_name']
    ];
    
    // Debug: Print student data
    error_log("Student Data: " . print_r($student_data, true));
    
    $student_stmt->execute($student_data);
    $student_id = $conn->lastInsertId();
    error_log("Inserted student_id: " . $student_id);
    
    $_SESSION['student_id'] = $student_id;
    
    // Insert academic info
    $academic_sql = "INSERT INTO academic_info (student_id, school_year, year_level, last_school_year, school_to_enroll, school_address, semester, course, block, school_type) 
                     VALUES (:student_id, :school_year, :year_level, :last_school_year, :school_to_enroll, :school_address, :semester, :course, :block, :school_type)";
    
    $academic_stmt = $conn->prepare($academic_sql);
    
    $academic_data = [
        ':student_id' => $student_id,
        ':school_year' => $_POST['school_year'],
        ':year_level' => $_POST['year_level'],
        ':last_school_year' => $_POST['last_school_year_completed'],
        ':school_to_enroll' => 'LGMCI',
        ':school_address' => 'Default Address',
        ':semester' => $_POST['semester'],
        ':course' => $_POST['course'],
        ':block' => $_POST['block'] ?? '1',
        ':school_type' => $_POST['school_type']
    ];
    
    // Debug: Print academic data
    error_log("Academic Data: " . print_r($academic_data, true));
    
    $academic_stmt->execute($academic_data);
    
    // After inserting academic_info, get its ID
    $academic_info_id = $conn->lastInsertId();
    
    // Get the selected course
    $selected_course = $_POST['course'];
    
    // Define subject loads based on course
    $course_subjects = [
        'BS in Accounting' => [
            ['GECC 104', 'TEST 1', 3, 'M/W/F', '4:00-5:00 pm', 'Mrs. Rosemarie V. Villa'],
            ['ACCT 201', 'Financial Accounting & Reporting', 3, 'M/W/F', '5:00-6:00 pm', 'Mrs. Maricil C. Callo'],
            ['COMP 101', 'IT Application Tools in Business', 3, 'M/W/F', '7:00-8:00 pm', 'Mr. Arm S. Montalbo'],
            ['ECON 201', 'Applied Economics', 3, 'T/Th', '2:45-3:15 pm', 'Mr. Michael L. Nisnisan'],
            ['NSTP 102', 'Civic Welfare Training Service 2', 3, 'Sun', '9:00-10:00 pm', 'Mr. Jennifer A. Magnaye']
        ],
        'BS in Business Administration Major in Marketing Management' => [
            ['GECC 104', 'TEST', 3, 'M/W/F', '4:00-5:00 pm', 'Mrs. Rosemarie V. Villa'],
            ['CBMG 206', 'Introduction to Business & Management', 3, 'M/W/F', '5:00-6:00 pm', 'Mrs. Carmen L. Pegollo'],
            ['MKTG 101', 'Principles of Marketing', 3, 'M/W/F', '7:00-8:00 pm', 'Mr. Arm S. Montalbo'],
            ['ECON 201', 'Applied Economics', 3, 'T/Th', '2:45-3:15 pm', 'Mr. Michael L. Nisnisan'],
            ['NSTP 102', 'Civic Welfare Training Service 2', 3, 'Sun', '9:00-10:00 pm', 'Mr. Jennifer A. Magnaye']
        ],
        'BS in Business Administration Major in Human Resource Management' => [
            ['GECC 104', 'Mathematics in the Modern World', 3, 'M/W/F', '4:00-5:00 pm', 'Mrs. Rosemarie V. Villa'],
            ['HRMN 100', 'Human Behavior in Organizations', 3, 'T/Th', '7:30-9:00 pm', 'Mrs. Ma. Elena P. Adona'],
            ['CBMG 206', 'Introduction to Business & Management', 3, 'M/W/F', '5:00-6:00 pm', 'Mrs. Carmen L. Pegollo'],
            ['ECON 201', 'Applied Economics', 3, 'T/Th', '2:45-3:15 pm', 'Mr. Michael L. Nisnisan'],
            ['NSTP 102', 'Civic Welfare Training Service 2', 3, 'Sun', '9:00-10:00 pm', 'Mr. Jennifer A. Magnaye']
        ],
        'BS in Business Administration Major in Financial Management' => [
            ['GECC 104', 'Mathematics in the Modern World', 3, 'M/W/F', '4:00-5:00 pm', 'Mrs. Rosemarie V. Villa'],
            ['FINC 101', 'Financial Management', 3, 'M/W/F', '5:00-6:00 pm', 'Mrs. Maricil C. Callo'],
            ['CBMG 206', 'Introduction to Business & Management', 3, 'M/W/F', '7:00-8:00 pm', 'Mrs. Carmen L. Pegollo'],
            ['ECON 201', 'Applied Economics', 3, 'T/Th', '2:45-3:15 pm', 'Mr. Michael L. Nisnisan'],
            ['NSTP 102', 'Civic Welfare Training Service 2', 3, 'Sun', '9:00-10:00 pm', 'Mr. Jennifer A. Magnaye']
        ],
        'Bachelor in Elementary Education' => [
            ['GECC 104', 'Mathematics in the Modern World', 3, 'M/W/F', '4:00-5:00 pm', 'Mrs. Rosemarie V. Villa'],
            ['EDUC 101', 'Child Development', 3, 'M/W/F', '5:00-6:00 pm', 'Mrs. Elena P. Adona'],
            ['GECF 104', 'Filipino sa Iba\'t Ibang Disiplina', 3, 'M/W/F', '8:00-9:00 pm', 'Mrs. Antonina Castor Magallor'],
            ['PHED 102', 'Rhythmic Activities', 2, 'Sat', '10:00-12:00 pm', 'Mr. Michael R. Inovero'],
            ['NSTP 102', 'Civic Welfare Training Service 2', 3, 'Sun', '9:00-10:00 pm', 'Mr. Jennifer A. Magnaye']
        ],
        'BS in Secondary Education Major in Mathematics' => [
            ['GECC 104', 'Mathematics in the Modern World', 3, 'M/W/F', '4:00-5:00 pm', 'Mrs. Rosemarie V. Villa'],
            ['MATH 101', 'College Algebra', 3, 'M/W/F', '2:00-3:00 pm', 'Mrs. Rosemarie V. Villa'],
            ['EDUC 101', 'Principles of Teaching', 3, 'T/Th', '1:00-2:30 pm', 'Mrs. Elena P. Adona'],
            ['PHED 102', 'Rhythmic Activities', 2, 'Sat', '10:00-12:00 pm', 'Mr. Michael R. Inovero'],
            ['NSTP 102', 'Civic Welfare Training Service 2', 3, 'Sun', '9:00-10:00 pm', 'Mr. Jennifer A. Magnaye']
        ],
        'BS in Secondary Education Major in English' => [
            ['GECC 104', 'Mathematics in the Modern World', 3, 'M/W/F', '4:00-5:00 pm', 'Mrs. Rosemarie V. Villa'],
            ['ENGL 101', 'Introduction to Literature', 3, 'T/Th', '1:00-2:30 pm', 'Mrs. Elena P. Adona'],
            ['EDUC 101', 'Principles of Teaching', 3, 'M/W/F', '5:00-6:00 pm', 'Mrs. Elena P. Adona'],
            ['PHED 102', 'Rhythmic Activities', 2, 'Sat', '10:00-12:00 pm', 'Mr. Michael R. Inovero'],
            ['NSTP 102', 'Civic Welfare Training Service 2', 3, 'Sun', '9:00-10:00 pm', 'Mr. Jennifer A. Magnaye']
        ],
        'BS in Secondary Education Major in Filipino' => [
            ['GECC 104', 'Mathematics in the Modern World', 3, 'M/W/F', '4:00-5:00 pm', 'Mrs. Rosemarie V. Villa'],
            ['GECF 104', 'Filipino sa Iba\'t Ibang Disiplina', 3, 'M/W/F', '8:00-9:00 pm', 'Mrs. Antonina Castor Magallor'],
            ['EDUC 101', 'Principles of Teaching', 3, 'M/W/F', '5:00-6:00 pm', 'Mrs. Elena P. Adona'],
            ['PHED 102', 'Rhythmic Activities', 2, 'Sat', '10:00-12:00 pm', 'Mr. Michael R. Inovero'],
            ['NSTP 102', 'Civic Welfare Training Service 2', 3, 'Sun', '9:00-10:00 pm', 'Mr. Jennifer A. Magnaye']
        ]
    ];

    // Get subject loads for the selected course
    $subject_loads = $course_subjects[$selected_course] ?? [];

    if (empty($subject_loads)) {
        throw new Exception("No subjects found for the selected course: " . $selected_course);
    }

    // Insert subject loads
    $subject_sql = "INSERT INTO subject_loads (academic_info_id, subject_code, description, units, day, time, professor_instructor) 
                    VALUES (:academic_info_id, :subject_code, :description, :units, :day, :time, :professor_instructor)";
    
    $subject_stmt = $conn->prepare($subject_sql);

    foreach ($subject_loads as $subject) {
        $subject_stmt->execute([
            ':academic_info_id' => $academic_info_id,
            ':subject_code' => $subject[0],
            ':description' => $subject[1],
            ':units' => $subject[2],
            ':day' => $subject[3],
            ':time' => $subject[4],
            ':professor_instructor' => $subject[5]
        ]);
    }

    // Store IDs in session for PDF generation
    $_SESSION['student_id'] = $student_id;
    $_SESSION['academic_info_id'] = $academic_info_id;
    
    // Insert address info
    $address_sql = "INSERT INTO address_info (lrn_no, address_type, house_number_street, city_municipality, barangay, province, region) 
                    VALUES (:lrn_no, :address_type, :house_number_street, :city_municipality, :barangay, :province, :region)";
    
    $address_stmt = $conn->prepare($address_sql);
    
    $address_data = [
        ':lrn_no' => $_POST['lrn_no'],
        ':address_type' => 'PRESENT',
        ':house_number_street' => $_POST['present_house_number'],
        ':city_municipality' => $_POST['present_city'],
        ':barangay' => $_POST['present_barangay'],
        ':province' => $_POST['present_province'],
        ':region' => $_POST['present_region']
    ];
    
    // Debug: Print address data
    error_log("Address Data: " . print_r($address_data, true));
    
    $address_stmt->execute($address_data);

    // Commit transaction
    $conn->commit();
    error_log("Transaction committed successfully");
    
    // Store email in session for confirmation page
    $_SESSION['student_email'] = $_POST['email_address'];
    $_SESSION['success_message'] = "Registration successful!";
    
    // Set the enrollment step
    $_SESSION['enrollment_step'] = 2;
    
    // After successful registration
    $_SESSION['student_id'] = $student_id;
    
    // Redirect to enrollment.php with step=2
    header("Location: confirmation.php");
    exit();

} catch (PDOException $e) {
    // Rollback transaction on error
    $conn->rollBack();
    error_log("Database Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    $_SESSION['error_message'] = "Registration failed: " . $e->getMessage();
    header("Location: ../steps/registration_form.php");
    exit();
}
?>
