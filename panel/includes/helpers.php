<?php
/**
 * General helpers for the admin panel.
 */

/**
 * Sanitize a string value (matching the main site's clean() pattern).
 */
function clean(string $val): string
{
    return htmlspecialchars(strip_tags(trim($val)), ENT_QUOTES, 'UTF-8');
}

/**
 * Return 'active' if the current page filename matches $page.
 */
function active_if(string $page): string
{
    $current = basename($_SERVER['PHP_SELF'] ?? '', '.php');
    return $current === $page ? 'active' : '';
}

/**
 * Compute pagination values.
 * Returns [total, per_page, current, total_pages, offset].
 */
function paginate(int $total, int $per_page, int $current_page): array
{
    $current_page = max(1, $current_page);
    $total_pages  = max(1, (int) ceil($total / $per_page));
    $current_page = min($current_page, $total_pages);
    $offset       = ($current_page - 1) * $per_page;

    return [
        'total'       => $total,
        'per_page'    => $per_page,
        'current'     => $current_page,
        'total_pages' => $total_pages,
        'offset'      => $offset,
    ];
}

/**
 * Render a colored status badge span.
 */
function status_badge(string $status): string
{
    $map = [
        'pending'      => 'pending',
        'confirmed'    => 'confirmed',
        'cancelled'    => 'cancelled',
        'active'       => 'active',
        'inactive'     => 'inactive',
        'maintenance'  => 'maintenance',
    ];
    $cls = $map[$status] ?? 'inactive';
    return '<span class="status-badge badge-' . $cls . '">' . htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8') . '</span>';
}

/**
 * Store a one-time flash message in session.
 */
function set_flash(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'msg' => $message];
}

/**
 * Retrieve and clear the flash message from session.
 * Returns null if none set.
 */
function get_flash(): ?array
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['flash'])) return null;
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}

/**
 * Render a Bootstrap pagination component.
 * $pager: array from paginate()
 * $base_url: URL string without &page=N (will append &page=N)
 */
function render_pagination(array $pager, string $base_url): string
{
    if ($pager['total_pages'] <= 1) return '';

    $html  = '<nav aria-label="Page navigation"><ul class="pagination pagination-sm mb-0">';

    // Previous
    if ($pager['current'] > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=' . ($pager['current'] - 1) . '">&laquo;</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">&laquo;</span></li>';
    }

    // Pages
    $start = max(1, $pager['current'] - 2);
    $end   = min($pager['total_pages'], $pager['current'] + 2);

    if ($start > 1) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=1">1</a></li>';
        if ($start > 2) $html .= '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
    }

    for ($i = $start; $i <= $end; $i++) {
        $active = $i === $pager['current'] ? ' active' : '';
        $html  .= '<li class="page-item' . $active . '"><a class="page-link" href="' . $base_url . '&page=' . $i . '">' . $i . '</a></li>';
    }

    if ($end < $pager['total_pages']) {
        if ($end < $pager['total_pages'] - 1) $html .= '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
        $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=' . $pager['total_pages'] . '">' . $pager['total_pages'] . '</a></li>';
    }

    // Next
    if ($pager['current'] < $pager['total_pages']) {
        $html .= '<li class="page-item"><a class="page-link" href="' . $base_url . '&page=' . ($pager['current'] + 1) . '">&raquo;</a></li>';
    } else {
        $html .= '<li class="page-item disabled"><span class="page-link">&raquo;</span></li>';
    }

    $html .= '</ul></nav>';
    return $html;
}

/**
 * Guard against CSV formula injection.
 * Prepends a tab to values starting with =, +, -, @.
 */
function csv_safe(string $val): string
{
    if (in_array($val[0] ?? '', ['=', '+', '-', '@'], true)) {
        return "\t" . $val;
    }
    return $val;
}

/**
 * Render star rating HTML.
 */
function star_rating(int $rating): string
{
    $html = '<span class="stars">';
    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= $rating ? '<i class="ri-star-fill"></i>' : '<i class="ri-star-line"></i>';
    }
    $html .= '</span>';
    return $html;
}

/**
 * Build a query string from current GET params, overriding specified keys.
 */
function build_query(array $overrides = [], array $exclude = []): string
{
    $params = array_merge($_GET, $overrides);
    foreach ($exclude as $key) unset($params[$key]);
    return http_build_query($params);
}
