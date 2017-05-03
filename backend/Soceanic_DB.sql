CREATE TABLE Users (
    username varchar(20) NOT NULL,
    first_name varchar(30) NOT NULL,
    last_name varchar(30) NOT NULL,
    email varchar(50) NOT NULL,
    password varchar(255) NOT NULL,
    profile_pic varchar(50) NULL,
    bg_pic varchar(50) NULL,
    bio varchar(500) NULL,
    birthday varchar(10) NOT NULL,
    verified smallint NOT NULL,
    last_login date NOT NULL,
    date_joined date NOT NULL,
    last_updated date NOT NULL,
    profile_views bigint NOT NULL,
    CONSTRAINT pk_Users PRIMARY KEY (
        username
    )
);

CREATE TABLE Relationships (
    username_1 varchar(20) NOT NULL,
    username_2 varchar(20) NOT NULL,
    status smallint NOT NULL,
    date_sent date NOT NULL,
    last_updated date NOT NULL,
    group_id_1 int NOT NULL,
    group_id_2 int NOT NULL,
    CONSTRAINT pk_Relationships PRIMARY KEY (
        username_1,username_2
    )
);

CREATE TABLE Groups (
    group_id int NOT NULL AUTO_INCREMENT,
    username varchar(20) NOT NULL,
    group_name varchar(30) NOT NULL,
    priority smallint NOT NULL,
    date_created date NOT NULL,
    last_updated date NOT NULL,
    CONSTRAINT pk_Groups PRIMARY KEY (
        group_id
    )
);

CREATE TABLE Posts (
    post_id int NOT NULL AUTO_INCREMENT,
    username varchar(20) NOT NULL,
    title varchar(30) NULL,
    text varchar(5000) NULL,
    attachment varchar(50) NULL,
    likes bigint NOT NULL,
    date_created date NOT NULL,
    last_updated date NOT NULL,
    CONSTRAINT pk_Posts PRIMARY KEY (
        post_id
    )
);

CREATE TABLE Comments (
    comment_id int NOT NULL AUTO_INCREMENT,
    post_id int NOT NULL,
    username varchar(20) NOT NULL,
    text varchar(500) NOT NULL,
    likes bigint NOT NULL,
    date_created date NOT NULL,
    last_updated date NOT NULL,
    CONSTRAINT pk_Comments PRIMARY KEY (
        comment_id
    )
);

CREATE TABLE Box_Saves (
    username varchar(20) NOT NULL,
    post_id int NOT NULL,
    post_creator varchar(20) NOT NULL,
    date_added date NOT NULL,
    date_removed date NULL,
    CONSTRAINT pk_Box_Saves PRIMARY KEY (
        username,post_id
    )
);

CREATE TABLE Votes (
    post_id int NOT NULL,
    username varchar(20) NOT NULL,
    upvote smallint DEFAULT 0,
    downvote smallint DEFAULT 0,
    date_created date NOT NULL,
    last_updated date NOT NULL,
    CONSTRAINT pk_Likes PRIMARY KEY (
        post_id, username
    )
);

ALTER TABLE Relationships ADD CONSTRAINT fk_Relationships_username_1 FOREIGN KEY(username_1)
REFERENCES Users (username);

ALTER TABLE Relationships ADD CONSTRAINT fk_Relationships_username_2 FOREIGN KEY(username_2)
REFERENCES Users (username);

ALTER TABLE Groups ADD CONSTRAINT fk_Groups_username FOREIGN KEY(username)
REFERENCES Users (username);

ALTER TABLE Posts ADD CONSTRAINT fk_Posts_username FOREIGN KEY(username)
REFERENCES Users (username);

ALTER TABLE Comments ADD CONSTRAINT fk_Comments_post_id FOREIGN KEY(post_id)
REFERENCES Posts (post_id);

ALTER TABLE Comments ADD CONSTRAINT fk_Comments_username FOREIGN KEY(username)
REFERENCES Users (username);

ALTER TABLE Box_Saves ADD CONSTRAINT fk_Box_Saves_username FOREIGN KEY(username)
REFERENCES Users (username);

ALTER TABLE Box_Saves ADD CONSTRAINT fk_Box_Saves_post_id FOREIGN KEY(post_id)
REFERENCES Posts (post_id);

ALTER TABLE Box_Saves ADD CONSTRAINT fk_Box_Saves_post_creator FOREIGN KEY(post_creator)
REFERENCES Users (username);
