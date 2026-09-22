<style>
    :root {
        --company-primary: #6C5DD3;
        --company-primary-soft: #EEF0FF;
        --company-border: #E9E9F1;
        --company-text-muted: #8A8CA5;
        --company-row-hover: #F7F7FC;
    }

    .company-tools-page {
        color: #4A4A5A;
    }

    .company-tools-page .page-heading {
        align-items: flex-end;
        display: flex;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .company-tools-page .page-heading h2 {
        color: #20202D;
        font-size: 28px;
        font-weight: 600;
        margin: 0 0 6px;
    }

    .company-tools-page .page-heading p {
        color: var(--company-text-muted);
        margin: 0;
    }

    .company-tools-page .card {
        border: 1px solid var(--company-border);
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(20, 20, 43, .04);
        overflow: hidden;
    }

    .company-tools-page .card-header {
        align-items: center;
        background: #fff;
        border-bottom: 1px solid var(--company-border);
        display: flex;
        justify-content: space-between;
        padding: 20px 24px;
    }

    .company-tools-page .card-header .card-title,
    .company-tools-page .card-header h3,
    .company-tools-page .card-header h5 {
        color: #20202D;
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }

    .company-tools-page .card-body {
        padding: 24px;
    }

    .company-tools-page .card-footer {
        background: #fff;
        border-top: 1px solid var(--company-border);
        padding: 18px 24px;
    }

    .company-tools-page .form-control,
    .company-tools-page .custom-file-label {
        border-color: var(--company-border);
        border-radius: 6px;
    }

    .company-tools-page .form-control:focus {
        border-color: var(--company-primary);
        box-shadow: 0 0 0 3px rgba(108, 93, 211, .12);
    }

    .company-tools-page .btn {
        border-radius: 8px;
    }

    .company-tools-page .field-group {
        height: 100%;
        border: 1px solid var(--company-border);
        border-radius: 10px;
        box-shadow: none;
    }

    .company-tools-page .field-group .card-header {
        background: var(--company-primary-soft);
        padding: 16px 18px;
    }

    .company-tools-page .field-group .card-header .card-title {
        color: var(--company-primary);
    }

    .company-tools-page .field-group .card-body {
        max-height: 400px;
        overflow-y: auto;
        padding: 18px;
    }

    .company-tools-page .form-check {
        margin-bottom: 10px;
        padding-left: 1.5rem;
    }

    .company-tools-page .form-check-label {
        cursor: pointer;
        user-select: none;
    }

    .company-tools-page .selection-toolbar {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .company-tools-page .selection-count {
        background: var(--company-primary-soft);
        border-radius: 6px;
        color: var(--company-primary);
        padding: 7px 10px;
    }

    .company-tools-page table {
        border-color: var(--company-border);
    }

    .company-tools-page table thead th {
        background: var(--company-primary-soft);
        border-bottom: 2px solid var(--company-primary);
        color: var(--company-primary);
        font-size: 12px;
        font-weight: 700;
        padding: 14px 16px;
        text-transform: uppercase;
    }

    .company-tools-page table tbody td {
        border-color: var(--company-border);
        padding: 12px 16px;
        vertical-align: middle;
    }

    .company-tools-page table tbody tr:hover td {
        background: var(--company-row-hover);
    }

    .company-tools-page .upload-actions {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .company-tools-page .upload-actions .btn {
        min-height: 52px;
    }

    .company-tools-page .instruction-list {
        line-height: 1.8;
    }

    @media (max-width: 767px) {
        .company-tools-page .page-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .company-tools-page .upload-actions {
            grid-template-columns: 1fr;
        }

        .company-tools-page .card-body,
        .company-tools-page .card-header,
        .company-tools-page .card-footer {
            padding-left: 16px;
            padding-right: 16px;
        }
    }
</style>
