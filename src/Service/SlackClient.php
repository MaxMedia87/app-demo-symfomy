<?php

declare(strict_types=1);

namespace App\Service;

use Nexy\Slack\Client;

class SlackClient
{
    /**
     * @var Client
     */
    private $slackClient;

    public function __construct(Client $slackClient)
    {
        $this->slackClient = $slackClient;
    }

    public function send(string $message, string $from, string $icon = ':ghost:'): void
    {
        $slackMessage = $this->slackClient->createMessage();

        $slackMessage
            ->from($from)
            ->withIcon($icon)
            ->setText($message);

        $this->slackClient->sendMessage($slackMessage);
    }
}
