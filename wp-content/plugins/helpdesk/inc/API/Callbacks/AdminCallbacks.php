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

    public function skill_manager ()
    {
        echo "<h2>Skill Manager</h2>";

        echo "<table class='widefat fixed'>";
        echo "<thead><tr><th>Agent Name</th><th>Agent Skills</th></tr></thead>";
        echo "<tbody>";
        echo "<tr><td>Dilip Clarke</td><td>Keyboard</td></tr>";
        echo "<tr><td>Bert Smith</td><td>Monitor</td></tr>";
        echo "</tbody>";
        echo "</table>";
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