<?php
include  'db/db.php';

$dbname = "contest";
$searchDate = $_GET['date'];
$url = "https://cp.zgzcw.com/lottery/jcplayvsForJsp.action?lotteryId=26&issue=$searchDate";
$db = new DB($servername = "localhost", $username = "root", $password = "", $dbname = "contest", $show_log = TRUE);
$db->show_log = TRUE;




$str = file_get_contents($url);
$need =  sub_table($str);

$re = '/(\d{4}\-\d{2}\-\d{2}星期.)/u';
preg_match($re, $need, $date);

if (count($date) == 0) {
    echo "No data";
}

$re = "/<tr ?.*[\n \w\W]+?<\/tr>/";
preg_match_all($re, $need, $tr);

for ($i = 0; $i < count($tr[0]); $i++) {
    $err = catchDetial($tr[0][$i], $date);

    if ($err != null) {
        break;
    }
}
if ($db->show_log) {
    echo "InsertDB successfully<br>";
}
if ($db->show_log) {
    echo ("Finish");
}




function catchDetial($element, $date)
{
    global $db;
    global $dbname;


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

    if ($err != null) {
        return $err;
    }
}
function sub_table($str)
{
    $start = '<div class="lqsf-body" id="dcc">';
    $end = '<div class="footer-fix" id="ggArea">';

    $s = strpos($str, $start) + strlen($start);
    $e = strpos($str, $end);

    return substr($str, $s, $e - $s);
}
