<?php
declare(strict_types = 1);

namespace Protacon\Bundle\TestToolsBundle\Util;

use RuntimeException;
use function in_array;
use function json_decode;
use function str_replace;

/**
 * Class ComposerManager
 *
 * @package Protacon\Bundle\TestToolsBundle\Util
 */
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

        // In this point composer file exists and it's readable
        $data = json_decode(file_get_contents($composerFile), true);

        $write = false;

        if (!array_key_exists('vendor-bin-install', $data['scripts'])) {
            $data['scripts']['vendor-bin-install'] = '@composer bin all install --ansi';

            $write = true;
        }

        if (!array_key_exists('vendor-bin-update', $data['scripts'])) {
            $data['scripts']['vendor-bin-update'] = '@composer bin all update --ansi';

            $write = true;
        }

        if (!in_array('@vendor-bin-install', $data['scripts']['post-install-cmd'], true)) {
            array_unshift($data['scripts']['post-install-cmd'], '@vendor-bin-install');

            $write = true;
        }

        if (!in_array('@vendor-bin-update', $data['scripts']['post-update-cmd'], true)) {
            array_unshift($data['scripts']['post-update-cmd'], '@vendor-bin-update');

            $write = true;
        }

        if (!in_array('@composer dump-autoload', $data['scripts']['post-install-cmd'], true)) {
            $data['scripts']['post-install-cmd'][] = '@composer dump-autoload';

            $write = true;
        }

        if (!in_array('@composer dump-autoload', $data['scripts']['post-update-cmd'], true)) {
            $data['scripts']['post-update-cmd'][] = '@composer dump-autoload';

            $write = true;
        }

        if ($write === true &&
            file_put_contents($composerFile, str_replace('\/', '/', json_encode($data, JSON_PRETTY_PRINT))) === false
        ) {
            throw new RuntimeException('Could not write \''  . $composerFile . '\'');
        }
    }

    /**
     * @return string
     */
    private function getComposerFile(): string
    {
        $composerFile = $this->projectDir . DIRECTORY_SEPARATOR . 'composer.json';

        if (!file_exists($composerFile) || !is_readable($composerFile)) {
            throw new RuntimeException('Composer file not found or it\'s not readable from \''  . $composerFile . '\'');
        }

        return $composerFile;
    }
}
