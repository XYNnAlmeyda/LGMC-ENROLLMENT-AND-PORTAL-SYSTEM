<?php

/**
 * Registration PDF Generator
 * Extends FPDF to create registration form documents
 */

if (!file_exists(__DIR__ . '/../fpdf_new/fpdf.php')) {
    throw new RuntimeException('FPDF library not found');
}
require_once __DIR__ . '/../fpdf_new/fpdf.php';

class RegistrationPDF extends FPDF {
    // Constants for configuration
    private const FONT_FAMILY = 'Arial';
    private const FONT_SIZE_NORMAL = 10;
    private const FONT_SIZE_HEADER = 16;
    private const FONT_SIZE_SUBHEADER = 12;
    
    /**
     * Format label text
     * @param string $text
     * @return string
     */
    private function formatLabel(string $text): string {
        return trim($text);
    }

    /**
     * Format value with fallback to N/A
     * @param mixed $value
     * @return string
     */
    private function formatValue($value): string {
        return ($value !== null && $value !== '') ? (string)$value : 'N/A';
    }

    /**
     * Add a field with label and value
     * @param string $label
     * @param mixed $value
     */
    private function addField(string $label, $value): void {
        $this->SetFont(self::FONT_FAMILY, 'B', self::FONT_SIZE_NORMAL);
        $this->Cell(60, 7, $this->formatLabel($label), 0);
        $this->SetFont(self::FONT_FAMILY, '', self::FONT_SIZE_NORMAL);
        $this->Cell(130, 7, $this->formatValue($value), 0);
        $this->Ln();
    }

    /**
     * Generate registration form PDF
     * @param array $data Registration data
     * @throws InvalidArgumentException
     */
    public function generateRegistrationForm(array $data): void {
        if (!isset($data['reference_number'])) {
            throw new InvalidArgumentException('Reference number is required');
        }

        $this->AddPage();
        
        // Header
        $this->SetFont(self::FONT_FAMILY, 'B', self::FONT_SIZE_HEADER);
        $this->Cell(0, 10, 'LGMC ENROLLMENT REGISTRATION FORM', 0, 1, 'C');
        $this->SetFont(self::FONT_FAMILY, 'B', self::FONT_SIZE_SUBHEADER);
        $this->Cell(0, 10, 'Reference Number: ' . $this->formatValue($data['reference_number']), 0, 1, 'C');
        $this->Ln(10);

        // Basic Information
        $this->SetFont(self::FONT_FAMILY, 'B', self::FONT_SIZE_SUBHEADER);
        $this->Cell(0, 10, 'BASIC INFORMATION', 0, 1, 'L');
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 190, $this->GetY());
        $this->Ln(5);

        $school_year = '';
        if (!empty($data['school_year_start']) && !empty($data['school_year_end'])) {
            $school_year = $data['school_year_start'] . ' - ' . $data['school_year_end'];
        }
        $this->addField('School Year:', $school_year);
        $this->addField('Year Level:', $data['year_level']);
        $this->addField('Semester:', $data['semester']);
        $this->addField('Course:', $data['course']);
        $this->Ln(5);

        // Personal Information
        $this->SetFont(self::FONT_FAMILY, 'B', self::FONT_SIZE_SUBHEADER);
        $this->Cell(0, 10, 'PERSONAL INFORMATION', 0, 1, 'L');
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 190, $this->GetY());
        $this->Ln(5);

        $this->addField('Last Name:', $data['last_name']);
        $this->addField('First Name:', $data['first_name']);
        $this->addField('Middle Name:', $data['middle_name']);
        $this->addField('Extension Name:', $data['extension_name']);
        $this->addField('Birthdate:', $data['birthdate']);
        $this->addField('Sex:', $data['sex']);
        $this->addField('Age:', $data['age']);
        $this->addField('Email:', $data['email']);
        $this->Ln(5);

        // Current Address
        $this->SetFont(self::FONT_FAMILY, 'B', self::FONT_SIZE_SUBHEADER);
        $this->Cell(0, 10, 'CURRENT ADDRESS', 0, 1, 'L');
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 190, $this->GetY());
        $this->Ln(5);

        $this->addField('House/Street:', $data['current_house_street']);
        $this->addField('Barangay:', $data['current_barangay']);
        $this->addField('City:', $data['current_city']);
        $this->addField('Province:', $data['current_province']);
        $this->addField('ZIP Code:', $data['current_zip']);
        $this->Ln(5);

        // Parent Information
        $this->SetFont(self::FONT_FAMILY, 'B', self::FONT_SIZE_SUBHEADER);
        $this->Cell(0, 10, 'PARENT/GUARDIAN INFORMATION', 0, 1, 'L');
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 190, $this->GetY());
        $this->Ln(5);

        // Format full names
        $father_name = $this->formatFullName($data['father_firstname'], $data['father_middlename'], $data['father_lastname']);
        $mother_name = $this->formatFullName($data['mother_firstname'], $data['mother_middlename'], $data['mother_lastname']);
        $guardian_name = $this->formatFullName($data['guardian_firstname'], $data['guardian_middlename'], $data['guardian_lastname']);

        $this->addField("Father's Name:", $father_name);
        $this->addField("Father's Contact:", $data['father_contact']);
        $this->addField("Mother's Name:", $mother_name);
        $this->addField("Mother's Contact:", $data['mother_contact']);
        $this->addField("Guardian's Name:", $guardian_name);
        $this->addField("Guardian's Contact:", $data['guardian_contact']);
        $this->Ln(5);

        // Educational Background
        $this->SetFont(self::FONT_FAMILY, 'B', self::FONT_SIZE_SUBHEADER);
        $this->Cell(0, 10, 'EDUCATIONAL BACKGROUND', 0, 1, 'L');
        $this->Line($this->GetX(), $this->GetY(), $this->GetX() + 190, $this->GetY());
        $this->Ln(5);

        $this->addField('Last Grade Level Completed:', $data['last_grade_completed']);
        $this->addField('Last School Year:', $data['last_school_year']);
        $this->addField('Last School Attended:', $data['last_school']);
        $this->addField('School ID:', $data['school_id']);
        $this->Ln(10);

        // Signature Line
        $this->Line($this->GetX() + 20, $this->GetY() + 20, $this->GetX() + 80, $this->GetY() + 20);
        $this->SetXY($this->GetX() + 20, $this->GetY() + 25);
        $this->Cell(60, 5, 'Student Signature', 0, 0, 'C');

        $this->Line($this->GetX() + 40, $this->GetY() - 5, $this->GetX() + 100, $this->GetY() - 5);
        $this->SetXY($this->GetX() + 40, $this->GetY());
        $this->Cell(60, 5, 'Date', 0, 0, 'C');
    }

    private function formatFullName($first, $middle, $last) {
        $parts = array_filter([$first, $middle, $last], function($part) {
            return !empty($part) && $part !== 'N/A';
        });
        return empty($parts) ? 'N/A' : implode(' ', $parts);
    }
}
