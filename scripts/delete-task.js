function toggleDeleteTaskWindow(taskId) {
    const resultDiv = document.getElementById('result');

    fetch(`deleteTask.php?id=${taskId}`, {
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'Task deleted successfully!'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to delete task'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            resultDiv.innerHTML = '<span class="error">Failed to delete task</span>';
            setTimeout(() => window.location.reload(), 1500);
        });
}
