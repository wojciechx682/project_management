<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        json_error('Invalid request method', 405);
    }

    if (!isset($_POST["comment_id"], $_POST["content"]) ||
        $_POST["comment_id"] === '' ||
        $_POST["content"] === '') {
        json_error('All fields are required.');
    }

    $commentIdRaw = $_POST["comment_id"];
    $contentRaw = trim($_POST["content"]);
    $commentId = filter_var($commentIdRaw, FILTER_VALIDATE_INT);
    $content = htmlspecialchars($contentRaw);

    if ($commentId === false || $commentIdRaw != $commentId) {
        json_error('Invalid identifiers.');
    }

    if ($content !== $contentRaw || strlen($content) < 10 || strlen($content) > 255) {
        json_error('Comment must contain between 10 and 255 characters and no special characters.');
    }

    $updateSuccessful = query(
        "UPDATE comment SET content = ? WHERE id = ?",
        "updateComment",
        [$content, $commentId]
    );

    if (!$updateSuccessful) {
        json_error('Failed to update comment. Please try again.');
    }

    json_success([], 'Comment updated successfully');
