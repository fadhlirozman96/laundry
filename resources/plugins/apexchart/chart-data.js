'use strict';

$(document).ready(function() {

	function generateData(baseval, count, yrange) {
		var i = 0;
		var series = [];
		while (i < count) {
			var x = Math.floor(Math.random() * (750 - 1 + 1)) + 1;;
			var y = Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min;
			var z = Math.floor(Math.random() * (75 - 15 + 1)) + 15;

			series.push([x, y, z]);
			baseval += 86400000;
			i++;
		}
		return series;
	}


	// Column chart
    if($('#sales_chart').length > 0 ){
    	var columnCtx = document.getElementById("sales_chart"),
    	columnConfig = {
    		colors: ['#7638ff', '#fda600'],
    		series: [
    			{
    			name: "Received",
    			type: "column",
    			data: [70, 150, 80, 180, 150, 175, 201, 60, 200, 120, 190, 160, 50]
    			},
    			{
    			name: "Pending",
    			type: "column",
    			data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16, 80]
    			}
    		],
    		chart: {
    			type: 'bar',
    			fontFamily: 'Poppins, sans-serif',
    			height: 350,
    			toolbar: {
    				show: false
    			}
    		},
    		plotOptions: {
    			bar: {
    				horizontal: false,
    				columnWidth: '60%',
    				endingShape: 'rounded'
    			},
    		},
    		dataLabels: {
    			enabled: false
    		},
    		stroke: {
    			show: true,
    			width: 2,
    			colors: ['transparent']
    		},
    		xaxis: {
    			categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    		},
    		yaxis: {
    			title: {
    				text: '$ (thousands)'
    			}
    		},
    		fill: {
    			opacity: 1
    		},
    		tooltip: {
    			y: {
    				formatter: function (val) {
    					return "$ " + val + " thousands"
    				}
    			}
    		}
    	};
    	var columnChart = new ApexCharts(columnCtx, columnConfig);
    	columnChart.render();
    }

	//Pie Chart
    if($('#invoice_chart').length > 0 ){
    	var pieCtx = document.getElementById("invoice_chart"),
    	pieConfig = {
    		colors: ['#7638ff', '#ff737b', '#fda600', '#1ec1b0'],
    		series: [55, 40, 20, 10],
    		chart: {
    			fontFamily: 'Poppins, sans-serif',
    			height: 350,
    			type: 'donut',
    		},
    		labels: ['Paid', 'Unpaid', 'Overdue', 'Draft'],
    		legend: {show: false},
    		responsive: [{
    			breakpoint: 480,
    			options: {
    				chart: {
    					width: 200
    				},
    				legend: {
    					position: 'bottom'
    				}
    			}
    		}]
    	};
    	var pieChart = new ApexCharts(pieCtx, pieConfig);
    	pieChart.render();
	}
	
	// Simple Line
    if($('#s-line').length > 0 ){
    var sline = {
      chart: {
        height: 350,
        type: 'line',
        zoom: {
          enabled: false
        },
        toolbar: {
          show: false,
        }
      },
      // colors: ['#4361ee'],
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'straight'
      },
      series: [{
        name: "Desktops",
        data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
      }],
      title: {
        text: 'Product Trends by Month',
        align: 'left'
      },
      grid: {
        row: {
          colors: ['#f1f2f3', 'transparent'], // takes an array which will be repeated on columns
          opacity: 0.5
        },
      },
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
      }
    }

    var chart = new ApexCharts(
      document.querySelector("#s-line"),
      sline
    );

    chart.render();
    }


// Simple Line Area
 if($('#s-line-area').length > 0 ){
var sLineArea = {
    chart: {
        height: 350,
        type: 'area',
        toolbar: {
          show: false,
        }
    },
    // colors: ['#4361ee', '#888ea8'],
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth'
    },
    series: [{
        name: 'series1',
        data: [31, 40, 28, 51, 42, 109, 100]
    }, {
        name: 'series2',
        data: [11, 32, 45, 32, 34, 52, 41]
    }],

    xaxis: {
        type: 'datetime',
        categories: ["2018-09-19T00:00:00", "2018-09-19T01:30:00", "2018-09-19T02:30:00", "2018-09-19T03:30:00", "2018-09-19T04:30:00", "2018-09-19T05:30:00", "2018-09-19T06:30:00"],                
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy HH:mm'
        },
    }
}

var chart = new ApexCharts(
    document.querySelector("#s-line-area"),
    sLineArea
);

chart.render();
}

// Simple Column
if($('#s-col').length > 0 ){
var sCol = {
    chart: {
        height: 350,
        type: 'bar',
        toolbar: {
          show: false,
        }
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'  
        },
    },
    // colors: ['#888ea8', '#4361ee'],
    dataLabels: {
        enabled: false
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    series: [{
        name: 'Net Profit',
        data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
    }, {
        name: 'Revenue',
        data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
    }],
    xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
    },
    yaxis: {
        title: {
            text: '$ (thousands)'
        }
    },
    fill: {
        opacity: 1

    },
    tooltip: {
        y: {
            formatter: function (val) {
                return "$ " + val + " thousands"
            }
        }
    }
}

var chart = new ApexCharts(
    document.querySelector("#s-col"),
    sCol
);

chart.render();
}


// Simple Column Stacked
if($('#s-col-stacked').length > 0 ){
var sColStacked = {
    chart: {
        height: 350,
        type: 'bar',
        stacked: true,
        toolbar: {
          show: false,
        }
    },
    // colors: ['#4361ee', '#888ea8', '#e3e4eb', '#d3d3d3'],
    responsive: [{
        breakpoint: 480,
        options: {
            legend: {
                position: 'bottom',
                offsetX: -10,
                offsetY: 0
            }
        }
    }],
    plotOptions: {
        bar: {
            horizontal: false,
        },
    },
    series: [{
        name: 'PRODUCT A',
        data: [44, 55, 41, 67, 22, 43]
    },{
        name: 'PRODUCT B',
        data: [13, 23, 20, 8, 13, 27]
    },{
        name: 'PRODUCT C',
        data: [11, 17, 15, 15, 21, 14]
    },{
        name: 'PRODUCT D',
        data: [21, 7, 25, 13, 22, 8]
    }],
    xaxis: {
        type: 'datetime',
        categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT', '01/05/2011 GMT', '01/06/2011 GMT'],
    },
    legend: {
        position: 'right',
        offsetY: 40
    },
    fill: {
        opacity: 1
    },
}

var chart = new ApexCharts(
    document.querySelector("#s-col-stacked"),
    sColStacked
);

chart.render();
}

// Simple Bar
if($('#s-bar').length > 0 ){
var sBar = {
    chart: {
        height: 350,
        type: 'bar',
        toolbar: {
          show: false,
        }
    },
    // colors: ['#4361ee'],
    plotOptions: {
        bar: {
            horizontal: true,
        }
    },
    dataLabels: {
        enabled: false
    },
    series: [{
        data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
    }],
    xaxis: {
        categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy', 'France', 'Japan', 'United States', 'China', 'Germany'],
    }
}

var chart = new ApexCharts(
    document.querySelector("#s-bar"),
    sBar
);

chart.render();
}

// Mixed Chart
if($('#mixed-chart').length > 0 ){
var options = {
  chart: {
    height: 350,
    type: 'line',
    toolbar: {
      show: false,
    }
  },
  // colors: ['#4361ee', '#888ea8'],
  series: [{
    name: 'Website Blog',
    type: 'column',
    data: [440, 505, 414, 671, 227, 413, 201, 352, 752, 320, 257, 160]
  }, {
    name: 'Social Media',
    type: 'line',
    data: [23, 42, 35, 27, 43, 22, 17, 31, 22, 22, 12, 16]
  }],
  stroke: {
    width: [0, 4]
  },
  title: {
    text: 'Traffic Sources'
  },
  labels: ['01 Jan 2001', '02 Jan 2001', '03 Jan 2001', '04 Jan 2001', '05 Jan 2001', '06 Jan 2001', '07 Jan 2001', '08 Jan 2001', '09 Jan 2001', '10 Jan 2001', '11 Jan 2001', '12 Jan 2001'],
  xaxis: {
    type: 'datetime'
  },
  yaxis: [{
    title: {
      text: 'Website Blog',
    },

  }, {
    opposite: true,
    title: {
      text: 'Social Media'
    }
  }]

}

var chart = new ApexCharts(
  document.querySelector("#mixed-chart"),
  options
);

chart.render();
}

// Donut Chart

if($('#donut-chart').length > 0 ){
var donutChart = {
    chart: {
        height: 350,
        type: 'donut',
        toolbar: {
          show: false,
        }
    },
    // colors: ['#4361ee', '#888ea8', '#e3e4eb', '#d3d3d3'],
    series: [44, 55, 41, 17],
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                width: 200
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
}

var donut = new ApexCharts(
    document.querySelector("#donut-chart"),
    donutChart
);

donut.render();
}

// Radial Chart
if($('#radial-chart').length > 0 ){
var radialChart = {
    chart: {
        height: 350,
        type: 'radialBar',
        toolbar: {
          show: false,
        }
    },
    // colors: ['#4361ee', '#888ea8', '#e3e4eb', '#d3d3d3'],
    plotOptions: {
        radialBar: {
            dataLabels: {
                name: {
                    fontSize: '22px',
                },
                value: {
                    fontSize: '16px',
                },
                total: {
                    show: true,
                    label: 'Total',
                    formatter: function (w) {
                        return 249
                    }
                }
            }
        }
    },
    series: [44, 55, 67, 83],
    labels: ['Apples', 'Oranges', 'Bananas', 'Berries'],    
}

var chart = new ApexCharts(
    document.querySelector("#radial-chart"),
    radialChart
);

chart.render();
}	
	
// Skip rendering for sales_charts - handled by custom script in index.blade.php
// This prevents duplicate chart rendering
// DISABLED: Chart is now handled by custom JavaScript in index.blade.php
/*
if($('#sales_charts').length > 0) {
	// Get chart data from data attributes
	var chartElement = document.querySelector("#sales_charts");
	if (!chartElement) {
		return;
	}
	
	// Mark as initialized to prevent duplicate rendering
	$('#sales_charts').addClass('chart-custom-initialized');
	
	// Destroy existing chart if it exists
	try {
		var existingChart = ApexCharts.getChartByID('sales_charts');
		if (existingChart) {
			existingChart.destroy();
		}
	} catch(e) {
		// Ignore if chart doesn't exist
	}
	
	var chartMonths = chartElement.getAttribute('data-chart-months');
	var chartData = chartElement.getAttribute('data-chart-data');
	
	// Default values
	var defaultMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
	var defaultData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
	
	// Parse JSON data with error handling
	var months = defaultMonths;
	var salesData = defaultData;
	
	try {
		if (chartMonths && chartMonths.trim() !== '') {
			var parsedMonths = JSON.parse(chartMonths);
			if (Array.isArray(parsedMonths) && parsedMonths.length > 0) {
				months = parsedMonths;
			}
		}
	} catch(e) {
		console.warn('Error parsing chart months:', e);
		months = defaultMonths;
	}
	
	try {
		if (chartData && chartData.trim() !== '') {
			var parsedData = JSON.parse(chartData);
			if (Array.isArray(parsedData) && parsedData.length > 0) {
				salesData = parsedData;
			}
		}
	} catch(e) {
		console.warn('Error parsing chart data:', e);
		salesData = defaultData;
	}
	
	// Ensure all values are positive (no negative values) and are numbers
	if (Array.isArray(salesData)) {
		salesData = salesData.map(function(value) {
			var numValue = parseFloat(value) || 0;
			return Math.max(0, numValue);
		});
	} else {
		salesData = defaultData;
	}
	
	// Ensure we have exactly 12 months of data
	if (!Array.isArray(months) || months.length !== 12) {
		months = defaultMonths;
	}
	if (!Array.isArray(salesData) || salesData.length !== 12) {
		var newData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
		if (Array.isArray(salesData)) {
			for (var i = 0; i < Math.min(salesData.length, 12); i++) {
				newData[i] = parseFloat(salesData[i]) || 0;
			}
		}
		salesData = newData;
	}
	
	// Calculate max value for y-axis (add 20% padding)
	var maxValue = Math.max(...salesData);
	var yAxisMax = maxValue > 0 ? Math.ceil(maxValue * 1.2) : 100;
	
	var options = {
		series: [{
			name: 'Sales',
			data: salesData
		}],
		colors: ['#28C76F'],
		chart: {
			type: 'bar',
			height: 320,
			id: 'sales_charts',
			zoom: {
				enabled: false
			},
			toolbar: {
				show: false
			}
		},
		responsive: [{
			breakpoint: 280,
			options: {
				legend: {
					position: 'bottom',
					offsetY: 0
				}
			}
		}],
		plotOptions: {
			bar: {
				horizontal: false,
				borderRadius: 4,
				borderRadiusApplication: "end",
				columnWidth: '60%',
				distributed: false
			},
		},
		dataLabels: {
			enabled: false
		},
		yaxis: {
			min: 0,
			max: yAxisMax,
			tickAmount: 5,
			labels: {
				formatter: function(val) {
					return "MYR " + val.toFixed(0);
				}
			}
		},
		xaxis: {
			categories: months,
			labels: {
				rotate: 0
			}
		},
		legend: {
			show: true,
			position: 'top',
			horizontalAlign: 'right'
		},
		fill: {
			opacity: 1,
			type: 'solid'
		},
		tooltip: {
			y: {
				formatter: function(val) {
					return "MYR " + val.toFixed(2);
				}
			}
		}
	};

	var chart = new ApexCharts(document.querySelector("#sales_charts"), options);
	chart.render();
}
*/

    if($('#sales-analysis').length > 0 ){
    var options = {
      series: [{
        name: "Sales Analysis",
        data: [25, 30, 18, 15, 22, 20, 30, 20, 22, 18, 15, 20]
    }],
      chart: {
      height: 273,
      type: 'area',
      zoom: {
        enabled: false
      }
    },
    colors: ['#0067e2'],
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'straight'
    },
    title: {
      text: '',
      align: 'left'
    },
    // grid: {
    //   row: {
    //     colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
    //     opacity: 0.5
    //   },
    // },
    xaxis: {
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
    },
    yaxis: {
      min: 10,
      max: 60,
      tickAmount: 5,
          labels: {
            formatter: (val) => {
              return val / 1 + 'K'
            }
          }
        },
        legend: {
          position: 'top',
          horizontalAlign: 'left'
        }
    };

    var chart = new ApexCharts(document.querySelector("#sales-analysis"), options);
    chart.render();
  }


  
});