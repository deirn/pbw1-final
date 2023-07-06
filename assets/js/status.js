let earliestStatusId = 0;

function likeButtonClick(statusId) {
    $.post("/api/like.php", {
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

function statusHandler(json) {
    const data = JSON.parse(json);

    for (let i = 0; i < data.length; i++) {
        const {
            username,
            status_id,
            status_content,
            display_name,
            like_count,
            liked_by_client
        } = data[i];

        const heartStyle = liked_by_client ? "fa-solid" : "fa-regular"

        // language=html
        const statusDiv = `
          <div class="c-status p-3 d-flex gap-3 border-bottom" id="status-${status_id}">
            <div class="c-status-avatar flex-shrink-0 mb-auto"></div>
            <div class="d-flex flex-column gap-2">
              <div>
                <div class="d-flex gap-2">
                  <a href="/profile/${username}"
                     class="link-body-emphasis link-underline link-underline-opacity-0 link-underline-opacity-100-hover fw-bold">
                    ${display_name}
                  </a>
                  <div class="font-monospace text-body-secondary flex-grow-1">@${username}</div>
                  <div class="font-monospace text-body-tertiary">#${status_id}</div>
                </div>

                <div>${status_content}</div>
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
          </div>`

        $("#status-container").append(statusDiv);
        $(`#status-${status_id} .c-like`).click(function () {
            likeButtonClick(status_id);
        });

        earliestStatusId = status_id;
    }
}