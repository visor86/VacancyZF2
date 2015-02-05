<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\FlashMessenger;

class ShowMessages extends AbstractHelper
{
    public function __invoke()
    {
        $messenger = new FlashMessenger();
        $error_messages = $messenger->getErrorMessages();
        $messages = $messenger->getMessages();
        $result = '';
        if (count($error_messages)) {
            $result .= '<div class="alert alert-danger" role="alert">'
                . '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>'
                . '<span class="sr-only">Error:</span>';
            foreach ($error_messages as $message) {
                $result .= '<div>' . $message . '</div>';
            }
            $result .= '</div>';
        }
        if (count($messages)) {
            $result .= '<ul>';
            foreach ($messages as $message) {
                $result .= '<li>' . $message . '</li>';
            }
            $result .= '</ul>';
        }
        return $result;
    }
}

