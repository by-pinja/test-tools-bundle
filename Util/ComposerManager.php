<?php

namespace Protacon\Bundle\TestToolsBundle\Util;

use function json_decode;

class ComposerManager
{
    /**
     * @var string
     */
    private $projectDir;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function initialize()
    {
        $data = json_decode($this->projectDir . DIRECTORY_SEPARATOR . 'composer.json');

        print_r($data);
    }
}
