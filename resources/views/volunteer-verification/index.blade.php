@extends('adminlte::page')

@section('title', 'Volunteer Verifications')

@section('content_header')
    <h1>{{ __('Volunteer Verifications') }}</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">
                                {{ __('Volunteer Verifications') }}
                            </span>
                            <div class="float-right">
                                <a href="{{ route('volunteer-verifications.create') }}" class="btn btn-primary btn-sm float-right" data-placement="left">
                                    {{ __('Create New') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>User Id</th>
                                        <th>User Resp Id</th>
                                        <th>Document Type</th>
                                        <th>Document Url</th>
                                        <th>Name Document</th>
                                        <th>Status</th>
                                        <th>Comment</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($volunteerVerifications as $volunteerVerification)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $volunteerVerification->user_id }}</td>
                                            <td>{{ $volunteerVerification->user_resp_id }}</td>
                                            <td>{{ $volunteerVerification->document_type }}</td>
                                            <td>{{ $volunteerVerification->document_url }}</td>
                                            <td>{{ $volunteerVerification->name_document }}</td>
                                            <td>{{ $volunteerVerification->status }}</td>
                                            <td>{{ $volunteerVerification->coment }}</td>

                                            <td>
                                                <form action="{{ route('volunteer-verifications.destroy', $volunteerVerification->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary" href="{{ route('volunteer-verifications.show', $volunteerVerification->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('volunteer-verifications.edit', $volunteerVerification->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $volunteerVerifications->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
