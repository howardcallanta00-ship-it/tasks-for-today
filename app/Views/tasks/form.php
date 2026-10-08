<?= $this->extend('layout') ?><?= $this->section('content') ?>
<?php $errors = session('errors') ?? []; ?>
<div class="form-shell"><header><a class="back-link" href="/tasks">← Back to tasks</a><p class="eyebrow">Task details</p><h1><?= esc($title) ?></h1><p>Keep it specific enough to act on at a glance.</p></header>
<form class="task-form" action="<?= esc($action) ?>" method="post"><?= csrf_field() ?>
    <div class="field"><label for="title">Title</label><input id="title" name="title" maxlength="150" value="<?= old('title', $task['title'] ?? '') ?>" aria-describedby="title-help title-error" required><small id="title-help">Use a clear, action-oriented description.</small><?php if (isset($errors['title'])): ?><p class="field-error" id="title-error"><?= esc($errors['title']) ?></p><?php endif ?></div>
    <div class="form-grid"><div class="field"><label for="task_date">Task date</label><input type="date" id="task_date" name="task_date" value="<?= old('task_date', $task['task_date'] ?? date('Y-m-d')) ?>" required><?php if (isset($errors['task_date'])): ?><p class="field-error"><?= esc($errors['task_date']) ?></p><?php endif ?></div>
    <div class="field"><label for="status">Status</label><select id="status" name="status"><?php foreach (['pending' => 'Pending', 'in_progress' => 'In progress', 'completed' => 'Completed'] as $value => $label): ?><option value="<?= $value ?>" <?= old('status', $task['status'] ?? 'pending') === $value ? 'selected' : '' ?>><?= $label ?></option><?php endforeach ?></select><?php if (isset($errors['status'])): ?><p class="field-error"><?= esc($errors['status']) ?></p><?php endif ?></div></div>
    <div class="form-actions"><a href="/tasks">Cancel</a><button class="button" type="submit" onclick="this.disabled=true;this.form.submit()">Save task</button></div>
</form></div>
<?= $this->endSection() ?>
