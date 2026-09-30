<?php

require_once __DIR__ . '/../model/DashboardModel.php';
function getInspectLastThreeMonths()
{
    $branch = $_SESSION['branch'] ?? null;
    return getInspectionLastThreeMonths($branch);
}

function getFireExtinguisherTypeCountsController()
{
    $branch = $_SESSION['branch'] ?? null;
    return getFireExtinguisherTypeCounts($branch);
}

function getAllExpiringCount()
{
    $branch = $_SESSION['branch'] ?? null;
    return getExpiringFireExtinguishers($branch);
}

function getNotGoodCondition()
{
    $branch = $_SESSION['branch'] ?? null;
    return getAllNotGoodCondition($branch);
}

function getGoodCondition()
{
    $branch = $_SESSION['branch'] ?? null;
    return getAllGoodCondition($branch);
}

function getTotalNotGoodInstalledFireExtinguishers()
{
    $branch = $_SESSION['branch'] ?? null;
    return getNotGoodInstalledFireExtinguishersCount($branch);
}

function getTotalGoodInstalledFireExtinguishers()
{
    $branch = $_SESSION['branch'] ?? null;
    return getGoodInstalledFireExtinguishersCount($branch);
}

function getTotalInstalledFireExtinguishers()
{
    $branch = $_SESSION['branch'] ?? null;
    return getInstalledFireExtinguishersCount($branch);
}

function getTotalNotGoodSpareFireExtinguishers()
{
    $branch = $_SESSION['branch'] ?? null;
    return getNotGoodSpareFireExtinguishersCount($branch);
}

function getTotalGoodSpareFireExtinguishers()
{
    $branch = $_SESSION['branch'] ?? null;
    return getGoodSpareFireExtinguishersCount($branch);
}

function getTotalSpareFireExtinguishers()
{
    $branch = $_SESSION['branch'] ?? null;
    return getSpareFireExtinguishersCount($branch);
}

function getTotalFireExtinguishersDashboard()
{
    $branch = $_SESSION['branch'] ?? null;
    return getAllFireExtinguishersCount($branch);
}