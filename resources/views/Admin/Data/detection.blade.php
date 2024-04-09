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
    <section class="line-3">
        <div class="line-3-float">
            <div class="line-3-float-search">
                <div class="line-3-search">
                    <div class="all-title">泛检测相关搜索量趋势:</div>
                    <div class="search-keywords-tu" id="detection_gather">
                        <script type="text/javascript">
                            // 基于准备好的dom，初始化echarts实例
                            var myChart = echarts.init(document.getElementById('detection_gather'));
                            var detection_query_times=<?php echo stripslashes($detection_query_times);?>;
                            var detection_query_total=<?php echo stripslashes($detection_query_total);?>;
                            // 指定图表的配置项和数据
                            var option = {
                                xAxis: {
                                    type: 'category',
                                    data: detection_query_times,
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
                                        data: detection_query_total,
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


        </div>

    </section>
</div>