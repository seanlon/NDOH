<?php

// API group
$app->group('/v1', function () use ($app) {
        
        //import api functions
        require('apiFunctionsV1.php');
    
        // Get book with ID
        $app->get('/books/:id', function ($id) {

        });
 
        //GET ALL FRIENDS  
        $app->get(  '/friends', function() use ($app)
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
        $app->get(  '/friends', function() use ($app)
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
        $app->post(  '/friends', function() use ($app)
        {
            getFriendsListing($app);
        });
        //GET ALL activity  via post
        $app->post(  '/activity', function() use ($app)
        {
            getActivityListing($app);
        });

        //GET ALL speaker  via post
        $app->post(  '/speaker', function() use ($app)
        {
            getSpeakerListing($app);
        });

 
//GET ALL speaking  via post
$app->post(  '/speaking', function() use ($app)
{
    getSpeakingListing($app);
});

//GET ALL    via post
$app->post(  '/joiner', function() use ($app)
{
    getJoinerListing($app);
});

//GET ALL    via post
$app->post(  '/joining', function() use ($app)
{
    getJoiningListing($app);
});

//Add new activity  via post
$app->post(  '/event', function() use ($app)
{
    addNewActivityEvent($app);
});
//Add new activity  via post
$app->put(  '/event', function() use ($app)
{
    editActivityEvent($app);
});
//del activity via delete
$app->delete(  '/event/:activityId', function($activityId) use ($app)
{
    deleteActivityEvent($app, $activityId);
});

//del photo   via delete
$app->delete(  '/photo/:activityId', function($activityId) use ($app)
{
    deletePhoto($app, $activityId);
});

//add photo  via post
$app->post(  '/photo', function() use ($app)
{
    addphoto($app);
});

//del ActivityType via delete
$app->delete(  '/ActivityType/:id', function($id) use ($app)
{
    deleteActivityType($app, $id);
});

//add ActivityType via post
$app->post(  '/ActivityType', function() use ($app)
{
    addActivityType($app);
});


//del User via delete
$app->delete(  '/user/:joinerFbUsername', function($joinerFbUsername) use ($app)
{
    deleteUser($app, $joinerFbUsername);
});
//add User via post
$app->post(  '/user', function() use ($app)
{
    addUser($app);
});

//modify User approval via  put
$app->put(  '/UserApproval', function() use ($app)
{
    modifyUserStatus($app);
});
//FORM SERVICE
$app->post(  '/FormService', function() use ($app)
{
    getFormService($app);
});

//del EventParticipation via delete
$app->delete(  '/EventParticipation/:joinerId', function($joinerId) use ($app)
{
    deleteEventParticipation($app, $joinerId);
});
//add EventParticipation  via post
$app->post(  '/EventParticipation', function() use ($app)
{
    addEventParticipation($app);
});

//del comment via delete
$app->delete(  '/comment/:activityId', function($activityId) use ($app)
{
    deleteComment($app, $activityId);
});

//add comment  via post
$app->post(  '/comment', function() use ($app)
{
    addComment($app);
});


//GET ALL Joiner  
$app->get(  '/joiner', function() use ($app)
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

});

?>