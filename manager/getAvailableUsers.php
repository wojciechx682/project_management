<?php

require_once __DIR__ . '/../common/auth.php';
requireRole(['Project Manager']);
require __DIR__ . '/../common/handlers/getAvailableUsers.php';
