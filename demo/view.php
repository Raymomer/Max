<html>

<body>

    <form method="post">
        Date: <input type="text" name="fdate" placeholder="xxxx-xx-xx">
        <input type="submit" name="submit">
        <input type="submit" name="delete" value="Delete">
    </form>


    <?php

    include  'db/db.php';
    $db = new DB($servername = "localhost", $username = "root", $password = "", $dbname = "contest");
    $html;



    if (isset($_POST['submit'])) {


        $searchDate = $_POST['fdate'];

        // checkdate formate
        $re = '/^\d{4}-\d{2}-\d{2}$/m';

        preg_match($re, $searchDate, $dateFormat);



        if (empty($_POST['fdate'])) {
            echo "Date is empty.";
            return;
        } else if (count($dateFormat) == 0) {
            echo "Date format is wrong";
            return;
        }
        $data  = $db->selectDBData($dbname,  $dateFormat[0]);
        show($data,  $dateFormat[0]);
    } else if (isset($_POST['delete'])) {
        $db->deleteDB($dbname);
        header('Location: http://localhost/demo/index.php');
    }

    function show($data, $date)
    {
        global $html;

        $html = "
                <h1>$date</h1>
                <table> 
                    <tr>
                        <th>Number</th>
                        <th>Competition</th>
                        <th>Away team</th>
                        <th>Time</th>
                        <th>Home team</th>
                        <th>Count</th>
                    </tr>";

        foreach (mysqli_fetch_all($data) as $row) {
            // print_r($row);

            $html .= "            
                    <tr>
                        <td>$row[0]</td>
                        <td>$row[2]</td>
                        <td>$row[3]</td>
                        <td>$row[5]</td>
                        <td>$row[4]</td>
                        <td>$row[6] :  $row[7]</td>
                    </tr>
            ";
        }

        $html .= "</table>";


        echo $html;
    }
    ?>
</body>

</html>