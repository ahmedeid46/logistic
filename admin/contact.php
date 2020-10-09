<?php
ob_start();

/*
==========================================
== manage contact
==========================================
*/
session_start();
function redirectHome($theMsg ,$url = null , $seconds = 3){

    if ( $url !== null && isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !==''){
        $url = $_SERVER['HTTP_REFERER'];
        $link = 'previous page';
    }else{
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
    if ($do == 'Manage') {//manage page
        $sort = 'ASC';
        $sort_array = array('ASC', 'DESC');
        if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        }
        $stmt = $con->prepare("SELECT * FROM contact ORDER BY id $sort ");
        $stmt->execute();
        $conts = $stmt->fetchAll();
        ?>
        <h1 class="text-center">View contact</h1>
        <div class="container categories">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-project-diagram  icon-position"></i> View Contact
                    <div class="option pull-right">
                        Ordering::
                        <a class="<?php if ($sort == 'ASC') {
                            echo 'active';
                        } ?>" href="?sort=ASC"><i class="fa fa-sort-up" style=" position: relative;top: 3px;"></i> ASC</a> |
                        <a class="<?php if ($sort == 'DESC') {
                            echo 'active';
                        } ?>" href="?sort=DESC"><i class="fa fa-sort-down" style=" position: relative;top: -3px;"></i> DESC</a>
                        -  View::
                        <span class="active" data-view="full"><i class="fa fa-bars"></i> Full </span>  |
                        <span data-view="classic"><i class="fas fa-window-minimize" style=" position: relative;top: -3px;"></i> min</span>
                    </div>

                </div>
                <div class="panel-body">
                    <?php
                    foreach ($conts as $cont) {
                        echo '<div class="cont">';
                        echo '<div class="hidden-button">';
                        echo '<a href="contact.php?do=view&id=' . $cont['id'] . '" class="btn btn-xs btn-primary"><i class="fas fa-info-circle"></i> View</a>';
                        echo '<a href="contact.php?do=delete&id=' . $cont['id'] . '" class="confirm btn btn-xs btn-danger" ><i class="fas fa-trash-alt"></i> Delete</a>';
                        if ($cont['status'] == 0){
                            echo '<a href="mailto:' . $cont['email'] . '" class="btn btn-xs btn-info" ><i class="fas fa-reply"></i> replay</a>';
                        }
                        echo '</div>';
                        echo '<h3>' . $cont['subject'] . '</h3>';
                        echo '<div class="full-view">';
                        echo '<p>';
                        if ($cont['message'] == '') {
                            echo 'non message';
                        } else {
                            echo $cont['message'];
                        }
                        echo '</p>';
                        echo '<span class="name"><i class="fa fa-user"></i> '. $cont['name'] .'</span>';
                        echo '<span class="phone"><i class="fas fa-phone"></i> '. $cont['phone'] .'</span>';
                        echo '<span class="date"><i class="fas fa-clock"></i> '. $cont['date_conact'] .'</span>';

                        if ($cont['status'] == 1) {
                            echo '<span class="reply"><i class="fas fa-reply"></i> replied</span>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    
    }elseif ($do == 'view') { //view page

        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        $stmt = $con->prepare("SELECT * FROM contact WHERE id = ? ");
        $stmt->execute(array($id));
        $cat = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($stmt->rowCount() > 0) { ?>


        <?php } else {
            echo '<div class="container">';
            $theMsg = '<div class="alert alert-danger">theres no such id</div>';
            redirectHome($theMsg);
            echo '</div>';
        }



    } elseif ($do == 'delete') {
        echo " <h1 class='text-center'> Delete Contact</h1>";
        echo "<div class='container'>";
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        $stmt = $con->prepare("SELECT id FROM contact WHERE id = ?");
        $stmt->execute(array($id));
        $check = $stmt->rowCount();
        if ($check > 0) {
            $stmt = $con->prepare('DELETE FROM contact  WHERE ID = :id ');
            $stmt->bindparam(':id', $id);
            $stmt->execute();
            $theMsg = '<div class=\'alert alert-success\'>' . $stmt->rowCount() . ' record Delete' . '</div>';
            redirectHome($theMsg, 'back');
        } else {

            $theMsg = '<div class="alert alert-danger">theres no such id</div>';
            redirectHome($theMsg);
        }
        echo "</div>";
    
    }
    include 'temb/footer.php';
}else {

    header('Location:index.php');
    exit();
}
ob_end_flush();
?>