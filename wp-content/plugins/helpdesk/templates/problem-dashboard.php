<?php

global $wpdb;

$query = "
    SELECT $wpdb->posts.*
    FROM $wpdb->posts
    WHERE $wpdb->posts.post_type= 'problem'";

$result = $wpdb->get_results($query, OBJECT);

?>

<h1>Problems Submitted: <span><?php echo count($result); ?></span></h1>

<div style="max-width: 70em;">
    <table id="problem-table" class="display cell-border compact">
        <thead>
            <tr>
                <th>ID</th>
                <th>Problem Description</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result as $row)
            {
                echo "<tr>";
                    echo "<td>$row->ID</td>";
                    echo "<td>$row->post_title</td>";
                    echo "<td><button style='width: 100%;height: 100%;' onclick='window.location.href=\"$row->guid\"'>View</button></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function () {
       $('#problem-table').dataTable();
    });
</script>