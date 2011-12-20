<?php

/**
 * Represent a conference room member.
 */
class ConferenceMember {
    private $dest = NULL;
    private $txnRef = '';

    /**
     * Get the destination number of this conference room member.
     *
     * @return string   Destination number
     */
    public function getDest() {
        return $this->dest;
    }

    /**
     * Get the Transaction Reference for this member's call.
     *
     * @return string   Transaction reference
     */
    public function getTxnRef() {
        return $this->txnRef;
    }

    public function __construct($txnRef, $dest) {
        $this->txnRef = $txnRef;
        $this->dest = $dest;
    }
}