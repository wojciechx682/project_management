<?php

/*
 * common/handlers/*.php — logika API (CRUD)
 * Jedna implementacja na endpoint, używana z obu folderów:
Projekty: addNewProject, updateProject, deleteProject, getProjectData
Zespoły: addNewTeam, updateTeam, deleteTeam, getTeamData
Zadania: addNewTask, updateTask, deleteTask, getTaskData
Komentarze: addNewComment, editComment, deleteComment, getCommentData
getAvailableUsers.php — w jednym pliku: dla Admina lista PM-ów, dla PM — członkowie do dodania do zespołu (jak wcześniej osobno).
addNewTeam.php — dwie ścieżki: Admin wybiera PM z formularza, PM jest brany z sesji (jak wcześniej).
 */

declare(strict_types=1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $response = ["success" => false];

    if (isset($_POST["task_id"]) && !empty($_POST["task_id"]) &&
        isset($_POST["content"]) && !empty($_POST["content"])) {

        $taskId = filter_var($_POST["task_id"], FILTER_VALIDATE_INT);
        $content = htmlspecialchars(trim($_POST["content"]));

        if ($taskId === false || $taskId != $_POST["task_id"]) {
            $response["error"] = "Invalid task ID.";
            echo json_encode($response);
            exit();
        }

        if ($content !== trim($_POST["content"]) || strlen($content) < 10 || strlen($content) > 255) {
            $response["error"] = "Comment must contain between 10 and 255 characters and no special characters.";
            echo json_encode($response);
            exit();
        }

        $commentData = [$taskId, $_SESSION["id"], $content];

        $insertSuccessful = query(
            "INSERT INTO comment (id, task_id, user_id, content, created_at) VALUES (NULL, ?, ?, ?, NOW())",
            "addNewComment",
            $commentData
        );

        if ($insertSuccessful) {
            echo json_encode([
                "success" => true,
                "message" => "Comment added successfully",
                "comment" => [
                    "task_id" => $taskId,
                    "user_id" => $_SESSION["id"],
                    "content" => $content,
                    "created_at" => date("j F Y, H:i")
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to add comment. Please try again."]);
        }

    } else {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
    }

} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
