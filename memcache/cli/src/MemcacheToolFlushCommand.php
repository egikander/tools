<?php

    namespace MemcacheToolFlush\Console\Command;

    require_once __DIR__ . '/../../lib/MemcachedHelper.php';

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\ConfirmationQuestion;
    use MemcacheTool\Console\Helper\MemcachedHelper;

    class MemcacheToolFlushCommand extends Command {
        protected function configure() {
            $this->setName('flush')
                ->setDescription('Flushes memcache keys and values')
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
                ->addArgument(
                    'key',
                    InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                    'Key or keys to flush'
                );
        }

        protected function execute(InputInterface $input, OutputInterface $output) {
            $memcachedAddress = $input->getOption('address');
            $memcachedPort = $input->getOption('port');
            $memcachedHelper = new MemcachedHelper($memcachedAddress, $memcachedPort);

            $flushKeys = $input->getArgument('key');

            if(is_array($flushKeys) && !empty($flushKeys)) {
                $keyNames = implode(', ', $flushKeys);

                $maxStrLen = 30;
                if(strlen($keyNames) > $maxStrLen) {
                    $keyNames = substr($keyNames, 0, $maxStrLen) . '...';
                }

                $questionText = "<question>Do you really wanna flush " .
                                "keys - {$keyNames}?  [y/N]</question>";
            } else {
                $questionText = "<question>Do you really wanna flush " .
                                "all memcache keys?  [y/N]</question>";
            }

            $questionHelper = $this->getHelper('question');
            $question = new ConfirmationQuestion(
                $questionText,
                false
            );

            $choice = $questionHelper->ask($input, $output, $question);

            if(!$choice) {
                return;
            } else {
                if($memcachedHelper->flushMemcacheData($flushKeys)) {
                    $output->writeln("<info>All keys successfully flushed</info>");
                }
            }
        }
    }
