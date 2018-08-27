<?php 

namespace App\Services\Twitter;

use App\Services\Twitter\TwitterAPI;
use App\Helpers\Decorator;

/*  
    An implementation of Twitter API service using package called CodeBird.
*/

class TwitterCodebird implements TwitterAPI
{
    private $cb; // current instance of Codebird class
    public function __construct ()
    {
        // initialize Codebird with our Twitter API credentials
        \Codebird\Codebird::setConsumerKey($_ENV['TWITTER_API_CONSUMER_KEY'], $_ENV['TWITTER_API_SECRET_KEY']);
        $this->cb = \Codebird\Codebird::getInstance();
        $this->cb->setToken($_ENV['TWITTER_API_ACCESS_TOKEN'], $_ENV['TWITTER_API_ACCESS_TOKEN_SECRET']);
    }

    public function getLatestTweets()
    {
        $tweets = $this->beautify((array) $this->cb->statuses_userTimeline("screen_name={$_ENV['TWITTER_HANDLE']}&count=5"));
        return $tweets;
    }

    /*
        Beautifies tweet content received from API
        by adding clickable links to link texts
    */
    private function beautify($tweets)
    {
        if (empty($tweets)) {
            return;
        }

        $tweetsBeautified = [];
        
        foreach ($tweets as $no => $tweet) {
            // strip tweet data of meta entries, we want just the tweets
            if (!is_numeric($no)) {
                unset($tweets[$no]);
                continue;
            }
            $entititesRpl = [];
            
            //gather replacements for raw links as html links
            if (isset($tweet->text) && !empty($tweet->entities->urls)) {
                foreach($tweet->entities->urls as $url) {
                    $temp['replaceStartPosition'] = $url->indices[0];
                    $temp['replaceEndPosition'] = $url->indices[1];
                    $temp['replacedUrl'] = "<a href='".$url->expanded_url."' target='_blank'>".$url->display_url."</a>";
                    $entititesRpl[] = $temp;
                }
            }

            //gather replacements for user mentions as full links to their profiles
            if (isset($tweet->text) && !empty($tweet->entities->user_mentions)) {
                foreach($tweet->entities->user_mentions as $mention) {
                    $temp['replaceStartPosition'] = $mention->indices[0];
                    $temp['replaceEndPosition'] = $mention->indices[1];
                    $temp['replacedUrl'] = "<a href='https://twitter.com/".$mention->screen_name."' target='_blank'>@".$mention->screen_name."</a>";
                    $entititesRpl[] = $temp;
                }
            }

            //gather replacements for used hashtags as their full links
            if (isset($tweet->text) && !empty($tweet->entities->hashtags)) {
                foreach($tweet->entities->hashtags as $tag) {
                    $temp['replaceStartPosition'] = $tag->indices[0];
                    $temp['replaceEndPosition'] = $tag->indices[1];
                    $temp['replacedUrl'] = "<a href='https://twitter.com/hashtag".$tag->text."' target='_blank'>#".$tag->text."</a>";
                    $entititesRpl[] = $temp;
                }
            }

            //gather replacements for any media raw links with full html links
            if (isset($tweet->text) && !empty($tweet->entities->media)) {
                foreach($tweet->entities->media as $medium) {
                    $temp['replaceStartPosition'] = $medium->indices[0];
                    $temp['replaceEndPosition'] = $medium->indices[1];
                    $temp['replacedUrl'] = "<a href='".$medium->expanded_url."' target='_blank'>".$medium->display_url."</a>";
                    $entititesRpl[] = $temp;
                }
            }
                    
            //string replacement process in $tweet->text takes place here
            usort($entititesRpl, function($a,$b){return($b["replaceStartPosition"]-$a["replaceEndPosition"]);});
            foreach($entititesRpl as $item) {
                   $tweet->text = Decorator::replaceStringContent($tweet->text, $item['replacedUrl'], $item['replaceStartPosition'], $item['replaceEndPosition'] - $item['replaceStartPosition']);
            }


            // make date of tweet look a little prettier
            if (isset($tweet->created_at)) {
                $tweet->created_at = Decorator::humanDate($tweet->created_at);
            }
            $tweet->order = $no;
            $tweetsBeautified[] = $tweet;
        }

        return $tweetsBeautified;
    }
}