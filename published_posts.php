<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="posts_style.css">
    </head>
    <body>
        <form action="index.php" method="POST">
            <div class="button-wrapper">
                <input class="button" type="submit" value="Вернуться">
            </div>
            <?php
                    
                    function print_post($post)
                    {
                        //print_r($post);
                        
                        echo '<table>';
                        echo '<tr><td>Платформа: '. $post['Platform'].'</td></tr>';
                        echo '<tr><td>Хозяин стены: '. $post['Owner_Name'].'</td></tr>';
                        echo '<tr><td>Сообщение: '. $post['Text'].'</td></tr>';
                        echo '<tr><td>Дата публикации платформе: '. $post['Publish_Date'].'</td></tr>';
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
                        
                        echo '</table>';
                    }
                    require_once("config.php");
                   
                    $link = mysqli_connect(Server, DB_UserName, DB_Password, DB_Name);
                    
                    
                    $ids = array_keys($_POST);
                    foreach($ids as $id)
                    {
                        $id_arr = explode("_",$id);
                        $owner_id = $id_arr[0];
                        $post_id = $id_arr[1];
                        $corrent_date = date('Y-n-j');
                        $sql = 'UPDATE posts
                                SET Is_Posted = 1, Last_Bitrix_Publish ="'.$corrent_date.'"
                                WHERE Owner_Id = '.$owner_id.' AND Post_Id = '.$post_id;
                        $result = mysqli_query($link, $sql);
                        $sql = 'SELECT * 
                                FROM posts
                                WHERE Owner_Id = '.$owner_id.' AND Post_Id = '.$post_id;
                        $result = mysqli_query($link, $sql);
                        $row = mysqli_fetch_array($result);
                        print_post($row);

                    }
                    
                    
                    
                    
                    
            ?>
        </form>
        
    </body>
</html>