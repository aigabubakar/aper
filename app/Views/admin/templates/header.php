<!DOCTYPE html>
<html>
<head>
    <title><?= esc($title ?? 'Admin Panel') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="<?= base_url('admin') ?>">Aper Admin</a>
    <div class="ml-auto">
        <a href="<?= base_url('admin/logout') ?>" class="btn btn-sm btn-danger">Logout</a>
    </div>
</nav>
<div class="container mt-4">
