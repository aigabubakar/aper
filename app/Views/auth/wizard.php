<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Complete Profile Wizard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mx-auto" style="max-width:1000px;">
  <div class="card-body">
    <h4 class="card-title">Complete Your Profile</h4>
    <p class="text-muted mb-3">Category: <strong><?= esc($category ?? 'N/A') ?></strong></p>

    <?= view('partials/flash') ?>

    <div id="wizard">
      <!-- Tabs -->
      <ul class="nav nav-tabs mb-3" id="wizardTabs" role="tablist">
        <?php $i = 0; foreach ($stepsConfig as $stepNo => $cfg): $i++; ?>
          <li class="nav-item" role="presentation">
            <a class="nav-link <?= $i === 1 ? 'active' : '' ?>" data-step="<?= $stepNo ?>" href="#" role="tab">
              <?= esc($i) ?>. <?= esc($cfg['title'] ?? ('Step '.$i)) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

      <div id="alert-placeholder"></div>

      <form id="wizardForm" method="post" enctype="multipart/form-data" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="step" id="stepInput" value="1">

        <?php
        // Helper to render an input for a field name (unchanged)
        $renderField = function(string $field) use ($user) {
            $val = $user[$field] ?? null;
            switch ($field) {
                case 'phone':
                    return '<label class="form-label">Phone</label>
                      <input name="phone" class="form-control" value="'.esc(old('phone',$val)).'">';
                case 'dob':
                    return '<label class="form-label">Date of Birth</label>
                      <input type="date" name="dob" class="form-control" value="'.esc(old('dob',$val)).'">';
                case 'gender':
                    $sel = old('gender',$val);
                    return '<label class="form-label">Gender</label>
                      <select name="gender" class="form-select">
                        <option value="">-- Select --</option>
                        <option value="male" '.($sel=='male'?'selected':'').'>Male</option>
                        <option value="female" '.($sel=='female'?'selected':'').'>Female</option>
                        <option value="other" '.($sel=='other'?'selected':'').'>Other</option>
                      </select>';
                case 'department':
                    return '<label class="form-label">Department</label>
                      <input name="department" class="form-control" value="'.esc(old('department',$val)).'">';
                case 'designation':
                    return '<label class="form-label">Designation</label>
                      <input name="designation" class="form-control" value="'.esc(old('designation',$val)).'">';
                case 'category':
                    $sel = old('category',$val);
                    return '<label class="form-label">Category</label>
                      <select name="category" class="form-select">
                        <option value="">-- Select --</option>
                        <option value="academic" '.($sel=='academic'?'selected':'').'>Academic</option>
                        <option value="non_academic" '.($sel=='non_academic'?'selected':'').'>Non-Academic</option>
                        <option value="junior_non_academic" '.($sel=='junior_non_academic'?'selected':'').'>Junior Non-Academic</option>
                      </select>';
                case 'period_from':
                    return '<label class="form-label">Period From (Year)</label>
                      <input type="number" name="period_from" min="1900" max="'.(date('Y')+1).'" class="form-control" value="'.esc(old('period_from',$val)).'">';
                case 'period_to':
                    return '<label class="form-label">Period To (Year)</label>
                      <input type="number" name="period_to" min="1900" max="'.(date('Y')+1).'" class="form-control" value="'.esc(old('period_to',$val)).'">';
                case 'academic_rank':
                    return '<label class="form-label">Academic Rank</label>
                      <input name="academic_rank" class="form-control" value="'.esc(old('academic_rank',$val)).'">';
                case 'courses_taught':
                    return '<label class="form-label">Courses Taught (comma-separated)</label>
                      <input name="courses_taught" class="form-control" value="'.esc(old('courses_taught',$val)).'">';
                case 'qualifications':
                    return '<label class="form-label">Qualifications</label>
                      <textarea name="qualifications" class="form-control" rows="4">'.esc(old('qualifications',$val)).'</textarea>';
                case 'emergency_contact':
                    return '<label class="form-label">Emergency Contact</label>
                      <input name="emergency_contact" class="form-control" value="'.esc(old('emergency_contact',$val)).'">';
                case 'avatar':
                    $html = '<label class="form-label">Avatar (optional, max 2MB)</label>
                      <input type="file" name="avatar" accept="image/*" class="form-control">';
                    if (! empty($user['avatar'])) {
                        $html .= '<div class="mt-2"><img src="'.esc(base_url($user['avatar'])).'" alt="avatar" style="height:60px;border-radius:6px"><div class="form-text">Current avatar</div></div>';
                    }
                    return $html;
                default:
                    // generic text input fallback
                    return '<label class="form-label">'.ucfirst(str_replace('_',' ',$field)).'</label>
                      <input name="'.esc($field).'" class="form-control" value="'.esc(old($field,$val)).'">';
            }
        };
        ?>

        <?php $panelIndex = 0; foreach ($stepsConfig as $stepNo => $cfg): $panelIndex++; ?>
          <div class="wizard-step <?= $panelIndex !== 1 ? 'd-none' : '' ?>" data-step="<?= esc($stepNo) ?>">
            <?php
              $fields = $cfg['fields'] ?? [];
              if (! empty($cfg['title'])) {
                  echo '<h5 class="mb-3">'.esc($cfg['title']).'</h5>';
              }
            ?>

            <div class="row">
              <?php foreach ($fields as $f): ?>
                <div class="mb-3 col-md-<?= in_array($f,['address','qualifications','courses_taught','avatar']) ? '12' : '6' ?>">
                  <?= $renderField($f) ?>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="panel-alert-placeholder mb-2"></div>
          </div>
        <?php endforeach; ?>

        <div class="d-flex justify-content-between mt-3">
          <button type="button" id="prevBtn" class="btn btn-outline-secondary d-none">Previous</button>

          <div>
            <button type="button" id="nextBtn" class="btn btn-primary">Next</button>
            <button type="submit" id="submitBtn" class="btn btn-success d-none">Finish</button>
            <span id="wizardSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
/*
  CLIENT-SIDE VALIDATION + PER-PANEL SUBMIT
  - clientRules: map of per-step rules (injected by controller if you want)
  - clientValidate(panel): returns object of errors if any
  - saveCurrentStep: creates FormData from current panel only (but appends CSRF from the full form)
  - next/prev/submit handlers use the above
*/

/* Export server rules to JS safely (controller passed $stepsConfig) */
const clientRules = <?= json_encode(array_map(function($s){ return $s['rules'] ?? []; }, $stepsConfig), JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT) ?>;

function parseRuleParts(ruleStr) {
  return ruleStr.split('|').map(s => s.trim()).filter(Boolean);
}
function parseBracket(name) {
  const m = name.match(/^[a-z_]+\[([^\]]+)\]$/i);
  return m ? m[1] : null;
}
function isEmptyValue(val) {
  return val === null || val === undefined || (typeof val === 'string' && val.trim() === '');
}

/* Client-side validator: implements a useful subset of CI rules */
function clientValidate(panel) {
  const step = panel.getAttribute('data-step');
  const rulesForStep = clientRules[step] || clientRules[String(step)] || {};
  const errors = {}; // field => [messages]

  for (const field in rulesForStep) {
    const ruleStr = rulesForStep[field];
    const parts = parseRuleParts(ruleStr);

    // find element inside this panel (if absent, skip)
    const el = panel.querySelector(`[name="${field}"]`);
    if (!el) continue;

    let val = null;
    if (el.type === 'file') val = el.files && el.files.length ? el.files[0] : null;
    else val = el.value;

    const fieldErrors = [];

    // If permit_empty and empty -> skip all other checks
    if (parts.includes('permit_empty') && isEmptyValue(val)) {
      // skip
    } else {
      for (const p of parts) {
        if (p === 'permit_empty') continue;

        if (p === 'required') {
          if (isEmptyValue(val)) fieldErrors.push('This field is required.');
        } else if (p.startsWith('max_length[')) {
          const n = parseInt(parseBracket(p), 10);
          if (!isNaN(n) && String(val || '').length > n) fieldErrors.push(`Maximum length is ${n} characters.`);
        } else if (p.startsWith('min_length[')) {
          const n = parseInt(parseBracket(p), 10);
          if (!isNaN(n) && String(val || '').length < n) fieldErrors.push(`Minimum length is ${n} characters.`);
        } else if (p === 'integer') {
          if (!isEmptyValue(val) && !/^-?\d+$/.test(String(val))) fieldErrors.push('Must be an integer.');
        } else if (p === 'valid_date') {
          if (!isEmptyValue(val) && !/^\d{4}-\d{2}-\d{2}$/.test(String(val))) {
            fieldErrors.push('Invalid date format (YYYY-MM-DD).');
          } else if (!isEmptyValue(val)) {
            const dt = new Date(String(val));
            if (Number.isNaN(dt.getTime())) fieldErrors.push('Invalid date.');
          }
        } else if (p.startsWith('in_list[')) {
          const list = parseBracket(p);
          if (list) {
            const opts = list.split(',').map(s => s.trim());
            if (!isEmptyValue(val) && opts.indexOf(String(val)) === -1) fieldErrors.push('Invalid value selected.');
          }
        } else if (p.startsWith('max_size[')) {
          // parse max size in KB (CI uses KB)
          const inner = parseBracket(p);
          let maxKB = null;
          if (inner) {
            const parts = inner.split(',').map(x => x.trim());
            const last = parts[parts.length - 1];
            const n = parseInt(last, 10);
            if (!isNaN(n)) maxKB = n;
          }
          if (maxKB !== null && el.type === 'file' && val) {
            const sizeKB = Math.ceil(val.size / 1024);
            if (sizeKB > maxKB) fieldErrors.push(`File too large. Maximum ${maxKB} KB.`);
          }
        } else if (p.startsWith('mime_in[')) {
          const inner = parseBracket(p);
          if (inner && el.type === 'file' && val) {
            const parts = inner.split(',').map(x => x.trim());
            const mimeCandidates = parts.filter(x => x.includes('/'));
            if (mimeCandidates.length) {
              const fileType = val.type || '';
              if (!mimeCandidates.includes(fileType)) fieldErrors.push('Invalid file type.');
            }
          }
        }
      }
    }

    if (fieldErrors.length) errors[field] = fieldErrors;
  }

  return errors; // {} if ok
}

/* DOM logic */
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('wizardForm');
  const steps = Array.from(document.querySelectorAll('.wizard-step'));
  const tabs = Array.from(document.querySelectorAll('#wizardTabs .nav-link'));
  const nextBtn = document.getElementById('nextBtn');
  const prevBtn = document.getElementById('prevBtn');
  const submitBtn = document.getElementById('submitBtn');
  const spinner = document.getElementById('wizardSpinner');
  const alertPlaceholder = document.getElementById('alert-placeholder');
  let currentIndex = 0;

  function showAlert(type, html) {
    alertPlaceholder.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${html}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
    alertPlaceholder.scrollIntoView({behavior:'smooth', block:'center'});
  }

  function clearPanelAlerts(panel) {
    const ph = panel.querySelector('.panel-alert-placeholder');
    if (ph) ph.innerHTML = '';
  }

  function setLoading(on) {
    if (on) spinner.classList.remove('d-none'); else spinner.classList.add('d-none');
    nextBtn.disabled = on;
    prevBtn.disabled = on;
    submitBtn.disabled = on;
  }

  function showStep(index) {
    steps.forEach((el, i) => el.classList.toggle('d-none', i !== index));
    tabs.forEach((t, i) => t.classList.toggle('active', i === index));
    prevBtn.classList.toggle('d-none', index === 0);
    nextBtn.classList.toggle('d-none', index === steps.length - 1);
    submitBtn.classList.toggle('d-none', index !== steps.length - 1);
    document.getElementById('stepInput').value = steps[index].getAttribute('data-step');
  }

  // Build FormData from current panel only, but append CSRF token from the full form
  function buildPanelFormData(panel) {
    const fd = new FormData();
    // include fields only in this panel
    Array.from(panel.querySelectorAll('input, select, textarea')).forEach(el => {
      if (!el.name) return;
      if (el.type === 'file') {
        if (el.files && el.files.length) fd.append(el.name, el.files[0]);
      } else {
        fd.append(el.name, el.value);
      }
    });
    // Ensure CSRF token is included: find hidden input inside the full form (csrf_field)
    const csrfInput = form.querySelector('input[type="hidden"][name^="<?= csrf_token() ?>"]') || form.querySelector('input[type="hidden"]:not([name="step"])');
    // Fallback: include all hidden inputs from form except step (safer)
    if (!csrfInput) {
      Array.from(form.querySelectorAll('input[type="hidden"]')).forEach(h => {
        if (h.name && h.name !== 'step') fd.append(h.name, h.value);
      });
    } else {
      if (csrfInput.name) fd.append(csrfInput.name, csrfInput.value);
    }

    // include step value explicitly (server needs it)
    fd.set('step', panel.getAttribute('data-step') || (currentIndex + 1));
    return fd;
  }

  // show errors on panel (field-level aggregated)
  function showPanelErrors(panel, errorsObj) {
    const ph = panel.querySelector('.panel-alert-placeholder');
    if (!ph) return;
    const items = [];
    for (const [field, msgs] of Object.entries(errorsObj)) {
      const label = field.replace(/_/g,' ');
      items.push(`<strong>${label}</strong>: ${msgs.join(' ')}`);
    }
    ph.innerHTML = `<div class="alert alert-danger"><ul class="mb-0"><li>${items.join('</li><li>')}</li></ul></div>`;
    ph.scrollIntoView({behavior:'smooth', block:'center'});
  }

  // Save current panel to server
  async function saveCurrentPanel() {
    const panel = steps[currentIndex];
    clearPanelAlerts(panel);

    // 1) client-side validate using clientRules
    const clientErrors = clientValidate(panel);
    if (Object.keys(clientErrors).length) {
      showPanelErrors(panel, clientErrors);
      return { ok:false, clientValidation:true };
    }

    const fd = buildPanelFormData(panel);
    setLoading(true);

    try {
      const res = await fetch('<?= site_url('profile/wizard/save') ?>', {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      const ct = res.headers.get('content-type') || '';
      if (!ct.includes('application/json')) {
        const txt = await res.text();
        showAlert('danger', 'Server returned non-JSON response:<pre style="white-space:pre-wrap;">' + txt.substring(0,2000) + '</pre>');
        setLoading(false);
        return { ok:false };
      }
      const json = await res.json();

      if (!res.ok) {
        // server-side validation errors or message
        if (json.errors) {
          showPanelErrors(panel, json.errors);
        } else {
          showAlert('danger', json.message || 'Server error');
        }
        setLoading(false);
        return { ok:false, json };
      }

      // success
      // show transient success message in top alert area
      showAlert('success', json.message || 'Saved');

      setLoading(false);
      return { ok:true, json };
    } catch (err) {
      console.error(err);
      showAlert('danger', 'Network error. Try again.');
      setLoading(false);
      return { ok:false };
    }
  }

  // Next button handler: validate+save panel, then advance per server response
  nextBtn.addEventListener('click', async function() {
    const result = await saveCurrentPanel();
    if (!result.ok) return;

    const json = result.json || {};
    // if server tells to show final (optionally), handle it
    if (json.showFinal) {
      // hide panels and show final view (we assume server provided redirect or front view will handle)
      steps.forEach(s => s.classList.add('d-none'));
      // show success alert is already done
      if (json.redirect) {
        setTimeout(() => window.location.href = json.redirect, json.redirectDelay || 3000);
      }
      return;
    }

    // advance using server-supplied nextStep (if provided) else next index
    if (json.nextStep) {
      const nextIdx = steps.findIndex(p => p.getAttribute('data-step') == json.nextStep);
      currentIndex = nextIdx !== -1 ? nextIdx : Math.min(currentIndex + 1, steps.length - 1);
    } else {
      currentIndex = Math.min(currentIndex + 1, steps.length - 1);
    }
    showStep(currentIndex);
  });

  // Previous handler
  prevBtn.addEventListener('click', function() {
    currentIndex = Math.max(currentIndex - 1, 0);
    showStep(currentIndex);
  });

  // Form submit (Finish)
  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const result = await saveCurrentPanel();
    if (!result.ok) {
      // if validation error, server errors, just show them
      return;
    }
    const json = result.json || {};
    // final-step behavior: if showFinal true, show success and redirect if present
    if (json.showFinal) {
      if (json.redirect) {
        setTimeout(() => window.location.href = json.redirect, json.redirectDelay || 3000);
      } else {
        // fallback redirect
        window.location.href = '<?= site_url('dashboard') ?>';
      }
    } else if (json.redirect) {
      window.location.href = json.redirect;
    } else {
      // fallback - redirect to dashboard
      window.location.href = '<?= site_url('dashboard') ?>';
    }
  });

  // Tab clicks: allow moving backwards only (you already intended this)
  tabs.forEach((t, i) => t.addEventListener('click', function(e) {
    e.preventDefault();
    if (i <= currentIndex) {
      currentIndex = i;
      showStep(currentIndex);
    }
  }));

  // initial display
  showStep(0);
});
</script>
<?= $this->endSection() ?>
