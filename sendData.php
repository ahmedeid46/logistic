<?php

/*
==========================================
== page for send Data for Data base from front end
==========================================
*/
include 'admin/contact_SQL.php';
 if (isset($_GET['sent'])) {
    $sent = $_GET['sent'];
} 
if ($sent == 'contact') {  
    if($_SERVER['REQUEST_METHOD']=='POST') {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        //insert info in  database
        $stmt = $con->prepare("INSERT INTO contact
                            (name,phone,email,subject,message,date_conact)
                            VALUES
                            (:zname, :ztel, :zemail, :zsub , :zmsg ,now())");
        $stmt->execute(array(
            'zname' => $name,
            'ztel' => $phone,
            'zemail' => $email,
            'zsub' => $subject,
            'zmsg' => $message
        ));
        global $count ;
        $count= $stmt->rowCount();
        if($count == 1){
            header("Location:index.php");
        }

    }
}elseif($sent == 'quote'){
    if($_SERVER['REQUEST_METHOD']=='POST') {
        
            //insert info in  database
            $stmt = $con->prepare("INSERT INTO `quote` 
                                (`name`,`email`,`phone`,`kind`,`city`,`inconterms`,`wieght`,`height`,`width`,`lenth`,`services`)
                                VALUES
                                (:zname,:zemail,:zphone,:zkind,:zcity,:zinconterms,:zwieght,:zheight,:zwidth,:zlenth,:zservices)");
            $stmt->execute(array(
                "zname"       => $_POST['name'],
                "zemail"      => $_POST['email'],
                "zphone"        => $_POST['tel'],
                "zkind"       => $_POST['type'],
                "zcity"       => $_POST['city'],
                "zinconterms" => $_POST['inconterms'],
                "zwieght"     => $_POST['wieght'],
                "zheight"     => $_POST['height'],
                "zwidth"      => $_POST['width'],
                "zlenth"      => $_POST['lenth'],
                "zservices"   => $_POST['services']
            ));
            global $count ;
            $count= $stmt->rowCount();
            if($count == 1){
                header("Location:index.php");
            }
        }
}elseif($sent == 'subscrib'){
        if($_SERVER['REQUEST_METHOD']=='POST') {
            $email = $_POST['email'];
            //insert info in  database
            $stmt = $con->prepare("INSERT INTO subscribe
                                (email,date)
                                VALUES
                                (:zemail ,now())");
            $stmt->execute(array(
                'zemail' => $email,
            ));
            global $count ;
            $count= $stmt->rowCount();
            if($count == 1){
                header("Location:index.php");
            }
        }

}else{

}

