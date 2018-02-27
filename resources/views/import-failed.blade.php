@extends('spark::layouts.app')

@section('content')
    <home :user="user" inline-template>
        <div class="container">
            <!-- Application Dashboard -->
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <ol class="breadcrumb">
                        <li>
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="active">Link Import Results</li>
                    </ol>
                    <div class="panel panel-default">
                        <div class="panel-heading">Import Results</div>

                        <div class="panel-body">
                            <p>
                                Uploaded file has {!! $links_attempted !!} lines... <br >
                            </p>

                            <p class="text-danger"><strong>Import failed</strong></p>
                            <p class="alert alert-danger">Error Message: {!! $error_message !!}</p>

                            <p>
                                You currently have {!! $user->links()->count() !!} links in the system.<br>
                            </p>

                            <p><a href="{!! route('dashboard') !!}">Back to dashboard</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </home>
@endsection
