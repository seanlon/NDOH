<?php
//slim framework example from http://scottnelle.com/
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


//import api functions
require('apiFunctions.php');
$GLOBALS['pathapi'] = "/v1";
//-----------PATCH----------- //  
$app->patch('/patch', function()
{
    echo 'This is a PATCH route';
});

//-----------DELETE----------- //  
$app->delete($GLOBALS['pathapi'] . '/delete', function()
{
    echo 'This is a DELETE route';
}); //-----------PUT----------- //
$app->put($GLOBALS['pathapi'] . '/put', function()
{
    echo 'This is a PUT route';
});


// set up a route to handle requests to the root of the application
$app->get('/', function() use ($app)
{
    // include out mysql connection code and make the connection
    require_once 'lib/mysql.php';
    
    $db = connect_db();
    // query the database
    $rs = $db->query('SELECT id, name, job FROM friends;');
    
    // convert the record set into an associative array so we can work with it easily
    $data = $rs->fetch_all(MYSQLI_ASSOC);
    
    header("Content-Type: application/json");
    echo json_encode($result);
    exit;
});

//GET ALL FRIENDS  
$app->get($GLOBALS['pathapi'] . '/friends', function() use ($app)
{
    // include out mysql connection code and make the connection
    require_once 'lib/mysql.php';
    
    $db = connect_db();
    // query the database
    $rs = $db->query('SELECT id, name, job FROM friends;');
    
    // convert the record set into an associative array so we can work with it easily
    $data = $rs->fetch_all(MYSQLI_ASSOC);
    
    header("Content-Type: application/json");
    echo json_encode($data, true);
    exit;
});


//GET ALL FRIENDS  
$app->get($GLOBALS['pathapi'] . '/friends', function() use ($app)
{
    // include out mysql connection code and make the connection
    require_once 'lib/mysql.php';
    
    $db = connect_db();
    // query the database
    $rs = $db->query('SELECT id, name, job FROM friends;');
    
    // convert the record set into an associative array so we can work with it easily
    $data = $rs->fetch_all(MYSQLI_ASSOC);
    
    header("Content-Type: application/json");
    echo json_encode($data, true);
    exit;
});


//GET ALL FRIENDS  via post
$app->post($GLOBALS['pathapi'] . '/friends', function() use ($app)
{
    getFriendsListing($app);
});
//GET ALL activity  via post
$app->post($GLOBALS['pathapi'] . '/activity', function() use ($app)
{
    getActivityListing($app);
});

//GET ALL speaker  via post
$app->post($GLOBALS['pathapi'] . '/speaker', function() use ($app)
{
    getSpeakerListing($app);
});

//GET ALL speaking  via post
$app->post($GLOBALS['pathapi'] . '/speaking', function() use ($app)
{
    getSpeakingListing($app);
});

//GET ALL    via post
$app->post($GLOBALS['pathapi'] . '/joiner', function() use ($app)
{
    getJoinerListing($app);
});

//GET ALL    via post
$app->post($GLOBALS['pathapi'] . '/joining', function() use ($app)
{
    getJoiningListing($app);
});

//Add new activity  via post
$app->post($GLOBALS['pathapi'] . '/event', function() use ($app)
{
    addNewActivityEvent($app);
});
//Add new activity  via post
$app->put($GLOBALS['pathapi'] . '/event', function() use ($app)
{
    editActivityEvent($app);
});
//del activity via delete
$app->delete($GLOBALS['pathapi'] . '/event/:activityId', function($activityId) use ($app)
{
    deleteActivityEvent($app, $activityId);
});

//del photo   via delete
$app->delete($GLOBALS['pathapi'] . '/photo/:activityId', function($activityId) use ($app)
{
    deletePhoto($app, $activityId);
});

//add photo  via post
$app->post($GLOBALS['pathapi'] . '/photo', function() use ($app)
{
    addphoto($app);
});

//del ActivityType via delete
$app->delete($GLOBALS['pathapi'] . '/ActivityType/:id', function($id) use ($app)
{
    deleteActivityType($app, $id);
});

//add ActivityType via post
$app->post($GLOBALS['pathapi'] . '/ActivityType', function() use ($app)
{
    addActivityType($app);
});


//del User via delete
$app->delete($GLOBALS['pathapi'] . '/user/:joinerFbUsername', function($joinerFbUsername) use ($app)
{
    deleteUser($app, $joinerFbUsername);
});
//add User via post
$app->post($GLOBALS['pathapi'] . '/user', function() use ($app)
{
    addUser($app);
});


//del EventParticipation via delete
$app->delete($GLOBALS['pathapi'] . '/EventParticipation/:joinerId', function($joinerId) use ($app)
{
    deleteEventParticipation($app, $joinerId);
});
//add EventParticipation  via post
$app->post($GLOBALS['pathapi'] . '/EventParticipation', function() use ($app)
{
    addEventParticipation($app);
});

//del comment via delete
$app->delete($GLOBALS['pathapi'] . '/comment/:activityId', function($activityId) use ($app)
{
    deleteComment($app, $activityId);
});

//add comment  via post
$app->post($GLOBALS['pathapi'] . '/comment', function() use ($app)
{
    addComment($app);
});

//GET ALL Joiner  
$app->get($GLOBALS['pathapi'] . '/joiner', function() use ($app)
{
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

 
$app->run();