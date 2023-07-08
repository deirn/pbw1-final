<?php

function active_class(string $title): string
{
    global $page_title;

    return $page_title == $title ? 'active' : '';
}

?>

<div class="c-nav sticky-top d-flex flex-column border-end p-3">
  <ul class="nav nav-flush flex-column gap-3 flex-grow-1">
    <li class="nav-item">
      <a href="/home" class="c-nav-icon nav-link active">
        <i class="active fa-solid fa-fw fa-bug fa-spin-pulse"></i>
      </a>
    </li>

    <li class="nav-item">
      <a href="/home" class="c-nav-icon nav-link <?= active_class("Home") ?>"
         aria-current="page" title=""
         data-bs-toggle="tooltip"
         data-bs-placement="right" data-bs-title="Home">
        <i class="fa-solid fa-fw fa-home"></i>
      </a>
    </li>

    <li class="nav-item">
      <a href="/search" class="c-nav-icon nav-link <?= active_class("Search") ?>"
         aria-current="page" title=""
         data-bs-toggle="tooltip"
         data-bs-placement="right" data-bs-title="Search">
        <i class="fa-solid fa-fw fa-magnifying-glass"></i>
      </a>
    </li>

    <li class="nav-item">
      <a href="?new" class="c-nav-icon c-nav-primary nav-link text-primary"
         aria-current="page" title=""
         data-bs-toggle="tooltip"
         data-bs-placement="right" data-bs-title="New Status">
        <i class="fa-solid fa-fw fa-pen-to-square"></i>
      </a>
    </li>

    <li class="nav-item flex-grow-1"></li>

    <li class="nav-item">
      <a href="/profile/<?= $_SESSION['username'] ?>" class="c-nav-icon nav-link"
         aria-current="page" title=""
         data-bs-toggle="tooltip"
         data-bs-placement="right" data-bs-title="Profile">
        <i class="fa-solid fa-fw fa-user"></i>
      </a>
    </li>

    <li class="nav-item">
      <a href="/auth" class="c-nav-icon c-nav-danger nav-link"
         aria-current="page" title=""
         data-bs-toggle="tooltip"
         data-bs-placement="right" data-bs-title="Logout">
        <i class="fa-solid fa-fw fa-arrow-right-from-bracket"></i>
      </a>
    </li>
  </ul>
</div>
