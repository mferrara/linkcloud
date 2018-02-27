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
                        Welcome to LinkCloud
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <!-- Default panel contents -->
                            <div class="panel-heading">Stats</div>

                            <!-- Table -->
                            <table class="table">
                                <table class="table table-condensed">
                                    <tbody>
                                    <tr>
                                        <td>Links</td>
                                        <td class="text-right"><strong>{!! Auth::user()->links()->count() !!}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Domains</td>
                                        <td class="text-right"><strong>{!! Auth::user()->domains()->count() !!}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Anchors</td>
                                        <td class="text-right"><strong>{!! Auth::user()->anchors()->count() !!}</strong></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Upload Links</h3>
                            </div>
                            <div class="panel-body">
                                <form action="/upload-links" method="post" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="linksfile">CSV Upload</label>
                                        <input type="file" name="linksfile" id="linksfile">
                                        <p class="help-block">File must be in the following format:<br><br>
                                            https://sub.domain.com/path/here,anchor text here<br>
                                            https://sub.domain.com/path/here,anchor text here<br>
                                            https://sub.domain.com/path/here,anchor text here<br>
                                            https://sub.domain.com/path/here,anchor text here<br>
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
