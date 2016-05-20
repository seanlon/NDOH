<?php

function connectDB()
{ 
    $db = connect_db();
    return $db;
}

function crudDB($statement)
{
    $db = connectDB();
    if ($db->query($statement) === TRUE) {
        return $db;
    } else {
        // echo " Error: " . $sql . "<br>" . $conn->error;
        return false;
    }
    
}
function queryDB($statement)
{
    $db   = connectDB();
    $rs   = $db->query($statement);
    $data = $rs->fetch_all(MYSQLI_ASSOC);
    return $data;
}
//Safety get value
function getKeyVal($objArr, $keyId)
{
    if (!is_null($objArr) && array_key_exists($keyId, $objArr)) {
        return $objArr->$keyId;
    } else {
        return "";
    }
    
}
function getJsonRequest($app)
{
    return json_decode($app->request->getBody());
}
function getJsonResponse($app, $jsonData)
{
    $jsonType = "application/json;charset=utf-8";
      header("Content-Type:  " . $jsonType);  
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Headers: X-Requested-With');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    header('Access-Control-Max-Age: 86400');
    $response                 = $app->response();
    $response['Content-Type'] = $jsonType;
    
    $response->headers->set('Content-Type', $jsonType);
    $response->body(json_encode($jsonData, JSON_UNESCAPED_UNICODE));
    //alternate / basic way return json
    //echo json_encode($jsonData)
    //WILL CAUSE 404 -NOT FOUND
    //exit;
}

function getSpeakingListing($app)
{
    $reqParam = getJsonRequest($app);
    
    $sqlStatement   = 'SELECT Id  ,
						ActivityId  , 
						SpeakerId 
						  FROM Speaking ';
    $whereStatement = " where 1=1 ";
    
    
    $filterSpeakerId = getKeyVal($reqParam, "speakerId");
    if (!empty($filterSpeakerId)) {
        $whereStatement = $whereStatement . " and SpeakerId like '%" . $filterSpeakerId . "%'";
    }
    
    $filterActivityId = getKeyVal($reqParam, "activityId");
    if (!empty($filterActivityId)) {
        $whereStatement = $whereStatement . " and ActivityId like '%" . $filterActivityId . "%'";
    }
    
    
    $sqlStatement = $sqlStatement . $whereStatement . " ; ";
    $data         = queryDB($sqlStatement);
    getJsonResponse($app, $data);
}

function getSpeakerListing($app)
{
    $reqParam = getJsonRequest($app);
    
    $sqlStatement   = 'SELECT Id  , 	Name  ,  Qualification  FROM Speaker ';
    $whereStatement = " where 1=1 ";
    
    
    $filterQualification = getKeyVal($reqParam, "qualification");
    if (!empty($filterQualification)) {
        $whereStatement = $whereStatement . " and Qualification like '%" . $filterQualification . "%'";
    }
    
    $filterName = getKeyVal($reqParam, "name");
    if (!empty($filterName)) {
        $whereStatement = $whereStatement . " and name like '%" . $filterName . "%'";
    }
    
    
    $sqlStatement = $sqlStatement . $whereStatement . " ; ";
    $data         = queryDB($sqlStatement);
    getJsonResponse($app, $data);
    // getJsonResponse( $app,"321" );   
}

function getMonth()
{
    //eg.2016-04-11
    $year         = date('Y');
    $month["JAN"] = $year . "-01-01|" . $year . "-02-01";
    $month["FEB"] = $year . "-02-01|" . $year . "-03-01";
    $month["MAR"] = $year . "-03-01|" . $year . "-04-01";
    $month["APR"] = $year . "-04-01|" . $year . "-05-01";
    $month["MAY"] = $year . "-05-01|" . $year . "-06-01";
    $month["JUN"] = $year . "-06-01|" . $year . "-07-01";
    $month["JUL"] = $year . "-07-01|" . $year . "-08-01";
    $month["AUG"] = $year . "-08-01|" . $year . "-09-01";
    $month["SEP"] = $year . "-09-01|" . $year . "-10-01";
    $month["OCT"] = $year . "-10-01|" . $year . "-11-01";
    $month["NOV"] = $year . "-11-01|" . $year . "-12-01";
    $month["DEC"] = $year . "-12-01|" . ($year + 1) . "-01-01";
    
    return $month;
}

function getPhotoByActivity($activityId)
{
    
    $sqlStatement   = 'SELECT photo.Id  , photo.url  , 
		   photo.Blob , photo.createdBy,photo.createdDate,
		   photo.activityId  ,
		   user.JoinerFbUsername,user.JoinerImageUrl,
            user.bankName as BankName,
            user.accName as AccName,
            user.accNo as AccNo,
            user.email as email,
            user.mobile as mobile,
		   user.Name,user.Qualification  FROM Photo photo, Joiner user ';
    $whereStatement = " where 1=1  and  photo.createdBy= user.Id ";
    
    
    if (!empty($activityId)) {
        $whereStatement = $whereStatement . " and activityId = '" . $activityId . "'";
    }
    
    $sqlStatement = $sqlStatement . $whereStatement . " ; ";
    $data         = queryDB($sqlStatement);
    return $data;
}
function getCommentByActivity($activityId)
{
    
    $sqlStatement   = 'SELECT com.Id  , com.Title  , 
		   com.Desc , com.createdBy,com.createdDate,
		   com.activityId ,
		   user.JoinerFbUsername,user.JoinerImageUrl,
            user.bankName as BankName,
            user.accName as AccName,
            user.accNo as AccNo,
            user.email as email,
            user.mobile as mobile,
		   user.Name,user.Qualification   FROM Comment com, Joiner user ';
    $whereStatement = " where 1=1  and  com.createdBy= user.Id ";
    
    
    if (!empty($activityId)) {
        $whereStatement = $whereStatement . " and activityId = '" . $activityId . "'";
    }
    
    $sqlStatement = $sqlStatement . $whereStatement . " ; ";
    $data         = queryDB($sqlStatement);
    return $data;
}
function getActivityListing($app)
{
    $reqParam = getJsonRequest($app);
    
    $sqlStatement = 'SELECT a.Id  ,
				a.Name  ,
				a.Description ,
				a.isPrivate ,
				a.joinersLimit  ,
				a.FromPeriod,a.price, 
                a.currencyId, cr.name as currencyName, cr.shortName as currencyShortName, cr.countryId as currencyCountryId,
                a.countryId,c.shortName as countryShortName,c.name as countryName, c.isBanned as countryIsBanned, 
				a.ToPeriod,
				a.ActivityType as activityId ,  t.name as activityDesc ,   
				a.Address  ,
				a.LatLoc ,
				a.LngLoc ,
				a.createdDate  ,

				a.createdBy as userCreatedId,
				user.JoinerFbUsername as userCreatedFbName  , 
				user.JoinerImageUrl  as userCreatedImageUrl,
                user.Name  as userCreatedName,  
                user.bankName as BankName,
                user.accName as AccName,
                user.accNo as AccNo,
                user.email as email,
                user.mobile as mobile,
                user.Qualification as userCreatedQualification 
		  
			 FROM ActivityType t  , Joiner user, Activity a   ';
    
    $joinStatement="
        LEFT JOIN Country c 
        ON a.countryId=c.Id  
     
        LEFT JOIN Currency cr
        ON a.currencyId=cr.Id   
     ";

    $whereStatement = " where 1=1 
							and a.ActivityType=t.Id 
						   	and a.createdBy=user.Id   
						   	 ";
    
    $orderStatement = "  ORDER BY a.FromPeriod ASC  ";
    
    
    
    $filterFromPeriod = getKeyVal($reqParam, "fromPeriod");
    $filterToPeriod   = getKeyVal($reqParam, "toPeriod");
    
    //MONTH FILTER
    $month       = getMonth();
    $filterMonth = getKeyVal($reqParam, "month");
    if (!empty($filterMonth)) {
        $valueRangeList   = explode("|", $month[$filterMonth]);
        $filterFromPeriod = $valueRangeList[0];
        $filterToPeriod   = $valueRangeList[1];
    }
    
    if (!empty($filterFromPeriod)) {
        $whereStatement = $whereStatement . " and a.fromPeriod >='" . $filterFromPeriod . "'";
    }
    
    if (!empty($filterToPeriod)) {
        $whereStatement = $whereStatement . " and a.toPeriod < '" . $filterToPeriod . "'";
    }
    
    
    $filterName = getKeyVal($reqParam, "name");
    if (!empty($filterName)) {
        $whereStatement = $whereStatement . " and a.name like '%" . $filterName . "%'";
    }
    
    $filterDescription = getKeyVal($reqParam, "description");
    if (!empty($filterDescription)) {
        $whereStatement = $whereStatement . " and a.description like '%" . $filterDescription . "%'";
    }
    $filterAddress = getKeyVal($reqParam, "address");
    if (!empty($filterAddress)) {
        $whereStatement = $whereStatement . " and a.address like '%" . $filterAddress . "%'";
    }
     $filtercurrencyId = getKeyVal($reqParam, "currencyId");
    if (!empty($filtercurrencyId)) {
        $whereStatement = $whereStatement . " and a.currencyId = '" . $filtercurrencyId . "'";
    }   
    $filterpriceMin = getKeyVal($reqParam, "priceMin");
    if (!empty($filterpriceMin)) {
        $whereStatement = $whereStatement . " and a.priceMin >= " . $filterpriceMin . " ";
    }
      $filterpriceMax = getKeyVal($reqParam, "priceMax");
    if (!empty($filterpriceMax)) {
        $whereStatement = $whereStatement . " and a.priceMax =<" . $filterpriceMax . " ";
    }
    
     $filtercountryId = getKeyVal($reqParam, "countryId");
    if (!empty($filtercountryId)) {
        $whereStatement = $whereStatement . " and a.countryId = '" . $filtercountryId . "'";
    }
    
    $filterCreatedDate = getKeyVal($reqParam, "createdDate");
    if (!empty($filterCreatedDate)) {
        $whereStatement = $whereStatement . " and a.CreatedDate   ='" . $filterCreatedDate . "'";
    }
    $filterCreatedBy = getKeyVal($reqParam, "createdBy");
    if (!empty($filterCreatedBy)) {
        $whereStatement = $whereStatement . " and a.CreatedBy = '" . $filterCreatedBy . "'";
    }
    
    $commentList      = null;
    $photoList        = null;
    $filterActivityId = getKeyVal($reqParam, "activityId");
    if (!empty($filterActivityId)) {
        $whereStatement = $whereStatement . " and a.Id = '" . $filterActivityId . "'";
        $commentList    = getCommentByActivity($filterActivityId);
        $photoList      = getPhotoByActivity($filterActivityId);
    }
    
    $sqlStatement = $sqlStatement .$joinStatement . $whereStatement . $orderStatement . " ; ";
    $data         = queryDB($sqlStatement);
    if (!empty($data) && !empty($filterActivityId)) {
        $activityResult   = $data[0];
        $data             = null;
        $data["comments"] = $commentList;
        $data["photos"]   = $photoList;
        $data["activity"] = $activityResult;
    }
    getJsonResponse($app, $data);
}



function deleteActivityEvent($app, $activityId)
{
    $reqParam     = getJsonRequest($app);
    $activityData = null;
    $joiningData  = null;
    $photoData    = null;
    $commentData  = null;
    
    $sqlStatement = "DELETE FROM Activity
					WHERE Id= ";
    
    if (!empty($activityId)) {
        $sqlStatement = $sqlStatement . "'" . $activityId . "'";
        $activityData = crudDB($sqlStatement);
    }
    
    //del joining
    $sqlStatement = "DELETE FROM Joining
					WHERE ActivityId= ";
    
    if (!empty($activityId)) {
        $sqlStatement = $sqlStatement . "'" . $activityId . "'";
        $joiningData  = crudDB($sqlStatement);
    }
    
    //del Photo
    $sqlStatement = "DELETE FROM Photo
					WHERE ActivityId= ";
    
    if (!empty($activityId)) {
        $sqlStatement = $sqlStatement . "'" . $activityId . "'";
        $photoData    = crudDB($sqlStatement);
    }
    
    //del Comment
    $sqlStatement = "DELETE FROM Comment
					WHERE ActivityId= ";
    
    if (!empty($activityId)) {
        $sqlStatement = $sqlStatement . "'" . $activityId . "'";
        $commentData  = crudDB($sqlStatement);
    }
    
    
    
    $result = array(
        "status" => true,
        "joiningData" => $joiningData,
        "photoData" => $photoData,
        "commentData" => $commentData
    );
    getJsonResponse($app, $result);
    
}

function addNewActivityEvent($app)
{
    
    $reqParam = getJsonRequest($app);
    
    
    
    $sqlStatement = "INSERT INTO  Activity ( 
						`Name` ,
						`Description` ,
						`isPrivate` ,
						`joinersLimit` ,
						`ActivityType` ,
						`FromPeriod` ,
						`ToPeriod` ,
						`Address` ,
						`LatLoc` ,
						`LngLoc` ,
                        `price` ,
                        `currencyId` ,
                        `countryId` ,
						`createdDate` ,
						`createdBy`  
 						)  ";
    
    
    $valueStatement = "VALUES (";
    $name           = getKeyVal($reqParam, "name");
    if (!empty($name)) {
        $valueStatement = $valueStatement . "'" . $name . "',";
    } 
    else{ 
        $valueStatement = $valueStatement . "'-',";
    }

    $description = getKeyVal($reqParam, "description");
    if (!empty($description)) {
        $valueStatement = $valueStatement . "'" . $description . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'-',";
    }
    
    $isPrivate = getKeyVal($reqParam, "isPrivate");
    if (!empty($isPrivate)) {
        $valueStatement = $valueStatement . "'" . $isPrivate . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'1',";
    }
    
    $joinersLimit = getKeyVal($reqParam, "joinersLimit");
    if (!empty($joinersLimit)) {
        $valueStatement = $valueStatement . "'" . $joinersLimit . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'10',";
    }
    
    
    $activityType = getKeyVal($reqParam, "activityType");
    if (!empty($activityType)) {
        $valueStatement = $valueStatement . "'" . $activityType . "',";
    } 
    
    $fromPeriod = getKeyVal($reqParam, "fromPeriod");
    if (!empty($fromPeriod)) {
        $valueStatement = $valueStatement . "'" . $fromPeriod . "',";
    }
    else{  
        $valueStatement = $valueStatement . "'" . date('Y-m-d H:i:s') . "',"; 
    }
    
    $toPeriod = getKeyVal($reqParam, "toPeriod");
    if (!empty($toPeriod)) {
        $valueStatement = $valueStatement . "'" . $toPeriod . "',";
    } 
    else{  
        $valueStatement = $valueStatement . "'" . date('Y-m-d H:i:s') . "',"; 
    }
    
    $address = getKeyVal($reqParam, "address");
    if (!empty($address)) {
        $valueStatement = $valueStatement . "'" . $address . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'-',";
    }
    
    $latLoc = getKeyVal($reqParam, "latLoc");
    if (!empty($latLoc)) {
        $valueStatement = $valueStatement . "'" . $latLoc . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'-',";
    }
    
    $lngLoc = getKeyVal($reqParam, "lngLoc");
    if (!empty($lngLoc)) {
        $valueStatement = $valueStatement . "'" . $lngLoc . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'-',";
    }
    
    $price = getKeyVal($reqParam, "price");
    if (!empty($price)) {
        $valueStatement = $valueStatement . "'" . $price . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'0',";
    }
    
    $currencyId = getKeyVal($reqParam, "currencyId");
    if (!empty($currencyId)) {
        $valueStatement = $valueStatement . "'" . $currencyId . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'1',";
    }
    
    $countryId = getKeyVal($reqParam, "countryId");
    if (!empty($countryId)) {
        $valueStatement = $valueStatement . "'" . $countryId . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'1',";
    }
    
    $createdDate = getKeyVal($reqParam, "createdDate");
    if (!empty($createdDate)) {
        $valueStatement = $valueStatement . "'" . $createdDate . "',";
    } 
    else{  
        $valueStatement = $valueStatement . "'" . date('Y-m-d H:i:s') . "',"; 
    }
    
    $createdBy = getKeyVal($reqParam, "createdBy");
    if (!empty($createdBy)) {
        $valueStatement = $valueStatement . "'" . $createdBy . "' ";
    }
    else{ 
        $valueStatement = $valueStatement . "'*' ";
    }
    
    
    $valueStatement = $valueStatement . " )";
    $mysqli         = crudDB($sqlStatement . $valueStatement);
    $activityId     = $mysqli->insert_id;
    //INSERT ownerid INTO JOINING TABLE 
    $sqlStatement   = "INSERT INTO  Joining ( 
						`ActivityId` ,
						`JoinerId` ,
						`isApproved` , 
                        `isSpeaker`  
 						)  ";
    
    $valueStatement = "VALUES (";
    $activityId     = $mysqli->insert_id;
    if (!empty($activityId)) {
        $valueStatement = $valueStatement . "'" . $activityId . "',";
    }  
    
    $createdBy = getKeyVal($reqParam, "createdBy");
    if (!empty($createdBy)) {
        $valueStatement = $valueStatement . "'" . $createdBy . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'*' ,";
    }
    
    //owner is approved
    $isApproved = getKeyVal($reqParam, "isApproved");
    if (!empty($isApproved)) {
        $valueStatement = $valueStatement . "'1', ";
    }
    else{ 
        $valueStatement = $valueStatement . "'0' ,  ";
    }
    
    $valueStatement = $valueStatement . "'0'  )";
    
    $mysqli         = crudDB($sqlStatement . $valueStatement);
    $ownerJoiningId = $mysqli->insert_id;
     
    $result = array(
        "ownerJoiningId" => $ownerJoiningId,
        "activityId" => $activityId,
        "status" => (!empty($ownerJoiningId)  && !empty($ownerJoiningId))? true:false 
    );
    getJsonResponse($app, $result);
    
    
}

function editUser($app)
{
    
    $reqParam = getJsonRequest($app);
    
    $userId     = getKeyVal($reqParam, "userId");
    $sqlStatement   = " Update  Joiner  ";
    $setStatement   = " SET ";
    $whereStatement = " WHERE Id='" . $userId . "' ;"; 

    $joinerFbUsername           = getKeyVal($reqParam, "joinerFbUsername");
    if (!empty($joinerFbUsername)) {
        $setStatement = $setStatement . " joinerFbUsername='" . $joinerFbUsername . "' ,";
    }
    $joinerImageUrl = getKeyVal($reqParam, "joinerImageUrl");
    if (!empty($joinerImageUrl)) {
        $setStatement = $setStatement . " joinerImageUrl='" . $joinerImageUrl . "' ,";
    }
    $name = getKeyVal($reqParam, "name");
    if (!empty($name)) {
        $setStatement = $setStatement . " name='" . $name . "' ,";
    }
    $bankName = getKeyVal($reqParam, "bankName");
    if (!empty($bankName)) {
        $setStatement = $setStatement . " bankName='" . $bankName . "' ,";
    }
    $accNo = getKeyVal($reqParam, "accNo");
    if (!empty($accNo)) {
        $setStatement = $setStatement . " accNo='" . $accNo . "' ,";
    }
    $accName = getKeyVal($reqParam, "accName");
    if (!empty($accName)) {
        $setStatement = $setStatement . " accName='" . $accName . "' ,";
    }
    $email = getKeyVal($reqParam, "email");
    if (!empty($email)) {
        $setStatement = $setStatement . " email='" . $email . "' ,";
    }
    $mobile = getKeyVal($reqParam, "mobile");
    if (!empty($mobile)) {
        $setStatement = $setStatement . " mobile='" . $mobile . "' ,";
    }
    $qualification = getKeyVal($reqParam, "qualification");
    if (!empty($qualification)) {
        $setStatement = $setStatement . " qualification='" . $qualification . "' ,";
    } 
    $setStatement = $setStatement . " qualification=qualification ";
    $mysqli       = crudDB($sqlStatement . $setStatement . $whereStatement);
    
    $data  =getUser($joinerFbUsername ) ;
      
      $data[0]["status"]=true;
    getJsonResponse($app, $data[0]);
    
}
function editActivityEvent($app)
{
    
    $reqParam = getJsonRequest($app);
    
    $activityId     = getKeyVal($reqParam, "activityId");
    $sqlStatement   = " Update  Activity  ";
    $setStatement   = " SET ";
    $whereStatement = " WHERE Id='" . $activityId . "' ;";
    $name           = getKeyVal($reqParam, "name");
    if (!empty($name)) {
        $setStatement = $setStatement . " name='" . $name . "' ,";
    }
    $description = getKeyVal($reqParam, "description");
    if (!empty($description)) {
        $setStatement = $setStatement . " description='" . $description . "' ,";
    }
    $isPrivate = getKeyVal($reqParam, "isPrivate");
    if (!empty($isPrivate)) {
        $setStatement = $setStatement . " isPrivate='" . $isPrivate . "' ,";
    }
    $joinersLimit = getKeyVal($reqParam, "joinersLimit");
    if (!empty($joinersLimit)) {
        $setStatement = $setStatement . " joinersLimit='" . $joinersLimit . "' ,";
    }
    $activityType = getKeyVal($reqParam, "activityType");
    if (!empty($activityType)) {
        $setStatement = $setStatement . " activityType='" . $activityType . "' ,";
    }
    $lngLoc = getKeyVal($reqParam, "lngLoc");
    if (!empty($lngLoc)) {
        $setStatement = $setStatement . " lngLoc='" . $lngLoc . "' ,";
    }
    $latLoc = getKeyVal($reqParam, "latLoc");
    if (!empty($latLoc)) {
        $setStatement = $setStatement . " latLoc='" . $latLoc . "' ,";
    }
    $address = getKeyVal($reqParam, "address");
    if (!empty($address)) {
        $setStatement = $setStatement . " address='" . $address . "' ,";
    }
    $toPeriod = getKeyVal($reqParam, "toPeriod");
    if (!empty($toPeriod)) {
        $setStatement = $setStatement . " toPeriod='" . $toPeriod . "' ,";
    }
    $fromPeriod = getKeyVal($reqParam, "fromPeriod");
    if (!empty($fromPeriod)) {
        $setStatement = $setStatement . " fromPeriod='" . $fromPeriod . "' ,";
    }
    
    $countryId = getKeyVal($reqParam, "countryId");
    if (!empty($countryId)) {
        $setStatement = $setStatement . " countryId='" . $countryId . "' ,";
    }
    $currencyId = getKeyVal($reqParam, "currencyId");
    if (!empty($currencyId)) {
        $setStatement = $setStatement . " currencyId='" . $currencyId . "' ,";
    }
    $price = getKeyVal($reqParam, "price");
    if (!empty($price)) {
        $setStatement = $setStatement . " price='" . $price . "' ,";
    }
    $activityId = getKeyVal($reqParam, "activityId");
    if (!empty($activityId)) {
        $setStatement = $setStatement . " activityId='" . $activityId . "' ,";
    }
    $createdDate = getKeyVal($reqParam, "createdDate");
    if (!empty($createdDate)) {
        $setStatement = $setStatement . " createdDate='" . $createdDate . "' ,";
    }
    $createdBy = getKeyVal($reqParam, "createdBy");
    if (!empty($createdBy)) {
        $setStatement = $setStatement . " createdBy='" . $createdBy . "' ,";
    }
    $setStatement = $setStatement . " fromPeriod=fromPeriod ";
    $mysqli       = crudDB($sqlStatement . $setStatement . $whereStatement);
    
    
    //UPDATE SPEAKER ID IN JOINING TABLE
    // 		$sqlStatement = " Update  Joining  ";    
    // $setStatement =" SET ";
    // $whereStatement =" WHERE activityId='" . $activityId ."' ;";
    // $speakerId =getKeyVal($reqParam, "speakerId" ) ;
    // if ( !empty($speakerId )) {  
    // 	$setStatement =$setStatement ." JoinerId='" . $speakerId ."' ,";
    // }  
    //  $setStatement =$setStatement . " JoinerId=JoinerId "  ; 
    //   $mysqli =crudDB( $sqlStatement .$setStatement. $whereStatement  ); 
    
    getJsonResponse($app, true);
    
}

function addPhoto($app)
{
    
    $reqParam = getJsonRequest($app);
    $filedata = getKeyVal($reqParam, "filedata");
    $filename = getKeyVal($reqParam, "filename");
    $fileext  = getKeyVal($reqParam, "fileext");
    
    $filePath   = "uploads/" . $filename . (time() . uniqid()) . date("Y-m-d") . $fileext;
    $fileResult = array(
        "path" => $filePath
    );
    base64_to_jpeg($filedata, $filePath);
    
    $sqlStatement = "INSERT INTO  Photo ( 
						`Url` ,
						`Blob` ,
						`createdDate` ,
						`createdBy` ,
						`activityId`
 						)  ";
    
    $valueStatement = "VALUES (";
    $url            = getKeyVal($reqParam, "url");
    $url            = $filePath;
    if (!empty($url)) {
        $valueStatement = $valueStatement . "'" . $url . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'-',";
    }
    
    $blob = getKeyVal($reqParam, "blob");
    if (!empty($blob)) {
        $valueStatement = $valueStatement . "'" . $blob . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "NULL,";
    }

    $createdDate = getKeyVal($reqParam, "createdDate");
    if (!empty($createdDate)) {
        $valueStatement = $valueStatement . "'" . $createdDate . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'-',";
    }

    $createdBy = getKeyVal($reqParam, "createdBy");
    if (!empty($createdBy)) {
        $valueStatement = $valueStatement . "'" . $createdBy . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'*',";
    }

    $activityId = getKeyVal($reqParam, "activityId");
    if (!empty($activityId)) {
        $valueStatement = $valueStatement . "'" . $activityId . "' ";
    } 
    
    $valueStatement = $valueStatement . " )";
    $data           = crudDB($sqlStatement . $valueStatement);
    
    $result = array(
        "photoId" => $data->insert_id,
        "status" =>!empty($data->insert_id) ? true:false,
        "path" => $filePath
    );
    getJsonResponse($app, $result);
    
}

function deletePhoto($app, $activityId)
{
    
    $reqParam = getJsonRequest($app);
    
    $sqlStatement = "DELETE FROM Photo
						WHERE Id= ";
    $id           = $activityId;
    if (!empty($id)) {
        $sqlStatement = $sqlStatement . "'" . $id . "'";
        $data         = crudDB($sqlStatement);
        
        
        $result = array(
            "status" => true,
            "data" => $data
        );
        getJsonResponse($app, $result);
        
    }
    
    
}

function addComment($app)
{
    // :Id, :Title, :Description,:createdDate,:createdBy,:activityId
    
    $reqParam = getJsonRequest($app);
    
    $sqlStatement = "INSERT INTO  Comment ( 
						`Title` ,
						`Desc` ,
						`createdDate` ,
						`createdBy` ,
						`activityId`
 						)  ";
    
    $valueStatement = "VALUES (";
    $title          = getKeyVal($reqParam, "title");
    if (!empty($title)) {
        $valueStatement = $valueStatement . "'" . $title . "',";
    } 
    else{ 
        $valueStatement = $valueStatement . "'-' , ";
    }
    
    $desc = getKeyVal($reqParam, "desc");
    if (!empty($desc)) {
        $valueStatement = $valueStatement . "'" . $desc . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'-' , ";
    }

    $createdDate = getKeyVal($reqParam, "createdDate");
    if (!empty($createdDate)) {
        $valueStatement = $valueStatement . "'" . $createdDate . "',";
    }
    else{ 
        
        $valueStatement = $valueStatement . "'" . date('Y-m-d H:i:s') . "',";

    }
    
    $createdBy = getKeyVal($reqParam, "createdBy");
    if (!empty($createdBy)) {
        $valueStatement = $valueStatement . "'" . $createdBy . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'*' , ";
    }
    
    $activityId = getKeyVal($reqParam, "activityId");
    if (!empty($activityId)) {
        $valueStatement = $valueStatement . "'" . $activityId . "' ";
    }
     
    
    
    $valueStatement = $valueStatement . " )";
    $data           = crudDB($sqlStatement . $valueStatement);
    
    
    $result = array(
        "commentId" => $data->insert_id,
        "status" =>!empty($data->insert_id) ? true:false 
    );
    getJsonResponse($app, $result);
    
}

function deleteComment($app, $activityId)
{
    
    $reqParam = getJsonRequest($app);
    
    $sqlStatement = "DELETE FROM Comment
						WHERE Id= ";
    $id           = $activityId;
    if (!empty($id)) {
        $sqlStatement = $sqlStatement . "'" . $id . "'";
        $data         = crudDB($sqlStatement);
        
        $result = array(
            "status" => true,
            "data" => $data
        );
        getJsonResponse($app, $result);
    }
    
    
}


function addActivityType($app)
{
    
    $reqParam = getJsonRequest($app);
    
    $sqlStatement = "INSERT INTO  ActivityType ( 
						`Name`  )  ";
    
    $valueStatement = "VALUES (";
    $name           = getKeyVal($reqParam, "name");
    if (!empty($name)) {
        $valueStatement = $valueStatement . "'" . $name . "' ";
    }
    else{ 
        $valueStatement = $valueStatement . "'-' ";
    }
    
    $valueStatement = $valueStatement . " )";
    $data           = crudDB($sqlStatement . $valueStatement);
    
    
    $result = array(
        "activityTypeId" => $data->insert_id, 
        "status" =>!empty($data->insert_id) ? true:false 
    );
    getJsonResponse($app, $result);
    
}

function deleteActivityType($app, $id)
{
    $reqParam = getJsonRequest($app);
    
    $sqlStatement = "DELETE FROM ActivityType
						WHERE Id= ";
    
    if (!empty($id)) {
        $sqlStatement = $sqlStatement . "'" . $id . "'";
        $data         = crudDB($sqlStatement);
        
        $result = array(
            "status" => true,
            "data" => $data
        );
        getJsonResponse($app, $result);
        
    }
    
    
}

function getCurrency($currencyId )
{ 
    $sqlStatement   = 'SELECT id,shortName, name,countryId  FROM Currency';
    $whereStatement = " where 1=1 " ;

    if (!empty($currencyId)) {
        $whereStatement = $whereStatement . " and id = '" . $currencyId . "'";
    }
    
    $sqlStatement = $sqlStatement . $whereStatement . " ; ";
    $data         = queryDB($sqlStatement);
    return $data;
}
function getCountry($countryId )
{
 
    $sqlStatement   = 'SELECT id,shortName,name,isBanned  FROM Country';
    $whereStatement = " where 1=1 " ;
    if (!empty($countryId)) {
        $whereStatement = $whereStatement . " and id = '" . $countryId . "'";
    }
    
    $sqlStatement = $sqlStatement . $whereStatement . " ; ";
    $data         = queryDB($sqlStatement);
    return $data;
}
function getFormService($app){
    $list=null;
    $list['country']=  getCountry(null ); 
    $list['currency']=  getCurrency(null ); 
      $list['action']= array("REMOVE","PENDING","APPROVE");
    return  getJsonResponse($app, $list);  
 }


function modifyUserStatus($app)
{    
    $reqParam = getJsonRequest($app); 
    $joinerId     = getKeyVal($reqParam, "joinerId");
    $activityId     = getKeyVal($reqParam, "activityId");
    $action     = getKeyVal($reqParam, "action");

    if (!empty($action) && !empty($activityId)&& !empty($joinerId)) { 
         if ( "PENDING"==($action)  ||  "APPROVE"==($action)) {
            $sqlStatement   = " Update  Joining  ";
            $setStatement   = " SET "; 
             if ( "PENDING"==($action)) {
                  $setStatement = $setStatement . " isapproved='0'  ";
             }
             else  if ( "APPROVE"==($action)) {
                  $setStatement = $setStatement . " isapproved='1'  ";
             }
             $whereStatement = " WHERE joinerId='" . $joinerId . "'  and activityId='". $activityId . "'"; 
             $mysqli = crudDB($sqlStatement . $setStatement . $whereStatement);
            $result = array(
                        "status" => true,
                        "data" => $mysqli
                    );
              getJsonResponse($app, $result);  
          }
          else  if ( "REMOVE"== $action  ) {

                $sqlStatement = "DELETE FROM Joining WHERE joinerId='". $joinerId . "'" . "  and activityId='". $activityId . "'"; 
                $data = crudDB($sqlStatement); 
                    $result = array(
                        "status" => true,
                        "data" => $data
                    );
                getJsonResponse($app, $result);  
        }
     } 
  
    
}

function getUser($joinerFbUsername){ 
    
    $sqlStatement = "Select 
                        `id` as userId ,  
                        `joinerFbUsername` ,
                        `joinerImageUrl` ,
                        `name`  ,
                        `bankName`  ,  
                        `accNo`  , 
                        `accName`    ,
                         `email`    ,
                        `mobile`    ,
                        `qualification`   
                         from Joiner  where joinerFbUsername='" . $joinerFbUsername . "'";
     return  queryDB($sqlStatement);
}
function addUser($app)
{
    $reqParam         = getJsonRequest($app); 
     
    $data  =getUser(getKeyVal($reqParam, "joinerFbUsername") ) ;
    if (empty($data)) {
        $sqlStatement = "INSERT INTO  Joiner ( 
						`joinerFbUsername` ,
						`joinerImageUrl` ,
						`name`  ,
                        `bankName`  ,  
                        `accNo`  , 
                        `accName`   ,
                         `email`    ,
                        `mobile`    ,
						`qualification`   
 						)  ";
        
        $valueStatement   = "VALUES (";
        $joinerFbUsername = getKeyVal($reqParam, "joinerFbUsername");
        if (!empty($joinerFbUsername)) {
            $valueStatement = $valueStatement . "'" . $joinerFbUsername . "',";
        }
        else{
           $valueStatement = $valueStatement . "'-',"; 
        }
        

        $joinerImageUrl = getKeyVal($reqParam, "joinerImageUrl");
        if (!empty($joinerImageUrl)) {
            $valueStatement = $valueStatement . "'" . $joinerImageUrl . "',";
        }
        else{
           $valueStatement = $valueStatement . "'-',"; 
        }


        $name = getKeyVal($reqParam, "name");
        if (!empty($name)) {
            $valueStatement = $valueStatement . "'" . $name . "' ,";
        }
        else{
           $valueStatement = $valueStatement . "'-',"; 
        }


        $bankName = getKeyVal($reqParam, "bankName");
        if (!empty($bankName)) {
            $valueStatement = $valueStatement . "'" . $bankName . "' ,";
        } 
        else{
           $valueStatement = $valueStatement . "'-',"; 
        }


        $accNo = getKeyVal($reqParam, "accNo");
        if (!empty($accNo)) {
            $valueStatement = $valueStatement . "'" . $accNo . "' ,";
        } 
        else{
           $valueStatement = $valueStatement . "'-',"; 
        }

        $accName = getKeyVal($reqParam, "accName");
        if (!empty($accName)) {
            $valueStatement = $valueStatement . "'" . $accName . "' ,";
        }
        else{
           $valueStatement = $valueStatement . "'-',"; 
        }

        $email = getKeyVal($reqParam, "email");
        if (!empty($email)) {
            $valueStatement = $valueStatement . "'" . $email . "' ,";
        }
        else{
           $valueStatement = $valueStatement . "'-',"; 
        }

        $mobile = getKeyVal($reqParam, "mobile");
        if (!empty($mobile)) {
            $valueStatement = $valueStatement . "'" . $mobile . "' ,";
        }
        else{
           $valueStatement = $valueStatement . "'-',"; 
        }

        $qualification = getKeyVal($reqParam, "qualification");
        if (!empty($qualification)) {
            $valueStatement = $valueStatement . "'" . $qualification . "' ";
        }
        else{
           $valueStatement = $valueStatement . "'-' ";
        }
        
        $valueStatement = $valueStatement . " )";
        $mysqli         = crudDB($sqlStatement . $valueStatement);
        $userId=$mysqli->insert_id;
        $data[0]['status']=!empty($mysqli->insert_id) ? true:false ;
        $data[0]['status'] = true;
        $data[0]['userId']= $userId; 
        getJsonResponse($app, $data[0]);
    } else {
        $data[0]['status'] = false;
        $data[0]['status'] = true;
        $data[0]['message'] = "already exist";
        getJsonResponse($app, $data[0]);
    }
    
    
}

function base64_to_jpeg($base64_string, $output_file)
{
    
    //  $filename_path = md5(time().uniqid()).".jpg";  
    // $output_file="uploads/".$filename_path;
    
    $ifp = fopen($output_file, "wb");
    
    $data = explode(',', $base64_string);
    
    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);
    
    return $output_file;
}
function deleteUser($app, $joinerFbUsername)
{
    
    $reqParam = getJsonRequest($app);
    
    $sqlStatement = "DELETE FROM Joiner
						WHERE joinerFbUsername= ";
    
    if (!empty($joinerFbUsername)) {
        $sqlStatement = $sqlStatement . "'" . $joinerFbUsername . "'";
        $data         = crudDB($sqlStatement);
        $result       = array(
            "status" => true,
            "data" => $data
        );
        getJsonResponse($app, $result);
    }
    
    
    
}
function addEventParticipation($app)
{
    
    $reqParam = getJsonRequest($app);
    
    $sqlStatement = "INSERT INTO  Joining ( 
						`activityId` ,
						`joinerId` ,
                        `isApproved` , 
						`isSpeaker`  
 						)  ";
    
    $valueStatement = "VALUES (";
    $activityId     = getKeyVal($reqParam, "activityId");
    if (!empty($activityId)) {
        $valueStatement = $valueStatement . "'" . $activityId . "',";
    } 

    $joinerId = getKeyVal($reqParam, "joinerId");
    if (!empty($joinerId)) {
        $valueStatement = $valueStatement . "'" . $joinerId . "',";
    } 
    
    $isApproved = getKeyVal($reqParam, "isApproved");
    if (!empty($isApproved)) {
        $valueStatement = $valueStatement . "'" . $isApproved . "',";
    }
    else{ 
        $valueStatement = $valueStatement . "'0',";
    }
    

    $isSpeaker = getKeyVal($reqParam, "isSpeaker");
    if (!empty($isSpeaker)) {
        $valueStatement = $valueStatement . "'" . $isSpeaker . "' ";
    }
    else{ 
        $valueStatement = $valueStatement . "'0' ";
    }
    
    
    $valueStatement = $valueStatement . " )";
    $data           = crudDB($sqlStatement . $valueStatement);
    
    $result = array(
        "joiningId" => $data->insert_id, 
        "status" =>!empty($data->insert_id) ? true:false 
    );
    getJsonResponse($app, $result);
    
}

function deleteEventParticipation($app, $joinerId)
{
    $reqParam     = getJsonRequest($app);
    $sqlStatement = "DELETE FROM Joining
						WHERE joinerId= ";
    
    $activityId = getKeyVal($reqParam, "activityId");
    if (!empty($joinerId) && !empty($activityId)) {
        $sqlStatement = $sqlStatement . "'" . $joinerId . "'";
        $sqlStatement = $sqlStatement . " and activityId='" . $activityId . "'";
        $data         = crudDB($sqlStatement);
        
        $result = array(
            "status" => true,
            "data" => $data
        );
        getJsonResponse($app, $result);
        
    } else {
        throw new Exception("FAILED");
    }
    
    
}

function getJoiningListing($app)
{
    
    
    $reqParam = getJsonRequest($app);
    
    $sqlStatement = ' SELECT j.Id  ,
						j.ActivityId  , 
						 j.isSpeaker,
						a.Name  ,
						a.Description ,
						a.isPrivate ,
						a.joinersLimit  ,
						a.FromPeriod,
						a.ToPeriod,a.price,
                        a.currencyId, cr.name as currencyName,cr.shortName as currencyShortName, cr.countryId as currencyCountryId,
                        a.countryId,c.shortName as countryShortName,c.name as countryName, c.isBanned as countryIsBanned,
						a.ActivityType as activityId ,  
						t.name as activityDesc ,   
						a.Address  ,
						a.LatLoc ,
						a.LngLoc ,
						a.createdBy as userCreatedId,
						a.createdDate ,
						j.JoinerId as joinerId,	 j.isApproved,
						user.JoinerFbUsername  as joinerFbUsername,   
						user.JoinerImageUrl   as joinerImageUrl, 
						user.Name  as  joinerName, 
						user.Qualification as  joinerQualification  ,
                        user.bankName as  joinerBankName  ,
                        user.accNo as  joinerAccNo  ,
                        user.accName as  joinerAccName,
                        user.email as joinerEmail,
                        user.mobile as joinerMobile
					    FROM Joining j, ActivityType t, Joiner user, Activity a  ';
    
     
    $joinStatement="
        LEFT JOIN Country c 
        ON a.countryId=c.Id  
     
        LEFT JOIN Currency cr
        ON a.currencyId=cr.Id   
     ";

     $whereStatement = " where 1=1 and j.ActivityId=a.Id and j.joinerId=user.Id  and a.ActivityType=t.Id   ";
    
    $orderStatement   = "  ORDER BY a.FromPeriod ASC  ";
    $filterActivityId = getKeyVal($reqParam, "activityId");
    if (!empty($filterActivityId)) {
        $whereStatement = $whereStatement . " and j.ActivityId = '" . $filterActivityId . "'";
    }
    
    $filterjoinerId = getKeyVal($reqParam, "joinerId");
    if (!empty($filterjoinerId)) {
        $whereStatement = $whereStatement . " and j.JoinerId = '" . $filterjoinerId . "'";
    }
    
    //MONTH FILTER
    $filterFromPeriod = getKeyVal($reqParam, "fromPeriod");
    $filterToPeriod   = getKeyVal($reqParam, "toPeriod");
    $month            = getMonth();
    $filterMonth      = getKeyVal($reqParam, "month");
    if (!empty($filterMonth)) {
        $valueRangeList = explode("|", $month[$filterMonth]);
        if (!empty($valueRangeList[0])) {
            $filterFromPeriod = $valueRangeList[0];
            $filterToPeriod   = $valueRangeList[1];
        }
    }
    
    if (!empty($filterFromPeriod)) {
        $whereStatement = $whereStatement . " and a.fromPeriod >='" . $filterFromPeriod . "'";
    }
    
    if (!empty($filterToPeriod)) {
        $whereStatement = $whereStatement . " and a.toPeriod < '" . $filterToPeriod . "'";
    }
    
    
    
    $filterName = getKeyVal($reqParam, "name");
    if (!empty($filterName)) {
        $whereStatement = $whereStatement . " and a.name like '%" . $filterName . "%'";
    }

    $filterisApproved = getKeyVal($reqParam, "isApproved");
    if (!empty($filterisApproved)) {
        $whereStatement = $whereStatement . " and j.isApproved ='" . $filterisApproved . "'";
    }
    
    $filterDescription = getKeyVal($reqParam, "description");
    if (!empty($filterDescription)) {
        $whereStatement = $whereStatement . " and a.description like '%" . $filterDescription . "%'";
    }
    $filterAddress = getKeyVal($reqParam, "address");
    if (!empty($filterAddress)) {
        $whereStatement = $whereStatement . " and a.address like '%" . $filterAddress . "%'";
    }
    $filterpriceMin = getKeyVal($reqParam, "priceMin");
    if (!empty($filterpriceMin)) {
        $whereStatement = $whereStatement . " and a.priceMin >= " . $filterpriceMin . " ";
    }
      $filterpriceMax = getKeyVal($reqParam, "priceMax");
    if (!empty($filterpriceMax)) {
        $whereStatement = $whereStatement . " and a.priceMax =<" . $filterpriceMax . " ";
    }
    
    $filtercurrencyId = getKeyVal($reqParam, "currencyId");
    if (!empty($filtercurrencyId)) {
        $whereStatement = $whereStatement . " and a.currencyId like '%" . $filtercurrencyId . "%'";
    }
    
    $filtercountryId = getKeyVal($reqParam, "countryId");
    if (!empty($filtercountryId)) {
        $whereStatement = $whereStatement . " and a.countryId like '%" . $filtercountryId . "%'";
    }
    
    
    $filterCreatedDate = getKeyVal($reqParam, "createdDate");
    if (!empty($filterCreatedDate)) {
        $whereStatement = $whereStatement . " and a.CreatedDate   ='" . $filterCreatedDate . "'";
    }
    $filterCreatedBy = getKeyVal($reqParam, "createdBy");
    if (!empty($filterCreatedBy)) {
        $whereStatement = $whereStatement . " and a.CreatedBy = '%" . $filterCreatedBy . "%'";
    }
    
    $sqlStatement = $sqlStatement . $joinStatement . $whereStatement . 
                    $orderStatement . " ; ";
    $data         = queryDB($sqlStatement);
    getJsonResponse($app, $data);
    
}

function getJoinerListing($app)
{
    $reqParam       = getJsonRequest($app);
    $sqlStatement   = ' SELECT Id  ,
						JoinerFbUsername  , 
                        bankName as BankName,
                        accName as AccName,
                        accNo as AccNo,
                        email as email,
                        mobile as mobile,
						JoinerImageUrl 
						  FROM Joiner ';
    $whereStatement = " where 1=1 ";
    
    
    
    $filterJoinerImageUrl = getKeyVal($reqParam, "joinerImageUrl");
    if (!empty($filterJoinerImageUrl)) {
        $whereStatement = $whereStatement . " and JoinerImageUrl like '%" . $filterJoinerImageUrl . "%'";
    }
    
    $filterbankName = getKeyVal($reqParam, "bankName");
    if (!empty($filterbankName)) {
        $whereStatement = $whereStatement . " and bankName like '%" . $filterbankName . "%'";
    }
    
    $filteremail = getKeyVal($reqParam, "email");
    if (!empty($filteremail)) {
        $whereStatement = $whereStatement . " and email like '%" . $filteremail . "%'";
    }
    $filtermobile = getKeyVal($reqParam, "mobile");
    if (!empty($filtermobile)) {
        $whereStatement = $whereStatement . " and mobile like '%" . $filtermobile . "%'";
    }
    
    $filteraccNo = getKeyVal($reqParam, "accNo");
    if (!empty($filteraccNo)) {
        $whereStatement = $whereStatement . " and accNo like '%" . $filteraccNo . "%'";
    }
    
    
    $filteraccName = getKeyVal($reqParam, "accName");
    if (!empty($filteraccName)) {
        $whereStatement = $whereStatement . " and accName like '%" . $filteraccName . "%'";
    }
    
    
    $filterJoinerFbUsername = getKeyVal($reqParam, "joinerFbUserName");
    if (!empty($filterJoinerFbUsername)) {
        $whereStatement = $whereStatement . " and JoinerFbUsername like '%" . $filterJoinerFbUsername . "%'";
    }
    
    $sqlStatement = $sqlStatement . $whereStatement . " ; ";
    $data         = queryDB($sqlStatement);
    getJsonResponse($app, $data);
    
}


function getFriendsListing($app)
{
    $reqParam = getJsonRequest($app);
    getJsonResponse($app, getKeyVal($reqParam, "A"));
}
?>