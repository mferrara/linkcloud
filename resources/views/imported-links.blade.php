@extends('spark::layouts.app')

@section('content')
    <home :user="user" inline-template>
        <div class="container">
            <!-- Application Dashboard -->
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">Import Results</div>

                        <div class="panel-body">
                            Links added...

                            <ul>
                            @foreach($new_links as $link)
                                <li>{!! $link->buildHTMLLink() !!} - URL: {!! $link->domain->name.$link->path !!} - Anchor: {!! $link->anchor->text !!}</li>
                            @endforeach
                            </ul>

                            <p>
                                {!! count($new_links) !!} links added.<br>
                                {!! $user->links()->count() !!} total links.<br>
                            </p>

                            <p><a href="{!! route('home') !!}">Back to dashboard</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </home>
@endsection
