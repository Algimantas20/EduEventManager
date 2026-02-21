document.addEventListener("DOMContentLoaded", () => {

    async function deleteRecord(id, table, row) {
        if (!confirm("Delete record?")) return;

        try {
            const response = await fetch("api/api_delete.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id=${id}&table=${table}`
            });

            const result = await response.text();
            alert(result);

            if (row) row.remove();
        } catch (err) {
            console.error("JS ERROR:", err);
            alert("Error deleting record");
        }
    }

    function edit(id, table) {
        window.location.href = `view/edit/edit.php?type=${table}&id=${id}`;
    }

    document.addEventListener("click", (event) => {
        const btn = event.target.closest("a");
        if (!btn) return;

        const id = btn.dataset.id;
        const tableName = btn.dataset.table;
        const row = btn.closest("tr");

        console.log(`Class: ${btn.className}, id: ${id}, table: ${tableName}`);

        if (btn.classList.contains("delete-link")) {
            deleteRecord(id, tableName, row);
        } else if (btn.classList.contains("edit-link")) {
            edit(id, tableName);
        }
    });
});
