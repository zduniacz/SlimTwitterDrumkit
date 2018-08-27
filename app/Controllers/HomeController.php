<?php
namespace App\Controllers;

class HomeController extends BaseController
{
    public function index($request, $response)
    {
        $latestTweets = $this->container->twitter->getLatestTweets();
        
        return $this->container->view->render($response, 'home.twig', [
            'tweets' => $latestTweets
        ]);
    }
}