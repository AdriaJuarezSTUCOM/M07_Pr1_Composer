<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workshop</title>
</head>

<body>
    <h1>Select user role</h1>
    <form action="../src/View/viewReparation.php" method="get">
        <select name="role">
            <option value="employee">Employee</option>
            <option value="client">Client</option>
        </select>
        <button type="submit">Login</button>
    </form>
</body>
</html>