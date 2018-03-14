@extends('spark::layouts.app')

@section('content')
    <home :user="user" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <ol class="breadcrumb">
                    	<li>
                    		<a href="/admin">Dashboard</a>
                    	</li>
                    	<li class="active">Users</li>
                    </ol>
                    <div class="panel panel-default">
                        <div class="panel-heading">Users</div>

                        <div class="panel-body">
                            <table class="table table-condensed">
                            	<thead>
                            		<tr>
                                        <th>ID</th>
                                        <th>Name</th>
                            			<th>Email</th>
                                        <th class="text-center">API Token</th>
                                        <th class="text-right">Links</th>
                                        <th class="text-right">Points</th>
                                        <th class="text-right"></th>
                            		</tr>
                            	</thead>
                            	<tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{!! $user->id !!}</td>
                                        <td>{!! $user->name !!}</td>
                                        <td>{!! $user->email !!}</td>
                                        <td class="text-center">{!! $user->tokens()->count() > 0 ? 'Yes' : 'No' !!}</td>
                                        <td class="text-right">{!! number_format($user->links()->count()) !!}</td>
                                        <td class="text-right">{!! number_format($user->points) !!}</td>
                                        <td class="text-right"></td>
                                    </tr>
                                @endforeach
                            	</tbody>
                            </table>
                            <p class="text-center">
                                {!! $users->links() !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </home>
@endsection
