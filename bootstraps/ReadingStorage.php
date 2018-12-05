<?php

require_once __DIR__.'/../../vendor/autoload.php';

putenv("AWS_KEY=<YOUR KEY>");
putenv("AWS_SECRET=<YOUR SECRET>");
putenv("MAX_SQS_MESSAGES=10");

require_once __DIR__.'/../../config/config.php';