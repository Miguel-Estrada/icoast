<?php

require_once('../includes/userFunctions.php');
require_once('../includes/globalFunctions.php');
//require_once($dbmsConnectionPathDeep);
$dbConnectionFile = DB_file_location();
require_once($dbConnectionFile);

if (!isset($_COOKIE['userId']) || !isset($_COOKIE['authCheckCode'])) {
    print "No Cookie Data<br>Please login to iCoast first.";
//    header('Location: index.php');
    exit;
}

$userId = $_COOKIE['userId'];
$authCheckCode = $_COOKIE['authCheckCode'];

$userData = authenticate_cookie_credentials($DBH, $userId, $authCheckCode, FALSE);
if (!$userData) {
    print "Failed iCoast Authentication<br>Please logout and then back in to iCoast.";
    exit;
}
$authCheckCode = generate_cookie_credentials($DBH, $userId);

if ($userData['account_type'] != 4) {
    print "Insufficient Permissions<br>Access Denied.";
//    header('Location: index.php');
    exit;
}

function convertSeconds($s) {
    $mins = floor($s / 60);
    $secs = $s % 60;
    return "$mins Minute(s) $secs Second(s)";
}

if (isset($_POST['averageUpperLimit'])) {
    settype($_POST['averageUpperLimit'], 'integer');
    if (!empty($_POST['averageUpperLimit'])) {
        $upperTimeLimit = $_POST['averageUpperLimit'];
    }
} else {
    $upperTimeLimit = 3600;
}

$classificationCount = 0;
$timeTotal = 0;
$excessiveTimeCount = 0;
$longestAnnotation = 0;
$shortestAnnotation = 0;
$avgTimeQuery = "SELECT initial_session_start_time, initial_session_end_time FROM annotations WHERE annotation_completed = 1 AND annotation_completed_under_revision = 0";
//$avgTimeResults = run_prepared_query($DBH, $avgTimeQuery, $avgTimeParams);
foreach ($DBH->query($avgTimeResults) as $classification) {
//while ($classification = $avgTimeResults->fetch(PDO::FETCH_ASSOC)) {

    $startTime = strtotime($classification['initial_session_start_time']);
    $endTime = strtotime($classification['initial_session_end_time']);
    $timeDelta = $endTime - $startTime;
    if ($timeDelta < $upperTimeLimit) {
        $timeTotal += $timeDelta;
        $classificationCount++;
        if ($timeDelta > $longestAnnotation) {
            $longestAnnotation = $timeDelta;
        } else if ($timeDelta < $shortestAnnotation || $shortestAnnotation == 0) {

        }
    } else {
        $excessiveTimeCount++;
    }

//    print "{$classification['initial_session_start_time']} ($startTime) - {$classification['initial_session_end_time']} ($endTime) = $timeDelta. Total = $timeTotal. Count = $classificationCount<br>";
}

$averageTime = $timeTotal / $classificationCount;

print "Average Time: " . convertSeconds($averageTime) . "<br>";
print "Longest Time: " . convertSeconds($longestAnnotation) . "<br>";
print "Shortest Time: " . convertSeconds($shortestAnnotation) . "<br>";
print $excessiveTimeCount . " classifications exceeded 60 minutes and are excluded from the average.<br>";