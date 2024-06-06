<?php

namespace gui\charts;

class ViewChart
{
	private mixed $dataset;
	private mixed $datasetKey;
	private mixed $datasetValue;

    /**
     * Constructs a new ViewChart instance.
     *
     * @param array $dataset The dataset to display.
     */
	public function __construct($dataset)
	{
		$this->dataset = $dataset;

		$this->datasetKey = json_encode(array_keys($this->dataset));
		$this->datasetValue = json_encode(array_values($this->dataset));
	}

        /**
        * Gets the dataset.
        *
        * @return mixed The dataset.
        */
	public function getDataset(): mixed
	{
		return $this->dataset;
	}

    /**
     * Gets the dataset key.
     *
     * @return mixed The dataset key.
     */
	public function getDatasetKey(): mixed
	{
		return $this->datasetKey;
	}

    /**
     * Gets the dataset value.
     *
     * @return mixed The dataset value.
     */
	public function getDatasetValue(): mixed
	{
		return $this->datasetValue;
	}
}