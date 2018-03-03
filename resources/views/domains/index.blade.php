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
                        <li class="active">Domains</li>
                    </ol>
                    <div class="row">
                        <div class="col-md-6">showing {{ $domains->count() }} of {{ $user->domains()->count() }} total domains</div>
                        <div class="col-md-6"></div>
                    </div>
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th>Domain</th>
                            <th class="text-right">Link Count</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($domains as $domain)
                            <tr>
                                <td>{!! $domain->name !!}</td>
                                <td class="text-right"><a href="{{ route('links.index').'?domain_id='.$domain->id }}">{!! $domain->links()->count() !!}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-center">
                        {{ $domains->links() }}
                    </div>
                </div>
            </div>
        </div>
    </home>
@endsection
