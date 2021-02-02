<?php


namespace Olsgreen\AdobeSign\Api\Builders;


class AgreementCancellationInfoBuilder extends AbstractBuilder implements BuilderInterface
{
    protected $comment;

    protected $notifyOthers;

    public function setComment(string $comment)
    {
        $this->comment = $comment;

        return $this;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setNotifyOthers(bool $notify)
    {
        $this->notifyOthers = $notify;

        return $this;
    }

    public function getNotifyOthers()
    {
        return $this->notifyOthers;
    }

    public function validate()
    {
        return true;
    }

    public function make(): array
    {
        return $this->filterMakeOutput([
           'comment' => $this->comment,
           'notifyOthers' => $this->notifyOthers,
        ]);
    }
}