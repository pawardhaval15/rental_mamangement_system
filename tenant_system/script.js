document.addEventListener("DOMContentLoaded", function () {
    // Delete tenant
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            const id = this.dataset.id;
            if (confirm("Are you sure you want to delete this tenant?")) {
                fetch("manager.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ delete_id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Find and remove the row corresponding to the deleted tenant
                        const row = this.closest('tr');
                        row.remove();
                        alert("Tenant deleted successfully.");
                    } else {
                        alert("Error deleting tenant.");
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    alert("An error occurred while deleting the tenant.");
                });
            }
        });
    });
});
