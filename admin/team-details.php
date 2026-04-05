<?php

declare(strict_types=1);

require_once __DIR__ . '/../common/auth.php';
requireRole(['Admin']);
$GLOBALS['__PAGE_UI'] = 'admin';
require __DIR__ . '/../common/pages/team-details.php';
