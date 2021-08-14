@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card-deck mb-3 text-center">
            @foreach($products as $product)
            <div class="card mb-4 box-shadow">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal">{{$product->title}}</h4>
                </div>
                <div class="card-body">
                    <h1 class="card-title pricing-card-title">${{$product->price}} <small class="text-muted">/ mo</small></h1>
                   <p>{{$product->desc}}</p>
                    <a href="{{route('products.buy',$product)}}" type="button" class="btn btn-lg btn-block btn-primary">Buy</a>
                </div>
            </div>
            @endforeach
        </div>
@endsection
