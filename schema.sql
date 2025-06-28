CREATE DATABASE IF NOT EXISTS tudayka_db
    DEFAULT CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE tudayka_db;

CREATE TABLE IF NOT EXISTS chats
(
    chat_id           bigint               not null
        primary key,
    first_name        varchar(255)         null,
    last_name         varchar(255)         null,
    username          varchar(255)         null,
    waiting_for_event tinyint(1) default 0 null
)
    collate = utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS messages
(
    id         int auto_increment
        primary key,
    chat_id    bigint    null,
    text       text      null,
    created_at timestamp null,
    constraint fk_chat_id
        foreign key (chat_id) references chats (chat_id)
)
    collate = utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS users_yougile
(
    id          int auto_increment
        primary key,
    chat_id     varchar(255) null,
    username_tg varchar(255) null,
    auth_token  varchar(255) null
);


