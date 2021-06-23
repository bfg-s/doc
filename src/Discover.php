<?php

namespace Bfg\Doc;

use Bfg\Doc\Core\DataForGenerate;

/**
 * Class Discover
 * @package Bfg\Doc
 */
class Discover
{
    public function handle()
    {
        \Doc::generate();
    }
}
