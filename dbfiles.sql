-- create database 
create database dmclient;

-- use database
use dmclient;

-- create table to store registered users data
create table if not exists regUsers(
	id int auto_increment primary key,
	email nvarchar(250),
	password nvarchar(400),
	confirm_code nvarchar(100),
	messageBal int
);
alter table regusers add column fname nvarchar(30), add column nickname nvarchar(30);
alter table regusers add column about nvarchar(300) default 'Default about me status. Update if you see this.';
alter table regusers add column phone_number nvarchar(30) after email;
-- create table messages
create table if not exists messages(
	id int auto_increment primary key,
    message nvarchar(900)
);
alter table messages add column msg_time datetime default current_timestamp;
alter table messages add column fkuser_id int;
alter table messages add foreign key(fkuser_id) references regusers(id) on delete cascade;
alter table messages add column msg_mode nvarchar(20), add column recipients_no int;
alter table messages add column delete_status int default 0, add column save_status int default 0;

-- create table chat_messages
drop table chat_messages;
create table if not exists chat_messages(
	chat_id int auto_increment primary key,
    my_id int,
    friend_id int,
    chat_time datetime default current_timestamp,
    message nvarchar(900),
    constraint fk_my_id foreign key(my_id) references regusers(id) on delete cascade
);
alter table chat_messages add constraint fk_friend_id foreign key(friend_id) references regusers(id) on delete cascade;

-- create a view left join chat_messages on specified user id
drop view chat_messages_filter;
create view chat_messages_filter as
(
	select * from chat_messages left join messages on chat_messages.my_id=messages.fkuser_id
);

-- create table friends_list
create table if not exists friends_list(
	my_id int,
    friend_id int,
    block_status int default 0,
    friends_since date
);

-- create a view to return friends data per specified id
drop view friends_filter;
create view  friends_filter as
(
	select friends_list.my_id, friends_list.friend_id, friends_list.block_status, friends_list.friends_since ,regusers.email,
    regusers.prof_image, regusers.fname, regusers.nickname, regusers.about from friends_list 
    left join regusers on friends_list.friend_id=regusers.id
);

-- create table contact_addresses
drop table contact_addresses;
create table if not exists contact_addresses(
	id int auto_increment,
    fname nvarchar(20),
    sname nvarchar(20),
    phone nvarchar(30),
    email nvarchar(60),
    added_by int,
    primary key(id)
    -- constraint fk_added_by foreign key(added_by) references regusers(id)
);
-- alter table contact_addresses drop foreign key fk_added_by;
alter table contact_addresses add constraint fk_added_by foreign key(added_by) references regusers(id) on delete cascade;

-- create table bs_categories
create table if not exists bs_categories(
	id int auto_increment,
    cat_name nvarchar(60),
    primary key(id)
);
-- create table bs_skills
create table if not exists bs_skills(
	id int auto_increment,
    skill_name nvarchar(80),
    primary key(id)
);
-- create table for user_bs_skills
create table if not exists user_bs_skills(
	user_id int,
    bs_skill_id int,
    constraint fk_user_id foreign key(user_id) references regusers(id) on delete cascade,
    constraint fk_bs_skill_id foreign key(bs_skill_id) references bs_skills(id) on delete cascade
);

-- create table for user_bs_info
drop table user_bs_info;
create table if not exists user_bs_info(
	owner_id int,
    bs_cat_id int,
    bs_name nvarchar(50),
    bs_logo blob,
    bs_description nvarchar(300),
    bs_wrk_time nvarchar(30),
    bs_address nvarchar(30),
    bs_contact_mail nvarchar(40),
    bs_contact_phone nvarchar(40),
    bs_map_address nvarchar(200),
    constraint fk_owner_id foreign key(owner_id) references regusers(id) on delete cascade,
    constraint fk_bs_cat_id foreign key(bs_cat_id) references bs_categories(id) on delete cascade
);

-- create table to hold post feeds
drop table post_feeds;
create table if not exists post_feeds(
	post_id int auto_increment primary key,
    user_id int,
    post_text nvarchar(900),
    post_image blob,
    post_time datetime default current_timestamp,
    constraint fk_client_id foreign key(user_id) references regusers(id) on delete cascade
);

-- create table call_handler
create table if not exists call_handler(
	call_id int auto_increment,
    initiator_id int,
    initiator_key nvarchar(50),
    receiver_id int,
    receiver_key nvarchar(50),
    call_status int,
    call_start_time datetime default current_timestamp,
    call_end_time datetime,
    call_duration nvarchar(8),
    primary key(call_id),
    constraint fk_init_id foreign key(initiator_id) references regusers(id) on delete cascade
);

-- create table post_u_reactions
create table if not exists post_u_reactions(
	user_iden int,
    post_iden int,
    constraint fk_user_iden foreign key(user_iden) references regusers(id),
    constraint fk_post_iden foreign key(post_iden) references post_feeds(post_id)
);














