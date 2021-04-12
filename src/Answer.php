<?php


namespace BotMan\Drivers\Facebook;


use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\Drivers\Facebook\Extensions\QuickReplyButton;

class Answer extends \BotMan\BotMan\Messages\Incoming\Answer
{
    public static $agreeAction = 'USER_AGREE_THE_ACTION';
    public static $userCancelAction = 'USER_CANCEL_THE_ACTION';

    /**
     * @param string $agreeIntent
     *
     * @return bool
     */
    public function isYes($agreeAction = 'USER_AGREE_THE_ACTION'): bool
    {
        return $this->isInteractiveMessageReply() ? $this->getValue() == 'yes' : $this->getAction($agreeAction) == static::$agreeAction;
    }

    /**
     * @param string $cancelIntent
     *
     * @return bool
     */
    public function isNo($cancelAction = 'USER_CANCEL_THE_ACTION'): bool
    {
        return $this->isInteractiveMessageReply() ? $this->getValue() == 'no' : $this->getAction($cancelAction) == static::$userCancelAction;
    }

    /**
     * @return array|mixed
     */
    public function getContexts()
    {
        return $this->message->getExtras('apiContexts');
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasContext(string $name)
    {
        $contexts = $this->getContexts();

        return is_array($contexts) && in_array($name, $contexts);
    }

    /**
     * @return array|mixed
     */
    public function getIntent()
    {
        return $this->message->getExtras('apiIntent');
    }

    /**
     * @return array|mixed
     */
    public function getAction()
    {
        return $this->message->getExtras('apiAction');
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->message->getExtras('apiParameters');
    }

    /**
     * @param string $name
     * @param false  $default
     *
     * @return false|mixed
     */
    public function getParameter(string $name, $default = false)
    {
        $params = $this->getParameters();

        return $params[$name] ?? $default;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasParameter(string $name): bool
    {
        $params = $this->getParameters();

        return isset($params[$name]);
    }

    /**
     * @param $address
     *
     * @return Question
     */
    public function createConfirmQuestion(string $text)
    {
        return Question::create($text)
            ->addButtons([
                QuickReplyButton::create('Yes')
                    ->payload('yes'),
                QuickReplyButton::create("No")
                    ->payload('no'),
            ]);
    }
}