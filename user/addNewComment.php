<?php
    require_once "../start-session.php";
    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Team Member") {
        $_SESSION["invalid_credentials"] = '<span class="error">Invalid role assigned</span>';
        header("Location: ../index.php");
        exit();
    }

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
                $response["error"] = "Invalid task ID.";
                echo json_encode($response);
                exit();
            }

            if ($content !== trim($_POST["content"]) || strlen($content) < 10 || strlen($content) > 255) {
                $response["error"] = "Comment must contain between 10 and 255 characters and no special characters.";
                echo json_encode($response);
                exit();
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
