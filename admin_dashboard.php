<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 230px;
            height: 100vh;
            background-image: url('images/sidebar1.jpg'); /* Ensure the image path is correct */
            background-size: cover;
            background-position: center;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        }

        .sidebar h2 {
            text-align: center;
            color: white;
            font-weight: bold;
            font-family: cursive;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 15px;
            font-family: Papyrus, sans-serif;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            display: block;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
            background-color: rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .sidebar ul li a:hover {
            background-color: #ffdf64;
            color: #1d1b31;
            font-weight: bold;
            transform: scale(1.05);
        }

        /* Content Area */
        .content {
            margin-left: 250px; /* Matches sidebar width */
            padding: 20px;
        }

        .content h1 {
            font-family: cursive;
            color: teal;
            margin-bottom: 20px;
        }

        /* Responsive Sidebar */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }

            .sidebar ul li a {
                text-align: left;
                padding-left: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="http://localhost/admin/ses.php">Session Tracking</a></li>
            <li><a href="http://localhost/admin/artist_management.php">Artist Profile Management</a></li>
            <li><a href="http://localhost/admin/sales_management.php">Offer Management</a></li>
            <li><a href="http://localhost/admin/artwork_management.php">Artwork Management</a></li>
            <li><a href="http://localhost/admin/a.php">Feedback Management</a></li>
            <li><a href="http://localhost/admin/a.php">Number of Visitors</a></li>

        </ul>
<div style="position: absolute; bottom: 20px; width: 100%; text-align: center;">
        <a href="http://localhost/admin/admin_login.html" style="
            text-decoration: none;
            color: white;
            background-color: #ff4d4d;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease;
        ">Logout</a>
    </div>
    </div>

    <!-- Content Area -->
    <div class="content">
        <h1>HELLO ADMIN</h1>
        <p>Welcome to ARTSoul.</p>
    </div>

</body>
</html>
