<style>
    /**
    Coded by /u/j0be in scss.
    See scss source here -> https://codepen.io/j0be/pen/MKRVyN
 */

    .ad { margin: 20px auto; max-width: 768px; background-image: url("http://www.seancrater.com/codepen/banner.jpg"); background-size: cover; background-position: left center; border: 1px solid #333; } .ad .container { clear: both; padding: 1px 0 0 0; } .ad .container .logo { width: 283px; height: 50px; margin: 20px auto; background-image: url("http://www.seancrater.com/codepen/timb2.png"); background-size: cover; background-position: center center; } .ad .container a { display: block; color: #ffffff; text-decoration: none; } .ad h2 { background-color: rgba(245, 160, 25, 0.8); padding: 10px 20px; font-size: 2.15em; line-height: 70px; font-family: 'Oswald', sans-serif; text-transform: uppercase; transition: all .15s ease; text-align: center; } .ad h2:hover { background-color: rgba(245, 160, 25, 0.8); transition: all .15s ease; } @media screen and (min-width: 525px) { .ad { height: 90px; } .ad .container { padding: 0 25px; } .ad .logo { float: left; } .ad h2 { float: right; background-color: rgba(255, 255, 255, 0.25); } }
</style>

<section class="ad text-center">
    <img src="https://lh5.ggpht.com/NFYFP2H9CCP50vAQNLa7AtCj_mbbYmOzY978fZqd31oL5qOdvXgxU3KW8ek2VgvIOvTqWY0=w728" />
</section>
{!! Ads::show('rectangle') !!}