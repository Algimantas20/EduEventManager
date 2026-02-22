function createRecordId()
{
    return Math.floor(1000 + Math.random() * 9000);
}

function createFormData(table, data)
{
    const formData = new FormData();
    formData.append("table", table);
    formData.append("created_at", new Date().toISOString());

    for (const key of Object.keys(data))
    {
        formData.append(key, data[key]);
    }
    return formData;
}

function assignRecordId()
{
    const participationField = document.getElementsByName("participation_id")[0];
    if (participationField)
    {
        participationField.value = createRecordId()
        return;
    }
    const studentField = document.getElementsByName("student_id")[0];
    const eventField = document.getElementsByName("event_id")[0];

    if (studentField) studentField.value = createRecordId();
    else if (eventField) eventField.value = createRecordId();
}

function createRecord(table, data) 
{
    try
    {
        const formData = createFormData(table, data);

        fetch("../../api/api_create.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(result => { alert(result);})
        .catch(error => {
            console.error("Error creating record:", error);
            alert("Error creating record");
        });
    } 
    catch (error) 
    {
        console.error("Error creating record:", error);
        alert("Error creating record");
    }
}

document.addEventListener("DOMContentLoaded", () =>
{
    const form = document.getElementById("AddForm");

    assignRecordId();

    if (!form) return;

    form.addEventListener("submit", (event) =>
    {
        if (!form.checkValidity())
        {
            form.reportValidity();
            event.preventDefault();
            return;
        }

        event.preventDefault();

        const table = form.dataset.table;
        const dataObject = Object.fromEntries(new FormData(form).entries());

        createRecord(table, dataObject);
    });
});