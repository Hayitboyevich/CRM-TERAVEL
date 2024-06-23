@extends('layouts.app')

@push('datatable-styles')
    @include('sections.datatable_css')
@endpush

@section('content')

    <!-- CONTENT WRAPPER START -->
    <div class="content-wrapper">
        <!-- Add Task Export Buttons Start -->
        <div class="d-block d-lg-flex d-md-flex justify-content-between action-bar dd">


        </div>
        <!-- Add Task Export Buttons End -->

        <!-- Task Box Start -->
        <div class="d-flex flex-column w-tables rounded mt-3 bg-white table-responsive">

            {!! $dataTable->table(['class' => 'table table-hover border-0 w-100']) !!}

        </div>
        <!-- Task Box End -->
    </div>
    <!-- CONTENT WRAPPER END -->

@endsection

@push('scripts')
    @include('sections.datatable_js')

    <script>
        const showTable = () => {
            window.LaravelDataTables["debititems-table"].draw(false);
        }
        $(document).ready(function () {
            $('#debititems-table').on('click', 'tbody tr', function () {
                // Preventing the default behavior if the click target is a checkbox
                if (!$(event.target).is(':checkbox')) {
                    const id = $(this)[0].id.split('-')[1];

                    // Log the extracted id for debugging purposes
                    console.log(id);

                    // Check if id is truthy (not empty, null, or undefined)
                    if (id) {
                        // Only proceed if id is not empty, null, or undefined
                        var url = '{{ route("applications.edit", ":id") }}'.replace(':id', id);
                        window.location.href = url;
                    } else {
                        // Optionally, log an error or handle the case when id is not valid
                        console.log('Invalid ID: navigation cancelled.');
                    }
                }
            });

            showTable();
        });


        $('.btn-group .btn-secondary').click(function () {
            $('.btn-secondary').removeClass('btn-active');
            $(this).addClass('btn-active');
        });
    </script>
@endpush
