    <script>
        const formToken = "{{ $formLink->token ?? '' }}";
        const STORAGE_KEY = "partner_form_" + formToken;

        /* Escape untuk name selector dengan bracket */
        function escapeName(name) {
            return name.replace(/([\[\]])/g, "\\$1");
        }

        /* Generate row dinamis jika belum ada */
        function ensureRowExists(baseName, index) {
            const container = $('[data-repeat="' + baseName + '"]');
            if (!container.length) {
                let addButton = null;

                if (baseName === 'address') {
                    addButton = $('#add_dynamic_address');
                } else if (baseName === 'bank_name' || baseName === 'account_name' || baseName === 'account_number') {
                    addButton = $('#add_bank');
                } else if (baseName.includes('contact_')) {
                    addButton = $('#add_contact');
                } else if (baseName === 'liable_person' || baseName === 'liable_position' || baseName === 'nik') {
                    addButton = $('#add_liable_person');
                } else if (baseName.includes('survey')) {
                    addButton = $('#add_survey_data');
                }

                if (!addButton || !addButton.length) return;

                let selector = `[name="${escapeName(baseName + '[]')}"]`;
                let currentCount = $(selector).length;

                while (currentCount <= index) {
                    addButton.trigger("click");
                    currentCount++;
                }
                return;
            }

            let rows = container.find(".repeat-row");
            let currentCount = rows.length;

            while (currentCount <= index) {
                container.find(".btn-add-row").trigger("click");
                currentCount++;
            }
        }

        /* SAVE FORM - EXCLUDE SURVEY FIELDS */
        function saveForm() {
            let data = {};

            $("#form_company")
                .find("input, textarea, select")
                .each(function() {
                    if (!this.name) return;
                    if (this.type === "file") return;
                    if (this.name === "company_type") return;

                    // ❌ SKIP survey fields - akan disimpan terpisah
                    if (this.name.includes('survey') ||
                        this.name.includes('ownership_status') ||
                        this.name.includes('product_survey') ||
                        this.name.includes('merk_survey') ||
                        this.name.includes('distributor_survey')) {
                        return;
                    }

                    let fieldName = this.name;
                    let fieldValue = null;

                    if (this.type === "checkbox") {
                        fieldValue = $(this).is(":checked");
                    } else if (this.type === "radio") {
                        if (!$(this).is(":checked")) return;
                        fieldValue = $(this).val();
                    } else {
                        fieldValue = $(this).val();
                    }

                    if (fieldName.includes('[]')) {
                        let cleanName = fieldName.replace('[]', '');
                        if (!data[cleanName]) {
                            data[cleanName] = [];
                        }
                        data[cleanName].push(fieldValue);
                    } else {
                        data[fieldName] = fieldValue;
                    }
                });

            data['survey_form_switch'] = $('#survey_form_switch').is(':checked');

            localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
            console.log("📝 Form saved (excluding survey):", data);
        }

        /* RESTORE FORM - EXCLUDE SURVEY FIELDS */
        function restoreForm() {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (!saved) return;

            let data = JSON.parse(saved);
            console.log("🔄 Restoring form:", data);

            for (let key in data) {
                const value = data[key];

                if (key === "company_type") continue;

                // Handle survey switch
                if (key === "survey_form_switch") {
                    $('#survey_form_switch').prop('checked', value);

                    if (value) {
                        $('#survey_form_container').show();
                        $('#switch_label').text('Hide Survey');
                    } else {
                        $('#survey_form_container').empty().hide();
                        $('#switch_label').text('Show Survey');
                    }
                    continue;
                }

                if (Array.isArray(value)) {
                    value.forEach((val, index) => {
                        let selector = `[name="${escapeName(key + '[]')}"]`;
                        let $els = $(selector);

                        if (index > 0) {
                            ensureRowExists(key, index);
                            $els = $(selector);
                        }

                        if ($els.length > index) {
                            let $el = $els.eq(index);
                            const type = $el.attr("type");

                            if (key === "city") {
                                $el.attr('data-pending-city-restore', val);
                                return;
                            }

                            if (type === "checkbox") {
                                $el.prop("checked", !!val).trigger("change");
                            } else if (type === "radio") {
                                if ($el.val() == val) {
                                    $el.prop("checked", true).trigger("change");
                                }
                            } else {
                                $el.val(val).trigger("change");
                            }

                            if ($el.hasClass("select2-hidden-accessible")) {
                                $el.trigger("change.select2");
                            }
                        }
                    });
                } else {
                    let escaped = escapeName(key);
                    let $els = $(`[name="${escaped}"]`);

                    if ($els.length === 0) continue;

                    $els.each(function() {
                        const $el = $(this);
                        const type = $el.attr("type");

                        if (type === "checkbox") {
                            $el.prop("checked", !!value).trigger("change");
                        } else if (type === "radio") {
                            if ($el.val() == value) {
                                $el.prop("checked", true).trigger("change");
                            }
                        } else {
                            $el.val(value).trigger("change");
                        }

                        if ($el.hasClass("select2-hidden-accessible")) {
                            $el.trigger("change.select2");
                        }
                    });
                }
            }
        }

        // Event listener untuk auto-save (EXCLUDE survey fields)
        $(document).on("input change", "#form_company input, #form_company textarea, #form_company select", function() {
            // Skip jika field dari survey
            if ($(this).closest('#survey_form_container').length > 0) {
                return;
            }
            saveForm();
        });

        $(document).on("change", "#survey_form_switch", function() {
            saveForm();
        });

        function syncPublicFieldState($field) {
            const field = $field.get(0);
            if (!field) return;

            const selector = field.id ? 'label[for="' + field.id + '"]' : null;
            let label = selector ? document.querySelector('#form_company ' + selector) : null;

            if (!label && field.name) {
                label = document.querySelector('#form_company label[for="' + field.name.replace(/\[|\]/g, '').replace(/[^a-zA-Z0-9_-]/g, '_') + '"]');
            }

            if (!label) {
                const group = $field.closest('.form-group, .row, .col-md-6, .col-md-4, .col-lg-6, .col-lg-4');
                if (group.length) {
                    label = group.find('label[for]').first().get(0);
                }
            }

            if (!label) return;

            const hasValue = field.value !== '' || document.activeElement === field;
            label.classList.toggle('active', hasValue);
            if (field.classList && field.classList.contains('select2-hidden-accessible')) {
                label.classList.toggle('active', true);
            }
        }

        $('#form_company').on('focusin', 'input, textarea, select', function () {
            syncPublicFieldState($(this));
        });

        $('#form_company').on('focusout', 'input, textarea, select', function () {
            syncPublicFieldState($(this));
        });

        $('#form_company').on('input change', 'input, textarea, select', function () {
            syncPublicFieldState($(this));
        });

        $('#form_company').find('input, textarea, select').each(function () {
            syncPublicFieldState($(this));
        });

        $('#form_company').on('select2:open select2:close', 'select', function () {
            syncPublicFieldState($(this));
        });

        /* Clear setelah submit */
        function clearSavedForm() {
            localStorage.removeItem(STORAGE_KEY);
            localStorage.removeItem(STORAGE_KEY + '_survey');
            console.log("✅ Form data cleared from localStorage");
        }

        // ============================================
        // DYNAMIC SURVEY TOGGLE - REMOVE & APPEND
        // ============================================
        $(document).ready(function() {
            // Simpan HTML survey form saat pertama kali load
            const surveyFormHTML = $('#survey_form_container').html();
            let isSurveyVisible = $('#survey_form_switch').is(':checked');

            // Jika awal tidak checked, langsung hapus form
            if (!isSurveyVisible) {
                $('#survey_form_container').empty();
            }

            // Handler untuk switch button
            $('#survey_form_switch').on('change', function() {
                const isChecked = $(this).is(':checked');

                if (isChecked) {
                    // SHOW: Append kembali survey form
                    $('#survey_form_container').html(surveyFormHTML).hide().slideDown(300);
                    $('#switch_label').text('Hide Survey');

                    // Restore data survey dari localStorage jika ada
                    restoreSurveyData();

                    console.log('✅ Survey form added to DOM');
                } else {
                    // HIDE: Konfirmasi dan remove survey form
                    Swal.fire({
                        title: 'Hide Survey?',
                        text: "Survey data will not be submitted to the server",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, hide it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Simpan data survey sebelum dihapus
                            saveSurveyData();

                            // Remove dari DOM
                            $('#survey_form_container').slideUp(300, function() {
                                $(this).empty();
                                console.log('❌ Survey form removed from DOM');
                            });

                            $('#switch_label').text('Show Survey');

                            Swal.fire({
                                icon: 'success',
                                title: 'Survey Hidden',
                                text: 'Survey fields will not be submitted',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            // User cancel, kembalikan switch ke checked
                            $('#survey_form_switch').prop('checked', true);
                        }
                    });
                }

                saveForm();
            });

            // Fungsi untuk save data survey ke localStorage terpisah
            function saveSurveyData() {
                let surveyData = {};

                $('#survey_form_container').find('input, textarea, select').each(function() {
                    if (!this.name) return;

                    let fieldName = this.name;
                    let fieldValue = null;

                    if (this.type === "checkbox") {
                        fieldValue = $(this).is(":checked");
                    } else if (this.type === "radio") {
                        if (!$(this).is(":checked")) return;
                        fieldValue = $(this).val();
                    } else {
                        fieldValue = $(this).val();
                    }

                    if (fieldName.includes('[]')) {
                        let cleanName = fieldName.replace('[]', '');
                        if (!surveyData[cleanName]) {
                            surveyData[cleanName] = [];
                        }
                        surveyData[cleanName].push(fieldValue);
                    } else {
                        surveyData[fieldName] = fieldValue;
                    }
                });

                localStorage.setItem(STORAGE_KEY + '_survey', JSON.stringify(surveyData));
                console.log('💾 Survey data saved separately:', surveyData);
            }

            // Fungsi untuk restore data survey
            function restoreSurveyData() {
                const savedSurvey = localStorage.getItem(STORAGE_KEY + '_survey');
                if (!savedSurvey) return;

                let surveyData = JSON.parse(savedSurvey);
                console.log('🔄 Restoring survey data:', surveyData);

                setTimeout(function() {
                    for (let key in surveyData) {
                        const value = surveyData[key];

                        if (Array.isArray(value)) {
                            value.forEach((val, index) => {
                                let selector = `[name="${escapeName(key + '[]')}"]`;
                                let $els = $(selector);

                                if (index > 0 && key.includes('survey')) {
                                    ensureRowExists(key, index);
                                    $els = $(selector);
                                }

                                if ($els.length > index) {
                                    let $el = $els.eq(index);
                                    const type = $el.attr("type");

                                    if (type === "checkbox") {
                                        $el.prop("checked", !!val);
                                    } else if (type === "radio") {
                                        if ($el.val() == val) {
                                            $el.prop("checked", true);
                                        }
                                    } else {
                                        $el.val(val);
                                    }
                                }
                            });
                        } else {
                            let $els = $(`[name="${escapeName(key)}"]`);

                            $els.each(function() {
                                const type = $(this).attr("type");

                                if (type === "checkbox") {
                                    $(this).prop("checked", !!value);
                                } else if (type === "radio") {
                                    if ($(this).val() == value) {
                                        $(this).prop("checked", true);
                                    }
                                } else {
                                    $(this).val(value);
                                }
                            });
                        }
                    }
                }, 300);
            }

            // Restore form setelah halaman dimuat
            setTimeout(restoreForm, 500);

            const totalWizardSteps = {{ isset($formLink) ? 5 : 6 }};
            const wizardStepLabels = [
                'Master Information',
                'Contact',
                'Address',
                'Bank',
                @unless (isset($formLink))
                'Form Survey',
                @endunless
                'Form Upload'
            ];
            let currentWizardStep = 1;

            function getFieldLabel($field) {
                let fieldId = $field.attr('id');
                let label = fieldId ? $('label[for="' + fieldId + '"]').first().text().trim() : '';

                if (!label) {
                    label = $field.closest('.col-md-3, .col-md-4, .col-md-6, .col-md-9, .col-md-auto, .input-group, .row')
                        .find('label').first().text().trim();
                }

                return (label || ($field.attr('name') || 'Field')).replace(/\*/g, '').replace(/\s+/g, ' ').trim();
            }

            function getMessageContainer($field) {
                let fieldName = ($field.attr('name') || '').replace('[]', '');
                let fieldId = $field.attr('id') || '';
                let index = null;

                if (fieldId.match(/_(\d+)$/)) {
                    index = fieldId.match(/_(\d+)$/)[1];
                }

                let candidates = [];

                if (fieldName === 'province') {
                    candidates.push('#message_province_' + index);
                } else if (fieldName === 'city') {
                    candidates.push('#message_city_' + index);
                } else if (fieldName) {
                    if (index !== null) {
                        candidates.push('#message_' + fieldName + '_' + index);
                    }
                    candidates.push('#message_' + fieldName);
                }

                if (fieldId) {
                    candidates.push('#message_' + fieldId);
                }

                for (let i = 0; i < candidates.length; i++) {
                    let $message = $(candidates[i]);
                    if ($message.length) {
                        return $message.first();
                    }
                }

                return $();
            }

            function isRequiredFieldEmpty($field) {
                if ($field.is(':disabled')) {
                    return false;
                }

                if ($field.is(':radio')) {
                    return $('[name="' + $field.attr('name') + '"]:checked').length === 0;
                }

                if ($field.is(':checkbox')) {
                    return !$field.is(':checked');
                }

                return $.trim($field.val() || '') === '';
            }

            function validateStep(step) {
                let $pane = $('.step-pane[data-step="' + step + '"]');
                let errors = [];

                $pane.find('[id^="message_"]').text('');
                $pane.find('.is-invalid').removeClass('is-invalid');
                $pane.find('.select2-selection.is-invalid').removeClass('is-invalid');

                $pane.find('[required]').each(function() {
                    let $field = $(this);
                    if (!isRequiredFieldEmpty($field)) {
                        return;
                    }

                    let label = getFieldLabel($field);
                    let message = label + ' wajib diisi.';
                    errors.push({
                        field: $field,
                        label: label,
                        message: message
                    });

                    $field.addClass('is-invalid');
                    if ($field.hasClass('select2-hidden-accessible')) {
                        $field.next('.select2').find('.select2-selection').addClass('is-invalid');
                    }
                    getMessageContainer($field).text(message);
                });

                $pane.find('input[type="url"], input[type="email"], input.international-phone').each(function() {
                    let $field = $(this);
                    let field = $field.get(0);
                    let isInternationalPhone = field.classList.contains('international-phone');
                    let invalidFormat = isInternationalPhone
                        ? field.validity.patternMismatch
                        : field.validity.typeMismatch;

                    if (!$field.val() || !invalidFormat) {
                        return;
                    }

                    let message = field.type === 'url'
                        ? 'Alamat website harus berupa URL yang valid.'
                        : isInternationalPhone
                            ? 'Nomor telepon harus terdiri dari 2–14 digit.'
                            : 'Format alamat email tidak valid.';
                    let label = getFieldLabel($field);
                    errors.push({
                        field: $field,
                        label: label,
                        message: message
                    });

                    $field.addClass('is-invalid');
                    getMessageContainer($field).text(message);
                });

                if (!errors.length) {
                    return true;
                }

                let toastBody = errors.slice(0, 6).map(function(error) {
                    return '<div><strong>' + error.label + '</strong>: ' + error.message + '</div>';
                }).join('');

                if (errors.length > 6) {
                    toastBody += '<div>+' + (errors.length - 6) + ' error lainnya.</div>';
                }

                if (typeof showPartnerToast === 'function') {
                    showPartnerToast('Mandatory Field', toastBody, 'danger');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Mandatory Field',
                        html: toastBody
                    });
                }

                let $firstError = errors[0].field;
                let $scrollTarget = $firstError.hasClass('select2-hidden-accessible') ? $firstError.next('.select2') : $firstError;
                if ($scrollTarget.length) {
                    $('html, body').animate({
                        scrollTop: $scrollTarget.offset().top - 150
                    }, 400);
                }

                return false;
            }

            function canMoveToStep(targetStep) {
                if (targetStep <= currentWizardStep) {
                    return true;
                }

                for (let step = currentWizardStep; step < targetStep; step++) {
                    if (step !== currentWizardStep) {
                        showStep(step);
                    }

                    if (!validateStep(step)) {
                        return false;
                    }
                }

                return true;
            }

            function showStep(step) {
                if (step < 1) {
                    step = 1;
                }
                if (step > totalWizardSteps) {
                    step = totalWizardSteps;
                }
                currentWizardStep = step;
                $('#wizard_current_label').text(wizardStepLabels[currentWizardStep - 1]);
                $('#wizard_current_number').text(String(currentWizardStep).padStart(2, '0'));

                $('.step-pane').each(function() {
                    const stepIndex = parseInt($(this).data('step'));
                    if (stepIndex === currentWizardStep) {
                        $(this).removeClass('d-none').addClass('active');
                    } else {
                        $(this).addClass('d-none').removeClass('active');
                    }
                });

                if (currentWizardStep === 3) {
                    document.dispatchEvent(new Event('partner:address-step-shown'));
                }

                $('.wizard-step').each(function() {
                    const stepIndex = parseInt($(this).data('step'));
                    $(this).removeClass('active completed');
                    if (stepIndex < currentWizardStep) {
                        $(this).addClass('completed');
                    }
                    if (stepIndex === currentWizardStep) {
                        $(this).addClass('active');
                    }
                });

                $('#step_prev').toggleClass('d-none', currentWizardStep === 1);
                $('#step_next').toggleClass('d-none', currentWizardStep === totalWizardSteps);
                $('#btn_submit_data_company').toggleClass('d-none', currentWizardStep !== totalWizardSteps);

                $('html, body').animate({
                    scrollTop: $('#form_company').offset().top - 100
                }, 300);
            }

            $('#step_prev').on('click', function() {
                showStep(currentWizardStep - 1);
            });

            $('#step_next').on('click', function() {
                if (canMoveToStep(currentWizardStep + 1)) {
                    showStep(currentWizardStep + 1);
                }
            });

            $('#wizard_stepper').on('click', '.wizard-step', function() {
                const step = parseInt($(this).data('step'));
                if (canMoveToStep(step)) {
                    showStep(step);
                }
            });

            showStep(1);
        });
    </script>

    @include('cs_vendor.partner_js')
