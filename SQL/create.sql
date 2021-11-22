drop table if exists "image";
drop table if exists user;
drop table if exists group;
drop table if exists friendship;
drop table if exists membership;
drop table if exists user_content;
drop table if exists post;
drop table if exists comment;
drop table if exists share;
drop table if exists tag;
drop table if exists "like";
drop table if exists friend_request;
drop table if exists group_request;
drop table if exists group_invite;
drop table if exists like_notification;
drop table if exists comment_notification;
drop table if exists tag_notification;
drop table if exists share_notification;
drop table if exists group_invite_notification;
drop table if exists group_request_notification;
drop table if exists friend_request_notification;


drop type if exists request_status;

create type request_status as ENUM (
    'Accepted',
    'Declined',
    'Pending'
);

create table "image"
(
    id serial primary key,
    alt text,
    "path" text not null
);

create table user
(
    id serial primary key,
    "name" text not null,
    birthdate date,
    email text not null unique,
    "password" text not null,
    admin_flag boolean not null default False,
    profile_pic integer references image (id) on delete set null,
    cover_pic integer references image (id) on delete set null,
    constraint email_formatting check (email like '_%@_%.-%')
);

create table group
(
    id serial primary key,
    "name" text not null,
    creation_date date not null default current_date,
    cover_pic integer references image (id) on delete set null,
    private_flag boolean not null
);

create table friendship
(
    user_1 integer references user (id) on delete cascade,
    user_2 integer references user (id) on delete cascade,
    primary key (user_1, user_2),
    constraint no_self_friendship check (user_1 <> user_2)
);

create table membership
(
    user integer references user(id) on delete cascade,
    group integer references group(id) on delete cascade,
    moderator boolean default False,
    primary key (user, group)
)

--- Why not OO approach

create table user_content
(
    id serial primary key,
    "text" text,
    "timestamp" timestamp with zone not null default now(),
    creator_id integer references user (id),
    edited_id boolean not null,
    group_id integer references group(id),
    pinned_id boolean not null,
);

create table post
(
    id integer references user_content(id) on delete cascade,
    pic_1 integer references "image" (id),
    pic_2 integer references "image" (id),
    pic_3 integer references "image" (id),
    pic_4 integer references "image" (id),
    pic_5 integer references "image" (id),
    primary key (post_id)
);

create table comment
(
    id integer references user_content(id) on delete cascade,
    parent_id integer references user_content(id) on delete cascade,
    primary key (comment_id)
);

create table share
(
    id integer references user_content(id) on delete cascade,
    post_id integer references post(post_id),
    primary key (share_id)
);

create table tag
(
    "user_id" integer references user(id),
    content_id integer references user_content(id),
    primary key (user, content)
);

create table "like"
(
    "user_id" integer references user(id),
    content_id integer references user_content(id),
    primary key (user, content)
);

create table friend_request
(
    requester_id integer references user(id),
    target_id integer references user(id),
    req_stat request_status not null default "Pending",
    primary key (requester, "target")
);

create table group_request
(
    requester_id integer references user(id),
    target_id integer references group(id),
    req_stat request_status not null default "Pending",
    invite boolean not null,
    primary key (requester, "target")
);

create table like_notification
(
    id serial primary key,
    "timestamp" timestamp with zone not null default now(),
    seen boolean not null default False,
    sender_id integer references user,            
    content_id integer references user_content
);

create table comment_notification
(
    id serial primary key,
    "timestamp" timestamp with zone not null default now(),
    seen boolean not null default False,        
    comment_id integer references comment
);

create table tag_notification
(
    id serial primary key,
    "timestamp" timestamp with zone not null default now(),
    seen boolean not null default False,         
    content_id integer references user_content,
    target_id integer references user
);

create table share_notification
(
    id serial primary key,
    "timestamp" timestamp with zone not null default now(),
    seen boolean not null default False,
    share_id integer references share
);

create table group_invite_notification
(
    id serial primary key,
    "timestamp" timestamp with zone not null default now(),
    seen boolean not null default False,
    group_id integer references group,
    "user_id" integer references user,
);

create table friend_request_notification
(
    id serial primary key,
    "timestamp" timestamp with zone not null default now(),
    seen boolean not null default False,
    sender_id integer references user,
    target_id integer references user
);

create table group_request_notification
(
    id serial primary key,
    "timestamp" timestamp with zone not null default now(),
    seen boolean not null default False,
    group_id integer references group,
    "user_id" integer references user
);
