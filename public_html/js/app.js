function edit(id, table) 
{
    window.location.href = `view/edit/edit.php?type=${table}&id=${id}`;
}

function editEventListener(event)
{
    const link = event.target.closest("a");
    const id = link.dataset.id;
    const tableName = link.dataset.table;

    edit(id, tableName);
}

function sortByEventListener(event, value)
{
    if (value === "student")
    {
        window.location.href = `reports?type=students`;
    } else if (value === "event")
    {
        window.location.href = `reports?type=events`;
    }
}

document.addEventListener("DOMContentLoaded", () => 
{
    const editLinks = document.querySelectorAll(".edit-link");

    editLinks.forEach(link => 
    {
        link.addEventListener("click", async (event) => 
        {
            event.preventDefault();
            editEventListener(event);
        });
    });

    const sortBySelect = document.getElementById("sort-by");
    if (sortBySelect)    {
        sortBySelect.addEventListener("change", async (event) => 
        {
            sortByEventListener(event, sortBySelect.value);
        });
    }
});