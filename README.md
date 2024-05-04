## About Defend Armenia Project

Defend Armenia is build with Laravel Framework

- Laravel version ~10.10
- Minimum php version 8.1
- Mysql version ~8.0

It is using Twitter API V2 to find latest tweets based on the provided search terms. 
Twitter API documentation can be found **[here](https://developer.twitter.com/en/docs/twitter-api)**

## Main Items

- App\API\Twitter\Concretes\V2\TwitterApi.php
  - This class is responsible for making requests to Twitter API V2
  - getDetailsOfUserFor(App\Models\OauthCredential $oauthCredential)
    - This method is responsible for getting user details from Twitter API V2 , OauthCredential is the opted in Twitter (X) account info
- App\Jobs\SearchForTweets
  - Main job to search for tweets based on the provided search terms, it is set to run every hour in cronjob scheduler
- App\Jobs\SearchForTweets
  - Main job to search for tweets based on the provided search terms, it is set to run every hour in cronjob scheduler
- App\Jobs\ReplyToTweetJob
  - Main job to reply to tweets based on the provided search terms 


## Main Functionality

After logging in with admin credentials, through the dashboard, the admin can add search terms and reply to them, 
i.e. if we want to look for all recent tweets with the word "Armenia" in them, we can add "Armenia" as a search term 
and then provide multiple reply options to choose from. The system will then search for tweets with the word "Armenia"
and reply to them with one of the provided replies.
  
