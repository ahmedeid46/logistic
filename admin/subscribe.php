<?php
ob_start();
/*
==========================================
== manage subscribe
==========================================
*/
session_start();
function redirectHome($theMsg, $url = null, $seconds = 3)
{

    if ($url !== null && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
        $url = $_SERVER['HTTP_REFERER'];
        $link = 'previous page';
    } else {
        $url = 'index.php';
        $link = 'Home Page';
    }

    echo $theMsg;
    echo "<div class='alert alert-info'>You Will Be Redirected to $link After $seconds seconds....</div>";
    header("refresh:$seconds;url=$url");
    exit();
}
if (isset($_SESSION['username'])) {
    include 'init.php';
    if (isset($_GET['do'])) {
        $do = $_GET['do'];
    } else {
        $do = 'Manage';
    }
    if ($do == 'Manage') { //manage page
        $sort = 'ASC';
        $sort_array = array('ASC', 'DESC');
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        }
        $stmt = $con->prepare("SELECT * FROM subscribe ORDER BY id $sort ");
        $stmt->execute();
        $conts = $stmt->fetchAll();
?>
        <h1 class="text-center">View subscribe</h1>
        <div class="container categories">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-project-diagram  icon-position"></i> View subscribe
                    <div class="option pull-right">
                        Ordering::
                        <a class="<?php if ($sort == 'ASC') {
                                        echo 'active';
                                    } ?>" href="?sort=ASC"><i class="fa fa-sort-up" style=" position: relative;top: 3px;"></i> ASC</a> |
                        <a class="<?php if ($sort == 'DESC') {
                                        echo 'active';
                                    } ?>" href="?sort=DESC"><i class="fa fa-sort-down" style=" position: relative;top: -3px;"></i> DESC</a>
                        - View::
                        <span class="active" data-view="full"><i class="fa fa-bars"></i> Full </span> |
                        <span data-view="classic"><i class="fas fa-window-minimize" style=" position: relative;top: -3px;"></i> min</span>
                    </div>

                </div>
                <div class="panel-body">
                    <?php
                    foreach ($conts as $cont) {
                        echo '<div class="cont">';
                        echo '<h3>' . $cont['email'] . '</h3>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php

    } 
    include 'temb/footer.php';
} else {

    header('Location:index.php');
    exit();
}
ob_end_flush();

?>