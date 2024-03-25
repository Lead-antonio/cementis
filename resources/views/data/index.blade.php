@extends('layouts.app')

@section('content')
    <div class="content px-3" style="padding: 15px">

        <div class="card">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">@lang('models/api.fields.id') </th>
                    <th scope="col">@lang('models/api.fields.type') </th>
                    <th scope="col">@lang('models/api.fields.description')</th>
                    <th scope="col">@lang('models/api.fields.vehicle')</th>
                    <th scope="col">@lang('models/api.fields.date')</th>
                    <th scope="col">@lang('models/api.fields.latitude')</th>
                    <th scope="col">@lang('models/api.fields.longitude')</th>
                </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($data as $item)
                        <tr>
                            <th scope="row">{{ $item[2] }}</th>
                            <td>{{ $item[0] }}</td>
                            <td>{{ $item[1] }}</td>
                            <td>{{ $item[3] }}</td>
                            <td>{{ $item[4] }}</td>
                            <td>{{ $item[5] }}</td>
                            <td>{{ $item[6] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $data->links() }}
    </div>
@endsection