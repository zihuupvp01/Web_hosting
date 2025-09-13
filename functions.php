<?php
define('USER_FILE', 'data/users.json');
define('UPLOAD_DIR', 'uploads/');

function get_users() {
    if (!file_exists(USER_FILE)) return [];
    return json_decode(file_get_contents(USER_FILE), true) ?: [];
}

function get_folder_size($folder) {    $size = 0;
    foreach (glob(rtrim($folder, '/') . '/*', GLOB_NOSORT) as $file) {
        $size += is_file($file) ? filesize($file) : get_folder_size($file);
    }
    return $size;
}
  
function save_users($users) {
    file_put_contents(USER_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

function get_user_website($email) {
    $users = get_users();
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return $user['website'] ?? null;
        }
    }
    return null;
}

function set_user_website($email, $website_name) {
    $users = get_users();
    foreach ($users as &$user) {
        if ($user['email'] === $email) {
            $user['website'] = $website_name;
        }
    }
    save_users($users);
}
  
?>