<?php

declare(strict_types=1);

namespace App\Shared\Message;
/**
 *	This class is configured to use the 'async' transport
 *	in `/config/packages/messenger.yaml`
 *
 *	Implement this interface in a message class if it 
 *	should be send async
 */
interface AsyncMessageInterface
{
}
