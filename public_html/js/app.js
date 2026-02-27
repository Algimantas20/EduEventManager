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

function groupByEventListener(event, value)
{
    const params = new URLSearchParams(window.location.search);
    params.set("group-by", value);

    window.location.search = params.toString();
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

    const reportSortBySelect = document.getElementById("sort-by");
    if (reportSortBySelect)
    {
        reportSortBySelect.addEventListener("change", async (event) => 
        {
            sortByEventListener(event, reportSortBySelect.value);
        });
    }

    const group_by = document.getElementById("group-by");
    if (group_by)
    {
        group_by.addEventListener("change", (event) => {
            groupByEventListener(event, group_by.value);
        });
    }

});