<?php
// File: app/Models/DetailApprovalModel.php
namespace App\Models;

/**
 * @deprecated Use ApprovalTemplateDetail instead
 */
class DetailApprovalModel extends ApprovalTemplateDetail
{
    protected $table = 'approval_template_details';

    // Override relationship name untuk backward compatibility
    public function masterApprovalModel()
    {
        return $this->template();
    }
}