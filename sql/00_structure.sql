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
 `LngLoc` VARCHAR( 100 ) ,
 `LatLoc` INT( 10 ) , 
  `price` DOUBLE(8, 2) ,
  `currencyId` INT( 10 ) ,
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
 `isSpeaker` TINYINT( 1 ) ,`isApproved` TINYINT( 1 ),
PRIMARY KEY (  `id` )
) ENGINE = MYISAM DEFAULT CHARSET = UTF8 AUTO_INCREMENT =1;
 


 CREATE TABLE  `Joiner` (
 `Id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
 `JoinerFbUsername` VARCHAR( 255 ) ,
 `JoinerImageUrl` VARCHAR( 255 ) ,
 `Name` VARCHAR( 255 ) ,
 `Qualification` VARCHAR( 255 ) ,  
   `bankName` VARCHAR( 255 ) ,  
   `accName` VARCHAR( 255 )   ,
   `accNo` VARCHAR( 100 )   ,
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



 --13may2016- sean : add country code 
CREATE TABLE  `Country` (
 `Id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
 `shortName` VARCHAR(15 )  ,
 `Name` VARCHAR( 255 )  ,
 `isBanned`  TINYINT( 1 ) ,
PRIMARY KEY (  `id` )
) ENGINE = MYISAM DEFAULT CHARSET = UTF8 AUTO_INCREMENT =1;


 --13may2016- sean : add currency  
CREATE TABLE  `Currency` (
 `Id` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
 `shortName` VARCHAR(15 )  ,`Name` VARCHAR( 255 ) , 
 `countryId` INT( 10 ),
PRIMARY KEY (  `id` )
) ENGINE = MYISAM DEFAULT CHARSET = UTF8 AUTO_INCREMENT =1;

 --13may2016- sean : add price for event   
ALTER TABLE Activity
ADD price DOUBLE(8, 2) ,
ADD countryId INT( 10 )   ,
ADD currencyId INT( 10 )     ;


 --13may2016- sean : add isApproved status for Joining   
ALTER TABLE Joining
ADD isApproved TINYINT( 1 );  


 --13may2016- sean : add  bank name, bnank acc no, bank acc name  for user   Joiner
ALTER TABLE Joiner
ADD  `bankName` VARCHAR( 255 )   ,
ADD  `accName` VARCHAR( 255 )   ,
ADD  `accNo` VARCHAR( 100 )    ;