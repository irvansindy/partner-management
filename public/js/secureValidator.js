const secureValidator = {
    validateText(input, options = {}) {
        const errors = [];

        const {
            fieldName = 'Input',
            required = true,
            maxLength = 255,
            allowHTML = false,
            safePattern = /^[a-zA-Z0-9.,_\-\s@]+$/
        } = options;

        const value = input?.trim() || '';

        if (required && value === '') {
            errors.push(`${fieldName} tidak boleh kosong.`);
        }

        if (value.length > maxLength) {
            errors.push(`${fieldName} tidak boleh lebih dari ${maxLength} karakter.`);
        }

        const sqlPattern = /(\b(SELECT|INSERT|DELETE|UPDATE|DROP|UNION|--|;|=|OR|AND)\b|['"`])/i;
        if (sqlPattern.test(value)) {
            errors.push(`${fieldName} mengandung pola SQL injection.`);
        }

        const xssPattern = /<[^>]+>|javascript:|on\w+=/i;
        if (!allowHTML && xssPattern.test(value)) {
            errors.push(`${fieldName} mengandung HTML atau JavaScript.`);
        }

        if (!safePattern.test(value)) {
            errors.push(`${fieldName} mengandung karakter tidak diizinkan.`);
        }

        return {
            isValid: errors.length === 0,
            sanitized: secureValidator.escapeHTML(value),
            errors
        };
    },

    validateFile(file, options = {}) {
        const errors = [];

        const {
            fieldName = 'File',
            allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'],
            maxSizeMB = 4
        } = options;

        if (!file) {
            errors.push(`${fieldName} harus dipilih.`);
        } else {
            if (!allowedTypes.includes(file.type)) {
                errors.push(`${fieldName} harus berupa: ${allowedTypes.join(', ')}`);
            }

            const maxSizeBytes = maxSizeMB * 1024 * 1024;
            if (file.size > maxSizeBytes) {
                errors.push(`${fieldName} maksimal ${maxSizeMB} MB.`);
            }
        }

        return {
            isValid: errors.length === 0,
            errors
        };
    },

    validateHTML(input, options = {}) {
        const errors = [];

        const {
            fieldName = 'Konten',
            required = true,
            allowedTags = ['b', 'i', 'u', 'strong', 'em', 'p', 'ul', 'ol', 'li', 'br', 'a'],
            maxLength = 5000
        } = options;

        const value = input?.trim() || '';

        if (required && value === '') {
            errors.push(`${fieldName} tidak boleh kosong.`);
        }

        if (value.length > maxLength) {
            errors.push(`${fieldName} tidak boleh lebih dari ${maxLength} karakter.`);
        }

        const dangerousPattern = /<(script|iframe)[^>]*>|on\w+="[^"]*"/gi;
        if (dangerousPattern.test(value)) {
            errors.push(`${fieldName} mengandung tag atau atribut berbahaya.`);
        }

        const sanitized = value.replace(/<\/?([a-z][a-z0-9]*)\b[^>]*>/gi, (match, tag) => {
            return allowedTags.includes(tag.toLowerCase()) ? match : '';
        });

        return {
            isValid: errors.length === 0,
            sanitized,
            errors
        };
    },

    validateForm(fields = {}) {
        const allErrors = [];
        const sanitizedData = {};

        for (const [key, field] of Object.entries(fields)) {
            if (field.type === 'text') {
                const result = secureValidator.validateText(field.value, field.options || {});
                if (!result.isValid) allErrors.push(...result.errors);
                sanitizedData[key] = result.sanitized;
            }

            if (field.type === 'file') {
                const result = secureValidator.validateFile(field.value, field.options || {});
                if (!result.isValid) allErrors.push(...result.errors);
                sanitizedData[key] = field.value;
            }

            if (field.type === 'html') {
                const result = secureValidator.validateHTML(field.value, field.options || {});
                if (!result.isValid) allErrors.push(...result.errors);
                sanitizedData[key] = result.sanitized;
            }
        }

        return {
            isValid: allErrors.length === 0,
            errors: allErrors,
            sanitized: sanitizedData
        };
    },

    escapeHTML(str) {
        const div = document.createElement('div');
        div.innerText = str;
        return div.innerHTML;
    }
};
