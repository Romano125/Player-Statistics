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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script src = ./app.js> </script>
        
    </head>
    <body style="background-color: #e6ffff">
        <?php 
            echo "<div class='container-fluid top-menu'>
                    <table>
                        <tr> <td width='25%'>
                                <a href='#' class='btn btn-dark' id='menu-toggle'><div class='menu-icon'></div>
                                <div class='menu-icon'></div>
                                <div class='menu-icon'></div></a>
                                <button type='button' class='btn btn-dark home-btn'><a href='app.php' style='text-decoration: none;color: white'>Home</a></button>
                            </td>
                            <td style='text-align: center; padding: 20px; font-family: Papyrus, fantasy; font-size: 49px; font-style: normal; font-variant: small-caps; font-weight: 700; line-height: 40.6px;'><h2>Welcome to the site about football players</h2></td> 
                            <td width='25%' style='text-align: right; padding: 20px'><button type='button' class='btn btn-dark btn-sm'><a href='logout.php' style='text-decoration: none;color: white'>LogOut</a></button>
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

                                            echo "<a href='settings.php' id='post'><img src=" . $pic . " alt='Avatar' class='avatar'></a>";
                                    echo '</div>
                                    <div class="col-md-8">';

                                                $q = "SELECT name, last_name, e_mail FROM users WHERE ID=" . $_SESSION['id'];

                                                $res = $db->query($q);

                                                while( $r = $res->fetch_assoc() ) {
                                                    echo "<p><span class='sidebar-name'>" . $r['name'] . " " . $r['last_name'] . "</span></p>";
                                                    echo "<small class='sidebar-name'>" . $r['e_mail'] . "</small>";
                                                }

                                   echo '</div>
                                </div>
                            </div>';
                        ?>
                    </li>
                    <li><a href="forwards.php" id="fwd">Forwards</a></li>
                    <li><a href="midfielders.php" id="mid">Midfielders</a></li>
                    <li><a href="defenders.php" id="def">Defenders</a></li>
                    <li><a href="goalkeepers.php" id="gk">Goalkeepers</a></li>
                    <li><a href="favourites.php" id="fav">Favourites</a></li>
                    <li><a href="settings.php" id="pos">Settings</a></li>
                </ul> 
            </div>
                                                
            <div id="page-content-wrapper" style="background-color: white;">
                
                <div class="container-fluid switch" id="player">
                    <div class="row align-items-center">

                        <div class="col-md-8">

                            <div class="container">

                                <div class="row">
                                    <div class="col-md-3">
                                        
                                        <img src="https://img.uefa.com/imgml/2016/ucl/social/og-statistics.png" style="text-align: center;" height="75px" width="75px">
                                    </div>
                                    <div class="col-md-8">
                                        <?php

                                            if( isset($_GET['id']) ) $id = $_GET['id'];

                                            $db = new mysqli('127.0.0.1', 'root', '', 'player_stats');

                                            $q = "SELECT DISTINCT reg_br_igr FROM igrac JOIN users_igrac using(reg_br_igr) JOIN klub using(klub_id) WHERE ID=" . $_SESSION['id'];

                                            $res = $db->query($q);

                                            if( $res->num_rows == 0 ) {
                                                echo "<form action='add_fav.php?id=" . $id . "' method='POST'>
                                                    <button type='submit' class='btn btn-outline-warning' id='fav-btn' style='float: right;'>Favourites</button>
                                                    </form>";
                                            }else{
                                                $f = 0;
                                                while( $r = $res->fetch_assoc() ) {
                                                    if( $r['reg_br_igr'] == $id ) $f = 1;
                                                }
                                                if( $f == 1 ) {
                                                    echo "<form action='del_fav.php?id=" . $id . "' method='POST'>
                                                    <button type='submit' class='btn btn-outline-warning paint' id='fav-btn' style='float: right;'>Favourites</button>
                                                    </form>";
                                                }else{
                                                    echo "<form action='add_fav.php?id=" . $id . "' method='POST'>
                                                    <button type='submit' class='btn btn-outline-warning' id='fav-btn' style='float: right;'>Favourites</button>
                                                    </form>";
                                                }
                                            }


                                            $q = "SELECT ime, prezime, br_gol, br_asist, klub_ime, br_dres, br_zkarton, br_ckarton, ime_poz FROM igrac NATURAL JOIN klub NATURAL JOIN pozicija WHERE reg_br_igr='" . $id . "'";
                                            
                                            $res = $db->query($q);

                                            while( $r = $res->fetch_assoc() ) {
                                                echo "Name: " . $r['ime'] . "<br>";
                                                echo "Last name: " . $r['prezime'] . "<br>";
                                                echo "Club: " . $r['klub_ime'] . "<br>";
                                                echo "Jersy number: " . $r['br_dres'] . "<br>";
                                                echo "Field position: " . $r['ime_poz'] . "<br>";
                                                echo "Total goals: " . $r['br_gol'] . "<br>";
                                                echo "Total assists: " . $r['br_asist'] . "<br>";
                                                echo "Total yellow cards: " . $r['br_zkarton'] . "<br>";
                                                echo "Total red cards: " . $r['br_ckarton'] . "<br>";
                                                echo "Market value: " . 5550055 . "$<br>";
                                                break;
                                            }

                                            mysqli_free_result($res);
                                        ?>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-4">
                          <div class="container">
                                <h3>Graf</h3>
                                <div class="container">
                                    <img src="https://img.uefa.com/imgml/2016/ucl/social/og-statistics.png" style="text-align: center;" height="55px" width="55px">
                                    Ime igraca<br>
                                    Golovi: 12<br>
                                </div>
                            </div>
                            <hr width="100%">
                            <div class="container">
                                <h3>Nesto</h3>
                                <div class="container">
                                    <img src="https://img.uefa.com/imgml/2016/ucl/social/og-statistics.png" style="text-align: center;" height="55px" width="55px">
                                    Ime igraca<br>
                                    Obrane: 15<br>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        
        <?php

            if( isset($_GET['id']) ) {
                $id = $_GET['id'];

                if( isset($id) ) {
                    if( $id != '0' ) {
                        echo "<script>
                                document.getElementById('player').classList.remove('switch');
                            </script>";
                    }
                }
            }
        ?>



        <script type="text/javascript">
            document.getElementById('menu-toggle').addEventListener( 'click', e => {
                e.preventDefault();
                document.getElementById('wrapper').classList.toggle('menuDisplayed');
            });
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </body>
</html>