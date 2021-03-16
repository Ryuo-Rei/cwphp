<?php

function sanitize($before)
{
    foreach ($before as $key => $value) {
        $after[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    return $after;
}

function hash_password($password)
{
    $hashed_password = hash('sha256', $password);
    return $hashed_password;
}
