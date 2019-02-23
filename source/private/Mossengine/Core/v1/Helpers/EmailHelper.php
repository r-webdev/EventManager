<?php
namespace Mossengine\Core\v1\Helpers;

use Closure;
use Http\Adapter\Guzzle6\Client;
use Mailgun\Mailgun;

/**
 * Class EmailHelper
 * @package Mossengine\Core\v1\Helpers
 */
class EmailHelper
{
    /**
     * @var array
     */
    public $arrayBag = [];

    /**
     * EmailHelper constructor.
     * @param null $arrayBag
     */
    public function __construct(array $arrayBag = []) {
        $this->defaults($arrayBag);
    }

    /**
     * @param array $arrayBag
     * @return $this
     */
    public function defaults(array $arrayBag = []) {
        $this->arrayBag                   = [
            'attributes' => [
                'subject' => array_get($arrayBag, 'attributes.subject', config('mailgun.defaults.attributes.subject')),
                'from' => array_get($arrayBag, 'attributes.from', config('mailgun.defaults.attributes.from')),
            ],
            'recipients' => [
                'to' => array_filter(
                    (
                    !empty($arrayTemp = array_get($arrayBag, 'recipients.to', null))
                        ? (
                    is_array($arrayTemp)
                        ? $arrayTemp
                        : [$arrayTemp]
                    )
                        : config('mailgun.defaults.recipients.to')
                    ),
                    function($stringEmail) {
                        return $this->isEmail($stringEmail);
                    }
                ),
                'cc' => array_filter(
                    (
                    !empty($arrayTemp = array_get($arrayBag, 'recipients.cc', null))
                        ? (
                    is_array($arrayTemp)
                        ? $arrayTemp
                        : [$arrayTemp]
                    )
                        : config('mailgun.defaults.recipients.cc')
                    ),
                    function($stringEmail) {
                        return $this->isEmail($stringEmail);
                    }
                ),
                'bcc' => array_filter(
                    (
                    !empty($arrayTemp = array_get($arrayBag, 'recipients.bcc', null))
                        ? (
                    is_array($arrayTemp)
                        ? $arrayTemp
                        : [$arrayTemp]
                    )
                        : config('mailgun.defaults.recipients.bcc')
                    ),
                    function($stringEmail) {
                        return $this->isEmail($stringEmail);
                    }
                )
            ],
            'contents' => [
                'text' => (
                !empty($arrayTemp = array_get($arrayBag, 'contents.text', null))
                    ? (
                is_array($arrayTemp)
                    ? $arrayTemp
                    : [$arrayTemp]
                )
                    : config('mailgun.defaults.contents.text')
                ),
                'html' => (
                !empty($arrayTemp = array_get($arrayBag, 'contents.html', null))
                    ? (
                is_array($arrayTemp)
                    ? $arrayTemp
                    : [$arrayTemp]
                )
                    : config('mailgun.defaults.contents.html')
                ),
            ]
        ];

        return $this;
    }

    /**
     * @param array $arrayBag
     * @return EmailHelper
     */
    public static function create(array $arrayBag = []) {
        // Return a new fresh helper
        return new EmailHelper($arrayBag);
    }

    /**
     * @param $stringType
     * @param $mixedValue
     * @return $this
     */
    private function attribute($stringType, $mixedValue = null) {
        if (empty($mixedValue)) {
            return array_get(
                $this->arrayBag,
                'attributes.' . $stringType
            );
        }

        // Set a new value for the attribute
        array_set(
            $this->arrayBag,
            'attributes.' . $stringType,
            $mixedValue
        );

        return $this;
    }

    /**
     * @param $stringSubject
     * @return EmailHelper
     */
    public function subject($stringSubject = null) {
        return $this->attribute('subject', $stringSubject);
    }

    /**
     * @param $stringFrom
     * @return EmailHelper
     */
    public function from($stringFrom = null) {
        return $this->attribute('from', $stringFrom);
    }

    /**
     * @param $stringType
     * @param $mixedEmail
     * @return $this
     */
    private function recipient($stringType, $mixedEmail = null) {
        if (empty($mixedEmail)) {
            return implode(', ', array_get($this->arrayBag, 'recipients.' . $stringType, []));
        }

        // Merge the new recipient(s) with the existing array
        array_set(
            $this->arrayBag,
            'recipients.' . $stringType,
            array_merge(
                array_get(
                    $this->arrayBag,
                    'recipients.' . $stringType,
                    []
                ),
                array_filter(
                    (
                    is_array($mixedEmail) ? $mixedEmail : [is_string($mixedEmail) ? $mixedEmail : null]
                    ),
                    function($stringEmail) {
                        return $this->isEmail($stringEmail);
                    }
                )
            )
        );

        return $this;
    }

    /**
     * @param $stringEmail
     * @return mixed
     */
    private function isEmail($stringEmail) {
        return filter_var($stringEmail, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param $mixedEmail
     * @return EmailHelper
     */
    public function to($mixedEmail = null) {
        return $this->recipient('to', $mixedEmail);
    }

    /**
     * @param $mixedEmail
     * @return EmailHelper
     */
    public function cc($mixedEmail = null) {
        return $this->recipient('cc', $mixedEmail);
    }

    /**
     * @param $mixedEmail
     * @return EmailHelper
     */
    public function bcc($mixedEmail = null) {
        return $this->recipient('bcc', $mixedEmail);
    }

    /**
     * @param $stringType
     * @param $mixedLine
     * @return $this
     */
    private function content($stringType, $mixedLine = null) {
        if (empty($mixedLine)) {
            return implode(PHP_EOL, array_get($this->arrayBag, 'contents.' . $stringType, []));
        }

        // Merge the new content(s) with the existing array
        array_set(
            $this->arrayBag,
            'contents.' . $stringType,
            array_merge(
                array_get($this->arrayBag, 'contents.' . $stringType, []),
                (is_array($mixedLine) ? $mixedLine : [$mixedLine])
            )
        );

        return $this;
    }

    /**
     * @param $mixedLine
     * @return EmailHelper
     */
    public function text($mixedLine = null) {
        return $this->content('text', $mixedLine);
    }

    /**
     * @param $mixedLine
     * @return EmailHelper
     */
    public function html($mixedLine = null) {
        return $this->content('html', $mixedLine);
    }

    /**
     * @param Closure|null $closureTrue
     * @param Closure|null $closureFalse
     * @return bool
     */
    public function send(Closure $closureTrue = null, Closure $closureFalse = null) {
        // Define the successful send as default false
        $boolSuccessfulSend = false;

        // Define a closure object for sending to closures if defined
        $closureObject = null;

        // Attempt to build and send from the set data
        try {
            // New mailgun based on mailgun key and guzzle client
            $mailgun = new Mailgun(config('mailgun.api.key'), new Client());

            // Attempt to send the message and return the response back into the closure object
            $closureObject = $mailgun->sendMessage(config('mailgun.api.domain'), array_filter(
                [
                    'to'      => $this->to(),
                    'cc'      => $this->cc(),
                    'bcc'     => $this->bcc(),
                    'from'    => $this->from(),
                    'subject' => $this->subject(),
                    'text'    => $this->text(),
                    'html'    => $this->html(),
                ],
                function($value) {
                    return !empty($value);
                }
            ));

            // Indicate true for send as an exception would have been thrown otherwise.
            $boolSuccessfulSend = true;
        } catch (\Exception $e) {
            // Exception now becomes the closure object
            $closureObject = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage()
            ];
        }

        // if successful send and we have the true closure then return the results of the true closure
        if (true === $boolSuccessfulSend && $closureTrue instanceof Closure) {
            call_user_func($closureTrue, $closureObject);
        }

        // if unsuccessful send and we have the false closure then return the results of the false closure
        if (true !== $boolSuccessfulSend && $closureFalse instanceof Closure) {
            call_user_func($closureFalse, $closureObject);
        }

        // Made it this far? then no closures and just return the bool success
        return $boolSuccessfulSend;
    }
}