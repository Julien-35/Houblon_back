<?php

// src/Command/SendTestEmailCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendTestEmailCommand extends Command
{
    protected static $defaultName = 'app:send-test-email';

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Send a test email.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = (new Email())
            ->from('test@example.com')
            ->to('d653d77ca5-888676@inbox.mailtrap.io')
            ->subject('Test Email')
            ->text('This is a test email.');

        try {
            $this->mailer->send($email);
            $output->writeln('Test email sent successfully.');
        } catch (\Exception $e) {
            $output->writeln('Failed to send test email: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}