<?php
/**
 * Redirect visitor to the hash-bang url.
 */
header('Location: /#!' . $_SERVER['REQUEST_URI']);
die();