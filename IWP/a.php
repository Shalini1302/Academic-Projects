<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Feedback Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        h2 {
            color: #333;
            text-align: center;
            margin-top: 20px;
            font-family: cursive;
        }

        .feedback-table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        .feedback-table th, .feedback-table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .feedback-table th {
            background-color: #4CAF50; /* Table header background color */
            color: white;
            font-weight: bold;
        }

        .feedback-table tr:nth-child(even) {
            background-color: #f2f2f2; /* Alternating row color */
        }

        .feedback-table tr:hover {
            background-color: #ddd; /* Row hover effect */
        }

        .delete-btn {
            padding: 8px 12px;
            color: white;
            background-color: #e74c3c;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

    <h2>Feedback Management</h2>
    <table class="feedback-table">
        <tr>
            <th>id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Type</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        
        <?php
        $conn = new mysqli('localhost', 'root', '', 'artwork');
        $result = $conn->query("SELECT * FROM feedback");

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>".$row['feedback_id']."</td>
                <td>".$row['name']."</td>
                <td>".$row['email']."</td>
                <td>".$row['phone']."</td>
                <td>".$row['feedback_type']."</td>
                <td>".$row['description']."</td>
                <td>
                    <form action='delete_feedback.php' method='POST' style='margin:0;'>
                        <input type='hidden' name='feedback_id' value='".$row['feedback_id']."'>
                        <button class='delete-btn' type='submit'>Delete</button>
                    </form>
                </td>
            </tr>";
        }
        $conn->close();
        ?>
    </table>

</body>
</html>
