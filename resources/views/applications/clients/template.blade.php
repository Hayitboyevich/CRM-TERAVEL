@extends('layouts.app')


@section('content')

    <div class="content-wrapper">

        @include($view)
    </div>

@endsection
@push('scripts')
    <script>
        $(document).on('change', '.file-input', function () {

            var filesCount = $(this)[0].files.length;

            var textbox = $(this).prev();

            if (filesCount === 1) {
                var fileName = $(this).val().split('\\').pop();
                textbox.text(fileName);
            } else {
                textbox.text(filesCount + ' files selected');
            }
        });
    </script>
    
@endpush