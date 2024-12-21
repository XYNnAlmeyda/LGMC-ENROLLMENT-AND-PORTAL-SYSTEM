<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Student Registration Form</h2>
        <form action="process_registration.php" method="POST">
            <!-- Student Information Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Student Information</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="lrn_no" class="form-label">LRN Number</label>
                            <input type="text" class="form-control" id="lrn_no" name="lrn_no" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" id="age" name="age" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="sex" class="form-label">Sex</label>
                            <select class="form-select" id="sex" name="sex" required>
                                <option value="">Select Sex</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="religion" class="form-label">Religion</label>
                            <input type="text" class="form-control" id="religion" name="religion">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="email_address" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email_address" name="email_address" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="fathers_name" class="form-label">Father's Name</label>
                            <input type="text" class="form-control" id="fathers_name" name="fathers_name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="mothers_maiden_name" class="form-label">Mother's Maiden Name</label>
                            <input type="text" class="form-control" id="mothers_maiden_name" name="mothers_maiden_name">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Address Information</h4>
                </div>
                <div class="card-body">
                    <!-- Present Address -->
                    <h5>Present Address</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="present_house_number" class="form-label">House Number/Street</label>
                            <input type="text" class="form-control" id="present_house_number" name="present_house_number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="present_barangay" class="form-label">Barangay</label>
                            <input type="text" class="form-control" id="present_barangay" name="present_barangay" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="present_city" class="form-label">City/Municipality</label>
                            <input type="text" class="form-control" id="present_city" name="present_city" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="present_province" class="form-label">Province</label>
                            <input type="text" class="form-control" id="present_province" name="present_province" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="present_region" class="form-label">Region</label>
                            <input type="text" class="form-control" id="present_region" name="present_region" required>
                        </div>
                    </div>

                    <!-- Academic Information Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4>Academic Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="school_year" class="form-label">School Year</label>
                                    <select class="form-select" id="school_year" name="school_year" required>
                                        <option value="">Select School Year</option>
                                        <option value="2025-2026">2025-2026</option>
                                        <option value="2026-2027">2026-2027</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="year_level" class="form-label">Year Level</label>
                                    <select class="form-select" id="year_level" name="year_level" required>
                                        <option value="">Select Year Level</option>
                                        <option value="First Year">First Year</option>
                                        <option value="Second Year">Second Year</option>
                                        <option value="Third Year">Third Year</option>
                                        <option value="Fourth Year">Fourth Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="course" class="form-label">Course</label>
                                    <select class="form-select" id="course" name="course" required>
                                        <option value="">Select Course</option>
                                        <option value="BS in Accounting">BS in Accounting</option>
                                        <option value="BS in Business Administration Major in Marketing Management">BS in Business Administration Major in Marketing Management</option>
                                        <option value="BS in Business Administration Major in Human Resource Management">BS in Business Administration Major in Human Resource Management</option>
                                        <option value="BS in Business Administration Major in Financial Management">BS in Business Administration Major in Financial Management</option>
                                        <option value="Bachelor in Elementary Education">Bachelor in Elementary Education</option>
                                        <option value="BS in Secondary Education Major in Mathematics">BS in Secondary Education Major in Mathematics</option>
                                        <option value="BS in Secondary Education Major in English">BS in Secondary Education Major in English</option>
                                        <option value="BS in Secondary Education Major in Filipino">BS in Secondary Education Major in Filipino</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_school_year_completed" class="form-label">Last School Year Completed</label>
                                    <input type="text" class="form-control" id="last_school_year_completed" name="last_school_year_completed" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="school_to_enroll" class="form-label">School to Enroll</label>
                                    <input type="text" class="form-control" id="school_to_enroll" name="school_to_enroll" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="school_address" class="form-label">School Address</label>
                                    <input type="text" class="form-control" id="school_address" name="school_address" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="student_id" class="form-label">Student ID</label>
                                    <input type="text" class="form-control" id="student_id" name="student_id">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="semester" class="form-label">Semester</label>
                                    <select class="form-select" id="semester" name="semester" required>
                                        <option value="">Select Semester</option>
                                        <option value="First">First</option>
                                        <option value="Second">Second</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="block" class="form-label">Block</label>
                                    <input type="text" class="form-control" id="block" name="block">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="school_type" class="form-label">School Type</label>
                                    <select class="form-select" id="school_type" name="school_type" required>
                                        <option value="">Select School Type</option>
                                        <option value="Public">Public</option>
                                        <option value="Private">Private</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Submit Registration</button>
                            <button type="reset" class="btn btn-secondary">Reset Form</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>