<?php

$url = 'http://username:password@hostname/path?arg=value#anchor';


$base = parse_url($url);
echo $base['path'];

echo basename($base['path']);