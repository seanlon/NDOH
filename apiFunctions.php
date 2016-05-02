<?php 
   
function connectDB(){
	require_once 'lib/mysql.php';
	$db = connect_db();  
	return $db;
}

function crudDB($statement) { 
	$db = connectDB();   
	if ($db->query($statement) === TRUE) { 
	    return $db;
	} else {
	    // echo " Error: " . $sql . "<br>" . $conn->error;
	    return false;
	} 
	 
}
function queryDB($statement) { 
	$db = connectDB();   
	$rs = $db->query( $statement );  
	$data = $rs->fetch_all(MYSQLI_ASSOC); 
	return $data;
}
//Safety get value
 function getKeyVal($objArr, $keyId ){
	if ( !is_null($objArr) && array_key_exists( $keyId,$objArr)){ 
		return $objArr->$keyId ;
	}
	else{
		return "";
	}
 
 }
 function getJsonRequest( $app ) { 
    return  json_decode($app->request->getBody() );    
 }
 function getJsonResponse( $app,$jsonData) {
		  $jsonType = "application/json;charset=utf-8"; 	 
		  header("Content-Type:  " .  $jsonType);

		// header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Credentials: true"); 
		header('Access-Control-Allow-Headers: X-Requested-With');
		header('Access-Control-Allow-Headers: Content-Type');
		header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');  
		header('Access-Control-Max-Age: 86400'); 
		$response = $app->response();
		$response['Content-Type'] = $jsonType; 
		 
		$response->headers->set('Content-Type',$jsonType );
		$response->body(json_encode($jsonData,JSON_UNESCAPED_UNICODE));
	//alternate / basic way return json
	//echo json_encode($jsonData)
	//WILL CAUSE 404 -NOT FOUND
	//exit;
 }

 function getSpeakingListing( $app ) {
		  $reqParam = getJsonRequest( $app);   

		  $sqlStatement ='SELECT Id  ,
						ActivityId  , 
						SpeakerId 
						  FROM Speaking ';
		  $whereStatement = " where 1=1 " ;

	
		$filterSpeakerId =getKeyVal($reqParam, "speakerId" ) ;
		if ( !empty($filterSpeakerId )) { 
			$whereStatement =$whereStatement . " and SpeakerId like '%". $filterSpeakerId."%'";
		} 
	
		$filterActivityId =getKeyVal($reqParam, "activityId" ) ;
		if ( !empty($filterActivityId )) { 
			$whereStatement =$whereStatement . " and ActivityId like '%". $filterActivityId."%'";
		} 
		 
 
		$sqlStatement = $sqlStatement. $whereStatement . " ; ";
		$data =queryDB( $sqlStatement ); 
		 getJsonResponse( $app,$data);     
  }

 function getSpeakerListing( $app ) {
		  $reqParam = getJsonRequest( $app);   

		  $sqlStatement ='SELECT Id  , 	Name  ,  Qualification  FROM Speaker ';
		  $whereStatement = " where 1=1 " ;

	
		$filterQualification =getKeyVal($reqParam, "qualification" ) ;
		if ( !empty($filterQualification )) { 
			$whereStatement =$whereStatement . " and Qualification like '%". $filterQualification."%'";
		} 
	
		$filterName =getKeyVal($reqParam, "name" ) ;
		if ( !empty($filterName )) { 
			$whereStatement =$whereStatement . " and name like '%". $filterName."%'";
		} 
		 
 
		$sqlStatement = $sqlStatement. $whereStatement . " ; ";
		$data =queryDB( $sqlStatement ); 
		 getJsonResponse( $app,$data);    
  // getJsonResponse( $app,"321" );   
  }

function getMonth(   ) {
		//eg.2016-04-11
		$year= date('Y') ;
		$month["JAN"]=$year."-01-01|".$year."-02-01";  
		$month["FEB"]=$year."-02-01|".$year."-03-01";  
		$month["MAR"]=$year."-03-01|".$year."-04-01";  
		$month["APR"]=$year."-04-01|".$year."-05-01";  
		$month["MAY"]=$year."-05-01|".$year."-06-01";  
		$month["JUN"]=$year."-06-01|".$year."-07-01";  
		$month["JUL"]=$year."-07-01|".$year."-08-01";  
		$month["AUG"]=$year."-08-01|".$year."-09-01";  
		$month["SEP"]=$year."-09-01|".$year."-10-01";  
		$month["OCT"]=$year."-10-01|".$year."-11-01";  
		$month["NOV"]=$year."-11-01|".$year."-12-01";  
		$month["DEC"]=$year."-12-01|".($year+1)."-01-01";  
		
	return $month;
}

 function getPhotoByActivity( $activityId ) {  
 
		  $sqlStatement ='SELECT photo.Id  , photo.url  , 
		   photo.Blob , photo.createdBy,photo.createdDate,
		   photo.activityId  ,
		   user.JoinerFbUsername,user.JoinerImageUrl,
		   user.Name,user.Qualification  FROM Photo photo, Joiner user ';
		  $whereStatement = " where 1=1  and  photo.createdBy= user.Id " ;
 
 
		if ( !empty($activityId )) { 
			$whereStatement =$whereStatement . " and activityId = '". $activityId."'";
		}  
 
		$sqlStatement = $sqlStatement. $whereStatement . " ; ";
		$data =queryDB( $sqlStatement ); 
		return $data ;
  }
 function getCommentByActivity( $activityId ) {  
 
		  $sqlStatement ='SELECT com.Id  , com.Title  , 
		   com.Desc , com.createdBy,com.createdDate,
		   com.activityId ,
		   user.JoinerFbUsername,user.JoinerImageUrl,
		   user.Name,user.Qualification   FROM Comment com, Joiner user ';
		  $whereStatement = " where 1=1  and  com.createdBy= user.Id " ;
 
 
		if ( !empty($activityId )) { 
			$whereStatement =$whereStatement . " and activityId = '". $activityId."'";
		}  
 
		$sqlStatement = $sqlStatement. $whereStatement . " ; ";
		$data =queryDB( $sqlStatement ); 
		return $data ;
  }
 function getActivityListing ( $app ) {
		$reqParam = getJsonRequest( $app);   
 		  
		$sqlStatement ='SELECT a.Id  ,
				a.Name  ,
				a.Description ,
				a.isPrivate ,
				a.joinersLimit  ,
				a.FromPeriod,
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
				user.Qualification as userCreatedQualification 
			
		  
			 FROM ActivityType t  , Joiner user, Activity a    ';
	
	 	// $joinStatement="

 		// LEFT OUTER JOIN Photo photo ON a.photoid = photo.Id 
 		// LEFT OUTER JOIN Comment comment ON a.commentid = comment.Id  
 		//  ";
		$whereStatement = " where 1=1 
							and a.ActivityType=t.Id 
						   	and a.createdBy=user.Id  
						   	 " ;  

		$orderStatement = "  ORDER BY a.FromPeriod ASC  " ;  

						   	

		$filterFromPeriod =getKeyVal($reqParam, "fromPeriod" ) ;
		$filterToPeriod =getKeyVal($reqParam, "toPeriod" ) ; 

		//MONTH FILTER
	 	$month=getMonth( );
		$filterMonth =getKeyVal($reqParam, "month" ) ;
		if ( !empty($filterMonth )) { 
			$valueRangeList=explode("|",$month[$filterMonth ]);
			$filterFromPeriod =$valueRangeList[0];
			$filterToPeriod =$valueRangeList[1];
		} 

		if ( !empty($filterFromPeriod )) { 
			$whereStatement =$whereStatement . " and a.fromPeriod >='". $filterFromPeriod."'";
		} 

		if ( !empty($filterToPeriod )) { 
			$whereStatement =$whereStatement . " and a.toPeriod < '". $filterToPeriod."'";
		} 


		$filterName =getKeyVal($reqParam, "name" ) ;
		if ( !empty($filterName )) { 
			$whereStatement =$whereStatement . " and a.name like '%". $filterName."%'";
		} 

		$filterDescription =getKeyVal($reqParam, "description" ) ;
		if ( !empty($filterDescription )) { 
			$whereStatement =$whereStatement . " and a.description like '%". $filterDescription."%'";
		} 
		$filterAddress =getKeyVal($reqParam, "address" ) ;
		if ( !empty($filterAddress )) { 
			$whereStatement =$whereStatement . " and a.address like '%". $filterAddress."%'";
		} 
  
		$filterCreatedDate =getKeyVal($reqParam, "createdDate" ) ;
		if ( !empty($filterCreatedDate )) { 
			$whereStatement =$whereStatement . " and a.CreatedDate   ='". $filterCreatedDate."'";  
		} 
		$filterCreatedBy =getKeyVal($reqParam, "createdBy" ) ;
		if ( !empty($filterCreatedBy )) { 
			$whereStatement =$whereStatement . " and a.CreatedBy = '". $filterCreatedBy."'";
		} 
 
 		$commentList=null;
 		$photoList=null;
		$filterActivityId =getKeyVal($reqParam, "activityId" ) ;
		if ( !empty($filterActivityId )) { 
			$whereStatement =$whereStatement . " and a.Id = '". $filterActivityId."'";
			  $commentList=getCommentByActivity($filterActivityId); 
			   $photoList=getPhotoByActivity($filterActivityId); 
		}  

		$sqlStatement = $sqlStatement .$whereStatement . $orderStatement." ; ";
		  $data =queryDB( $sqlStatement ); 
		  if ( !empty($data )   &&!empty($filterActivityId )) { 
					$activityResult=$data[0];
					$data=null;
					$data["comments"]= $commentList;
					$data["photos"]= $photoList; 
					$data["activity"]=$activityResult;
		  }
		getJsonResponse( $app,$data);   
  }



function deleteActivityEvent( $app,$activityId )  { 
	$reqParam = getJsonRequest( $app);    
	$activityData=null;$joiningData=null;
	$photoData=null;$commentData=null;

	$sqlStatement = "DELETE FROM Activity
					WHERE Id= ";   
	 
	if ( !empty($activityId )) { 
		$sqlStatement =$sqlStatement . "'". $activityId."'"; 
	    $activityData =crudDB( $sqlStatement  ); 
	}  

	//del joining
	$sqlStatement = "DELETE FROM Joining
					WHERE ActivityId= ";   
	 
	if ( !empty($activityId )) { 
		$sqlStatement =$sqlStatement . "'". $activityId."'"; 
	    $joiningData =crudDB( $sqlStatement  ); 
	} 
	
	//del Photo
	$sqlStatement = "DELETE FROM Photo
					WHERE ActivityId= ";   
	 
	if ( !empty($activityId )) { 
		$sqlStatement =$sqlStatement . "'". $activityId."'"; 
	    $photoData =crudDB( $sqlStatement  ); 
	} 

	//del Comment
	$sqlStatement = "DELETE FROM Comment
					WHERE ActivityId= ";   
	 
	if ( !empty($activityId )) { 
		$sqlStatement =$sqlStatement . "'". $activityId."'"; 
	    $commentData =crudDB( $sqlStatement  ); 
	} 

 
	 
  	 $result = array( "status"=> true ,"joiningData"=> $joiningData,"photoData"=> $photoData,"commentData"=> $commentData); 
     getJsonResponse( $app,  $result ); 

}

 function addNewActivityEvent($app ) {
  
		$reqParam = getJsonRequest( $app);  

 

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
						`createdDate` ,
						`createdBy`  
 						)  ";  

 
		$valueStatement ="VALUES (";
		$name =getKeyVal($reqParam, "name" ) ;
		if ( !empty($name )) { 
			$valueStatement =$valueStatement . "'". $name."',";
		} 
 
		$description =getKeyVal($reqParam, "description" ) ;
		if ( !empty($description )) { 
			$valueStatement =$valueStatement . "'". $description."',";
		} 
		$isPrivate =getKeyVal($reqParam, "isPrivate" ) ;
		if ( !empty($isPrivate )) { 
			$valueStatement =$valueStatement . "'". $isPrivate."',";
		} 
		$joinersLimit =getKeyVal($reqParam, "joinersLimit" ) ;
		if ( !empty($joinersLimit )) { 
			$valueStatement =$valueStatement . "'". $joinersLimit."',";
		} 
	
		$activityType =getKeyVal($reqParam, "activityType" ) ;
		if ( !empty($activityType )) { 
			$valueStatement =$valueStatement . "'". $activityType."',";
		} 
		$fromPeriod =getKeyVal($reqParam, "fromPeriod" ) ;
		if ( !empty($fromPeriod )) { 
			$valueStatement =$valueStatement . "'". $fromPeriod."',";
		} 
		$toPeriod =getKeyVal($reqParam, "toPeriod" ) ;
		if ( !empty($toPeriod )) { 
			$valueStatement =$valueStatement . "'". $toPeriod."',";
		} 
		$address =getKeyVal($reqParam, "address" ) ;
		if ( !empty($address )) { 
			$valueStatement =$valueStatement . "'". $address."',";
		} 
		$latLoc =getKeyVal($reqParam, "latLoc" ) ;
		if ( !empty($latLoc )) { 
			$valueStatement =$valueStatement . "'". $latLoc."',";
		} 
		$lngLoc =getKeyVal($reqParam, "lngLoc" ) ;
		if ( !empty($lngLoc )) { 
			$valueStatement =$valueStatement . "'". $lngLoc."',";
		} 
		$createdDate =getKeyVal($reqParam, "createdDate" ) ;
		if ( !empty($createdDate )) { 
			$valueStatement =$valueStatement . "'". $createdDate."',";
		} 
		$createdBy =getKeyVal($reqParam, "createdBy" ) ;
		if ( !empty($createdBy )) { 
			$valueStatement =$valueStatement . "'". $createdBy."' ";
		} 
 
		$valueStatement =$valueStatement ." )";  
		  $mysqli =crudDB( $sqlStatement .$valueStatement ); 
 		 $activityId =$mysqli->insert_id; 
 		 //INSERT ownerid INTO JOINING TABLE 
		$sqlStatement = "INSERT INTO  Joining ( 
						`ActivityId` ,
						`JoinerId` ,
						`isSpeaker`  
 						)  ";   
		
		$valueStatement ="VALUES (";
		 $activityId =$mysqli->insert_id ;
		if ( !empty($activityId )) { 
		 	$valueStatement =$valueStatement . "'". $activityId."',";
		} 
 
		$createdBy =getKeyVal($reqParam, "createdBy" ) ;
		if ( !empty($createdBy )) { 
			$valueStatement =$valueStatement . "'". $createdBy."',";
		}  

		$valueStatement =$valueStatement . "'0'  )"; 
		 
		  $mysqli =crudDB( $sqlStatement .$valueStatement );  
		$ownerJoiningId=$mysqli->insert_id;

	   //INSERT SPEAKER INTO JOINING TABLE 
		// $sqlStatement = "INSERT INTO  Joining ( 
		// 				`ActivityId` ,
		// 				`JoinerId` ,
		// 				`isSpeaker`  
		// 					)  ";   
		
		// $valueStatement ="VALUES (";
		//  $activityId =$mysqli->insert_id ;
		// if ( !empty($activityId )) { 
		//  	$valueStatement =$valueStatement . "'". $activityId."',";
		// } 

		// $speakerId =getKeyVal($reqParam, "speakerId" ) ;
		// if ( !empty($speakerId )) { 
		// 	$valueStatement =$valueStatement . "'". $speakerId."',";
		// }  

		// $valueStatement =$valueStatement . "'1'  )"; 
		//  "joiningId"=>$mysqli->insert_id,
		  // $mysqli =crudDB( $sqlStatement .$valueStatement );    
		  $result = array("ownerJoiningId"=>$ownerJoiningId,"activityId"=>$activityId,"status"=> true   ); 
	     getJsonResponse( $app,  $result ); 

 
  }

 function editActivityEvent($app ) {
  
		$reqParam = getJsonRequest( $app);  
 
	    $activityId =getKeyVal($reqParam, "activityId" ) ;
		$sqlStatement = " Update  Activity  ";    
		$setStatement =" SET ";
		$whereStatement =" WHERE Id='" . $activityId ."' ;";
		$name =getKeyVal($reqParam, "name" ) ;
		if ( !empty($name )) {  
			$setStatement =$setStatement ." name='" . $name ."' ,";
		} 
		$description =getKeyVal($reqParam, "description" ) ;
		if ( !empty($description )) {  
			$setStatement =$setStatement ." description='" . $description ."' ,";
		} 
		$isPrivate =getKeyVal($reqParam, "isPrivate" ) ;
		if ( !empty($isPrivate )) {  
			$setStatement =$setStatement ." isPrivate='" . $isPrivate ."' ,";
		} 
		$joinersLimit =getKeyVal($reqParam, "joinersLimit" ) ;
		if ( !empty($joinersLimit )) {  
			$setStatement =$setStatement ." joinersLimit='" . $joinersLimit ."' ,";
		} 
		$activityType =getKeyVal($reqParam, "activityType" ) ;
		if ( !empty($activityType )) {  
			$setStatement =$setStatement ." activityType='" . $activityType ."' ,";
		}   
		$lngLoc =getKeyVal($reqParam, "lngLoc" ) ;
		if ( !empty($lngLoc )) {  
			$setStatement =$setStatement ." lngLoc='" . $lngLoc ."' ,";
		} 
		$latLoc =getKeyVal($reqParam, "latLoc" ) ;
		if ( !empty($latLoc )) {  
			$setStatement =$setStatement ." latLoc='" . $latLoc ."' ,";
		} 
		$address =getKeyVal($reqParam, "address" ) ;
		if ( !empty($address )) {  
			$setStatement =$setStatement . " address='" . $address ."' ,";
		} 
		$toPeriod =getKeyVal($reqParam, "toPeriod" ) ;
		if ( !empty($toPeriod )) {  
			$setStatement =$setStatement ." toPeriod='" . $toPeriod ."' ,";
		} 
		$fromPeriod =getKeyVal($reqParam, "fromPeriod" ) ;
		if ( !empty($fromPeriod )) {  
			$setStatement =$setStatement ." fromPeriod='" . $fromPeriod ."' ,";
		} 
  
		$createdDate =getKeyVal($reqParam, "createdDate" ) ;
		if ( !empty($createdDate )) { 
			$setStatement =$setStatement ." createdDate='" . $createdDate ."' ,";
		} 
		$createdBy =getKeyVal($reqParam, "createdBy" ) ;
		if ( !empty($createdBy )) { 
			$setStatement =$setStatement ." createdBy='" . $createdBy ."' ,";
		}  
		 $setStatement =$setStatement . " fromPeriod=fromPeriod "  ; 
		 $mysqli =crudDB( $sqlStatement .$setStatement. $whereStatement  ); 
  

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
  
		getJsonResponse( $app,   true );    
 
  }

 function addPhoto( $app) {
  
		$reqParam = getJsonRequest( $app);   
		$filedata =getKeyVal($reqParam, "filedata" ) ;
		$filename =getKeyVal($reqParam, "filename" ) ;
		$fileext =getKeyVal($reqParam, "fileext" ) ;   

		$filePath = "uploads/".$filename.(time().uniqid()).  date("Y-m-d") . $fileext;
 	      $fileResult = array("path"=>$filePath); 
 	     base64_to_jpeg( $filedata , $filePath )  ;

		$sqlStatement = "INSERT INTO  Photo ( 
						`Url` ,
						`Blob` ,
						`createdDate` ,
						`createdBy` ,
						`activityId`
 						)  ";  

		$valueStatement ="VALUES (";
		$url =getKeyVal($reqParam, "url" ) ;
		$url =$filePath;
		if ( !empty($url )) { 
			$valueStatement =$valueStatement . "'". $url."',";
		} 

		$blob =getKeyVal($reqParam, "blob" ) ;
		if ( !empty($blob )) { 
			$valueStatement =$valueStatement . "'". $blob."',";
		} 
		$createdDate =getKeyVal($reqParam, "createdDate" ) ;
		if ( !empty($createdDate )) { 
			$valueStatement =$valueStatement . "'". $createdDate."',";
		} 
		$createdBy =getKeyVal($reqParam, "createdBy" ) ;
		if ( !empty($createdBy )) { 
			$valueStatement =$valueStatement . "'". $createdBy."',";
		} 
		$activityId =getKeyVal($reqParam, "activityId" ) ;
		if ( !empty($activityId )) { 
			$valueStatement =$valueStatement . "'". $activityId."' ";
		}  

		$valueStatement =$valueStatement ." )";  
		    $data =crudDB( $sqlStatement .$valueStatement );     

		  $result = array("photoId"=>$data->insert_id,"status"=> true,"path"=>$filePath); 
	     getJsonResponse( $app,  $result ); 
 
  }

 function deletePhoto( $app,$activityId) { 
  
		$reqParam = getJsonRequest( $app);    

		$sqlStatement = "DELETE FROM Photo
						WHERE Id= ";   
		$id =$activityId;
		if ( !empty($id )) { 
			$sqlStatement =$sqlStatement . "'". $id."'"; 
		    $data =crudDB( $sqlStatement  ); 
  

		  	 $result = array( "status"=> true ,"data"=> $data); 
		     getJsonResponse( $app,  $result ); 

		}  
  
 
  }
 
 function addComment( $app) {
 // :Id, :Title, :Description,:createdDate,:createdBy,:activityId
  
		$reqParam = getJsonRequest( $app);    

		$sqlStatement = "INSERT INTO  Comment ( 
						`Title` ,
						`Desc` ,
						`createdDate` ,
						`createdBy` ,
						`activityId`
 						)  ";  

		$valueStatement ="VALUES (";
		$title =getKeyVal($reqParam, "title" ) ;
		if ( !empty($title )) { 
			$valueStatement =$valueStatement . "'". $title."',";
		} 

		$desc =getKeyVal($reqParam, "desc" ) ;
		if ( !empty($desc )) { 
			$valueStatement =$valueStatement . "'". $desc."',";
		} 
		$createdDate =getKeyVal($reqParam, "createdDate" ) ;
		if ( !empty($createdDate )) { 
			$valueStatement =$valueStatement . "'". $createdDate."',";
		} 
		$createdBy =getKeyVal($reqParam, "createdBy" ) ;
		if ( !empty($createdBy )) { 
			$valueStatement =$valueStatement . "'". $createdBy."',";
		} 
		$activityId =getKeyVal($reqParam, "activityId" ) ;
		if ( !empty($activityId )) { 
			$valueStatement =$valueStatement . "'". $activityId."' ";
		} 


		$valueStatement =$valueStatement ." )";  
		    $data =crudDB( $sqlStatement .$valueStatement ); 
		 

		  $result = array("commentId"=>$data->insert_id,"status"=> true ); 
	     getJsonResponse( $app,  $result ); 
 
  }

 function deleteComment( $app,$activityId) { 
  
		$reqParam = getJsonRequest( $app);    

		$sqlStatement = "DELETE FROM Comment
						WHERE Id= ";   
		$id =$activityId;
		if ( !empty($id )) { 
			$sqlStatement =$sqlStatement . "'". $id."'"; 
		    $data =crudDB( $sqlStatement  ); 

		  	 $result = array( "status"=> true ,"data"=> $data); 
		     getJsonResponse( $app,  $result ); 
		}     

  
  }


function addActivityType( $app ) {  

		$reqParam = getJsonRequest( $app);    

		$sqlStatement = "INSERT INTO  ActivityType ( 
						`Name`  )  ";  

		$valueStatement ="VALUES (";
		$name =getKeyVal($reqParam, "name" ) ;
		if ( !empty($name )) { 
			$valueStatement =$valueStatement . "'". $name."' ";
		}   

		$valueStatement =$valueStatement ." )";  
		  $data =crudDB( $sqlStatement .$valueStatement ); 
	  

		  $result = array("activityTypeId"=>$data->insert_id,"status"=> true ); 
	     getJsonResponse( $app,  $result );  

}

function deleteActivityType( $app,$id  ) {  	
	$reqParam = getJsonRequest( $app);    

		$sqlStatement = "DELETE FROM ActivityType
						WHERE Id= ";   
	 
		if ( !empty($id )) { 
			$sqlStatement =$sqlStatement . "'". $id."'"; 
		    $data =crudDB( $sqlStatement  );

		  	 $result = array( "status"=> true ,"data"=> $data); 
		     getJsonResponse( $app,  $result ); 
 
		}  
 
 
}

function addUser( $app )  {  
		$reqParam = getJsonRequest( $app);    
  		$joinerFbUsername =getKeyVal($reqParam, "joinerFbUsername" ) ;
	
		$sqlStatement = "Select 
						`id` as userId ,  
						`joinerFbUsername` ,
						`joinerImageUrl` ,
						`name`  ,
						`qualification`   
 						 from Joiner  where joinerFbUsername='".	$joinerFbUsername."'";
  		$data =queryDB( $sqlStatement ); 
		  if( empty($data )){
		  		$sqlStatement = "INSERT INTO  Joiner ( 
						`joinerFbUsername` ,
						`joinerImageUrl` ,
						`name`  ,
						`qualification`   
 						)  ";  

		$valueStatement ="VALUES (";
		$joinerFbUsername =getKeyVal($reqParam, "joinerFbUsername" ) ;
		if ( !empty($joinerFbUsername )) { 
			$valueStatement =$valueStatement . "'". $joinerFbUsername."',";
		} 

		$joinerImageUrl =getKeyVal($reqParam, "joinerImageUrl" ) ;
		if ( !empty($joinerImageUrl )) { 
			$valueStatement =$valueStatement . "'". $joinerImageUrl."',";
		} 
		$name =getKeyVal($reqParam, "name" ) ;
		if ( !empty($name )) { 
			$valueStatement =$valueStatement . "'". $name."' ,";
		}  
		$qualification =getKeyVal($reqParam, "qualification" ) ;
		if ( !empty($qualification )) { 
			$valueStatement =$valueStatement . "'". $qualification."' ";
		}   

		$valueStatement =$valueStatement ." )";  
	    $mysqli =crudDB( $sqlStatement .$valueStatement ); 
 
		  $result = array("userId"=>$mysqli->insert_id,"status"=> true); 
	     getJsonResponse( $app,  $result ); 
		  }else{ 
		  	$data[0]['status']=true;
				getJsonResponse( $app,  $data[0] );  
		  }
		

}

function base64_to_jpeg($base64_string, $output_file) {

	//  $filename_path = md5(time().uniqid()).".jpg";  
	// $output_file="uploads/".$filename_path;

    $ifp = fopen($output_file, "wb"); 

    $data = explode(',', $base64_string);

    fwrite($ifp, base64_decode($data[1])); 
    fclose($ifp); 

    return $output_file; 
}
function deleteUser(  $app,$joinerFbUsername )  {  
  
		$reqParam = getJsonRequest( $app);    

		$sqlStatement = "DELETE FROM Joiner
						WHERE joinerFbUsername= ";   
		 
		if ( !empty($joinerFbUsername )) { 
			$sqlStatement =$sqlStatement . "'". $joinerFbUsername."'"; 
		    $data =crudDB( $sqlStatement  ); 
		  	 $result = array( "status"=> true ,"data"=> $data); 
		     getJsonResponse( $app,  $result ); 
		}  
 

 
}
function addEventParticipation( $app )  {   
 
		$reqParam = getJsonRequest( $app);   

		$sqlStatement = "INSERT INTO  Joining ( 
						`activityId` ,
						`joinerId` ,
						`isSpeaker`  
 						)  ";  

		$valueStatement ="VALUES (";
		$activityId =getKeyVal($reqParam, "activityId" ) ;
		if ( !empty($activityId )) { 
			$valueStatement =$valueStatement . "'". $activityId."',";
		} 

		$joinerId =getKeyVal($reqParam, "joinerId" ) ;
		if ( !empty($joinerId )) { 
			$valueStatement =$valueStatement . "'". $joinerId."',";
		} 
		$isSpeaker =getKeyVal($reqParam, "isSpeaker" ) ;
		if ( !empty($isSpeaker )) { 
			$valueStatement =$valueStatement . "'". $isSpeaker."' ";
		}   

		$valueStatement =$valueStatement ." )";  
	    $data =crudDB( $sqlStatement .$valueStatement ); 
		     
		  $result = array("joiningId"=>$data->insert_id,"status"=> true ); 
	     getJsonResponse( $app,  $result ); 

}

function deleteEventParticipation( $app,$joinerId ) { 
		$reqParam = getJsonRequest( $app);    
		$sqlStatement = "DELETE FROM Joining
						WHERE joinerId= ";   
		 
		$activityId =getKeyVal($reqParam, "activityId" ) ;
		if ( !empty($joinerId ) &&	!empty($activityId )) { 
			$sqlStatement =$sqlStatement . "'". $joinerId."'";  
		 	$sqlStatement =$sqlStatement . " and activityId='". $activityId."'";  
		    $data =crudDB( $sqlStatement  ); 
		  	 
		  	 $result = array( "status"=> true ,"data"=> $data); 
		     getJsonResponse( $app,  $result ); 
 
		} 
		else{ 
				throw new Exception("FAILED"   );
		}

	     
}

 function getJoiningListing( $app ) {
 
			
		$reqParam = getJsonRequest( $app);    
 
		$sqlStatement =' SELECT j.Id  ,
						j.ActivityId  , 
						 j.isSpeaker,
						a.Name  ,
						a.Description ,
						a.isPrivate ,
						a.joinersLimit  ,
						a.FromPeriod,
						a.ToPeriod,
						a.ActivityType as activityId ,  
						t.name as activityDesc ,   
						a.Address  ,
						a.LatLoc ,
						a.LngLoc ,
						a.createdBy as userCreatedId,
						a.createdDate ,
						j.JoinerId as joinerId,	 
						user.JoinerFbUsername  as joinerFbUsername,   
						user.JoinerImageUrl   as joinerImageUrl, 
						user.Name  as  joinerName, 
						user.Qualification as  joinerQualification  
					    FROM Joining j, ActivityType t, Joiner user, Activity a  ';
  
 		// $joinStatement=" 
 		// LEFT OUTER JOIN Photo photo ON a.photoid = photo.Id 
 		// LEFT OUTER JOIN Comment comment ON a.commentid = comment.Id   
 		//  ";
		$whereStatement = " where 1=1 and j.ActivityId=a.Id and j.joinerId=user.Id  and a.ActivityType=t.Id  " ;  
 
		$orderStatement = "  ORDER BY a.FromPeriod ASC  " ;  
		$filterActivityId =getKeyVal($reqParam, "activityId" ) ;
		if ( !empty($filterActivityId )) { 
			$whereStatement =$whereStatement . " and j.ActivityId = '". $filterActivityId."'";
		} 

		$filterjoinerId =getKeyVal($reqParam, "joinerId" ) ;
		if ( !empty($filterjoinerId )) { 
			$whereStatement =$whereStatement . " and j.JoinerId = '". $filterjoinerId."'";
		} 

		//MONTH FILTER
		$filterFromPeriod =getKeyVal($reqParam, "fromPeriod" ) ;
		$filterToPeriod =getKeyVal($reqParam, "toPeriod" ) ; 
	 	$month=getMonth( );
		$filterMonth =getKeyVal($reqParam, "month" ) ;
		if ( !empty($filterMonth )) { 
			$valueRangeList=explode("|",$month[$filterMonth ]); 
			if ( !empty($valueRangeList[0] )) { 
				$filterFromPeriod =$valueRangeList[0]; 
				$filterToPeriod =$valueRangeList[1];
			}
		} 

		if ( !empty($filterFromPeriod )) { 
			$whereStatement =$whereStatement . " and a.fromPeriod >='". $filterFromPeriod."'";
		} 

		if ( !empty($filterToPeriod )) { 
			$whereStatement =$whereStatement . " and a.toPeriod < '". $filterToPeriod."'";
		} 

 

		$filterName =getKeyVal($reqParam, "name" ) ;
		if ( !empty($filterName )) { 
			$whereStatement =$whereStatement . " and a.name like '%". $filterName."%'";
		} 

		$filterDescription =getKeyVal($reqParam, "description" ) ;
		if ( !empty($filterDescription )) { 
			$whereStatement =$whereStatement . " and a.description like '%". $filterDescription."%'";
		} 
		$filterAddress =getKeyVal($reqParam, "address" ) ;
		if ( !empty($filterAddress )) { 
			$whereStatement =$whereStatement . " and a.address like '%". $filterAddress."%'";
		} 
  
		$filterCreatedDate =getKeyVal($reqParam, "createdDate" ) ;
		if ( !empty($filterCreatedDate )) { 
			$whereStatement =$whereStatement . " and a.CreatedDate   ='". $filterCreatedDate."'";  
		} 
		$filterCreatedBy =getKeyVal($reqParam, "createdBy" ) ;
		if ( !empty($filterCreatedBy )) { 
			$whereStatement =$whereStatement . " and a.CreatedBy = '%". $filterCreatedBy."%'";
		} 

		$sqlStatement = $sqlStatement. $whereStatement . $orderStatement ." ; ";
		   $data =queryDB( $sqlStatement   ); 
		  getJsonResponse( $app,$data);    
 
  }

 function getJoinerListing( $app ) {
		$reqParam = getJsonRequest( $app);   
		$sqlStatement =' SELECT Id  ,
						JoinerFbUsername  , 
						JoinerImageUrl 
						  FROM Joiner ';
		$whereStatement = " where 1=1 " ;

		 

		$filterJoinerImageUrl =getKeyVal($reqParam, "joinerImageUrl" ) ;
		if ( !empty($filterJoinerImageUrl )) { 
			$whereStatement =$whereStatement . " and JoinerImageUrl like '%". $filterJoinerImageUrl."%'";
		} 

		$filterJoinerFbUsername =getKeyVal($reqParam, "joinerFbUserName" ) ;
		if ( !empty($filterJoinerFbUsername )) { 
			$whereStatement =$whereStatement . " and JoinerFbUsername like '%". $filterJoinerFbUsername."%'";
		}  

		$sqlStatement = $sqlStatement. $whereStatement . " ; ";
		$data =queryDB( $sqlStatement ); 
		 getJsonResponse( $app,$data);    
 
  }


 function getFriendsListing ( $app ) {
    $reqParam = getJsonRequest( $app);    
	getJsonResponse( $app, getKeyVal($reqParam, "A" ) ); 
  }
 ?>