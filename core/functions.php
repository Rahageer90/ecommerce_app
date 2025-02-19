<?php

function abort($status = 404) {
    http_response_code($status);
    die("Error $status: Page Not Found");
}
