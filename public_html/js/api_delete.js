async function deleteRecord(id, table) 
{
    if (!confirm("Delete record?")) return;

    try 
    {
        const body = `id=${encodeURIComponent(id)}&table=${encodeURIComponent(table)}`;

        const response = await fetch("api/api_delete.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: body
        });

        if (!response.ok) 
        {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();

        alert(result.message);

        if (result.success) 
        {
            window.location.reload();
        }
    } 
    catch (error) 
    {
        console.error("Error deleting record:", error);
        alert("Error deleting record");
    }
}

document.addEventListener("DOMContentLoaded", () => 
{
    document.querySelectorAll(".delete-link").forEach(link => 
    {
        link.addEventListener("click", event => 
        {
            event.preventDefault();

            const id = link.dataset.id;
            const tableName = link.dataset.table;

            deleteRecord(id, tableName);
        });
    });
});