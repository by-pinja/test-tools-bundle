<?php

namespace Protacon\Bundle\TestToolsBundle\Util;

use function in_array;
use function json_decode;
use function str_replace;

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

    public function initialize(): void
    {
        $composerFile = $this->getComposerFile();

        $data = json_decode(file_get_contents($composerFile), true);

        if (!array_key_exists('vendor-bin-install', $data['scripts'])) {
            $data['scripts']['vendor-bin-install'] = '@composer bin all install --ansi';
        }

        if (!array_key_exists('vendor-bin-update', $data['scripts'])) {
            $data['scripts']['vendor-bin-update'] = '@composer bin all update --ansi';
        }

        if (!in_array('@vendor-bin-install', $data['scripts']['post-install-cmd'], true)) {
            array_unshift($data['scripts']['post-install-cmd'], '@vendor-bin-install');
        }

        if (!in_array('@vendor-bin-update', $data['scripts']['post-update-cmd'], true)) {
            array_unshift($data['scripts']['post-update-cmd'], '@vendor-bin-update');
        }

        if (!in_array('@composer dump-autoload', $data['scripts']['post-install-cmd'], true)) {
            $data['scripts']['post-install-cmd'][] = '@composer dump-autoload';
        }

        if (!in_array('@composer dump-autoload', $data['scripts']['post-update-cmd'], true)) {
            $data['scripts']['post-update-cmd'][] = '@composer dump-autoload';
        }

        file_put_contents($composerFile, str_replace('\/', '/', json_encode($data, JSON_PRETTY_PRINT)));
    }

    private function getComposerFile(): string
    {
        return $this->projectDir . DIRECTORY_SEPARATOR . 'composer.json';
    }
}
