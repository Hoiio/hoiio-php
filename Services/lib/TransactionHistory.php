<?php

/**
 * Represent a list of transaction history. It can contain an array of
 * CallTransaction of SMSTransaction objects.
 */
class TransactionHistory {
    private $resultFrom = NULL;
    private $resultTo = NULL;
    private $page = 1;

    private $totalEntries = 0;
    private $entriesCount = 0;
    private $transactions = NULL;

    /**
     * Start date for the query
     *
     * @return string|NULL  The start date of this query result. Returns NULL if not specified.
     */
    public function getResultFrom() {
        return $this->resultFrom;
    }


    /**
    * End date for the query
    *
    * @return string|NULL   The end date of this query result. Returns NULL if not specified.
    */
    public function getResultTo() {
        return $this->resultTo;
    }

    /**
     * Current page
     *
     * @return int  Current page
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * Total number of entries in the query result
     *
     * @return int  Total number of entries in query result
     */
    public function getTotalEntries() {
        return $this->totalEntries;
    }

    /**
     * Number of entries in this page
     *
     * @return int  Number of entries in this page
     */
    public function getEntriesCount() {
        return $this->entriesCount;
    }

    /**
     * Get the array of SMSTransaction or CallTransaction objects.
     *
     * @return array    Array of SMSTransaction or CallTransaction objects
     */
    public function getTransactions() {
        return $this->transactions;
    }

    public function __construct($totalEntries, $entriesCount, $transactions,
                                    $resultFrom = NULL, $resultTo = NULL, $page = 1) {
        $this->resultFrom = $resultFrom;
        $this->resultTo = $resultTo;
        $this->page = $page;

        $this->totalEntries = $totalEntries;
        $this->entriesCount = $entriesCount;
        $this->transactions = $transactions;
    }
}


