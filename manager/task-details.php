<?php

declare(strict_types=1);

require_once __DIR__ . '/../common/auth.php';
requireRole(['Project Manager']);
$GLOBALS['__PAGE_UI'] = 'manager';
require __DIR__ . '/../common/pages/task-details.php';
