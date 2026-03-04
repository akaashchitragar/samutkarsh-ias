<?php
/**
 * Authentication helpers for the admin panel.
 * All protected pages must call require_login() before any output.
 */

define('PANEL_SESSION_TIMEOUT', 3600); // 1 hour inactivity

function require_login(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check session exists
    if (empty($_SESSION['admin_id'])) {
        header('Location: ' . panel_url('login.php?reason=session'));
        exit;
    }

    // Check inactivity timeout
    if (!empty($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > PANEL_SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        header('Location: ' . panel_url('login.php?reason=timeout'));
        exit;
    }

    // Refresh activity timestamp
    $_SESSION['last_activity'] = time();
}

function current_user(): array
{
    return [
        'id'    => $_SESSION['admin_id']    ?? 0,
        'email' => $_SESSION['admin_email'] ?? '',
        'name'  => $_SESSION['admin_name']  ?? 'Admin',
        'role'  => $_SESSION['admin_role']  ?? 'viewer',
    ];
}

function is_superadmin(): bool
{
    return ($_SESSION['admin_role'] ?? '') === 'superadmin';
}

function can_edit(): bool
{
    return in_array($_SESSION['admin_role'] ?? '', ['admin', 'superadmin'], true);
}

function can_delete(): bool
{
    return ($_SESSION['admin_role'] ?? '') === 'superadmin';
}

/**
 * Build a URL relative to the panel root.
 * Works whether panel is at /panel/ or directly at /.
 */
function panel_url(string $path = ''): string
{
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $base   = rtrim(dirname($script), '/\\');
    return $base . '/' . ltrim($path, '/');
}
