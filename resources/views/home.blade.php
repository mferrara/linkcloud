@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        Welcome to LinkCloud<br/><br />
                        Give links. Get links. Clean and simple.
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">Your Stats</div>

                            <table class="table">
                                <table class="table table-condensed">
                                    <tbody>
                                    <tr>
                                        <td>Links Served</td>
                                        <td class="text-right"><strong>{{ number_format(\DB::table('links')->where('user_id', \Auth::user()->id)->sum('given_links')) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{!! route('links.index') !!}">Links</a></td>
                                        <td class="text-right"><strong>{!! number_format(Auth::user()->links()->count()) !!}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{!! route('domains.index') !!}">Domains</a></td>
                                        <td class="text-right"><strong>{!! number_format(Auth::user()->domains()->count()) !!}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><a href="{!! route('anchors.index') !!}">Anchors</a></td>
                                        <td class="text-right"><strong>{!! number_format(Auth::user()->anchors()->count()) !!}</strong></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">Global Stats</div>

                            <table class="table">
                                <table class="table table-condensed">
                                    <tbody>
                                    <tr>
                                        <td>Links in pool</td>
                                        <td class="text-right">{{ number_format(\App\Link::linkPoolCount()) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Links Served</td>
                                        <td class="text-right"><strong>{{ number_format(\DB::table('links')->sum('given_links')) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Domains</td>
                                        <td class="text-right"><strong>{!! number_format(\App\Domain::count()) !!}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Users</td>
                                        <td class="text-right"><strong>{{ number_format(\App\User::count()) }}</strong></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </table>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Helpful links</div>
                            <br>
                            <ul>
                                <li><a href="{{ route('docs.api.v1.index') }}">API "documentation"</a></li>
                                <li>Sample link import .csv file <a href="{!! route('home').'/downloads/sample-links-file.csv' !!}">(Download)</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Upload Links</h3>
                            </div>
                            <div class="panel-body">
                                <form action="/upload-links" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="linksfile">CSV Upload</label>
                                        <input type="file" name="linksfile" id="linksfile">
                                        <br>
                                        <p class="help-block">File must be in the following format:<br><br>
                                            https://sub.domain.com/path/here,anchor text,views (optional - default: {{ config('linkcloud.default_link_count') }})<br>
                                            https://sub.domain.com/path/here,anchor text,views (optional - default: {{ config('linkcloud.default_link_count') }})<br>
                                            <br>
                                            One link per line, each line comma-separated.<br>
                                            url,anchor,link views
                                        </p>
                                        <p class="help-block">
                                            Download a sample file <a
                                                    href="{!! route('home').'/downloads/sample-links-file.csv' !!}">here.</a>
                                        </p>
                                    </div>
                                    @csrf
                                    <br>
                                    <div class="text-right">
                                        <input type="submit" class="btn btn-default">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</home>
@endsection
