SET search_path TO lbaw2192;

drop table if exists "image";
drop table if exists "user";
drop table if exists "group";
drop table if exists "friendship";
drop table if exists "membership";
drop table if exists "user_content";
drop table if exists "post";
drop table if exists "comment";
drop table if exists "share";
drop table if exists "tag";
drop table if exists "like";
drop table if exists "friend_request";
drop table if exists "group_request";
drop table if exists "group_invite";
drop table if exists "like_notification";
drop table if exists "comment_notification";
drop table if exists "tag_notification";
drop table if exists "share_notification";
drop table if exists "group_invite_notification";
drop table if exists "group_request_notification";
drop table if exists "friend_request_notification";

drop type if exists request_status;
drop type if exists privacy_status;

create type request_status as ENUM (
    'Accepted',
    'Declined',
    'Pending'
);

create type privacy_status as ENUM (
    'Public',
    'Private',
    'Anonymous'
);

create table "image"
(
    id serial primary key,
    alt text,
    "path" text not null
);

create table "user"
(
    id serial,
    "name" text not null,
    birthdate date not null,
    email text not null unique,
    "password" text not null,
    admin_flag boolean not null default 'false',
    profile_pic integer,
    cover_pic integer,
    priv_stat privacy_status not null default 'Public',
	primary key(id),
    constraint email_formatting check (email like '_%@_%._%'),
	constraint fk_profile_pic foreign key(profile_pic) references image(id) on delete set null,
	constraint fk_cover_pic foreign key(cover_pic) references image(id) on delete set null
);

create table "group"
(
    id serial primary key,
    "name" text not null,
    creation_date date not null default current_date,
    cover_pic integer,
    priv_stat privacy_status not null default 'Public',
	constraint fk_cover_pic foreign key(cover_pic) references image(id) on delete set null
);

create table "friendship"
(
    user_1 integer,
    user_2 integer,
    primary key(user_1, user_2),
    constraint no_self_friendship check (user_1 <> user_2),
	constraint fk_user_1 foreign key(user_1) references "user"(id) on delete cascade,
	constraint fk_user_2 foreign key(user_2) references "user"(id) on delete cascade
);

create table "membership"
(
    "user" integer,
    "group" integer,
    moderator boolean not null default 'false',
    primary key ("user", "group"),
	constraint fk_user foreign key("user") references "user"(id) on delete cascade,
	constraint fk_group foreign key("group") references "group"(id) on delete cascade
);

--- Why not OO approach

create table "user_content"
(
    id serial primary key,
    "text" text not null,
    "timestamp" timestamptz not null default now(),
    creator_id integer not null,
    edited boolean not null default 'false',
    group_id integer,
    pinned boolean not null default 'false',
    priv_stat privacy_status not null references "user"(priv_stat),
	constraint fk_creator_id foreign key(creator_id) references "user"(id),
	constraint fk_group_id foreign key(group_id) references "group"(id)
);

create table "post"
(
    id integer,
    pic_1 integer,
    pic_2 integer,
    pic_3 integer,
    pic_4 integer,
    pic_5 integer,
    primary key (id),
	constraint fk_id foreign key(id) references user_content(id) on delete cascade,
	constraint fk_pic_1 foreign key(pic_1) references "image"(id),
	constraint fk_pic_2 foreign key(pic_2) references "image"(id),
	constraint fk_pic_3 foreign key(pic_3) references "image"(id),
	constraint fk_pic_4 foreign key(pic_4) references "image"(id),
	constraint fk_pic_5 foreign key(pic_5) references "image"(id)
);

create table "comment"
(
    id integer,
    parent_id integer,
    primary key (id),
	constraint fk_id foreign key(id) references user_content(id) on delete cascade,
	constraint fk_parent_id foreign key(parent_id) references user_content(id) on delete cascade
);

create table "share"
(
    id integer,
    post_id integer,
    primary key (id),
	constraint fk_id foreign key(id) references user_content(id) on delete cascade,
	constraint fk_post_id foreign key(post_id) references post(post_id)	
);

create table "tag"
(
    user_id integer,
    content_id integer,
    primary key (user_id, content_id),
	constraint fk_user_id foreign key(user_id) references "user"(id),
	constraint fk_content_id foreign key(content_id) references user_content(id)
);

create table "like"
(
    user_id integer,
    content_id integer,
    primary key ("user_id", content_id),
	constraint fk_user_id foreign key(user_id) references "user"(id),
	constraint fk_content_id foreign key(content_id) references user_content(id)
);

create table "friend_request"
(
    requester_id integer,
    target_id integer,
    req_stat request_status not null default 'Pending',
    primary key (requester_id, target_id),
	constraint fk_request_id foreign key(request_id) references "user"(id),
	constraint fk_target_id foreign key(target_id) references "user"(id)
);

create table "group_request"
(
    requester_id integer,
    target_id integer,
    req_stat request_status not null default 'Pending',
    invite boolean not null,
    primary key (requester_id, target_id),
	constraint fk_request_id foreign key(request_id) references "user"(id),
	constraint fk_target_id foreign key(target_id) references "group"(id)
);

-- why not pk with user and user_content

create table "like_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',
    sender_id integer,            
    content_id integer,
	constraint fk_sender_id foreign key(sender_id) references "user"(id),
	constraint fk_content_id foreign key(content_id) references user_content(id)
);

create table "comment_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',        
    comment_id integer,
	constraint fk_comment_id foreign key(comment_id) references "comment"(id)
);

create table "tag_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',         
    content_id integer,
    target_id integer,
	constraint fk_content_id foreign key(content_id) references user_content(id),
	constraint fk_target_id foreign key(target_id) references "user"(id)
);

create table "share_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',
    share_id integer,
	constraint fk_share_id foreign key(share_id) references "share"(id)
);

create table "group_invite_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',
    group_id integer,
    user_id integer,
	constraint fk_group_id foreign key(group_id) references "group"(id),
	constraint fk_user_id foreign key(user_id) references "user"(id)
	
);

create table "friend_request_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',
    sender_id integer references "user"(id),
    target_id integer references "user"(id)
);

create table "group_request_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',
    group_id integer references "group"(id),
    "user_id" integer references "user"(id)
);

drop index if exists "post_text";

create index post_text on user_content using gist (to_tsvector('english', "text"));

