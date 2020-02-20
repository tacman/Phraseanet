<?php

/*
 * This file is part of Phraseanet
 *
 * (c) 2005-2016 Alchemy
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Alchemy\Phrasea\Command\Databox;

use Alchemy\Phrasea\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListDataboxCommand extends Command
{
    /**
     * Constructor
     */
    public function __construct($name = null)
    {
        parent::__construct('databox:list');

        $this->setDescription('List all databox in Phraseanet')
            ->setHelp('');

        return $this;
    }

    protected function doExecute(InputInterface $input, OutputInterface $output)
    {
        try {
            $databoxes = array_map(function (\databox $databox) {
                return $this->listDatabox($databox);
            }, $this->container->getApplicationBox()->get_databoxes());

            $table = $this->getHelperSet()->get('table');
            $table
                ->setHeaders(['id', 'name', 'alias'])
                ->setRows($databoxes)
                ->render($output);

        } catch (\Exception $e) {
            $output->writeln('<error>Listing databox failed : '.$e->getMessage().'</error>');
        }

        return 0;
    }

    private function listDatabox(\databox $databox)
    {
        return [
            $databox->get_sbas_id(),
            $databox->get_dbname(),
            $databox->get_viewname()
        ];
    }

}
