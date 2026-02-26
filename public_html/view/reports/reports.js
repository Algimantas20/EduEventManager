document.addEventListener("DOMContentLoaded", () => {
    const selectElement = document.getElementById("events_id");
    if (selectElement) {
        selectElement.addEventListener("change", async (event) => {
            const eventId = event.target.value;
            window.location.href = `reports?type=events&id=${eventId}`;
        });
    }

    const selectStudentElemet = document.getElementById("students_id")
    if (selectStudentElemet) {
        selectStudentElemet.addEventListener("change", async (event) => {
            const studentId = event.target.value;
            window.location.href = `reports?type=students&id=${studentId}`;
        });
    }
});