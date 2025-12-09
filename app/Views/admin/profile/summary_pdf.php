<?php // app/Views/admin/profile/summary_pdf.php

?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Profile Summary - <?= esc($user['fullname'] ?? 'N/A') ?></title>

<style>
/* Force zero page margins for Dompdf */
@page { margin: 0; }

/* Global reset for printing */
@media print {
  html, body { margin: 0 !important; padding: 0 !important; height: auto !important; background: #fff !important; }
}

/* Container used by Dompdf as printable area */
#printArea {
  position: relative;
  z-index: 1;
  background: #fff;
  margin: 0 auto;
  padding: 12mm; /* printable padding */
  box-sizing: border-box;
  max-width: 1000px;
}

/* Make watermark appear on every page: use fixed positioning */
.watermark {
  position: fixed;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%) rotate(-30deg);
  z-index: 0;
  pointer-events: none;
  user-select: none;
  white-space: nowrap;
  font-weight: 500;
  font-family: "Helvetica Neue", Arial, sans-serif;
  font-size: 72px; /* large for print */
  color: rgba(0,0,0,0.06);
  text-align: center;
  width: 90%;
  text-transform: uppercase;
  letter-spacing: 2px;
}

/* Stamp fixed on every page (bottom-right) */
.stamp {
  position: fixed;
  right: 18mm;
  bottom: 18mm;
  z-index: 2;
  border: 2px solid #d00;
  color: #d00;
  padding: 8px 14px;
  border-radius: 6px;
  text-align: center;
  font-weight: bold;
  font-family: "Courier New", monospace;
  transform: rotate(-8deg);
  background: rgba(255,255,255,0.85);
  box-shadow: 0 0 5px rgba(0,0,0,0.12);
}

/* Header area */
.print-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }

/* Section title */
.section-title { background:#f5f7fa; padding:8px 12px; margin-bottom:8px; border-left:4px solid #0d6efd; font-weight:600; }

/* Field rows: flexible, supports merging */
.field-row {
  display: grid;
  grid-template-columns: 1fr 1fr; /* 2 columns when space available */
  gap: 12px;
  margin-bottom: 6px;
}
.field-row .field {
  display: flex;
  gap:10px;
  align-items: flex-start;
  border-bottom: 1px solid #eee;
  padding-bottom: 6px;
  padding-top: 6px;
}
.field-row .field label { min-width: 140px; font-weight:600; color:#333; }
.field-row .field .value { flex:1; color:#111; }

/* single field full width fallback */
.field { display:flex; gap:10px; padding:6px 0; border-bottom:1px solid #eee; }
.field label { min-width:220px; font-weight:600; color:#333; }
.field .value { flex:1; color:#111; }

/* smaller text */
.small { font-size:0.9rem; color:#666; }

/* ensure watermark/stamp visible on print */
@media print {
  .watermark { color: rgba(0,0,0,0.10); font-size: 60px; }
  .stamp { opacity: 1; }
  /* hide layout wrappers present in site */
  header, footer, .navbar, .sidebar, .no-print, .page-title { display: none !important; }
}

/* page margins for Dompdf */
@page { margin: 0; }
#printArea { padding: 12mm; max-width: 1000px; margin: 0 auto; background:#fff; }

/* table layout for fields */
.field-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
.field-table td, .field-table th { vertical-align: top; padding: 6px 8px; border-bottom: 1px solid #eee; }
.field-label { width: 30%; font-weight:700; color:#222; padding-right: 8px; }
.field-value { width: 70%; color:#111; }

/* If you want the right-hand column labels to be slightly narrower */
.field-table .right .field-label { width: 18%; }

/* Full width (single cell) */
.field-full { padding: 8px 8px; border-bottom: 1px solid #eee; }

/* small text style */
.small { font-size:0.9rem; color:#666; }

/* keep watermark & stamp fixed as in your working version */
.watermark { position: fixed; left: 50%; top: 50%; transform: translate(-50%,-50%) rotate(-30deg); z-index:0; color: rgba(0,0,0,0.06); font-size:72px; }
.stamp { position: fixed; right: 18mm; bottom: 18mm; z-index:2; border: 2px solid #d00; color:#d00; padding:8px 14px; background: rgba(255,255,255,0.85); transform: rotate(-8deg); }
</style>

</head>
<body>
 

<!-- WATERMARK AND STAMP (keep in page) -->
<div class="watermark">Edo State University Iyamho<br><?= date('Y') ?> APER</div>
<div class="stamp">
  <div class="stamp-text">EDO STATE UNIVERSITY<br>&copy; <?= date('Y') ?> APER</div>
  <span class="stamp-time"><?= date('Y-m-d H:i:s') ?></span>
</div>

<!-- PRINT AREA -->
<div id="printArea">
<div class="print-header">
        <div>
          <h3>Edo State University Iyamho — Staff Profile Summary</h3>
          <div class="small">Generated: <?= date('Y-m-d H:i') ?></div>     
        <div class="small">APER <?= date('Y') ?></div>
           </div>
      </div>

  <!-- Example: two-column rows using table -->
   <div class="section-title">Basic Personal Registration</div>
  <table class="field-table">
    <tr>
      <td class="field-label">Full name</td>
      <td class="field-value"><?= esc($user['fullname'] ?? 'N/A') ?></td>

      <td class="field-label right">Staff ID</td>
      <td class="field-value"><?= esc($user['staff_id'] ?? 'N/A') ?></td>
    </tr>

    <tr>
      <td class="field-label">Email</td>
      <td class="field-value"><?= esc($user['email'] ?? 'N/A') ?></td>

      <td class="field-label right">Phone</td>
      <td class="field-value"><?= esc($user['phone'] ?? 'N/A') ?></td>
    </tr>

    <!-- Single full-width field -->
    <tr>
      <td colspan="4" class="field-full">
        <strong>Reporting period:</strong> <?= esc($user['period_from'] ?? 'N/A') ?> — <?= esc($user['period_to'] ?? 'N/A') ?>
      </td>
    </tr>
  </table>

  <!-- Employment: contiss + step on same row -->
  <table class="field-table">
    <tr>
      <td class="field-label">Present salary</td>
      <td class="field-value"><?= esc($user['present_salary'] ?? 'N/A') ?></td>

      <td class="field-label right">CONTISS / Step</td>
      <td class="field-value"><?= esc($user['contiss'] ?? 'N/A') ?> / <?= esc($user['step'] ?? 'N/A') ?></td>
    </tr>

    <tr>
      <td class="field-label">Faculty</td>
      <td class="field-value"><?= esc($facultyName ?? ($user['faculty'] ?? 'N/A')) ?></td>

      <td class="field-label right">Department</td>
      <td class="field-value"><?= esc($departmentName ?? ($user['department'] ?? 'N/A')) ?></td>
    </tr>
  </table>

  <!-- Qualifications: show up to 3 columns per row if you like by adjusting table -->
  <div class="section-title">Academic Qualifications</div>
  
  <table class="field-table">
    <?php for ($i = 1; $i <= 5; $i++):
        $q = $user["qual{$i}"] ?? null;
        $grade = $user["qual{$i}_grade"] ?? null;
        $ins = $user["qual{$i}_institution"] ?? null;
        $d = $user["qual{$i}_date"] ?? null;
        if (! $q && ! $grade && ! $ins && ! $d) continue;
    ?>
      <tr>
        <td class="field-label">Qualification #<?= $i ?></td>
        <td class="field-value">
          <strong><?= esc($q ?? 'N/A') ?></strong><br/>
          <span class="small"><?= esc($ins ?? '') ?> <?= $d ? ' — ' . esc($d) : '' ?> <?= $grade ? ' ('.$grade.')' : '' ?></span>
        </td>
        <td class="field-value"></td>
      </tr>
    <?php endfor; ?>
  </table>


   <!--Professional Qualifications: show up to 3 columns per row if you like by adjusting table -->
      <div class="section-title">Professional Qualifications</div>

  <table class="field-table">
     <?php for ($i=1;$i<=5;$i++):
        $pq = $user["prof_qual{$i}"] ?? null;
        $pb = $user["prof_qual{$i}_body"] ?? null;
        $pd = $user["prof_qual{$i}_date"] ?? null;
        if (! $pq && ! $pb && ! $pd) continue;
      ?>
      <tr>
        <td class="field-label">Qualification #<?= $i ?></td>
        <td class="field-value">
          <strong><?= esc($pq ?? 'N/A') ?></strong><br/>
          <span class="small">   <?= esc($pb ?? '') ?> <?= $pd ? ' — ' . esc($pd) : '' ?></span>
        </td>
        <!-- optionally leave the right columns blank or use to show another item -->
        <td class="field-value"></td>
      </tr>
    <?php endfor; ?>
  </table>

   <!-- Example: two-column rows using table -->
   <div class="section-title">Experience & Research</div>
  <table class="field-table">
    <tr>
      <td class="field-label">University Teaching Experience</td>
      <td class="field-value"><?= esc($user['teaching_experience'] ?? 'N/A') ?></td>
    </tr>
    <tr>
      <td class="field-label right">Professional Experience</td>
      <td class="field-value"><?= esc($user['professional_experience'] ?? 'N/A') ?></td>
    </tr>

    <tr>
      <td class="field-label">Dissertation / Thesis</td>
      <td class="field-value"><?= esc($user['dissertation'] ?? 'N/A') ?></td>
    </tr>
    <!-- Single full-width field -->
    <tr>
      <td colspan="4" class="field-full">
        <strong>Articles (published)</strong> <?= esc($user['articles'] ?? 'N/A') ?>
      </td>
    </tr>

    <tr>
      <td colspan="4" class="field-full">
        <strong>Books / Monographs</strong> <?= esc($user['books_monographs'] ?? 'N/A') ?>
      </td>
    </tr>
     <tr>
      <td colspan="4" class="field-full">
        <strong>Papers accepted for publication</strong> <?= esc($user['papers_accepted'] ?? 'N/A') ?>
      </td>
    </tr>
      <tr>
      <td colspan="4" class="field-full">
        <strong>Contribution to knowledge</strong> <?= esc($user['contribution_to_knowledge'] ?? 'N/A') ?>
      </td>
    </tr>
     <tr>
      <td colspan="4" class="field-full">
        <strong>Unpublished papers / conferences</strong> <?= esc($user['unpub_paper_conference'] ?? 'N/A') ?>
      </td>
    </tr>
  </table>

  <!-- Continue rest of sections similarly -->
       <div class="mt-3 small">This summary is generated from the staff profile data saved in the system. For corrections contact ICT.</div>

</div>

</div>



</body>
</html>



