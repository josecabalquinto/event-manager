<?php

namespace App\Services;

use App\Models\Event;
use TCPDF;

class AttendanceReportService
{
    public function generateReport(Event $event, $participants)
    {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('CICTE CertChain');
        $pdf->SetAuthor('CICTE CertChain System');
        $pdf->SetTitle('Attendance Report - ' . $event->title);
        $pdf->SetSubject('Event Attendance Report');

        // Set default header data
        $pdf->SetHeaderData('', 0, 'Attendance Report', $event->title . ' - ' . $event->event_date->format('M d, Y'));

        // Set header and footer fonts
        $pdf->setHeaderFont(['helvetica', '', 12]);
        $pdf->setFooterFont(['helvetica', '', 8]);

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);

        // Set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 10);

        // Event Information
        $html = '<h2 style="color: #2563eb; text-align: center; margin-bottom: 20px;">Event Attendance Report</h2>';

        $html .= '<table border="0" cellpadding="5" style="margin-bottom: 20px;">
            <tr>
                <td style="width: 25%; font-weight: bold; color: #374151;">Event Title:</td>
                <td style="width: 75%; color: #1f2937;">' . htmlspecialchars($event->title) . '</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #374151;">Date & Time:</td>
                <td style="color: #1f2937;">' . $event->event_date->format('F d, Y') . ' at ' . $event->event_time . '</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #374151;">Location:</td>
                <td style="color: #1f2937;">' . htmlspecialchars($event->location) . '</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #374151;">Total Registrations:</td>
                <td style="color: #1f2937;">' . $participants->count() . '</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #374151;">Checked In:</td>
                <td style="color: #16a34a;">' . $participants->where('is_checked_in', true)->count() . '</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #374151;">Not Checked In:</td>
                <td style="color: #dc2626;">' . $participants->where('is_checked_in', false)->count() . '</td>
            </tr>
        </table>';

        // Statistics by Status
        $approved = $participants->where('status', 'approved')->count();
        $pending = $participants->where('status', 'pending')->count();
        $rejected = $participants->where('status', 'rejected')->count();

        $html .= '<h3 style="color: #1f2937; margin-top: 20px; margin-bottom: 10px;">Registration Status Summary</h3>';
        $html .= '<table border="0" cellpadding="5" style="margin-bottom: 20px;">
            <tr>
                <td style="width: 25%; font-weight: bold; color: #374151;">Approved:</td>
                <td style="width: 25%; color: #16a34a;">' . $approved . '</td>
                <td style="width: 25%; font-weight: bold; color: #374151;">Pending:</td>
                <td style="width: 25%; color: #f59e0b;">' . $pending . '</td>
            </tr>
            <tr>
                <td style="font-weight: bold; color: #374151;">Rejected:</td>
                <td style="color: #dc2626;">' . $rejected . '</td>
                <td colspan="2"></td>
            </tr>
        </table>';

        // Participants Table Header
        $html .= '<h3 style="color: #1f2937; margin-top: 25px; margin-bottom: 15px;">Participant Details</h3>';
        $html .= '<table border="1" cellpadding="6" cellspacing="0" style="width: 100%; border-collapse: collapse; border-color: #374151; font-family: Arial, sans-serif;">
            <thead>
                <tr style="background-color: #f3f4f6; font-weight: bold; color: #111827; font-size: 10px;">
                    <th style="width: 25%; text-align: left; padding: 8px 6px; border: 1px solid #374151;">Name</th>
                    <th style="width: 15%; text-align: center; padding: 8px 6px; border: 1px solid #374151;">Student ID</th>
                    <th style="width: 20%; text-align: center; padding: 8px 6px; border: 1px solid #374151;">Course/Year/Section</th>
                    <th style="width: 12%; text-align: center; padding: 8px 6px; border: 1px solid #374151;">Status</th>
                    <th style="width: 12%; text-align: center; padding: 8px 6px; border: 1px solid #374151;">Check-in</th>
                    <th style="width: 16%; text-align: center; padding: 8px 6px; border: 1px solid #374151;">Registration Date</th>
                </tr>
            </thead>
            <tbody>';

        // Sort participants by name
        $sortedParticipants = $participants->sortBy('name');

        foreach ($sortedParticipants as $participant) {
            // Status color coding
            $statusColor = match ($participant['status']) {
                'approved' => '#16a34a',
                'pending' => '#f59e0b',
                'rejected' => '#dc2626',
                default => '#6b7280'
            };

            // Check-in status
            $checkInStatus = $participant['is_checked_in'] ? '✓ Yes' : '✗ No';
            $checkInColor = $participant['is_checked_in'] ? '#16a34a' : '#dc2626';

            // Combine all academic info for better space utilization
            $academicInfo = [];
            if (!empty($participant['course'])) $academicInfo[] = $participant['course'];
            if (!empty($participant['year_level'])) $academicInfo[] = $participant['year_level'];
            if (!empty($participant['section'])) $academicInfo[] = $participant['section'];
            $fullAcademicInfo = !empty($academicInfo) ? implode(' ', $academicInfo) : 'N/A';

            $html .= '<tr style="border-bottom: 1px solid #6b7280;">
                <td style="width: 25%; padding: 6px; font-size: 9px; border-right: 1px solid #6b7280; text-align: left; vertical-align: top;">' . htmlspecialchars($participant['name']) . '</td>
                <td style="width: 15%; padding: 6px; text-align: center; font-size: 9px; border-right: 1px solid #6b7280; vertical-align: top;">' . htmlspecialchars($participant['student_id'] ?? 'N/A') . '</td>
                <td style="width: 20%; padding: 6px; text-align: center; font-size: 9px; border-right: 1px solid #6b7280; vertical-align: top;">' . htmlspecialchars($fullAcademicInfo) . '</td>
                <td style="width: 12%; padding: 6px; text-align: center; color: ' . $statusColor . '; font-size: 9px; font-weight: bold; border-right: 1px solid #6b7280; vertical-align: top;">' . ucfirst($participant['status']) . '</td>
                <td style="width: 12%; padding: 6px; text-align: center; color: ' . $checkInColor . '; font-size: 9px; font-weight: bold; border-right: 1px solid #6b7280; vertical-align: top;">' . ($participant['is_checked_in'] ? 'Yes' : 'No') . '</td>
                <td style="width: 16%; padding: 6px; text-align: center; font-size: 9px; vertical-align: top;">' . date('M d, Y', strtotime($participant['registration_date'])) . '</td>
            </tr>';
        }

        $html .= '</tbody></table>';

        // Add check-in details if available
        $checkedInParticipants = $participants->where('is_checked_in', true);
        if ($checkedInParticipants->count() > 0) {
            $html .= '<h3 style="color: #1f2937; margin-top: 25px; margin-bottom: 15px;">Check-in Timeline</h3>';
            $html .= '<table border="1" cellpadding="6" cellspacing="0" style="width: 100%; border-collapse: collapse; border-color: #374151; font-family: Arial, sans-serif;">
                <thead>
                    <tr style="background-color: #f3f4f6; font-weight: bold; color: #111827; font-size: 10px;">
                        <th style="width: 30%; text-align: left; padding: 8px 6px; border: 1px solid #374151;">Name</th>
                        <th style="width: 18%; text-align: center; padding: 8px 6px; border: 1px solid #374151;">Student ID</th>
                        <th style="width: 25%; text-align: center; padding: 8px 6px; border: 1px solid #374151;">Course/Year/Section</th>
                        <th style="width: 27%; text-align: center; padding: 8px 6px; border: 1px solid #374151;">Check-in Time</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($checkedInParticipants->sortBy('check_in_time') as $participant) {
                $academicInfo = [];
                if (!empty($participant['course'])) $academicInfo[] = $participant['course'];
                if (!empty($participant['year_level'])) $academicInfo[] = $participant['year_level'];
                if (!empty($participant['section'])) $academicInfo[] = $participant['section'];
                $fullAcademicInfo = !empty($academicInfo) ? implode(' ', $academicInfo) : 'N/A';

                $html .= '<tr style="border-bottom: 1px solid #6b7280;">
                    <td style="width: 30%; padding: 6px; font-size: 9px; border-right: 1px solid #6b7280; text-align: left; vertical-align: top;">' . htmlspecialchars($participant['name']) . '</td>
                    <td style="width: 18%; padding: 6px; text-align: center; font-size: 9px; border-right: 1px solid #6b7280; vertical-align: top;">' . htmlspecialchars($participant['student_id'] ?? 'N/A') . '</td>
                    <td style="width: 25%; padding: 6px; text-align: center; font-size: 9px; border-right: 1px solid #6b7280; vertical-align: top;">' . htmlspecialchars($fullAcademicInfo) . '</td>
                    <td style="width: 27%; padding: 6px; text-align: center; font-size: 9px; vertical-align: top;">' . ($participant['check_in_time'] ?? 'N/A') . '</td>
                </tr>';
            }
            $html .= '</tbody></table>';
        }

        // Footer information
        $html .= '<br><br>
        <table border="0" cellpadding="5" style="margin-top: 20px;">
            <tr>
                <td style="font-size: 8px; color: #6b7280;">Report generated on: ' . now()->format('F d, Y \a\t g:i A') . '</td>
            </tr>
            <tr>
                <td style="font-size: 8px; color: #6b7280;">Generated by: CICTE CertChain System</td>
            </tr>
        </table>';

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html, true, false, true, false, '');

        // Close and output PDF document
        return $pdf->Output('attendance-report-' . $event->id . '.pdf', 'S');
    }
}
