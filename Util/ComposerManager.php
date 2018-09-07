<?php

namespace Protacon\Bundle\TestToolsBundle\Util;

use function json_decode;
use Symfony\Component\Console\Style\SymfonyStyle;

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

    public function initialize(SymfonyStyle $io)
    {
        $data = json_decode($this->projectDir . DIRECTORY_SEPARATOR . 'composer.json');

        $io->comment($data);
    }
}
