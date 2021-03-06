<?php
    require "./lib/twitteroauth/autoload.php";
    require "./lib/PHPExcel/PHPExcel.php";
    require './lib/FPDF/fpdf.php';
    use Abraham\TwitterOAuth\TwitterOAuth;

    define('CONSUMER_KEY', 'AiO4ZE5cmcgsYsZ92GKvZzjOl');
    define('CONSUMER_SECRET', '0thMZH91OENuzNRfL9rJ5Q5oyQCD3RfVdlNQwvkMA93oPTL78n');
    define('OAUTH_CALLBACK', 'https://rtcamp.000webhostapp.com/callback.php');

    /**
    * #This is Model Class which include all the functionality of the project.
    * @category PHP
    * @author "Mitesh Thakor"
    *
    */
    class Model {

        /*
        * Handle Twitter Connect
        *
        */
        public function twitter_connect() {
            if (!isset($_SESSION['access_token'])) {
                $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
                $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
                $_SESSION['oauth_token'] = $request_token['oauth_token'];
                $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
                $url = $connection->url('oauth/authorize', array('oauth_token' => $_SESSION['oauth_token']));
                header('location:' . $url);
            } 
            else {
                header('Location: ./home.php');
            }
        }

        /**
        * Handle Callback Functionality
        *
        */
        public function callback(){
            $request_token = [];
            $request_token['oauth_token'] = $_REQUEST['oauth_token'];
            $request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);
            $access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
            $_SESSION['access_token'] = $access_token;
            header('Location: ./home.php');
        }

        /**
        * To get connection from access_token
        * @return Connection Object
        */
        public function getConnection() {
            $access_token = $_SESSION['access_token'];
            $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
            return $connection;
        }

        /**
        * To Fetch Current User Object
        * @return user object of the currently login
        */
        public function getUser($connection) {
            $user = $connection->get("account/verify_credentials");
            return $user;
        }

        /**
        * To Fetch most recent 10 tweets of the users
        * @param = @screen_name = its screen_name of the user
        * @return 10 tweets of the user
        */
        public function getUserTweets($screen_name) {
            $connection = $this->getConnection();
            $tweets = $connection->get("statuses/user_timeline",["count" => 10, "exclude_replies" => true,"screen_name" => $screen_name]);
            foreach( $tweets as $val ) {
                $t[] = array(
                    'text' => $val->text
                );
            }
            if( count($tweets) == 0 )
                $t[] = array(
                    'text' => 'No Tweets Found'
                );
            return $t;
        }

        /**
        * To Fetch all tweets of the users
        * @param = @screen_name = its screen_name of the user
        * @return all tweets of the user
        */
        public function getUserAllTweets($screen_name) {
            $connection = $this->getConnection();
            $tweets = $connection->get("statuses/user_timeline",["count" => 200, "exclude_replies" => true,"include_rts"=>true,"screen_name" => $screen_name]);
            if( count($tweets) == 1 ) {
                $user_tweets[] = 'Soory, No Tweets Found';
                return $user_tweets;
            }
            $totalTweets[] = $tweets;
            $page = 0;
            for ($count = 200; $count <= 3200; $count += 200) { 
                $max = count($totalTweets[$page]) - 1;
                $tweets = $connection->get('statuses/user_timeline', ["count" => 200, "exclude_replies" => true,"include_rts"=>true,"screen_name" => $screen_name, 'max_id' => $totalTweets[$page][$max]->id_str]);
                if( count($tweets) == 1 ) {
                    break;
                }
                $totalTweets[] = $tweets;
                $page += 1;
            }
            $start = 1;
            $index = 0;
            foreach ($totalTweets as $page) {
                foreach ($page as $key) {
                    $user_tweets[$index++] = $key->text;
                    $start++;
                }
            }
            return $user_tweets;
        }

        /**
        * To Fetch all followers of the users
        * @param = @screen_name = its screen_name of the user
        * @return all followers of the user
        */
        public function getFollowers($screen_name) {
            $connection = $this->getConnection();
            $next = -1;
            $max = 0;
            while( $next != 0 ) {
                $friends = $connection->get("followers/list", ["screen_name"=>$screen_name,"next_cursor"=>$next]);
                $followers[] = $friends;
                $next = $friends->next_cursor;
                if($max==0)
                    break;
                $max++;
            }
            foreach( $followers as $val ) {
                foreach( $val->users as $usr ) {
                    $f[] = array(
                        'name' => $usr->name,
                        'screen_name' => $usr->screen_name,
                        'propic' => $usr->profile_image_url_https
                    );
                }
            }
            $json = array(
                'followers' => $f
            );
            echo json_encode($json);
        }

        /**
        * To Fetch followers information
        * @param = @id = its screen_name of the user
        */
        public function getFollowerInfo($id) {
            $connection = $this->getConnection();
            $user = $connection->get("users/show",['screen_name'=>$id]);
            $name = $user->name;
            $propic = $user->profile_image_url_https;
            $screen_name = $user->screen_name;
            $tweets = $this->getUserTweets($screen_name);
            $res = array(
                'name' => $name,
                'propic' => $propic,
                'tweets' => $tweets
            );
            $json = json_encode($res);
            echo $json;
        }

        /**
        * To Fetch login user information
        */
        public function getUserData() {
            $connection = $this->getConnection();
            $user = $this->getUser($connection);
            $tweets = $this->getUserTweets($user->screen_name);
            $screen_name = $user->screen_name;
            $res = array(
                'id' => $user->id,
                'name' => $user->name,
                'screen_name' => $user->screen_name,
                'propic' => $user->profile_image_url_https,
                'tweets' => $tweets,
            );
            $json = json_encode($res);
            echo $json;
        }

        /**
        * Fetch loginuser tweets and download all tweets ub CSV
        */
        public function downloadCSV() {
            $connection = $this->getConnection();
            $user = $this->getUser($connection);
            $tweets[] = $this->getUserAllTweets($user->screen_name);
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=tweets.csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            $count = count($tweets);
            for($i=0;$i<$count;$i++) {
                $c = count($tweets[$i]);
                for($j=0;$j<$c;$j++) {
                    echo $tweets[$i][$j].' , ' ;
                }
            }
        }

        /**
        * Fetch loginuser tweets and download all tweets ub XLS
        */
        public function downloadXLS() {
            $connection = $this->getConnection();
            $user = $this->getUser($connection);
            $tweets[] = $this->getUserAllTweets($user->screen_name);
            $excel = new PHPExcel();
            $count = count($tweets);
            $row = 1;
            $col = 1;
            for($i=0;$i<$count;$i++) {
                $c = count($tweets[$i]);
                for($j=0;$j<$c;$j++) {
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $tweets[$i][$j]);
                    $row++;
                }
            }
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment;filename=tweets.xlsx");
            header("Cache-Control: max-age=0");
            $file = PHPExcel_IOFactory::createWriter($excel,'Excel2007');
            $file->save("php://output");
        }

        /**
        * Fetch loginuser tweets and download all tweets ub JSON
        */
        public function downloadJSON() {
            $connection = $this->getConnection();
            $user = $this->getUser($connection);
            $tweets[] = $this->getUserAllTweets($user->screen_name);
            header('Content-disposition: attachment; filename=tweets.json');
            header('Content-type: application/json');
            header("Pragma: no-cache");
            header("Expires: 0");
            $arr = array(
                'tweets' => $tweets[0]
            );
            $arr = json_encode($arr);
            print_r($arr);
        }

        /**
        * Fetch loginuser tweets and save in user google drive
        */
        public function uploadGoogleDrive() {
            $connection = $this->getConnection();
            $user = $this->getUser($connection);
            $tweets = $this->getUserAllTweets($user->screen_name);
            return $tweets;
        }

        /**
        * Fetch public user tweets and download in pdf
        */
        public function downloadPublicUserTweets($screen_name) {
            $tweets = $this->getUserAllTweets($screen_name);
            $pdf = new FPDF();
            $pdf->AliasNbPages();
            $pdf->SetFont('Times','',12);
            $pdf->AddPage();
            $index = 1;
            foreach($tweets as $text) {
                $pdf->MultiCell(0,10,$index. ' ' . $text,0,5);
                $index++;
            }
            $pdf->Output('D',$screen_name.'.pdf');
        }

        /**
        * Handle logout functionality
        *
        */
        public function logout() {
            session_unset();
            session_destroy();
            header("location:https://rtcamp.000webhostapp.com/");
            exit();
        }

    }
?>