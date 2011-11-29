<?php

namespace Brood\Action\Distribute;

use Brood\Action\AbstractAction,
    Brood\Log\Logger;

class Git extends AbstractAction
{
    public function execute()
    {
        if (!$this->chdir($this->getRequiredParameter('directory'))) {
            return;
        }

        $this->log(Logger::INFO, __CLASS__, 'Performing git pull');

        // Use --ff-only to ensure that we don't have any local revisions that aren't
        // also at the remote. Forbidding local revisions helps all nodes stay in sync.
        $command = 'git pull --ff-only';
        unset($output);

        if (!$this->exec($command, $output, $return_var)) {
            return;
        }

        // If the ref parameter is present, reset to that ref. This allows us to "roll
        // back" to a previous commit or tag if a deploy fails (or if the code that was
        // deployed was broken).
        $ref = (string) $this->getParameter('ref');
        if (!empty($ref)) {
            $this->log(Logger::INFO, __CLASS__, sprintf('Performing git reset to "%s"', $ref));

            $command = sprintf('git reset --hard %s', escapeshellarg($ref));
            unset($output);

            if (!$this->exec($command, $output, $return_var)) {
                return;
            }
        }

        // If the clean parameter is present, clean up any build artifacts or temp files.
        $clean = $this->getParameter('clean');
        if (isset($clean[0])) {
            $this->log(Logger::INFO, __CLASS__, 'Removing untracked and ignored files and directories');

            $command = sprintf('git clean -dxf');
            unset($output);

            if (!$this->exec($command, $output, $return_var)) {
                return;
            }
        }
    }
}