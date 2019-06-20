<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once  APPPATH . '/third_party/PhpOffice/PhpWord/Autoloader.php';
require_once  APPPATH . '/third_party/PhpOffice/Common/Common_Autoloader.php';

use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

use PhpOffice\Common\Common_Autoloader;

Autoloader::register();
Settings::loadConfig();

Common_Autoloader::register();

class Phpword extends Autoloader
{
}