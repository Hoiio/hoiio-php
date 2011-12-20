<?php

/**
 * Represent a conference room. It contains an array of
 * ConferenceMember objects, each containing the destination
 * number and the transaction reference.
 */
class ConferenceRoom {
    private $members = NULL;
    private $room = NULL;

    /**
     * Get the Room ID of the conference room.
     *
     * @return string   Room ID
     */
    public function getRoom() {
        return $this->room;
    }

    /**
     * Get the array of ConferenceMember objects.
     *
     * @return array    Array of ConferenceMember objects
     */
    public function getMembers() {
        return $this->members;
    }

    public function __construct($room, $members) {
        $this->room = $room;
        $this->members = $members;
    }
}