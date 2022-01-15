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
drop table if exists "password_resets" cascade;

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

create table "password_resets"
(
    email text not null,
    token text not null,
    "created_at" timestamptz not null default now()
);



create table "user"
(
    id serial,
    "name" text not null,
    birthdate date not null check (age(birthdate) >= '13 years'),
    email text not null unique,
    "password" text not null,
    admin_flag boolean not null default 'false',
    profile_pic integer default 3,
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
    cover_pic integer default 4,
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

create index "user_name_index" on "user" using gist (setweight(to_tsvector('english', "name"), 'A'));

create index "group_name_index" on "group" using gist (setweight(to_tsvector('english', "name"), 'C'));

create index "user_content_text" on user_content using gist (setweight(to_tsvector('english', "text"), 'B'));

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
            insert into friendship(user_2, user_1) values(new.requester_id, new.target_id);
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


insert into "image"(id, path) values (1, 'storage/images/Wheel-of-Time-MyrddraalTeaser.webp');
insert into "image"(id, path) values (2, 'storage/images/tumblr_nqza7zgUZc1tqgexdo1_1280.jpg');
insert into "image"(id, path) values (4, 'storage/images/coverpic.png');
insert into "image"(id, alt, path) values (3, 'Default profile picture','storage/images/blank-profile-picture.png');
SELECT setval('image_id_seq', max(id)) FROM "image";

insert into "user"(id, "name", birthdate, email, "password", priv_stat, admin_flag) values(1, 'Andre', '2001-03-15', 'lbaw2192@gmail.com', '$2a$12$rHC7sIE90OGsGyI9KWiNx.DCMHS2X6Z/hiHeH9D/Bi.zy6xDHoYbS', 'Private', true); /*pass*/
insert into "user"(id, "name", birthdate, email, "password", priv_stat) values(2, 'Tiago', '2001-05-11', 't@t.t', '$2a$12$rHC7sIE90OGsGyI9KWiNx.DCMHS2X6Z/hiHeH9D/Bi.zy6xDHoYbS', 'Private'); /*pass*/
insert into "user"(id, "name", birthdate, email, "password", priv_stat) values(3, 'Diogo', '2001-11-30', 'd@d.d', '$2a$12$rHC7sIE90OGsGyI9KWiNx.DCMHS2X6Z/hiHeH9D/Bi.zy6xDHoYbS', 'Private'); /*pass*/
insert into "user"(id, "name", birthdate, email, "password") values(4, 'Mendes', '1998-10-21', 'm@m.m', '$2a$12$rHC7sIE90OGsGyI9KWiNx.DCMHS2X6Z/hiHeH9D/Bi.zy6xDHoYbS'); /*pass*/
insert into "user"(id, "name", birthdate, email, "password") values(5, 'Ana', '2000-02-23', 'ana@a.a', '$2a$12$UUB35/ttUXOVxZwYtTLsQ.jVvKji4I4ueL3qE1w/pn7AUWqxd/PHu'); /*1234567*/
insert into "user"(id, "name", birthdate, email, "password") values(6, 'Miguel', '1997-01-01', 'miguel@mig.m', '$2a$12$d0x8OyoGxUcrS6Vu6NJ8ROlpTxTdQFjdl2JISn0sCw8pQiBSrYy6m'); /*passe*/
insert into "user"(id, "name", birthdate, email, "password") values(7, 'Joao Diogo', '2003-07-05', 'j@j.j', '$2a$12$.SYGqHyG8gs0UNeUJbwhwe/EFKYRJRDhlye.bAQhTTQNOM6DplM.m'); /*passsss*/
insert into "user"(id, "name", birthdate, email, "password") values(8, 'Pedro', '1999-09-19', 'p@p.p', '$2a$12$iHNjhHEkBlfm9q6oSJ/WEOg1mNt9KgH4fGJzrqpFYg5xBdV9yNx/m'); /*palavrapasse*/
insert into "user"(id, "name", birthdate, email, "password") values(9, 'Maria', '1998-12-23', 'maria@m.ma', '$2a$12$oNerZH7Pa0EcvtCQNgVW.uiqftZKBZmcrfTf/7EqyX8P06cDjg6Ni'); /*sim123*/
insert into "user"(id, "name", birthdate, email, "password") values(10, 'Carlos', '2000-04-01', 'car@car.car', '$2a$12$Xeogp.buV4E5RmhaKBydNuz3SaO96ggcX.PMfOBD4QjEozu.po6Ou'); /*teste*/
insert into "user"(id, "name", birthdate, email, "password") values(11, 'Sara', '1999-03-20', 's@s.s', '$2a$12$MZyAPxz48a848MFcEqYyNu0lx7wfvVAk/ufK4MWckHWgDkoTUp7YO'); /*teste123*/
insert into "user"(id, "name", birthdate, email, "password") values(12, 'Manuel', '2002-01-13', 'man@man.m', '$2a$12$kBSIE.4JMFQIA5Z9wMIz5.PIVvUk4md0AGegbsS31EPFAdAEPCaYe'); /*exemplo*/
insert into "user"(id, "name", birthdate, email, "password") values(13, 'Sofia', '1996-08-11', 'sof@sof.s', '$2a$12$aSi6rZsuJydROwxSd98Lx.9bk/YTJb27n18VFg.V29NgnyJotgPB.'); /*example*/
insert into "user"(id, "name", birthdate, email, "password") values(14, 'Fernando', '1997-03-07', 'f@f.f', '$2a$12$ae3kN3.se5oC4M4MYbfbmOhMQeELXhzk5dBkkaSYtyvF0ArWgRdbe'); /*yup123*/
insert into "user"(id, "name", birthdate, email, "password") values(15, 'Ariana', '2000-09-22', 'ari@ari.ari', '$2a$12$mrTndZiBN33dOJrMZ0LxI.78mnZds3tqkKM3PiZjgCzGY3k.pO19O'); /*naosei*/
insert into "user"(id, "name", birthdate, email, "password") values(16, 'Julio', '1995-10-31', 'jul@jul.j', '$2a$12$DvFtkjVAF6dnj9vuY0cj7.OBEmoGqGMITQElXVsCJba8aQ9uMA9iC'); /*esqueci_me*/
insert into "user"(id, "name", birthdate, email, "password") values(17, 'Paula', '2000-06-25', 'pa@pa.pa', '$2a$12$My0JbNsFiBVJu/YPVsz9/OLVaMP1sbYXiAGxiN..DFuk8HHM0GOSq'); /*strongpass*/
insert into "user"(id, "name", birthdate, email, "password") values(18, 'Marco', '1998-03-18', 'mar@mar.mar', '$2a$12$Nep.hmPjNmcuU6ahc2IC4O95eDma74K7oX4WZH66IRw/dpfXfRXNa'); /*lockedin*/
insert into "user"(id, "name", birthdate, email, "password") values(19, 'Alice', '1999-09-23', 'ali@ali.a', '$2a$12$gpGye1OmY.fPnxx5qco2YuipcuXbE5Ao.T13oqC52f7831SqrunSK'); /*123exemplo*/
insert into "user"(id, "name", birthdate, email, "password") values(20, 'Rui', '1999-09-19', 'r@ru.rui', '$2a$12$vAiztVROfNs4OdL8Vv/uLeUQxLNnos0cI13w76C2MFHaFmicxrs5q'); /*123321*/
insert into "user"(id, "name", birthdate, email, "password") values(21, 'Carolina', '2002-10-04', 'carol@c.car', '$2a$12$hvw.bXjDkYAWYHdt7WXAGem8dABqK3aaTHaz/2MTjT3kiqFf1LIgW'); /*numeros*/

SELECT setval('user_id_seq', max(id)) FROM "user";

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

insert into "group"(id, "name", creator_id, cover_pic) values(1, 'FEUP', 1, 2);
insert into "group"(id, "name", creator_id) values(2, 'TechNerds', 1);
insert into "group"(id, "name", creator_id) values(3, 'HarryPotter Fans', 2);
insert into "group"(id, "name", creator_id, priv_stat) values(4, 'Book Geeks', 3, 'Private');
insert into "group"(id, "name", creator_id, priv_stat) values(5, 'Gaming Community', 3, 'Private');
insert into "group"(id, "name", creator_id) values(6, 'Influencers', 4);
insert into "group"(id, "name", creator_id) values(7, 'FoodLovers', 4);
insert into "group"(id, "name", creator_id) values(8, '_ShareMusic_', 4);
insert into "group"(id, "name", creator_id) values(9, 'MathGeniuses', 3);
insert into "group"(id, "name", creator_id) values(10, 'AnimeAddicted', 1);

SELECT setval('group_id_seq', max(id)) FROM "group";

insert into "membership"(user_id, group_id) values(2,1);
insert into "membership"(user_id, group_id) values(3,1);
insert into "membership"(user_id, group_id) values(4,1);
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
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(12, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque ultricies molestie ligula nec dignissim. Sed dignissim sapien sit amet lectus interdum vestibulum. Cras placerat et sapien in vulputate. Aliquam orci dolor, euismod ut volutpat id, efficitur eget magna. Morbi at lobortis nisl. Donec gravida tempor bibendum. Sed a convallis lacus. Sed ac tempus mauris. Suspendisse laoreet nulla sit amet ex maximus, eget tempor nunc feugiat. Maecenas et ligula id ante bibendum dapibus. Ut rhoncus gravida libero, venenatis ullamcorper sapien semper sed. Nullam efficitur dolor sed cursus sagittis. Cras a tellus eu nibh eleifend tempor.', 7, null, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(13, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla massa risus, congue non molestie vitae, interdum a lorem. Aliquam fringilla ex a felis pretium ornare. Proin in rhoncus mi. Etiam eget diam ac urna viverra malesuada eget non ligula. Nulla dignissim scelerisque cursus. Donec at dui sit amet sapien consectetur scelerisque. Etiam eu sem odio. Fusce velit ipsum, pellentesque sit amet ullamcorper id, bibendum non sapien. Mauris consectetur augue quis vehicula faucibus.', 4, null, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(26, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean pulvinar varius erat, sit amet porttitor urna. Aliquam erat volutpat. Nulla dignissim libero massa, vitae ornare sem tincidunt tincidunt. Nullam feugiat, orci non cursus placerat, dui orci tincidunt diam, eu fermentum tortor velit vitae quam. Aenean et tellus posuere, tincidunt turpis vel, euismod dolor. Phasellus eu dui et ex scelerisque pharetra. Phasellus dignissim dapibus lacus ac vulputate. Donec interdum nulla quam, vitae scelerisque ligula feugiat quis. Suspendisse aliquet vitae dui et tempor. Ut non sodales augue. Maecenas tincidunt consectetur tellus aliquam bibendum.', 2, null, 'Private');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(15, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean maximus mauris dolor, nec scelerisque nibh mollis in. Etiam at dui id velit eleifend dignissim fringilla id nisi. Quisque vel molestie quam, vel placerat ante. Nam rhoncus sagittis ligula, in pulvinar elit accumsan ac. Vestibulum at ipsum euismod, aliquam tellus at, placerat nibh. Nulla tempor ullamcorper volutpat. Nam elit nulla, euismod nec nibh vel, ornare volutpat nibh. Duis dapibus nunc eleifend mauris pulvinar, condimentum malesuada erat mattis. Donec a cursus massa, a mattis ipsum. In hac habitasse platea dictumst. Cras sodales, dui sed sodales varius, nunc nulla laoreet ipsum, in tempus ipsum ipsum in ante. Vivamus et libero vitae metus gravida scelerisque. Donec tristique lobortis lacus a sagittis. Vivamus molestie orci at diam tristique tincidunt. Integer vel libero eget massa pharetra tempor. Sed ac facilisis purus.', 8, null, 'Private');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(16, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque feugiat velit nec sapien porttitor condimentum. Nulla faucibus pharetra est, quis consequat lorem aliquet a. Phasellus a vulputate felis. In pellentesque lacus sed nulla pharetra molestie. Etiam sit amet suscipit magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer gravida leo sed erat pellentesque porttitor. Maecenas at interdum leo. Suspendisse venenatis urna ut condimentum imperdiet. Vestibulum neque lectus, viverra eu vehicula eget, placerat ut mi.', 16, null, 'Private');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(17, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus eu pellentesque turpis, eget pharetra diam. Suspendisse ut neque eu dolor dignissim venenatis. Donec volutpat tincidunt nulla nec eleifend. Mauris rhoncus neque eu ante aliquet rutrum. Vestibulum eleifend placerat velit at scelerisque. In felis mauris, accumsan eget nulla sit amet, rutrum tincidunt magna. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Nunc non pretium ante. Donec diam orci, condimentum faucibus malesuada at, commodo in lectus. Praesent congue dolor ac ex finibus, id molestie orci gravida. Nullam quis magna sodales, dapibus ipsum et, suscipit mi. Proin consectetur, justo ac sodales euismod, purus massa aliquam massa, eu dapibus ligula est vitae lacus. Integer magna enim, facilisis non laoreet aliquet, convallis nec felis.', 3, null, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(18, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed et congue purus, tincidunt varius magna. Curabitur venenatis at turpis ac fermentum.', 3, null, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(19, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent nisi ante, tempor convallis molestie sit amet, luctus quis turpis. Sed sit amet sagittis enim.', 5, null, 'Private');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(20, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a turpis posuere libero rutrum mollis ut nec quam. Vivamus eget consequat erat, ac aliquam leo.', 2, null, 'Private');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(21, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed ullamcorper nulla, sit amet convallis mauris. Praesent at sem lobortis, tincidunt nibh ut, iaculis urna.', 8, null, 'Private');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(22, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque lacinia aliquet fermentum. Aliquam vel porttitor nibh. Pellentesque cursus lobortis interdum.', 4, null, 'Public');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(23, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum cursus, tortor at ornare sagittis, mauris justo rhoncus elit, at malesuada lorem arcu id arcu.', 6, null, 'Private');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(24, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer rutrum enim feugiat enim venenatis tempus. Pellentesque tempor magna sit amet viverra convallis.', 7, null, 'Private');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(25, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vel quam nec magna pharetra laoreet vitae ac nisi. Phasellus ut sapien et nisl tincidunt fringilla condimentum non ex.', 10, null, 'Private');
insert into "user_content"(id, "text", creator_id, group_id, priv_stat) values(14, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sem elit, sodales ut felis vel, eleifend ultricies felis. Donec imperdiet efficitur est, non laoreet diam iaculis nec. Integer at consequat nulla. Nullam nec ultricies tellus, eleifend dignissim lectus. Nullam tincidunt ornare odio vel scelerisque. Mauris quis tellus arcu. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In interdum, justo vitae ultrices tristique, felis nisl interdum enim, pellentesque luctus mauris quam sit amet ex. Sed eget rhoncus tortor.', 5, null, 'Private');


SELECT setval('user_content_id_seq', max(id)) FROM "user_content";

insert into "post"(id) values(1);
insert into "post"(id, pic_1, pic_2) values(2, 1, 2);
insert into "post"(id) values(3);
insert into "post"(id) values(4);
insert into "post"(id) values(5);
insert into "post"(id) values(12);
insert into "post"(id) values(13);
insert into "post"(id) values(14);
insert into "post"(id) values(15);
insert into "post"(id) values(16);
insert into "post"(id) values(26);

insert into "comment"(id, parent_id) values(6, 2);
insert into "comment"(id, parent_id) values(8, 3);
insert into "comment"(id, parent_id) values(9, 6);
insert into "comment"(id, parent_id) values(3, 6);
insert into "comment"(id, parent_id) values(17, 12);
insert into "comment"(id, parent_id) values(18, 13);
insert into "comment"(id, parent_id) values(19, 14);
insert into "comment"(id, parent_id) values(20, 15);
insert into "comment"(id, parent_id) values(21, 16);
insert into "comment"(id, parent_id) values(22, 12);

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
