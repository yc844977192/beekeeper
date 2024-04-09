<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.2/dist/echarts.min.js"></script>
<style>
    .float-clear{
        clear: both;
    }
    .line-2-search{
        float: left;
        width: 100%;
        height:240px;
        margin-left: 20px;
    }
</style>
<div class="search-keywords-tu" id="search-keywords-tu" style="width: 45%;height:340px;float: left">
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('search-keywords-tu'));
        var data1=<?php echo stripslashes($data);?>;
        // 指定图表的配置项和数据
        option = {
            title: {
                text: '按照地域分布',
                subtext: '三方数据',
                left: 'center'
            },
            tooltip: {
                trigger: 'item'
            },
            legend: {
                orient: 'vertical',
                left: 'left'
            },
            series: [
                {
                    name: 'Access From',
                    type: 'pie',
                    radius: '50%',
                    data: data1,
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
</div>

<div class="search-keywords-tu" id="search-keywords-yewu" style="width: 45%;height:340px;float: left">
    {{--<div id="main" style="width: 100%;height:340px;"></div>--}}
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('search-keywords-yewu'));
        var data2=<?php echo stripslashes($data2);?>;
        // 指定图表的配置项和数据
        option = {
            title: {
                text: '按照业务分布',
                subtext: '三方数据',
                left: 'center'
            },
            tooltip: {
                trigger: 'item'
            },
            legend: {
                orient: 'vertical',
                left: 'left'
            },
            series: [
                {
                    name: 'Access From',
                    type: 'pie',
                    radius: '50%',
                    data:data2,
                    emphasis: {
                        itemStyle: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
</div>

<div class="line-2-search">
    <div class="all-title">上海</div>
    <div class="search-keywords-tu" id="search-keywords-tu2" style="width: 100%;height:240px;">
        {{--<div id="main" style="width: 100%;height:340px;"></div>--}}
        <script type="text/javascript">
            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById('search-keywords-tu2'));
            var time=<?php echo stripslashes($times);?>;
            var data_sh=<?php echo stripslashes($data_sh);?>;
            // 指定图表的配置项和数据
            var option = {
                xAxis: {
                    type: 'category',
                    data: time,
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
                        data: data_sh,
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
<div class="line-2-search">
    <div class="all-title">南京</div>
    <div class="search-keywords-tu" id="search-keywords-tu4" style="width: 100%;height:240px;">
        <script type="text/javascript">
            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById('search-keywords-tu4'));
            var time=<?php echo stripslashes($times);?>;
            var data_nj=<?php echo stripslashes($data_nj);?>;
            // 指定图表的配置项和数据
            var option = {
                xAxis: {
                    type: 'category',
                    data: time,
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
                        data: data_nj,
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
<div class="line-2-search">
    <div class="all-title">北京</div>
    <div class="search-keywords-tu" id="search-keywords-tu6" style="width: 100%;height:240px;">
        <script type="text/javascript">
            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById('search-keywords-tu6'));
            var time=<?php echo stripslashes($times);?>;
            var data_jn=<?php echo stripslashes($data_jn);?>;
            // 指定图表的配置项和数据
            var option = {
                xAxis: {
                    type: 'category',
                    data: time,
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
                        data: data_jn,
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
<div class="line-2-search">
    <div class="all-title">山东</div>
    <div class="search-keywords-tu" id="search-keywords-tu8" style="width: 100%;height:240px;">
        <script type="text/javascript">
            // 基于准备好的dom，初始化echarts实例
            var myChart = echarts.init(document.getElementById('search-keywords-tu8'));
            var time=<?php echo stripslashes($times);?>;
            var data_gz=<?php echo stripslashes($data_gz);?>;
            // 指定图表的配置项和数据
            var option = {
                xAxis: {
                    type: 'category',
                    data: time,
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
                        data: data_gz,
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