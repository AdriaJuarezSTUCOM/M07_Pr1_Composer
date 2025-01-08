<?php

namespace Src\View;

require_once  __DIR__ . "/../../vendor/autoload.php";

use Src\Model\reparation;

if(session_status() == PHP_SESSION_NONE){
    session_start();
    $_SESSION["role"] = $_GET["role"];
}

class viewReparation{
    function renderMessage($message): void{
        echo"$message<br>";
    }

    function renderReparation(Reparation $reparation): void {?>
        <body>
            <h1>Reparation Details</h1>
            <table border="1" cellspacing="0" cellpadding="10">
                <tr>
                    <th>Workshop ID</th>
                    <td><?php echo htmlspecialchars($reparation->getworkshopId()); ?></td>
                </tr>
                <tr>
                    <th>Workshop Name</th>
                    <td><?php echo htmlspecialchars($reparation->getworkshopName()); ?></td>
                </tr>
                <tr>
                    <th>Register Date</th>
                    <td><?php echo htmlspecialchars($reparation->getregisterDate()); ?></td>
                </tr>
                <tr>
                    <th>License Plate</th>
                    <td><?php echo htmlspecialchars($reparation->getLicensePlate()); ?></td>
                </tr>
                <!-- <tr>
                    <th>Photo</th>
                    <td>
                        <?php if ($reparation["photo"]): ?>
                            <img src="<?php echo htmlspecialchars($reparation["photo"]); ?>" alt="Vehicle Photo" style="max-width: 300px; height: auto;">
                        <?php else: ?>
                            No photo uploaded.
                        <?php endif; ?>
                    </td>
                </tr> -->
            </table>
        </body>
<?php }} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main page</title>
</head>
<body>
    <h1>Car reparation menu</h1>
    <h2>Search for reparation</h2>
    <form action="../Controller/controllerReparation.php" method="get">
        Id reparation number: <input type="text" name="uuid" id="uuid">
        <input type="submit" value="search" name="getReparation">
    </form>

    <?php
        if(isset($_GET["role"]) && $_GET["role"] == "employee"){ ?>
            <h2>Register reparation</h2>
            <form action="../Controller/controllerReparation.php" method="get">
                Workshop Id (4 numbers): <input type="number" name="workshopId" id="workshopId" maxlength="4" required><br>
                Workshop Name (up to 12 characters): <input type="text" name="workshopName" id="workshopName" maxlength="12" required> <br>
                Register Date (yyyy-mm-dd): <input type="text" name="registerDate" id="registerDate" pattern="\d{4}-\d{2}-\d{2}" required><br>
                License Plate (9999-XXX): <input type="text" name="licensePlate" id="licensePlate" pattern="\d{4}-[A-Za-z]{3}" required><br>
                Photo of Damaged Vehicle: <input type="file" name="photo" id="photo" accept="image/*" ><br>
                <br>
                <input type="submit" value="Create" name="insertReparation">
            </form>
    <?php
        }
    ?>
    <br>
    <form action="../../public/index.php">
        <button type="submit">Back</button>
    </form>
</body>
</html>