function toggleDeleteCommentWindow(commentId) {
    const resultDiv = document.getElementById('result');

    fetch(`deleteComment.php?id=${commentId}`, {
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'Comment deleted successfully!'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to delete comment'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            resultDiv.innerHTML = '<span class="error">Failed to delete comment</span>';
            setTimeout(() => window.location.reload(), 1500);
        });
}
