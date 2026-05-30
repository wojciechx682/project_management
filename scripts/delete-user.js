function toggleDeleteUserWindow(userId) {
    const resultDiv = document.getElementById('result');

    fetch(`deleteUser.php?id=${userId}`, {
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = `<span class='success'>${data.message || 'User removed from team successfully!'}</span>`;
            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to remove user'}</span>`;
            }
            setTimeout(() => window.location.reload(), 1500);
        })
        .catch(error => {
            console.error('Error:', error);
            resultDiv.innerHTML = '<span class="error">Failed to remove user</span>';
            setTimeout(() => window.location.reload(), 1500);
        });
}
