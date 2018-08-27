var tweetWrapper = document.querySelector(".tweets");

const grabLatestTweets = async () => {
    var resource = new Request('/resources/twitter');
    let tweets = await fetch(resource);
    let newTweetsHTML = '';
    tweets = await tweets.json();
    tweets.map(async (tweet) => {
        newTweetsHTML += `
            <div class="tweet">
                <img src='${tweet.user.profile_image_url}'/>
                <div>
                    <p><strong>${tweet.user.name}</strong> <span class="tweet-meta created_at">on ${tweet.created_at}</span></p>
                    <p>${tweet.text}</p>
                    <p class="tweet-meta">Buzz: <strong>${tweet.favorite_count}</strong> likes, <strong>${tweet.retweet_count}}</strong> retweets</span>
                </div>
                <audio data-order="${tweet.order}"  src="/assets/wav/${tweet.order}.wav"></audio>
            </div>
        `;
        tweetWrapper.innerHTML = newTweetsHTML;
        audioTuneTweets();
    });
};
const updateTweets = async () => {
    tweetWrapper.innerHTML = `<div class="tweet"><div></div><p class="updating">Refreshing content...</p></div>`;
    window.setTimeout(grabLatestTweets, 2500); //this is just to force the above message to stay on the screen for a few secs so that it can be read 
};

//attaches audio play to tweets on click event
const audioTuneTweets = () => {
    let tweetsCollection = document.querySelectorAll(".tweet");
    tweetsCollection.forEach((tweet, index) => {
    tweet.onclick = (click) => {
        const audio = document.querySelector(`audio[data-order="${index}"]`);
        console.log(audio);
        if (!audio) {
            return;
        }
        audio.play();
    };
    });
}

audioTuneTweets();
window.setInterval(updateTweets, 10000);