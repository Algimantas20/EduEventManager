function deleteRecord(id, table) 
{
    if (!confirm("Delete record?")) return;

    fetch("api/api_delete.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "id=" + id + "&table=" + table
    }).then(r => r.text())
    .then(alert)
    .then(() => location.reload());
}

function updateRecord(id, table) {
    if (!confirm("Update record?")) return;

    const form = document.querySelector("form");
    const formData = new FormData(form);

    const data = new FormData();
    data.append("id", id);
    data.append("table", table);

    for (const [key, value] of formData.entries()) {
        data.append(`data[${key}]`, value);
    }

    fetch("api/api_update.php", {
        method: "POST",
        body: data
    })
    .then(r => r.text())
    .then(alert)
    .then(() => location.reload());
}



function edit(id, table) {
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "edit.php";

    let inputId = document.createElement("input");
    inputId.type = "hidden";
    inputId.name = "id";
    inputId.value = id;

    let inputTable = document.createElement("input");
    inputTable.type = "hidden";
    inputTable.name = "table";
    inputTable.value = table;

    form.appendChild(inputId);
    form.appendChild(inputTable);

    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
