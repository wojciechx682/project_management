<?php
    require_once "../start-session.php";
    require_role("Team Member");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('Invalid request method', 405);
    }

    if (!isset($_POST["id"]) || empty($_POST["id"]) ||
        !isset($_POST["status"]) || empty($_POST["status"])) {
        json_error('Task ID and status are required');
    }

    $id = filter_var($_POST["id"], FILTER_VALIDATE_INT);
    $status = $_POST["status"];
    $validStatuses = ["not_started", "in_progress", "completed", "cancelled"];

    if (!in_array($status, $validStatuses)) {
        json_error('Invalid status');
    }

    switch ($status) {
        case "not_started": $statusFormatted = "Not Started"; break;
        case "in_progress": $statusFormatted = "In Progress"; break;
        case "completed": $statusFormatted = "Completed"; break;
        case "cancelled": $statusFormatted = "Cancelled"; break;
    }

    $updateSuccessful = query(
        "UPDATE task SET status=?, updated_at = NOW() WHERE id=?",
        "updateTaskStatus",
        [$statusFormatted, $id]
    );

    if (!$updateSuccessful) {
        json_error('Failed to update task status');
    }

    json_success([], 'Task status updated successfully');
