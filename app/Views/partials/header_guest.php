<?php
// header for non-authenticated visitors
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="<?= base_url() ?>">APER</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?= site_url('check-email') ?>">Apply</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('about') ?>">About</a></li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="<?= site_url('login') ?>">Login</a></li>
      </ul>
    </div>
  </div>
</nav>
