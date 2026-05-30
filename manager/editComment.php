<?php
    require_once "../start-session.php";
    require_role("Project Manager");

    header('Content-Type: application/json; charset=UTF-8');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $response = ["success" => false];

        if (isset($_POST["comment_id"], $_POST["content"]) &&
            $_POST["comment_id"] !== "" &&
            $_POST["content"] !== "") {

            $commentIdRaw = $_POST["comment_id"];
            //$taskIdRaw    = $_POST["task_id"];
            $contentRaw   = trim($_POST["content"]);

            $commentId = filter_var($commentIdRaw, FILTER_VALIDATE_INT);
            //$taskId    = filter_var($taskIdRaw, FILTER_VALIDATE_INT);
            $content   = htmlspecialchars($contentRaw);

            if ($commentId === false || /*$taskId === false ||*/ $commentIdRaw != $commentId /*|| $taskIdRaw != $taskId*/) {
                json_error("Invalid identifiers.");
            }

            if ($content !== $contentRaw || strlen($content) < 10 || strlen($content) > 255) {
                json_error("Comment must contain between 10 and 255 characters and no special characters.");
            }

            $updateSuccessful = query(
                "UPDATE comment SET content = ? WHERE id = ?",
                "updateComment",
                [$content, $commentId]
            );

            if ($updateSuccessful) {
                json_success([], "Comment updated successfully");
            } else {
                json_error("Failed to update comment. Please try again.");
            }
        } else {
            json_error("All fields are required.");
        }
    } else {
        json_error("Invalid request method.", 405);
    }
