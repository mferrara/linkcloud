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
                    	<li class="active">Links</li>
                    </ol>
                    <div class="row">
                        <div class="col-md-6">showing {{ $links->count() }} of {{ $user->links()->count() }} total links</div>
                        <div class="col-md-6">
                            <p class="text-right">Links listed by most recently added</p>
                        </div>
                    </div>
                    <table class="table table-condensed">
                    	<thead>
                    		<tr>
                    			<th>URL</th>
                                <th>Anchor Text</th>
                                <th class="text-right">Link</th>
                    		</tr>
                    	</thead>
                    	<tbody>
                        @foreach($links as $link)
                    		<tr>
                    			<td>{!! $link->domain->name.$link->path !!}</td>
                                <td>{{ $link->anchor->text }}</td>
                                <td class="text-right">{!! $link->buildHTMLLink() !!}</td>
                    		</tr>
                        @endforeach
                    	</tbody>
                    </table>
                    <div class="text-center">
                        {{ $links->links() }}
                    </div>
                </div>
            </div>
        </div>
    </home>
@endsection
