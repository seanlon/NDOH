CREATE TABLE  `Activity` (
 `Id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
 `Name` VARCHAR( 255 ) ,
 `Description` VARCHAR( 255 ) ,
 `isPrivate` TINYINT( 1 ) ,
 `joinersLimit` BIGINT( 100 ) ,
 `ActivityType` BIGINT( 100 ) ,
 `FromPeriod` DATETIME( 3 ) ,
 `ToPeriod` DATETIME( 3 ) ,
 `Address` VARCHAR( 255 ) ,
 `LatLoc` VARCHAR( 100 ) ,
 `LngLoc` VARCHAR( 100 ) ,
 `LatLoc` INT( 10 ) ,
  `createdBy` VARCHAR( 100 ) ,   
  `createdDate`  DATETIME( 3 )   , 
PRIMARY KEY (  `id` )
) ENGINE = MYISAM DEFAULT CHARSET = UTF8 AUTO_INCREMENT =1;
 
 
CREATE TABLE  `ActivityType` (
 `Id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
 `Name` VARCHAR( 255 )  ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM DEFAULT CHARSET = UTF8 AUTO_INCREMENT =1;

 CREATE TABLE  `Joining` (
 `Id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
 `ActivityId` INT( 10 )  ,
 `JoinerId` INT( 10 )  , 
 `isSpeaker` TINYINT( 1 ) ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM DEFAULT CHARSET = UTF8 AUTO_INCREMENT =1;
 


 CREATE TABLE  `Joiner` (
 `Id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
 `JoinerFbUsername` VARCHAR( 255 ) ,
 `JoinerImageUrl` VARCHAR( 255 ) ,
 `Name` VARCHAR( 255 ) ,
 `Qualification` VARCHAR( 255 ) , 
PRIMARY KEY (  `id` )
) ENGINE = MYISAM DEFAULT CHARSET = UTF8 AUTO_INCREMENT =1;
 
 
CREATE TABLE  `Photo` (
`Id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
`Url` VARCHAR( 255 ) ,
`Blob` BLOB,  
`createdBy` VARCHAR( 100 ) ,   
`createdDate`  DATETIME( 3 )   ,
`activityId` INT( 10 ), 
PRIMARY KEY (  `id` )
) ENGINE = MYISAM DEFAULT CHARSET = UTF8 AUTO_INCREMENT =1;
 

CREATE TABLE  `Comment` (
`Id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
`Title` VARCHAR( 255 ) ,
`Desc` VARCHAR( 255 )  , 
`createdBy` VARCHAR( 100 ) ,   
`createdDate`  DATETIME( 3 )   ,
`activityId` INT( 10 ), 
PRIMARY KEY (  `id` )
) ENGINE = MYISAM DEFAULT CHARSET = UTF8 AUTO_INCREMENT =1;

  
ALTER TABLE Joining
ADD isSpeaker TINYINT( 1 );  

ALTER TABLE Joiner
ADD Name VARCHAR( 255 ) ; 
ADD Qualification VARCHAR( 255 ) ; 


ALTER TABLE Joining
ADD isSpeaker TINYINT( 1 );  

ALTER TABLE Photo
ADD  `createdBy` VARCHAR( 100 )     
ADD `createdDate`  DATETIME( 3 ) 
ADD `activityId` INT( 10 )    ; 


ALTER TABLE Comment
ADD  `createdBy` VARCHAR( 100 )  
ADD `createdDate`  DATETIME( 3 )
ADD `activityId` INT( 10 )      ; 
 

ALTER TABLE Activity
ADD LatLoc VARCHAR( 100 )   
ADD LngLoc VARCHAR( 100 )   
ADD createdBy VARCHAR( 100 )    
ADD createdDate  DATETIME( 3 )     



-- CREATE TABLE  `Speaker` (
--  `Id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
--  `Name` VARCHAR( 255 ) ,
--  `Qualification` VARCHAR( 255 ) , 
-- PRIMARY KEY (  `id` )
-- ) ENGINE = MYISAM DEFAULT CHARSET = UTF8 AUTO_INCREMENT =1;

-- CREATE TABLE  `Speaking` (
--  `Id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
--  `ActivityId` INT( 10 )  ,
--  `SpeakerId` INT( 10 )  , 
-- PRIMARY KEY (  `id` )
-- ) ENGINE = MYISAM DEFAULT CHARSET = UTF8 AUTO_INCREMENT =1;

