<?php

namespace App;

class Config
{
    public const DB_SERVER_NAME = 'mysql';        // docker service name
    public const DB_NAME        = 'developmentdb';  // your DB
    public const DB_USERNAME    = 'root';    // from docker-compose
    public const DB_PASSWORD    = 'secret123';    // from docker-compose
}
