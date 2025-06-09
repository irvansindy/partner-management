<script>
    $(document).ready(function() {
        // Initialize DataTable for any tables in the form
        $('#list_partner').DataTable({
            processing: true,
            ajax: {
                url: "{{ route('fetch-partner-by-user') }}",
                type: 'GET',
            },
            columns: [
                {
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                { data: 'name', name: 'name' },
                { data: 'company_name', name: 'company_name' },
                { data: 'email', name: 'email' },
                {
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        return '<button type="button" data-partner_id="'+item.id+'" data-partner_name="'+item.name+'" class="btn btn-outline-info btn-sm mt-2 detail_partner" data-bs-toggle="modal" data-bs-target="#formCreateEvent">View</button>';
                    }
                }
            ],
        });
    });
</script>