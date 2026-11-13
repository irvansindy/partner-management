<script>
    $(function () {
        const summaryUrl = @json(route('fetch-data-count'));
        const mapUrl = @json(route('fetch-map-point'));
        const regencyUrl = @json(route('fetch-regency-summary'));
        const customerUrl = @json(route('fetch-recent-customer'));
        const vendorUrl = @json(route('fetch-recent-vendor'));
        const dashboardText = {
            customer: @json(__('messages.Dashboard Customer')),
            vendor: @json(__('messages.Dashboard Vendor')),
            partner: @json(__('messages.Dashboard Partner')),
            noPartnerData: @json(__('messages.Dashboard No Partner Data')),
            approvedPartnersAxis: @json(__('messages.Dashboard Approved Partners Axis')),
            distributionInProvince: @json(__('messages.Dashboard Distribution In Province', ['province' => '__PROVINCE__'])),
            cityDistributionLoadFailed: @json(__('messages.Dashboard City Distribution Load Failed')),
            recentPartnersEmpty: @json(__('messages.Dashboard Recent Partners Empty')),
            loadingPartners: @json(__('messages.Dashboard Loading Partners')),
            partnerLocationsLoadFailed: @json(__('messages.Dashboard Partner Locations Load Failed')),
            summaryLoadFailed: @json(__('messages.Dashboard Summary Load Failed')),
            tableSearch: @json(__('messages.Dashboard Table Search')),
            tableInfo: @json(__('messages.Dashboard Table Info')),
            tableInfoEmpty: @json(__('messages.Dashboard Table Info Empty')),
            tableZeroRecords: @json(__('messages.Dashboard Table Zero Records')),
            tablePrevious: @json(__('messages.Dashboard Table Previous')),
            tableNext: @json(__('messages.Dashboard Table Next'))
        };
        const statusLabels = @json(__('partner.statuses'));
        const numberFormat = new Intl.NumberFormat(@json(app()->getLocale()));
        const $status = $('#dashboard-status');
        let regencyChartInstance = null;

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
                chart: { type: 'donut', height: 300, toolbar: { show: false } },
                series: [customerCount, vendorCount],
                labels: [dashboardText.customer, dashboardText.vendor],
                colors: ['#2f80a8', '#e0a13b'],
                legend: { position: 'bottom' },
                dataLabels: { enabled: true },
                noData: { text: dashboardText.noPartnerData }
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
                    name: dashboardText.vendor,
                    data: provinceNames.map(function (name) {
                        const row = vendorRows.find(function (item) { return item.province === name; });
                        return row ? Number(row.total) : 0;
                    })
                });
            }

            if (customerRows.length) {
                series.push({
                    name: dashboardText.customer,
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
                            if (provinceName) loadRegencyChart(provinceName);
                        }
                    }
                },
                series: series,
                colors: ['#2f80a8', '#df7180'],
                plotOptions: { bar: { columnWidth: '55%', borderRadius: 3 } },
                dataLabels: { enabled: false },
                xaxis: { categories: provinceNames, labels: { rotate: -35 } },
                yaxis: { min: 0, forceNiceScale: true, title: { text: dashboardText.approvedPartnersAxis }, labels: { formatter: function (value) { return numberFormat.format(value); } } },
                legend: { position: 'bottom' },
                tooltip: { shared: true, intersect: false }
            }).render();
        }

        function renderRegencyChart(distribution) {
            const vendorRows = Array.isArray(distribution.vendor) ? distribution.vendor : [];
            const customerRows = Array.isArray(distribution.customer) ? distribution.customer : [];
            const cities = Array.from(new Set(
                vendorRows.concat(customerRows).map(function (row) { return row.city; })
            )).sort();
            const series = [];

            if (!cities.length) {
                $('#regency-chart').addClass('d-none');
                $('#regency-chart-empty').removeClass('d-none');
                return;
            }

            if (vendorRows.length) {
                series.push({
                    name: dashboardText.vendor,
                    data: cities.map(function (city) {
                        const row = vendorRows.find(function (item) { return item.city === city; });
                        return row ? Number(row.total) : 0;
                    })
                });
            }
            if (customerRows.length) {
                series.push({
                    name: dashboardText.customer,
                    data: cities.map(function (city) {
                        const row = customerRows.find(function (item) { return item.city === city; });
                        return row ? Number(row.total) : 0;
                    })
                });
            }

            $('#regency-chart-empty').addClass('d-none');
            $('#regency-chart').removeClass('d-none').empty();
            if (regencyChartInstance) {
                regencyChartInstance.destroy();
            }
            regencyChartInstance = new ApexCharts(document.querySelector('#regency-chart'), {
                chart: { type: 'bar', height: 380, toolbar: { show: false } },
                series: series,
                colors: ['#2f80a8', '#df7180'],
                plotOptions: { bar: { columnWidth: '55%', borderRadius: 3 } },
                xaxis: { categories: cities, labels: { rotate: -35 } },
                yaxis: { min: 0, forceNiceScale: true, labels: { formatter: function (value) { return numberFormat.format(value); } } },
                dataLabels: { enabled: false },
                legend: { position: 'bottom' }
            });
            regencyChartInstance.render();
        }

        function loadRegencyChart(provinceName) {
            const $modal = $('#regencyModal');
            $('#regencyModalLabel').text(
                dashboardText.distributionInProvince.replace('__PROVINCE__', provinceName)
            );
            $('#regency-chart').empty().removeClass('d-none');
            $('#regency-chart-empty').addClass('d-none');

            $.ajax({
                url: regencyUrl,
                type: 'GET',
                dataType: 'json',
                data: { province: provinceName },
                success: function (response) {
                    const distribution = response.data || {};
                    if ($modal.hasClass('show')) {
                        renderRegencyChart(distribution);
                    } else {
                        $modal.one('shown.bs.modal', function () {
                            renderRegencyChart(distribution);
                        });
                    }
                    $modal.modal('show');
                },
                error: function () {
                    $('#regency-chart').addClass('d-none');
                    $('#regency-chart-empty').text(dashboardText.cityDistributionLoadFailed).removeClass('d-none');
                    $modal.modal('show');
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
                    error: function () {
                        showDashboardError(@json(__('partner.Unable to load recent partners.')));
                        $table.closest('.dataTables_wrapper').find('.dataTables_processing').hide();
                    },
                    dataSrc: function (response) {
                        return Array.isArray(response.data) ? response.data : [];
                    }
                },
                columns: [
                    { data: 'name', defaultContent: '-', render: $.fn.dataTable.render.text() },
                    { data: 'email_address', defaultContent: '-', render: $.fn.dataTable.render.text() },
                    { data: 'contact_person', defaultContent: '-', render: $.fn.dataTable.render.text() },
                    { data: 'status', defaultContent: '-', render: function (data, type) {
                        const label = Object.prototype.hasOwnProperty.call(statusLabels, data) ? statusLabels[data] : (data || '-');
                        return type === 'display' ? $('<span>').text(label).prop('outerHTML') : label;
                    } }
                ],
                language: {
                    infoFiltered: @json(__('form_links.filtered')),
                    loadingRecords: dashboardText.loadingPartners,
                    aria: { sortAscending: @json(__('partner.sort_ascending')), sortDescending: @json(__('partner.sort_descending')) },
                    emptyTable: dashboardText.recentPartnersEmpty,
                    processing: dashboardText.loadingPartners,
                    search: dashboardText.tableSearch,
                    info: dashboardText.tableInfo,
                    infoEmpty: dashboardText.tableInfoEmpty,
                    zeroRecords: dashboardText.tableZeroRecords,
                    paginate: {
                        first: @json(__('partner.first')),
                        last: @json(__('partner.last')),
                        previous: dashboardText.tablePrevious,
                        next: dashboardText.tableNext
                    }
                }
            });
        }

        function initializeMap() {
            const mapElement = document.getElementById('dashboard-map');
            if (!mapElement || !window.L) return;

            const map = L.map(mapElement, { scrollWheelZoom: false, zoomControl: false }).setView([-6.2, 106.8], 5);
            L.control.zoom({ zoomInTitle: @json(__('partner.Zoom in')), zoomOutTitle: @json(__('partner.Zoom out')) }).addTo(map);
            map.on('popupopen', function (event) {
                const close = event.popup.getElement().querySelector('.leaflet-popup-close-button');
                if (close) {
                    close.title = @json(__('partner.Close popup'));
                    close.setAttribute('aria-label', @json(__('partner.Close popup')));
                }
            });
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
                            (latitude === 0 && longitude === 0)) return;

                        const isCustomer = point.type === 'customer';
                        const marker = L.marker([latitude, longitude], {
                            title: point.name || dashboardText.partner,
                            alt: (isCustomer ? dashboardText.customer : dashboardText.vendor) + ': ' + (point.name || dashboardText.partner),
                            icon: L.divIcon({
                                html: '<span class="dashboard-map-marker ' + (isCustomer ? 'customer' : 'vendor') + '"><i class="fas ' + (isCustomer ? 'fa-user' : 'fa-store') + '" aria-hidden="true"></i></span>',
                                className: '',
                                iconSize: [34, 34],
                                iconAnchor: [17, 34]
                            })
                        }).addTo(map);

                        const popup = document.createElement('div');
                        const title = document.createElement('strong');
                        title.textContent = isCustomer ? dashboardText.customer : dashboardText.vendor;
                        const name = document.createElement('div');
                        name.textContent = point.name || dashboardText.partner;
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
                    $('#dashboard-map-empty').text(dashboardText.partnerLocationsLoadFailed).removeClass('d-none');
                }
            });
        }

        $.ajax({
            url: summaryUrl,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                const data = response.data || {};
                $('#text-total-partner').text(numberFormat.format(data.total_partners ?? 0));
                $('#text-total-customer').text(numberFormat.format(data.total_customers ?? 0));
                $('#text-total-vendor').text(numberFormat.format(data.total_vendors ?? 0));
                $('#pending-approval').text(numberFormat.format(data.total_pending_approvals ?? 0));
                renderTypeChart(Number(data.total_customers) || 0, Number(data.total_vendors) || 0);
                renderProvinceChart(data.total_partner_location || {});
            },
            error: function () {
                showDashboardError(dashboardText.summaryLoadFailed);
                $('.dashboard-metric-value').text('—');
            }
        });

        initializeRecentTable('#table_recent_list_customer', customerUrl);
        initializeRecentTable('#table_recent_list_vendor', vendorUrl);
        initializeMap();
    });
</script>
