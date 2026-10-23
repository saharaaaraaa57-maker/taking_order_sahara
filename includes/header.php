<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$pageTitle = isset($judul) ? $judul : BRAND_NAME . ' — Cafe & More';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle); ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:   '#E0708C',
                        secondary: '#F3A8BB',
                        accent:    '#623933',
                        soft:      '#FFF5F7',
                        surface:   '#FFFFFF',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="icon" type="image/png" href="<?= asset('logo-brand.png'); ?>">
    <link href="<?= asset('css/style.css'); ?>" rel="stylesheet">
</head>
<body class="bg-soft text-accent font-sans">