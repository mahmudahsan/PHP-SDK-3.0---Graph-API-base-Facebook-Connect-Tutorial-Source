<?php
    include_once "fbmain.php";
    $config['baseurl']  =   "http://thinkdiff.net/demo/newfbconnect1/php/page.php";
    
    //if user is logged in and session is valid.
    if ($fbme){
        $urllike    =   'http://thinkdiff.net';
        if (isset($_REQUEST['urllike']) && !empty($_REQUEST['urllike'])){
            $urllike=    $_REQUEST['urllike'];
        }

        //check number of like of an url
        $pagelike = '';
        try {
            $pagelike = $facebook->api("/$urllike");
        }
        catch(Exception $o) {
            d($o);
        }

        //facebook page information
        $fbpageinfo =   '';
        try {
            $fbpageinfo = $facebook->api("/thinkdiff.net");
        }
        catch(Exception $o) {
            d($o);
        }

        //publish in fanpage
        if (isset($_REQUEST['publish'])){
            try {
                  $wallpostpage = $facebook->api('/thinkdiff.net/feed', 'post',
                                  array(
                                      'message' => 'I love Thinkdiff.net for facebook and web related tutorials and iThinkdiff.net for iPhone/iPad Dictionaries.',
                                      'picture' => 'http://thinkdiff.net/ithinkdiff.png',
                                      'link'    => 'http://ithinkdiff.net',
                                      'name'    => 'iThinkdiff.net iOS App Site',
                                      'cb'      => ''
                                      )
                                  );
            } catch (FacebookApiException $e) {
                  d($e);
            }
        }

        //upload photo in facebook album
        if (isset($_REQUEST['albumid'])){
            $albumID    =   isset($_REQUEST['albumid']) ? $_REQUEST['albumid'] : '';
           
            try {
                  $uploadstatus = $facebook->api("/me/photos", 'post',
                                  array(
                                      'source'  =>  '@ithinkdiff.png',
                                      'message' => 'I love Thinkdiff.net for facebook and web related tutorials and iThinkdiff.net for iPhone/iPad Dictionaries.'
                                      )
                                  );
            } catch (FacebookApiException $e) {
                  d($e);
            }
        }

        //create events
        /*
        try {
                  $event = $facebook->api("/me/events", 'post',
                                  array(
                                        'name'       => 'Facebook Users Party',
                                        'start_time' => '1272718027',
                                        'end_time'   => '1272718027'

                                      )
                                  );
            } catch (FacebookApiException $e) {
                  d($e);
            }
            */
        

            //fql query example using legacy method call and passing parameter
        try{
            //get user id
            $uid    = $facebook->getUser();
            //or you can use $uid = $fbme['id'];

            $fql    =   "select name, cell FROM user where uid=$uid";
            $param  =   array(
                'method'    => 'fql.query',
                'query'     => $fql,
                'callback'  => ''
            );
            $fqlResult   =   $facebook->api($param);
            d($fqlResult);
        }
        catch(Exception $o){
            d($o);
        }
    }

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Essential Graph API of Facebook | Thinkdiff.net</title>
    </head>
<body>
    <div id="fb-root"></div>
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({appId: '<?=$fbconfig['appid' ]?>', status: true, cookie: true, xfbml: true});

                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
                    login();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
                    logout();
                });
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());

            function login(){
                document.location.href = "<?=$config['baseurl']?>";
            }
            function logout(){
                document.location.href = "<?=$config['baseurl']?>";
            }
</script>
<style type="text/css">
    .box{
        margin: 5px;
        border: 1px solid #60729b;
        padding: 5px;
        width: 500px;
        height: 200px;
        overflow:auto;
        background-color: #e6ebf8;
        float: left;
    }
    .fnt11{
        color: gray;
        font-size: 11px;
    }

    .status{
        border: 1px solid gray;
        background-color: #ddbcce;
        color: #1f4f19;
        padding: 10px;
        margin: 5px;
    }
</style>

    <h3>Essential Graph API of Facebook | Thinkdiff.net</h3>
    <?php if (!$fbme) { ?>
        You've to login using FB Login Button to see api calling result.
    <?php } ?>
    <p>
        <fb:login-button autologoutlink="true" perms="email,user_birthday,status_update,publish_stream,user_hometown,friends_hometown"></fb:login-button>
    </p>

    <!-- all time check if user session is valid or not -->
    <?php if ($fbme){ ?>
        <div class="box">
            <b>1. Get total number of Like of an url</b>
            <form name="" action="page.php" method="POST">
                Provide your url here: <br />
        
                <input type="text" name="urllike"  style="width: 300px;" value="<?=$urllike?>" />
                <span class="fnt11">(possible value: url, post id, page id)</span>
                <br />
                <div class="fnt11">
                    For example: <br />
                    Facebook Page: thinkdiff.net <br />
                    URL: http://thinkdiff.net/php/integrate-linkedin-api-part-2/ <br />
                </div>
                <input type="submit" value="Check" />
            </form>
            <?php
                d($pagelike);
            ?>
        </div>

        <div class="box">
            <b>2. Get brief information of a facebook page</b>
            <span clas="fnt11">(here you'll see about: thinkdiff.net facebook page)</span>
            <?php
                 d($fbpageinfo);
            ?>
        </div>
        <div style="clear: both"></div>
        <div class="box">
            <b>3. Click Below button and visit <a href="http://www.facebook.com/thinkdiff.net">Thinkdiff.net FB Page</a> to see your post </b>

            <?php if (isset($wallpostpage)) { ?>
            <div class="status">
                Post published in Facebook Page Successfully.
            </div>
            <?php } ?>
            <form action="page.php" method="POST">
                <input type="hidden" value="thinkdiff.net" name="publish" />
                <input type="submit" value="Publish in Thinkdiff.net Fan Page" />
            </form>
        </div>

        <div class="box">
            <b>4. Click Below button to upload photo in your album</b>

            <?php if (isset($uploadstatus)) { ?>
            <div class="status">
                <?php
                    if (is_array($uploadstatus) && !empty($uploadstatus['id'])){
                        echo "Post ID: " . $uploadstatus['id'] . '<br />';
                        echo "Photo Published Successfully";
                    }
                ?>
            </div>
            <?php } ?>

            <form action="page.php" method="POST" enctype="multipart/form-data" >
                Facebook Album ID: <input type="text" name="albumid" /> <br />
                <input type="submit" value="Upload Photo" />
            </form>
        </div>
    <?php } ?>

    </body>
</html>

