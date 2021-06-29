<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="posts_style.css">
    </head>
    <body>
        <form action="published_posts.php" method="POST">
            <?php
                    
                    function console_log( $data )
                {
                    echo '<script>';
                    echo 'console.log('. json_encode( $data ) .')';
                    echo '</script>';
                }

                    function print_post($post)
                    {
                        //print_r($post);
                        $post_status = 'table_unpublished_post';
                        if ($post['Is_Posted'] == 1)
                        {
                            $post_status = 'table_published_post';
                        }
                        echo '<table class="'.$post_status.'">';
                        echo '<tr><td>Платформа: '. $post['Platform'].'</td></tr>';
                        echo '<tr><td>Хозяин стены: '. $post['Owner_Name'].'</td></tr>';
                        echo '<tr><td>Сообщение: '. $post['Text'].'</td></tr>';
                        echo '<tr><td>Дата публикации на платформе: '. $post['Publish_Date'].'</td></tr>';
                        echo '<tr><td>Дата последней публикации в Bitrix: '. $post['Last_Bitrix_Publish'].'</td></tr>';
                        echo '<tr><td><a href='. $post['Link'].'>Ссылка на оригинал</a></td></tr>';


                        $srcs = explode(' ', $post['Photo_Srcs']);
                        
                        foreach($srcs as $src)
                        {
                           if(strcmp($src, "") != 0)
                           {
                               
                                $img_params = getimagesize($src);
                                echo '<tr><td><img src='. $src.' width="300" height="'. $img_params[1] / $img_params[0] * 300 .'"></td></tr>';
                               
                           }
                        }
                        echo '<tr><td>Лайки: '. $post['Likes'].',  Комментарии: '.$post['Comments'].',  Репосты: '.$post['Reposts'].'</td></tr>';
                        echo '<tr><td><input type=checkbox name='.$post['Owner_Id'].'_'.$post['Post_Id'].'>Опубликовать</input></td></tr>';
                        echo '</table>';
                    }


                    require_once("config.php");
                    echo '<div class="button-wrapper">
                                <input class="button" type="submit" value="Опубликовать">
                            </div>';
                    $link = mysqli_connect(Server, DB_UserName, DB_Password, DB_Name);
                    
                    $sql = 'SELECT * FROM `posts` ORDER BY Publish_Date DESC';
                    $result = mysqli_query($link, $sql);
                    while($row = mysqli_fetch_array($result))
                    {
                        print_post($row);
                    }
  
            ?>
        </form>
        
    </body>
</html>