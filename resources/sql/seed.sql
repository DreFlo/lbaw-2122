create schema if not exists lbaw2192;
set search_path to lbaw2192;

drop table if exists "image" cascade;
drop table if exists "user" cascade;
drop table if exists "group" cascade;
drop table if exists "friendship" cascade;
drop table if exists "membership" cascade;
drop table if exists "user_content" cascade;
drop table if exists "post" cascade;
drop table if exists "comment" cascade;
drop table if exists "share" cascade;
drop table if exists "tag" cascade;
drop table if exists "like" cascade;
drop table if exists "friend_request" cascade;
drop table if exists "group_request" cascade;
drop table if exists "like_notification" cascade;
drop table if exists "comment_notification" cascade;
drop table if exists "tag_notification" cascade;
drop table if exists "share_notification" cascade;
drop table if exists "group_invite_notification" cascade;
drop table if exists "group_request_notification" cascade;
drop table if exists "friend_request_notification" cascade;

drop type if exists request_status cascade;
drop type if exists privacy_status cascade;

create type request_status as ENUM (
    'Accepted',
    'Declined',
    'Pending'
    );

create type privacy_status as ENUM (
    'Public',
    'Private',
    'Anonymous',
    'Banned'
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
    birthdate date not null check (age(birthdate) >= '13 years'),
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
    creator_id integer not null,
    priv_stat privacy_status not null default 'Public',
    constraint fk_cover_pic foreign key(cover_pic) references image(id) on delete set null,
    constraint fk_creator_id foreign key(creator_id) references "user"(id) on delete set null
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
    user_id integer,
    group_id integer,
    moderator boolean not null default 'false',
    primary key (user_id, group_id),
    constraint fk_user foreign key(user_id) references "user"(id) on delete cascade,
    constraint fk_group foreign key(group_id) references "group"(id) on delete cascade
);

create table "user_content"
(
    id serial primary key,
    "text" text not null,
    "timestamp" timestamptz not null default Now(),
    creator_id integer not null,
    edited boolean not null default 'false',
    group_id integer,
    pinned boolean not null default 'false',
    priv_stat privacy_status not null,
    constraint fk_creator_id foreign key(creator_id) references "user"(id) on delete cascade,
    constraint fk_group_id foreign key(group_id) references "group"(id) on delete cascade,
    constraint no_future_dates check ("timestamp" <= now())
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
    constraint fk_pic_1 foreign key(pic_1) references "image"(id) on delete set null,
    constraint fk_pic_2 foreign key(pic_2) references "image"(id) on delete set null,
    constraint fk_pic_3 foreign key(pic_3) references "image"(id) on delete set null,
    constraint fk_pic_4 foreign key(pic_4) references "image"(id) on delete set null,
    constraint fk_pic_5 foreign key(pic_5) references "image"(id) on delete set null
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
    constraint fk_post_id foreign key(post_id) references post(id)
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
    constraint fk_requester_id foreign key(requester_id) references "user"(id),
    constraint fk_target_id foreign key(target_id) references "user"(id)
);

create table "group_request"
(
    user_id integer,
    group_id integer,
    req_stat request_status not null default 'Pending',
    invite boolean not null,
    primary key (user_id, group_id),
    constraint fk_user_id foreign key(user_id) references "user"(id),
    constraint fk_group_id foreign key(group_id) references "group"(id)
);

create table "like_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',
    sender_id integer not null,
    content_id integer not null,
    constraint fk_sender_id foreign key(sender_id) references "user"(id),
    constraint fk_content_id foreign key(content_id) references user_content(id),
    constraint no_future_dates check ("timestamp" <= now())
);

create table "comment_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',
    comment_id integer not null,
    constraint fk_comment_id foreign key(comment_id) references "comment"(id),
    constraint no_future_dates check ("timestamp" <= now())
);

create table "tag_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',
    content_id integer not null,
    target_id integer not null,
    constraint fk_content_id foreign key(content_id) references user_content(id),
    constraint fk_target_id foreign key(target_id) references "user"(id),
    constraint no_future_dates check ("timestamp" <= now())
);

create table "share_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',
    share_id integer not null,
    constraint fk_share_id foreign key(share_id) references "share"(id),
    constraint no_future_dates check ("timestamp" <= now())
);

create table "group_invite_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',
    group_id integer not null,
    "user_id" integer not null,
    constraint fk_group_id foreign key(group_id) references "group"(id),
    constraint fk_user_id foreign key(user_id) references "user"(id),
    constraint no_future_dates check ("timestamp" <= now())
);

create table "friend_request_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',
    sender_id integer not null,
    target_id integer not null,
    constraint fk_sender_id foreign key(sender_id) references "user"(id),
    constraint fk_target_id foreign key(target_id) references "user"(id),
    constraint no_future_dates check ("timestamp" <= now())
);

create table "group_request_notification"
(
    id serial primary key,
    "timestamp" timestamptz not null default now(),
    seen boolean not null default 'false',
    group_id integer not null,
    "user_id" integer not null,
    constraint fk_group_id foreign key(group_id) references "group"(id),
    constraint fk_user_id foreign key("user_id") references "user"(id),
    constraint no_future_dates check ("timestamp" <= now())
);

drop index if exists "group_posts_index";
drop index if exists "parent_comments_index";
drop index if exists "like_notif_index";
drop index if exists "comment_notif_index";
drop index if exists "tag_notif_index";
drop index if exists "group_inv_notif_index";
drop index if exists "group_req_notif_index";
drop index if exists "friend_req_notif_index";
drop index if exists "user_name_index";
drop index if exists "group_name_index";
drop index if exists "user_content_text";
drop index if exists "user_content_creator_index";

create index "user_name_index" on "user" using hash ("name");

create index "group_name_index" on "group" using gist (setweight(to_tsvector('english', "name"), 'B'));

create index "user_content_text" on user_content using gist (setweight(to_tsvector('english', "text"), 'A'));

create index "user_content_creator_index" on user_content using hash(creator_id);

create index "group_posts_index" on user_content using hash (group_id);

create index "parent_comments_index" on comment using hash (parent_id);

create index "like_notif_index" on like_notification using hash (content_id);

create index "comment_notif_index" on comment_notification using hash (comment_id);

create index "tag_notif_index" on tag_notification using hash (target_id);

create index "group_inv_notif_index" on group_invite_notification using hash (user_id);

create index "group_req_notif_index" on group_request_notification using hash (group_id);

create index "friend_req_notif_index" on friend_request_notification using hash (target_id);

-- Make data anonymous on user_content delete
create or replace function user_content_delete_fn(content_id integer)
    returns integer as '
    begin
        update "user_content" set priv_stat = ''Anonymous'' where id=content_id;
        return 0;
    end;'
    language plpgsql;

-- Make data anonymous on user delete
create or replace function user_delete_fn(u_id integer)
    returns integer as '
    begin
        update "user" set priv_stat = ''Anonymous'' where id=u_id;
        update "user_content" set priv_stat = ''Anonymous'' where creator_id=u_id;
        return 0;
    end;'
    language plpgsql;

-- Make data anonymous on group delete
create or replace function group_delete_fn(g_id integer)
    returns integer as '
    begin
        update "group" set priv_stat = ''Anonymous'' where id=g_id;
        update "user_content" set priv_stat = ''Anonymous'' where group_id=g_id;
        return 0;
    end;'
    language plpgsql;

create or replace function create_like_notif_fn()
    returns trigger as '
    begin
        insert into "like_notification"(sender_id, content_id) values (new.user_id, new.content_id);
        return null;
    end; '
    language plpgsql;

drop trigger if exists create_like_notification on "like";

create trigger create_like_notification
    after insert on "like" for each row
execute procedure create_like_notif_fn();

create or replace function create_comment_notif_fn()
    returns trigger as '
    begin
        insert into "comment_notification"(comment_id) values (new.id);
        return null;
    end; '
    language plpgsql;

drop trigger if exists create_comment_notification on "comment";

create trigger create_comment_notification
    after insert on "comment" for each row
execute procedure create_comment_notif_fn();

create or replace function create_tag_notif_fn()
    returns trigger as '
    begin
        insert into "tag_notification"(content_id, target_id) values(new.content_id, new.user_id);
        return null;
    end'
    language plpgsql;

drop trigger if exists create_tag_notification on "tag";

create trigger create_tag_notification
    after insert on "tag" for each row
execute procedure create_tag_notif_fn();

create or replace function create_share_notif_fn()
    returns trigger as '
    begin
        insert into share_notification(share_id) values(new.id);
        return null;
    end;'
    language plpgsql;

drop trigger if exists create_share_notification on "share";

create trigger create_share_notification
    after insert on "share" for each row
execute procedure create_share_notif_fn();

create or replace function create_group_req_or_inv_notif_fn()
    returns trigger as '
    begin
        if (new.invite) then
            insert into group_invite_notification(group_id, user_id) values(new.group_id, new.user_id);
        else
            insert into group_request_notification(group_id, user_id) values(new.group_id, new.user_id);
        end if;
        return null;
    end;'
    language plpgsql;

drop trigger if exists create_group_inv_or_req_notification on "group_request";

create trigger create_group_inv_or_req_notification
    after insert on "group_request" for each row
execute procedure create_group_req_or_inv_notif_fn();

create or replace function create_friend_req_notif_fn()
    returns trigger as '
    begin
        insert into friend_request_notification(sender_id, target_id) values(new.requester_id, new.target_id);
        return null;
    end;'
    language plpgsql;

drop trigger if exists create_friend_request_notification on "friend_request";

create trigger create_friend_request_notification
    after insert on "friend_request" for each row
execute procedure create_friend_req_notif_fn();

create or replace function add_founder_fn()
    returns trigger as '
    begin
        insert into membership(user_id, group_id, moderator) values(new.creator_id, new.id, ''true'');
        return null;
    end;'
    language plpgsql;

drop trigger if exists add_founder_to_group on "group";

create trigger add_founder_to_group
    after insert on "group" for each row
execute procedure add_founder_fn();

create or replace function check_new_friendship_fn()
    returns trigger as '
    begin
        if (new.req_stat = ''Accepted'') then
            insert into friendship(user_1, user_2) values(new.requester_id, new.target_id);
            delete from friend_request where requester_id = new.requester_id and target_id = new.target_id;
            return null;
        elsif (new.req_stat = ''Declined'') then
            delete from friend_request where requester_id = new.requester_id and target_id = new.target_id;
            return null;
        else
            return null;
        end if;
    end;'
    language plpgsql;

drop trigger if exists add_friendship_on_friend_req_update on "friend_request";

create trigger add_friendship_on_friend_req_update
    after update on "friend_request" for each row
execute procedure check_new_friendship_fn();

create or replace function check_new_membership_fn()
    returns trigger as '
    begin
        if (new.req_stat = ''Accepted'') then
            insert into membership(user_id, group_id) values(new.user_id, new.group_id);
            delete from group_request where user_id = new.user_id and group_id = new.group_id;
            return null;
        elsif (new.req_stat = ''Declined'') then
            delete from group_request where user_id = new.user_id and group_id = new.group_id;
            return null;
        else
            return null;
        end if;
    end;
'
    language plpgsql;

drop trigger if exists add_membership_on_group_req_update on "group_request";

create trigger add_membership_on_group_req_update
    after update on "group_request" for each row
execute procedure  check_new_membership_fn();

insert into "user"(id, "name", birthdate, email, "password") values(1, 'Andre', '2001-03-15', 'a@a.a', 'pass');
insert into "user"(id, "name", birthdate, email, "password") values(2, 'Tiago', '2001-05-11', 't@t.t', 'pass');
insert into "user"(id, "name", birthdate, email, "password") values(3, 'Diogo', '2001-11-30', 'd@d.d', 'pass');
insert into "user"(id, "name", birthdate, email, "password") values(4, 'Mendes', '1998-10-21', 'm@m.m', 'pass');
insert into "user"(id, "name", birthdate, email, "password") values(5, 'Ana', '2000-02-23', 'ana@a.a', '1234567');
insert into "user"(id, "name", birthdate, email, "password") values(6, 'Miguel', '1997-01-01', 'miguel@mig.m', 'passe');
insert into "user"(id, "name", birthdate, email, "password") values(7, 'Joao', '2003-07-05', 'j@j.j', 'passsss');
insert into "user"(id, "name", birthdate, email, "password") values(8, 'Pedro', '1999-09-19', 'p@p.p', 'palavrapasse');
insert into "user"(id, "name", birthdate, email, "password") values(9, 'Maria', '1998-12-23', 'maria@m.ma', 'sim123');
insert into "user"(id, "name", birthdate, email, "password") values(10, 'Carlos', '2000-04-01', 'car@car.car', 'teste');
insert into "user"(id, "name", birthdate, email, "password") values(11, 'Sara', '1999-03-20', 's@s.s', 'teste123');
insert into "user"(id, "name", birthdate, email, "password") values(12, 'Manuel', '2002-01-13', 'man@man.m', 'exemplo');
insert into "user"(id, "name", birthdate, email, "password") values(13, 'Sofia', '1996-08-11', 'sof@sof.s', 'example');
insert into "user"(id, "name", birthdate, email, "password") values(14, 'Fernando', '1997-03-07', 'f@f.f', 'yup123');
insert into "user"(id, "name", birthdate, email, "password") values(15, 'Ariana', '2000-09-22', 'ari@ari.ari', 'naosei');
insert into "user"(id, "name", birthdate, email, "password") values(16, 'Julio', '1995-10-31', 'jul@jul.j', 'esqueci_me');
insert into "user"(id, "name", birthdate, email, "password") values(17, 'Paula', '2000-06-25', 'pa@pa.pa', 'strongpass');
insert into "user"(id, "name", birthdate, email, "password") values(18, 'Marco', '1998-03-18', 'mar@mar.mar', 'lockedin');
insert into "user"(id, "name", birthdate, email, "password") values(19, 'Alice', '1999-09-23', 'ali@ali.a', '123exemplo');
insert into "user"(id, "name", birthdate, email, "password") values(20, 'Rui', '1999-09-19', 'r@ru.rui', '123321');
insert into "user"(id, "name", birthdate, email, "password") values(21, 'Carolina', '2002-10-04', 'carol@c.car', 'numeros');

insert into "friendship"(user_1, user_2) values(2,8);
insert into "friendship"(user_1, user_2) values(8,14);
insert into "friendship"(user_1, user_2) values(1,3);
insert into "friendship"(user_1, user_2) values(2,16);
insert into "friendship"(user_1, user_2) values(10,6);
insert into "friendship"(user_1, user_2) values(7,1);
insert into "friendship"(user_1, user_2) values(8,3);
insert into "friendship"(user_1, user_2) values(16,12);
insert into "friendship"(user_1, user_2) values(12,18);
insert into "friendship"(user_1, user_2) values(3,5);

--new part
insert into "friendship"(user_1, user_2) values(8,2);
insert into "friendship"(user_1, user_2) values(14,8);
insert into "friendship"(user_1, user_2) values(3,1);
insert into "friendship"(user_1, user_2) values(16,2);
insert into "friendship"(user_1, user_2) values(6,10);
insert into "friendship"(user_1, user_2) values(1,7);
insert into "friendship"(user_1, user_2) values(3,8);
insert into "friendship"(user_1, user_2) values(12,16);
insert into "friendship"(user_1, user_2) values(18,12);
insert into "friendship"(user_1, user_2) values(5,3);
--

insert into "group"(id, "name", creator_id) values(1, 'FEUP', 1);
insert into "group"(id, "name", creator_id) values(2, 'TechNerds', 1);
insert into "group"(id, "name", creator_id) values(3, 'HarryPotter Fans', 2);
insert into "group"(id, "name", creator_id) values(4, 'Book Geeks', 3);
insert into "group"(id, "name", creator_id) values(5, 'Gaming Community', 3);
insert into "group"(id, "name", creator_id) values(6, 'Influencers', 4);
insert into "group"(id, "name", creator_id) values(7, 'FoodLovers', 4);
insert into "group"(id, "name", creator_id) values(8, '_ShareMusic_', 4);
insert into "group"(id, "name", creator_id) values(9, 'MathGeniuses', 3);
insert into "group"(id, "name", creator_id) values(10, 'AnimeAddicted', 1);

insert into "membership"(user_id, group_id) values(2,1);
insert into "membership"(user_id, group_id) values(2,2);
insert into "membership"(user_id, group_id) values(2,4);
insert into "membership"(user_id, group_id) values(2,5);
insert into "membership"(user_id, group_id) values(2,6);
insert into "membership"(user_id, group_id) values(2,7);
insert into "membership"(user_id, group_id) values(2,8);
insert into "membership"(user_id, group_id) values(2,9);
insert into "membership"(user_id, group_id) values(2,10);
insert into "membership"(user_id, group_id) values(1,5);

insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(1, 'Hi!', 1, 2, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(2, 'My recent trip was quite satisfying!', 2, null, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(3, 'Your mission is not just difficult, it is impossible.', 3, null, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(4, 'The word SUN has only one syllable.', 1, 6, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(5, 'Hi! It is often said that cats have nine lives but that is really just a myth.', 5, 1, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(6, 'Clowns like to display humor!', 2, null, 'Private');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(7, 'I have been busier these days due to having a lot on my plate.', 5, 3, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(8, 'This... is... delicious!', 4, null, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(9, 'I finally got a new bike!', 7, null, 'Private');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(10, 'Thank you for repaying the favor when you didnt have to.', 1, 7, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(11, 'Share', 1, null, 'Public');

insert into "image"(id, path) values (1, 'storage/images/Wheel-of-Time-MyrddraalTeaser.webp');
insert into "image"(id, path) values (2, 'storage/images/tumblr_nqza7zgUZc1tqgexdo1_1280.jpg');

insert into "post"(id) values(1);
insert into "post"(id, pic_1, pic_2) values(2, 1, 2);
insert into "post"(id) values(3);
insert into "post"(id) values(4);
insert into "post"(id) values(5);

insert into "comment"(id, parent_id) values(6, 2);
insert into "comment"(id, parent_id) values(8, 3);
insert into "comment"(id, parent_id) values(9, 6);
insert into "comment"(id, parent_id) values(3, 6);

insert into "share"(id, post_id) values(10,2);

insert into "tag"(user_id, content_id) values(3, 1);
insert into "tag"(user_id, content_id) values(5, 7);
insert into "tag"(user_id, content_id) values(2, 1);
insert into "tag"(user_id, content_id) values(8, 10);
insert into "tag"(user_id, content_id) values(7, 5);
insert into "tag"(user_id, content_id) values(1, 1);
insert into "tag"(user_id, content_id) values(6, 3);
insert into "tag"(user_id, content_id) values(9, 3);
insert into "tag"(user_id, content_id) values(2, 5);
insert into "tag"(user_id, content_id) values(10, 10);

insert into "like"(user_id, content_id) values(1, 3);
insert into "like"(user_id, content_id) values(16, 2);
insert into "like"(user_id, content_id) values(7, 7);
insert into "like"(user_id, content_id) values(4, 8);
insert into "like"(user_id, content_id) values(10, 1);
insert into "like"(user_id, content_id) values(1, 7);
insert into "like"(user_id, content_id) values(8, 7);
insert into "like"(user_id, content_id) values(8, 6);
insert into "like"(user_id, content_id) values(13, 4);
insert into "like"(user_id, content_id) values(18, 1);

insert into "friend_request"(requester_id, target_id) values(18, 2);
insert into "friend_request"(requester_id, target_id) values(15, 10);
insert into "friend_request"(requester_id, target_id) values(6, 11);
insert into "friend_request"(requester_id, target_id) values(7, 8);
insert into "friend_request"(requester_id, target_id) values(12, 7);
insert into "friend_request"(requester_id, target_id) values(19, 5);
insert into "friend_request"(requester_id, target_id) values(5, 1);
insert into "friend_request"(requester_id, target_id) values(1, 17);
insert into "friend_request"(requester_id, target_id) values(4, 19);
insert into "friend_request"(requester_id, target_id) values(14, 3);

insert into "group_request"(user_id, group_id, invite) values(1, 3, true);
insert into "group_request"(user_id, group_id, invite) values(3, 6, false);
insert into "group_request"(user_id, group_id, invite) values(5, 1, true);
insert into "group_request"(user_id, group_id, invite) values(7, 2, true);
insert into "group_request"(user_id, group_id, invite) values(9, 7, false);
insert into "group_request"(user_id, group_id, invite) values(11, 9, false);
insert into "group_request"(user_id, group_id, invite) values(13, 10, true);
insert into "group_request"(user_id, group_id, invite) values(15, 3, false);
insert into "group_request"(user_id, group_id, invite) values(17, 4, false);
insert into "group_request"(user_id, group_id, invite) values(19, 5, true);
