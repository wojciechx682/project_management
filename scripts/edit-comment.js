function toggleEditCommentWindow(commentId) {

    const modal = document.getElementById("add-comment");
    const mainContainer = document.getElementById("main-container");
    const textarea = document.getElementById("comment-content");
    const commentIdInput = document.getElementById("comment-id");
    const resultDiv = document.getElementById("result");

    if (!modal || !textarea) return;

    if (commentIdInput) {
        commentIdInput.value = commentId;
    }

    modal.classList.toggle("hidden");

    if (!modal.classList.contains("hidden")) {
        if (mainContainer) mainContainer.classList.add("unreachable");
    } else {
        if (mainContainer) mainContainer.classList.remove("unreachable");
    }

    fetch(`getCommentData.php?id=${commentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data && data.data.comment) {
                textarea.value = data.data.comment.content || "";
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to load comment data'}</span>`;
                setTimeout(() => window.location.reload(), 1500);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>Failed to load comment data</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
}

function closeAddCommentWindow() {
    const modal = document.getElementById("add-comment");
    const mainContainer = document.getElementById("main-container");

    if (modal) modal.classList.add("hidden");
    if (mainContainer) mainContainer.classList.remove("unreachable");
}

document.addEventListener("keydown", function(event) {
    const modal = document.getElementById("add-comment");
    if (!modal || modal.classList.contains("hidden")) return;

    if (event.key === "Escape") {
        closeAddCommentWindow();
    }
});

document.getElementById("add-comment-form").addEventListener("submit", function (event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");

    const commentId = document.getElementById("comment-id").value.trim();
    const content = document.getElementById("comment-content").value.trim();

    const cleanCommentId = DOMPurify.sanitize(commentId);
    const cleanContent = DOMPurify.sanitize(content);

    const isValid = (
        cleanCommentId === commentId && commentId !== "" &&
        cleanContent === content && cleanContent.length >= 10 && cleanContent.length <= 255
    );

    if (!isValid) {
        resultDiv.innerHTML = "<span class='error'>Please provide a valid comment (10–255 characters)</span>";
        closeAddCommentWindow();
        setTimeout(() => window.location.reload(), 1500);
        return;
    }

    const formData = new FormData();
    formData.append("comment_id", cleanCommentId);
    formData.append("content", cleanContent);

    fetch("editComment.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            closeAddCommentWindow();
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'Comment updated successfully'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to update comment. Please try again.'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error("Error:", error);
            closeAddCommentWindow();
            resultDiv.innerHTML = "<span class='error'>An unexpected error occurred. Please try again.</span>";
            setTimeout(() => window.location.reload(), 1500);
        });
});
