<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Personal Details<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mx-auto" style="max-width:900px;">
  <div class="card-body">
    <h4>Personal Details</h4>
    <?= view('partials/flash') ?>

    <form id="personalForm" method="post" action="<?= site_url('profile/personal/save') ?>" novalidate>
      <?= csrf_field() ?>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Phone</label>
          <input name="phone" class="form-control" value="<?= esc(old('phone', $user['phone'] ?? '')) ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Date of Birth</label>
          <input type="date" name="dob" class="form-control" value="<?= esc(old('dob', $user['dob'] ?? '')) ?>">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select">
            <option value="">--</option>
            <option value="male" <?= (old('gender',$user['gender'] ?? '')=='male')?'selected':'' ?>>Male</option>
            <option value="female" <?= (old('gender',$user['gender'] ?? '')=='female')?'selected':'' ?>>Female</option>
            <option value="other" <?= (old('gender',$user['gender'] ?? '')=='other')?'selected':'' ?>>Other</option>
          </select>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?= site_url('profile/overview') ?>" class="btn btn-outline-secondary">Back to overview</a>
        <button class="btn btn-primary" id="savePersonalBtn">Save & Continue</button>
      </div>
    </form>
  </div>
</div>
</div>








<div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h5>Vertical Validation Wizard </h5>
                    <p class="f-m-light mt-1">
                       Fill up your true details and next proceed.</p>
                  </div>
                  <div class="card-body">
                    <div class="vertical-main-wizard">
                      <div class="row g-3">    
                        <div class="col-xxl-3 col-xl-4 col-12">
                          <div class="nav flex-column header-vertical-wizard" id="wizard-tab" role="tablist" aria-orientation="vertical"><a class="nav-link active" id="wizard-contact-tab" data-bs-toggle="pill" href="#wizard-contact" role="tab" aria-controls="wizard-contact" aria-selected="true"> 
                              <div class="vertical-wizard">
                                <div class="stroke-icon-wizard"><i class="fa-solid fa-user"></i></div>
                                <div class="vertical-wizard-content"> 
                                  <h6>Your Info</h6>
                                  <p>Add your details </p>
                                </div>
                              </div></a><a class="nav-link" id="wizard-cart-tab" data-bs-toggle="pill" href="#wizard-cart" role="tab" aria-controls="wizard-cart" aria-selected="false"> 
                              <div class="vertical-wizard">
                                <div class="stroke-icon-wizard"><i class="fa-solid fa-link"></i></div>
                                <div class="vertical-wizard-content"> 
                                  <h6>Cart Info</h6>
                                  <p>Add your a/c details</p>
                                </div>
                              </div></a><a class="nav-link" id="wizard-banking-tab" data-bs-toggle="pill" href="#wizard-banking" role="tab" aria-controls="wizard-banking" aria-selected="false"> 
                              <div class="vertical-wizard">
                                <div class="stroke-icon-wizard"><i class="fa-solid fa-user-group"></i></div>
                                <div class="vertical-wizard-content"> 
                                  <h6>Net Banking</h6>
                                  <p>Choose your bank</p>
                                </div>
                              </div></a></div>
                        </div>
                        <div class="col-xxl-9 col-xl-8 col-12">
                          <div class="tab-content" id="wizard-tabContent">
                            <div class="tab-pane fade show active" id="wizard-contact" role="tabpanel" aria-labelledby="wizard-contact-tab">
                              <form class="row g-3 needs-validation custom-input" novalidate="">
                                <div class="col-xxl-4 col-sm-6">
                                  <label class="form-label" for="validationCustom0-a">First Name<span class="txt-danger">*</span></label>
                                  <input class="form-control" id="validationCustom0-a" type="text" placeholder="Enter first name" required="">
                                  <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-xxl-4 col-sm-6">
                                  <label class="form-label" for="validationCustom-b">Last Name<span class="txt-danger">*</span></label>
                                  <input class="form-control" id="validationCustom-b" type="text" placeholder="Enter last name" required="">
                                  <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-xxl-4 col-sm-6">
                                  <label class="form-label" for="validationEmail-b">Email<span class="txt-danger">*</span></label>
                                  <input class="form-control" id="validationEmail-b" type="email" required="" placeholder="mofi@example.com">
                                  <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-xxl-5 col-sm-6">
                                  <label class="form-label" for="validationCustom04">State</label>
                                  <select class="form-select" id="validationCustom04" required="">
                                    <option selected="" disabled="" value="">Choose...</option>
                                    <option>USA </option>
                                    <option>U.K </option>
                                    <option>U.S</option>
                                  </select>
                                  <div class="invalid-feedback">Please select a valid state.</div>
                                </div>
                                <div class="col-xxl-3 col-sm-6">
                                  <label class="form-label" for="validationCustom05">Zip Code</label>
                                  <input class="form-control" id="validationCustom05" type="text" required="">
                                  <div class="invalid-feedback">Please provide a valid zip.</div>
                                </div>
                                <div class="col-xxl-4 col-sm-6">
                                  <label class="form-label" for="contactNumber">Contact Number</label>
                                  <input class="form-control" id="contactNumber" type="number" placeholder="Enter number" required="">
                                  <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-12">
                                  <div class="form-check">
                                    <input class="form-check-input" id="invalidCheck-n" type="checkbox" value="" required="">
                                    <label class="form-check-label" for="invalidCheck-n">Agree to terms and conditions</label>
                                  </div>
                                </div>
                                <div class="col-12 text-end"> 
                                  <button class="btn btn-primary">Next</button>
                                </div>
                              </form>
                            </div>
                            <div class="tab-pane fade" id="wizard-cart" role="tabpanel" aria-labelledby="wizard-cart-tab">
                              <form class="row g-3 needs-validation custom-input" novalidate="">
                                <div class="col-xxl-6">
                                  <div class="card-wrapper border rounded-3 checkbox-checked">
                                    <h6 class="sub-title">Select your payment method</h6>
                                    <div class="radio-form">
                                      <div class="form-check">
                                        <input class="form-check-input" id="flexRadioDefault1" type="radio" name="flexRadioDefault-a">
                                        <label class="form-check-label" for="flexRadioDefault1">Visa</label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" id="flexRadioDefault2" type="radio" name="flexRadioDefault-a" checked="">
                                        <label class="form-check-label" for="flexRadioDefault2">MasterCard</label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" id="flexRadioDefault3" type="radio" name="flexRadioDefault-a" checked="">
                                        <label class="form-check-label" for="flexRadioDefault3">Paypal</label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-xxl-6"> 
                                  <div class="row"> 
                                    <div class="col-12">
                                      <div class="input-group mb-3">
                                        <input class="form-control" type="text" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
                                        <button class="btn btn-outline-secondary" id="button-addon2" type="button">Submit</button>
                                      </div>
                                    </div>
                                    <div class="col-12"> 
                                      <div class="input-group"><span class="input-group-text" id="basic-addon1">@</span>
                                        <input class="form-control" type="text" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                  <label class="form-label" for="txtCardNumber1">Card Number</label>
                                  <input class="form-control" id="txtCardNumber1" type="number" required="" placeholder="xxxx xxxx xxxx xxxx">
                                  <div class="invalid-feedback">Please enter your valid number</div>
                                  <div class="valid-feedback">
                                     Looks's Good!</div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                  <label class="form-label" for="validationDates">Expiration(MM/YY)</label>
                                  <input class="form-control" id="validationDates" type="number" required="" placeholder="xx/xx">
                                  <div class="invalid-feedback">Please enter your valid expiration MM/YY</div>
                                  <div class="valid-feedback">
                                     Looks's Good!</div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                  <label class="form-label" for="cvvNumber-b">CVV</label>
                                  <input class="form-control" id="cvvNumber-b" type="number" required="">
                                  <div class="invalid-feedback">Please enter your valid CVV</div>
                                  <div class="valid-feedback">
                                     Looks's Good!</div>
                                </div>
                                <div class="col-md-12 col-sm-6">
                                  <label class="form-label" for="formFile2">Upload Documentation</label>
                                  <input class="form-control" id="formFile2" type="file" aria-label="file example" required="">
                                  <div class="invalid-feedback">Invalid form file selected</div>
                                </div>
                                <div class="col-12">
                                  <div class="form-check mb-0">
                                    <input class="form-check-input" id="invalidCheck-b" type="checkbox" value="" required="">
                                    <label class="form-check-label" for="invalidCheck-b">All the above information is correct</label>
                                    <div class="invalid-feedback">You must agree before submitting.</div>
                                  </div>
                                </div>
                                <div class="col-12 text-end"> 
                                  <button class="btn btn-primary">Previous</button>
                                  <button class="btn btn-primary">Next</button>
                                </div>
                              </form>
                            </div>
                            <div class="tab-pane fade custom-input" id="wizard-banking" role="tabpanel" aria-labelledby="wizard-banking-tab">
                              <form class="row g-3 needs-validation" novalidate="">
                                <div class="col-md-12"> 
                                  <div class="accordion dark-accordion" id="accordionExample-a">
                                    <div class="accordion-item">
                                      <h2 class="accordion-header">
                                        <button class="accordion-button collapsed accordion-light-primary txt-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-a" aria-expanded="true" aria-controls="collapseOne-a">NET BANKING<i class="svg-color" data-feather="chevron-down"></i></button>
                                      </h2>
                                      <div class="accordion-collapse collapse" id="collapseOne-a">
                                        <div class="accordion-body weight-title card-wrapper">
                                          <h6 class="sub-title f-14">SELECT YOUR BANK</h6>
                                          <div class="row choose-bank">
                                            <div class="col-sm-6">
                                              <div class="form-check radio radio-primary">
                                                <input class="form-check-input" id="flexRadioDefault-z" type="radio" name="flexRadioDefault-v">
                                                <label class="form-check-label" for="flexRadioDefault-z">Industrial & Commercial Bank</label>
                                              </div>
                                              <div class="form-check radio radio-primary">
                                                <input class="form-check-input" id="flexRadioDefault-y" type="radio" name="flexRadioDefault-v">
                                                <label class="form-check-label" for="flexRadioDefault-y">Agricultural Bank</label>
                                              </div>
                                              <div class="form-check radio radio-primary">
                                                <input class="form-check-input" id="flexRadioDefault-x" type="radio" name="flexRadioDefault-v" checked="">
                                                <label class="form-check-label" for="flexRadioDefault-x">JPMorgan Chase & Co.</label>
                                              </div>
                                            </div>
                                            <div class="col-sm-6"> 
                                              <div class="form-check radio radio-primary">
                                                <input class="form-check-input" id="flexRadioDefault-w" type="radio" name="flexRadioDefault-v">
                                                <label class="form-check-label" for="flexRadioDefault-w">Construction Bank Corp.</label>
                                              </div>
                                              <div class="form-check radio radio-primary">
                                                <input class="form-check-input" id="flexRadioDefault-v" type="radio" name="flexRadioDefault-v">
                                                <label class="form-check-label" for="flexRadioDefault-v">Bank of America</label>
                                              </div>
                                              <div class="form-check radio radio-primary">
                                                <input class="form-check-input" id="flexRadioDefault-u" type="radio" name="flexRadioDefault-v">
                                                <label class="form-check-label" for="flexRadioDefault-u">HDFC Bank</label>
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
<script>
document.getElementById('personalForm').addEventListener('submit', async function(e){
  e.preventDefault();
  const btn = document.getElementById('savePersonalBtn');
  btn.disabled = true;
  const fd = new FormData(this);
  try {
    const res = await fetch(this.action, { method:'POST', body:fd, credentials:'same-origin', headers:{ 'X-Requested-With':'XMLHttpRequest' }});
    const json = await res.json();
    if (!res.ok) {
      if (json.errors) {
        // show errors - simple
        alert(Object.values(json.errors).flat().join('\n'));
      } else alert(json.message || 'Server error');
      btn.disabled = false;
      return;
    }
    // success -> go to employment
    window.location.href = json.redirect || '<?= site_url('profile/employment') ?>';
  } catch (err) {
    console.error(err);
    alert('Network error');
    btn.disabled = false;
  }
});
</script>
<?= $this->endSection() ?>
