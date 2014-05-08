<?php
ob_start();
$pageModifiedTime = filemtime(__FILE__);

require_once('includes/pageCode/welcomeCode.php');

$pageBody = <<<EOL
    <div id="contentWrapper">
        <div id="welcomeImageColumn">
            <div id="welcomeImageWrapper">
                <img src="images/system/indexImages/rodanthe.jpg"
                    alt="An image of the North Carolina coast at Rodanthe where inundation and infrastructure
                        damage following Hurricane Sandy is clearly visible." height="357" width="550" title="" />
            </div>
            <div id="welcomeImageCaptionWrapper">
                <p><span class="captionTitle" id="captionTitle">Rodanthe, NC.</span> <span id="captionText">
                    Hurricane Sandy caused this section of coastline to experience inundation with resulting
                    damage to housing and infrastructure.</span></p>
            </div>
        </div>
        <div id="welcomeTextColumn">
        <h1>Welcome $welcomeBackHTML to USGS iCoast</h1>
        <p>You are logged in as <span class="userData">$userEmail</span><br>
            If this is not you, logout then login with your Google Account.</p>
        $variableContent
        </div>
    </div>
EOL;

require_once('includes/template.php');