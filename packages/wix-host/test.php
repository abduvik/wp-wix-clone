<?php
require_once 'vendor/autoload.php';

use WixCloneHost\Core\EncryptionService;

print_r(EncryptionService::generateKeyPair());