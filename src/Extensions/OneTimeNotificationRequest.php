<?php


namespace BotMan\Drivers\Facebook\Extensions;

use BotMan\BotMan\Interfaces\WebAccess;
use JsonSerializable;

class OneTimeNotificationRequest implements JsonSerializable, WebAccess
{
    /**
     * @var string
     */
    protected $title;

    protected $payload;

    /**
     * @param string $title
     *
     * @return $this
     * @throws \Exception
     */
    public function setTitle(string $title)
    {
        if (strlen($title) > 65) {
            throw new \Exception('Title must be less than 65 character long');
        }
        $this->title = $title;

        return $this;
    }

    public function setPayload(string $payload)
    {
        $this->payload = $payload;

        return $this;
    }

    public function toArray()
    {
        return [
            'attachment' => [
                'type' => 'template',
                'payload' => [
                    'template_type' => 'one_time_notif_req',
                    'title' => $this->title,
                    'payload' => $this->payload,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Get the instance as a web accessible array.
     * This will be used within the WebDriver.
     *
     * @return array
     */
    public function toWebDriver()
    {
        return [
            'type' => 'one_time_notif_req',
        ];
    }
}