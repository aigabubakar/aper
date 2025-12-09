
<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Profile Summary<?= $this->endSection() ?>


<?= $this->section('styles') ?>
<style>
@media print {
  body * { visibility: hidden; }
  #printArea, #printArea * { visibility: visible; }
  #printArea {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    padding: 24px;
    box-sizing: border-box;
  }
  .no-print { display: none !important; }
  .watermark { opacity: 0.08 !important; -webkit-print-color-adjust: exact; color: #000 !important; }
  .stamp { opacity: 0.5 !important; -webkit-print-color-adjust: exact; }
}

/* General layout */
.summary-card { max-width: 1000px; margin: 0 auto; position: relative; }
.section-title { background:#f5f7fa; padding:8px 12px; margin-bottom:8px; border-left:4px solid #0d6efd; font-weight:600; }
.field { display:flex; gap:10px; padding:6px 0; border-bottom:1px solid #eee; }
.field label { min-width:220px; font-weight:600; color:#333; }
.field .value { flex:1; color:#111; }
.small { font-size:0.9rem; color:#666; }
.print-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }

/* WATERMARK */
.watermark {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -70%) rotate(-30deg);
  transform-origin: center;
  z-index: 0;
  pointer-events: none;
  user-select: none;
  white-space: nowrap;
  font-weight: 700;
  font-family: "Helvetica Neue", Arial, sans-serif;
  font-size: 5rem;
  color: rgba(0,0,0,0.06);
  text-align: center;
  width: 120%;
  text-transform: uppercase;
  letter-spacing: 2px;
}
@media print {
  .watermark { color: rgba(0,0,0,0.12); opacity: 0.12; font-size: 60px; }
}

/* HR STAMP */
.stamp {
  position: absolute;
  right: 40px;
  bottom: 40px;
  z-index: 2;
  border: 2px solid #d00;
  color: #d00;
  padding: 10px 18px;
  border-radius: 10px;
  text-align: center;
  font-weight: bold;
  font-family: "Courier New", monospace;
  transform: rotate(-10deg);
  background: rgba(255,255,255,0.85);
  box-shadow: 0 0 5px rgba(0,0,0,0.2);
  opacity: 0.8;
}
.stamp .stamp-text { font-size: 0.9rem; letter-spacing: 1px; }
.stamp .stamp-time { font-size: 0.75rem; margin-top: 3px; display:block; }

/* ensure card content above watermark but below stamp */
#printArea {
  position: relative;
  z-index: 1;
  background: #fff;
  margin: 0 auto;
  padding: 16px 24px;
  box-sizing: border-box;
}

@media print {
  html, body {
    margin: 0 !important;
    padding: 0 !important;
  }

  body {
    background: #fff !important;
  }

  #printArea {
    margin: 0 !important;
    padding-top: 0 !important;
    top: 0 !important;
  }

  /* Hide layout wrappers */
  header, footer, .navbar, .sidebar, .page-title, .no-print, .card-header {
    display: none !important;
  }

  .card {
    box-shadow: none !important;
    border: none !important;
  }
}
@media print {
  .section-title {
    page-break-before: avoid;
    page-break-after: avoid;
  }
  .field {
    page-break-inside: avoid;
  }
}


</style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>   
					<div class="row">
						<!-- Sidebar -->
               <?= view('layouts/sidebar') ?>
						<div class="col-lg-9">
							<div class="page-title d-flex align-items-center justify-content-between">
            </div>
							<div class="card">
								 <div class="print-header">
                  <div>
                    <h3>Edo State University Iyamho 2025 APER <br/> Staff Profile Summary</h3>
                    <div class="small">Generated: <?= date('Y-m-d H:i') ?></div>
                  </div>
                  <div class="no-print">
                    <a class="btn btn-outline-secondary" href="<?= site_url('dashboard') ?>">Back to Dashboard</a>
                    <!-- <button class="btn btn-primary" onclick="window.print()">Print</button> -->

                    <a class="btn btn-outline-primary" href="<?= site_url('admin/profile/' . (int)$user['id'] . '/download') ?>">
                      Download PDF
                    </a>

                  </div>
                </div>
								<div class="row">

                
  <div id="printArea" class="card p-3">
    <!-- WATERMARK -->
    <div class="watermark">Edo State University Iyamho <br/>&copy;2025 APER</div>

    <!-- HR STAMP -->
    <div class="stamp">
      <div class="stamp-text">EDO STATE UNIVERSITY<br>
      &copy; <?= date('Y') ?>&nbsp;APER</div>
      <span class="stamp-time"><?= date('Y-m-d H:i:s') ?></span>
    </div>

    <!-- Basic -->
    <div class="section-title">Basic Personal Registration</div>
    <div class="mb-2">
      <div class="field"><label>Full name</label><div class="value"><?= esc($user['fullname'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Email</label><div class="value"><?= esc($user['email'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Staff ID</label><div class="value"><?= esc($user['staff_id'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Phone</label><div class="value"><?= esc($user['phone'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Date of Birth</label><div class="value"><?= esc($user['dob'] ?? 'N/A') ?></div></div>

      <div class="field"><label>Category</label><div class="value"><?= esc($user['category'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Reporting period</label><div class="value"><?= esc($user['period_from'] ?? 'N/A') ?> — <?= esc($user['period_to'] ?? 'N/A') ?></div></div>
    </div>

    <!-- Employment -->
    <div class="section-title">Employment History</div>
    <div class="mb-2">
      <div class="field"><label>Present salary</label><div class="value"><?= esc($user['present_salary'] ?? 'N/A') ?></div></div>
      <div class="field"><label>CONTISS / Step</label><div class="value"><?= esc($user['contiss'] ?? 'N/A') ?> / <?= esc($user['step'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Date of first appointment</label><div class="value"><?= esc($user['date_of_first_appointment'] ?? 'N/A') ?></div></div>
      <div class="field"><label>First appointment grade</label><div class="value"><?= esc($user['first_appointment_grade'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Last promotion date</label><div class="value"><?= esc($user['last_promotion_date'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Last promotion grade</label><div class="value"><?= esc($user['last_promotion_grade'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Current appointment date</label><div class="value"><?= esc($user['current_appointment_date'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Current appointment grade</label><div class="value"><?= esc($user['current_appointment_grade'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Appointment confirmed</label><div class="value"><?= (isset($user['appointment_confirmed']) ? (($user['appointment_confirmed'] == 1 || $user['appointment_confirmed'] === 'yes') ? 'Yes' : 'No') : 'N/A') ?></div></div>
      <div class="field"><label>Appointment confirmed at</label><div class="value"><?= esc($user['appointment_confirmed_at'] ?? 'N/A') ?></div></div>

     <div class="field"><label>Faculty</label><div class="value"><?= esc($facultyName ?? ($user['faculty'] ?? 'N/A')) ?></div></div>
      <div class="field"><label>Department</label><div class="value"><?= esc($departmentName ?? ($user['department'] ?? 'N/A')) ?></div></div>
      <div class="field"><label>Designation</label><div class="value"><?= esc($user['designation'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Grade level</label><div class="value"><?= esc($user['grade_level'] ?? 'N/A') ?></div></div>
    </div>

    <!-- Qualifications -->
    <div class="section-title">Academic Qualifications</div>
    <div class="mb-2">
      <?php for ($i=1;$i<=5;$i++): 
        $q = $user["qual{$i}"] ?? null;
        $grade = $user["qual{$i}_grade"] ?? null;
        $ins = $user["qual{$i}_institution"] ?? null;
        $d = $user["qual{$i}_date"] ?? null;
        if (! $q && ! $grade && ! $ins && ! $d) continue;
      ?>
        <div class="field"><label>Qualification #<?= $i ?></label>
          <div class="value">
            <div><strong><?= esc($q ?? 'N/A') ?></strong></div>
            <div class="small"><?= esc($ins ?? '') ?> <?= $d ? ' — ' . esc($d) : '' ?> <?= $grade ? ' ('.$grade.')' : '' ?></div>
          </div>
        </div>
      <?php endfor; ?>
    </div>

    <div class="section-title">Professional Qualifications</div>
    <div class="mb-2">
      <?php for ($i=1;$i<=5;$i++):
        $pq = $user["prof_qual{$i}"] ?? null;
        $pb = $user["prof_qual{$i}_body"] ?? null;
        $pd = $user["prof_qual{$i}_date"] ?? null;
        if (! $pq && ! $pb && ! $pd) continue;
      ?>
        <div class="field"><label>Professional #<?= $i ?></label>
          <div class="value">
            <div><strong><?= esc($pq ?? 'N/A') ?></strong></div>
            <div class="small"><?= esc($pb ?? '') ?> <?= $pd ? ' — ' . esc($pd) : '' ?></div>
          </div>
        </div>
      <?php endfor; ?>
    </div>
    
    
    <div class="section-title">Experience & Research</div>
    <div class="mb-2">
      <div class="field"><label>University Teaching Experience</label><div class="value"><?= nl2br(esc($user['teaching_experience'] ?? 'N/A')) ?></div></div>
      <div class="field"><label>Professional Experience</label><div class="value"><?= nl2br(esc($user['professional_experience'] ?? 'N/A')) ?></div></div>
      <div class="field"><label>Dissertation / Thesis</label><div class="value"><?= esc($user['dissertation'] ?? 'N/A') ?></div></div>
      <div class="field"><label>Articles (published)</label><div class="value"><?= nl2br(esc($user['articles'] ?? 'N/A')) ?></div></div>
      <div class="field"><label>Books / Monographs</label><div class="value"><?= nl2br(esc($user['books_monographs'] ?? 'N/A')) ?></div></div>
      <div class="field"><label>Papers accepted for publication</label><div class="value"><?= nl2br(esc($user['papers_accepted'] ?? 'N/A')) ?></div></div>
      <div class="field"><label>Contribution to knowledge</label><div class="value"><?= nl2br(esc($user['contribution_to_knowledge'] ?? 'N/A')) ?></div></div>
      <div class="field"><label>Unpublished papers / conferences</label><div class="value"><?= nl2br(esc($user['unpub_paper_conference'] ?? 'N/A')) ?></div></div>
    </div>

    <div class="section-title">Activities</div>
    <div class="mb-2"> 

    
      <div class="field">
        <label>Within University</label><div class="value"><?= nl2br(esc($user['exp_out_institution_name1'] ?? 'N/A')) ?></div> 
        <div class="value"><?= nl2br(esc($user['exp_out_designation1'] ?? 'N/A')) ?></div>
         <div class="value"><?= nl2br(esc($user['exp_out_specialization1'] ?? 'N/A')) ?></div>
        <div class="value"><?= nl2br(esc($user['exp_out_date1'] ?? 'N/A')) ?></div><br/>
    </div>
      <div class="field"><label>Outside University</label><div class="value"><?= nl2br(esc($user['exp_out_institution_name2'] ?? 'N/A')) ?></div>
    <div class="value"><?= nl2br(esc($user['exp_out_designation2'] ?? 'N/A')) ?></div>
         <div class="value"><?= nl2br(esc($user['exp_out_specialization2'] ?? 'N/A')) ?></div>
        <div class="value"><?= nl2br(esc($user['exp_out_date2'] ?? 'N/A')) ?></div><br/>
    </div>
      <div class="field"><label>Courses / Conferences</label><div class="value"><?= nl2br(esc($user['courses_conferences'] ?? 'N/A')) ?></div></div>
      <div class="field"><label>Other notes</label><div class="value"><?= nl2br(esc($user['other_notes'] ?? '')) ?></div></div>
    </div>

    <div class="mt-3 small">This summary is generated from the staff profile data saved in the system. For corrections contact ICT.</div>
    </div>          
   </div>
   </div>
	</div>								
  </div>
  </div>
 </div>

			
<?= $this->endSection() ?>

