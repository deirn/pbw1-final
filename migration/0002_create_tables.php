<?php

use Controllers\Database\DB;

// language=MariaDB
$query = <<<EOL
create or replace table attachment
(
    attachment_id int auto_increment
        primary key,
    status_id     int         not null,
    uploaded_at   datetime    not null,
    file_id       varchar(64) not null
);

create or replace index status_id
    on attachment (status_id);

create or replace table connection
(
    connection_id      int auto_increment
        primary key,
    follower_username  varchar(15) not null,
    following_username varchar(15) not null
);

create or replace index follower_username
    on connection (follower_username);

create or replace index following_username
    on connection (following_username);

create or replace table status
(
    status_id        int auto_increment
        primary key,
    username         varchar(15)  not null,
    parent_status_id int          null,
    status_content   varchar(280) not null,
    created_at       datetime     not null,
    updated_at       datetime     null
);

alter table attachment
    add constraint attachment_ibfk_1
        foreign key (status_id) references status (status_id);

create or replace index parent_status_id
    on status (parent_status_id);

create or replace index username
    on status (username);

alter table status
    add constraint status_ibfk_1
        foreign key (parent_status_id) references status (status_id)
            on delete set null;

create or replace table user
(
    user_id      int auto_increment
        primary key,
    username     varchar(15)  not null,
    password     varchar(64)  not null,
    display_name varchar(30)  not null,
    avatar       varchar(64)  null,
    bio          varchar(160) null
);

alter table user
    add constraint username
        unique (username);

alter table connection
    add constraint connection_ibfk_1
        foreign key (follower_username) references user (username)
            on update cascade on delete cascade;

alter table connection
    add constraint connection_ibfk_2
        foreign key (following_username) references user (username)
            on update cascade on delete cascade;

alter table status
    add constraint status_ibfk_2
        foreign key (username) references user (username)
            on update cascade on delete cascade;
EOL;

DB::connection()->multi_query($query);
DB::clear_stored_result();

echo 'Tables created' . PHP_EOL;
