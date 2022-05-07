<?php

function hashPasswork($plainText)
{
    return password_hash($plainText, PASSWORD_BCRYPT);
}

function verifyPasswork($plainText, $hash)
{
    return password_verify($plainText, $hash);
}
