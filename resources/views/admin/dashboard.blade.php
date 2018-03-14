@extends('spark::layouts.app')

@section('content')
    <home :user="user" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <ol class="breadcrumb">
                    	<li class="active">Dashboard</li>
                    </ol>
                    <div class="panel panel-default">
                        <div class="panel-heading">Admin Dashboard</div>

                        <div class="panel-body">
                            <a href="{!! route('admin.users.index') !!}">Users</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </home>
@endsection
