<?php // app/Views/admin/profile/summary_pdf.php
// Expects $user, $facultyName, $departmentName (same data you used in the HTML view)

?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Profile Summary - <?= esc($user['fullname'] ?? 'N/A') ?></title>

<style>
/* --- Page / PDF layout - remove top whitespace --- */
/* Set page margins to zero or small defined margin */
@page {
  size: A4;
  margin: 10mm 12mm; /* <--- change to 0mm if you want literally no margin */
}

html, body {
  margin: 0;
  padding: 0;
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
  color: #111;
  background: #fff;
  -webkit-print-color-adjust: exact;
}

/* Container that will be rendered to PDF */
#printArea {
  width: 100%;
  box-sizing: border-box;
  padding: 6mm 6mm; /* small internal padding */
  margin: 0;
}

/* Visual layout */
.summary-card { max-width: 1000px; margin: 0 auto; position: relative; }
.print-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:6px; }
.print-header h3 { margin:0; font-size:16px; }
.small { font-size:11px; color:#555; }
.section-title { background:#f5f7fa; padding:6px 8px; margin:8px 0 6px; border-left:4px solid #0d6efd; font-weight:600; font-size:13px; }
.field { display:flex; gap:10px; padding:6px 0; border-bottom:1px solid #eee; }
.field label { min-width:180px; font-weight:600; color:#333; font-size:12px; }
.field .value { flex:1; color:#111; font-size:12px; }
.watermark { display:none !important; } /* hide complex watermark for PDF generation or enable a subtle one if needed */

/* stamp (optional) */
.stamp { position: absolute; right: 12mm; bottom: 12mm; border: 1px solid #d00; color: #d00; padding: 6px 10px; border-radius:6px; font-family: monospace; font-size:10px; transform: rotate(-6deg); background: rgba(255,255,255,0.95); }

/* Prevent page-break inside fields */
.field { page-break-inside: avoid; }

/* Headings */
h4 { margin: 6px 0; font-size: 14px; }

/* For small screens when previewing in browser */
@media screen {
  body { background: #f6f7f9; padding: 8px; }
  #printArea { background: #fff; box-shadow: 0 0 8px rgba(0,0,0,0.06); }
}
</style>
</head>
<body>
  <div id="printArea">
    <div class="summary-card">
      <div class="print-header">
        <div>
          <h3>Edo State University Iyamho — Staff Profile Summary</h3>
          <div class="small">Generated: <?= date('Y-m-d H:i') ?></div>
        </div>
        <div class="small">APER <?= date('Y') ?></div>
      </div>

      <div class="section-title">Basic Personal Registration</div>

      <div class="field"><label>Full name</label><div class="value"><?= esc($user['fullname'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Email</label><div class="value"><?= esc($user['email'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Staff ID</label><div class="value"><?= esc($user['staff_id'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Phone</label><div class="value"><?= esc($user['phone'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Date of Birth</label><div class="value"><?= esc($user['dob'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Category</label><div class="value"><?= esc($user['category'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Reporting period</label><div class="value"><?= esc($user['period_from'] ?? 'N/A') ?> — <?= esc($user['period_to'] ?? 'N/A') ?></div></div>

      <div class="section-title">Employment History</div>
      <div class="field"><label>Present salary</label><div class="value"><?= esc($user['present_salary'] ?? 'N/A') ?></div></div>
      <div class="field"><label>CONTISS / Step</label><div class="value"><?= esc($user['contiss'] ?? 'N/A') ?> / <?= esc($user['step'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Date of first appointment</label><div class="value"><?= esc($user['date_of_first_appointment'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Current appointment grade</label><div class="value"><?= esc($user['current_appointment_grade'] ?? 'N/A') ?></div></div>

      <div class="field"><label>Faculty</label><div class="value"><?= esc($facultyName ?? ($user['faculty'] ?? 'N/A')) ?></div></div>
      <div class="field"><label>Department</label><div class="value"><?= esc($departmentName ?? ($user['department'] ?? 'N/A')) ?></div></div>

      <?php if (! empty($user['qual1']) || ! empty($user['qual1_grade'])): ?>
        <div class="section-title">Academic Qualifications</div>
        <?php for ($i=1;$i<=5;$i++): 
          $q = $user["qual{$i}"] ?? null;
          $ins = $user["qual{$i}_institution"] ?? null;
          $d = $user["qual{$i}_date"] ?? null;
          $grade = $user["qual{$i}_grade"] ?? null;
          if (! $q && ! $ins && ! $d && ! $grade) continue;
        ?>
          <div class="field">
            <label>Qualification #<?= $i ?></label>
            <div class="value">
              <div><strong><?= esc($q ?? 'N/A') ?></strong></div>
              <div class="small"><?= esc($ins ?? '') ?> <?= $d ? ' — ' . esc($d) : '' ?> <?= $grade ? ' ('.esc($grade).')' : '' ?></div>
            </div>
          </div>
        <?php endfor; ?>
      <?php endif; ?>

      <!-- Experience & Activities trimmed for PDF brevity (add more sections as needed) -->
      <div class="section-title">Experience</div>
      <div class="field"><label>University Teaching Experience</label><div class="value"><?= nl2br(esc($user['teaching_experience'] ?? 'N/A')) ?></div></div>

      <div style="height:6mm;"></div> <!-- small space at end so stamp doesn't overlap text -->
      <div class="stamp">APER <?= date('Y') ?></div>
    </div>
  </div>
</body>
</html>
