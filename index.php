<!DOCTYPE html>
<html>
<body>

<h2>Simple Form</h2>

<form method="POST">
  Name: <input type="text" name="name"><br><br>
  Job: <input type="text" name="job"><br><br>
  <button type="submit">Submit</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<h3>Result:</h3>";
    echo "Name: " . $_POST["name"] . "<br>";
    echo "Job: " . $_POST["job"];
}
?>

</body>
</html>
