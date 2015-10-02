<?php

    namespace MemcacheToolShow\Console\Command;

    require_once __DIR__ . '/../../lib/MemcachedHelper.php';

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Formatter\OutputFormatterStyle;
    use Symfony\Component\Console\Helper\Table;
    use MemcacheTool\Console\Helper\MemcachedHelper;

    class MemcacheToolShowCommand extends Command {
        protected function configure() {
            $this->setName('show')
                ->setDescription('Shows list of all memcache keys and values')
                ->addOption(
                    'address',
                    'a',
                    InputOption::VALUE_OPTIONAL,
                    'Memcache server network address (ip)',
                    'localhost'
                )
                ->addOption(
                    'port',
                    'p',
                    InputOption::VALUE_OPTIONAL,
                    'Memcache server port',
                    11211
                )
                ->addOption(
                    'count',
                    'c',
                    InputOption::VALUE_NONE,
                    'Number of the keys in storage'
                )
                ->addOption(
                    'number',
                    null,
                    InputOption::VALUE_OPTIONAL,
                    'Number of keys that will be shown'
                )
                ->addArgument(
                    'key',
                    InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                    'Key or keys to display'
                );
        }

        protected function execute(InputInterface $input, OutputInterface $output) {
            $memcachedAddress = $input->getOption('address');
            $memcachedPort = $input->getOption('port');
            $memcachedHelper = new MemcachedHelper($memcachedAddress, $memcachedPort);

            if($input->getOption('count')) {
                $keysCount = $memcachedHelper->getKeysCount();
                $output->writeln("<info>Number of the keys in storage: {$keysCount}</info>");
                return;
            }

            $keys = $input->getArgument('key');
            $compactMode = (!empty($keys) && count($keys) <= 3) ? true : false;
            $keysNumber = $input->getOption('number');
            $mcData = $memcachedHelper->getMemcacheData($keys, $keysNumber, $compactMode);

            if($compactMode && is_array($mcData) && !empty($mcData)) {
                $outputStyle = new OutputFormatterStyle('yellow', null, array('bold'));
                $output->getFormatter()->setStyle('compactInfo', $outputStyle);
                foreach($mcData as $data) {
                    $output->writeln("<compactInfo>Key:</compactInfo> {$data[0]}");
                    $output->writeln("");
                    $output->writeln("<compactInfo>Value:</compactInfo> {$data[1]}");
                    $output->writeln("");
                    $output->writeln("<fg=green;options=bold>------------------------------------------</>");
                }
            } elseif(is_array($mcData) && !empty($mcData)) {
                $table = new Table($output);
                $table
                    ->setHeaders(['Key', 'Value'])
                    ->setRows($mcData);
                $table->render();
            } else {
                $output->writeln("<error>Nothing to show! Check your memcached server.</error>");
            }
        }
    }
