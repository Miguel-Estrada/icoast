<?php
ob_start();
$pageModifiedTime = filemtime(__FILE__);

require_once('includes/pageCode/eventViewerCode.php');
require_once('includes/adminNavigation.php');
$pageBody = <<<EOL
    <div id="adminPageWrapper">
        $adminNavHTML
        <div id="adminContentWrapper">

        </div>
    </div>
EOL;

require_once('includes/template.php');
