<?php
if (isset($_POST['artwork_id'])) {
    $artwork_id = $_POST['artwork_id'];

    $conn = new mysqli('localhost', 'root', '', 'artwork');

    $sql = "DELETE FROM artworks WHERE id = '$artwork_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Artwork deleted successfully";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
