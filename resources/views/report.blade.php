
<html>
    <head>
        <title>All Company Details</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
        <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
        
        <style>
           .companyDetails{
               width:500px;
               border-radius:20px;
               padding:50px;
               margin:3%;
               background-color:#f8f8f8;
               float:left;
               margin-top:20px;
           }
           h3{
               text-align:center;
           }
           #chartdiv {
                width: 50%;
                height: 400px;
                float:right;
                margin-bottom: 5%;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="companyDetails">
                    <h3>Company Details</h3><br>  
                    @foreach($allCompanyData as $company) 
                        <div class="form-group">
                            <label>Company Name :</label>
                            <label>{{$company['name']}}</label>
                        </div>
                        <div class="form-group">
                            <label>Company Email :</label>
                            <label>{{$company['email']}}</label>
                        </div>
                        <div class="form-group">
                            <label>Company Address :</label>
                            <label>{{$company['address']}}</label>
                        </div>
                    @endforeach
             </div>
             <div id="chartdiv"></div>
             <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Employee Name</th>
                        <th scope="col">Employee Email</th>
                        <th scope="col">Employee Age</th>
                        <th scope="col">Earning 2016</th>
                        <th scope="col">Earning 2017</th>
                        <th scope="col">Earning 2018</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allCompanyData as $employee)
                       @foreach($employee['employee'] as $employees)
                            <tr>
                                <td>{{$employees['name']}}</td>
                                <td>{{$employees['email']}}</td>
                                <td>{{$employees['age']}}</td>
                                <td>{{$employees['earning2016']}}</td>
                                <td>{{$employees['earning2017']}}</td>
                                <td>{{$employees['earning2018']}}</td>
                            </tr> 
                        @endforeach
                    @endforeach
                </tbody>
             </table>
             <div class="container-fluid p-5">
                 <div id="barchart_material" style="width: 100%; height: 500px;"></div>
             </div>
        </div>
        <script>
            am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("chartdiv", am4charts.XYChart);

            // Add data
            chart.data = [{
                    "earning": "2016",
                    "amount": {{$earning_2016}}
                }, {
                    "earning": "2017",
                    "amount": {{$earning_2017}}
                }, {
                    "earning": "2018",
                    "amount": {{$earning_2018}}
                }];

            // Create axes

            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "earning";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 30;

            categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
            if (target.dataItem && target.dataItem.index & 2 == 2) {
                return dy + 25;
            }
            return dy;
            });

            var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

            // Create series
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueY = "amount";
            series.dataFields.categoryX = "earning";
            series.name = "amount";
            series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
            series.columns.template.fillOpacity = .8;

            var columnTemplate = series.columns.template;
            columnTemplate.strokeWidth = 2;
            columnTemplate.strokeOpacity = 1;

            }); // end am4core.ready()
        </script>
    </body>
</html>
