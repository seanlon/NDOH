<?php

// API group
$app->group('/v2', function () use ($app) {
        
     
  
        //GET ALL activity  via post
        $app->post(  '/activity', function() use ($app)
        {

            //import api functions
            require_once 'apiFunctionsV2.php' ;
            getActivityListing($app);
        });

        //GET ALL speaker  via post
        $app->post(  '/speaker', function() use ($app)
        {
            //import api functions
            require_once 'apiFunctionsV2.php' ;
            getSpeakerListing($app);
        });

 
//GET ALL speaking  via post
$app->post(  '/speaking', function() use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    getSpeakingListing($app);
});

//GET ALL    via post
$app->post(  '/joiner', function() use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    getJoinerListing($app);
});

//GET ALL    via post
$app->post(  '/joining', function() use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    getJoiningListing($app);
});

//Add new activity  via post
$app->post(  '/event', function() use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    addNewActivityEvent($app);
});
//Add new activity  via post
$app->put(  '/event', function() use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    editActivityEvent($app);
});
//del activity via delete
$app->delete(  '/event/:activityId', function($activityId) use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    deleteActivityEvent($app, $activityId);
});

//del photo   via delete
$app->delete(  '/photo/:activityId', function($activityId) use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    deletePhoto($app, $activityId);
});

//add photo  via post
$app->post(  '/photo', function() use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    addphoto($app);
});

//del ActivityType via delete
$app->delete(  '/ActivityType/:id', function($id) use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    deleteActivityType($app, $id);
});

//add ActivityType via post
$app->post(  '/ActivityType', function() use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    addActivityType($app);
});


//del User via delete
$app->delete(  '/user/:joinerFbUsername', function($joinerFbUsername) use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    deleteUser($app, $joinerFbUsername);
});
//add User via post
$app->post(  '/user', function() use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    addUser($app);
});

//modify User approval via  put
$app->put(  '/UserApproval', function() use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    modifyUserStatus($app);
});
//FORM SERVICE
$app->post(  '/FormService', function() use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    getFormService($app);
});

//del EventParticipation via delete
$app->delete(  '/EventParticipation/:joinerId', function($joinerId) use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    deleteEventParticipation($app, $joinerId);
});
//add EventParticipation  via post
$app->post(  '/EventParticipation', function() use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    addEventParticipation($app);
});

//del comment via delete
$app->delete(  '/comment/:activityId', function($activityId) use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    deleteComment($app, $activityId);
});

//add comment  via post
$app->post(  '/comment', function() use ($app)
{
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    addComment($app);
});


//GET ALL Joiner  
$app->get(  '/joiner', function() use ($app)
{
    
            //import api functions
            require_once 'apiFunctionsV2.php' ;
    // include out mysql connection code and make the connection
    require_once 'lib/mysql.php';
    
    $db = connect_db();
    // query the database
    $rs = $db->query('SELECT Id  ,
                        JoinerFbUsername  , 
                        JoinerImageUrl 
                          FROM Joiner;');
    
    // convert the record set into an associative array so we can work with it easily
    $data = $rs->fetch_all(MYSQLI_ASSOC);
    
    header("Content-Type: application/json");
    echo json_encode($data, true);
    exit;
});

});

?>