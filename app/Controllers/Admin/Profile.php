<?php namespace App\Controllers\Admin;

use App\Controllers\Admin\AdminBaseController;
use Dompdf\Dompdf;
use Dompdf\Options;

class Profile extends AdminBaseController
{
    public function downloadSummaryPdf($id = null)
    {
        $this->guard(); // optional

        $id = (int)$id;
        if (! $id) {
            return redirect()->back()->with('error', 'Invalid user id');
        }

        // Load user (adjust model to your UserModel location)
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($id);
        if (! $user) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Resolve faculty/department names if needed
        $facultyName = null;
        $departmentName = null;
        if (class_exists(\App\Models\FacultyModel::class)) {
            $fm = new \App\Models\FacultyModel();
            $f = $fm->find($user['faculty_id'] ?? null);
            $facultyName = $f['name'] ?? null;
        }
        if (class_exists(\App\Models\DepartmentModel::class)) {
            $dm = new \App\Models\DepartmentModel();
            $d = $dm->find($user['department_id'] ?? null);
            $departmentName = $d['name'] ?? null;
        }

        // Render view to HTML (use the PDF-specific view above) 
        $html = view('admin/profile/summary_pdf', [
            'user' => $user,
            'facultyName' => $facultyName,
            'departmentName' => $departmentName,
        ]);

        // Configure Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true); // allow remote images if used
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        // Paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render and stream PDF
        $dompdf->render();

        // Optional: clear any output buffers
        if (ob_get_length()) ob_end_clean();

        $filename = 'profile_summary_' . ($user['staff_id'] ?? $user['id']) . '_' . date('Ymd_His') . '.pdf';
        // Stream: true = open in browser, false = force download
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }
}
