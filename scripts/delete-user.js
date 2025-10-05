function toggleDeleteUserWindow(userId) {
    fetch(`deleteUser.php?id=${userId}`, {
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(data => {
            const resultDiv = document.getElementById('result');
            if (data.success) {
                // Usuń usera z DOM
                document.querySelectorAll('.team-member.team-member-content').forEach(member => {
                    if (member.querySelector('.team-member-id').textContent.trim() === userId.toString()) {
                        member.remove();
                    }
                });

                // Usuń nagłówek, jeśli nie ma już członków
                const remainingUsers = document.querySelectorAll('.team-member.team-member-content');
                const userHeader = document.querySelector('.team-member:not(.team-member-content)');
                if (remainingUsers.length === 0 && userHeader) {
                    userHeader.remove();
                }

                resultDiv.innerHTML = '<span class="success">User removed from team successfully!</span>';

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
            document.getElementById('result').innerHTML = '<span class="error">Failed to remove user</span>';
        });
}
