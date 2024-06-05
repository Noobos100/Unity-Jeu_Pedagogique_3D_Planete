<?php

namespace service;

/**
 * Represents an exception thrown when an action cannot be executed.
 */
class CannotDoException extends \Exception
{
    private string $default_msg = 'An action cannot be executed.';
    private string $target;
    private string $action;
    private string $explanation;

    /**
     * Constructs a new CannotDoException instance.
     *
     * @param string $target The target of the action.
     * @param string $action The action that cannot be executed.
     * @param string $explanation The explanation for why the action cannot be executed.
     * @param int $code The exception code.
     * @param \Throwable|null $previous The previous throwable used for the exception chaining.
     */
    public function __construct(string $target = '', string $action = '', string $explanation = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($this->default_msg, $code, $previous);
        $this->target = $target;
        $this->action = $action;
        $this->explanation = $explanation;
    }

    /**
     * Get the detailed report of the exception.
     *
     * @return string The detailed report.
     */
    public function getReport(): string
    {
        $report = $this->default_msg . '\n';
        $report .= 'Target: ' . $this->target . '\n';
        $report .= 'Action concerned: ' . $this->action . '\n';
        $report .= 'Cause: ' . $this->explanation;
        return $report;
    }
}
