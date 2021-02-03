<?php


namespace Olsgreen\AdobeSign\Api\Builders;


use Olsgreen\AdobeSign\Api\Enums\SendOptionsStatuses;

class SendOptionsBuilder extends AbstractBuilder implements BuilderInterface
{
    /**
     * Control notification mails for agreement completion
     * events - COMPLETED, CANCELLED, EXPIRED and REJECTED
     *
     * @see \Olsgreen\AdobeSign\Api\Enums\SendOptionsStatuses
     * @var string
     */
    protected $completionEmails;

    /**
     * Control notification mails for agreement-in-process
     * events - DELEGATED, REPLACED
     *
     * @see \Olsgreen\AdobeSign\Api\Enums\SendOptionsStatuses
     * @var string
     */
    protected $inFlightEmails;

    /**
     * Control notification mails for Agreement initiation events
     *
     * @see \Olsgreen\AdobeSign\Api\Enums\SendOptionsStatuses
     * @var string
     */
    protected $initEmails;

    public function setCompletionEmails(string $status)
    {
        $statuses = new SendOptionsStatuses();

        if (!$statuses->contains($status)) {
            throw new \Exception(
                sprintf('\'%s\' is an invalid completion email status.', $status)
            );
        }

        $this->completionEmails = $status;

        return $this;
    }

    public function getCompletionEmails()
    {
        return $this->completionEmails;
    }

    public function setInitEmails(string $status)
    {
        $statuses = new SendOptionsStatuses();

        if (!$statuses->contains($status)) {
            throw new \Exception(
                sprintf('\'%s\' is an invalid init email status.', $status)
            );
        }

        $this->initEmails = $status;

        return $this;
    }

    public function getInitEmails()
    {
        return $this->initEmails;
    }

    public function setInFlightEmails(string $status)
    {
        $statuses = new SendOptionsStatuses();

        if (!$statuses->contains($status)) {
            throw new \Exception(
                sprintf('\'%s\' is an invalid in-flight email status.', $status)
            );
        }

        $this->inFlightEmails = $status;

        return $this;
    }

    public function getInFlightEmails()
    {
        return $this->inFlightEmails;
    }

    public function validate()
    {
        return true;
    }

    public function make(): array
    {
        return $this->filterMakeOutput([
            'initEmails' => $this->initEmails,
            'inFlightEmails' => $this->inFlightEmails,
            'completionEmails' => $this->completionEmails,
        ]);
    }
}