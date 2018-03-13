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
                        <div class="col-md-6">showing {{ $links->count() }} of {{ $total_count }} total links</div>
                        <div class="col-md-6">
                            <p class="text-right">Links listed by most recently added first</p>
                        </div>
                    </div>
                    @if(\Illuminate\Support\Facades\Request::has('domain_id') || \Illuminate\Support\Facades\Request::has('anchor_id'))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                            	<strong>Filter:</strong> Links for <strong>
                                @if(\Illuminate\Support\Facades\Request::has('domain_id'))
                                    {{ \App\Domain::find(\Illuminate\Support\Facades\Request::get('domain_id'))->name }}
                                @elseif(\Illuminate\Support\Facades\Request::has('anchor_id'))
                                    {{ \App\Anchor::find(\Illuminate\Support\Facades\Request::get('anchor_id'))->text }}
                                @endif
                                </strong>
                            </div>
                        </div>
                    </div>
                    @endif
                    <table class="table table-condensed">
                    	<thead>
                    		<tr>
                                <th>Link</th>
                    			<th>URL</th>
                                <th>Anchor Text</th>
                                <th class="text-center">Expected Links</th>
                                <th class="text-center">Given Links</th>
                    		</tr>
                    	</thead>
                    	<tbody>
                        @foreach($links as $link)
                    		<tr>
                                <td>{!! $link->buildHTMLLink() !!}</td>
                    			<td>{!! $link->domain->name.$link->path !!}</td>
                                <td>{{ $link->anchor->text }}</td>
                                <td class="text-center">{{ $link->expected_links }}</td>
                                <td class="text-center">{{ $link->given_links }}</td>
                    		</tr>
                        @endforeach
                    	</tbody>
                    </table>
                    <div class="text-center">
                        @if(\Illuminate\Support\Facades\Request::has('domain_id'))
                            {{ $links->appends(['domain_id' => \Illuminate\Support\Facades\Request::get('domain_id')])->links() }}
                        @elseif(\Illuminate\Support\Facades\Request::has('anchor_id'))
                            {{ $links->appends(['anchor_id' => \Illuminate\Support\Facades\Request::get('anchor_id')])->links() }}
                        @else
                            {{ $links->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </home>
@endsection
