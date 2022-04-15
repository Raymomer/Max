<html>

<body>


    <form method="post">
        Date: <input type="text" name="fdate" placeholder="xxxx-xx-xx">
        <input type="submit" name="submit">
    </form>

    <?php

    if (isset($_POST['submit'])) {

        $searchDate = $_POST['fdate'];
        $url = "http://localhost/demo/apiFetch.php?date=$searchDate";

        $response = json_decode(file_get_contents($url));


        if ($response->success and  count($response->payload) > 0) {

            show($response->payload);
        }


    }



    function show($data)
    {
        global $searchDate;

        $html = "
        <h1>$searchDate</h1>
        <table> 
            <tr>
                <th>Number</th>
                <th>Competition</th>
                <th>Away team</th>
                <th>Time1</th>
                <th>Home team</th>
                <th>Count</th>
            </tr>";

        foreach ($data as $row) {

            $html .= "            
            <tr>
                <td>$row->no</td>
                <td>$row->type</td>
                <td>$row->away_team</td>
                <td>$row->time</td>
                <td>$row->home_team</td>
                <td>$row->lose :  $row->win</td>
            </tr>";
        };

        $html .= "</table>";
        print_r($html);
    }
    ?>

</body>

</html>