INSERT INTO  `seanlohc_NDOH`.`Speaking` (
`Id` ,
`ActivityId` ,
`SpeakerId`
) 
VALUES ( '1',  '1',  '1' );
INSERT INTO  `seanlohc_NDOH`.`Speaking` (
`Id` ,
`ActivityId` ,
`SpeakerId`
)
VALUES ( '2',  '2',  '2' );
INSERT INTO  `seanlohc_NDOH`.`Speaking` (
`Id` ,
`ActivityId` ,
`SpeakerId`
)
VALUES ( '3',  '3',  '3' );

INSERT INTO  `seanlohc_NDOH`.`Speaker` (
`Id` ,
`Name` ,
`Qualification`
)
VALUES ( '1',  '1',  '1' ), ( '2',  '2',  '2' );

INSERT INTO  `seanlohc_NDOH`.`Joining` (
`Id` ,
`ActivityId` ,
`JoinerId`
)
VALUES (
'1',  '1',  '1'
), (
'2',  '2',  '2'
);
INSERT INTO  `seanlohc_NDOH`.`Joiner` (
`Id` ,
`JoinerFbUsername` ,
`JoinerImageUrl`
)
VALUES (
'1',  'aa',  'aaa'
), (
'2',  'bb',  'bb'
);

INSERT INTO  `seanlohc_NDOH`.`ActivityType` (
`Id` ,
`Name`  
)
VALUES (
'1',  'Party'
), (
'2',  'Outdoor'
),(
'3',  'Knowledge Sharing'
),(
'4',  'Sport'
),(
'5',  'Coffee'
) ;

 
 iNSERT INTO  `seanlohc_NDOH`.`Comment` (
`Id` ,
`Title` ,
`Desc` ,
`createdDate` ,
`createdBy` ,
`activityId`
)
VALUES (
'1',  'fun activity!',  'went there and wow', NULL ,  '1',  '1'
), (
'2',  'boring..!',  'went activity and disappointed', NULL ,  '1',  '2'
);


iNSERT INTO  `seanlohc_NDOH`.`Photo` (
`Id` ,
`Url` ,
`Blob` ,
`createdBy` ,
`createdDate` ,
`activityId`
)
VALUES (
'1',  'http://e2ua.com/data/wallpapers/59/WDF_1048495.jpg', NULL ,  '1', NULL ,  '1'
);