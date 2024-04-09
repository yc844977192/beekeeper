<style>
    .search-keywords{
        width: 100%;height: 20px;
    }
    .search-keywords div{
        float: left;
        margin-right: 30px;
    }
    .clear-float{
        clear: both;
    }
    .search-keywords-tu{

        width: 100%;
        height: 300px;
        padding: 0;
    }
    .line-2 {
        width: 100%;
    }
    .line-2-search {
        float: left;
        margin-right: 30px;
        width: 47%
    }
    line-3{
        width: 100%;
    }
    .line-3-float{
        float: left;
        margin-right: 30px;
        width: 100%
    }
    .line-3-float-search{
        width: 100%;
    }
    .line-3-search {
        float: left;
        margin-right: 30px;
        width: 100%
    }
    .line-3-float-keywords{
        width: 100%;
        height: 500px;
        /*display: flex;*/
        flex-direction: column;

    }
    .detail{
        float:right;
    }
    .all-title{
        font-size: 18px;
        margin-top: 5px;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.2/dist/echarts.min.js"></script>
<div>
    <section class="line-2">
        <div >
            <div class="all-title">搜索引擎关键词数据:</div>
                <div class="search-keywords">
                    <div>搜索关键词今日新增: {{$rubbings_list['query_simple_count']}}条</div>
                    <div>合计关键词: {{$rubbings_list['query_total']}}条</div>
                </div>
                <div class="clear-float"></div>
            <div class="search-keywords-tu" id="search-keywords-tu">
                {{--<div id="main" style="width: 100%;height:340px;"></div>--}}
                <script type="text/javascript">
                    // 基于准备好的dom，初始化echarts实例
                    var myChart = echarts.init(document.getElementById('search-keywords-tu'));
                    var time=<?php echo stripslashes($rubbings_query_times);?>;
                    var data=<?php echo stripslashes($rubbings_query_total);?>;
                    // 指定图表的配置项和数据
                    var option = {
                        xAxis: {
                            type: 'category',
                            data: time
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [
                            {
                                data: data,
                                type: 'line',
                                smooth: true,
                            }
                        ],
                        tooltip: {
                            trigger: 'axis',
                            formatter: function (params) {
                                var result = params[0].name;
                                for (var i = 0, l = params.length; i < l; i++) {
                                    result += '<br/>'+ params[i].value;
                                }
                                return result;
                            }
                        },


                    };

                    // 使用刚指定的配置项和数据显示图表。
                    myChart.setOption(option);
                </script>
            </div>
        </div>

        <div>
            <div style="margin-top: 20px;"><span class="all-title">检测业务搜索量趋势:</span>
                {{--<span class="detail">详情>></span>--}}
            </div>
            <div class="search-keywords-tu" id="detection"></div>
            <script type="text/javascript">
                // 基于准备好的dom，初始化echarts实例
                var myChart = echarts.init(document.getElementById('detection'));
                var time_detection=<?php echo stripslashes($detection_query_times);?>;
                var data_detection=<?php echo stripslashes($detection_query_total);?>;
                var params="shuliang";
                // 指定图表的配置项和数据
                var option = {
                    xAxis: {
                        type: 'category',
                        data: time_detection
                    },
                    yAxis: {
                        type: 'value'
                    },
                    tooltip: {
                        trigger: 'axis',
                        formatter: function (params) {
                            var result = params[0].name;
                            for (var i = 0, l = params.length; i < l; i++) {
                                result += '<br/>'+ params[i].value;
                            }
                            return result;
                        }
                    },
                    series: [
                        {
                            data: data_detection,
                            type: 'line',
                            smooth: true
                        }
                    ]
                };

                // 使用刚指定的配置项和数据显示图表。
                myChart.setOption(option);
            </script>
        </div>
    </section>
    <section class="line-3">
        <div class="line-3-float">
            <div class="line-3-float-search">
                <div class="line-3-search">
                    <div class="all-title">泛检测相关搜索量趋势:</div>
                    <div class="search-keywords-tu" id="detection_gather">
                        <script type="text/javascript">
                            // 基于准备好的dom，初始化echarts实例
                            var myChart = echarts.init(document.getElementById('detection_gather'));
                            var time_detection_gather=<?php echo stripslashes($detection_gather_query_times);?>;
                            var data_detection_gather=<?php echo stripslashes($detection_gather_query_total);?>;
                            // 指定图表的配置项和数据
                            var option = {
                                xAxis: {
                                    type: 'category',
                                    data: time_detection_gather,
                                    name: 'x',
                                    splitLine: { show: false },
                                },
                                yAxis: {
                                    type: 'value',
                                    minorSplitLine: {
                                        show: true
                                    }
                                },
                                tooltip: {
                                    trigger: 'axis',
                                    formatter: function (params) {
                                        var result = params[0].name;
                                        for (var i = 0, l = params.length; i < l; i++) {
                                            result += '<br/>'+ params[i].value;
                                        }
                                        return result;
                                    }
                                },
                                series: [
                                    {
                                        data: data_detection_gather,
                                        type: 'line',
                                        smooth: true
                                    }
                                ]
                            };

                            // 使用刚指定的配置项和数据显示图表。
                            myChart.setOption(option);
                        </script>
                    </div>
                </div>
                <div class="clear-float" ></div>
            </div>
            <div>
                <div class="line-3-search">
                    <div class="all-title">检测行业三方竞品公司数据:</div>
                    <div class="search-keywords">
                        <div>今日新增三方数据: {{$tripartite_list['query_simple_count']}}条</div>
                        <div>合计三方数据: {{$tripartite_list['query_count']}}条</div>
                    </div>
                    <div class="search-keywords-tu" id="tripartite"></div>
                    <script type="text/javascript">
                        // 基于准备好的dom，初始化echarts实例
                        var myChart = echarts.init(document.getElementById('tripartite'));
                        var time_tripartite=<?php echo stripslashes($tripartite_query_times);?>;
                        var data_tripartite=<?php echo stripslashes($tripartite_query_total);?>;
                        // 指定图表的配置项和数据
                        var option = {
                            xAxis: {
                                type: 'category',
                                data: time_tripartite
                            },
                            yAxis: {
                                type: 'value'
                            },
                            tooltip: {
                                trigger: 'axis',
                                formatter: function (params) {
                                    var result = params[0].name;
                                    for (var i = 0, l = params.length; i < l; i++) {
                                        result += '<br/>'+ params[i].value;
                                    }
                                    return result;
                                }
                            },
                            series: [
                                {
                                    data: data_tripartite,
                                    type: 'line',
                                    smooth: true
                                }
                            ]
                        };

                        // 使用刚指定的配置项和数据显示图表。
                        myChart.setOption(option);
                    </script>
                </div>
                <div class="clear-float"></div>
            </div>
            <div>
                <div class="line-3-search">
                    <div class="all-title">山东省项目备案赋码数据</div>
                    {{--<button style="float: right;">数据查询</button>--}}
                    <div class="search-keywords">
                        <div>今日新增赋码数据: {{$fuma_list['query_simple_count']}}条</div>
                        <div>合计赋码数据: {{$fuma_list['query_count']}}条</div>
                    </div>
                    <div class="search-keywords-tu" id="fuma"></div>
                    <script type="text/javascript">
                        // 基于准备好的dom，初始化echarts实例
                        var myChart = echarts.init(document.getElementById('fuma'));
                        var time_fuma=<?php echo stripslashes($fuma_query_times);?>;
                        var data_fuma=<?php echo stripslashes($fuma_query_total);?>;
                        // 指定图表的配置项和数据
                        var option = {
                            xAxis: {
                                type: 'category',
                                data: time_fuma
                            },
                            yAxis: {
                                type: 'value'
                            },
                            tooltip: {
                                trigger: 'axis',
                                formatter: function (params) {
                                    var result = params[0].name;
                                    for (var i = 0, l = params.length; i < l; i++) {
                                        result += '<br/>'+ params[i].value;
                                    }
                                    return result;
                                }
                            },
                            series: [
                                {
                                    data: data_fuma,
                                    type: 'line',
                                    smooth: true
                                }
                            ]
                        };

                        // 使用刚指定的配置项和数据显示图表。
                        myChart.setOption(option);
                    </script>
                </div>
                <div class="clear-float"></div>
            </div>
            <div>
                <div class="line-3-search">

                    <div class="all-title">山东省招投标数据：</div>
                    {{--<button style="float: right;">数据查询</button>--}}
                    <div class="search-keywords">
                        <div>今日新增投标数据: {{$bid_list['query_simple_count']}}条</div>
                        <div>合计投标数据: {{$bid_list['query_count']}}条</div>
                    </div>
                    <div class="search-keywords-tu" id="bid"></div>
                    <script type="text/javascript">
                        // 基于准备好的dom，初始化echarts实例
                        var myChart = echarts.init(document.getElementById('bid'));
                        var time_bid=<?php echo stripslashes($bid_query_times);?>;
                        var data_bid=<?php echo stripslashes($bid_query_total);?>;
                        // 指定图表的配置项和数据
                        var option = {
                            xAxis: {
                                type: 'category',
                                data: time_bid
                            },
                            yAxis: {
                                type: 'value'
                            },
                            tooltip: {
                                trigger: 'axis',
                                formatter: function (params) {
                                    var result = params[0].name;
                                    for (var i = 0, l = params.length; i < l; i++) {
                                        result += '<br/>'+ params[i].value;
                                    }
                                    return result;
                                }
                            },
                            series: [
                                {
                                    data: data_bid,
                                    type: 'line',
                                    smooth: true
                                }
                            ]
                        };

                        // 使用刚指定的配置项和数据显示图表。
                        myChart.setOption(option);
                    </script>
                </div>
                <div class="clear-float"></div>
            </div>
        </div>
        {{--<div class="line-3-float">--}}
            {{--<div><span class="all-title">追踪的关键词/集合:</span>--}}
                {{--<span class="detail">详情>></span>--}}
            {{--</div>--}}
            {{--<div class="line-3-float-keywords">--}}
                {{--<div style="flex: 1; position: relative;">--}}
                    {{--<sapn>词：</sapn>--}}
                    {{--@foreach($data_keywords as $k=>$v)--}}
                        {{--<button style="margin-top: 5px;">{{$v['keywords']}}</button>--}}
                    {{--@endforeach--}}
                    {{--<button style="position: absolute; bottom: 0; right: 0;">更多</button>--}}
                {{--</div>--}}
                {{--<div style="flex: 1; position: relative;margin-top: 10px;">--}}
                    {{--<sapn>集合：</sapn>--}}
                    {{--@foreach($data_keywords_list as $k=>$v)--}}
                        {{--<button style="margin-top: 5px;">{{$v['keywords']}}</button>--}}
                    {{--@endforeach--}}
                    {{--<button style="position: absolute; bottom: 0; right: 0;">更多</button>--}}
                {{--</div>--}}
            {{--</div>--}}

        {{--</div>--}}
        {{--<div class="clear-float"></div>--}}
    </section>
</div>