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
                        <li class="active">Anchors</li>
                    </ol>
                    <div class="row">
                        <div class="col-md-6">showing {{ $anchors->count() }} of {{ $user->anchors()->count() }} total anchors</div>
                        <div class="col-md-6"></div>
                    </div>
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>Anchor Text</th>
                            <th class="text-right">Link Count</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($anchors as $anchor)
                            <tr>
                                <td>{!! $anchor->text !!}</td>
                                <td class="text-right"><a href="{{ route('links.index').'?anchor_id='.$anchor->id }}">{!! $anchor->links()->count() !!}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $anchors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </home>
@endsection
