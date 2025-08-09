<?php
require "db.php";
$sql="create table users(id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'organizer', 'staff', 'student') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$res=$conn->query($sql);
if($res){
    echo "seccessfull OK!!!";
} else echo "not secccessfully".$conn->error;
/*$sql="create table events(id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME,
    location VARCHAR(255) NOT NULL,
    event_type ENUM('academic', 'cultural', 'workshop', 'conference', 'training', 'other') NOT NULL,
    capacity INT NOT NULL,
    organizer_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (organizer_id) REFERENCES users(id) ON DELETE CASCADE)";
    $res=$conn->query($sql);
    if($res){
        echo "seccessfully table created";
    }else echo "not created".$conn->error;
    
    $sql="create table registrations(id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    user_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    attended BOOLEAN DEFAULT FALSE,
    feedback TEXT,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_registration (event_id, user_id))";
    $res=$conn->query($sql);
    if($res){
        echo "table created seccessfully!!!";
    } else echo "not created".$conn->error;
     $sql="create table notifications(id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE SET NULL)";
    $res=$conn->query($sql);
    if($res){
        echo "seccessfully created of notifications table o OK!!!";
    } else echo "not created".$conn->error;
     $sql="drop table users";
     if($conn->query($sql)){
        echo "secccessfully droup";
     }*/
$conn->close();
?>