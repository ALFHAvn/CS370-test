<?php
// Connect DB
$conn = new mysqli("localhost", "webuser", "123456", "userdb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query data
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Table</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            text-align: center;
        }

        table {
            margin: auto;
            border-collapse: collapse;
            width: 70%;
            background: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        th {
            background: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>User List</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Password</th>
    </tr>

<?php
// Show data
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["fullname"] . "</td>";
        echo "<td>" . $row["password"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No data</td></tr>";
}

$conn->close();
?>

</table>

</body>
</html>
