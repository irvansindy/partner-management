<?php
// File: app/Models/ApprovalMaster.php
namespace App\Models;

/**
 * @deprecated Use ApprovalProcess instead
 */
class ApprovalMaster extends ApprovalProcess
{
    protected $table = 'approval_processes';

    public function approval()
    {
        return $this->steps();
    }
}