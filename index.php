<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action = "update.php" method="POST">
        
            <table>
                <caption>
                    Мониторинг соц. сетей
                </caption>
                <tr class="table_head">
                    <td>
                        Платформа
                    </td>
                    <td>Имя/Название</td>
                    <td>Адрес на платформе</td>
                    <td>Дата публикации последней записи</td>
                    <td></td>
                    <td></td>
                </tr>
                <?php
                    require_once("config.php");
                    $link = mysqli_connect(Server, DB_UserName, DB_Password, DB_Name);

                    $sql = 'SELECT * 
                            FROM Owners';

                    $result = mysqli_query($link, $sql);


                    while($row = mysqli_fetch_array($result))
                    {

                        printf("<tr>
                                    <td>%s</td>
                                    <td>%s</td>
                                    <td><a href=%s>%s</a></td>
                                    <td>%s</td>
                                    <td><button class=\"button\" name=update[%s_%s]> Обновить </button></td> 
                                    <td><button class=\"button\" name=delete[%s_%s]> Удалить </button></td> 
                               </tr>", $row['Platform'],
                                       $row['Owner_Name'], 
                                       $row['Link'], 
                                       $row['Link'], 
                                       date('j.n.Y', $row['Last_Post_Date']), 
                                       $row['Platform'],
                                       $row['Owner_Id'],
                                       $row['Platform'], 
                                       $row['Owner_Id']);


                    }
                
                ?>
            </table>
        
    </form>
    <form action = "new_owner.php" method = "POST">
        <table>
            <tr>
                <td>
                    Платформа
                </td>
                
                <td>
                    Имя/Название
                </td>
                <td>
                    Адрес на платформе
                </td>
                <td>
                    Id хозяина стены (id групп vk начинаются с -) 
                </td>
                <td>
                    
                </td>
            </tr>
            <tr>
                <td>
                    <select name="platform" required>
                        <option value="vk.com">vk.com</option>
                        <option value="facebook.com">facebook.com</option>
                        <option value="instagram.com">instagram.com</option>
                    </select>
                </td>
                
                <td>
                    <input name="name" type="text" required>
                </td>
                <td>
                    <input name="link" type="text" required>
                </td>
                <td>
                    <input name="id" type="text" required>
                </td>
                <td>
                    <input class="button" type="submit"  value="Добавить">
                </td>
            </tr>
        </table>
    </form>
    
    <form action = "posts.php" method = "POST">

        <div class="button-wrapper">
            <input class="button" type="submit" name="all_posts", value="Опубликовать">
        </div>


    </form>
</body>
</html>