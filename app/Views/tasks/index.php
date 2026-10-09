<?= $this->extend('layout') ?><?= $this->section('content') ?>
<header class="page-heading"><div><p class="eyebrow">Work queue</p><h1>All tasks</h1><p>Past, present, and upcoming work in one ordered view.</p></div><?php if (session('user_id')): ?><a class="button" href="/tasks/new">New task</a><?php endif ?></header>
<div class="summary-strip"><div><strong><?= count($tasks) ?></strong><span>Active</span></div><div><strong><?= $counts['in_progress'] ?></strong><span>In progress</span></div><div><strong><?= $counts['completed'] ?></strong><span>Completed</span></div></div>
<?php if ($tasks): ?><div class="task-list"><?php foreach ($tasks as $index => $task): $relation = $task['task_date'] < date('Y-m-d') ? 'Overdue' : ($task['task_date'] === date('Y-m-d') ? 'Today' : 'Upcoming'); ?>
<article class="task-item">
    <span class="task-index" aria-hidden="true"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
    <div class="date-block"><span><?= esc($relation) ?></span><strong><?= date('M j', strtotime($task['task_date'])) ?></strong></div>
    <div class="task-copy"><h2><?= esc($task['title']) ?></h2><span class="status-label status-text-<?= esc($task['status']) ?>"><?= esc(ucwords(str_replace('_', ' ', $task['status']))) ?></span></div>
    <?php if (session('user_id')): ?><div class="row-actions"><a href="/tasks/<?= $task['id'] ?>/edit">Edit</a><form action="/tasks/<?= $task['id'] ?>/archive" method="post" data-confirm="Archive this task? It will leave all public lists."><?= csrf_field() ?><button type="submit">Archive</button></form></div><?php endif ?>
</article><?php endforeach ?></div><?php else: ?><div class="empty"><h2>No active tasks</h2><p>When tasks are created, they will appear here in date order.</p><?php if (session('user_id')): ?><a class="button" href="/tasks/new">Create the first task</a><?php endif ?></div><?php endif ?>
<?= $this->endSection() ?>
