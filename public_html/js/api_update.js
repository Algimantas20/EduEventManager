function createFormData(form, id, table)
{
    const formData = new FormData(form);
    const data = new FormData();

    data.append("id", id);
    data.append("table", table);

    for (const [key, value] of formData.entries()) 
    {
        data.append(key, value);
    }

    return data;
}

async function updateRecord(id, table, row, form) 
{
    if (!confirm("Update record?")) return;

    try {
        const data = createFormData(form, id, table);
        const response = await fetch("../../api/api_update.php", { method: "POST", body: data })
            .then(response => response.json())
            .then(result => { alert(result.message); })
            .catch(error => {
                console.error("Error updating record:", error);
            });
            
        if (row) 
        {
            for (const [key, value] of formData.entries()) 
            {
                const cell = row.querySelector(`td[data-field="${key}"]`);
                if (cell) cell.textContent = value;
            }
        }
    } 
    catch (err) 
    {
        console.error("JS ERROR:", err);
        alert("Error updating record");
    }
}

async function updateEventListener(event, updateForm)
{
    event.preventDefault();
    console.log("Form submit detected");

    const id = updateForm.querySelector('[name="id"]').value;
    const table = updateForm.querySelector('[name="table"]').value;
    const row = document.querySelector(`tr[data-id="${id}"]`);

    await updateRecord(id, table, row, updateForm);
}

document.addEventListener("DOMContentLoaded", () => 
{
    const updateForm = document.getElementById("UpdateForm");

    updateForm.addEventListener("submit", async (event) => await updateEventListener(event, updateForm));
});