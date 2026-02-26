function createFormData(table, data)
{
    const formData = new FormData();
    formData.append("table", table);
    formData.append("created_at", new Date().toISOString());

    for (const key of Object.keys(data))
    {
        formData.append(key, data[key]);
    }

    validateData(table, data);

    return formData;
}

function validateData(table, data)
{
    if (table === "events") 
    {
        validateEventData(data);
    } else if (table === "students")
    {
        validateStudentData(data);
    }
}

function validateEventData(data)
{
    if (new Date(data.event_date) < new Date(data.created_at))
    {
        alert("Event date cannot be in the past.");
        throw new Error("Invalid event date");
    }
}

function validateStudentData(data)
{
    if (new Date(data.date_of_birth) > new Date(data.created_at))
    {
        alert("Date of birth cannot be in the future.");
        throw new Error("Invalid date of birth");
    }
}

function createRecord(table, data) 
{
    try
    {
        const formData = createFormData(table, data);

        console.log(formData)

        fetch("../../api/api_create.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(result => { alert(result.message); })
        .catch(error => {
            alert("Error creating record:", error);
        });
    } 
    catch (error) 
    {
        console.error("Error creating record:", error);
    }
}

document.addEventListener("DOMContentLoaded", () =>
{
    const form = document.getElementById("AddForm");

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