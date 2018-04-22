<?php

$router->get('me', ['uses' => 'AuthenticatedUsersController@me'])->middleware('auth:api');
