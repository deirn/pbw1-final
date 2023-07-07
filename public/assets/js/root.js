// bootstrap tooltip
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
[...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

function parseMysqlDateTime(dateTime) {
    // Y-m-d H:i:s
    const split = dateTime.split(/[- :]/);
    return new Date(Date.UTC(split[0], split[1] - 1, split[2], split[3], split[4], split[5]));
}


