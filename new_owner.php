<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="posts_style.css">
</head>
<body>
    <form action = "index.php" method = "POST">
    <?php
        $message = 'Успешно добавлено!';
        require_once("config.php");
        $mysqli = new mysqli(Server, DB_UserName, DB_Password, DB_Name);
        $stmt_excist = $mysqli->prepare('SELECT Owner_Name FROM owners WHERE Owner_Id=? and Platform=?');
        $stmt = $mysqli->prepare('INSERT INTO owners(Platform, Owner_Name, Link, Owner_Id) VALUES (?, ?, ?, ?)');
        
        $stmt_excist->bind_param('ss', $_POST['id'], $_POST['platform']);
        $stmt_excist->bind_result($row);
        if(!$stmt_excist->execute())
        {
            print("no execute select");
        } 
        $stmt_excist->fetch();
        if(isset($row))
        {
            $message = 'Данный пользователь уже добавлен';
        }
        else{
            $stmt->bind_param('ssss', $_POST['platform'], $_POST['name'], $_POST['link'], $_POST['id']);
            $stmt->execute();
        }
        
       
        echo '<table>
                    <tr>
                        <td>
                            '.$message.'
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input class="button" type = "submit" value="Вернуться">
                        </td>
                    </tr>
                  </table>';
    ?>
    </form>
</body>
</html>