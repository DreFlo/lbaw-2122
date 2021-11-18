drop table if exists "image";
drop table if exists authenticated_user;
drop table if exists group;
drop table if exists friendship;
drop table if exists membership;
drop table if exists user_content;
drop table if exists post;
drop table if exists comment;
drop table if exists share;
drop table if exists tag;
drop table if exists "like";
drop table if exists user_notification;
drop table if exists content_user_notification;
drop table if exists friend_request_user_notification;
drop table if exists group_invite_user_notification;
drop table if exists request_group_notification;
drop table if exists friend_request;
drop table if exists group_request;
drop table if exists group_invite;


drop type if exists request_status;

create type request_status as ENUM (
    'Accepted',
    'Declined',
    'Pending'
);

create table "image"
(
    img_id integer primary key,
    alt text,
    img_path text not null
);

create table authenticated_user
(
    auth_user_id integer primary key,
    name text not null,
    birthdate date,
    email text not null like ,
    password text not null,
    admin_flag boolean not null,
    profile_pic integer references image (img_id) on delete set null,
    cover_pic integer references image (img_id) on delete set null,
    constraint email_formatting check (email like '_%@_%.-%')
);

create table group
(
    group_id integer primary key,
    name text not null,
    creation_date date not null default current_date,
    cover_pic integer references image (img_id) on delete set null,
    private_flag boolean not null
);

create table friendship
(
    user_1 integer references authenticated_user (auth_user_id) on delete cascade,
    user_2 integer references authenticated_user (auth_user_id) on delete cascade,
    primary key (user_1, user_2),
    constraint no_self_friendship check (user_1 <> user_2)
);

create table membership
(
    user integer references authenticated_user(auth_user_id) on delete cascade,
    group integer references group(group_id) on delete cascade,
    moderator boolean,
    primary key (user, group)
)

create table user_content
(
    user_content_id integer primary key,
    "text" text,
    "timestamp" timestamp with zone not null default now(),
    creator integer references authenticated_user (auth_user_id),
    edited boolean not null,
    pinned boolean not null,
    group_post boolean not null,
    group_id integer references group(group_id),
    pic_1 integer references "image"(img_id),
    pic_2 integer references "image"(img_id),
    pic_3 integer references "image"(img_id),
    pic_4 integer references "image"(img_id),
    pic_5 integer references "image"(img_id),
    constraint group_constraint check ((group_post == false and group_id is null) or (group_post == true and not group_id is null))
);

create table post
(
    post_id integer references user_content(user_content_id) on delete cascade,
    primary key (post_id)
);

create table comment
(
    comment_id integer references user_content(user_content_id) on delete cascade,
    parent_id integer references user_content(user_content_id) on delete cascade,
    primary key (comment_id)
);

create table share
(
    share_id integer references user_content(user_content_id) on delete cascade,
    post_id integer references post(post_id),
    primary key (share_id)
);

create table tag
(
    user integer references authenticated_user(auth_user_id),
    content integer references user_content(user_content_id),
    primary key (user, content)
);

create table "like"
(
    user integer references authenticated_user(auth_user_id),
    content integer references user_content(user_content_id),
    "timestamp" timestamp with zone not null default now(),
    primary key (user, content)
);

create table user_notification
(
    not_id integer primary key,
    "timestamp" timestamp with zone not null default now(),
    user integer references authenticated_user(auth_user_id),
    seen boolean
);

create table content_user_notification
(
    not_id integer references user_notification(not_id),
    content_id integer references user_content(user_content_id),
    primary key (not_id)
);

create table friend_request_user_notification
(
    not_id integer references user_notification(not_id),
    requester_id integer references authenticated_user(auth_user_id),
    primary key (not_id)
);

create table group_invite_user_notification
(
    not_id integer references user_notification(not_id),
    inviter_id integer references group(group_id),
    primary key (not_id)
);

create table request_group_notification
(
    requester_id integer references authenticated_user(auth_user_id),
    group_id integer references group(group_id),
    primary key (requester_id, group_id)
);

create table friend_request
(
    requester integer references authenticated_user(auth_user_id),
    "target" integer references authenticated_user(auth_user_id),
    req_stat request_status not null default "Pending",
    "timestamp" timestamp with zone not null default now(),
    primary key (requester, "target")
);

create table group_request
(
    requester integer references authenticated_user(auth_user_id),
    "target" integer references group(group_id),
    req_stat request_status not null default "Pending",
    "timestamp" timestamp with zone not null default now(),
    primary key (requester, "target")
);

create table group_invite
(
    requester integer references group(group_id),
    "target" integer references authenticated_user(auth_user_id),
    req_stat request_status not null default "Pending",
    "timestamp" timestamp with zone not null default now(),
    primary key (requester, "target")
);
