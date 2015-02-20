<?php
$page = 'snap' . str_replace('/', '-', $_GET['_escaped_fragment_']) . '.html';
if (is_readable($page)) {
    die(file_get_contents($page));
}
header('Location: /#!/');