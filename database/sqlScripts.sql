
DROP database if exists RzWkG5OcpN;

CREATE DATABASE RzWkG5OcpN;

use RzWkG5OcpN;

CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  email VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

CREATE TABLE questions (
  id INT(11) NOT NULL AUTO_INCREMENT,
  question VARCHAR(255) DEFAULT NULL,
  dimension VARCHAR(255) DEFAULT NULL,
  direction INT(11) DEFAULT NULL,
  meaning VARCHAR(255) DEFAULT NULL,
  lowerchar VARCHAR(255) DEFAULT NULL,
  higherchar VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;

CREATE TABLE results (
  id INT(11) NOT NULL AUTO_INCREMENT,
  questionid INT(11) DEFAULT NULL,
  selectedrank INT(11) DEFAULT NULL,
  userid INT(11) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;


CREATE TABLE perspective (
  id INT(11) NOT NULL AUTO_INCREMENT,
  leftname VARCHAR(50) DEFAULT NULL,
  rightname VARCHAR(255) DEFAULT NULL,
  dimension VARCHAR(255) DEFAULT NULL,
  leftchar VARCHAR(255) DEFAULT NULL,
  rightchar VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB;


INSERT INTO RzWkG5OcpN.questions(question, dimension, direction, meaning, lowerchar, higherchar) VALUES
('You find it takes effort to introduce yourself to other people.', 'EI', 1, 'I', 'E', 'I'),
('You consider yourself more practical than creative.', 'SN', -1, 'S', 'N', 'S'),
('Winning a debate matters less to you than making sure no one gets upset.', 'TF', 1, 'F', 'T', 'F'),
('You get energized going to social events that involve many interactions.', 'EI', -1, 'E', 'I', 'E'),
('You often spend time exploring unrealistic and impractical yet intriguing ideas.', 'SN', 1, 'N', 'S', 'N'),
('Deadlines seem to you to be of relative rather than absolute importance.', 'JP', 1, 'P', 'J', 'P'),
('Logic is usually more important than heart when it comes to making important decisions.', 'TF', -1, 'T', 'F', 'T'),
('Your home and work environments are quite tidy.', 'JP', -1, 'J', 'P', 'J'),
('You do not mind being at the center of attention.', 'EI', -1, 'E', 'I', 'E'),
('Keeping your options open is more important than having a to-do list.', 'JP', 1, 'P', 'J', 'p');

INSERT INTO perspective(leftname, rightname, dimension, leftchar, rightchar) VALUES
('Introversion (I)', 'Extraversion (E)', 'EI', 'I', 'E'),
('Sensing (S)', 'Intuition (N)', 'SN', 'S', 'N'),
('Thinking (T)', 'Feeling (F)', 'TF', 'T', 'F'),
('Judging (J)', 'Perceiving (P)', 'JP', 'J', 'P');