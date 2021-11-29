------------------
-- NOTIFICAÇÕES --
------------------

-- After insert a like create a like_notification

create trigger send_like_notification
after insert on "like"
begin
    insert into like_notification(content_id, user_sender_id)
    values (new.content, new.user);
end;


-- After insert on friend_request create a friend_request_notification

create trigger send_friend_request_notification
after insert on friend_request
begin
    insert into friend_request_notification(user_sender_id, user_target_id)
    values (new.requester, new."target");
end;


-- After insert a comment create a comment_notification

create trigger send_comment_notification
after insert on comment
begin
    create view comment_content1 as
    select user_id, content_id
    from user_content;

    create view comment_content2 as
    select user_id, content_id
    from user_content;

    select
        insert into comment_notification(content_id, user_sender_id, user_target_id)
        values (parent_id, comment_content1.user_id, comment_content2.user_id)
    from comment_content1 , comment_content2
    where comment_content1.content_id = content_id and comment_content2.content_id = parent_id

end;


drop trigger if exists delete_user;

-- Make data anonymous on user delete
create trigger delete_user
instead of delete on user
begin
    update user
    set priv_stat = 'Anonymous'
    where id = old.id;
end;

create function update_user_content_priv_stat(user_id integer, priv_stat privacy_status)
