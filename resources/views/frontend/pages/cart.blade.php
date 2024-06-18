@extends('frontend.layouts.master')
@section('title','Cos cumparaturi')
@section('main-content')
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="{{('home')}}">Acasa<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="">Cos</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="shopping-cart section">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<table class="table shopping-summery">
						<thead>
							<tr class="main-hading">
								<th>Produs</th>
								<th>Denumire</th>
								<th class="text-center">Pretul</th>
								<th class="text-center">Cantitatea</th>
								<th class="text-center">Total</th>
								<th class="text-center"><i class="ti-trash remove-icon"></i></th>
							</tr>
						</thead>
						<tbody id="cart_item_list">
							<form action="{{route('cart.update')}}" method="POST">
								@csrf
								@if(Helper::getAllProductFromCart())
									@foreach(Helper::getAllProductFromCart() as $key=>$cart)
										<tr>
											@php
											$photo=explode(',',$cart->product['photo']);
											@endphp
											<td class="image" data-title="No"><img src="{{$photo[0]}}" alt="{{$photo[0]}}"></td>
											<td class="product-des" data-title="Description">
												<p class="product-name"><a href="{{route('product-detail',$cart->product['slug'])}}" target="_blank">{{$cart->product['title']}}</a></p>
												<p class="product-des">{!!($cart['summary']) !!}</p>
											</td>
											<td class="price" data-title="Price"><span>{{number_format($cart['price'],2)}}Lei</span></td>
											<td class="qty" data-title="Qty">
												<div class="input-group">
													<div class="button minus">
														<button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[{{$key}}]">
															<i class="ti-minus"></i>
														</button>
													</div>
													<input type="text" name="quant[{{$key}}]" class="input-number"  data-min="1" data-max="100" value="{{$cart->quantity}}">
													<input type="hidden" name="qty_id[]" value="{{$cart->id}}">
													<div class="button plus">
														<button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[{{$key}}]">
															<i class="ti-plus"></i>
														</button>
													</div>
												</div>
											</td>
											<td class="total-amount cart_single_price" data-title="Total"><span class="money">{{$cart['amount']}}Lei</span></td>

											<td class="action" data-title="Remove"><a href="{{route('cart-delete',$cart->id)}}"><i class="ti-trash remove-icon"></i></a></td>
										</tr>
									@endforeach
									<track>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td class="float-right">
											<button class="btn float-right" type="submit">Update</button>
										</td>
									</track>
								@else
										<tr>
											<td class="text-center">
											<a href="{{route('product-grids')}}" style="color:blue;">Continua cumparaturile</a>

											</td>
										</tr>
								@endif

							</form>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="total-amount mx-4">
						<div class="row">
							<div class="col-lg-4 col-md-7 col-12">
								<div class="right">
									<ul>
										@php
											$total_amount=Helper::totalCartPrice();
										@endphp
										@if(session()->has('coupon'))
											<li class="last" id="order_total_price">Pret<span>{{number_format($total_amount,2)}}Lei</span></li>
										@else
											<li class="last" id="order_total_price">Pret<span>{{number_format($total_amount,2)}}Lei</span></li>
										@endif
									</ul>
									<div class="button5">
										<a href="{{route('checkout')}}" class="btn">Comanda</a>
										<a href="{{route('product-grids')}}" class="btn">Continua cumparaturile</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@push('styles')
	<style>
		.input-group-icon .icon {
			position: absolute;
			left: 20px;
			top: 0;
			line-height: 40px;
			z-index: 3;
		}
		.form-select {
			height: 30px;
			width: 100%;
		}
		.form-select .nice-select {
			border: none;
			border-radius: 0px;
			height: 40px;
			background: #f6f6f6 !important;
			padding-left: 45px;
			padding-right: 40px;
			width: 100%;
		}
		.list li{
			margin-bottom:0 !important;
		}
		.list li:hover{
			background:#F7941D !important;
			color:white !important;
		}
		.form-select .nice-select::after {
			top: 14px;
		}
	</style>
@endpush
@push('scripts')
	<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
	<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
	<script>
		$(document).ready(function() { $("select.select2").select2(); });
  		$('select.nice-select').niceSelect();
	</script>
	<script>
		$(document).ready(function(){
			$('.shipping select[name=shipping]').change(function(){
				let cost = parseFloat( $(this).find('option:selected').data('price') ) || 0;
				let subtotal = parseFloat( $('.order_subtotal').data('price') );
				let coupon = parseFloat( $('.coupon_price').data('price') ) || 0;
				// alert(coupon);
				$('#order_total_price span').text('$'+(subtotal + cost-coupon).toFixed(2));
			});

		});

	</script>

@endpush
