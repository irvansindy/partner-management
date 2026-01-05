<?php
// File: app/Models/ApprovalDetails.php
namespace App\Models;

/**
 * @deprecated Use ApprovalProcessStep instead
 */
class ApprovalDetails extends ApprovalProcessStep
{
    protected $table = 'approval_process_steps';

    public function approvalMaster()
    {
        return $this->process();
    }
}