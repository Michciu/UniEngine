<?php

// TODO: Migrate to IIFE once PHP 5 support is removed
call_user_func(function () {
    global $_EnginePath;

    $includePath = $_EnginePath . 'modules/session/';

    include($includePath . './screens/LoginView/LoginView.component.php');
    include($includePath . './screens/LoginView/components/LoginForm/LoginForm.component.php');

});

?>
