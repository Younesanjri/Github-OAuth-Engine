<?php

function generateEmbedCode($codePath) {
    $embedCode = file_get_contents($codePath);
    echo rtrim($embedCode);
}

global $embedCode;

?>