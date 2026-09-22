<style>
    :root {
        --form-link-primary: #6C5DD3;
        --form-link-primary-soft: #EEF0FF;
        --form-link-border: #E9E9F1;
        --form-link-text-muted: #8A8CA5;
        --form-link-row-hover: #F7F7FC;
    }

    .form-links-page {
        color: #4A4A5A;
    }

    .form-links-page .page-heading {
        align-items: flex-end;
        display: flex;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .form-links-page .page-heading h2 {
        color: #20202D;
        font-size: 28px;
        font-weight: 600;
        margin: 0 0 6px;
    }

    .form-links-page .page-heading p {
        color: var(--form-link-text-muted);
        margin: 0;
    }

    .form-links-page .page-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .form-links-page .page-actions .btn,
    .form-links-page .card-footer .btn,
    .form-links-page .btn.form-link-action {
        border-radius: 8px;
    }

    .form-links-page .card {
        border: 1px solid var(--form-link-border);
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(20, 20, 43, .04);
        overflow: hidden;
    }

    .form-links-page .card-header {
        align-items: center;
        background: #fff;
        border-bottom: 1px solid var(--form-link-border);
        display: flex;
        justify-content: space-between;
        padding: 20px 24px;
    }

    .form-links-page .card-header .card-title,
    .form-links-page .card-header h3 {
        color: #20202D;
        font-size: 16px;
        font-weight: 600;
        margin: 0;
    }

    .form-links-page .card-body {
        padding: 24px;
    }

    .form-links-page .card-footer {
        background: #fff;
        border-top: 1px solid var(--form-link-border);
        padding: 18px 24px;
    }

    .form-links-page table {
        border-color: var(--form-link-border);
    }

    .form-links-page table thead th {
        background: var(--form-link-primary-soft);
        border-bottom: 2px solid var(--form-link-primary);
        color: var(--form-link-primary);
        font-size: 12px;
        font-weight: 700;
        padding: 14px 16px;
        text-transform: uppercase;
    }

    .form-links-page table tbody td {
        border-color: var(--form-link-border);
        padding: 14px 16px;
        vertical-align: middle;
    }

    .form-links-page table tbody tr:hover td {
        background: var(--form-link-row-hover);
    }

    .form-links-page .form-control,
    .form-links-page .custom-select {
        border-color: var(--form-link-border);
        border-radius: 6px;
    }

    .form-links-page .form-control:focus,
    .form-links-page .custom-select:focus {
        border-color: var(--form-link-primary);
        box-shadow: 0 0 0 3px rgba(108, 93, 211, .12);
    }

    .form-links-page .badge {
        border-radius: 6px;
        font-weight: 600;
        padding: 6px 9px;
    }

    .form-links-page .alert {
        border: 0;
        border-radius: 8px;
    }

    .form-links-page .form-link-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .form-links-page .stat-card {
        border-radius: 10px;
        color: #fff;
        min-height: 112px;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }

    .form-links-page .stat-card h3,
    .form-links-page .stat-card p {
        position: relative;
        z-index: 1;
    }

    .form-links-page .stat-card h3 {
        font-size: 28px;
        margin: 0 0 4px;
    }

    .form-links-page .stat-card p {
        margin: 0;
    }

    .form-links-page .stat-card .material-icons {
        bottom: 12px;
        font-size: 56px;
        opacity: .2;
        position: absolute;
        right: 14px;
    }

    .form-links-page .stat-total { background: #5B8DEF; }
    .form-links-page .stat-approved { background: #31B77A; }
    .form-links-page .stat-progress { background: #E6A23C; }
    .form-links-page .stat-rejected { background: #E05B68; }

    .form-links-page .progress {
        background: #E9E9F1;
        border-radius: 8px;
    }

    .form-links-page .progress-bar {
        border-radius: 8px;
    }

    .form-links-page .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px;
    }

    .form-links-page .qr-box {
        background: #fff;
        border: 1px solid var(--form-link-border);
        border-radius: 8px;
        display: inline-block;
        padding: 12px;
    }

    .form-links-page .action-stack .btn,
    .form-links-page .action-stack form {
        display: block;
        margin-bottom: 10px;
        width: 100%;
    }

    .form-links-page .submission-status {
        min-width: 110px;
    }

    @media (max-width: 991px) {
        .form-links-page .form-link-stats,
        .form-links-page .detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 576px) {
        .form-links-page .page-heading {
            align-items: flex-start;
            flex-direction: column;
        }

        .form-links-page .page-actions {
            justify-content: flex-start;
        }

        .form-links-page .form-link-stats,
        .form-links-page .detail-grid {
            grid-template-columns: 1fr;
        }

        .form-links-page .card-body,
        .form-links-page .card-header,
        .form-links-page .card-footer {
            padding-left: 16px;
            padding-right: 16px;
        }
    }
</style>
