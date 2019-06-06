@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Menu</div>

                <div class="card-body">
                    @if ($variation === 'control')
                        @include('variations.control')
                    @elseif ($variation === 'varA')
                        @include('variations.varA')
                    @else
                        <div>Default</div>
                    @endif
                </div>
            </div>
            <br>
            @if ($featureEnabled)
                @include('variations._feature')
            @endif
            <br>
            <div class="card">
                <div class="card-body">
                    <div class="container">
                        <form method="POST" action="/order">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">Would you like to order Appetizer?</div>
                                <div class="col-md-4">
                                    <div class="radio">
                                        <label class="radio-inline">
                                            <input type="radio" name="appetizer" id="appetizer1" value="1"> Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="appetizer" id="appetizer2" value="0"> No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">Would you like to order Main Course?</div>
                                <div class="col-md-4">
                                    <div class="radio">
                                        <label class="radio-inline">
                                            <input type="radio" name="main" id="main1" value="1"> Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="main" id="main2" value="0"> No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">Would you like to order Dessert?</div>
                                <div class="col-md-4">
                                    <div class="radio">
                                        <label class="radio-inline">
                                            <input type="radio" name="dessert" id="dessert1" value="1"> Yes
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="dessert" id="dessert2" value="0"> No
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            @if ($featureEnabled)
                                <div class="row">
                                        <div class="col-md-8">Would you like to Today Special set instead?</div>
                                        <div class="col-md-4">
                                            <div class="radio">
                                                <label class="radio-inline">
                                                    <input type="radio" name="special" id="special1" value="1"> Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="special" id="special2" value="0"> No
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <hr>
                            @endif
                            <button type="submit" class="btn btn-primary">Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
