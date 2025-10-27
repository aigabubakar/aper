<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Academic — Experience & Activities<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row">

						<!-- Sidebar -->
						<div class="col-lg-3 theiaStickySidebar">
							<div class="settings-sidebar mb-lg-0">
									<div>
										<h6 class="mb-3">Main Menu</h6>
										<ul class="mb-3 pb-1">
                     <li>
												<a href="<?= site_url('/dashboard') ?>" class="d-inline-flex align-items-center active"><i class="isax isax-grid-35 me-2"></i>Dashboard</a>
											</li>
                      <?php
                // sidebar-menu.php (or inside your existing partial)
                // Ensure $user is available (passed from controller) or use session()
                // $user = $user ?? session()->get('user') ?? [];

                $cat = $user['category'] ?? session()->get('category') ?? 'non_academic';
                $uri = service('uri');
                $current = trim($uri->getPath(), '/'); // e.g. "profile/personal"

                $menus = [
                    // common base node present for all
                    'profile' => [
                        'label' => 'Profile',
                        'url'   => site_url('profile/overview'),
                        'children' => [
                            // common links available to all categories
                            'personal' => ['label' => 'Personal', 'url' => site_url('profile/personal')],
                        ],
                    ],
                    // category-specific nodes (we can add/remove children below)
                ];

                // Extend children depending on category
                switch ($cat) {
                    case 'academic':
                        $menus['profile']['children'] += [
                          
                            'employment'   => ['label' => 'Employment',   'url' => site_url('profile/academic/employment')],
                            'qualifications' => ['label' => 'Qualifications/Professional', 'url' => site_url('profile/academic/qualifications')],
                            'experience'   => ['label' => 'Experience',   'url' => site_url('profile/academic/experience')],
                            'professional' => ['label' => 'Professional','url' => site_url('profile/academic/professional')],
                            'print'        => ['label' => 'Print Summary','url' => site_url('profile/print-summary')],
                        ];
                        break;

                    case 'senior_non_academic':
                        $menus['profile']['children'] += [
                            'employment'   => ['label' => 'Employment',   'url' => site_url('profile/senior/employment')],
                            'professional' => ['label' => 'Qualifications/Professional','url' => site_url('profile/senior/professional')],
                            'experience'   => ['label' => 'Experience',   'url' => site_url('profile/senior/experience')],
                            'print'        => ['label' => 'Print Summary','url' => site_url('profile/print-summary')],
                        ];
                        break;

                    case 'junior_non_academic':
                        $menus['profile']['children'] += [
                            'employment'   => ['label' => 'Employment',   'url' => site_url('profile/junior/employment')],
                            'professional' => ['label' => 'Professional','url' => site_url('profile/junior/professional')],
                            'print'        => ['label' => 'Print Summary','url' => site_url('profile/print-summary')],
                        ];
                        break;

                    case 'non_academic':
                    default:
                        $menus['profile']['children'] += [
                            'employment'   => ['label' => 'Employment',   'url' => site_url('profile/employment')],
                            'professional' => ['label' => 'Professional','url' => site_url('profile/professional')],
                            'print'        => ['label' => 'Print Summary','url' => site_url('profile/print-summary')],
                        ];
                        break;
                }

                // Utility: is current path starts with menu url path
                function isActive(string $menuUrlPath, string $currentPath): bool {
                    // normalize both
                    $menu = trim(parse_url($menuUrlPath, PHP_URL_PATH), '/');
                    if ($menu === '') return $currentPath === '';
                    // exact match or prefix match so "profile/academic/employment" matches "profile/academic/employment/save" etc.
                    return $currentPath === $menu || strpos($currentPath, $menu . '/') === 0;
                }
                ?>

                <ul class="sidebar-submenu list-unstyled">
                <?php foreach ($menus as $key => $node): 
                    $nodeActive = false;
                    // determine if any child is active
                    foreach ($node['children'] as $ck => $child) {
                        if (isActive($child['url'], $current)) { $nodeActive = true; break; }
                    }
                ?>
                  <li class="nav-item <?= $nodeActive ? 'open' : '' ?>">
                    <a class="nav-link <?= $nodeActive ? 'active' : '' ?>" href="<?= esc($node['url']) ?>">
                      <?= esc($node['label']) ?>
                    </a>

                    <?php if (! empty($node['children'])): ?>
                      <ul class="nav flex-column ms-3">
                        <?php foreach ($node['children'] as $ck => $child): 
                            $active = isActive($child['url'], $current) ? 'active' : '';
                        ?>
                          <li class="nav-item">
                            <a class="nav-link <?= $active ?>" href="<?= esc($child['url']) ?>"><?= esc($child['label']) ?></a>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>

										<hr>
										<h6 class="mb-3">Account Settings</h6>
										<ul>
											<li>
												<a href="<?= site_url('logout') ?>" class="d-inline-flex align-items-center"><i class="isax isax-logout5 me-2"></i>Logout</a>
											</li>
										</ul>
									</div>
							</div>
       </ul>
		</div>
<!-- /Sidebar -->
 <div class="col-lg-9">
<div class="row">
           
<div class="card mx-auto" style="max-width:1100px;">
  <div class="card-body">
   <h4 class="card-title">Stage 4 — Experience & Activities</h4>
    <p class="text-muted mb-3">Fill your teaching, professional experience, research outputs and activities.</p>

    <div id="alert-placeholder"><?= view('partials/flash') ?></div>

    <form id="experienceForm" action="<?= site_url('profile/academic/experience/save') ?>" method="post" novalidate>
      <?= csrf_field() ?>

     
        <legend class="w-auto px-2">Experience</legend>
        <div class="row g-3 mb-2">
          <div class="col-md-6">
            <label class="form-label">(a) University Teaching Experience</label>
            <textarea name="teaching_experience" class="form-control" rows="4"><?= esc(old('teaching_experience', $user['teaching_experience'] ?? '')) ?></textarea>
            <div class="form-text">Indicate institution, your designation, area of specialization, subjects taught and dates.</div>
          </div>
          <div class="col-md-6">
            <label class="form-label">(b) Professional Experience</label>
            <textarea name="professional_experience" class="form-control" rows="4"><?= esc(old('professional_experience', $user['professional_experience'] ?? '')) ?></textarea>
            <div class="form-text">Employer, designation, nature of duty and dates.</div>
          </div>
        </div>
  

     
        <legend class="w-auto px-2">Research</legend>
        <div class="row g-3 mb-2">
          <div class="col-md-6">
            <label class="form-label">(i) Dissertation or Thesis</label>
            <input name="dissertation" class="form-control" value="<?= esc(old('dissertation', $user['dissertation'] ?? '')) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">(ii) Articles published in learned journals</label>
            <input name="articles" class="form-control" value="<?= esc(old('articles', $user['articles'] ?? '')) ?>">
          </div>
        </div>

        <div class="row g-3 mb-2">
          <div class="col-md-6">
            <label class="form-label">(iii) Books and Monographs</label>
            <input name="books_monographs" class="form-control" value="<?= esc(old('books_monographs', $user['books_monographs'] ?? '')) ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label">(iv) Papers already accepted for publication</label>
            <input name="papers_accepted" class="form-control" value="<?= esc(old('papers_accepted', $user['papers_accepted'] ?? '')) ?>">
          </div>
        </div>
      

      
        <legend class="w-auto px-2">Contribution & Unpublished Papers</legend>
        <div class="mb-3">
          <label class="form-label">State briefly any breakthrough or significant contribution to knowledge you have made</label>
          <textarea name="contribution_to_knowledge" class="form-control" rows="3"><?= esc(old('contribution_to_knowledge', $user['contribution_to_knowledge'] ?? '')) ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Unpublished Paper read at Conferences (Title, Conference and Dates)</label>
          <textarea name="unpub_paper_conference" class="form-control" rows="3"><?= esc(old('unpub_paper_conference', $user['unpub_paper_conference'] ?? '')) ?></textarea>
        </div>
      

     
        <legend class="w-auto px-2">Activities</legend>
        <div class="mb-3">
          <label class="form-label">Other Activities within the University (participation in Department/Faculty and University actions etc.)</label>
          <textarea name="activities_within_university" class="form-control" rows="3"><?= esc(old('activities_within_university', $user['activities_within_university'] ?? '')) ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Other activities outside regular University work</label>
          <textarea name="activities_outside_university" class="form-control" rows="3"><?= esc(old('activities_outside_university', $user['activities_outside_university'] ?? '')) ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Courses/Conferences undertaken during the period of report</label>
          <textarea name="courses_conferences" class="form-control" rows="2"><?= esc(old('courses_conferences', $user['courses_conferences'] ?? '')) ?></textarea>
        </div>
     

      <div class="d-flex justify-content-between">
        <a href="<?= site_url('profile/academic/qualifications') ?>" class="btn btn-outline-secondary">Back</a>
        <button id="experienceSaveBtn" class="btn btn-primary" type="submit">
          <span id="experienceSaveText">Finish</span>
          <span id="experienceSpinner" class="spinner-border spinner-border-sm ms-2 d-none" aria-hidden="true"></span>
        </button>
      </div>
    </form>
</div>
<!-- Employement Information-->
   </div>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('experienceForm');
  const btn = document.getElementById('experienceSaveBtn');
  const btnText = document.getElementById('experienceSaveText');
  const spinner = document.getElementById('experienceSpinner');
  const alertPlaceholder = document.getElementById('alert-placeholder');

  function setLoading(on) {
    if (on) { btn && btn.setAttribute('disabled','disabled'); btnText && (btnText.textContent='Saving...'); spinner && spinner.classList.remove('d-none'); }
    else { btn && btn.removeAttribute('disabled'); btnText && (btnText.textContent='Finish'); spinner && spinner.classList.add('d-none'); }
  }

  function showAlert(type, html) {
    if (!alertPlaceholder) return;
    alertPlaceholder.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${html}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
    alertPlaceholder.scrollIntoView({behavior:'smooth', block:'center'});
  }

  if (!form) return;
  form.addEventListener('submit', async function(e){
    e.preventDefault(); e.stopPropagation();
    alertPlaceholder && (alertPlaceholder.innerHTML = '');
    setLoading(true);

    const fd = new FormData(form);
    try {
      const res = await fetch(form.action, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      const ct = (res.headers.get('content-type') || '').toLowerCase();
      if (! ct.includes('application/json')) {
        const txt = await res.text();
        console.error('Unexpected response:', txt);
        await Swal.fire({icon:'error', title:'Server error', text:'Unexpected server response. See console.'});
        setLoading(false);
        return;
      }

      const data = await res.json();
      if (! res.ok) {
        if (data.errors) {
          const list = Object.values(data.errors).map(v => `<li>${v}</li>`).join('');
          showAlert('danger', `<ul class="mb-0">${list}</ul>`);
        } else {
          showAlert('danger', data.message || 'Server error');
        }
        setLoading(false);
        return;
      }

      await Swal.fire({icon:'success', title:'Done', html:`${data.message || 'Profile completed'}`, timer:data.redirectDelay || 1200, showConfirmButton:false, timerProgressBar:true});
      if (data.redirect) window.location.href = data.redirect;
      else window.location.href = '<?= site_url('profile/success') ?>';

    } catch (err) {
      console.error(err);
      showAlert('danger', 'Network error. Check console.');
      setLoading(false);
    } finally {
      setTimeout(()=>setLoading(false), 300);
    }
  });
});
</script>
<?= $this->endSection() ?>
