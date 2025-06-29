<?php

function team_task_manager_setup_roles() {
    add_role('team_member', 'Team Member', ['read' => true]);
}