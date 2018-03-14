@extends('spark::layouts.app')

@section('content')
<home :user="user" inline-template>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @if(Auth::user()->tokens()->count() == 0)
                    <div class="alert alert-info">
                    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    	<strong>API Token</strong> You haven't issued an API token yet! Head over to <a href="{{ route('user.settings') }}#/api">API settings</a> to
                        issue a token.
                    </div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        Welcome to LinkCloud<br/><br />
                        Give links. Get links. Clean and simple.

                        <br>
                        <br>
                        <p>Sample PHP code snippet to include links on a page <a class="btn btn-default btn-sm" href="#sample-snippet" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="sample-snippet">show</a></p>
                        <pre class="collapse" id="sample-snippet">
$lc_api_token = 'your_token_here';
$lc_max_request_time = 1; // # of seconds before link request is aborted
$lc_user_agent = null;
if(isset($_SERVER['HTTP_USER_AGENT']))
    $lc_user_agent = $_SERVER['HTTP_USER_AGENT'];

$lc_ip_address = null;
if(isset($_SERVER['REMOTE_ADDR']))
    $lc_ip_address = $_SERVER['REMOTE_ADDR'];

$lc_links = '';
if( ! is_null($lc_user_agent) && ! is_null($lc_ip_address))
{
    $lc_request_url = 'https://linkcloud.net/api/v1/links?api_token='.$lc_api_token.'&ip='.urlencode($lc_ip_address).'&ua='.urlencode($lc_user_agent);
    $lc_options = stream_context_create(array('http'=>
        array(
            'timeout' => $lc_max_request_time
        )
    ));
    $links = file_get_contents($lc_request_url, false, $lc_options);
}
echo $lc_links;
                        </pre>
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
