<html>

<body>



    <form method="post">
        Date: <input type="text" name="fdate" placeholder="xxxx-xx-xx">
        <input type="submit" name="submit">
        <input type="submit" name="delete" value="Delete">

    </form>
    <?php
    include  'db/db.php';

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "contest";


    $searchDate = "2022-04-07";
    $url = "https://cp.zgzcw.com/lottery/jcplayvsForJsp.action?lotteryId=26&issue=$searchDate";
    $html = "";


    $db = new DB($servername = "localhost", $username = "root", $password = "", $dbname = "contest");



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

        //check Data exists
        $err = $db->checkDBData($dbname, $dateFormat[0]);

        if ($err  != null) {
            $url = "https://cp.zgzcw.com/lottery/jcplayvsForJsp.action?lotteryId=26&issue=$searchDate";
            $err = start();

            if ($err != null) {

                echo $err;
                return;
            }
        }


        $data  = $db->selectDBData($dbname,  $dateFormat[0]);
        show($data,  $dateFormat[0]);
    } else if (isset($_POST['delete'])) {

        $db->deleteDB($dbname);
        header('Location: http://localhost/demo/index.php');
    }



    function start()
    {

        global $url, $db;


        $str = file_get_contents($url);
        $need =  sub_table($str);


        // 取得日期 data
        $re = '/(\d{4}\-\d{2}\-\d{2}星期.)/u';
        preg_match($re, $need, $date);

        if (count($date) == 0) {
            return "No data";
        }

        // 取得table tr 資料
        $re = "/<tr ?.*[\n \w\W]+?<\/tr>/";
        preg_match_all($re, $need, $tr);

        for ($i = 0; $i < count($tr[0]); $i++) {
            $err = catchDetial($tr[0][$i], $date);

            if ($err != null) {
                return $err;
                break;
            }
        }
    }



    // 整理tr資料
    function catchDetial($element, $date)
    {
        global $dbname;
        global $db;

        // 取得編號
        $re = '/<\/code><i>(\d+)/';
        preg_match($re, $element, $number);


        // 取得賽事
        $re = '/<span class="g_qt">(.*?)<\/span>/';
        preg_match($re, $element, $competition);

        //取得客隊
        $re = '/<td ?class="wh-4 t-r"[\w\W]+?>(.+)<\/a>/';
        preg_match($re, $element, $turn);
        $re = '/<a ?.+?>(.+?)<\/a>/';
        preg_match($re, $turn[0], $awayTeam);
        $awayTeam[0] = strip_tags($awayTeam[0], '<br>');

        //取得主隊
        $re = '/<td ?class="wh-6 t-l"[\w\W]+?>(.+?)<\/a>/';
        preg_match($re, $element, $turn);
        $re = '/<a ?.+?>(.+?)<\/a>/';
        preg_match($re, $turn[0], $homeTeam);
        $homeTeam[0] = strip_tags($homeTeam[0], '<br>');

        //取得比賽時間s
        $re = '/<td ?class="wh-5 bf">[\w\W]+?(\d+:\d+)/';
        preg_match($re, $element, $time);
        $time[0] = preg_replace('/[\n\s\t]/', "", $time[0]);


        // 取得積分
        $re = '/<td ?class="wh-7 b-l">[\w\W]+?(\d+\.\d+)[\w\W]+?(\d+\.\d+)<\/a>/';
        preg_match($re, $element, $count);


        $dbElement = "(no, date, type, time, away_team, home_team, lose, win)";
        $dbData = "('$number[1]', '$date[1]' ,'$competition[1]','$time[1]','$awayTeam[1]','$homeTeam[1]','$count[1]','$count[2]')";

        $err = $db->insertDB($dbname, $dbElement, $dbData);
        // $err = insertDB($servername = "localhost", $username = "root", $password = "", $dbname, $dbElement, $dbData);
        if ($err != null) {
            return $err;
        }
    }


    function show($data, $date)
    {
        // print_r(mysqli_fetch_all($data));
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

    // 擷取 table
    function sub_table($str)
    {
        $start = '<div class="lqsf-body" id="dcc">';
        $end = '<div class="footer-fix" id="ggArea">';

        $s = strpos($str, $start) + strlen($start);
        $e = strpos($str, $end);

        return substr($str, $s, $e - $s);
    }

    ?>


</body>

</html>