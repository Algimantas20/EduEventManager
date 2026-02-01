function deleteUser(id, table) 
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
