

<?php
require "db.php";
$sql="INSERT INTO registrations (event_id, user_id) 
VALUES (1, 5); -- event_id=1, user_id=5";
$res=$conn->query($sql);
if($res){
    echo "seccessfully inserted!!!";
}

/* List all users registered for an event
SELECT u.name, u.email 
FROM registrations r
JOIN users u ON r.user_id = u.id
WHERE r.event_id = 1;

-- Mark attendance
UPDATE registrations 
SET attended = TRUE 
WHERE event_id = 1 AND user_id = 5;*/
?>