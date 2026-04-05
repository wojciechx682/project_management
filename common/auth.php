<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/start-session.php';

/**
 * @param string[] $allowedRoles Dokładne wartości $_SESSION["role"] (np. "Admin", "Project Manager").
 */
function requireRole(array $allowedRoles): void
{
    // Funkcja requireRole(array $allowedRoles) — jedna wspólna kontrola dostępu zamiast powielanego if ($_SESSION["role"] !== "...").
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles, true)) {
        $_SESSION['invalid_credentials'] = '<span class="error">Invalid role assigned</span>';
        header('Location: ../index.php');
        exit();
    }
}
