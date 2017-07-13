<?php

namespace MC\StreamsAPI\Data;

/**
 * A simple stream repository interface
 */
interface StreamRepositoryInterface
{
    /**
     * Get all streams
     *
     * In a real application, it would not be a good idea to load all entities from storage as
     * there could be many. Instead, result sets should be limited and paginated to a size that
     * meets both the business and performance needs.
     *
     * @return Stream[]
     */
    public function getAll() : array;

    /**
     * Get a single stream by ID
     *
     * @param string $id
     * @return Stream
     */
    public function get(string $id);
}
