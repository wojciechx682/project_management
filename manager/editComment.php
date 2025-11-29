<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Project Manager") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

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
                $response["message"] = "Invalid identifiers.";
                echo json_encode($response);
                exit();
            }

            if ($content !== $contentRaw || strlen($content) < 10 || strlen($content) > 255) {
                $response["message"] = "Comment must contain between 10 and 255 characters and no special characters.";
                echo json_encode($response);
                exit();
            }

            $updateSuccessful = query(
                "UPDATE comment SET content = ? WHERE id = ?",
                "updateComment",
                [$content, $commentId]
            );

            if ($updateSuccessful) {
                $response["success"] = true;
                $response["message"] = "Comment updated successfully";
            } else {
                $response["message"] = "Failed to update comment. Please try again.";
            }
        } else {
            $response["message"] = "All fields are required.";
        }

        echo json_encode($response);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid request method."]);
    }
