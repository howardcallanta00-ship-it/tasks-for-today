<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A focused daily task management system.">
    <title><?= esc($title ?? 'Tasks for Today') ?> | Tasks for Today</title>
    <link rel="stylesheet" href="<?= base_url('assets/app.css') ?>">
</head>
<body>
<a class="skip-link" href="#content">Skip to content</a>
<header class="site-header">
    <nav class="nav shell" aria-label="Primary navigation">
        <a class="brand" href="/"><span class="brand-mark" aria-hidden="true">T</span><span>Tasks for Today</span></a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="nav-links">Menu</button>
        <div class="nav-links" id="nav-links">
            <?php foreach (['/' => 'Home', '/tasks' => 'Tasks', '/profile' => 'Profile', '/about' => 'About'] as $path => $label): ?>
                <a class="<?= url_is(trim($path, '/') ?: '/') ? 'active' : '' ?>" href="<?= $path ?>"><?= $label ?></a>
            <?php endforeach ?>
            <?php if (session('user_id')): ?>
                <a class="new-link" href="/tasks/new">New Task</a><span class="signed-in">@<?= esc(session('username')) ?></span>
                <form action="/logout" method="post"><?= csrf_field() ?><button class="link-button" type="submit">Logout</button></form>
            <?php else: ?><a class="login-link" href="/login">Login</a><?php endif ?>
        </div>
    </nav>
</header>
<main id="content" class="shell page">
    <?php foreach (['success', 'warning', 'error'] as $type): ?><?php if (session()->getFlashdata($type)): ?><div class="alert alert-<?= $type ?>" role="status"><?= esc(session()->getFlashdata($type)) ?></div><?php endif ?><?php endforeach ?>
    <div class="page-content"><?= $this->renderSection('content') ?></div>
</main>
<footer class="footer shell"><span>Developed by Howard Mac Callanta · Technical Assessment Project</span><a href="/health">System status</a></footer>
<div class="page-loader" aria-hidden="true">
    <div class="loader-shell">
        <div class="loader-heading skeleton"></div>
        <div class="loader-subheading skeleton"></div>
        <div class="loader-list">
            <?php for ($i = 0; $i < 4; $i++): ?><div class="loader-row"><i class="skeleton"></i><span class="skeleton"></span><b class="skeleton"></b></div><?php endfor ?>
        </div>
    </div>
</div>
<script>
const menuButton = document.querySelector('.nav-toggle');
const navigation = document.querySelector('.nav-links');
menuButton?.addEventListener('click', () => {
    const open = menuButton.getAttribute('aria-expanded') === 'true';
    menuButton.setAttribute('aria-expanded', String(!open));
    navigation.classList.toggle('open');
});

document.querySelectorAll('form[data-confirm]').forEach((form) => form.addEventListener('submit', (event) => {
    if (!confirm(form.dataset.confirm)) event.preventDefault();
}));

document.querySelectorAll('a[href]').forEach((link) => link.addEventListener('click', (event) => {
    const url = new URL(link.href, window.location.href);
    const isInternalPage = url.origin === window.location.origin && url.pathname !== window.location.pathname && !url.pathname.startsWith('/health');
    if (!isInternalPage || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || link.target === '_blank') return;
    event.preventDefault();
    document.body.classList.add('is-navigating');
    document.querySelector('main')?.setAttribute('aria-busy', 'true');
    requestAnimationFrame(() => requestAnimationFrame(() => window.location.assign(url.href)));
}));

window.addEventListener('pageshow', () => {
    document.body.classList.remove('is-navigating');
    document.querySelector('main')?.removeAttribute('aria-busy');
});
</script>
</body></html>
