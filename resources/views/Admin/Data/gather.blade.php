<script src="/static/js/echarts.js"></script>
<div class="line-2-search">
    @foreach($data as $k=>$v)
        <div>
                <div class="search-keywords-tu" id="search-keywords-tu{{$k}}" style="width: 30%;height:200px;float: left;">
                    {{--<div id="main" style="width: 100%;height:340px;"></div>--}}
                </div>

        </div>

    @endforeach
        <script type="text/javascript">
            // 一次性初始化所有 ECharts 实例
            var myCharts = [];
                    @foreach($data as $k=>$v)
            var time = <?php echo stripslashes($v['time']); ?>;
            var data = <?php echo stripslashes($v['data']); ?>;
            var chartId = 'search-keywords-tu{{$k}}';
            myCharts.push({
                id: chartId,
                chart: echarts.init(document.getElementById(chartId)),
                time: time,
                data: data,
                title: '{{$k}}'
            });
            @endforeach
            // 批量设置图表的配置项和数据
            myCharts.forEach(function (item) {
                var option = {
                    title: {
                        text: item.title,
                        subtext: '检测数据',
                        left: 'center'
                    },
                    xAxis: {
                        type: 'category',
                        data: item.time
                    },
                    yAxis: {
                        type: 'value'
                    },
                    tooltip: {
                        trigger: 'axis',
                        formatter: function (params) {
                            var result = params[0].name;
                            for (var i = 0, l = params.length; i < l; i++) {
                                result += '<br/>' + params[i].value;
                            }
                            return result;
                        }
                    },
                    series: [
                        {
                            data: item.data,
                            type: 'line',
                            smooth: true
                        }
                    ]
                };
                item.chart.setOption(option);
            });
        </script>
        <div style="clear: both"></div>
</div>
