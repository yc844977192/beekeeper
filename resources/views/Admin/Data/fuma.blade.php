<script src="https://cdn.jsdelivr.net/npm/echarts@5.4.2/dist/echarts.min.js"></script>
<style>
    .float-clear{
        clear: both;
    }
    .line-2-search{
        float: left;
        width: 45%;
        height:240px;
        margin-left: 20px;
    }
</style>
<div><span>查询数据联系上海分公司</span></div>
<div class="search-keywords-tu" id="search-keywords-tu" style="width: 30%;height:340px;float: left">
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('search-keywords-tu'));
        var data1=<?php echo stripslashes($data);?>;
        // 指定图表的配置项和数据
        option = {
            title: {
                text: '地域',
                subtext: '三方数据',
                left: 'center'
            },
            tooltip: {
                trigger: 'item'
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
<div class="search-keywords-tu" id="search-keywords-tu1" style="width: 30%;height:340px;float: left">
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('search-keywords-tu1'));
        var data1=<?php echo stripslashes($data2);?>;
        // 指定图表的配置项和数据
        option = {
            title: {
                text: '项目类型',
                subtext: '三方数据',
                left: 'center'
            },
            tooltip: {
                trigger: 'item'
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
<div class="search-keywords-tu" id="search-keywords-tu2" style="width: 30%;height:340px;float: left">
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('search-keywords-tu2'));
        var data1=<?php echo stripslashes($data3);?>;
        // 指定图表的配置项和数据
        option = {
            title: {
                text: '项目类型',
                subtext: '三方数据',
                left: 'center'
            },
            tooltip: {
                trigger: 'item'
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
<div class="float-clear"></div>
