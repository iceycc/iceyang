
	    // 基于准备好的dom，初始化echarts实例
	    var myChart = echarts.init(document.getElementById('main1'));
	    // 指定图表的配置项和数据
	    myChart.title = '嵌套环形图';
	    var option = {
	        tooltip: {
	            trigger: 'item',
	            formatter: "{a} <br/>{b}: {c} ({d}%)"
	        },
	        legend: {
	            orient: 'vertical',
	            x: 'left',
	            data:['站内文章','分类','评论','草稿','待审核','待审核评论','带发布文章','点赞','收藏','其他']
	        },
	        series: [
	            {
	                name:'访问来源',
	                type:'pie',
	                selectedMode: 'single',
	                radius: [0, '30%'],

	                label: {
	                    normal: {
	                        position: 'inner'
	                    }
	                },
	                labelLine: {
	                    normal: {
	                        show: false
	                    }
	                },
	                data:[
	                    {value:335, name:'站内文章', selected:true},
	                    {value:679, name:'分类'},
	                    {value:1548, name:'评论'}
	                ]
	            },
	            {
	                name:'访问来源',
	                type:'pie',
	                radius: ['40%', '55%'],
	                label: {
	                    normal: {
	                        formatter: '{a|{a}}{abg|}\n{hr|}\n  {b|{b}：}{c}  {per|{d}%}  ',
	                        backgroundColor: '#eee',
	                        borderColor: '#aaa',
	                        borderWidth: 1,
	                        borderRadius: 4,
	                        // shadowBlur:3,
	                        // shadowOffsetX: 2,
	                        // shadowOffsetY: 2,
	                        // shadowColor: '#999',
	                        // padding: [0, 7],
	                        rich: {
	                            a: {
	                                color: '#999',
	                                lineHeight: 22,
	                                align: 'center'
	                            },
	                            // abg: {
	                            //     backgroundColor: '#333',
	                            //     width: '100%',
	                            //     align: 'right',
	                            //     height: 22,
	                            //     borderRadius: [4, 4, 0, 0]
	                            // },
	                            hr: {
	                                borderColor: '#aaa',
	                                width: '100%',
	                                borderWidth: 0.5,
	                                height: 0
	                            },
	                            b: {
	                                fontSize: 16,
	                                lineHeight: 33
	                            },
	                            per: {
	                                color: '#eee',
	                                backgroundColor: '#334455',
	                                padding: [2, 4],
	                                borderRadius: 2
	                            }
	                        }
	                    }
	                },
	                data:[
	                    {value:335, name:'站内文章'},
	                    {value:310, name:'草稿'},
	                    {value:234, name:'待审核'},
	                    {value:135, name:'待审核评论'},
	                    {value:1048, name:'带发布文章'},
	                    {value:251, name:'点赞'},
	                    {value:147, name:'收藏'},
	                    {value:102, name:'其他'}
	                ]
	            }
	        ]
	    };
	    // 使用刚指定的配置项和数据显示图表。
	    myChart.setOption(option);
