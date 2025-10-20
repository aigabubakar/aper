<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Congratulations<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container my-5">
  <div class="card mx-auto" style="max-width:800px;">
    <div class="card-body text-center">
      
         <div class="successful-form img-fluid"> <img src="<?= base_url('assets/images/gif/dashboard-8/successful.gif') ?>" alt="Congrats" style="max-width:180px" class="mb-3">
            <h2 class="mb-2">Congratulations, <?= esc($user['fullname'] ?? $user['email']) ?>!</h2>
               <p>Well done! You have successfully completed your <?= date('Y') ?> APER registration.</p>
        </div>
            <p class="text-muted mb-4">Thank you — your details have been saved.</p>

      <p>
        <a href="<?= site_url('dashboard') ?>" class="btn btn-primary me-2">Go to Dashboard</a>
        <a href="<?= site_url('profile/print-summary') ?>" class="btn btn-outline-secondary">Print Summary</a>
      </p>
    </div>
  </div>
</div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function(){
  // optional: small confetti or animation can be added here
});
</script>
<?= $this->endSection() ?>
