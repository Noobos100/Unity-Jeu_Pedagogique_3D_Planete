<?php

namespace gui\charts;

class ViewChart
{
	private mixed $dataset;
	private mixed $datasetKey;
	private mixed $datasetValue;

	public function __construct($dataset)
	{
		$this->dataset = $dataset;

		$this->datasetKey = json_encode(array_keys($this->dataset));
		$this->datasetValue = json_encode(array_values($this->dataset));
	}

	public function getDataset(): mixed
	{
		return $this->dataset;
	}

	public function getDatasetKey(): mixed
	{
		return $this->datasetKey;
	}

	public function getDatasetValue(): mixed
	{
		return $this->datasetValue;
	}
}