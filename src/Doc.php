<?php

namespace Bfg\Doc;

use Bfg\Doc\Core\DataForGenerate;

/**
 * Class Doc
 * @package Bfg\Doc
 */
class Doc
{
    /**
     * Run a generator
     */
    public function generate()
    {
        event(app(DataForGenerate::class));
    }
}
