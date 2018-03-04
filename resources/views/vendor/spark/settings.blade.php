@extends('spark::layouts.app')

@section('scripts')
    @if (Spark::billsUsingStripe())
        <script src="https://js.stripe.com/v2/"></script>
    @else
        <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
    @endif
@endsection

@section('content')
<spark-settings :user="user" :teams="teams" inline-template>
    <div class="spark-screen container">
        <div class="row">
            <!-- Tabs -->
            <div class="col-md-4">
                <div class="panel panel-default panel-flush">
                    <div class="panel-heading">
                        Settings
                    </div>

                    <div class="panel-body">
                        <div class="spark-settings-tabs">
                            <ul class="nav spark-settings-stacked-tabs" role="tablist">
                                <!-- Profile Link -->
                                <li role="presentation">
                                    <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">
                                        <i class="fa fa-fw fa-btn fa-edit"></i>Profile
                                    </a>
                                </li>

                                <!-- Teams Link -->
                                @if (Spark::usesTeams())
                                    <li role="presentation">
                                        <a href="#{{str_plural(Spark::teamString())}}" aria-controls="teams" role="tab" data-toggle="tab">
                                            <i class="fa fa-fw fa-btn fa-users"></i>{{ ucfirst(str_plural(Spark::teamString())) }}
                                        </a>
                                    </li>
                                @endif

                                <!-- Security Link -->
                                <li role="presentation">
                                    <a href="#security" aria-controls="security" role="tab" data-toggle="tab">
                                        <i class="fa fa-fw fa-btn fa-lock"></i>Security
                                    </a>
                                </li>

                                <!-- API Link -->
                                @if (Spark::usesApi())
                                    <li role="presentation">
                                        <a href="#api" aria-controls="api" role="tab" data-toggle="tab">
                                            <i class="fa fa-fw fa-btn fa-cubes"></i>API
                                        </a>
                                    </li>
                                @endif

                                <li role="presentation">
                                    <a href="#link-settings" aria-controls="link-settings" role="tab" data-toggle="tab">
                                        <i class="fa fa-fw fa-btn fa-cubes"></i>Link Settings
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Billing Tabs -->
                @if (Spark::canBillCustomers())
                    <div class="panel panel-default panel-flush">
                        <div class="panel-heading">
                            Billing
                        </div>

                        <div class="panel-body">
                            <div class="spark-settings-tabs">
                                <ul class="nav spark-settings-stacked-tabs" role="tablist">
                                    @if (Spark::hasPaidPlans())
                                        <!-- Subscription Link -->
                                        <li role="presentation">
                                            <a href="#subscription" aria-controls="subscription" role="tab" data-toggle="tab">
                                                <i class="fa fa-fw fa-btn fa-shopping-bag"></i>Subscription
                                            </a>
                                        </li>
                                    @endif

                                    <!-- Payment Method Link -->
                                    <li role="presentation">
                                        <a href="#payment-method" aria-controls="payment-method" role="tab" data-toggle="tab">
                                            <i class="fa fa-fw fa-btn fa-credit-card"></i>Payment Method
                                        </a>
                                    </li>

                                    <!-- Invoices Link -->
                                    <li role="presentation">
                                        <a href="#invoices" aria-controls="invoices" role="tab" data-toggle="tab">
                                            <i class="fa fa-fw fa-btn fa-history"></i>Invoices
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Tab Panels -->
            <div class="col-md-8">
                <div class="tab-content">
                    <!-- Profile -->
                    <div role="tabpanel" class="tab-pane active" id="profile">
                        @include('spark::settings.profile')
                    </div>

                    <!-- Teams -->
                    @if (Spark::usesTeams())
                        <div role="tabpanel" class="tab-pane" id="{{str_plural(Spark::teamString())}}">
                            @include('spark::settings.teams')
                        </div>
                    @endif

                    <!-- Security -->
                    <div role="tabpanel" class="tab-pane" id="security">
                        @include('spark::settings.security')
                    </div>

                    <!-- API -->
                    @if (Spark::usesApi())
                        <div role="tabpanel" class="tab-pane" id="api">
                            @include('spark::settings.api')
                        </div>
                    @endif

                    <!-- Link Settings -->
                    <div role="tabpanel" class="tab-pane" id="link-settings">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Link Settings</h3>
                            </div>
                            <div class="panel-body">
                                <form action="{{ route('user.settings.update') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p>
                                                <strong>Link presentation format options</strong><br />
                                            <ul>
                                                <li>
                                                    <strong>{{ '<br />' }}</strong> (Default) - Links will be delivered as follows:<br />
                                                    Link{{ '<br />' }}<br>
                                                    Link{{ '<br />' }}<br>
                                                    Link
                                                </li>
                                                <li>
                                                    <strong>{{ '<li></li>' }}</strong> - Links will be delivered as follows:<br />
                                                    {{ '<li>' }}Link{{ '</li>' }}<br>
                                                    {{ '<li>' }}Link{{ '</li>' }}<br>
                                                    {{ '<li>' }}Link{{ '</li>' }}
                                                </li>
                                            </ul>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select class="form-control" name="link-wrapper-method" id="link-wrapper-method">
                                                    <option value="">Select</option>
                                                    <option value="br" @if(\Auth::user()->linkMethod() == 'br') selected @endif>{{ '<br />' }}</option>
                                                    <option value="li" @if(\Auth::user()->linkMethod() == 'li') selected @endif>{{ '<li></li>' }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="interlinking" value="on" @if(\Auth::user()->settings('interlinking', config('linkcloud.default_interlinking')) == true) checked @endif>
                                            Allow my own links to be shown on my own sites (interlinking)
                                        </label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-10 text-right">
                                            <input type="submit" class="btn btn-primary">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Tab Panes -->
                    @if (Spark::canBillCustomers())
                        @if (Spark::hasPaidPlans())
                            <!-- Subscription -->
                            <div role="tabpanel" class="tab-pane" id="subscription">
                                <div v-if="user">
                                    @include('spark::settings.subscription')
                                </div>
                            </div>
                        @endif

                        <!-- Payment Method -->
                        <div role="tabpanel" class="tab-pane" id="payment-method">
                            <div v-if="user">
                                @include('spark::settings.payment-method')
                            </div>
                        </div>

                        <!-- Invoices -->
                        <div role="tabpanel" class="tab-pane" id="invoices">
                            @include('spark::settings.invoices')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</spark-settings>
@endsection
