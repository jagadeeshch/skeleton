

CREATE TABLE `posts` (
  `post_id` INT NOT NULL,   			# PK
  `post_date` datetime NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_excerpt` varchar(255) NOT NULL,
  `post_text` text,
  `user_id` varchar(255) NOT NULL,  	# FK
  PRIMARY KEY  (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `comments` (
  `comment_id` INT UNSIGNED NOT NULL,  	# PK
  `post_id` INT UNSIGNED NOT NULL,  	# FK
  `comment_permalink` VARCHAR(255) NOT NULL,
  `comment_author` VARCHAR(255) NOT NULL,
  `comment_authoremail` VARCHAR(255) NOT NULL,
  `comment_authorurl` VARCHAR(255),
  `comment_datetime` datetime NOT NULL,
  `comment_text` text NOT NULL,
  PRIMARY KEY  (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `users` (
  `user_id` INT UNSIGNED NOT NULL,		# PK
  `user_firstname` varchar(255) NOT NULL,
  `user_lastname` varchar(255) NOT NULL,
  `user_username` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


/*
CREATE TABLE `authors` (
  `author_id` INT UNSIGNED NOT NULL,
  `author_firstname` varchar(255) NOT NULL,
  `author_lastname` varchar(255) NOT NULL,
  `author_username` varchar(255) NOT NULL,
  `author_password` varchar(255) NOT NULL,
  `author_email` varchar(255) NOT NULL,
  PRIMARY KEY  (`author_id`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
*/