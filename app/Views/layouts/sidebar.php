<?php
$userRole = session()->get('role');
$userCategory = session()->get('category');
?>



<?php
// app/Views/layouts/sidebar.php

$session = session();

// Prefer a $user passed to the view, else fall back to session keys
$viewUser = $user ?? $session->get('user') ?? null;
$role     = $viewUser['role'] ?? $session->get('role') ?? 'staff';
$category = $viewUser['category'] ?? $session->get('category') ?? 'non_academic';

// current path for active link detection (trim leading/trailing slashes)
$uri = service('uri');
$currentPath = trim($uri->getPath(), '/');

// helper to detect if a target url (site_url path) is active
function isActivePath(string $target, string $currentPath): bool {
    $targetPath = trim(parse_url(site_url($target), PHP_URL_PATH), '/');
    if ($targetPath === '') return $currentPath === '';
    // exact or prefix match so section links also highlight
    return $currentPath === $targetPath || strpos($currentPath, rtrim($targetPath, '/') . '/') === 0;
}
?>

<!-- /Sidebar -->
<div class="col-lg-3 theiaStickySidebar">
  <div class="settings-sidebar mb-lg-0">
    <div>
      <h6 class="mb-3">Main Menu</h6>

      <ul class="mb-3 pb-1">
        <!-- Dashboard (available to all) -->
        <li class="<?= isActivePath('/dashboard', $currentPath) ? 'active' : '' ?>">
          <a href="<?= site_url('/dashboard') ?>" class="d-inline-flex align-items-center <?= isActivePath('/dashboard', $currentPath) ? 'active' : '' ?>">
            <i class="isax isax-grid-35 me-2"></i>Dashboard
          </a>
        </li>

        <!-- My Profile - visible to staff and most roles -->
        <?php if (in_array($role, ['staff','hod','dean','admin'])): ?>

        <li class="<?= isActivePath('profile/print-summary', $currentPath) || isActivePath('profile', $currentPath) ? 'active' : '' ?>">
          <a href="<?= site_url('profile/print-summary') ?>" class="d-inline-flex align-items-center <?= isActivePath('profile/print-summary', $currentPath) || isActivePath('profile', $currentPath) ? 'active' : '' ?>">
            <i class="isax isax-ticket5 me-2"></i>My Summary
          </a>
        </li>
        <?php endif; ?>

        <!-- Courses (example only for academic category) -->
        <?php if ($category === 'academic' && in_array($role, ['staff','hod','dean'])): ?>
        <li class="<?= isActivePath('courses', $currentPath) ? 'active' : '' ?>">
          <a href="<?= site_url('courses') ?>" class="d-inline-flex align-items-center <?= isActivePath('courses', $currentPath) ? 'active' : '' ?>">
            <i class="isax isax-teacher5 me-2"></i>Courses
          </a>
        </li>
        <?php endif; ?>

        <!-- Announcements (visible to all logged in users) -->
        <!-- <li class="<?= isActivePath('announcements', $currentPath) ? 'active' : '' ?>">
          <a href="<?= site_url('announcements') ?>" class="d-inline-flex align-items-center <?= isActivePath('announcements', $currentPath) ? 'active' : '' ?>">
            <i class="isax isax-volume-high5 me-2"></i>Announcements
          </a>
        </li> -->

        <!-- Additional role-specific links -->
        <?php if ($role === 'hod'): ?>
          <li class="<?= isActivePath('hod/evaluate', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('hod/evaluate') ?>" class="d-inline-flex align-items-center <?= isActivePath('hod/evaluate', $currentPath) ? 'active' : '' ?>">
              <i class="fa-solid fa-check-to-slot me-2"></i>Evaluate Staff
            </a>
          </li>
          <li class="<?= isActivePath('hod/reports', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('hod/reports') ?>" class="d-inline-flex align-items-center <?= isActivePath('hod/reports', $currentPath) ? 'active' : '' ?>">
              <i class="fa-solid fa-chart-line me-2"></i>Reports
            </a>
          </li>
        <?php endif; ?>

        <?php if ($role === 'dean'): ?>
          <li class="<?= isActivePath('dean/overview', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('dean/overview') ?>" class="d-inline-flex align-items-center <?= isActivePath('dean/overview', $currentPath) ? 'active' : '' ?>">
              <i class="fa-solid fa-building-columns me-2"></i>Faculty Overview
            </a>
          </li>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
          <li class="<?= isActivePath('admin/users', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('admin/users') ?>" class="d-inline-flex align-items-center <?= isActivePath('admin/users', $currentPath) ? 'active' : '' ?>">
              <i class="fa-solid fa-users-cog me-2"></i>Manage Users
            </a>
          </li>
          
          <li class="<?= isActivePath('admin/menus', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('admin/menus') ?>" class="d-inline-flex align-items-center <?= isActivePath('admin/menus', $currentPath) ? 'active' : '' ?>">
              <i class="isax isax-setting me-2"></i>Menus & Settings
            </a>
          </li>
          <?php else: ?>
      <!-- Category-specific menus for staff -->
      <?php if ($userCategory === 'academic'): ?>
               <li class="<?= isActivePath('profile/academic/personal', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('profile/academic/personal') ?>" class="d-inline-flex align-items-center <?= isActivePath('profile/academic/personal', $currentPath) ? 'active' : '' ?>">
              <i class="isax isax-security-user me-2"></i>Profile
            </a>
        </li>
        <li class="<?= isActivePath('profile/academic/employment', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('profile/academic/employment') ?>" class="d-inline-flex align-items-center <?= isActivePath('profile/academic/employment', $currentPath) ? 'active' : '' ?>">
              <i class="isax isax-medal-star5 me-2"></i>Employment
            </a>
        </li>
        <li class="<?= isActivePath('profile/academic/experience', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('profile/academic/experience') ?>" class="d-inline-flex align-items-center <?= isActivePath('profile/academic/experience', $currentPath) ? 'active' : '' ?>">
              <i class="isax isax-note-215 me-2"></i>Experience
            </a>
        </li>

        <?php elseif ($userCategory === 'senior_non_academic'): ?>
              <li class="<?= isActivePath('profile/senior/personal', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('profile/senior/personal') ?>" class="d-inline-flex align-items-center <?= isActivePath('profile/senior/personal', $currentPath) ? 'active' : '' ?>">
              <i class="isax isax-security-user me-2"></i>Profile
            </a>
        </li>
        <li class="<?= isActivePath('profile/senior/employment', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('profile/senior/employment') ?>" class="d-inline-flex align-items-center <?= isActivePath('profile/senior/employment', $currentPath) ? 'active' : '' ?>">
              <i class="isax isax-medal-star5 me-2"></i>Employment
            </a>
        </li>
        <li class="<?= isActivePath('profile/senior/experience', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('profile/senior/experience') ?>" class="d-inline-flex align-items-center <?= isActivePath('profile/senior/experience', $currentPath) ? 'active' : '' ?>">
              <i class="isax isax-note-215 me-2"></i>Experience
            </a>
        </li>

      <?php elseif ($userCategory === 'junior_non_academic'): ?>
        <li class="<?= isActivePath('profile/junior/personal', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('profile/junior/personal') ?>" class="d-inline-flex align-items-center <?= isActivePath('profile/junior/personal', $currentPath) ? 'active' : '' ?>">
              <i class="isax isax-security-user me-2"></i>Profile
            </a>
        </li>
        <li class="<?= isActivePath('profile/junior/employment', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('profile/junior/employment') ?>" class="d-inline-flex align-items-center <?= isActivePath('profile/junior/employment', $currentPath) ? 'active' : '' ?>">
              <i class="isax isax-medal-star5 me-2"></i>Employment
            </a>
        </li>
        <li class="<?= isActivePath('profile/junior/experience', $currentPath) ? 'active' : '' ?>">
            <a href="<?= site_url('profile/junior/experience') ?>" class="d-inline-flex align-items-center <?= isActivePath('profile/junior/experience', $currentPath) ? 'active' : '' ?>">
              <i class="isax isax-note-215 me-2"></i>Experience
            </a>
        </li>
        

      <?php else: ?>
        <li><a href="<?= base_url('profile') ?>">My Profile</a></li>
      <?php endif; ?>

    <?php endif; ?>

      </ul>

      <hr>

      <ul>
        <li>
          <a href="<?= site_url('logout') ?>" class="d-inline-flex align-items-center text-danger">
            <i class="isax isax-logout5 me-2"></i>Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- /Sidebar (end) -->


  
