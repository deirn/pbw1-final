<?php

function active_class_mobile(string $title): string
{
    global $page_view;

    return $page_view == $title ? 'active' : '';
}

?>

<div class="c-nav-mobile sticky-bottom d-flex border-top p-3 bg-light">
  <ul class="nav nav-flush gap-3 flex-even">
    <li class="nav-item">
      <a href="/home" class="c-nav-icon nav-link active">
        <i class="active fa-solid fa-fw fa-bug fa-spin-pulse"></i>
      </a>
    </li>

    <li class="nav-item">
      <a href="/home" class="c-nav-icon nav-link <?= active_class_mobile("home") ?>"
         aria-current="page" title=""
         data-bs-toggle="tooltip"
         data-bs-placement="top" data-bs-title="Home">
        <i class="fa-solid fa-fw fa-home"></i>
      </a>
    </li>

    <li class="nav-item">
      <a href="/search" class="c-nav-icon nav-link <?= active_class_mobile("search") ?>"
         aria-current="page" title=""
         data-bs-toggle="tooltip"
         data-bs-placement="top" data-bs-title="Search">
        <i class="fa-solid fa-fw fa-magnifying-glass"></i>
      </a>
    </li>
  </ul>

  <ul class="nav nav-flush gap-3">
    <li class="nav-item">
      <a href="/home#new" class="c-nav-icon c-nav-primary nav-link text-primary" id="new-status-anchor"
         aria-current="page" title=""
         data-bs-toggle="tooltip"
         data-bs-placement="top" data-bs-title="New Status">
        <i class="fa-solid fa-fw fa-pen-to-square"></i>
      </a>
    </li>
  </ul>

  <ul class="nav nav-flush gap-3 flex-even justify-content-end">
    <li class="nav-item">
      <a href="/profile/<?= $_SESSION['username'] ?>" class="c-nav-icon nav-link"
         aria-current="page" title=""
         data-bs-toggle="tooltip"
         data-bs-placement="top" data-bs-title="Profile">
        <i class="fa-solid fa-fw fa-user"></i>
      </a>
    </li>

    <li class="nav-item">
      <a href="/info" class="c-nav-icon nav-link <?= active_class("info") ?>"
         aria-current="page" title=""
         data-bs-toggle="tooltip"
         data-bs-placement="top" data-bs-title="Info">
        <i class="fa-solid fa-fw fa-circle-info"></i>
      </a>
    </li>

    <li class="nav-item">
      <a href="/auth" class="c-nav-icon c-nav-danger nav-link"
         aria-current="page" title=""
         data-bs-toggle="tooltip"
         data-bs-placement="top" data-bs-title="Logout">
        <i class="fa-solid fa-fw fa-arrow-right-from-bracket"></i>
      </a>
    </li>
  </ul>
</div>
