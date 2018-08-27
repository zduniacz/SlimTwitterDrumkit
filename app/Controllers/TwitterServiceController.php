<?php
/*
    Handles asynchronous calls to internal Twitter Service endpoints 
*/
namespace App\Controllers;

class TwitterServiceController extends BaseController
{
    // displays latest Tweets for the Twitter Widget in JSON format
    public function getTwitterResource($request, $response)
    {
        $latestTweets = $this->container->twitter->getLatestTweets();
        $jsonResponse = $response->withJson($latestTweets, 201);
        return $jsonResponse;
    }
}