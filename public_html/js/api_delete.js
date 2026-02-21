async function deleteRecord(id, table, row) 
{
    if (!confirm("Delete record?")) return;

    try 
    {
        const response = await fetch("api/api_delete.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id=${id}&table=${table}`
        });

        const result = await response.text();
        alert(result);

        window.location.reload();
    } 
    catch (err) 
    {
        console.error("ERROR:", err);
        alert("Error deleting record");
    }
}

async function deleteEventListener(event) 
{
    const link = event.target.closest("a");
    const id = link.dataset.id;
    const tableName = link.dataset.table;
    const row = link.closest("tr");

    deleteRecord(id, tableName, row);
}


document.addEventListener("DOMContentLoaded", () => 
{
    const deleteLinks = document.querySelectorAll(".delete-link");

    deleteLinks.forEach(link => 
    {
        link.addEventListener("click", async (event) => 
        {
            event.preventDefault();
            deleteEventListener(event);
        });
    });
});