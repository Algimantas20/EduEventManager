document.addEventListener("DOMContentLoaded", () => {

    const updateForm = document.getElementById("UpdateForm");

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

    async function updateRecord(id, table, row, form) {
        if (!confirm("Update record?")) return;

        try {
            const formData = new FormData(form);
            const data = new FormData();
            data.append("id", id);
            data.append("table", table);

        for (const [key, value] of formData.entries()) {
            data.append(key, value);
        }

            const response = await fetch("../../api/api_update.php", { method: "POST", body: data });
            const result = await response.text();
            alert(result);

            if (row) {
                for (const [key, value] of formData.entries()) {
                    const cell = row.querySelector(`td[data-field="${key}"]`);
                    if (cell) cell.textContent = value;
                }
            }
        } catch (err) {
            console.error("JS ERROR:", err);
            alert("Error updating record");
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

    if (updateForm) {
        updateForm.addEventListener("submit", async (event) => {
            event.preventDefault();
            console.log("Form submit detected");

            const id = updateForm.querySelector('[name="id"]').value;
            const table = updateForm.querySelector('[name="table"]').value;
            const row = document.querySelector(`tr[data-id="${id}"]`); // optional

            console.log(`ID: ${id}, Table: ${table}`);

            await updateRecord(id, table, row, updateForm);
        });
    }

});
