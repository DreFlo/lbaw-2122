/*
Insert post

A transaction is used in order to mantain data integrity in the presence of simultaneous accesses.
The isolation level is Repeatable Read since a concurrent may insert a new value into the user_content table,
causing inconsistent data to be stored. 
*/
BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

INSERT INTO user_content("text", "timestamp", creator_id, edited, group_id, pinned, priv_stat)
VALUES ($text, $timestamp, $creator_id, $edited, $group_id, $pinned, $priv_stat) RETURNING id as user_content_id;

INSERT INTO post(id, pic_1, pic_2, pic_3, pic_4, pic_5)
VALUES ($user_content_id, $pic_1, $pic_2, $pic_3, $pic_4, $pic_5);

COMMIT;


/*
Insert comment

A transaction is used in order to mantain data integrity in the presence of simultaneous accesses.
The isolation level is Repeatable Read since a concurrent may insert a new value into the user_content table,
causing inconsistent data to be stored.
*/
BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

INSERT INTO user_content(id, "text", "timestamp", creator_id, edited, group_id, pinned, priv_stat)
VALUES ($text, $timestamp, $creator_id, $edited, $group_id, $pinned, $priv_stat) RETURNING id as user_content_id;

INSERT INTO comment(id, parent_id)
VALUES ($user_content_id, $parent_id);

COMMIT;

/*
Insert share

A transaction is used in order to mantain data integrity in the presence of simultaneous accesses.
The isolation level is Repeatable Read since a concurrent may insert a new value into the user_content table,
causing inconsistent data to be stored.
*/
BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL REPEATABLE READ;

INSERT INTO user_content(id, "text", "timestamp", creator_id, edited, group_id, pinned, priv_stat)
VALUES ($text, $timestamp, $creator_id, $edited, $group_id, $pinned, $priv_stat) RETURNING id as user_content_id;

INSERT INTO share(id, post_id)
VALUES ($user_content_id, $post_id);

COMMIT;


/*
Search for content by it's text

If a search results ends up being deleted by another transaction running simultaniously, then errors might occur during the search of the deleted items
or the user might be presented with Phantom Content. The isolation level is SERIALIZABLE READ ONLY because it only uses SELECT's.
*/
BEGIN TRANSACTION;
SET TRANSACTION ISOLATION LEVEL SERIALIZABLE READ ONLY;

SELECT (SELECT "name"
		FROM "user"
		WHERE "user".id = "user_content".creator_id) AS Creator, 
		text AS "Content/Group Name"
FROM (SELECT id, ts_rank(setweight(to_tsvector('english', user_content."text"), 'A'), to_tsquery('english', $search)) AS rank
	  FROM user_content
	  WHERE to_tsvector('english', user_content.text) @@ to_tsquery('english', $search) 
              AND priv_stat = 'Public'
	  GROUP BY id, creator_id
	  ORDER BY rank DESC) AS results INNER JOIN "user_content" 
              ON (results.id = "user_content".id)

UNION
			  
	  
SELECT (SELECT "name"
		FROM "user"
		WHERE "user".id = "group".creator_id) AS Creator, 
		"name" AS "Content/Group Name"
FROM (SELECT id, ts_rank(setweight(to_tsvector('english', "group"."name"), 'B'), to_tsquery('english', $search)) AS rank
	  FROM "group"
	  WHERE to_tsvector('english', "group"."name") @@ to_tsquery('english', $search) 
              AND priv_stat = 'Public'
	  GROUP BY id
	  ORDER BY rank DESC) AS results INNER JOIN "group" 
              ON (results.id = "group".id);

COMMIT;
