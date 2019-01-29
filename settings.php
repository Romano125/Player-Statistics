<?php
    session_start();

    $now = time();
    $then = $_SESSION['time'];
    $diff = $now - $then;

    if( $diff > 600000 ) {
        session_destroy();
        header('Location: http://localhost:8080/projekt/expired.html');
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Player statistics</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="app.css">
        <link rel="stylesheet" type="text/css" href="settings.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script src = ./app.js> </script>
        
    </head>
    <body style="background-color: #e6ffff">
        <?php 
           $s = '';
           $db = new mysqli('127.0.0.1', 'root', '', 'player_stats');
           if( isset($_SESSION['priv']) ) {
               if( isset($_SESSION['priv']) ) {
                if( $_SESSION['priv'] == 1 ) $s = $_SESSION['user'] . '(admin) &nbsp';
                else {
                    $s = $_SESSION['user'];
                }
            }
           }

            $q = "SELECT * FROM followers_pending WHERE want_follow=" . $_SESSION['id'];

            $res = $db->query($q);

            $foll = $res->num_rows;

            //$q = "SELECT notification FROM users_notifications WHERE seen=1 AND ID=" . $_SESSION['id'];

            $q = "UPDATE users_notifications SET was_fav=1 WHERE reg_br_igr IN ( SELECT reg_br_igr FROM users_igrac WHERE ID=" . $_SESSION['id'] . " ) AND ID=" . $_SESSION['id'];

            $db->query($q);

            $q = "UPDATE users_notifications SET seen=0 WHERE was_fav=0 AND ID=" . $_SESSION['id'];

            $db->query($q);

            $q = "SELECT notification FROM users_notifications WHERE seen=1 AND ID=" . $_SESSION['id'];

            $res = $db->query($q);

            if ($res) {
                $not = $res->num_rows;
            } 
            else $not = 0;

            echo "<div class='container-fluid top-menu'>
                    <table>
                        <tr> <td width='25%'>
                                <a href='#' class='btn btn-dark' id='menu-toggle'><div class='menu-icon'></div>
                                <div class='menu-icon'></div>
                                <div class='menu-icon'></div></a>";
            
            $sum = $foll + $not;                
            if( $foll == 0 && $not == 0 ) {
                echo "&nbsp<button type='button' class='btn btn-dark home-btn'><a href='notifications.php' style='text-decoration: none;color: white'>Notifications <span class='badge badge-light'>0</span></a></button>";
            }else {
                echo "&nbsp<button type='button' class='btn btn-dark home-btn'><a href='notifications.php' style='text-decoration: none;color: white'>Notifications <span class='badge badge-light' style='color: red'>" . $sum . "</span></a></button>";
            }

                                echo "  <button type='button' class='btn btn-dark home-btn'><a href='app.php' style='text-decoration: none;color: white'>Home</a></button>
                            </td>
                            <td style='text-align: center; padding: 20px; font-family: Papyrus, fantasy; font-size: 49px; font-style: normal; font-variant: small-caps; font-weight: 700; line-height: 40.6px;'><h2>Welcome to the site about football players</h2></td> 
                            <td width='25%' style='text-align: right; padding: 20px'><button type='button' class='btn btn-dark btn-sm'><a href='logout.php' style='text-decoration: none;color: white'>LogOut " . $s . "</a></button>
                            </td> 
                        </tr>
                    </table>
                </div>"; 
        ?>

        <div id="wrapper">

            <div id="sidebar-wrapper">
                <ul class="sidebar-nav">
                    <li>
                        <?php
                        $db = new mysqli('127.0.0.1', 'root', '', 'player_stats');
                        
                        $q = "SELECT back_photo FROM users WHERE ID=" . $_SESSION['id'];

                        $res = $db->query($q);

                        while( $r = $res->fetch_assoc() ) {
                            $pic = $r['back_photo'];
                        }

                        echo '<div id="wrapper" class="images" style="background-image: url(' . $pic . ');">
                            <div class="row">
                                <div class="col-md-3">';

                                        $q = "SELECT user_photo FROM users WHERE ID=" . $_SESSION['id'];

                                        $res = $db->query($q);

                                        while( $r = $res->fetch_assoc() ) {
                                            $pic = $r['user_photo'];
                                        }

                                        echo "<a href='users_info.php?id=" . $_SESSION['id'] . "' id='post'><img src=" . $pic . " alt='Avatar' class='avatar useravatar'></a>";
                                echo '</div>
                                <div class="col-md-8 sidebar-text">';

                                            $q = "SELECT name, last_name, e_mail FROM users WHERE ID=" . $_SESSION['id'];

                                            $res = $db->query($q);

                                            while( $r = $res->fetch_assoc() ) {
                                                echo "<span class='sidebar-name'>" . $r['name'] . " " . $r['last_name'] . "</span><br>";
                                                echo "<small class='sidebar-name'>" . $r['e_mail'] . "</small>";
                                            }

                                echo '</div>
                            </div>
                        </div>';
                    ?>
                    </li>
                    <li class="fwd"><a href="forwards.php" class="f">Forwards</a></li>
                    <li class="mid"><a href="midfielders.php" class="m">Midfielders</a></li>
                    <li class="def"><a href="defenders.php" class="d">Defenders</a></li>
                    <li class="gk"><a href="goalkeepers.php" class="g">Goalkeepers</a></li>
                    <li class="fav"><a href="favourites.php" class="f">Favourites</a></li>
                    <li class="pos"><a href="settings.php" class="s">Settings</a></li>
                    <li class='use'><a href='users.php' class="u">Users</a></li>
                    <!--<?php
                        if( isset($_SESSION['priv']) ) {
                            if( $_SESSION['priv'] == 1 ) echo "<li><a href='users.php' id='pos'>Users</a></li>";
                        }
                    ?>-->
                </ul> 
            </div>
                                                
            <div id="page-content-wrapper" style="background-color: white;">

                <div class="row align-items-center">

                        <div class="col-md-8">

                            <div class="container">

                                <div class="row">
                                    <div class="col-md-3">
                                        <?php
                                            $db = new mysqli('127.0.0.1', 'root', '', 'player_stats');

                                            $q = "SELECT user_photo FROM users WHERE ID=" . $_SESSION['id'];

                                            $res = $db->query($q);

                                            while( $r = $res->fetch_assoc() ) {
                                                $pic = $r['user_photo'];
                                            }

                                            echo "<div class='card'>
                                                  <form action='photo_upload.php?id=" . $_SESSION['id'] . "' method='POST'  enctype='multipart/form-data'>
                                                    <img src=" . $pic . " style='text-align: center; width:100%'>
                                                    <input type='file' name='profile' style='width:100px;'>
                                                    <button type='submit' class='btn btn-success'>Upload</button>
                                                    </form>
                                                  <h3>" . $_SESSION['user'] . " " . $_SESSION['last'] . "</h3>
                                                  <p class='title'>" . $_SESSION['mail'] . "</p>
                                                </div>";

                                            if( isset($_SESSION['to_big']) ) {
                                                if( $_SESSION['to_big'] == 1 ) {
                                                    echo "<p>Sorry your picture is to big</p>";
                                                }
                                            }
                                        ?>
                                    </div>
                                    <div class="col-md-8 user_info">
                                        <?php
                                            $db = new mysqli('127.0.0.1', 'root', '', 'player_stats');

                                            $q = "SELECT name, last_name, gender, age, password, e_mail, back_photo FROM users WHERE ID=" . $_SESSION['id'];

                                            $res = $db->query($q);

                                            while( $r = $res->fetch_assoc() ) {
                                                echo "<form action='update_user.php' method='POST'>
                                                        Name: <input class='form-control user' type='text' value='" . $r['name'] . "' name='n' readonly>
                                                        Last name: <input class='form-control user' type='text' value='" . $r['last_name'] . "' name='l' readonly>
                                                        Gender: <input class='form-control user' type='text' value='" . $r['gender'] . "' name='g' readonly>
                                                        Age: <input class='form-control user' type='text' value='" . $r['age'] . "' name='a' readonly>
                                                        Password: <input class='form-control user' type='text' value='" . $r['password'] . "' name='p' readonly>
                                                        E-mail: <input class='form-control user' type='text' value='" . $r['e_mail'] . "' name='e' readonly>
                                                        Backround photo(link): <input class='form-control user' type='text' value='" . $r['back_photo'] . "' name='b' readonly>
                                                        <button type='button' class='btn btn-outline-dark' id='mod-btn'>Modify</button>
                                                        <button type='submit' class='btn btn-outline-dark' id='sav-btn'>Save</button>
                                                    </form>";
                                            }
                                        ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>


        <script type="text/javascript">
            document.getElementById('menu-toggle').addEventListener( 'click', e => {
                e.preventDefault();
                document.getElementById('wrapper').classList.toggle('menuDisplayed');
            });
            

            document.getElementById('mod-btn').addEventListener( 'click', e => {
                e.preventDefault();
                var el = document.getElementsByClassName('user');

                for( let i = 0; i < el.length; i++ ) {
                    el[i].readOnly = false;
                }
            });

            document.getElementById('sav-btn').addEventListener( 'click', e => {
                //e.preventDefault();
                var el = document.getElementsByClassName('user');

                for( let i = 0; i < el.length; i++ ) {
                    el[i].readOnly = true;
                }
            });
            document.querySelector('.pos').classList.add('active');
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>