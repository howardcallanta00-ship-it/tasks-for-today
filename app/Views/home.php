<?= $this->extend('layout') ?><?= $this->section('content') ?>
<section class="today-header">
    <div class="hero-copy"><p class="eyebrow"><?= esc($today) ?></p><h1>Make today<br><span>count.</span></h1><p class="lede">A clear view of what needs your attention, without the noise.</p></div>
    <div class="day-card" aria-label="Today’s completion progress"><div class="day-card-top"><span>Daily ledger</span><b><?= date('d') ?></b></div><div class="day-card-main"><strong><?= count($tasks) ? (int) round($completed / count($tasks) * 100) : 0 ?><sup>%</sup></strong><span><?= $completed ?> of <?= count($tasks) ?> tasks complete</span></div><div class="progress-track"><i style="width:<?= count($tasks) ? (int) round($completed / count($tasks) * 100) : 0 ?>%"></i></div></div>
</section>
<section class="task-section">
    <div class="section-heading"><h2>Today’s focus</h2><a href="/tasks">View complete list <span aria-hidden="true">→</span></a></div>
    <?php if ($tasks): ?><div class="task-stack"><?php foreach ($tasks as $index => $task): ?>
        <article class="task-row"><span class="task-index" aria-hidden="true"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span><span class="status-line status-<?= esc($task['status']) ?>"></span><div><h3><?= esc($task['title']) ?></h3><span class="status-label"><?= esc(ucwords(str_replace('_', ' ', $task['status']))) ?></span></div><?php if (session('user_id')): ?><a class="quiet-action" href="/tasks/<?= $task['id'] ?>/edit">Edit</a><?php endif ?></article>
    <?php endforeach ?></div><?php else: ?><div class="empty"><h2>The day is clear.</h2><p>No active tasks are scheduled for today.</p><a class="button" href="/tasks">Browse all tasks</a></div><?php endif ?>
</section>
<?= $this->endSection() ?>
