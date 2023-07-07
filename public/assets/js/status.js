const monthName = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
const statusContainer = $("#status-container");

let earliestStatusId = 0;

function likeButtonClick(statusId) {
    $.post("/api/like", {
        status_id: statusId
    }, function (json) {
        const data = JSON.parse(json);

        const {
            liked,
            new_like_count
        } = data;

        if (new_like_count !== undefined) {
            const likeCounter = $(`#status-${statusId} #like-count`);
            likeCounter.html(new_like_count);

            const heart = $(`#status-${statusId} #heart`);
            if (liked) heart
                .removeClass("fa-regular")
                .addClass("fa-solid");
            else heart
                .removeClass("fa-solid")
                .addClass("fa-regular")
        }
    });
}

function formatStatusDate(date) {
    return `${date.getHours()}:${date.getMinutes()} - ${monthName[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
}

function createStatusDiv({username, status_id, status_content, created_at, display_name, like_count, liked_by_client}) {
    const heartStyle = liked_by_client ? "fa-solid" : "fa-regular";
    const date = parseMysqlDateTime(created_at);
    const dateString = formatStatusDate(date);

    // language=html
    return `
      <div class="c-status p-3 d-flex gap-3 border-bottom" id="status-${status_id}">
        <div class="c-status-avatar flex-shrink-0 mb-auto"></div>
        <div class="d-flex flex-column gap-2 flex-grow-1">
          <div>
            <div class="d-flex gap-2">
              <a href="/profile/${username}"
                 class="link-body-emphasis link-underline link-underline-opacity-0 link-underline-opacity-100-hover fw-bold">
                ${display_name}
              </a>
              <div class="font-monospace text-body-secondary">@${username}</div>
              <div class="text-body-tertiary flex-grow-1">${dateString}</div>
              <div class="font-monospace text-body-tertiary">#${status_id}</div>
            </div>

            <div class="text-break">${status_content}</div>
          </div>
          <div class="c-status-buttons d-flex gap-3">
            <div>
              <button class="c-comment btn"><i class="fa-regular fa-fw fa-comment"></i></button>
            </div>
            <div>
              <button class="c-like btn">
                <i class="${heartStyle} fa-fw fa-heart" id="heart"></i> <span id="like-count">${like_count}</span>
              </button>
            </div>
          </div>
        </div>
      </div>`;
}

function setupStatusEventHandler(statusId) {
    const status = $(`#status-${statusId}`);

    status.click(function () {
        if (hasTextSelected()) return;

        window.location.href = `/status/${statusId}`;
    });

    status.find(".c-like").click(function () {
        likeButtonClick(statusId);
    });

    status.find("button").click(function (e) {
        e.stopPropagation();
    });
}

function statusResponseHandler(json) {
    const data = JSON.parse(json);
    console.log(data);

    for (let i = 0; i < data.length; i++) {
        const {status_id} = data[i];
        const statusDiv = createStatusDiv(data[i]);

        statusContainer.append(statusDiv);
        setupStatusEventHandler(status_id);

        earliestStatusId = status_id;
    }
}