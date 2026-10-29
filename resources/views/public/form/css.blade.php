    <style>
        #wizard_stepper {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            padding: 1rem 0;
        }

        #wizard_stepper::before {
            content: '';
            position: absolute;
            top: calc(1rem + 21px);
            left: calc(0.5rem + 21px);
            right: calc(0.5rem + 21px);
            height: 2px;
            background: #dee2e6;
            z-index: 1;
        }

        .wizard-step {
            position: relative;
            z-index: 2;
            flex: 1 1 0;
            min-width: 120px;
            max-width: 170px;
            padding: 0.25rem 0.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            color: #6c757d;
            transition: transform 0.2s ease, color 0.2s ease;
            white-space: nowrap;
        }

        .wizard-step:hover {
            transform: translateY(-2px);
        }

        .wizard-step-number {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f1f3f5;
            border: 2px solid #dee2e6;
            margin-bottom: 0.5rem;
            font-weight: 700;
            color: #495057;
            transition: all 0.25s ease;
        }

        .wizard-step.active .wizard-step-number,
        .wizard-step.completed .wizard-step-number {
            background: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .wizard-step.active,
        .wizard-step.completed {
            color: #212529;
        }

        .wizard-step-text {
            font-size: 0.9rem;
            line-height: 1.2;
            max-width: 120px;
            word-break: break-word;
        }

        .step-pane {
            display: none;
        }

        .step-pane.active {
            display: block;
        }

        .wizard-actions button {
            min-width: 130px;
        }

        @media (max-width: 991px) {
            #wizard_stepper {
                overflow-x: auto;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            #wizard_stepper::before {
                left: 2rem;
                right: 2rem;
            }

            .wizard-step {
                min-width: 140px;
            }
        }

        @media (max-width: 576px) {
            #wizard_stepper {
                gap: 0.75rem;
            }

            .wizard-step {
                min-width: 120px;
            }

            .wizard-step-text {
                font-size: 0.8rem;
            }
        }

        #survey_form_container {
            transition: all 0.3s ease-in-out;
        }

        .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        .form-check-input:not(:checked) {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            cursor: pointer;
        }

        .form-switch .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }

        .select2-selection.is-invalid {
            border-color: #dc3545 !important;
        }

        #form_company {
            --partner-border: #d9e1ea;
            --partner-muted: #667085;
        }

        #form_company .card {
            border: 1px solid var(--partner-border);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        }

        #form_company .card-header {
            border-bottom-color: var(--partner-border);
        }

        #form_company .card-title,
        #form_company h2,
        #form_company h3 {
            line-height: 1.25;
        }

        #form_company .partner-form-section {
            line-height: 1.35;
            margin-left: 0;
            margin-right: 0;
            width: 100%;
        }

        #form_company .partner-fieldset,
        #form_company fieldset.border {
            width: 100%;
            min-width: 0;
            border: 1px solid var(--partner-border) !important;
            border-radius: 8px;
            padding: 1.25rem !important;
            margin-bottom: 1.5rem;
            background: #fff;
        }

        #form_company legend {
            margin: 0;
            padding: 0 0.5rem;
            font-size: 1rem;
            line-height: 1.25;
        }

        #form_company .form-group {
            position: relative;
            margin-bottom: 1.15rem;
        }

        #form_company label {
            display: block;
            margin-bottom: 0.45rem;
            line-height: 1.3;
            font-weight: 600;
            color: #374151;
            transition: color 0.2s ease;
        }

        #form_company label[for].active,
        #form_company label[for]:focus-visible {
            color: var(--public-primary);
        }

        #form_company .form-control,
        #form_company .form-select,
        #form_company .select2-container--default .select2-selection--single {
            min-height: 42px;
            border-radius: 6px;
        }

        #form_company .select2-container {
            width: 100% !important;
            max-width: 100%;
        }

        #form_company .select2-container--default .select2-selection--single {
            display: flex;
            align-items: center;
            border-color: #ced4da;
        }

        #form_company .select2-container--default .select2-selection--single .select2-selection__rendered {
            width: 100%;
            padding-left: 0.75rem;
            padding-right: 2rem;
            line-height: 40px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #form_company .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        #form_company .text-muted,
        #form_company small {
            color: var(--partner-muted) !important;
            line-height: 1.35;
        }

        #form_company .partner-map-shell {
            position: relative;
            min-height: 400px;
        }

        #form_company .partner-map {
            width: 100%;
            height: 400px;
            min-height: 400px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.10);
        }

        #form_company .partner-map-fullscreen {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 40px;
            padding: 8px 12px;
            border: 1px solid #4b5563;
            border-radius: 6px;
            background: #ffffff;
            color: #252b3b;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .18);
        }

        #form_company .partner-map-fullscreen:hover {
            background: #f3f4f6;
        }

        #form_company .partner-map-fullscreen:focus-visible {
            outline: 3px solid #f4b740;
            outline-offset: 2px;
        }

        #form_company .partner-map-shell:fullscreen,
        #form_company .partner-map-shell.is-map-fullscreen {
            width: 100vw;
            height: 100vh;
            min-height: 100vh;
            padding: 12px;
            background: #ffffff;
        }

        #form_company .partner-map-shell.is-map-fullscreen {
            position: fixed;
            inset: 0;
            z-index: 2050;
        }

        #form_company .partner-map-shell:fullscreen .partner-map,
        #form_company .partner-map-shell.is-map-fullscreen .partner-map {
            height: 100%;
            min-height: 100%;
            border-radius: 0;
        }

        body.map-fullscreen-open {
            overflow: hidden;
        }

        #form_company .input-group {
            flex-wrap: nowrap;
        }

        #form_company .iti {
            display: block;
            width: 100%;
        }

        #form_company .iti .international-phone {
            min-height: 44px;
            width: 100%;
        }

        #form_company .iti--separate-dial-code input.international-phone {
            padding-left: 104px !important;
        }

        #form_company .iti--separate-dial-code input.international-phone::placeholder {
            color: #8a94a4;
            opacity: 1;
        }

        #form_company .iti--separate-dial-code .iti__selected-flag {
            background: #f8fafc;
            border-right: 1px solid #d8dee8;
            min-width: 88px;
            padding-left: 12px;
            padding-right: 10px;
        }

        #form_company .iti__selected-dial-code {
            color: var(--partner-ink);
            font-variant-numeric: tabular-nums;
            margin-left: 8px;
        }

        #form_company .iti__country-list {
            border-color: #d8dee8;
            border-radius: 6px;
            box-shadow: 0 8px 22px rgba(15, 23, 42, .14);
            max-width: min(420px, calc(100vw - 32px));
            white-space: normal;
            z-index: 1080;
        }

        #form_company .country-option {
            align-items: center;
            display: inline-flex;
            gap: 10px;
            min-width: 0;
        }

        #form_company .country-option .iti__flag {
            flex: 0 0 auto;
        }

        #form_company .country-option__label {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #form_company .contact-phone-column,
        #form_company .iti {
            min-width: 0;
        }

        #form_company .contact-action-column .btn {
            min-width: 48px;
            min-height: 40px;
        }

        #form_company .btn {
            border-radius: 6px;
            white-space: nowrap;
        }

        #wizard_actions {
            gap: 1rem;
        }

        #wizard_actions > div {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        @media (max-width: 991px) {
            .card-body {
                padding: 1rem;
            }

            #form_company .partner-fieldset,
            #form_company fieldset.border {
                padding: 1rem !important;
            }
        }

        @media (max-width: 576px) {
            #form_company .international-phone-group > .select2-container {
                flex-basis: 88px;
                width: 88px !important;
                min-width: 88px;
            }

            #form_company .card-header h3 {
                font-size: 1.35rem;
            }

            #form_company .card-title {
                font-size: 1.1rem;
            }

            #form_company h2 {
                font-size: 1.25rem;
            }

            #form_company .partner-fieldset,
            #form_company fieldset.border {
                padding: 0.85rem !important;
            }

            #form_company .input-group {
                flex-wrap: wrap;
            }

            #form_company .input-group > .form-control,
            #form_company .input-group > .btn {
                min-width: 0;
            }

            #form_company .input-group > .btn {
                flex: 0 0 auto;
            }

            #wizard_actions {
                align-items: stretch !important;
                flex-direction: column;
            }

            #wizard_actions .btn {
                width: 100%;
            }

            #wizard_actions > div {
                width: 100%;
                flex-direction: column;
            }
        }

        .public-form-page {
            animation: public-form-enter .35s ease-out both;
        }

        @keyframes public-form-enter {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .public-form-heading {
            align-items: flex-end;
            display: flex;
            justify-content: space-between;
            gap: 24px;
            margin: 0 0 22px;
        }

        .public-eyebrow,
        .wizard-kicker {
            color: var(--public-primary);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
        }

        .public-form-heading h1 {
            color: var(--public-ink);
            font-size: 30px;
            font-weight: 700;
            line-height: 1.2;
            margin: 8px 0;
            overflow-wrap: anywhere;
        }

        .public-form-heading p {
            color: var(--public-muted);
            margin: 0;
        }

        .public-form-type {
            background: #e8f4ef;
            border-radius: 999px;
            color: var(--public-success);
            flex: 0 0 auto;
            font-size: 12px;
            font-weight: 700;
            padding: 9px 13px;
        }

        .public-form-description {
            align-items: flex-start;
            background: #fff;
            border: 1px solid var(--public-line);
            border-left: 3px solid var(--public-primary);
            border-radius: 8px;
            color: var(--public-muted);
            display: flex;
            gap: 11px;
            line-height: 1.55;
            margin-bottom: 20px;
            padding: 14px 17px;
        }

        .public-form-description i {
            color: var(--public-primary);
            margin-top: 3px;
        }

        .wizard-shell {
            background: #fff;
            border: 1px solid var(--public-line);
            border-radius: 10px;
            box-shadow: 0 4px 18px rgba(32, 38, 56, .045);
            margin-bottom: 22px;
            padding: 19px 24px 10px;
        }

        .wizard-topline {
            align-items: center;
            border-bottom: 1px solid #edf0f4;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 15px;
        }

        .wizard-topline > div {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .wizard-current-label {
            color: var(--public-ink);
            font-size: 14px;
            font-weight: 700;
        }

        .wizard-count {
            color: var(--public-muted);
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .wizard-count span {
            color: var(--public-primary);
            font-size: 15px;
        }

        #wizard_stepper {
            gap: 8px;
            margin: 0;
            overflow: visible;
            padding: 17px 0 6px;
        }

        #wizard_stepper::before {
            background: #e4e8ef;
            height: 2px;
            left: 8%;
            right: 8%;
            top: 35px;
        }

        .public-form-page .wizard-step {
            color: #8991a1;
            min-width: 0;
            max-width: none;
            padding: 0 4px;
            white-space: normal;
        }

        .public-form-page .wizard-step:hover {
            color: var(--public-primary);
            transform: translateY(-1px);
        }

        .public-form-page .wizard-step-number {
            background: #fff;
            border: 2px solid #dbe1e9;
            color: #778195;
            height: 38px;
            margin-bottom: 8px;
            width: 38px;
        }

        .public-form-page .wizard-step.active .wizard-step-number,
        .public-form-page .wizard-step.completed .wizard-step-number {
            background: var(--public-primary);
            border-color: var(--public-primary);
            color: #fff;
        }

        .public-form-page .wizard-step.active,
        .public-form-page .wizard-step.completed {
            color: var(--public-ink);
        }

        .public-form-page .wizard-step-text {
            font-size: 12px;
            font-weight: 600;
            line-height: 1.25;
            max-width: 130px;
        }

        #form_company .card {
            background: #fff;
            border: 1px solid var(--public-line);
            border-radius: 10px;
            box-shadow: 0 3px 14px rgba(32, 38, 56, .035);
            margin-bottom: 20px;
            overflow: hidden;
        }

        #form_company .card-header {
            background: #fff;
            border-bottom: 1px solid #edf0f4;
            color: var(--public-ink);
            padding: 17px 24px;
        }

        #form_company .card-title,
        #form_company .card-header h3,
        #form_company .card-header h5 {
            color: var(--public-ink);
            font-size: 17px;
            font-weight: 700;
            margin: 0;
        }

        #form_company .card-header .fas,
        #form_company .card-header .fa {
            color: var(--public-primary);
            margin-right: 7px;
        }

        #form_company .card-body {
            padding: 25px;
        }

        #form_company .form-control,
        #form_company .form-select,
        #form_company .select2-container--default .select2-selection--single {
            background-color: #fbfcfe;
            border: 1px solid #dce2eb;
            color: var(--public-ink);
            min-height: 44px;
            transition: border-color .15s ease, box-shadow .15s ease, background-color .15s ease;
        }

        #form_company textarea.form-control {
            min-height: 92px;
        }

        #form_company .form-control:focus,
        #form_company .form-select:focus {
            background: #fff;
            border-color: var(--public-primary);
            box-shadow: 0 0 0 3px rgba(101, 88, 211, .12);
        }

        #form_company label {
            color: #3b4352;
            font-size: 13px;
            font-weight: 600;
        }

        #form_company .partner-fieldset,
        #form_company fieldset.border {
            background: #fcfdff;
            border-color: var(--public-line) !important;
            border-radius: 9px;
        }

        #form_company legend {
            background: #fff;
            color: var(--public-ink);
            font-weight: 700;
        }

        #form_company .text-muted,
        #form_company small {
            color: var(--public-muted) !important;
        }

        #form_company .btn {
            border-radius: 7px;
            font-weight: 600;
            min-height: 42px;
            padding: 9px 16px;
        }

        #form_company .btn-primary {
            background: var(--public-primary);
            border-color: var(--public-primary);
        }

        #form_company .btn-primary:hover {
            background: var(--public-primary-dark);
            border-color: var(--public-primary-dark);
        }

        #form_company .btn-success {
            background: var(--public-success);
            border-color: var(--public-success);
        }

        #form_company .form-check-input:checked {
            background-color: var(--public-primary);
            border-color: var(--public-primary);
        }

        #form_company .form-check-input:not(:checked) {
            background-color: #fff;
            border-color: #b8c1ce;
        }

        #form_company .address-sync-control {
            align-items: center;
            background: #f4f1ff;
            border: 1px solid #ddd7ff;
            border-radius: 8px;
            color: #5947d6;
            cursor: pointer;
            display: inline-flex;
            gap: 10px;
            margin: 0;
            min-height: 44px;
            padding: 10px 13px;
        }

        #form_company .address-sync-control:hover {
            background: #ece8ff;
            border-color: #c8c0ff;
            color: #4736c3;
        }

        #form_company .address-sync-control:focus-within {
            outline: 3px solid rgba(101, 88, 211, 0.22);
            outline-offset: 2px;
        }

        #form_company .address-sync-control .form-check-input {
            flex: 0 0 20px;
            float: none;
            height: 20px;
            margin: 0;
            position: static;
            width: 20px;
        }

        #form_company .address-sync-control__text {
            color: inherit;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.35;
        }

        #wizard_actions {
            border-top: 1px solid var(--public-line);
            margin-top: 24px;
            padding-top: 20px;
        }

        #wizard_actions .btn {
            min-width: 140px;
        }

        #form_company .partner-map {
            border: 1px solid var(--public-line);
            box-shadow: 0 4px 14px rgba(32, 38, 56, .08);
        }

        @media (max-width: 767px) {
            .public-form-heading {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            .public-form-heading h1 {
                font-size: 25px;
            }

            .wizard-shell {
                padding: 16px 15px 8px;
            }

            #wizard_stepper {
                justify-content: flex-start;
                overflow-x: auto;
                padding-bottom: 10px;
                scrollbar-width: thin;
            }

            #wizard_stepper::before {
                left: 22px;
                right: auto;
                width: calc(6 * 112px - 44px);
            }

            .public-form-page .wizard-step {
                flex: 0 0 112px;
            }

            #form_company .card-body {
                padding: 18px 16px;
            }

            #form_company .card-header {
                padding: 15px 16px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .public-form-page {
                animation: none;
            }
        }

        @media (max-width: 576px) {
            #form_company .iti__country-list {
                width: calc(100vw - 48px);
            }

            .wizard-current-label {
                max-width: 230px;
                overflow-wrap: anywhere;
            }

            #wizard_actions {
                align-items: stretch !important;
                gap: 10px;
            }

            #wizard_actions > button,
            #wizard_actions > div,
            #wizard_actions > div .btn {
                width: 100%;
            }
        }
    </style>
