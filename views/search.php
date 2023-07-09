<?php
global $page_title;

$client_username = $_SESSION['username'];
$page_title = "Search";
?>

<!doctype html>
<html lang="en">

<head>
    <?php PhpComponents::header(); ?>
    <?php CssComponents::navbar(); ?>

  <style>
      .c-avatar {
          width: 3rem;
          height: 3rem;
          background-color: var(--bs-gray-300);
          border-radius: 50%;
          overflow: hidden;

          background-image: url("/assets/media/noavatar.svg");
          background-position: center;
          background-repeat: no-repeat;
          background-size: auto 100%;
      }

      .c-avatar img {
          width: 100%;
          height: auto;
      }

      .c-user:hover {
          cursor: pointer;
          background-color: var(--bs-gray-200);
      }

      .c-profile-button {
          min-width: 6.5em;
      }

      .c-profile-button.c-unfollow > span:before {
          content: "Following";
      }

      .c-profile-button.c-unfollow:hover {
          background-color: var(--bs-danger-bg-subtle);
          border-color: var(--bs-danger-border-subtle) !important;
      }

      .c-profile-button.c-unfollow:hover > span:before {
          color: var(--bs-danger);
          content: "Unfollow";
      }
  </style>
</head>

<body>

<div class="c-container container d-flex">
    <?php PhpComponents::navbar(); ?>

  <div class="flex-grow-1 border-end">
    <div class="input-group p-3 border-bottom sticky-top bg-light">
      <span class="input-group-text"><i class="fa-solid fa-fw fa-search"></i></span>
      <input type="text" class="form-control" placeholder="Search Profiles" id="search-input"
             aria-label="Search Profiles">
    </div>

    <div class="d-flex flex-column" id="search-result-container"></div>
  </div>
</div>

<?php PhpComponents::footer(); ?>

<script>
    const clientUsername = "<?= $client_username ?>";
    const searchInput = $("#search-input");
    const searchResultContainer = $("#search-result-container");

    let searchInputTimer;

    searchInput.keyup(function () {
        clearTimeout(searchInputTimer);

        const value = searchInput.val().trim();
        if (value.length === 0) return;
        if (value.length === 1 && value[0] === '@') return;

        searchInputTimer = setTimeout(requestResults, 500);
    });

    function requestResults() {
        $.get("/api/search", {
            query: searchInput.val().trim()
        }, function (data) {
            searchResultContainer.html("");

            for (let i = 0; i < data.length; i++) {
                const {username, display_name, avatar, bio, following_client, followed_by_client} = data[i];
                let followed = followed_by_client;

                // language=html
                const followsYouSpan = !following_client ? "" : `
                  <span class="badge text-secondary bg-body-secondary">Follows you</span>`;

                // language=html
                const followButtonsDiv = username === clientUsername ? "" : `
                  <div class="c-profile-buttons my-auto">
                    <button class="c-profile-button c-unfollow btn btn-light border border-dark-subtle fw-bold">
                      <span></span>
                    </button>
                    <button class="c-profile-button c-follow btn btn-dark fw-bold">Follow</button>
                  </div>`;

                // language=html
                searchResultContainer.append(`
                  <div class="c-user d-flex px-3 py-2 gap-3" id="user-${username}">
                    <div class="c-avatar flex-shrink-0 mb-auto">
                      <img src="/assets/media/avatar/${avatar}" alt="">
                    </div>
                    <div class="d-flex flex-column flex-grow-1">
                      <div class="d-flex">
                        <div class="flex-grow-1">
                          <a href="/profile/${username}"
                             class="link-body-emphasis link-underline link-underline-opacity-0 link-underline-opacity-100-hover fw-bold">
                            ${display_name}
                          </a>
                          <div>
                            <span class="font-monospace text-body-secondary">@${username}</span>
                            ${followsYouSpan}
                          </div>
                        </div>

                        ${followButtonsDiv}
                      </div>
                      <div>${bio ?? ''}</div>
                    </div>
                  </div>`);

                const userDiv = $(`#user-${username}`);
                const followButton = userDiv.find(".c-follow");
                const unfollowButton = userDiv.find(".c-unfollow");

                userDiv.click(function () {
                    if (hasTextSelected()) return;
                    window.location.href = `/profile/${username}`;
                });

                followButton.click(clickButton("follow"));
                unfollowButton.click(clickButton("unfollow"));

                function clickButton(type) {
                    return function (e) {
                        e.stopPropagation();

                        $.post("/api/follow", {
                            type,
                            follower: clientUsername,
                            following: username
                        }, function (data) {
                            if (data["follower_count"] !== undefined) {
                                followed = !followed;
                                toggleButtons();
                            }
                        });
                    }
                }

                function toggleButtons() {
                    if (followed) {
                        followButton.hide();
                        unfollowButton.show();
                    } else {
                        followButton.show();
                        unfollowButton.hide();
                    }
                }

                toggleButtons();
            }
        })
    }
</script>
</body>
</html>
