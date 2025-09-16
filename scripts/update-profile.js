// Obsługa formularza edycji profilu
document.getElementById("profile-form").addEventListener("submit", function(event) {

    event.preventDefault();

    const resultDiv = document.getElementById("result");
    const formData = new FormData(this);

    // Walidacja danych
    const firstName = formData.get("firstName").trim();
    const lastName = formData.get("lastName").trim();
    const email = formData.get("email").trim();

    // Walidacja DOMPurify
    const cleanFirstName = DOMPurify.sanitize(firstName);
    const cleanLastName = DOMPurify.sanitize(lastName);
    const cleanEmail = DOMPurify.sanitize(email);

    // Nadpisujemy wartości w formData (po oczyszczeniu)
    formData.set("firstName", cleanFirstName);
    formData.set("lastName", cleanLastName);
    formData.set("email", cleanEmail);

    // Wysłanie danych do serwera
    fetch("../update-profile.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = "<span class='success'>Profile has been updated successfully</span>";

                // Jeśli backend zwrócił updated_at → aktualizujemy w widoku
                if (data.updated_at) {
                    const updatedField = document.getElementById("updated");
                    if (updatedField) {
                        updatedField.value = data.updated_at;
                    }
                }

                // Możesz też odświeżyć stronę po kilku sekundach
                // setTimeout(() => window.location.reload(), 1500);

            } else {
                resultDiv.innerHTML = `<span class='error'>${data.message || 'Failed to update profile'}</span>`;
            }
        })
        .catch(error => {
            console.error("Error:", error);
            resultDiv.innerHTML = "<span class='error'>An error occurred. Please try again</span>";
        });
});
