<?php
namespace Sanity;

use InvalidArgumentException;
use JsonSerializable;

class Selection implements JsonSerializable
{
    private $selection;

    /**
     * Constructs a new selection
     *
     * @param string|array $selection
     */
    public function __construct($selection)
    {
        $this->selection = $this->normalize($selection);
    }

    /**
     * Serializes the selection for use in requests
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->serialize();
    }

    /**
     * Serializes the selection for use in requests
     *
     * @return array
     */
    public function serialize()
    {
        return $this->selection;
    }

    /**
     * Returns whether or not the selection *can* match multiple documents
     *
     * @return bool
     */
    public function matchesMultiple()
    {
        return isset($this->selection['id']) && is_string($this->selection['id']);
    }

    /**
     * Validates and normalizes a selection
     *
     * @return array
     * @throws InvalidArgumentException
     */
    private function normalize($selection)
    {
        if (isset($selection['query'])) {
            return ['query' => $selection['query']];
        }

        if (is_string($selection) || is_array($selection)) {
            return ['id' => $selection];
        }

        $selectionOpts = implode(PHP_EOL, [
            '',
            '* Document ID (<docId>)',
            '* Array of document IDs',
            '* Array containing "query"',
        ]);

        throw new InvalidArgumentException('Unknown selection, must be one of: ' . $selectionOpts);
    }
}