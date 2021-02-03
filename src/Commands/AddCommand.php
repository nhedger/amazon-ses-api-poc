<?php

namespace Hedger\SES\Commands;

use Aws\Credentials\CredentialProvider;
use Aws\Credentials\Credentials;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Aws\SES\SesClient;

class AddCommand extends Command
{
    protected static $defaultName = 'add';

    protected function configure()
    {
        $this->setDescription('Adds a new domain to SES');

        $this->addOption('region', 'r', InputOption::VALUE_OPTIONAL, 'AWS region', 'us-east-2');
        $this->addOption('key', 'k', InputOption::VALUE_REQUIRED);
        $this->addOption('secret', 's', InputOption::VALUE_REQUIRED);

        $this->addArgument('fqdn', InputArgument::REQUIRED, 'The fully qualified domain name to add to AWS');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fqdn = $input->getArgument('fqdn');

        $output->writeln("Trying to add [$fqdn] to Amazon SES.");

        $ses = new SesClient([
            'region' => $input->getOption('region'),
            'version' => 'latest',
            'credentials' => CredentialProvider::fromCredentials(
                new Credentials($input->getOption('key'), $input->getOption('secret'))
            )
        ]);

        $result = $ses->verifyDomainIdentity([
            'Domain' => $fqdn,
        ]);
        $token = $result->get('VerificationToken');

        $result = $ses->verifyDomainDkim([
            'Domain' => $fqdn,
        ]);

        $output->writeln("\nDNS records to add to the zone of $fqdn :");
        $output->writeln("TXT  _amazonses.$fqdn   $token");
        foreach ($result->get('DkimTokens') as $dkim) {
            $output->writeln("CNAME $dkim._domainkey.$fqdn   $dkim.dkim.amazonses.com");
        }


        return Command::SUCCESS;
    }
}