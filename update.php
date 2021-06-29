<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="posts_style.css">
    </head>
    <body>
        <form action="index.php" method="POST">
            <?php
                function console_log( $data )
                {
                    echo '<script>';
                    echo 'console.log('. json_encode( $data ) .')';
                    echo '</script>';
                }
                function vk_request($method, $params)
                {
                
                
                    return $result = json_decode(file_get_contents('https://api.vk.com/method/'.$method.'?'. $params));
                
                
                }
                function facebook_api_request($request_str, $access_token)
                {
                    require_once __DIR__ . '/vendor/autoload.php';   

                    $fb = new \Facebook\Facebook([
                      'app_id' => facebook_app_id,           
                      'app_secret' => facebook_app_secret,   
                      'graph_api_version' => graph_api_version,
                    ]);
                    
                    
                    try {
                       
                    
                      $response = $fb->get($request_str, $access_token);
                    
                    } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                            // Returns Graph API errors when they occur
                      echo 'Graph returned an error: ' . $e->getMessage();
                      exit;
                    } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                            // Returns SDK errors when validation fails or other local issues
                      echo 'Facebook SDK returned an error: ' . $e->getMessage();
                      exit;
                    } 
                    $response_array = $response->getDecodedBody();
                    return $response_array;
                }

                function prepares($mysqli ,&$stmt_select, &$stmt_update, &$stmt_new, &$stmt_date)
                {
                    $stmt_select = $mysqli->prepare('SELECT Owner_Name FROM posts WHERE Post_Id=? and Owner_Id=?');

                    $stmt_new = $mysqli->prepare('INSERT INTO posts ( Owner_Name,
                                                                  Owner_Id,
                                                                  Post_Id,
                                                                  Text,
                                                                  Publish_Date,
                                                                  Link,
                                                                  Likes,
                                                                  Comments,
                                                                  Reposts,
                                                                  Is_Posted,
                                                                  Photo_Srcs,
                                                                  Platform)
                                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?)');

                    $stmt_update = $mysqli->prepare('UPDATE posts
                                                     SET Text = ?, 
                                                         Likes = ?, 
                                                         Comments = ?, 
                                                         Reposts = ?, 
                                                         Photo_Srcs = ?
                                                     where Platform=? and Post_Id=? and Owner_Id=?');

                    $stmt_date = $mysqli->prepare('UPDATE owners 
                                                   SET Last_Post_Date = ?
                                                   WHERE Owner_Id=? and Platform=?');
                }

//---------------------------------------------------------------------------------------------------------------------------------
                function update_DB_Instagram($name, $owner_id)
                {
                    $mysqli = new mysqli(Server, DB_UserName, DB_Password, DB_Name);
                    $response_array = facebook_api_request($owner_id.'/media?fields=like_count,comments_count,permalink,caption,media_type,timestamp,children,media_url', FACEBOOK_ACCESS_TOKEN);
                    console_log($response_array);
                    $platform = "instagram.com";
                    $max_date = 0;
                    $reposts = -1;
                    
                    prepares($mysqli, $stmt_select, $stmt_update, $stmt_new, $stmt_date);
                    
                    
                    foreach ($response_array['data'] as $post)
                    {
                        
                        $message = "";
                        $post_id = $post['id'];
                        $srcs = "";
                        $comments = $post['comments_count'];
                        $likes = $post['like_count'];
                        print($likes);
                        $link = $post['permalink'];
                        $date = strtotime($post['timestamp']);
                        if ($max_date < $date)
                        {
                            $max_date = $date;
                        }
                        if(isset($post['caption']))
                        {
                           $message = $post['caption'];
                        }
                        if(isset($post['children']))
                        {
                           foreach($post['children']['data'] as $child)
                          {
                             $response = facebook_api_request($child['id'].'?fields=media_type,media_url', FACEBOOK_ACCESS_TOKEN);
                             
                             $srcs .= $response['media_url'] . ' ';
                             

                          }
                        }
                        else
                        {
                           $srcs = $post['media_url'];
                        }
                       
                        $stmt_select->bind_param('ss', $post_id, $owner_id);
                        $stmt_select->execute();
                        $stmt_select->bind_result($row);
                        $stmt_select->fetch();
                        $stmt_select->reset();
                                                      
                        
                        if(!isset($row))
                        {
                            $date = date("Y-n-j", $date);
                            
                            
                            
                            if(!$stmt_new->bind_param('ssssssiiiss', $name, $owner_id, $post_id, $message, $date, $link, $likes, $comments, $reposts, $srcs, $platform))
                            {
                                echo "Не удалось привязать параметры";
                            }
                            
                            if(!$stmt_new->execute())
                            {
                                echo "no execute new";
                            }
                            
                        }
                        else
                        {
                            $stmt_update->bind_param('siiissss', $post_text, $likes, $comments, $reposts, $srcs, $platform, $post_id, $owner_id);
                            $stmt_update->execute();
                        }
                        
                    }
                    $max_date;
                    console_log($max_date);
                    $stmt_date->bind_param('iss', $max_date, $owner_id, $platform);
                    if(!$stmt_date->execute())
                            {
                                echo "no execute date";
                            }

                }



// ---------------------------------------------------------------------------------------

                function update_DB_Facebook($name, $owner_id)
                {
                    $platform = "facebook.com";
                    $response_array = facebook_api_request($owner_id.'/feed?fields=reactions,comments,shares,attachments,created_time,message', FACEBOOK_ACCESS_TOKEN);
                    
                    $max_date = 0;
                    $mysqli = new mysqli(Server, DB_UserName, DB_Password, DB_Name);
                    prepares($mysqli, $stmt_select, $stmt_update, $stmt_new, $stmt_date);
                    foreach($response_array['data'] as $post)
                    {
                       
                        console_log($post);
                        $likes = 0;
                        $comments = 0;
                        $reposts = 0;
                        $message = "";
                        $post_id = explode("_", $post['id'])[1];
                        $link = 'http://facebook.com/'. $owner_id . '/posts/' . $post_id;
                        $srcs = "";
                        if(isset($post['message']))
                        {
                            $message = $post['message'];
                           
                        }
                        if(isset($post['reactions']))
                        {
                            $likes = count($post['reactions']['data']);
                            
                        }
                        if(isset($post['comments']))
                        {
                            $comments = count($post['comments']['data']);
                            
                        }
                        if(isset($post['shares']))
                        {
                            $reposts = $post['shares']['count'];
                            
                        }
                        $date = strtotime($post['created_time']);
                        console_log($date);
                        if ($max_date < $date)
                        {
                            $max_date = $date;
                        }
                        if(isset($post['attachments']['data'][0]['subattachments']))
                        {
                            foreach($post['attachments']['data'][0]['subattachments']['data'] as $subattachment)
                            {
                                if(strcmp($subattachment['type'], "photo") == 0)
                                {
                                    $src = $subattachment['media']['image']['src'];
                                    $srcs .= $src .' ';

                                    
                                    
                                }
                            }
                        }
                        else if (isset($post['attachments']))
                        {
                            if(strcmp($post['attachments']['data'][0]['type'], "photo") == 0)
                            {
                                $srcs = $post['attachments']['data'][0]['media']['image']['src'];
                               
                                
                            }
                        }
                        
                        $stmt_select->bind_param('ss', $post_id, $owner_id);
                        $stmt_select->execute();
                        $stmt_select->bind_result($row);
                        $stmt_select->fetch();
                        $stmt_select->reset();
                                                      
                        
                        if(!isset($row))
                        {
                            console_log($date);
                            $str_date = date("Y-n-j", $date);
                                   
                            if(!$stmt_new->bind_param('ssssssiiiss', $name, $owner_id, $post_id, $message, $str_date, $link, $likes, $comments, $reposts, $srcs, $platform))
                            {
                                echo "Не удалось привязать параметры";
                            }
                            if(!$stmt_new->execute())
                            {
                                echo "no execute";
                            }
                            
                        }
                        else
                        {
                            
                            console_log("---------------------------------------------------------------");
                            if(!$stmt_update->bind_param('siiissss', $message, $likes, $comments, $reposts, $srcs, $platform, $post_id, $owner_id))
                            {
                                console_log("bind err");
                            }
                            if(!$stmt_update->execute())
                            {
                                console_log($stmt_update->error);
                            }
                        }
                        
                        
                    }
                    
                    
                    if(!$stmt_date->bind_param('iss', $max_date, $owner_id, $platform))
                    {
                        console_log("no param date");
                    }
                    
                    if(!$stmt_date->execute())
                    {
                        console_log("no exec date");
                    } 
                }

    //------------------------------------------------------------------------------------------------------------------            
                function update_DB_VK($result, $name)
                {
                    $platform = "vk.com";
                    console_log($result);
                    $mysqli = new mysqli(Server, DB_UserName, DB_Password, DB_Name);
                    prepares($mysqli, $stmt_select, $stmt_update, $stmt_new, $stmt_date);
                    $items = $result -> response -> items;
                    $owner_id = $items[0] -> owner_id;
                    $max_date = 0;
                    
                    foreach ($items as $post)
                    {
                        if($max_date < $post->date)
                        {
                            $max_date = $post->date;
                            
                        }
                        $Photo_srcs="";
                        
                        console_log($post->id);
                        console_log($post->owner_id);
                        $stmt_select->bind_param('ss', $post->id, $post->owner_id);
                        $stmt_select->bind_result($row);
                        if(!$stmt_select->execute())
                        {
                            print("no execute select");
                        } 
                        $stmt_select->fetch();
                        $stmt_select->reset();
                               
                        if (isset($post->attachments))
                        {
                            foreach($post->attachments as $attachment)
                            {
                                if(strcasecmp($attachment->type, 'photo') == 0)
                                {
                                    $max_width = 0;
                                    $max_src = "";
                                    foreach($attachment->photo->sizes as $size)
                                    {
                                        if ($max_width < $size->width)
                                        {
                                            $max_width = $size->width;
                                            $max_src = $size->url;
                                        }
                                    }
                                    
                                    $Photo_srcs .= $max_src . " "; 
                                   
                                }
                            }
                        }
                        if(!isset($row))
                        {
                            //console_log("!isset");
                            $date = date("Y-n-j", $post->date);
                            $link = 'http://vk.com/wall'.$post->owner_id .'_'. $post->id;
                            
                            if(!$stmt_new->bind_param('ssssssiiiss', $name, 
                                                                     $post->owner_id, 
                                                                     $post->id, 
                                                                     $post->text, 
                                                                     $date, 
                                                                     $link, 
                                                                     $post->likes->count, 
                                                                     $post->comments->count, 
                                                                     $post->reposts->count, 
                                                                     $Photo_srcs, 
                                                                     $platform))
                            {
                                echo "Не удалось привязать параметры";
                            }
                            
                            if(!$stmt_new->execute())
                            {
                                echo "no execute new";
                            }
                            
                        }
                        else
                        {
                            
                            
                            $stmt_update->bind_param('siiissss', $post->text, 
                                                                 $post->likes->count, 
                                                                 $post->comments->count, 
                                                                 $post->reposts->count, 
                                                                 $Photo_srcs, 
                                                                 $platform, 
                                                                 $post->id, 
                                                                 $post->owner_id);
                            
                            if(!$stmt_update->execute())
                            {
                                printf("execute update err");
                            }
                        }
                        
                    }
                    if(!$stmt_date->bind_param('iss', $max_date, $owner_id, $platform))
                    {
                        console_log("no param date");
                    }
                    
                    if(!$stmt_date->execute())
                    {
                        console_log("no exec date");
                    } 
                   
                }
            
            
            
                require_once("config.php");
                

                $link = mysqli_connect(Server, DB_UserName, DB_Password, DB_Name);
                
                if (isset($_POST['update']))
                {
                    $input = explode ("_", key($_POST['update']));
                    $platform = $input[0];
                    $id = $input[1];
                    
                    $sql = 'SELECT *
                            FROM owners
                            where Owner_Id='. $id .' and Platform="'.$platform.'"';
                    $result = mysqli_query($link, $sql);
                    $row = mysqli_fetch_array($result);
                    switch($platform)
                    {
                        case "vk.com":
                            $request_params = array(
                                    'owner_id' => $id,
                                    'count' => 5,
                                    'v' => '5.130',
                                    'access_token' => VK_ACCESS_TOKEN
                                );
                            $params = http_build_query($request_params);
                            $result = vk_request("wall.get", $params);
                            update_DB_VK($result, $row['Owner_Name']); 
                            break;
                    
                        case "facebook.com":
                            update_DB_Facebook($row['Owner_Name'], $row['Owner_Id']);
                            break;
                        case "instagram.com":
                            console_log('insta');
                            update_DB_Instagram($row['Owner_Name'], $row['Owner_Id']);
                            break;
                    }
                    
                    echo '<table>
                            <tr>
                                <td>
                                    Посты успешно обновлены
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input class="button" type = "submit" value="Вернуться">
                                </td>
                            </tr>
                          </table>';
                }     
                else
                {
                    $input = explode ("_", key($_POST['delete']));
                    $platform = $input[0];
                    $id = $input[1];
                    
                    $sql = 'DELETE 
                            FROM owners
                            where Owner_Id='. $id.' and Platform="'.$platform.'"';

                    $result = mysqli_query($link, $sql);
                    $sql = 'DELETE 
                            FROM posts
                            where Owner_Id='. $id.' and Platform="'.$platform.'"';
                    $result = mysqli_query($link, $sql);
                    echo '<table>
                    <tr>
                        <td>
                            Успешно удалено!
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="button" type = "submit" value="Вернуться">
                        </td>
                    </tr>
                  </table>';
                }
                    
            ?>
        </form>
    </body>
</html>