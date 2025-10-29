<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= $this->renderSection('title') ?: 'Admin' ?></title>
  <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.css') ?>">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="<?= site_url('admin') ?>">Admin</a>
    <div>
      <a class="text-white me-2" href="<?= site_url('/') ?>">Site</a>
      <a class="text-white" href="<?= site_url('admin/logout') ?>">Logout</a>
    </div>
  </div>
</nav>
<div class="container mt-4">
  <?= $this->renderSection('content') ?>
</div>
</body>
</html>
