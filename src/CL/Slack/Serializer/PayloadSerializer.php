<?php

/*
 * This file is part of the Slack API library.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Slack\Serializer;

use CL\Slack\Payload\PayloadInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class PayloadSerializer extends AbstractSerializer
{
    /**
     * @param PayloadInterface $payload
     *
     * @return array The serialized payload data
     */
    public function serialize(PayloadInterface $payload)
    {

        $has_attachments = method_exists( $payload, 'getAttachments' );
        $serializedAttachments = null;
        if ( $has_attachments ) {
          $serializedAttachments = $this->serializer->serialize( $payload->getAttachments(), 'json' );
        }

        $serializedPayload = $this->serializer->serialize($payload, 'json');
        if (!$serializedPayload || !is_string($serializedPayload)) {
            throw new \RuntimeException(sprintf(
                'Failed to serialize payload; expected it to be a string, got: %s',
                var_export($serializedPayload, true)
            ));
        }

        $result = json_decode($serializedPayload, true);

        if ( $has_attachments ) {
          $result[ 'attachments' ] = $serializedAttachments;
        }

        return $result;

    }
}
