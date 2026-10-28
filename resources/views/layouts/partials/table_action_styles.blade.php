<style>
    .table-actions,
    table.dataTable tbody td .btn-group,
    .data_tables tbody td .btn-group {
        display: inline-flex !important;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        vertical-align: middle;
    }

    .table-actions form,
    table.dataTable tbody td .btn-group form,
    .data_tables tbody td .btn-group form {
        display: inline-flex;
        margin: 0;
    }

    .table-actions .table-action,
    table.dataTable tbody td .btn,
    .data_tables tbody td .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-width: 44px;
        min-height: 44px;
        margin: 0 !important;
        padding: 8px 12px;
        border-width: 1px;
        border-radius: 8px !important;
        box-shadow: none !important;
        font-weight: 600;
        line-height: 1.15;
        text-align: center;
        white-space: nowrap;
        transition: background-color 150ms ease, border-color 150ms ease, color 150ms ease;
    }

    .table-actions .table-action:hover,
    table.dataTable tbody td .btn:hover,
    .data_tables tbody td .btn:hover {
        text-decoration: none;
    }

    .table-actions .table-action:focus-visible,
    table.dataTable tbody td .btn:focus-visible,
    .data_tables tbody td .btn:focus-visible {
        outline: 3px solid rgba(101, 78, 220, 0.35);
        outline-offset: 2px;
    }

    .table-actions .table-action:disabled,
    .table-actions .table-action.disabled,
    table.dataTable tbody td .btn:disabled,
    table.dataTable tbody td .btn.disabled,
    .data_tables tbody td .btn:disabled,
    .data_tables tbody td .btn.disabled {
        cursor: not-allowed;
        opacity: 0.55;
    }

    .table-actions .table-action--view,
    table.dataTable tbody td .btn-info,
    table.dataTable tbody td .btn-outline-info,
    .data_tables tbody td .btn-info,
    .data_tables tbody td .btn-outline-info {
        color: #ffffff !important;
        background: #2563eb !important;
        border-color: #2563eb !important;
    }

    .table-actions .table-action--view:hover,
    table.dataTable tbody td .btn-info:hover,
    table.dataTable tbody td .btn-outline-info:hover,
    .data_tables tbody td .btn-info:hover,
    .data_tables tbody td .btn-outline-info:hover {
        color: #ffffff !important;
        background: #1d4ed8 !important;
        border-color: #1d4ed8 !important;
    }

    .table-actions .table-action--edit,
    table.dataTable tbody td .btn-warning,
    table.dataTable tbody td .btn-outline-warning,
    .data_tables tbody td .btn-warning,
    .data_tables tbody td .btn-outline-warning {
        color: #3b2600 !important;
        background: #f59e0b !important;
        border-color: #f59e0b !important;
    }

    .table-actions .table-action--edit:hover,
    table.dataTable tbody td .btn-warning:hover,
    table.dataTable tbody td .btn-outline-warning:hover,
    .data_tables tbody td .btn-warning:hover,
    .data_tables tbody td .btn-outline-warning:hover {
        color: #2d1c00 !important;
        background: #d98506 !important;
        border-color: #d98506 !important;
    }

    .table-actions .table-action--toggle,
    table.dataTable tbody td .btn-success,
    table.dataTable tbody td .btn-outline-success,
    .data_tables tbody td .btn-success,
    .data_tables tbody td .btn-outline-success {
        color: #ffffff !important;
        background: #15925f !important;
        border-color: #15925f !important;
    }

    .table-actions .table-action--toggle:hover,
    table.dataTable tbody td .btn-success:hover,
    table.dataTable tbody td .btn-outline-success:hover,
    .data_tables tbody td .btn-success:hover,
    .data_tables tbody td .btn-outline-success:hover {
        color: #ffffff !important;
        background: #0f754b !important;
        border-color: #0f754b !important;
    }

    .table-actions .table-action--toggle.is-inactive,
    .table-actions .table-action--copy,
    table.dataTable tbody td .btn-secondary,
    table.dataTable tbody td .btn-outline-secondary,
    .data_tables tbody td .btn-secondary,
    .data_tables tbody td .btn-outline-secondary {
        color: #ffffff !important;
        background: #475569 !important;
        border-color: #475569 !important;
    }

    .table-actions .table-action--toggle.is-inactive:hover,
    .table-actions .table-action--copy:hover,
    table.dataTable tbody td .btn-secondary:hover,
    table.dataTable tbody td .btn-outline-secondary:hover,
    .data_tables tbody td .btn-secondary:hover,
    .data_tables tbody td .btn-outline-secondary:hover {
        color: #ffffff !important;
        background: #334155 !important;
        border-color: #334155 !important;
    }

    .table-actions .table-action--delete,
    table.dataTable tbody td .btn-danger,
    table.dataTable tbody td .btn-outline-danger,
    .data_tables tbody td .btn-danger,
    .data_tables tbody td .btn-outline-danger {
        color: #ffffff !important;
        background: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .table-actions .table-action--delete:hover,
    table.dataTable tbody td .btn-danger:hover,
    table.dataTable tbody td .btn-outline-danger:hover,
    .data_tables tbody td .btn-danger:hover,
    .data_tables tbody td .btn-outline-danger:hover {
        color: #ffffff !important;
        background: #b42331 !important;
        border-color: #b42331 !important;
    }

    @media (max-width: 767.98px) {
        .table-actions,
        table.dataTable tbody td .btn-group,
        .data_tables tbody td .btn-group {
            max-width: 100%;
        }
    }
</style>
