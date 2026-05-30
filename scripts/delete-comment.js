function toggleDeleteCommentWindow(commentId) {
    fetch(`deleteComment.php?id=${commentId}`, {
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('result');
            if (data.success) {
                // Remove comment from DOM using his ID
                document.querySelectorAll('.main-comment').forEach(comment => {
                    if (comment.querySelector('.comment-id').textContent.trim() === commentId.toString()) {
                        comment.remove();
                    }
                });

                /*document.querySelectorAll('.main-comment').forEach(comment => {
                    const id = comment.getAttribute('data-comment-id');
                    if (id === String(commentId)) {
                        comment.remove();
                    }
                });*/

                // Usuń nagłówek jeśli nie ma już zadań
               /* const remainingTasks = document.querySelectorAll('.task-content');
                const taskHeader = document.querySelector('.task:not(.task-content)');
                if (remainingTasks.length === 0 && taskHeader) {
                    taskHeader.remove();
                }*/
                resultDiv.innerHTML = '<span class="success">Comment deleted successfully!</span>';

                // Odśwież stronę, aby pokazać zmiany
                /*setTimeout(() => {
                    window.location.reload();
                }, 1500);*/

            } else {
                resultDiv.innerHTML = `<span class="error">${data.message}</span>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('result').innerHTML = '<span class="error">Failed to delete comment</span>';
        });
}