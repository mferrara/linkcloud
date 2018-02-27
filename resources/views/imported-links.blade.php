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
                                Uploaded file has {!! $links_attempted !!} lines... <br ><br >

                                {!! count($new_links) !!} links successfully added...
                            </p>

                            @if(count($new_links) < 1000)
                                <ul>
                                    @foreach($new_links as $link)
                                        <li>{!! $link->buildHTMLLink(true) !!} - URL: {!! $link->domain->name.$link->path !!} - Anchor: {!! $link->anchor->text !!}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Skipping listing each link since you imported > 1000 links (trying to save your browser/computer)</p>
                            @endif

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
