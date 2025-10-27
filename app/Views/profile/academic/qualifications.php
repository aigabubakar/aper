<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Academic — Qualifications<?= $this->endSection() ?>

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
   <h4 class="card-title">Qualifications</h4>
    <p class="text-muted mb-3">Enter up to 5 academic qualifications and up to 5 professional qualifications.</p>

    <div id="alert-placeholder"><?= view('partials/flash') ?></div>

    <form id="qualForm" action="<?= site_url('profile/academic/qualifications/save') ?>" method="post" novalidate>
      <?= csrf_field() ?>

      <h5>Academic Qualifications</h5>
      <div class="row g-3 mb-3">
        <?php for ($i=1;$i<=5;$i++): 
          $q = $user["qual{$i}"] ?? '';
          $g = $user["qual{$i}_grade"] ?? '';
          $ins = $user["qual{$i}_institution"] ?? '';
          $d = $user["qual{$i}_date"] ?? '';
        ?>
        <div class="col-12 border rounded p-3 mb-2">
          <div class="row g-2 align-items-end">
            <div class="col-md-5">
              <label class="form-label">Qualification #<?= $i ?></label>
              <input name="qual<?= $i ?>" class="form-control" value="<?= esc(old("qual{$i}", $q)) ?>" placeholder="e.g. B.Sc Computer Science">
            </div>
            <div class="col-md-2">
              <label class="form-label">Grade</label>
              <input name="qual<?= $i ?>_grade" class="form-control" value="<?= esc(old("qual{$i}_grade", $g)) ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Institution</label>
              <input name="qual<?= $i ?>_institution" class="form-control" value="<?= esc(old("qual{$i}_institution", $ins)) ?>">
            </div>
            <div class="col-md-2">
              <label class="form-label">Date</label>
              <input type="date" name="qual<?= $i ?>_date" class="form-control" value="<?= esc(old("qual{$i}_date", $d)) ?>">
            </div>
          </div>
        </div>
        <?php endfor; ?>
      </div>

      <h5>Professional Qualifications</h5>
      <div class="row g-3 mb-3">
        <?php for ($i=1;$i<=5;$i++):
          $pq = $user["prof_qual{$i}"] ?? '';
          $pb = $user["prof_qual{$i}_body"] ?? '';
          $pd = $user["prof_qual{$i}_date"] ?? '';
        ?>
        <div class="col-12 border rounded p-3 mb-2">
          <div class="row g-2 align-items-end">
            <div class="col-md-5">
              <label class="form-label">Professional Qualification #<?= $i ?></label>
              <input name="prof_qual<?= $i ?>" class="form-control" value="<?= esc(old("prof_qual{$i}", $pq)) ?>" placeholder="e.g. PhD (Professional) or PGDip">
            </div>
            <div class="col-md-4">
              <label class="form-label">Awarding Body / Society</label>
              <input name="prof_qual<?= $i ?>_body" class="form-control" value="<?= esc(old("prof_qual{$i}_body", $pb)) ?>">
            </div>
            <div class="col-md-3">
              <label class="form-label">Date</label>
              <input type="date" name="prof_qual<?= $i ?>_date" class="form-control" value="<?= esc(old("prof_qual{$i}_date", $pd)) ?>">
            </div>
          </div>
        </div>
        <?php endfor; ?>
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?= site_url('profile/academic/employment') ?>" class="btn btn-outline-secondary">Back</a>
        <button id="qualSaveBtn" class="btn btn-primary" type="submit">
          <span id="qualSaveText">Save & Continue</span>
          <span id="qualSpinner" class="spinner-border spinner-border-sm ms-2 d-none" aria-hidden="true"></span>
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
  const form = document.getElementById('qualForm');
  const btn = document.getElementById('qualSaveBtn');
  const btnText = document.getElementById('qualSaveText');
  const spinner = document.getElementById('qualSpinner');
  const alertPlaceholder = document.getElementById('alert-placeholder');

  function setLoading(on){
    if (on) { btn && btn.setAttribute('disabled','disabled'); btnText && (btnText.textContent='Saving...'); spinner && spinner.classList.remove('d-none'); }
    else { btn && btn.removeAttribute('disabled'); btnText && (btnText.textContent='Save & Continue'); spinner && spinner.classList.add('d-none'); }
  }

  function showAlert(type, html){
    if (!alertPlaceholder) return;
    alertPlaceholder.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${html}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
    alertPlaceholder.scrollIntoView({behavior:'smooth', block:'center'});
  }

  if (!form) return;

  form.addEventListener('submit', async function(e){
    e.preventDefault(); e.stopPropagation();
    alertPlaceholder && (alertPlaceholder.innerHTML='');
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
        const text = await res.text();
        console.error('Unexpected server response:', text);
        await Swal.fire({icon:'error', title:'Server error', text:'Unexpected server response. Check console.'});
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

      await Swal.fire({icon:'success', title:data.message||'Saved', timer:data.redirectDelay||900, showConfirmButton:false, timerProgressBar:true});
      if (data.redirect) window.location.href = data.redirect;
      else window.location.href = '<?= site_url('profile/academic/experience') ?>';

    } catch (err) {
      console.error(err);
      showAlert('danger', 'Network error. Check console.');
      setLoading(false);
    } finally {
      setTimeout(()=>setLoading(false),300);
    }
  });
});
</script>
<?= $this->endSection() ?>

