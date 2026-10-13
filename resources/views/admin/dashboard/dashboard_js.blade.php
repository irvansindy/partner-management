<script>
    $(function () {
        const summaryUrl = @json(route('fetch-data-count'));
        const mapUrl = @json(route('fetch-map-point'));
        const regencyUrl = @json(route('fetch-regency-summary'));
        const customerUrl = @json(route('fetch-recent-customer'));
        const vendorUrl = @json(route('fetch-recent-vendor'));
        const $status = $('#dashboard-status');

        function showDashboardError(message) {
            $status.text(message).removeClass('d-none');
        }

        function renderTypeChart(customerCount, vendorCount) {
            const total = customerCount + vendorCount;
            const $chart = $('#partner-type-chart');

            if (!total) {
                $chart.addClass('d-none');
                $('#partner-type-empty').removeClass('d-none');
                return;
            }

            $('#partner-type-empty').addClass('d-none');
            $chart.removeClass('d-none');

            new ApexCharts($chart.get(0), {
                chart: {
                    type: 'donut',
                    height: 300
                },
                series: [customerCount, vendorCount],
                labels: ['Customer', 'Vendor'],
                colors: ['#2f80a8', '#e0a13b'],
                legend: {
                    position: 'bottom'
                },
                dataLabels: {
                    enabled: true
                },
                noData: {
                    text: 'No partner data'
                }
            }).render();
        }

        function renderProvinceChart(distribution) {
            const vendorRows = Array.isArray(distribution.vendor) ? distribution.vendor : [];
            const customerRows = Array.isArray(distribution.customer) ? distribution.customer : [];
            const provinceNames = Array.from(new Set(
                vendorRows.concat(customerRows).map(function (row) { return row.province; })
            )).sort();
            const series = [];

            if (vendorRows.length) {
                series.push({
                    name: 'Vendor',
                    data: provinceNames.map(function (name) {
                        const row = vendorRows.find(function (item) { return item.province === name; });
                        return row ? Number(row.total) : 0;
                    })
                });
            }

            if (customerRows.length) {
                series.push({
                    name: 'Customer',
                    data: provinceNames.map(function (name) {
                        const row = customerRows.find(function (item) { return item.province === name; });
                        return row ? Number(row.total) : 0;
                    })
                });
            }

            if (!series.length) {
                $('#province-chart').addClass('d-none');
                $('#province-chart-empty').removeClass('d-none');
                return;
            }

            $('#province-chart-empty').addClass('d-none');
            $('#province-chart').removeClass('d-none');

            new ApexCharts(document.querySelector('#province-chart'), {
                chart: {
                    type: 'bar',
                    height: 360,
                    toolbar: { show: false },
                    events: {
                        dataPointSelection: function (event, chartContext, config) {
                            const provinceName = provinceNames[config.dataPointIndex];
                            if (provinceName) {
                                loadRegencyChart(provinceName);
                            }
                        }
                    }
                },
                series: series,
                colors: ['#2f80a8', '#df7180'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 3
                    }
                },
                dataLabels: { enabled: false },
                xaxis: {
                    categories: provinceNames,
                    labels: { rotate: -35 }
                },
                yaxis: {
                    min: 0,
                    forceNiceScale: true,
                    title: { text: 'Approved partners' }
                },
                legend: { position: 'bottom' },
                tooltip: { shared: true, intersect: false }
            }).render();
        }

        function loadRegencyChart(provinceName) {
            const $modal = $('#regencyModal');
            $('#regencyModalLabel').text('Partner distribution in ' + provinceName);
            $('#regency-chart').empty();
            $('#regency-chart-empty').addClass('d-none');
            $modal.modal('show');

            $.ajax({
                url: regencyUrl,
                type: 'GET',
                dataType: 'json',
                data: { province: provinceName },
                success: function (response) {
                    const distribution = response.data || {};
                    const vendorRows = Array.isArray(distribution.vendor) ? distribution.vendor : [];
                    const customerRows = Array.isArray(distribution.customer) ? distribution.customer : [];
                    const cities = Array.from(new Set(
                        vendorRows.concat(customerRows).map(function (row) { return row.city; })
                    )).sort();

                    if (!cities.length) {
                        $('#regency-chart-empty').removeClass('d-none');
                        return;
                    }

                    const series = [];
                    if (vendorRows.length) {
                        series.push({
                            name: 'Vendor',
                            data: cities.map(function (city) {
                                const row = vendorRows.find(function (item) { return item.city === city; });
                                return row ? Number(row.total) : 0;
                            })
                        });
                    }
                    if (customerRows.length) {
                        series.push({
                            name: 'Customer',
                            data: cities.map(function (city) {
                                const row = customerRows.find(function (item) { return item.city === city; });
                                return row ? Number(row.total) : 0;
                            })
                        });
                    }

                    new ApexCharts(document.querySelector('#regency-chart'), {
                        chart: { type: 'bar', height: 380, toolbar: { show: false } },
                        series: series,
                        colors: ['#2f80a8', '#df7180'],
                        plotOptions: { bar: { columnWidth: '55%', borderRadius: 3 } },
                        xaxis: { categories: cities, labels: { rotate: -35 } },
                        yaxis: { min: 0, forceNiceScale: true },
                        dataLabels: { enabled: false },
                        legend: { position: 'bottom' }
                    }).render();
                },
                error: function () {
                    $('#regency-chart-empty').text('Unable to load city distribution.').removeClass('d-none');
                }
            });
        }

        function initializeRecentTable(selector, url) {
            const $table = $(selector);
            if (!$table.length) return;

            $table.DataTable({
                processing: true,
                pageLength: 5,
                lengthChange: false,
                order: [[0, 'asc']],
                ajax: {
                    url: url,
                    type: 'GET',
                    dataSrc: function (response) {
                        return Array.isArray(response.data) ? response.data : [];
                    }
                },
                columns: [
                    { data: 'name', defaultContent: '-', render: $.fn.dataTable.render.text() },
                    { data: 'email_address', defaultContent: '-', render: $.fn.dataTable.render.text() },
                    { data: 'contact_person', defaultContent: '-', render: $.fn.dataTable.render.text() },
                    { data: 'status', defaultContent: '-', render: $.fn.dataTable.render.text() }
                ],
                language: {
                    emptyTable: 'No recent partners found',
                    processing: 'Loading partners...'
                }
            });
        }

        function initializeMap() {
            const mapElement = document.getElementById('dashboard-map');
            if (!mapElement || !window.L) return;

            const map = L.map(mapElement, { scrollWheelZoom: false }).setView([-6.2, 106.8], 5);
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles &copy; Esri'
            }).addTo(map);

            $.ajax({
                url: mapUrl,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    const points = Array.isArray(response.data) ? response.data : [];
                    const bounds = L.latLngBounds([]);
                    let markerCount = 0;

                    points.forEach(function (point) {
                        const latitude = Number(point.latitude);
                        const longitude = Number(point.longitude);
                        if (!Number.isFinite(latitude) || !Number.isFinite(longitude) ||
                            latitude < -90 || latitude > 90 || longitude < -180 || longitude > 180 ||
                            (latitude === 0 && longitude === 0)) {
                            return;
                        }

                        const isCustomer = point.type === 'customer';
                        const marker = L.marker([latitude, longitude], {
                            icon: L.divIcon({
                                html: '<span class="dashboard-map-marker ' + (isCustomer ? 'customer' : 'vendor') + '"><i class="fas ' + (isCustomer ? 'fa-user' : 'fa-house') + '"></i></span>',
                                className: '',
                                iconSize: [34, 34],
                                iconAnchor: [17, 34]
                            })
                        }).addTo(map);

                        const popup = document.createElement('div');
                        const title = document.createElement('strong');
                        title.textContent = isCustomer ? 'Customer' : 'Vendor';
                        const name = document.createElement('div');
                        name.textContent = point.name || 'Partner';
                        const address = document.createElement('div');
                        address.textContent = point.address || '';
                        popup.append(title, name, address);
                        marker.bindPopup(popup);
                        bounds.extend([latitude, longitude]);
                        markerCount++;
                    });

                    if (markerCount) {
                        map.fitBounds(bounds, { padding: [24, 24], maxZoom: 7 });
                    } else {
                        $('#dashboard-map-empty').removeClass('d-none');
                    }
                },
                error: function () {
                    $('#dashboard-map-empty').text('Unable to load partner locations.').removeClass('d-none');
                }
            });
        }

        $.ajax({
            url: summaryUrl,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const data = response.data || {};
                $('#text-total-partner').text(data.total_partners ?? 0);
                $('#text-total-customer').text(data.total_customers ?? 0);
                $('#text-total-vendor').text(data.total_vendors ?? 0);
                $('#pending-approval').text(data.total_pending_approvals ?? 0);
                renderTypeChart(Number(data.total_customers) || 0, Number(data.total_vendors) || 0);
                renderProvinceChart(data.total_partner_location || {});
            },
            error: function () {
                showDashboardError('Dashboard summary could not be loaded. Refresh the page to try again.');
                $('.dashboard-metric-value').text('—');
            }
        });

        initializeRecentTable('#table_recent_list_customer', customerUrl);
        initializeRecentTable('#table_recent_list_vendor', vendorUrl);
        initializeMap();
    });
</script>
