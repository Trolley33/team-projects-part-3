<?php

/**
 * @package Helpdesk Plugin
 */
namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{
    public function dashboard ()
    {
        return require_once("$this->plugin_path/templates/admin.php");
    }

    public function problem_manager ()
    {
        return require_once("$this->plugin_path/templates/problem-viewer.php");
    }

    public function equipment_manager ()
    {
        // return require_once("$this->plugin_path/templates/equipment_manager.php");
        echo "<h2>Equipment Manager</h2>";
    }

    public function software_manager ()
    {
        // return require_once("$this->plugin_path/templates/software_manager.php");
        echo "<h2>Software Manager</h2>";
    }
}