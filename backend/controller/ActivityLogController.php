<?php
require_once __DIR__ . '/../model/ActivityLogModel.php';



function createActivityLog($user_name, $action, $description)
{
    return addActivityLog(
        $user_name,
        $action,
        $description
    );
}

function getActivityLogs()
{
    return getAllActivityLogs();
}