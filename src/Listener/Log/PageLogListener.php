<?php

namespace BackBee\Listener\Log;

use BackBee\Event\Event;

/**
 * Class PageLogListener
 *
 * @package BackBee\Listener\Log
 *
 * @author  Djoudi Bensid <djoudi.bensid@lp-digital.fr>
 */
class PageLogListener implements LogListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public static function onFlushContent(Event $event): void
    {
        // TODO: Implement onFlushContent() method.
    }

    /**
     * {@inheritDoc}
     */
    public static function onPreRemoveContent(Event $event): void
    {
        // TODO: Implement onPreRemoveContent() method.
    }
}