<?php



// Logs all the requests/responses in a specific file per day
$app->hook('slim.after', function() use ($app)
{
    $request  = $app->request();
    $response = $app->response();
    
    $app->getLog()->debug('*** Time: ' . date('Y-m-d h:i:s'));
    $app->getLog()->debug('*** Request URL: ' . $request->getUrl());
    $app->getLog()->debug('*** Request Path: ' . (string) $request->getPath());
    $app->getLog()->debug('*** Request Method: ' . (string) $request->getMethod());
    $app->getLog()->debug('*** Request Body: ' . (string) $request->getBody());
    $app->getLog()->debug('*** Response: ' . (string) $response->getBody());
    $app->getLog()->debug('====================');
    $app->getLog()->debug('');
});
?>