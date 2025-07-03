<script>
    $(document).ready(function() {
        fetchDataCount()

        function fetchDataCount() {
            $('#text-total-partner').text('...');
            $('#text-total-customer').text('...');
            $('#text-total-vendor').text('...');
            $('#text-total-user').text('...');

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
                    $('#text-total-user').text(res.data.total_users);

                    const ctx = document.getElementById('pieChart').getContext('2d');

                    // Daftarkan plugin jika belum
                    if (!Chart.registry.plugins.get('datalabels')) {
                        Chart.register(ChartDataLabels);
                    }

                    if (window.pieChartInstance) {
                        window.pieChartInstance.destroy();
                    }

                    window.pieChartInstance = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: ['Customer', 'Vendor', 'User'],
                            datasets: [{
                                data: [res.data.total_customers, res.data.total_vendors, res.data.total_users],
                                backgroundColor: ['#17a2b8', '#ffc107', '#28a745'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: 'top' // karena label sudah tampil di luar
                                },
                                datalabels: {
                                    color: '#ffffff',
                                    font: {
                                        weight: 'bold',
                                        size: 14
                                    }
                                },
                                outlabels: {
                                    text: '%l: %v',
                                    color: '#111',
                                    stretch: 25,
                                    font: {
                                        resizable: true,
                                        minSize: 10,
                                        maxSize: 14
                                    },
                                    lineColor: '#ccc',
                                    padding: 6,
                                    fontStyle: 'bold'
                                }
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching count data:', error);
                }
            });
        }

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
            $('#list_recent_approvals').html('<li class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</li>');
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
                                        approvalText = approval.approval; // fallback jika bukan array
                                    }

                                    const companyName = approval.company.name;
                                    const companyType = approval.company.type;
                                    const approvalTextImage = Array.isArray(approval.approval)
                                        ? approval.approval.join(', ')
                                        : approval.approval;

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
                                    $('.auto-initial-avatar').each(function () {
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
                                $('#list_recent_approvals').append('<li class="text-center">No recent approvals found</li>');
                            }
                            break;
                        
                        case false:
                            if (data_approvals.length != 0) {
                                data_approvals.forEach(function(approval) {
                                    const companyName = approval.approval_master.company.name;
                                    const companyType = approval.approval_master.company.type;
                                    const approvalTextImage = Array.isArray(approval.approval)
                                        ? approval.approval.join(', ')
                                        : approval.approval;
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
                                    $('.auto-initial-avatar').each(function () {
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
                                $('#list_recent_approvals').append('<li class="text-center">No recent approvals found</li>');
                            }
                            break;
                        default:
                            $('#list_recent_approvals').append('<li class="text-center">No recent approvals found</li>');
                            break;
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching recent approvals:', error);
                    $('#list_recent_approvals').html('<li class="text-center">Error loading approvals</li>');
                }
            });
            
        @endcan

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
