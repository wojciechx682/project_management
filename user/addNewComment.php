<?php
    require_once "../start-session.php";
    require_role("Team Member");

    header('Content-Type: application/json; charset=UTF-8');

    // ========================
    //  Sprawdzenie metody żądania
    // ========================
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $response = ["success" => false];

        // ========================
        //  Sprawdzenie wymaganych pól
        // ========================
        if (isset($_POST["task_id"]) && !empty($_POST["task_id"]) &&
            isset($_POST["content"]) && !empty($_POST["content"])) {

            // ========================
            //  Pobranie i walidacja danych
            // ========================
            $taskId = filter_var($_POST["task_id"], FILTER_VALIDATE_INT);
            $content = htmlspecialchars(trim($_POST["content"]));

            if ($taskId === false || $taskId != $_POST["task_id"]) {
                json_error("Invalid task ID.");
            }

            if ($content !== trim($_POST["content"]) || strlen($content) < 10 || strlen($content) > 255) {
                json_error("Comment must contain between 10 and 255 characters and no special characters.");
            }

            // ========================
            //  Wstawienie komentarza
            // ========================
            $commentData = [$taskId, $_SESSION["id"], $content];

            $insertSuccessful = query(
                "INSERT INTO comment (id, task_id, user_id, content, created_at) VALUES (NULL, ?, ?, ?, NOW())",
                "addNewComment",
                $commentData
            );

            // ========================
            //  Odpowiedź JSON
            // ========================
            if ($insertSuccessful) {
                json_success([
                    "comment" => [
                        "task_id" => $taskId,
                        "user_id" => $_SESSION["id"],
                        "content" => $content,
                        "created_at" => date("j F Y, H:i"),
                    ],
                ], "Comment added successfully");
            } else {
                json_error("Failed to add comment. Please try again.");
            }

        } else {
            json_error("All fields are required.");
        }

    } else {
        json_error("Invalid request method.", 405);
    }
