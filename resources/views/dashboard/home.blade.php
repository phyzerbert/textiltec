@extends('layouts.master')
@section('style')    
    <link href="{{asset('master/plugins/daterangepicker/daterangepicker.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="container-fluid p-0">
        <div class="row">            
            <div class="col-12 col-md-6 col-xl d-flex">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="mb-2">{{$return['total_suppliers']}}</h3>
                                <div class="mb-0">{{__('page.total_suppliers')}}</div>
                            </div>
                            <div class="col-4 ml-auto text-right">
                                <div class="d-inline-block mt-2">
                                    <i class="feather-lg text-primary" data-feather="truck"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl d-flex">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="mb-2">{{$return['total_customers']}}</h3>
                                <div class="mb-0">{{__('page.total_customers')}}</div>
                            </div>
                            <div class="col-4 ml-auto text-right">
                                <div class="d-inline-block mt-2">
                                    <i class="feather-lg text-warning" data-feather="activity"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl d-flex">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="mb-2">{{$return['total_supply_count']}} / {{$return['total_supply_quantity']}}</h3>
                                <div class="mb-0">{{__('page.total_supplies')}}</div>
                            </div>
                            <div class="col-4 ml-auto text-right">
                                <div class="d-inline-block mt-2">
                                    <i class="feather-lg text-success" data-feather="codepen"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl d-flex">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-8">
                                <h3 class="mb-2">{{$return['total_product_count']}} / {{$return['total_product_quantity']}}</h3>
                                <div class="mb-0">{{__('page.total_products')}}</div>
                            </div>
                            <div class="col-4 ml-auto text-right">
                                <div class="d-inline-block mt-2">
                                    <i class="feather-lg text-info" data-feather="box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            if($period != ""){   
                $period = $period;
                $from = substr($period, 0, 10);
                $to = substr($period, 14, 10);
            }

            if(isset($from) && isset($to)){
                $chart_start = \Carbon\Carbon::createFromFormat('Y-m-d', $from);
                $chart_end = \Carbon\Carbon::createFromFormat('Y-m-d', $to);
            }else{
                $chart_start = \Carbon\Carbon::now()->startOfMonth();
                $chart_end = \Carbon\Carbon::now()->endOfMonth();
            }

            $key_array = $supply_in = $supply_out = $product_in = $product_out = array();

            for ($dt=$chart_start; $dt < $chart_end; $dt->addDay()) {
                $key = $dt->format('Y-m-d');
                $key1 = $dt->format('M/d');
                array_push($key_array, $key1);
                $supply_purchases = \App\Models\Purchase::whereDate('timestamp', $key)->pluck('id');
                $production_orders = \App\Models\ProduceOrder::whereDate('timestamp', $key)->pluck('id');
                $product_sales = \App\Models\ProductSale::whereDate('timestamp', $key)->pluck('id');
                $daily_supply_in = \App\Models\PurchaseOrder::whereIn('purchase_id', $supply_purchases)->sum('quantity');
                $daily_supply_out = \App\Models\ProduceOrderSupply::whereIn('produce_order_id', $production_orders)->sum('quantity');
                $daily_product_in = \App\Models\ProduceOrderReception::whereIn('produce_order_id', $production_orders)->sum('quantity');
                $daily_product_out = \App\Models\SaleOrder::whereIn('product_sale_id', $product_sales)->sum('quantity');
                array_push($supply_in, $daily_supply_in);
                array_push($supply_out, $daily_supply_out);
                array_push($product_in, $daily_product_in);
                array_push($product_out, $daily_product_out);
            }
        @endphp
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="float-left"><i class="feather-lg" data-feather="codepen"></i> {{__('page.overview_supply')}}</h3>
                        <form action="" method="post" class="form-inline float-right">
                            @csrf
                            <input type="text" class="form-control mr-2" name="period" autocomplete="off" id="top_period">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> {{__('page.search')}}</button>
                        </form>
                    </div>

                    <div class="card-body">
                        <div id="supply_chart" style="height:400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="feather-lg" data-feather="box"></i> {{__('page.overview_product')}}</h3>
                    </div>
                    <div class="card-body">
                        <div id="product_chart" style="height:400px;"></div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('master/plugins/daterangepicker/jquery.daterangepicker.min.js')}}"></script>
    <script src="{{asset('master/plugins/echarts/echarts-en.js')}}"></script>
    <script>
        var legend_array = {!! json_encode([__('page.purchase'), __('page.sale')]) !!};
        var purchase = "{{__('page.purchase')}}";
        var sale = "{{__('page.sale')}}";
            
        // console.log(legend_array);
        var home_overview = function() {

            var home_chart = function() {
                if (typeof echarts == 'undefined') {
                    console.warn('Warning - echarts.min.js is not loaded.');
                    return;
                }

                // Define elements
                var supply_basic_element = document.getElementById('supply_chart');
                var product_basic_element = document.getElementById('product_chart');

                if (supply_basic_element) {

                    var area_supply = echarts.init(supply_basic_element);

                    area_supply.setOption({

                        color: ['#2ec7c9','#5ab1ef','#ff0000','#d87a80','#b6a2de'],

                        textStyle: {
                            fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                            fontSize: 13
                        },

                        animationDuration: 750,

                        grid: {
                            left: 0,
                            right: 40,
                            top: 35,
                            bottom: 0,
                            containLabel: true
                        },

                        
                        legend: {
                            data: [purchase, sale],
                            itemHeight: 8,
                            itemGap: 20
                        },

                        tooltip: {
                            trigger: 'axis',
                            backgroundColor: 'rgba(0,0,0,0.75)',
                            padding: [10, 15],
                            textStyle: {
                                fontSize: 13,
                                fontFamily: 'Roboto, sans-serif'
                            }
                        },

                        xAxis: [{
                            type: 'category',
                            boundaryGap: false,
                            data: {!! json_encode($key_array) !!},
                            axisLabel: {
                                color: '#333'
                            },
                            axisLine: {
                                lineStyle: {
                                    color: '#999'
                                }
                            },
                            splitLine: {
                                show: true,
                                lineStyle: {
                                    color: '#eee',
                                    type: 'dashed'
                                }
                            }
                        }],

                        yAxis: [{
                            type: 'value',
                            axisLabel: {
                                color: '#333'
                            },
                            axisLine: {
                                lineStyle: {
                                    color: '#999'
                                }
                            },
                            splitLine: {
                                lineStyle: {
                                    color: '#eee'
                                }
                            },
                            splitArea: {
                                show: true,
                                areaStyle: {
                                    color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
                                }
                            }
                        }],

                        series: [
                            {
                                name: purchase,
                                type: 'line',
                                data: {!! json_encode($supply_in) !!},
                                areaStyle: {
                                    normal: {
                                        opacity: 0.25
                                    }
                                },
                                smooth: true,
                                symbolSize: 7,
                                itemStyle: {
                                    normal: {
                                        borderWidth: 2
                                    }
                                }
                            },
                            {
                                name: sale,
                                type: 'line',
                                smooth: true,
                                symbolSize: 7,
                                itemStyle: {
                                    normal: {
                                        borderWidth: 2
                                    }
                                },
                                areaStyle: {
                                    normal: {
                                        opacity: 0.25
                                    }
                                },
                                data: {!! json_encode($supply_out) !!}
                            },
                        ]
                    });
                }

                if (product_basic_element) {

                    var area_product = echarts.init(product_basic_element);

                    area_product.setOption({

                        color: ['#2ec7c9','#5ab1ef','#ff0000','#d87a80','#b6a2de'],

                        textStyle: {
                            fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                            fontSize: 13
                        },

                        animationDuration: 750,

                        grid: {
                            left: 0,
                            right: 40,
                            top: 35,
                            bottom: 0,
                            containLabel: true
                        },

                        
                        legend: {
                            data: [purchase, sale],
                            itemHeight: 8,
                            itemGap: 20
                        },

                        tooltip: {
                            trigger: 'axis',
                            backgroundColor: 'rgba(0,0,0,0.75)',
                            padding: [10, 15],
                            textStyle: {
                                fontSize: 13,
                                fontFamily: 'Roboto, sans-serif'
                            }
                        },

                        xAxis: [{
                            type: 'category',
                            boundaryGap: false,
                            data: {!! json_encode($key_array) !!},
                            axisLabel: {
                                color: '#333'
                            },
                            axisLine: {
                                lineStyle: {
                                    color: '#999'
                                }
                            },
                            splitLine: {
                                show: true,
                                lineStyle: {
                                    color: '#eee',
                                    type: 'dashed'
                                }
                            }
                        }],

                        yAxis: [{
                            type: 'value',
                            axisLabel: {
                                color: '#333'
                            },
                            axisLine: {
                                lineStyle: {
                                    color: '#999'
                                }
                            },
                            splitLine: {
                                lineStyle: {
                                    color: '#eee'
                                }
                            },
                            splitArea: {
                                show: true,
                                areaStyle: {
                                    color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
                                }
                            }
                        }],

                        series: [
                            {
                                name: purchase,
                                type: 'line',
                                data: {!! json_encode($product_in) !!},
                                areaStyle: {
                                    normal: {
                                        opacity: 0.25
                                    }
                                },
                                smooth: true,
                                symbolSize: 7,
                                itemStyle: {
                                    normal: {
                                        borderWidth: 2
                                    }
                                }
                            },
                            {
                                name: sale,
                                type: 'line',
                                smooth: true,
                                symbolSize: 7,
                                itemStyle: {
                                    normal: {
                                        borderWidth: 2
                                    }
                                },
                                areaStyle: {
                                    normal: {
                                        opacity: 0.25
                                    }
                                },
                                data: {!! json_encode($product_out) !!}
                            },
                        ]
                    });
                }



                // Resize function
                var triggerChartResize = function() {
                    supply_basic_element && area_supply.resize();
                    product_basic_element && area_product.resize();
                };

                // On sidebar width change
                $(document).on('click', '.sidebar-toggle', function() {
                    setTimeout(function () {
                        triggerChartResize();
                    }, 0);
                });

                // On window resize
                var resizeCharts;
                window.onresize = function () {
                    clearTimeout(resizeCharts);
                    resizeCharts = setTimeout(function () {
                        triggerChartResize();
                    }, 200);
                };
            };

            return {
                init: function() {
                    home_chart();
                }
            }
        }();
       

        document.addEventListener('DOMContentLoaded', function() {
            home_overview.init();
        });
    </script>
    <script>
        $(document).ready(function(){
            $("#top_period").dateRangePicker({
                autoClose: false,
            });
        })
    </script>
@endsection
                    