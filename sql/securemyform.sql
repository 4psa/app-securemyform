-- Copyright (c) 2011 by 4PSA
-- All rights reserved
-- 
--  Secure My Form database dump
CREATE TABLE `bans` (
  `PhoneNumber` varchar(10) DEFAULT NULL,
  `Time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `calls` (
  `PhoneNumber` varchar(10) NOT NULL DEFAULT '',
  `Time` int NOT NULL DEFAULT '0',
  `AttemptsNumber` int DEFAULT NULL,
  `RandomNumber` int DEFAULT NULL,
  `ApiId` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`PhoneNumber`,`Time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `ips` (
  `Ip` varchar(39) DEFAULT NULL,
  `Time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `phones` (
  `PhoneNumber` varchar(10) DEFAULT NULL,
  `Time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
