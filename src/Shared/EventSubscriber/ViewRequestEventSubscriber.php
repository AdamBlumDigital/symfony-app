<?php

declare(strict_types=1);

namespace App\Shared\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 *	Not currently in use, since it seems like a pretty
 *	bad idea to use RegEx for HTML.
 */
class ViewRequestEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
//			KernelEvents::RESPONSE => ['minifyView', -256]
        ];
    }

    public function minifyView(ResponseEvent $event): Response
    {
        $response = $event->getResponse();

        if (
            (HttpKernelInterface::MAIN_REQUEST != $event->getRequestType())
            || ('_' === substr(
                strval(
                    $event->getRequest()->get('_route')
                ), 0, 1
            ))) {
            return $response;
        }

        $buffer = (string) $response->getContent();

        $replace = [
            '/<!--[^\[](.*?)[^\]]-->/s' => '',
            "/<\?php/" => '<?php ',
            "/\n([\S])/" => '$1',
            "/\r/" => '',
            "/\n/" => '',
            "/\t/" => '',
            '/ +/' => ' ',
        ];

        if (false !== strpos($buffer, '<pre>')) {
            $replace = [
                '/<!--[^\[](.*?)[^\]]-->/s' => '',
                "/<\?php/" => '<?php ',
                "/\r/" => '',
                "/>\n</" => '><',
                "/>\s+\n</" => '><',
                "/>\n\s+</" => '><',
            ];
        }

        $buffer = preg_replace(array_keys($replace), array_values($replace), $buffer);

        $response->setContent($buffer);

        return $response;
    }
}
