<?php

class SMSHistory {
	private $resultFrom = NULL;
	private $resultTo = NULL;
	private $page = 1;
		
	private $totalEntries = 0;
	private $entriesCount = 0;
	private $transactions = NULL;

	/*
	* Start date for the query
	*
	* @return	int|NULL
	*/
	public function getResultFrom() {
		return $this->resultFrom;
	}
	
	/*
	* End date of the query
	*
	* @return	int|NULL
	*/
	public function getResultTo() {
		return $this->resultTo;
	}

	/*
	* Current page
	*
	* @return	int
	*/
	public function getPage() {
		return $this->page;
	}
		
	/*
	* Total number of entries in the query result
	* 
	* @return	int
	*/
	public function getTotalEntries() {
		return $this->totalEntries;
	}
	
	/*
	* Number of entries in this result
	* 
	* @return	int
	*/
	public function getEntriesCount() {
		return $this->entriesCount;
	}
	
	/*
	* Get the array of SMSTransaction objects.
	* 
	* @return	array	Array of SMSTransaction objects
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