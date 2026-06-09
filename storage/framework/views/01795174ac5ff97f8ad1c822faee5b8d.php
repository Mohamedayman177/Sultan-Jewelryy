<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'لوحة التحكم'); ?> — <?php echo e(config('app.name')); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f7f4ee;
            --card: #fdfbf7;
            --gold: #b8922e;
            --gold-dark: #8a6d22;
            --text: #1f1c18;
            --muted: #5c5349;
            --border: #e4dac8;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Rubik, system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }
        a { color: var(--gold-dark); }
        .admin-nav {
            background: linear-gradient(135deg, #2a231c 0%, #1a1612 100%);
            color: #fdfbf7;
            padding: 0.85rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .admin-nav a { color: #f0e6d4; text-decoration: none; font-weight: 600; }
        .admin-nav a:hover { color: #fff; }
        .admin-nav__brand { font-size: 1.05rem; }
        .admin-nav__actions { display: flex; align-items: center; gap: 1rem; }
        .admin-main { padding: 1.5rem; max-width: 1200px; margin: 0 auto; }
        .admin-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 6px 24px rgba(30, 24, 16, 0.06);
        }
        .admin-h1 { margin: 0 0 1rem; font-size: 1.35rem; }
        .btn-logout {
            background: rgba(201, 162, 39, 0.25);
            border: 1px solid rgba(201, 162, 39, 0.45);
            color: #fff;
            padding: 0.4rem 0.9rem;
            border-radius: 8px;
            cursor: pointer;
            font-family: inherit;
            font-weight: 600;
        }
        .btn-logout:hover { background: rgba(201, 162, 39, 0.4); }
        table.admin-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        .admin-table th, .admin-table td {
            padding: 0.65rem 0.5rem;
            text-align: right;
            border-bottom: 1px solid var(--border);
        }
        .admin-table th { color: var(--muted); font-weight: 600; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.02em; }
        .admin-table tr:hover td { background: rgba(201, 162, 39, 0.06); }
        .muted { color: var(--muted); font-size: 0.85rem; }
        .pagination {
            display: flex;
            flex-wrap: wrap;
            list-style: none;
            padding: 0;
            gap: 0.35rem;
            justify-content: center;
            margin-top: 1.25rem;
        }
        .pagination .page-item .page-link {
            display: block;
            padding: 0.4rem 0.65rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            text-decoration: none;
            color: var(--text);
            background: #fff;
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #d4b04a 0%, #b8922e 100%);
            border-color: transparent;
            color: #fff;
        }
        .pagination .page-item.disabled .page-link {
            opacity: 0.45;
            pointer-events: none;
        }
        .admin-flash {
            margin: 0 1.5rem;
            max-width: 1200px;
            margin-inline: auto;
            padding: 0 1.5rem;
            margin-top: 1rem;
        }
        .admin-flash__inner {
            padding: 0.65rem 1rem;
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 600;
        }
        .admin-flash--ok .admin-flash__inner {
            background: rgba(46, 125, 50, 0.12);
            border: 1px solid rgba(46, 125, 50, 0.35);
            color: #1b5e20;
        }
        .admin-flash--err .admin-flash__inner {
            background: rgba(196, 68, 68, 0.09);
            border: 1px solid rgba(196, 68, 68, 0.35);
            color: #7a1f1f;
        }
        .admin-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center; }
        .btn-admin {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }
        .btn-admin--primary {
            background: linear-gradient(135deg, #d4b04a 0%, #b8922e 100%);
            color: #fff;
        }
        .btn-admin--muted {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--text);
        }
        .btn-admin--danger {
            background: rgba(196, 68, 68, 0.12);
            border: 1px solid rgba(196, 68, 68, 0.45);
            color: #7a1f1f;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <?php if (! empty(trim($__env->yieldContent('nav')))): ?>
        <?php echo $__env->yieldContent('nav'); ?>
    <?php else: ?>
        <header class="admin-nav">
            <span class="admin-nav__brand"><?php echo e(config('app.name')); ?> — لوحة التحكم</span>
            <div class="admin-nav__actions">
                <a href="<?php echo e(route('admin.customers.index')); ?>">العملاء المسجّلون</a>
                <a href="<?php echo e(route('admin.payment-links.index')); ?>">روابط الدفع</a>
                <a href="<?php echo e(route('admin.services.index')); ?>">الخدمات</a>
                <a href="<?php echo e(route('home')); ?>" target="_blank" rel="noopener noreferrer">الموقع</a>
                <form action="<?php echo e(route('admin.logout')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-logout">تسجيل الخروج</button>
                </form>
            </div>
        </header>
    <?php endif; ?>

    <?php if(session('flash_ok')): ?>
        <div class="admin-flash admin-flash--ok"><div class="admin-flash__inner"><?php echo e(session('flash_ok')); ?></div></div>
    <?php endif; ?>
    <?php if(session('flash_error')): ?>
        <div class="admin-flash admin-flash--err"><div class="admin-flash__inner"><?php echo e(session('flash_error')); ?></div></div>
    <?php endif; ?>

    <main class="admin-main">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Sultan-Jewelryy\resources\views/layouts/admin.blade.php ENDPATH**/ ?>