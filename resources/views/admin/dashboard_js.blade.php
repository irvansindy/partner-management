<script>
    $(document).ready(function() {
        fetchDataCount()

        function fetchDataCount() {
            $('#text-total-partner').text('...');
            $('#text-total-customer').text('...');
            $('#text-total-vendor').text('...');
            $('#pending-approval').text('...');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-data-count') }}',
                type: 'GET',
                success: function(res) {
                    $('#text-total-partner').text(res.data.total_partners);
                    $('#text-total-customer').text(res.data.total_customers);
                    $('#text-total-vendor').text(res.data.total_vendors);
                    $('#pending-approval').text(res.data.total_pending_approvals);

                    const ctx = document.getElementById('pieChartUser').getContext('2d');

                    // Daftarkan plugin jika belum
                    if (!Chart.registry.plugins.get('datalabels')) {
                        Chart.register(ChartDataLabels);
                    }

                    if (window.pieChartInstance) {
                        window.pieChartInstance.destroy();
                    }

                    // Data dari response
                    const customerData = res.data.total_customers || 0;
                    const vendorData = res.data.total_vendors || 0;
                    const partnerData = res.data.total_partners || 0;

                    // Check apakah semua data = 0
                    const totalData = customerData + vendorData + partnerData;
                    const isAllZero = totalData === 0;

                    // Jika semua 0, gunakan data dummy untuk tampilan
                    const chartData = isAllZero ? [1, 1, 1] // Data dummy agar chart tetap tampil
                        :
                        [customerData, vendorData, partnerData];

                    // Warna background - jika semua 0, pakai warna abu-abu
                    const chartColors = isAllZero ? ['#e0e0e0', '#e0e0e0',
                        '#e0e0e0'] // Abu-abu untuk empty state
                        :
                        ['#17a2b8', '#ffc107', '#28a745'];

                    window.pieChartInstance = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ['Customer', 'Vendor', 'All Partner'],
                            datasets: [{
                                data: chartData,
                                backgroundColor: chartColors,
                                borderWidth: 1,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                },
                                datalabels: {
                                    color: '#ffffff',
                                    font: {
                                        weight: 'bold',
                                        size: 14
                                    },
                                    formatter: function(value, context) {
                                        // Jika semua data 0, tampilkan "0" bukan data dummy
                                        if (isAllZero) {
                                            return '0';
                                        }
                                        // Jika ada data, tampilkan data asli
                                        return value;
                                    }
                                },
                                tooltip: {
                                    enabled: !isAllZero, // Disable tooltip jika data kosong
                                    callbacks: {
                                        label: function(context) {
                                            if (isAllZero) {
                                                return context.label + ': 0';
                                            }
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const total = context.dataset.data.reduce((
                                                a, b) => a + b, 0);
                                            const percentage = ((value / total) * 100)
                                                .toFixed(1);
                                            return label + ': ' + value + ' (' +
                                                percentage + '%)';
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // OPTIONAL: Tambahkan text "No Data" di tengah chart jika semua 0
                    if (isAllZero) {
                        const chartArea = window.pieChartInstance.chartArea;
                        const centerX = (chartArea.left + chartArea.right) / 2;
                        const centerY = (chartArea.top + chartArea.bottom) / 2;

                        // Plugin untuk draw text di tengah
                        const noDataPlugin = {
                            id: 'noDataText',
                            afterDraw: function(chart) {
                                if (isAllZero) {
                                    const ctx = chart.ctx;
                                    ctx.save();
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'middle';
                                    ctx.font = 'bold 16px Arial';
                                    ctx.fillStyle = '#999';
                                    ctx.fillText('No Data Available', centerX, centerY);
                                    ctx.restore();
                                }
                            }
                        };

                        // Register plugin
                        if (!Chart.registry.plugins.get('noDataText')) {
                            Chart.register(noDataPlugin);
                        }
                    }

                    generateChartPartnerbyProvince(res.data.total_partner_location)
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching count data:', error);
                }
            });
        }

        // =======================
        // 1. Inisialisasi Peta
        // =======================
        const map = L.map('map').setView([-6.2, 106.8], 6); // posisi awal: Indonesia
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // =======================
        // 2. Fungsi Buat Icon FA
        // =======================
        function createFaDivIcon(type) {
            const isCustomer = type === 'customer';
            const bg = isCustomer ? '#dc3545' : '#007bff'; // merah = customer, biru = vendor
            const iconClass = isCustomer ? 'fa-user' : 'fa-home';

            const html = `
            <div style="
                background:${bg};
                width:34px;
                height:34px;
                border-radius:50%;
                display:flex;
                align-items:center;
                justify-content:center;
                border:2px solid #fff;
                box-shadow:0 1px 3px rgba(0,0,0,0.25);
            ">
                <i class="fa ${iconClass}" style="color:#fff;font-size:16px;"></i>
            </div>
            `;

            return L.divIcon({
                html: html,
                className: '',
                iconSize: [34, 34],
                iconAnchor: [17, 34], // tengah bawah
                popupAnchor: [0, -34]
            });
        }

        // =======================
        // 3. Ambil Data via AJAX
        // =======================
        $.ajax({
            url: '{{ route('fetch-map-point') }}', // pastikan route ini mengembalikan JSON {data: [...]}
            type: 'GET',
            dataType: 'json',
            success: function(points) {
                (points.data || []).forEach(function(p) {
                    const marker = L.marker([p.latitude, p.longitude], {
                        icon: createFaDivIcon(p.type)
                    }).addTo(map);

                    marker.bindPopup(`
                        <strong>${p.type.toUpperCase()}</strong><br>
                        <b>${p.name}</b><br>
                        ${p.address}
                    `);
                });
            },
            error: function(xhr, status, error) {
                console.error('Gagal memuat titik peta:', status, error);
            }
        });

        @can('viewCustomer', \App\Models\CompanyInformation::class)
            $('#table_recent_list_customer').DataTable({
                processing: true,
                ajax: {
                    url: '{{ route('fetch-recent-customer') }}',
                    type: 'GET',
                },
                columns: [{
                        "data": "name",
                        "defaultContent": "<i>Not set</i>"
                    },
                    {
                        "data": "email_address",
                        "defaultContent": "<i>Not set</i>"
                    },
                    {
                        "data": "contact_person",
                        "defaultContent": "<i>Not set</i>"
                    },
                    {
                        "data": "status",
                        "defaultContent": "<i>Not set</i>"
                    },
                ]
            });
        @endcan

        @can('viewVendor', \App\Models\CompanyInformation::class)
            $('#table_recent_list_vendor').DataTable({
                processing: true,
                ajax: {
                    url: '{{ route('fetch-recent-vendor') }}',
                    type: 'GET',
                },
                columns: [{
                        "data": "name",
                        "defaultContent": "<i>Not set</i>"
                    },
                    {
                        "data": "email_address",
                        "defaultContent": "<i>Not set</i>"
                    },
                    {
                        "data": "contact_person",
                        "defaultContent": "<i>Not set</i>"
                    },
                    {
                        "data": "status",
                        "defaultContent": "<i>Not set</i>"
                    },
                ]
            });
        @endcan

        @can('viewApproval', \App\Models\CompanyInformation::class)
            $('#list_recent_approvals').html(
                '<li class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</li>');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('fetch-recent-approvals') }}',
                type: 'GET',
                success: function(res) {
                    $('#list_recent_approvals').empty();

                    let is_admin = res.data.isSuperAdmin
                    let data_approvals = res.data.approvals
                    switch (is_admin) {
                        case true:
                            if (data_approvals.length != 0) {
                                data_approvals.forEach(function(approval) {
                                    let approvalText = '';

                                    if (Array.isArray(approval.approval)) {
                                        approvalText = approval.approval
                                            .map((item, index) => `• ${item.user.name}`)
                                            .join('<br>');
                                    } else {
                                        approvalText = approval
                                            .approval; // fallback jika bukan array
                                    }

                                    const companyName = approval.company.name;
                                    const companyType = approval.company.type;
                                    const approvalTextImage = Array.isArray(approval
                                            .approval) ?
                                        approval.approval.join(', ') :
                                        approval.approval;

                                    $('#list_recent_approvals').append(
                                        `<li class="item" style="cursor: pointer;">
                                            <div class="product-img">
                                                <img class="auto-initial-avatar" data-name="${companyName}" width="50" height="50" alt="Avatar">
                                            </div>
                                            <div class="product-info">
                                                <span class="product-title">${approval.company.name}
                                                    <span class="badge badge-info float-right">${approval.company.type}</span>
                                                </span>
                                                <span class="product-description">
                                                    ${approvalText}
                                                </span>
                                            </div>
                                        </li>`
                                    );

                                    // Setelah append, generate avatar dari inisial
                                    $('.auto-initial-avatar').each(function() {
                                        const name = $(this).data('name') || 'NA';
                                        const imgUrl = generateInitialImage(name);
                                        $(this).attr('src', imgUrl);
                                        $(this).css({
                                            'border-radius': '50%',
                                            'object-fit': 'cover'
                                        });
                                    });
                                });
                            } else {
                                $('#list_recent_approvals').append(
                                    '<li class="text-center">No recent approvals found</li>');
                            }
                            break;

                        case false:
                            if (data_approvals.length != 0) {
                                data_approvals.forEach(function(approval) {
                                    const companyName = approval.approval_master.company
                                        .name;
                                    const companyType = approval.approval_master.company
                                        .type;
                                    const approvalTextImage = Array.isArray(approval
                                            .approval) ?
                                        approval.approval.join(', ') :
                                        approval.approval;
                                    $('#list_recent_approvals').append(
                                        `<li class="item" style="cursor: pointer;">
                                            <div class="product-img">
                                                <img class="auto-initial-avatar" data-name="${companyName}" width="50" height="50" alt="Avatar">
                                            </div>
                                            <div class="product-info">
                                                <span class="product-title">${approval.approval_master.company.name}
                                                    <span class="badge badge-info float-right">${approval.approval_master.company.status}</span>
                                                </span>
                                                <span class="product-description">
                                                    ${approval.approval_master.company.liable_person_and_position}
                                                </span>
                                            </div>
                                        </li>`
                                    );
                                    // Setelah append, generate avatar dari inisial
                                    $('.auto-initial-avatar').each(function() {
                                        const name = $(this).data('name') || 'NA';
                                        const imgUrl = generateInitialImage(name);
                                        $(this).attr('src', imgUrl);
                                        $(this).css({
                                            'border-radius': '50%',
                                            'object-fit': 'cover'
                                        });
                                    });
                                });
                            } else {
                                $('#list_recent_approvals').append(
                                    '<li class="text-center">No recent approvals found</li>');
                            }
                            break;
                        default:
                            $('#list_recent_approvals').append(
                                '<li class="text-center">No recent approvals found</li>');
                            break;
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching recent approvals:', error);
                    $('#list_recent_approvals').html(
                        '<li class="text-center">Error loading approvals</li>');
                }
            });
        @endcan

        function generateChartPartnerbyProvince(response) {
            const ctx = document.getElementById('chartPartnerLocation').getContext('2d');

            // Hapus chart sebelumnya jika ada
            if (window.ChartLocationInstance) {
                window.ChartLocationInstance.destroy();
            }

            // Data vendor & customer
            const provinces = [];
            const vendorData = [];
            const customerData = [];

            const hasVendor = response.vendor && response.vendor.length > 0;
            const hasCustomer = response.customer && response.customer.length > 0;

            // Gabungkan semua provinsi unik
            if (hasVendor) {
                response.vendor.forEach(item => {
                    if (!provinces.includes(item.province)) provinces.push(item.province);
                });
            }
            if (hasCustomer) {
                response.customer.forEach(item => {
                    if (!provinces.includes(item.province)) provinces.push(item.province);
                });
            }

            provinces.sort();

            // Isi data sesuai provinsi
            provinces.forEach(prov => {
                vendorData.push(response.vendor?.find(item => item.province === prov)?.total || 0);
                customerData.push(response.customer?.find(item => item.province === prov)?.total || 0);
            });

            // Kalau data kosong, tampilkan teks "No data available"
            if (!hasVendor && !hasCustomer) {
                ctx.save();
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.font = 'bold 16px Arial';
                ctx.fillStyle = '#999';
                ctx.fillText('No data available', ctx.canvas.width / 2, ctx.canvas.height / 2);
                ctx.restore();
                return;
            }

            const datasets = [];

            if (hasVendor) {
                datasets.push({
                    label: 'Vendor',
                    data: vendorData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                });
            }

            if (hasCustomer) {
                datasets.push({
                    label: 'Customer',
                    data: customerData,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                });
            }

            // Buat chart baru
            window.ChartLocationInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: provinces.map(p => p.length > 15 ? p.slice(0, 15) + '…' :
                    p), // singkatin nama panjang
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    layout: {
                        padding: {
                            top: 30,
                            bottom: 30
                        }
                    },
                    onClick: (evt, elements, chart) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const provinceName = chart.data.labels[index];
                            loadRegencyChart(provinceName);
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            title: {
                                display: true,
                                text: 'Total'
                            },
                            grid: {
                                color: '#e5e5e5'
                            }
                        },
                        x: {
                            ticks: {
                                autoSkip: false,
                                maxRotation: 45,
                                minRotation: 45,
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: hasVendor && hasCustomer ?
                                'Total Vendor & Customer per Provinsi' :
                                hasVendor ?
                                'Total Vendor per Provinsi' :
                                'Total Customer per Provinsi'
                        },
                        tooltip: {
                            enabled: true
                        },
                        datalabels: {
                            color: '#000',
                            anchor: 'end',
                            align: 'end',
                            font: {
                                weight: 'bold'
                            },
                            formatter: function(value) {
                                return value > 0 ? value : '';
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        }


        function loadRegencyChart(provinceName) {
            $('#regencyModalLabel').text('Distribusi Kota/Kabupaten di ' + provinceName);
            $('#regencyModal').modal('show');

            $.ajax({
                url: '{{ route('fetch-regency-summary') }}',
                data: {
                    province: provinceName
                },
                method: 'GET',
                success: function(res) {
                    const hasVendor = res.data.vendor && res.data.vendor.length > 0;
                    const hasCustomer = res.data.customer && res.data.customer.length > 0;

                    // Ambil semua nama kota unik
                    const allCities = [
                        ...new Set([
                            ...(hasVendor ? res.data.vendor.map(v => v.city) : []),
                            ...(hasCustomer ? res.data.customer.map(c => c.city) : [])
                        ])
                    ];

                    // Urutkan biar rapi
                    allCities.sort();

                    // Mapping data vendor & customer sesuai urutan kota
                    const vendorTotals = allCities.map(city => {
                        const v = res.data.vendor.find(x => x.city === city);
                        return v ? v.total : 0;
                    });
                    const customerTotals = allCities.map(city => {
                        const c = res.data.customer.find(x => x.city === city);
                        return c ? c.total : 0;
                    });

                    // Destroy chart lama agar tidak menumpuk
                    if (window.regencyChart && typeof window.regencyChart.destroy === 'function') {
                        window.regencyChart.destroy();
                    }

                    const ctx2 = document.getElementById('regencyChart').getContext('2d');

                    window.regencyChart = new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: allCities.map(c => c.length > 15 ? c.slice(0, 15) +
                                '…' : c), // potong nama panjang
                            datasets: [
                                ...(hasVendor ? [{
                                    label: 'Vendor',
                                    data: vendorTotals,
                                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1,
                                    borderRadius: 6
                                }] : []),
                                ...(hasCustomer ? [{
                                    label: 'Customer',
                                    data: customerTotals,
                                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                    borderColor: 'rgba(255, 99, 132, 1)',
                                    borderWidth: 1,
                                    borderRadius: 6
                                }] : [])
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    top: 30,
                                    bottom: 40,
                                    left: 10,
                                    right: 10
                                }
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 45,
                                        minRotation: 30,
                                        font: {
                                            size: 11
                                        }
                                    },
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        font: {
                                            size: 11
                                        },
                                        precision: 0
                                    },
                                    title: {
                                        display: true,
                                        text: 'Total',
                                        font: {
                                            size: 12,
                                            weight: 'bold'
                                        }
                                    },
                                    grid: {
                                        color: 'rgba(200,200,200,0.2)'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        usePointStyle: true,
                                        pointStyle: 'rectRounded',
                                        boxWidth: 10,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                title: {
                                    display: true,
                                    text: hasVendor && hasCustomer ?
                                        `Total Vendor & Customer per Kota/Kabupaten di ${provinceName}` :
                                        hasVendor ?
                                        `Total Vendor per Kota/Kabupaten di ${provinceName}` :
                                        `Total Customer per Kota/Kabupaten di ${provinceName}`,
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    padding: {
                                        bottom: 30
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0,0,0,0.8)',
                                    titleFont: {
                                        size: 13,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 12
                                    },
                                    padding: 10,
                                    callbacks: {
                                        label: function(context) {
                                            return `${context.dataset.label}: ${context.parsed.y}`;
                                        }
                                    }
                                },
                                datalabels: {
                                    color: '#000',
                                    anchor: 'end',
                                    align: 'end',
                                    font: {
                                        weight: 'bold',
                                        size: 11
                                    },
                                    formatter: function(value) {
                                        return value > 0 ? value : '';
                                    }
                                }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                },
                error: function(xhr) {
                    console.error('Error fetching regency data:', xhr);
                }
            });
        }

        function stringToColor(str) {
            let hash = 0;
            for (let i = 0; i < str.length; i++) {
                hash = str.charCodeAt(i) + ((hash << 5) - hash);
            }
            let color = '#';
            for (let i = 0; i < 3; i++) {
                const value = (hash >> (i * 8)) & 0xFF;
                color += ('00' + value.toString(16)).substr(-2);
            }
            return color;
        }

        function generateInitialImage(name, size = 50, textColor = '#fff') {
            const initials = name
                .split(' ')
                .map(word => word.charAt(0))
                .slice(0, 2)
                .join('')
                .toUpperCase();

            const canvas = document.createElement('canvas');
            canvas.width = size;
            canvas.height = size;
            const ctx = canvas.getContext('2d');

            const bgColor = stringToColor(name);
            ctx.fillStyle = bgColor;
            ctx.beginPath();
            ctx.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2);
            ctx.fill();

            ctx.fillStyle = textColor;
            ctx.font = `${size / 2}px sans-serif`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(initials, size / 2, size / 2);

            return canvas.toDataURL();
        }

    });
</script>
