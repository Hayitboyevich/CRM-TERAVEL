@extends('layouts.app')

@push('styles')
    <style>
        .container {
            margin-top: 50px;
        }

        #search-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #search-results {
            margin-top: 10px;

        }

        .result-item:hover {
            cursor: pointer;
        }

        .result-item {
            margin: 5px 0;
            padding: 10px;
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .custom-center {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 20vh;
        }


    </style>
@endpush
@section('content')
    <div class="custom-center">
        <h4 class="mb-4">Выберите уже существующего</h4>
        <div class="row">
            <div class="col-md-8">
                <!-- Search Form -->
                <form>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search..." id="search-input">

                    </div>
                </form>
                <!-- Search Results -->
                <div id="search-results" class="w-100">
                    <!-- Results will be displayed here dynamically -->
                </div>
            </div>
            <div class="col-md-4">
                <x-forms.link-primary :link="route('services.create')"
                                      class="mr-4 float-left openModal mb-lg-0 mb-md-0"
                >
                    Создать
                </x-forms.link-primary>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#search-input').on('input', function () {
                const searchTerm = $(this).val();
                searchFunction(searchTerm);
            });

            function searchFunction(searchTerm) {
                // Make an AJAX request to your API
                $.ajax({
                    url: '{{route('service.search', $application->id)}}',
                    type: 'GET',
                    data: {q: searchTerm},
                    success: function (data) {
                        displayResults(data);
                    },
                    error: function () {
                        console.log('Error while fetching data from the API.');
                    }
                });
            }

            function displayResults(results) {
                const searchResults = $('#search-results');
                searchResults.empty();

                if (results.length === 0) {
                    searchResults.append('<p>No results found.</p>');
                } else {
                    results.forEach(result => {
                        const resultItem = $('<div class="add-travel">')
                            .addClass('result-item')
                            .text((result.name + ' - ' + result.product_name ?? ""));
                        resultItem.attr('data-id', result.id);
                        // item.append(resultItem);

                        searchResults.append(resultItem);
                    });
                }
            }

            $('body').on('click', '.result-item', function () {
                const id = $(this).data('id');
                var url = "{{ route('applications.services.store', [$application->id, ':id']) }}";
                url = url.replace(':id', id)
                var token = "{{ csrf_token() }}";
                window.location.href = url;
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        '_token': token
                    },
                    success: function (data) {
                        console.log(data);
                    },
                    error: function () {
                        console.log('Error while fetching data from the API.');
                    }
                });
            });

        });
    </script>
@endpush