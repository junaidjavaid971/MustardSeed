<?php

class ContractApplications
{
    public $contractApplications;

    public function __construct()
    {
        $this->contractApplications = new ContractApplicationModel();
    }
}

class ContractApplicationModel
{
    public $id;
    public $appliedBy;
    public $contractID;
    public $appliedOn;
    public $invoiceContact;
    public $invoiceEmail;
    public $invoiceTelephone;
    public $invoiceContactRef1;
    public $invoiceContactRef2;
    public $invoiceAddress;
    public $purchaseOrder;
    public $communicationContactName;
    public $communicationContactEmail;
    public $communicationContactTelephone;
    public $attachmentExtension;

    public function __construct()
    {
    }
}
