<?= $this->extend('layout') ?><?= $this->section('content') ?>
<?php $errors = session('errors') ?? []; ?>
<div class="login-layout"><section><p class="eyebrow">Private workspace</p><h1>Return to<br>your focus.</h1><p>Sign in to create, refine, and archive tasks. Public task views remain open to everyone.</p></section>
<form class="login-card" action="/login" method="post"><?= csrf_field() ?><h2>Sign in</h2><p>Use your project account.</p>
<div class="field"><label for="username">Username</label><input id="username" name="username" autocomplete="username" value="<?= old('username') ?>" required><?php if (isset($errors['username'])): ?><p class="field-error"><?= esc($errors['username']) ?></p><?php endif ?></div>
<div class="field"><label for="password">Password</label><input type="password" id="password" name="password" autocomplete="current-password" required><?php if (isset($errors['password'])): ?><p class="field-error"><?= esc($errors['password']) ?></p><?php endif ?></div>
<?php if (session()->getFlashdata('error')): ?><div class="inline-error" role="alert"><?= esc(session()->getFlashdata('error')) ?></div><?php endif ?><button class="button full" type="submit">Sign in</button></form></div>
<?= $this->endSection() ?>
