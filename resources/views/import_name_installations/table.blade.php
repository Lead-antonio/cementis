@push('third_party_stylesheets')
    @include('layouts.datatables_css')
@endpush

{!! $dataTable->table(['width' => '100%', 'class' => 'table table-striped table-bordered','id'=>"dataTableBuilder"]) !!}

@push('third_party_scripts')
    @include('layouts.datatables_js')
    {!! $dataTable->scripts() !!}

    <script>
        $(document).ready(function() {
            $('#dataTableBuilder').DataTable().column('observation:name').nodes().to$().each(function () {
                var content = $(this).html();
                $(this).html(content.replace(/&lt;br&gt;/g, '<br>'));
            });
        });
    </script>
@endpush