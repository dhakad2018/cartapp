<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
<div class="container">
    <a class="navbar-brand" href="<?= base_url() ?>">CartApp</a>
    <a href="<?= base_url('cart/cart') ?>" class="btn btn-warning">
        Cart (<span id="cartCount"><?= count($this->session->userdata('cart') ?? []) ?></span>)
    </a>
</div>
</nav>