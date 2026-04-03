function toggleDeleteTaskWindow(taskId) {
    fetch(`deleteTask.php?id=${taskId}`, {
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('result');
            if (data.success) {
                // Remove task from DOM
                document.querySelectorAll('.task-content').forEach(task => {
                    if (task.querySelector('.task-id').textContent.trim() === taskId.toString()) {
                        task.remove();
                    }
                });
                // Usuń nagłówek jeśli nie ma już zadań
                const remainingTasks = document.querySelectorAll('.task-content');
                const taskHeader = document.querySelector('.task:not(.task-content)');
                if (remainingTasks.length === 0 && taskHeader) {
                    taskHeader.remove();
                }
                resultDiv.innerHTML = '<span class="success">Task deleted successfully!</span>';

                // Odśwież stronę, aby pokazać zmiany
                setTimeout(() => {
                    window.location.reload();
                }, 1500);

            } else {
                resultDiv.innerHTML = `<span class="error">${data.message}</span>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('result').innerHTML = '<span class="error">Failed to delete task</span>';
        });
}