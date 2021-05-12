
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
           .company-form{
               width:500px;
               border-radius:20px;
               padding:50px;
               margin:3%;
               background-color:#f8f8f8;
               float:left;
               margin-top:20px;
           }
           .form-control{
               border-radius:30px;
           }
           h3{
               text-align:center;
           }
           #chartdiv {
                width: 50%;
                height: 450px;
                float:right;
                margin-top:6%;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="company-form">
                @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
                <h3>Add/Update Company Details</h3><br>   
                <form method="POST" action="add-company" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Company Name</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label>Company Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label>Company Address</label>
                        <textarea class="form-control" name="address" id="address" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Employee Details(csv file)</label>
                        <input type="file" class="form-control" name="csvfile" accept=".csv" required/>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit"/>
                    </div>
                </form>
             </div>
             <div id="chartdiv"></div>
             <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Company Name</th>
                        <th scope="col">Company Email</th>
                        <th scope="col">Company Address</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($companyDetail as $companyDetails)
                        <tr>
                            <td>{{$companyDetails['name']}}</td>
                            <td>{{$companyDetails['email']}}</td>
                            <td>{{$companyDetails['address']}}</td>
                            <td> <a href="{{ route('viewreport', $companyDetails['id']) }}" >View Reports</a></td>
                        </tr> 
                    @endforeach
                </tbody>
             </table>
        </div>
        <script>
            am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("chartdiv", am4charts.XYChart);

            // Add data
            chart.data = [
                @foreach($totalEarning as $totalEarnings)
                    {
                    "earning": "{{$totalEarnings->name}}",
                    "amount": {{$totalEarnings->total_earnings}}
                    },
                @endforeach
                ];

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
